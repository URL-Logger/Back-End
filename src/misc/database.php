<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/misc/users.php");

$_DB = array(
	'HOST'=> "main.cm9mres9mcqi.us-west-1.rds.amazonaws.com",
	'DATABASE'=> "main",
	'ROOT'=> array(
		'USER'=> "logger_root",
		'PASS'=> "vGlNFIWgccw8gZtMBiFMnnOW"
	)
);
foreach($_DB_USERS as $user=>$props) {
	$_DB[$props['Name']] = array(
		'USER'=>$user,
		'PASS'=>$props['Password']
	);
}