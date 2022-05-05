<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';

	$submit_attempt = false;
  $error_message = false;

  $recaptcha_check = isset($_POST['g-recaptcha-response']) && validate_recaptchaV2();

	if(isset($_POST['email']) && $recaptcha_check){
    $submit_attempt = true;

    if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
      $error_message = "Invalid email address. Try again";
    } else {
        $user = $db->email_info($_POST['email']);
        if($user){
          $res = email_username($user['email'], $user['username']);
          if(!$res){
            $error_message = 'Failed to send email. Please try again';
            $mail_error = error_get_last()['message'];
            track("Forgot Username Error - error:{$mail_error}");
          }
        }
    }
    track("Forgot Username Attempt");
  }

?>
<!doctype html>
<html lang="en">
<head>
  <title>Account Forgot Username</title>
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
    "keywords" => "account, login, register, logout, forgot username",
    "og:title" => "IdleUser - Forgot Username",
    "og:description" => "Recover your " . getenv('DOMAIN') . " account"
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

        <h1 class="h3 mb-3 font-weight-normal">Forgot Username</h1>
        <?php if(!$submit_attempt || $error_message) { ?>
          <p>Recover your account.<br/>Please enter your email to begin the process.</p>
        <?php } ?>
      </div>

      <?php if(!$submit_attempt || $error_message) { ?>
        <div class="form-label-group">
          <input type="email" id="inpuEmail" class="form-control" placeholder="Email" name="email">
          <label for="inputEmail">Email</label>
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
              <p>If there is an account associated with that email address, you'll get an email with your username shorty.</p>
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

      <?php include 'includes/footer.php'; ?>

    </form>

  </div>

</body>
</html>

