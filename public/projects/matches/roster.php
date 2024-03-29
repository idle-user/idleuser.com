<?php
require_once getenv('APP_PATH') . '/src/session.php';

if (!isset($_GET['brand_id']) || empty($_GET['brand_id']))
    $brand_id = 1;
else
    $brand_id = htmlspecialchars($_GET['brand_id']);
$brand = $db->brand($brand_id);
if (!$brand)
    $brand = $db->brand(1);

$meta = [
    "keywords" => "{$brand['name']}, roster, watchwrestling, WWE, AEW, NJPW, ROH, IMPACT, wrestling, bet, points, fjbot, chatroom, streams, watch online, wrestling discord, discord",
    "og:title" => "WatchWrestling Roster - {$brand['name']}",
];
include 'header.php';
?>
<header class="main">
    <h1><?php echo $brand['name'] . ' Roster'; ?></h1>
</header>
<?php include getenv('APP_PATH') . '/public/includes/alert.php'; ?>

<span class="image main"><img src="<?php echo $brand['image_url']; ?>" alt="[Image Coming Soon]"
                              style="max-height:150px;width:auto;display:block;margin-left:auto;margin-right:auto;"/></span>
<div class="table-wrapper">
    <table class="alt">
        <thead>
        <tr>
            <th>Name</th>
            <th>Height</th>
            <th>Weight</th>
            <th>Hometown</th>
            <th>DOB</th>
            <?php if ($_SESSION['loggedin']) echo '<th>Favorite</tr>'; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($db->brand_superstars($brand['id']) as $superstar) { ?>
            <tr>
                <td>
                    <a href="/projects/matches/superstar?superstar_id=<?php echo $superstar['id']; ?>"> <?php echo $superstar['name']; ?></a>
                </td>
                <td><?php echo $superstar['height']; ?></td>
                <td><?php echo $superstar['weight']; ?></td>
                <td><?php echo $superstar['hometown']; ?></td>
                <td><?php echo $superstar['dob']; ?></td>
                <?php if ($_SESSION['loggedin']) {
                    if ($superstar['id'] == $userStats['favorite_superstar_id']) {
                        echo '<td><button type="button" onclick="updateFavorite(' . $superstar['id'] . ')" disabled>Your Favorite</button></td>';
                    } else {
                        echo '<td><button type="button" onclick="updateFavorite(' . $superstar['id'] . ')">Favorite</button></td>';
                    }
                }?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php include 'navi-footer.php'; ?>
