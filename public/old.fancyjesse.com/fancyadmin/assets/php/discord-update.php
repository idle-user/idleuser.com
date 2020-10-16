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
				case 'command_table':
					$data['response'] = trim($data['response']);
					$response['success'] = $db->add_discord_command($data['command'], $data['response']);
					$response['message'] = 'Command added';
					break;
				case 'scheduler_table':
					$response['success'] = $db->add_discord_schedule(
						$data['name'],
						$data['description'],
						$data['message'],
						$data['tweet'],
						$data['sunFlag'],
						$data['sunTime'],
						$data['monFlag'],
						$data['monTime'],
						$data['tueFlag'],
						$data['tueTime'],
						$data['wedFlag'],
						$data['wedTime'],
						$data['thuFlag'],
						$data['thuTime'],
						$data['friFlag'],
						$data['friTime'],
						$data['satFlag'],
						$data['satTime'],
						$data['active']
					);
					$response['message'] = 'Schedule added';
					break;
			}
		} else {
			// update
			switch ($data['table']){
				case 'command_table':
					$data['response'] = trim($data['response']);
					$response['success'] = $db->update_discord_command($data['id'], $data['command'], $data['response']);
					$response['message'] = 'Command updated';
					break;
				case 'scheduler_table':
					$response['success'] = $db->updated_discord_schedule(
						$data['id'],
						$data['name'],
						$data['description'],
						$data['message'],
						$data['tweet'],
						$data['sunFlag'],
						$data['sunTime'],
						$data['monFlag'],
						$data['monTime'],
						$data['tueFlag'],
						$data['tueTime'],
						$data['wedFlag'],
						$data['wedTime'],
						$data['thuFlag'],
						$data['thuTime'],
						$data['friFlag'],
						$data['friTime'],
						$data['satFlag'],
						$data['satTime'],
						$data['active']
					);
					$response['message'] = 'Schedule updated';
					break;
			}
		}
		if(!$response['success']){
			$response['message'] = 'ERROR - Unable to handle request';
		}
	} else {
		$response['success'] = false;
		$response['message'] = 'Unable to connect to database';
	}
	echo json_encode($response);
?>
