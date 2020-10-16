 <?php
	require_once('/srv/http/src/session.php');
	$data = $db->get_quote();
	echo json_encode($data);
?>

