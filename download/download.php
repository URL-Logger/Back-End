<?php
# This file is called by the download page.
# It creates a preview of the data or a downloadable file.

ob_start();

require_once("{$_SERVER['DOCUMENT_ROOT']}/src/lib/db.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/misc/database.php");

$db = DB::connect($_DB['HOST'], $_DB['READ_COLLECTION']['USER'], $_DB['READ_COLLECTION']['PASS'], $_DB['DATABASE']);
if(!$db) die("Failed to connect to database. Please try again.</br>");

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

if(isset($_REQUEST['browser'])) {
	$mode = "browser";
	$dataset = "Collection_Chrome";
	$orderby = "Timestamp";
}
else if(isset($_REQUEST['mobile'])) {
	$mode = "mobile";
	$dataset = "Collection_Android";
	$orderby = "EndTime";
}
else exit;

$clause = "WHERE 1";
$params = array();

# Import filter handling scripts
require_once("filters/filters.php");

# Query the database for filtered data
$db->prepare("getFields", "SELECT * FROM `{$dataset}` LIMIT 1");
if(! $db->prepare("getData", "SELECT * FROM `{$dataset}` {$clause} ORDER BY `{$orderby}` DESC {$clause_limit}"))
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

# Output the table headers
if($preview) echo "<table class='preview'>";
$line = "";
if($preview) echo "<tr>";
for($i=0; $i<count($columns); ++$i) {
	if(!( ($columns[$i] == "ID")
		|| ($mode == "mobile" && $columns[$i] == "LastTime") )) {
			
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

# Output the data to a CSV file or HTML table
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

# Download the file, if not previewing
if(!$preview) {
	set_time_limit(0);
	header("Content-disposition: attachment; filename='{$file}");
	ob_end_clean();
	readfile($file);
	unlink($file);
}
?>