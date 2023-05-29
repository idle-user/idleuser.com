<?php
ini_set('error_reporting', E_ALL);
ini_set('session.cookie_lifetime', 3600 * 24 * 7);
ini_set('session.gc-maxlifetime', 3600 * 24 * 7);
date_default_timezone_set(getenv('TZ') ?? date_default_timezone_get());
session_start();

require_once 'db.php';
require_once 'tools.php';

$db = new MYSQLHandler();

$_SESSION['loggedin'] = isset($_SESSION['profile']);

login_token_check();
check_auth();

track();