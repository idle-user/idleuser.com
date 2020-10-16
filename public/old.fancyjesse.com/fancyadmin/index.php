<?php require_once('assets/php/admin.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - Home</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="/assets/favicon.ico">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/custom.js"></script>
	<link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
<?php include('nav.php') ?>
	<div class="container">
		<h1 class="text-center">Admin Links</h1>
		<p class="text-center small">Don't fuck shit up.</p>
	</div>
	<br/><br/>
	<div id="data" class="container text-center">
		<div class="row">
			<div class="col-lg-12">
				<div class="row">
					<a href="matches.php" class="no-color">
						<div class="col-sm-4 col-md-4 col-lg-4">
							<div class="box">
								<div class="aligncenter">
									<div class="icon">
										<i class="fa fa-list fa-5x"></i>
									</div>
									<h4>Matches Editor</h4>
									<p>
										Manage Matches match list.
									</p>
								</div>
							</div>
						</div>
					</a>
					<a href="matches-base.php" class="no-color">
						<div class="col-sm-4 col-md-4 col-lg-4">
							<div class="box">
								<div class="aligncenter">
									<div class="icon">
										<i class="fa fa-list fa-5x"></i>
									</div>
									<h4>Matches Base Data Editor</h4>
									<p>
										Manage Matches base data.
									</p>
								</div>
							</div>
						</div>
					</a>
					<a href="matches-roster.php" class="no-color">
						<div class="col-sm-4 col-md-4 col-lg-4">
							<div class="box">
								<div class="aligncenter">
									<div class="icon">
										<i class="fa fa-list fa-5x"></i>
									</div>
									<h4>Matches Roster Editor</h4>
									<p>
										Manage the Matches roster.
									</p>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="row">
					<a href="discordbot-commands.php" class="no-color">
						<div class="col-sm-6 col-md-6 col-lg-6">
							<div class="box">
								<div class="aligncenter">
									<div class="icon">
										<i class="fa fa-cubes fa-5x"></i>
									</div>
									<h4>FJBot Commands</h4>
									<p>
										Manage Discord bot chat commands.
									</p>
								</div>
							</div>
						</div>
					</a>
					<a href="discordbot-scheduler.php" class="no-color">
						<div class="col-sm-6 col-md-6 col-lg-6">
							<div class="box">
								<div class="aligncenter">
									<div class="icon">
										<i class="fa fa-cubes fa-5x"></i>
									</div>
									<h4>FJBot Scheduler</h4>
									<p>
										Manage Discord bot scheduled alerts.
									</p>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
