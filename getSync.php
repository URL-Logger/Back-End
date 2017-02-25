<?php
require_once("lib/db.php");
require_once("lib/secure.php");
require_once("msc/database.php");

$partid = (isset($_POST['partid']))? $_POST['partid'] : null;

$db = DB::connect(
	$_CONNECTION['LOGIN']['HOST'],
	$_CONNECTION['LOGIN']['USER'], 
	$_CONNECTION['LOGIN']['PASS'], 
	$_CONNECTION['LOGIN']['BASE']
);

if($db === null)
	die("failed");

if( !$db->prepare("getSync", 
	"SELECT User_LastSync FROM `User_Data` WHERE ParticipantID=? LIMIT 1") )
		die($db->error());
$db->param("getSync", "s", $partid);
$result = $db->execute("getSync");

if($partid) {
	if($result !== null) {
		print $result[0]['User_LastSync'];
	}
	else
		print "INVALID_PARTICIPANTID";
}
else
	print "BAD_PARAMS";

