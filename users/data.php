<?php
ob_start();
set_time_limit(0);

require_once("{$_SERVER['DOCUMENT_ROOT']}/src/lib/db.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/misc/database.php");

$db = DB::connect($_DB['HOST'], $_DB['READ_USER_LOGIN']['USER'], $_DB['READ_USER_LOGIN']['PASS'], $_DB['DATABASE']);
if(!$db) die("Failed to connect to database. Please try again.</br>");

$download = isset($_REQUEST['download']);

$limit = (isset($_REQUEST['limit']) && intval($_REQUEST['limit']) > 0)? "LIMIT ". intval($_REQUEST['limit']) : "";

$clause = "WHERE 1";
$params = array();



if(! $db->prepare("getData", "SELECT * FROM `User_Login` {$clause} {$limit}"))
	die($db->error());
foreach($params as $param)
	$db->param("getData", "s", $param);
$result = $db->execute("getData");
/*
$line = "";
if(!$download)  echo "<tr>";
for($i=0; $i<count($columns); ++$i) {
	if(!( ($columns[$i] == "ID")
		|| ($mode == "mobile" && $columns[$i] == "LastTime") )) {
		if(!$download)  echo "<td class='header'>{$columns[$i]}</td>";
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
}*/

if($result !== null) {
	if(!$download) {
		echo "<table>";
		echo "<tr>";
			echo "<th>ID</th>";
			echo "<th>Respondent</th>";
			echo "<th>Email</th>";
			echo "<th>Browser Sync</th>";
			echo "<th>Mobile Sync</th>";
		echo "</tr>";
		
		foreach($result as $row) {
			$sync_browser_time = (strtotime($row['LastSyncBrowser']) > 0)?
				date("Y-m-d H:i", strtotime($row['LastSyncBrowser']))
				: "Never";
			$sync_mobile_time = (strtotime($row['LastSyncBrowser']) > 0)?
				date("Y-m-d H:i", strtotime($row['LastSyncBrowser']))
				: "Never";
			
			echo "<tr>";
				echo "<td>{$row['ID']}</td>";
				echo "<td>{$row['RespondentID']}</td>";
				echo "<td>{$row['Email']}</td>";
				echo "<td>{$sync_browser_time}</td>";
				echo "<td>{$sync_mobile_time}</td>";
			echo "</tr>";
		}
		
		echo "</table>";
	}
	else {
		$file = "users.". date("Y-m-d") .".csv";
		file_put_contents($file, "ID,RespondentID,Email,BrowserSync,MobileSync\n");
		
		for($i=0; $i<count($result); ++$i) {
			$row = $result[$i];
			$sync_browser_time = (strtotime($row['LastSyncBrowser']) > 0)?
				date("Y-m-d H:i", strtotime($row['LastSyncBrowser']))
				: "Never";
			$sync_mobile_time = (strtotime($row['LastSyncBrowser']) > 0)?
				date("Y-m-d H:i", strtotime($row['LastSyncBrowser']))
				: "Never";
			
			echo "<tr>";
				echo "<td>{$row['ID']}</td>";
				echo "<td>{$row['RespondentID']}</td>";
				echo "<td>{$row['Email']}</td>";
				echo "<td>{$sync_browser_time}</td>";
				echo "<td>{$sync_mobile_time}</td>";
			echo "</tr>";
			file_put_contents($file, "{$row['ID']},{$row['RespondentID']},{$row['Email']},{$row['LastSyncBrowser']},{$row['LastSyncMobile']}\n", FILE_APPEND);
		}
		
		header("Content-disposition: attachment; filename='{$file}");
		ob_end_clean();
		readfile($file);
		unlink($file);
	}
}
?>