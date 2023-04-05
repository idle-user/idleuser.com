<?php
require_once getenv('APP_PATH') . '/src/session.php';
set_last_page();

if (!$_SESSION['loggedin']) {
    redirect(0, '/login');
    exit();
}

if (isset($_GET['season']) && !empty($_GET['season'])) {
    $season_id = htmlspecialchars($_GET['season']);
}

$stats = $db->user_season_stats($_SESSION['profile']['id'], $season_id);
$bets = $db->user_season_bets($_SESSION['profile']['id'], $season_id);

?>
<!doctype html>
<html lang="en">
<head>
    <?php
    $title = 'History - Matches';
    include 'includes/head.php';
    ?>
</head>
<body>

<?php include 'includes/nav.php'; ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Matches - Your Season <?php echo $season_id; ?> Bets</h1>
    </div>

    <div class="table-responsive-xl">
        <table class="table table-striped table-bordered">
            <caption>Everyone begins with an initial 100 points</caption>
            <thead>
            <tr>
                <th scope="col">Wins</th>
                <th scope="col">Losses</th>
                <th scope="col">Matches Rated</th>
                <th scope="col">Ratings Points</th>
                <th scope="col">Daily Points</th>
                <th scope="col">Total Points</th>
                <th scope="col">Available Points</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo number_format($stats['wins']); ?></td>
                <td><?php echo number_format($stats['losses']); ?></td>
                <td><?php echo number_format($stats['ratings']); ?></td>
                <td><?php echo number_format($stats['rating_points']); ?></td>
                <td><?php echo number_format($stats['daily_points']); ?></td>
                <td><?php echo number_format($stats['total_points']); ?></td>
                <td><?php echo number_format($stats['available_points']); ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive-xl">
        <table class="table table-sm">
            <caption>Click on the match link to visit public page</caption>
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Match</th>
                <th scope="col">Bet</th>
                <th scope="col">Result</th>
                <th scope="col">Standing History</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $bet_cnt = count($bets);
            $standing = $stats['bet_points'];
            foreach ($bets as $bet) {
                $standing_formatted = number_format($standing);

                $bet_amount = number_format($bet['bet_amount']);
                $potential_cut_pct = number_format($bet['potential_cut_pct'] * 100, 3);
                $potential_cut_points = number_format($bet['potential_cut_points']);
                $total_pot = number_format($bet['total_pot']);
                $base_pot = number_format($bet['base_pot']);

                $match_url = "<a class='text-dark' href='/projects/matches/matches?match_id={$bet['match_id']}'>{$bet['event_dt']} {$bet['event_name']}</a>";
                $match_contestants = "<text class='small text-muted'>{$bet['contestants']}</text>";

                $bet_on = "<strong>{$bet_amount}</strong> on {$bet['bet_on']}";
                if ($season_id < 7) {
                    $pot_actuals = "<text class='small font-italic'>Pot: {$total_pot}</text>";
                } else {
                    $pot_actuals = "<text class='small font-italic'>Pot: {$base_pot} + ({$bet['team_base_pot']} x {$bet['bet_multiplier']}) => {$total_pot}</text>";
                }
                $bet_potentials = "<text class='small font-italic'>Potential Cut: {$potential_cut_points} ({$potential_cut_pct}%)</text>";

                $num_text = $bet_cnt--;
                $match_text = "{$match_url}<br/>{$match_contestants}";
                $bet_text = "{$bet_on}<br/>{$pot_actuals}<br/>{$bet_potentials}";
                if ($bet['bet_won']) {
                    $result_text = "<text class='alert-success'>+{$potential_cut_points}</text>";
                    $standing = $standing - $bet['potential_cut_points'];
                } else {
                    if ($bet['pot_valid']) {
                        $result_text = "<text class='alert-danger'>-{$bet_amount}</text>";
                        $standing = $standing + $bet['bet_amount'];
                    } else {
                        $result_text = "<text class='alert-warning'>+0<br/>Pot Invalid</text>";
                    }
                }

                echo '<tr>';
                echo "<th scope='row'>{$num_text}</th>";
                echo "<td>{$match_text}</td>";
                echo "<td>{$bet_text}</td>";
                echo "<td>{$result_text}</td>";
                echo "<td>{$standing_formatted}</td>";
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>

    </div>

    <?php include 'includes/footer.php'; ?>
</main>
</body>
</html>
