<?php
require_once "includes.php";

function SetTitles(){
	$journalists = GetJournalists();
	foreach($journalists as $jo){
		if($jo->_title == ""){
			$pub = GetJournalistPublication($jo->_id);
			if($pub != ""){
				SaveUserTitle($jo->_id, $pub[0]);
			}
		}
	}
}

function UpdateRoleManagement($userid, $role){
	$mysqli = Connect();
	
	if($role == 'journalist-role')
		$access = 'Journalist';
	else if($role == 'authenticated-role')
		$access = 'Authenticated';
	else if($role == 'user-role')
		$access = 'User';
	else
		$access = 'User';
		
	$mysqli->query("UPDATE `Users` SET `Access` = '".$access."' WHERE `ID` = '".$userid."'");
	Close($mysqli, $result);
}

function SaveAccountChanges($id, $username, $password, $first, $last, $email, $birthdate, $watchedsource, $steam, $psn, $xbox, $title, $weburl, $twitter, $image){
	$mysqli = Connect();
	if($password != ""){
		$Salt = uniqid();
		$Algo = '6';
		$Rounds = '5000';
		$CryptSalt = '$' . $Algo . '$rounds=' . $Rounds . '$' . $Salt;
		$hashedpw = crypt($password, $CryptSalt);
		$mysqli->query("Update `Users` SET `Username`='".$username."', `Hash`='".$hashedpw."', `Email`='".$email."', `First`='".$first."', `Last`='".$last."', `Birthdate`='".$birthdate."-01-01', `DefaultWatched`='".$watchedsource."', `SteamName`='".$steam."', `Xbox`='".$xbox."', `PSN`='".$psn."', `Image`='".$image."', `Title`='".$title."', `Website`='".$weburl."', `Twitter`='".$twitter."' WHERE `ID` = '".$id."'");
	}else{
		$mysqli->query("Update `Users` SET `Username`='".$username."', `Email`='".$email."', `First`='".$first."', `Last`='".$last."', `Birthdate`='".$birthdate."-01-01', `DefaultWatched`='".$watchedsource."', `SteamName`='".$steam."', `Xbox`='".$xbox."', `PSN`='".$psn."', `Title`='".$title."', `Website`='".$weburl."', `Twitter`='".$twitter."', `Image`='".$image."' WHERE `ID` = '".$id."'");
	}
	Close($mysqli, $result);
	FastLogin($id);
}
/*
	$Salt = uniqid();
	$Algo = '6';
	$Rounds = '5000';
	$CryptSalt = '$' . $Algo . '$rounds=' . $Rounds . '$' . $Salt;
	$pw = "test";
	$hashedpw = crypt($pw, $CryptSalt);
	$randomToken = hash('sha256',uniqid(mt_rand(), true).uniqid(mt_rand(), true));
	echo $hashedpw;
	echo $randomToken;
*/
function RegisterUser($username, $password, $first, $last, $email, $birthdate,$privacy){
	$Salt = uniqid();
	$Algo = '6';
	$Rounds = '5000';
	$CryptSalt = '$' . $Algo . '$rounds=' . $Rounds . '$' . $Salt;
	$hashedpw = crypt($password, $CryptSalt);
	$mysqli = Connect();
	$founduser = false;
	if ($result = $mysqli->query("select * from `Users` where `Username` = '".$username."'")) {
		while($row = mysqli_fetch_array($result)){
			$founduser = true;
			$user = Login($username, $password, $mysqli);
		}
	}
	if($founduser == false){
		$randomToken = hash('sha256',uniqid(mt_rand(), true).uniqid(mt_rand(), true));
		$mysqli->query("INSERT INTO `Users` (`Username`,`Hash`,`Email`,`First`,`Last`,`Birthdate`,`Privacy`, `SessionID`) VALUES ('".$username."','".$hashedpw."','".$email."','".$first."','".$last."','".$birthdate."-01-01','".$privacy."', '".$randomToken."')");
		$user = Login($username, $password);
		AddIntroNotifications($user->_id, $mysqli);
		CreateDefaultFollowingConnections($user->_id, $mysqli);
		SignupEmail($email);
	}
	Close($mysqli, $result);
	return $user;
}

