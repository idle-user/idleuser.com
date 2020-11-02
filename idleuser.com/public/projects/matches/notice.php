<?php
	$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	if(false && $curPageName!='royalrumble.php'){
?>
	<div class="container alert alert-primary fade show mt-3 button">
		<strong><a href="event.php">JOIN THE 2020 RUMBLE!</a></strong>
	</div>
<?php
	}
?>


<?php if(false) { ?>
<div class="container alert alert-info fade show mt-3 alert-dismissible" role="alert">
	<strong>Are you registered to vote? Check your status and register in two minutes. <a href="https://vote.org/">Vote.org</a></strong>
</div>
<?php } ?>


<?php if(count($matches_bets_open)||count($matches_today)) { ?>
<div class="container alert alert-info fade show mt-3 alert-dismissible text-center" role="alert">
	<strong>Matches are available!</strong>
	<div class="col-12">
		<?php if(count($matches_bets_open)){ ?><text class="col-6"><a href="/projects/matches/matches.php?type=bets_open">Matches (Bets Open)</a></text><?php } ?>
		<?php if(count($matches_today)){ ?><text class="col-6"><a href="/projects/matches/matches.php?type=today">Today's Matches</a></text> <?php } ?>
	</div>
</div>
<?php } ?>



<?php
	if(!$_SESSION['loggedin']){
?>
	<div class="container alert alert-warning fade show mt-3" role="alert">
		<strong>You are not logged-in. Please <a href="/login.php?<?php echo get_direct_to();?>">register and login</a> to bet on matches.</strong>
	</div>
<?php
	} else { 
		$uemail = $db->user_email($_SESSION['user_id'])['email'];
		if(strpos($uemail, '@INVALID') || $uemail='') {
?>

	<div class="container alert alert-info fade show mt-3 alert-dismissible" role="alert" style="background-color: bisque;border-color: bisque;">
		<strong>You have not linked an email to your account - <a href="/projects/matches">Click Here</a> to update it.</strong>
	</div>
	
<?php 
		} 
	}
?>

<div class="mb-n4"> </div>
