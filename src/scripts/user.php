<?php
function has_privilege($privilege) {
	global $_DB;
	if(!isset($_SESSION['ADMIN_USER'])) return null;
	
	$db = DB::connect($_DB['HOST'], $_DB['READ_ADMIN_LOGIN']['USER'], $_DB['READ_ADMIN_LOGIN']['PASS'], $_DB['DATABASE']);
	$db->prepare("getPermissions", "SELECT Permissions FROM `Admin_Login` WHERE ID=? LIMIT 1");
	$db->param("getPermissions", "i", $_SESSION['ADMIN_USER']);
	$result = $db->execute("getPermissions");
	if($result) {
		if($result[0]['Permissions'] == "")
			return false;
		else if($result[0]['Permissions'] == "*")
			return true;
		else {
			$permissions = str_split($privilege);
			foreach($permissions as $permission) {
				if(strpos($result[0]['Permissions'], $permission) === FALSE)
					return false;
			}
			return true;
		}
	}
	else
		return null;
}

function deny_on($privilege) {
	if(!has_privilege($privilege)) {
		require_once("{$_SERVER['DOCUMENT_ROOT']}/src/denied.php");
		exit;
	}
}