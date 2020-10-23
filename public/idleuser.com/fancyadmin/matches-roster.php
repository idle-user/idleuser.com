<?php require_once('/srv/http/src/session.php'); set_last_page(); requires_admin(); ?>
<?php
    $is_success = false;
    $alert_message = false;
    $update_attempt = false;
    $new_attempt = false;
    $superstar_info = false;
    $superstar_list = $db->all_superstars();
    $matches_base_data = $db->matches_base_data();
    usort($matches_base_data['matches_brand'], function($a, $b) {return strcmp($a['name'], $b['name']);});
    $brand_list = $matches_base_data['matches_brand'];

    if(isset($_GET['search']) && $_GET['search'] && empty($_POST)){
        $superstar_info = $db->superstar($_GET['search']);
        if($superstar_info){
            $is_success = true;
            $alert_message = "Superstar <b>$superstar_info[name]</b> info loaded.";
        } else {
            $is_success = false;
            $alert_message = "Unable to find Superstar.";
        }
    } elseif(isset($_POST['superstar_id']) && $_POST['superstar_id']!=0){
        $superstar_info = $db->superstar($_POST['superstar_id']);
        if(!$superstar_info){
            $is_success = false;
            $alert_message = "Unable to find Superstar.";
        }
    }

    $new_attempt = !$superstar_info && isset($_POST['superstar_id']) && $_POST['superstar_id']==0;
    $update_attempt = $superstar_info && isset($_POST['superstar_id']) && $_POST['superstar_id']==$superstar_info['id'];
    if($new_attempt || $update_attempt){

        $superstar_id = $_POST['superstar_id']?:0;
        $name = $_POST['name']?:'';
        $brand_id = $_POST['brand_id']?:0;
        $height = $_POST['height']?:'';
        $weight = $_POST['weight']?:'';
        $hometown = $_POST['hometown']?:'';
        $dob = $_POST['dob']?:'0000-00-00';
        $signature_move = $_POST['signature_move']?:''; 
        $page_url = $_POST['page_url']?:'';
        $image_url = $_POST['image_url']?:'';
        $bio = $_POST['bio']?:'';
        $last_updated = $_POST['last_updated']?:'';

        if(isset($_POST['name']) && !empty($_POST['name'])){
            if($new_attempt){
                $superstar_id = $db->add_superstar($name, $brand_id, $height, $weight, $hometown, $dob, $signature_move, $page_url, $image_url, $bio);
                $is_success = $superstar_id?true:false;
                if(!$is_success){
                    $alert_message = "Failed to add the Superstar. Please contact admin.";
                }
            } elseif($update_attempt){
                if($last_updated == $superstar_info['last_updated']){
                    $is_success = $db->update_superstar($superstar_id, $name, $brand_id, $height, $weight, $hometown, $dob, $signature_move, $page_url, $image_url, $bio);
                    if(!$is_success){
                        $alert_message = "Update failed. Please contact admin.";
                    }
                } else {
                    $is_success = false;
                    $alert_message = "Failed to update. Editing an older version of the Superstar.<br/>Latest info loaded.";
                }
            } else{
                $is_success = false;
            }

        } else {
            $alert_message = "Must contain a name.";
        }
    }

    if(!$alert_message){
        if($new_attempt){
            if($is_success){
                $alert_message = "Superstar <b>$superstar_info[name]</b> created.";
            } else {
                $alert_message = "Superstar creation failed.";
            }
        } elseif($update_attempt){
            if($is_success){
                $alert_message = "Superstar <b>$superstar_info[name]</b> updated.";
            } else {
                $alert_message = "Superstar <b>$superstar_info[name]</b> update failed.";
            }
        }
    }

    if($is_success && ($new_attempt || $update_attempt)){
        $superstar_list = $db->all_superstars();
        $superstar_info = $db->superstar($superstar_id);
    }
?>
<!doctype html>
<html lang="en">
<head>
    <?php
        $title = 'Admin - Matches Roster';
        include('includes/head.php');
    ?>
