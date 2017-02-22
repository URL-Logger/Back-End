<?php
require_once("lib/db.php");
require_once("lib/secure.php");
require_once("msc/database.php");

$userid =    (isset($_POST['UserID']))? $_POST['UserID'] : null;
$url =       (isset($_POST['URL']))? $_POST['URL'] : null;
$title =     (isset($_POST['Title']))? $_POST['Title'] : "";
$timestamp = (isset($_POST['Timestamp']))? $_POST['Timestamp'] : null;
$urlid =     (isset($_POST['URLID']))? $_POST['URLID'] : null;
$urlvid =    (isset($_POST['URLVID']))? $_POST['URLVID'] : null;
$urlrid =    (isset($_POST['URLRID']))? $_POST['URLRID'] : null;
$trans = 	 (isset($_POST['Transition']))? $_POST['Transition'] : null;

if($userid !== null
  && $url !== null
  && $timestamp !== null
  && $urlid !== null
  && $urlvid !== null
  && $urlrid !== null
  && $trans !== null) {
	$db = DB::connect(
		$_CONNECTION['LOGIN']['HOST'],
		$_CONNECTION['LOGIN']['USER'], 
		$_CONNECTION['LOGIN']['PASS'], 
		$_CONNECTION['LOGIN']['BASE']
	);
	$db->prepare("postData", "INSERT INTO `URL_Data` (
		ParticipantID, URL_Actual, URL_Title, URL_TimeStamp, URL_ID, URL_visitID, URL_refVisitID, URL_Transition)
		VALUES (?,?,?,?,?,?,?,?)");
	print $db->error();
	$db->param("postData", "i", $userid);
	$db->param("postData", "s", $url);
	$db->param("postData", "s", $title);
	$db->param("postData", "s", $timestamp);
	$db->param("postData", "s", $urlid);
	$db->param("postData", "s", $urlvid);
	$db->param("postData", "s", $urlrid);
	$db->param("postData", "s", $trans);
	if( $db->execute("postData") === false )
		die("FAILED");
	print "SUCCESS";
}
else
	print "BAD_PARAMS";