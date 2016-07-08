<?php
require_once "includes.php";

function GetAgreesForUser($userid, $limit){
	$agrees = array();
	$agree = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Liked` exp where `UserQuoted` =  '".$userid."' order by `GameID` LIMIT $limit,100")) {
		while($row = mysqli_fetch_array($result)){
			unset($agree);
			$agree[] = $row['GameID'];
			$agree[] = $row['UserLiked'];
			$agrees[] = $agree;
		}
	}
	Close($mysqli, $result);
	return $agrees;
}

function GetAgreesForEvent($eventid){
	$agrees = array();
	$mysqli = Connect();
	if($eventid > 0){
		if ($result = $mysqli->query("select count(*) as count from `Liked` where `EventID` = '".$eventid."'")) {
			while($row = mysqli_fetch_array($result)){
				$agrees[] = $row['count'];
			}
		}
		
		if ($result = $mysqli->query("select * from `Liked` where `EventID` = '".$eventid."'")) {
			while($row = mysqli_fetch_array($result)){
				$agrees[] = $row['UserLiked'];
			}
		}
	}
	if(sizeof($agrees) == 0)
		$agrees[0] = 0;
	Close($mysqli, $result);
	return $agrees;
}

function GetTotalAgreesForEvent($eventid){
	$agrees = 0;
	$mysqli = Connect();
	if ($result = $mysqli->query("select count(*) as count from `Liked` exp where `EventID` =  '".$eventid."'")) {
		while($row = mysqli_fetch_array($result)){
			$agrees = $row['count'];
		}
	}
	Close($mysqli, $result);
	return $agrees;
}

function SaveAgreed($gameid, $userid, $agreedwith, $eventid){
	$mysqli = Connect();
	$found = false;
	if ($result = $mysqli->query("select * from `Liked` exp where `EventID` =  '".$eventid."' && `UserLiked` = '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			$found = true;
		}
	}
	if(!$found){
		$mysqli->query("insert into `Liked` (`GameID`,`UserQuoted`,`UserLiked`,`EventID`) values ('$gameid','$agreedwith','$userid','$eventid')");
		AddAgreedNotification($gameid, $userid, $agreedwith, $eventid);
		AddAgreedEmail($gameid, $userid, $agreedwith, $eventid);
	}
	Close($mysqli, $result);
}

function RemoveAgreed($gameid, $userid, $agreedwith, $eventid){
	$mysqli = Connect();
	$mysqli->query("delete from `Liked` where `GameID`= '$gameid' and `UserQuoted` = '$agreedwith' and `UserLiked` = '$userid' and `EventID` = '$eventid'");
	Close($mysqli, $result);
}
?>
