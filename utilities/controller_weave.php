<?php
require_once "controller_database.php";
require_once "controller_agree.php";
require_once "controller_experience.php";

AutoRunCalcWeave();

function AutoRunCalcWeave(){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Weave` order by `LastUpdated` ASC LIMIT 0, 20")) {
		while($row = mysqli_fetch_array($result)){
			echo "Calculating weave for user id ".$row["UserID"]."<br><br>";
			CalculateWeave($row["UserID"]);
		}
		echo "Finished";
	}
}

function CalculateWeave($userid){
	$mysqli = Connect();
	$t1 = 0;
	$t2 = 0;
	$t3 = 0;
	$t4 = 0;
	$t5 = 0;
	$t1Y = 0;
	$t2Y = 0;
	$t3Y = 0;
	$t4Y = 0;
	$t5Y = 0;
	$played= 0;
	$watched = 0;
	$both = 0;
	$finished = 0;
	$total = 0;
	$bookmarked = 0;
	$early = 0;
	$owned = 0;
	$dlc = 0;
	$demo = 0;
	$streamed = 0;
	$unfinished = 0;
	$watchedfull = 0;
	$multiplePlays = 0;
	$platforms = array();
	$currentYear = date('Y');
	$mostRecentXPDate = "";
	$mostRecentXPGame = "";
	$mostRecentPWDate = "";
	$mostRecentPWGame = "";
	$favGenre = array();
	$favPlatform = array();
	$favDeveloper = array();
	$favPublisher = array();
	$favFranchise = array();
	$mostPlayedYear = array();
	$mostWatchedYear = array();
	$mostPlayed = array();
	$mostWatched = array();
	$mostPlatform = array();
	$mostFranchise = array();
	$mostDeveloper = array();
	$mostPublisher = array();
	$mostGenre = array();
	$worstPlatform = array();
	$worstFranchise = array();
	$worstDeveloper = array();
	$worstPublisher = array();
	$worstGenre = array();
	$mostWatchedSource = array();
	$totalAgrees = 0;
	$topGameAgrees = array();
	$totalFollowers = 0;
	
	if ($result = $mysqli->query("select * from `Experiences` exp, `Games` gms where exp.`UserID` = '".$userid."' and exp.`GameID` = gms.`ID`")) {
		while($row = mysqli_fetch_array($result)){
			if($row["Tier"] == "1"){$t1 = $t1 + 1; }
			if($row["Tier"] == "2"){$t2 = $t2 + 1; }
			if($row["Tier"] == "3"){$t3 = $t3 + 1; }
			if($row["Tier"] == "4"){$t4 = $t4 + 1; }
			if($row["Tier"] == "5"){$t5 = $t5 + 1; }
			
			if($row["Tier"] == "1" && $row["Year"] == $currentYear){$t1Y = $t1Y + 1; }
			if($row["Tier"] == "2" && $row["Year"] == $currentYear){$t2Y = $t2Y + 1; }
			if($row["Tier"] == "3" && $row["Year"] == $currentYear){$t3Y = $t3Y + 1; }
			if($row["Tier"] == "4" && $row["Year"] == $currentYear){$t4Y = $t4Y + 1; }
			if($row["Tier"] == "5" && $row["Year"] == $currentYear){$t5Y = $t5Y + 1; }
			
			if($mostRecentPWDate == "" || $mostRecentPWDate < $row['ExperienceDate']){
				$mostRecentPWGame = $row["Title"]."||".$row["GameID"];
				$mostRecentPWDate = $row['ExperienceDate'];
			}
			
			$playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played');
			$watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched');
			if(sizeof($playedxp) > 0)
				$played = $played + 1;
			if(sizeof($watchedxp) > 0)
				$watched = $watched + 1;
			if(sizeof($playedxp) > 0 && sizeof($watchedxp) > 0)
				$both = $both + 1;
			foreach($watchedxp as $watch){
				if($watch->_length == 'Watched a complete single player playthrough')
					$watchedfull++;
					$mostWatched[$row["Title"]."||".$row["GameID"]] = $mostWatched[$row["Title"]."||".$row["GameID"]] + 1;
					
					$thisyear = explode("-",$watch->_date);
					$mostWatchedYear[$thisyear[0]] = $mostWatchedYear[$thisyear[0]] + 1;
					$mostWatchedSource[$watch->_source] = $mostWatchedSource[$watch->_source] + 1;
					
					if($mostRecentXPDate == "" || $mostRecentXPDate < $watch->_entereddate){
						$mostRecentXPGame = $row["Title"]."||".$row["GameID"];
						$mostRecentXPDate = $watch->_entereddate;
					}
			}
					
			
			$first = true;
			foreach($playedxp as $play){
				if($play->_completed == '100' || $play->_completed == '101')
					$finished++;
				if($play->_completed == '101')
					$multiplePlays++;
				if(($play->_completed == '10' || $play->_completed == '20' || $play->_completed == '30' || $play->_completed == '40' || $play->_completed == '50' || $play->_completed == '60' || $play->_completed == '70' || $play->_completed == '80' || $play->_completed == '90') && $first && ($play->_mode == "Single Player" || $play->_mode == "Single and Multiplayer"))
					$unfinished++;
				if($play->_alpha == "1" || $play->_beta == "1" || $play->_earlyaccess == "1")
					$early++;
				if($play->_dlc == "1")
					$dlc++;
				if($play->_streamed == "1")
					$streamed++;
				if($play->_demo == "1")
					$demo++;
				
				$mostPlayed[$row["Title"]."||".$row["GameID"]] = $mostPlayed[$row["Title"]."||".$row["GameID"]] + 1;
				
				$thisyear = explode("-",$play->_date);
				$mostPlayedYear[$thisyear[0]] = $mostPlayedYear[$thisyear[0]] + 1;
				
				$myplatforms = explode("\n", $play->_platform);
				foreach($myplatforms as $myplatform){
					if(trim($myplatform) != ""){ 
						if($row["Tier"] == "1"){ $favPlatform[trim($myplatform)] = $favPlatform[trim($myplatform)] + 1; }
						if($row["Tier"] == "5"){ $worstPlatform[trim($myplatform)] = $worstPlatform[trim($myplatform)] + 1; }
						$platforms[trim($myplatform)] = $platforms[trim($myplatform)] + 1;
					}
				}
				
				if($mostRecentXPDate == "" || $mostRecentXPDate < $play->_entereddate){
					$mostRecentXPGame = $row["Title"]."||".$row["GameID"];
					$mostRecentXPDate = $play->_entereddate;
				}
				
				$first = false;
			}
			
			if($row["BucketList"] == "Yes"){
				$bookmarked++;	
			}
			if($row["Owned"] == "Yes"){
				$owned++;	
			}
			
			$developers = explode("\n", $row["Developer"]);
			foreach($developers as $developer){ 
				if($developer != ""){ 
						$mostDeveloper[trim($developer)] = $mostDeveloper[trim($developer)] + 1;
						if($row["Tier"] == "1"){ $favDeveloper[trim($developer)] = $favDeveloper[trim($developer)] + 1; }
						if($row["Tier"] == "5"){ $worstDeveloper[trim($developer)] = $worstDeveloper[trim($developer)] + 1; }
				}
			}
			
			$franchises = explode("\n", $row["Franchise"]);
			foreach($franchises as $franchise){ 
				if($franchise != ""){ 
					$mostFranchise[trim($franchise)] = $mostFranchise[trim($franchise)] + 1;
					if($row["Tier"] == "1"){ $favFranchise[trim($franchise)] = $favFranchise[trim($franchise)] + 1; }
					if($row["Tier"] == "5"){ $worstFranchise[trim($franchise)] = $worstFranchise[trim($franchise)] + 1; }
				}
			}
			
			$publishers = explode("\n", $row["Publisher"]);
			foreach($publishers as $publisher){ 
				if($publisher != ""){ 
					$mostPublisher[trim($publisher)] = $mostPublisher[trim($publisher)] + 1;
					if($row["Tier"] == "1"){ $favPublisher[trim($publisher)] = $favPublisher[trim($publisher)] + 1; }
					if($row["Tier"] == "5"){ $worstPublisher[trim($publisher)] = $worstPublisher[trim($publisher)] + 1; }
				}
			}
			
			$genres = explode("\n", $row["Genre"]);
			foreach($genres as $genre){ 
				if($genre != ""){ 
					$mostGenre[trim($genre)] = $mostGenre[trim($genre)] + 1;
					if($row["Tier"] == "1"){ $favGenre[trim($genre)] = $favGenre[trim($genre)] + 1; }
					if($row["Tier"] == "5"){ $worstGenre[trim($genre)] = $worstGenre[trim($genre)] + 1; }
				}
			}
			
			$totalAgrees = $totalAgrees + GetTotalAgreesForXP($row['ID']);
			
			if($row["Tier"] > 0)
				$total = $total + 1;
			
		}
	}
	
	//Sort array and find matches
	arsort($mostWatched);
	$mostWatched = array_keys($mostWatched);
	arsort($mostPlayed);
	$mostPlayed = array_keys($mostPlayed);
	arsort($mostWatchedYear);
	$mostWatchedYear = array_keys($mostWatchedYear);
	arsort($mostPlayedYear);
	$mostPlayedYear = array_keys($mostPlayedYear);
	arsort($platforms);
	$mostPlatform = array_keys($platforms);
	arsort($mostDeveloper);
	$mostDeveloper = array_keys($mostDeveloper);
	arsort($mostPublisher);
	$mostPublisher = array_keys($mostPublisher);
	arsort($mostGenre);
	$mostGenre = array_keys($mostGenre);
	arsort($mostFranchise);
	$mostFranchise = array_keys($mostFranchise);
	arsort($worstPlatform);
	$worstPlatform = array_keys($worstPlatform);
	arsort($worstDeveloper);
	$worstDeveloper = array_keys($worstDeveloper);
	arsort($worstPublisher);
	$worstPublisher = array_keys($worstPublisher);
	arsort($worstGenre);
	$worstGenre = array_keys($worstGenre);
	arsort($worstFranchise);
	$worstFranchise = array_keys($worstFranchise);	
	arsort($favPlatform);
	$favPlatform = array_keys($favPlatform);
	arsort($favDeveloper);
	$favDeveloper = array_keys($favDeveloper);
	arsort($favPublisher);
	$favPublisher = array_keys($favPublisher);
	arsort($favGenre);
	$favGenre = array_keys($favGenre);
	arsort($favFranchise);
	$favFranchise = array_keys($favFranchise);
	arsort($mostWatchedSource);
	$mostWatchedSource = array_keys($mostWatchedSource);
	arsort($topGameAgrees);
	$topGameAgrees = array_keys($topGameAgrees);
	
	UpdateWeave($userid, 
				$total,
				mysqli_real_escape_string($mysqli, $mostRecentPWGame),
				mysqli_real_escape_string($mysqli, $mostRecentXPGame),
				$t1."||".$t2."||".$t3."||".$t4."||".$t5,
				$bookmarked,
				$played,
				$watched,
				$t1Y."||".$t2Y."||".$t3Y."||".$t4Y."||".$t5Y,
				$unfinished,
				$early,
				$watchedfull,
				$streamed,
				json_encode($platforms),
				mysqli_real_escape_string($mysqli, $mostWatched[0]),
				mysqli_real_escape_string($mysqli, $mostPlayed[0]),
				"CriticMatch",
				$mostWatchedYear[0],
				$mostPlayedYear[0],
				mysqli_real_escape_string($mysqli, $favPlatform[0]),
				mysqli_real_escape_string($mysqli, $favFranchise[0]),
				mysqli_real_escape_string($mysqli, $favDeveloper[0]),
				mysqli_real_escape_string($mysqli, $favPublisher[0]),
				mysqli_real_escape_string($mysqli, $favGenre[0]),
				$multiplePlays,
				$demo,
				$dlc,
				$finished,
				$owned,
				mysqli_real_escape_string($mysqli, $mostPlatform[0]),
				mysqli_real_escape_string($mysqli, $mostFranchise[0]),
				mysqli_real_escape_string($mysqli, $mostDeveloper[0]),
				mysqli_real_escape_string($mysqli, $mostPublisher[0]),
				mysqli_real_escape_string($mysqli, $mostGenre[0]),
				mysqli_real_escape_string($mysqli, $worstPlatform[0]),
				mysqli_real_escape_string($mysqli, $worstFranchise[0]),
				mysqli_real_escape_string($mysqli, $worstDeveloper[0]),
				mysqli_real_escape_string($mysqli, $worstPublisher[0]),
				mysqli_real_escape_string($mysqli, $worstGenre[0]),
				mysqli_real_escape_string($mysqli, $mostWatchedSource[0]),
				$totalAgrees,
				$topGameAgrees[0],
				$totalFollowers,
				$both
				);
				
	 UpdateWeaveTimeStamp($userid);
}


function GetWeave($userid){
	$weave = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Weave` where `UserID` = ".$userid)) {
		while($row = mysqli_fetch_array($result)){
			$weave = new Weave($row["ID"], 
				$row["UserID"],
				$row["TotalXP"],
				$row["RecentPW"],
				$row["RecentXP"],
				$row["OverallTierTotal"],
				$row["TotalBookmarked"],
				$row["PercentagePlayed"],
				$row["PercentageWatched"],
				$row["TierTotalCurrentYear"],
				$row["Unfinished"],
				$row["EarlyAccess"],
				$row["WatchedFull"],
				$row["Streamed"],
				$row["Platforms"],
				$row["MostWatched"],
				$row["MostPlayed"],
				$row["CriticMatch"],
				$row["MostPlayedYear"],
				$row["MostWatchedYear"],
				$row["FavPlatform"],
				$row["FavFranchise"],
				$row["FavDeveloper"],
				$row["FavPublisher"],
				$row["FavGenre"],
				$row["MultiplePlays"],
				$row["Demo"],
				$row["DLC"],
				$row["Completed"],
				$row["Owned"],
				$row["MostPlatform"],
				$row["MostFranchise"],
				$row["MostDeveloper"],
				$row["MostPublisher"],
				$row["MostGenre"],
				$row["WorstPlatform"],
				$row["WorstFranchise"],
				$row["WorstDeveloper"],
				$row["WorstPublisher"],
				$row["WorstGenre"],
				$row["MostWatchedSource"],
				$row["TotalAgrees"],
				$row["TopGameAgrees"],
				$row["TotalFollowers"],
				$row["PercentageBoth"]
				);
		}
	}
	return $weave;
}

