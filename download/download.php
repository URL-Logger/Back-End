<?php
ob_start();
set_time_limit(0);

require_once("../lib/db.php");
require_once("../lib/secure.php");
require_once("../msc/database.php");

$db = DB::connect($_DB['HOST'], $_DB['READ_COLLECTION']['USER'], $_DB['READ_COLLECTION']['PASS'], $_DB['DATABASE']);

$preview = isset($_REQUEST['preview']);

$f_date = (!empty($_POST['date']))? $_POST['date'] : array();
$f_daterange = (!empty($_POST['daterange']))? $_POST['daterange'] : array();
$f_userid = (!empty($_POST['userid']))? $_POST['userid'] : array();

$clause_limit = "";
$limit = 0;
$max_limit = 25;
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

// Date Filter
$set = "";
foreach($f_date as $filter) {
	$data = $_POST['date_'.$filter];
	if($data) {
		if($set != "") $set .= " or ";
		$set .= "DATE(Timestamp)=?";
		$params []= $data;
	}
}
if($set) $clause .= " and ({$set})";

// DateRange Filter
$set = "";
foreach($f_daterange as $filter) {
	$start = $_POST['daterange_start_'.$filter];
	$end = $_POST['daterange_end_'.$filter];
	if($start && $end) {
		if($set != "") $set .= " or ";
		$set .= "(DATE(Timestamp)>=? and DATE(Timestamp)<=?)";
		$params []= $start;
		$params []= $end;
	}
}
if($set) $clause .= " and ({$set})";

// UserID Filter
$set = "";
foreach($f_userid as $filter) {
	$data = $_POST['userid_'.$filter];
	$split = explode(',', $data);
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

$db->prepare("getFields", "SELECT * FROM `Collection_Chrome` LIMIT 1");
if(! $db->prepare("getData", "SELECT * FROM `Collection_Chrome` {$clause} ORDER BY `Timestamp` ASC {$clause_limit}"))
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

if($preview) echo "<table>";
$line = "";
if($preview) echo "<tr>";
for($i=0; $i<count($columns); ++$i) {
	if($preview) echo "<td class='header'>{$columns[$i]}</td>";
	else {
		$line .= $columns[$i];
		if($d < count($columns)-1)
			$line .= ",";
	}
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
			if($preview) echo "<td>". htmlspecialchars($values[$d]). "</td>";
			else {
				$line .= $values[$d];
				if($d < count($values)-1)
					$line .= ",";
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