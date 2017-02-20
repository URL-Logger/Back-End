<?php
require_once("lib/db.php");
require_once("lib/secure.php");
require_once("msc/database.php");

$user = (isset($_POST['user']))? $_POST['user'] : null;
$pass = (isset($_POST['pass']))? $_POST['pass'] : null;

$db = DB::connect(
	$_CONNECTION['LOGIN']['HOST'],
	$_CONNECTION['LOGIN']['USER'], 
	$_CONNECTION['LOGIN']['PASS'], 
	$_CONNECTION['LOGIN']['BASE']
);

if($db === null)
	die("failed");

if( !$db->prepare("getUser", "SELECT ParticipantID, User_Name, User_Pass FROM `User_Data` WHERE User_Name=? LIMIT 1") )
	die($db->error());
$db->param("getUser", "s", $user);
$result = $db->execute("getUser");

if($user && $pass) {
	if($result !== null) {
		if(encrypt_password($user, $pass) == $result[0]['User_Pass'])
			print $result[0]['ParticipantID'];
		else
			print "INVALID_PASS";
	}
	else
		print "INVALID_USER";
}
else
	print "BAD_PARAMS";