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

	//Get a This or That
	
	//Get Collections that have games they liked
	
	
	//Determine the order & content
	
	//Recent Releases (ALWAYS SHOWS UP)
	$recentGames = RecentlyReleasedCategory(); 
		$dAtts['DTYPE'] = 'GAMELIST';
		$dAtts['CATEGORY'] = "Recent Releases";
		$dAtts['GAMES'] = $recentGames;
		$dAtts['TYPE'] = "categoryResults";
		$dAtts['COLOR'] = "#009688";
		$dItems[] = $dAtts;
		
	//Get Users that aren't mutual followers (ALWAYS SHOWS UP)
	$activePersonalities = GetActivePersonalitiesCategory(); 
		$dAtts['DTYPE'] = 'USERLIST';
		$dAtts['CATEGORY'] = "Active Personalities";
		$dAtts['USERS'] = $activePersonalities;
		$dAtts['TYPE'] = "categoryResults";
		$dAtts['COLOR'] = "rgb(255, 126, 0)";
		$dItems[] = $dAtts;
	
	
	Close($mysqli, $result);
	
	return $dItems;
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
	if ($result = $mysqli->query("SELECT * FROM  `Daily` WHERE  `Scheduled` =  '".$date."'")) {
		while($row = mysqli_fetch_array($result)){
			$daily = $row;
		}
	}
	return $daily;
}

?>