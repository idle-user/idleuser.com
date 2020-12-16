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
        $title = 'Account - Settings';
        include 'includes/head.php';
    ?>
</head>
<body>

    <?php include 'includes/nav.php'; ?>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">My Account</h1>
      </div>

      <form>
        <div class="form-group">
          <label for="usernameFormControlInput">Username</label>
          <input type="text" class="form-control" id="usernameFormControlInput" value="<?php echo $_SESSION['username'] ?>">
        </div>
        <div class="form-group">
          <label for="emailFormControlInput">Email address</label>
          <input type="email" class="form-control" id="emailFormControlInput" value="<?php echo $db->user_email($_SESSION['user_id'])['email']; ?>">
        </div>
      </form>

      <a class="btn btn-primary" type="button" href="change-password.php">Change Password</a>

      <?php include 'includes/footer.php'; ?>
    </main>

</body>
</html>
