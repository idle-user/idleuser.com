<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php'; set_last_page();

  if(!isset($_GET['reset_token'])){
		redirect(0, '/login');
		exit();
	}

	$update_attempt = false;
  $error_message = false;

	if(isset($_POST['reset_token']) && isset($_POST['new_secret']) && isset($_POST['new_secret_verify'])){
		$update_attempt = true;

		if(strlen($_POST["new_secret"]) < 6){
      $error_message = 'Password must contain at least 6 characters. Try again.';
		} elseif($_POST['new_secret'] != $_POST['new_secret_verify']){
      $error_message = 'Passwords do not match.';
		} else {
      $user = $db->reset_token_info($_POST['reset_token']);
      if($user){
        $res = $db->user_reset_password($user['id'], $_POST['reset_token'], $_POST['new_secret']);
        if(!$res){
          $error_message = 'Failed to update account. Try again.';
        }
      } else {
        $error_message = 'Invalid reset token. Please try again.';
      }

    }

    $success = $error_message ? 0 : 1;
    $attempted_by = $success ? $user['username'] : '';
		track("Reset Password Attempt - username:{$attempted_by}; result:{$success}");
  }

  if($update_attempt && !$error_message){
    redirect($delay=2, $url='/account/');
  }

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <title>Account</title>
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
	<link rel="shortcut icon" href="/assets/images/favicon.ico">
  <link rel="manifest" href="/assets/images/site.webmanifest">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <link href="/assets/css/form.css" rel="stylesheet">
</head>
<body>
  <form class="form-signin" method="post" oninput="inputNewPasswordVerify.setCustomValidity(inputNewPasswordVerify.value != inputNewPassword.value ? 'Passwords do not match.' : '')">
    <div class="text-center mb-4">
      <a href="/"><img class="mb-4" src="/assets/images/favicon-512x512.png" alt="" width="72" height="72"></a>

      <h1 class="h3 mb-3 font-weight-normal">Reset Password</h1>
      <p>Reset your account password. Use your IdleUser Account across the entire website, including <a href="/projects/matches/">Matches</a> and <a href="/projects/create-a-poll/">Create-a-Poll</a>.</p>
    </div>

    <div class="form-label-group">
      <input type="text" id="inputResetToken" class="form-control d-none" placeholder="Reset Token" name="reset_token"  value="<?php echo $_GET['reset_token'] ?>" required readonly>
      <label for="inputResetToken">Reset Token</label>
    </div>

    <div class="form-label-group">
      <input type="password" id="inputNewPassword" class="form-control" placeholder="New Password" name="new_secret" autofocus required>
      <label for="inputPassword">New Password</label>
    </div>

    <div class="form-label-group">
      <input type="password" id="inputNewPasswordVerify" class="form-control" placeholder="Verify New Password" name="new_secret_verify" required>
      <label for="inputNewPasswordVerify">Verify New Password</label>
    </div>

    <?php
      if($update_attempt) {
        if($error_message){
    ?>
          <div class="p-2 alert-danger text-center alert">
            <text><?php echo $error_message ?></text>
          </div>
      <?php } else {?>
          <div class="p-2 alert-success text-center alert">
            <text>Account Password Updated.<br/>Redirecting you back ...</text>
          </div>
    <?php
        }
      }
    ?>

    <div class="row">
      <div class="col-lg-12">
          <a href="/login" class="btn btn-sm text-primary font-weight-bold" type="button">Login instead</a>

          <button class="btn btn-lg btn-primary float-right" type="submit">Update</button>
      </div>
    </div>

    <p class="mt-5 mb-3 text-muted text-center small">
      &copy; 2017-2021 Jesus Andrade
      <br/><a href="https://freedns.afraid.org/">Free DNS</a> | <a href="/privacy-policy">Privacy Policy</a>
    </p>
  </form>
</body>
</html>

