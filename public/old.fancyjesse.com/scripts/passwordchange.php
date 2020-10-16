<?php
	require_once('/srv/http/src/session.php');
	if(empty($_SESSION['user_id'])||empty($_SESSION['username'])){
		redirect_back();
	}
	
	$project = '';
	if(isset($_GET['project']) && !empty($_GET['project'])){
		$project = htmlspecialchars($_GET['project']);
	}

	$res = $db->connect();
	if($res){
		$temp_pw = $db->user_update_temp_secret($_SESSION['user_id'], $_SESSION['username']);
		if($temp_pw){
			header("Location: /account.php?temp_pw=$temp_pw&project=$project");
			exit();
		}
	}
	echo $res;
?>
