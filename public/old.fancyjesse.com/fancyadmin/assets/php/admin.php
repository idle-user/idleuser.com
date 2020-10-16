<?php
	require_once('/srv/http/src/session.php');
	if($_SESSION['user_id'] == 0){
		include('login.php');
		exit();
	} else {
		if($db->connect()){
			if($db->user_info($_SESSION['user_id'])['access']<2){
				echo 'Access Denied.';
				exit();
			}
		}
	}
?>
