<?php
require_once("src/lib/db.php");
require_once("src/scripts/secure.php");
require_once("src/misc/database.php");

$userid =     (isset($_POST['UserID']))? htmlspecialchars($_POST['UserID'], ENT_QUOTES) : null;
$appid =      (isset($_POST['AppID']))? htmlspecialchars($_POST['AppID'], ENT_QUOTES) : null;
$start =      (isset($_POST['StartTime']))? htmlspecialchars($_POST['StartTime'], ENT_QUOTES) : null;
$end =        (isset($_POST['EndTime']))? htmlspecialchars($_POST['EndTime'], ENT_QUOTES) : null;
$last =       (isset($_POST['LastTime']))? htmlspecialchars($_POST['LastTime'], ENT_QUOTES) : null;
$total =      (isset($_POST['TotalTime']))? htmlspecialchars($_POST['TotalTime'], ENT_QUOTES) : null;
$launch =      (isset($_POST['Launch']))? htmlspecialchars($_POST['Launch'], ENT_QUOTES) : null;

if($userid !== null
  && $appid !== null
  && $start !== null
  && $end !== null
  && $last !== null
  && $total !== null
  && $launch !== null) {
	$db = DB::connect($_DB['HOST'], $_DB['WRITE_COLLECTION']['USER'], $_DB['WRITE_COLLECTION']['PASS'], $_DB['DATABASE']);
	$db->prepare("postData", "INSERT INTO `Collection_Android` (
		UserID, AppID, StartTime, EndTime, LastTime, TotalTime, Launch)
		VALUES (?,?,?,?,?,?)");
	$db->param("postData", "s", $userid);
	$db->param("postData", "s", $appid);
	$db->param("postData", "s", $start);
	$db->param("postData", "s", $end);
	$db->param("postData", "s", $last);
	$db->param("postData", "s", $total);
	$db->param("postData", "s", $launch);
	if( $db->execute("postData") !== false )
		print "SUCCESS";
	else
		print "FAILURE";
}
else
	print "BAD_PARAMS";