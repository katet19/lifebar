<?php
require_once "includes.php";

function CheckForAsyncNotifications(){
	$lastvisit = GetLastTimeVisited($_SESSION['logged-in']->_id);
	$notifications = GetUserNotificationsByCategory($_SESSION['logged-in']->_id);
	$i = 0;
	$hasNew = false;
	while(sizeof($notifications) > $i){
		if($notifications[$i]->_date > $lastvisit){
			$hasNew = true;
			$i = sizeof($notifications) + 1;
		}
		$i++;
	}
	
	echo $hasNew;
}

function UpdateLastTimeVisited($userid){
	$mysqli = Connect();
	$timestamp = date("Y-m-d H:i:s");
	$mysqli->query("Update `Users` SET `LastCardVisit`='".$timestamp."' WHERE `ID` = '".$userid."'");
	Close($mysqli, $result);
}

function GetLastTimeVisited($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Users` where `ID` = '".$userid."' ")) {
		while($row = mysqli_fetch_array($result)){	
			$lastvisit = $row['LastCardVisit'];	
		}
	}
	Close($mysqli, $result);
	return $lastvisit;
}

function GetTotalNew($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Users` where `ID` = '".$userid."' ")) {
		while($row = mysqli_fetch_array($result)){	
			$totalnew = $row['NewCards'];	
		}
	}
	Close($mysqli, $result);
	return $totalnew;
}

function UpdateTotalNew($userid, $added){
	$mysqli = Connect();
	if($added == 0){
		$total = 0;
	}else{
		if ($result = $mysqli->query("select * from `Users` where `ID` = '".$userid."' ")) {
			while($row = mysqli_fetch_array($result)){	
				$totalnew = $row['NewCards'];	
				$total = $totalnew + $added;
			}
		}
	}
	
	$mysqli->query("Update `Users` SET `NewCards`='".$total."' WHERE `ID` = '".$userid."'");
	Close($mysqli, $result);
	return $total;
}

function GetUserNotificationsByCategory($userid){
	$quests = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Quests` where `UserID` = '".$userid."' order by `Category` ASC, `ActionTwo` ASC, `Date` ASC, `ID` ASC LIMIT 0, 150")) {
		while($row = mysqli_fetch_array($result)){
			$quests[] = new Notification($row["ID"],
				$row["UserID"],
				$row["CoreID"],
				$row["Category"],
				$row["Type"],
				$row["Title"],
				$row["Caption"],
				$row["Date"],
				$row["ValueOne"],
				$row["ActionOne"],
				$row["ValueTwo"],
				$row["ActionTwo"],
				$row["ValueThree"],
				$row["ActionThree"],
				$row["Icon"],
				$row["Color"],
				$row["Linked"]);
		}
	}
	Close($mysqli, $result);
	return $quests;
}

function GetUserNotifications($userid){
	$quests = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Quests` where `UserID` = '".$userid."' order by `Date` DESC, `ID` ASC LIMIT 0, 150")) {
		while($row = mysqli_fetch_array($result)){
			$quests[] = new Notification($row["ID"],
				$row["UserID"],
				$row["CoreID"],
				$row["Category"],
				$row["Type"],
				$row["Title"],
				$row["Caption"],
				$row["Date"],
				$row["ValueOne"],
				$row["ActionOne"],
				$row["ValueTwo"],
				$row["ActionTwo"],
				$row["ValueThree"],
				$row["ActionThree"],
				$row["Icon"],
				$row["Color"],
				$row["Linked"]);
		}
	}
	Close($mysqli, $result);
	return $quests;
}

