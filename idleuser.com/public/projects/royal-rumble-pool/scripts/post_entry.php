<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	$_POST['entry_username'] = trim($_POST['entry_username']);
	if(empty($_POST['entry_username'])){
		redirect();
	}

	$ret['success'] = false;
	$ret['data'] = '';

	$user_id = $_SESSION['user_id']?:0;
	$username = $_SESSION['username']?:$_POST['entry_username'];
	$comment = isset($_POST['entry_comment'])?$_POST['entry_comment']:'';

	if(!$db->royalrumble_open()){
		ret(false, 'Sorry, entries for the '.date('Y').' Royal Rumble are closed. See you next year!');
	}

	$user_check = $db->royalrumble_entry($username);
	if($user_check){
		ret(false, $user_check['username'].' has already been assigned Entry Number #'.$user_check['number']);
	}
	$user_check = $db->username_info($username);
	if($user_check && $user_check['id']!=$user_id){
		ret(false, 'That username is taken by a registered account. Please Login.');
	}

	$entry_num = $db->add_royalrumble_entry($user_id, $username, $comment);
	if($entry_num){
		ret(true, $username.' has entered the Royal Rumble as #'.$entry_num.'!');
	}

	ret(false, 'Unable to Process Entry.');

	function ret($success, $data){
		echo json_encode(array('success'=>$success, 'data'=>$data));
		exit();
	}
	function redirect(){
		header("Location: /projects/royal-rumble-pool");
		exit();
	}
?>
