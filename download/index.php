<?php require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Control Panel</title>
		<style>
			<?php include("src/styles/layout.php"); ?>
			
			::-webkit-scrollbar { width: 3px; height: 3px; }
			::-webkit-scrollbar-button { width: 0px; height: 0px; }
			::-webkit-scrollbar-thumb { background: #444; }
			::-webkit-scrollbar-track { background: transparent; }
			::-webkit-scrollbar-corner { background: transparent; }
			
			#sel_filter {
				display: block;
				position: relative;
				width: 100%;
				height: 48px;
				background: #FFF;
				border: 0;
				outline: 0;
				font-family: "Yu Gothic UI";
				font-size: 18px;
				text-align: center;
			} #sel_filter option {
				display: block;
				position: relative;
				width: 100%;
				height: 32px;
				font-family: inherit;
				font-size: inherit;
				text-align: center;
			}
			
			#list_filters {
				display: block;
				position: relative;
				width: 100%;
				height: calc(100% - 96px);
				background: #DDD;
				border-top: 1px solid #AAA;
				border-bottom: 1px solid #AAA;
				overflow-y: scroll;
			}
			
			#list_filters .item {
				display: block;
				position: relative;
				width: 100%;
				height: auto;
				border-bottom: 1px solid #888;
				padding: 2px 4px 2px 4px;
				font-family: "Yu Gothic UI";
				
			}	#list_filters .item .exit {
				display: block;
				position: absolute;
				top: 0px;
				right: 0px;
				width: 20px;
				height: 20px;
				border: 0;
				outline: 0;
				line-height: 20px;
				font-size: 16px;
				text-align: center;
				cursor: pointer;
			}
			
			#display {
				overflow: auto;
			}

			#b_download {
				display: block;
				position: relative;
				width: 100%;
				height: 48px;
				background: #EEE;
				border: 0;
				outline: 0;
				padding: 0;
				margin: 0;
				line-height: 48px;
				font-family: "Yu Gothic UI";
				font-size: 18px;
				text-align: center;
				cursor: pointer;
			}
			
			table {
				width: auto;
				height: auto;
				border-collapse: collapse;
			}
			table td {
				max-width: 250px;
				border-bottom: 1px solid #CCC;
				padding: 4px 16px 4px 16px;
				white-space: pre;
				overflow: hidden;
			}
			table td.header {
				font-weight: bold;
			}
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script><?php include("src/scripts/download.php"); ?></script>
	</head>
	<body onload="update()">
		<div id="header"></div>
		<div id="maincontent">
			<form id="form" class="section" method='POST' action='download.php' target='_blank' style="width: 300px; height: 100%; border-right: 1px solid #888;">
				<select id="sel_filter" onchange="addFilter()">
					<option>- Select Filter -</option>
					<option>Limit Rows</option>
					<option>Date</option>
					<option>Date Range</option>
					<option>User ID</option>
				</select>
				<div id="list_filters"></div>
				<input id="b_download" type="button" onclick="document.getElementById('form').submit()" value="Download"/>
			</form><div id="display" class="section" style="width: calc(100% - 300px); height: 100%;"></div>
		</div>
	</body>
</html>