<?php
// Color Scheme:
$C_PRIMARY=    "#FAFAFA"; #F4F3E5
$C_SECONDARY=  "#3B65AF";
$C_BORDER=     "#000000";
$C_TERNARY=    "#929596";
?>

::-webkit-scrollbar { width: 6px; height: 4px; }
::-webkit-scrollbar-button { width: 0px; height: 0px; }
::-webkit-scrollbar-thumb { background: <?=$C_SECONDARY?>; }
::-webkit-scrollbar-track { background: <?=$C_BORDER?>; }
::-webkit-scrollbar-corner { background: transparent; }

* { box-sizing: border-box; }

html, body {
	display: block;
	position: relative;
	width: 100%;
	height: 100%;
	background: <?=$C_PRIMARY?>;
	padding: 0;
	margin: 0;
	font-family: "Verdana";
	font-size: 14px;
	overflow: hidden;
}

a {
	color: <?=$C_SECONDARY?>;
	text-decoration: none;
}

#container {
	display: block;
	position: relative;
	width: 100%;
	height: calc(100% - 2em);
	overflow-x: hidden;
	overflow-y: scroll;
}

<?php include("menu.php"); ?>