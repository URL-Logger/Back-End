<?php
require_once("../lib/db.php");
require_once("../lib/database.php");
require_once("../lib/secure.php");

if(isset($_POST['submit'])) {
	$user = (isset($_POST['user']))? $_POST['user'] : null;
	$pass = (isset($_POST['pass']))? $_POST['pass'] : null;
	if($user !== null
	  && $pass !== null) {
		$db = DB::connect(
			$_CONNECTION['ADMIN']['HOST'],
			$_CONNECTION['ADMIN']['USER'],
			$_CONNECTION['ADMIN']['PASS'],
			$_CONNECTION['ADMIN']['BASE']
		);
		$db->prepare("getUser", "SELECT UserID, Username, Password FROM `Admin_Users` WHERE Username=? LIMIT 1");
		$db->param("getUser", "s", $user);
		$result = $db->execute("getUser");
		if($result !== null) {
			
		}
	}
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Login</title>
		<style>
		form {
			border: 1px solid #777;
			border-radius: 8px;
			padding: 8px;
			text-align: right;
		}
		</style>
	</head>
	<body>
		<form method="POST">
			<input type="text" name="user" placeholder="Username"/>
			<input type="password" name="pass" placeholder="Password"/>
			<input type="submit" name="submit" value="Login"/>
		</form>
	</body>
</html>