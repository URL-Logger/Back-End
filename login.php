<?php
require_once("src/lib/db.php");
require_once("src/scripts/secure.php");
require_once("src/misc/database.php");

$user = (isset($_POST['user']))? $_POST['user'] : null;
$pass = (isset($_POST['pass']))? $_POST['pass'] : null;

$db = DB::connect($_DB['HOST'], $_DB['READ_USER_LOGIN']['USER'], $_DB['READ_USER_LOGIN']['PASS'], $_DB['DATABASE']);
if($db === null) die("failed");

if( !$db->prepare("getUser", "SELECT ID, Email, Password FROM `User_Login` WHERE Email=? LIMIT 1") )
	die($db->error());
$db->param("getUser", "s", $user);
$result = $db->execute("getUser");

if($user !== null && $pass !== null) {
	if($result !== null) {
		if($pass == $result[0]['Password'])
			echo $result[0]['ID'];
		else echo 0;
	} else echo 0;
}