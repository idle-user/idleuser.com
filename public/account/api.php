<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php'; set_last_page();

if(!$_SESSION['loggedin']){
    redirect(0, '/login');
    exit();
}

$response = maybe_process_form();

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

      <?php include 'includes/alert.php'; ?>

      <label for="authTokenInput">Auth Token</label>
      <div>
        <span class="small text-muted">Do not share this.</span>
        <span class="small text-muted float-right">Expires: <?php echo $_SESSION['auth_token_exp']?></span>
      </div>
      <div class="input-group mb-3">
        <input type="password" class="form-control" id="authTokenInput" value="<?php echo $_SESSION['auth_token'] ?>" readonly>
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="button" id="showHideButton" onclick="showHide()">Show</button>
        </div>
      </div>
      <!--a class="btn btn-outline-primary float-right" type="button" href="">Regenerate</a-->

      <?php include 'includes/footer.php'; ?>
    </main>

    <script>
      function showHide() {
        var x = document.getElementById("authTokenInput");
        var y = document.getElementById("showHideButton");
        showHideButton
        if (x.type === "password") {
          x.type = "text";
          y.textContent = "Hide";
        } else {
          x.type = "password";
          y.textContent = "Show";
        }
      }
    </script>
</body>
</html>
