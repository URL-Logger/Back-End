<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");

deny_on('u');

$DBU = $_DB['READ_USER_LOGIN'];
$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
$result = $db->query("SELECT ID, Email, LastSyncBrowser, LastSyncMobile FROM `User_Login` ORDER BY ID");
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Utelem - Users</title>
		<link rel="icon" href="<?=$_CONFIG['FAVICON']?>" type="image/x-icon"/>
		<style><?php include_once("../src/styles/layout.php"); ?>
		body {
			background: <?=$C_PRIMARY?>;
		}
		table tr.header td {
			font-weight: bold;
		}
		table tr td {
			padding: 2px 8px 2px 8px;
		}
		</style>
	</head>
	<body>
		<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php"); ?>
		
		<div id="maincontent">
			<div class="menu">
				<a class="button" href="add/">Add User</a>
				<div class="spacer"></div>
				<a class="button broken" href="">Export</a>
				<a class="button broken" href="">Filter</a>
			</div></br>
			<table>
				<tr class="header">
					<td>ID</td>
					<td>Account</td>
					<td>Browser Sync</td>
					<td>Mobile Sync</td>
					<td></td>
					<td></td>
				</tr>
				<?php
				if($result) {
					foreach($result as $entry) {
						$sync_browser = (strtotime($entry['LastSyncBrowser']) > 0)? date("M d, Y H:m", strtotime($entry['LastSyncBrowser'])) : "Never";
						$sync_mobile = (strtotime($entry['LastSyncMobile']) > 0)? date("M d, Y H:m", strtotime($entry['LastSyncMobile'])) : "Never";
						echo "<tr>
							<td>{$entry['ID']}</td>
							<td>{$entry['Email']}</td>
							<td>{$sync_browser}</td>
							<td>{$sync_mobile}</td>
							<td style=\"text-align: right;\">
								<a href=\"/users/edit/?id={$entry['ID']}\">Edit</a> | 
								<a href=\"/users/delete/?id={$entry['ID']}\">Delete</a>
							</td>
						</tr>";
					}
				}
				?>
			</table>
		</div>
	</body>
</html>