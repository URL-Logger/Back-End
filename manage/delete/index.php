<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
$user = $_GET['id'];

deny_on('A');

$DBU = $_DB['READ_ADMIN_LOGIN'];
$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
$db->prepare("getUser", "SELECT ID, Email, Name FROM `Admin_Login` WHERE ID=? LIMIT 1");
$db->param("getUser", "i", $user);
$result = $db->execute("getUser");
$db->close();
if(!$result) {
	header("location: ..");
	exit;
}

if(isset($_POST['delete'])) {
	$DBU = $_DB['DELETE_ADMIN'];
	$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
	if(!$db->prepare("deleteUser", "DELETE FROM `Admin_Login` WHERE ID=? LIMIT 1"))
		die($db->error());
	$db->param("deleteUser", "i", $user);
	$db->execute("deleteUser");
	header("location: ..");
	exit;
}
else if(isset($_POST['cancel'])) {
	header("location: ..");
	exit;
}
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
Are you sure you want to delete the following account?</br>
</br>
<?php
echo "{$result[0]['ID']}</br>";
echo "{$result[0]['Name']}</br>";
echo "{$result[0]['Email']}</br>";
?>
</br>
<form method="POST">
	<input type="submit" name="delete" value="Delete Account"/>
	<input type="submit" name="cancel" value="Cancel"/>
</form>
</br>