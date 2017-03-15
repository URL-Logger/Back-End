<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/lib/db.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/lib/secure.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/msc/database.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/msc/users.php");

$db = DB::connect($_DB['HOST'], $_DB['ROOT']['USER'], $_DB['ROOT']['PASS'], $_DB['DATABASE']);

if(! $db->prepare("DropUserLogin", "DROP TABLE IF EXISTS `User_Login`;")) die($db->error());
if(! $db->prepare("DropUserInfo", "DROP TABLE IF EXISTS `User_Info`;")) die($db->error());
if(! $db->prepare("DropAdminLogin", "DROP TABLE IF EXISTS `Admin_Login`;")) die($db->error());
if(! $db->prepare("DropSecuritySalt", "DROP TABLE IF EXISTS `Security_Salt`;")) die($db->error());
if(! $db->prepare("DropSecurityRecover", "DROP TABLE IF EXISTS `Security_Recover`;")) die($db->error());
if(! $db->prepare("DropSecurityRecoverAdmin", "DROP TABLE IF EXISTS `Security_Recover_Admin`;")) die($db->error());
if(! $db->prepare("DropCollectionChrome", "DROP TABLE IF EXISTS `Collection_Chrome`;")) die($db->error());
if(! $db->prepare("DropCollectionAndroid", "DROP TABLE IF EXISTS `Collection_Android`;")) die($db->error());
if(! $db->prepare("DropOpportunities", "DROP TABLE IF EXISTS `Opportunities`;")) die($db->error());
if(! $db->prepare("DropOpportunitiesRespondents", "DROP TABLE IF EXISTS `Opportunities_Respondents`;")) die($db->error());

$db->execute("DropUserLogin");
$db->execute("DropUserInfo");
$db->execute("DropAdminLogin");
$db->execute("DropSecuritySalt");
$db->execute("DropSecurityRecover");
$db->execute("DropSecurityRecoverAdmin");
$db->execute("DropCollectionChrome");
$db->execute("DropCollectionAndroid");
$db->execute("DropOpportunities");
$db->execute("DropOpportunitiesRespondents");

foreach($_DB_USERS as $user=>$props) {
	foreach($props['Allow'] as $allow)
		$db->query("DROP USER '{$user}'@'{$allow}'");
}