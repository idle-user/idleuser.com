<?php
	require_once getenv('APP_PATH') . '/src/session.php';
	if (empty($_POST['match_id']) || empty($_POST['rating']) || !$_SESSION['loggedin']) {
		header("Location: /projects/matches");
		exit();
	}
	$response['success'] = False;
	$match = $db->match($_POST['match_id']);
	if($match['date']==date("Y-m-d") && $match['team_won']!=0){
		$user_rating = $db->user_match_rating($_SESSION['profile']['id'], $_POST['match_id']);
		$response = array();
		if($user_rating && $_POST['rating'] == $user_rating['rate']){
			$db->user_rate_match($_SESSION['profile']['id'], $_POST['match_id'], 0);
			$response['success'] = True;
			$response['message'] = 'Match rating has been removed';
		} else {
			if($db->user_rate_match($_SESSION['profile']['id'], $_POST['match_id'], $_POST['rating'])){
				$response['success'] = True;
				$response['message'] = 'Match rating updated';
			} else {
				$response['message'] = 'Something broke, man. Please let me know so I can check on it.';
			}
		}
	} else {
		$response['message'] = 'Unable to rate matches past event date or that are pending.';
	}
	echo json_encode($response);
?>
