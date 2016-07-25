<?php
require_once "includes.php";

//MilestonesForCritics(0);

function GetOnboardingMilestones(){
	$mysqli = Connect();
	$milestones = array();
	
	//Platforms
	$platforms = array();
		//Must have
		$platforms[] = 145;	$platforms[] = 146;	$platforms[] = 94;	$platforms[] = 139;	$platforms[] = 117;
		//Extra
		$platforms[] = 20;	$platforms[] = 35;	$platforms[] = 96;	$platforms[] = 43;	$platforms[] = 9;	$platforms[] = 22;
		$platforms[] = 6;	$platforms[] = 19;	$platforms[] = 21;	$platforms[] = 36;	$platforms[] = 4;
		
	//Franchises
	$franchises = array();
		$franchises[] = 82; $franchises[] = 654; $franchises[] = 173; $franchises[] = 326; $franchises[] = 383; $franchises[] = 293; $franchises[] = 263; $franchises[] = 156;
		$franchises[] = 194; $franchises[] = 6; $franchises[] = 331; $franchises[] = 186; $franchises[] = 1; $franchises[] = 491; $franchises[] = 2; $franchises[] = 397;
		$franchises[] = 456; $franchises[] = 49; $franchises[] = 7; $franchises[] = 9; $franchises[] = 267; $franchises[] = 125; $franchises[] = 565; $franchises[] = 3; $franchises[] = 609;
		$franchises[] = 32; $franchises[] = 46; $franchises[] = 240; $franchises[] = 82; $franchises[] = 159; $franchises[] = 82; $franchises[] = 1609; $franchises[] = 1575; $franchises[] = 523;
		
	//Developers
	$developers = array();
		$developers[] = 476; $developers[] = 827; $developers[] = 463; $developers[] = 397; $developers[] = 1088; $developers[] = 98; $developers[] = 1374; $developers[] = 347; $developers[] = 104;
		$developers[] = 1408; $developers[] = 367; $developers[] = 1559; $developers[] = 6753; $developers[] = 1488;  $developers[] = 3342; $developers[] = 1412; $developers[] = 1615;
		$developers[] = 549; $developers[] = 1526; $developers[] = 2545; $developers[] = 3899; $developers[] = 1379; $developers[] = 149; $developers[] = 2215; $developers[] = 6319;
		$developers[] = 1082; $developers[] = 340; $developers[] = 4880; $developers[] = 174; $developers[] = 293;
		
	
	if ($result = $mysqli->query("select * from `Milestones` where (`Category` = 'Developers' and `ObjectID` in (".implode(",", $developers).")) or (`Category` = 'Platform' and `ObjectID` in (".implode(",", $platforms).")) or (`Category` = 'Franchises' and `ObjectID` in (".implode(",", $franchises).")) order by rand()")) {
		while($row = mysqli_fetch_array($result)){
			$milestone = new Milestone($row['ID'],
			$row['Name'],
			$row['Description'],
			$row['Type'],
			$row['Image'],
			$row['Difficulty'],
			$row['Validation'],
			$row['Level1'],
			$row['Level2'],
			$row['Level3'],
			$row['Level4'],
			$row['Level5'],
			$row['Enabled'],
			$row['Parent'],
			$row['Category'],
			null,
			$row['ObjectID']
			);
			$milestones[] = $milestone;
		}
	}
	
	
	Close($mysqli, $result);
	return $milestones;
}

