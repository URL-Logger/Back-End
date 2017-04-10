<?php
$db = DB::connect();
$_USER_INFO = 

function admin_get_name() {
	if(isset($_SESSION['user'])) {
		$db = DB::connect();
	}
	else
		return null;
}

function admin_get_permissions() {
	
}