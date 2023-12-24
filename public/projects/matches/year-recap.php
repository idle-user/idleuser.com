<?php
require_once getenv('APP_PATH') . '/src/session.php';

requires_login();

$user_id = $_SESSION['profile']['id'];
$year_bets = $db->yearly_bets($user_id);
$year_ratings =  $db->yearly_ratings($user_id);

$recap_stats = [
    'year' => date('Y'),
    'username' => $_SESSION['profile']['username'],
    'total_bets' => 0,
    'bets_won' => 0,
    'bets_lost' => 0,
    'highest_bet_match_id' => 0,
    'highest_won_match_id' => 0,
    'highest_lost_match_id' => 0,
    'total_bet_points' => 0,
    'highest_pot_match_id' => 0,
    'highest_cut_match_id' => 0,
    'matches_rated' => count($year_ratings),
    'highest_rated_matches' => [],
    'lowest_rated_matches' => [],
];

foreach($year_bets as $match_id => $bet)
{
    if($bet['completed'] == 1 && $bet['pot_valid'] == 1)
    {
        $recap_stats['total_bets']++;
        $recap_stats['total_bet_points'] += $bet['bet_amount'];

        if(!$recap_stats['highest_bet_match_id'] || $bet['bet_amount'] > $year_bets[$recap_stats['highest_bet_match_id']]['bet_amount'])
        {
            $recap_stats['highest_bet_match_id'] = $match_id;
        }

        if(!$recap_stats['highest_pot_match_id'] || $bet['bet_amount'] > $year_bets[$recap_stats['highest_pot_match_id']]['total_pot'])
        {
            $recap_stats['highest_pot_match_id'] = $match_id;
        }

        if($bet['bet_won'] == 1)
        {
            $recap_stats['bets_won']++;

            if(!$recap_stats['highest_won_match_id'] || $bet['bet_amount'] > $year_bets[$recap_stats['highest_won_match_id']]['bet_amount'])
            {
                $recap_stats['highest_won_match_id'] = $match_id;
            }

            if(!$recap_stats['highest_cut_match_id'] || $bet['potential_cut_points'] > $year_bets[$recap_stats['highest_cut_match_id']]['potential_cut_points'])
            {
                $recap_stats['highest_cut_match_id'] = $match_id;
            }
        }
        else
        {
            $recap_stats['bets_lost']++;

            if(!$recap_stats['highest_lost_match_id'] || $bet['bet_amount'] > $year_bets[$recap_stats['highest_lost_match_id']]['bet_amount'])
            {
                $recap_stats['highest_lost_match_id'] = $match_id;
            }
        }
    }
}

foreach($year_ratings as $match_id => $match_rating)
{
    if(empty($recap_stats['highest_rated_matches']) && empty($recap_stats['lowest_rated_matches']))
    {
        $recap_stats['highest_rated_matches'] = [$match_id];
        $recap_stats['lowest_rated_matches'] = [$match_id];
        continue;
    }


    if($year_ratings[$recap_stats['highest_rated_matches'][0]]['rating'] < $match_rating['rating'])
    {
        $recap_stats['highest_rated_matches'] = [$match_id];
    }
    elseif($year_ratings[$recap_stats['highest_rated_matches'][0]]['rating'] == $match_rating['rating'])
    {
        $recap_stats['highest_rated_matches'][] = $match_id;
    }
    elseif($year_ratings[$recap_stats['lowest_rated_matches'][0]]['rating'] > $match_rating['rating'])
    {
        $recap_stats['lowest_rated_matches'] = [$match_id];
    }
    elseif($year_ratings[$recap_stats['lowest_rated_matches'][0]]['rating'] == $match_rating['rating'])
    {
        $recap_stats['lowest_rated_matches'][] = $match_id;
    }
}

