<?php
	$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	if(false && $curPageName!='royalrumble.php'){
?>
	<div class="container alert alert-primary fade show mt-3 button">
		<strong><a href="event">JOIN THE 2020 RUMBLE!</a></strong>
	</div>
<?php
	}
?>

<!-- Voting (manual) -->
<?php if(false) { ?>
<div class="container alert alert-info fade show mt-3 text-center" role="alert">
	<strong>Are you registered to vote? Check your status and register in two minutes. <a href="https://vote.org/">Vote.org</a></strong>
</div>
<?php } ?>


<!-- Royal Rumble open -->
<?php if(count($royalrumbles_open)) { ?>
<div class="container alert alert-info fade show mt-3 text-center" role="alert">
	<strong class="h4">Royal Rumble Entries are Open!</strong>
	<div class="col-12">
		<text class="h5"><a href="/projects/matches/royalrumble">Enter the Royal Rumble!</a></text>
	</div>
</div>
<?php } ?>

<!-- Bets open-->
<?php if(count($matches_bets_open)||count($matches_today)) { ?>
<div class="container alert alert-info fade show mt-3 text-center" role="alert">
	<strong>Matches are available!</strong>
	<div class="col-12">
		<?php if(count($matches_bets_open)){ ?><text class="col-6"><a href="/projects/matches/matches?type=bets_open">Matches (Bets Open)</a></text><?php } ?>
		<?php if(count($matches_today)){ ?><text class="col-6"><a href="/projects/matches/matches?type=today">Today's Matches</a></text> <?php } ?>
	</div>
</div>
<?php } ?>


<!-- not logged-in notice -->
<?php
	if(!$_SESSION['loggedin']){
?>
	<div class="container alert alert-warning fade show mt-3 text-center" role="alert">
		<strong>You are not logged-in.<br/>Please <a href="/login?<?php echo get_direct_to();?>">register and login</a> to bet on matches and earn points.</strong>
	</div>
<?php
	}
?>

<div class="mb-n4"> </div>

