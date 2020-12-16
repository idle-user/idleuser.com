<?php 	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';

if(!$_SESSION['loggedin']){
    redirect(0, '/login.php');
    exit();
}
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

      <form>
        <div class="form-group">
          <label for="discordFormControlInput">Discord ID</label>
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="discordFormControlInput" value="<?php echo $db->user_discord($_SESSION['user_id'])['discord_id']; ?>">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button">Update</button>
            </div>
          </div>
        </div>
      </form>
      <form>
        <div class="form-group">
          <label for="chatangoFormControlInput">Chatango Username</label>
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="chatangoFormControlInput" value="<?php echo $db->user_chatango($_SESSION['user_id'])['chatango_id']; ?>">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button">Update</button>
            </div>
          </div>
        </div>
      </form>
      <form>
        <div class="form-group">
          <label for="twitterFormControlInput">Twitter ID</label>
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="twitterFormControlInput" value="<?php echo $db->user_twitter($_SESSION['user_id'])['twitter_id']; ?>">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button">Update</button>
            </div>
          </div>
        </div>
      </form>

      <?php include 'includes/footer.php'; ?>
    </main>

</body>
</html>
