 <?php
	require_once getenv('APP_PATH') . '/src/session.php';
	$data = $db->get_quote();
	echo json_encode($data);
?>

