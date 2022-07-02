<?php
ob_start();
require_once getenv('APP_PATH') . '/src/session.php';

if ($_SESSION['loggedin']) {
    echo 'Already logged in. Redirecting ..';
    redirect(1, '/account');
    exit();
}

$response = maybe_process_form();
$login_attempt = $response ? true : false;
$login_successful = ($response['statusCode'] ?? 0) === 200;
$successMessage = 'Successfully Logged in.';

?>
<!doctype html>
<html lang="en">
<head>
    <title>Account Login</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
    <link rel="shortcut icon" href="/assets/images/favicon.ico">
    <link rel="manifest" href="/assets/images/site.webmanifest">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/form.css">

    <?php
    $meta = [
        "viewport" => "width=device-width, initial-scale=1, user-scalable=no",
        "keywords" => "account, login, register, logout",
        "og:title" => "IdleUser - Login",
        "og:description" => "Account login page for " . getenv('DOMAIN')
    ];
    echo page_meta($meta);
    ?>

</head>
<body>

<?php include 'includes/nav.php'; ?>

<div class="main">
    <form class="form-signin" method="post">
        <div class="text-center mb-4">
            <a href="/"><img class="mb-4" src="/assets/images/favicon-512x512.png" alt="" width="72" height="72"></a>

            <?php if ($login_successful){ ?>
                <h1 class="h3 mb-3 font-weight-normal">Login Successful</h1>
                <p>Redirecting you ...</p>
                <input type="button" value="Return to previous page" onclick="history.go(-1)"/>
            <?php redirect(1); } else { ?>

            <h1 class="h3 mb-3 font-weight-normal">Account Login</h1>
            <p>Sign in. Use your IdleUser Account across the entire website, including <a href="/projects/matches/">Matches</a>
                and <a href="/projects/create-a-poll/">Create-a-Poll</a>.</p>
        </div>

        <div class="form-label-group">
            <input type="username" id="inputUsername" class="form-control" placeholder="Username/Email" name="username"
                   <?php if (isset($_POST['username'])) {
                       echo "value='{$_POST['username']}'";
                   } ?>required autofocus>
            <label for="inputUsername">Username/Email</label>
        </div>

        <div class="form-label-group">
            <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="secret"
                   required>
            <label for="inputPassword">Password</label>
        </div>

        <!--
        <div class="checkbox mb-3">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        -->

        <?php if ($login_attempt) {
            include getenv('APP_PATH') . '/public/includes/alert.php';
        } ?>
        <div class="row">
            <div class="col-lg-12">
                <button class="btn btn-lg btn-primary float-right" name="login" type="submit">Sign in</button>
                <a href="/forgot-password" class="btn btn-sm text-primary font-weight-bold" type="button">Forgot
                    Password?</a>
                <a href="/forgot-username" class="btn btn-sm text-primary font-weight-bold" type="button">Forgot
                    Username?</a>
                <a href="/register" class="btn btn-sm text-primary font-weight-bold" type="button">Create account</a>
            </div>
        </div>
        <?php } ?>

        <?php include 'includes/footer.php'; ?>

    </form>
    <div>

</body>
</html>
