<?php

function BuildDiscoverFlow($userid){
	$mysqli = Connect();
	//Get Preferences
	$prefs = GetUserPrefs($userid, $mysqli);

	//Get GNow Cards
	

	//Get Lifebar Backlog
	

	//Get Active personalities they aren't following
	

	//Get the Daily
	$daily = GetDaily($mysqli);
		unset($dAtts);
		$dAtts['DTYPE'] = 'DAILY';
		$dAtts['QUESTION'] = $daily['Header'];
		$dAtts['SUBQUESTION'] = $daily['SubHeader'];
		$dAtts['ID'] = $daily['ID'];
		$dAtts['OBJECTID'] = $daily['ObjectID'];
		$dAtts['OBJECTTYPE'] = $daily['OBJECTTYPE'];
		$dAtts['ITEMS'] = $daily["Items"];
		$dItems[] = $dAtts;
	//Get a This or That
	
	//Get Collections that have games they liked
	
	//Determine the order & content
	
	//Recent Releases (ALWAYS SHOWS UP)
	$recentGames = RecentlyReleasedCategory(); 
		unset($dAtts);
		$dAtts['DTYPE'] = 'GAMELIST';
		$dAtts['CATEGORY'] = "Recent Releases";
		$dAtts['CATEGORYDESC'] = "Check out the newest games coming out";
		$dAtts['GAMES'] = $recentGames;
		$dAtts['TYPE'] = "categoryResults";
		$dAtts['COLOR'] = "#009688";
		$dItems[] = $dAtts;
		
	//Get Users that aren't mutual followers (ALWAYS SHOWS UP)
	
	//Get Suggester Personalities 
	$suggestedPersonalities = GetSuggestedPersonalities($mysqli, $userid); 
		unset($dAtts);
		$dAtts['DTYPE'] = 'USERLIST';
		$dAtts['CATEGORY'] = "Suggested Personalities";
		$dAtts['CATEGORYDESC'] = "Follow the gaming industries brightest critics & influencers";
		$dAtts['USERS'] = $suggestedPersonalities;
		$dAtts['TYPE'] = "";
		$dAtts['COLOR'] = "rgb(255, 126, 0)";
		$dItems[] = $dAtts;
		
	//Get Watched
	$suggestedWatch = GetSuggestedWatch($mysqli, $userid);
		unset($dAtts);
		$dAtts['DTYPE'] = 'WATCHLIST';
		$dAtts['CATEGORY'] = 'Playing Now';
		$dAtts['CATEGORYDESC'] = "Pull out your favorite snack and check out what members are watching!";
		$dAtts['VIDEOS'] = $suggestedWatch;
		$dItems[] = $dAtts;
		
	//Get Suggested Users that have 1ups that arent' being followed
	$suggestedMembers = GetSuggestedMembers($mysqli, $userid); 
		unset($dAtts);
		$dAtts['DTYPE'] = 'MEMBERLIST';
		$dAtts['CATEGORY'] = "Suggested Members";
		$dAtts['CATEGORYDESC'] = "Follow members who are thoughtful & appreciated by others";
		$dAtts['USERS'] = $suggestedMembers;
		$dAtts['TYPE'] = "";
		$dAtts['COLOR'] = "rgb(255, 126, 0)";
		$dItems[] = $dAtts;
		
	//Ask if they want to invite friends		
		unset($dAtts);
		$dAtts['DTYPE'] = 'INVITEFRIENDS';
		$dItems[] = $dAtts;
		
	//Interested games list
	$prefList = GetAGamingPreferenceList($mysqli, $userid, $prefs); 
		unset($dAtts);
		$dAtts['DTYPE'] = 'GAMELIST';
		$dAtts['CATEGORY'] = $prefList[0]['Title'];
		$dAtts['CATEGORYDESC'] = "Suggested these games based on your gaming preferences";
		$dAtts['GAMES'] = $prefList;
		$dAtts['TYPE'] = "";
		$dAtts['COLOR'] = "#009688";
		$dItems[] = $dAtts;	
	
	
	Close($mysqli, $result);
	
	return $dItems;
}

