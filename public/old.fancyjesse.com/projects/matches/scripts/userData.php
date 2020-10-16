<?php

	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		header("Location: ../index.php");
		exit();
	}
	require_once('/srv/http/src/session.php');
	$data = false;
	if($_SESSION['user_id']){
		$data = $db->user_stats($_SESSION['user_id']);
	}
	echo json_encode($data);
?>
