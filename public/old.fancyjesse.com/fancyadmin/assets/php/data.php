<?php
require_once('admin.php');
$res = $db->matches_base_data();
$res['event'] = $db->upcoming_events();
$res['superstar'] = $db->all_superstars();
//foreach($res['stable'] as $stable){
//	$res['stable'][$stable['id']]['members'] = $db->stable_members($stable['id']);
//}
// $res['match'] = $db->all_matches();
// $res['match_contestant'] = $db->all_match_contestants();
echo json_encode($res);
?>
