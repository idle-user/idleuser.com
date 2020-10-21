<?php 
  require_once('/srv/http/src/session.php');
  if($_SESSION['loggedin']){
    echo 'Already logged in. Redirecting back.';
    redirect(1);
    exit();
  }
  $register_attempt = false;
  $register_error = false;
	if(isset($_POST['username']) && isset($_POST['secret']) && isset($_POST['secret_verify'])){
    $register_attempt = true;

    if(!preg_match('/^[\w\-]+$/i', $_POST['username'])){
      $register_error = "Invalid username. Try again.";
    } elseif(strlen($_POST["secret"]) < 6){
      $register_error = "Password must contain at least 6 characters.";
    } elseif($_POST['secret'] == $_POST['secret_verify']){
        $res = $db->user_register($_POST['username'], $_POST['secret']);
    } else {
      $res = false;
      $register_error = "Passwords do not match.";
    }

		if($res){
			$_SESSION['user_id'] = $res['id'];
      $_SESSION['username'] = $res['username'];
      $_SESSION['access'] = $res['access'];
      $_SESSION['loggedin'] = true;
		}
    
    track("Register Attempt - username:$_POST[username]; result:$_SESSION[loggedin]");
    if($_SESSION['loggedin']){
      redirect(1);
    }

    if(!$res && !$register_error){
      $register_error = "Failed to register.<br/>Username may already be in use. Try again.";
    }

	}
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
  <link href="/assets/css/account.css" rel="stylesheet">

  <?php 
    $meta = [
    "viewport" => "width=device-width, initial-scale=1, user-scalable=no",
    "keywords" => "account, login, register, logout",
    "og:title" => "IdleUser - Register",
    "og:description" => "Account register page for idleuser.com"
    ];
    echo page_meta($meta);
  ?>

</head>
<body>
  <form class="form-signin" method="post" oninput="inputPasswordVerify.setCustomValidity(inputPasswordVerify.value != inputPassword.value ? 'Passwords do not match.' : '')">
    <div class="text-center mb-4">
      <a href="/"><img class="mb-4" src="/assets/images/favicon-512x512.png" alt="" width="72" height="72"></a>

      <?php if($register_attempt && $_SESSION['loggedin']){ ?>
        <h1 class="h3 mb-3 font-weight-normal">Registration Successful</h1>
        <p>Redirecting you ...</p>
        <input type="button" value="Return to previous page" onclick="javascript:history.go(-1)" />
      <?php } else { ?>

      <h1 class="h3 mb-3 font-weight-normal">Account Register</h1>
      <p>Register an account. Use your IdleUser Account across the entire website, including <a href="/projects/matches/">Matches</a> and <a href="/projects/create-a-poll/">Create-a-Poll</a>.</p>
    </div>

    <div class="form-label-group">
      <input type="username" id="inputUsername" class="form-control" placeholder="Username" name="username" maxlength="25" required autofocus>
      <label for="inputUsername">Username</label>
    </div>

    <div class="form-label-group">
      <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="secret" required>
      <label for="inputPassword">Password</label>
    </div>

    <div class="form-label-group">
      <input type="password" id="inputPasswordVerify" class="form-control" placeholder="Verify Password" name="secret_verify" required>
      <label for="inputPasswordVerify">Verify Password</label>
    </div>

    <?php if($register_attempt) { ?>
    <div class="p-2 alert-danger text-center alert">
      <text><?php echo $register_error ?></text>
    </div>
    <?php } ?>

    <div class="row">
      <div class="col-lg-12">
          <a href="/login.php" class="btn btn-sm text-primary font-weight-bold" type="button">Login instead</a>
          <button class="btn btn-lg btn-primary float-right" type="submit">Register</button>
      </div>
    </div>
  <?php } ?>

    <p class="mt-5 mb-3 text-muted text-center small">
      &copy; 2020 Jesus Andrade
      <br/><a href="https://freedns.afraid.org/">Free DNS</a> | <a href="/privacy-policy.php">Privacy Policy</a>
    </p>
  </form>
</body>
</html>
