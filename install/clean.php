<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/lib/db.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/misc/database.php");

$db = DB::connect($_DB['HOST'], $_DB['ROOT']['USER'], $_DB['ROOT']['PASS'], $_DB['DATABASE']);

if(isset($_GET['all']) || isset($_GET['tables'])) {
	if(! $db->prepare("DropAdminLogin", "DROP TABLE IF EXISTS `Admin_Login`;")) die($db->error());
	if(! $db->prepare("DropSecuritySalt", "DROP TABLE IF EXISTS `Security_Salt`;")) die($db->error());
	if(! $db->prepare("DropCollectionChrome", "DROP TABLE IF EXISTS `Collection_Chrome`;")) die($db->error());
	if(! $db->prepare("DropCollectionAndroid", "DROP TABLE IF EXISTS `Collection_Android`;")) die($db->error());
	
	//$db->execute("DropAdminLogin");
	$db->execute("DropSecuritySalt");
	//$db->execute("DropCollectionChrome");
	//$db->execute("DropCollectionAndroid");
}

if(isset($_GET['all']) || isset($_GET['users'])) {
	foreach($_DB_USERS as $user=>$props) {
		foreach($props['Allow'] as $allow)
			$db->query("DROP USER '{$user}'@'{$allow}'");
	}
}