<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';

  $is_success = false;
  $alert_message = false;
  $contact_attempt = false;
	if(isset($_POST['email']) && !empty($_POST['email'])){
    $contact_attempt = true;

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];
    $recaptcha_check = $_SESSION['loggedin'] || (isset($_POST['g-recaptcha-response']) && validate_recaptchaV2());

    if($recaptcha_check){
      $is_success = $db->add_web_contact($fname, $lname, $email, $subject, $body, get_ip(), $_SESSION['user_id']);
      if($is_success){
        $alert_message = "Contact information sent!<br/>Will get back to you when possible.";
      } else {
        $alert_message = "Failed to send contact information<br/>Please try contacting me through Twitter or Discord.";
      }
    } else {
      $alert_message = "reCAPTCHA check failed. Try again.";
    }

  }

  if($contact_attempt){
    if(!$alert_message){
      $alert_message = "Something went wrong.<br/>Please try contacting me through Twitter or Discord.";
    }
    track("Contact Attempt - email:$_POST[email]; message:$alert_message");
  }

?>
<!doctype html>
<html lang="en">
<head>
  <title>Contact</title>
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
    "keywords" => "contact, contact me, feedback, account, login, register, logout",
    "og:title" => "IdleUser - Contact",
    "og:description" => "Contact page for IdleUser.com"
    ];
    echo page_meta($meta);
  ?>

</head>
<body>
  <form class="form-signin" method="post">

    <div class="text-center mb-4">
      <a href="/"><img class="mb-4" src="/assets/images/favicon-512x512.png" alt="" width="72" height="72"></a>
      <h1 class="h3 mb-3 font-weight-normal">Contact Me</h1>
      <p></p>
    </div>

    <?php
      if($contact_attempt) {
        if($is_success) {
    ?>
          <div class="p-2 alert-success text-center alert">
    <?php } else { ?>
          <div class="p-2 alert-danger text-center alert">
    <?php } ?>
        <text><?php echo $alert_message ?></label>
      </div>
    <?php
      }
    ?>

    <div class="form-row">
      <div class="col-md-6 mb-3">
        <label for="inputFName">First name</label>
        <input type="fname" id="inputName" class="form-control" placeholder="First name" name="fname" maxlength="45" autofocus required>
      </div>
      <div class="col-md-6 mb-3">
        <label for="inputLName">Last name</label>
        <input type="lname" id="inputLName" class="form-control" placeholder="Last name" name="lname" maxlength="45" required>
      </div>
    </div>

    <div class="form-row">
      <div class="col-md-12 mb-3">
        <label for="inputEmail">Email</label>
        <input type="email" id="inputEmail" class="form-control" placeholder="Email" name="email" required>
      </div>
    </div>

    <div class="form-row">
      <div class="col-md-12 mb-3">
        <label for="inputSubject">Subject</label>
        <input type="text" id="inputSubject" class="form-control" placeholder="Subject" name="subject" maxlength="120" required>
      </div>
    </div>

    <div class="form-row">
      <div class="col-md-12 mb-3">
        <label for="inputBody">Message</label>
        <textarea rows="10" id="inputBody" class="form-control" placeholder="Message" name="body" required></textarea>
      </div>
    </div>

  <div class="form-row">
    <div class="col-md-8 mb-3">
      <?php if(!$_SESSION['loggedin']) { ?>
        <div class="g-recaptcha" data-callback="recaptchaCallback" data-expired-callback="expiredRecaptchaCallback" data-sitekey="<?php echo get_recaptchav2_sitekey() ?>" id="recaptchaDiv"></div>
      <?php } ?>
      </div>
      <div class="col-md-4 mb-3">
        <button class="btn btn-lg btn-primary float-right" type="submit" id="recaptchaSubmitBtn">Send</button>
      </div>
    </div>
  </div>


    <p class="mt-5 mb-3 text-muted text-center small">
      &copy; 2020 Jesus Andrade
      <br/><a href="https://freedns.afraid.org/">Free DNS</a> | <a href="/privacy-policy.php">Privacy Policy</a>
    </p>

  </form>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script src='/assets/js/recaptcha.js'></script>
</body>
</html>
