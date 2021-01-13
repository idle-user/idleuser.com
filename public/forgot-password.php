<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';

	$submit_attempt = false;
  $error_message = false;

  $recaptcha_check = isset($_POST['g-recaptcha-response']) && validate_recaptchaV2();

	if(isset($_POST['username']) && $recaptcha_check){
    $submit_attempt = true;

    $user = $db->username_info($_POST['username']);
    if(!$user){
      $error_message = 'Unable to find user. Please try again.';
    } elseif(empty($user['email'])){
      $error_message = 'Unfortunately the account requested does not have an email linked.<br/><br/>If the account is linked to Discord or Chatango, use command !reset-password.';
    } else {
      $reset_token = $db->user_update_temp_secret($user['id']);
      $res = email_reset_password_token($user['email'], $user['username'], $reset_token);
      if(!$res){
        $error_message = 'Failed to send email. Please try again';
        $mail_error = error_get_last()['message'];
        track("Forgot Password Error - username:{$_POST['username']}; error:{$mail_error}");
      }
    }

		track("Forgot Password Attempt - username:{$_POST['username']}");
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

      <h1 class="h3 mb-3 font-weight-normal">Forgot Password</h1>
      <?php if(!$submit_attempt || $error_message) { ?>
        <p>Recover your account.<br/>Please enter your username to begin the process.</p>
      <?php } ?>
    </div>

    <?php if(!$submit_attempt || $error_message) { ?>
      <div class="form-label-group">
        <input type="username" id="inputUsername" class="form-control" placeholder="Username" name="username">
        <label for="inputUsername">Username</label>
      </div>
    <?php } ?>


    <?php
      if($submit_attempt) {
        if($error_message){
    ?>
          <div class="p-2 alert-danger text-center alert">
            <text><?php echo $error_message ?></text>
          </div>
      <?php } else {?>
          <div class="p-2 alert-success text-center alert">
            <p>An email was sent to the provided username's email address.<br/><br/>Please check your email and click on included link to reset your password.</p>
          </div>
    <?php
        }
      }
    ?>

  <?php if(!$submit_attempt || $error_message) { ?>
    <div class="form-row">
      <div class="col-md-8 mb-3">
        <div class="g-recaptcha" data-callback="recaptchaCallback" data-expired-callback="expiredRecaptchaCallback" data-sitekey="<?php echo get_recaptchav2_sitekey() ?>" id="recaptchaDiv"></div>
      </div>
      <div class="col-md-4 mb-3">
        <button class="btn btn-lg btn-primary float-right" type="submit" id="recaptchaSubmitBtn">Send</button>
      </div>
      <a href="/register" class="btn btn-sm text-primary font-weight-bold" type="button">Register instead</a>
    </div>
  <?php } ?>


    <p class="mt-5 mb-3 text-muted text-center small">
      &copy; 2017-2021 Jesus Andrade
      <br/><a href="https://freedns.afraid.org/">Free DNS</a> | <a href="/privacy-policy">Privacy Policy</a>
    </p>
  </form>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script src='/assets/js/recaptcha.js'></script>
</body>
</html>

