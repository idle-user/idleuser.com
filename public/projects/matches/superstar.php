<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';

	if(isset($_GET['superstar_id']) && !empty($_GET['superstar_id']))
		$superstar_id = htmlspecialchars($_GET['superstar_id']);
	else
		$superstar_id = 1;
	$superstar = $db->superstar($superstar_id);
	if(!$superstar) $superstar = $db->superstar(1);
	if($superstar['brand_id'])
		$superstar_brand = $db->brand($superstar['brand_id']);
	else
		$superstar_brand = ['id'=>0, 'name'=>'N/A'];


	$meta = [
		"keywords" => "{$superstar['name']}, superstar, bio, watchwrestling, WWE, AEW, NJPW, ROH, IMPACT, wrestling, bet, points, fjbot, chatroom, streams, watch online, wrestling discord, discord",
		"og:title" => "WatchWrestling Superstar - {$superstar['name']}",
	];
	include 'header.php';
?>
<header class="main">
	<h1><?php echo $superstar['name']; ?></h1>
</header>
<span class="image main">
	<img src="<?php echo $superstar['image_url']; ?>" alt="[Image Coming Soon]" />
</span>
<div class="table-wrapper">
	<table class="alt">
		<thead>
			<tr>
				<th>Name</th>
				<th>Brand</th>
				<th>Height</th>
				<th>Weight</th>
				<th>Hometown</th>
				<th>DOB</th>
				<th>Signature Moves</th>
				<?php if($_SESSION['user_id']) echo '<th>Favorite</tr>'; ?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $superstar['name']; ?></td>
				<td><a href="/projects/matches/roster?brand_id=<?php echo $superstar_brand['id']; ?>"><?php echo $superstar_brand['name']; ?></a></td>
				<td><?php echo $superstar['height']; ?></td>
				<td><?php echo $superstar['weight']; ?></td>
				<td><?php echo $superstar['hometown']; ?></td>
				<td><?php echo $superstar['dob']; ?></td>
				<td><?php echo $superstar['signature_move']; ?></td>
				<?php if($_SESSION['user_id']) echo '<td><button type="button" onclick="return updateFavorite('.$superstar['id'].')">Favorite</button></td>'; ?>
			</tr>
		</tbody>
	</table>
	<div style="white-space: pre-wrap;">
	<?php echo $superstar['bio']; ?>
	</div>
</div>
<hr class="major"/>
<?php
	$header = '<h2>Recent Matches</h2>';
	$matches = [];
	$matches = $db->superstar_matches($superstar['id']);
	include 'matchlist.php';
?>
<?php include 'navi-footer.php'; ?>
