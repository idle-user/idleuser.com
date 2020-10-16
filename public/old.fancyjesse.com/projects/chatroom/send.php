<?php
	require_once('/srv/http/src/session.php');
	if(empty($_POST['user_id'])||empty($_POST['message'])){
		header("Location: /projects/chatroom");
		exit();
	}
	$success = $db->chatroom_send_message($_POST['user_id'], $_POST['message']);
	echo $success;
?>
