<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';
	if(empty($_SESSION['user_id'])) {
		header("Location: /projects/matches");
		exit();
	}
	$response = array();
	$response['success'] = false;
	$_POST['discord_id'] = trim($_POST['discord_id']);
	if(empty($_POST['discord_id'])){
		$_POST['discord_id'] = null;
	}

	if(! (empty($_POST['discord_id']) || is_numeric($_POST['discord_id'])) ){
		$response['message'] = 'Invalid Discord ID';
		echo json_encode($response);
		exit();
	}

	if($db->user_discord_link($_SESSION['user_id'], $_POST['discord_id'])){
		if(empty($_POST['discord_id'])){
			$response['message'] = 'Discord ID removed.';
		} else {
			$response['message'] = 'Discord ID Updated. Please confirm by entering "!verify" on Discord.';
		}
		$response['success'] = true;
	} else {
		$response['message'] = 'Something broke, man. Please let me know so I can check on it.';
	}

	echo json_encode($response);
?>
