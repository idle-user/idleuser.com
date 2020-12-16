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
        $title = 'Account - API';
        include 'includes/head.php';
    ?>
</head>
<body>

    <?php include 'includes/nav.php'; ?>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">IdleUser API</h1>
      </div>

      <div class="input-group mb-3">
        <input type="password" class="form-control" id="showHideInput" value="<?php echo $_SESSION['auth_token'] ?>" readonly>
        <span class="input-group-text">Expires: <?php echo $_SESSION['auth_token_exp']?></span>
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="button" onclick="showHide()">Show/Hide</button>
        </div>
      </div>

      <?php include 'includes/footer.php'; ?>
    </main>

    <script>
      function showHide() {
        var x = document.getElementById("showHideInput");
        if (x.type === "password") {
          x.type = "text";
        } else {
          x.type = "password";
        }
      }
    </script>
</body>
</html>
