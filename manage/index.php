<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");

# deny if user does not have View Admin access
deny_on('a');

$DBU = $_DB['READ_ADMIN_LOGIN'];
$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);

$search = empty($_GET['q'])? "" : $_GET['q'];

# if no search is given, grab all accounts
if(!$search)
	$result = $db->query("SELECT ID, Email, Name, Password FROM `Admin_Login` ORDER BY Name");

# if search is given, grab accounts based on search terms
else {
	# break the query into terms by spaces
	$terms = explode(' ', $search);
	$query = "1";
	
	# build the query from terms
	$params = array();
	foreach($terms as $term) {
		$query .= " AND (Name LIKE ? OR Email LIKE ?)";
		for($i=0; $i<2; $i++)
			$params []= "%{$term}%";
	}
	
	# get accounts 
	$db->prepare("getUsers", "SELECT ID, Email, Name, Password FROM `Admin_Login` WHERE {$query} ORDER BY Name");
	foreach($params as $param)
		$db->param("getUsers", "s", $param);
	$result = $db->execute("getUsers");
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Utelem - Administrators</title>
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
		form {
			margin: 0;
		}
		</style>
	</head>
	<body>
		<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php"); ?>
		<div id="maincontent">
			<div class="menu">
				<a class="button" href="add/">New</a>
				<div class="spacer"></div>
			</div>
			</br>
			<table>
				<tr class="header">
					<td>Account</td>
					<td>Name</td>
					<td></td>
					<td></td>
				</tr>
				<?php
				if($result) {
					foreach($result as $entry) {
						echo "<tr>
							<td>{$entry['Email']}</td>
							<td>{$entry['Name']}</td>
							<td style=\"text-align: right;\">
							<a href=\"edit/?id={$entry['ID']}\">Edit</a>";
						if($entry['ID'] != 1)
							echo " | <a href=\"delete/?id={$entry['ID']}\">Delete</a>";
						echo "</td></tr>";
					}
				}
				?>
			</table>
		</div>
	</body>
</html>