function RegisterThirdPartyUser($username, $email, $first, $last, $image, $thirdpartyID, $whoAmI){
	$mysqli = Connect();
	$founduser = false;
	
	//User already has signed before with this account
	if ($result = $mysqli->query("select * from `Users` where `".$whoAmI."OAuthID` = '".$thirdpartyID."'")) {
		while($row = mysqli_fetch_array($result)){
			$founduser = true;
			$user = ThirdPartyLogin($thirdpartyID, $whoAmI, false, $mysqli);
		}
	}
	//User already has a valid account with the same email, update and login
	if($founduser == false && $email != ''){
		if ($result = $mysqli->query("select * from `Users` where `Email` = '".$email."'")) {
			while($row = mysqli_fetch_array($result)){
				$founduser = true;
				$mysqli->query("UPDATE `Users` SET `".$whoAmI."OAuthID` = '".$thirdpartyID."' WHERE `Email` = '".$email."'");
					
				$user = ThirdPartyLogin($thirdpartyID, $whoAmI, false, $mysqli);
			}
		}
	}
	//New user, must have a username and third party ID. Does not require an email
	if($founduser == false && $thirdpartyID != '' ){
		$randomToken = hash('sha256',uniqid(mt_rand(), true).uniqid(mt_rand(), true));
		if($image == '')
			$image = "Gravatar";
		
		if($whoAmI == "Twitter"){
			$notunique = VerifyUniqueUsername($username);
			if($notunique){
				$mysqli->query("INSERT INTO `Users` (`Username`,`Email`,`First`,`Last`,`Image`,`".$whoAmI."OAuthID`,`SessionID`,`Key`) VALUES ('".$username."','".$email."','".$first."','".$last."','".$image."','".$thirdpartyID."','".$randomToken."','PENDING')");
				$user = ThirdPartyLogin($thirdpartyID, $whoAmI, true, $mysqli);
			}else{
				$mysqli->query("INSERT INTO `Users` (`Username`,`Email`,`First`,`Last`,`Image`,`".$whoAmI."OAuthID`,`SessionID`,`Key`) VALUES ('".$username."','".$email."','".$first."','".$last."','".$image."','".$thirdpartyID."','".$randomToken."','ACTIVE')");
				$user = ThirdPartyLogin($thirdpartyID, $whoAmI, false, $mysqli);
			}
		}else{
			$mysqli->query("INSERT INTO `Users` (`Username`,`Email`,`First`,`Last`,`Image`,`".$whoAmI."OAuthID`,`SessionID`,`Key`) VALUES ('".$username."','".$email."','".$first."','".$last."','".$image."','".$thirdpartyID."','".$randomToken."','PENDING')");
			$user = ThirdPartyLogin($thirdpartyID, $whoAmI, true, $mysqli);
		}
		
		AddIntroNotifications($user->_id, $mysqli);
		CreateDefaultFollowingConnections($user->_id, $mysqli);
		if($email != '')
			SignupEmail($email);
	}
	Close($mysqli, $result);
	return $user;
}

function FinishRegisterUser($userid, $email, $username){
	$mysqli = Connect();
	if($userid != '' && $username != ''){
		if($email != '')
			$mysqli->query("UPDATE `Users` SET `Email` = '".$email."', `Username` = '".$username."', `Key` = 'ACTIVE' WHERE `ID` = '".$userid."'");
		else
			$mysqli->query("UPDATE `Users` SET `Username` = '".$username."', `Key` = 'ACTIVE' WHERE `ID` = '".$userid."'");
			
		FastLogin($userid);
	}
	Close($mysqli, $result);
}

function RegisterUserEarly($username, $password, $first, $last, $birthdate, $email){
	$Salt = uniqid();
	$Algo = '6';
	$Rounds = '5000';
	$CryptSalt = '$' . $Algo . '$rounds=' . $Rounds . '$' . $Salt;
	$hashedpw = crypt($password, $CryptSalt);
	$mysqli = Connect();
	$founduser = false;
	if ($result = $mysqli->query("select * from `Users` where `Username` = '".$username."'")) {
		while($row = mysqli_fetch_array($result)){
			$founduser = true;
			$user = Login($username, $password);
		}
	}
	if($founduser == false){
		$randomToken = hash('sha256',uniqid(mt_rand(), true).uniqid(mt_rand(), true));
		$mysqli->query("UPDATE `Users` SET `Username` = '".$username."',`Hash`='".$hashedpw."',`First`='".$first."',`Last`='".$last."',`Birthdate`='".$birthdate."-01-01', `SessionID`='".$randomToken."', `Key`='ACTIVE' WHERE `Email` = '".$email."'");
		$user = Login($username, $password);
		CreateDefaultFollowingConnections($user->_id);
		AddIntroQuests($user->_id);
	}
	Close($mysqli, $result);
	return $user;
}

