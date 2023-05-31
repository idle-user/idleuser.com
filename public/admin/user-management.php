<?php require_once getenv('APP_PATH') . '/src/session.php';
set_last_page();
requires_admin(); ?>
<?php
$is_success = false;
$alert_message = false;
$update_attempt = false;
$user_info = false;

function compareByUserAccess($a, $b)
{
    if ($a['access'] == $b['access']) {
        return ($a['id'] < $b['id']) ? -1 : 1;
    }
    return ($a['access'] > $b['access']) ? -1 : 1;
}

// attempt to grab initial user list
$response = api_call('GET', 'users');
$is_success = $response['statusCode'] === 200;
if ($is_success) {
    $user_list = [];
    usort($response['data'], 'compareByUserAccess');
    foreach ($response['data'] as $user) {
        $user_list[$user['id']] = $user;
    };
} else {
    $alert_message = print_r($response['error'], 1);
}

// check if searching - use initial list
if ($is_success && isset($_GET['search']) && $_GET['search']) {
    $is_success = isset($user_list[$_GET['search']]);
    if ($is_success) {
        $user_info = $user_list[$_GET['search']];
        $alert_message = "User <b>$user_info[username] ($user_info[id])</b> info loaded.";
    } else {
        $alert_message = "Unable to find User.";
    }
}

$update_attempt = $user_info && isset($_POST['user_id']) && $_POST['user_id'] == $user_info['id'] && isset($_POST['access']);
if ($update_attempt) {
    // TODO: specify valid fields to update - only handling access atm
    $updated_user_info = $user_info;
    $updated_user_info['access'] = $_POST['access'];

    $response = api_call('PUT', 'users/' . $_POST['user_id'], json_encode($updated_user_info));
    $is_success = $response['statusCode'] === 200;
    if ($is_success) {
        $user_diff = array_diff($updated_user_info, $user_info);
        $user_list[$_POST['user_id']] = $updated_user_info;
        $user_info = $updated_user_info;
        $alert_message = "User <b>$user_info[username] ($user_info[id])</b> Updated.<br/>" . print_r($user_diff, 1);
    } else {
        $alert_message = "Failed to update user.</br>" . print_r($response, 1);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <?php
    $title = 'Admin - User Management';
    include 'includes/head.php';
    ?>
</head>
<body>

<?php include 'includes/nav.php'; ?>


<main role="main" class="container">

    <?php include 'includes/banner.php'; ?>

    <?php if ($alert_message) { ?>
        <!-- Notice Message -->
        <div class="alert text-center p-3 <?php echo $is_success ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <h5><?php echo $alert_message ?></h5>
        </div>
    <?php } ?>

    <!-- User dropdown -->
    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <form method="get">
            <label class="my-1 mr-2" for="selectUserSelector">User Selector (<?php echo count($user_list) ?>)</label>
            <select class="custom-select my-1 mr-sm-2" id="selectUserSelector" id="user_selected" name="search">c
                <option value="0" selected>Select a User ...</option>
                <?php
                foreach ($user_list as $user) {
                    print_r($user);
                    $is_searched = ($user_info && ($user['id'] == $user_info['id'])) ? 'selected' : '';
                    switch($user['access'])
                    {
                        case '0':
                            $accessColor = 'red';
                            break;
                        case '1':
                            $accessColor = 'unset';
                            break;
                        case '2':
                            $accessColor = 'limegreen';
                            break;
                        case '3':
                            $accessColor = 'goldenrod';
                            break;
                        default:
                            $accessColor = 'pink';
                    }
                    $accessColor = 'style="color:' . $accessColor . '"';
                    echo "<option value='$user[id]' $accessColor $is_searched>$user[username] ($user[id])</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn btn-primary my-1">Search</button>
        </form>
    </div>

    <?php if ($user_info) {
        $userStatus = '';
        switch ($user_info['access']) {
            case '1':
                $userStatus = 'active';
                break;
            case '2':
                $userStatus = 'moderator';
                break;
            case '3':
                $userStatus = 'administrator';
                break;
            default:
                $userStatus = 'banned';
                break;
        }
        if ($user_info['id'] === $_SESSION['profile']['id']) {
            $userStatus .= ' (self)';
        }
        ?>
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">User Info <small
                        class='float-right'><?php echo $userStatus ?></small></h6>
            <div class="media text-muted pt-3">
                <form class="container" method="post">
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <pre><?php echo print_r($user_info, 1) ?></pre>
                        </div>
                    </div>
                    <input name="user_id" value="<?php echo $user_info['id']; ?>" hidden>
                    <?php if ($user_info['id'] !== $_SESSION['profile']['id']) { ?>
                        <?php if ($user_info['access'] < 1) { ?>
                            <button class="btn btn-danger float-right" type="submit" name='access' value='1'>
                                Unban User
                            </button>
                        <?php } else { ?>
                            <?php if ($user_info['access'] == 1) { ?>
                                <button class="btn btn-danger float-left" type="submit" name='access' value='2'>
                                    Make Mod
                                </button>
                            <?php } else { ?>
                                <button class="btn btn-danger float-left" type="submit" name='access' value='1'>
                                    Remove Mod
                                </button>
                            <?php } ?>
                            <button class="btn btn-danger float-right" type="submit" name='access' value='0'>
                                Ban User
                            </button>
                        <?php } ?>

                    <?php } ?>
                </form>
            </div>
        </div>
    <?php } ?>


</main>

<?php include getenv('APP_PATH') . '/public/includes/footer.php'; ?>

</body>
</html>
