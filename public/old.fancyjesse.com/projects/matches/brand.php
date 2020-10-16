<?php include('header.php'); ?>
<?php
	if(!isset($_GET['brand_id']) || empty($_GET['brand_id']))
		$brand_id = 1;
	else
		$brand_id = htmlspecialchars($_GET['brand_id']);
	if(!array_key_exists($brand_id, $_SESSION['data']['brand'])){
		$brand_id = 1;
	}
	$brand = $_SESSION['data']['brand'][$brand_id];
?>
<header class="main">
	<h1><?php echo $brand['name'].' Roster'; ?></h1>
</header>
<span class="image main"><img src="images/brand_<?php echo $brand['id']; ?>.jpg" alt="[Image Coming Soon]" /></span>
<div class="table-wrapper">
	<table class="alt">
		<thead>
			<tr>
				<th>Name</th>
				<th>Height</th>
				<th>Weight</th>
				<th>Hometown</th>
				<th>DOB</th>
				<?php if($_SESSION['user_id']) echo '<th>Favorite</tr>'; ?>
			</tr>
		</thead>
		<tbody>
	<?php foreach($db->brand_superstars($brand['id']) as $superstar){ ?>
			<tr>
				<td><a href="/projects/matches/superstar.php?superstar_id=<?php echo $superstar['id']; ?>"> <?php echo $superstar['name']; ?></a></td>
				<td><?php echo $superstar['height']; ?></td>
				<td><?php echo $superstar['weight']; ?></td>
				<td><?php echo $superstar['hometown']; ?></td>
				<td><?php echo $superstar['dob']; ?></td>
				<?php if($_SESSION['user_id']) echo '<td><button type="button" onclick="updateFavorite('.$superstar['id'].')">Favorite</button></td>'; ?>
			</tr>
	<?php } ?>
		</tbody>
	</table>
</div>
<?php include('navi-footer.php'); ?>