function UpdateWeaveTimeStamp($userid){
	$mysqli = Connect();
	$timestamp = date("Y-m-d H:i:s");
	$mysqli->query("update `Weave` SET `LastUpdated`='$timestamp' where `UserID` = '".$userid."'");
}

function UpdateWeave($userid, $totalXP, $recentPW, $recentXP, $overallTierTotal, $totalBookmarked, $percentagePlayed, $percentageWatched, $tierTotalCurrentYear, $unfinished, 
						$earlyAccess, $watchedFull, $streamed, $platforms, $mostWatched, $mostPlayed, $criticMatch, $mostPlayedYear, $mostWatchedYear, $favPlatform, $favFranchise,
						$favDeveloper, $favPublisher, $favGenre, $multiplePlays, $demo, $dlc, $completed, $owned, $mostPlatform, $mostFranchise, $mostDeveloper, $mostPublisher, $mostGenre, 
						$worstPlatform, $worstFranchise, $worstDeveloper, $worstPublisher, $worstGenre, $mostWatchedSource, $totalAgrees, $topGameAgrees, $totalFollowers, $percentageBoth){

	$mysqli = Connect();
	$doinsert = true;
	if ($result = $mysqli->query("select * from `Weave` where `UserID` = ".$userid)) {
		while($row = mysqli_fetch_array($result)){
			$doinsert = false;
		}
	}
	if($doinsert){
		InsertWeave($userid, $totalXP, $recentPW, $recentXP, $overallTierTotal, $totalBookmarked, $percentagePlayed, $percentageWatched, $tierTotalCurrentYear, $unfinished, 
						$earlyAccess, $watchedFull, $streamed, $platforms, $mostWatched, $mostPlayed, $criticMatch, $mostPlayedYear, $mostWatchedYear, $favPlatform, $favFranchise,
						$favDeveloper, $favPublisher, $favGenre, $multiplePlays, $demo, $dlc, $completed, $owned, $mostPlatform, $mostFranchise, $mostDeveloper, $mostPublisher, $mostGenre, 
						$worstPlatform, $worstFranchise, $worstDeveloper, $worstPublisher, $worstGenre, $mostWatchedSource, $totalAgrees, $topGameAgrees, $totalFollowers, $percentageBoth);
	}else{
		$result = $mysqli->query("update `Weave` SET `TotalXP`='$totalXP',`RecentPW`='$recentPW',`RecentXP`='$recentXP',`OverallTierTotal`='$overallTierTotal',`TotalBookmarked`='$totalBookmarked',`PercentagePlayed`='$percentagePlayed',`PercentageWatched`='$percentageWatched',`TierTotalCurrentYear`='$tierTotalCurrentYear',`Unfinished`='$unfinished',`EarlyAccess`='$earlyAccess',`WatchedFull`='$watchedFull',`Streamed`='$streamed',`Platforms`='$platforms',`MostWatched`='$mostWatched',`MostPlayed`='$mostPlayed',`CriticMatch`='$criticMatch',`MostPlayedYear`='$mostPlayedYear',`MostWatchedYear`='$mostWatchedYear',`FavPlatform`='$favPlatform',`FavFranchise`='$favFranchise',`FavDeveloper`='$favDeveloper',`FavPublisher`='$favPublisher',`FavGenre`='$favGenre',`MultiplePlays`='$multiplePlays',`Demo`='$demo',`DLC`='$dlc',`Completed`='$completed',`Owned`='$owned',`MostPlatform`='$mostPlatform',`MostFranchise`='$mostFranchise',`MostDeveloper`='$mostDeveloper',`MostPublisher`='$mostPublisher',`MostGenre`='$mostGenre',`WorstPlatform`='$worstPlatform',`WorstFranchise`='$worstFranchise',`WorstDeveloper`='$worstDeveloper',`WorstPublisher`='$worstPublisher',`WorstGenre`='$worstGenre',`MostWatchedSource`='$mostWatchedSource',`TotalAgrees`='$totalAgrees',`TopGameAgrees`='$topGameAgrees',`TotalFollowers`='$totalFollowers', `PercentageBoth`='$percentageBoth' where `UserID` = '$userid'") or die;
		//print_r( mysqli_error($mysqli));
	}
}

