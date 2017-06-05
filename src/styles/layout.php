<?php
// Color Scheme:
$C_PRIMARY=    "#FAFAFA"; #F4F3E5
$C_SECONDARY=  "#3B65AF";
$C_BORDER=     "#000000";
$C_TERNARY=    "#929596";
?>

@import url('https://fonts.googleapis.com/css?family=Raleway:500');

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
	font-family: 'Raleway', sans-serif;
	font-size: 14px;
	overflow: auto;
}

a {
	color: <?=$C_SECONDARY?>;
	text-decoration: none;
}

a.broken {
	color: #F44;
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

table {
	width: 100%;
}
tr, td {
	width: auto;
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
	padding: 3px 18px 0px 18px;
	line-height: 1.8em;
	font-size: 14px;
	text-align: center;
	whitespace: nowrap;
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
	background: <?=$C_SECONDARY?>;
	border: 1px solid <?=$C_BORDER?>;
	border-radius: 32px;
	color: <?=$C_PRIMARY?>;
	margin: 1%;
	line-height: 223px;
	font-size: 14px;
	vertical-align: top;
}

.boxmenu .button.disabled {
	background: <?=$C_TERNARY?>;
	opacity: 0.25;
}

.fieldset {
	width: auto;
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
.fieldset input {
	border: 1px solid <?=$C_BORDER?>;
	padding: 3px 6px 3px 6px;
	font-size: 14px;
}
.fieldset input[type='submit'] {
	width: 256px;
}

<?php include("menu.php"); ?>