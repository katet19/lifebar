<?php
require_once "includes.php";

function GetEventHistoryForGame($userid, $gameid){
	$events = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Events` where `UserID` = '".$userid."' and `GameID` = '".$gameid."' order by `Date` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$events[] = new Event($row["ID"],
				$row["UserID"],
				"",
				$row["Event"],
				$row["GameID"],
				$row["Date"],
				$row["Quote"],
				$row["Tier"],
				$row['URL']);
		}
	}
	Close($mysqli, $result);
	return $events;
}

function GetEvent($eventid){
	$mysqli = Connect();
	$result = $mysqli->query("select * from `Events` eve where `ID` = '".$eventid."'");	
	while($row = mysqli_fetch_array($result)){
				$event = new Event($row["ID"],
						$row["UserID"],
						"",
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
		}
		return $event;
}

function GetMyFeed($userid, $page, $filter){
	$myfeed = array();
	$seen = array();
	$mysqli = Connect();
	if($userid > 0){
		//$result = $mysqli->query("select eve.*, DATE(`Date`) as `ForDate` from `Events` eve where eve.`UserID` = '".$userid."' or eve.`UserID` = '0' or (".implode(" or ", $addedquery).") order by `ForDate` DESC limit ".$page.",45");
		if($filter == "All"){
			$mylist = GetConnectedToList($userid, $mysqli);
			//$collectionList = GetSubscribedCollectionList($userid, $mysqli);
			$addedquery = array();
			foreach($mylist as $user){
				$addedquery[] = "'".$user."'";
			}
			$addedquery[] = "'".$userid."'";
			//$collectionQuery = array();
			//if(sizeof($collectionList) > 0){
			//	foreach($collectionList as $collection){
			//		$collectionQuery[] = "'".$collection."'";
			//	}
			//	$result = $mysqli->query("select eve.* from `Events` eve where eve.`UserID` = '0' or (eve.`UserID` in (".implode(",", $addedquery).") and eve.`Event` != 'COLLECTIONUPDATE') or (eve.`Event` = 'COLLECTIONUPDATE' and eve.`GameID` in (".implode(",",$collectionQuery).")) order by //eve.`Date` DESC limit ".$page.",45");	
			//}else{
				$result = $mysqli->query("select eve.* from `Events` eve where eve.`UserID` = '0' or (eve.`UserID` in (".implode(",", $addedquery).") and eve.`Event` != 'COLLECTIONUPDATE') order by eve.`Date` DESC limit ".$page.",45");
			//}
		}else if($filter == "Only Users I Follow"){
			$mylist = GetConnectedToUsersList($userid, $mysqli);
			$collectionList = GetSubscribedCollectionList($userid, $mysqli);
			$addedquery = array();
			foreach($mylist as $user){
				$addedquery[] = "'".$user."'";
			}
			$collectionQuery = array();
			if(sizeof($collectionList) > 0){
				foreach($collectionList as $collection){
					$collectionQuery[] = "'".$collection."'";
				}
				$result = $mysqli->query("select eve.* from `Events` eve where (eve.`UserID` in (".implode(",", $addedquery).") and eve.`Event` != 'COLLECTIONUPDATE') or (eve.`Event` = 'COLLECTIONUPDATE' and eve.`GameID` in (".implode(",",$collectionQuery).")) order by eve.`Date` DESC limit ".$page.",45");
			}else{
				$result = $mysqli->query("select eve.* from `Events` eve where eve.`UserID` in (".implode(",", $addedquery).") and eve.`Event` != 'COLLECTIONUPDATE' order by eve.`Date` DESC limit ".$page.",45");
			}
		}else if($filter == "Only Critics I Follow"){
			$mylist = GetConnectedToCriticsList($userid, $mysqli);
			$addedquery = array();
			foreach($mylist as $user){
				$addedquery[] = "'".$user."'";
			}
			$result = $mysqli->query("select eve.* from `Events` eve where eve.`UserID` in (".implode(",", $addedquery).") order by eve.`Date` DESC limit ".$page.",45");
		}else if($filter == "My Activity"){
			$result = $mysqli->query("select eve.* from `Events` eve where eve.`UserID` = '".$userid."' order by eve.`Date` DESC limit ".$page.",45");
		}else if($filter == "Game Activity"){
			$result = $mysqli->query("select eve.* from `Events` eve where eve.`GameID` = '".$userid."' order by eve.`Date` DESC limit ".$page.",250");
		}else if($filter == "All Users"){
			$collectionList = GetSubscribedCollectionList($userid, $mysqli);
			$collectionQuery = array();
			if(sizeof($collectionList) > 0){
				foreach($collectionList as $collection){
					$collectionQuery[] = "'".$collection."'";
				}
				$result = $mysqli->query("select eve.* from `Events` eve, `Users` usr where eve.`UserID` != '".$userid."' and (eve.`Event` != 'COLLECTIONUPDATE' or (eve.`Event` = 'COLLECTIONUPDATE' and eve.`GameID` in (".implode(",",$collectionQuery)."))) and eve.`UserID` = usr.`ID` and (usr.`Access` != 'Journalist' and usr.`Access` != 'Authenticated') order by eve.`Date` DESC limit ".$page.",45");
			}else{
				$result = $mysqli->query("select eve.* from `Events` eve, `Users` usr where eve.`UserID` != '".$userid."' and eve.`Event` != 'COLLECTIONUPDATE' and eve.`UserID` = usr.`ID` and (usr.`Access` != 'Journalist' and usr.`Access` != 'Authenticated') order by eve.`Date` DESC limit ".$page.",45");
			}
		}else if($filter == "All Critics"){
			$collectionList = GetSubscribedCollectionList($userid, $mysqli);
			$collectionQuery = array();
			if(sizeof($collectionList) > 0){
				foreach($collectionList as $collection){
					$collectionQuery[] = "'".$collection."'";
				}
				$result = $mysqli->query("select eve.* from `Events` eve, `Users` usr where eve.`UserID` != '".$userid."' and (eve.`Event` != 'COLLECTIONUPDATE' or (eve.`Event` = 'COLLECTIONUPDATE' and eve.`GameID` in (".implode(",",$collectionQuery)."))) and eve.`UserID` = usr.`ID` and (usr.`Access` = 'Journalist' or usr.`Access` = 'Authenticated') order by eve.`Date` DESC limit ".$page.",45");
			}else{
				$result = $mysqli->query("select eve.* from `Events` eve, `Users` usr where eve.`UserID` != '".$userid."' and eve.`Event` != 'COLLECTIONUPDATE' and eve.`UserID` = usr.`ID` and (usr.`Access` = 'Journalist' or usr.`Access` = 'Authenticated') order by eve.`Date` DESC limit ".$page.",45");
			}
		}else if($filter == "Popular XP"){
			$result = $mysqli->query("select eve.* from `Events` eve, `Liked` lk where eve.`UserID` = lk.`UserQuoted` and eve.`GameID` = lk.`GameID` order by eve.`Date` DESC limit ".$page.",45");
		}else{
			$mylist = GetConnectedToList($userid, $mysqli);
			$collectionList = GetSubscribedCollectionList($userid, $mysqli);
			$addedquery = array();
			foreach($mylist as $user){
				$addedquery[] = "'".$user."'";
			}
			$collectionQuery = array();
			if(sizeof($collectionList) > 0){
				foreach($collectionList as $collection){
					$collectionQuery[] = "'".$collection."'";
				}
				$result = $mysqli->query("select eve.* from `Events` eve where eve.`UserID` = '0' or (eve.`UserID` in (".implode(",", $addedquery).") and eve.`Event` != 'COLLECTIONUPDATE') or (eve.`Event` = 'COLLECTIONUPDATE' and eve.`GameID` in (".implode(",",$collectionQuery).")) order by eve.`Date` DESC limit ".$page.",45");
			}else{
				$result = $mysqli->query("select eve.* from `Events` eve where eve.`UserID` = '0' or (eve.`UserID` in (".implode(",", $addedquery).") and eve.`Event` != 'COLLECTIONUPDATE') order by eve.`Date` DESC limit ".$page.",45");
			}
		}
	}else{
		if($filter == "All Users"){
			$result = $mysqli->query("select eve.* from `Events` eve, `Users` usr where eve.`UserID` != '".$userid."' and eve.`Event` != 'COLLECTIONUPDATE' and eve.`UserID` = usr.`ID` and (usr.`Access` != 'Journalist' and usr.`Access` != 'Authenticated') order by eve.`Date` DESC limit ".$page.",45");
		}else if($filter == "All Critics" || $filter == "All"){
			$result = $mysqli->query("select eve.* from `Events` eve, `Users` usr where eve.`UserID` != '".$userid."' and eve.`Event` != 'COLLECTIONUPDATE' and eve.`UserID` = usr.`ID` and (usr.`Access` = 'Journalist' or usr.`Access` = 'Authenticated') order by eve.`Date` DESC limit ".$page.",45");
		}else if($filter == "Popular XP"){
			$result = $mysqli->query("select eve.* from `Events` eve, `Liked` lk where eve.`UserID` = lk.`UserQuoted` and eve.`GameID` = lk.`GameID` order by eve.`Date` DESC limit ".$page.",45");
		}else{
			$result = $mysqli->query("select eve.* from `Events` eve, `Users` usr where eve.`UserID` != '".$userid."' and eve.`Event` != 'COLLECTIONUPDATE' and eve.`UserID` = usr.`ID` and (usr.`Access` = 'Journalist' or usr.`Access` = 'Authenticated') order by eve.`Date` DESC limit ".$page.",45");
		}
	}	
	
	if($result->num_rows > 0){
		while($row = mysqli_fetch_array($result)){
			if(!in_array($row["UserID"]."-".$row["GameID"]."-".$row['Quote'], $seen) && ($row["Event"] == "ADDED" || $row["Event"] == "UPDATE" || $row["Event"] == "FINISHED")){
				$myfeeditem = array();						
				$game = GetGame($row["GameID"], $mysqli);
				$exp = GetExperienceForUserByGame($row["UserID"], $row["GameID"], $mysqli);
				$event = new Event($row["ID"],
						$row["UserID"],
						$exp->_first." ".$exp->_last,
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
						
				$myfeeditem[] = $event;
				$myfeeditem[] = $game;
				$myfeeditem[] = $row["GameID"];
				$myfeeditem[] = $exp;
				$myfeeditem[] = 3;
				$myfeeditem[] = "XP";
				$myfeed[] = $myfeeditem;
				$seen[] = $row["UserID"]."-".$row["GameID"]."-".$row['Quote'];
			}else if($row["Event"] == "BUCKETLIST"){
				$myfeeditem = array();						
				$game = GetGame($row["GameID"], $mysqli);
				$exp = GetExperienceForUserByGame($row["UserID"], $row["GameID"], $mysqli);
				$event = new Event($row["ID"],
						$row["UserID"],
						$exp->_first." ".$exp->_last,
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
						
				$myfeeditem[] = $event;
				$myfeeditem[] = $game;
				$myfeeditem[] = $row["GameID"];
				$myfeeditem[] = $exp;
				$myfeeditem[] = 1;
				$myfeeditem[] = "BUCKETLIST";
				$myfeed[] = $myfeeditem;
			}else if($row["Event"] == "CONNECTIONS"){
				$myfeeditem = array();						
				$game = null;
				$exp = null;
				$event = new Event($row["ID"],
						$row["UserID"],
						$exp->_first." ".$exp->_last,
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
						
				$myfeeditem[] = $event;
				$myfeeditem[] = $game;
				$myfeeditem[] = $row["GameID"];
				$myfeeditem[] = $exp;
				$myfeeditem[] = 1;
				$myfeeditem[] = "CONNECTIONS";
				$myfeed[] = $myfeeditem;
				
			}else if($row["Event"] == "TIERCHANGED"){
				$myfeeditem = array();						
				$game = GetGame($row["GameID"], $mysqli);
				$exp = GetExperienceForUserByGame($row["UserID"], $row["GameID"], $mysqli);
				$event = new Event($row["ID"],
						$row["UserID"],
						$exp->_first." ".$exp->_last,
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
						
				$myfeeditem[] = $event;
				$myfeeditem[] = $game;
				$myfeeditem[] = $row["GameID"];
				$myfeeditem[] = $exp;
				$myfeeditem[] = 2;
				$myfeeditem[] = "TIERCHANGED";
				$myfeed[] = $myfeeditem;
			
			}else if($row["Event"] == "QUOTECHANGED"){
				$myfeeditem = array();						
				$game = GetGame($row["GameID"], $mysqli);
				$exp = GetExperienceForUserByGame($row["UserID"], $row["GameID"], $mysqli);
				$event = new Event($row["ID"],
						$row["UserID"],
						$exp->_first." ".$exp->_last,
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
						
				$myfeeditem[] = $event;
				$myfeeditem[] = $game;
				$myfeeditem[] = $row["GameID"];
				$myfeeditem[] = $exp;
				$myfeeditem[] = 2;
				$myfeeditem[] = "QUOTECHANGED";
				$myfeed[] = $myfeeditem;
			}else if($row["Event"] == "GAMERELEASE"){
				$myfeeditem = array();						
				$game = GetGame($row["GameID"], $mysqli);
				$exp = null;
				$event = new Event($row["ID"],
						$row["UserID"],
						$exp->_first." ".$exp->_last,
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
						
				$myfeeditem[] = $event;
				$myfeeditem[] = $game;
				$myfeeditem[] = $row["GameID"];
				$myfeeditem[] = $exp;
				$myfeeditem[] = 3;
				$myfeeditem[] = "GAMERELEASE";
				$myfeed[] = $myfeeditem;
			}else if($row["Event"] == "COLLECTIONCREATION"){
				$myfeeditem = array();						
				$collection = GetCollectionByID($row["GameID"], $mysqli);
				if($collection != null){
					$exp = null;
					$event = new Event($row["ID"],
							$row["UserID"],
							$exp->_first." ".$exp->_last,
							$row["Event"],
							$row["GameID"],
							$row["Date"],
							$row["Quote"],
							$row["Tier"],
							$row["URL"]);
							
					$myfeeditem[] = $event;
					$myfeeditem[] = $collection;
					$myfeeditem[] = $row["GameID"];
					$myfeeditem[] = $exp;
					$myfeeditem[] = 3;
					$myfeeditem[] = "COLLECTIONCREATION";
					$myfeed[] = $myfeeditem;
				}
			}else if($row["Event"] == "COLLECTIONFOLLOW"){
				$myfeeditem = array();						
				$collection = GetCollectionByID($row["GameID"], $mysqli);
				if($collection != null){
					$exp = null;
					$event = new Event($row["ID"],
							$row["UserID"],
							$exp->_first." ".$exp->_last,
							$row["Event"],
							$row["GameID"],
							$row["Date"],
							$row["Quote"],
							$row["Tier"],
							$row["URL"]);
							
					$myfeeditem[] = $event;
					$myfeeditem[] = $collection;
					$myfeeditem[] = $row["GameID"];
					$myfeeditem[] = $exp;
					$myfeeditem[] = 3;
					$myfeeditem[] = "COLLECTIONFOLLOW";
					$myfeed[] = $myfeeditem;
				}
			}else if($row["Event"] == "STEAMIMPORT"){
				$myfeeditem = array();			
				$collections = explode("||", $row["Quote"]);
				$backlog = GetCollectionByID($collections[1], $mysqli);
				$played = GetCollectionByID($collections[0], $mysqli);
				$total = $row["GameID"];
				if($collections != null){
					$exp = null;
					$event = new Event($row["ID"],
							$row["UserID"],
							$exp->_first." ".$exp->_last,
							$row["Event"],
							$row["GameID"],
							$row["Date"],
							$row["Quote"],
							$row["Tier"],
							$row["URL"]);
							
					$myfeeditem[] = $event;
					$myfeeditem[] = $backlog;
					$myfeeditem[] = $played;
					$myfeeditem[] = $total;
					$myfeeditem[] = 3;
					$myfeeditem[] = "STEAMIMPORT";
					$myfeed[] = $myfeeditem;
				}
			}else if($row["Event"] == "COLLECTIONUPDATE"){
				$myfeeditem = array();			
				$game = GetGame($row["Quote"]);
				$collection = GetCollectionByID($row["GameID"], $mysqli);
				if($collection != null){
					$exp = null;
					$event = new Event($row["ID"],
							$row["UserID"],
							$exp->_first." ".$exp->_last,
							$row["Event"],
							$row["GameID"],
							$row["Date"],
							$row["Quote"],
							$row["Tier"],
							$row["URL"]);
							
					$myfeeditem[] = $event;
					$myfeeditem[] = $game;
					$myfeeditem[] = $collection;
					$myfeeditem[] = $exp;
					$myfeeditem[] = 1;
					$myfeeditem[] = "COLLECTIONUPDATE";
					$myfeed[] = $myfeeditem;
				}
			}
		}
	}
	
	Close($mysqli, $result);
	return $myfeed;
}

function GetMyEvents($userid){
	$myfeed = array();
	$seen = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select eve.* from `Events` eve where eve.`UserID` = '".$userid."' order by eve.`Date` DESC, eve.`GameID` limit 0,20")) {
		while($row = mysqli_fetch_array($result)){
			if(!in_array($row["UserID"]."-".$row["GameID"]."-".$row['Quote'], $seen) && ($row["Event"] == "ADDED" || $row["Event"] == "UPDATE" || $row["Event"] == "FINISHED")){
				$myfeeditem = array();						
				$game = GetGame($row["GameID"], $mysqli);
				$exp = GetExperienceForUserByGame($row["UserID"], $row["GameID"], $mysqli);
				$event = new Event($row["ID"],
						$row["UserID"],
						$exp->_first." ".$exp->_last,
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
						
				$myfeeditem[] = $event;
				$myfeeditem[] = $game;
				$myfeeditem[] = $row["GameID"];
				$myfeeditem[] = $exp;
				$myfeeditem[] = 3;
				$myfeed[] = $myfeeditem;
				$seen[] = $row["UserID"]."-".$row["GameID"]."-".$row['Quote'];
			}else if($row["Event"] == "BUCKETLIST"){
				$myfeeditem = array();						
				$game = GetGame($row["GameID"], $mysqli);
				$exp = GetExperienceForUserByGame($row["UserID"], $row["GameID"], $mysqli);
				$event = new Event($row["ID"],
						$row["UserID"],
						$exp->_first." ".$exp->_last,
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
						
				$myfeeditem[] = $event;
				$myfeeditem[] = $game;
				$myfeeditem[] = $row["GameID"];
				$myfeeditem[] = $exp;
				$myfeeditem[] = 1;
				$myfeed[] = $myfeeditem;
			}else if($row["Event"] == "CONNECTIONS"){
				$myfeeditem = array();						
				$game = null;
				$exp = null;
				$event = new Event($row["ID"],
						$row["UserID"],
						$exp->_first." ".$exp->_last,
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
						
				$myfeeditem[] = $event;
				$myfeeditem[] = $game;
				$myfeeditem[] = $row["GameID"];
				$myfeeditem[] = $exp;
				$myfeeditem[] = 1;
				$myfeed[] = $myfeeditem;
				
			}else if($row["Event"] == "TIERCHANGED"){
				$myfeeditem = array();						
				$game = GetGame($row["GameID"], $mysqli);
				$exp = GetExperienceForUserByGame($row["UserID"], $row["GameID"], $mysqli);
				$event = new Event($row["ID"],
						$row["UserID"],
						$exp->_first." ".$exp->_last,
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
						
				$myfeeditem[] = $event;
				$myfeeditem[] = $game;
				$myfeeditem[] = $row["GameID"];
				$myfeeditem[] = $exp;
				$myfeeditem[] = 2;
				$myfeed[] = $myfeeditem;
			
			}else if($row["Event"] == "QUOTECHANGED"){
				$myfeeditem = array();						
				$game = GetGame($row["GameID"], $mysqli);
				$exp = GetExperienceForUserByGame($row["UserID"], $row["GameID"], $mysqli);
				$event = new Event($row["ID"],
						$row["UserID"],
						$exp->_first." ".$exp->_last,
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
						
				$myfeeditem[] = $event;
				$myfeeditem[] = $game;
				$myfeeditem[] = $row["GameID"];
				$myfeeditem[] = $exp;
				$myfeeditem[] = 2;
				$myfeed[] = $myfeeditem;
			}else if($row["Event"] == "GAMERELEASE"){
				$myfeeditem = array();						
				$game = GetGame($row["GameID"], $mysqli);
				$exp = null;
				$event = new Event($row["ID"],
						$row["UserID"],
						$exp->_first." ".$exp->_last,
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
						
				$myfeeditem[] = $event;
				$myfeeditem[] = $game;
				$myfeeditem[] = $row["GameID"];
				$myfeeditem[] = $exp;
				$myfeeditem[] = 3;
				$myfeed[] = $myfeeditem;
			}else if($row["Event"] == "COLLECTIONCREATION"){
				$myfeeditem = array();						
				$collection = GetCollectionByID($row["GameID"], $mysqli);
				$exp = null;
				$event = new Event($row["ID"],
						$row["UserID"],
						$exp->_first." ".$exp->_last,
						$row["Event"],
						$row["GameID"],
						$row["Date"],
						$row["Quote"],
						$row["Tier"],
						$row["URL"]);
						
				$myfeeditem[] = $event;
				$myfeeditem[] = $collection;
				$myfeeditem[] = $row["GameID"];
				$myfeeditem[] = $exp;
				$myfeeditem[] = 3;
				$myfeeditem[] = "COLLECTIONCREATION";
				$myfeed[] = $myfeeditem;
			}else if($row["Event"] == "STEAMIMPORT"){
				$myfeeditem = array();			
				$collections = explode("||", $row["Quote"]);
				$backlog = GetCollectionByID($collections[1], $mysqli);
				$played = GetCollectionByID($collections[0], $mysqli);
				$total = $row["GameID"];
				if($collections != null){
					$exp = null;
					$event = new Event($row["ID"],
							$row["UserID"],
							$exp->_first." ".$exp->_last,
							$row["Event"],
							$row["GameID"],
							$row["Date"],
							$row["Quote"],
							$row["Tier"],
							$row["URL"]);
							
					$myfeeditem[] = $event;
					$myfeeditem[] = $backlog;
					$myfeeditem[] = $played;
					$myfeeditem[] = $total;
					$myfeeditem[] = 3;
					$myfeeditem[] = "STEAMIMPORT";
					$myfeed[] = $myfeeditem;
				}
			}else if($row["Event"] == "COLLECTIONUPDATE"){
				$myfeeditem = array();			
				$game = GetGame($row["Quote"]);
				$collection = GetCollectionByID($row["GameID"], $mysqli);
				if($collection != null){
					$exp = null;
					$event = new Event($row["ID"],
							$row["UserID"],
							$exp->_first." ".$exp->_last,
							$row["Event"],
							$row["GameID"],
							$row["Date"],
							$row["Quote"],
							$row["Tier"],
							$row["URL"]);
							
					$myfeeditem[] = $event;
					$myfeeditem[] = $game;
					$myfeeditem[] = $collection;
					$myfeeditem[] = $exp;
					$myfeeditem[] = 1;
					$myfeeditem[] = "COLLECTIONUPDATE";
					$myfeed[] = $myfeeditem;
				}
			}
		}
		
	}
	Close($mysqli, $result);
	
	return $myfeed;
}

function CalculateLifetimeGraph($userid){
	$played = array();
	$watched = array();
	$lifeplayed = array();
	$lifewatched = array();
	$curryear = 0;
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Sub-Experiences` s where s.`UserID` = '".$userid."' order by `Date`")){
		while($row = mysqli_fetch_array($result)){
			if(substr($row['Date'],0,4) != $curryear){
				$curryear = substr($row['Date'],0,4);	
			}
			if($row['Type'] == "Played")
				$played[$curryear] = $played[$curryear] + 1;
			else
				$watched[$curryear] = $watched[$curryear] + 1;
			
		}
	}

	$user = GetUser($userid, $mysqli);
    Close($mysqli, $result);
	$birthyear = substr($user->_birthdate,0,4); 
	$year = date("Y");  
	$y = $birthyear;
	$age = 1;
	$first = false;
	if($y != 0){
		while($y <= $year){
			if((isset($played[$y]) || isset($watched[$y])) && $first == false)
				$first = true;

			if($first){
				if(isset($played[$y]))
					$lifeplayed[$y] = $played[$y];
				else
					$lifeplayed[$y] = 0;
					
				if(isset($watched[$y]))
					$lifewatched[$y] = $watched[$y];
				else
					$lifewatched[$y] = 0;
					
				$years[] = $y;
				$howold[] = $age;
			}
			$y++;
			$age++;
		}
	}
	$experiences[] = $lifeplayed;
	$experiences[] = $lifewatched;
	$experiences[] = $years; 
	$experiences[] = $howold; 
	
	return $experiences;
}

