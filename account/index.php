<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");

$p_password = isset($_POST['password'])? $_POST['password'] : null;
$p_email = null;
$p_newpass = null;
$p_conpass = null;

if(isset($_POST['submit'])) {
	
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
		<form method="POST">
			Account Information</br>
			<input type="text" name="email" placeholder="Email" value="<?=$p_email?>"/></br>
			</br>
			Change Password</br>
			<input type="password" name="pass" placeholder="New Password"/></br>
			<input type="password" name="pass_confirm" placeholder="Confirm Password"/></br>
			</br>
			<input type="password" name="password" placeholder="Current Password"/></br>
			<input type="submit" name="submit" value="Save"/></br>
		</form>
	</body>
</html>