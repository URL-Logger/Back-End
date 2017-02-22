<?php
ob_start();
require_once($_SERVER['DOCUMENT_ROOT']. "/lib/db.php");
require_once($_SERVER['DOCUMENT_ROOT']. "/msc/database.php");
require_once($_SERVER['DOCUMENT_ROOT']. "/lib/secure.php");

$db = DB::connect(
	$_CONNECTION['LOGIN']['HOST'],
	$_CONNECTION['LOGIN']['USER'], 
	$_CONNECTION['LOGIN']['PASS'], 
	$_CONNECTION['LOGIN']['BASE']
);

$db->prepare("getData", "SELECT * FROM `URL_Data`");
$result = $db->execute("getData");
if($result !== null) {
	$columns = "";
	
	$line = "";
	for($i=0; $i<count($columns); ++$i) {
		$line .= $columns[$i];
		if($d < count($columns)-1)
			$line .= "\t";
	}
	$line .= "\n";
	file_put_contents("download.txt", $line);
	
	for($i=0; $i<count($result); ++$i) {
		$line = "";
		for($d=0; $d<count($result[$i]); ++$d) {
			$values = array_values($result[$i]);
			$line .= $values[$d];
			if($d < count($values)-1)
				$line .= "\t";
		}
		$line .= "\n";
		file_put_contents("download.txt", $line, FILE_APPEND);
	}
}

header('Content-Type: application/octet-stream');
header("Content-disposition: attachment; filename='download.csv");
ob_end_clean();
readfile("download.txt");
?>