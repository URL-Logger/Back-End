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

<table class="menu_main" class="header">
	<tr>
		<td><a class="button" href="/">Home</a></td>
		<?php if(has_privilege('D')) { ?>
		<td><div id="dd_download" class="dropdown left">
			<table class="menu_main">
				<tr><td><a class="button" href="/download/?browser">Browser Data</a></td></tr>
				<tr><td><a class="button" href="/download/?mobile">Mobile Data</a></td></tr>
			</table>
		</div><a class="button" onclick="dropdown_toggle('dd_download')">Download</a></td>
		<?php } ?>
		<?php if(has_privilege('u') || has_privilege('a')) { ?>
		<td><div id="dd_manage" class="dropdown left">
			<table class="menu_main">
				<?php
				if(has_privilege('u')) echo "<tr><td><a class=\"button\" href=\"/users/\">User Accounts</a></td></tr>";
				if(has_privilege('a')) echo "<tr><td><a class=\"button\" href=\"/manage/\">Admin Accounts</a></td></tr>";
				?>
			</table>
		</div><a class="button" onclick="dropdown_toggle('dd_manage')">Manage</a></td>
		<?php } ?>
		<td class="spacing"></td>
		<?php if(has_privilege('F')) { ?>
		<td><a class="button" onclick="dropdown_toggle('dd_flush')">Flush</a><div id="dd_flush" class="dropdown right">
			<table class="menu_main">
				<?php
				echo "<tr><td><a class=\"button\" href=\"/flush/?browser\">Browser Data</a></td></tr>";
				echo "<tr><td><a class=\"button\" href=\"/flush/?mobile\">Mobile Data</a></td></tr>";
				?>
			</table>
		</div></td>
		<?php } ?>
		<td><a class="button" onclick="dropdown_toggle('dd_account')">Options</a><div id="dd_account" class="dropdown right">
			<table class="menu_main">
				<tr><td><a class="button" href="/account/">My Account</a></td></tr>
				<tr><td><a class="button" href="?logout">Logout</a></td></tr>
			</table>
		</div></td>
	</tr>
</table>