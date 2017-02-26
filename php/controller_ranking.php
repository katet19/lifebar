<?php
require_once "includes.php";

function GetMyRankedList($userid, $year, $platform, $genre){
	$mysqli = Connect();
	$ranklist = array();
	$myquery = "select g.*, `Tier`, `Rank`  from `Experiences` e, `Games` g where e.`UserID` = '".$userid."' and g.`ID` = e.`GameID` and e.`Rank` > 0";
	
	if($year > -1)
		$myquery = $myquery . " and g.`Year` == '".$year."' ";
	if($platform != '')
		$myquery = $myquery . " and g.`Platforms` like '%".$year."% ' ";
	if($genre != '')
		$myquery = $myquery . " and g.`Genre` like '%".$genre."% ' ";

	$myquery = $myquery . " order by `Rank`,`Title`";		 

	if ($result = $mysqli->query($myquery)) {
		while($row = mysqli_fetch_array($result)){
			$ranklist[] = GameRankObject($row);
		}
	}
	Close($mysqli, $result);
	return $ranklist;
}

function GetMyUnrankedList($userid, $year, $platform, $genre){
	$mysqli = Connect();
	$ranklist = array();
	$myquery = "select g.*, `Tier`, `Rank` from `Experiences` e, `Games` g where e.`UserID` = '".$userid."' and g.`ID` = e.`GameID` and e.`Rank` <= 0 and e.`Tier` > 0";
	
	if($year > -1)
		$myquery = $myquery . " and g.`Year` == '".$year."' ";
	if($platform != '')
		$myquery = $myquery . " and g.`Platforms` like '%".$year."% ' ";
	if($genre != '')
		$myquery = $myquery . " and g.`Genre` like '%".$genre."% ' ";

	$myquery = $myquery . " order by `Tier`,`Title`";		 

	if ($result = $mysqli->query($myquery)) {
		while($row = mysqli_fetch_array($result)){
			$ranklist[] = GameRankObject($row);
		}
	}
	Close($mysqli, $result);
	return $ranklist;
}
?>