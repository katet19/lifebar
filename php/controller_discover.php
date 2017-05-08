<?php

function BuildDiscoverFlow($userid){
	$mysqli = Connect();
	
	//Get the Daily (always the header)
	/*$daily = GetDaily($mysqli);
		unset($dAtts);
		$dAtts['DTYPE'] = 'DAILY';
		$dAtts['QUESTION'] = $daily['Header'];
		$dAtts['SUBQUESTION'] = $daily['SubHeader'];
		$dAtts['ID'] = $daily['ID'];
		$dAtts['OBJECTID'] = $daily['ObjectID'];
		$dAtts['OBJECTTYPE'] = $daily['OBJECTTYPE'];
		$dAtts['FINISHED'] = $daily['Finished'];
		$dAtts['ITEMS'] = $daily["Items"];
		$dItems[] = $dAtts;*/
	
	/*
	* Determine the order & content
	*/
	
	//Recent Releases (ALWAYS SHOWS UP)
	$recentGames = RecentlyReleasedCategory(); 
		unset($dAtts);
		$dAtts['DTYPE'] = 'GAMELIST';
		$dAtts['CATEGORY'] = "Recent Releases";
		$dAtts['CATEGORYDESC'] = "Check out the newest games coming out";
		$dAtts['GAMES'] = $recentGames;
		$dAtts['TYPE'] = "categoryResults";
		$dItems[] = $dAtts;

	//Get Recently Played
	$played = GetCollectionByName("Recently Played", $userid);
	if($played->_games > 0){
		unset($dAtts);
		$dAtts['DTYPE'] = 'GAMELIST';
		$dAtts['CATEGORY'] = "Recently Played";
		$dAtts['CATEGORYDESC'] = "Quickly update the games you have played last";
		$dAtts['GAMES'] = $played->_games;
		$dItems[] = $dAtts;
	}

	
	//Get Watched
	$suggestedWatch = GetSuggestedWatch($mysqli, $userid);
		unset($dAtts);
		$dAtts['DTYPE'] = 'WATCHLIST';
		$dAtts['CATEGORY'] = 'Playing Now';
		$dAtts['CATEGORYDESC'] = "Pull out your favorite snack and check out what members are watching!";
		$dAtts['VIDEOS'] = $suggestedWatch;
		$dItems[] = $dAtts;
	
	//Games pref list
	$golden = GetGoldenYearsNoXP($mysqli);
	$backlog = GetGamesFromBacklog($userid);
	$backlogShow = false;
	if(sizeof($backlog) > 5){
			unset($dAtts);
			$dAtts['DTYPE'] = 'GAMELIST';
			$dAtts['CATEGORY'] = "Lifebar Backlog";
			$dAtts['CATEGORYDESC'] = "Games similar to other games you have experienced";
			$dAtts['GAMES'] = $backlog;
			$dItems[] = $dAtts;	
			$backlogShow = true;
	}else if(sizeof($golden) > 0){
			unset($dAtts);
			$dAtts['DTYPE'] = 'GAMELIST';
			$dAtts['CATEGORY'] = "The Golden Years";
			$dAtts['CATEGORYDESC'] = "Games released during your more informative age. See Paul Barnett's <a href='http://www.giantbomb.com/podcasts/paul-barnett-s-golden-rule/1600-709/' target='_blank'>Golden Rule</a>";
			$dAtts['GAMES'] = $golden;
			$dItems[] = $dAtts;	
	}
		
	//Get Suggested Users that have 1ups that arent' being followed
	$suggestedMembers = GetSuggestedMembers($mysqli, $userid); 
		unset($dAtts);
		$dAtts['DTYPE'] = 'MEMBERLIST';
		$dAtts['CATEGORY'] = "Suggested Members";
		$dAtts['CATEGORYDESC'] = "Follow members who are thoughtful & appreciated by others";
		$dAtts['USERS'] = $suggestedMembers;
		$dItems[] = $dAtts;
		
	//Ask if they want to invite friends		
		unset($dAtts);
		$dAtts['DTYPE'] = 'INVITEFRIENDS';
		$dItems[] = $dAtts;
		
	//Check if we can show gaming golden years
	if($backlogShow && sizeof($golden) > 0){
		unset($dAtts);
		$dAtts['DTYPE'] = 'GAMELIST';
		$dAtts['CATEGORY'] = "The Golden Years";
		$dAtts['CATEGORYDESC'] = "Games released during your more informative age. See Paul Barnett's <a href='http://www.giantbomb.com/podcasts/paul-barnett-s-golden-rule/1600-709/' target='_blank'>Golden Rule</a>";
		$dAtts['GAMES'] = $golden;
		$dItems[] = $dAtts;	
	}

	//Get Suggested Personalities 
	$suggestedPersonalities = GetSuggestedPersonalities($mysqli, $userid); 
		unset($dAtts);
		$dAtts['DTYPE'] = 'USERLIST';
		$dAtts['CATEGORY'] = "Suggested Personalities";
		$dAtts['CATEGORYDESC'] = "Follow the gaming industries brightest critics & influencers";
		$dAtts['USERS'] = $suggestedPersonalities;
		$dItems[] = $dAtts;	
	
	//Get Suggested Collection
	$suggcoll = GetSuggestedCollection($mysqli, $userid);
	if($suggcoll != ''){
		unset($dAtts);
		$dAtts['DTYPE'] = 'COLLECTION';
		$dAtts['COLLECTION'] = $suggcoll;
		$dItems[] = $dAtts;
	}

	//Get Users that aren't mutual followers
	$notmutual = GetNotMutualFollowers($mysqli, $userid);
	if(sizeof($notmutual) > 0){
		unset($dAtts);
		$dAtts['DTYPE'] = 'USERLIST';
		$dAtts['CATEGORY'] = "Members that like to know what you are up to";
		$dAtts['CATEGORYDESC'] = "Check out members that are following you";
		$dAtts['USERS'] = $notmutual;
		$dItems[] = $dAtts;
	}
	
	//Trending games
	$trendingGames = GetTrendingGamesCategory();
		unset($dAtts);
		$dAtts['DTYPE'] = 'GAMELIST';
		$dAtts['CATEGORY'] = "Trending on Lifebar";
		$dAtts['CATEGORYDESC'] = "See what everyone else has been talking about";
		$dAtts['GAMES'] = $trendingGames;
		$dAtts['TYPE'] = "";
		$dItems[] = $dAtts;

	//Get New Members
	if($_SESSION['logged-in']->_security == "Admin"){
		$newMembers = GetNewUsersCategory(15);
			unset($dAtts);
			$dAtts['DTYPE'] = 'USERLIST';
			$dAtts['CATEGORY'] = "New Members";
			$dAtts['CATEGORYDESC'] = "Members that have joined recently";
			$dAtts['USERS'] = $newMembers;
			$dItems[] = $dAtts;
	}
	
	Close($mysqli, $result);
	
	return $dItems;
}

