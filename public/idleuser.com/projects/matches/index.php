<?php
	require_once('/srv/http/src/session.php');
	if($_SESSION['loggedin']){
		include("user.php");
	} 
	else {
		include("FAQs.php");
	}
?>
