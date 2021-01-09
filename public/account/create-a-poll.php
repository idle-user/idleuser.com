<?php 	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';  set_last_page();

if(!$_SESSION['loggedin']){
    redirect(0, '/login.php');
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <?php
        $title = 'History - Create-a-Poll';
        include 'includes/head.php';
    ?>
</head>
<body>

    <?php include 'includes/nav.php'; ?>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Create-a-Poll</h1>
      </div>

      View your past polls here - Coming soon.


      <?php include 'includes/footer.php'; ?>
    </main>

</body>
</html>
