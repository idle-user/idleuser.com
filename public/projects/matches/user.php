<?php
require_once getenv('APP_PATH') . '/src/session.php';

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $user_id = htmlspecialchars($_GET['user_id']);
} else if ($_SESSION['loggedin']) {
    $user_id = $_SESSION['profile']['id'];
} else {
    $user_id = 1;
}
$user_stats = $db->user_stats($user_id);
if (!$user_stats)
    $user_stats = $db->user_stats(1);
$superstar = $db->superstar($user_stats['favorite_superstar_id']);
if ($superstar['brand_id'])
    $superstar_brand = $db->brand($superstar['brand_id']);
else
    $superstar_brand = ['id' => 0, 'name' => 'N/A'];

$meta = [
    "keywords" => "{$user_stats['username']}, watchwrestling, WWE, AEW, NJPW, ROH, IMPACT, wrestling, bet, points, fjbot, chatroom, streams, watch online, wrestling discord, discord",
    "og:title" => "WatchWrestling Profile - {$user_stats['username']}",
    "og:description" => "{$user_stats['username']}'s WatchWrestling profile. View their stats, bets, win/loss record."
];
include 'header.php';
?>
<header class="main">
    <h1><?= $user_stats['username']; ?></h1>
    <?php if(is_owner()) { ?>
    <pre><?= var_dump($db->user_socials($user_id)); ?></pre>
    <?php } ?>
</header>
<?php //var_dump($user_stats); ?>
<div class="table-wrapper">
    <?php foreach ($user_stats['season'] as $stats) { ?>
    <table class="alt">
        <thead>
        <th>Season <?= $stats['season'] ?></th>
        <tr>
            <th width="33%">Wins</th>
            <th width="33%">Losses</th>
            <th width="33%">Points</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?= $stats['wins'] ?: '0'; ?></td>
            <td><?= $stats['losses'] ?: '0'; ?></td>
            <td><?= number_format($stats['total_points'], 0, '', ',') ?: '0'; ?></td>
        </tr>
        </tbody>
        <?php } ?>
    </table>
</div>
<hr class="major"/><h2>Favorite Superstar</h2>
<div class="table-wrapper">
    <span class="image main"><img src="<?php echo $superstar['image_url']; ?>" alt="[Image Coming Soon]"/></span>
    <table class="alt">
        <thead>
        <tr>
            <th>Name</th>
            <th>Brand</th>
            <th>Height</th>
            <th>Weight</th>
            <th>Hometown</th>
            <th>DOB</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $superstar['name']; ?></td>
            <td>
                <a href="/matches/roster?brand_id=<?php echo $superstar_brand['id']; ?>"><?php echo $superstar_brand['name']; ?></a>
            </td>
            <td><?php echo $superstar['height']; ?></td>
            <td><?php echo $superstar['weight']; ?></td>
            <td><?php echo $superstar['hometown']; ?></td>
            <td><?php echo $superstar['dob']; ?></td>
        </tr>
        </tbody>
    </table>
    <div>
        <?php echo $superstar['bio']; ?>
    </div>
</div>
<hr class="major"/>
<?php
$header = '<h2>Matches Bet On</h2>';
$matches = $db->user_matches($user_stats['user_id']);
include 'matchlist.php';
?>
<?php include 'navi-footer.php'; ?>
