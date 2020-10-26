<?php require_once '/srv/http/src/session.php'; set_last_page();?>
<!DOCTYPE HTML>
<!--
	Eventually by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
<head>
	<title>Random Quote</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<meta property="og:image" content="/assets/images/favicon-512x512.png" />
	<meta property="og:title" content="Random Quote" />
	<meta property="og:description" content="Jesse's Project - Random Quote" />
	<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="assets/css/main.css" />
	<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
</head>
<body>
	<?php
	$data = $db->get_quote();
	if(!$data){
		$data = array(
			'quote' => 'Unable to retreive quote',
			'author' => 'Error',
		);
	}
	?>
	<!-- Header -->
	<header id="header">
		<h1>"<?php echo $data['quote']; ?>"</h1>
		<p>- <?php echo $data['author']; ?></p>
	</header>
	<!-- Footer -->
	<footer id="footer">
		<ul class="copyright">
			<li>© 2017 Jesus Andrade</li>
			<li>Design: <a href="https://html5up.net">HTML5 UP</a></li>
			<li>DNS: <a href="https://freedns.afraid.org/">Free DNS</a></li>
			<li>Page Last Updated: <?php echo date("Y.m.d H:i:s.", getlastmod()); ?></i>
		</ul>
	</footer>
	<!-- Scripts -->
	<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
	<script src="assets/js/main.js"></script>
</body>
</html>
