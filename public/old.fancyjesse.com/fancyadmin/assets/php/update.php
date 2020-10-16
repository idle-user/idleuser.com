<?php
	if(empty($_POST['data'])){
		header("Location: /fancyadmin");
		exit();
	}
	require_once('admin.php');
	$response = array();
	if($db->connect()){
		$data = $_POST['data'];
		if(empty($data['id'])){
			// add new
			switch ($data['table']){
				case 'title_table':
					$response['success'] = $db->add_title($data['name']);
					$response['message'] = 'Title "'.$data['name'].'" added';
					break;
				case 'brand_table':
					$response['success'] = $db->add_brand($data['name']);
					$response['message'] = 'Brand "'.$data['name'].'" added';
					break;
				case 'match_type_table':
					$response['success'] = $db->add_match_type($data['name']);
					$response['message'] = 'Match Type "'.$data['name'].'" added';
					break;
				case 'event_table':
					$response['success'] = $db->add_event($data['date_time'], $data['name'], $data['ppv']);
					$response['message'] = 'Event "'.$data['name'].'" added';
					break;
				case 'stable_table':
					$response['success'] = $db->add_stable($data['name']);
					$response['message'] = 'Stable "'.$data['name'].'" added';
					break;
				case 'superstar_table':
					$response['success'] = $db->add_superstar($data['name'], $data['brand_id'], $data['height'], $data['weight'], $data['hometown'], $data['dob'], '', $data['page_url'], '', $data['bio']);
					$response['message'] = 'Superstar "'.$data['name'].'" added';
					break;
				/*
				case 'match_table':
					$response['success'] = $db->add_match($data['date'], $data['title_id'], $data['match_type_id'], $data['match_note'], $data['team_won'], $data['winner_note'], $data['bet_open'], $data['bet_multiplier']);
					$response['message'] = 'Match Added';
					break;
				case 'contestant_table':
					$response['success'] = $db->add_match_contestant($data['match_id'], $data['superstar_id'], $data['team']);
					$response['message'] = 'Contestant['.$data['superstar_id'].'] added to Match'.$data['match_id'];
					break;
				*/
			}
		} else {
			// update
			switch ($data['table']){
				case 'title_table':
					$response['success'] = $db->update_title($data['id'], $data['name']);
					$response['message'] = $data['name'].'['.$data['id'].'] now set to ['.$data['superstar_id'].']';
					break;
				case 'brand_table':
					$response['success'] = $db->update_brand($data['id'], $data['name']);
					$response['message'] = '['.$data['id'].'] renamed to '.$data['name'];
					break;
				case 'match_type_table':
					$response['success'] = $db->update_match_type($data['id'], $data['name']);
					$response['message'] = 'Match_Type['.$data['id'].'] renamed to '.$data['name'];
					break;
				case 'event_table':
					$response['success'] = $db->update_event($data['id'], $data['date_time'], $data['name'], $data['ppv']);
					$response['message'] = 'Event ['.$data['id'].'] updated';
					break;
				case 'stable_table':
					$response['success'] = $db->update_stable($data['id'], $data['name']);
					if($response['success']){
						$response['success'] = $db->remove_all_stable_members($data['id']);
						if($response['success']){
							foreach($data['members'] as $member){
								if(!$response['success'])
									break;
								if(!$member)
									continue;
								$response['success'] = $db->add_stable_member($data['id'], $member);
							}
						}
					}
					$response['message'] = 'Stable['.$data['id'].'] '.$data['name'].' updated';
					break;
				case 'superstar_table':
					$response['success'] = $db->update_superstar($data['id'], $data['name'], $data['brand_id'], $data['height'], $data['weight'], $data['hometown'], $data['dob'], $data['signature_move'], $data['page_url'], $data['image_url'], $data['bio']);
					$response['message'] = $data['name'].'['.$data['id'].'] updated';
					break;
				/*
				case 'match_table':
					$response['success'] = $db->update_match($data['id'], $data['date'], $data['title_id'], $data['match_type_id'], $data['match_note'], $data['team_won'], $data['winner_note'], $data['bet_open'], $data['bet_multiplier']);
					$response['message'] = 'Match['.$data['id'].'] updated';
					break;
				case 'contestant_table':
					$response['success'] = $db->update_match_contestant($data['id'], $data['match_id'], $data['superstar_id'], $data['team']);
					$response['message'] = 'Contestant['.$data['id'].'] updated';
					break;
				*/
			}
		}
		if(!$response['success']){
			$response['message'] = 'ERROR - Unable to Handle Request';
		}
	} else {
		$response['success'] = false;
		$response['message'] = 'Unable to Connect to Database';
	}
	echo json_encode($response);
?>