function GetUserSuggestedGames($userid){
	$cards = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Quests` where `UserID` = '".$userid."' and `Category` = 'Games' order by rand() limit 0,5")) {
		while($row = mysqli_fetch_array($result)){
			$cards[] = new Notification($row["ID"],
				$row["UserID"],
				$row["CoreID"],
				$row["Category"],
				$row["Type"],
				$row["Title"],
				$row["Caption"],
				$row["Date"],
				$row["ValueOne"],
				$row["ActionOne"],
				$row["ValueTwo"],
				$row["ActionTwo"],
				$row["ValueThree"],
				$row["ActionThree"],
				$row["Icon"],
				$row["Color"],
				$row["Linked"]);
		}
	}
	
	if(sizeof($cards) < 1){
		$cards = GetZeitgeist();
	}
	Close($mysqli, $result);
	return $cards;
}

function GetZeitgeist(){
	$cards = array();
	$mysqli = Connect();
	$thisquarter = date('Y-m-d', strtotime("now -14 days") );
	$tomorow = date('Y-m-d', strtotime("now +1 days") );
	if ($result = $mysqli->query("select * from `Sub-Experiences` exp, `Games` gm where gm.`Released` >= '".$thisquarter."' and gm.`Released` < '".$tomorow."' and exp.`GameID` = gm.`ID` GROUP BY  `GameID` ORDER BY COUNT(  `GameID` ) DESC LIMIT 5")) {
		while($row = mysqli_fetch_array($result)){
			$cards[] = new Notification(-1,
				-1,
				$row["GameID"],
				"Games",
				"",
				$row["Title"],
				$row["Title"]." has been getting a lot of attention lately. Jump in with your own opinion!",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"");
		}
	}
	shuffle($cards);
	Close($mysqli, $result);
	return $cards;
}

function ActionOnNotification($userid, $cardid, $action){	
	$mysqli = Connect();
	if($action == "remove"){
		echo  $userid." ".$cardid." ".$action;
		$mysqli->query("Delete from `Quests` where `UserID` = '$userid' and `ID` = '$cardid'");
	}
	Close($mysqli, $result);
		
}

function CheckForNotifications($type,$user,$gameid){
	$mysqli = Connect();
	if($type == "Bucket"){
		$now = date("Y-m-d");
		if ($result = $mysqli->query("select * from `Games` game where `ID` = '".$gameid."' and game.`Released` > '".$now."'")) {
			while($row = mysqli_fetch_array($result)){
				AddComingSoon($user, $row["ID"]);
				//Toast
				echo "Release card was added to Build your Lifebar";
			}
		
		}
	}	
	Close($mysqli, $result);
}

//AddIntroNotifications(7);
//AddIntroQuests(7588);

//AddAllBookmarked(7);
//AddAllBookmarked(7588);


function AddIntroNotifications($userid){
	$mysqli = Connect();
	//Welcome Card
	$type = "info";
	$category = "General";
	$title = "Welcome to Lifebar!";
	$caption = "A place where you can document all of your gaming experiences whether you watched someone play a game, played it yourself or did both. Fill out your history of playing gaming and track your present experiences.";
	$color = "#66BB6A";
	$icon = "mdi-action-thumb-up";
	$mysqli->query("insert into `Quests` (`UserID`,`Category`,`Type`,`Title`,`Caption`,`Color`,`Icon`) values ('$userid','$category','$type','$title','$caption','$color','$icon')");
	//Building your Lifebar
	$type = "link";
	$category = "General";
	$title = "Building your lifebar one game at a time";
	$caption = "Your lifebar is a representation of all of your experiences with games. To get started, try searching for a game you have played recently. There are lots of ways to discover games, but the most direct way is to navigate to Discover or use Search.";
	$valueone="Discover Games";
	$actionone="Trigger,#NavSearch";
	$color = "#66BB6A";
	$icon = "mdi-content-add-box";
	$mysqli->query("insert into `Quests` (`UserID`,`Category`,`Type`,`Title`,`Caption`,`ValueOne`,`ActionOne`,`Color`,`Icon`) values ('$userid','$category','$type','$title','$caption','$valueone','$actionone','$color','$icon')");
	//Experiences
	//$type = "info";
	//$category = "General";
	//$title = "The 3 parts of entering your experiences";
	//$caption = "<ol><li>- A short tweet sized summary of your feelings about your time with the game</li><li>- What Tier the game falls into (Tier 1 being the highest and Tier 5 the lowest)</li><li>- The type of experience, whether it was something you played or watched and its related details</li></ol>";
	//$color = "#66BB6A";
	//$icon = "mdi-image-edit";
	//$mysqli->query("insert into `Quests` (`UserID`,`Category`,`Type`,`Title`,`Caption`,`Color`,`Icon`) values ('$userid','$category','$type','$title','$caption','$color','$icon')");
	//Tier
	//$type = "info";
	//$category = "General";
	//$title = "The Tier System";
	//$caption = "Instead of assigning a score to a game, you do the inverse, you assign a game to a tier. Whenever you place a game into a tier, you should ask yourself, do you agree with the company it finds itself in? While it might be a small distinction, its important!";
	//$color = "#66BB6A";
	//$icon = "mdi-image-filter-1";
	//$mysqli->query("insert into `Quests` (`UserID`,`Category`,`Type`,`Title`,`Caption`,`Color`,`Icon`) values ('$userid','$category','$type','$title','$caption','$color','$icon')");
	//Bookmarking
	//$type = "info";
	//$category = "Releases";
	//$title = "Bookmarking games";
	//$caption = "Every game can be bookmarked to signify that you have a vested interest in that game. When looking at a game, go to the action button and click on the bookmark. Now relevant cards, like a release countdown if it has not been released yet, will appear over time for the game you just bookmarked.";
	//$color = "#66BB6A";
	//$icon = "mdi-action-bookmark";
	//$mysqli->query("insert into `Quests` (`UserID`,`Category`,`Type`,`Title`,`Caption`,`Color`,`Icon`) values ('$userid','$category','$type','$title','$caption','$color','$icon')");
	//Following Critics
	//$type = "link";
	//$category = "Personalities";
	//$title = "Follow popular personalites to keep up with the word on the street";
	//$caption = "After reviews are published online, we collect them and add them to special curated profiles for the critic writing the review. Search for personalities and follow them to add have their most recent reviews and thoughts show up in your Activity feed.";
	//$valueone="Find Critics";
	//$actionone="Method,ShowJournalistConnect()";
	//$color = "#66BB6A";
	//$icon = "mdi-maps-person_pin";
	//$mysqli->query("insert into `Quests` (`UserID`,`Category`,`Type`,`Title`,`Caption`,`ValueOne`,`ActionOne`,`Color`,`Icon`) values ('$userid','$category','$type','$title','$caption','$valueone','$actionone','$color','$icon')");
	//Archive of reviews
	$type = "info";
	$category = "General";
	$title = "An archive of historical reviews";
	$caption = "While new reviews are always being added, there is also a growing backlog of critic reviews. There are thousands of reviews going back to the 90s already archived with additional ones being added each day. You can find them by going to a game and viewing the Critics tab or finding the critic who originally reviewed it and browsing their profile.";
	$color = "#66BB6A";
	$icon = "mdi-action-description";
	$mysqli->query("insert into `Quests` (`UserID`,`Category`,`Type`,`Title`,`Caption`,`Color`,`Icon`) values ('$userid','$category','$type','$title','$caption','$color','$icon')");
	//Similar Games
	//$type = "info";
	//$category = "General";
	//$title = "Suggested games";
	//$caption = "After entering an experience for a game, we check to see if there are other similar games that you might be interested in. If we find any games that are similar, we add a card in the Suggestions category. The next time you are not sure what game to add next, you can stop here and see if we found something that might tickle your fancy.";
	//$color = "#66BB6A";
	//$icon = "mdi-action-class";
	//$mysqli->query("insert into `Quests` (`UserID`,`Category`,`Type`,`Title`,`Caption`,`Color`,`Icon`) values ('$userid','$category','$type','$title','$caption','$color','$icon')");
	//Following Users
	$type = "info";
	$category = "General";
	$title = "Follow your friends!";
	$caption = "Find your friends and follow them. By following someone, their most recent activity is added to your feed and you can see their experiences when viewing specific games by going to the Users tab. We have taken the liberty to have you follow the top 10 current personalities, which you will see below. Feel free to un-follow them if you like, but who knows, you may find someone with tastes similar to yours.";
	$valueone="Find Users";
	$actionone="Trigger,#NavConnections";
	$color = "#66BB6A";
	$icon = "mdi-social-group-add";
	$mysqli->query("insert into `Quests` (`UserID`,`Category`,`Type`,`Title`,`Caption`,`ValueOne`,`ActionOne`,`Color`,`Icon`) values ('$userid','$category','$type','$title','$caption','$valueone','$actionone','$color','$icon')");
	//Bugs
	$type = "info";
	$category = "General";
	$title = "Early access";
	$caption = "Lifebar is a work in progress, we are proud of what we have built so far, but we know there is so much more that could be done. If you find any bugs or have feedback please click on the bug at the top right and please let us know!";
	$color = "#66BB6A";
	$icon = "mdi-action-bug-report";
	$mysqli->query("insert into `Quests` (`UserID`,`Category`,`Type`,`Title`,`Caption`,`Color`,`Icon`) values ('$userid','$category','$type','$title','$caption','$color','$icon')");
	Close($mysqli, $result);
	UpdateTotalNew($userid, 10);
}

function AddAllBookmarked($userid){
	$mysqli = Connect();
	$now = date("Y-m-d");
	if ($result = $mysqli->query("select exp.*, game.* from `Experiences` exp, `Games` game where exp.`UserID` = '".$userid."' and exp.`BucketList` = 'Yes' and `GameID` = game.`ID` and game.`Released` > '".$now."'")) {
		while($row = mysqli_fetch_array($result)){
			AddComingSoon($userid, $row["GameID"]);
		}
	}
	Close($mysqli, $result);
}

function AddComingSoon($userid, $gameid){
	$game = GetGame($gameid);
	$gameyear = explode("-",$game->_released);
	if($game->_title != "" && $gameyear[0] > "0000"){
		$mysqli = Connect();
		$type = "linkwithbg";
		$category = "Releases";
		$coreid = $game->_id;
		$title = $game->_title." is releasing soon";
		$platforms = explode("\n", $game->_platforms);
		foreach($platforms as $platform){ if($platform != ""){ $allplatforms[] = $platform; } }
		$caption = $game->_title." is coming out ".ConvertDateToRelationalEnglish($game->_released)." on ".implode(", ",$allplatforms);	
		$valueone="View game";
		$actionone="Method,ShowGame(".$game->_gbid.")";
		$valuetwo="Release";
		$actiontwo=$game->_released;
		$color = "#000";
		$mysqli->query("insert into `Quests` (`UserID`,`CoreID`,`Category`,`Type`,`Title`,`Caption`,`ValueOne`,`ActionOne`,`ValueTwo`,`ActionTwo`,`Color`,`Icon`) values ('$userid','$coreid','$category','$type','".mysqli_real_escape_string($mysqli,$title)."','".mysqli_real_escape_string($mysqli,$caption)."','$valueone','$actionone','$valuetwo','$actiontwo','$color','".$game->_imagesmall."')") or die;
		Close($mysqli, $result);
		UpdateTotalNew($userid, 1);
	}
}

function AddNewFollower($fan, $celeb){
	$mysqli = Connect();
	$cardexists = false;	
	if ($result = $mysqli->query("select * from `Quests` where `UserID` = '".$celeb."' and `CoreID` = '".$fan."' and `Category` = 'Users'")) {
		while($row = mysqli_fetch_array($result)){
			$cardexists = true;		
		}
	}
	if(!$cardexists){
		$type = "link";
		$category = "Users";
		$coreid = $fan;
		$fanuser = GetUser($fan);
		$title = DisplayNameReturn($fanuser)." is now following you";
		$caption = "Now that ".DisplayNameReturn($fanuser)." is following you they will see your latest activity, including games you bookmark, your latest experiences and who you follow. Follow them back to see their activity too!";	
		$valueone= "View ".DisplayNameReturn($fanuser)."\'s Profile";
		$actionone="Method,ShowUser(".$fan.")";
		$color = "#68204E";
		$mysqli->query("insert into `Quests` (`UserID`,`CoreID`,`Category`,`Type`,`Title`,`Caption`,`ValueOne`,`ActionOne`,`Color`, `Icon`) values ('$celeb','$coreid','$category','$type','".mysqli_real_escape_string($mysqli,$title)."','".mysqli_real_escape_string($mysqli,$caption)."','$valueone','$actionone','$color','$icon')") or die;
		UpdateTotalNew($celeb, 1);
	}
	Close($mysqli, $result);
}

function AddAutoNotificationCard($userid, $criticid){
	$mysqli = Connect();
	$cardexists = false;	
	if ($result = $mysqli->query("select * from `Quests` where `UserID` = '".$userid."' and `CoreID` = '".$criticid."' and `Category` = 'Critics'")) {
		while($row = mysqli_fetch_array($result)){
			$cardexists = true;		
		}
	}
	if(!$cardexists){
		$type = "criticlink";
		$category = "Users";
		$coreid = $criticid;
		$critic = GetUser($criticid);
		$title = "You are now following ".$critic->_first." ".$critic->_last;
		$caption = $critic->_first." was added to your connections to help flesh out your activity feed. Follow other users and personalities to make your feed more relevant to you.";	
		$valueone= "View ".$critic->_first."\'s Profile";
		$actionone="Method,ShowUser(".$criticid.")";
		$valuetwo= "Unfollow";
		$actiontwo="Method,RemoveConnectionCard(".$criticid.")";
		$color = "#68204E";
		$icon = $critic->_avatar;
		$mysqli->query("insert into `Quests` (`UserID`,`CoreID`,`Category`,`Type`,`Title`,`Caption`,`ValueOne`,`ActionOne`,`ValueTwo`,`ActionTwo`,`Color`, `Icon`) values ('$userid','$coreid','$category','$type','".mysqli_real_escape_string($mysqli,$title)."','".mysqli_real_escape_string($mysqli,$caption)."','$valueone','$actionone','$valuetwo','$actiontwo','$color','$icon')") or die;
		UpdateTotalNew($userid, 1);
	}
	Close($mysqli, $result);
}

function AddWatchLaterCard($userid, $video, $game){
	$mysqli = Connect();
	$cardexists = false;
	if ($result = $mysqli->query("select * from `Quests` where `UserID` = '".$userid."' and `CoreID` = '".$video->_id."' and `Category` = 'Video'")) {
		while($row = mysqli_fetch_array($result)){
			$cardexists = true;		
		}
	}
	if(!$cardexists){
		$type = "watchlink";
		$category = "Video";
		$coreid = $video->_id;
		$title = "Watch video of ".$game->_title;
		$caption = "Check out ".$video->_desc." from ".$video->_source;	
		$valueone= "Watch on ".$video->_source;
		$actionone="Method,ViewVideo(\'".$video->_url."\')";
		$valuetwo="Add your experience";
		$actiontwo="Method,ShowGame(".$game->_gbid.")";
		$color = "#051249";
		$icon = $game->_imagesmall;
		$mysqli->query("insert into `Quests` (`UserID`,`CoreID`,`Category`,`Type`,`Title`,`Caption`,`ValueOne`,`ActionOne`,`ValueTwo`,`ActionTwo`,`Color`, `Icon`) values ('$userid','$coreid','$category','$type','".mysqli_real_escape_string($mysqli,$title)."','".mysqli_real_escape_string($mysqli,$caption)."','$valueone','$actionone','$valuetwo','$actiontwo','$color','$icon')") or die;
		echo $video->_desc." video card was added to Build your Lifebar";
		UpdateTotalNew($userid, 1);
	}
	Close($mysqli, $result);
}


//AddCriticCardsToBookmarkedUsers(821);
function AddCriticCardsToBookmarkedUsers($gameid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select exp.* from `Experiences` exp where exp.`UserID` not in (select `UserID` from `Quests` where `Category`='Critics' and `CoreID` = '".$gameid."') and exp.`BucketList` = 'Yes' and exp.`GameID` = '".$gameid."' ")) {
		while($row = mysqli_fetch_array($result)){
			$userid = $row['UserID'];
			$type = "criticreviews";
			$category = "Critics";
			$coreid = $gameid;
			$game = GetGame($gameid, $mysqli);
			$title = "Critics have published new reviews for ".$game->_title;
			$caption = $game->_title." was recently released and the critics are weighing in. Check out what they have to say!";	
			$valueone= "View Critic Experiences";
			$actionone="Method,ShowGame(".$game->_gbid."||\'Critic\')";
			$valuetwo = $totalreviews;
			$color = "#68204E";
			$icon = $game->_imagesmall;
			$mysqli->query("insert into `Quests` (`UserID`,`CoreID`,`Category`,`Type`,`Title`,`Caption`,`ValueOne`,`ActionOne`,`ValueTwo`,`Color`, `Icon`) values ('$userid','$coreid','$category','$type','".mysqli_real_escape_string($mysqli,$title)."','".mysqli_real_escape_string($mysqli,$caption)."','$valueone','$actionone','$valuetwo','$color','$icon')") or die;
		}
	}
	Close($mysqli, $result);
}


function AddSimilarGames($userid, $gameid){
	$mysqli = Connect();
	$gameinfo = GetGame($gameid, $mysqli);
	$mysqli->query("Delete from `Quests` where `Category` = 'Games' && `CoreID` = '".$gameid."' && `UserID` = '".$userid."' ");	
	$addcards =  array();
	$simgames = explode(",", $gameinfo->_similar);
	$max = rand(2,5);
	$totalsim = 0;
	foreach($simgames as $gameid){
		$found = false;
		if($gameid != "" && $totalsim <= $max){
			if ($result = $mysqli->query("select g.`ID` as `GameID` from `Experiences` exp, `Games` g where exp.`GameID` = g.`ID` and  g.`GBID` = '".$gameid."' and exp.`UserID` = '".$userid."'")){
				while($row = mysqli_fetch_array($result)){
					$found = true;
				}
			}
			
			if(!$found){
				if ($result2 = $mysqli->query("select g.`ID` as `GameID` from `Quests` q, `Games` g where q.`Category` = 'Games' and q.`CoreID` = g.`ID` and g.`GBID` = '".$gameid."' and q.`UserID` = '".$userid."'")){ 
					while($row2 = mysqli_fetch_array($result2)){
						$found = true;
					}
				}
			}
			if(!$found && $totalsim <= $max){
				$game = GetGameByGBID($gameid, $mysqli);
				$gameyear = explode("-",$game->_released);
				if($game->_title != "" && $gameyear[0] > "0000"){
					$questgames[] = $game;
					$totalsim++;
					$type = "gamewithbg";
					$category = "Games";
					$coreid = $game->_id;
					$title = "Have you experienced ".$game->_title."?";
					$caption = $game->_title." is similar to another game you have experienced, ".$gameinfo->_title.", and you have not entered any experiences for it yet.";
					$valueone="Add your experience";
					$actionone="Method,ShowGame(".$game->_gbid.")";
					$valuetwo="Release";
					$actiontwo=$game->_released;
					$color = "#000";
					$mysqli->query("insert into `Quests` (`UserID`,`CoreID`,`Category`,`Type`,`Title`,`Caption`,`ValueOne`,`ActionOne`,`ValueTwo`,`ActionTwo`,`Color`,`Icon`) values ('$userid','$coreid','$category','$type','".mysqli_real_escape_string($mysqli,$title)."','".mysqli_real_escape_string($mysqli,$caption)."','$valueone','$actionone','$valuetwo','$actiontwo','$color','".$game->_imagesmall."')") or die;
				}
			}
		}
	}
	Close($mysqli, $result);
	UpdateTotalNew($userid, $totalsim);
	
	return $questgames;
}

function AddAgreedNotification($gameid, $userid, $agreedwith, $expid){
	$mysqli = Connect();
	$found = false;
	$notificationrow = "";
	if ($result = $mysqli->query("select * from `Quests` where `CoreID` = '".$expid."' and `Category` = 'Agree' and `UserID` = '".$agreedwith."' and `ValueTwo` = '".$gameid."'")) {
		while($row = mysqli_fetch_array($result)){
			$found = true;
			$notificationrow = $row;
		}
	}
	if($found){
		$count = GetTotalAgreesForXP($expid);
		if($count > $notificationrow['ValueThree']){
			$type = "agree";
			$category = "Agree";
			$coreid = $expid;
			$game = GetGame($gameid, $mysqli);
			$user = GetUser($userid, $mysqli);
			$title = "Your XP has been given ".$count."up's!";
			if($count == 2){
				$caption = DisplayNameReturn($user)." and 1 other have liked your thoughts on ".$game->_title;	
			}else{
				$caption = DisplayNameReturn($user)." and ".($count-1)." others have liked your thoughts on ".$game->_title;	
			}
			$valueone= $userid;
			$valuetwo = $gameid;
			$valuethree = $count;
			$color = "#000";
			$icon = $game->_imagesmall;
			$mysqli->query("delete from `Quests` where `ID` = '".$notificationrow['ID']."'");
			$mysqli->query("insert into `Quests` (`UserID`,`CoreID`,`Category`,`Type`,`Title`,`Caption`,`ValueOne`,`ValueTwo`,`ValueThree`,`Color`, `Icon`) values ('$agreedwith','$coreid','$category','$type','".mysqli_real_escape_string($mysqli,$title)."','".mysqli_real_escape_string($mysqli,$caption)."','$valueone','$valuetwo','$valuethree','$color','$icon')") or die;
		}
	}else{
		$count = GetTotalAgreesForXP($expid);
		$type = "agree";
		$category = "Agree";
		$coreid = $expid;
		$game = GetGame($gameid, $mysqli);
		$user = GetUser($userid, $mysqli);
		$title = "Your XP has been given a 1up!";
		$caption = DisplayNameReturn($user)." liked your thoughts on ".$game->_title." and gave you a 1up.";	
		$valueone= $userid;
		$valuetwo = $gameid;
		$valuethree = $count;
		$color = "#000";
		$icon = $game->_imagesmall;
		$mysqli->query("insert into `Quests` (`UserID`,`CoreID`,`Category`,`Type`,`Title`,`Caption`,`ValueOne`,`ValueTwo`,`ValueThree`,`Color`, `Icon`) values ('$agreedwith','$coreid','$category','$type','".mysqli_real_escape_string($mysqli,$title)."','".mysqli_real_escape_string($mysqli,$caption)."','$valueone','$valuetwo','$valuethree','$color','$icon')") or die;
	}
	Close($mysqli, $result);
}
?>
