<?php
require_once("src/lib/db.php");
require_once("src/misc/database.php");

file_put_contents("android.log", "");
foreach($_POST as $key=>$entry) {
	$length = count($entry);
	file_put_contents("android.log", "{$key}: {$entry} [{$length}]\n", FILE_APPEND);
}

# get parameters
$userid =     (isset($_POST['UserID']))? $_POST['UserID'] : null;
$appid =      (isset($_POST['AppID']))? $_POST['AppID'] : null;
$start =      (isset($_POST['StartTime']))? $_POST['StartTime'] : null;
$end =        (isset($_POST['EndTime']))? $_POST['EndTime'] : null;
$last =       (isset($_POST['LastTime']))? $_POST['LastTime'] : null;
$total =      (isset($_POST['TotalTime']))? $_POST['TotalTime'] : null;
$launch =     (isset($_POST['Launch']))? $_POST['Launch'] : null;

if($userid !== null) {
	$db = DB::connect($_DB['HOST'], $_DB['WRITE_USER_INFO']['USER'], $_DB['WRITE_USER_INFO']['PASS'], $_DB['DATABASE']);
	$db->prepare("postSync", "UPDATE `User_Login` SET LastSync=NOW() WHERE ID=?");
	$db->param("postSync", "i", is_array($userid)? $userid[0] : $userid);
	$db->execute("postSync");
}

// htmlspecialchars( , ENT_QUOTES);

# if all parameters are arrays fo the same size,
# copy array values into $rows
$rows = array();
if(is_array($userid)
	&& is_array($appid)
	&& is_array($start)
	&& is_array($end)
	&& is_array($last)
	&& is_array($total)
	&& is_array($launch)
	&& count($userid) == count($appid)
	&& count($userid) == count($start)
	&& count($userid) == count($end)
	&& count($userid) == count($last)
	&& count($userid) == count($total)
	&& count($userid) == count($launch)	) {
		
	# add all array entries to $rows
	for($i=0; $i<count($userid); ++$i)
		$rows []= array('userid'=>$userid[$i], 'appid'=>$appid[$i], 'start'=>$start[$i], 'end'=>$end[$i], 'last'=>$last[$i], 'total'=>$total[$i], 'launch'=>$launch[$i]);
}
# if only a single record is sent, add that record to $rows
else if(!is_array($userid)
	&& !is_array($appid)
	&& !is_array($start)
	&& !is_array($end)
	&& !is_array($last)
	&& !is_array($total)
	&& !is_array($launch)
	&& $userid !== null
	&& $appid !== null
	&& $start !== null
	&& $end !== null
	&& $last !== null
	&& $total !== null
	&& $launch !== null) {
		
	# add the single entry to $rows
	$rows []= array('userid'=>$userid, 'appid'=>$appid, 'start'=>$start, 'end'=>$end, 'last'=>$last, 'total'=>$total, 'launch'=>$launch);
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
		$query .= "(?,?,?,?,?,?,?)";
		if($i<count($rows)-1)
			$query .= ", ";
	}
	
	# prepare database query to insert values
	if(!$db->prepare("postData", "INSERT INTO `Collection_Android` (
		UserID, AppID, StartTime, EndTime, LastTime, TotalTime, Launch)
		VALUES {$query}")) die($db->error());
		
	# pass values to query
	foreach($rows as $row) {
		$db->param("postData", "i", $row['userid']);
		$db->param("postData", "s", $row['appid']);
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