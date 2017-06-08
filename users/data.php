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
$order = "";
$params = array();

if(!empty($_POST['user'])) {
	$items = explode(" ", $_POST['user']);
	$sub = "0";
	foreach($items as $item) {
		$item = trim($item);
		if($item !== "") {
			$sub .= " OR (Email=? OR ID=?)";
			$params []= $item;
			$params []= $item;
		}
	}
	if($sub != "0")
		$clause .= " AND {$sub}";
}

if(!empty($_POST['usage'])) {
	if($_POST['usage'] == "never") {
		if(!empty($_POST['platform'])) {
			if($_POST['platform'] == "browser")
				$clause .= " AND (LastSyncBrowser=0)";
			else
				$clause .= " AND (LastSyncMobile=0)";
		}
		else
			$clause .= " AND (LastSyncBrowser=0 AND LastSyncMobile=0)";
	}
	else if(!empty($_POST['date'])){
		$compare = ($_POST['usage'] == "active")? ">=" : "<";
		if(!empty($_POST['platform'])) {
			if($_POST['platform'] == "browser") {
				$clause .= " AND (LastSyncBrowser{$compare}?)";
				$order = "LastSyncBrowser DESC";
			}
			else {
				$clause .= " AND (LastSyncMobile{$compare}?)";
				$order = "LastSyncMobile DESC";
			}
			$params []= $_POST['date'];
		}
		else {
			$clause .= " AND (LastSyncBrowser{$compare}? OR LastSyncMobile{$compare}?)";
			$order = "GREATEST(LastSyncBrowser, LastSyncMobile) DESC";
			$params []= $_POST['date'];
			$params []= $_POST['date'];
		}
	}
}

if($order) $order = "ORDER BY {$order}";

if(! $db->prepare("getData", "SELECT * FROM `User_Login` {$clause} {$order} {$limit}"))
	die($db->error());
foreach($params as $param)
	$db->param("getData", "s", $param);
$result = $db->execute("getData");

if($result !== null) {
	if(!$download) {
		echo "<table>";
		echo "<tr>";
			echo "<th>ID</th>";
			echo "<th>Respondent</th>";
			echo "<th>Email</th>";
			echo "<th>Browser Sync</th>";
			echo "<th>Mobile Sync</th>";
			echo "<th></th>";
		echo "</tr>";
		
		foreach($result as $row) {
			$sync_browser_time = (strtotime($row['LastSyncBrowser']) > 0)?
				date("Y-m-d H:i", strtotime($row['LastSyncBrowser']))
				: "Never";
			$sync_mobile_time = (strtotime($row['LastSyncMobile']) > 0)?
				date("Y-m-d H:i", strtotime($row['LastSyncMobile']))
				: "Never";
			
			echo "<tr>";
				echo "<td>{$row['ID']}</td>";
				echo "<td>{$row['RespondentID']}</td>";
				echo "<td>{$row['Email']}</td>";
				echo "<td>{$sync_browser_time}</td>";
				echo "<td>{$sync_mobile_time}</td>";
				echo "<td style=\"text-align: right;\"><a href=\"edit/?id={$row['ID']}\">Edit</a></td>";
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
			file_put_contents($file, "{$row['ID']},{$row['RespondentID']},{$row['Email']},{$row['LastSyncBrowser']},{$row['LastSyncMobile']}\n", FILE_APPEND);
		}
		
		header("Content-disposition: attachment; filename='{$file}");
		ob_end_clean();
		readfile($file);
		unlink($file);
	}
}
?>