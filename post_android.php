<?php
require_once("src/lib/db.php");
require_once("src/misc/database.php");

# get parameters
$userid =     (isset($_POST['UserID']))? $_POST['UserID'] : 0;
$appid =      (isset($_POST['AppID']))? $_POST['AppID'] : "";
$appname =      (isset($_POST['AppName']))? $_POST['AppName'] : "";
$start =      (isset($_POST['StartTime']))? $_POST['StartTime'] : 0;
$end =        (isset($_POST['EndTime']))? $_POST['EndTime'] : 0;
$last =       (isset($_POST['LastTime']))? $_POST['LastTime'] : 0;
$total =      (isset($_POST['TotalTime']))? $_POST['TotalTime'] : 0;
$launch =     (isset($_POST['Launch']))? $_POST['Launch'] : 0;

if($userid !== null) {
	$db = DB::connect($_DB['HOST'], $_DB['WRITE_USER_INFO']['USER'], $_DB['WRITE_USER_INFO']['PASS'], $_DB['DATABASE']);
	$db->prepare("postSync", "UPDATE `User_Login` SET LastSyncMobile=NOW() WHERE ID=?");
	$db->param("postSync", "i", is_array($userid)? $userid[0] : $userid);
	$db->execute("postSync");
}

# if all parameters are arrays fo the same size,
# copy array values into $rows
$rows = array();
if(is_array($userid)
	&& is_array($appid)
	&& is_array($appname)
	&& is_array($start)
	&& is_array($end)
	&& is_array($last)
	&& is_array($total)
	&& is_array($launch)
	&& count($userid) == count($appid)
	&& count($userid) == count($appname)
	&& count($userid) == count($start)
	&& count($userid) == count($end)
	&& count($userid) == count($last)
	&& count($userid) == count($total)
	&& count($userid) == count($launch)	) {
		
	# add all array entries to $rows
	for($i=0; $i<count($userid); ++$i) {
		$rows []= array(
			'userid'=>htmlspecialchars($userid, ENT_QUOTES),
			'appid'=>htmlspecialchars($appid, ENT_QUOTES),
			'appname'=>htmlspecialchars($appname, ENT_QUOTES),
			'start'=>htmlspecialchars($start, ENT_QUOTES),
			'end'=>htmlspecialchars($end, ENT_QUOTES),
			'last'=>htmlspecialchars($last, ENT_QUOTES),
			'total'=>htmlspecialchars($total, ENT_QUOTES),
			'launch'=>htmlspecialchars($launch, ENT_QUOTES)
		);
	}
}
# if only a single record is sent, add that record to $rows
else if(!is_array($userid)
	&& !is_array($appid)
	&& !is_array($appname)
	&& !is_array($start)
	&& !is_array($end)
	&& !is_array($last)
	&& !is_array($total)
	&& !is_array($launch)) {
		
	# add the single entry to $rows
	$rows []= array(
		'userid'=>htmlspecialchars($userid, ENT_QUOTES),
		'appid'=>htmlspecialchars($appid, ENT_QUOTES),
		'appname'=>htmlspecialchars($appname, ENT_QUOTES),
		'start'=>htmlspecialchars($start, ENT_QUOTES),
		'end'=>htmlspecialchars($end, ENT_QUOTES),
		'last'=>htmlspecialchars($last, ENT_QUOTES),
		'total'=>htmlspecialchars($total, ENT_QUOTES),
		'launch'=>htmlspecialchars($launch, ENT_QUOTES)
	);
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
	if(!$db->prepare("postData", "INSERT INTO `Collection_Android` (
		UserID, AppID, AppName, StartTime, EndTime, LastTime, TotalTime, Launch)
		VALUES {$query}")) die($db->error());
		
	# pass values to query
	foreach($rows as $row) {
		$db->param("postData", "i", $row['userid']);
		$db->param("postData", "s", $row['appid']);
		$db->param("postData", "s", $row['appname']);
		$db->param("postData", "s", $row['start']);
		$db->param("postData", "s", $row['end']);
		$db->param("postData", "s", $row['last']);
		$db->param("postData", "s", $row['total']);
		$db->param("postData", "s", $row['launch']);
	}
	
	# exeucte database query and write status
	echo ($db->execute("postData") !== false)? 1 : 0;
}

# if input is empty and DEBUG is set, write failed status
else if(isset($_POST['DEBUG'])) {
	echo "No Input";
}