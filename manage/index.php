<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");

deny_on('a');

$DBU = $_DB['READ_ADMIN_LOGIN'];
$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
$search = empty($_GET['q'])? "" : $_GET['q'];
if(!$search)
	$result = $db->query("SELECT ID, Email, Name, Password FROM `Admin_Login` ORDER BY Name");
else {
	$terms = explode(' ', $search);
	$query = "1";
	$params = array();
	foreach($terms as $term) {
		$query .= " AND (Name LIKE ? OR Email LIKE ?)";
		for($i=0; $i<2; $i++)
			$params []= "%{$term}%";
	}
	
	$db->prepare("getUsers", "SELECT ID, Email, Name, Password FROM `Admin_Login` WHERE {$query} ORDER BY Name");
	foreach($params as $param)
		$db->param("getUsers", "s", $param);
	$result = $db->execute("getUsers");
}
?>
<style>
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
<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php"); ?>
<a href="add/">Create Administrator</a></br>
<form method="GET"><input type="text" name="q" placeholder="Search" value="<?php echo $search; ?>" /><input type="submit" value="Go"/></form>
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
				<td><a href=\"edit/?id={$entry['ID']}\">Edit</a></td>
				<td><a href=\"delete/?id={$entry['ID']}\">Delete</a></td>
			</tr>";
		}
	}
	?>
</table>
