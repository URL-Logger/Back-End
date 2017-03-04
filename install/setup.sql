

CREATE TABLE IF NOT EXISTS `User_Info` (
	id						INT UNSIGNED NOT NULL,
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
/*	password				VARCHAR(255),*/
	PRIMARY KEY (UserID),
	UNIQUE KEY (respondent_id)
);

CREATE TABLE IF NOT EXISTS `Admin_Login` (
	ID				INT UNSIGNED NOT NULL,
	Username		VARCHAR(32) NOT NULL,
	Password		VARCHAR(255) NOT NULL,
	Secure			INT UNSIGNED NOT NULL,
	PRIMARY KEY (ID),
	UNIQUE KEY (Username)
);

CREATE TABLE IF NOT EXISTS `Security_Salt` (
	ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
	Salt			VARCHAR(16) NOT NULL,
	PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS `Collection_Chrome` (
	ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
	UserID			INT UNSIGNED NOT NULL,
	Timestamp		TIMESTAMP DEFAULT NULL,
	PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS `Collection_Android` (
	ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
	UserID			INT UNSIGNED NOT NULL,
	AppID			VARCHAR(64) NOT NULL,
	StartTime		INT UNSIGNED DEFAULT NULL,
	EndTime			INT UNSIGNED DEFAULT NULL,
	LastTime		INT UNSIGNED DEFAULT NULL,
	TotalTime		INT UNSIGNED DEFAULT NULL,
	Timestamp		TIMESTAMP DEFAULT NULL,
	PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS `Opportunities` (
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
);

CREATE TABLE IF NOT EXISTS `Opportunities_Respondents` (
	opportunities_table_project_id		INT UNSIGNED NOT NULL,
	respondents_id						INT NOT NULL,
	respondents_respondent_id			INT NOT NULL,
	PRIMARY KEY
		(opportunities_table_project_id,
		respondents_id,
		respondents_respondent_id)
);
	
CREATE USER 'read_user_login'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT SELECT
	ON `Users`.`Login` TO 'read_user_login'@'localhost' WITH GRANT OPTION;
CREATE USER 'read_user_info'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT SELECT
	ON `Users`.`Login`, `Users`.`Info` TO 'read_user_info'@'localhost' WITH GRANT OPTION;
CREATE USER 'update_user_info'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT INSERT, UPDATE
	ON `Users`.`Login`, `Users`.`Info` TO 'update_user_info'@'localhost' WITH GRANT OPTION;
CREATE USER 'read_collection'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT SELECT
	ON `Collection`.* TO 'read_collection'@'localhost' WITH GRANT OPTION;
CREATE USER 'write_collection'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT INSERT
	ON `Collection`.* TO 'write_collection'@'localhost' WITH GRANT OPTION;
CREATE USER 'read_admin_login'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT SELECT
	ON `ControlPanel`.`Login` TO 'read_admin_login'@'localhost' WITH GRANT OPTION;
CREATE USER 'update_admin_login'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT INSERT, UPDATE
	ON `ControlPanel`.`Login` TO 'update_admin_login'@'localhost' WITH GRANT OPTION;
CREATE USER 'read_security_login'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT SELECT
	ON `Security`.`Salt` TO 'read_security_login'@'localhost' WITH GRANT OPTION;