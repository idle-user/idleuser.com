<!DOCTYPE html>
<html>
<head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title>Please Login</title>
</head>
<body>
	<div id="user-container">
	<strong id="notifier">Admin Login</strong>
	<br/>
	<input placeholder="username" type="text" id="username" />
	<input placeholder="password" type="password" id="password" />
	<br/>
	<input type="button" id="login-button" value="Login" onclick="login()" />
	</div>
	<script src="/assets/js/jquery.min.js"></script>
	<script type="text/javascript">
		function login(){
			var username = document.getElementById('username').value.trim();
			var secret = document.getElementById('password').value.trim();
			$.post('/scripts/login.php', {'username':username, 'secret':secret},
			function(data){
				if(data){
					data = JSON.parse(data);
					window.location.reload();
				} else {
					notifier.innerHTML="Invalid username or password.";
				}
			});
			return false;
		}
	 </script>
</body>
</html>
