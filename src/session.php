<?php
	ini_set('error_reporting', E_ALL);
	ini_set('session.cookie_lifetime', 3600 * 24 * 7);
	ini_set('session.gc-maxlifetime', 3600 * 24 * 7);
	session_start();

	require_once 'config.php';
	require_once 'db.php';
	require_once 'tools.php';

	date_default_timezone_set($configs['TIMEZONE']);

	$db = new MYSQLHandler($configs);
	$db->connect();

	if(!isset($_SESSION['loggedin'])){
		$_SESSION['loggedin'] = false;
		$_SESSION['user_id'] = null;
		$_SESSION['username'] = null;
		$_SESSION['access'] = 1;
		$_SESSION['auth_token'] = false;
		$_SESSION['auth_token_exp'] = false;
	}

	login_token_check();
	track();
?>
