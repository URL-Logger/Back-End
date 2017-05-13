<?php
require_once("src/lib/db.php");
require_once("src/misc/database.php");

# get parameters
$userid =    (isset($_POST['UserID']))? htmlspecialchars($_POST['UserID'], ENT_QUOTES) : null;
$url =       (isset($_POST['URL']))? htmlspecialchars($_POST['URL'], ENT_QUOTES) : null;
$title =     (isset($_POST['Title']))? htmlspecialchars($_POST['Title'], ENT_QUOTES) : "";
$timestamp = (isset($_POST['Timestamp']))? htmlspecialchars($_POST['Timestamp'], ENT_QUOTES) : null;
$urlid =     (isset($_POST['URLID']))? htmlspecialchars($_POST['URLID'], ENT_QUOTES) : null;
$urlvid =    (isset($_POST['URLVID']))? htmlspecialchars($_POST['URLVID'], ENT_QUOTES) : null;
$urlrid =    (isset($_POST['URLRID']))? htmlspecialchars($_POST['URLRID'], ENT_QUOTES) : null;
$trans = 	 (isset($_POST['Transition']))? htmlspecialchars($_POST['Transition'], ENT_QUOTES) : null;

$rows = array();
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
		
	for($i=0; $i<count($userid); ++$i)
		$rows []= array('userid'=>$userid[$i], 'url'=>$url[$i], 'title'=>$title[$i], 'timestamp'=>$timestamp[$i],
			'urlid'=>$urlid[$i], 'urlvid'=>$urlvid[$i], 'urlrid'=>$urlrid[$i], 'trans'=>$trans[$i]);
}
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
	$rows []= array('userid'=>$userid, 'url'=>$url, 'title'=>$title[$i], 'timestamp'=>$timestamp[$i],
		'urlid'=>$urlid[$i], 'urlvid'=>$urlvid[$i], 'urlrid'=>$urlrid[$i], 'trans'=>$trans[$i]);
}

# if rows exists, write rows to database
if(count($rows) > 0) {
	$db = DB::connect($_DB['HOST'], $_DB['WRITE_COLLECTION']['USER'], $_DB['WRITE_COLLECTION']['PASS'], $_DB['DATABASE']);
	
	# create insert query
	$query = "";
	for($i=0; $i<count($rows); ++$i) {
		$query .= "(?,?,?,?,?,?,?,?)";
		if($i<count($rows)-1)
			$query .= ", ";
	}
	
	# prepare database query
	if(!$db->prepare("postData", "INSERT INTO `Collection_Chrome` (
		UserID, URL, Title, Timestamp, URLID, VisitID, ReferID, Transition)
		VALUES {$query}")) die($db->error());
		
	# pass values to query
	foreach($rows as $row) {
		$db->param("postData", "i", $userid);
		$db->param("postData", "s", $url);
		$db->param("postData", "s", $title);
		$db->param("postData", "s", $timestamp);
		$db->param("postData", "s", $urlid);
		$db->param("postData", "s", $urlvid);
		$db->param("postData", "s", $urlrid);
		$db->param("postData", "s", $trans);
	}
	
	# exeucte database query
	echo ($db->execute("postData") !== false)? 1 : 0;
}