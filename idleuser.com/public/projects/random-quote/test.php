 <?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php';
	$data = $db->get_quote();
	echo json_encode($data);
?>

