<?php
# This file contains database host and login information.
# Users are taken from the src/misc/users.php file
# and added to the $_DB global variable with
# properties USER and PASS.
# The ROOT user is entered in this file.

require_once("{$_SERVER['DOCUMENT_ROOT']}/src/misc/users.php");

global $_DB;

# database information
$_DB = array(
	'HOST'=> "main.cm9mres9mcqi.us-west-1.rds.amazonaws.com",
	'DATABASE'=> "main",
	'ROOT'=> array(
		'USER'=> "logger_root",
		'PASS'=> "vGlNFIWgccw8gZtMBiFMnnOW"
	)
);

# load users into database information
foreach($_DB_USERS as $user=>$props) {
	$_DB[$props['Name']] = array(
		'USER'=>$user,
		'PASS'=>$props['Password']
	);
}