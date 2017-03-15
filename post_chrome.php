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
	$db = DB::connect($_DB['HOST'], $_DB['WRITE_COLLECTION']['USER'], $_DB['WRITE_COLLECTION']['PASS'], $_DB['DATABASE']);
	$db->prepare("postData", "INSERT INTO `Collection_Chrome` (
		UserID, URL, Title, Timestamp, URLID, VisitID, ReferID, Transition)
		VALUES (?,?,?,?,?,?,?,?)");
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