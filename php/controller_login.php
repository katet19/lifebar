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
						$row["Website"]);
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
						$row["Website"]);
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
					$row["Website"]);
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
function GetOAuthConfig(){
	$config = array();
	$providers = array();
	$googlearray = array();
	$keys = array();
	
	$config["base_url"] = "http://lifebar.io/php/library/Hybrid";
	
	//Google
	unset($keys);
	$googlearray["enabled"] = true;
	$keys["id"] = "520374804291-il70cd9di22ii68ira29f132n3075rup.apps.googleusercontent.com";
	$keys["secret"] = "l_DKu31fiLPte7XUlF5tblJ4";
	$googlearray["keys"] = $keys;
	$providers["Google"] = $googlearray;
	
    $config["providers"]  = $providers;
    
    return $config;
}
function GoogleLogin(){
	try{
       $hybridauth = new Hybrid_Auth( GetOAuthConfig() );
 
       $google = $hybridauth->authenticate( "Google" );
 
       $user_profile = $google->getUserProfile();
 
       echo "Hi there! " . $user_profile->displayName;
 
   }
   catch( Exception $e ){
       echo "Ooophs, we got an error: " . $e->getMessage();
   }
}
?>
