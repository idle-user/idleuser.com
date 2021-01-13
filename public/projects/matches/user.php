<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';

	if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
		$user_id = htmlspecialchars($_GET['user_id']);
	} else if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ){
		$user_id = $_SESSION['user_id'];
	} else {
		$user_id = 1;
	}
	$user = $db->user_stats($user_id);
	if(!$user)
		$user = $db->user_stats(1);
	$superstar = $db->superstar($user['favorite_superstar_id']);
	if($superstar['brand_id'])
		$superstar_brand = $db->brand($superstar['brand_id']);
	else
		$superstar_brand = ['id'=>0, 'name'=>'N/A'];

	$meta = [
		"keywords" => "{$user['username']}, watchwrestling, WWE, AEW, NJPW, ROH, IMPACT, wrestling, bet, points, fjbot, chatroom, streams, watch online, wrestling discord, discord",
		"og:title" => "WatchWrestling Profile - {$user['username']}",
		"og:description" => "{$user['username']}'s WatchWrestling profile. View their stats, bets, win/loss record."
	];
	include 'header.php';
?>
<header class="main">
	<h1><?php echo $user['username']; ?></h1>
</header>
<div class="table-wrapper">
	<table class="alt">
		<thead>
			<th>Season 4</th>
			<tr>
				<th width="33%">Wins</th>
				<th width="33%">Losses</th>
				<th width="33%">Points</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $user['s4_wins'] ? $user['s4_wins'] : '0'; ?></td>
				<td><?php echo $user['s4_losses'] ? $user['s4_losses'] : '0'; ?></td>
				<td><?php echo number_format($user['s4_total_points'], 0, '', ',')?:'0'; ?></td>
			</tr>
		</tbody>
	</table>
	<table class="alt">
		<thead>
			<th>Season 3</th>
			<tr>
				<th width="33%">Wins</th>
				<th width="33%">Losses</th>
				<th width="33%">Points</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $user['s3_wins'] ? $user['s3_wins'] : '0'; ?></td>
				<td><?php echo $user['s3_losses'] ? $user['s3_losses'] : '0'; ?></td>
				<td><?php echo number_format($user['s3_total_points'], 0, '', ',')?:'0'; ?></td>
			</tr>
		</tbody>
	</table>
	<table class="alt">
		<thead>
			<th>Season 2</th>
			<tr>
				<th width="33%">Wins</th>
				<th width="33%">Losses</th>
				<th width="33%">Points</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $user['s2_wins'] ? $user['s2_wins'] : '0'; ?></td>
				<td><?php echo $user['s2_losses'] ? $user['s2_losses'] : '0'; ?></td>
				<td><?php echo number_format($user['s2_total_points'], 0, '', ',')?:'0'; ?></td>
			</tr>
		</tbody>
	</table>
	<table class="alt">
		<thead>
			<th>Season 1</th>
			<tr>
				<th width="33%">Wins</th>
				<th width="33%">Losses</th>
				<th width="33%">Points</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $user['s1_wins'] ? $user['s1_wins'] : '0'; ?></td>
				<td><?php echo $user['s1_losses'] ? $user['s1_losses'] : '0'; ?></td>
				<td><?php echo number_format($user['s1_total_points'], 0, '', ',')?:'0'; ?></td>
			</tr>
		</tbody>
	</table>
</div>
<hr class="major" /><h2>Favorite Superstar</h2>
<div class="table-wrapper">
	<span class="image main"><img src="<?php echo $superstar['image_url']; ?>" alt="[Image Coming Soon]" /></span>
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
				<td><a href="/matches/roster?brand_id=<?php echo $superstar_brand['id']; ?>"><?php echo $superstar_brand['name']; ?></a></td>
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
	$matches = $db->user_matches($user['user_id']);
	include 'matchlist.php';
?>
<?php include 'navi-footer.php'; ?>