function InsertWeave($userid, $totalXP, $recentPW, $recentXP, $overallTierTotal, $totalBookmarked, $percentagePlayed, $percentageWatched, $tierTotalCurrentYear, $unfinished, 
						$earlyAccess, $watchedFull, $streamed, $platforms, $mostWatched, $mostPlayed, $criticMatch, $mostPlayedYear, $mostWatchedYear, $favPlatform, $favFranchise,
						$favDeveloper, $favPublisher, $favGenre, $multiplePlays, $demo, $dlc, $completed, $owned, $mostPlatform, $mostFranchise, $mostDeveloper, $mostPublisher, $mostGenre, 
						$worstPlatform, $worstFranchise, $worstDeveloper, $worstPublisher, $worstGenre, $mostWatchedSource, $totalAgrees, $topGameAgrees, $totalFollowers){

	$mysqli = Connect();
	$mysqli->query("insert into `Weave` (`UserID`,`TotalXP`,`RecentPW`,`RecentXP`,`OverallTierTotal`,`TotalBookmarked`,`PercentagePlayed`,`PercentageWatched`,`TierTotalCurrentYear`,`Unfinished`,`EarlyAccess`,`WatchedFull`,`Streamed`,`Platforms`,`MostWatched`,`MostPlayed`,`CriticMatch`,`MostPlayedYear`,`MostWatchedYear`,`FavPlatform`,`FavFranchise`,`FavDeveloper`,`FavPublisher`,`FavGenre`,`MultiplePlays`,`Demo`,`DLC`,`Completed`,`Owned`,`MostPlatform`,`MostFranchise`,`MostDeveloper`,`MostPublisher`,`MostGenre`,`WorstPlatform`,`WorstFranchise`,`WorstDeveloper`,`WorstPublisher`,`WorstGenre`,`MostWatchedSource`,`TotalAgrees`,`TopGameAgrees`,`TotalFollowers`,`PercentageBoth`) VALUES ('$userid','$totalXP','$recentPW','$recentXP','$overallTierTotal','$totalBookmarked','$percentagePlayed','$percentageWatched','$tierTotalCurrentYear','$unfinished','$earlyAccess','$watchedFull','$streamed','$platforms','$mostWatched','$mostPlayed','$criticMatch','$mostPlayedYear','$mostWatchedYear','$favPlatform','$favFranchise','$favDeveloper','$favPublisher','$favGenre','$multiplePlays','$demo','$dlc','$completed','$owned','$mostPlatform','$mostFranchise','$mostDeveloper','$mostPublisher','$mostGenre','$worstPlatform','$worstFranchise','$worstDeveloper','$worstPublisher','$worstGenre','$mostWatchedSource','$totalAgrees','$topGameAgrees','$totalFollowers','$percentageBoth')");
	//print_r( mysqli_error($mysqli));
}

