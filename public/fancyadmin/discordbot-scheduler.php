<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php'; set_last_page(); requires_admin(); ?>
<?php

    $is_success = false;
    $alert_message = false;
    $post_attempt = !empty($_POST);

    if($post_attempt){

        if(isset($_POST['schedule_id'])){

            if(isset($_POST['name']) && isset($_POST['start_time']) && isset($_POST['discord_message'])){
                $schedule_id = $_POST['schedule_id'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $discord_message = $_POST['discord_message'];
                $twitter_message = $_POST['twitter_message'];
                $start_time = $_POST['start_time'];
                $is_active = $_POST['is_active']=='Active'?1:0;
                $sun_active = isset($_POST['sun_active'])?1:0;
                $mon_active = isset($_POST['mon_active'])?1:0;
                $tue_active = isset($_POST['tue_active'])?1:0;
                $wed_active = isset($_POST['wed_active'])?1:0;
                $thu_active = isset($_POST['thu_active'])?1:0;
                $fri_active = isset($_POST['fri_active'])?1:0;
                $sat_active = isset($_POST['sat_active'])?1:0;

                if($schedule_id){
                    $is_success = $db->updated_discord_schedule(
                        $schedule_id,
                        $name, $description,
                        $discord_message, $twitter_message,
                        $start_time,
                        $sun_active, $mon_active, $tue_active, $wed_active, $thu_active, $fri_active, $sat_active,
                        $is_active
                    );
                    if($is_success){
                        $alert_message = "Schedule <b>$name</b> updated.";
                    }  else {
                        $alert_message = "Failed to update schedule <b>$name</b>. Please contact admin.";
                    }
                } else {
                    $is_success = $db->add_discord_schedule(
                        $name, $description,
                        $name, $description,
                        $discord_message, $twitter_message,
                        $start_time,
                        $sun_active, $mon_active, $tue_active, $wed_active, $thu_active, $fri_active, $sat_active,
                        $is_active
                    );
                    if($is_success){
                        $alert_message = "Schedule <b>$name</b> added.";
                    }  else {
                        $alert_message = "Failed to add schedule <b>$name</b>. Please contact admin.";
                    }
                }
            } else {
                $alert_message = "Missing required fields. Try again.";
            }

        } else {
            $alert_message = "Unknown operation attempted. Please contact admin.";
        }

    }

    $schedule_list = $db->all_discord_schedules();

?>
<!doctype html>
<html lang="en">
<head>
    <?php
        $title = 'Admin - Discord Scheduler';
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

        <!-- Discord Schedules -->
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-3">Discord Schedules (<?php echo count($schedule_list) ?>)</h6>
            <?php foreach($schedule_list as $ds){ ?>
                <form method="post" class="container">
                    <div class="form-row pb-2">
                        <input type="text" class="form-control d-none" name="schedule_id" value="<?php echo $ds['id'] ?>">
                        <div class="col-md-3 mb-1 text-center">
                            <input type="text" class="form-control input-group-text" name="name" value="<?php echo $ds['name'] ?>">
                            <textarea class="form-control font-italic" name="description"><?php echo $ds['description'] ?></textarea>
                        </div>
                        <div class="col-md-4 mb-2 text-center">
                            <textarea rows="3" class="form-control" name="discord_message" placeholder="Discord Message"><?php echo $ds['message'] ?></textarea>
                            <textarea rows="3" class="form-control" name="twitter_message" placeholder="Twitter Message"><?php echo $ds['tweet'] ?></textarea>
                        </div>
                        <div class="form-group-sm col-md-2 mb-1">
                            <input type="time" class="form-control" name="start_time" value="<?php echo $ds['start_time'] ?>">
                            <div class="form-check-inline">
                                <input type="checkbox" name="sun_active" <?php echo $ds['sunday_flag']?'checked':'' ?>>
                                <label class="form-check-label ml-2">Sun</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="checkbox" name="mon_active" <?php echo $ds['monday_flag']?'checked':'' ?>>
                                <label class="form-check-label ml-2">Mon</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="checkbox" name="tue_active" <?php echo $ds['tuesday_flag']?'checked':'' ?>>
                                <label class="form-check-label ml-2">Tue</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="checkbox" name="wed_active" <?php echo $ds['wednesday_flag']?'checked':'' ?>>
                                <label class="form-check-label ml-2">Wed</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="checkbox" name="thu_active" <?php echo $ds['thursday_flag']?'checked':'' ?>>
                                <label class="form-check-label ml-2">Thu</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="checkbox" name="fri_active" <?php echo $ds['friday_flag']?'checked':'' ?>>
                                <label class="form-check-label ml-2">Fri</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="checkbox" name="sat_active" <?php echo $ds['saturday_flag']?'checked':'' ?>>
                                <label class="form-check-label ml-2">Sat</label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-1">
                            <select name="is_active" class="form-control"><option <?php echo $ds['active']?'selected':'' ?>>Active</option><option <?php echo !$ds['active']?'selected':'' ?>>Disabled</option></select>
                        </div>
                        <div class="col-md-1 mb-1">
                            <button type="submit" class="form-control btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            <?php } ?>
            <form method="post" class="container">
                    <div class="form-row pb-2">
                        <input type="text" class="form-control d-none" name="schedule_id" value="0">
                        <div class="col-md-3 mb-1 text-center">
                            <input type="text" class="form-control input-group-text" name="name" placeholder="Schedule Name" required>
                            <textarea class="form-control font-italic" name="description" placeholder="Description"></textarea>
                        </div>
                        <div class="col-md-4 mb-2 text-center">
                            <textarea rows="3" class="form-control" name="discord_message" placeholder="Discord Message" required></textarea>
                            <textarea rows="3" class="form-control" name="twitter_message" placeholder="Twitter Message"></textarea>
                        </div>
                        <div class="form-group-sm col-md-2 mb-1">
                            <input type="time" class="form-control" name="start_time" required>
                            <div class="form-check-inline">
                                <input type="checkbox" name="sun_active">
                                <label class="form-check-label ml-2">Sun</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="checkbox" name="mon_active">
                                <label class="form-check-label ml-2">Mon</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="checkbox" name="tue_active">
                                <label class="form-check-label ml-2">Tue</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="checkbox" name="wed_active">
                                <label class="form-check-label ml-2">Wed</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="checkbox" name="thu_active">
                                <label class="form-check-label ml-2">Thu</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="checkbox" name="fri_active">
                                <label class="form-check-label ml-2">Fri</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="checkbox" name="sat_active">
                                <label class="form-check-label ml-2">Sat</label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-1">
                            <select name="is_active" class="form-control"><option>Active</option><option>Disabled</option></select>
                        </div>
                        <div class="col-md-1 mb-1">
                            <button type="submit" class="form-control btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>
        </div>

    </main>

    <?php include'../includes/footer.php'; ?>

</body>
</html>
