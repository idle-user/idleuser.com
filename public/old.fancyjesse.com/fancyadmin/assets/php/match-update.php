<?php
	require_once('/srv/http/src/session.php');
	if(empty($_POST['data'])){
		header("Location: /fancyadmin");
		exit();
	}

	$response = array();
	if($db->connect()){
		$data = $_POST['data'];
		if(empty($data['id'])){
			$response['success'] = $db->add_match($data['event_id'], $data['title_id'], $data['match_type_id'], $data['match_note'], $data['team_won'], $data['winner_note'], $data['bet_open'], $_SESSION['user_id']);
			if($response['success']){
				$data['id'] = $response['success'];
				$response['message'] = 'Match Added - '.$data['id'];
				$response['match_id'] = $data['id'];
			}
		} else {
			$response['success'] = $db->update_match($data['id'], $data['event_id'], $data['title_id'], $data['match_type_id'], $data['match_note'], $data['team_won'], $data['winner_note'], $data['bet_open'], $_SESSION['user_id']);
			$response['success'] = $db->remove_all_match_contestants($data['id']);
			$response['message'] = 'Match Updated';
			$response['match_id'] = $data['id'];
		}
		if(isset($data['contestant']))
		foreach($data['contestant'] as $contestant){
			if(!$response['success']){
				$response['message'] = 'ERROR - Unable to Handle Request';
				break;
			}
			$response['success'] = $db->add_match_contestant($data['id'], $contestant['superstar_id'], $contestant['team'], $contestant['bet_multiplier']);
		}
	} else {
		$response['success'] = false;
		$response['message'] = 'Unable to Connect to Database';
		$response['match_id'] = 0;
	}
	$response['error'] = $db->get_err();
	echo json_encode($response);
?>
