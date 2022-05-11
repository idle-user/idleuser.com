<?php
  ob_start();
	require_once getenv('APP_PATH') . '/src/session.php';

  if(!$_SESSION['loggedin']){
		redirect(0, '/login');
		exit();
	}

  $response = maybe_process_form();
  $update_attempt = $response ? true : false;
  $update_successful = ($response['statusCode'] ?? 0) === 200;

?>
<!doctype html>
<html lang="en">
<head>
  <title>Account Change Password</title>
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
	<link rel="shortcut icon" href="/assets/images/favicon.ico">
  <link rel="manifest" href="/assets/images/site.webmanifest">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <link rel="stylesheet" href="/assets/css/form.css">

  <?php
    $meta = [
    "viewport" => "width=device-width, initial-scale=1, user-scalable=no",
    "keywords" => "contact, contact me, feedback, account, login, register, logout",
    "og:title" => "IdleUser - Change Password",
    "og:description" => "Change Password page for " . getenv('DOMAIN')
    ];
    echo page_meta($meta);
  ?>

</head>
<body>

  <?php include 'includes/nav.php'; ?>

  <div class="main">
    <form class="form-signin" method="post" oninput="inputNewPasswordVerify.setCustomValidity(inputNewPasswordVerify.value != inputNewPassword.value ? 'Passwords do not match.' : '')">
      <div class="text-center mb-4">
        <a href="/"><img class="mb-4" src="/assets/images/favicon-512x512.png" alt="" width="72" height="72"></a>

        <h1 class="h3 mb-3 font-weight-normal">Change Password</h1>
        <p>Change your account password. Use your IdleUser Account across the entire website, including <a href="/projects/matches/">Matches</a> and <a href="/projects/create-a-poll/">Create-a-Poll</a>.</p>
      </div>

      <div class="form-label-group">
        <input type="username" id="inputUsername" class="form-control" placeholder="Username" name="username" value="<?php echo $_SESSION['profile']['username'] ?>" disabled>
        <label for="inputUsername">Username</label>
      </div>

      <div class="form-label-group">
        <input type="password" id="inputOldPassword" class="form-control" placeholder="Current Password" name="old_secret" autofocus required>
        <label for="inputOldPassword">Current Password</label>
      </div>

      <div class="form-label-group">
        <input type="password" id="inputNewPassword" class="form-control" placeholder="New Password" name="secret" autofocus required>
        <label for="inputPassword">New Password</label>
      </div>

      <div class="form-label-group">
        <input type="password" id="inputNewPasswordVerify" class="form-control" placeholder="Verify New Password" name="secret_check" required>
        <label for="inputNewPasswordVerify">Verify New Password</label>
      </div>

      <?php if($update_successful){  redirect($delay=2, $url='/account'); ?>
        <div class="p-2 alert-success text-center alert">
          <text>Account Password Updated.<br/>Redirecting you back ...</text>
        </div>
      <?php } else { if($update_attempt){ include getenv('APP_PATH') . '/public/includes/alert.php'; } ?>

      <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-lg btn-primary float-right" type="submit" name="secret-update">Update</button>
        </div>
      </div>
       <?php } ?>

    <?php include 'includes/footer.php'; ?>

    </form>
  </div>
</body>
</html>

