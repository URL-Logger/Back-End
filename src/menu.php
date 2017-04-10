<style>
* {
	box-sizing: border-box;
}

html,body {
	width: 100%;
	height: 100%;
	margin: 0;
	font-family: "Yu Gothic UI";
}

::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-button { width: 0px; height: 0px; }
::-webkit-scrollbar-thumb { background: #444; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-corner { background: transparent; }

.menu {
	position: relative;
	width: 100%;
	height: auto;
	background: #FFF;
	border-collapse: collapse;
}
.menu td {
	position: relative;
	min-width: 100px;
	height: 2em;
	border: 1px solid #444;
	padding: 0;
} .menu td.spacing {
	width: 100%;
}
.menu .button {
	display: inline-block;
	position: relative;
	width: 100%;
	height: auto;
	font-size: 16px;
}	.menu a.button {
	display: block;
	position: relative;
	width: 100%;
	height: 100%;
	color: #000;
	line-height: 2em;
	text-align: center;
	text-decoration: none;
	cursor: pointer;
}	.menu .dropdown .button {
	padding-left: 6px;
	text-align: left;
}

.menu .dropdown {
	display: none;
	position: absolute;
	top: 2em;
	width: 150px;
	height: auto;
} .menu .dropdown.left {
	left: -1px;
} .menu .dropdown.right {
	right: -1px;
}
</style>

<script>
function dropdown_toggle(item) {
	var objs = document.getElementsByClassName('dropdown');
	for(var i=0; i<objs.length; ++i) {
		if(objs[i].id == item) {
			if(objs[i].style.display != "block")
				objs[i].style.display = "block";
			else
				objs[i].style.display = "none";
		}
		else
			objs[i].style.display = "none";
	}
}
</script>

<table class="menu header">
	<tr>
		<td><a class="button" href="/">Home</a></td>
		<td><div id="dd_download" class="dropdown left">
			<table class="menu">
				<tr><td><a class="button" href="/download/">Browser Data</a></td></tr>
				<tr><td><a class="button" href="">Mobile Data</a></td></tr>
				<tr><td><a class="button" href="">Aggregate Data</a></td></tr>
			</table>
		</div><a class="button" onclick="dropdown_toggle('dd_download')">Download</a></td>
		<td><a class="button" href="/users/">Users</a></td>
		<td class="spacing"></td>
		<td><a class="button" href="/manage/">Accounts</a></td>
		<td><a class="button" onclick="dropdown_toggle('dd_account')">Options</a><div id="dd_account" class="dropdown right">
			<table class="menu">
				<tr><td><a class="button" href="/account/">My Account</a></td></tr>
				<tr><td><a class="button" href="?logout">Logout</a></td></tr>
			</table>
		</div></td>
	</tr>
</table>