<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/lib/db.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/misc/database.php");

$db = DB::connect($_DB['HOST'], $_DB['ROOT']['USER'], $_DB['ROOT']['PASS'], $_DB['DATABASE']);

# if tags `all or `table_accounts are set, reinstall Admin_Login
if(isset($_GET['all']) || isset($_GET['table_accounts'])) {
	$db->query("DROP TABLE IF EXISTS `Admin_Login`;");
	if(! $db->prepare("CreateAdminLogin",
		"CREATE TABLE IF NOT EXISTS `Admin_Login` (
			ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
			Email			VARCHAR(128) NOT NULL,
			Name			VARCHAR(48) NOT NULL,
			Password		VARCHAR(255) NOT NULL,
			Secure			INT UNSIGNED DEFAULT NULL,
			Permissions		VARCHAR(16) NOT NULL,
			PRIMARY KEY (ID),
			UNIQUE KEY (Email)
		);")) die($db->error());
	$db->execute("CreateAdminLogin");

# if tags `all or `table_security are set, reinstall Security_Salt
if(isset($_GET['all']) || isset($_GET['table_security'])) {
	$db->query("DROP TABLE IF EXISTS `Security_Salt`;");
	if(! $db->prepare("CreateSecuritySalt",
		"CREATE TABLE IF NOT EXISTS `Security_Salt` (
			ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
			Salt			VARCHAR(24) NOT NULL,
			PRIMARY KEY (ID)
		);")) die($db->error());
	$db->execute("CreateSecuritySalt");
}

# if tags `all or `table_collection_chrome are set, reinstall Collection_Chrome
if(isset($_GET['all']) || isset($_GET['table_collection_chrome'])) {
	$db->query("DROP TABLE IF EXISTS `Collection_Chrome`;");
	if(! $db->prepare("CreateCollectionChrome",
		"CREATE TABLE IF NOT EXISTS `Collection_Chrome` (
			ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
			UserID			INT UNSIGNED NOT NULL,
			Timestamp		TIMESTAMP NOT NULL,
			URL				TEXT NOT NULL,
			URLID			INT UNSIGNED NOT NULL,
			VisitID			INT UNSIGNED NOT NULL,
			ReferID			INT UNSIGNED NOT NULL,
			Title			TEXT NOT NULL,
			Transition		TEXT NOT NULL,
			PRIMARY KEY (ID)
		);")) die($db->error());
	$db->execute("CreateCollectionChrome");
}

# if tags `all or `table_collection_android are set, reinstall Collection_Android
if(isset($_GET['all']) || isset($_GET['table_collection_android'])) {
	$db->query("DROP TABLE IF EXISTS `Collection_Android`;");
	if(! $db->prepare("CreateCollectionAndroid",
		"CREATE TABLE IF NOT EXISTS `Collection_Android` (
			ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
			UserID			INT UNSIGNED NOT NULL,
			AppID			VARCHAR(64) NOT NULL,
			StartTime		TIMESTAMP,
			EndTime			TIMESTAMP,
			LastTime		TIMESTAMP,
			TotalTime		INT UNSIGNED DEFAULT 0,
			Launch			INT UNSIGNED DEFAULT 0,
			PRIMARY KEY (ID)
		);")) die($db->error());
	$db->execute("CreateCollectionAndroid");
}

# if tags `all or `users are set, reinstall database users
if(isset($_GET['all']) || isset($_GET['users'])) {
	foreach($_DB_USERS as $user=>$props) {
		foreach($props['Allow'] as $allow)
			$db->query("DROP USER '{$user}'@'{$allow}'");
		foreach($props['Allow'] as $allow) {
			$db->query("CREATE USER '{$user}'@'{$allow}' IDENTIFIED BY '{$props['Password']}'");
			foreach($props['Access'] as $table=>$permissions)
				$db->query("GRANT {$permissions} ON `{$table}` TO '{$user}'@'{$allow}' WITH GRANT OPTION;");
		}
	}
}

# if tags `all or `values are set, insert default values
if(isset($_GET['all']) || isset($_GET['values'])) {
	$db->query("INSERT INTO `Admin_Login` (Email, Password) VALUES ('admin', 'admin')");
}