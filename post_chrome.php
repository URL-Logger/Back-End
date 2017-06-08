<?php
require_once("src/lib/db.php");
require_once("src/misc/database.php");

function strip_input($str) {
	$str = htmlspecialchars($str, ENT_QUOTES);
	$str = str_replace(',', "&#44;", $str);
	return $str;
}

# get parameters
$userid =    (isset($_REQUEST['UserID']))? $_REQUEST['UserID'] : null;
$url =       (isset($_REQUEST['URL']))? $_REQUEST['URL'] : null;
$title =     (isset($_REQUEST['Title']))? $_REQUEST['Title'] : null;
$timestamp = (isset($_REQUEST['Timestamp']))? $_REQUEST['Timestamp'] : null;
$urlid =     (isset($_REQUEST['URLID']))? $_REQUEST['URLID'] : null;
$urlvid =    (isset($_REQUEST['URLVID']))? $_REQUEST['URLVID'] : null;
$urlrid =    (isset($_REQUEST['URLRID']))? $_REQUEST['URLRID'] : null;
$trans = 	 (isset($_REQUEST['Transition']))? $_REQUEST['Transition'] : null;
$keywords =  (isset($_REQUEST['Keywords']))? $_REQUEST['Keywords'] : null;
$device =    (isset($_REQUEST['Device']))? $_REQUEST['Device'] : null;

file_put_contents("chrome.log", "--------\n", FILE_APPEND);
foreach($_POST as $key=>$array) {
	file_put_contents("chrome.log", "{$key}[". count($_POST[$key]). "]=", FILE_APPEND);
	foreach($array as $value)
		file_put_contents("chrome.log", "{$value}, ", FILE_APPEND);
	file_put_contents("chrome.log", "\n", FILE_APPEND);
}

if($userid !== null) {
	$db = DB::connect($_DB['HOST'], $_DB['WRITE_USER_INFO']['USER'], $_DB['WRITE_USER_INFO']['PASS'], $_DB['DATABASE']);
	$db->prepare("postSync", "UPDATE `User_Login` SET LastSyncBrowser=NOW() WHERE ID=?");
	$db->param("postSync", "i", is_array($userid)? $userid[0] : $userid);
	$db->execute("postSync");
	$db->close();
}

$rows = array();

# add all array entries to $rows
for($i=0; $i<count($userid); ++$i) {
	if($userid[$i] > 0) {
		$rows []= array('userid'=>strip_input($userid[$i]), 'url'=>strip_input($url[$i]), 'title'=>strip_input($title[$i]),
			'timestamp'=>strip_input($timestamp[$i]), 'urlid'=>strip_input($urlid[$i]), 'urlvid'=>strip_input($urlvid[$i]),
			'urlrid'=>strip_input($urlrid[$i]), 'trans'=>strip_input($trans[$i]), 'keywords'=>strip_input($keywords[$i]),
			'device'=>strip_input($device[$i]));
	}
}

foreach($rows as $row) {
	file_put_contents("chrome.log","?", FILE_APPEND);
	foreach($row as $key=>$value)
		file_put_contents("chrome.log","\t{$key}={$value}\n", FILE_APPEND);
	file_put_contents("chrome.log","\n", FILE_APPEND);
}

# if rows exists, write rows to database
if(count($rows) > 0) {
	$db = DB::connect($_DB['HOST'], $_DB['WRITE_COLLECTION']['USER'], $_DB['WRITE_COLLECTION']['PASS'], $_DB['DATABASE']);
	
	# create value placeholders for INSERT query
	$query = "";
	for($i=0; $i<count($rows); ++$i) {
		$query .= "(?,?,?,?,?,?,?,?,?,?)";
		if($i<count($rows)-1)
			$query .= ", ";
	}
	
	# prepare database query to insert values
	$db->prepare("postData", "INSERT INTO `Collection_Chrome` (
		UserID, URL, Title, Timestamp, URLID, VisitID, ReferID, Transition, Keywords, Device)
		VALUES {$query}");
		
	# pass values to query
	foreach($rows as $row) {
		$db->param("postData", "i", $row['userid']);
		$db->param("postData", "s", $row['url']);
		$db->param("postData", "s", $row['title']);
		$db->param("postData", "s", $row['timestamp']);
		$db->param("postData", "s", $row['urlid']);
		$db->param("postData", "s", $row['urlvid']);
		$db->param("postData", "s", $row['urlrid']);
		$db->param("postData", "s", $row['trans']);
		$db->param("postData", "s", $row['keywords']);
		$db->param("postData", "s", $row['device']);
	}
	
	if($db->execute("postData") !== false)
		file_put_contents("chrome.log","Successfully added ". count($rows) ." rows\n", FILE_APPEND);
	else
		file_put_contents("error.log", $db->error(). "\n", FILE_APPEND);
}