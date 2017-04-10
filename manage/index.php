<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
$DBU = $_DB['READ_ADMIN_LOGIN'];
$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
$result = $db->query("SELECT ID, Email FROM `Admin_Login` ORDER BY ID");
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
		<td>User</td>
		<td></td>
		<td></td>
	</tr>
	<?php
	if($result) {
		foreach($result as $entry) {
			echo "<tr>
				<td>{$entry['Email']}</td>
				<td></td>
				<td><a href=\"/manage/edit/?user={$entry['ID']}\">Edit</a></td>
				<td><a href=\"/manage/delete/?user={$entry['ID']}\">Delete</a></td>
			</tr>";
		}
	}
	?>
</table>
