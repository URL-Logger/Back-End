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
			
			.boxmenu {
				display: block;
				position: relative;
				font-size: 0;
				text-align: center;
			}

			.boxmenu .button {
				display: inline-block;
				position: relative;
				width: 25%;
				height: 224px;
				background-color: <?=$C_SECONDARY?>;
				background-size: contain;
				background-repeat: no-repeat;
				background-position: center center;
				box-shadow: 2px 2px 6px <?=$C_BORDER?>;
				border: 3px solid <?=$C_BORDER?>;
				border-radius: 32px;
				color: <?=$C_PRIMARY?>;
				margin: 1%;
				line-height: 2.2em;
				font-size: 14px;
				vertical-align: top;
			}
			
			.boxmenu .button.disabled {
				background-color: <?=$C_TERNARY?>;
				opacity: 0.5;
			}
		</style>
	</head>
	<body>
		<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php"); ?>
		<div class="boxmenu" style="top: 2%;">
			<?php
			if(has_privilege("u"))
				echo "<a class=\"button\" href=\"users/\" style=\"background-image: url('/src/images/graphics/users.png');\">User Accounts</a>";
			else
				echo "<div class=\"button disabled\" style=\"background-image: url('/src/images/graphics/users.png');\">User Accounts</div>";
			
			if(has_privilege("D"))
				echo "<a class=\"button\" href=\"download/?browser\" style=\"background-image: url('/src/images/graphics/browser.png');\">Browser Data</a>";
			else
				echo "<div class=\"button disabled\" style=\"background-image: url('/src/images/graphics/browser.png');\">Browser Data</div>";
			
			if(has_privilege("D"))
				echo "<a class=\"button\" href=\"download/?mobile\" style=\"background-image: url('/src/images/graphics/mobile.png');\">Mobile Data</a>";
			else
				echo "<div class=\"button disabled\" style=\"background-image: url('/src/images/graphics/mobile.png');\">Mobile Data</div>";
			
			echo "</br>";
			
			if(has_privilege("a"))
				echo "<a class=\"button\" href=\"manage/\" style=\"background-image: url('/src/images/graphics/admins.png');\">Admin Accounts</a>";
			else
				echo "<div class=\"button disabled\" style=\"background-image: url('/src/images/graphics/admins.png');\">Admin Accounts</div>";
			
			if(has_privilege("F"))
				echo "<a class=\"button\" href=\"flush/?browser\" style=\"background-image: url('/src/images/graphics/flush_browser.png');\">Flush Browser Data</a>";
			else
				echo "<div class=\"button disabled\" style=\"background-image: url('/src/images/graphics/flush_browser.png');\">Flush Browser Data</div>";
			
			if(has_privilege("F"))
				echo "<a class=\"button\" href=\"flush/?mobile\" style=\"background-image: url('/src/images/graphics/flush_mobile.png');\">Flush Mobile Data</a>";
			else
				echo "<div class=\"button disabled\" style=\"background-image: url('/src/images/graphics/flush_mobile.png');\">Flush Mobile Data</div>";
			?>
		</div>
	</body>
</html>