<form method="POST" action="login.php">
	<input type="text" name="user" placeholder="User"/><br>
	<input type="password" name="pass" placeholder="Pass"/><br>
	<input type="submit" name="submit" value="Login"/><br>
</form>

<form method="POST" action="getSync.php">
	<input type="text" name="partid" placeholder="ParticipantID"/><br>
	<input type="submit" name="submit" value="Sync"/><br>
</form>

<form method="POST" action="post_chrome.php">
	<input type="text" name="UserID" placeholder="UserID"/><br>
	<input type="text" name="URL" placeholder="URL"/><br>
	<input type="text" name="Title" placeholder="Title"/><br>
	<input type="text" name="Timestamp" placeholder="Timestamp" value="<?php print time(); ?>"/><br>
	<input type="text" name="URLID" placeholder="URL ID"/><br>
	<input type="text" name="URLVID" placeholder="URL Visit ID"/><br>
	<input type="text" name="URLRID" placeholder="URL Refer ID"/><br>
	<input type="text" name="Transition" placeholder="Transition"/><br>
	<input type="submit" name="submit" value="Send"/><br>
</form>

<form method="POST" action="post_android.php">
	<input type="text" name="UserID" placeholder="UserID"/><br>
	<input type="text" name="Timestamp" placeholder="Timestamp" value="<?php print time(); ?>"/><br>
	<input type="text" name="AppID" placeholder="Application ID"/><br>
	<input type="text" name="StartTime" placeholder="Start Time"><br>
	<input type="text" name="EndTime" placeholder="End Time"/><br>
	<input type="text" name="LastTime" placeholder="Last Time"/><br>
	<input type="text" name="TotalTime" placeholder="Total Time"/><br>
	<input type="submit" name="submit" value="Send"/><br>
</form>