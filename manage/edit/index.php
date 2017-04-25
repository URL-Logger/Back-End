<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php");

# Get user ID from parent page or GET
if(!isset($_USER))
	$_USER = empty($_GET['id'])? 0 : $_GET['id'];
else if($_USER == -1) {
	if(isset($_SESSION['ADMIN_USER']))
		$_USER = $_SESSION['ADMIN_USER'];
}

$email = "";
$name = "";
$salt = -1;

# Determine if users exists
$result = true;
if($_USER > 0) {
	$DBU = $_DB['READ_ADMIN_LOGIN'];
	$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
	$db->prepare("getAdmin", "SELECT * FROM `Admin_Login` WHERE ID=? LIMIT 1");
	$db->param("getAdmin", "i", $_USER);
	$result = $db->execute("getAdmin");
	$db->close();
	
	# If user exists, get user information
	if($result) {
		$name = $result[0]['Name'];
		$email = $result[0]['Email'];
		$saltID = $result[0]['Secure'];
	}
}
# If user does not exist, return to listing page
if($_USER < 0 || !$result) {
	header("Location: ..");
	exit;
}

if(isset($_POST['submit'])) {
	# Get input parameters
	$name = isset($_POST['name'])? $_POST['name'] : "";
	$email = isset($_POST['email'])? $_POST['email'] : "";
	
	$newpass = isset($_POST['newPassword'])? $_POST['newPassword'] : "";
	$conpass = isset($_POST['confirmPassword'])? $_POST['confirmPassword'] : "";
	
	if($email !== "") {
		if($_USER > 0) {
			if($newpass) {
				if($newpass == $conpass) {
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
					$db->prepare("setAdminPassword", "UPDATE `Admin_Login` SET Password=?, Secure=? WHERE ID=?");
					$db->param("setAdminPassword", "s", $password);
					$db->param("setAdminPassword", "s", $saltID);
					$db->param("setAdminPassword", "i", $_USER);
					$db->execute("setAdminPassword");
					$db->close();
				}
				else $out = "Passwords do not match.";
			}
			
			# Update user's information
			$DBU = $_DB['WRITE_ADMIN_INFO'];
			$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
			$db->prepare("setAdmin", "UPDATE `Admin_Login` SET Name=?, Email=? WHERE ID=?");
			$db->param("setAdmin", "s", $name);
			$db->param("setAdmin", "s", $email);
			$db->param("setAdmin", "i", $_USER);
			$db->execute("setAdmin");
			$db->close();
			if(!$out) {
				header("Location:?id={$_USER}&Success");
				exit;
			}
		}
		else if($newpass && $newpass == $conpass) {
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
			
			# Create new user
			$DBU = $_DB['WRITE_ADMIN_INFO'];
			$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
			$db->prepare("addAdmin", "INSERT INTO `Admin_Login` (Name, Email, Password, Secure) VALUES (?,?,?,?)");
			$db->param("addAdmin", "s", $name);
			$db->param("addAdmin", "s", $email);
			$db->param("addAdmin", "s", $password);
			$db->param("addAdmin", "s", $saltID);
			$db->execute("addAdmin");
			$id = $db->id();
			$db->close();
			header("Location:../edit/?id={$id}&Created");
			exit;
		}
		else $out = "Passwords do not match.";
	}
	else $out = "Email is required.";
}
?>
<a href='..'/>Back</a></br>
<?php
if(isset($_GET['Created'])) $out = "Account has been created.";
if(isset($_GET['Success'])) $out = "Account has been updated.";
if(isset($out)) echo $out;
?>
<div id="maincontent">
	<form method="POST">
		<input type="text" placeholder="Email" name="email" value="<?=$email?>"/></br>
		</br>
		<input type="text" placeholder="Name" name="name" value="<?=$name?>"/></br>
		</br>
		<input type="password" name="newPassword" placeholder="New Password"/></br>
		<input type="password" name="confirmPassword" placeholder="Confirm Password"/></br>
		</br>
		<!--<b>Permissions</b></br>
		<input type="checkbox"> Download Data</br>
		<input type="checkbox"> Edit Own Account</br>
		<input type="checkbox"> View User Accounts</br>
		<input type="checkbox"> Edit User Accounts</br>
		<input type="checkbox"> View Admin Accounts</br>
		<input type="checkbox"> Edit Admin Accounts</br>
		</br>-->
		<input type="submit" name="submit" value="<?php echo ($_USER > 0)? "Save" : "Create"; ?>"/></br>
	</form>
</div>