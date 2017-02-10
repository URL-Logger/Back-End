<?php
ob_start();
$mode = $_POST['mode'];
$data = json_decode($_POST['data']);

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
function LOGIN() {}

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
function SEND() {}

/* Check-In
 Parameters:
	?
*/
function CHECKIN() {}

switch($mode) {
	case "login": LOGIN(); break;
	case "send": SEND(); break;
	case "checkin": CHECKIN(); break;
	default: exit;
}

ob_end_clean();
exit;
?>