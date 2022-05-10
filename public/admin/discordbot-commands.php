<?php require_once getenv('APP_PATH') . '/src/session.php'; set_last_page(); requires_admin(); ?>
<?php
    $is_success = false;
    $alert_message = false;
    $update_attempt = false;
    $new_attempt = false;
    $command_info = false;
    $command_list = $db->all_discord_commands();

    if(isset($_GET['search']) && $_GET['search'] && empty($_POST)){
        $command_info = $db->discord_command($_GET['search']);
        if($command_info){
            $is_success = true;
            $alert_message = "Command <b>$command_info[command]</b> info loaded.";
        } else {
            $is_success = false;
            $alert_message = "Unable to find Command.";
        }
    } elseif(isset($_POST['command_id']) && $_POST['command_id']!=0){
        $command_info = $db->discord_command($_POST['command_id']);
        if(!$command_info){
            $is_success = false;
            $alert_message = "Unable to find Command.";
        }
    }

    $new_attempt = !$command_info && isset($_POST['command_id']) && $_POST['command_id']==0;
    $update_attempt = $command_info && isset($_POST['command_id']) && $_POST['command_id']==$command_info['id'];
    if($new_attempt || $update_attempt){

        $command_id = $_POST['command_id']?:0;
        $command = $_POST['command']?:'';
        $response = $_POST['response']?:'';
        $description = $_POST['description']?:'';
        $last_updated = $_POST['last_updated']?:'';

        if(isset($_POST['command']) && !empty($_POST['command']) && isset($_POST['response']) && !empty($_POST['response'])){
            if($new_attempt){
                $command_id = $db->add_discord_command($command, $response, $description);
                $is_success = $command_id?true:false;
                if(!$is_success){
                    $alert_message = "Failed to add command. Please contact admin.";
                }
            } elseif($update_attempt){
                if($last_updated == $command_info['last_updated']){
                    $is_success = $db->update_discord_command($command_id, $command, $response, $description);
                    if(!$is_success){
                        $alert_message = "Update failed. Please contact admin.";
                    }
                } else {
                    $is_success = false;
                    $alert_message = "Failed to update. Editing an older version of the command.<br/>Latest info loaded.";
                }
            } else{
                $is_success = false;
            }

        } else {
            $alert_message = "Must contain a command and response.";
        }
    }

    if(!$alert_message){
        if($new_attempt){
            if($is_success){
                $alert_message = "Command <b>$command_info[command]</b> created.";
            } else {
                $alert_message = "Command creation failed.";
            }
        } elseif($update_attempt){
            if($is_success){
                $alert_message = "Command <b>$command_info[command]</b> updated.";
            } else {
                $alert_message = "Command <b>$command_info[command]</b> update failed.";
            }
        }
    }

    if($is_success && ($new_attempt || $update_attempt)){
        $command_list = $db->all_discord_commands();
        $command_info = $db->discord_command($command_id);
    }
?>
<!doctype html>
<html lang="en">
<head>
    <?php
        $title = 'Admin - Matches Roster';
        include 'includes/head.php';
    ?>
</head>
<body>

    <?php include 'includes/nav.php'; ?>

    <main role="main" class="container">

        <?php include 'includes/banner.php'; ?>

        <?php if($alert_message){ ?>
        <!-- Notice Message -->
        <div class="alert text-center p-3 <?php echo $is_success?'alert-success':'alert-danger' ?>" role="alert">
            <h5><?php echo $alert_message ?></h5>
        </div>
        <?php } ?>

        <!-- Command dropdown -->
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <form method="get">
                <label class="my-1 mr-2" for="selectCommandSelector">Command Selector (<?php echo count($command_list) ?>)</label>
                <select class="custom-select my-1 mr-sm-2" id="selectCommandSelector" id="command_selected" name="search">c
                    <option value="0" selected>Select a Command ...</option>
                    <?php
                        foreach($command_list as $command){
                            $is_searched = ($command_info && $command['id']==$command_info['id'])?'selected':'';
                            echo "<option value='$command[id]' $is_searched>$command[command]</option>";
                        }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary my-1">Search</button>
            </form>
        </div>

        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">Command Editor<?php if($command_info) echo  "<small class='float-right'>Last Updated: $command_info[last_updated]</small>"; ?></h6>
            <div class="media text-muted pt-3">

            <?php if($command_info){ ?>

                 <!-- Update Form -->
                <form class="container" method="post">
                    <input type="text" class="form-control d-none" name="command_id" value="<?php echo $command_info['id'] ?>">
                    <input type="text" class="form-control d-none" name="last_updated" value="<?php echo $command_info['last_updated'] ?>">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="inputCommand">Command</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputCommand" name="command"  placeholder="!command" value="<?php echo $command_info['command'] ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="inputResponse">Response</label>
                            <textarea rows="5" class="form-control my-1 mr-sm-2" id="inputResponse" name="response"  placeholder="Response"><?php echo $command_info['response'] ?></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="inputDescription">Description</label>
                            <textarea rows="3" class="form-control my-1 mr-sm-2" id="inputDescription" name="description"  placeholder="Description"><?php echo $command_info['description'] ?></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary float-right" type="submit">Update Command</button>
                    <a type="button" class="btn btn-primary btn-sm mt-3 float-left" href="discordbot-commands">Create New</a>
                </form>

            <?php } else { ?>

                 <!-- Create Form -->

                 <form class="container" method="post">
                    <input type="text" class="form-control d-none" name="command_id" value="0">
                    <input type="text" class="form-control d-none" name="last_updated" value="0">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="inputCommand">Command</label>
                            <input type="text" class="form-control my-1 mr-sm-2" id="inputCommand" name="command"  placeholder="!command" pattern="^!\S+$"  title="Must start with '!' and no spaces." required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="inputResponse">Response</label>
                            <textarea rows="5" class="form-control my-1 mr-sm-2" id="inputResponse" name="response"  placeholder="Response" required></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="inputDescription">Description</label>
                            <textarea rows="3" class="form-control my-1 mr-sm-2" id="inputDescription" name="description"  placeholder="Description"></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary float-right" type="submit">Create Command</button>
                </form>
            <?php } ?>

            </div>

        </div>



    </main>

    <?php include getenv('APP_PATH') . '/public/includes/footer.php'; ?>

</body>
</html>
