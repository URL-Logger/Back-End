<?php
include("../lib/db.php");
require_once("../lib/secure.php");
require_once("../msc/database.php");

$db = DB::connect("localhost", "root", "", "SmartRevenue");

if(! $db->prepare("DropUserLogin", "DROP TABLE IF EXISTS `User_Login`;")) die($db->error());
if(! $db->prepare("DropUserInfo", "DROP TABLE IF EXISTS `User_Info`;")) die($db->error());
if(! $db->prepare("DropAdminLogin", "DROP TABLE IF EXISTS `Admin_Login`;")) die($db->error());
if(! $db->prepare("DropSecuritySalt", "DROP TABLE IF EXISTS `Security_Salt`;")) die($db->error());
if(! $db->prepare("DropCollectionChrome", "DROP TABLE IF EXISTS `Collection_Chrome`;")) die($db->error());
if(! $db->prepare("DropCollectionAndroid", "DROP TABLE IF EXISTS `Collection_Android`;")) die($db->error());
if(! $db->prepare("DropOpportunities", "DROP TABLE IF EXISTS `Opportunities`;")) die($db->error());
if(! $db->prepare("DropOpportunitiesRespondents", "DROP TABLE IF EXISTS `Opportunities_Respondents`;")) die($db->error());

if(! $db->prepare("RemoveReadUserLogin", "DROP USER 'read_user_login'@'localhost';")) print $db->error();
if(! $db->prepare("RemoveReadUserInfo", "DROP USER 'read_user_info'@'localhost';")) print $db->error();
if(! $db->prepare("RemoveUpdateUserInfo", "DROP USER 'update_user_info'@'localhost';")) print $db->error();
if(! $db->prepare("RemoveReadAdminLogin", "DROP USER 'read_admin_login'@'localhost';")) print $db->error();
if(! $db->prepare("RemoveUpdateAdminLogin", "DROP USER 'update_admin_login'@'localhost';")) print $db->error();
if(! $db->prepare("RemoveReadCollection", "DROP USER 'read_collection'@'localhost';")) print $db->error();
if(! $db->prepare("RemoveWriteCollection", "DROP USER 'write_collection'@'localhost';")) print $db->error();
if(! $db->prepare("RemoveReadSecurityLogin", "DROP USER 'read_security_login'@'localhost';")) print $db->error();
if(! $db->prepare("RemoveUpdateSecurityLogin", "DROP USER 'update_security_login'@'localhost';")) print $db->error();

$db->execute("DropUserLogin");
$db->execute("DropUserInfo");
$db->execute("DropAdminLogin");
$db->execute("DropSecuritySalt");
$db->execute("DropCollectionChrome");
$db->execute("DropCollectionAndroid");
$db->execute("DropOpportunities");
$db->execute("DropOpportunitiesRespondents");

$db->execute("RemoveReadUserLogin");
$db->execute("RemoveReadUserInfo");
$db->execute("RemoveUpdateUserInfo");
$db->execute("RemoveReadAdminLogin");
$db->execute("RemoveUpdateAdminLogin");
$db->execute("RemoveReadCollection");
$db->execute("RemoveWriteCollection");
$db->execute("RemoveReadSecurityLogin");
$db->execute("RemoveUpdateSecurityLogin");