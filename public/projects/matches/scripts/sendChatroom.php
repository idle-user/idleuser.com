<?php
	if(empty($_POST['message'])){
		header("Location: /projects/matches");
		exit();
	}
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';
	if($_SESSION['user_id']){
		$success = $db->chatroom_send_message($_SESSION['user_id'], $_POST['message']);
	} else {
		$success = false;
	}
	echo $success;
?>
