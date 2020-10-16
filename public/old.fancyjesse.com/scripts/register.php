<?php
	require_once('/srv/http/src/session.php');
	if(empty($_POST['username'])||empty($_POST['secret'])||empty($_POST['secret_verify'])){
		redirect_back();
	}
	if(!preg_match('/^[a-zA-Z_\-\d]+$/i',$_POST['username']) || $_POST['secret'] != $_POST['secret_verify']){
		echo false;
		exit();
	}
	$res = $db->connect();
	if($res){
		$res = $db->user_register($_POST['username'], $_POST['secret']);
		if($res){
			session_start();
			$_SESSION['user_id'] = $res['id'];
			$_SESSION['username'] = $res['username'];
			$res = json_encode($res);
		}
		track('Register attempt');
	}
	echo $res;
?>
