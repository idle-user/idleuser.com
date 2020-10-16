<?php
	require_once('baseData.php');
	if (empty($_POST['match_id']) || empty($_POST['superstar']) || !isset($_POST['bet']) || empty($_SESSION['user_id'])) {
		echo 'error';
		header("Location: /projects/matches");
		exit();
	}
	$response = array();
	$response['success'] = false;
	if($_POST['bet'] == 0){
		$response['message'] = "You can't bet 0.";
	} else if($_POST['bet'] < 0){
		$response['message'] = "Nice try.";
	} else {
		$match = $db->match($_POST['match_id']);
		$user = $db->user_stats($_SESSION['user_id']);
		if(!$match['bet_open']){
			$response['message'] = "Open bets for this match are closed.";
		} else if($user['s4_available_points'] < $_POST['bet']){
				$response['message'] = "You do not have enough points to place this bet.";
		} else {
			try {
				$s = explode(', ',$_POST['superstar'])[0];
				$superstar_info = $db->superstar_info($s);
				$team = 0;
				foreach($db->match_contestants($match['id']) as $contestant){
					if($contestant['superstar_id']==$superstar_info['id']){
						$team = $contestant['team'];
						break;
					}
				}
				if(!$superstar_info || !$team){
					$response['message'] = "Unable to find necessary information for ".$_POST['superstar'];
				} else if($db->add_user_bet($_SESSION['user_id'], $_POST['match_id'], $team, $_POST['bet'])){
					$response['message'] = number_format($_POST['bet'])." point bet placed on ".$_POST['superstar'].". Good luck!";
					$response['success'] = true;
				} else {
					$response['message'] = "Something broke, man. You probably already voted for this match and I haven't handled this yet. If not, Please let me know so I can check on it.";
				}
			} catch (Exception $e){
				 $response['message'] = "Unable to Connect to Database. Either I'm working on something or it broke. Try again or contact me. Thanks.";
			}
		}
	}
	echo json_encode($response);
?>
