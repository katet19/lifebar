<?php
require_once "includes.php";
//ManuallyRunCalcWeave(500);
function ManuallyRunCalcWeave($start){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Users` order by `ID` LIMIT ".$start.", 50")) {
		while($row = mysqli_fetch_array($result)){
			CalculateWeave($row["ID"]);
		}
	}
	Close($mysqli, $result);
}

//CalculateWeave(7);

function CalculateWeave($userid){
	$mysqli = Connect();
	$t1 = 0;
	$t2 = 0;
	$t3 = 0;
	$t4 = 0;
	$t5 = 0;
	$played= 0;
	$watched = 0;
	$both = 0;
	$totalAgrees = 0;
	$totalFollowers = 0;

	
	//Get Tiers
	if ($result = $mysqli->query("select count(*) from `Experiences` exp where exp.`UserID` = '".$userid."' and `Tier` = '5'")) {
		while($row = mysqli_fetch_array($result)){
			$t5 = $row['count(*)'];
		}
	}
	
	if ($result = $mysqli->query("select count(*) from `Experiences` exp where exp.`UserID` = '".$userid."' and `Tier` = '4'")) {
		while($row = mysqli_fetch_array($result)){
			$t4 = $row['count(*)'];
		}
	}
	
	if ($result = $mysqli->query("select count(*) from `Experiences` exp where exp.`UserID` = '".$userid."' and `Tier` = '3'")) {
		while($row = mysqli_fetch_array($result)){
			$t3 = $row['count(*)'];
		}
	}
	
	if ($result = $mysqli->query("select count(*) from `Experiences` exp where exp.`UserID` = '".$userid."' and `Tier` = '2'")) {
		while($row = mysqli_fetch_array($result)){
			$t2 = $row['count(*)'];
		}
	}
	
	if ($result = $mysqli->query("select count(*) from `Experiences` exp where exp.`UserID` = '".$userid."' and `Tier` = '1'")) {
		while($row = mysqli_fetch_array($result)){
			$t1 = $row['count(*)'];
		}
	}
	
	//Get Total XP
	$total = $t1 + $t2 + $t3 + $t4 + $t5;
	
	//Get Played
	if ($result = $mysqli->query("select count(*) from `Sub-Experiences` exp where exp.`UserID` = '".$userid."' and exp.`Type` = 'Played' and exp.`Archived` = 'No'")) {
		while($row = mysqli_fetch_array($result)){
			$played = $row['count(*)'];
		}
	}
	
	//Get Watched
	if ($result = $mysqli->query("select `GameID` from `Sub-Experiences` exp where exp.`UserID` = '".$userid."' and exp.`Type` = 'Watched' and exp.`Archived` = 'No' Group By `GameID`")) {
		$watched = $result->num_rows;
	}
	
	//Get Both
	if ($result = $mysqli->query("select count(*) from `Sub-Experiences` exp where exp.`UserID` = '".$userid."' and exp.`Type` = 'Played' and exp.`Archived` = 'No' and `GameID` in (select s.`GameID` from `Sub-Experiences` s where s.`UserID` = '".$userid."' and s.`Type` = 'Watched' group by `GameID`)")) {
		while($row = mysqli_fetch_array($result)){
			$both = $row['count(*)'];
		}
	}
	
	//Get Total Agrees
	if ($result = $mysqli->query("select count(*) as count from `Liked` exp where `UserQuoted` = '".$userid."' ")) {
		while($row = mysqli_fetch_array($result)){
			$totalAgrees = $row['count'];
		}
	}
	
	//Get Total Followers
	if ($result = $mysqli->query("select count(*) from `Connections` con where con.`Celebrity` = '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			$totalFollowers = $row['count(*)'];
		}
	}
	
	//Get Most Recent XP
	if ($result = $mysqli->query("select sxp.`GameID`, g.`Title` from `Sub-Experiences` sxp, `Games` g where sxp.`UserID` = '".$userid."' and sxp.`GameID` = g.`ID` order by sxp.`Date` DESC,sxp.`ID` DESC LIMIT 0,1")) {
		while($row = mysqli_fetch_array($result)){
			$mostRecentXPGame = $row["Title"]."||".$row["GameID"];
		}
	}
	
	//Get Latest XP Entry
	if ($result = $mysqli->query("select eve.`GameID`, g.`Title` from `Events` eve, `Games` g where eve.`UserID` = '".$userid."' and eve.`GameID` = g.`ID` order by eve.`Date` DESC LIMIT 0,1")) {
		while($row = mysqli_fetch_array($result)){
			$mostRecentPWGame = $row["Title"]."||".$row["GameID"];
		}
	}
	
	
	
	UpdateWeave($userid, 
				$total,
				mysqli_real_escape_string($mysqli, $mostRecentPWGame),
				mysqli_real_escape_string($mysqli, $mostRecentXPGame),
				$t1."||".$t2."||".$t3."||".$t4."||".$t5,
				$played,
				$watched,
				$totalAgrees,
				$totalFollowers,
				$both
				);
				
	 UpdateWeaveTimeStamp($userid);
	 Close($mysqli, $result);
}


function GetWeave($userid, $pconn = null){
	$weave = "";
	$mysqli = Connect($pconn);
	if ($result = $mysqli->query("select * from `Weave` where `UserID` = ".$userid)) {
		while($row = mysqli_fetch_array($result)){
			$weave = new Weave($row["ID"], 
				$row["UserID"],
				$row["TotalXP"],
				$row["RecentPW"],
				$row["RecentXP"],
				$row["OverallTierTotal"],
				$row["PercentagePlayed"],
				$row["PercentageWatched"],
				$row["TotalAgrees"],
				$row["TotalFollowers"],
				$row["PercentageBoth"],
				$row["PreferredXP"],
				$row["SubPreferredXP1"],
				$row["SubPreferredXP2"]
				);
		}
	}
	if($pconn == null)
		Close($mysqli, $result);
	return $weave;
}

function UpdateWeaveTimeStamp($userid){
	$mysqli = Connect();
	$timestamp = date("Y-m-d H:i:s");
	$mysqli->query("update `Weave` SET `LastUpdated`='$timestamp' where `UserID` = '".$userid."'");
	Close($mysqli, $result);
}

function UpdateWeave($userid, $totalXP, $recentPW, $recentXP, $overallTierTotal, $percentagePlayed, $percentageWatched, $totalAgrees, $totalFollowers, $percentageBoth){

	$mysqli = Connect();
	$doinsert = true;
	if ($result = $mysqli->query("select * from `Weave` where `UserID` = ".$userid)) {
		while($row = mysqli_fetch_array($result)){
			$doinsert = false;
		}
	}
	if($doinsert){
		InsertWeave($userid, $totalXP, $recentPW, $recentXP, $overallTierTotal, $percentagePlayed, $percentageWatched, $totalAgrees, $totalFollowers, $percentageBoth);
	}else{
		$result = $mysqli->query("update `Weave` SET `TotalXP`='$totalXP',`RecentPW`='$recentPW',`RecentXP`='$recentXP',`OverallTierTotal`='$overallTierTotal',`PercentagePlayed`='$percentagePlayed',`PercentageWatched`='$percentageWatched',`TotalAgrees`='$totalAgrees',`TotalFollowers`='$totalFollowers', `PercentageBoth`='$percentageBoth' where `UserID` = '$userid'") or die;
		//print_r( mysqli_error($mysqli));
	}
	Close($mysqli, $result);
}

function InsertWeave($userid, $totalXP, $recentPW, $recentXP, $overallTierTotal, $percentagePlayed, $percentageWatched, $totalAgrees, $totalFollowers, $percentageBoth){

	$mysqli = Connect();
	$mysqli->query("insert into `Weave` (`UserID`,`TotalXP`,`RecentPW`,`RecentXP`,`OverallTierTotal`,`PercentagePlayed`,`PercentageWatched`,`TotalAgrees`,`TotalFollowers`,`PercentageBoth`) VALUES ('$userid','$totalXP','$recentPW','$recentXP','$overallTierTotal','$percentagePlayed','$percentageWatched','$totalAgrees','$totalFollowers','$percentageBoth')");
	//print_r( mysqli_error($mysqli));
	Close($mysqli, $result);
}

function UpdatePreferredXP($gameid, $userid, $slot){
	    $mysqli = Connect();
	    if($slot == 1)
			$result = $mysqli->query("update `Weave` SET `PreferredXP`='".$gameid."' where `UserID` = '".$userid."'");
	    else if($slot == 2)
			$result = $mysqli->query("update `Weave` SET `SubPreferredXP1`='".$gameid."' where `UserID` = '".$userid."'");
		else if($slot == 3)
			$result = $mysqli->query("update `Weave` SET `SubPreferredXP2`='".$gameid."' where `UserID` = '".$userid."'");
		else{
			$weave = GetWeave($userid, $mysqli);
			if($weave->_preferredXP <= 0)
				$result = $mysqli->query("update `Weave` SET `PreferredXP`='".$gameid."' where `UserID` = '".$userid."'");
			else if($weave->_subpreferredXP1 <= 0)
				$result = $mysqli->query("update `Weave` SET `SubPreferredXP1`='".$gameid."' where `UserID` = '".$userid."'");
			else
				$result = $mysqli->query("update `Weave` SET `SubPreferredXP2`='".$gameid."' where `UserID` = '".$userid."'");
		}
		Close($mysqli, $result);
}

?>
