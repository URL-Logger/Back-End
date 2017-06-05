<?php
require_once("src/lib/db.php");
require_once("src/scripts/secure.php");
require_once("src/misc/database.php");

$user = (isset($_REQUEST['user']))? $_REQUEST['user'] : null;
$pass = (isset($_REQUEST['pass']))? $_REQUEST['pass'] : null;

if($user !== null && $pass !== null) {
	$DBSalt = $_DB['READ_USER_LOGIN'];
	$db = DB::connect($_DB['HOST'], $DBSalt['USER'], $DBSalt['PASS'], $_DB['DATABASE']);
	if($db === null) die("failed");

	if( !$db->prepare("getUser", "SELECT ID, Email, Password FROM `User_Login` WHERE Email=? LIMIT 1") )
		die($db->error());
	$db->param("getUser", "s", $user);
	$result = $db->execute("getUser");
	
	if($result)
		echo password_verify($pass, $result[0]['Password'])? $result[0]['ID'] : 0;
	else
		echo 0;
}