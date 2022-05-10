<?php
	if(empty($_POST['message'])){
		header("Location: /projects/matches");
		exit();
	}
	require_once getenv('APP_PATH') . '/src/session.php';
	if($_SESSION['profile']['id']){
		$success = $db->chatroom_send_message($_SESSION['profile']['id'], $_POST['message']);
	} else {
		$success = false;
	}
	echo $success;
?>