function MilestonesForCritics($i){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Users` where `Access` = 'Journalist' LIMIT ".$i.",10")) {
		while($row = mysqli_fetch_array($result)){
			echo $row["First"]." ".$row["Last"]."<br>";
			if ($result2 = $mysqli->query("select * from `Experiences` where `UserID` = '".$row['ID']."'")) {
				while($row2 = mysqli_fetch_array($result2)){
					echo $row2['GameID']." - ";
					$gbid = GetGameByGBIDFull($row['GameID'], $mysqli);
					CalculateMilestones($row['ID'], $gbid, '', "Played XP", true);
				}
			}
			echo "<br><br>";
		}
	}
	Close($mysqli, $result);
}


function GetAllMilestones($userid){
	$mysqli = Connect();
	$milestones = array();
	if ($result = $mysqli->query("select * from `Milestones` order by `Category`, `Parent`")) {
		while($row = mysqli_fetch_array($result)){
			$milestone = new Milestone($row['ID'],
			$row['Name'],
			$row['Description'],
			$row['Type'],
			$row['Image'],
			$row['Difficulty'],
			$row['Validation'],
			$row['Level1'],
			$row['Level2'],
			$row['Level3'],
			$row['Level4'],
			$row['Level5'],
			$row['Enabled'],
			$row['Parent'],
			$row['Category'],
			GetMilestoneProgression($row['ID'], $userid, $mysqli),
			$row['ObjectID']
			);
			$milestones[] = $milestone;
		}
	}
	Close($mysqli, $result);
	return $milestones;
}

function GetMilestonesForCategory($userid, $category){
	$mysqli = Connect();
	$milestones = array();
	if ($result = $mysqli->query("select * from `Milestones` where `Category` = '".$category."' order by `Parent`")) {
		while($row = mysqli_fetch_array($result)){
			$milestone = new Milestone($row['ID'],
			$row['Name'],
			$row['Description'],
			$row['Type'],
			$row['Image'],
			$row['Difficulty'],
			$row['Validation'],
			$row['Level1'],
			$row['Level2'],
			$row['Level3'],
			$row['Level4'],
			$row['Level5'],
			$row['Enabled'],
			$row['Parent'],
			$row['Category'],
			GetMilestoneProgression($row['ID'], $userid, $mysqli),
			$row['ObjectID']
			);
			$milestones[] = $milestone;
		}
	}
	Close($mysqli, $result);
	return $milestones;
}

function GetRecentlyFinishedMilestones($userid){
	$mysqli = Connect();
	$milestones = array();
	if ($result = $mysqli->query("select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and b.`ID` = p.`MilestoneID` and `Percentage` = '100' ORDER BY `CompletionDate` DESC LIMIT 0,10")) {
		while($row = mysqli_fetch_array($result)){
			$milestone = new Milestone($row[0],
			$row['Name'],
			$row['Description'],
			$row['Type'],
			$row['Image'],
			$row['Difficulty'],
			$row['Validation'],
			$row[9],
			$row[10],
			$row[11],
			$row[12],
			$row[13],
			$row['Enabled'],
			$row['Parent'],
			$row['Category'],
			GetMilestoneProgression($row[0], $userid, $mysqli),
			$row['ObjectID']
			);
			$milestones[] = $milestone;
		}
	}
	Close($mysqli, $result);
	return $milestones;
}

function GetRecentlyStartedMilestones($userid){
	$mysqli = Connect();
	$milestones = array();
	if ($result = $mysqli->query("select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and b.`ID` = p.`MilestoneID` and `Percentage` < '100' and `Percentage` > 0 ORDER BY `Start` DESC LIMIT 0,10")) {
		while($row = mysqli_fetch_array($result)){
			$milestone = new Milestone($row[0],
			$row['Name'],
			$row['Description'],
			$row['Type'],
			$row['Image'],
			$row['Difficulty'],
			$row['Validation'],
			$row[9],
			$row[10],
			$row[11],
			$row[12],
			$row[13],
			$row['Enabled'],
			$row['Parent'],
			$row['Category'],
			GetMilestoneProgression($row[0], $userid, $mysqli)
			);
			$milestones[] = $milestone;
		}
	}
	Close($mysqli, $result);
	return $milestones;
}

function GetNearlyFinishedMilestones($userid){
	$mysqli = Connect();
	$milestones = array();
	if ($result = $mysqli->query("select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and b.`ID` = p.`MilestoneID` and ((b.`Threshold` >= 25 and `Percentage` < '100' and `Percentage` > '90') or (b.`Threshold` < 25 and `Percentage` < '100' and `Percentage` > '51') or (b.`Threshold` <= 3 and `Percentage` < '100')) ORDER BY `Percentage` DESC LIMIT 0,10")) {
		while($row = mysqli_fetch_array($result)){
			$milestone = new Milestone($row[0],
			$row['Name'],
			$row['Description'],
			$row['Type'],
			$row['Image'],
			$row['Difficulty'],
			$row['Validation'],
			$row[9],
			$row[10],
			$row[11],
			$row[12],
			$row[13],
			$row['Enabled'],
			$row['Parent'],
			$row['Category'],
			GetMilestoneProgression($row[0], $userid, $mysqli),
			$row['ObjectID']
			);
			$milestones[] = $milestone;
		}
	}
	Close($mysqli, $result);
	return $milestones;
}

function GetFranchiseMilestones($userid, $limit, $type){
	$mysqli = Connect();
	$milestones = array();
	$yearago = date('Y-m-d', strtotime("now -360 days") );
	if($type == "past")
		$query = "select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and b.`ID` = p.`MilestoneID` and b.`Category` = 'Franchises' and p.`Level1` > 0 and p.`LastUpdate` < '".$yearago."' ORDER BY p.`LastUpdate` DESC, p.`Start` DESC LIMIT 0,".$limit;
	else if($type == "year")
		$query = "select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and b.`ID` = p.`MilestoneID` and b.`Category` = 'Franchises' and p.`Level1` > 0 and p.`LastUpdate` > '".$yearago."' ORDER BY p.`LastUpdate` DESC, p.`Start` DESC LIMIT 6,".$limit;
	else
		$query = "select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and b.`ID` = p.`MilestoneID` and b.`Category` = 'Franchises' and p.`Level1` > 0 ORDER BY p.`LastUpdate` DESC, p.`Start` DESC LIMIT 0,".$limit;
	
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$milestone = new Milestone($row[0],
			$row['Name'],
			$row['Description'],
			$row['Type'],
			$row['Image'],
			$row['Difficulty'],
			$row['Validation'],
			$row[9],
			$row[10],
			$row[11],
			$row[12],
			$row[13],
			$row['Enabled'],
			$row['Parent'],
			$row['Category'],
			GetMilestoneProgression($row[0], $userid, $mysqli),
			$row['ObjectID']
			);
			$milestones[] = $milestone;
		}
	}
	Close($mysqli, $result);
	return $milestones;
}

function GetFranchiseMilestoneForUser($milestoneid, $userid){
	$mysqli = Connect();
	$query = "select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and p.`ID` = '".$milestoneid."' and b.`ID` = p.`MilestoneID` and `Category` = 'Franchises'";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$milestone = new Milestone($row[0],
			$row['Name'],
			$row['Description'],
			$row['Type'],
			$row['Image'],
			$row['Difficulty'],
			$row['Validation'],
			$row[9],
			$row[10],
			$row[11],
			$row[12],
			$row[13],
			$row['Enabled'],
			$row['Parent'],
			$row['Category'],
			GetMilestoneProgression($row[0], $userid, $mysqli),
			$row['ObjectID']
			);
		}
	}
	Close($mysqli, $result);
	return $milestone;
}

function GetKnowledgeGames($knowledgeid, $userid){
	$mysqli = Connect();
	$myxp = array();
	$query = "select * from `Game_Franchises` f, `Games` g where f.`FranchiseID` = '".$knowledgeid."' and g.`GBID` = f.`GBID`";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$xp = GetExperienceForUserComplete($userid, $row['ID'], $mysqli);
			if($xp->_id == ""){
				$user = GetUser($userid);
				$experience = new Experience(-1,
					$user->_first,
					$user->_last,
					$user->_username,
					$userid,
					$row["ID"],
					GetGame($row["ID"], $mysqli),
					0,
					'',
					'',
					'',
					$row["Owned"],
					$row["BucketList"],
					$row["AuthenticXP"]);
					$myxp[] = $experience; 
			}else{
				$myxp[] = $xp;
			}
		}
	}
	Close($mysqli, $result);
	return $myxp;
}

function GetKnowledgeGamesForDiscover($knowledgeid, $userid){
	$mysqli = Connect();
	$myxp = array();
	$query = "select * from `Game_Franchises` f, `Games` g where f.`FranchiseID` = '".$knowledgeid."' and g.`GBID` = f.`GBID` and g.`ID` not in (select `GameID` from `Sub-Experiences` where `UserID` = '".$userid."')";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$xp = GetExperienceForUserComplete($userid, $row['ID'], $mysqli);
			if($xp->_id == ""){
				$user = GetUser($userid);
				$experience = new Experience(-1,
					$user->_first,
					$user->_last,
					$user->_username,
					$userid,
					$row["ID"],
					GetGame($row["ID"], $mysqli),
					0,
					'',
					'',
					'',
					$row["Owned"],
					$row["BucketList"],
					$row["AuthenticXP"]);
					$myxp[] = $experience; 
			}else{
				$myxp[] = $xp;
			}
		}
	}
	Close($mysqli, $result);
	return $myxp;
}

function GetPlatformMilestones($userid){
	$mysqli = Connect();
	$milestones = array();
	if ($result = $mysqli->query("select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and b.`ID` = p.`MilestoneID` and b.`Category` = 'Platform' ORDER BY p.`Level4` DESC,p.`Level5` DESC,p.`Level3` DESC,p.`Level2` DESC,p.`Level1` DESC LIMIT 0,50")) {
		while($row = mysqli_fetch_array($result)){
			$milestone = new Milestone($row[0],
			$row['Name'],
			$row['Description'],
			$row['Type'],
			$row['Image'],
			$row['Difficulty'],
			$row['Validation'],
			$row[9],
			$row[10],
			$row[11],
			$row[12],
			$row[13],
			$row['Enabled'],
			$row['Parent'],
			$row['Category'],
			GetMilestoneProgression($row[0], $userid, $mysqli),
			$row['ObjectID']
			);
			$milestones[] = $milestone;
		}
	}
	Close($mysqli, $result);
	return $milestones;
}

function GetPlatformMilestone($userid, $platform){
	$mysqli = Connect();
	$query = "select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and b.`ObjectID` = '".$platform."' and b.`ID` = p.`MilestoneID` and `Category` = 'Platform'";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$milestone = new Milestone($row[0],
			$row['Name'],
			$row['Description'],
			$row['Type'],
			$row['Image'],
			$row['Difficulty'],
			$row['Validation'],
			$row[9],
			$row[10],
			$row[11],
			$row[12],
			$row[13],
			$row['Enabled'],
			$row['Parent'],
			$row['Category'],
			GetMilestoneProgression($row[0], $userid, $mysqli),
			$row['ObjectID']
			);
		}
	}
	Close($mysqli, $result);
	return $milestone;
}

function GetPlatformGames($platformid, $userid){
	$mysqli = Connect();
	$myxp = array();
	$query = "select * from `Sub-Experiences` e where (e.`PlatformIDs` = '".$platformid."' OR e.`PlatformIDs` LIKE '%,".$platformid."' OR e.`PlatformIDs` LIKE '%,".$platformid.",%' OR e.`PlatformIDs` LIKE '".$platformid.",%') and e.`Type` = 'Played' and e.`UserID` = '".$userid."' and e.`Archived` = 'No'";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$xp = GetExperienceForUserComplete($userid, $row['GameID'], $mysqli);
			$myxp[] = $xp;
		}
	}
	Close($mysqli, $result);
	return $myxp;
}

function GetPlatformGamesForDiscover($platformid, $userid){
	$mysqli = Connect();
	$myxp = array();
	$query = $query = "select * from `Game_Platforms` f, `Games` g where f.`PlatformID` = '".$platformid."' and g.`GBID` = f.`GBID` and g.`ID` not in (select `GameID` from `Sub-Experiences` where `UserID` = '".$userid."') LIMIT 0, 6";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$xp = GetExperienceForUserComplete($userid, $row['ID'], $mysqli);
			if($xp->_id == ""){
				$user = GetUser($userid);
				$experience = new Experience(-1,
					$user->_first,
					$user->_last,
					$user->_username,
					$userid,
					$row["ID"],
					GetGame($row["ID"], $mysqli),
					0,
					'',
					'',
					'',
					$row["Owned"],
					$row["BucketList"],
					$row["AuthenticXP"]);
					$myxp[] = $experience; 
			}else{
				$myxp[] = $xp;
			}
		}
	}
	Close($mysqli, $result);
	return $myxp;
}


function GetDeveloperMilestones($userid, $limit, $type){
	$mysqli = Connect();
	$milestones = array();
	$yearago = date('Y-m-d', strtotime("now -360 days") );
	if($type == "past")
		$query = "select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and b.`ID` = p.`MilestoneID` and b.`Category` = 'Developers' and p.`Level1` > 0 and p.`LastUpdate` < '".$yearago."' ORDER BY p.`LastUpdate` DESC, p.`Start` DESC LIMIT 0,".$limit;
	else if($type == "year")
		$query = "select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and b.`ID` = p.`MilestoneID` and b.`Category` = 'Developers' and p.`Level1` > 0 and p.`LastUpdate` > '".$yearago."' ORDER BY p.`LastUpdate` DESC, p.`Start` DESC LIMIT 10,".$limit;
	else if($type == "recent")
		$query = "select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and b.`ID` = p.`MilestoneID` and b.`Category` = 'Developers' and p.`Level1` > 0  ORDER BY p.`LastUpdate` DESC, p.`Start` DESC LIMIT 0,".$limit;
	else
		$query = "select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and b.`ID` = p.`MilestoneID` and b.`Category` = 'Developers' and p.`Level1` > 0 ORDER BY p.`Level5` DESC, p.`Level4` DESC, p.`Level3` DESC, p.`Level2` DESC, p.`Level1` DESC  LIMIT 0,10";
	
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$milestone = new Milestone($row[0],
			$row['Name'],
			$row['Description'],
			$row['Type'],
			$row['Image'],
			$row['Difficulty'],
			$row['Validation'],
			$row[9],
			$row[10],
			$row[11],
			$row[12],
			$row[13],
			$row['Enabled'],
			$row['Parent'],
			$row['Category'],
			GetMilestoneProgression($row[0], $userid, $mysqli),
			$row['ObjectID']
			);
			$milestones[] = $milestone;
		}
	}
	Close($mysqli, $result);
	return $milestones;
}

function GetDeveloperMilestoneForUser($milestoneid, $userid){
	$mysqli = Connect();
	$query = "select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and p.`ID` = '".$milestoneid."' and b.`ID` = p.`MilestoneID` and `Category` = 'Developers'";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$milestone = new Milestone($row[0],
			$row['Name'],
			$row['Description'],
			$row['Type'],
			$row['Image'],
			$row['Difficulty'],
			$row['Validation'],
			$row[9],
			$row[10],
			$row[11],
			$row[12],
			$row[13],
			$row['Enabled'],
			$row['Parent'],
			$row['Category'],
			GetMilestoneProgression($row[0], $userid, $mysqli),
			$row['ObjectID']
			);
		}
	}
	Close($mysqli, $result);
	return $milestone;
}

function GetDeveloperGamesForDiscover($devid, $userid){
	$mysqli = Connect();
	$myxp = array();
	$query = "select * from `Game_Developers` f, `Games` g where f.`DeveloperID` = '".$devid."' and g.`GBID` = f.`GBID` and g.`ID` not in (select `GameID` from `Sub-Experiences` where `UserID` = '".$userid."')";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$xp = GetExperienceForUserComplete($userid, $row['ID'], $mysqli);
			if($xp->_id == ""){
				$user = GetUser($userid);
				$experience = new Experience(-1,
					$user->_first,
					$user->_last,
					$user->_username,
					$userid,
					$row["ID"],
					GetGame($row["ID"], $mysqli),
					0,
					'',
					'',
					'',
					$row["Owned"],
					$row["BucketList"],
					$row["AuthenticXP"]);
					$myxp[] = $experience; 
			}else{
				$myxp[] = $xp;
			}
		}
	}
	Close($mysqli, $result);
	return $myxp;
}

function GetSpecificMilestoneForUser($userid, $objectID, $type){
	$mysqli = Connect();
	$query = "select * from `Milestones` b, `Milestone_Progression` p where p.`UserID` = '".$userid."' and `Type` = 'XP' and b.`ObjectID` = '".$objectID."' and b.`ID` = p.`MilestoneID` and `Category` = '".$type."'";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$milestone = new Milestone($row[0],
			$row['Name'],
			$row['Description'],
			$row['Type'],
			$row['Image'],
			$row['Difficulty'],
			$row['Validation'],
			$row[9],
			$row[10],
			$row[11],
			$row[12],
			$row[13],
			$row['Enabled'],
			$row['Parent'],
			$row['Category'],
			GetMilestoneProgression($row[0], $userid, $mysqli),
			$row['ObjectID']
			);
			
		}
	}
	Close($mysqli, $result);
	return $milestone;
}


function CalculateMilestones($userid, $gameid, $anotheruserid, $action, $critic){
	$progress = array();
	$mysqli = Connect();
	//Always calculate these on all triggers....
	$generalresults = TestMilestones($userid, $gameid, "select * from `Milestones` where `Type` = 'General'", $mysqli);
	if(sizeof($generalresults) > 0){
		$progress = array_merge($progress, $generalresults);
	}
	
	if($gameid > 0){
		$game = GetGame($gameid, $mysqli);
		$gamemeta = GetGameMetaDataIDs($game->_gbid, $mysqli);
		$objectchecks = "and (";
		$objectarray = array();
		if(sizeof($gamemeta->_franchises) > 0)
			$objectarray[] = "`ObjectID` in (".implode(",",$gamemeta->_franchises).") and `Category` = 'Franchises'";
		
		if(sizeof($gamemeta->_developers) > 0)
			$objectarray[] = "`ObjectID` in (".implode(",",$gamemeta->_developers).") and `Category` = 'Developers'";
			
		if(sizeof($gamemeta->_platforms) > 0)
			$objectarray[] = "`ObjectID` in (".implode(",",$gamemeta->_platforms).") and `Category` = 'Platform'";
		
		if(sizeof($objectarray) > 0){
			
			$objectchecks = $objectchecks.implode(" or ",$objectarray)." )";
		}else
			$objectchecks = "";
	}else{
		$objectchecks = "";
	}
	
	if(	$action == "Bookmark" || $action == "Owned"){
		$results = TestMilestones($userid, $gameid, "select * from `Milestones` where `Type` = 'Bookmark' or `Type` = 'Owned'", $mysqli);
	}else if($action == "Connection"){
		$results = TestMilestones($userid, $gameid, "select * from `Milestones` where `Type` = 'Connection'", $mysqli);
	}else if($action == "XP"){
		$results = TestMilestones($userid, $gameid, "select * from `Milestones` where `Type` = 'XP' ".$objectchecks, $mysqli); 
	}else if($action == "Played XP"){
		$results = TestMilestones($userid, $gameid, "select * from `Milestones` where (`Type` = 'Played' or `Type` = 'XP') ".$objectchecks, $mysqli);
	}else if($action == "Watched XP"){
		$results = TestMilestones($userid, $gameid, "select * from `Milestones` where (`Type` = 'Watched'or `Type` = 'XP') ".$objectchecks, $mysqli);
	}else if($action == "1up"){
		$results = TestMilestones($userid, $gameid, "select * from `Milestones` where `Type` = '1up'", $mysqli);
	}
	
	if(sizeof($results) > 0){
		$progress = array_merge($progress, $results);
	}
	
	Close($mysqli, $result);
	
	if(!$critic)
		DisplayBattleProgress(GetUser($userid), $progress, $gameid);
}

function CalculateAllMilestones($userid){
	$mysqli = Connect();
 	TestMilestones($userid, 0, "select * from `Milestones` ORDER BY `ID` DESC", $mysqli);
 	Close($mysqli, $result);
}

function TestMilestones($userid, $gameid, $query, $mysqli){
	$progress = array();
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$milestoneprogress = GetMilestoneProgression($row['ID'], $userid, $mysqli);
			if($milestoneprogress->_progresslevel1 < $row['Level1'] || $milestoneprogress->_progresslevel2 < $row['Level2'] || 
				$milestoneprogress->_progresslevel3 < $row['Level3'] || $milestoneprogress->_progresslevel4 < $row['Level4'] ||
				$milestoneprogress->_progresslevel5 < $row['Level5']){
				$script = BindMilestonesVariables($row['Validation'], $userid, $gameid);
				
				//reset variables
				$percentlevel1 = 0;
				$percentlevel2 = 0;
				$percentlevel3 = 0;
				$percentlevel4 = 0;
				$percentlevel5 = 0;
				$level1 = 0;
				$level2 = 0;
				$level3 = 0;
				$level4 = 0;
				$level5 = 0;
				$total = 0;
				if ($testresult = $mysqli->query($script)){
					while($testrow = mysqli_fetch_array($testresult)){
						$total = $testrow['cnt'];
						if($row['Level1'] > 0){
							if($total >=  $row['Level1']){
								$level1 = $row['Level1'];
								$percentlevel1 = 100;
							}else{
								$level1 = $total;
								$percentlevel1 = round(($total / $row['Level1']) * 100);
							}
						}	
						if($row['Level2'] > 0){
							if($total >=  $row['Level2']){
								$level2 = $row['Level2'];
								$percentlevel2 = 100;
							}else{
								$level2 = $total;
								$percentlevel2 = round(($total / $row['Level2']) * 100);
							}
						}
						if($row['Level3'] > 0){
							if($total >=  $row['Level3']){
								$level3 = $row['Level3'];
								$percentlevel3 = 100;
							}else{
								$level3 = $total;
								$percentlevel3 = round(($total / $row['Level3']) * 100);
							}
						}
						if($row['Level4'] > 0){
							if($total >=  $row['Level4']){
								$level4 = $row['Level4'];
								$percentlevel4 = 100;
							}else{
								$level4 = $total;
								$percentlevel4 = round(($total / $row['Level4']) * 100);
							}
						}
						if($row['Level5'] > 0){
							if($total >=  $row['Level5']){
								$level5 = $row['Level5'];
								$percentlevel5 = 100;
							}else{
								$level5 = $total;
								$percentlevel5 = round(($total / $row['Level5']) * 100);
							}
						}
					}
				}
				
				//Keep existing finished dates
				$finishlevel1 = $milestoneprogress->_finishlevel1;
				$finishlevel2 = $milestoneprogress->_finishlevel2;
				$finishlevel3 = $milestoneprogress->_finishlevel3;
				$finishlevel4 = $milestoneprogress->_finishlevel4;
				$finishlevel5 = $milestoneprogress->_finishlevel5;

				//Was a new level finished?
				if($milestoneprogress->_progresslevel1 < $row['Level1'] && $total >= $row['Level1'] && $row['Level1'] != 0){
					$progress[] = new BattleProgressItem($row['ID'],$row['Name'], $row['Description'], $row['Category'],$userid,$milestoneprogress->_progresslevel1,$total,$row['Level1'],1,2,$row['Image']);
					$finishlevel1 = date('Y-m-d H:i:s');
				}else if($milestoneprogress->_progresslevel2 < $row['Level2'] && $total >= $row['Level2'] && $row['Level2'] != 0){
					$progress[] = new BattleProgressItem($row['ID'],$row['Name'], $row['Description'], $row['Category'],$userid,$milestoneprogress->_progresslevel2,$total,$row['Level2'],2,3,$row['Image']);
					$finishlevel2 = date('Y-m-d H:i:s');
				}else if($milestoneprogress->_progresslevel3 < $row['Level3'] && $total >= $row['Level3'] && $row['Level3'] != 0){
					$progress[] = new BattleProgressItem($row['ID'],$row['Name'], $row['Description'], $row['Category'],$userid,$milestoneprogress->_progresslevel3,$total,$row['Level3'],3,4,$row['Image']);
					$finishlevel3 = date('Y-m-d H:i:s');
				}else if($milestoneprogress->_progresslevel4 < $row['Level4'] && $total >= $row['Level4'] && $row['Level4'] != 0){
					$progress[] = new BattleProgressItem($row['ID'],$row['Name'], $row['Description'], $row['Category'],$userid,$milestoneprogress->_progresslevel4,$total,$row['Level4'],4,5,$row['Image']);
					$finishlevel4 = date('Y-m-d H:i:s');
				}else if($milestoneprogress->_progresslevel5 < $row['Level5'] && $total >= $row['Level5'] && $row['Level5'] != 0){
					$progress[] = new BattleProgressItem($row['ID'],$row['Name'], $row['Description'], $row['Category'],$userid,$milestoneprogress->_progresslevel5,$total,$row['Level1'],5,6,$row['Image']);
					$finishlevel5 = date('Y-m-d H:i:s');
				}else{
					//Was progress made?
					if($milestoneprogress->_percentlevel1 < $percentlevel1 )
						$progress[] = new BattleProgressItem($row['ID'],$row['Name'], $row['Description'], $row['Category'],$userid,$milestoneprogress->_progresslevel1,$total,$row['Level1'],1,1,$row['Image']);
					else if($milestoneprogress->_percentlevel2 < $percentlevel2 )
						$progress[] = new BattleProgressItem($row['ID'],$row['Name'], $row['Description'], $row['Category'],$userid,$milestoneprogress->_progresslevel2,$total,$row['Level2'],2,2,$row['Image']);
					else if($milestoneprogress->_percentlevel3 < $percentlevel3 )
						$progress[] = new BattleProgressItem($row['ID'],$row['Name'], $row['Description'], $row['Category'],$userid,$milestoneprogress->_progresslevel3,$total,$row['Level3'],3,3,$row['Image']);
					else if($milestoneprogress->_percentlevel4 < $percentlevel4 )
						$progress[] = new BattleProgressItem($row['ID'],$row['Name'], $row['Description'], $row['Category'],$userid,$milestoneprogress->_progresslevel4,$total,$row['Level4'],4,4,$row['Image']);
					else if($milestoneprogress->_percentlevel5 < $percentlevel5 )
						$progress[] = new BattleProgressItem($row['ID'],$row['Name'], $row['Description'], $row['Category'],$userid,$milestoneprogress->_progresslevel5,$total,$row['Level5'],5,5,$row['Image']);
				}
				
				//Comment this out to do testing with BattleProgress
				UpdateMilestoneProgress($row['ID'], $userid, $level1, $level2, $level3, $level4, $level5, $percentlevel1, $percentlevel2, $percentlevel3, $percentlevel4, $percentlevel5, $finishlevel1, $finishlevel2, $finishlevel3, $finishlevel4, $finishlevel5, $mysqli);
			}
		}
	}
	
	return $progress;
}

function TestSpecificMilestone($milestoneid, $userid){
	$mysqli = Connect();
	$query = "select * from `Milestones` where `ID` = '".$milestoneid."'";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$script = BindMilestonesVariables($row['Validation'], $userid, $gameid);
			if ($testresult = $mysqli->query($script)){
				while($testrow = mysqli_fetch_array($testresult)){
					$total = $testrow['cnt'];
					$percentage = round(($total / $row['Threshold']) * 100);
				}
			}

			UpdateMilestoneProgress($row['ID'], $userid, $total, $percentage, $mysqli);
		}
	}
	Close($mysqli, $result);
}

function UpdateMilestoneProgress($milestoneid, $userid, $level1, $level2, $level3, $level4, $level5, $percentlevel1, $percentlevel2, $percentlevel3, $percentlevel4, $percentlevel5, $finishlevel1, $finishlevel2, $finishlevel3, $finishlevel4, $finishlevel5, $pconn = null){
	$mysqli = Connect($pconn);
	$new = true;
	
	if ($result = $mysqli->query("select * from `Milestone_Progression` where `MilestoneID` = '".$milestoneid."' and `UserID` = '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			$new = false;	
		}
	}
	
	$updated = date('Y-m-d H:i:s');
	
	if($new)
		$mysqli->query("insert into `Milestone_Progression` (`UserID`,`MilestoneID`,`Level1`,`Level2`,`Level3`,`Level4`,`Level5`,`PercentLevel1`,`PercentLevel2`,`PercentLevel3`,`PercentLevel4`,`PercentLevel5`,`FinishLevel1`,`FinishLevel2`,`FinishLevel3`,`FinishLevel4`,`FinishLevel5`,`LastUpdate`) values ('$userid','$milestoneid','$level1','$level2','$level3','$level4','$level5','$percentlevel1','$percentlevel2','$percentlevel3','$percentlevel4','$percentlevel5','$finishlevel1','$finishlevel2','$finishlevel3','$finishlevel4','$finishlevel5','$updated')");
	else
		$mysqli->query("update `Milestone_Progression` set `Level1` = '$level1', `Level2` = '$level2', `Level3` = '$level3', `Level4` = '$level4', `Level5` = '$level5', `PercentLevel1` = '$percentlevel1', `PercentLevel2` = '$percentlevel2', `PercentLevel3` = '$percentlevel3', `PercentLevel4` = '$percentlevel4', `PercentLevel5` = '$percentlevel5', `FinishLevel1` = '$finishlevel1', `FinishLevel2` = '$finishlevel2', `FinishLevel3` = '$finishlevel3', `FinishLevel4` = '$finishlevel4', `FinishLevel5` = '$finishlevel5', `LastUpdate` = '$updated' where `UserID` = '$userid' and `MilestoneID` = '$milestoneid'");

    if($pconn == null)
	   Close($mysqli, $result);
}

function GetMilestoneProgression($milestoneid, $userid, $mysqli){
	if ($result = $mysqli->query("select * from `Milestone_Progression` where `MilestoneID` = '".$milestoneid."' and `UserID` = '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			$milestoneprogress = new MilestoneProgress($row['ID'],
								$row['UserID'],
								$row['MilestoneID'],
								$row['Level1'],
								$row['Level2'],
								$row['Level3'],
								$row['Level4'],
								$row['Level5'],
								$row['PercentLevel1'],
								$row['PercentLevel2'],
								$row['PercentLevel3'],
								$row['PercentLevel4'],
								$row['PercentLevel5'],
								$row['Start'],
								$row['FinishLevel1'],
								$row['FinishLevel2'],
								$row['FinishLevel3'],
								$row['FinishLevel4'],
								$row['FinishLevel5']
								);
								
		}
	}	
	
	if($milestoneprogress == "" || $milestoneprogress->_id < 1){
		$milestoneprogress = new MilestoneProgress(-1,
						$userid,
						$milestoneid,
						0,
						0,
						0,
						0,
						0,
						0,
						0,
						0,
						0,
						0,
						'',
						'',
						'',
						'',
						'',
						'');	
	}
	
	return $milestoneprogress;
}

function BindMilestonesVariables($script, $userid, $gameid){
	$script = str_replace("[UserID]", "'".$userid."'", $script);
	$script = str_replace("[GameID]", "'".$gameid."'", $script);
	return $script;
}

function GetMilestonesCount(){
	$mysqli = Connect();
	if ($result = $mysqli->query("select count(*) as count from `Milestones`")) {
		while($row = mysqli_fetch_array($result)){
			$count = $row['count'];
		}
	}
	Close($mysqli, $result);
	return $count;
}

function GetMilestone($id, $userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Milestones` where 	`ID` = '".$id."' ")) {
		while($row = mysqli_fetch_array($result)){
			$progress = GetMilestoneProgression($row['ID'], $userid, $mysqli);
			$badge = new Milestone($row['ID'],
						$row['Name'],
						$row['Description'],
						$row['Type'],
						$row['Image'],
						$row['Rarity'],
						$row['Difficulty'],
						$row['Validation'],
						$row['Threshold'],
						$row['Enabled'],
						$progress->_completion,
						$progress->_start,
						$progress->_finished,
						$row['Parent'],
						$row['Category'],
						'',
						$row['ObjectID']
						);
		}
	}
	Close($mysqli, $result);
	return $badge;
}

