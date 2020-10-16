<?php
	if(!isset($_POST['last_message_time'])){
		header("Location: /projects/matches");
		exit();
	}
	require_once('baseData.php');
	$dt = $_POST['last_message_time'];
	$res = array();
	$res['users'] = $db->chatroom_users();;
	if($dt==0){
		$res['messages'] = $db->chatroom_history();
	} else{
		$res['messages'] = $db->chatroom_update($dt);
	}
	echo json_encode($res);
?>