function GetAGamingPreferenceList($mysqli, $userid, $prefs){
	if(sizeof($prefs) > 1)
		$pointers = array_rand($prefs, 2);
	else if(sizeof($prefs) > 0)
		$pointers[] = array_rand($prefs);

	$first = $prefs[$pointers[0]];
	if($first['Type'] == 'Franchises')
		$gprefs[] = GetKnowledgeGames($first['ObjectID'], $userid);
	else if($first['Type'] == 'Platform')
		$gprefs[] = GetPlatformGames($first['ObjectID'], $userid);
	else if($first['Type'] == 'Developers')
		$gprefs[] = GetDeveloperGames($first['ObjectID'], $userid);
	
	return $gprefs;
}

function GetUserPrefs($userid, $mysqli){
	$prefs = array();
	if ($result = $mysqli->query("SELECT * FROM  `UserPref` WHERE  `UserID` =  '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			$prefs[] = $row;
		}
	}
	return $prefs;
}

function GetDaily($mysqli){
	$date = Date('Y-m-d');
	//$query = "SELECT * FROM  `Daily` WHERE  `Scheduled` =  '".$date."'";
	$query = "SELECT * FROM  `Forms` where `FormType` = 'Daily' order by Rand() limit 0,1";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$daily = $row;
		}
	}
	
	if(sizeof($daily) > 0 ){
		$query = "SELECT * FROM `FormItems` where `FormID` = '".$daily["ID"]."'"; 
		if ($result = $mysqli->query($query)) {
			while($row = mysqli_fetch_array($result)){
				$items[] = $row;
			}
		}
		$daily['Items'] = $items;
	}
	return $daily;
}

function GetSuggestedPersonalities($mysqli, $userid){
	$users = array();
	$count = array();
	$date = date('Y-m-d', strtotime("now -90 days") );
	$query = "select *, Count(`UserID`) as TotalRows from `Events` event, `Users` users WHERE `Date` > '".$date."' and users.`ID` not in (select `Celebrity` from `Connections` conn where conn.`Fan` = '".$userid."' ) and  users.`Access` != 'User' and users.`Access` != 'Admin' and users.`ID` = event.`UserID` GROUP BY `UserID` ORDER BY COUNT(  `UserID` ) DESC LIMIT 6";
	//echo $query;
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
				$users[] = GetUser($row["UserID"], $mysqli);
		}
	}
	return $users;
}

function GetSuggestedMembers($mysqli, $userid){
	$users = array();
	$tracker = array();
	$count = array();
	$mysqli = Connect();
	$query = "select *, Count(`EventID`) as TotalRows from `Liked` liked, `Users` u WHERE `Access` != 'Journalist' and `Access` != 'Authenticated' and u.`ID` = liked.`UserQuoted` and `EventID` > 0 and u.`ID` != '".$userid."' and u.`ID` not in (select `Celebrity` from `Connections` conn where conn.`Fan` = '".$userid."' ) GROUP BY `EventID` ORDER BY COUNT(  `EventID` ) DESC LIMIT 0,30";	

	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
				if(!in_array($row["UserQuoted"], $tracker)){
					$users[] = GetUser($row["UserQuoted"],$mysqli);
					$count[] = $row["TotalRows"];
					$tracker[] = $row["UserQuoted"];
				}
		}
	}
	$total[] = $users;
	$total[] = $count;
	Close($mysqli, $result);
	return $total;
}

function GetSuggestedWatch($mysqli, $userid){
	$videos = array();
	$query = "select * from `Events` event WHERE event.`URL` != '' and event.`URL` not in (select `URL` from `Events` where `UserID` = '".$userid."') GROUP BY `GameID` ORDER BY `ID` DESC LIMIT 0,6";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
				unset($video);
				$video[] = $row['URL'];
				$video[] = $row['GameID'];
				$videos[] = $video;
		}
	}
	return $videos;
}

?>
