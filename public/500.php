<?php require_once getenv('APP_PATH') . '/src/session.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="500">
    <title>Error 500 :(</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
    <link rel="shortcut icon" href="/assets/images/favicon.ico">
    <link rel="manifest" href="/assets/images/site.webmanifest">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>

<?php include 'includes/nav.php'; ?>

<main role="main">

    <div class="jumbotron text-center">
        <div class="containe">
            <h1 class="display-3">500</h1>
            <p>Internal Server Error.</p>
            <p><img src="/assets/images/error-ditto.gif" class="w-25 p-5"></p>
            <small>It's okay, here's a dancing ditto.</small>
        </div>
    </div>

</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>
