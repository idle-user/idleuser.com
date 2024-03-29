<?php
require_once getenv('APP_PATH') . '/src/session.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['profile']['id']) || empty($_POST['color_id'])) {
    header("Location: /projects/led-vote");
    exit();
}

$success = $db->led_vote($_SESSION['profile']['id'], $_POST['color_id']);

try {
    $userData = array('user_id' => $_SESSION['profile']['id'], 'username' => $_SESSION['profile']['username'], 'type' => 'led', 'data' => $_POST['color_id']);
    $disconnect = array('user_id' => $_SESSION['profile']['id'], 'type' => 'request', 'data' => 'disconnect');
    $host = '192.168.1.13';
    $port = 1330;
    $fp = @fsockopen($host, $port, $errno, $errstr, 1);
    if ($fp) {
        fwrite($fp, json_encode($userData));
        fgets($fp, 128);
        fwrite($fp, json_encode($disconnect));
        fgets($fp, 128);
        fclose($fp);
    }
} catch (Exception $e) {
}

?>
