<?php
ob_start();
$_MODE = $_POST['mode'];
$_DATA = json_decode($_POST['data']);

$_DB = array(
	'LOGIN'=> array(
		'HOST'=> "",
		'USER'=> "",
		'PASS'=> ""
	),
	'SEND'=> array(
		'HOST'=> "",
		'USER'=> "",
		'PASS'=> ""
	)
	'CHECKIN'=> array(
		'HOST'=> "",
		'USER'=> "",
		'PASS'=> ""
	)
);

/* Protocol
 Data should be sent via HTTP/POST.
 The type of request should be sent under 'mode'.
 All other information should be stored as JSON under 'data'.
 See respective requests for data contents.
 
 Modes: login, send, checkin
*/

/* Login
 Paramters:
	`user`
	`pass`
*/

function LOGIN() {
	global $_DATA;
	function encode_password($user, $pass) {
		return $pass;
	}
	
	$user = "";
	$pass = "";
	
	if(!$user || !$pass)
		print "INVALID_PARAMTERS";
	
	$db = new DB();
	$db->connect($DB_HOST, $DB_USER, $DB_PASS, "user");
	$db->prepare("getUser", "SELECT id, password FROM `users` WHERE username=? LIMIT 1");
	$db->param("getUser", "s", $user);
	$result = $db->execute("getUser");
	
	if($result !== null) {
		$pass = encode_password($user, $pass);
		if($pass == $result[0]['password'])
			print $result[0]['id'];
		else
			print "INCORRECT_PASSWORD";
	}
	else
		print "USER_NOT_VALID";
}

/* Send
 Parameters:
	`pid`		The user's ID
	`source`	The source of information: 'PC', 'Mobile'
	`url`		The page URL
	`rurl`		The referral URL
	`title`		The title of the page
	`timestamp`	The timestamp of the collected ata
	`misc`		Addtional data string in JSON format.
*/
function SEND() {
	global $_DATA;
	
	$pid = $_DATA['pid'];
	$url = $_DATA['url'];
	$stamp = $_DATA['timestamp'];
	
}

/* Check-In
 Parameters:
	?
*/
function CHECKIN() {
	global $_DATA;
	
}

switch($_MODE) {
	case "login": LOGIN(); break;
	case "send": SEND(); break;
	case "checkin": CHECKIN(); break;
	default: exit;
}

ob_end_clean();
exit;
?>