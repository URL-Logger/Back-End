<?php
$out = "";
if(isset($_POST['login'])) {
	$user = (!empty($_POST['user']))? $_POST['user'] : null;
	$pass = (!empty($_POST['pass']))? $_POST['pass'] : null;
	if($user !== null && $pass !== null) {
		$db = DB::connect($_DB['HOST'], $_DB['READ_ADMIN_LOGIN']['USER'], $_DB['READ_ADMIN_LOGIN']['PASS'], $_DB['DATABASE']);
		if(!$db->prepare("GetUserLogin", "SELECT ID, Email, Password, Secure FROM `Admin_Login` WHERE Email=? LIMIT 1"))
			die($db->error());
		$db->param("GetUserLogin", "s", $user);
		$result = $db->execute("GetUserLogin");
		if($result !== null) {
			if($result[0]['Secure'] !== null) {}
			if($pass == $result[0]['Password']) {
				$_SESSION['ADMIN_USER'] = $result[0]['ID'];
				header("Refresh: 0");
				exit;
			}
			else
				print "Invalid password";
		}
		else print "User does not exist.";
	}
	header("Refresh: 2");
	exit;
} else if(isset($_POST['recover'])) {
	
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title></title>
		<style></style>
		<script></script>
	</head>
	<body>
		<?php if($out !== null) print $out; ?></br>
		<form method="POST">
			<input type="text" name="user" placeholder="Email"/></br>
			<input type="password" name="pass" placeholder="Password"/></br>
			<input type="submit" name="login" value="Login"/></br>
		</form>
	</body>
</html>