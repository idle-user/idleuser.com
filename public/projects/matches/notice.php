<?php
$curPageName = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
if (false && $curPageName != 'royalrumble.php') {
    ?>
    <div class="container alert alert-primary fade show mt-3 button">
        <strong><a href="event">JOIN THE <?php echo date("Y"); ?> RUMBLE!</a></strong>
    </div>
    <?php
}
?>


<div class="container alert alert-secondary fade show mt-3 text-center" role="alert">
    <strong class="h4">The betting feature for Matches has been <u>discontinued</u>.</strong>
    <div class="col-12">
        <text class="small">
            After 7 seasons, we have made the decision to stop adding matches for betting.
            <br/>The Royal Rumble and certain roster updates will still be ongoing.
            <br/>Thank you to everyone who has participated over the years.
        </text>
    </div>
</div>


<!-- Royal Rumble open -->
<?php if (count($royalrumbles_open) && $curPageName != 'royalrumble.php') { ?>
    <div class="container alert alert-info fade show mt-3 text-center" role="alert">
        <strong class="h4">Royal Rumble Entries are Open!</strong>
        <div class="col-12">
            <text class="h5"><a href="/projects/matches/royalrumble">Enter the Royal Rumble!</a></text>
        </div>
    </div>
<?php } ?>

<!-- Bets open-->
<?php if (count($matches_bets_open) || count($matches_today)) { ?>
    <div class="container alert alert-info fade show mt-3 text-center" role="alert">
        <strong>Matches are available!</strong>
        <div class="col-12">
            <?php if (count($matches_bets_open)) { ?>
                <text class="col-6"><a href="/projects/matches/matches?type=bets_open">Matches (Bets Open)</a>
                </text><?php } ?>
            <?php if (count($matches_today)) { ?>
                <text class="col-6"><a href="/projects/matches/matches?type=today">Today's Matches</a></text> <?php } ?>
        </div>
    </div>
<?php } ?>


<!-- not logged-in notice -->
<?php
if (!$_SESSION['loggedin']) {
    ?>
    <div class="container alert alert-warning fade show mt-3 text-center" role="alert">
        <strong>You are not logged-in.<br/>Please <a href="/login?<?php echo get_direct_to(); ?>">register and login</a>
            to bet on matches and earn points.</strong>
    </div>
    <?php
} else {

    if ($curPageName != 'year-recap.php' && DATE('m') == 12 && false) // yearly recap only in December
    {
        ?>
        <div class="container alert alert-info fade show mt-3 text-center" role="alert">
            <strong class="h4">Your Yearly Recap is Now Available!</strong>
            <div class="col-12 p-1">
                <text class="h4 pt-1"><a href="/projects/matches/year-recap">Check out how you did in <?php echo date('Y'); ?></a></text>
            </div>
        </div>
        <?php
    }
}
?>

<div class="mb-n4"></div>

