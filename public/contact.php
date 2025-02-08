<?php
require_once getenv('APP_PATH') . '/src/session.php';

$is_success = false;
$alert_message = false;
$contact_attempt = false;
if (isset($_POST['email']) && !empty($_POST['email'])) {
    $contact_attempt = true;

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];
    $recaptcha_check = $_SESSION['loggedin'] || (isset($_POST['g-recaptcha-response']) && validate_recaptchaV2());

    if ($recaptcha_check) {
        $user_ip = get_ip();
        $is_success = $db->add_web_contact($fname, $lname, $email, $subject, $body, $user_ip, $_SESSION['profile']['id']);
        if ($is_success) {
            $alert_message = "Contact information sent!<br/>Will get back to you when possible.";
            email_admin_contact_alert($fname, $lname, $email, $subject, $body, $user_ip, $_SESSION['profile']['id']);
        } else {
            $alert_message = "Failed to send contact information<br/>Please try contacting me through Discord.";
        }
    } else {
        $alert_message = "reCAPTCHA check failed. Try again.";
    }

}

if ($contact_attempt) {
    if (!$alert_message) {
        $alert_message = "Something went wrong.<br/>Please try contacting me through Discord.";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/form.css">

    <?php
    $meta = [
        "viewport" => "width=device-width, initial-scale=1, user-scalable=no",
        "keywords" => "contact, contact me, feedback, account, login, register, logout",
        "og:title" => "IdleUser - Contact",
        "og:description" => "Contact page for " . getenv('DOMAIN')
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
            <h1 class="h3 mb-3 font-weight-normal">Contact Me</h1>
            <p></p>
        </div>

        <?php
        if ($contact_attempt) {
        if ($is_success) {
        ?>
        <div class="p-2 alert-success text-center alert">
            <?php } else { ?>
            <div class="p-2 alert-danger text-center alert">
                <?php } ?>
                <text><?php echo $alert_message ?></text>
            </div>
            <?php
            }
            ?>

            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="inputFName">First name</label>
                    <input type="fname" id="inputName" class="form-control" placeholder="First name" name="fname"
                           maxlength="45" autofocus required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="inputLName">Last name</label>
                    <input type="lname" id="inputLName" class="form-control" placeholder="Last name" name="lname"
                           maxlength="45" required>
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
                    <input type="text" id="inputSubject" class="form-control" placeholder="Subject" name="subject"
                           maxlength="120" required>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label for="inputBody">Message</label>
                    <textarea rows="10" id="inputBody" class="form-control" placeholder="Message" name="body"
                              required></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-8 mb-3">
                    <?php if (!$_SESSION['loggedin']) { ?>
                        <div class="g-recaptcha" data-callback="recaptchaCallback"
                             data-expired-callback="expiredRecaptchaCallback"
                             data-sitekey="<?= getenv('RECAPTCHA_V2_SITEKEY') ?>" id="recaptchaDiv"></div>
                    <?php } ?>
                </div>
                <div class="col-md-4 mb-3">
                    <button class="btn btn-lg btn-primary float-right" type="submit" id="recaptchaSubmitBtn">Send
                    </button>
                </div>
            </div>

            <?php include 'includes/footer.php'; ?>

    </form>
    <div>

</body>
</html>
