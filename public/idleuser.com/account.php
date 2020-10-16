<?php
	require_once('/srv/http/src/session.php');

	if(!$_SESSION['loggedin']){
		redirect(0, '/login.php');
		exit();
	  }
	
	$update_attempt = false;
  $error_message = false;
  

	if( (isset($_POST['temp_secret']) || isset($_POST['old_secret'])) && isset($_POST['new_secret']) && isset($_POST['new_secret_verify']) ){
		$update_attempt = true;

		if(strlen($_POST["new_secret"]) < 6){
      $error_message = "Password must contain at least 6 characters. Try again.";
    } elseif(isset($_POST['old_secret']) && !$db->user_login($_SESSION['username'], $_POST['old_secret'])){
      $error_message = "Incorrect Password. Try again.";
		} elseif($_POST['new_secret'] == $_POST['new_secret_verify']){

      if(isset($_POST['temp_secret'])){
        $res = $db->user_reset_password($_SESSION['user_id'], $_SESSION['username'], $_POST['temp_secret'], $_POST['new_secret']);
      } else {
			  $res = $db->user_change_password($_SESSION['user_id'], $_SESSION['username'], $_POST['old_secret'], $_POST['new_secret']);
      }

		} else {
			$res = false;
			$error_message = "Passwords do not match.";
		}

			if($res){
				$res = $db->user_login($_SESSION['username'], $_POST['new_secret']);
				if($res){
					$_SESSION['user_id'] = $res['id'];
					$_SESSION['username'] = $res['username'];
					$_SESSION['access'] = $res['access'];
					$_SESSION['loggedin'] = true;
				}
			}
		
		track("Update Password Attempt - username:$_SESSION[username]; result:$_SESSION[loggedin]");

		if(!$res && !$error_message){
			$error_message = "Failed to update account. Try again.";
    }
    
  }
  
  if($update_attempt && !$error_message){
    redirect(2);
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
  <link href="/assets/bootstrap-4.5.2-dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/css/account.css" rel="stylesheet">
</head>
<body>
  <form class="form-signin" method="post" oninput="inputNewPasswordVerify.setCustomValidity(inputNewPasswordVerify.value != inputNewPassword.value ? 'Passwords do not match.' : '')">
    <div class="text-center mb-4">
      <a href="/"><img class="mb-4" src="/assets/images/favicon-512x512.png" alt="" width="72" height="72"></a>

      <h1 class="h3 mb-3 font-weight-normal">Update Account</h1>
      <p>Update your account information. Use your IdleUser Account across the entire website, including <a href="/projects/matches/">Matches</a> and <a href="/projects/create-a-poll/">Create-a-Poll</a>.</p>
    </div>

    <div class="form-label-group">
      <input type="username" id="inputUsername" class="form-control" placeholder="Username" name="username" value="<?php echo $_SESSION['username'] ?>" disabled>
      <label for="inputUsername">Username</label>
    </div>

    <?php if(isset($_GET['temp_pw'])){?>
      <div class="form-label-group">
        <input type="text" id="inputTempSecret" class="form-control" placeholder="Temporary Password" name="temp_secret"  value="<?php echo $_GET['temp_pw'] ?>" required hidden>
        <label for="inputOldPassword">Temporary Password</label>
      </div>
    <?php } else { ?>
      <div class="form-label-group">
        <input type="password" id="inputOldPassword" class="form-control" placeholder="Current Password" name="old_secret" autofocus required>
        <label for="inputOldPassword">Current Password</label>
      </div>
    <?php } ?>

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
            <text>Updated Account.<br/>Redirecting you back ...</text>
          </div>
    <?php 
        }
      } 
    ?>

    <div class="row">
      <div class="col-lg-12">
          <button class="btn btn-lg btn-primary float-right" type="submit">Update</button>
      </div>
    </div>

    <p class="mt-5 mb-3 text-muted text-center small">
      &copy; 2020 Jesus Andrade
      <br/><a href="https://freedns.afraid.org/">Free DNS</a> | <a href="/privacy-policy.php">Privacy Policy</a>
    </p>
  </form>
</body>
</html>

