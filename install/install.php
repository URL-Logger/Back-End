<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/lib/db.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/lib/secure.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/msc/database.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/msc/users.php");

$db = DB::connect($_DB['HOST'], $_DB['ROOT']['USER'], $_DB['ROOT']['PASS'], $_DB['DATABASE']);

// ---- Prepare Statements ---- //
if(! $db->prepare("CreateUserLogin",
	"CREATE TABLE IF NOT EXISTS `User_Login` (
		ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
		RespondentID	CHAR (6) NOT NULL,
		Email			VARCHAR(32) NOT NULL,
		Password		VARCHAR(255) NOT NULL,
		Secure			INT UNSIGNED DEFAULT NULL,
		LastSync		TIMESTAMP NOT NULL,
		PRIMARY KEY (ID),
		UNIQUE KEY (Email)
	);")) die($db->error());

if(! $db->prepare("CreateUserInfo",
	"CREATE TABLE IF NOT EXISTS `User_Info` (
		id						INT UNSIGNED NOT NULL AUTO_INCREMENT,
		respondent_id			CHAR(6) NOT NULL,
		first_name				VARCHAR(255) NOT NULL,
		last_name				VARCHAR(255) DEFAULT NULL,
		email_address			VARCHAR(255) NOT NULL,
		primary_phone_number	VARCHAR(255) DEFAULT NULL,
		secondary_phone_number	VARCHAR(255) DEFAULT NULL,
		gender					VARCHAR(45) DEFAULT NULL,
		country					VARCHAR(255) DEFAULT NULL,
		country_other			VARCHAR(255) DEFAULT NULL,
		us_state				VARCHAR(255) DEFAULT NULL,
		ca_province				VARCHAR(255) DEFAULT NULL,
		uk_area					VARCHAR(255) DEFAULT NULL,
		au_state				VARCHAR(255) DEFAULT NULL,
		zip_code				VARCHAR(25) DEFAULT NULL,
		postal_code				VARCHAR(45) DEFAULT NULL,
		nearest_city			VARCHAR(255) DEFAULT NULL,
		yob						VARCHAR(45) NOT NULL,
		mob						VARCHAR(45) NOT NULL,
		dob						VARCHAR(45) NOT NULL,
		race_ethnicity			VARCHAR(45) DEFAULT NULL,
		hispanic				VARCHAR(45) DEFAULT NULL,
		language				VARCHAR(255) DEFAULT NULL,
		househould				VARCHAR(45) DEFAULT NULL,
		under_18				BOOLEAN NOT NULL,
		children_1				INT DEFAULT NULL,
		children_2				INT DEFAULT NULL,
		children_3				INT DEFAULT NULL,
		children_4				INT DEFAULT NULL,
		marital					VARCHAR(45) DEFAULT NULL,
		employment				VARCHAR(45) DEFAULT NULL,
		industry				VARCHAR(45) DEFAULT NULL,
		income					VARCHAR(45) DEFAULT NULL,
		education				VARCHAR(45) DEFAULT NULL,
		smartphone				BOOLEAN,
		lead					VARCHAR(45) DEFAULT NULL,
		referral				VARCHAR(255) DEFAULT NULL,
		phone_brand				VARCHAR(45) DEFAULT NULL,
		cats					INT DEFAULT NULL,
		dogs					INT DEFAULT NULL,
		housing					VARCHAR(45) DEFAULT NULL,
		residence				VARCHAR(45) DEFAULT NULL,
		home					VARCHAR(45) DEFAULT NULL,
		transportation			VARCHAR(255) DEFAULT NULL,
		sign_up					DATETIME,
		sign_in					DATETIME,
		responses				VARCHAR(255),
		last_participation		DATETIME,
		all_participation		VARCHAR(255),
		points					INT UNSIGNED,
		tr_code					VARCHAR(255),
		status					VARCHAR(255),
		status_notes			VARCHAR(255),
		status_date				DATETIME,
		confirmed				BOOLEAN,
		PRIMARY KEY (id),
		UNIQUE KEY (respondent_id)
	);")) die($db->error());

if(! $db->prepare("CreateAdminLogin",
	"CREATE TABLE IF NOT EXISTS `Admin_Login` (
		ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
		Email			VARCHAR(128) NOT NULL,
		Password		VARCHAR(255) NOT NULL,
		Secure			INT UNSIGNED DEFAULT NULL,
		Permissions		VARCHAR(16) NOT NULL,
		PRIMARY KEY (ID),
		UNIQUE KEY (Email)
	);")) die($db->error());

if(! $db->prepare("CreateSecuritySalt",
	"CREATE TABLE IF NOT EXISTS `Security_Salt` (
		ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
		Salt			VARCHAR(16) NOT NULL,
		PRIMARY KEY (ID)
	);")) die($db->error());
	
if(! $db->prepare("CreateSecurityRecover",
	"CREATE TABLE IF NOT EXISTS `Security_Recover` (
		ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
		UserID			INT UNSIGNED NOT NULL,
		SecureKey		CHAR(8) NOT NULL,
		PRIMARY KEY (ID),
		UNIQUE KEY (UserID)
	);")) die($db->error());
	
if(! $db->prepare("CreateSecurityRecoverAdmin",
	"CREATE TABLE IF NOT EXISTS `Security_Recover_Admin` (
		ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
		UserID			INT UNSIGNED NOT NULL,
		SecureKey		CHAR(8) NOT NULL,
		PRIMARY KEY (ID),
		UNIQUE KEY (UserID)
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
		StartTime		INT UNSIGNED DEFAULT NULL,
		EndTime			INT UNSIGNED DEFAULT NULL,
		LastTime		INT UNSIGNED DEFAULT NULL,
		TotalTime		INT UNSIGNED DEFAULT NULL,
		Timestamp		TIMESTAMP NOT NULL,
		PRIMARY KEY (ID)
	);")) die($db->error());

if(! $db->prepare("CreateOpportunities",
	"CREATE TABLE IF NOT EXISTS `Opportunities` (
		project_id			INT UNSIGNED AUTO_INCREMENT,
		client_name			VARCHAR(45) NOT NULL,
		project_name		VARCHAR(45) NOT NULL,
		open_date			DATE NOT NULL,
		closing_date		DATE NOT NULL,
		project_type		VARCHAR(45),
		project_category	VARCHAR(45),
		criteria1			VARCHAR(45),
		criteria2			VARCHAR(45),
		criteria3			VARCHAR(45),
		criteria4			VARCHAR(45),
		clicked				BOOLEAN,
		participated		BOOLEAN,
		opportunity_name	VARCHAR(45),
		PRIMARY KEY (project_id)
	);")) die($db->error());

if(! $db->prepare("CreateOpportunitiesRespondents",
	"CREATE TABLE IF NOT EXISTS `Opportunities_Respondents` (
		opportunities_table_project_id		INT UNSIGNED NOT NULL AUTO_INCREMENT,
		respondents_id						INT NOT NULL,
		respondents_respondent_id			INT NOT NULL,
		PRIMARY KEY
			(opportunities_table_project_id,
			respondents_id,
			respondents_respondent_id)
	);")) die($db->error());
	
// ---- Execute Statements ---- //
$db->execute("CreateUserLogin");
$db->execute("CreateUserInfo");
$db->execute("CreateAdminLogin");
$db->execute("CreateSecuritySalt");
$db->execute("CreateSecurityRecover");
$db->execute("CreateSecurityRecoverAdmin");
$db->execute("CreateCollectionChrome");
$db->execute("CreateCollectionAndroid");
$db->execute("CreateOpportunities");
$db->execute("CreateOpportunitiesRespondents");

foreach($_DB_USERS as $user=>$props) {
	foreach($props['Allow'] as $allow) {
		$db->query("CREATE USER '{$user}'@'{$allow}' IDENTIFIED BY '{$props['Password']}'");
		foreach($props['Access'] as $table=>$permissions)
			$db->query("GRANT {$permissions} ON `{$table}` TO '{$user}'@'{$allow}' WITH GRANT OPTION;");
	}
}

// ---- Insert Values ---- //
$db->query("INSERT INTO `Admin_Login` (Email, Password) VALUES ('admin', 'admin')");
$db->query("INSERT INTO `Admin_Login` (Email, Password) VALUES ('jarad@yukiri.net', 'admin')");

$db->query("INSERT INTO `User_Login` (Email, Password) VALUES ('test@test.com', 'testpass'), ('test2@test.com', 'testpass'), ('test3@test.com', 'testpass'), ('test4@test.com', 'testpass')");