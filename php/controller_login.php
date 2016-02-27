<?php require_once "includes.php";
function Login($username, $password){
	$myuser = "";
	$mysqli = Connect();
		if ($result = $mysqli->query("select * from `Users` where `Username` = '".$username."' or `Email` = '".$username."'")) {
		while($row = mysqli_fetch_array($result)){
			if (crypt($password, $row['Hash']) == $row['Hash']) {
				$user= new User($row["ID"], 
						$row["First"],
						$row["Last"],
						$row["Username"],
						$row["Email"],
						$row["Birthdate"],
						$row["Facebook"],
						$row["Twitter"],
						$row["SteamName"],
						$row["Xbox"],
						$row["PSN"],
						$row["Google"],
						$row["Access"],
						$row["DefaultWatched"],
						$row["WeaveView"],
						$row["SessionID"],
						$row["Privacy"],
						$row["RealNames"],
						$row["Title"],
						$row["Image"],
						$row["Website"],
						$row["Badge"]);
				$user->_weave = GetWeave($row["ID"], $mysqli);
				$myuser = $user;
				$_SESSION['logged-in'] = $myuser;
				echo $row["SessionID"];
			}else{
				echo "INCORRECT USERNAME OR PASSWORD";
			}
		}
		if($myuser == ""){ echo "INCORRECT USERNAME OR PASSWORD"; }
	}
	Close($mysqli, $result);
	return $myuser;
}
function ThirdPartyLogin($thirdpartyID, $whoAmI, $verified, $pconn = null){
	$myuser = "";
	$mysqli = Connect($pconn);
		if ($result = $mysqli->query("select * from `Users` where `".$whoAmI."OAuthID` = '".$thirdpartyID."'")) {
		while($row = mysqli_fetch_array($result)){
			$user= new User($row["ID"], 
					$row["First"],
					$row["Last"],
					$row["Username"],
					$row["Email"],
					$row["Birthdate"],
					$row["Facebook"],
					$row["Twitter"],
					$row["SteamName"],
					$row["Xbox"],
					$row["PSN"],
					$row["Google"],
					$row["Access"],
					$row["DefaultWatched"],
					$row["WeaveView"],
					$row["SessionID"],
					$row["Privacy"],
					$row["RealNames"],
					$row["Title"],
					$row["Image"],
					$row["Website"],
					$row["Badge"]);
			$user->_weave = GetWeave($row["ID"], $mysqli);
			$myuser = $user;
			
			if($verified == true){
				$verified = "needusername";
				$_SESSION['pending-user'] = $myuser;
			}else{
				$_SESSION['logged-in'] = $myuser;
			}
			echo $row["SessionID"]."||".$row['Email']."||".$row['Username']."||".$verified;
		}
	}
	if($pconn == null)
		Close($mysqli, $result);
	return $myuser;
}
function FastLogin($id){
	$myuser = "";
	$mysqli = Connect();
		if ($result = $mysqli->query("select * from `Users` where `ID` = '".$id."'")) {
		while($row = mysqli_fetch_array($result)){
				$user= new User($row["ID"], 
						$row["First"],
						$row["Last"],
						$row["Username"],
						$row["Email"],
						$row["Birthdate"],
						$row["Facebook"],
						$row["Twitter"],
						$row["SteamName"],
						$row["Xbox"],
						$row["PSN"],
						$row["Google"],
						$row["Access"],
						$row["DefaultWatched"],
						$row["WeaveView"],
						$row["SessionID"],
						$row["Privacy"],
						$row["RealNames"],
						$row["Title"],
						$row["Image"],
						$row["Website"],
						$row["Badge"]);
				$user->_weave = GetWeave($row["ID"], $mysqli);
				$myuser = $user;
				$_SESSION['logged-in'] = $myuser;
		}
	}
	Close($mysqli, $result);
}
function LoginWithCookie($cookieID){
	$myuser = "";
	$mysqli = Connect();
		if ($result = $mysqli->query("select * from `Users` where `SessionID` = '".$cookieID."'")) {
		while($row = mysqli_fetch_array($result)){	
			$user= new User($row["ID"], 
					$row["First"],
					$row["Last"],
					$row["Username"],
					$row["Email"],
					$row["Birthdate"],
					$row["Facebook"],
					$row["Twitter"],
					$row["SteamName"],
					$row["Xbox"],
					$row["PSN"],
					$row["Google"],
					$row["Access"],
					$row["DefaultWatched"],
					$row["WeaveView"],
					$row["SessionID"],
					$row["Privacy"],
					$row["RealNames"],
					$row["Title"],
					$row["Image"],
					$row["Website"],
					$row["Badge"]);
				$user->_weave = GetWeave($row["ID"], $mysqli);
			$myuser = $user;
			$_SESSION['logged-in'] = $myuser;
		}
	}
	Close($mysqli, $result);
	return $myuser;
}
function SubmitPWReset($key, $pw){
	$mysqli = Connect();
	$id = 0;
	if(strlen($key) > 9){
		$query = "select * from `Users` where `Key` = '".$key."' and `Key` != 'ACTIVE'";
		if ($result = $mysqli->query($query)) {
			while($row = mysqli_fetch_array($result)){
					$id = $row["ID"];
			}
		}
	}
	if($pw != ""){
		$Salt = uniqid();
		$Algo = '6';
		$Rounds = '5000';
		$CryptSalt = '$' . $Algo . '$rounds=' . $Rounds . '$' . $Salt;
		$hashedpw = crypt($pw, $CryptSalt);
		$mysqli->query("Update `Users` SET `Hash`='".$hashedpw."', `Key`='ACTIVE' WHERE `Key` = '".$key."'");
		if($id > 0)
			FastLogin($id);
	}
	Close($mysqli, $result);
}
function Logout(){
	//setcookie("RememberMe", "", time()-3600, '/');
	//if ( isset( $_COOKIE[session_name()] ) )
	//	setcookie( session_name(), “”, time()-3600, “/” );
	$_SESSION = array();
	$_SESSION['logged-in'] = null;
	session_destroy();
}
?>
