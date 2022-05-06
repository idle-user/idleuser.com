<?php
	require_once getenv('APP_PATH') . '/src/session.php';

	$meta = [
		"keywords" => "FAQ, watchwrestling, WWE, AEW, NJPW, ROH, IMPACT, wrestling, bet, points, fjbot, chatroom, streams, watch online, wrestling discord, discord",
		"og:title" => "WatchWrestling - FAQ",
	];
	include 'header.php';
?>
<header class="main">
	<h1>FAQs</h1>
</header>
<hr class="major" />
<section>
	<header class="main">
		<h2>What is this place?</h2>
	</header>
	<p>Matches is a website that lets you wager <i>points</i> on current and upcoming matches. You win or lose points depending on the outcome of the match. So it's like a simplified Fantasy Football for wrestling</p>
	<p>There is also a Chatroom, Leaderboard, and profiles for all Superstars in the various wrestling promotions.</p>
	<p><i>More updates will be coming in the future.</i></p>
</section>
<hr class="major" />
<section>
	<header class="main">
		<h2>How soon are Matches added?</h2>
	</header>
	<p>Matches are added manually, so I try to add them near real time.</p>
	<p>Once a match is announced, I add it and make it available to bet on.</p>
	<p>Once the match bell rings or a decent amount of time to bet has been given, betting for the match is closed.</p>
</section>
<hr class="major" />
<section>
	<header class="main">
		<h2>Why aren't NXT Matches added?</h2>
	</header>
	<p>NXT is pretaped, so the outcomes of matches is known before the airing of the show.</p>
	<p>Only their PPVs (TakeOver) are added.</p>
</section>
<hr class="major" />
<section>
	<header class="main">
		<h2>How do I earn points?</h2>
	</header>
	<p>You automatically start with 100 points after registering.</p>
	<p>You automatically earn 20 points per day.</p>
	<p>You earn points after winning a bet on a Match. (keep an eye out for those point multipliers)</p>
</section>
<hr class="major" />
<section>
	<header class="main">
		<h2>Do I lose my points if the Match ended in No Contest?</h2>
	</header>
	<p>Nope.</p>
	<p>No contest outcomes are not that rare in wrestling. So if this occurs, your wagered points are refunded.</p>
</section>
<hr class="major" />
<section>
	<header class="main">
		<h2>What is a Point Multiplier?</h2>
	</header>
	<p>Some matches multiply your potential earnings if you win. They do not affect your loss amount.</p>
	<p>i.e: [WIN] 2x multiplier * 50 point bet = 100 points added to the Total Pot</p>
	<p>i.e: [LOSS] 2x multiplier * 50 point bet = 50 points lost</p>
</section>
<hr class="major" />
<section>
	<header class="main">
		<h2>What is a Pot?</h2>
	</header>
	<p>All bets are added to a Pot.</p>
	<p>The winners of the match split the Pot based on the percentage of their team's total bets.</p>
</section>
<hr class="major" />
<section>
	<header class="main">
		<h2>How do I update my Favorite Superstar?</h2>
	</header>
	<p>Navigate to the Superstar Menu and locate your Superstar. Then click on the FAVORITE button.</p>
</section>
<hr class="major" />
<section>
	<header class="main">
		<h2>What is the purpose of these points?</h2>
	</header>
	<p><s>Nothing. They're worthless.</s></p>
	<p>Besides your ranking on the Leaderboard, nothing can be done with them.</p>
	<p>Well, I'm actually creating a shop where you can redeem these points.</p>
	<p>Still trying to figure out what to provide in the shop, so if you have any ideas, please let me know through a feedback. I'd appreciate it.</p>
</section>
<hr class="major" />
<section>
	<header class="main">
		<h2>I found a mistake or have a recommendation. How do I get in contact?</h2>
	</header>
	<p>Great! Get in contact with me in the Chatroom or through a feedback.</p>
</section>
<hr class="major" />
<?php
	if(!$_SESSION['user_id']){
		echo "<p>I also see you're not registered. Please register and take a look around</p>";
	} else {
		echo '<p><b>Thanks for registering, '.$_SESSION['username'].'!</b></p>';
		echo "<p>Hope you stick around :)</p>";
	}
?>
<?php include 'navi-footer.php'; ?>
