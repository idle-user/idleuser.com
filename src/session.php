<?php
	ini_set('error_reporting', E_ALL);
	ini_set('session.cookie_lifetime', 3600 * 24 * 7);
	ini_set('session.gc-maxlifetime', 3600 * 24 * 7);
	session_start();
	require_once('db.php');
	require_once('tools.php');

	token_login_check();
	defaults_check();
	track();
?>
