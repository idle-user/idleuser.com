<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php'; set_last_page(); requires_admin(); ?>
<?php

    $is_success = false;
    $alert_message = false;
    $post_attempt = !empty($_POST);

    if($post_attempt){

        if(isset($_POST['event_id'])){

            if(isset($_POST['name']) && isset($_POST['start_time']) && isset($_POST['is_ppv'])){
                $event_id = $_POST['event_id'];
                $name = $_POST['name'];
                $datetime = $_POST['start_time'];
                $is_ppv = $_POST['is_ppv']=='PPV'?1:0;
                
                if($event_id){
                    $is_success = $db->update_event($event_id, $datetime, $name, $is_ppv);
                    if($is_success){
                        $alert_message = "Event <b>$name</b> updated.";
                    }  else {
                        $alert_message = "Failed to update event <b>$name</b>. Please contact admin.";
                    }
                } else {
                    $is_success = $db->add_event($datetime, $name, $is_ppv);
                    if($is_success){
                        $alert_message = "Event <b>$name</b> added.";
                    }  else {
                        $alert_message = "Failed to add event <b>$name</b>. Please contact admin.";
                    }
                }
            } else {
                $alert_message = "Missing required fields. Try again.";
            }

        } elseif(isset($_POST['brand_id'])) {

            if(isset($_POST['name'])){
                $brand_id = $_POST['brand_id'];
                $name = $_POST['name'];
                $image_url = $_POST['image_url'];
                
                if($brand_id){
                    $is_success = $db->update_brand($brand_id, $name, $image_url);
                    if($is_success){
                        $alert_message = "Brand <b>$name</b> updated.";
                    }  else {
                        $alert_message = "Failed to update Brand <b>$name</b>. Please contact admin.";
                    }
                } else {
                    $is_success = $db->add_brand($name, $image_url);
                    if($is_success){
                        $alert_message = "Brand <b>$name</b> added.";
                    }  else {
                        $alert_message = "Failed to add Brand <b>$name</b>. Please contact admin.";
                    }
                }
            } else {
                $alert_message = "Missing required fields. Try again.";
            }

        } elseif(isset($_POST['title_id'])) {

            if(isset($_POST['name'])){
                $title_id = $_POST['title_id'];
                $name = $_POST['name'];
                
                if($title_id){
                    $is_success = $db->update_title($title_id, $name);
                    if($is_success){
                        $alert_message = "Title <b>$name</b> updated.";
                    }  else {
                        $alert_message = "Failed to update Title <b>$name</b>. Please contact admin.";
                    }
                } else {
                    $is_success = $db->add_title($name);
                    if($is_success){
                        $alert_message = "Title <b>$name</b> added.";
                    }  else {
                        $alert_message = "Failed to add Title <b>$name</b>. Please contact admin.";
                    }
                }
            } else {
                $alert_message = "Missing required fields. Try again.";
            }

        } elseif(isset($_POST['match_type_id'])) {

            if(isset($_POST['name'])){
                $match_type_id = $_POST['match_type_id'];
                $name = $_POST['name'];
                
                if($match_type_id){
                    $is_success = $db->update_match_type($match_type_id, $name);
                    if($is_success){
                        $alert_message = "Match Type <b>$name</b> updated.";
                    }  else {
                        $alert_message = "Failed to update Match Type <b>$name</b>. Please contact admin.";
                    }
                } else {
                    $is_success = $db->add_match_type($name);
                    if($is_success){
                        $alert_message = "Match Type <b>$name</b> added.";
                    }  else {
                        $alert_message = "Failed to add Match Type <b>$name</b>. Please contact admin.";
                    }
                }
            } else {
                $alert_message = "Missing required fields. Try again.";
            }

        } else {
            $alert_message = "Unknown operation attempted. Please contact admin.";
        }

    }

    $upcoming_events = $db->upcoming_events();
    $matches_base_data = $db->matches_base_data();
    usort($matches_base_data['matches_brand'], function($a, $b) {return strcmp($a['name'], $b['name']);});
    usort($matches_base_data['matches_title'], function($a, $b) {return strcmp($a['name'], $b['name']);});
    usort($matches_base_data['matches_match_type'], function($a, $b) {return strcmp($a['name'], $b['name']);});
    $brand_list = $matches_base_data['matches_brand'];
    $title_list = $matches_base_data['matches_title'];
    $match_type_list = $matches_base_data['matches_match_type'];
?>
<!doctype html>
<html lang="en">
<head>
    <?php
        $title = 'Admin - Matches Metadata';
        include 'includes/head.php';
    ?>
