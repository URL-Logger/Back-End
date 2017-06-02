<?php
# DATE Filter
# Uses:
#   $clause  ->  SQL query WHERE clause
#   $params  ->  Parameters to pass to SQL

$f_data = (!empty($_POST['date']))? $_POST['date'] : array();
$set = "";
foreach($f_data as $filter) {
	if($set != "") $set .= " or ";
	
	$start = $_POST['date_'.$filter]. " 00:00:00";
	$end = $_POST['date_'.$filter]. " 23:59:59";
	if($mode == "browser")
		$set .= "(Timestamp BETWEEN ? and ?)";
	else if($mode == "mobile")
		$set .= "(StartTime BETWEEN ? and ?)";
	$params []= $start;
	$params []= $end;
}
if($set != "") $clause .= " and ({$set})";