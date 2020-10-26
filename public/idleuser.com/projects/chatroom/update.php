<?php
	require_once '/srv/http/src/session.php';
	if(!isset($_POST['last_message_time'])){
		header("Location: /projects/chatroom");
		exit();
	}
	$last_message_time = $_POST['last_message_time'];
	if($last_message_time==0){
		$res = $db->chatroom_history();
	}
	else{
		$res = $db->chatroom_update($last_message_time);
	}
	echo json_encode($res);
?>
