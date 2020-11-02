<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';
	$_POST['entry_username'] = trim($_POST['entry_username']);
	if(empty($_POST['entry_username'])){
		redirect_back();
	}

	$rr_id = isset($_POST['entry_event'])?$_POST['entry_event']:0;
	if($rr_id==0){
		ret(false, 'Please select a Royal Rumble event.');
	}

	$ret['success'] = false;
	$ret['data'] = '';

	$user_id = $_SESSION['user_id']?:0;
	$username = $_SESSION['username']?:$_POST['entry_username'];
	$comment = isset($_POST['entry_comment'])?$_POST['entry_comment']:'';

	$rr = $db->royalrumble($rr_id);
	if($rr['winner']){
		ret(false, 'Sorry, entries for that event are closed.');
	}

	$user_check = $db->username_info($username);
	if($user_check && $user_check['id']!=$user_id){
		ret(false, 'That username is taken by a registered account. Please login or choose a different username.');
	}

	$user_check = $db->royalrumble_entry($rr_id, $username);
	if($user_check){
		ret(false, $user_check['username'].' has already been assigned Entry Number #'.$user_check['number']);
	}

	$entry_num = $db->add_royalrumble_entry($rr_id, $user_id, $username, $comment);
	if($entry_num){
		ret(true, $username.' has entered the Royal Rumble as #'.$entry_num.'!');
	}

	ret(false, 'Unable to Process Entry.');

	function ret($success, $data){
		echo json_encode(array('success'=>$success, 'data'=>$data));
		exit();
	}
?>
