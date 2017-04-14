<?php
require_once("src/lib/db.php");
require_once("src/scripts/secure.php");
require_once("src/misc/database.php");

$partid = (isset($_POST['partid']))? $_POST['partid'] : null;
$timestamp = (isset($_POST['Timestamp']))? $_POST['Timestamp'] : null;

if($partid !== null && $timestamp !== null) {
	$db = DB::connect($_DB['HOST'], $_DB['WRITE_USER_INFO']['USER'], $_DB['WRITE_USER_INFO']['PASS'], $_DB['DATABASE']);
	echo $db->error();
	if($db === null) die("failed");
	if( !$db->prepare("postSync", "UPDATE `User_Login` SET LastSync=? WHERE ID=?"))
		die($db->error());
	$db->param("postSync", "s", $timestamp);
	$db->param("postSync", "i", $partid);
	
	if( $db->execute("postSync") === false )
		die("FAILED");
	print "SUCCESS";
}
else
	print "BAD_PARAMS";