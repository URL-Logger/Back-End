<?php
require_once("src/lib/db.php");
require_once("src/scripts/secure.php");
require_once("src/misc/database.php");

$partid = (isset($_POST['partid']))? $_POST['partid'] : null;

$db = DB::connect($_DB['HOST'], $_DB['READ_USER_LOGIN']['USER'], $_DB['READ_USER_LOGIN']['PASS'], $_DB['DATABASE']);

if($db === null)
	die("failed");

if( !$db->prepare("getSync",
	"SELECT LastSync FROM `User_Login` WHERE ID=? LIMIT 1") )
		die($db->error());
$db->param("getSync", "s", $partid);
$result = $db->execute("getSync");

if($partid) {
	if($result !== null) {
		print $result[0]['LastSync'];
	}
	else
		print "INVALID_PARTICIPANTID";
}
else
	print "BAD_PARAMS";

