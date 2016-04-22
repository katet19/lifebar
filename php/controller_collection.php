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
						$numOfSubs, 
						$row['Created'], 
						$row['LastUpdated'], 
						GetCollectionGames($row['ID'], $mysqli), 
						$row['Visibility']);
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
						$numOfSubs, 
						$row['Created'], 
						$row['LastUpdated'], 
						GetCollectionGames($row['ID'], $mysqli), 
						$row['Visibility']);
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
						$numOfSubs, 
						$row['Created'], 
						$row['LastUpdated'], 
						GetCollectionGames($row['ID'], $mysqli),
						$row['Visibility']);
			$collections[] = $collection;
		}
	}
	Close($mysqli, $result);
	return $collections;
}

function CreateCollection($name,$desc,$ownerid,$createdBy,$visibility,$games){
	$mysqli = Connect();
	$exists = DoesCollectionExist($name,$ownerid);
	if($exists == null){
		$result = $mysqli->query("insert into `Collections` (`Name`,`Description`,`OwnerID`,`CreatedBy`,`Visibility`) values ('$name','$desc','$ownerid','$createdBy','$visibility')");
		if ($result = $mysqli->query("select * from `Collections` where `Name` = '".$name."' and `OwnerID` = '".$ownerid."' order by `ID` DESC")) {
			while($row = mysqli_fetch_array($result)){
				$collectionID = $row['ID'];
			}
		}
		foreach($games as $game){
			if($game['GameID'] > 0 && $game['GBID'] > 0)
				$result = $mysqli->query("insert into `CollectionGames` (`CollectionID`,`GameID`,`GBID`) values ('".$collectionID."','".$game['GameID']."','".$game['GBID']."')");
		}
	}
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
						$numOfSubs, 
						$row['Created'], 
						$row['LastUpdated'], 
						GetCollectionGames($row['ID'], $mysqli),
						$row['Visibility']);
		}
	}
	Close($mysqli, $result);
	
	return $collection;
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


function AddToCollection($collectionID, $game){
	
}

function RemoveFromCollection($collectionID, $gameID){
	
}


?>
