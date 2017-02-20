<?php
require_once("lib/db.php");
require_once("lib/secure.php");
require_once("msc/database.php");

$userid = (isset($_POST['UserID'])? $_POST['UserID'] : null;
$url =    (isset($_POST['URL'])? $_POST['URL'] : null;
$urlid =  (isset($_POST['URLID'])? $_POST['URLID'] : null;
$urlvid = (isset($_POST['URLVID'])? $_POST['URLVID'] : null;
$urlrid = (isset($_POST['URLRID'])? $_POST['URLRID'] : null;

if($userid && $url && $urlid && $urlvid && $urlrid) {
	$db = DB::connect(
		$_CONNECTION['LOGIN']['HOST'],
		$_CONNECTION['LOGIN']['USER'], 
		$_CONNECTION['LOGIN']['PASS'], 
		$_CONNECTION['LOGIN']['BASE'], 
	);
	$db->prepare("postData", "INSERT INTO `Collection_Chrome` (
		ParticipantID, URL_Actual, URL_ID, URL_visitID, URL_refVisitID
		VALUES (?,?,?,?,?)");
	$db->param("s", $userid);
	$db->param("s", $url);
	$db->param("s", $urlid);
	$db->param("s", $urlvid);
	$db->param("s", $urlrid);
	$db->execute();
	print "SUCCESS";
}
else
	print "BAD_PARAMS";