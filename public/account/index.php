<?php require_once getenv('APP_PATH') . '/src/session.php';
set_last_page();

if (!$_SESSION['loggedin']) {
    redirect(0, '/login');
    exit();
}

$response = maybe_process_form();


?>
<!doctype html>
<html lang="en">
<head>
    <?php
    $title = 'Account - Settings';
    include 'includes/head.php';
    ?>
</head>
<body>

<?php include 'includes/nav.php'; ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">My Account</h1>
    </div>

    <?php include getenv('APP_PATH') . '/public/includes/alert.php'; ?>

    <form method="post">
        <div class="form-group">
            <label for="usernameFormControlInput">Username</label>
            <div>
                <span class="small text-muted">You can change your username every 2 weeks.</span>
                <span class="small text-muted float-right">Last Updated: <?= $_SESSION['profile']['username_last_updated'] ?? 'Never' ?></span>
            </div>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="usernameFormControlInput" name="username"
                       value="<?= $_SESSION['profile']['username'] ?>">
                <div class="input-group-append">
                    <input class="btn btn-primary" type="submit" name="username-update" value="Update">
                </div>
            </div>
        </div>
    </form>

    <form method="post">
        <div class="form-group">
            <label for="emailFormControlInput">Email Address</label>
            <span class="small text-muted float-right pt-3">Last Updated: <?= $_SESSION['profile']['email_last_updated'] ?? 'Never' ?></span>
            <div class="input-group mb-3">
                <input type="email" class="form-control" id="emailFormControlInput" name="email"
                       value="<?= $_SESSION['profile']['email'] ?>">
                <div class="input-group-append">
                    <input class="btn btn-primary" type="submit" name="email-update" value="Update">
                </div>
            </div>
        </div>
    </form>


    <div>
        <a class="btn btn-primary mt-5" type="button" href="/change-password">Change Password</a>
    </div>
    <text class="small text-muted">Last Updated: <?= $_SESSION['profile']['secret_last_updated'] ?? 'Never' ?></text>


    <?php include 'includes/footer.php'; ?>
</main>
</body>
</html>
