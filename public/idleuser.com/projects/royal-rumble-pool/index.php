<?php
$new_page = "/projects/matches/royalrumble.php";
require_once '/srv/http/src/session.php';
echo "Redirecting to <a href='$new_page'>Matches - RoyalRumble</a> ...";
redirect(3, $new_page);
exit();
// Outdated page below
$db->connect();
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Royal Rumble Pool</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:image" content="/assets/images/favicon-512x512.png" />
	<meta property="og:title" content="Royal Rumble Pool" />
	<meta property="og:description" content="Jesse's Project - Enter the Rumble!" />
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
	<section id="sidebar">
		<div class="inner">
			<nav>
				<ul>
					<li><a href="#intro">Welcome</a></li>
					<li><a href="#one">Enter Now</a></li>
					<li><a href="#two">Current Entries</a></li>
					<li><a href="#three">Previous Winners</a></li>
					<li><a href="#four">Get in touch</a></li>
				</ul>
			</nav>
		</div>
	</section>
	<div id="wrapper">
		<section id="intro" class="wrapper style1 fullscreen fade-up">
			<div class="inner">
				<h1>Royal Rumble Pool</h1>
				<p>Test your luck in this year's Royal Rumble<br/>
				Get an entry number and watch to see if your entrant is the winner!</p>
				<ul class="actions">
					<li><a href="#one" class="button scrolly">Enter Now</a></li>
				</ul>
			</div>
		</section>
		<section id="one" class="wrapper style2 fade-up">
			<div class="inner">
				<h2>Enter the Rumble!</h2>
				<p>Enter your username an a comment to be assigned an entry number</p>
				<div class="inner">
					<form id="entryForm" method="post" action="scripts/post_entry.php">
						<div class="field half first">
							<label for="entry_username">Username</label>
							<input type="text" name="entry_username" id="entry_username" maxlength="20" placeholder="required" <?php if($_SESSION['username']){ echo 'value="'.$_SESSION['username'].'" readonly="readonly"'; } ?> required />
						</div>
						<div class="field half">
							<label for="entry_comment">Comment</label>
							<input type="text" name="entry_comment" id="entry_comment" maxlength="50" placeholder="optional" />
						</div>
						<ul class="actions">
							<li><input type="submit" value="Get your Entry Number" /></li>
						</ul>
					</form>
					<h2 id="entryAck" hidden />
				</div>
			</div>
		</section>
		<section id="two" class="wrapper style3 fade-up">
			<div class="inner">
				<h2>Current Entries (Royal Rumble <?php $year=date('Y'); echo $year; ?>)</h2>
				<div class="table-wrapper">
					<table id="entryTable" class="alt">
						<thead>
							<tr>
								<th>Username</th>
								<th>Comment</th>
								<th>Entry Number</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$cnt = 0;
						$entries = $db->all_royalrumble_entries();
						foreach($entries as $entrant) {
							if($entrant['year']!=$year) continue; $cnt=$cnt+1;
						?>
							<tr>
								<td><?php echo $entrant['username']; ?></td>
								<td><?php echo $entrant['comment']; ?></td>
								<td><?php echo $entrant['number']; ?></td>
							</tr>
						<?php
						}
						if($cnt==0) {
						?>
							<tr>
								<td>Be the first to enter!</td>
								<td>Be the first to enter!</td>
								<td>Be the first to enter!</td>
							</tr>
						<?php
						}
						?>
						</tbody>
					</table>
				</div>
				<ul class="actions">
					<li><a href="#three" class="button scrolly">Previous Winners</a></li>
				</ul>
			</div>
		</section>
		<section id="three" class="wrapper style2 fade-up">
			<div class="inner">
				<h2>Previous Winners</h2>
				<div class="table-wrapper">
					<table id="winnerTable" class="alt">
						<thead>
							<tr>
								<th>Username</th>
								<th>Comment</th>
								<th>Entry Number</th>
								<th>Year</th>
							</tr>
						</thead>
						<tbody>
						<?php
						foreach($entries as $entrant) {
							if(!$entrant['winner']) continue;
						?>
							<tr>
								<td><?php echo $entrant['username']; ?></td>
								<td><?php echo $entrant['comment']; ?></td>
								<td><?php echo $entrant['number']; ?></td>
								<td><?php echo $entrant['year']; ?></td>
							</tr>
						<?php
						}
						?>
						</tbody>
					</table>
				</div>
				<ul class="actions">
					<li><a href="#four" class="button scrolly">Get in touch</a></li>
				</ul>
			</div>
		</section>
		<section id="four" class="wrapper style1 fade-up">
			<div class="inner">
				<h2>Get in touch</h2>
				<ul class="icons">
					<li><a href="https://twitter.com/an_idle_user" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a href="https://www.linkedin.com/in/andradejesus" class="icon fa-linkedin"><span class="label">LinkedIn</span></a></li>
					<li><a href="https://github.com/idle-user" class="icon fa-github"><span class="label">Github</span></a></li>
				</ul>
				<p>Feel free to contact me. I'll do my best to reply as soon as possible.</p>
				<div class="inner">
					<section>
						<form id="contactForm" method="post" action="/scripts/post_feedback.php">
							<input type="hidden" name="subject" id="subject" value="RoyalRumblePool">
							<div class="field half first">
								<label for="name">Name</label>
								<input type="text" name="name" id="name" placeholder="required" required />
							</div>
							<div class="field half">
								<label for="email">Email</label>
								<input type="email" name="email" id="email" placeholder="required" required />
							</div>
							<div class="field">
								<label for="message">Message</label>
								<textarea name="message" id="message" rows="5" placeholder="required" required></textarea>
							</div>
							<ul class="actions">
								<li><input type="submit" value="Send Message" /></li>
							</ul>
						</form>
						<h2 id="messageAck" hidden>Message Sent. Thanks!</h2>
					</section>
				</div>
			</div>
		</section>
	</div>
	<footer id="footer" class="wrapper style1-alt">
		<div class="inner">
			<ul class="menu">
				<li>© 2017 Jesus Andrade</li>
				<li>Design: <a href="https://html5up.net">HTML5 UP</a></li>
				<li>DNS: <a href="https://freedns.afraid.org/">Free DNS</a></li>
				<li>Page Last Updated: <?php echo date("Y.m.d H:i:s.", getlastmod()); ?></i>
			</ul>
		</div>
	</footer>
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/jquery.scrollex.min.js"></script>
	<script src="assets/js/jquery.scrolly.min.js"></script>
	<script src="assets/js/skel.min.js"></script>
	<script src="assets/js/util.js"></script>
	<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
	<script src="assets/js/main.js"></script>
	<script type="text/javascript">
		var ef = $('#entryForm');
		var audio = document.createElement('audio');
		audio.setAttribute('src', '../matches/audio/royalrumble_countdown.mp3');
		ef.submit(function (ev) {
			$.ajax({
				type: ef.attr('method'),
				url: ef.attr('action'),
				dataType: 'json',
				data: ef.serialize(),
				success: function (data) {
					if(data['success']){
						$('#entryForm :input').prop('readonly', true);
						$('input[type="submit"]').prop('disable', false);
						$('#entryForm').hide();
						var newRow =
							'<tr><td>' + $('#entry_username').val().trim() +
							'</td><td>' + $('#entry_comment').val().trim() +
							'</td><td>' + data['data'].substring(
								data['data'].lastIndexOf('#') + 1,
								data['data'].lastIndexOf('!')) +
							'</td></tr>';
						function countdown() {
							if (audio.readyState) {
								audio.play();
							}
							var count = 10;
							$('#entryAck').empty();
							$('#entryAck').append('<h1>' + count-- + '</h1>');
							var interval = window.setInterval(function () {
								$('#entryAck').empty();
								if(count == 0) {
									window.clearInterval(interval);
									$('#entryAck').append(data['data']);
									$('#entryTable').append(newRow);
								} else {
									$('#entryAck').append('<h1>' + count-- + '</h1>');
								}
							} , 1000);
						}
						countdown();
					} else {
						$('#entryAck').empty();
						$('#entryAck').append(data['data']);
					}
					$('#entryAck').show();
				}
			});
			ev.preventDefault();
		});
	</script>
	<script type="text/javascript">
		var cf = $('#contactForm');
		cf.submit(function (ev) {
			$.ajax({
				type: cf.attr('method'),
				url: cf.attr('action'),
				data: cf.serialize(),
				success: function (data) {
					$('#contactForm :input').prop('readonly', true);
					$('input[type="submit"]').prop('disable', false);
					$('#contactForm').hide();
					$('#messageAck').show();
				}
			});
			ev.preventDefault();
		});
	</script>
</body>
</html-->