</head>
<body>

    <?php include('includes/nav.php') ?>

    <main role="main" class="container">

        <?php include('includes/banner.php') ?>

        <?php if($alert_message){ ?>
        <!-- Notice Message -->
        <div class="alert text-center p-3 <?php echo $is_success?'alert-success':'alert-danger' ?>" role="alert">
            <h5><?php echo $alert_message ?></h5>
        </div>
        <?php } ?>

        <!-- Superstar dropdown -->
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <form method="get">
                <label class="my-1 mr-2" for="selectSuperstarSelector">Superstar Selector (<?php echo count($superstar_list) ?>)</label>
                <select class="custom-select my-1 mr-sm-2" id="selectSuperstarSelector" id="superstar_selected" name="search">
                    <option value="0" selected>Select a Superstar ...</option>
                    <?php 
                        foreach($superstar_list as $superstar){
                            $is_searched = ($superstar_info && $superstar['id']==$superstar_info['id'])?'selected':'';
                            echo "<option value='$superstar[id]' $is_searched>$superstar[name]</option>";
                        }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary my-1">Search</button>
            </form>
        </div>

        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">Superstar Editor<?php if($superstar_info) echo  "<small class='float-right'>Last Updated: $superstar_info[last_updated]</small>"; ?></h6>
            <div class="media text-muted pt-3">

            <?php if($superstar_info){ ?>

                 <!-- Update Form -->
                <form class="container" method="post">
                    <input type="text" class="form-control d-none" name="superstar_id" value="<?php echo $superstar_info['id'] ?>">
                    <input type="text" class="form-control d-none" name="last_updated" value="<?php echo $superstar_info['last_updated'] ?>">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="inputName">Name</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputName" name="name"  placeholder="Superstar Name" value="<?php echo htmlspecialchars($superstar_info['name']) ?>" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="selectBrand">Brand</label>
                            <select class="custom-select my-1 mr-sm-2" id="selectBrand" name="brand_id" required>
                                <option value="0" selected>Select a Brand ...</option>
                                <?php 
                                    foreach($brand_list as $brand){
                                        $is_selected = ($brand['id']==$superstar_info['brand_id'])?'selected':'';
                                        echo "<option value='$brand[id]' $is_selected>$brand[name]</option>";
                                    }
                                ?>
                            </select>                              
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="inputPageUrl">Page URL</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputPageUrl" name="page_url"  placeholder="Page URL" value="<?php echo $superstar_info['page_url'] ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="inputImage">Image URL</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputImage" name="image_url"  placeholder="Image URL" value="<?php echo $superstar_info['image_url'] ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2 mb-3">
                            <label for="inputDob">DoB</label>
                            <input type="date" class="form-control my-1 mr-sm-2" id="inputDob" name="dob" value="<?php echo $superstar_info['dob'] ?>">
                        </div>
                        <div class="col-md-1 mb-3">
                            <label for="inputHeight">Height</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputHeight" name="height"  placeholder="Superstar Height" value="<?php echo $superstar_info['height'] ?>">
                        </div>
                        <div class="col-md-1 mb-3">
                            <label for="inputWeight">Weight</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputWeight" name="weight"  placeholder="Superstar Weight" value="<?php echo $superstar_info['weight'] ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="inputHometown">Hometown</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputHometown" name="hometown"  placeholder="Hometown" value="<?php echo $superstar_info['hometown'] ?>">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="inputSignatures">Signature Moves</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputSignatures" name="signature_move"  placeholder="Signature Moves" value="<?php echo $superstar_info['signature_move'] ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="inputBio">Bio</label>
                            <textarea rows="10" class="form-control my-1 mr-sm-2" id="inputBio" name="bio"  placeholder="Bio"><?php echo $superstar_info['bio'] ?></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary float-right" type="submit">Update Superstar</button>
                    <a type="button" class="btn btn-primary btn-sm mt-3 float-left" href="matches-roster.php">Create New</a>
                </form>

            <?php } else { ?>

                 <!-- Create Form -->

                 <form class="container" method="post">
                    <input type="text" class="form-control d-none" name="superstar_id" value="0">
                    <input type="text" class="form-control d-none" name="last_updated" value="0">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="inputName">Name</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputName" name="name"  placeholder="Superstar Name" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="selectBrand">Brand</label>
                            <select class="custom-select my-1 mr-sm-2" id="selectBrand" name="brand_id" required>
                                <option value="0" selected>Select a Brand ...</option>
                                <?php 
                                    foreach($brand_list as $brand){
                                        echo "<option value='$brand[id]'>$brand[name]</option>";
                                    }
                                ?>
                            </select>                              
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="inputPageUrl">Page URL</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputPageUrl" name="page_url"  placeholder="Page URL">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="inputImage">Image URL</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputImage" name="image_url"  placeholder="Image URL">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2 mb-3">
                            <label for="inputDob">DoB</label>
                            <input type="date" class="form-control my-1 mr-sm-2" id="inputDob" name="dob">
                        </div>
                        <div class="col-md-1 mb-3">
                            <label for="inputHeight">Height</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputHeight" name="height" placeholder="Superstar Height">
                        </div>
                        <div class="col-md-1 mb-3">
                            <label for="inputWeight">Weight</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputWeight" name="weight"  placeholder="Superstar Weight">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="inputHometown">Hometown</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputHometown" name="hometown"  placeholder="Hometown">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="inputSignatures">Signature Moves</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputSignatures" name="signature_move"  placeholder="Signature Moves">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="inputBio">Bio</label>
                            <textarea rows="10" class="form-control my-1 mr-sm-2" id="inputBio" name="bio"  placeholder="Bio"></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary float-right" type="submit">Create Superstar</button>
                </form>
            <?php } ?>

            </div>

        </div>



    </main>
	
    <?php include('../includes/footer.php'); ?>

</body>
</html>