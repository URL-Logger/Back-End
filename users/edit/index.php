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
				/*
				# Create salt and hash password
				$salt = random_bytes(24);
				$password = password_hash($newpass.$salt, PASSWORD_BCRYPT);
				
				# Delete old salt
				$DBU = $_DB['DELETE_SECURITY_LOGIN'];
				$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
				$db->prepare("clearSalt", "DELETE FROM `Security_Salt` WHERE ID=?");
				$db->param("clearSalt", "i", $saltID);
				$db->execute("clearSalt");
				
				# Write new salt to database
				$DBU = $_DB['WRITE_SECURITY_LOGIN'];
				$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
				$db->prepare("addSalt", "INSERT INTO `Security_Salt` (Salt) VALUES (?)");
				$db->param("addSalt", "s", $salt);
				$db->execute("addSalt");
				$saltID = $db->id();
				$db->close();
				
				# Update user's password and salt
				$DBU = $_DB['WRITE_ADMIN_INFO'];
				$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
				$db->prepare("setUserPassword", "UPDATE `Admin_Login` SET Password=?, Secure=? WHERE ID=?");
				$db->param("setUserPassword", "s", $password);
				$db->param("setUserPassword", "s", $saltID);
				$db->param("setUserPassword", "i", $_USER);
				$db->execute("setUserPassword");
				$db->close();
				*/

				$DBU = $_DB['WRITE_USER_INFO'];
				$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
				$db->prepare("setUserPassword", "UPDATE `User_Login` SET Password=? WHERE ID=?");
				$db->param("setUserPassword", "s", $newpass);
				$db->param("setUserPassword", "i", $_USER);
				$db->execute("setUserPassword");
				$db->close();
			}
			
			# Update user's information
			$DBU = $_DB['WRITE_USER_INFO'];
			$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
			$db->prepare("setUser", "UPDATE `User_Login` SET Email=? WHERE ID=?");
			$db->param("setUser", "s", $email);
			$db->param("setUser", "i", $_USER);
			$db->execute("setUser");
			$db->close();
			header("Location:?id={$_USER}&Success");
			exit;
		}
		else if($newpass && $newpass == $conpass) {
			/*
			# Create salt and hash password
			$salt = random_bytes(24);
			$password = password_hash($newpass.$salt, PASSWORD_BCRYPT);
			
			# Write new salt to database
			$DBU = $_DB['WRITE_SECURITY_LOGIN'];
			$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
			$db->prepare("clearSalt", "DELETE FROM `Security_Salt` WHERE ID=?");
			$db->prepare("addSalt", "INSERT INTO `Security_Salt` (Salt) VALUES (?)");
			$db->param("clearSalt", "i", $saltID);
			$db->execute("clearSalt");
			$db->param("addSalt", "s", $salt);
			$db->execute("addSalt");
			$saltID = $db->id();
			*/
			
			# Create new user
			$DBU = $_DB['WRITE_USER_INFO'];
			$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
			$db->prepare("addUser", "INSERT INTO `User_Login` (Email, Password) VALUES (?,?)");
			$db->param("addUser", "s", $email);
			$db->param("addUser", "s", $newpass);
			$db->execute("addUser");
			$id = $db->id();
			$db->close();
			header("Location:../edit/?id={$id}&Created");
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
	</head>
	<body>
		<a href='..'/>Back</a></br>
		<?php
		if(isset($_GET['Success']))
			echo "Account has been updated.</br>";
		else if(isset($_GET['Created']))
			echo "Account has been created.</br>";
		else if(count($errors) > 0) {
			# Display errors
			if(in_array("EmailRequired", $errors))
				echo "An email is required.";
			if(in_array("UnmatchedPasswords", $errors))
				echo "Passwords do not match.";
		}
		else
			echo "</br>";
		?>
		<div id="maincontent">
			<form method="POST">
				<input type="text" placeholder="Email" name="email" value="<?=$email?>"/><br>
				<br>
				<input type="password" name="newPassword" placeholder="New Password"/><br>
				<input type="password" name="confirmPassword" placeholder="Confirm Password"/><br>
				<br>
				<input type="submit" name="submit" value="<?php echo ($_USER > 0)? "Save" : "Create"; ?>"/></br
			</form>
		</div>
	</body>
</html>