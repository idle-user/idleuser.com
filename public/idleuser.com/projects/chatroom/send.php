<?php
	require_once('/srv/http/src/session.php');
	if(!$_SESSION['loggedin'] || empty($_POST['message'])){
		header("Location: /projects/chatroom");
		exit();
	}
	$success = $db->chatroom_send_message($_SESSION['user_id'], $_POST['message']);
	echo $success;
?>
