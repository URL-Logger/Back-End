<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
$mode = "";
if(isset($_GET['browser'])) $mode = "browser";
else if(isset($_GET['mobile'])) $mode = "mobile";

if(isset($_POST['cancel']) || !$mode) {
	header("location: ..");
	exit;
}
else if(isset($_POST['submit'])) {
	// truncate database
}
?>
<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php"); ?>
<a href="..">Back</a></br>
</br>
<b>Delete <?php echo ($mode == "browser")? "Browser" : (($mode == "mobile")? "Mobile" : ""); ?> Data</b></br>
</br>
This will delete all <?=$mode?> data. You may not reverse this action.</br>
Are you sure you want to proceed?</br>
<br>
<form method="POST">
	<input type="submit" name="submit" value="Delete Collected Data" style="background: #FAA;"/>
	<input type="submit" name="cancel" value="Cancel Operation"/></br>
</form>