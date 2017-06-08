<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
deny_on('u');
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Utelem - Users</title>
		<link rel="icon" href="<?=$_CONFIG['FAVICON']?>" type="image/x-icon"/>
		<style><?php include_once("../src/styles/layout.php"); ?>
		body {
			background: <?=$C_PRIMARY?>;
		}
		table {
			width: 100%;
		}
		table tr.header td {
			font-weight: bold;
		}
		table tr td {
			padding: 2px 8px 2px 8px;
			text-align: left;
		}
		table tr th {
			padding: 2px 8px 2px 8px;
			text-align: left;
		}
		
		.region {
			display: block;
			position: relative;
			width: 100%;
			height: 100%;
		}
		
		.popup {
			display: none;
			position: absolute;
			top: 30px;
			right: -1px;
			width: 241px;
			height: auto;
			background: <?=$C_PRIMARY?>;
			border: 1px solid <?=$C_BORDER?>;
			color: #000;
			padding: 3px 6px 3px 6px;
			marign: 0;
			line-height: 1.4em;
			text-align: left;
		}
		
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script>
		function update() {
			$.post("data.php",
				$("#filters").serialize(),
				function(data) {
					document.getElementById("data").innerHTML = data;
			});
		}
		
		function fn_download() {
			document.getElementById("filters").submit();
		}
		
		function toggle(id) {
			var obj = document.getElementById(id);
			if(obj.style.display != "block")
				obj.style.display = "block";
			else
				obj.style.display = "none";
		}
		
		$(document).ready(function() {
			$(window).keydown(function(event){
				if(event.keyCode == 13) {
					event.preventDefault();
					return false;
				}
			});
		});
		</script>
	</head>
	<body onload="update()">
		<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php"); ?>
		
		<div id="maincontent">
			<div class="menu">
				<a class="button" href="add/">Add User</a>
				<div class="spacer"></div>
				<a class="button" href="#" onclick="fn_download()">Export</a>
				<div class="button" href="#"><a class="region" onclick="toggle('pop_filters');">Filter</a>
				<div id="pop_filters" class="popup">
					<form id="filters" method="POST" action="data.php?download" target="_blank">
						<table class="fieldset">
							<tr><th></th> <td><input type="button" value="Apply" onclick="update()"/></td></tr>
							
							<tr><th>Limit</th> <td><input type="text" name="limit" value="150"/></td></tr>
							<tr caption="ID or Email"><th>User</th> <td><input type="text" name="user"/></td></tr>
							<tr><th>Usage</th> <td><select name="usage">
									<option value="">Select Usage</option>
									<option value="active">Active since</option>
									<option value="inactive">Inactive since</option>
									<option value="never">Never active</option>
								</select></td></tr>
							<tr><th></th> <td><input type="date" name="date"/></td></tr>
							<tr><th></th> <td><select name="platform">
									<option value="">Platform (Either)</option>
									<option value="browser">Browser</option>
									<option value="mobile">Mobile</option>
								</select></td></tr>
						</table>
					</form>
				</div></div>
			</div></br>
			<div id="data">
			</div>
		</div>
	</body>
</html>