function VerifyUniqueUsername($username){
	$mysqli = Connect();
	$founduser = false;
	if($username != ''){
		if ($result = $mysqli->query("select * from `Users` where `Username` = '".$username."' and `Key` = 'ACTIVE'")) {
			while($row = mysqli_fetch_array($result)){
				$founduser = true;
			}
		}
	}
	
	if($founduser){
		echo "Username is already used";	
	}
	Close($mysqli, $result);
	return $founduser;
}

function VerifyUniqueEmail($email){
	$mysqli = Connect();
	$founduser = false;
	if ($result = $mysqli->query("select * from `Users` where `Email` = '".$email."' and `Key` = 'ACTIVE'")) {
		while($row = mysqli_fetch_array($result)){
			$founduser = true;
		}
	}
	
	if($founduser){
		echo "Email is already used";	
	}	
	Close($mysqli, $result);
}

//CreateDefaultFollowingConnections(7);
function CreateDefaultFollowingConnections($userid, $pconn = null){
	$journalist = array();
	$mysqli = Connect($pconn);
	$date = date('Y-m-d', strtotime("now -30 days") );
	$query = "select *, Count(`UserID`) as TotalRows from `Events` eve, `Users` usr WHERE eve.`Date` > '".$date."' and usr.`ID` = eve.`UserID` and usr.`Access` = 'Journalist' GROUP BY `UserID` ORDER BY COUNT(  `UserID` ) DESC LIMIT 10";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
				$journalist[] = "('".$userid."','".$row['UserID']."')";
				AddAutoNotificationCard($userid, $row['UserID'], $mysqli);
		}
		$mysqli->query("INSERT INTO `Connections`  (`Fan`, `Celebrity`) VALUES ".implode(",",$journalist));
	}
    if($pconn == null)
	   Close($mysqli, $result);
}

function GetUser($userid, $pconn = null){
	$myuser = "";
	$mysqli = Connect($pconn);
	if ($result = $mysqli->query("select * from `Users` where `ID` = '".$userid."'")) {
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

		}
	}
	if($pconn == null)
		Close($mysqli, $result);
	return $myuser;
}

function GetUserByName($username){
	$myuser = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Users` where `Username` = '".$username."'")) {
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

		}
	}
	Close($mysqli, $result);
	return $myuser;
}

function GetJournalists(){
	$journalists = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Users` where `Access` = 'Journalist' or `Access` = 'Authenticated' order by `First`")) {
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
				$journalists[] = $user;

		}
	}
	Close($mysqli, $result);
	return $journalists;
}

function RegisterJournalist($first, $last){
	$mysqli = Connect();
	$userid = "";
	if ($result = $mysqli->query("select * from `Users` where `First` = '".$first."' and `Last` = '".$last."' and `Access` = 'Journalist'")) {
		while($row = mysqli_fetch_array($result)){
			$userid = $row["ID"];		
		}
	}
	
	if($userid == ""){
		$mysqli->query("INSERT INTO `Users` (`Username`,`First`,`Last`,`Access`) VALUES ('".$first.$last."[CRITIC]','".$first."','".$last."','Journalist')");
		
		if ($result = $mysqli->query("select * from `Users` where `First` = '".$first."' and `Last` = '".$last."' and `Access` = 'Journalist' order by `ID`")) {
			while($row = mysqli_fetch_array($result)){
				$userid = $row["ID"];
			}
		}
	}
	Close($mysqli, $result);
	return $userid;
}


function GetPublications(){
	$mysqli = Connect();
	$publications = array();
	if ($result = $mysqli->query("SELECT * FROM  `Publications` ORDER BY `Title` ASC ")) {
		while($row = mysqli_fetch_array($result)){
			$pub = array();
			$pub[] = $row["Title"];
			$pub[] = $row["ID"];
			$publications[] = $pub;
		}
	}
	Close($mysqli, $result);
	return $publications;		
}

