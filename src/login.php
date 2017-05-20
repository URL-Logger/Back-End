<?php
$user = null;
if(isset($_POST['login'])) {
	$user = (!empty($_POST['user']))? $_POST['user'] : "";
	$pass = (!empty($_POST['pass']))? $_POST['pass'] : "";
	$db = DB::connect($_DB['HOST'], $_DB['READ_ADMIN_LOGIN']['USER'], $_DB['READ_ADMIN_LOGIN']['PASS'], $_DB['DATABASE']);
	if(!$db->prepare("GetUserLogin", "SELECT ID, Email, Password, Secure FROM `Admin_Login` WHERE Email=? LIMIT 1"))
		die($db->error());
	$db->param("GetUserLogin", "s", $user);
	$result = $db->execute("GetUserLogin");
	if($result !== null) {
		if($result[0]['Secure'] !== null) {
			$dbs = DB::connect($_DB['HOST'], $_DB['READ_SECURITY_LOGIN']['USER'], $_DB['READ_SECURITY_LOGIN']['PASS'], $_DB['DATABASE']);
			$dbs->prepare("getSalt", "SELECT Salt FROM `Security_Salt` WHERE ID=?");
			$dbs->param("getSalt", "i", $result[0]['Secure']);
			$res_salt = $dbs->execute("getSalt");
			if($res_salt) {
				if(password_verify($pass. $res_salt[0]['Salt'], $result[0]['Password'])) {
					$_SESSION['ADMIN_USER'] = $result[0]['ID'];
					header("Refresh: 0");
					exit;
				} else $out = "Invalid login credentials.";
			} else $out = "An internal error has occurred.";
		}
		else {
			if($pass == $result[0]['Password']) {
				$_SESSION['ADMIN_USER'] = $result[0]['ID'];
				header("Refresh: 0");
				exit;
			} else $out = "Invalid login credentials.";
		}
	} else $out = "Invalid login credentials.";
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Utelem - Login</title>
		<style>
			* {
				box-sizing: border-box;
			}
			
			body {
				background: #000;
			}
		
			#popup {
				display: block;
				position: absolute;
				top: calc( (100% - 250px) / 2.5 );
				left: 0px;
				right: 0px;
				width: 400px;
				height: 250px;
				background: #F4F3E5;
				border-radius: 18px;
				margin: 0 auto;
				overflow: hidden;
			}
			#popup .header {
				display: block;
				position: relative;
				width: 100%;
				height: 48px;
				background: #3B65AF;
				color: #F4F3E5;
				border-bottom: 1px solid #000;
				padding: 0px 24px 0px 18px;
				line-height: 48px;
				font-family: "Yu Gothic UI";
				font-size: 20px;
			}
			#popup .content {
				display: block;
				position: relative;
				width: 100%;
				height: auto;
			}
			
			#popup .content .status {
				display: block;
				position: relative;
				width: 100%;
				height: 36px;
				padding-left: 12px;
				line-height: 36px;
				font-family: "Yu Gothic UI";
				font-size: 14px;
				text-align: left;
			}
			
			#popup .content input[type=text], #popup .content input[type=password] {
				display: block;
				width: 80%;
				height: 36px;
				background: #F4F4F4;
				border: 1px solid #929596;
				border-radius: 4px;
				box-shadow: inset 1px 0px 2px #929596;
				outline: 0;
				padding: 0px 6px 2px 6px;
				margin-left: 10%;
				margin-bottom: 6px;
				line-height: 34px;
				font-family: "Yu Gothic UI";
				font-size: 16px;
				text-align: left;
				vertical-algin: top;
			}
			
			#popup .content input[type=submit] {
				display: block;
				width: 80%;
				height: 42px;
				background: #F4F3E5;
				border: 1px solid #929596;
				border-radius: 4px;
				outline: 0;
				margin-left: 10%;
				line-height: 38px;
				font-family: "Yu Gothic UI";
				font-size: 18px;
				text-align: center;
			}
			
			.logo {
				display: inline-block;
				position: relative;
				width: auto;
				height: auto;
				background-image: url('/src/images/Utelem-graphic-icon-blue-bk 2.png');
				background-position: center;
				background-size: contain;
				background-repeat: no-repeat;
				border: 0;
				border-radius: 4px;
				vertical-align: top;
			}
		</style>
		<script></script>
	</head>
	<body>
		<div id="popup">
			<div class="header">Login</div>
			<div class="content">
				<div class="status"><?php if(isset($out)) print $out; ?></div>
				<form method="POST">
					<input type="text" name="user" placeholder="User" value="<?=$user?>"/>
					<input type="password" name="pass" placeholder="Password"/>
					<input type="submit" name="login" value="Login"/>
				</form>
			</div>
		</div>
	</body>
</html>