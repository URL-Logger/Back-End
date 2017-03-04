<?php
ob_start();
require_once("lib/db.php");
require_once("lib/secure.php");
require_once("msc/database.php");

$db = DB::connect(
	$_CONNECTION['LOGIN']['HOST'],
	$_CONNECTION['LOGIN']['USER'], 
	$_CONNECTION['LOGIN']['PASS'], 
	$_CONNECTION['LOGIN']['BASE']
);

$db->prepare("getData", "SELECT * FROM `URL_Data`");
$result = $db->execute("getData");
$columns = $db->fields("getData");

$file = date("Y-m-d").".log.csv";
file_put_contents($file, "");

if($result !== null) {
	$line = "";
	for($i=0; $i<count($columns); ++$i) {
		$line .= $columns[$i];
		if($d < count($columns)-1)
			$line .= "\t";
	}
	$line .= "\n";
	file_put_contents($file, $line);
	
	for($i=0; $i<count($result); ++$i) {
		$line = "";
		for($d=0; $d<count($result[$i]); ++$d) {
			$values = array_values($result[$i]);
			$line .= $values[$d];
			if($d < count($values)-1)
				$line .= "\t";
		}
		$line .= "\n";
		file_put_contents($file, $line, FILE_APPEND);
	}
}

header("Content-disposition: attachment; filename='{$file}");
ob_end_clean();
readfile($file);
unlink($file);
?>