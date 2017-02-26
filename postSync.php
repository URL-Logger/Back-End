<?php
require_once("lib/db.php");
require_once("lib/secure.php");
require_once("msc/database.php");

$partid = (isset($_POST['partid']))? $_POST['partid'] : null;
$timestamp = (isset($_POST['Timestamp']))? $_POST['Timestamp'] : null;

if($partid !== null && $timestamp !== null) {
	$db = DB::connect(
		$_CONNECTION['LOGIN']['HOST'],
		$_CONNECTION['LOGIN']['USER'], 
		$_CONNECTION['LOGIN']['PASS'], 
		$_CONNECTION['LOGIN']['BASE']
	);  
	
	if($db === null) die("failed");
	if( !$db->prepare("postSync", 
		"UPDATE `User_Data` SET User_LastSync=? WHERE ParticipantID=?"))
			die($db->error());
	$db->param("postSync", "s", $timestamp);	
	$db->param("postSync", "s", $partid);
	
	if( $db->execute("postSync") === false )
		die("FAILED");
	print "SUCCESS";
}
else
	print "BAD_PARAMS";
