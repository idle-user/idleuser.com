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
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="usernameFormControlInput" name="username" pattern="/^[\w\-]+$/i" title="Can contain: a-z A-Z 0-9 - _" value="<?php echo $_SESSION['username']; ?>">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button">Update</button>
            </div>
          </div>
        </div>
      </form>
      <form>
        <div class="form-group">
          <label for="emailFormControlInput">Email Address</label>
          <div class="input-group mb-3">
            <input type="password" class="form-control" id="emailFormControlInput" name="email" value="<?php echo $db->user_email($_SESSION['user_id'])['email']; ?>">
            <div class="input-group-append">
              <button class="btn btn-secondary" type="button" id="showHideButton" onclick="showHide()">Show</button>
            </div>
            <div class="input-group-append">
              <button class="btn btn-primary" type="button">Update</button>
            </div>
          </div>
        </div>
      </form>

      <a class="btn btn-primary mt-5" type="button" href="change-password.php">Change Password</a>

      <?php include 'includes/footer.php'; ?>
    </main>
    <script>
      function showHide() {
        var x = document.getElementById("emailFormControlInput");
        var y = document.getElementById("showHideButton");
        if (x.type === "password") {
          x.type = "email";
          y.textContent = "Hide";
        } else {
          x.type = "password";
          y.textContent = "Show";
        }
      }
    </script>
</body>
</html>
