<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php");

# Get user ID from parent page or GET
if(!isset($_USER)) {
	$_USER = empty($_GET['id'])? 0 : $_GET['id'];
	if($_USER == 0)
		deny_on('A');
}
else if($_USER == -1) {
	if(isset($_SESSION['ADMIN_USER']))
		$_USER = $_SESSION['ADMIN_USER'];
}

$is_self = ($_USER == $_SESSION['ADMIN_USER']);

$email = "";
$name = "";
$permissions = "";
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
		$permissions = empty($result[0]['Permissions'])? '' : $result[0]['Permissions'];
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
		if($_USER != 1) {
			if(has_privilege('P') && !$is_self) {
				$permissions = "";
				foreach($_POST['privilege'] as $privilege)
					$permissions .= $privilege;
				if($permissions == "DMuUaAP")
					$permissions = "*";
			} else if($is_self)
				$out = "You may not modify your own privileges.";
		}
		
		if($_USER > 0) {
			if($newpass) {
				# Update user's password if changed
				#
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
			#
			$DBU = $_DB['WRITE_ADMIN_INFO'];
			$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
			$db->prepare("setAdmin", "UPDATE `Admin_Login` SET Name=?, Email=?, Permissions=? WHERE ID=?");
			$db->param("setAdmin", "s", $name);
			$db->param("setAdmin", "s", $email);
			$db->param("setAdmin", "s", $permissions);
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
			$db->prepare("addAdmin", "INSERT INTO `Admin_Login` (Name, Email, Password, Permissions, Secure) VALUES (?,?,?,?,?)");
			$db->param("addAdmin", "s", $name);
			$db->param("addAdmin", "s", $email);
			$db->param("addAdmin", "s", $password);
			$db->param("addAdmin", "s", $permissions);
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

$disabled = (($is_self && !has_privilege('M')) || !has_privilege('A'));
?>
<div id="maincontent">
	<form method="POST">
		<input type="text" placeholder="Email" name="email" value="<?=$email?>" <?php if($disabled) echo "disabled"; ?>/></br>
		</br>
		<input type="text" placeholder="Name" name="name" value="<?=$name?>" <?php if($disabled) echo "disabled"; ?>/></br>
		</br>
		<input type="password" name="newPassword" placeholder="New Password" <?php if($disabled) echo "disabled"; ?>/></br>
		<input type="password" name="confirmPassword" placeholder="Confirm Password" <?php if($disabled) echo "disabled"; ?>/></br>
		</br>
		<b>Privileges</b></br>
		<input type="hidden" name="privilege[]" value="-"/>
		<input type="checkbox" name="privilege[]" value="D" <?php if(!has_privilege('P') || $is_self || $disabled) echo " disabled"; if(strpos($permissions, 'D') !== FALSE || $permissions == "*" || $_USER == 0) echo " checked"; ?>> Download Collection</br>
		<input type="checkbox" name="privilege[]" value="M" <?php if(!has_privilege('P') || $is_self || $disabled) echo " disabled"; if(strpos($permissions, 'M') !== FALSE || $permissions == "*") echo " checked"; ?>> Edit Own Account</br>
		<input type="checkbox" name="privilege[]" value="u" <?php if(!has_privilege('P') || $is_self || $disabled) echo " disabled"; if(strpos($permissions, 'u') !== FALSE || $permissions == "*" || $_USER == 0) echo " checked"; ?>> View User Accounts</br>
		<input type="checkbox" name="privilege[]" value="U" <?php if(!has_privilege('P') || $is_self || $disabled) echo " disabled"; if(strpos($permissions, 'U') !== FALSE || $permissions == "*") echo " checked"; ?>> Edit User Accounts</br>
		<input type="checkbox" name="privilege[]" value="a" <?php if(!has_privilege('P') || $is_self || $disabled) echo " disabled"; if(strpos($permissions, 'a') !== FALSE || $permissions == "*") echo " checked"; ?>> View Admin Accounts</br>
		<input type="checkbox" name="privilege[]" value="A" <?php if(!has_privilege('P') || $is_self || $disabled) echo " disabled"; if(strpos($permissions, 'A') !== FALSE || $permissions == "*") echo " checked"; ?>> Edit Admin Accounts</br>
		<input type="checkbox" name="privilege[]" value="P" <?php if(!has_privilege('P') || $is_self || $disabled) echo " disabled"; if(strpos($permissions, 'P') !== FALSE || $permissions == "*") echo " checked"; ?>> Edit Admin Privileges</br>
		</br>
		<?php if(!$disabled) echo "<input type=\"submit\" name=\"submit\" value='". (($_USER > 0)? "Save" : "Create"). "'". (($disabled)? " disabled" : ""). "/></br>"; ?>
	</form>
</div>