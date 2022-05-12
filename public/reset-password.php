<?php
  ob_start();
	require_once getenv('APP_PATH') . '/src/session.php';

  if(!isset($_GET['reset_token'])){
		redirect(0, '/forgot-password');
		exit();
	}

  $response = maybe_process_form();
  $reset_attempt = $response ? true : false;
  $reset_successful = ($response['statusCode'] ?? 0) === 200;

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
  <link rel="stylesheet" href="/assets/css/form.css">
</head>
<body>

  <?php include 'includes/nav.php'; ?>

  <div class="main">
    <form class="form-signin" method="post" oninput="inputNewPasswordVerify.setCustomValidity(inputNewPasswordVerify.value != inputNewPassword.value ? 'Passwords do not match.' : '')">
      <div class="text-center mb-4">
        <a href="/"><img class="mb-4" src="/assets/images/favicon-512x512.png" alt="" width="72" height="72"></a>

        <h1 class="h3 mb-3 font-weight-normal">Reset Password</h1>
        <p>Reset your account password. Use your IdleUser Account across the entire website, including <a href="/projects/matches/">Matches</a> and <a href="/projects/create-a-poll/">Create-a-Poll</a>.</p>
      </div>

      <div class="form-label-group">
        <input type="text" id="inputResetToken" class="form-control d-none" placeholder="Reset Token" name="reset_token"  value="<?= $_GET['reset_token'] ?>" required readonly>
        <label for="inputResetToken">Reset Token</label>
      </div>

      <div class="form-label-group">
        <input type="password" id="inputNewPassword" class="form-control" placeholder="New Password" name="secret" minlength="6" autofocus required>
        <label for="inputPassword">New Password</label>
      </div>

      <div class="form-label-group">
        <input type="password" id="inputNewPasswordVerify" class="form-control" placeholder="Verify New Password" name="secret_check" minlength="6" required>
        <label for="inputNewPasswordVerify">Verify New Password</label>
      </div>

      <?php if($reset_successful){ redirect($delay=2, $url='/login'); ?>
        <div class="p-2 alert-success text-center alert">
          <text>Account Password Updated.<br/>Redirecting you to login ...</text>
        </div>
      <?php } else { if($reset_attempt){ include getenv('APP_PATH') . '/public/includes/alert.php'; } ?>

      <div class="row">
        <div class="col-lg-12">
            <a href="/login" class="btn btn-sm text-primary font-weight-bold" type="button">Login instead</a>
            <button class="btn btn-lg btn-primary float-right" type="submit" name="secret-reset">Update</button>
        </div>
      </div>
       <?php } ?>

      <?php include 'includes/footer.php'; ?>

    </form>
  </div>

</body>
</html>

