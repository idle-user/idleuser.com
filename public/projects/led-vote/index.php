<?php require_once getenv('APP_PATH') . '/src/session.php'; set_last_page();?>
<!DOCTYPE HTML>
<!--
	Photon by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
<head>
	<title>LED-Vote</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:image" content="/assets/images/favicon-512x512.png" />
	<meta property="og:title" content="LED-Vote" />
	<meta property="og:description" content="Jesse's Project - LED-Vote" />
	<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="assets/css/main.css" />
	<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
	<link rel="shortcut icon" href="/assets/images/favicon.ico">
  	<link rel="manifest" href="/assets/images/site.webmanifest">
  </head>
<body>
	<!-- Header -->
	<section id="header">
		<div class="inner">
			<span class="icon major fa-cloud"></span>
			<!--h1>PAGE IS CURRENTLY UNDER CONSTRUCTION<hr/></h1-->
			<h1>Take Over My LED Lights!</h1>
			<p><strong>Register, Sign-In, and Click on a colored LED to Vote</strong><br/>
			Once an LED Vote is received, the LED light will flash on my end!</p>
			<ul class="actions">
				<li><a href="#one" class="button scrolly">Log-In / Register</a></li>
			</ul>
		</div>
	</section>
	<!-- One -->
	<section id="one" class="main style1">
		<div class="container">
			<div class="row 150%">
				<div class="6u 12u$(medium)">
					<header class="major">
						<h2>Log-In / Register</h2>
					</header>
					<p>Enter your username and password to login.<br/>
					Once registered and logged in, your voting data will be stored.</p>
				</div>
				<div class="6u$ 12u$(medium) important(medium)">
					<strong id="notifier"></strong>
					<div id="entry_div">
						<div class="row uniform 50%">
							<div class="12u$">
								<ul class="actions">
									<li><input type="submit" value="Login" class="special" onclick="location.href='/login?<?php echo get_direct_to();?>';"  /></li>
									<li><input type="submit" value="Register" class="special" onclick="location.href='/register?<?php echo get_direct_to();?>';" /></li>
								</ul>
							</div>
						</div>
					</div>
					<br/>
					<ul class="actions">
						<li><a href="#two" class="button scrolly">Vote Now</a></li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<!-- Two -->
	<section id="two" class="main style2">
		<div class="container">
			<h1>Click to Vote</h1>
			<div class="row 150%">
				<div class="6u 12u$(medium)">
					<ul id="led_select"></ul>
				</div>
				<div class="6u$ 12u$(medium)">
					<header class="major">
						<h2>Your Votes</h2>
					</header>
					<div class="table-wrapper">
						<table>
							<thead>
								<tr>
									<th>Username</th>
									<th>Votes</th>
								</tr>
							</thead>
							<tbody id="user_votes_table"></tbody>
							<tfoot>
								<tr>
									<td colspan="1"></td>
									<td id="user_votes_table_calc"></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Three -->
	<section id="three" class="main style1 special">
		<div class="container">
			<header class="major">
				<h2>Total Votes</h2>
			</header>
			<div class="table-wrapper">
				<table>
					<thead>
						<tr>
							<th>Color</th>
							<th>Votes</th>
						</tr>
					</thead>
					<tbody id="total_votes_table"></tbody>
					<tfoot>
						<tr>
							<td colspan="1"></td>
							<td id="total_votes_table_calc"></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<hr/>
		<div class="container">
			<header class="major">
				<h2>Top Users</h2>
			</header>
			<div class="table-wrapper">
				<table>
					<thead>
						<tr>
							<th>Username</th>
							<th>Total Votes</th>
							<th>Highest Voted</th>
						</tr>
					</thead>
					<tbody id="top_users_table"></tbody>
				</table>
			</div>
		</div>
	</section>
	<!-- Footer -->
	<section id="footer">
		<ul class="icons">
			<li><a href="https://twitter.com/an_idle_user" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
			<li><a href="https://www.linkedin.com/in/andradejesus" class="icon fa-linkedin"><span class="label">LinkedIn</span></a></li>
			<li><a href="https://github.com/idle-user" class="icon fa-github"><span class="label">Github</span></a></li>
		</ul>
		<ul class="copyright">
			<li>&copy; 2017-2021 Jesus Andrade</li>
			<li>Design: <a href="https://html5up.net">HTML5 UP</a></li>
			<li>Page Last Updated: <?php echo date("Y.m.d H:i:s.", getlastmod()); ?></i>
		</ul>
	</section>
	<!-- Scripts -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/jquery.scrolly.min.js"></script>
	<script src="assets/js/skel.min.js"></script>
	<script src="assets/js/util.js"></script>
	<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
	<script src="assets/js/main.js"></script>
	<script type="text/javascript">
		var user_id = <?php echo $_SESSION['user_id']?:'0'; ?>;
		var ledSelectList = document.getElementById('led_select');
		var userVotesTable = document.getElementById('user_votes_table');
		var totalVotesTable = document.getElementById('total_votes_table');
		var topUsersTable = document.getElementById('top_users_table');
		var notifier = document.getElementById('notifier');
		function loggedIn(data){
			console.log(data);
			data = JSON.parse(data);
			user_id = data.user_id;
			notifier.innerHTML="Welcome, " + data.username + ".";
			$("#entry_div").hide();
			updateTables();
		}
		function updateTables(){
			$.ajax({
				type: 'POST',
				url: 'update.php',
				dataType: 'json',
				data: {},
				success: function (data) {
					calc(data);
				}
			});
			function calc(data){
				var user_votes = 0;
				var led_colors = {};
				var user_list = [];
				for(var k in data[0])
					if(!(k=='username'||k=='user_id'))
						led_colors[k] = 0;
				for(var i=0; i<data.length; i++){
					user = {}
					user['username'] = data[i].username;
					user['total_votes'] = 0;
					user['highest_voted'] = '';
					for(var color in led_colors){
						var num = parseInt(data[i][color]);
						user[color] = num;
						user['total_votes'] += num;
						led_colors[color] += num;
					}
					user_list.push(user);
					if(data[i].user_id==user_id)
						user_votes = user;
				}
				user_list.sort(function(a,b){return b['total_votes'] - a['total_votes']});
				display(user_votes, led_colors, user_list.slice(0,4));
			}
			function display(user_votes, total_votes, top_users){
				ledSelectList.innerHTML = '';
				ledSelectList.className = 'major-icons';
				for(var color in total_votes){
					var newColor = document.createElement('li');
					var newColorData = document.createElement('span');
					newColorData.className = 'icon major fa-bolt';
					newColorData.style.color = '#C4C4C4';
					newColorData.style.backgroundColor = color;
					newColor.appendChild(newColorData);
					newColor.title = color;
					newColor.onclick = function(){
						this.childNodes[0].style.color = this.title;
						this.childNodes[0].style.backgroundColor="";
						vote(this.title);
					};
					ledSelectList.appendChild(newColor);
				}

				var userVotesHtml = '';
				var userVotesCnt = 0;
				if(user_votes==0){
					userVotesHtml = 'Please login to retrieve data.';
				}
				else{
					for(var color in total_votes){
						userVotesCnt += user_votes[color];
						userVotesHtml += '<tr><td>' + color + '</td><td>' + total_votes[color] + '</td></tr>';
					}
					document.getElementById('user_votes_table_calc').innerHTML = userVotesCnt;
				}
				userVotesTable.innerHTML = userVotesHtml;

				var totalVotesHtml = '';
				var totalVotesCnt = 0;
				for(var color in total_votes){
					totalVotesCnt += total_votes[color];
					totalVotesHtml += '<tr><td>' + color + '</td><td>' + total_votes[color] + '</td></tr>';
				}
				totalVotesTable.innerHTML = totalVotesHtml;
				document.getElementById('total_votes_table_calc').innerHTML = totalVotesCnt;

				var topUsersHtml = '';
				for(var i=0; i<top_users.length; i++){
					var highestVoted = '';
					for(var color in total_votes){
						if(highestVoted=='' || top_users[i][highestVoted]<top_users[i][color])
							highestVoted = color;
					}
					topUsersHtml += '<tr><td>' + top_users[i]['username'] + '</td><td>' + top_users[i]['total_votes'] + '</td><td>' + highestVoted + '</td></tr>';
				}
				topUsersTable.innerHTML = topUsersHtml;
			}
		}
		function vote(color){
			$.ajax({
				type: 'POST',
				url: 'vote.php',
				dataType: 'json',
				data: {color_id:color},
				success: function (data) {
					updateTables();
				}
			});
		}
	<?php	if($_SESSION['loggedin']){ ?>
		notifier.innerHTML="Welcome back, <?php echo $_SESSION['username']; ?>.";
		$("#entry_div").hide();
	<?php } ?>
		updateTables();
	</script>
</body>
</html>
