CREATE DATABASE `SmartRevenue_Users`;
	CREATE TABLE IF NOT EXISTS `Users`.`Login` (
		ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
		Username		VARCHAR(32) NOT NULL,
		Password		VARCHAR(255) NOT NULL,
		PRIMARY KEY (ID),
		UNIQUE KEY (Username)
	);
	CREATE TABLE IF NOT EXISTS `Users`.`Info` (
		UserID			INT UNSIGNED NOT NULL,
		PRIMARY KEY (UserID),
		FOREIGN KEY (UserID) REFERENCES `Login` (ID)
	);
	CREATE TABLE IF NOT EXISTS `Users`.`Admin_Login` (
		ID				INT UNSIGNED NOT NULL,
		Username		VARCHAR(32) NOT NULL,
		Password		VARCHAR(255) NOT NULL,
		PRIMARY KEY (ID),
		UNIQUE KEY (Username)
	);
	
CREATE DATABASE `SmartRevenue_Collection`;
	CREATE TABLE IF NOT EXISTS `Chrome_Data` (
		ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
		UserID			INT UNSIGNED NOT NULL,
		Timestamp		TIMESTAMP DEFAULT NULL,
		PRIMARY KEY (ID),
		FOREIGN KEY (UserID) REFERENCES `Users`.`Login` (ID)
	);
	CREATE TABLE IF NOT EXISTS `Android_Data` (
		ID				INT UNSIGNED NOT NULL AUTO_INCREMENT,
		UserID			INT UNSIGNED NOT NULL,
		AppID			VARCHAR(64) NOT NULL,
		StartTime		INT UNSIGNED DEFAULT NULL,
		EndTime			INT UNSIGNED DEFAULT NULL,
		LastTime		INT UNSIGNED DEFAULT NULL,
		TotalTime		INT UNSIGNED DEFAULT NULL,
		Timestamp		TIMESTAMP DEFAULT NULL,
		PRIMARY KEY (ID),
		FOREIGN KEY (UserID) REFERENCES `Users`.`Login` (ID)
	);
	
CREATE USER 'read_user_login'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT SELECT
	ON `SmartRevenue_Users`.`Login` TO 'read_user_login'@'localhost' WITH GRANT OPTION;
CREATE USER 'read_user_info'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT SELECT
	ON `SmartRevenue_Users`.`Login`, `SmartRevenue`.`Info` TO 'read_user_info'@'localhost' WITH GRANT OPTION;
CREATE USER 'update_user_info'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT INSERT, UPDATE
	ON `SmartRevenue_Users`.`Login`, `SmartRevenue_Users`.`Info` TO 'update_user_info'@'localhost' WITH GRANT OPTION;
CREATE USER 'read_collection'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT SELECT
	ON `SmartRevenue_Collection`.* TO 'read_collection'@'localhost' WITH GRANT OPTION;
CREATE USER 'write_collection'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT INSERT
	ON `SmartRevenue_Collection`.* TO 'write_collection'@'localhost' WITH GRANT OPTION;
CREATE USER 'read_admin_login'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT SELECT
	ON `SmartRevenue_Users`.`Admin_Login` TO 'read_admin_login'@'localhost' WITH GRANT OPTION;
CREATE USER 'update_admin_login'@'localhost' IDENTIFIED BY 'PASSWORD';
	GRANT INSERT, UPDATE
	ON `SmartRevenue_Users`.`Admin_Login` TO 'update_admin_login'@'localhost' WITH GRANT OPTION;