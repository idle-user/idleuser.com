<?php
	exit();
	require_once('/srv/http/src/session.php');
	if(empty($_POST['name'])||empty($_POST['email'])||empty($_POST['subject'])||empty($_POST['message'])){
		redirect_back();
	}

	$name = trim($_POST['name']);
	$email = trim($_POST['email']);
	$subject = trim($_POST['subject']);
	$message = trim($_POST['message']);
	$ip = getIP();

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
		redirect_back();
	}

	$subject = $subject.' - '.$_SESSION['user_id'];

	$db->connect();
	$res = $db->add_feedback($ip, $name, $email, $subject, $message);
	echo $res;
?>
