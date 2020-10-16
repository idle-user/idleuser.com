<?php require_once('/srv/http/src/session.php'); ?>
<!DOCTYPE HTML>
<html>
<head>
	<title>FJBot-Discord Commands</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta property="og:image" content="/favicon256.png" />
	<meta property="og:title" content="FJBot Discord Commands" />
	<meta property="og:description" content="List of commands to use in Discord with FJBot" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</head>
<body>
	<div class="container-sm">
		<h1 class="text-center">FJBot Command List</h1>
	<?php
	$data = $db->all_discord_commands();
	if(!$data) {
       $data = array(array(
            'id' => '0',
            'command' => 'No commands found.',
            'response' => 'No commands found.',
            'description' => 'No commands found.',
        ));
		$message = 'Unable to retrieve commands.';
	}
	?>
		<div class="row">
			<div class="col-4">
				<div class="list-group" id="list-tab" role="tablist" style="max-height: 75vh; overflow: auto;">
					<?php foreach($data as $item){ ?>
					<a class="list-group-item list-group-item-action" id=<?php echo '"list-'.$item['id'].'-list"'; ?> data-toggle="list" href=<?php echo '"#list-'.$item['id'].'"'; ?> role="tab" aria-controls=<?php echo '"'.$item['id'].'"'; ?>><?php echo $item['command']; ?></a>
					<?php } ?>
				</div>
			</div>
			<div class="col-8">
				<div class="tab-content" id="nav-tabContent">
					<?php foreach($data as $item){ ?>
					<div class="tab-pane fade" id=<?php echo '"list-'.$item['id'].'"'; ?> role="tabpanel" aria-labelledby=<?php echo '"list-'.$item['id'].'-list"'; ?>>
						<p><?php echo $item['response'];?></p>
						<p class="muted"></br/><?php echo $item['description'];?></p>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<footer class="page-footer font-small blue pt-4">
			<div class="footer-copyright text-center py-3">
				<ul class="list-unstyled">
					<li>© 2020 Jesus Andrade</li>
					<li>DNS: <a href="https://freedns.afraid.org/">Free DNS</a></li>
					<li>Page Last Updated: <?php echo date("Y.m.d H:i:s.", getlastmod()); ?></i>
				</ul>
			</div
		</footer>
	</div>
</body>
</html>
