<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php");

deny_on('uU');

# Get User ID
$_USER = empty($_GET['id'])? 0 : $_GET['id'];

$user = "";
$email = "";
$name = "";
$salt = -1;

# Determine if users exists
$result = true;
if($_USER > 0) {
	$DBU = $_DB['READ_USER_LOGIN'];
	$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
	$db->prepare("getUser", "SELECT * FROM `User_Login` WHERE ID=? LIMIT 1");
	$db->param("getUser", "i", $_USER);
	$result = $db->execute("getUser");
	$db->close();
	
	# If user exists, get user information
	if($result)
		$email = $result[0]['Email'];
}
# If user does not exist, return to listing page
if($_USER < 0 || !$result) {
	header("Location: ..");
	exit;
}

$errors = array();
if(isset($_POST['submit'])) {
	# Get input parameters
	$email = isset($_POST['email'])? $_POST['email'] : "";
	
	$newpass = isset($_POST['newPassword'])? $_POST['newPassword'] : "";
	$conpass = isset($_POST['confirmPassword'])? $_POST['confirmPassword'] : "";
	
	if($email !== "") {
		if($_USER > 0) {
			if($newpass && $newpass == $conpass) {
				$newpass = password_hash($newpass, PASSWORD_BCRYPT);

				$DBU = $_DB['WRITE_USER_INFO'];
				$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
				$db->prepare("setUserPassword", "UPDATE `User_Login` SET Password=? WHERE ID=?");
				$db->param("setUserPassword", "s", $newpass);
				$db->param("setUserPassword", "i", $_USER);
				$db->execute("setUserPassword");
				$db->close();
				
				if($result[0]['RespondentID']) {
					$DBU = $_DB['ROOT'];
					$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
					$db->prepare("setRespondentPassword", "UPDATE `respondents` SET password=? WHERE respondent_id=?");
					$db->param("setRespondentPassword", "s", $newpass);
					$db->param("setRespondentPassword", "s", $result[0]['RespondentID']);
					$db->execute("setRespondentPassword");
					$id = $db->id();
					$db->close();
				}
			}
			
			# Update user's information
			$DBU = $_DB['WRITE_USER_INFO'];
			$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
			$db->prepare("setUser", "UPDATE `User_Login` SET Email=? WHERE ID=?");
			$db->param("setUser", "s", $email);
			$db->param("setUser", "i", $_USER);
			$db->execute("setUser");
			$db->close();
			
			if($result[0]['RespondentID']) {
				$DBU = $_DB['ROOT'];
				$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
				$db->prepare("setRespondent", "UPDATE `respondents` SET email_address=? WHERE respondent_id=?");
				$db->param("setRespondent", "s", $email);
				$db->param("setRespondent", "s", $result[0]['RespondentID']);
				$db->execute("setRespondent");
				$id = $db->id();
				$db->close();
			}
			
			header("Location:?id={$_USER}&Success");
			exit;
		}
		else if($newpass && $newpass == $conpass) {
			$newpass = password_hash($newpass, PASSWORD_BCRYPT);
			
			# Create new user
			$DBU = $_DB['WRITE_USER_INFO'];
			$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
			$db->prepare("addUser", "INSERT INTO `User_Login` (Email, Password) VALUES (?,?)");
			$db->param("addUser", "s", $email);
			$db->param("addUser", "s", $newpass);
			$db->execute("addUser");
			$id = $db->id();
			$db->close();
			header("Location:?id={$id}&Created");
			exit;
		}
		else $errors []= "UnmatchedPasswords";
	}
	else $errors []= "EmailRequired";
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Utelem - <?php echo ($_USER == 0)? "Add" : "Edit"; ?> Account</title>
		<link rel="icon" href="<?=$_CONFIG['FAVICON']?>" type="image/x-icon"/>
		<style><?php include_once("../../src/styles/layout.php"); ?>
		body {
			background: <?=$C_PRIMARY?>;
		}
		</style>
	</head>
	<body>
		<div id="maincontent">
			<div class="menu">
				<a class="button" href="..">Back</a>
				<div class="spacer"></div>
			</div>
			<?php
			if(isset($_GET['Success']))
				$out = "Account has been updated.</br>";
			else if(isset($_GET['Created']))
				$out = "Account has been created.</br>";
			else if(count($errors) > 0) {
				# Display errors
				if(in_array("EmailRequired", $errors))
					$out = "An email is required.";
				if(in_array("UnmatchedPasswords", $errors))
					$out = "Passwords do not match.";
			}
			echo isset($out)? $out : "</br>";
			?>
			
			<form method="POST">
				<table class="fieldset">
					<tr> <th>Email</th> <td><input type="text" placeholder="Email" name="email" value="<?=$email?>"/></td> </tr>
					<tr class="spacer"></tr>
					<tr> <th>Password</th> <td><input type="password" name="newPassword" placeholder="New Password"/></td> </tr>
					<tr> <th>Confirm</th> <td><input type="password" name="confirmPassword" placeholder="Confirm Password"/></td> </tr>
					<tr class="spacer"></tr>
					<tr> <td></td> <td><input type="submit" name="submit" value="<?php echo ($_USER > 0)? "Save" : "Create"; ?>"/></td> </tr>
				</table>
			</form>
		</div>
	</body>
</html>