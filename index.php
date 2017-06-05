<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php"); ?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Utelem - Home</title>
		<link rel="icon" href="<?=$_CONFIG['FAVICON']?>" type="image/x-icon"/>
		<style><?php include_once("./src/styles/layout.php"); ?>
			body {
				background-image: url('/src/images/landingpage1.jpg');
				background-size: cover;
			}
			
			.boxmenu .button {
				border: 4px solid #000;
				border-radius: 18px;
				box-shadow: 2px 2px 6px <?=$C_BORDER?>;
			}
		</style>
	</head>
	<body>
		<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php"); ?>
		<div class="boxmenu" style="top: 2%;">
			<?php
			$pv_user = has_privilege("u")? "" : "disabled";
			$pv_admin = has_privilege("u")? "" : "disabled";
			$pv_data = has_privilege("u")? "" : "disabled";
			$pv_flush = has_privilege("u")? "" : "disabled";
			?>
			
			<a class="button <?=$pv_user?>" href="users/">User Accounts</a>
			<a class="button <?=$pv_data?>" href="download/?browser">Browser Data</a>
			<a class="button <?=$pv_data?>" href="download/?mobile">Mobile Data</a>
			</br>
			<a class="button <?=$pv_admin?>" href="manage/">Admin Accounts</a>
			<a class="button <?=$pv_flush?>" href="flush/?browser">Flush Browser Data</a>
			<a class="button <?=$pv_flush?>" href="flush/?mobile">Flush Mobile Data</a>
		</div>
	</body>
</html>