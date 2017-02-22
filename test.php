<form method="POST" action="login.php">
	<input type="text" name="user" placeholder="User"/><br>
	<input type="password" name="pass" placeholder="Pass"/><br>
	<input type="submit" name="submit" value="Login"/><br>
</form>

<form method="POST" action="post_chrome.php">
	<input type="text" name="UserID" placeholder="UserID"/><br>
	<input type="text" name="URL" placeholder="URL"/><br>
	<input type="text" name="Title" placeholder="Title"/><br>
	<input type="text" name="Timestamp" placeholder="Timestamp" value="<?php print time(); ?>"/><br>
	<input type="text" name="URLID" placeholder="URL ID"/><br>
	<input type="text" name="URLVID" placeholder="URL Visit ID"/><br>
	<input type="text" name="URLRID" placeholder="URL Refer ID"/><br>
	<input type="submit" name="submit" value="Send"/><br>
</form>