$meta = [
    "keywords" => "{$recap_stats['username']}, watchwrestling, WWE, AEW, NJPW, ROH, IMPACT, wrestling, bet, points, fjbot, chatroom, streams, watch online, wrestling discord, discord",
    "og:title" => "WatchWrestling Year Recap {$recap_stats['year']} - {$recap_stats['username']}",
    "og:description" => "{$recap_stats['username']}'s WatchWrestling Year Recap {$recap_stats['year']}."
];
include 'header.php';
?>

<div id='recap-container' class="recap-container">

    <header class="main">
        <h1 <?php if (is_admin()) { ?>title='<?= var_dump($db->user_socials($user_id)); ?>'<?php } ?> >
            <?php echo $recap_stats['username'] . "'s " . $recap_stats['year'] . " Year Recap"; ?>
        </h1>
        <p>idleuser.com - Matches</p>
    </header>

    <div class="recap-block full-width-block">
        <h2>Bets Placed</h2>
        <h3><?= number_format($recap_stats['total_bets']); ?></h3>
    </div>

    <div class="recap-block">
        <h2>Bets Won</h2>
        <h3><?= number_format($recap_stats['bets_won']); ?></h3>
        <small>You have won <u><?= number_format($recap_stats['bets_won']/$recap_stats['total_bets'] * 100, 2); ?>%</u> of your bets.</small>
    </div>

    <div class="recap-block">
        <h2>Bets Lost</h2>
        <h3><?= number_format($recap_stats['bets_lost']); ?></h3>
        <small>You have lost <u><?= number_format($recap_stats['bets_lost']/$recap_stats['total_bets'] * 100, 2); ?>%</u> of your bets.</small>

    </div>

    <div class="recap-block full-width-block">
        <h2>Highest Bet</h2>
        <h3><?= number_format($year_bets[$recap_stats['highest_bet_match_id']]['bet_amount']); ?></h3>
        <a href="/projects/matches/matches?match_id=<?= $recap_stats['highest_bet_match_id']; ?>">
            <small><?= $year_bets[$recap_stats['highest_bet_match_id']]['event_name']; ?></small><br/>
            <small><?= $year_bets[$recap_stats['highest_bet_match_id']]['bet_on']; ?></small>
        </a>
    </div>

    <div class="recap-block">
        <h2>Highest Bet Won on a Single Bet</h2>
        <h3><?= number_format($year_bets[$recap_stats['highest_won_match_id']]['bet_amount']); ?></h3>
        <a href="/projects/matches/matches?match_id=<?= $recap_stats['highest_won_match_id']; ?>">
            <small><?= $year_bets[$recap_stats['highest_won_match_id']]['event_name']; ?></small><br/>
            <small><?= $year_bets[$recap_stats['highest_won_match_id']]['bet_on']; ?></small>
        </a>
    </div>

    <div class="recap-block">
        <h2>Highest Bet Lost on a Single Bet</h2>
        <h3><?= number_format($year_bets[$recap_stats['highest_lost_match_id']]['bet_amount']); ?></h3>
        <a href="/projects/matches/matches?match_id=<?= $recap_stats['highest_lost_match_id']; ?>">
            <small><?= $year_bets[$recap_stats['highest_lost_match_id']]['event_name']; ?></small><br/>
            <small><?= $year_bets[$recap_stats['highest_lost_match_id']]['bet_on']; ?></small>
        </a>
    </div>

    <div class="recap-block full-width-block">
        <h2>Total Points Place for Bet</h2>
        <h3><?= number_format($recap_stats['total_bet_points']); ?></h3>
    </div>

    <div class="recap-block full-width-block">
        <h2>Highest Match Pot Participated In</h2>
        <h3><?= number_format($year_bets[$recap_stats['highest_pot_match_id']]['total_pot']); ?></h3>
        <a href="/projects/matches/matches?match_id=<?= $recap_stats['highest_pot_match_id']; ?>">
            <small><?= $year_bets[$recap_stats['highest_pot_match_id']]['event_name']; ?></small><br/>
            <small><?= $year_bets[$recap_stats['highest_pot_match_id']]['bet_on']; ?></small><br/>
            <small>Placed Bet: <?= number_format($year_bets[$recap_stats['highest_pot_match_id']]['bet_amount']); ?></small>
        </a>
    </div>

    <div class="recap-block full-width-block">
        <h2>Most Points Won from a Single Bet</h2>
        <h3><?= number_format($year_bets[$recap_stats['highest_cut_match_id']]['potential_cut_points']); ?></h3>
        <a href="/projects/matches/matches?match_id=<?= $recap_stats['highest_cut_match_id']; ?>">
            <small><?= $year_bets[$recap_stats['highest_cut_match_id']]['event_name']; ?></small><br/>
            <small><?= $year_bets[$recap_stats['highest_cut_match_id']]['bet_on']; ?></small><br/>
            <small>Placed Bet: <?= number_format($year_bets[$recap_stats['highest_cut_match_id']]['bet_amount']); ?></small>
        </a>
    </div>

    <div class="recap-block full-width-block">
        <h2>Matches Rated</h2>
        <h3><?= number_format($recap_stats['matches_rated']); ?></h3>
    </div>

    <div class="recap-block">
        <h2>Highest Rated Matches</h2>
        <?php
        $stars = '';
        for ($star_cnt = 1; $star_cnt < 6; $star_cnt++) {
            $stars .= '<span class="fa fa-star' . ($year_ratings[$recap_stats['highest_rated_matches'][0]]['rating'] >= $star_cnt ? '' : '-o') . '"></span>';
        }
        ?>
        <h3><?= $year_ratings[$recap_stats['highest_rated_matches'][0]]['rating']; ?>/5<br/><?= $stars; ?></h3>
        <?php
        foreach($recap_stats['highest_rated_matches'] as $match_id)
            {
                ?>
           <div class="p-1">
                <a href="/projects/matches/matches?match_id=<?=$match_id; ?>">
                        <small><?= $year_ratings[$match_id]['event']; ?></small><br/>
                        <small><?= $year_ratings[$match_id]['contestants']; ?></small>
                </a>
           </div>
            <?php
            }
        ?>
    </div>

    <div class="recap-block">
        <h2>Lowest Rated Matches</h2>
        <?php
            $stars = '';
            for ($star_cnt = 1; $star_cnt < 6; $star_cnt++) {
                $stars .= '<span class="fa fa-star' . ($year_ratings[$recap_stats['lowest_rated_matches'][0]]['rating'] >= $star_cnt ? '' : '-o') . '"></span>';
            }
        ?>
        <h3><?= $year_ratings[$recap_stats['lowest_rated_matches'][0]]['rating']; ?>/5<br/><?= $stars; ?></h3>
        <?php
        foreach($recap_stats['lowest_rated_matches'] as $match_id)
        {
            ?>
            <div class="p-1">
                <a href="/projects/matches/matches?match_id=<?=$match_id; ?>">
                    <small><?= $year_ratings[$match_id]['event']; ?></small><br/>
                    <small><?= $year_ratings[$match_id]['contestants']; ?></small>
                </a>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php
$cleanUsername = preg_replace("/[^a-zA-Z0-9]/", "",  $recap_stats['username']);
$fn = $cleanUsername . '_matches_year-recap-' . DATE('Y') . '.png';
?>
<input download="<?= $fn ?>" id="btn-Preview-Image" type="button" value="Download as Image" />

<hr class="major"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script>
    $(document).ready(function() {
        let element = $("#recap-container");
        let getCanvas;

        $("#btn-Preview-Image").on('click', function() {
            html2canvas(element, {
                onrendered: function(canvas) {
                    getCanvas = canvas;
                    let imgageData = getCanvas.toDataURL("image/png");
                    let a = document.createElement("a");
                    a.href = imgageData;
                    a.download = "<?= $fn ?>";
                    a.click();
                }
            });
        });


    });
</script>
<?php include 'navi-footer.php'; ?>
