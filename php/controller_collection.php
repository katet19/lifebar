<?php
require_once "includes.php";

function GetPersonalCollections($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Collections` where `OwnerID` = '".$userid."' and `CreatedBy` = '".$userid."' order by `ID` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$collection = new Collection($row['ID'], 
						$row['OwnerID'], 
						$row['Name'], 
						$row['Description'],
						0, 
						$row['Created'], 
						$row['LastUpdated'], 
						GetCollectionGames($row['ID'], $mysqli), 
						$row['Visibility'],
						$row['Cover'],
						$row['CoverSmall']);
			$collections[] = $collection;
		}
	}
	Close($mysqli, $result);
	return $collections;
}

function GetPersonalAutoCollections($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Collections` where `OwnerID` = '".$userid."' and `CreatedBy` = '-1' order by `ID` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$collection = new Collection($row['ID'], 
						$row['OwnerID'], 
						$row['Name'],
						$row['Description'],
						0, 
						$row['Created'], 
						$row['LastUpdated'], 
						GetCollectionGames($row['ID'], $mysqli), 
						$row['Visibility'],
						$row['Cover'],
						$row['CoverSmall']);
			$collections[] = $collection;
		}
	}
	Close($mysqli, $result);
	return $collections;
}

function GetLatestCollectionForUser($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Collections` where `OwnerID` = '".$userid."' order by `LastUpdated` DESC LIMIT 0,5")) {
		while($row = mysqli_fetch_array($result)){
			$collection = new Collection($row['ID'], 
						$row['OwnerID'], 
						$row['Name'],
						$row['Description'],
						0, 
						$row['Created'], 
						$row['LastUpdated'], 
						GetCollectionGames($row['ID'], $mysqli), 
						$row['Visibility'],
						$row['Cover'],
						$row['CoverSmall']);
			$collections[] = $collection;
		}
	}
	Close($mysqli, $result);
	return $collections;	
}

function GetCollectionListForUser($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Collections` where `OwnerID` = '".$userid."' order by `Name`")) {
		while($row = mysqli_fetch_array($result)){
			unset($collection);
			$collection['ID'] = $row['ID'];
			$collection['Name'] = $row['Name'];
			$collections[] = $collection;
		}
	}
	Close($mysqli, $result);
	return $collections;	
}