function InsertPolygonalWeave($mostBookmarkedGame, $mostActiveUsers, $topTierGameTrending, $bottomTierGameTrending, $topTierGameAllTime, $bottomTierGameAllTime
						, $bestYear, $worstYear, $topTierPlatform, $lovedDev, $hatedDev, $watchedGamesTrending, $playedGamesTrending, $newReleaseGenres
						, $newReleasePlatforms, $mostLikedQuoteTrending, $lovedFranchise, $hatedFranchise, $mostUnfinishedGame, $mostStreamedGame, $mostReplayedGame){

	$mysqli = Connect();
	$mysqli->query("insert into `Weave` (`MostBookmarkedGame`,`MostActiveUsers`,`TopTierGameTrending`,`BottomTierGameTrending`,`TopTierGameAllTime`,`BottomTierGameAllTime`,`BestYear`,`WorstYear`,`TopTierPlatform`,`LovedDev`,`HatedDev`,`WatchedGamesTrending`,`PlayedGamesTrending`,`NewReleaseGenres`,`NewReleasePlatforms`,`MostLikedQuoteTrending`,`LovedFranchise`,`HatedFranchise`,`MostUnfinishedGame`,`MostStreamedGame`,`MostReplayedGame`) VALUES ('$mostBookmarkedGame', '$mostActiveUsers', '$topTierGameTrending', '$bottomTierGameTrending', '$topTierGameAllTime', '$bottomTierGameAllTime', '$bestYear', '$worstYear', '$topTierPlatform', '$lovedDev', '$hatedDev', '$watchedGamesTrending', '$playedGamesTrending', '$newReleaseGenres','$newReleasePlatforms', '$mostLikedQuoteTrending', '$lovedFranchise', '$hatedFranchise', '$mostUnfinishedGame', '$mostStreamedGame', '$mostReplayedGame')");
	//print_r( mysqli_error($mysqli));
}

function GetPolygonalWeave(){
	$weave = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `PolygonalWeave` Order By `Date` Desc LIMIT 0,1")) {
		while($row = mysqli_fetch_array($result)){
			$weave = new PolygonalWeave($row["ID"], 
				$row["MostBookmarkedGame"],
				$row["MostActiveUsers"],
				$row["TopTierGameTrending"],
				$row["BottomTierGameTrending"],
				$row["TopTierGameAllTime"],
				$row["BottomTierGameAllTime"],
				$row["BestYear"],
				$row["WorstYear"],
				$row["TopTierPlatform"],
				$row["LovedDev"],
				$row["HatedDev"],
				$row["WatchedGamesTrending"],
				$row["PlayedGamesTrending"],
				$row["NewReleaseGenres"],
				$row["NewReleasePlatforms"],
				$row["MostLikedQuoteTrending"],
				$row["LovedFranchise"],
				$row["HatedFranchise"],
				$row["MostUnfinishedGame"],
				$row["MostStreamedGame"],
				$row["MostReplayedGame"],
				$row["Date"]
				);
		}
	}
	return $weave;
}

?>