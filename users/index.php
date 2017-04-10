<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
$DBU = $_DB['READ_USER_LOGIN'];
$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
$result = $db->query("SELECT ID, Email FROM `User_Login` ORDER BY ID");
?>
<style>
table tr.header td {
	font-weight: bold;
}
table tr td {
	padding: 2px 8px 2px 8px;
}
</style>
<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php"); ?>
<input type="text" name="search" placeholder="Search"/></br>
<table>
	<tr class="header">
		<td>Account</td>
		<td></td>
		<td></td>
	</tr>
	<?php
	if($result) {
		foreach($result as $entry) {
			echo "<tr>
				<td>{$entry['Email']}</td>
				<td><a href=\"/users/edit/?user={$entry['ID']}\">Edit</a></td>
				<td><a href=\"/users/delete/?user={$entry['ID']}\">Delete</a></td>
			</tr>";
		}
	}
	?>
</table>
