<?php
# USERID Filter
# Uses:
#   $clause  ->  SQL query WHERE clause
#   $params  ->  Parameters to pass to SQL

$f_data = (!empty($_POST['userid']))? $_POST['userid'] : array();
$set = "";
foreach($f_data as $filter) {
	if($set != "") $set .= " or ";
	$set .= "(1";
	
	$data = $_POST['userid_'.$filter];
	$split = explode(' ', $data);
	foreach($split as $item) {
		$item = trim($item);
		if($item) {
			$set .= " and UserID=?";
			$params []= $item;
		}
	}
	
	$set .= ")";
}
if($set != "") $clause .= " and ({$set})";