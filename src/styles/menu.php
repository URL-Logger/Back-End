<?php /* Menu Container */ ?>
.menu_main {
	position: relative;
	width: 100%;
	z-index: 100;
	height: auto;
	background: #3B65AF;
	border-collapse: collapse;
}

<?php /* Element Container */ ?>
.menu_main td {
	position: relative;
	min-width: 100px;
	height: auto;
	border: 1px solid #000;
	padding: 0;
} .menu_main td.spacing {
	width: 100%;
}

<?php /* Menu Element */ ?>
.menu_main .button {
	display: inline-block;
	position: relative;
	width: 100%;
	height: 32px;
	background: #3B65AF;
	color: #F4F3E5;
	font-size: 14px;
}	.menu_main a.button {
	display: block;
	position: relative;
	width: 100%;
	height: 100%;
	line-height: 32px;
	text-align: center;
	text-decoration: none;
	cursor: pointer;
}	.menu_main .dropdown .button {
	padding-left: 6px;
	text-align: left;
}

<?php /* Dropdown Container */ ?>
.menu_main .dropdown {
	display: none;
	position: absolute;
	top: 32px;
	width: 150px;
	height: auto;
} .menu_main .dropdown.left {
	left: -1px;
} .menu_main .dropdown.right {
	right: -1px;
}