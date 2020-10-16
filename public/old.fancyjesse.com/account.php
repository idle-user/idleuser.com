<?php
	require_once('/srv/http/src/session.php');
	$user_id = $_SESSION['user_id'];
	$username = $_SESSION['username'];
	$temp_pw = '';
	$project = '';
	if(!$user_id && isset($_GET['user_id']) && !empty($_GET['user_id'])){
		$user_id = htmlspecialchars($_GET['user_id']);
	}
	if(!$username && isset($_GET['username']) && !empty($_GET['username'])){
		$username = htmlspecialchars($_GET['username']);
	}
	if(isset($_GET['temp_pw']) && !empty($_GET['temp_pw'])){
		$temp_pw = htmlspecialchars($_GET['temp_pw']);
	} else {
		// redirect_back();
	}
	if(isset($_GET['project']) && !empty($_GET['project'])){
		$project = htmlspecialchars($_GET['project']);
	}
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Account</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:image" content="/favicon256.png" />
	<meta property="og:title" content="Account" />
	<meta property="og:description" content="Your Account" />
	<link rel="stylesheet" href="assets/css/main.css" />
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" size="32x32" />
</head>
<body>
	<div class="wrapper style1 first container">
		<h2>Account Password Reset</h2>
		<div class="box">
			<div>
				<label for="username">Username</label>
				<input placeholder="username" type="text" id="username"<?php if($username){ echo 'value="'.$username.'" disabled="disabled"'; }?>/>
			</div>

			<div hidden>
				<input placeholder="Temporary Password" type="text" id="temp-pw"<?php if($temp_pw){ echo 'value="'.$temp_pw.'" disabled="disabled"'; }?>"/>
			</div>
			<div>
				<br/>
				<label for="new password">New Password</label>
				<input placeholder="new password" type="password" id="password" />
				<input placeholder="verify password" type="password" id="password-verify" style="display:none;" />
			</div>
			<div>
				<br/>
				<input type="button" id="submit-button" value="Submit" onclick="submit()">
			</div>
			<div>
				<strong id="notifier"></strong>
			</div>
		</div>
	</div>
	<!-- Scripts -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/jquery.scrolly.min.js"></script>
	<script src="assets/js/skel.min.js"></script>
	<script src="assets/js/skel-viewport.min.js"></script>
	<script src="assets/js/util.js"></script>
	<script src="assets/js/main.js"></script>
	<script type="text/javascript">
		function verify(){
			var username = document.getElementById('username').value.trim();
			var temp = document.getElementById('temp-pw').value.trim();
			var secret = document.getElementById('password').value.trim();
			if(username==''){
				notifier.innerHTML="Invalid username.";
				return false;
			}
			if(temp==''){
				notifier.innerHTML="Invalid temporary password.";
				return false;
			}
			if(secret==''){
				notifier.innerHTML="Invalid password.";
				return false;
			}
			return true;
		}
		function submit(){
			if(verify()){
				var username = document.getElementById('username').value.trim();
				var temp = document.getElementById('temp-pw').value.trim();
				var secret = document.getElementById('password').value.trim();
				var secret_verify = document.getElementById('password-verify').value.trim();
				if(secret_verify==''){
					notifier.innerHTML="Please re-enter your password to confirm.";
					document.getElementById('password-verify').style.display="inline";
					return false;
				}
				if(secret!=secret_verify){
					notifier.innerHTML="Passwords do not match.";
					return false;
				}
				$.post('/scripts/passwordreset.php', {'uid':<?php echo $user_id; ?>, 'username':username, 'temp':temp, 'secret':secret, 'secret_verify':secret_verify},
					function(data){
						console.log(data);
						if(data!=0){
							location = "/projects/<?php echo $project;?>";
						} else {
							notifier.innerHTML="Failed to reset password.";
						}
					}
				);
			} else {
				return false;
			}
		}
	</script>
</body>
</html>

