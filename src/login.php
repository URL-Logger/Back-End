<?php
$user = null;
if(isset($_POST['login'])) {
	$user = (!empty($_POST['user']))? $_POST['user'] : "";
	$pass = (!empty($_POST['pass']))? $_POST['pass'] : "";
	$db = DB::connect($_DB['HOST'], $_DB['READ_ADMIN_LOGIN']['USER'], $_DB['READ_ADMIN_LOGIN']['PASS'], $_DB['DATABASE']);
	if(!$db->prepare("GetUserLogin", "SELECT ID, Email, Password FROM `Admin_Login` WHERE Email=? LIMIT 1"))
		die($db->error());
	$db->param("GetUserLogin", "s", $user);
	$result = $db->execute("GetUserLogin");
	if($result !== null) {
		if(password_verify($pass, $result[0]['Password'])) {
			$_SESSION['ADMIN_USER'] = $result[0]['ID'];
			header("Refresh: 0");
			exit;
		} else $out = "Invalid login credentials.";
	} else $out = "Invalid login credentials.";
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Utelem - Login</title>
		<style>
			<?php include_once("{$_SERVER['DOCUMENT_ROOT']}/src/styles/layout.php"); ?>
			
			body {
				background-image: url('/src/images/landingpage1.jpg');
				background-size: cover;
			}
		
			#popup {
				display: block;
				position: absolute;
				top: calc( (100% - 250px) / 2.5 );
				left: 0px;
				right: 0px;
				width: 400px;
				height: 250px;
				background: <?=$C_PRIMARY?>;
				border: 4px solid #000;
				border-radius: 18px;
				box-shadow: 2px 2px 6px <?=$C_BORDER?>;
				margin: 0 auto;
				overflow: hidden;
			}
			#popup .header {
				display: block;
				position: relative;
				width: 100%;
				height: 48px;
				background: #3B65AF;
				border-bottom: 1px solid #000;
				color: <?=$C_PRIMARY?>;
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
				box-shadow: inset 1px 0px 2px #929596;
				border: 1px solid #929596;
				border-radius: 4px;
				outline: 0;
				color: #000;
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
				background: <?=$C_PRIMARY?>;
				border: 1px solid #929596;
				border-radius: 4px;
				outline: 0;
				margin-left: 10%;
				line-height: 38px;
				font-family: "Yu Gothic UI";
				font-size: 18px;
				text-align: center;
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