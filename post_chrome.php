<?php
require_once("src/lib/db.php");
require_once("src/misc/database.php");

function strip_input($str) {
	$str = htmlspecialchars($str, ENT_QUOTES);
	$str = str_replace(",", "&#44;", $str);
	return $str;
}

# get parameters
$userid =    (isset($_POST['UserID']))? $_POST['UserID'] : null;
$url =       (isset($_POST['URL']))? $_POST['URL'] : null;
$title =     (isset($_POST['Title']))? $_POST['Title'] : null;
$timestamp = (isset($_POST['Timestamp']))? $_POST['Timestamp'] : null;
$urlid =     (isset($_POST['URLID']))? $_POST['URLID'] : null;
$urlvid =    (isset($_POST['URLVID']))? $_POST['URLVID'] : null;
$urlrid =    (isset($_POST['URLRID']))? $_POST['URLRID'] : null;
$trans = 	 (isset($_POST['Transition']))? $_POST['Transition'] : null;
$keywords =  (isset($_POST['Keywords']))? $_POST['Keywords'] : null;

if($userid !== null) {
	$db = DB::connect($_DB['HOST'], $_DB['WRITE_USER_INFO']['USER'], $_DB['WRITE_USER_INFO']['PASS'], $_DB['DATABASE']);
	$db->prepare("postSync", "UPDATE `User_Login` SET LastSyncBrowser=NOW() WHERE ID=?");
	$db->param("postSync", "i", is_array($userid)? $userid[0] : $userid);
	$db->execute("postSync");
	$db->close();
}
else exit;

$rows = array();
# if all parameters are arrays fo the same size,
# copy array values into $rows
if(is_array($userid)
	&& is_array($url)
	&& is_array($title)
	&& is_array($timestamp)
	&& is_array($urlid)
	&& is_array($urlvid)
	&& is_array($urlrid)
	&& is_array($trans)
	&& count($userid) == count($url)
	&& count($userid) == count($title)
	&& count($userid) == count($timestamp)
	&& count($userid) == count($urlid)
	&& count($userid) == count($urlvid)
	&& count($userid) == count($urlrid)
	&& count($userid) == count($trans) ) {
		
	# add all array entries to $rows
	for($i=0; $i<count($userid); ++$i)
		$rows []= array('userid'=>strip_input($userid[$i]), 'url'=>strip_input($url[$i]), 'title'=>strip_input($title[$i]), 'timestamp'=>strip_input($timestamp[$i]),
			'urlid'=>strip_input($urlid[$i]), 'urlvid'=>strip_input($urlvid[$i]), 'urlrid'=>strip_input($urlrid[$i]), 'trans'=>strip_input($trans[$i]), 'keywords'=>strip_input($keywords[$i]));
}
# if only a single record is sent, add that record to $rows
else if(!is_array($userid)
	&& !is_array($url)
	&& !is_array($title)
	&& !is_array($timestamp)
	&& !is_array($urlid)
	&& !is_array($urlvid)
	&& !is_array($urlrid)
	&& !is_array($trans)
	&& $userid !== null
	&& $url !== null
	&& $title !== null
	&& $timestamp !== null
	&& $urlid !== null
	&& $urlvid !== null
	&& $urlrid !== null
	&& $trans !== null) {
		
	# add the single entry to $rows
	$rows []= array('userid'=>strip_input($userid), 'url'=>strip_input($url), 'title'=>strip_input($title), 'timestamp'=>strip_input($timestamp),
		'urlid'=>strip_input($urlid), 'urlvid'=>strip_input($urlvid), 'urlrid'=>strip_input($urlrid), 'trans'=>strip_input($trans), 'keywords'=>strip_input($keywords));
}
# if input is invalid, write failed status
else {
	file_put_contents("chrome.log", "Invalid Input\n", FILE_APPEND);
	exit;
}

# if rows exists, write rows to database
if(count($rows) > 0) {
	file_put_contents("chrome.log", "Rows:". count($rows). "\n", FILE_APPEND);
	$db = DB::connect($_DB['HOST'], $_DB['WRITE_COLLECTION']['USER'], $_DB['WRITE_COLLECTION']['PASS'], $_DB['DATABASE']);
	
	# create value placeholders for INSERT query
	$query = "";
	for($i=0; $i<count($rows); ++$i) {
		$query .= "(?,?,?,?,?,?,?,?,?)";
		if($i<count($rows)-1)
			$query .= ", ";
	}
	
	# prepare database query to insert values
	if(!$db->prepare("postData", "INSERT INTO `Collection_Chrome` (
		UserID, URL, Title, Timestamp, URLID, VisitID, ReferID, Transition, Keywords)
		VALUES {$query}")) file_put_contents("chrome.log", $db->error(). "\n", FILE_APPEND);
		
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
	}
	
	# exeucte database query and write status
	if($db->execute("postData") === false)
		file_put_contents("chrome.log", $db->error(). "\n", FILE_APPEND);
}

# if input is empty, write failed status
else {
	file_put_contents("chrome.log", "No Input\n", FILE_APPEND);
	exit;
}