function RemoveMilestone($id){
	$mysqli = Connect();
	$mysqli->query("delete from `Milestones` where 	`ID` = '".$id."' ");
	Close($mysqli, $result);
}

function SaveNewMilestone($name, $desc, $type, $image, $diff, $validation, $threshold, $category, $subcategory){
	$mysqli = Connect();
	if($subcategory == "")
		$subcategory = -1;
	$mysqli->query("insert into `Milestones` (`Name`,`Description`,`Type`,`Image`,`Difficulty`,`Validation`,`Threshold`,`Category`,`Parent`) values ('$name','$desc','$type','$image','$diff','".mysqli_real_escape_string($mysqli, $validation)."','$threshold','$category','$subcategory')");
	Close($mysqli, $result);
}

function UpdateMilestone($id, $name, $desc, $type, $image, $diff, $validation, $threshold, $enabled, $category, $subcategory){
	$mysqli = Connect();
	if($subcategory == "")
		$subcategory = -1;
	$result = $mysqli->query("update `Milestones` SET `Name`='$name',`Description`='$desc',`Type`='$type',`Image`='$image',`Difficulty`='$diff',`Validation`='".mysqli_real_escape_string($mysqli, $validation)."',`Threshold`='$threshold',`Enabled`='$enabled', `Category`='$category', `Parent`='$subcategory' where `ID` = '$id'") or die;
	Close($mysqli, $result);
}

?>