</head>
<body>

    <?php include 'includes/nav.php'; ?>

    <main role="main" class="container">

        <?php include 'includes/banner.php'; ?>

        <?php if($alert_message){ ?>
        <!-- Notice Message -->
        <div class="alert text-center p-3 pt-2 <?php echo $is_success?'alert-success':'alert-danger' ?>" role="alert">
            <h5><?php echo $alert_message ?></h5>
        </div>
        <?php } ?>

        <!-- Upcoming Events -->
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-3">Upcoming Events (<?php echo count($upcoming_events) ?>)</h6>
            <?php foreach($upcoming_events as $ue){ ?>
                <form method="post" class="container">
                    <div class="form-row pb-2">
                        <input type="text" class="form-control d-none" name="event_id" value="<?php echo $ue['id'] ?>">
                        <div class="col-md-6 mb-1">
                            <input type="text" class="form-control" name="name" value="<?php echo $ue['name'] ?>">
                        </div>
                        <div class="col-md-3 mb-1">   
                            <input type="datetime-local" class="form-control" name="start_time" value="<?php echo str_replace(' ','T',$ue['date_time']) ?>">
                        </div>
                        <div class="col-md-2 mb-1">
                            <select name="is_ppv" class="form-control"><option <?php echo $ue['ppv']?'selected':'' ?>>PPV</option><option <?php echo !$ue['ppv']?'selected':'' ?>>Not PPV</option></select>
                        </div>
                        <div class="col-md-1 mb-1">
                            <button type="submit" class="form-control btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            <?php } ?>
            <form method="post" class="container">
                <div class="form-row mt-2">
                    <input type="text" class="form-control d-none" name="event_id" value="0">
                    <div class="col-md-6 mb-1">
                        <input type="text" class="form-control" name="name" placeholder="Event Name" required>
                    </div>
                    <div class="col-md-3 mb-1">                    
                        <input type="datetime-local" class="form-control" name="start_time" required>
                    </div>
                    <div class="col-md-2 mb-1">
                        <select name="is_ppv" class="form-control"><option selected>PPV</option><option>Not PPV</option></select>
                    </div>
                    <div class="col-md-1 mb-1">
                        <button type="submit" class="form-control btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Brands -->
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-3">Brands (<?php echo count($brand_list) ?>)</h6>
            <?php foreach($brand_list as $brand){ ?>
                <form method="post" class="container">
                    <div class="form-row pb-2">
                        <input type="text" class="form-control d-none" name="brand_id" value="<?php echo $brand['id'] ?>">
                        <div class="col-md-4 mb-1">
                            <input type="text" class="form-control" name="name" value="<?php echo $brand['name'] ?>">
                        </div>
                        <div class="col-md-7 mb-1">
                            <input type="text" class="form-control" name="image_url" value="<?php echo $brand['image_url'] ?>">
                        </div>
                        <div class="col-md-1 mb-1">
                            <button type="submit" class="form-control btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            <?php } ?>
            <form method="post" class="container">
                <div class="form-row mt-2">
                    <input type="text" class="form-control d-none" name="brand_id" value="0">
                    <div class="col-md-4 mb-1">
                        <input type="text" class="form-control" name="name" placeholder="Brand Name" required>
                    </div>
                    <div class="col-md-7 mb-1">
                        <input type="text" class="form-control" name="image_url" placeholder="Image URL" required>
                    </div>
                    <div class="col-md-1 mb-1">
                        <button type="submit" class="form-control btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Title -->
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-3">Title (<?php echo count($title_list) ?>)</h6>
            <?php foreach($title_list as $title){ ?>
                <form method="post" class="container">
                    <div class="form-row pb-2">
                        <input type="text" class="form-control d-none" name="title_id" value="<?php echo $title['id'] ?>">
                        <div class="col-md-11 mb-1">
                            <input type="text" class="form-control" name="name" value="<?php echo $title['name'] ?>">
                        </div>
                        <div class="col-md-1 mb-1">
                            <button type="submit" class="form-control btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            <?php } ?>
            <form method="post" class="container">
                <div class="form-row mt-2">
                    <input type="text" class="form-control d-none" name="title_id" value="0">
                    <div class="col-md-11 mb-1">
                        <input type="text" class="form-control" name="name" placeholder="Title Name" required>
                    </div>
                    <div class="col-md-1 mb-1">
                        <button type="submit" class="form-control btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Match Type -->
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-3">Match Type (<?php echo count($match_type_list) ?>)</h6>
            <?php foreach($match_type_list as $match_type){ ?>
                <form method="post" class="container">
                    <div class="form-row pb-2">
                        <input type="text" class="form-control d-none" name="match_type_id" value="<?php echo $match_type['id'] ?>">
                        <div class="col-md-11 mb-1">
                            <input type="text" class="form-control" name="name" value="<?php echo $match_type['name'] ?>">
                        </div>
                        <div class="col-md-1 mb-1">
                            <button type="submit" class="form-control btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            <?php } ?>
            <form method="post" class="container">
                <div class="form-row mt-2">
                    <input type="text" class="form-control d-none" name="match_type_id" value="0">
                    <div class="col-md-11 mb-1">
                        <input type="text" class="form-control" name="name" placeholder="Match Type Name" required>
                    </div>
                    <div class="col-md-1 mb-1">
                        <button type="submit" class="form-control btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>

    </main>
	
    <?php include '../includes/footer.php'; ?>

</body>
</html>
