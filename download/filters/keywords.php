<?php
# KEYWORDS Filter
# Uses:
#   $clause  ->  SQL query WHERE clause
#   $params  ->  Parameters to pass to SQL

if($mode == "browser") {
	$f_keywords = (!empty($_POST['keywords']))? $_POST['keywords'] : array();
	$set = "";
	foreach($f_keywords as $filter) {
		if($set != "") $set .= " or ";
		$set .= "(1";
		
		$data = $_POST['keywords_'.$filter];
		$split = explode(' ', $data);
		foreach($split as $item) {
			$item = trim($item);
			if($item) {
				$set .= " and (URL LIKE ? or Title LIKE ?)";
				$params []= "%{$item}%";
				$params []= "%{$item}%";
			}
		}
		
		$set .= ")";
	}
	if($set != "") $clause .= " and ({$set})";
}