function GetPublisher($PubID){
	$mysqli = Connect();
	if ($result = $mysqli->query("SELECT * FROM  `Publications` where `ID` = '".$PubID."' ORDER BY `Title` ASC ")) {
		while($row = mysqli_fetch_array($result)){
			$pub[] = $row["Title"];
			$pub[] = $row["Link"];
			$pub[] = $row["ID"];
		}
	}
	Close($mysqli, $result);
	return $pub;
}

function GetPublishersJournalistList($PubID){
	$mysqli = Connect();
	$pub = GetPublisher($PubID);
	$users = array();
	if ($result = $mysqli->query("SELECT * FROM  `Experiences` exp, `Users` usr where `Link` like '%".$pub[1]."%' and usr.`ID` = exp.`UserID` GROUP BY `First` ASC")) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$users[] = $user;
		}
	}
	Close($mysqli, $result);
	return $users;
}

function GetConnectedTo($userid){
	$users = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("SELECT * FROM  `Users` usr,  `Connections` con WHERE con.`Fan` = '".$userid."' AND con.`Celebrity` = usr.`ID` group by `Username` order by `Username`")) {
		while($row = mysqli_fetch_array($result)){
			if($row["Privacy"] != "Private"){
				$user= new User($row["Celebrity"], 
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
				$users[] = $user;
			}

		}
	}
	Close($mysqli, $result);
	return $users;
}

function GetConnectedToList($userid, $pconn = null){
	$users = array();
	$mysqli = Connect($pconn);
	if ($result = $mysqli->query("SELECT * FROM  `Users` usr,  `Connections` con WHERE con.`Fan` = '".$userid."' AND con.`Celebrity` = usr.`ID` group by `Username` order by `First`")) {
		while($row = mysqli_fetch_array($result)){
				$users[] = $row["Celebrity"];
		}
	}
	if($pconn == null)
		Close($mysqli, $result);
	return $users;
}

function GetConnectedToUsersList($userid, $pconn = null){
	$users = array();
	$mysqli = Connect($pconn);
	if ($result = $mysqli->query("SELECT * FROM  `Users` usr,  `Connections` con WHERE con.`Fan` = '".$userid."' AND con.`Celebrity` = usr.`ID` and (usr.`Access` != 'Journalist' && usr.`Access` != 'Authenticated') order by `First`")) {
		while($row = mysqli_fetch_array($result)){
				$users[] = $row["Celebrity"];
		}
	}
	if($pconn == null)
		Close($mysqli, $result);
	return $users;
}

function GetConnectedToCriticsList($userid, $pconn = null){
	$users = array();
	$mysqli = Connect($pconn);
	if ($result = $mysqli->query("SELECT * FROM  `Users` usr,  `Connections` con WHERE con.`Fan` = '".$userid."' AND con.`Celebrity` = usr.`ID` and (usr.`Access` = 'Journalist' || usr.`Access` = 'Authenticated') order by `First`")) {
		while($row = mysqli_fetch_array($result)){
				$users[] = $row["Celebrity"];
		}
	}
	if($pconn == null)
		Close($mysqli, $result);
	return $users;
}

function GetMutalConnections($userid){
	$users = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("SELECT * FROM `Connections` WHERE `Fan` = '".$userid."' and `Celebrity` in (select `Fan` from `Connections` where `Celebrity` = '".$userid."')")) {
		while($row = mysqli_fetch_array($result)){
			$users[] = $row["Celebrity"];
		}
	}
	Close($mysqli, $result);
	return $users;	
}

function DisplayName($user){
	$myuser = $_SESSION['logged-in'];
	$conn = GetMutalConnections($myuser->_id);
	if($user->_security == "Journalist"){
		echo $user->_first." ".$user->_last;
	}else if($myuser->_id == $user->_id && $user->_first != "" && $user->_last != ""){
		echo $user->_first." ".$user->_last;
	}else if($myuser->_realnames == "True" && in_array($user->_id, $conn)){
		echo $user->_username." (".$user->_first." ".$user->_last.")";
	}else{
		echo $user->_username;
	}	
}

function DisplayNameReturn($user){
	$myuser = $_SESSION['logged-in'];
	$conn = GetMutalConnections($myuser->_id);
	if($user->_security == "Journalist"){
		return $user->_first." ".$user->_last;
	//}else if($myuser->_id == $user->_id && $user->_first != "" && $user->_last != ""){
	//	return $user->_first." ".$user->_last;
	//}else if($myuser->_realnames == "True" && in_array($user->_id, $conn)){
	//	return $user->_username." (".$user->_first." ".$user->_last.")";
	}else{
		return $user->_username;
	}	
}

