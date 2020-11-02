<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';
	if(empty($_SESSION['user_id'])) {
		header("Location: /projects/matches");
		exit();
	}
	$response = array();
	$response['success'] = false;
	$_POST['email'] = trim($_POST['email']);
	if(empty($_POST['email'])){
		$_POST['email'] = null;
	}

	if(! (empty($_POST['email']) || filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) ){
		$response['message'] = 'Invalid Email';
		echo json_encode($response);
		exit();
	}

	if($db->user_email_link($_SESSION['user_id'], $_POST['email'])){
		if(empty($_POST['email'])){
			$response['message'] = 'Email removed.';
		} else {
			$response['message'] = 'Email Updated.';
		}
		$response['success'] = true;
	} else {
		$response['message'] = 'Something broke, man. Please let me know so I can check on it.';
	}

	echo json_encode($response);
?>
