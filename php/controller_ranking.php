<?php
require_once "includes.php";

function GetMyRankedList($userid, $year, $platform, $genre){
	$mysqli = Connect();
	$ranklist = array();
	$myquery = "select g.*, `Tier`, `Rank`, (select count(*)  from `Sub-Experiences` where `UserID` = '".$userid."' and `GameID` = g.`ID` and `Type` = 'Played') as `Played`,
				(select count(*)  from `Sub-Experiences` where `UserID` = '".$userid."' and `GameID` = g.`ID` and `Type` = 'Watched') as `Watched`,
				(select count(*)  from `Sub-Experiences` where `UserID` = '".$userid."' and `GameID` = g.`ID` and `Type` = 'Played' and (`Completed` = 100 or `Completed` = 101)) as `Finished`
				from `Experiences` e, `Games` g where e.`UserID` = '".$userid."' and g.`ID` = e.`GameID` and e.`Rank` > 0";

	if($year > -1)
		$myquery = $myquery . " and g.`Year` == '".$year."' ";
	if($platform != '')
		$myquery = $myquery . " and g.`Platforms` like '%".$year."% ' ";
	if($genre != '')
		$myquery = $myquery . " and g.`Genre` like '%".$genre."% ' ";

	$myquery = $myquery . " order by `Rank`,`Title`";		 

	if ($result = $mysqli->query($myquery)) {
		while($row = mysqli_fetch_array($result)){
			if($row["Finished"] > 0)
				$row['XPType'] = "Played Finished";
			else if($row['Played'] > 0)
				$row['XPType'] = $row['XPType']." Played";

			if($row['Watched'] > 0)
				$row['XPType'] = $row['XPType']." Watched";
			
			$ranklist[] = GameRankObject($row);
		}
	}
	Close($mysqli, $result);
	return $ranklist;
}

function GetMyUnrankedList($userid, $year, $platform, $genre){
	$mysqli = Connect();
	$ranklist = array();
	$myquery = "select g.*, `Tier`, `Rank`, (select count(*)  from `Sub-Experiences` where `UserID` = '".$userid."' and `GameID` = g.`ID` and `Type` = 'Played') as `Played`,
				(select count(*)  from `Sub-Experiences` where `UserID` = '".$userid."' and `GameID` = g.`ID` and `Type` = 'Watched') as `Watched`,
				(select count(*)  from `Sub-Experiences` where `UserID` = '".$userid."' and `GameID` = g.`ID` and `Type` = 'Played' and  (`Completed` = 100 or `Completed` = 101)) as `Finished`
				from `Experiences` e, `Games` g where e.`UserID` = '".$userid."' and g.`ID` = e.`GameID` and e.`Rank` <= 0 and e.`Tier` > 0";

	if($year > -1)
		$myquery = $myquery . " and g.`Year` == '".$year."' ";
	if($platform != '')
		$myquery = $myquery . " and g.`Platforms` like '%".$year."% ' ";
	if($genre != '')
		$myquery = $myquery . " and g.`Genre` like '%".$genre."% ' ";

	$myquery = $myquery . " order by `Tier`,`Title`";		 

	if ($result = $mysqli->query($myquery)) {
		while($row = mysqli_fetch_array($result)){
			if($row["Finished"] > 0)
				$row['XPType'] = "Played Finished";
			else if($row['Played'] > 0)
				$row['XPType'] = $row['XPType']." Played";

			if($row['Watched'] > 0)
				$row['XPType'] = $row['XPType']." Watched";
			$ranklist[] = GameRankObject($row);
		}
	}
	Close($mysqli, $result);
	return $ranklist;
}

function GetRankedPosForYear($gameid, $year, $userid){
	$mysqli = Connect();
	$pos = 1;
	$finalrank = 0;
	$query = "select * from `Experiences` e, `Games` g where e.`UserID` = '".$userid."' and e.`Rank` > 0 and e.`GameID` = g.`ID` and g.`Year` = '".$year."'  ORDER BY `Rank` ASC";
	if ($result = $mysqli->query($query)){
		while($row = mysqli_fetch_array($result)){
			if($gameid == $row['GameID'])
				$finalrank = $pos;

			$pos++;
		}
	}
	Close($mysqli, $result);
	
	return $finalrank;
}

function GetPlatformsByExperience($userid){
	$mysqli = Connect();
	$platformquery = "select * from `Link_Platforms` l, `Sub-Experiences` s where s.`UserID` = '".$userid."' and s.`PlatformIDs` = l.`GBID`  GROUP BY `NAME` ORDER BY `Name`";
	if ($platformresult = $mysqli->query($platformquery)){
		while($row = mysqli_fetch_array($platformresult)){
			$platforms[] = $row['Name'];
		}
	}
	Close($mysqli, $platformresult);
	
	return $platforms;
}

function GetGenresByExperience($userid){
	$mysqli = Connect();
	$genrequery = "select l.`Name` as Name from `Link_Genre` l, `Experiences` e, `Game_Genre` gg, `Games` g where e.`UserID` = '".$userid."' and e.`GameID` = g.`ID` and g.`GBID` = gg.`GBID` and gg.`GenreID` = l.`GBID` GROUP BY l.`NAME` ORDER BY l.`Name`";
	if ($genreresult = $mysqli->query($genrequery)){
		while($row = mysqli_fetch_array($genreresult)){
			$genres[] = $row['Name'];
		}
	}
	Close($mysqli, $genreresult);
	
	return $genres;
}

function GetYearsByExperience($userid){
	$mysqli = Connect();
	$yearquery = "select `Year` from `Experiences` e, `Games` g where e.`UserID` = '".$userid."' and e.`GameID` = g.`ID` GROUP BY `Year` ORDER BY `Year` DESC";
	if ($yearresult = $mysqli->query($yearquery)){
		while($row = mysqli_fetch_array($yearresult)){
			if($row['Year'] == 0){
				$row['Year'] = "Unreleased";
			}

			$years[] = $row['Year'];
		}
	}
	Close($mysqli, $yearresult);
	
	return $years;
}

function SaveUserRankedList($userid, $rankingList){
	$mysqli = Connect();

	if($rankingList != ""){
		$rankedGames = explode(",",$rankingList);
		//Reset ranked list
		$mysqli->query("update `Experiences` set `Rank` = '0' where `UserID` = '".$userid."'");

		$count = 1;
		foreach($rankedGames as $gamemeta){
			$gamedata = explode("||",$gamemeta);
			if($gamedata[0] > 0){
				$rankupdate = "update `Experiences` set `Rank` = '".$count."' where `UserID` = '".$userid."' and `GameID` = '".$gamedata[0]."'";
				$mysqli->query($rankupdate);
				$count++;
			}
			
			if(($gamedata[2] == "NEW" || $gamedata[2] > 0) && sizeof($rankingNotable) < 6){
				$rankingNotable[] = $gamemeta;
			}
		}

		AuditRanking($mysqli, $userid, $rankingList);

		if(sizeof($rankingNotable) > 0)
			CreateRankingEvent($mysqli,$userid, $rankingNotable);
	}

	Close($mysqli, $result);
}

function AuditRanking($mysqli, $userid, $ranklist){
	$mysqli->query("insert into `Rank_History` (`UserID`,`Log`) values ('".$userid."', '".$ranklist."')");
}

function CreateRankingEvent($mysqli, $userid, $rankingNotable){
	$result = $mysqli->query("insert into `Events` (`UserID`,`GameID`,`Event`,`Quote`,`Tier`) values ('".$userid."','0','RANK','".implode(",",$rankingNotable)."','2')");
}
?>