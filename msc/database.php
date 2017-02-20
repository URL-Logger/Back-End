<?php
$_CONNECTION = array(
	'LOGIN'=> array(
		'HOST'=> "url-logger-db1.cm9mres9mcqi.us-west-1.rds.amazonaws.com", //$_SERVER['RDS_HOSTNAME'],
		'USER'=> "masteruser", // $_SERVER['RDS_USERNAME'],
		'PASS'=> "mastpass", //$_SERVER['RDS_PASSWORD'],
		'BASE'=> "URL_Logger_DB1" //$_SERVER['RDS_DB_NAME']
	),
	'POST_EXTENSION'=> array(
		'HOST'=> "url-logger-db1.cm9mres9mcqi.us-west-1.rds.amazonaws.com", //$_SERVER['RDS_HOSTNAME'],
		'USER'=> "masteruser", // $_SERVER['RDS_USERNAME'],
		'PASS'=> "mastpass", //$_SERVER['RDS_PASSWORD'],
		'BASE'=> "URL_Logger_DB1" //$_SERVER['RDS_DB_NAME']
	),
	'POST_ANDROID'=> array(
		'HOST'=> "url-logger-db1.cm9mres9mcqi.us-west-1.rds.amazonaws.com", //$_SERVER['RDS_HOSTNAME'],
		'USER'=> "masteruser", // $_SERVER['RDS_USERNAME'],
		'PASS'=> "mastpass", //$_SERVER['RDS_PASSWORD'],
		'BASE'=> "URL_Logger_DB1" //$_SERVER['RDS_DB_NAME']
	)
);