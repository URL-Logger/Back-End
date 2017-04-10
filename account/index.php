<?php
$_USER = isset($_SESSION['ADMIN_USER'])? $_SESSION['ADMIN_USER'] : 1;
include("{$_SERVER['DOCUMENT_ROOT']}/manage/edit/index.php");
?>