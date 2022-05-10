<?php
  require_once getenv('APP_PATH') . '/src/session.php';
  if(!$_SESSION['loggedin']){
    echo 'You must be logged in to access this.';
    redirect(1);
    exit();
  }
  set_last_page();
?>
<!doctype html>
<html lang="en">
<head>
  <title>Create a Poll</title>
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon-180x180.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
	<link rel="shortcut icon" href="/assets/images/favicon.ico">
  <link rel="manifest" href="/assets/images/site.webmanifest">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/custom.css">

  <?php
    $meta = [
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
  <?php include 'header.php'; ?>

  <main role="main">

    <section class="jumbotron text-center bg-transparent">
      <div class="container">
        <h1>Your Poll History</h1>
        <p class="lead text-muted">These are the polls you have created.</p>
        <p>
          <a href="./" class="btn btn-secondary my-2">Home</a>
          <a href="create" class="btn btn-primary my-2">Create a Poll</a>
        </p>
      </div>
    </section>

    <div class="py-5">
      <div class="container bg-light shadow">
        <h2 class="text-center py-3">Popular</h2>
        <div class="row">

        <?php
          $poll_list_limit = 6;
          $poll_list = $db->polls_user_most_recent($_SESSION['profile']['id']);
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
                      <a href="vote?id=<?php echo $poll['id'] ?>" type="button" class="btn btn-sm btn-outline-secondary">View</a>
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
        <h2 class="text-center py-3">Most Recent</h2>
        <div class="row">

        <?php
          $poll_list_limit = 6;
          $poll_list = $db->polls_user_most_recent($_SESSION['profile']['id']);
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
                      <a href="vote?id=<?php echo $poll['id'] ?>" type="button" class="btn btn-sm btn-outline-secondary">View</a>
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
        <h2 class="text-center py-3">Expired</h2>
        <div class="row">

        <?php
          $poll_list_limit = 30;
          $poll_list = $db->polls_user_expired($_SESSION['profile']['id']);
          $poll_list = array_slice($poll_list, 0, $poll_list_limit);
          if(empty($poll_list)){  ?>
            <div class="card-body">
              <p class="text-center">No previous polls found.</p>
            </div>
          <?php
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
                      <a href="vote?id=<?php echo $poll['id'] ?>" type="button" class="btn btn-sm btn-outline-secondary">View</a>
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

  <?php include 'footer.php'; ?>

</body>
</html>
