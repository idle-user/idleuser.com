<?php include('header.php'); ?>
<header class="main">
	<h1>Stables</h1>
</header>
<div class="table-wrapper">
	<table class="alt">
		<thead>
			<tr>
				<th>Name</th>
				<th>Members</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($_SESSION['data']['stable'] as $stable){ if(!$stable['members']) continue; ?>
		<tr>
			<td><a href="/projects/matches/stable.php?stable_id=<?php echo $stable['id']; ?>"><?php echo $stable['name']; ?></a></td>
			<td>
			<?php foreach($stable['members'] as $member){ $superstar = $db->superstar($member);?>
				<a href="/projects/matches/superstar.php?superstar_id=<?php echo $superstar['id']; ?>"><?php echo $superstar['name']; ?></a><br/>
			<?php } ?>
			</td>
		</tr>
<?php } ?>
	</table>
</div>
<?php include('navi-footer.php'); ?>
