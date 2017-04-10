<?php
ob_start();
session_start();

require_once("{$_SERVER['DOCUMENT_ROOT']}/src/lib/db.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/misc/database.php");

if(empty($_SESSION['ADMIN_USER'])) {
	require_once("{$_SERVER['DOCUMENT_ROOT']}/src/login.php");
	exit;
} else {
	if(isset($_GET['logout'])) {
		unset($_SESSION['ADMIN_USER']);
		header("Location: /");
		exit;
	}
}