<?php
ob_start();
set_time_limit(0);

require_once("{$_SERVER['DOCUMENT_ROOT']}/src/lib/db.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/misc/database.php");

# Connect to database
$db = DB::connect($_DB['HOST'], $_DB['READ_USER_LOGIN']['USER'], $_DB['READ_USER_LOGIN']['PASS'], $_DB['DATABASE']);
if(!$db) die("Failed to connect to database. Please try again.</br>");

# Get page and possible download request
$page = isset($_REQUEST['page'])? $_REQUEST['page'] : 1;
$download = isset($_REQUEST['download']);

# Set the limit of the number of rows
$limit = (isset($_REQUEST['limit']) && intval($_REQUEST['limit']) > 0)?
	($download)? "LIMIT ". intval($_REQUEST['limit'])
		: min(intval($_REQUEST['limit']), 50);
	: "";

$clause = "WHERE 1";
$order = "";
$params = array();

# Add user filter
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

# Add usage filter
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

# Query the database
if(! $db->prepare("getData", "SELECT * FROM `User_Login` {$clause} {$order} {$limit}"))
	die($db->error());
foreach($params as $param)
	$db->param("getData", "s", $param);
$result = $db->execute("getData");

# Output result
if($result !== null) {
	
	# Output data table
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
		echo "</table></br>";
		
		$pages = 4;
		# Output paging links
		if($pages >= 10) {
			
		}
		else {
			for($i=1; $i<=$pages; ++$i)
				echo "<a href=\"#\">{$i}</a>";
		}
	}
	
	# Output download file
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