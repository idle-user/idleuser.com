<?php
	require_once('admin.php');
	$res = $db->matches_base_data();
	$all_matches = $db->s4_matches();
	foreach($all_matches as $match){
		$all_matches[$match['id']]['contestants'] = $db->match_contestants($match['id']);
	}
	$res['event'] = $db->all_events();
	$res['superstar'] = $db->all_superstars();
	$res['match'] = $all_matches;
	$res['user_bets'] = $db->all_user_bets();
	echo json_encode($res);
?>
