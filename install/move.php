<?php
set_time_limit(0);

require_once("{$_SERVER['DOCUMENT_ROOT']}/lib/db.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/lib/secure.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/msc/database.php");

$read = DB::connect("url-logger-db1.cm9mres9mcqi.us-west-1.rds.amazonaws.com", "masteruser", "mastpass", "URL_Logger_DB1");
$write = DB::connect($_DB['HOST'], $_DB['WRITE_COLLECTION']['USER'], $_DB['WRITE_COLLECTION']['PASS'], $_DB['DATABASE']);

$write->prepare("postData", "INSERT INTO `Collection_Chrome` (
		UserID, URL, Title, Timestamp, URLID, VisitID, ReferID, Transition)
		VALUES (?,?,?,?,?,?,?,?)");
		
$result = $read->query("SELECT * FROM `URL_Data`");
for($i=0; $i<count($result); ++$i) {
	$write->param("postData", "i", $result[$i]['ParticipantID']);
	$write->param("postData", "s", $result[$i]['URL_Actual']);
	$write->param("postData", "s", $result[$i]['URL_Title']);
	$write->param("postData", "s", $result[$i]['URL_TimeStamp']);
	$write->param("postData", "s", $result[$i]['URL_ID']);
	$write->param("postData", "s", $result[$i]['URL_visitID']);
	$write->param("postData", "s", $result[$i]['URL_refVisitID']);
	$write->param("postData", "s", $result[$i]['URL_Transition']);
	$write->execute("postData");
}