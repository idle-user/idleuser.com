<?php require_once getenv('APP_PATH') . '/src/session.php';
set_last_page();
requires_admin(); ?>
<?php

$is_success = false;
$alert_message = false;
$post_attempt = !empty($_POST);

if ($post_attempt) {

    if (isset($_POST['royalrumble_id'])) {

        if (isset($_POST['description']) && isset($_POST['event_id']) && isset($_POST['entry_max']) && isset($_POST['entry_won'])) {
            $royalrumble_id = $_POST['royalrumble_id'];
            $description = $_POST['description'];
            $event_id = $_POST['event_id'];
            $entry_max = $_POST['entry_max'];
            $entry_won = empty($_POST['entry_won']) ? null : $_POST['entry_won'];

            if ($royalrumble_id) {
                $is_success = $db->update_royalrumble($royalrumble_id, $description, $event_id, $entry_max, $entry_won);
                if ($is_success) {
                    $alert_message = "Royal Rumble updated.";
                } else {
                    $alert_message = "Failed to update event Royal Rumble. Please contact admin.";
                }
            } else {
                $is_success = $db->add_royalrumble($description, $event_id, $entry_max, $entry_won);
                if ($is_success) {
                    $alert_message = "Royal Rumble added.";
                } else {
                    $alert_message = "Failed to add event Royal Rumble. Please contact admin.";
                }
            }
        } else {
            $alert_message = "Missing required fields. Try again.";
        }

    } else {
        $alert_message = "Unknown operation attempted. Please contact admin.";
    }

}

$royalrumbles = $db->all_royalrumbles();
$upcoming_events = $db->upcoming_events();
$all_events = $db->all_events();
?>
<!doctype html>
<html lang="en">
<head>
    <?php
    $title = 'Admin - Matches Royal Rumble';
    include 'includes/head.php';
    ?>
</head>
<body>

<?php include 'includes/nav.php'; ?>

<main role="main" class="container">

    <?php include 'includes/banner.php'; ?>

    <?php if ($alert_message) { ?>
        <!-- Notice Message -->
        <div class="alert text-center p-3 pt-2 <?php echo $is_success ? 'alert-success' : 'alert-danger' ?>"
             role="alert">
            <h5><?php echo $alert_message ?></h5>
        </div>
    <?php } ?>

    <form method="post" class="container">

    </form>

    <!-- Royal Rumble Events -->
    <?php $dtToday = date('Y-m-d 0:0:0'); ?>
    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-3">Royal Rumbles (<?php echo count($royalrumbles) ?>)</h6>
        <div class="container form-row mt-2 d-none d-lg-flex">
            <div class="col-md-4 mb-1">Event</div>
            <div class="col-md-4 mb-1">Description</div>
            <div class="col-md-1 mb-1">Max Entry #</div>
            <div class="col-md-1 mb-1">Entry # Won</div>
        </div>
        <?php foreach ($royalrumbles as $rr) { ?>
            <form method="post" class="container">
                <div class="form-row pb-1">
                    <input type="text" class="form-control d-none" name="royalrumble_id"
                           value="<?php echo $rr['id'] ?>">
                    <div class="col-md-4 mb-1">
                        <select class="custom-select my-1 mr-sm-2" id="selectMatchEvent" name="event_id">
                            <option value="0" selected>Select an Event ...</option>
                            <?php
                            foreach ($all_events as $event) {
                                $is_selected = ($event['id'] == $rr['event_id']) ? 'selected' : '';
                                $event_date_only = explode(' ', $event['date_time'])[0];
                                $classes = $event['date_time'] < $dtToday ? 'text-danger' : 'text-success';
                                echo "<option class='$classes' value='$event[id]' $is_selected>[$event_date_only] $event[name]</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-1">
                        <input type="text" class="form-control" name="description"
                               value="<?php echo $rr['description'] ?>">
                    </div>
                    <div class="col-md-1 mb-1">
                        <input type="number" class="form-control" name="entry_max"
                               value="<?php echo $rr['entry_max'] ?>">
                    </div>
                    <div class="col-md-1 mb-1">
                        <input type="number" class="form-control" name="entry_won"
                               value="<?php echo $rr['entry_won'] ?>">
                    </div>
                    <div class="col-md-2 mb-1">
                        <button type="submit" class="form-control btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        <?php } ?>
        <form method="post" class="container">
            <div class="form-row mt-2">
                <input type="text" class="form-control d-none" name="royalrumble_id" value="0">
                <div class="col-md-4 mb-1">
                    <select class="custom-select my-1 mr-sm-2" id="selectEvent" name="event_id">
                        <option value="0" selected>Select an Event ...</option>
                        <?php
                        foreach ($upcoming_events as $event) {
                            $event_date_only = explode(' ', $event['date_time'])[0];
                            echo "<option class='text-success' value='$event[id]'>[$event_date_only] $event[name]</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4 mb-1">
                    <input type="text" class="form-control" name="description" placeholder="Description" required>
                </div>
                <div class="col-md-1 mb-1">
                    <input type="number" class="form-control" name="entry_max" placeholder="Max Entry #" required>
                </div>
                <div class="col-md-1 mb-1">
                    <input type="number" class="form-control" name="entry_won" placeholder="Entry # Won">
                </div>
                <div class="col-md-2 mb-1">
                    <button type="submit" class="form-control btn btn-primary">Add</button>
                </div>
            </div>
        </form>
    </div>

</main>

<?php include getenv('APP_PATH') . '/public/includes/footer.php'; ?>

</body>
</html>
