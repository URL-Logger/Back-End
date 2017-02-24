<?php
require_once("lib/db.php");
require_once("lib/secure.php");
require_once("msc/database.php");

$userid =     (isset($_POST['UserID']))? $_POST['UserID'] : null;
$appid =      (isset($_POST['AppID']))? $_POST['AppID'] : null;
$timestamp =  (isset($_POST['Timestamp']))? $_POST['Timestamp'] : "";
$start =      (isset($_POST['StartTime']))? $_POST['StartTime'] : "";
$end =        (isset($_POST['EndTime']))? $_POST['EndTime'] : null;
$last =       (isset($_POST['LastTime']))? $_POST['LastTime'] : null;
$total =      (isset($_POST['TotalTime']))? $_POST['TotalTime'] : null;

if($userid !== null
  && $appid !== null
  && $start !== null
  && $end !== null
  && $last !== null
  && $total !== null) {
	$db = DB::connect(
		$_CONNECTION['LOGIN']['HOST'],
		$_CONNECTION['LOGIN']['USER'], 
		$_CONNECTION['LOGIN']['PASS'], 
		$_CONNECTION['LOGIN']['BASE']
	);
	$db->prepare("postData", "INSERT INTO `Android_Data` (
		ParticipantID, Timestamp, AppID, StartTime, EndTime, LastTime, TotalTime)
		VALUES (?,?,?,?,?,?,?)");
	print $db->error();
	$db->param("postData", "i", $userid);
	$db->param("postData", "s", $timestamp);
	$db->param("postData", "s", $appid);
	$db->param("postData", "i", $start);
	$db->param("postData", "i", $end);
	$db->param("postData", "i", $last);
	$db->param("postData", "i", $total);
	if( $db->execute("postData") === false )
		die("FAILED");
	print "SUCCESS";
}
else
	print "BAD_PARAMS";