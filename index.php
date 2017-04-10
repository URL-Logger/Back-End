<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
$DBU = $_DB['READ_USER_LOGIN'];
$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
$result = $db->query("SELECT COUNT(*) as count FROM `User_Login` LIMIT 1");
$num_users = ($result)? $result[0]['count'] : 0;
?>
<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php"); ?>
<style>
#maincontent {
	display: block;
	width: 100%;
	height: auto;
	padding: 8px;
}
</style>
<div id="maincontent">
Users: <?=$num_users;?></br>
</div>