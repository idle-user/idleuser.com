<?php include('header.php'); ?>
<?php
	function redirect(){
		header("Location: /projects/matches/stables.php");
		die();
	}
	if(isset($_GET['stable_id']) && !empty($_GET['stable_id']))
		$stable_id = htmlspecialchars($_GET['stable_id']);
	else
		redirect();
	$stable = $_SESSION['data']['stable'][$stable_id];
	if(!$stable) redirect();
?>
<header class="main">
	<h1><?php echo $stable['name']; ?></h1>
</header>
<span class="image main">
	<img src="<?php echo $stable['image_url']; ?>" alt="[Image Coming Soon]" />
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
				<?php if($_SESSION['user_id']) echo '<th>Favorite</tr>'; ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach($stable['members'] as $member){
			if(!$stable['members']) continue;
			$superstar = $db->superstar($member);
			if($superstar['brand_id'])
				$superstar_brand = $_SESSION['data']['brand'][$superstar['brand_id']];
			else
				$superstar_brand = ['id'=>0, 'name'=>'N/A'];
		?>
			<tr>
				<td><a href="/projects/matches/superstar.php?superstar_id=<?php echo $superstar['id']; ?>"><?php echo $superstar['name']; ?></a></td>
				<td><a href="/projects/matches/brand.php?brand_id=<?php echo $superstar_brand['id']; ?>"><?php echo $superstar_brand['name']; ?></a></td>
				<td><?php echo $superstar['height']; ?></td>
				<td><?php echo $superstar['weight']; ?></td>
				<td><?php echo $superstar['hometown']; ?></td>
				<td><?php echo $superstar['dob']; ?></td>
				<?php if($_SESSION['user_id']) echo '<td><button type="button" onclick="return updateFavorite('.$superstar['id'].')">Favorite</button></td>'; ?>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<div style="white-space: pre-wrap;">
	<?php echo $stable['bio']; ?>
	</div>
</div>
<?php include('navi-footer.php'); ?>
