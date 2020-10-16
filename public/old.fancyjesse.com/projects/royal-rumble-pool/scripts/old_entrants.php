<?php
	$pythonScript = '/home/website/royal-rumble-pool/royalrumblepool.py';
	$name = trim($_POST['entry_name']);
	$comment = empty($_POST['entry_comment']) ? '' : trim($_POST['entry_comment']);
	$cmd = 'python3 ' .
		$pythonScript .
		' -d ';

	$output = shell_exec($cmd);
	echo $output;
?>
