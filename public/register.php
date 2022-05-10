<?php
  require_once getenv('APP_PATH') . '/src/session.php';

  if($_SESSION['loggedin']){
    echo 'Already logged in. Redirecting ..';
    redirect(1, '/account');
    exit();
  }

  $response = maybe_process_form();
  $register_attempt = $response ? true : false;
  $register_successful = $response['statusCode'] ?? 0 === 200;
  $successMessage = 'Successfully Logged in.';


?>
<!doctype html>
<html lang="en">
<head>
  <title>Account Register</title>
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
    "keywords" => "account, login, register, logout",
    "og:title" => "IdleUser - Register",
    "og:description" => "Account register page for " . getenv('DOMAIN')
    ];
    echo page_meta($meta);
  ?>

</head>
<body>

  <?php include 'includes/nav.php'; ?>

  <div class="main">
    <form class="form-signin" method="post" oninput="inputPasswordVerify.setCustomValidity(inputPasswordVerify.value != inputPassword.value ? 'Passwords do not match.' : '')">
      <div class="text-center mb-4">
        <a href="/"><img class="mb-4" src="/assets/images/favicon-512x512.png" alt="" width="72" height="72"></a>

        <?php if($register_successful){ ?>
          <h1 class="h3 mb-3 font-weight-normal">Registration Successful</h1>
          <p>Redirecting you ...</p>
          <input type="button" value="Return to previous page" onclick="javascript:history.go(-1)" />
        <?php } else { ?>

        <h1 class="h3 mb-3 font-weight-normal">Account Register</h1>
        <p>Register an account. Use your IdleUser Account across the entire website, including <a href="/projects/matches/">Matches</a> and <a href="/projects/create-a-poll/">Create-a-Poll</a>.</p>
      </div>

      <div class="form-label-group">
        <input type="username" id="inputUsername" class="form-control" placeholder="Username" name="username" maxlength="25" <?php if(isset($_POST['username'])){ echo "value='{$_POST['username']}'"; }?> required autofocus>
        <label for="inputUsername">Username</label>
      </div>

      <div class="form-label-group">
        <input type="email" id="inputEmail" class="form-control" placeholder="Username" name="email" <?php if(isset($_POST['email'])){ echo "value='{$_POST['email']}'"; }?>>
        <label for="inputEmail">Email (optional)</label>
      </div>

      <div class="form-label-group">
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="secret" required>
        <label for="inputPassword">Password</label>
      </div>

      <div class="form-label-group">
        <input type="password" id="inputPasswordVerify" class="form-control" placeholder="Verify Password" name="secret_verify" required>
        <label for="inputPasswordVerify">Verify Password</label>
      </div>

      <?php if($register_attempt){ include getenv('APP_PATH') . '/public/includes/alert.php'; }?>

      <div class="row">
        <div class="col-lg-12">
            <a href="/login" class="btn btn-sm text-primary font-weight-bold" type="button">Login instead</a>
            <button class="btn btn-lg btn-primary float-right" name="register" type="submit">Register</button>
        </div>
      </div>
      <?php } ?>

      <?php include 'includes/footer.php'; ?>

    </form>
  </div>

</body>
</html>
