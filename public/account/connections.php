<?php 	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';


if(!$_SESSION['loggedin']){
    redirect(0, '/login.php');
    exit();
}

$response = maybe_process_form();

?>
<!doctype html>
<html lang="en">
<head>
    <?php
        $title = 'Account - Connections';
        include 'includes/head.php';
    ?>
</head>
<body>

    <?php include 'includes/nav.php'; ?>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Connections</h1>
      </div>

      <?php include 'includes/alert.php'; ?>

      <?php $discordInfo = $db->user_discord($_SESSION['user_id']); ?>
      <form method="post">
        <div class="form-group">
          <label for="discordFormControlInput">Discord ID</label>
          <span class="small text-muted float-right pt-3">Last Updated: <?php echo $discordInfo['discord_last_updated'];?></span>
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="discordFormControlInput" name="discord_id" value="<?php echo $discordInfo['discord_id']; ?>">
            <div class="input-group-append">
              <input class="btn btn-primary" type="submit" name="discord-update" value="Update">
            </div>
          </div>
        </div>
      </form>

      <?php $chatangoInfo = $db->user_chatango($_SESSION['user_id']); ?>
      <form method="post">
        <div class="form-group">
          <label for="chatangoFormControlInput">Chatango Username</label>
          <span class="small text-muted float-right pt-3">Last Updated: <?php echo $chatangoInfo['chatango_last_updated'];?></span>
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="chatangoFormControlInput" name="chatango_id" value="<?php echo $chatangoInfo['chatango_id']; ?>">
            <div class="input-group-append">
              <input class="btn btn-primary" type="submit" name="chatango-update" value="Update">
          </div>
        </div>
      </form>

      <?php $twitterInfo = $db->user_twitter($_SESSION['user_id']); ?>
      <form method="post" class="mb-3">
        <div class="form-group">
          <label for="twitterFormControlInput">Twitter ID</label>
          <span class="small text-muted float-right pt-3">Last Updated: <?php echo $twitterInfo['twitter_last_updated'];?></span>
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="twitterFormControlInput" name="twitter_id" value="<?php echo $twitterInfo['twitter_id']; ?>">
            <div class="input-group-append">
              <input class="btn btn-primary" type="submit" name="twitter-update" value="Update">
            </div>
          </div>
        </div>
      </form>

      <?php include 'includes/footer.php'; ?>
    </main>

</body>
</html>
