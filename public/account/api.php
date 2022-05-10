<?php require_once getenv('APP_PATH') . '/src/session.php'; set_last_page();

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

      <?php include getenv('APP_PATH') . '/public/includes/alert.php'; ?>

      <label for="authTokenInput">Auth Token</label>
      <div>
        <span class="small text-muted">Do not share this.</span>
        <span class="small text-muted float-right">Expires: <?= $_SESSION['profile']['auth_token_exp'] ?></span>
      </div>
      <div class="input-group mb-3">
        <input type="password" class="form-control" id="authTokenInput" value="<?= $_SESSION['profile']['auth_token'] ?>" readonly>
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="button" id="showHideButton" onclick="showHide()" title='hide password'><i class="fas fa-eye"></i></button>
          <a class="btn btn-outline-primary float-right" type="button" data-toggle="modal" data-target="#newTokenModal" title="New Token"><i class="fas fa-sync"></i></a>
        </div>
      </div>

      <div class="modal fade" id="newTokenModal" tabindex="-1" role="dialog" aria-labelledby="newTokenModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="newTokenModalLabel">Request New API Token</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post">
              <div class="modal-body">
                <p>Any applications tied to existing token will need to be updated with the new token.</p></br>
                <p>Are you sure you want to request a new API Token?</p>
                <div class="form-label-group">
                  <input type="username" id="inputUsername" class="form-control" placeholder="Username" name="username" value="<?= $_SESSION['profile']['username'] ?>"  hidden readonly>
                </div>
                <div class="form-label-group">
                  <input type="password" id="inputPassword" class="form-control" placeholder="Confirm Password" name="secret" required autofocus>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit" name="auth-token-update">New Token</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <?php include 'includes/footer.php'; ?>
    </main>

    <script>
      function showHide() {
        var x = document.getElementById("authTokenInput");
        var y = document.getElementById("showHideButton");
        showHideButton
        if (x.type === "password") {
          x.type = "text";
          y.firstElementChild.classList="fas fa-eye-slash";
          y.title="hide token"
        } else {
          x.type = "password";
          y.firstElementChild.classList="fas fa-eye";
          y.title="show token"

        }
      }
    </script>
</body>
</html>
