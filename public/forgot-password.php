<?php
	require_once getenv('APP_PATH') . '/src/session.php';

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
  <title>Account Forgot Password</title>
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
    "keywords" => "account, login, register, logout, forgot password",
    "og:title" => "IdleUser - Forgot Password",
    "og:description" => "Recover your " . getenv('DOMAIN') . " account"
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

        <h1 class="h3 mb-3 font-weight-normal">Forgot Password</h1>
        <?php if(!$submit_attempt || $error_message) { ?>
          <p>Recover your account.<br/>Please enter your username to begin the process.</p>
        <?php } ?>
      </div>

      <?php if(!$submit_attempt || $error_message) { ?>
        <div class="form-label-group">
          <input type="username" id="inputUsername" class="form-control" placeholder="Username" name="username" <?php if($_SESSION['loggedin']){ echo "value='{$_SESSION['username']}' readonly"; }?>>
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
          <div class="g-recaptcha" data-callback="recaptchaCallback" data-expired-callback="expiredRecaptchaCallback" data-sitekey="<?= getenv('RECAPTCHA_V2_SITEKEY') ?>" id="recaptchaDiv"></div>
        </div>
        <div class="col-md-4 mb-3">
          <button class="btn btn-lg btn-primary float-right" type="submit" id="recaptchaSubmitBtn">Send</button>
        </div>
        <a href="/forgot-username" class="btn btn-sm text-primary font-weight-bold" type="button">Forgot Username?</a>
        <a href="/register" class="btn btn-sm text-primary font-weight-bold" type="button">Register instead</a>
      </div>
    <?php } ?>

      <?php include 'includes/footer.php'; ?>

    </form>

  </div>

</body>
</html>

