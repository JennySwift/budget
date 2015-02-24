<?php
session_start();

if (isset($_GET['log_out'])) {
	$_SESSION['database_user_id'] = null;
	$_SESSION['database_username'] = null;
	$_SESSION['database_password'] = null;
	$_SESSION = array();
	session_unset();
	session_destroy();
	exit;
}
?>