function GetLastestXPForUser($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select eve.* from `Events` eve where eve.`UserID` = '".$userid."' and (`Event` = 'ADDED' or `Event` = 'FINISHED' or `Event` = 'TIERCHANGED') order by eve.`Date` DESC, eve.`GameID` limit 0,6")) {
		while($row = mysqli_fetch_array($result)){
			$event = new Event($row["ID"],
				$row["UserID"],
				"",
				$row["Event"],
				$row["GameID"],
				$row["Date"],
				$row["Quote"],
				$row["Tier"],
				$row["URL"]);
		
			$events[] = $event;
		}
	}
	Close($mysqli, $result);
	return $events;
}

function ConvertTimeStampToRelativeTime($timestamp){
	$now = new DateTime("now");
	$old = new DateTime($timestamp);
	$interval = date_diff($now, $old);
	if($interval->y > 0){
		if($interval->y == 1)
			return $interval->y." year ago";
		else
			return $interval->y." years ago";
	}
	else if($interval->m > 0){
		if($interval->m == 1)
			return $interval->m." month ago";
		else
			return $interval->m." months ago";
	}
	else if($interval->d > 0){
		if($interval->d == 1)
			return $interval->d." day ago";
		else
			return $interval->d." days ago";
	}
	else if($interval->h > 0){
		if($interval->h == 1)
			return $interval->h." hour ago";
		else
			return $interval->h." hours ago";
	}
	else if($interval->i > 0){
		if($interval->i == 1)
			return $interval->i." minute ago";
		else
			return $interval->i." minutes ago";
	}else{
		if($interval->s == 1)
			return $interval->s." second ago";
		else
			return $interval->s." seconds ago";
	}
}

