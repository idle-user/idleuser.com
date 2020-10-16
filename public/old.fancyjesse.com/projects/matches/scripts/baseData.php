<?php
	require_once('/srv/http/src/session.php');

	$tables = $db->matches_base_data();
	$_SESSION['data'] = [
		'brand' => $tables['brand'],
		'title' => $tables['title'],
		'match_type' => $tables['match_type'],
		'stable' => $tables['stable'],
	];
	foreach($_SESSION['data']['stable'] as $stable){
       		$_SESSION['data']['stable'][$stable['id']]['members'] = $db->stable_members($stable['id']);
	}
?>
