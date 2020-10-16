<?php require_once('/srv/http/src/session.php'); set_last_page(); ?>
<!doctype html>
<html lang="en">
<head>
  <title>Create a Poll</title>
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
	<link rel="shortcut icon" href="/assets/images/favicon.ico">
  <link rel="manifest" href="/assets/images/site.webmanifest">	
  <link href="/assets/bootstrap-4.5.2-dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/custom.css" rel="stylesheet">

  <?php 
    $meta = [
      "keywords" => "questionair, straw poll, poll, poll online, ask online, create poll",
      "og:title" => "Create-a-Poll",
      "og:description" => "Create and share any poll fast!"
    ];
    echo page_meta($meta);
  ?>

  <style>
    body {
      background-image: linear-gradient(to bottom, white, <?php echo random_hex_color() ?>, white);
    }
  </style>
</head>
<body>
  <?php include('header.php'); ?>

  <main role="main">

    <section class="jumbotron text-center bg-transparent">
      <div class="container">
        <h1>Create a Poll</h1>
        <p class="lead text-muted">Pineapple on pizza? Peanut butter on burgers?<br/>Whatever it may be, quickly create and share a poll with others!</p>
        <p>
          <a href="create.php" class="btn btn-primary my-2">Create a Poll</a>
          <?php if(!$_SESSION['loggedin']){ ?>
            <a href="/login.php?<?php echo get_direct_to();?>" class="btn btn-secondary my-2">Register / Login</a>
          <?php } else { ?>
            <a href="history.php" class="btn btn-secondary my-2">Your Polls</a>
          <?php } ?>
        </p>
      </div>
    </section>

    <div class="py-5">
      <div class="container bg-light shadow">
        <h2 class="text-center py-3">Popular</h2>
        <div class="row">

        <?php
          $poll_list_limit = 6;
          $poll_list = $db->polls_most_active();
          $poll_list = array_slice($poll_list, 0, $poll_list_limit);
          if(empty($poll_list)){
              echo '<p class="card-body">No active polls found.</p>';
          } else {
            foreach($poll_list as $poll){
        ?>
            <div class="col-md-4">
              <div class="card mb-4 shadow-sm">
                <div class="card-header text-center m-2" style="height:225px;background-color: <?php echo random_hex_color(0x7f7f7f) ?>">
                  <h4 class="text-light"><?php echo $poll['content']?></h4>
                </div>
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      <a href="vote.php?id=<?php echo $poll['id'] ?>" type="button" class="btn btn-sm btn-outline-secondary">Vote</a>
                    </div>
                    <small class="text-muted"><text name="countdown" value="<?php echo $poll['ending_in'] ?>"></text></small>
                  </div>
                </div>
              </div>
            </div>
        <?php 
            }
          } 
        ?>
        </div>
      </div>
    </div>

    <div class="py-5">
      <div class="container bg-light shadow">
        <h2 class="text-center py-3">Ending Soon</h2>
        <div class="row">

        <?php
          $poll_list_limit = 6;
          $poll_list = $db->polls_ending_soon();
          $poll_list = array_slice($poll_list, 0, $poll_list_limit);
          if(empty($poll_list)){
              echo '<p class="card-body">No active polls found.</p>';
          } else {
            foreach($poll_list as $poll){
        ?>
            <div class="col-md-4">
              <div class="card mb-4 shadow-sm">
                <div class="card-header text-center m-2" style="height:225px;background-color: <?php echo random_hex_color(0x7f7f7f) ?>">
                  <h4 class="text-light"><?php echo $poll['content']?></h4>
                </div>
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      <a href="vote.php?id=<?php echo $poll['id'] ?>" type="button" class="btn btn-sm btn-outline-secondary">Vote</a>
                    </div>
                    <small class="text-muted"><text name="countdown" value="<?php echo $poll['ending_in'] ?>"></text></small>
                  </div>
                </div>
              </div>
            </div>
        <?php 
            }
          } 
        ?>
        </div>
      </div>
    </div>

    <div class="py-5">
      <div class="container bg-light shadow">
        <h2 class="text-center py-3">Recently  Added</h2>
        <div class="row">

        <?php
          $poll_list_limit = 6;
          $poll_list = $db->polls_most_recent();
          $poll_list = array_slice($poll_list, 0, $poll_list_limit);
          if(empty($poll_list)){
              echo '<p class="card-body">No active polls found.</p>';
          } else {
            foreach($poll_list as $poll){
        ?>
            <div class="col-md-4">
              <div class="card mb-4 shadow-sm">
                <div class="card-header text-center m-2" style="height:225px;background-color: <?php echo random_hex_color(0x7f7f7f) ?>">
                  <h4 class="text-light"><?php echo $poll['content']?></h4>
                </div>
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      <a href="vote.php?id=<?php echo $poll['id'] ?>" type="button" class="btn btn-sm btn-outline-secondary">Vote</a>
                    </div>
                    <small class="text-muted"><text name="countdown" value="<?php echo $poll['ending_in'] ?>"></text></small>
                  </div>
                </div>
              </div>
            </div>
        <?php 
            }
          } 
        ?>
        </div>
      </div>
    </div>

  </main>

  <?php include('footer.php'); ?>
  
</body>
</html>
