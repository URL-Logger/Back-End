<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php"); ?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Utelem - Settings</title>
	</head>
	<body>
		<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php");
		if(has_privilege("F")) {
			echo "<a href=\"flush/?browser\">Flush Browser Data</a></br>";
			echo "<a href=\"flush/?mobile\">Flush Mobile Data</a></br>";
		}
		?>
	</body>
</html>