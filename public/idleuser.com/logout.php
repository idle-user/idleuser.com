<?php
	require_once '/srv/http/src/session.php';
	session_destroy();
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <link href="/assets/css/account.css" rel="stylesheet">

  <?php 
    $meta = [
    "viewport" => "width=device-width, initial-scale=1, user-scalable=no",
    "keywords" => "account, login, register, logout",
    "og:title" => "IdleUser - Logout",
    "og:description" => "Account logout page for IdleUser.com"
    ];
    echo page_meta($meta);
  ?>

</head>
<body>
  <form class="form-signin" method="post">
    <div class="text-center mb-4">
      <a href="/"><img class="mb-4" src="/assets/images/favicon-512x512.png" alt="" width="72" height="72"></a>
      <h1 class="h3 mb-3 font-weight-normal">Logout Successful</h1>
      <p>Redirecting you ...</p>
      <input type="button" value="Return to previous page" onclick="javascript:history.go(-1)" >
      <p class="mt-5 mb-3 text-muted text-center small">
      &copy; 2020 Jesus Andrade
      <br/><a href="https://freedns.afraid.org/">Free DNS</a> | <a href="/privacy-policy.php">Privacy Policy</a>
    </p>
  </form>
</body>
</html>
