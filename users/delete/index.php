<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
$_USER = empty($_GET['id'])? 0 : $_GET['id'];

deny_on('uU');

$DBU = $_DB['READ_USER_INFO'];
$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
$db->prepare("getUserInfo", "SELECT * FROM `User_Login` WHERE ID=? LIMIT 1");
$db->param("getUserInfo", "i", $_USER);
$result = $db->execute("getUserInfo");
if(!$result || isset($_POST['cancel'])) {
	header("Location: ..");
	exit;
}

if(isset($_POST['delete'])) {
	if($result[0]['RespondentID']) {
		$DBU = $_DB['ROOT'];
		$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
		$db->prepare("deleteInfo", "DELETE FROM `respondents` WHERE respondent_id=? LIMIT 1");
		$db->param("deleteInfo", "i", $result[0]['RespondentID']);
		$db->execute("deleteInfo");
	}
	
	$DBU = $_DB['DELETE_USER'];
	$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
	$db->prepare("deleteLogin", "DELETE FROM `User_Login` WHERE ID=? LIMIT 1");
	$db->param("deleteLogin", "i", $_USER);
	$db->execute("deleteLogin");
	
	header("Location: ..");
	exit;
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Utelem - Remove Account</title>
		<link rel="icon" href="<?=$_CONFIG['FAVICON']?>" type="image/x-icon"/>
		<style><?php include_once("../../src/styles/layout.php"); ?>
		body {
			background: <?=$C_PRIMARY?>;
		}
		</style>
	</head>
	<body>
		<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php"); ?>
		<div id="maincontent">
			Are you sure you want to delete the following user?</br>
			<?php
			echo "ID: {$result[0]['ID']}</br>";
			echo "Email: {$result[0]['Email']}</br>";
			?>
			</br>
			<form method="POST">
				<input type="submit" name="delete" value="Delete User" style="background: #FAA;"/>
				<input type="submit" name="cancel" value="Cancel Operation"/>
			</form>
		</div>
	</body>
</html>