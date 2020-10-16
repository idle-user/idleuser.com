<?php require_once('/srv/http/src/session.php'); ?>
<!DOCTYPE HTML>
<!--
	Miniport by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
<head>
	<title>FancyJesse</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:image" content="/favicon256.png" />
	<meta property="og:title" content="FancyJesse" />
	<meta property="og:description" content="Jesse's Website - About" />
	<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="assets/css/main.css" />
	<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" size="32x32" />
</head>
<body>
	<!-- Nav -->
	<nav id="nav">
		<ul class="container">
			<li><a href="#top">Top</a></li>
			<li><a href="#work">About Me</a></li>
			<li><a href="#portfolio">Projects</a></li>
			<li><a href="#contact">Contact</a></li>
		</ul>
	</nav>
	<!-- Home -->
	<div class="wrapper style1 first">
		<article class="container" id="top">
			<div class="row">
				<div class="4u 12u(mobile)">
					<span class="image fit"><img src="images/red.png" /></span>
				</div>
				<div class="8u 12u(mobile)">
					<header><h1>Hi. I'm <span title="Jesus Andrade"><strong>Jesse</strong>.</span></h1></header>
					<p>And this is my website, a place where I store my current work, projects, and additional information in the case that you would like to contact me.</p>
					<a href="#work" class="button big scrolly">Learn more about me</a>
				</div>
			</div>
		</article>
	</div>
	<!-- Work -->
	<div class="wrapper style2">
		<article id="work">
			<header>
				<h2>Here is some info about me.</h2>
				<p>My whole life has revolved around computers.<br/>I always had a knack for figuring out how things work from the ground up.<br/>It was only natural that my education and future will include the same.</p>
			</header>
			<div class="container">
				<div class="row">
					<div class="4u 12u(mobile)">
						<section class="box style1">
							<span class="icon featured fa-book" />
							<h3>Education</h3>
							<p><i>Bachelor of Science</i>, Computer Science<br/>2016</p>
						</section>
					</div>
					<div class="4u 12u(mobile)">
						<section class="box style1">
							<span class="icon featured fa-desktop" />
							<h3>Skills</h3>
							<p>Java, Python, C, SQL,<br/>Javascript, PHP, HTML, CSS,<br/>Android Development<br/></p>
						</section>
					</div>
					<div class="4u 12u(mobile)">
						<section class="box style1">
							<span class="icon featured fa-briefcase" />
							<h3>Experience</h3>
							<p>Sr. Coordinator<br/>June 2018 – Current</p>
							<p>Information Analyst<br/>April 2015 – June 2018</p>
						</section>
					</div>
				</div>
			</div>
			<footer>
				<a href="#portfolio" class="button big scrolly">See some of my recent projects</a>
			</footer>
		</article>
	</div>
	<!-- Portfolio -->
	<div class="wrapper style3">
		<article id="portfolio">
			<header>
				<h2>Here are some projects.</h2>
			</header>
			<div class="container">
				<div class="row">
					<div class="4u 12u(mobile)">
						<article class="box style2">
							<h3><a href="#">This Website</a></h3>
							<p>After learning what it takes to host a website, I registered a domain and began hosting this website. User profiles are shared throughtout different projects.<br/>
								<i>(SQL, PHP, JS, HTML)</i>
							</p>
						</article>
					</div>
					<div class="4u 12u(mobile)">
						<article class="box style2">
							<h3><a href="projects/matches/">Matches</a></h3>
							<p>Users register and login to view upcomming wrestling matches, and compete against others in leaderboard rankings by wagering their daily points.<br/>
								<i>(SQL, PHP, JS, HTML)</i>
							</p>
						</article>
					</div>
					<div class="4u 12u(mobile)">
						<article class="box style2">
							<h3><a href="projects/fjbot/">FJBot</a></h3>
							<p>A chat bot used for Discord and Chatango. Moderates chatrooms and provides users a variety commands. Includes Matches functionality where users can bet, view, and rate matches. And other miscellaneous commands such as retrieving Tweets from Twitter accounts.<br/>
								<i>(Python, SQL)</i>
							</p>
						</article>
					</div>
				</div>
				<div class="row">
					<div class="4u 12u(mobile)">
						<article class="box style2">
							<h3><a href="projects/royal-rumble-pool/">Royal Rumble Pool</a></h3>
							<p>Assigns Royal Rumble entry numbers at random and displays data on a webpage. Entries and Winners are recorded and displayed in their respective tables.<br/>
								<i>(Python, SQL, PHP, JS, HTML)</i>
							</p>
						</article>
					</div>
					<div class="4u 12u(mobile)">
						<article class="box style2">
							<h3><a href="projects/motioncam/">MotionCam</a></h3>
							<p>Utilizes a Raspberry-Pi and Camera to analyze, capture, and store records of any motion.<br/>
								<i>(Python)</i>
							</p>
						</article>
					</div>
					<div class="4u 12u(mobile)">
						<article class="box style2">
							<h3><a href="projects/chatroom/">Chatroom</a></h3>
							<p>A persistant and logged chatroom. Only registered users are able to send messages.<br/>
								<i>(SQL, PHP, JS, HTML)</i>
							</p>
						</article>
					</div>
				</div>
				<div class="row">
					<div class="4u 12u(mobile)">
						<article class="box style2">
							<h3><a href="projects/led-vote/">LED Vote</a></h3>
							<p>Users register an account to vote for a color. When a vote is received, a corresponding LED blinks. Votes are recorded and a webpage displays the total vote counts.<br/>
								<i>(Python, SQL, PHP, JS, HTML)</i>
							</p>
						</article>
					</div>
					<div class="4u 12u(mobile)">
						<article class="box style2">
							<h3><a href="projects/coming-soon/">LED Messages</a></h3>
							<p>Users send messages through a webpage. When a message is received, the message is displayed through an LED strip. All messages are logged.<br/>
								<i>(Python, SQL, PHP, JS, HTML)</i>
							</p>
						</article>
					</div>
					<div class="4u 12u(mobile)">
						<article class="box style2">
							<h3><a href="projects/random-quote/">Random Quote</a></h3>
							<p>A simple webpage that displays a random quote each time the page is accessed.<br/>
								<i>(Python, SQL, PHP, HTML)</i>
							</p>
						</article>
					</div>
				</div>
			</div>
			<footer>
				<p>Do you have any project ideas you want to pass my way?</p>
				<a href="#contact" class="button big scrolly">Send me a message</a>
			</footer>
		</article>
	</div>
	<!-- Contact -->
	<div class="wrapper style4">
		<article id="contact" class="container 75%">
			<header>
				<h2>Contact me or leave some feedback.</h2>
				<p>I'll do my best to reply as soon as possible.</p>
			</header>
			<div>
				<div class="row">
					<div class="12u">
						<form id="contactForm" method="post" action="/scripts/post_feedback.php" >
							<div>
								<div class="row">
									<div class="6u 12u(mobile)">
										<input type="text" name="name" id="name" placeholder="Name" required />
									</div>
									<div class="6u 12u(mobile)">
										<input type="email" name="email" id="email" placeholder="Email" required />
									</div>
								</div>
								<div class="row">
									<div class="12u">
										<input type="text" name="subject" id="subject" placeholder="Subject" required />
									</div>
								</div>
								<div class="row">
									<div class="12u">
										<textarea name="message" id="message" placeholder="Message" required></textarea>
									</div>
								</div>
								<div class="row 200%">
									<div class="12u">
										<ul class="actions">
											<li><input type="submit" value="Send Message" /></li>
											<li><input type="reset" value="Clear Form" class="alt" /></li>
										</ul>
									</div>
								</div>
							</div>
						</form>
						<h2 id="submitAck" hidden>Message Sent. Thanks!</h2>
					</div>
				</div>
				<div class="row">
					<div class="12u">
						<hr/>
						<h3>Find me on ...</h3>
						<ul class="social">
							<li><a href="https://www.facebook.com/FancyJesse" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="https://twitter.com/fancyjesse" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="https://www.linkedin.com/in/andradejesus" class="icon fa-linkedin"><span class="label">LinkedIn</span></a></li>
							<li><a href="https://github.com/FancyJesse" class="icon fa-github"><span class="label">Github</span></a></li>
						<hr/>
						</ul>
					</div>
				</div>
			</div>
			<footer>
				<ul id="copyright">
					<li>© 2016 Jesus Andrade</li>
					<li>Design: <a href="https://html5up.net">HTML5 UP</a></li>
					<li>DNS: <a href="https://freedns.afraid.org/">Free DNS</a></li>
					<li>Page Last Updated: <?php echo date("Y.m.d H:i:s.", getlastmod()); ?> </li>
				</ul>
			</footer>
		</article>
	</div>
	<!-- Scripts -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/jquery.scrolly.min.js"></script>
	<script src="assets/js/skel.min.js"></script>
	<script src="assets/js/skel-viewport.min.js"></script>
	<script src="assets/js/util.js"></script>
	<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
	<script src="assets/js/main.js"></script>
	<script type="text/javascript">
		var form = $('#contactForm');
		form.submit(function (ev) {
			$.ajax({
				type: form.attr('method'),
				url: form.attr('action'),
				data: form.serialize(),
				success: function (data) {
					if(!data){
						return false;
					}
					$('#contactForm :input').prop('readonly', true);
					$('input[type="submit"]').prop('disable', false);
					$('input[type="reset"]').prop('disable', false);
					$('#contactForm').hide();
					$('#submitAck').show();
				}
			});
		ev.preventDefault();
		});
	</script>
</body>
</html>
