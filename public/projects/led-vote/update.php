<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		header("Location: /projects/led-vote");
		exit();
	}
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	$res = $db->led_total_votes();
	$arr = array();
	while($row = $res->fetch_array(MYSQLI_ASSOC)) {
		$arr[] = $row;
	}
	echo json_encode($arr);
?>
