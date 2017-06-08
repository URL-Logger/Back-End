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
	font-family: 'Verdana';
	font-size: 14px;
	overflow: auto;
}

a {
	color: <?=$C_SECONDARY?>;
	text-decoration: none;
	cursor: pointer;
}

#container {
	display: block;
	position: relative;
	width: 100%;
	height: calc(100% - 2em);
	overflow-x: hidden;
	overflow-y: scroll;
}

#maincontent {
	display: block;
	position: relative;
	top: 6px;
	width: 900px;
	height: auto;
	margin: 0 auto;
}

.menu {
	display: table;
	width: 100%;
	border: 1px solid <?=$C_BORDER?>;
	border-collapse: collapse;
	font-size: 0;
}
.menu .spacer {
	display: table-cell;
	width: auto;
}
.menu .button {
	display: table-cell;
	position: relative;
	width: 120px;
	height: auto;
	border: 1px solid #000;
	padding: 0px 18px 0px 18px;
	line-height: 2em;
	font-size: 15px;
	text-align: center;
	whitespace: nowrap;
}

.fieldset {
	width: 100%;
	font-size: 14px;
}
.fieldset tr.spacer {
	height: 16px;
}
.fieldset th {
	text-align: right;
	border-right: 1px solid <?=$C_SECONDARY?>;
	padding-right: 4px;
	font-size: 14px;
}
.fieldset td {
	padding: 0;
	font-size: 14px;
}
.fieldset input, select {
	width: 100%;
	border: 1px solid <?=$C_BORDER?>;
	padding: 3px 6px 3px 6px;
	line-height: 1.6em;
	font-size: 14px;
}
.fieldset input[type=button], .fieldset input[type=submit] {
	background: <?=$C_SECONDARY?>;
	color: <?=$C_PRIMARY?>;
}
.fieldset input[type=checkbox], .fieldset input[type=radio] {
	width: 3em;
}

.overlay_background {
	display: block;
	position: absolute;
	top: 0px;
	left: 0px;
	z-index: 1000;
	width: 100%;
	height: 100%;
	background: rgba(0,0,0,0.8);
	text-align: center;
	vertical-align: middle;
}

.overlay_text {
	display: inline-block;
	position: relative;
	top: 45%;
	width: auto;
	height: auto;
	background: <?=$C_PRIMARY?>;
	border-radius: 4px;
	padding: 12px 24px 12px 24px;
	margin: 0 auto;
	line-height: 1.5em;
	text-align: center;
}

<?php include("menu.php"); ?>