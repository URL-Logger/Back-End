<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/scripts/secure.php");

// Validate User/Password combination and login on success
function admin_login($user, $pass) {
	$_DBU = $_DB['READ_ADMIN_LOGIN'];
	$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
	$db->prepare("getAdminLogin", "SELECT ID, User, Password FROM `Admin_Login` WHERE User=? LIMIT 1");
	$db->param("getAdminLogin", "s", $user);
	$result = $db->execute();
	if($result) {
		if(password_verify(password_salt($pass), $result[0]['Password'])) {
			$_SESSION['ADMIN_USER'] = $result[0]['User'];
			return true;
		}
		else
			return false;
	}
	else
		return false;
}

// Validate Password of the current user
function admin_auth($pass) {
	if(!empty($_SESSION['ADMIN_USER'])) {
		$_DBU = $_DB['READ_ADMIN_LOGIN'];
		$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
		$db->prepare("getAdminAuth", "SELECT Password FROM `Admin_Login` WHERE ID=? LIMIT 1");
		$db->param("getAdminAuth", "s", $_SESSION['ADMIN_USER']);
		$result = $db->execute("getAdminAuth");
		if($result) {
			if(password_verify(password_salt($pass), $result[0]['Password'])) {
				$_SESSION['user'] = $result[0]['User'];
				return true;
			}
			else
				return false;
		}
		else
			return false;
	}
	else
		return null;
}