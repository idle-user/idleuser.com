<?php
require_once('admin.php');
$res['commands'] = $db->all_discord_commands();
$res['scheduler'] = $db->all_discord_schedules();
echo json_encode($res);
?>
