<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/header.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/src/menu.php");

# Get user ID from parent page or GET
if(!isset($_USER)) {
	$_USER = empty($_GET['id'])? 0 : $_GET['id'];
	if($_USER == 0)
		deny_on('A');
	else
		deny_on('aA');
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
	}
}
# If user does not exist, return to listing page
if($_USER < 0 || !$result) {
	header("Location: ..");
	exit;
}

$disabled = !(($is_self && has_privilege('M')) || (has_privilege('A')));

if(isset($_POST['submit'])) { do {
	# Get input parameters
	$name = isset($_POST['name'])? $_POST['name'] : "";
	$email = isset($_POST['email'])? $_POST['email'] : "";
	
	$newpass = isset($_POST['newPassword'])? $_POST['newPassword'] : "";
	$conpass = isset($_POST['confirmPassword'])? $_POST['confirmPassword'] : "";
	
	if($email !== "") {
		if(has_privilege('P') && (!$is_self xor $_USER == 1)) {
			$permissions = ($_USER != 1)? "" : "MP";
			foreach($_POST['privilege'] as $privilege)
				$permissions .= $privilege;
		}
		
		if($_USER > 0) {
			if($newpass) {
				# Update user's password if changed
				#
				if($newpass == $conpass) {
					# Hash password
					$password = password_hash($newpass, PASSWORD_BCRYPT);
					
					# Update user's password and salt
					$DBU = $_DB['WRITE_ADMIN_INFO'];
					$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
					$db->prepare("setAdminPassword", "UPDATE `Admin_Login` SET Password=? WHERE ID=?");
					$db->param("setAdminPassword", "s", $password);
					$db->param("setAdminPassword", "i", $_USER);
					$db->execute("setAdminPassword");
					$db->close();
				}
				else {
					$out = "Passwords do not match.";
					break;
				}
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
			$password = password_hash($newpass, PASSWORD_BCRYPT);
			
			# Create new user
			$DBU = $_DB['WRITE_ADMIN_INFO'];
			$db = DB::connect($_DB['HOST'], $DBU['USER'], $DBU['PASS'], $_DB['DATABASE']);
			$db->prepare("addAdmin", "INSERT INTO `Admin_Login` (Name, Email, Password, Permissions) VALUES (?,?,?,?)");
			$db->param("addAdmin", "s", $name);
			$db->param("addAdmin", "s", $email);
			$db->param("addAdmin", "s", $password);
			$db->param("addAdmin", "s", $permissions);
			$db->execute("addAdmin");
			$id = $db->id();
			$db->close();
			header("Location:../edit/?id={$id}&Created");
			exit;
		}
		else {
			$out = "Passwords do not match.";
			break;
		}
	}
	else {
		$out = "Email is required.";
		break;
	}
} while(0); }
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Utelem - <?php echo ($_USER == 0)? "Add" : (($is_self)? "My" : "Edit"); ?> Account</title>
		<link rel="icon" href="<?=$_CONFIG['FAVICON']?>" type="image/x-icon"/>
		<style><?php include_once("{$_SERVER['DOCUMENT_ROOT']}/src/styles/layout.php"); ?>
		body {
			background: <?=$C_PRIMARY?>;
		}
		</style>
	</head>
	<body>
		<?php
		if(isset($_GET['Success']))
			echo "<a class=\"overlay_background\" href=\"?id={$_USER}\"><div class=\"overlay_text\">Account has been successfully modified.</br><span style='font-size: 12px;'>[Click to continue]</span></div></a>";
		else if(isset($_GET['Created']))
			echo "<a class=\"overlay_background\" href=\"?id={$_USER}\"><div class=\"overlay_text\">Account has been successfully created.</br><span style='font-size: 12px;'>[Click to continue]</span></div></a>";
		?>
		<div id="maincontent">
			<div class="menu">
				<a class="button" href="..">Back</a>
				<div class="spacer"></div>
			</div>
			<?php
			echo isset($out)? "<span style='color: #F44;'>{$out}</span>" : "</br>";
			?>
			<form method="POST">
				<table class="fieldset">
					<tr> <th>Email</th> <td><input type="text" placeholder="Email" name="email" value="<?=$email?>" <?php if($disabled) echo "disabled"; ?>/></td> </tr>
					<tr> <th>Name</th> <td><input type="text" placeholder="Name" name="name" value="<?=$name?>" <?php if($disabled) echo "disabled"; ?>/></td> </tr>
					<tr class="spacer"></tr>
					<tr> <th>Password</th> <td><input type="password" name="newPassword" placeholder="New Password" <?php if($disabled) echo "disabled"; ?>/></td> </tr>
					<tr> <th>Confirm</th> <td><input type="password" name="confirmPassword" placeholder="Confirm Password" <?php if($disabled) echo "disabled"; ?>/></td> </tr>
					<tr class="spacer"></tr>
					<tr> <th>Privileges</th> <td>
						<input type="checkbox" name="privilege[]" value="D" <?php if(!has_privilege('P') || ($is_self && $_USER != 1) || $disabled) echo " disabled"; if(strpos($permissions, 'D') !== FALSE || $permissions == "*" || $_USER == 0) echo " checked"; ?>> Access Data</br>
						<input type="checkbox" name="privilege[]" value="F" <?php if(!has_privilege('P') || ($is_self && $_USER != 1) || $disabled) echo " disabled"; if(strpos($permissions, 'F') !== FALSE || $permissions == "*") echo " checked"; ?>> Flush Data</br>
						<input type="checkbox" name="privilege[]" value="M" <?php if(!has_privilege('P') || $is_self || $disabled) echo " disabled"; if(strpos($permissions, 'M') !== FALSE || $permissions == "*") echo " checked"; ?>> Edit Own Account</br>
						<input type="checkbox" name="privilege[]" value="u" <?php if(!has_privilege('P') || ($is_self && $_USER != 1) || $disabled) echo " disabled"; if(strpos($permissions, 'u') !== FALSE || $permissions == "*" || $_USER == 0) echo " checked"; ?>> View User Accounts</br>
						<input type="checkbox" name="privilege[]" value="U" <?php if(!has_privilege('P') || ($is_self && $_USER != 1) || $disabled) echo " disabled"; if(strpos($permissions, 'U') !== FALSE || $permissions == "*") echo " checked"; ?>> Edit User Accounts</br>
						<input type="checkbox" name="privilege[]" value="a" <?php if(!has_privilege('P') || ($is_self && $_USER != 1) || $disabled) echo " disabled"; if(strpos($permissions, 'a') !== FALSE || $permissions == "*") echo " checked"; ?>> View Admin Accounts</br>
						<input type="checkbox" name="privilege[]" value="A" <?php if(!has_privilege('P') || ($is_self && $_USER != 1) || $disabled) echo " disabled"; if(strpos($permissions, 'A') !== FALSE || $permissions == "*") echo " checked"; ?>> Edit Admin Accounts</br>
						<input type="checkbox" name="privilege[]" value="P" <?php if(!has_privilege('P') || $is_self || $disabled) echo " disabled"; if(strpos($permissions, 'P') !== FALSE || $permissions == "*") echo " checked"; ?>> Edit Admin Privileges</br>
					</td> </tr>
					<tr class="spacer"></tr>
					<tr> <td></td> <td><?php if(!$disabled) echo "<input type=\"submit\" name=\"submit\" value='". (($_USER > 0)? "Save" : "Create"). "'". (($disabled)? " disabled" : ""). "/></br>"; ?></td> </tr>
				</table>
			</form>
			</br>
			<?php
			if(!$is_self && $_USER != 1 && has_privilege("A"))
				echo "<a href=\"../delete/?id={$_USER}\">Delete Account</a></br>";
			?>
		</div>
	</body>
</html>