<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");

deny_on("F");

# get which table is being flushed
$mode = "";
if(isset($_GET['browser'])) $mode = "browser";
else if(isset($_GET['mobile'])) $mode = "mobile";

# if "Cancel" is pressed, go back
if(isset($_POST['cancel']) || !$mode) {
	header("location: ..");
	exit;
}

# if "Delete" is pressed, truncate the table
else if(isset($_POST['submit'])) {
	$DBU = $_DB['ROOT'];
	$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
	if($mode == "browser")
		$db->query("TRUNCATE TABLE `Collection_Chrome`");
	else if($mode == "mobile")
		$db->query("TRUNCATE TABLE `Collection_Android`");
	header("location: ..");
	exit;
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Utelem - Flush Data</title>
		<link rel="icon" href="<?=$_CONFIG['FAVICON']?>" type="image/x-icon"/>
		<style><?php include_once("../src/styles/layout.php"); ?>
		body {
			background: <?=$C_PRIMARY?>;
		}
		</style>
	</head>
	<body>
		<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php"); ?>
		<b>Flush <?php echo ($mode == "browser")? "Browser" : (($mode == "mobile")? "Mobile" : ""); ?> Data</b></br>
		</br>
		This will delete all <?=$mode?> data. You may not reverse this action.</br>
		Are you sure you want to proceed?</br>
		<br>
		<form method="POST">
			<input type="submit" name="submit" value="Delete Collected Data" style="background: #FAA;"/>
			<input type="submit" name="cancel" value="Cancel Operation"/></br>
		</form>
	</body>
</html>