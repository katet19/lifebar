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

function GetAgreesForXP($expid){
	$agrees = array();
	$mysqli = Connect();
	if($expid > 0){
		if ($result = $mysqli->query("select count(*) as count from `Liked` exp where `ExpID` =  '".$expid."'")) {
			while($row = mysqli_fetch_array($result)){
				$agrees[] = $row['count'];
			}
		}
		
		if ($result = $mysqli->query("select * from `Liked` exp where `ExpID` =  '".$expid."'")) {
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

function GetTotalAgreesForXP($expid){
	$agrees = 0;
	$mysqli = Connect();
	if ($result = $mysqli->query("select count(*) as count from `Liked` exp where `ExpID` =  '".$expid."'")) {
		while($row = mysqli_fetch_array($result)){
			$agrees = $row['count'];
		}
	}
	Close($mysqli, $result);
	return $agrees;
}

function SaveAgreed($gameid, $userid, $agreedwith, $expid){
	$mysqli = Connect();
	$found = false;
	if ($result = $mysqli->query("select * from `Liked` exp where `ExpID` =  '".$expid."' && `UserLiked` = '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			$found = true;
		}
	}
	if(!$found){
		$mysqli->query("insert into `Liked` (`GameID`,`UserQuoted`,`UserLiked`,`ExpID`) values ('$gameid','$agreedwith','$userid','$expid')");
		AddAgreedNotification($gameid, $userid, $agreedwith, $expid);
	}
	Close($mysqli, $result);
}

function RemoveAgreed($gameid, $userid, $agreedwith, $expid){
	$mysqli = Connect();
	$mysqli->query("delete from `Liked` where `GameID`= '$gameid' and `UserQuoted` = '$agreedwith' and `UserLiked` = '$userid' and `ExpID` = '$expid'");
	Close($mysqli, $result);
}
?>
