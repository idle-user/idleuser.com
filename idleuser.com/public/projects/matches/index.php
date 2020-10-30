<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';
	if($_SESSION['loggedin']){
		include 'user.php';
	} 
	else {
		include 'FAQs.php';
	}
?>
