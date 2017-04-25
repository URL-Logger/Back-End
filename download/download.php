<?php
ob_start();
set_time_limit(0);

require_once("{$_SERVER['DOCUMENT_ROOT']}/src/lib/db.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/scripts/secure.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/misc/database.php");

$db = DB::connect($_DB['HOST'], $_DB['READ_COLLECTION']['USER'], $_DB['READ_COLLECTION']['PASS'], $_DB['DATABASE']);

$preview = isset($_REQUEST['preview']);

$clause_limit = "";
$limit = 0;
$max_limit = 150;
if(isset($_POST['limit']) && $_POST['limit'] > 0) {
	if($preview)
		$limit = ($_POST['limit'] < $max_limit)? $_POST['limit'] : $max_limit;
	else
		$limit = $_POST['limit'];
}
if($limit == 0 && $preview)
	$limit = $max_limit;
if($limit > 0) $clause_limit = "LIMIT {$limit}";

$clause = "WHERE 1";
$params = array();

if(isset($_REQUEST['browser'])) {
	$dataset = "Collection_Chrome";
	
	## Browser Data Filters ##
	
	# Keywords Filter
	$f_keywords = (!empty($_POST['keywords']))? $_POST['keywords'] : array();
		$set = "";
		foreach($f_keywords as $filter) {
			$data = $_POST['keywords_'.$filter];
			$split = explode(' ', $data);
			foreach($split as $item) {
				$item = trim($item);
				if($item) {
					if($set != "") $set .= " and ";
					$set .= "(URL LIKE ? or Title LIKE ?)";
					$params []= "%{$item}%";
					$params []= "%{$item}%";
				}
			}
		}
		if($set) $clause .= " and ({$set})";
	
}
else if(isset($_REQUEST['mobile'])) {
	$dataset = "Collection_Android";
	
	## Android Data Filters ##
	$f_application = (!empty($_POST['application']))? $_POST['application'] : array();
		$set = "";
		foreach($f_application as $filter) {
			$data = $_POST['application_'.$filter];
			$split = explode(' ', $data);
			foreach($split as $item) {
				$item = trim($item);
				if($item) {
					if($set != "") $set .= " and ";
					$set .= "AppID LIKE ?";
					$params []= "%{$item}%";
				}
			}
		}
		if($set) $clause .= " and ({$set})";
}
else exit;

## General Filters ##

# Date Filter
$f_date = (!empty($_POST['date']))? $_POST['date'] : array();
	$set = "";
	foreach($f_date as $filter) {
		$data = $_POST['date_'.$filter];
		if($data) {
			if($set != "") $set .= " or ";
			if($dataset == "Collection_Chrome")
				$set .= "DATE(Timestamp)=?";
			else if($dataset == "Collection_Android")
				$set .= "DATE(StartTime)=?";
			$params []= $data;
		}
	}
	if($set) $clause .= " and ({$set})";

# DateRange Filter
$f_daterange = (!empty($_POST['daterange']))? $_POST['daterange'] : array();
	$set = "";
	foreach($f_daterange as $filter) {
		$start = $_POST['daterange_start_'.$filter];
		$end = $_POST['daterange_end_'.$filter];
		if($start && $end) {
			if($set != "") $set .= " or ";
			if($dataset == "Collection_Chrome")
				$set .= "(DATE(Timestamp)>=? and DATE(Timestamp)<=?)";
			else if($dataset == "Collection_Android")
				$set .= "(DATE(StartTime)>=? and DATE(StartTime)<=?)";
			$params []= $start;
			$params []= $end;
		}
	}
	if($set) $clause .= " and ({$set})";

# UserID Filter
$f_userid = (!empty($_POST['userid']))? $_POST['userid'] : array();
	$set = "";
	foreach($f_userid as $filter) {
		$data = $_POST['userid_'.$filter];
		$split = explode(' ', $data);
		foreach($split as $item) {
			$item = trim($item);
			if($item) {
				if($set != "") $set .= " or ";
				$set .= "UserID=?";
				$params []= $item;
			}
		}
	}
	if($set) $clause .= " and ({$set})";

switch($dataset) {
	case "Collection_Chrome":
		$orderby = "Timestamp"; break;
	case "Collection_Android":
		$orderby = "StartTime"; break;
}
	
$db->prepare("getFields", "SELECT * FROM `{$dataset}` LIMIT 1");
if(! $db->prepare("getData", "SELECT * FROM `{$dataset}` {$clause} ORDER BY `{$orderby}` ASC {$clause_limit}"))
	die($db->error());
foreach($params as $param)
	$db->param("getData", "s", $param);
$result = $db->execute("getData");
$db->execute("getFields");
$columns = $db->fields("getFields");

if(!$preview) {
	$file = date("Y-m-d").".log.csv";
	file_put_contents($file, "");
}

$ignore_cols = array();

if($preview) echo "<table class='preview'>";
$line = "";
if($preview) echo "<tr>";
for($i=0; $i<count($columns); ++$i) {
	if($columns[$i] != "ID") {
		if($preview) echo "<td class='header'>{$columns[$i]}</td>";
		else {
			$line .= $columns[$i];
			if($d < count($columns)-1)
				$line .= ",";
		}
	}
	else
		$ignore_cols []= $i;
}
if($preview) echo "</tr>";
else {
	$line .= "\n";
	file_put_contents($file, $line);
}

if($result !== null) {
	for($i=0; $i<count($result); ++$i) {
		$line = "";
		if($preview) echo "<tr>";
		$values = array_values($result[$i]);
		for($d=0; $d<count($result[$i]); ++$d) {
			if(!in_array($d, $ignore_cols)) {
				if($preview) echo "<td>". htmlspecialchars($values[$d]). "</td>";
				else {
					$line .= $values[$d];
					if($d < count($values)-1)
						$line .= ",";
				}
			}
		}
		if($preview) echo "</tr>";
		else {
			$line .= "\n";
			file_put_contents($file, $line, FILE_APPEND);
		}
	}
}
if($preview) echo "</table>";

if(!$preview) {
	header("Content-disposition: attachment; filename='{$file}");
	ob_end_clean();
	readfile($file);
	unlink($file);
}
?>