<?php
if(isset($_POST['submit'])) {
	$key = (!empty($_POST['key']))? $_POST['key'] : null;
	if($key !== null) {
		
	}
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
			Please check your email for the recovery key.</br>
			<input type="text" name="key" placeholder="Key"/></br>
			<input type="password" name="pass" placeholder="New Password"/></br>
			<input type="password" name="pass_confirm" placeholder="Confirm Password"/></br>
			<input type="submit" name="submit" value="Confirm"/>
		</form>
	</body>
</html>