<?php
# APPID Filter
# Uses:
#   $clause  ->  SQL query WHERE clause
#   $params  ->  Parameters to pass to SQL

if($mode == "mobile") {
	$f_data = (!empty($_POST['application']))? $_POST['application'] : array();
	$set = "";
	foreach($f_data as $filter) {
		if($set != "") $set .= " or ";
		$set .= "(1";
		
		$data = $_POST['application_'.$filter];
		$split = explode(' ', $data);
		foreach($split as $item) {
			$item = trim($item);
			if($item) {
				$set .= " and AppID LIKE ?";
				$params []= "%{$item}%";
			}
		}
		
		$set .= ")";
	}
	if($set != "") $clause .= " and ({$set})";
}