function ConvertDateToLongRelationalEnglish($date){
	$datetime = explode(" ", $date);
	$datesplit = explode('-',$datetime[0]);
	
	if($datesplit[1] == "1"){ $real = "January ".$datesplit[2]; }
	else if($datesplit[1] == "2"){ $real = "February ".$datesplit[2]; }
	else if($datesplit[1] == "3"){ $real = "March ".$datesplit[2]; }
	else if($datesplit[1] == "4"){ $real = "April ".$datesplit[2]; }
	else if($datesplit[1] == "5"){ $real = "May ".$datesplit[2]; }
	else if($datesplit[1] == "6"){ $real = "June ".$datesplit[2]; }
	else if($datesplit[1] == "7"){ $real = "July ".$datesplit[2]; }
	else if($datesplit[1] == "8"){ $real = "August ".$datesplit[2]; }
	else if($datesplit[1] == "9"){ $real = "September ".$datesplit[2]; }
	else if($datesplit[1] == "10"){ $real = "October ".$datesplit[2]; }
	else if($datesplit[1] == "11"){ $real = "November ".$datesplit[2]; }
	else if($datesplit[1] == "12"){ $real = "December ".$datesplit[2]; }
	
	return $real;
}

function ConvertDateToRelationalEnglish($date){
	$datetime = explode(" ", $date);
	$datesplit = explode('-',$datetime[0]);
	
	if($datesplit[1] == "1"){ $real = "Jan ".$datesplit[2]; }
	else if($datesplit[1] == "2"){ $real = "Feb ".$datesplit[2]; }
	else if($datesplit[1] == "3"){ $real = "Mar ".$datesplit[2]; }
	else if($datesplit[1] == "4"){ $real = "Apr ".$datesplit[2]; }
	else if($datesplit[1] == "5"){ $real = "May ".$datesplit[2]; }
	else if($datesplit[1] == "6"){ $real = "Jun ".$datesplit[2]; }
	else if($datesplit[1] == "7"){ $real = "Jul ".$datesplit[2]; }
	else if($datesplit[1] == "8"){ $real = "Aug ".$datesplit[2]; }
	else if($datesplit[1] == "9"){ $real = "Sep ".$datesplit[2]; }
	else if($datesplit[1] == "10"){ $real = "Oct ".$datesplit[2]; }
	else if($datesplit[1] == "11"){ $real = "Nov ".$datesplit[2]; }
	else if($datesplit[1] == "12"){ $real = "Dec ".$datesplit[2]; }
	
	return $real;
}

function ConvertDateToQuarter($time){
	$date = explode('-',$time); 
	$converted = array();
	if($date[0] > '0000' ){ 
		if($date[1] > '0' && $date[1] <= '3'){ 
			$converted[] = 'Q1';
			$converted[] = $date[0];
			$converted[] = 'Spring';
		}else if($date[1] > '3' && $date[1] <= '7'){
			$converted[] = 'Q2';
			$converted[] = $date[0];
			$converted[] = 'Summer';
		}else if($date[1] > '7' && $date[1] <= '10'){ 
			$converted[] = 'Q3';
			$converted[] = $date[0];
			$converted[] = 'Fall';
		}else if($date[1] > '10' && $date[1] <= '12'){ 
			$converted[] = 'Q4';
			$converted[] = $date[0];
			$converted[] = 'Winter';
		}
	}
	return $converted;
}

?>