function GetCollectionListForUserAndGame($userid, $gameid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Collections` where `OwnerID` = '".$userid."' order by `Name`")) {
		while($row = mysqli_fetch_array($result)){
			unset($collection);
			$collection['ID'] = $row['ID'];
			$collection['Name'] = $row['Name'];
			$collection['Exists'] = false;
			if ($result2 = $mysqli->query("select * from `CollectionGames` where `CollectionID` = '".$row['ID']."' and `GameID` = '".$gameid."'")) {
				while($row2 = mysqli_fetch_array($result2)){
					$collection['Exists'] = true;
				}
			}
			$collections[] = $collection;
		}
	}
	Close($mysqli, $result);
	return $collections;	
}

function GetSubscribedCollections($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select c.*, s.`SubSince` from `Collections` c, `CollectionSubs` s where s.`CollectionID` = c.`ID` and  s.`UserID` = '".$userid."' order by `ID` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$collection = new Collection($row['ID'], 
						$row['OwnerID'], 
						$row['Name'],
						$row['Description'],
						0, 
						$row['Created'], 
						$row['LastUpdated'], 
						GetCollectionGames($row['ID'], $mysqli),
						$row['Visibility'],
						$row['Cover'],
						$row['CoverSmall']);
			$collections[] = $collection;
		}
	}
	Close($mysqli, $result);
	return $collections;
}

function CreatePersonalCollection($name, $desc, $userid, $gameid){
	if($gameid > 0){
		$gameData = GetGame($gameid);
		$gameOne['GameID'] = $gameid;
		$gameOne['GBID'] = $gameData->_gbid;
		$game[] = $gameOne;
	}else{
		$game = null;
	}
	$collectionID = CreateCollection($name,$desc,$userid,$userid,'Yes',$game);
	
	$mysqli = Connect();
	$result = $mysqli->query("insert into `Events` (`UserID`,`GameID`,`Event`,`Quote`) values ('$userid','$collectionID','COLLECTIONCREATION','".$name."||".$desc."')");
	Close($mysqli, $result);
	
	echo $collectionID;
}

function CreateCollection($name,$desc,$ownerid,$createdBy,$visibility,$games){
	$mysqli = Connect();
	$exists = DoesCollectionExist($name,$ownerid);
	$collectionID = -1;
	if($exists == null){
		$result = $mysqli->query("insert into `Collections` (`Name`,`Description`,`OwnerID`,`CreatedBy`,`Visibility`) values ('".mysqli_real_escape_string($mysqli,$name)."','".mysqli_real_escape_string($mysqli,$desc)."','".$ownerid."','".$createdBy."','".$visibility."')");
		if ($result = $mysqli->query("select * from `Collections` where `Name` = '".$name."' and `OwnerID` = '".$ownerid."' order by `ID` DESC")) {
			while($row = mysqli_fetch_array($result)){
				$collectionID = $row['ID'];
			}
		}
		if(sizeof($games) > 0 && $collectionID > 0){
			foreach($games as $game){
				if($game['GameID'] > 0 && $game['GBID'] > 0)
					$result = $mysqli->query("insert into `CollectionGames` (`CollectionID`,`GameID`,`GBID`) values ('".$collectionID."','".$game['GameID']."','".$game['GBID']."')");
			}
		}
	}
	Close($mysqli, $result);
	return $collectionID;
}

function UpdateCollection($collectionid, $name, $desc){
	$mysqli = Connect();
	$updatedDate = date('Y-m-d H:i:s', strtotime("now"));
	$result = $mysqli->query("update `Collections` set `Name` = '".mysqli_real_escape_string($mysqli,$name)."', `Description` = '".mysqli_real_escape_string($mysqli,$desc)."', `LastUpdated` = '".$updatedDate."' where `ID` = '".$collectionid."' and `OwnerID` = '".$_SESSION['logged-in']->_id."'");
	Close($mysqli, $result);
}

function SetCollectionCover($collectionid, $gameid){
	$mysqli = Connect();
	$game = GetGame($gameid, $mysqli);
	$updatedDate = date('Y-m-d H:i:s', strtotime("now"));
	$result = $mysqli->query("update `Collections` set `Cover` = '".$game->_image."', `CoverSmall` = '".$game->_imagesmall."', `LastUpdated` = '".$updatedDate."' where `ID` = '".$collectionid."' and `OwnerID` = '".$_SESSION['logged-in']->_id."'");
	echo $game->_image;
	Close($mysqli, $result);
}

function RemoveCollection($collectionid){
	$mysqli = Connect();
	$mysqli->query("delete from `Collections` where `ID` = '".$collectionid."' and `OwnerID` = '".$_SESSION['logged-in']->_id."'");
	$mysqli->query("delete from `CollectionGames` where `CollectionID` = '".$collectionid."'");
	Close($mysqli, $result);
}

function GetCollectionByName($name,$ownerid){
	$mysqli = Connect();
	$collection = null;
	if ($result = $mysqli->query("select * from `Collections` where `Name` = '".$name."' and `OwnerID` = '".$ownerid."' order by `ID` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$collection = new Collection($row['ID'], 
						$row['OwnerID'], 
						$row['Name'],
						$row['Description'],
						0, 
						$row['Created'], 
						$row['LastUpdated'], 
						GetCollectionGames($row['ID'], $mysqli),
						$row['Visibility'],
						$row['Cover'],
						$row['CoverSmall']);
		}
	}
	Close($mysqli, $result);
	
	return $collection;
}

function GetCollectionByNameForUser($name,$ownerid,$userid){
	$mysqli = Connect();
	$collection = null;
	if ($result = $mysqli->query("select * from `Collections` where `Name` = '".$name."' and `OwnerID` = '".$ownerid."' order by `ID` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$collection = new Collection($row['ID'], 
						$row['OwnerID'], 
						$row['Name'],
						$row['Description'],
						0, 
						$row['Created'], 
						$row['LastUpdated'], 
						GetCollectionGamesWithXP($row['ID'], $userid, 0, 25, $mysqli),
						$row['Visibility'],
						$row['Cover'],
						$row['CoverSmall']);
		}
	}
	Close($mysqli, $result);
	
	return $collection;
}

function GetCollectionByID($collectionID, $pconn = null){
	$mysqli = Connect($pconn);
	$collection = null;
	if ($result = $mysqli->query("select * from `Collections` where `ID` = '".$collectionID."'")) {
		while($row = mysqli_fetch_array($result)){
			$collection = new Collection($row['ID'], 
						$row['OwnerID'], 
						$row['Name'],
						$row['Description'],
						0, 
						$row['Created'], 
						$row['LastUpdated'], 
						GetCollectionGames($row['ID'], $mysqli),
						$row['Visibility'],
						$row['Cover'],
						$row['CoverSmall']);
		}
	}
	
	if($pconn == null)
		Close($mysqli, $result);
	
	return $collection;
}

function GetCollectionByIDForUser($collectionID, $userid){
	$mysqli = Connect();
	$collection = null;
	if ($result = $mysqli->query("select * from `Collections` where `ID` = '".$collectionID."'")) {
		while($row = mysqli_fetch_array($result)){
			$collection = new Collection($row['ID'], 
						$row['OwnerID'], 
						$row['Name'],
						$row['Description'],
						0, 
						$row['Created'], 
						$row['LastUpdated'], 
						GetCollectionGamesWithXP($row['ID'], $userid, 0, 25, $mysqli),
						$row['Visibility'],
						$row['Cover'],
						$row['CoverSmall']);
		}
	}
	Close($mysqli, $result);
	
	return $collection;
}

function ValidateCollectionName($collectionName, $userid){
	$collection = DoesCollectionExist($collectionName, $userid);
	if($collection == null)
		echo "NEW";
	else
		echo "EXISTS";
}

function DoesCollectionExist($name,$ownerid){
	$mysqli = Connect();
	$collection = null;
	if ($result = $mysqli->query("select * from `Collections` where `Name` = '".$name."' and `OwnerID` = '".$ownerid."' order by `ID` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$collection = $row['ID'];
		}
	}
	Close($mysqli, $result);
	
	return $collection;
}

function GetCollectionGames($collectionID, $pconn = null){
	$mysqli = Connect($pconn);
	$games = null;
	if ($result = $mysqli->query("select * from `CollectionGames` where `CollectionID` = '".$collectionID."' order by `GBID` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$game = GetGame($row['GameID']);
			$games[] = $game;
		}
	}
	
	if($pconn == null)
		Close($mysqli, $result);
	return $games;
}

function IsGameBookmarkedFromCollection($gameid, $pconn = null){
	$mysqli = Connect($pconn);
	$bookmarked = "No";
	if ($result = $mysqli->query("select * from `Collections` c, `CollectionGames` g where c.`Name` = 'Bookmarked' and c.`OwnerID` = '".$_SESSION['logged-in']->_id."' and c.`ID` = g.`CollectionID` and g.`GameID` = '".$gameid."'")) {
		while($row = mysqli_fetch_array($result)){
			$bookmarked = "Yes";
		}
	}
	
	if($pconn == null)
		Close($mysqli, $result);
	return $bookmarked;
}

function GetCollectionGamesWithXP($collectionID, $userid, $offSet, $limit, $pconn = null){
	$mysqli = Connect($pconn);
	$gameXP = null;
	if ($result = $mysqli->query("select * from `CollectionGames` c, `Games` g where c.`CollectionID` = '".$collectionID."' and c.`GameID` = g.`ID` order by g.`Title` LIMIT ".$offSet.",".$limit)) {
		while($row = mysqli_fetch_array($result)){
			$xp = GetExperienceForUserCompleteOrEmptyGame($userid, $row['GameID']);
			$gameXP[] = $xp;
		}
	}
	
	if($pconn == null)
		Close($mysqli, $result);
	return $gameXP;
}

function GetCollectionGamesBySearch($collectionID, $search, $offSet, $limit, $userid, $pconn = null){
	$mysqli = Connect($pconn);
	$gameXP = null;
	if ($result = $mysqli->query("select * from `CollectionGames` c, `Games` g where c.`CollectionID` = '".$collectionID."' and c.`GameID` = g.`ID` and g.`Title` like '%".$search."%' order by g.`Title` LIMIT ".$offSet.",".$limit)) {
		while($row = mysqli_fetch_array($result)){
			$xp = GetExperienceForUserCompleteOrEmptyGame($userid, $row['GameID']);
			$gameXP[] = $xp;
		}
	}
	
	if($pconn == null)
		Close($mysqli, $result);
	return $gameXP;
}

function GetCollectionSize($collectionID){
	$mysqli = Connect();
	$size = 0;
	if ($result = $mysqli->query("select count(*) as cnt from `CollectionGames` where `CollectionID` = '".$collectionID."'")) {
		while($row = mysqli_fetch_array($result)){
			$size = $row['cnt'];
		}
	}

	
	Close($mysqli, $result);
	return number_format($size);	
}


function AddToCollection($collectionID, $gbid, $userid){
	$mysqli = Connect();
	$alreadyExists = false;
	$gameid = -1;
	if ($result = $mysqli->query("select * from `CollectionGames` where `CollectionID` = '".$collectionID."' and `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$alreadyExists = true;
		}
	}
	
	if($alreadyExists == false){
		$game = GetGameByGBIDFull($gbid, $mysqli);
		$gameid = $game->_id;
		$mysqli->query("insert into `CollectionGames` (`CollectionID`,`GameID`, `GBID`) values ('".$collectionID."', '".$game->_id."', '".$gbid."')");
		$updatedDate = date('Y-m-d H:i:s', strtotime("now"));
		$result = $mysqli->query("update `Collections` set  `LastUpdated` = '".$updatedDate."' where `ID` = '".$collectionID."' and `OwnerID` = '".$_SESSION['logged-in']->_id."'");

	}
	
	Close($mysqli, $result);
	
	return $gameid;
}

