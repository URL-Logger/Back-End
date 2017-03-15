* { box-sizing: border-box; }
html, body {
	display: block;
	position: relative;
	width: 100%;
	height: 100%;
	background: #EEE;
	padding: 0;
	margin: 0;
}

#header {
	display: block;
	position: relative;
	width: 100%;
	height: 56px;
	background: #FFF;
	border-bottom: 1px solid #888;
}

#maincontent {
	display: block;
	position: relative;
	width: calc(100% - 150px);
	height: calc(100% - 56px);
	background: #FFF;
	border-left: 1px solid #888;
	border-right: 1px solid #888;
	margin: 0 auto;
}

.section {
	display: inline-block;
	position: relative;
	height: 100%;
	vertical-align: top;
}