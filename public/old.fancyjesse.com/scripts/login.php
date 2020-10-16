<?php
	require_once('/srv/http/src/session.php');
	if(empty($_POST['username']) || empty($_POST['secret'])){
		redirect_back();
	}
	$res = $db->connect();
	if($res){
		$username = $_POST['username'];
		$res = $db->user_login($_POST['username'], $_POST['secret']);
		if($res){
			$_SESSION['user_id'] = $res['id'];
			$_SESSION['username'] = $res['username'];
			$username = $_SESSION['username'];
			$res = json_encode($res);
		}
 		track('login attempt');
	}
	echo $res;
?>