function RemoveFromCollection($collectionID, $gameID, $userid){
	$mysqli = Connect();
	$mysqli->query("delete from `CollectionGames` where `CollectionID` = '".$collectionID."' and `GameID` = '".$gameID."'");
	$updatedDate = date('Y-m-d H:i:s', strtotime("now"));
	$mysqli->query("update `Collections` set  `LastUpdated` = '".$updatedDate."' where `ID` = '".$collectionID."' and `OwnerID` = '".$_SESSION['logged-in']->_id."'");
	Close($mysqli, $result);
}

//CreateDefaultUserCollections(7588);

function CreateDefaultUserCollections($userid){
	$mysqli = Connect();
	
	if(DoesCollectionExist('Upcomming Quests',$userid) == null){
		unset($games);
		$query = "select g.`ID`, g.`GBID` from `Quests` q, `Games` g where q.`Category` = 'Games' and q.`CoreID` = g.`ID` and q.`UserID` = '".$userid."'";
		if ($result = $mysqli->query($query)) {
			while($row = mysqli_fetch_array($result)){
				unset($game);
				$game['GameID'] = $row['ID'];
				$game['GBID'] = $row['GBID'];
				$games[] = $game;
			}
		}
		CreateCollection('Upcoming Quests','Games that are similar to other games I have experienced',$userid,-1,'Yes',$games);
	}
	if(DoesCollectionExist('Bookmarked',$userid) == null){
		unset($games);
		$query = "select g.`ID`, g.`GBID` from `Experiences` e, `Games` g where e.`UserID` = '".$userid."' and e.`BucketList` = 'Yes' and g.`ID` = e.`GameID`";
		if ($result = $mysqli->query($query)) {
			while($row = mysqli_fetch_array($result)){
				unset($game);
				$game['GameID'] = $row['ID'];
				$game['GBID'] = $row['GBID'];
				$games[] = $game;
			}
		}
		CreateCollection('Bookmarked','Games I have bookmarked',$userid,-1,'Yes',$games);
	}
	Close($mysqli, $result);
	
}

?>
