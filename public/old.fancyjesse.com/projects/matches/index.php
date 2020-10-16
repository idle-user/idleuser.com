<?php include('header.php'); ?>
<header class="main">
	<h1>Matches</h1>
</header>
<?php
	if($_SESSION['user_id']){
		header("Location: user.php");
	} else {
		header("Location: FAQs.php");
	}
?>
<?php include('navi-footer.php'); ?>

