<?php
require_once("src/lib/db.php");
require_once("src/misc/database.php");

# get parameters
$userid =    (isset($_POST['UserID']))? $_POST['UserID'] : null;
$url =       (isset($_POST['URL']))? $_POST['URL'] : null;
$title =     (isset($_POST['Title']))? $_POST['Title'] : null;
$timestamp = (isset($_POST['Timestamp']))? $_POST['Timestamp'] : null;
$urlid =     (isset($_POST['URLID']))? $_POST['URLID'] : null;
$urlvid =    (isset($_POST['URLVID']))? $_POST['URLVID'] : null;
$urlrid =    (isset($_POST['URLRID']))? $_POST['URLRID'] : null;
$trans = 	 (isset($_POST['Transition']))? $_POST['Transition'] : null;

// htmlspecialchars( , ENT_QUOTES);

if($userid !== null) {
	$db = DB::connect($_DB['HOST'], $_DB['WRITE_USER_INFO']['USER'], $_DB['WRITE_USER_INFO']['PASS'], $_DB['DATABASE']);
	$db->prepare("postSync", "UPDATE `User_Login` SET LastSyncBrowser=NOW() WHERE ID=?");
	$db->param("postSync", "i", is_array($userid)? $userid[0] : $userid);
	$db->execute("postSync");
}

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
		$rows []= array('userid'=>htmlspecialchars($userid[$i], ENT_QUOTES), 'url'=>htmlspecialchars($url[$i], ENT_QUOTES), 'title'=>htmlspecialchars($title[$i], ENT_QUOTES), 'timestamp'=>htmlspecialchars($timestamp[$i], ENT_QUOTES),
			'urlid'=>htmlspecialchars($urlid[$i], ENT_QUOTES), 'urlvid'=>htmlspecialchars($urlvid[$i], ENT_QUOTES), 'urlrid'=>htmlspecialchars($urlrid[$i], ENT_QUOTES), 'trans'=>htmlspecialchars($trans[$i], ENT_QUOTES));
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
	$rows []= array('userid'=>$userid, 'url'=>$url, 'title'=>$title[$i], 'timestamp'=>$timestamp[$i],
		'urlid'=>$urlid[$i], 'urlvid'=>$urlvid[$i], 'urlrid'=>$urlrid[$i], 'trans'=>$trans[$i]);
		
}
# if input is invalid and DEBUG is set, write failed status
else if(isset($_POST['DEBUG'])) {
	echo "Invalid Input";
}	

# if rows exists, write rows to database
if(count($rows) > 0) {
	$db = DB::connect($_DB['HOST'], $_DB['WRITE_COLLECTION']['USER'], $_DB['WRITE_COLLECTION']['PASS'], $_DB['DATABASE']);
	
	# create value placeholders for INSERT query
	$query = "";
	for($i=0; $i<count($rows); ++$i) {
		$query .= "(?,?,?,?,?,?,?,?)";
		if($i<count($rows)-1)
			$query .= ", ";
	}
	
	# prepare database query to insert values
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
	
	# exeucte database query and write status
	echo ($db->execute("postData") !== false)? 1 : 0;
}

# if input is empty and DEBUG is set, write failed status
else if(isset($_POST['DEBUG'])) {
	echo "No Input";
}