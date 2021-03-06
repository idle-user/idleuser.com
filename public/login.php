<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';
  if($_SESSION['loggedin']){
    echo 'Already logged in. Redirecting back.';
    redirect(1);
    exit();
  }
  $login_attempt = false;
	if(isset($_POST['username']) && isset($_POST['secret'])  && !empty($_POST['username']) && !empty($_POST['secret'])){
    $login_attempt = true;
    $username = trim($_POST['username']);

    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
      email_login($username, $_POST['secret']);
    } else {
      username_login($username, $_POST['secret']);
    }

    if($_SESSION['loggedin']){
      redirect(1);
    }

	}
?>
<!doctype html>
<html lang="en">
<head>
  <title>Account Login</title>
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
    "og:title" => "IdleUser - Login",
    "og:description" => "Account login page for " . $configs['DOMAIN']
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

        <?php if($login_attempt && $_SESSION['loggedin']){ ?>
          <h1 class="h3 mb-3 font-weight-normal">Login Successful</h1>
          <p>Redirecting you ...</p>
          <input type="button" value="Return to previous page" onclick="javascript:history.go(-1)" />
        <?php }  else { ?>

        <h1 class="h3 mb-3 font-weight-normal">Account Login</h1>
        <p>Sign in. Use your IdleUser Account across the entire website, including <a href="/projects/matches/">Matches</a> and <a href="/projects/create-a-poll/">Create-a-Poll</a>.</p>
      </div>

      <div class="form-label-group">
        <input type="username" id="inputUsername" class="form-control" placeholder="Username/Email" name="username" <?php if(isset($_POST['username'])){ echo "value='{$_POST['username']}'"; }?>required autofocus>
        <label for="inputUsername">Username/Email</label>
      </div>

      <div class="form-label-group">
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="secret" required>
        <label for="inputPassword">Password</label>
      </div>

      <!--
      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Remember me
        </label>
      </div>
      -->

      <?php if($login_attempt) { ?>
      <div class="p-2 alert-danger text-center alert">
        <text>Invalid credentials. Please try again.</label>
      </div>
      <?php } ?>

      <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-lg btn-primary float-right" type="submit">Sign in</button>
            <a href="/forgot-password" class="btn btn-sm text-primary font-weight-bold" type="button">Forgot Password?</a>
            <a href="/forgot-username" class="btn btn-sm text-primary font-weight-bold" type="button">Forgot Username?</a>
            <a href="/register" class="btn btn-sm text-primary font-weight-bold" type="button">Create account</a>
        </div>
      </div>
    <?php } ?>

    <?php include 'includes/footer.php'; ?>

    </form>
  <div>

</body>
</html>
