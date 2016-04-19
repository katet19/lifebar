<?php
require_once "includes.php";

function GetPersonalCollections($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Collections` where `OwnerID` = '".$ownerid."' and `CreatedBy` = '".$ownerid."' order by `ID` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$collection = new Collection($row['ID'], 
						$row['OwnerID'], 
						$row['Name'], 
						$numOfSubs, 
						$row['Created'], 
						$row['LastUpdated'], 
						$games, 
						$row['Visibility']);
			$collections[] = $collection;
		}
	}
	Close($mysqli, $result);
	return $collections;
}

function GetPersonalAutoCollections($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Collections` where `OwnerID` = '".$ownerid."' and `CreatedBy` = '-1' order by `ID` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$collection = new Collection($row['ID'], 
						$row['OwnerID'], 
						$row['Name'], 
						$numOfSubs, 
						$row['Created'], 
						$row['LastUpdated'], 
						$games, 
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
						$numOfSubs, 
						$row['Created'], 
						$row['LastUpdated'], 
						$games, 
						$row['Visibility']);
			$collections[] = $collection;
		}
	}
	Close($mysqli, $result);
	return $collections;
}

function CreateCollection($name,$ownerid,$createdBy,$visibility,$games){
	$mysqli = Connect();
	$result = $mysqli->query("insert into `Collections` (`Name`,`OwnerID`,`CreatedBy`,`Visibility`) values ('$name','$ownerid','$createdBy','$visibility')");
	if ($result = $mysqli->query("select * from `Collections` where `Name` = '".$name."' and `OwnerID` = '".$ownerid."' order by `ID` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$collectionID = $row['ID'];
		}
	}
	foreach($games as $game){
		$result = $mysqli->query("insert into `CollectionGames` (`CollectionID`,`GameID`,`GBID`) values ('".$collectionID."','".$game[0]."','".$game[1]."')");
	}
	Close($mysqli, $result);
}

function AddToCollection($collectionID, $game){
	
}

function RemoveFromCollection($collectionID, $gameID){
	
}


?>