function GetSuggestedCollection($mysqli, $userid){
	$query = "SELECT c.`ID` as `ID` FROM  `Collections` c, `CollectionSubs` s where c.`Visibility` = 'Yes' and c.`OwnerID` != '".$userid."' and `CreatedBy` > 0 and c.`ID` = s.`CollectionID` and c.`ID` not in (select `CollectionID` from `CollectionSubs` where `UserID` = '".$userid."') group by c.`ID` order by rand() limit 0, 1";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$collection = GetCollectionByID($row['ID'], $mysqli);
		}
	}
	return $collection;
}

function GetNotMutualFollowers($mysqli, $userid){
	$query = "SELECT * FROM  `Connections` c where c.`Celebrity` = '".$userid."' and c.`Fan` not in (select `Celebrity` from `Connections` cc where cc.`Fan` = '".$userid."' ) and c.`Fan` not in (select `UserToIgnore` from `IgnoreUser` where `UserID` = '".$userid."') limit 0,6";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$users[] = GetUser($row["Fan"], $mysqli);
		}
	}
	return $users;
}

function GetGamesFromBacklog($userid){
	$collection = GetCollectionByName('Lifebar Backlog',$userid);
	$games = $collection->_games;
	if(sizeof($games) > 0){
		$count=0;
		shuffle($games);
		while($count < sizeof($games) && $count < 8){
			$list[] = $games[$count];
			$count++;
		}
	}
	return $list;
}

function GetAGamingPreferenceList($mysqli, $userid, $prefs){
	if(sizeof($prefs) > 0){
		$pointer = array_rand($prefs);

		$first = $prefs[$pointer];
		if($first['Type'] == 'Franchises'){
			$games = GetKnowledgeGamesForDiscover($first['ObjectID'], $userid);
			if(sizeof($games) > 0)
				$gprefs['Title'] = 'Games from the '.$games[0]->_first." franchise";
		}else if($first['Type'] == 'Platform'){
			$games = GetPlatformGamesForDiscover($first['ObjectID'], $userid);
			if(sizeof($games) > 0)
				$gprefs['Title'] = 'Games released on the '.$games[0]->_first;
		}else if($first['Type'] == 'Developers'){
			$games = GetDeveloperGamesForDiscover($first['ObjectID'], $userid);
			if(sizeof($games) > 0)
				$gprefs['Title'] = 'Games developed by '.$games[0]->_first;
		}
		
		shuffle($games);
		$count = 0;
		while($count < sizeof($games) && $count < 8){
			$gprefs['Games'][] = $games[$count]->_game;
			$count++;
		}
	}
	
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
	$query = "SELECT * FROM  `Forms` WHERE `FormType` = 'Daily' and `Daily` =  '".$date."'";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$daily = $row;
		}
	}
	
	if(sizeof($daily) == 0 ){
		$query = "SELECT * FROM  `Forms` where `FormType` = 'Daily' order by Rand() limit 0,1";
		if ($result = $mysqli->query($query)) {
			while($row = mysqli_fetch_array($result)){
				$daily = $row;
			}
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
	$query = "select *, Count(`UserID`) as TotalRows from `Events` event, `Users` users WHERE `Date` > '".$date."' and users.`ID` not in (select `Celebrity` from `Connections` conn where conn.`Fan` = '".$userid."' ) and  users.`Access` != 'User' and users.`Access` != 'Admin' and users.`ID` = event.`UserID` and users.`ID` not in (select `UserToIgnore` from `IgnoreUser` where `UserID` = '".$userid."') GROUP BY `UserID` ORDER BY COUNT(  `UserID` ) DESC LIMIT 8";
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
	$query = "select *, Count(`EventID`) as TotalRows from `Liked` liked, `Users` u WHERE `Access` != 'Journalist' and `Access` != 'Authenticated' and u.`ID` = liked.`UserQuoted` and `EventID` > 0 and u.`ID` != '".$userid."' and u.`ID` not in (select `Celebrity` from `Connections` conn where conn.`Fan` = '".$userid."' ) and u.`ID` not in (select `UserToIgnore` from `IgnoreUser` where `UserID` = '".$userid."') GROUP BY `EventID` ORDER BY COUNT(  `EventID` ) DESC LIMIT 0,30";	

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
	$query = "select * from `Events` event WHERE event.`URL` != '' and event.`URL` not in (select `URL` from `Events` where `UserID` = '".$userid."') GROUP BY `GameID` ORDER BY `ID` DESC LIMIT 0,12";
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
