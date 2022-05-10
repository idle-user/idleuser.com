<?php
	require_once getenv('APP_PATH') . '/src/session.php';
	if($_SESSION['loggedin']){
		include 'user.php';
	}
	else {
		include 'FAQs.php';
	}
?>
