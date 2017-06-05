<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
$user = $_GET['id'];

deny_on('aA');

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
<!DOCTYPE HTML>
<html>
	<head>
		<title>Utelem - Remove Account</title>
		<link rel="icon" href="<?=$_CONFIG['FAVICON']?>" type="image/x-icon"/>
		<style><?php include_once("{$_SERVER['DOCUMENT_ROOT']}/src/styles/layout.php"); ?>
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
		<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php");

		if($user != 1) {
			echo "Are you sure you want to delete the following account?</br></br>";
			echo "Name: {$result[0]['Name']}</br>";
			echo "Email: {$result[0]['Email']}</br>";
			echo "</br>";
		}
		else {
			echo "You may not delete the primary administrator account.</br></br>";
		}
		?>
		<form method="POST">
			<?php if($user != 1)
				echo "<input type=\"submit\" name=\"delete\" value=\"Delete Account\" style=\"background: #FAA;\"/>";
			?>
			<input type="submit" name="cancel" value="Cancel Operation"/>
		</form>
		</br>
	</body>
</html>