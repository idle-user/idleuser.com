<?php 	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';  set_last_page();

if(!$_SESSION['loggedin']){
    redirect(0, '/login');
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

      <div class="table-responsive-xl">
        <table class="table table-striped table-bordered">
        <caption>List of Polls Created by You</caption>
        <thead>
                <tr>
                  <th scope="col">Title</th>
                  <th scope="col">Votes</th>
                  <th scope="col">Created</th>
                  <th scope="col">Active</th>
                  <th scope="col">Link</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $poll_list = $db->all_user_polls($_SESSION['user_id']);
                  if(empty($poll_list)){
                    echo '<p>No active polls found.</p>';
                  } else {
                    foreach($poll_list as $poll){
                ?>
                    <tr>
                      <td><?php echo $poll['content'] ?></td>
                      <td><?php echo $poll['votes'] ?></td>
                      <td><?php echo $poll['created_dt'] ?></td>
                      <td><?php echo $poll['ending_in'] > 0 ? 'Yes' : 'No'; ?></td>
                      <td><a href="/projects/create-a-poll/vote?id=<?php echo $poll['id'] ?>" type="button" class="btn btn-sm btn-outline-secondary" target="_blank">View</a></td>
                    </tr>
                <?php } } ?>
                </tbody>
            </table>
      </div>
      <?php include 'includes/footer.php'; ?>
    </main>

</body>
</html>
