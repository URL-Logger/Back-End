<?php
function setup() {
	$db->prepare("CreateSurveyTable", "
		CREATE TABLE IF NOT EXISTS `Surveys` (
			ID				INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			Name			VARCHAR(64),
			StartTime		DATETIME DEFAULT NULL,
			EndTime			DATETIME
		)
	");
	$db->execute("CreateSurveyTable");
}

function create_survey($name, $start, $end) {
	$db->prepare("AddSurvey", "INSERT INTO `Surveys` (Name, StartTime, EndTime) VALUES (?,?,?)");
	$db->param("AddSurvey", "s", $name);
	$db->param("AddSurvey", "s", $start);
	$db->param("AddSurvey", "s", $end);
	$db->execute("AddSurvey");
	if(!$db->error()) {
		$id = $db->last_id();
		$db->prepare("CreateCollectionTable", "
			CREATE TABLE IF NOT EXISTS `Collection_{$id}` (
				ID				INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				UserID			INT UNSIGNED,
				Source			ENUM('PC', 'Mobile') DEFAULT NULL,
				URL				TEXT,
				ReferralURL		TEXT DEFAULT NULL,
				Title			VARCHAR(128) DEFAULT NULL,
				Time			TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				Misc			JSON DEFAULT NULL
			)
		");
		$db->execute("CreateCollectionTable");
		return true;
	}
	else
		return false;
}

function remove_survey($id) {
	$db->prepare("RemoveSurvey", "DELETE `Surveys` WHERE id=?");
	$db->param("RemoveSurvey", "i", $id);
	$db->execute("RemoveSurvey");
	
	$db->prepare("DropSurveyTable", "DROP TABLE `Collection_{$id}`");
	$db->execute("DropSurveyTable");
}

function add_data($id, $data) {
	$userid = isset($data['userid'])? $data['userdata'] : 0;
	$url = isset($data['url'])? $data['url'] : "";
	$rurl = isset($data['rurl'])? $data['rurl'] : "";
	$title = isset($data['title'])? $data['title'] : "";
	$timestamp = isset($data['timestamp'])? $data['timestamp'] : 0;
	$misc = isset($data['misc'])? $data['misc'] : "";
	
	if($userid && $url && $timestamp) {
		$db->prepare("AddData", "INSERT INTO `Collection_{$id}` (UserID, URL, ReferralURL, Title, Tiimestamp, Misc) VALUES (?,?,?,?,?,?)");
		$db->param("AddData", "i", $userid);
		$db->param("AddData", "s", $url);
		$db->param("AddData", "s", $rurl);
		$db->param("AddData", "s", $title);
		$db->param("AddData", "i", $timestamp);
		$db->param("AddData", "s", $misc);
		$db->execute("AddData");
		return true;
	}
	return false;
}

?>