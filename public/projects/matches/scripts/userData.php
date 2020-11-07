<?php

	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		header("Location: ..");
		exit();
	}
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';
	$data = false;
	if($_SESSION['loggedin']){
		$data = $db->user_stats($_SESSION['user_id']);
	}
	echo json_encode($data);
?>
