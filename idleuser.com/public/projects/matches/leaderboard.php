<?php
	include 'header.php';

	$cnt = 1;

	if(isset($_GET['season_id']) && !empty($_GET['season_id'])){
		$season_id = htmlspecialchars($_GET['season_id']);
	}
	if($season_id == 1){
		$leaderboard = $db->s1_leaderboard();
	} else if($season_id == 2) {
		$season_id = 2;
		$leaderboard = $db->s2_leaderboard();
	} else if($season_id == 3){
		$season_id = 3;
		$leaderboard = $db->s3_leaderboard();
	} else {
		$season_id = 4;
		$leaderboard = $db->s4_leaderboard();
	}
?>
<header class="main">
	<h1>Leaderboard (Season <?php echo $season_id; ?>)</h1>
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
	<?php foreach($leaderboard as $user){ ?>
			<tr>
				<td><?php echo $cnt; ?></td>
				<td><a href="/projects/matches/user.php?user_id=<?php echo $user['user_id']; ?>"><?php echo $user['username']; ?></a></td>
				<td><a href="/projects/matches/superstar.php?superstar_id=<?php echo $user['favorite_superstar_id']; ?>"><?php echo $db->superstar($user['favorite_superstar_id'])['name'] ?></a></td>
				<td><?php echo $user['wins'] ? $user['wins'] : '0'; ?></td>
				<td><?php echo $user['losses'] ? $user['losses'] : '0'; ?></td>
				<td><?php echo number_format($user['points'], 0, '', ',')?:'0'; ?></td>
			</tr>
	<?php $cnt += 1; }?>
		</tbody>
	</table>
</div>
<?php include 'navi-footer.php'; ?>
