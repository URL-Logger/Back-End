<?php
require_once("src/lib/db.php");
require_once("src/scripts/secure.php");
require_once("src/misc/database.php");

$userid =     (isset($_POST['UserID']))? htmlspecialchars($_POST['UserID'], ENT_QUOTES) : null;
$appid =      (isset($_POST['AppID']))? htmlspecialchars($_POST['AppID'], ENT_QUOTES) : null;
$timestamp =  (isset($_POST['Timestamp']))? htmlspecialchars($_POST['Timestamp'], ENT_QUOTES) : "";
$start =      (isset($_POST['StartTime']))? htmlspecialchars($_POST['StartTime'], ENT_QUOTES) : null;
$end =        (isset($_POST['EndTime']))? htmlspecialchars($_POST['EndTime'], ENT_QUOTES) : null;
$last =       (isset($_POST['LastTime']))? htmlspecialchars($_POST['LastTime'], ENT_QUOTES) : null;
$total =      (isset($_POST['TotalTime']))? htmlspecialchars($_POST['TotalTime'], ENT_QUOTES) : null;

if($userid !== null
  && $appid !== null
  && $start !== null
  && $end !== null
  && $last !== null
  && $total !== null) {
	$db = DB::connect($_DB['HOST'], $_DB['WRITE_COLLECTION']['USER'], $_DB['WRITE_COLLECTION']['PASS'], $_DB['DATABASE']);
	$db->prepare("postData", "INSERT INTO `Collection_Android` (
		UserID, Timestamp, AppID, StartTime, EndTime, LastTime, TotalTime)
		VALUES (?,?,?,?,?,?,?)");
	$db->param("postData", "i", $userid);
	$db->param("postData", "s", $timestamp);
	$db->param("postData", "s", $appid);
	$db->param("postData", "i", $start);
	$db->param("postData", "i", $end);
	$db->param("postData", "i", $last);
	$db->param("postData", "i", $total);
	if( $db->execute("postData") === false )
	print "SUCCESS";
}
else
	print "BAD_PARAMS";