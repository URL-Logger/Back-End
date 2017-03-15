<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
$db['adminr'] = DB::connect($_DB['HOST'], $_DB['ADMIN_LOGIN_R']['USER'], $_DB['ADMIN_LOGIN_R']['PASS'], $_DB['DATABASE']);
$admin_users = $db['adminr']->query("SELECT Email, Permissions FROM `Admin_Login` ORDER BY Email ASC");
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title></title>
		<style></style>
		<script></script>
	</head>
	<body>
		<table>
		<?php
		if($admin_users) {
			foreach($admin_users as $user) {
				print "<tr><td>{$user['Email']}</td><td>{$user['Permissions']}</td></tr>";
			}
		}
		?>
		<hr>
		<table>
			<tr><td>Account Recovery</td><td><select><option>Allow</option><option>Deny</option></select></td></tr>
			<tr><td></td><td></td></tr>
			<tr><td></td><td></td></tr>
			<tr><td></td><td></td></tr>
			<tr><td></td><td></td></tr>
		</table>
	</body>
</html>