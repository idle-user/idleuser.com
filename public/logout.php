<?php
ob_start();
require_once getenv('APP_PATH') . '/src/session.php';
logout();
redirect(1);
?>
<!doctype html>
<html lang="en">
<head>
    <title>Account Logout</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
    <link rel="shortcut icon" href="/assets/images/favicon.ico">
    <link rel="manifest" href="/assets/images/site.webmanifest">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="/assets/css/form.css" rel="stylesheet">

    <?php
    $meta = ["viewport" => "width=device-width, initial-scale=1, user-scalable=no", "keywords" => "account, login, register, logout", "og:title" => "IdleUser - Logout", "og:description" => "Account logout page for " . getenv('DOMAIN')];
    echo page_meta($meta);
    ?>

</head>
<body>
<div class="main">
    <form class="form-signin" method="post">
        <div class="text-center mb-4">
            <a href="/"><img class="mb-4" src="/assets/images/favicon-512x512.png" alt="" width="72" height="72"></a>
            <h1 class="h3 mb-3 font-weight-normal">Logout Successful</h1>
            <p>Redirecting you ...</p>
            <input type="button" value="Return to previous page" onclick="history.go(-1)">
            <?php include 'includes/footer.php'; ?>
    </form>
</div>
</body>
</html>
