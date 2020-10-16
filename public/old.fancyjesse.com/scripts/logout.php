<?php
	require_once('/srv/http/src/session.php');
	logout();
?>
Successfully logged out. Redirecting ...
<script>
setTimeout(function(){
	window.history.back();
}, 2000);
</script>
