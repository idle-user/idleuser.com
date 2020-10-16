<?php
	require_once('/srv/http/src/session.php');
	if(!$_SESSION['loggedin']) {
		header("Location: /login.php");
		exit();
	}
	$response = array();
	$response['success'] = false;
	$_POST['chatango_id'] = trim($_POST['chatango_id']);
	if(empty($_POST['chatango_id'])){
		$_POST['chatango_id'] = null;
	}

	if($db->user_chatango_link($_SESSION['user_id'], $_POST['chatango_id'])){
		if(empty($_POST['chatango_id'])){
			$response['message'] = 'Chatango ID removed.';
		} else{
			$response['message'] = 'Chatango ID updated. Please confirm by entering "!verify" on Chatango.';
		}
		$response['success'] = true;
	} else {
		$response['message'] = 'Something broke, man. Please let me know so I can check on it.';
	}

	echo json_encode($response);
?>
