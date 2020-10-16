<?php
	require_once('/srv/http/src/session.php');
	if(empty($_SESSION['user_id'])||empty($_SESSION['username'])||empty($_POST['old_secret'])||empty($_POST['new_secret'])||empty($_POST['new_secret_verify'])){
		redirect_back();
	}
	if($_POST['new_secret'] != $_POST['new_secret_verify']){
		echo false;
		exit();
	}
	$res = $db->connect();
	if($res){
		$res = $db->user_change_password($_SESSION['user_id'], $_SESSION['username'], $_POST['old_secret'], $_POST['new_secret']);
		if($res){
			$res = $db->user_login($_POST['username'], $_POST['new_secret']);
			if($res){
				session_start();
				$_SESSION['user_id'] = $res['id'];
				$_SESSION['username'] = $res['username'];
				$res = json_encode($res);
			}
		}
		track('Change password attempt');
	}
	echo $res;
?>
