<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/lib/db.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/scripts/secure.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/misc/database.php");

$db = DB::connect($_DB['HOST'], $_DB['ROOT']['USER'], $_DB['ROOT']['PASS'], $_DB['DATABASE']);

//if(! $db->prepare("DropUserLogin", "DROP TABLE IF EXISTS `User_Login`;")) die($db->error());
if(! $db->prepare("DropAdminLogin", "DROP TABLE IF EXISTS `Admin_Login`;")) die($db->error());
if(! $db->prepare("DropSecuritySalt", "DROP TABLE IF EXISTS `Security_Salt`;")) die($db->error());
if(! $db->prepare("DropSecurityRecover", "DROP TABLE IF EXISTS `Security_Recover`;")) die($db->error());
if(! $db->prepare("DropSecurityRecoverAdmin", "DROP TABLE IF EXISTS `Security_Recover_Admin`;")) die($db->error());
if(! $db->prepare("DropCollectionChrome", "DROP TABLE IF EXISTS `Collection_Chrome`;")) die($db->error());
if(! $db->prepare("DropCollectionAndroid", "DROP TABLE IF EXISTS `Collection_Android`;")) die($db->error());

//$db->execute("DropUserLogin");
$db->execute("DropAdminLogin");
$db->execute("DropSecuritySalt");
$db->execute("DropSecurityRecover");
$db->execute("DropSecurityRecoverAdmin");
$db->execute("DropCollectionChrome");
$db->execute("DropCollectionAndroid");

foreach($_DB_USERS as $user=>$props) {
	foreach($props['Allow'] as $allow)
		$db->query("DROP USER '{$user}'@'{$allow}'");
}