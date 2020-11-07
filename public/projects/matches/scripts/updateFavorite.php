<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';
	if (empty($_POST['superstarID']) || empty($_SESSION['user_id'])) {
		error_log('Error updateFavorite - '.$_POST['superstarID'].' - '.$_SESSION['user_id']);
		header("Location: /projects/matches");
		exit();
	}
	$user = $db->user_stats($_SESSION['user_id']);
	$response = array();
	$response['refresh'] = false;
	if($_POST['superstarID'] == $user['favorite_superstar_id']){
		$response['message'] = $db->superstar($_POST['superstarID'])['name'].' is already set as your favorite.';
	} else {
		if($db->update_favorite_superstar($_SESSION['user_id'], $_POST['superstarID'])){
			$response['message'] = $db->superstar($_POST['superstarID'])['name'].' is now set as favorite.';
		} else {
			$response['message'] = 'Something broke, man. Please let me know so I can check on it.';
		}
	}
	echo json_encode($response);
?>
