<?php require_once('/srv/http/src/session.php'); ?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Chatroom</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:image" content="/favicon256.png" />
	<meta property="og:title" content="Chatroom" />
	<meta property="og:description" content="Jesse's Project - Chatroom" />
	<link rel="stylesheet" type="text/css" href="style.css" />
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" size="32x32" />
</head>
<body>
	<table cellspacing="0" cellpadding="10" class="main-table">
		<tr style="height:85%;">
			<td valign="top" rowspan="2" id="o-cont">
				<table id="message-table" class="message-table"></table>
			</td>
			<td width="150" valign="top" id="right-bar">
				<h2>Users Online</h2>
				<ul id="user-list">Coming Soon</ul>
			</td>
		</tr>
		<tr>
			<td id="info-cont">
				<div class="info"></div>
			</td>
		</tr>
		<tr style="height:10%;">
			<td colspan="2" style="position:relative">
				<div id="login-container">
					<strong id="notifier">Please register and login to chat.</strong>
					<br/>
					<input placeholder="username" type="text" id="username" />
					<input placeholder="password" type="password" id="password" />
					<input placeholder="verify password" type="password" id="password-verify" style="display:none;" />
					<br/>
					<input type="button" id="login-button" value="Login" onclick="login()" />
					<input type="button" id="register-button" value="Register" onclick="register()" />
				</div>
				<strong id="welcome-message"></strong>
				<textarea placeholder="Message..." maxlength="255" disabled="true" id="entry"></textarea>
			</td>
		</tr>
		<tr style="height:5%;">
			<td colspan="2">
				<div class="info" id="copyright">
					<ul>
						<li>Find Me On:</li>
						<li><a href="https://www.facebook.com/FancyJesse">Facebook</a></li>
						<li><a href="https://twitter.com/fancyjesse">Twitter</a></li>
						<li><a href="https://www.linkedin.com/in/andradejesus">LinkedIn</a></li>
						<li><a href="https://github.com/FancyJesse">Github</a></li>
					</ul>
					<ul>
						<li>© 2017 Jesus Andrade</li>
						<li>DNS: <a href="https://freedns.afraid.org/">Free DNS</a></li>
						<li>Page Last Updated: <?php echo date("Y.m.d H:i:s.", getlastmod()); ?></li>
					</ul>
				</div>
			</td>
		</tr>
	</table>
	<script src="/assets/js/jquery.min.js"></script>
	<script type="text/javascript">
		var user_id = <?php echo $_SESSION['user_id']; ?>;
		var last_message_time = 0;
		var messageTable = document.getElementById('message-table');
		var welcomeMessage = document.getElementById('welcome-message');
		var notifier = document.getElementById('notifier');
		updateChat();
		checkSession();
		setInterval(updateChat, 10000);
		function addNewMessage(username, message, time){
			var newRow = messageTable.insertRow(messageTable.rows.length).insertCell(0);
			var timeData = '<div class="message-time">['+time+']</div>'
			newRow.className = 'message-content';
			newRow.innerHTML = timeData + '<strong>' + username + ': </strong>' + message;
			messageTable.scrollTop = messageTable.scrollHeight;
		}
		function updateChat(){
			$.ajax({
				type: 'POST',
				url: 'update.php',
				dataType: 'json',
				data: {'last_message_time':last_message_time},
				success: function (data) {
					$.each(data, function(i, message){
						addNewMessage(message.username,message.message,message.time);
						last_message_time = message.time;
					});
				}
			});
		}
		function send_message(message){
			$.post('send.php', { 'user_id':user_id, 'message':message},
				function(data){
					//console.log(data);
				}
			);
		}
		$(function(){
			$("#entry").keyup(function (event) {
				if(event.which == 13) {
					event.preventDefault();
					var data = $(this).val().trim();
					$(this).val('');
					if(data==''){
						return false;
					}
					if(user_id!=0){
						send_message(data);
					}
					return false;
				}
			});
		});
		function verify(){
			var username = document.getElementById('username').value.trim();
			var secret = document.getElementById('password').value.trim();
			if(username==''){
				notifier.innerHTML="Invalid username.";
				return false;
			}
			if(secret==''){
				notifier.innerHTML="Invalid password.";
				return false;
			}
			return true;
		}
		function loggedIn(data){
			data = JSON.parse(data);
			user_id = data.user_id;
			welcomeMessage.innerHTML="Welcome, " + data.username + ".";
			$("#login-container").hide();
			$("#entry").attr("disabled", false);
		}
		function login(){
			if(verify()){
				var username = document.getElementById('username').value.trim();
				var secret = document.getElementById('password').value.trim();
				$.post('/scripts/login.php', {'username':username, 'secret':secret},
					function(data){
						if(data!=0){
							loggedIn(data);
						} else {
							notifier.innerHTML="Invalid username or password.";
							user_id = 0;
						}
					}
				);
			} else{
				return false;
			}
		}
		function register(){
			if(verify()){
				var username = document.getElementById('username').value.trim();
				var secret = document.getElementById('password').value.trim();
				var secret_verify = document.getElementById('password-verify').value.trim();
				if(secret_verify==''){
					notifier.innerHTML="Please re-enter your password to register.";
					document.getElementById('password-verify').style.display="inline";
					return false;
				}
				if(secret!=secret_verify){
					notifier.innerHTML="Passwords do not match.";
					return false;
				}
				$.post('/scripts/register.php', {'username':username, 'secret':secret, 'secret_verify':secret_verify},
					function(data){
						// console.log(data);
						if(data!=0){
							loggedIn(data);
						} else {
							notifier.innerHTML="Failed to register. Username might be taken.";
							user_id = 0;
						}
					}
				);
			} else{
				return false;
			}
		}
		function checkSession(){
			if(user_id){
				welcomeMessage.innerHTML="Welcome back, " + <?php echo "'".$_SESSION['username']."'"; ?> + ".";
				$("#login-container").hide();
				$("#entry").attr("disabled", false);
			}
		}
		alert("Polling set to 10 seconds to conserve resources. See fancyjesse.com/projects/matches for an enhanced version of a chatroom.");
	</script>
</body>
</html>
