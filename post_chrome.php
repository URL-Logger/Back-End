<?php
require_once("src/lib/db.php");
require_once("src/misc/database.php");

$userid =    (isset($_POST['UserID']))? htmlspecialchars($_POST['UserID'], ENT_QUOTES) : null;
$url =       (isset($_POST['URL']))? htmlspecialchars($_POST['URL'], ENT_QUOTES) : null;
$title =     (isset($_POST['Title']))? htmlspecialchars($_POST['Title'], ENT_QUOTES) : "";
$timestamp = (isset($_POST['Timestamp']))? htmlspecialchars($_POST['Timestamp'], ENT_QUOTES) : null;
$urlid =     (isset($_POST['URLID']))? htmlspecialchars($_POST['URLID'], ENT_QUOTES) : null;
$urlvid =    (isset($_POST['URLVID']))? htmlspecialchars($_POST['URLVID'], ENT_QUOTES) : null;
$urlrid =    (isset($_POST['URLRID']))? htmlspecialchars($_POST['URLRID'], ENT_QUOTES) : null;
$trans = 	 (isset($_POST['Transition']))? htmlspecialchars($_POST['Transition'], ENT_QUOTES) : null;

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
	echo "SUCCESS";
}
else
	echo "BAD_PARAMS";