function GetConnectedToMe($userid){
	$users = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Users` usr, `Connections` con where con.`Celebrity` = '".$userid."' and con.`Fan` = usr.`ID` order by `Username`")) {
		while($row = mysqli_fetch_array($result)){
			if($row["Privacy"] != "Private"){
				$user= new User($row["Fan"], 
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
				$users[] = $user;
			}

		}
	}
	Close($mysqli, $result);
	return $users;
}

function GetConnectedCounts($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select count(*) as cnt from `Connections` con where con.`Celebrity` = '".$userid."' group by `Celebrity`")) {
		while($row = mysqli_fetch_array($result)){
			$followingme = $row["cnt"];
		}
	}
	
	if ($result = $mysqli->query("select count(*) as cnt from `Connections` con where con.`Fan` = '".$userid."' group by `Fan`")) {
		while($row = mysqli_fetch_array($result)){
			$following = $row["cnt"];
		}
	}
	
	$conn[] = $followingme;
	$conn[] = $following;
	Close($mysqli, $result);
	return $conn;
}

function RemoveConnection($userid, $removeid){
	$mysqli = Connect();
	$result = $mysqli->query("Delete from `Connections` where `Fan` = '".$userid."' and `Celebrity` = '".$removeid."'");
	Close($mysqli, $result);
	return "Delete from `Connections` where `Fan` = '".$userid."' and `Celebrity` = '".$removeid."'";
}

function AddConnection($userid, $addid){
	$mysqli = Connect();
    $brandnew = true;
    if ($result = $mysqli->query("select * from `Connections` where `Celebrity` = '".$addid."' and `Fan` = '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			if($row['ID'] > 0)
                $brandnew = false;
        }
    }
    if($brandnew){
        $mysqli->query("INSERT INTO `Connections`  (`Fan`, `Celebrity`) VALUES ('".$userid."', '".$addid."')");
        $result = $mysqli->query("insert into `Events` (`UserID`,`Event`,`Quote`) values ('$userid','CONNECTIONS','$addid')");
        Close($mysqli, $result);
        AddNewFollower($userid, $addid);
    }else{
        Close($mysqli, $result);
    }
}

function MakeRequest($name, $id){
	$mysqli = Connect();
	$mysqli->query("INSERT INTO `Requested`  (`Name`, `UserID`) VALUES ('".$name."', '".$id."')");
	Close($mysqli, $result);
}

function SearchForUser($search){
	$users = array();
	$mysqli = Connect();
	$namedivided = explode(" ", $search);
	/*if(sizeof($namedivided) == 2){
		$query = "select * from `Users` where `Username` like '%".$search."%' or (`First` like '%".$namedivided[0]."%' and `Last` like '%".$namedivided[1]."%') order by `First`";
	}else{
		$query = "select * from `Users` where `Username` like '%".$search."%' or (`First` like '%".$search."%' or `Last` like '%".$search."%') order by `First`";
	}*/
	$query = "select * from `Users` where `Username` like '%".$search."%' or ((`Access` = 'Journalist' or `Access` = 'Authenticated') and (`First` like '%".$search."%' or `Last` like '%".$search."%' or `First` like '%".$namedivided[0]."%' and `Last` like '%".$namedivided[1]."%')) order by `Username`";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			if($row["Privacy"] != "Private"){
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
				$users[] = $user;
			}

		}
	}
	Close($mysqli, $result);
	return $users;
}

function GetPopularUsers(){
	$users = array();
	$count = array();
	$mysqli = Connect();
	$query = "select *, Count(`Celebrity`) as TotalRows from `Connections` GROUP BY `Celebrity` ORDER BY COUNT(  `Celebrity` ) DESC LIMIT 15";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
				$users[] = GetUser($row["Celebrity"], $mysqli);
				$count[] = $row["TotalRows"];
		}
	}
	$total[] = $users;
	$total[] = $count;
	Close($mysqli, $result);
	return $total;
}

function GetActivePersonalitiesCategory(){
	$users = array();
	$count = array();
	$mysqli = Connect();
	$date = date('Y-m-d', strtotime("now -30 days") );
	$query = "select *, Count(`UserID`) as TotalRows from `Events` event, `Users` users WHERE `Date` > '".$date."' and users.`Access` != 'User' and users.`Access` != 'Admin' and users.`ID` = event.`UserID` GROUP BY `UserID` ORDER BY COUNT(  `UserID` ) DESC LIMIT 6";
	//echo $query;
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
				$users[] = GetUser($row["UserID"], $mysqli);
		}
	}
	Close($mysqli, $result);
	return $users;
}

function GetActivePersonalities(){
	$users = array();
	$count = array();
	$mysqli = Connect();
	$date = date('Y-m-d', strtotime("now -30 days") );
	$query = "select *, Count(`UserID`) as TotalRows from `Events` event, `Users` users WHERE `Date` > '".$date."' and users.`Access` != 'User' and users.`Access` != 'Admin' and users.`ID` = event.`UserID` GROUP BY `UserID` ORDER BY COUNT(  `UserID` ) DESC LIMIT 15";
	//echo $query;
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
				$users[] = GetUser($row["UserID"], $mysqli);
		}
	}
	Close($mysqli, $result);
	return $users;
}


function GetNewUsersCategory($limit){
	$users = array();
	$mysqli = Connect();
	$thisquarter = date('Y-m-d', strtotime("now -5 days") );
	if ($result = $mysqli->query("select * from `Users` usr where usr.`Access` != 'Journalist' and usr.`Access` != 'Authenticated' and usr.`Established` >= '".$thisquarter."' ORDER BY `ID` DESC LIMIT ".$limit)) {
		while($row = mysqli_fetch_array($result)){
			$users[] = GetUser($row["ID"], $mysqli);
		}
	}
	Close($mysqli, $result);
	
	return $users;
}

function GetActiveUsers(){
	$users = array();
	$count = array();
	$mysqli = Connect();
	$date = date('Y-m-d', strtotime("now -3 days") );
	$query = "select *, Count(`UserID`) as TotalRows from `Events` event, `Users` users WHERE `Date` > '".$date."' and `Access` != 'Journalist' and `Access` != 'Authenticated' and users.`ID` = event.`UserID` GROUP BY `UserID` ORDER BY COUNT(  `UserID` ) DESC LIMIT 15";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
				$users[] = GetUser($row["UserID"],$mysqli);
				$count[] = $row["TotalRows"];
		}
	}
	$total[] = $users;
	$total[] = $count;
	Close($mysqli, $result);
	return $total;
}

function GetNewUsers(){
	$users = array();
	$mysqli = Connect();
	$query = "select * from `Users` where `Access` != 'Journalist' ORDER BY `ID` DESC LIMIT 15";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
				$users[] = GetUser($row["ID"], $mysqli);
		}
	}
	Close($mysqli, $result);
	return $users;
}

function VerifyKey($key){
	$users = array();
	$mysqli = Connect();
	if(strlen($key) > 9){
		$query = "select * from `Users` where `Key` = '".$key."' and `Key` != 'ACTIVE'";
		if ($result = $mysqli->query($query)) {
			while($row = mysqli_fetch_array($result)){
					$users[] = GetUser($row["ID"]);
			}
		}
	}
	Close($mysqli, $result);
	return $users;
}

function RegisterEarlyAccess($email){
	$mysqli = Connect();
	$uniqueemail = true;
	if ($result = $mysqli->query("SELECT * FROM  `Users` where `Email` = '".$email."'")) {
		while($row = mysqli_fetch_array($result)){
			if($row['Key'] == "ACTIVE"){
				$randomKey = hash('sha256',uniqid(mt_rand(), true).uniqid(mt_rand(), true));
				$mysqli->query("UPDATE `Users` SET `Key` = '".$randomKey."' WHERE `Email` ='".$email."'");
			}else{
				$uniqueemail = false;
				$randomKey = $row['Key'];
			}
			
		}
	}
	
	if($uniqueemail){
		$randomKey = hash('sha256',uniqid(mt_rand(), true).uniqid(mt_rand(), true));
		$mysqli->query("INSERT INTO `Users`  (`Email`, `Key`) VALUES ('".$email."', '".$randomKey."')");
	}
	Close($mysqli, $result);
	return $randomKey;
}

function SaveUserTitle($userid, $title){
	$mysqli = Connect();
	$mysqli->query("UPDATE `Users` SET `Title` = '".$title."' WHERE `ID` ='".$userid."'");
	Close($mysqli, $result);
}

?>
