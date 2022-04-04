<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';

	if(isset($_GET['season']) && !empty($_GET['season'])){
		$season = htmlspecialchars($_GET['season']);
	} else {
		$season = 0;
	}

	if($season == 1){
		$leaderboard = $db->s1_leaderboard();
	} elseif($season == 2) {
		$leaderboard = $db->s2_leaderboard();
	} elseif($season == 3){
		$leaderboard = $db->s3_leaderboard();
	} elseif($season == 4){
		$leaderboard = $db->s4_leaderboard();
	} elseif($season == 5){
		$leaderboard = $db->s5_leaderboard();
	} else {
		$season = 6;
		$leaderboard = $db->s6_leaderboard();
	}

	$meta = [
		"keywords" => "Season {$season}, watchwrestling, WWE, AEW, NJPW, ROH, IMPACT, wrestling, bet, points, fjbot, chatroom, streams, watch online, wrestling discord, discord",
		"og:title" => "WatchWrestling Leaderboard - Season {$season}",
	];
	include 'header.php';

?>
<header class="main">
	<h1>Leaderboard (Season <?php echo $season; ?>)</h1>
</header>
<span class="image main"><img src="" alt="" /></span>
<div class="table-wrapper">
	<table class="alt">
		<thead>
			<tr>
				<th>Rank</th>
				<th>Username</th>
				<th>Favorite Superstar</th>
				<th>Wins</th>
				<th>Losses</th>
				<th>Points</th>
			</tr>
		</thead>
		<tbody>
	<?php $cnt = 1; foreach($leaderboard as $user){ ?>
			<tr>
				<td><?php echo $cnt; ?></td>
				<td><a href="/projects/matches/user?user_id=<?php echo $user['user_id']; ?>"><?php echo $user['username']; ?></a></td>
				<td><a href="/projects/matches/superstar?superstar_id=<?php echo $user['favorite_superstar_id']; ?>"><?php echo $db->superstar($user['favorite_superstar_id'])['name'] ?></a></td>
				<td><?php echo $user['wins'] ? $user['wins'] : '0'; ?></td>
				<td><?php echo $user['losses'] ? $user['losses'] : '0'; ?></td>
				<td><?php echo number_format($user['points'], 0, '', ',')?:'0'; ?></td>
			</tr>
	<?php $cnt += 1; }?>
		</tbody>
	</table>
</div>
<?php include 'navi-footer.php'; ?>
