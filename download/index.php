<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php");

deny_on('D');

if(isset($_GET['browser']))
	$dataset = "browser";
else if(isset($_GET['mobile']))
	$dataset = "mobile";
else exit;
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Utelem - Download</title>
		<style>
			<?php include_once("../src/styles/layout.php"); ?>
			form {
				margin: 0;
			}

			#header {
				display: block;
				position: relative;
				width: 100%;
				height: 0px;
				background: #FFF;
				border-bottom: 1px solid #888;
			}

			#maincontent {
				display: block;
				position: relative;
				width: 100%;
				height: calc(100% - 2em - 2px);
				border-left: 1px solid #000;
				border-right: 1px solid #000;
				padding: 0;
				margin: 0 auto;
			}

			.section {
				display: inline-block;
				position: relative;
				height: 100%;
				vertical-align: top;
			}
			
			#refresh {
				width: 100%;
				height: 32px;
				line-height: 32px;
				background: <?=$C_SECONDARY?>;
				border: 0;
				border-top: 1px solid <?=$C_BORDER?>;
				color: <?=$C_PRIMARY?>;
				outline: 0;
				font-size: 16px;
				cursor: pointer;
			}
			
			#sel_filter {
				display: block;
				position: relative;
				width: 100%;
				height: 42px;
				background: <?=$C_PRIMARY?>;
				border: 0;
				outline: 0;
				font-size: 18px;
				text-align: center;
			} #sel_filter option {
				display: block;
				position: relative;
				width: 100%;
				height: 32px;
				background: <?=$C_PRIMARY?>;
				border: <?=$C_BORDER?>;
				font-family: inherit;
				font-size: inherit;
				text-align: center;
			}
			
			#list_filters {
				display: block;
				position: relative;
				width: 100%;
				height: calc(100% - 96px - 30px);
				background: <?=$C_TERNARY?>;
				border-top: 1px solid <?=$C_BORDER?>;
				border-bottom: 1px solid <?=$C_BORDER?>;
				overflow-y: scroll;
			}
			#list_filters .item {
				display: block;
				position: relative;
				width: 100%;
				height: auto;
				background: <?=$C_PRIMARY?>;
				border-bottom: 1px solid <?=$C_BORDER?>;
				padding: 2px 4px 6px 4px;
				font-size: 15px;
				line-height: 28px;
			} #list_filters .item .exit {
				display: block;
				position: absolute;
				top: 0px;
				right: 0px;
				width: 20px;
				height: 20px;
				border: 0;
				outline: 0;
				color: #D22;
				line-height: 20px;
				font-size: 18px;
				text-align: center;
				cursor: pointer;
			} #list_filters .item input {
				width: 100%;
			}
			
			#display {
				overflow: auto;
			}

			#b_download {
				display: block;
				position: relative;
				width: 100%;
				height: 48px;
				background: <?=$C_SECONDARY?>;
				border: 0;
				border-bottom: 1px solid <?=$C_BORDER?>;
				outline: 0;
				color: <?=$C_PRIMARY?>;
				padding: 0;
				margin: 0;
				line-height: 48px;
				font-size: 16px;
				text-align: center;
				cursor: pointer;
			}
			
			table.preview {
				min-width: 100%;
				height: auto;
				padding: 0;
				border-collapse: collapse;
			}
			table.preview td {
				max-width: 250px;
				border-left: 1px solid #E0E0E0;
				border-right: 1px solid #E0E0E0;
				border-bottom: 1px solid <?=$C_TERNARY?>;
				font-size: 14px;
				padding: 4px 8px 4px 8px;
				white-space: pre;
				overflow: auto;
			}
			table.preview td.header {
				font-weight: bold;
			}
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script>
			var mode = "<?=$dataset?>";
			<?php include("script.php"); ?>
		</script>
	</head>
	<body onload="update()">
		<div id="maincontent">
			<form id="form" class="section" method='POST' action='download.php' target='_blank' style="width: 300px; height: 100%; border-right: 1px solid <?=$C_BORDER?>;">
				<input type="hidden" name="<?=$dataset?>"/>
				<select id="sel_filter" onchange="addFilter()">
					<option>Select Filter</option>
					<option>Limit Rows</option>
					<option>Date</option>
					<option>Date Range</option>
					<option>User ID</option>
					<?php
					if($dataset == "browser") {
						echo "<option>Keywords</option>";
					}
					else if($dataset == "mobile") {
						echo "<option>Application</option>";
					}
					?>
				</select>
				<input id="refresh" type="button" value="Refresh" onclick="update()"/>
				<div id="list_filters"></div>
				<input id="b_download" type="button" onclick="document.getElementById('form').submit()" value="Download"/>
			</form><div id="display" class="section" style="width: calc(100% - 300px); height: 100%;"></div>
		</div>
	</body>
</html>