<?php require_once getenv('APP_PATH') . '/src/session.php'; set_last_page();?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Chatroom</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:image" content="/assets/images/favicon-512x512.png" />
	<meta property="og:title" content="Chatroom" />
	<meta property="og:description" content="Jesse's Project - Chatroom" />
	<link rel="stylesheet" type="text/css" href="style.css" />
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
	<link rel="shortcut icon" href="/assets/images/favicon.ico">
  	<link rel="manifest" href="/assets/images/site.webmanifest">
  	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

	<link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon.ico" size="32x32" />
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
					<input type="button" value="Login" onclick="location.href='/login?<?php echo get_direct_to();?>';"  />
					<input type="button" value="Register" onclick="location.href='/register?<?php echo get_direct_to();?>';" />
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
						<li><a href="https://twitter.com/an_idle_user">Twitter</a></li>
						<li><a href="https://www.linkedin.com/in/andradejesus">LinkedIn</a></li>
						<li><a href="https://github.com/idle-user">Github</a></li>
					</ul>
					<ul>
						<li>&copy; 2017-2021 Jesus Andrade</li>
						<li>Page Last Updated: <?php echo date("Y.m.d H:i:s.", getlastmod()); ?></li>
					</ul>
				</div>
			</td>
		</tr>
	</table>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	<script type="text/javascript">
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
			$.post('send.php', { 'message':message},
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
					if(<?php echo $_SESSION['profile']['id'] ?>!=0){
						send_message(data);
					}
					return false;
				}
			});
		});
		function checkSession(){
			if(<?php echo $_SESSION['profile']['id'] ?>){
				welcomeMessage.innerHTML="Welcome back, " + <?php echo "'".$_SESSION['profile']['username']."'"; ?> + ".";
				$("#login-container").hide();
				$("#entry").attr("disabled", false);
			}
		}
		alert("Polling set to 10 seconds to conserve resources.");
	</script>
</body>
</html>
