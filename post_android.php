<?php
require_once("lib/db.php");
require_once("lib/secure.php");
require_once("msc/database.php");

$userid =     (isset($_POST['UserID']))? $_POST['UserID'] : null;
$appid =      (isset($_POST['AppID']))? $_POST['AppID'] : null;
$timestamp =  (isset($_POST['Timestamp']))? $_POST['Timestamp'] : "";
$start =      (isset($_POST['StartTime']))? $_POST['StartTime'] : null;
$end =        (isset($_POST['EndTime']))? $_POST['EndTime'] : null;
$last =       (isset($_POST['LastTime']))? $_POST['LastTime'] : null;
$total =      (isset($_POST['TotalTime']))? $_POST['TotalTime'] : null;

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