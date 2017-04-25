<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/lib/db.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/misc/database.php");

$db = DB::connect($_DB['HOST'], $_DB['ROOT']['USER'], $_DB['ROOT']['PASS'], $_DB['DATABASE']);

if(isset($_GET['all']) || isset($_GET['tables'])) {
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

	if(! $db->prepare("CreateSecuritySalt",
		"CREATE TABLE IF NOT EXISTS `Security_Salt` (
			ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
			Salt			VARCHAR(24) NOT NULL,
			PRIMARY KEY (ID)
		);")) die($db->error());

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

	if(! $db->prepare("CreateCollectionAndroid",
		"CREATE TABLE IF NOT EXISTS `Collection_Android` (
			ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
			UserID			INT UNSIGNED NOT NULL,
			AppID			VARCHAR(64) NOT NULL,
			StartTime		INT UNSIGNED DEFAULT 0,
			EndTime			INT UNSIGNED DEFAULT 0,
			LastTime		INT UNSIGNED DEFAULT 0,
			TotalTime		INT UNSIGNED DEFAULT 0,
			Launch			INT UNSIGNED DEFAULT 0,
			PRIMARY KEY (ID)
		);")) die($db->error());
		
	// ---- Execute Statements ---- //
	//$db->execute("CreateAdminLogin");
	$db->execute("CreateSecuritySalt");
	//$db->execute("CreateCollectionChrome");
	//$db->execute("CreateCollectionAndroid");
}

if(isset($_GET['all']) || isset($_GET['users'])) {
	foreach($_DB_USERS as $user=>$props) {
		foreach($props['Allow'] as $allow) {
			$db->query("CREATE USER '{$user}'@'{$allow}' IDENTIFIED BY '{$props['Password']}'");
			foreach($props['Access'] as $table=>$permissions)
				$db->query("GRANT {$permissions} ON `{$table}` TO '{$user}'@'{$allow}' WITH GRANT OPTION;");
		}
	}
}

if(isset($_GET['all']) || isset($_GET['values'])) {
	$db->query("INSERT INTO `Admin_Login` (Email, Password) VALUES ('admin', 'admin')");
	$db->query("INSERT INTO `Admin_Login` (Email, Password) VALUES ('ajshelton@yukiri.net', 'admin')");
	
	$db->query("INSERT INTO `User_Login` (Email, Password) VALUES ('test@test.com', 'testpass'), ('test2@test.com', 'testpass'), ('test3@test.com', 'testpass'), ('test4@test.com', 'testpass')");
}