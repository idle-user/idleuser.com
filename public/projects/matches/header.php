<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php'; set_last_page(); ?>
<?php
	$response = maybe_process_form();
	$matches_today = $db->todays_matches();
	$matches_bets_open = $db->open_matches();
?>
<!DOCTYPE HTML>
<html lang="en">
<!--
	Editorial by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<head>
	<title>WatchWrestling Matches</title>
	<script src="assets/js/jquery.min.js"></script>
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
	<link rel="shortcut icon" href="/assets/images/favicon.ico">
  	<link rel="manifest" href="/assets/images/site.webmanifest">
  	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="assets/css/main.css" />
	<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->

	<?php
    $meta = [
	  "viewport" => "width=device-width, initial-scale=1, user-scalable=no",
	  "keywords" => "WWE, AEW, NJPW, ROH, IMPACT, wrestling, bet, points, fjbot, chatroom, streams, watch online, wrestling discord, discord",
	  "og:title" => "WatchWrestling Matches",
	  "og:description" => "Wager points against others on upcoming wrestling matches. Rank up on the leaderboard and rate your favorite matches."
	];
    echo page_meta($meta);
  	?>

</head>
<body>
	<div id="wrapper">
		<div id="main">
			<div class="inner">
				<?php include 'notice.php'; ?>
				<header id="header" style="padding-top:3em;">
					<a href="#" class="logo"><strong>Matches</strong> by Jesse</a>
					<ul class="icons">
						<li><a href="https://discord.gg/U5wDzWP8yD target="_blank" class="icon fa-discord"><span class="label">Discord</span></a></li>
						<li><a href="https://twitter.com/an_idle_user" target="_blank" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="https://www.linkedin.com/in/andradejesus" target="_blank" class="icon fa-linkedin"><span class="label">LinkedIn</span></a></li>
						<li><a href="https://github.com/idle-user" target="_blank" class="icon fa-github"><span class="label">GitHub</span></a></li>
					</ul>
				</header>
				<section id="main-content">
