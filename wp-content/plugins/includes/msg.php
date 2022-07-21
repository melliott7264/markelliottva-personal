<?php
// Check for messages in SESSION
 
if (isset($_SESSION['msg_error'])) {
	if (isset($msg_error))
		$msg_error = array_merge_recursive($msg_error,$_SESSION['msg_error']);
	else 
		$msg_error = $_SESSION['msg_error'];
	unset($_SESSION['msg_error']);
}
if (isset($_SESSION['msg_notify'])) {
	if (isset($msg_notify))
		$msg_notify = array_merge_recursive($msg_notify,$_SESSION['msg_notify']);
	else 
		$msg_notify = $_SESSION['msg_notify'];
	unset($_SESSION['msg_notify']);
}

include( WPPostsRateKeys::$template_dir . '/includes/msg.php');