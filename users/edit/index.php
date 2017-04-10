<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php");

if(!isset($_USER)) {
	if(!empty($_GET['user'])) {
		$_USER = $_GET['user'];
		echo "<a href='..'/>Back</a><br>";
	}
	else {
		header("Location: /users/");
		exit;
	}
}

$DBU = $_DB['READ_USER_LOGIN'];
$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
$db->prepare("getUser", "SELECT Email FROM `User_Login` WHERE ID=? LIMIT 1");
$db->param("getUser", "i", $_USER);
$result = $db->execute("getUser");
$db->close();

if($result) {
	$email = $result[0]['Email'];
	$name = "";
	if(isset($_POST['submit'])) {
		$email = isset($_POST['email'])? $_POST['email'] : "";
		$name = isset($_POST['name'])? $_POST['name'] : "";
		/*
		if($email !== "" && $name !== "") {
			$DBU = $_DB['WRITE_ADMIN_INFO'];
			$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
			$db->prepare("setAdmin", "UPDATE `Admin_Login` SET Email=? WHERE ID=?");
			$db->param("setAdmin", "s", $email);
			$db->param("setAdmin", "i", $_USER);
			$db->execute("setAdmin");
			$db->close();
			header("Refresh:0");
			exit;
		}*/
	}
}
else {
	header("Location: /manage/");
	exit;
}

?>
<div id="maincontent">
	<form method="POST">
		<input type="text" placeholder="Email" name="email" value="<?=$email?>"/><br>
		<br>
		<input type="password" name="newPassword" placeholder="New Password"/><br>
		<input type="password" name="confirmPassword" placeholder="Confirm Password"/><br>
		<br>
		<input type="password" name="password" placeholder="Password"/> <input type="submit" name="submit" value="Save"/><br>
	</form>
</div>