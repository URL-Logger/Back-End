<?php
if(!isset($_SESSION['User'])) {
	require("login.php");
	exit;
}