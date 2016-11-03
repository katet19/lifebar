<?php
require_once "includes.php";

function GetEventsForGame($userid, $gameid){
	$mysqli = Connect();
	$events = array();
	if ($result = $mysqli->query("select * from `Events` eve where eve.`UserID` = '".$userid."' and eve.`GameID` = '".$gameid."' and `Event` in ('TIERCHANGED','QUOTECHANGED','ADDED','UPDATE','FINISHED','BUCKETLIST') order by `Date` desc")) {
		while($row = mysqli_fetch_array($result)){
			unset($event);
			$event[] = $row;
			if($row['S_XPID'] > 0){
				if ($result2 = $mysqli->query("select * from `Sub-Experiences` where `UserID` = '".$userid."' and `GameID` = '".$gameid."' and `ID` = '".$row['S_XPID']."'")) {
					while($row2 = mysqli_fetch_array($result2)){
						$event[] = $row2;	
					}
				}
			}else{
				$event[] = '';
			}
			$events[] = $event;
		}
	}
	Close($mysqli, $result);
	return array_filter($events);
}

//RunTierGameUpdater(200);
function RunTierGameUpdater($gameid){
	$mysqli = Connect();
	$end = $gameid + 50;
	while($gameid <= $end){
		$tier1Query = "select count(*) from `Experiences` where `Tier` = '1' and `GameID` = exp.GameID";
		$tier2Query = "select count(*) from `Experiences` where `Tier` = '2' and `GameID` = exp.GameID";
		$tier3Query = "select count(*) from `Experiences` where `Tier` = '3' and `GameID` = exp.GameID";
		$tier4Query = "select count(*) from `Experiences` where `Tier` = '4' and `GameID` = exp.GameID";
		$tier5Query = "select count(*) from `Experiences` where `Tier` = '5' and `GameID` = exp.GameID";
		if ($result = $mysqli->query("select *, (".$tier1Query.") as t1, (".$tier2Query.") as t2, (".$tier3Query.") as t3, (".$tier4Query.") as t4, (".$tier5Query.") as t5 from `Experiences` exp where `GameID` = '".$gameid."'")) {
			while($row = mysqli_fetch_array($result)){
				$mysqli->query("update `Games` set `Tier1`='".$row['t1']."',`Tier2`='".$row['t2']."',`Tier3`='".$row['t3']."',`Tier4`='".$row['t4']."',`Tier5`='".$row['t5']."' where `ID` = '".$row['GameID']."'");
			}	
		}
		$gameid++;
	}
	Close($mysqli, $result);
}

function CalculateGameTierData($gameid){
	$mysqli = Connect();
	$tier1Query = "select count(*) from `Experiences` where `Tier` = '1' and `GameID` = exp.GameID";
	$tier2Query = "select count(*) from `Experiences` where `Tier` = '2' and `GameID` = exp.GameID";
	$tier3Query = "select count(*) from `Experiences` where `Tier` = '3' and `GameID` = exp.GameID";
	$tier4Query = "select count(*) from `Experiences` where `Tier` = '4' and `GameID` = exp.GameID";
	$tier5Query = "select count(*) from `Experiences` where `Tier` = '5' and `GameID` = exp.GameID";
	if ($result = $mysqli->query("select *, (".$tier1Query.") as t1, (".$tier2Query.") as t2, (".$tier3Query.") as t3, (".$tier4Query.") as t4, (".$tier5Query.") as t5 from `Experiences` exp where `GameID` = '".$gameid."'")) {
		while($row = mysqli_fetch_array($result)){
			$mysqli->query("update `Games` set `Tier1`='".$row['t1']."',`Tier2`='".$row['t2']."',`Tier3`='".$row['t3']."',`Tier4`='".$row['t4']."',`Tier5`='".$row['t5']."' where `ID` = '".$row['GameID']."'");
		}	
	}
	Close($mysqli, $result);
}

function GetGlobalLatestXP(){
	$experiences = array();
	$lastuser = '';
	$mysqli = Connect();
	if ($result = $mysqli->query("select `UserID`, `GameID`, `DateEntered` from `Sub-Experiences` ORDER BY `DateEntered` DESC, `UserID` LIMIT 0,8")){
		while($row = mysqli_fetch_array($result)){
			$game = GetGame($row["GameID"], $mysqli);
			$user = GetUser($row['UserID'], $mysqli);
			if($lastuser != $row['UserID']){
				$lastuser = $row['UserID'];
				if ($result2 = $mysqli->query("select * from `Experiences` where `UserID` = '".$row['UserID']."' and `GameID` = '".$row['GameID']."' ")){
					while($row2 = mysqli_fetch_array($result2)){
						$experience = new Experience($row2["ID"],
									$user->_first,
									$user->_last,
									$user,
									$row2["UserID"],
									$row2["GameID"],
									$game,
									$row2["Tier"],
									$row2["Quote"],
									$row["DateEntered"],
									$row2["Link"],
									$row2["Owned"],
									$row2["BucketList"],
									$row2["AuthenticXP"],
									$row2['Rank']);
								
						$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
						$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
						$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
										
						$experiences[] = $experience;
					}
				}
			}
		}
	}
	Close($mysqli, $result);
	return $experiences;
}

function GetGameVideoXP($gameid){
	$mysqli = Connect();
	$videos = array();
	if ($result = $mysqli->query("SELECT * FROM `Sub-Experiences` WHERE `GameID` = '".$gameid."' and `URL` != '' group by `URL` order by `DateEntered` DESC")){
		while($row = mysqli_fetch_array($result)){
			unset($video);
			$video['URL'] = $row['URL'];
			$video['Length'] = $row['Length'];
			$video['Source'] = $row['Source'];
			$videos[] = $video;
		}
	}
	Close($mysqli, $result);
	return $videos;
}

function GetVideoXPForGame($url, $gameid){
	$mysqli = Connect();
	$video = array();
	if ($result = $mysqli->query("SELECT * FROM `Sub-Experiences` WHERE `GameID` = '".$gameid."' and `URL` = '".$url."' LIMIT 0,1")){
		while($row = mysqli_fetch_array($result)){
			$video['URL'] = $row['URL'];
			$video['Length'] = $row['Length'];
			$video['Source'] = $row['Source'];
		}
	}
	Close($mysqli, $result);
	return $video;
}

function GetVideoMyXPForGame($url, $gameid){
	$mysqli = Connect();
	if ($result = $mysqli->query("SELECT * FROM `Sub-Experiences` WHERE `GameID` = '".$gameid."' and `UserID` = '".$_SESSION['logged-in']->_id."' and `URL` = '".$url."' LIMIT 0,1")){
		while($row = mysqli_fetch_array($result)){
			$subexp = new SubExperience($row['ID'], 
				$row['ExpID'], 
				$row['UserID'], 
				$row['GameID'],
				$row['Type'], 
				$row['Source'], 
				$row['Date'], 
				$row['URL'],
				$row['Length'], 
				$row['Thoughts'], 
				$row['ArchiveQuote'], 
				$row['ArchiveTier'], 
				$row['DateEntered'], 
				$row['Completed'], 
				$row['Mode'], 
				$row['Platform'],
				$row['PlatformIDs'],
				$row['DLC'],
				$row['Alpha'],
				$row['Beta'],
				$row['Early Access'],
				$row['Demo'],
				$row['Streamed'],
				$row['Archived'],
				$row['AuthenticXP']);
		}
	}
	Close($mysqli, $result);
	return $subexp;
}

function NormalizeVideoURLs($url){
	if(strpos($url, 'giantbomb.com') !== false){
		if(strpos($url , 'giantbomb.com/videos/embed/') !== false){
			$vurl = $url;
		}else{
			$vurl = "http://www.giantbomb.com/videos/embed/";
			$vidArray = explode("-", $url);
			$vurl = $vurl.end($vidArray);
		}
	}else if(strpos($url , 'youtube.com') !== false || strpos($url , 'youtu.be') !== false){
			$vurl = "https://www.youtube.com/embed/";
			$vidArray = explode("/", $url);
			$vurl = $vurl.end(str_replace("watch?v=","",$vidArray));
	}else if(strpos($video['URL'], 'gamespot.com') !== false){
			$vurl = "http://www.gamespot.com/videos/embed/";
			$vidArray = explode("-", $video['URL']);
			$vurl = $vurl.end($vidArray);
	}else{
		$vurl = $url;
	}

	return $vurl;
}

function AdvancedFilterWeave($userid, $paramaters, $sort){
	$mysqli = Connect();
	
	//Loop through and find out which tables we need
		$i = 0;
		$exp = false;
		$subexp = false;
		while($i < sizeof($paramaters)){
			$key = $paramaters[$i];
			$value = $paramaters[$i+1];
			$logic = $paramaters[$i+2];
	
			if(($key == "Owned" || $key == "BucketList" || $key == "ExperienceDate" || $key == "Tier" || $key == "Quote") && !$exp){
				$exp = true;
				if ($logic == "or") {
					$orquery[] = "e.`".$key."` = '".$value."'";
				}else if ($logic == "notand") {
					$andquery[] = "e.`".$key."` != '".$value."'";
				}else if ($logic == "likeand") {
					$andquery[] = "e.`".$key."` like '%".$value."%'";
				}else{
					$andquery[] = "e.`".$key."` = '".$value."'";
				}
			}
			if($key == "Archived" || $key == "Type" || $key == "Completed" || $key == "Source" || $key == "URL" || $key == "Length" || $key == "Mode" || $key == "Platform" || $key == "DLC" || $key == "Alpha" || $key == "Beta" || $key == "Demo" || $key == "Streamed"|| $key == "Early Access"){
				$subexp = true;
				if ($logic == "or") {
					$orquery[] = "s.`".$key."` = '".$value."'";
				}else if ($logic == "notand") {
					$andquery[] = "s.`".$key."` != '".$value."'";
				}else if ($logic == "likeand") {
					$andquery[] = "s.`".$key."` like '%".$value."%'";
				}else{
					$andquery[] = "s.`".$key."` = '".$value."'";
				}
			}
			if($key == "Genre" || $key == "Rated" || $key == "Released" || $key == "Year" || $key == "Publisher" || $key == "Developer" || $key == "Theme" || $key == "Franchise" || $key == "Similar"){
				if ($logic == "or") {
					$orquery[] = "g.`".$key."` = '".$value."'";
				}else if ($logic == "notand") {
					$andquery[] = "g.`".$key."` != '".$value."'";
				}else if ($logic == "likeand") {
					$andquery[] = "g.`".$key."` like '%".$value."%'";
				}else{
					$andquery[] = "g.`".$key."` = '".$value."'";
				}
			}
			$i = $i + 3;
		} 
		
		if($exp && $subexp)
			$selectquery = $selectquery = "select * from `Experiences` e, `Sub-Experiences` s, `Games` g where e.`UserID` = '".$userid."' and s.`UserID` = '".$userid."' and e.`GameID` = g.`ID` and e.`GameID` = s.`GameID` and ( ";
		else if(!$exp && $subexp)
			$selectquery = $selectquery = "select * from `Experiences` e, `Sub-Experiences` s, `Games` g where s.`UserID` = '".$userid."' and e.`UserID` = '".$userid."' and s.`GameID` = g.`ID` and s.`GameID` = e.`GameID` and ( ";
		else
			$selectquery = $selectquery = "select * from `Experiences` e, `Games` g where e.`UserID` = '".$userid."'  and e.`GameID` = g.`ID` and ( ";
			
	
	//Build my query
	if(sizeof($andquery) > 0)
		$and = implode(" and ",$andquery);
	if(sizeof($orquery) > 0)
		$or = implode(" or ",$orquery);
	if(sizeof($orquery) > 0 &&  sizeof($andquery) > 0)
		$query = $selectquery.$and." ) and ( ".$or." ) group by g.`ID` ".$sort;
	else if(sizeof($orquery) > 0)
		$query = $selectquery.$or." ) group by g.`ID` ".$sort;
	else
		$query = $selectquery.$and." ) group by g.`ID` ".$sort;
	
	//echo $query;		
	if ($result = $mysqli->query($query) or die(mysqli_error($mysqli))){
		$user = GetUser($userid, $mysqli);
		while($row = mysqli_fetch_array($result)){
			$game = new Game($row["GameID"], 
					$row["GBID"],
					$row["Title"],
					$row["Rated"],
					$row["Released"],
					$row["Genre"],
					$row["Platforms"],
					$row["Year"],
					$row["ImageLarge"],
					$row["ImageSmall"],
					$row["Highlight"],
					$row["Publisher"],
					$row["Developer"],
					$row["Alias"],
					$row["Theme"],
					$row["Franchise"],
					$row["Similar"],
					$row["Tier1"],
					$row["Tier2"],
					$row["Tier3"],
					$row["Tier4"],
					$row["Tier5"]
					);
			
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						$game,
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
						
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
							
			$experiences[] = $experience;
		}
	}
	Close($mysqli, $result);
	return $experiences;
}


function SearchForGamesInWeave($searchstring, $userid){
	$experiences = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Experiences` exp, `Games` gms where exp.`UserID` = '".$userid."' and exp.`Tier` > 0 and gms.`Title` like '%".$searchstring."%' and exp.`GameID` = gms.`ID` LIMIT 0,50")) {
		$user = GetUser($userid);
		while($row = mysqli_fetch_array($result)){
			$game = new Game($row["GameID"], 
					$row["GBID"],
					$row["Title"],
					$row["Rated"],
					$row["Released"],
					$row["Genre"],
					$row["Platforms"],
					$row["Year"],
					$row["ImageLarge"],
					$row["ImageSmall"],
					$row["Highlight"],
					$row["Publisher"],
					$row["Developer"],
					$row["Alias"],
					$row["Theme"],
					$row["Franchise"],
					$row["Similar"],
					$row["Tier1"],
					$row["Tier2"],
					$row["Tier3"],
					$row["Tier4"],
					$row["Tier5"]
					);
			
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						$game,
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
						
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);	
						
			$experiences[] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $experiences;
}

function GetTierBreakdownLight($userid, $year){
	$tiers = array();
	$tier1 = 0;
	$tier2 = 0;
	$tier3 = 0;
	$tier4 = 0;
	$tier5 = 0;
	$total = 0;
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Experiences` exp, `Games` gms where `UserID` = '".$userid."' and exp.`GameID` = gms.`ID` and gms.`Year` = '".$year."' and exp.`Tier` != '0'")) {
		while($row = mysqli_fetch_array($result)){
			if($row["Tier"] == 1)
				$tier1++;
			else if($row["Tier"] == 2)
				$tier2++;
			else if($row["Tier"] == 3)
				$tier3++;
			else if($row["Tier"] == 4)
				$tier4++;
			else if($row["Tier"] == 5)
				$tier5++;
			
			$total++;
		}
		$tiers[] = $total;
		$tiers[] = $tier1;
		$tiers[] = $tier2;
		$tiers[] = $tier3;
		$tiers[] = $tier4;
		$tiers[] = $tier5;
	}
	Close($mysqli, $result);
	
	return $tiers;
}

function GetTierBreakdownYearTier($userid, $year, $tier){
	$mysqli = Connect();
	$exp = array();
	if ($result = $mysqli->query("select exp.*, gms.* from `Experiences` exp, `Games` gms where `UserID` = '".$userid."' and exp.`GameID` = gms.`ID` and gms.`Year` = '".$year."' and exp.`Tier` = '".$tier."' order by gms.`Title` ASC")) {
		while($row = mysqli_fetch_array($result)){
				$game = new Game($row["GameID"], 
					$row["GBID"],
					$row["Title"],
					$row["Rated"],
					$row["Released"],
					$row["Genre"],
					$row["Platforms"],
					$row["Year"],
					$row["ImageLarge"],
					$row["ImageSmall"],
					$row["Highlight"],
					$row["Publisher"],
					$row["Developer"],
					$row["Alias"],
					$row["Theme"],
					$row["Franchise"],
					$row["Similar"],
					$row["Tier1"],
					$row["Tier2"],
					$row["Tier3"],
					$row["Tier4"],
					$row["Tier5"]
					);
					
					$experience = new Experience($row["exp.ID"],
						'',
						'',
						'',
						$row["UserID"],
						$row["GameID"],
						$game,
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
						
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
			
			$exp[] = $experience;
					
		}
		
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetTierBreakdown($userid, $year){
	$tiers = array();
	$tier1 = array();
	$tier2 = array();
	$tier3 = array();
	$tier4 = array();
	$tier5 = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Experiences` exp, `Games` gms where `UserID` = '".$userid."' and exp.`GameID` = gms.`ID` and gms.`Year` = '".$year."' and exp.`Tier` != '0' order by exp.`Tier`, exp.`ExperienceDate` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$game = new Game($row["GameID"], 
					$row["GBID"],
					$row["Title"],
					$row["Rated"],
					$row["Released"],
					$row["Genre"],
					$row["Platforms"],
					$row["Year"],
					$row["ImageLarge"],
					$row["ImageSmall"],
					$row["Highlight"],
					$row["Publisher"],
					$row["Developer"],
					$row["Alias"],
					$row["Theme"],
					$row["Franchise"],
					$row["Similar"],
					$row["Tier1"],
					$row["Tier2"],
					$row["Tier3"],
					$row["Tier4"],
					$row["Tier5"]
					);
			
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						$game,
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
						
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
			if($row["Tier"] == 1)
				$tier1[] = $experience;
			else if($row["Tier"] == 2)
				$tier2[] = $experience;
			else if($row["Tier"] == 3)
				$tier3[] = $experience;
			else if($row["Tier"] == 4)
				$tier4[] = $experience;
			else if($row["Tier"] == 5)
				$tier5[] = $experience;
		}
		$tiers[] = $tier1;
		$tiers[] = $tier2;
		$tiers[] = $tier3;
		$tiers[] = $tier4;
		$tiers[] = $tier5;
	}
	Close($mysqli, $result);
	
	return $tiers;
}

function GetPopularFilterBy($filter){
	$games= array();
	$totalcount = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select *, Count(`GameID`) as TotalRows from `Sub-Experiences` exp where ".$filter)) {
		$user = GetUser($userid);
		while($row = mysqli_fetch_array($result)){
			$games[] = GetGame($row["GameID"], $mysqli);
			$totalcount[] = $row["TotalRows"];
		}
	}
	
	$allresults[] = $games;
	$allresults[] = $totalcount;
	Close($mysqli, $result);
	
	return $allresults;
}

function GetPopularGamesData(){
	$games= array();
	$mysqli = Connect();
	$thisquarter = date('Y-m-d', strtotime("now -93 days") );
	if ($result = $mysqli->query("select * from `Sub-Experiences` exp where `Type` LIKE  'Played' and `Date` >= '".$thisquarter."' GROUP BY  `GameID` ORDER BY COUNT(  `GameID` ) DESC LIMIT 1")) {
		$user = GetUser($userid, $mysqli);
		while($row = mysqli_fetch_array($result)){
			$games[] = GetGame($row["GameID"], $mysqli);
		}
	}
	if ($result = $mysqli->query("select * from `Sub-Experiences` exp where `Type` LIKE  'Watched' and `Date` >= '".$thisquarter."' GROUP BY  `GameID` ORDER BY COUNT(  `GameID` ) DESC LIMIT 1")) {
		$user = GetUser($userid, $mysqli);
		while($row = mysqli_fetch_array($result)){
			$games[] = GetGame($row["GameID"], $mysqli);
		}
	}
	if ($result = $mysqli->query("select * from `Sub-Experiences` exp where `Type` LIKE  'Played' GROUP BY  `GameID` ORDER BY COUNT(  `GameID` ) DESC LIMIT 1")) {
		$user = GetUser($userid, $mysqli);
		while($row = mysqli_fetch_array($result)){
			$games[] = GetGame($row["GameID"], $mysqli);
		}
	}
	if ($result = $mysqli->query("select * from `Sub-Experiences` exp where `Type` LIKE  'Watched' GROUP BY `GameID` ORDER BY COUNT(  `GameID` ) DESC LIMIT 1")) {
		$user = GetUser($userid, $mysqli);
		while($row = mysqli_fetch_array($result)){
			$games[] = GetGame($row["GameID"], $mysqli);
		}
	}
	Close($mysqli, $result);
		
	return $games;	
}

function GetTrendingGamesCategory(){
	$games= array();
	$mysqli = Connect();
	$thisquarter = date('Y-m-d', strtotime("now -30 days") );
	if ($result = $mysqli->query("select * from `Sub-Experiences` exp where `DateEntered` >= '".$thisquarter."' GROUP BY  `GameID` ORDER BY COUNT(  `GameID` ) DESC LIMIT 6")) {
		while($row = mysqli_fetch_array($result)){
			$games[] = GetGame($row["GameID"], $mysqli);
		}
	}
	Close($mysqli, $result);
	
	return $games;
}

function GetTrendingGames(){
	$games= array();
	$mysqli = Connect();
	$thisquarter = date('Y-m-d', strtotime("now -30 days") );
	$month = date('m');
	if($month > 0 && $month < 4){ 
		$startmonth = date('Y')+"-01-01";
	}else if($month > 3 && $month < 7){ 
		$startmonth = date('Y')+"-04-01";
	}else if($month > 6 && $month < 10){ 
		$startmonth = date('Y')+"-07-01";
	}else{
		$startmonth = date('Y')+"-10-01";
	}
	if ($result = $mysqli->query("select * from `Sub-Experiences` exp where `DateEntered` >= '".$thisquarter."' GROUP BY  `GameID` ORDER BY COUNT(  `GameID` ) DESC LIMIT 10")) {
		while($row = mysqli_fetch_array($result)){
			$games[] = GetGame($row["GameID"], $mysqli);
		}
	}
	Close($mysqli, $result);

	return $games;
}

function GetTrendingGamesLandingPage(){
	$games= array();
	$mysqli = Connect();
	$thisquarter = date('Y-m-d', strtotime("now -30 days") );
	$month = date('m');
	if($month > 0 && $month < 4){ 
		$startmonth = date('Y')+"-01-01";
	}else if($month > 3 && $month < 7){ 
		$startmonth = date('Y')+"-04-01";
	}else if($month > 6 && $month < 10){ 
		$startmonth = date('Y')+"-07-01";
	}else{
		$startmonth = date('Y')+"-10-01";
	}
	//echo "select *, (".$tier1Query.") as tier1, (".$tier2Query.") as tier2, (".$tier3Query.") as tier3, (".$tier4Query.") as tier4, (".$tier5Query.") as tier5 from `Sub-Experiences` exp where `DateEntered` >= '".$thisquarter."' GROUP BY  `GameID` ORDER BY COUNT(  `GameID` ) DESC LIMIT 10";
	if ($result = $mysqli->query("select * from `Sub-Experiences` exp where `DateEntered` >= '".$thisquarter."' GROUP BY  `GameID` ORDER BY COUNT(  `GameID` ) DESC LIMIT 6")) {
		while($row = mysqli_fetch_array($result)){
			$games[] = GetGame($row["GameID"], $mysqli);
		}
	}
	Close($mysqli, $result);
	
	return $games;
}

function GetBestExperiencesCategory(){
	$games= array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Games` ORDER BY `Tier1` DESC LIMIT 15")) {
		while($row = mysqli_fetch_array($result)){
			$games[] = new Game($row["ID"], 
					$row["GBID"],
					$row["Title"],
					$row["Rated"],
					$row["Released"],
					$row["Genre"],
					$row["Platforms"],
					$row["Year"],
					$row["ImageLarge"],
					$row["ImageSmall"],
					$row["Highlight"],
					$row["Publisher"],
					$row["Developer"],
					$row["Alias"],
					$row["Theme"],
					$row["Franchise"],
					$row["Similar"],
					$row["Tier1"],
					$row["Tier2"],
					$row["Tier3"],
					$row["Tier4"],
					$row["Tier5"]
					);
		}
	}
	shuffle($games);
	$subgames[] = $games[0];
	$subgames[] = $games[1];
	$subgames[] = $games[2];
	$subgames[] = $games[3];
	$subgames[] = $games[4];
	$subgames[] = $games[5];
	Close($mysqli, $result);
	
	return $subgames;
}

function GetBestExperiences(){
	$game= array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Games` ORDER BY `Tier1` DESC LIMIT 15")) {
		while($row = mysqli_fetch_array($result)){
				$game[] = new Game($row["ID"], 
					$row["GBID"],
					$row["Title"],
					$row["Rated"],
					$row["Released"],
					$row["Genre"],
					$row["Platforms"],
					$row["Year"],
					$row["ImageLarge"],
					$row["ImageSmall"],
					$row["Highlight"],
					$row["Publisher"],
					$row["Developer"],
					$row["Alias"],
					$row["Theme"],
					$row["Franchise"],
					$row["Similar"],
					$row["Tier1"],
					$row["Tier2"],
					$row["Tier3"],
					$row["Tier4"],
					$row["Tier5"]
					);
		}
	}
	Close($mysqli, $result);
	
	return $game;
}

function GetExperiencedUsersCategory(){
	$users = array();
	$mysqli = Connect();
	$thisquarter = date('Y-m-d', strtotime("now -3 days") );
	if ($result = $mysqli->query("select * from `Sub-Experiences` exp, `Users` usr where usr.`ID` = exp.`UserID` and usr.`Access` != 'Journalist' and usr.`Access` != 'Authenticated' and exp.`DateEntered` >= '".$thisquarter."' GROUP BY  `UserID` ORDER BY COUNT(  `UserID` ) DESC LIMIT 6")) {
		while($row = mysqli_fetch_array($result)){
			$users[] = GetUser($row["UserID"], $mysqli);
		}
	}
	if(sizeof($users) < 5){
		//Expand the search
		unset($users);
		$thisquarter = date('Y-m-d', strtotime("now -15 days") );
		if ($result = $mysqli->query("select * from `Sub-Experiences` exp, `Users` usr where usr.`ID` = exp.`UserID` and usr.`Access` != 'Journalist' and usr.`Access` != 'Authenticated' and exp.`DateEntered` >= '".$thisquarter."' GROUP BY  `UserID` ORDER BY COUNT(  `UserID` ) DESC LIMIT 6")) {
			while($row = mysqli_fetch_array($result)){
				$users[] = GetUser($row["UserID"], $mysqli);
			}
		}
	}
	Close($mysqli, $result);
	
	return $users;
}

function GetAnticipatedGames($userid, $limit){
	$users = array();
	$mysqli = Connect();
	$now = date('Y-m-d');
	if ($result = $mysqli->query("select * from `Collections` c, `CollectionGames` cg,  `Games` g where c.`Name` = 'Bookmarked' and c.`ID` = cg.`CollectionID` and c.`OwnerID` = '".$userid."' and cg.`GameID` = g.`ID` and g.`Released` > '".$now."'  ORDER BY g.`Released` ASC LIMIT 0,".$limit)) {
		while($row = mysqli_fetch_array($result)){
			$game = GetGame($row["GameID"], $mysqli);
			
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						$game,
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
			$xps[] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $xps;
}

function GetAnticipatedGamesAbilities($userid, $limit){
	$users = array();
	$mysqli = Connect();
	$now = date('Y-m-d');
	if ($result = $mysqli->query("select * from `Experiences` exp, `Games` g where `UserID` = '".$userid."' and exp.`GameID` = g.`ID` and (g.`Released` > '".$now."' or g.`Year` = '0') and exp.`BucketList` = 'Yes' ORDER BY g.`Released` DESC LIMIT 0,".$limit)) {
		while($row = mysqli_fetch_array($result)){
			$game = GetGame($row["GameID"], $mysqli);
			
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						$game,
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
			$xps[] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $xps;
}

function GetAnticipatedGamesThisYear($userid, $limit){
	$users = array();
	$mysqli = Connect();
	$now = date('Y-m-d');
	$year = date('Y');
	if ($result = $mysqli->query("select * from `Experiences` exp, `Games` g where `UserID` = '".$userid."' and exp.`GameID` = g.`ID` and g.`Released` < '".$now."' and g.`Year` = '".$year."' and exp.`BucketList` = 'Yes' ORDER BY g.`Released` ASC LIMIT 0,".$limit)) {
		while($row = mysqli_fetch_array($result)){
			$game = GetGame($row["GameID"], $mysqli);
			
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						$game,
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
			$xps[] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $xps;
}

function GetAnticipatedGamesInPast($userid, $limit){
	$users = array();
	$mysqli = Connect();
	$now = date('Y-m-d');
	$year = date('Y');
	if ($result = $mysqli->query("select * from `Experiences` exp, `Games` g where `UserID` = '".$userid."' and exp.`GameID` = g.`ID` and g.`Released` < '".$now."' and g.`Year` != '".$year."' and `Year` != '0' and exp.`BucketList` = 'Yes' ORDER BY g.`Released` ASC LIMIT 0,".$limit)) {
		while($row = mysqli_fetch_array($result)){
			$game = GetGame($row["GameID"], $mysqli);
			
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						$game,
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
			$xps[] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $xps;
}

function Get3LatestXPForUser($userid){
	$users = array();
	$mysqli = Connect();
	$thisquarter = date('Y-m-d', strtotime("now -120 days") );
	if ($result = $mysqli->query("select * from `Experiences` exp where `UserID` = '".$userid."' ORDER BY `ExperienceDate` DESC LIMIT 3")) {
		while($row = mysqli_fetch_array($result)){
			$game = GetGame($row["GameID"], $mysqli);
			
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						$game,
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
			$xps[] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $xps;
}

function GetExperiencedUsers(){
	$users = array();
	$mysqli = Connect();
	$thisquarter = date('Y-m-d', strtotime("now -3 days") );
	if ($result = $mysqli->query("select * from `Sub-Experiences` exp, `Users` usr where usr.`ID` = exp.`UserID` and usr.`Access` != 'Journalist' and usr.`Access` != 'Authenticated' and exp.`DateEntered` >= '".$thisquarter."' GROUP BY  `UserID` ORDER BY COUNT(  `UserID` ) DESC LIMIT 15")) {
		while($row = mysqli_fetch_array($result)){
			$users[] = GetUser($row["UserID"], $mysqli);
		}
	}
	Close($mysqli, $result);
	
	return $users;
}

function GetMyWeaveFilterBy($userid, $filter, $sort){
	$myweave= array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Experiences` exp, `Games` gms where `UserID` = '".$userid."' and exp.`GameID` = gms.`ID` and (".$filter.") order by exp.`ID`, ".$sort)) {
		$user = GetUser($userid);
		while($row = mysqli_fetch_array($result)){
			$game = new Game($row["GameID"], 
					$row["GBID"],
					$row["Title"],
					$row["Rated"],
					$row["Released"],
					$row["Genre"],
					$row["Platforms"],
					$row["Year"],
					$row["ImageLarge"],
					$row["ImageSmall"],
					$row["Highlight"],
					$row["Publisher"],
					$row["Developer"],
					$row["Alias"],
					$row["Theme"],
					$row["Franchise"],
					$row["Similar"],
					$row["Tier1"],
					$row["Tier2"],
					$row["Tier3"],
					$row["Tier4"],
					$row["Tier5"]
					);
			
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						$game,
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
				
						
			$myweave[] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $myweave;
}

function GetMyWeaveSortBy($userid, $sort){
	$myweave= array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select exp.*, gms.* from `Experiences` exp, `Games` gms where exp.`UserID` = '".$userid."' and exp.`GameID` = gms.`ID` and exp.`Tier` > 0 order by ".$sort)) {
		$user = GetUser($userid, $mysqli);
		while($row = mysqli_fetch_array($result)){			
			$game = new Game($row["GameID"], 
					$row["GBID"],
					$row["Title"],
					$row["Rated"],
					$row["Released"],
					$row["Genre"],
					$row["Platforms"],
					$row["Year"],
					$row["ImageLarge"],
					$row["ImageSmall"],
					$row["Highlight"],
					$row["Publisher"],
					$row["Developer"],
					$row["Alias"],
					$row["Theme"],
					$row["Franchise"],
					$row["Similar"],
					$row["Tier1"],
					$row["Tier2"],
					$row["Tier3"],
					$row["Tier4"],
					$row["Tier5"]
					);
			
			$experience = new Experience($row["exp.ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						$game,
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($userid, $game->_id, 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($userid, $game->_id, 'Watched', $mysqli);
						
			$myweave[] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $myweave;
}

function GetExperienceForGame($gameid, $pconn = null){
	$exp = array();
	$mysqli = Connect($pconn);
	if ($result = $mysqli->query("select * from `Experiences` where `GameID` = '".$gameid."' and `Tier` != 0 order by `Tier`")) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
				
			$exp[] = $experience;

		}
	}
    if($pconn == null)
	   Close($mysqli, $result);
	
	return $exp;
}

function GetExperienceForUser($userid){
	$exp = array();
    $mysqli = Connect();
	$user = GetUser($userid, $mysqli);
	if ($result = $mysqli->query("select * from `Experiences` where `UserID` = '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
				
			$exp[] = $experience;

		}
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetExperienceForUserSurfaceLevel($userid, $gameid, $pconn = null){
	$mysqli = Connect($pconn);
	if ($result = $mysqli->query("select * from `Experiences` where `UserID` = '".$userid."' and `GameID` = '".$gameid."'")) {
		while($row = mysqli_fetch_array($result)){
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
		}
	}
    if($pconn == null)
	   Close($mysqli, $result);
	
	return $experience;
}

function GetExperienceForUserSubset($userid, $subset, $pconn = null){
	$exp = array();
    $mysqli = Connect($pconn);
	$user = GetUser($userid, $mysqli);
	if ($result = $mysqli->query("select * from `Experiences` where `UserID` = '".$userid."' and `Tier` > 0 ORDER BY `ID` DESC LIMIT ".$subset.",20")) {
		while($row = mysqli_fetch_array($result)){
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
				
			$exp[] = $experience;

		}
	}
    if($pconn == null)
	   Close($mysqli, $result);
	
	return $exp;
}

function GetExperienceForUserComplete($userid, $gameid, $pconn = null){
	$mysqli = Connect($pconn);
	if ($result = $mysqli->query("select * from `Experiences` where `UserID` = '".$userid."' and `GameID` = '".$gameid."'")) {
		while($row = mysqli_fetch_array($result)){
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						IsGameBookmarkedFromCollection($gameid, $mysqli),
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
		}
	}
    if($pconn == null)
	   Close($mysqli, $result);
	
	return $experience;
}

function GetExperienceForUserCompleteOrEmptyGame($userid, $gameid, $pconn = null){
	$mysqli = Connect($pconn);
	$missing = true;
	if ($result = $mysqli->query("select * from `Experiences` where `UserID` = '".$userid."' and `GameID` = '".$gameid."'")) {
		while($row = mysqli_fetch_array($result)){
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						IsGameBookmarkedFromCollection($gameid, $mysqli),
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
			$missing = false;
		}
	}
	
	if($missing){
		$experience = new Experience($row["ID"],
						'',
						'',
						'',
						'',
						$gameid,
						GetGame($gameid, $mysqli),
						'',
						'',
						'',
						'',
						'',
						IsGameBookmarkedFromCollection($gameid, $mysqli),
						'',
						'');
	}
	
    if($pconn == null)
	   Close($mysqli, $result);
	
	return $experience;
}

function GetExperienceForFeed($gameid, $filter, $pconn = null){
	$exp = array();
	$mysqli = Connect($pconn);
	if ($result = $mysqli->query("select * from `Experiences` where `GameID` = '".$gameid."' and ".$filter)) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
				
			$exp[] = $experience;

		}
	}
    if($pconn == null)
	   Close($mysqli, $result);
	
	return $exp;
}

function GetMyUsersXPForGame($gameid, $userid){
	$exp = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select exp.*, exp.ID as 'THEEXPID', gms.*, usr.`First`, usr.`Last` from `Experiences` exp, `Games` gms, `Users` usr where exp.`Quote` != '' and  exp.`UserID` in (select `Celebrity` from `Connections` where `Fan` = '".$userid."') and exp.`GameID` = '".$gameid."' and gms.`ID` = '".$gameid."' and exp.`UserID` = usr.`ID` and usr.`Access` != 'Journalist' and usr.`Access` != 'Authenticated' order by exp.`Tier` ASC")) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["THEEXPID"],
						$user->_first,
						$user->_last,
						$user,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
			if(sizeof($experience->_playedxp) > 0 || sizeof($experience->_watchedxp) > 0)
				$exp[$row["THEEXPID"]] = $experience;

		}
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetMyTiersByYear($userid, $year){
	$mysqli = Connect();
	$query = "SELECT e.`Tier` FROM `Experiences` e, `Games`g WHERE e.`GameID` = g.`ID` and e.`UserID` = '".$userid."' and g.`Year` = '".$year."'";
	$tierData = array(); 
	$tierData[1]=0; 
	$tierData[2]=0; 
	$tierData[3]=0; 
	$tierData[4]=0; 
	$tierData[5]=0;
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			if($row["Tier"] == 1)
				$tierData[1] = $tierData[1] + 1;
			else if($row["Tier"] == 2)
				$tierData[2] = $tierData[2] + 1;
			else if($row["Tier"] == 3)
				$tierData[3] = $tierData[3] + 1;
			else if($row["Tier"] == 4)
				$tierData[4] = $tierData[4] + 1;
			else if($row["Tier"] == 5)
				$tierData[5] = $tierData[5] + 1;
		}
	}
	Close($mysqli, $result);
	return $tierData;
}

function GetMyTiersByGenre($userid, $genre){
	$mysqli = Connect();
	$query = "SELECT e.`Tier` FROM `Experiences` e, `Games`g WHERE e.`GameID` = g.`ID` and e.`UserID` = '".$userid."' and g.`Genre` = '".$genre."'";
	$tierData = array(); 
	$tierData[1]=0; 
	$tierData[2]=0; 
	$tierData[3]=0; 
	$tierData[4]=0; 
	$tierData[5]=0;
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			if($row["Tier"] == 1)
				$tierData[1] = $tierData[1] + 1;
			else if($row["Tier"] == 2)
				$tierData[2] = $tierData[2] + 1;
			else if($row["Tier"] == 3)
				$tierData[3] = $tierData[3] + 1;
			else if($row["Tier"] == 4)
				$tierData[4] = $tierData[4] + 1;
			else if($row["Tier"] == 5)
				$tierData[5] = $tierData[5] + 1;
		}
	}
	Close($mysqli, $result);
	return $tierData;
}

function GetUserTiersForGame($gameid){
	$mysqli = Connect();
	$query = "SELECT e.`Tier` FROM `Games` g, `Experiences` e, `Users` u where g.`ID` = '".$gameid."' and g.`ID` = e.`GameID` and e.`UserID` = u.`ID` and (u.`Access` = 'User' or u.`Access` = 'Admin')";
	$tierData = array(); 
	$tierData[1]=0; 
	$tierData[2]=0; 
	$tierData[3]=0; 
	$tierData[4]=0; 
	$tierData[5]=0;
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			if($row["Tier"] == 1)
				$tierData[1] = $tierData[1] + 1;
			else if($row["Tier"] == 2)
				$tierData[2] = $tierData[2] + 1;
			else if($row["Tier"] == 3)
				$tierData[3] = $tierData[3] + 1;
			else if($row["Tier"] == 4)
				$tierData[4] = $tierData[4] + 1;
			else if($row["Tier"] == 5)
				$tierData[5] = $tierData[5] + 1;
		}
	}
	return $tierData;
}

function GetCriticTiersForGame($gameid){
	$mysqli = Connect();
	$query = "SELECT e.`Tier` FROM `Games` g, `Experiences` e, `Users` u where g.`ID` = '".$gameid."' and g.`ID` = e.`GameID` and e.`UserID` = u.`ID` and (u.`Access` = 'Journalist' or u.`Access` = 'Authenticated')";
	$tierData = array(); 
	$tierData[1]=0; 
	$tierData[2]=0; 
	$tierData[3]=0; 
	$tierData[4]=0; 
	$tierData[5]=0;
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			if($row["Tier"] == 1)
				$tierData[1] = $tierData[1] + 1;
			else if($row["Tier"] == 2)
				$tierData[2] = $tierData[2] + 1;
			else if($row["Tier"] == 3)
				$tierData[3] = $tierData[3] + 1;
			else if($row["Tier"] == 4)
				$tierData[4] = $tierData[4] + 1;
			else if($row["Tier"] == 5)
				$tierData[5] = $tierData[5] + 1;
		}
	}
	Close($mysqli, $result);
	return $tierData;
}

function GetFollowingTiersForGame($gameid, $userid){
	$mysqli = Connect();
	$query = "SELECT e.`Tier` FROM `Games` g, `Experiences` e, `Users` u where g.`ID` = '".$gameid."' and g.`ID` = e.`GameID` and e.`UserID` in (select `Celebrity` from `Connections` where `Fan` = '".$userid."') and e.`UserID` = u.`ID`";
	$tierData = array(); 
	$tierData[1]=0; 
	$tierData[2]=0; 
	$tierData[3]=0; 
	$tierData[4]=0; 
	$tierData[5]=0;
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			if($row["Tier"] == 1)
				$tierData[1] = $tierData[1] + 1;
			else if($row["Tier"] == 2)
				$tierData[2] = $tierData[2] + 1;
			else if($row["Tier"] == 3)
				$tierData[3] = $tierData[3] + 1;
			else if($row["Tier"] == 4)
				$tierData[4] = $tierData[4] + 1;
			else if($row["Tier"] == 5)
				$tierData[5] = $tierData[5] + 1;
		}
	}
	Close($mysqli, $result);
	return $tierData;
}

function GetTiersForGameWithDate($gameid){
	$mysqli = Connect();
	$query = "SELECT e.`Tier`, u.`Birthdate`, g.`Year` FROM `Games` g, `Experiences` e, `Users` u where g.`ID` = '".$gameid."' and g.`ID` = e.`GameID` and e.`UserID` = u.`ID` and (u.`Access` = 'User' or u.`Access` = 'Admin' or u.`Access` = 'Authenticated')";
	$tierData = array(); 
	$i = 0;
	while($i < 26){ $tierData[$i] = 0;  $i++; }
	$gameyear = -1;
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$i = -1;
			if($gameyear == -1)
				$gameyear = $row['Year'];
			
			$birthyear = explode("-",$row['Birthdate']);
			$diff = $gameyear - $birthyear[0];
			if($diff <= 10){
				$i = 0;
			}else if($diff <= 20){
				$i = 5;
			}else if($diff <= 30){
				$i = 10;
			}else if($diff <= 40){
				$i = 15;
			}else if($diff <= 50){
				$i = 20;
			}
			
			if($i != -1){
				if($row["Tier"] == 1)
					$tierData[$i + 1] = $tierData[$i + 1] + 1;
				else if($row["Tier"] == 2)
					$tierData[$i + 2] = $tierData[$i + 2] + 1;
				else if($row["Tier"] == 3)
					$tierData[$i + 3] = $tierData[$i + 3] + 1;
				else if($row["Tier"] == 4)
					$tierData[$i + 4] = $tierData[$i + 4] + 1;
				else if($row["Tier"] == 5)
					$tierData[$i + 5] = $tierData[$i + 5] + 1;
			}
		}
	}
	Close($mysqli, $result);
	return $tierData;
	
}

function GetAverageAgePlayed($gameid){
	$mysqli = Connect();
	$query = "SELECT u.`Birthdate`, g.`Year` FROM `Games` g, `Sub-Experiences` e, `Users` u where g.`ID` = '".$gameid."' and g.`Year` != 0 and g.`ID` = e.`GameID` and e.`Archived` = 'No' and e.`Type` = 'Played' and e.`UserID` = u.`ID` and (u.`Access` = 'User' or u.`Access` = 'Admin' or u.`Access` = 'Authenticated')";
	$gameyear = -1;
	$i = 0;
	$avg = 0;
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			if($gameyear == -1)
				$gameyear = $row['Year'];
			
			if($gameyear != 0){
				$birthyear = explode("-",$row['Birthdate']);
				$diff = $gameyear - $birthyear[0];
				$avg = $avg + $diff;
				$i++;
			}
		}
	}
	if($i == 0)
		$finalAvg = -1;
	else
		$finalAvg = round($avg / $i);
	
	Close($mysqli, $result);
	return $finalAvg;
}

function GetGameCompletion($gameid){
	$mysqli = Connect();
	$query = "select count(*) as cnt from `Sub-Experiences` where `GameID` = '".$gameid."' and `Completed` >= 100 and `Archived` = 'No' and `Type` = 'Played'";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$finished[] = $row['cnt'];
		}
	}
	
	$query = "select count(*) as cnt from `Sub-Experiences` where `GameID` = '".$gameid."' and `Archived` = 'No' and `Type` = 'Played'";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$finished[] = $row['cnt'];
		}
	}
	Close($mysqli, $result);
	return $finished;
}

function GetGameBookmarked($gameid){
	$mysqli = Connect();
	$query = "SELECT COUNT( * ) AS cnt FROM  `Collections` c,  `CollectionGames` g WHERE g.`GameID` =  '".$gameid."' AND c.`Name` = 'Bookmarked' AND c.`ID` = g.`CollectionID`";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$bookmarked = $row['cnt'];
		}
	}
	Close($mysqli, $result);
	return $bookmarked;
}


function GetOutsideUsersXPForGame($gameid, $userid){
	$exp = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select exp.*, exp.ID as 'THEEXPID', gms.*, usr.`First`, usr.`Last` from `Experiences` exp, `Games` gms, `Users` usr where exp.`Quote` != '' and exp.`UserID` not in (select `Celebrity` from `Connections` where `Fan` = '".$userid."') and usr.`ID` != '".$userid."' and exp.`GameID` = '".$gameid."' and gms.`ID` = '".$gameid."' and exp.`UserID` = usr.`ID` and usr.`Access` != 'Journalist' and usr.`Access` != 'Authenticated' and usr.`Flagged` = 'No' order by exp.`ID` DESC LIMIT 0,30")) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["THEEXPID"],
						$user->_first,
						$user->_last,
						$user,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
			if(sizeof($experience->_playedxp) > 0 || sizeof($experience->_watchedxp) > 0)
				$exp[$row["THEEXPID"]] = $experience;

		}
	}
	Close($mysqli, $result);
	
	return $exp;
}


function GetBestXPForUser($userid, $latestxp){
	$mysqli = Connect();
	if(sizeof($latestxp) > 0){
		foreach($latestxp as $xp){
			$ids[] = $xp->_gameid;
		}
	}
	if(sizeof($ids) == 0)
		$ids[] = 0;
	if ($result = $mysqli->query("select * from `Experiences` where `UserID` = '".$userid."' and `Tier` = '1' and `GameID` not in (".implode(",",$ids).") order by rand() limit 0,3")) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
				
			$exp[] = $experience;
		}
	}
	
	if(sizeof($exp) < 3){
		$size = 3 - sizeof($exp); 
		if ($result = $mysqli->query("select * from `Experiences` where `UserID` = '".$userid."' and `Tier` = '2' and `GameID` not in (".implode(",",$ids).") order by rand() limit 0,".$size)) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
				
			$exp[] = $experience;
		}
	}
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetBestXPForUserAll($userid, $type){
	$mysqli = Connect();
	$user = GetUser($userid, $mysqli);
	$year = date('Y');
	if($type == "year"){
		$query = "select * from `Experiences` e, `Games` g where e.`UserID` = '".$userid."' and (e.`Tier` = '1') and e.`GameID` = g.`ID` and g.`Year` = '".$year."' order by g.`Year` DESC limit 6,506";
	}else if($type == "past"){
		$query = "select * from `Experiences` e, `Games` g where e.`UserID` = '".$userid."' and (e.`Tier` = '1') and e.`GameID` = g.`ID` and g.`Year` < '".$year."' and g.`Year` != 0 order by g.`Year` DESC limit 0,500";
	}else{
		$query = "select * from `Experiences` e, `Games` g where e.`UserID` = '".$userid."' and (e.`Tier` = '1') and e.`GameID` = g.`ID` order by g.`Year` DESC limit 0,6";
	}
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
				
			$exp[] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetWorstXPForUser($userid, $latestxp){
	$mysqli = Connect();
	if(sizeof($latestxp) > 0){
		foreach($latestxp as $xp){
			$ids[] = $xp->_gameid;
		}
	}
	if(sizeof($ids) == 0)
		$ids[] = 0;
	if ($result = $mysqli->query("select * from `Experiences` where `UserID` = '".$userid."' and `Tier` = '5' and `GameID` not in (".implode(",",$ids).") order by rand() limit 0,3")) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
				
			$exp[] = $experience;
		}
	}
	
	if(sizeof($exp) < 3){
		$size = 3 - sizeof($exp); 
		if ($result = $mysqli->query("select * from `Experiences` where `UserID` = '".$userid."' and `Tier` = '4' and `GameID` not in (".implode(",",$ids).") order by rand() limit 0,".$size)) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
				
			$exp[] = $experience;
		}
	}
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetRecentlyBookmarked($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Experiences` where `BucketList`='Yes' and `UserID` = '$userid' order by `ID` DESC limit 0,6")) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = '';
			$experience->_watchedxp = '';
			$experience->_earlyxp = '';
				
			$exp[] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetExperienceForGamePopUsers($gameid, $userid){
	$exp = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select exp.*, gms.*, usr.`First`, usr.`Last` from `Experiences` exp, `Games` gms, `Users` usr where exp.`GameID` = '".$gameid."' and gms.`ID` = '".$gameid."' and exp.`UserID` = usr.`ID` and usr.`Access` != 'Journalist' order by rand() limit 0,10")) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
				
			$exp[] = $experience;

		}
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetExperienceForGameByEvents($gameid, $userid){
	$exp = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select exp.*, gms.*, usr.`First`, usr.`Last` from `Experiences` exp, `Games` gms, `Users` usr where exp.`GameID` = '".$gameid."' and gms.`ID` = '".$gameid."' and (exp.`UserID` = usr.`ID` and usr.`Access` = 'Journalist') order by exp.`Tier` ASC")) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user->_username,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);
				
			$exp[] = $experience;

		}
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetCuratedXPForGame($gameid, $userid){
	$exp = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select exp.* from `Experiences` exp, `Users` usr where exp.`GameID` = '".$gameid."' and exp.`Quote` != '' and exp.`UserID` in (select `Celebrity` from `Connections` where `Fan` = '".$userid."') and (exp.`UserID` = usr.`ID` and usr.`Access` = 'Journalist') order by exp.`Tier` ASC")) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
						if($user->_security == "Authenticated"){
							$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
							$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
						}
				
			$exp[$row["ID"]] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetVerifiedXPForGame($gameid, $userid){
	$exp = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select exp.* from `Experiences` exp, `Users` usr where exp.`GameID` = '".$gameid."' and exp.`Quote` != '' and exp.`UserID` in (select `Celebrity` from `Connections` where `Fan` = '".$userid."') and (exp.`UserID` = usr.`ID` and usr.`Access` = 'Authenticated') order by exp.`Tier` ASC")) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
						if($user->_security == "Authenticated"){
							$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
							$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
						}
				
			$exp[$row["ID"]] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetOutsideCuratedXPForGame($gameid, $userid){
	$exp = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select exp.* from `Experiences` exp, `Users` usr where exp.`GameID` = '".$gameid."' and exp.`Quote` != '' and exp.`UserID` not in (select `Celebrity` from `Connections` where `Fan` = '".$userid."') and (exp.`UserID` = usr.`ID` and usr.`Access` = 'Journalist') order by exp.`Tier` ASC")) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
						if($user->_security == "Authenticated"){
							$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
							$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
						}
				
			$exp[$row["ID"]] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetOutsideVerifiedXPForGame($gameid, $userid){
	$exp = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select exp.* from `Experiences` exp, `Users` usr where exp.`GameID` = '".$gameid."' and exp.`Quote` != '' and exp.`UserID` not in (select `Celebrity` from `Connections` where `Fan` = '".$userid."') and (exp.`UserID` = usr.`ID` and usr.`Access` = 'Authenticated') order by exp.`Tier` ASC")) {
		while($row = mysqli_fetch_array($result)){
			$user = GetUser($row["UserID"], $mysqli);
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
						if($user->_security == "Authenticated"){
							$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
							$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
						}
				
			$exp[$row["ID"]] = $experience;
		}
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetExperienceForUserByGame($userid, $gameid, $pconn = null){
	$experience = "";
	$mysqli = Connect($pconn);
	$user = GetUser($userid, $mysqli);

	if ($result = $mysqli->query("select * from `Experiences` where `UserID` = '".$userid."' and `GameID` = '".$gameid."'")) {
		while($row = mysqli_fetch_array($result)){
			$experience = new Experience($row["ID"],
						$user->_first,
						$user->_last,
						$user,
						$row["UserID"],
						$row["GameID"],
						GetGame($row["GameID"], $mysqli),
						$row["Tier"],
						$row["Quote"],
						$row["ExperienceDate"],
						$row["Link"],
						$row["Owned"],
						$row["BucketList"],
						$row["AuthenticXP"],
						$row['Rank']);
			$experience->_playedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Played', $mysqli);
			$experience->_watchedxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Watched', $mysqli);
			$experience->_earlyxp = GetSubExperiences($row["UserID"], $row["GameID"], 'Early', $mysqli);	

		}
	}
	
	if($experience == ""){
		$experience = new Experience("",
			$user->_first,
			$user->_last,
			$user->_username,
			$userid,
			$gameid,
			GetGame($gameid, $mysqli),
			0,
			"",
			"",
			"",
			"",
			"",
            "",
			'');
	}
	if($pconn == null)
		Close($mysqli, $result);
	
	return $experience;
}

function GetTierCounts($userid, $year){
	$mysqli = Connect();
	$t1 = 0;
	$t5 = 0;
	if ($result = $mysqli->query("select * from `Experiences` exp, `Games` gms where exp.`UserID` = '".$userid."' and (exp.`Tier` = '1' or exp.`Tier` = '5') and exp.`GameID` = gms.`ID` and gms.`Year` = '".$year."'")) {
		while($row = mysqli_fetch_array($result)){
			if($row['Tier'] == '1'){
				$t1 = $t1 + 1;
			}else if($row['Tier'] == '5'){
				$t5 = $t5 + 1;
			}
		}
	}
	$tiers[] = $t1;
	$tiers[] = $t5;
	Close($mysqli, $result);
	
	return $tiers;
}

function GetExperiencedList($userid){
	$exp = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Experiences` where `UserID` = '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			$exp[] = $row["GameID"];
		}
	}
	Close($mysqli, $result);
	
	return $exp;
}

function GetMyLibraryCount($userid){
	$total = 0;
	$mysqli = Connect();
	if ($result = $mysqli->query("select count(*) as cnt from `Experiences` where `UserID` = '".$userid."' and (`Tier` > 0 or `Owned` = 'Yes' or `BucketList` = 'Yes')")) {
		while($row = mysqli_fetch_array($result)){
			$total = $row["cnt"];
		}
	}
	Close($mysqli, $result);
	
	return $total;
}

function GetMyLibrary($userid, $filter, $pos){
	$mysqli = Connect();
	$query = "select `GameID`, `Title`, `Year`, `ExperienceDate`, `ImageSmall`, `GBID`, `Tier`, `ImageLarge` from `Experiences` e, `Games` g where `UserID` = '".$userid."' and (`Tier` > 0 or `Owned` = 'Yes' or `BucketList` = 'Yes') and g.`ID` = e.`GameID` order by `Title`";
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			unset($game);
			$game[] = $row["GameID"];
			$game[] = $row["Title"];
			$game[] = $row["Year"];
			$game[] = $row["ExperienceDate"];
			if($row["ImageSmall"] != "")
				$game[] = $row["ImageSmall"];
			else
				$game[] = $row["ImageLarge"];
			$game[] = $row["GBID"];
			$game[] = $row['Tier'];
			$mylib[] = $game;
		}
	}
	Close($mysqli, $result);
	
	return $mylib;
}


function GetSubExperiences($userid, $gameid, $type, $pconn = null){
	$sexp = array();
	$mysqli = Connect($pconn);
	if ($result = $mysqli->query("select * from `Sub-Experiences` where `UserID` = '".$userid."' and `GameID` = '".$gameid."' and `Type` = '".$type."' order by `ID` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$subexp = "";
			$subexp = new SubExperience($row['ID'], 
				$row['ExpID'], 
				$row['UserID'], 
				$row['GameID'],
				$row['Type'], 
				$row['Source'], 
				$row['Date'], 
				$row['URL'],
				$row['Length'], 
				$row['Thoughts'], 
				$row['ArchiveQuote'], 
				$row['ArchiveTier'], 
				$row['DateEntered'], 
				$row['Completed'], 
				$row['Mode'], 
				$row['Platform'],
				$row['PlatformIDs'],
				$row['DLC'],
				$row['Alpha'],
				$row['Beta'],
				$row['Early Access'],
				$row['Demo'],
				$row['Streamed'],
				$row['Archived'],
				$row['AuthenticXP']);
				
				if($subexp != "")
					$sexp[] = $subexp;
		}
	}
    if($pconn == null)
	   Close($mysqli, $result);
	
	return $sexp;
}


function SubmitCriticExperience($user,$gameid,$quote,$tier,$links){
	$mysqli = Connect();
	$quote = mysqli_real_escape_string($mysqli, $quote);
	$dates = date('Y-m-d');
	
	if (HasUserExperienced($user, $gameid, $mysqli)){
		$result = $mysqli->query("update `Experiences` set `Quote`='$quote',`Tier`='$tier',`ExperienceDate`='$dates',`Link`='$links' where `UserID` = '$user' and `GameID` = '$gameid'");
		$result = $mysqli->query("insert into `Events` (`UserID`,`GameID`,`Event`,`Tier`,`Quote`) values ('$user','$gameid','UPDATE','$tier','$quote')");
		$mysqli->query("insert into `Sub-Experiences` (`UserID`,`GameID`,`ArchiveQuote`,`ArchiveTier`,`Type`,`Completed`,`Date`) values ('$user','$gameid','$quote','$tier','Played','100','$dates')");
	}else{
		$result = $mysqli->query("insert into `Experiences` (`UserID`,`GameID`,`Quote`,`Tier`,`ExperienceDate`,`Link`) values ('$user','$gameid','$quote','$tier','$dates','$links')");
		$result = $mysqli->query("insert into `Events` (`UserID`,`GameID`,`Event`,`Tier`,`Quote`) values ('$user','$gameid','ADDED','$tier','$quote')");
		$mysqli->query("insert into `Sub-Experiences` (`UserID`,`GameID`,`ArchiveQuote`,`ArchiveTier`,`Type`,`Completed`,`Date`) values ('$user','$gameid','$quote','$tier','Played','100','$dates')");
	}
	AddCriticCardsToBookmarkedUsers($gameid);
	//CalculateGameTierData($gameid);
	//CalculateWeave($user);
	Close($mysqli, $result);
}

function SaveXP($user,$gameid,$quote,$tier,$quarter, $year,$link){
	$mysqli = Connect();
	if($quote != '')
		$quote = mysqli_real_escape_string($mysqli, $quote);
	$newXP = "true";
	
	$quickxp = GetExperienceForUserSurfaceLevel($user, $gameid, $mysqli);
	if($quickxp != ''){
		if($quote == '')
			$quote = mysqli_real_escape_string($mysqli, $quickxp->_quote);
		if($tier <= 0)
			$tier = $quickxp->_tier;
		$newXP = "false";
	}
	
	
	if($quarter == 'q1')
		$dates = $year."-01-01";
	else if($quarter == "q2")
		$dates = $year."-04-01";
	else if($quarter == "q3")
		$dates = $year."-07-01";
	else if($quarter == "q4")
		$dates = $year."-10-01";
	else if($quarter == "q0")
		$date = $year."-00-00";
	
	if($_SESSION['logged-in']->_security == "Authenticated")
		$authentic = "Yes";
	else
		$authentic = "No";
		
	if($newXP == "false"){
		$update = "update `Experiences` set `Quote`='$quote',`Tier`='$tier',`ExperienceDate`='$dates',`Link`='$link',`AuthenticXP`='$authentic' where `UserID` = '$user' and `GameID` = '$gameid'";
		$result = $mysqli->query($update);
		if($result == '' || $result == false)
			customError('MySQL', mysqli_error($mysqli),'controller_experience','SaveXP - ('.$update.')');
	}else{
		$insert = "insert into `Experiences` (`UserID`,`GameID`,`Quote`,`Tier`,`ExperienceDate`,`Link`,`AuthenticXP`) values ('$user','$gameid','$quote','$tier','$dates','$link','$authentic')";
		$result = $mysqli->query($insert);
		if($result == '' || $result == false)
			customError('MySQL', mysqli_error($mysqli),'controller_experience','SaveXP - ('.$insert.')');
	}
	
	Close($mysqli, $result);
	//CalculateGameTierData($gameid);
}

function UpdateXP($user,$gameid,$quote,$tier,$link,$completed){
	$mysqli = Connect();
	$data = HasUserGivenXP($user, $gameid);
	$quote = mysqli_real_escape_string($mysqli, $quote);
	$quotechanged = false;
	similar_text($data["Quote"], $quote, $percent); 
	if(number_format($percent, 0) <= 85){ $quotechanged = true; }
	
	if($quotechanged && $data['Tier'] != $tier){
		$result = $mysqli->query("insert into `Events` (`UserID`,`GameID`,`Event`,`Tier`,`Quote`) values ('$user','$gameid','UPDATE','$tier','$quote')");
	}else if($quotechanged){
		$result = $mysqli->query("insert into `Events` (`UserID`,`GameID`,`Event`,`Tier`,`Quote`) values ('$user','$gameid','QUOTECHANGED','$tier','$quote')");
	}else if($data['Tier'] != $tier){
		$result = $mysqli->query("insert into `Events` (`UserID`,`GameID`,`Event`,`Tier`,`Quote`) values ('$user','$gameid','TIERCHANGED','$tier','".$data["Tier"].",".$tier."')");
	}
	
	if($_SESSION['logged-in']->_security == "Authenticated")
		$authentic = "Yes";
	else
		$authentic = "No";

	$update = "update `Experiences` set `Quote`='$quote',`Tier`='$tier',`Link`='$link',`AuthenticXP`='$authentic' where `UserID` = '$user' and `GameID` = '$gameid'";
	$result = $mysqli->query($update);
	if($result == '' || $result == false){
		customError('MySQL', mysqli_error($mysqli),'controller_experience','UpdateXP - ('.$update.')');
	}else{
		if($completed > 0 && $completed != ''){
			$subupdate = "update `Sub-Experiences` set `Completed` = '".$completed."' where `UserID` = '".$user."' and `GameID` = '".$gameid."' and `Type` = 'Played' and `Archived` = 'No'";
			$result = $mysqli->query($subupdate);
			if($result == '' || $result == false){
				customError('MySQL', mysqli_error($mysqli),'controller_experience','UpdateXP - ('.$subupdate.')');
			}
		}
	}
	Close($mysqli, $result);
	//CalculateGameTierData($gameid);
}


function SubmitBookmark($user,$gameid,$bucketlist){
	$mysqli = Connect();
	$game = GetGame($gameid);
	$collectionid = DoesCollectionExist('Bookmarked',$user);
	echo $collectionid;
	if($collectionid > 0){
		if($bucketlist == "Yes"){
			$added = AddToCollection($collectionid, $game->_gbid, $user, true);
			if($added > 0){
				$result = $mysqli->query("insert into `Events` (`UserID`,`GameID`,`Event`) values ('$user','$gameid','BUCKETLIST')");
				CheckForNotifications("Bucket",$user,$gameid);
			}
		}else{
			RemoveFromCollection($collectionid, $gameid, $user, true);
		}
	}
	
	Close($mysqli, $result);
}

function SubmitOwned($user,$gameid,$owned){
	$mysqli = Connect();
	$datarow = HasUserExperienced($user, $gameid, $mysqli);
	if ($datarow != false){
		$result = $mysqli->query("update `Experiences` set `Owned`='$owned' where `UserID` = '$user' and `GameID` = '$gameid'");
	}else{
		$result = $mysqli->query("insert into `Experiences` (`UserID`,`GameID`,`Owned`) values ('$user','$gameid','$owned')");
	}
}

function SavePlayedXP($user, $gameid, $quote, $tier, $completed, $quarter, $year, $single, $multi, $platform, $dlc, $alpha, $beta, $earlyaccess, $demo, $streamed){
	$mysqli = Connect();
	$completed = str_replace("%","",$completed);
	$newXP = "true";
	
	$quickxp = GetExperienceForUserComplete($user, $gameid, $mysqli);
	if(sizeof($quickxp->_playedxp) > 0){
		if($quote == '')
			$quote = $quickxp->_quote;
		if($tier <= 0)
			$tier = $quickxp->_tier;
		if($completed == '')
			$completed = $quickxp->_playedxp[0]->_completed;
		if($quarter == '')
			$quarter = $quickxp->_playedxp[0]->_quarter;
		if($year == '')
			$year = $quickxp->_playedxp[0]->_year;
		if($single == '' && $multi == '')
			$modesplayed = $quickxp->_playedxp[0]->_mode;
		if($dlc == '')
			$dlc = $quickxp->_dlc;
		if($alpha == '')
			$alpha = $quickxp->_alpha;
		if($beta == '')
			$beta = $quickxp->_beta;
		if($earlyaccess == '')
			$earlyaccess = $quickxp->_earlyaccess;
		if($demo == '')
			$demo = $quickxp->_demo;
		if($streamed == '')
			$streamed = $quickxp->_streamed;
		$newXP = "false";
	}
	
	
	$quote = mysqli_real_escape_string($mysqli, $quote);
	
	if($completed == "Multiple Playthroughs"){
		$completed = "101";
	}
	
	if($single && $multi)
		$modesplayed = "Single and Multiplayer";
	else if($single)
		$modesplayed = "Single Player";
	else if($mulit)
		$modesplayed = "Multiplayer";
		
	$platformids = GetPlatformIDs($platform);
	if(sizeof($platform) > 0)
		$platform = implode("\n", $platform);
	
	if($quarter == 'q1')
		$date = $year."-01-01";
	else if($quarter == "q2")
		$date = $year."-04-01";
	else if($quarter == "q3")
		$date = $year."-07-01";
	else if($quarter == "q4")
		$date = $year."-10-01";
	else if($quarter == "q0")
		$date = $year."-00-00";
		
	if (sizeof($quickxp->_playedxp) == 0){
		$insert = "insert into `Sub-Experiences` (`UserID`,`ExpID`,`GameID`,`ArchiveQuote`,`ArchiveTier`,`Type`,`Completed`,`Date`,`Mode`,`Platform`,`PlatformIDs`,`DLC`,`Alpha`,`Beta`,`Early Access`,`Demo`,`Streamed`) values ('$user','$expid','$gameid','$quote','$tier','Played','$completed','$date', '$modesplayed', '$platform', '$platformids', '$dlc', '$alpha', '$beta', '$earlyaccess', '$demo', '$streamed')";
		$result = $mysqli->query($insert);
		if($result == '' || $result == false){
			customError('MySQL', mysqli_error($mysqli),'controller_experience','SavePlayedXP - ('.$insert.')');
		}else{
			CreateEventForPlayedXP(false, null, $completed, $user, $gameid, $tier, $quote);
		}
	}else{
		$update = "update `Sub-Experiences` set `Archived`='Yes' where `GameID` = '".$gameid."' and `UserID` = '".$user."' and `Type` = 'Played'";
		$result = $mysqli->query($update);
		if($result == '' || $result == false){
			customError('MySQL', mysqli_error($mysqli),'controller_experience','SavePlayedXP - ('.$update.')');
		}else{
			$insert = "insert into `Sub-Experiences` (`UserID`,`ExpID`,`GameID`,`ArchiveQuote`,`ArchiveTier`,`Type`,`Completed`,`Date`,`Mode`,`Platform`,`PlatformIDs`,`DLC`,`Alpha`,`Beta`,`Early Access`,`Demo`,`Streamed`) values ('$user','$expid','$gameid','$quote','$tier','Played','$completed','$date', '$modesplayed', '$platform', '$platformids', '$dlc', '$alpha', '$beta', '$earlyaccess', '$demo', '$streamed')";
			$result = $mysqli->query($insert);
			if($result == '' || $result == false){
				customError('MySQL', mysqli_error($mysqli),'controller_experience','SavePlayedXP - ('.$insert.')');
			}else{
				CreateEventForPlayedXP(true, $data, $completed, $user, $gameid, $tier, $quote);
			}
		}
	}
	Close($mysqli, $result);
	
	return $newXP;
}

function GetSubXPID($userid, $gameid, $mysqli){
	if($result = $mysqli->query("select `ID` from `Sub-Experiences` where `UserID` = '".$userid."' and `GameID` = '".$gameid."' order by `ID` desc limit 0,1")){
		while($row = mysqli_fetch_array($result)){
			$subxpid = $row["ID"];
		}
	}
	return $subxpid;
}

function GetPlatformIDs($platforms){
	$mysqli = Connect();
	foreach($platforms as $platform){ 
		$platform = trim($platform);
		if($platform != ""){
			$platformquery = "select * from `Link_Platforms` where `Name` = '".$platform."'";
			if ($platformresult = $mysqli->query($platformquery)){
				while($platformrow = mysqli_fetch_array($platformresult)){
					$platformID[] = $platformrow['GBID'];
				}
			}
		}
	}
	Close($mysqli, $platformresult);
	
	if(sizeof($platformID) > 0)
		return implode(",",$platformID);
	else	
		return ''; 
}

function CreateEventForPlayedXP($hasPlayedXP, $data, $completed, $user, $gameid, $tier, $quote){
	$mysqli = Connect();
	//$hasXP = HasUserExperienced($user, $gameid);
	$sxpid = GetSubXPID($user, $gameid, $mysqli);
	if($completed == '100' || $completed == '101')
		$mysqli->query("insert into `Events` (`UserID`,`GameID`,`Event`,`Tier`,`Quote`,`S_XPID`) values ('$user','$gameid','FINISHED','$tier','$quote','$sxpid')");
	else if($hasPlayedXP == false)
		$mysqli->query("insert into `Events` (`UserID`,`GameID`,`Event`,`Tier`,`Quote`,`S_XPID`) values ('$user','$gameid','ADDED','$tier','$quote','$sxpid')");
	else
		$mysqli->query("insert into `Events` (`UserID`,`GameID`,`Event`,`Tier`,`Quote`,`S_XPID`) values ('$user','$gameid','UPDATE','$tier','$quote','$sxpid')");
		
	Close($mysqli, $result);
}

function SaveWatchedXP($user, $gameid, $quote, $tier, $url, $source, $length, $quarter, $year){
	$mysqli = Connect();
	$newXP = "true";
	
	$quickxp = GetExperienceForUserSurfaceLevel($user, $gameid, $mysqli);
	if($quickxp != ''){
		if($quote == '')
			$quote = mysqli_real_escape_string($mysqli, $quickxp->_quote);
		if($tier <= 0)
			$tier = $quickxp->_tier;
		$newXP = "false";
	}
	
	$quote = mysqli_real_escape_string($mysqli, $quote);
	
	if($length == "watchedanhourorless")
		$length = "Watched gameplay";
	else if($length == "watchedmultiplehours")
		$length = "Watched gameplay";
	else if($length == "competitiveplay")
		$length = "Watched competitive play";
	else if($length == "speedrun")
		$length = "Watched a speed run";
	else if($length == "completesingleplay")
		$length = "Watched a complete single player playthrough";
	else if($length == "watchedtrailer")
		$length = "Watched trailer(s)";
	else if($length == "watcheddeveloper")
		$length = "Watched developer diary";
	else if($length == "watchedpromotional")
		$length = "Watched promotional gameplay";
	
	if($quarter == 'q1')
		$date = $year."-01-01";
	else if($quarter == "q2")
		$date = $year."-04-01";
	else if($quarter == "q3")
		$date = $year."-07-01";
	else if($quarter == "q4")
		$date = $year."-10-01";
	else if($quarter == "q0")
		$date = $year."-00-00";
	
	
	$url = NormalizeVideoURLs($url);
	$insert = "insert into `Sub-Experiences` (`UserID`,`ExpID`,`GameID`,`ArchiveQuote`,`ArchiveTier`,`Type`,`URL`,`Date`,`Length`,`Source`) values ('$user','$expid','$gameid','$quote','$tier','Watched','$url','$date', '$length', '$source')";
	$result = $mysqli->query($insert);
	if($result == '' || $result == false){
		customError('MySQL', mysqli_error($mysqli),'controller_experience','SaveWatchedXP - ('.$insert.')');
	}else{
		CreateEventForWatchedXP($user, $gameid, $tier, $quote, $url);
	}
	Close($mysqli, $result);
	
	return $newXP;
}

function UpdateWatchedXP($id, $user, $gameid, $url, $source, $length, $quarter, $year){
	$mysqli = Connect();

	$quickxp = GetExperienceForUserSurfaceLevel($user, $gameid, $mysqli);
	$tier = $quickxp->_tier;
	$quote = $quickxp->_quote;
	
	if($length == "watchedanhourorless")
		$length = "Watched gameplay";
	else if($length == "watchedmultiplehours")
		$length = "Watched gameplay";
	else if($length == "competitiveplay")
		$length = "Watched competitive play";
	else if($length == "speedrun")
		$length = "Watched a speed run";
	else if($length == "completesingleplay")
		$length = "Watched a complete single player playthrough";
	else if($length == "watchedtrailer")
		$length = "Watched trailer(s)";
	else if($length == "watcheddeveloper")
		$length = "Watched a developer diary";
	else if($length == "watchedpromotional")
		$length = "Watched promotional gameplay";
	
	if($quarter == 'q1')
		$date = $year."-01-01";
	else if($quarter == "q2")
		$date = $year."-04-01";
	else if($quarter == "q3")
		$date = $year."-07-01";
	else if($quarter == "q4")
		$date = $year."-10-01";
	else if($quarter == "q0")
		$date = $year."-00-00";
	
	$url = NormalizeVideoURLs($url);
	$result = $mysqli->query("update `Sub-Experiences` set `ArchiveQuote`='$quote',`ArchiveTier`='$tier',`URL`='$url',`Date`='$date',`Length`='$length',`Source`='$source' where `ID` = '$id'");
	Close($mysqli, $result);
}

function CreateEventForWatchedXP($user, $gameid, $tier, $quote, $url){
	$mysqli = Connect();
	$sxpid = GetSubXPID($user, $gameid, $mysqli);
	$url = NormalizeVideoURLs($url);
	$insert = "insert into `Events` (`UserID`,`GameID`,`Event`,`Tier`,`Quote`,`URL`,`S_XPID`) values ('$user','$gameid','ADDED','$tier','$quote','$url','$sxpid')";
	$result = $mysqli->query($insert);
	if($result == '' || $result == false){
		customError('MySQL', mysqli_error($mysqli),'controller_experience','CreateEventForWatchedXP - ('.$insert.')');
	}
	Close($mysqli, $result);
}

function HasUserExperienced($userid, $gameid, $pconn = null){
	$mysqli = Connect($pconn);
	$hasexp = false;
	if ($result = $mysqli->query("select `GameID` from `Experiences` where `UserID` = '".$userid."' and `GameID` = '".$gameid."'")) {
		while($row = mysqli_fetch_array($result)){
			$hasexp = true;
		}
	}
    if($pconn == null)
	   Close($mysqli, $result);
	
	return $hasexp;
}

function HasFinished($userid, $gameid){
	$mysqli = Connect();
	$hasexp = false;
	if ($result = $mysqli->query("select * from `Sub-Experiences` where `UserID` = '".$userid."' and `GameID` = '".$gameid."' and ((`Completed` = '100' or `Completed` = '101')  and `Type` = 'Played') or (`Type` = 'Watched' and (`Length` = 'Watched a speed run' or `Length` like 'Watched a complete%')) Limit 0,1")) {
		while($row = mysqli_fetch_array($result)){
			$hasexp = $row["ID"];
		}
	}
	Close($mysqli, $result);
	
	return $hasexp;	
}

function HasUserPlayedXP($userid, $gameid, $completed){
	$mysqli = Connect();
	$hasexp = -1;
	if ($result = $mysqli->query("select * from `Sub-Experiences` where `UserID` = '".$userid."' and `GameID` = '".$gameid."' and `Archived` = 'No' and `Type` = 'Played' ")) {
		while($row = mysqli_fetch_array($result)){
			$hasexp = $row;
		}
	}
	Close($mysqli, $result);
	
	return $hasexp;
}

function HasUserGivenXP($userid, $gameid){
	$mysqli = Connect();
	$hasexp = -1;
	if ($result = $mysqli->query("select * from `Experiences` where `UserID` = '".$userid."' and `GameID` = '".$gameid."' and `Tier` > 0 ")) {
		while($row = mysqli_fetch_array($result)){
			$hasexp = $row;
		}
	}
	Close($mysqli, $result);
	
	return $hasexp;
}

function RemoveEntireExperience($user,$gameid){
	$mysqli = Connect();
	$result = $mysqli->query("Delete from `Experiences` where `UserID` = '$user' and `GameID` = '$gameid'");
	$result = $mysqli->query("Delete from `Sub-Experiences` where `UserID` = '$user' and `GameID` = '$gameid'");
	$result = $mysqli->query("Delete from `Events` where `UserID` = '$user' and `GameID` = '$gameid'");
	Close($mysqli, $result);
}

function RemoveSubExperience($subexpid, $gameid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Sub-Experiences` where `ID` = '".$subexpid."' and `GameID` = '".$gameid."'")) {
		while($row = mysqli_fetch_array($result)){
			$mysqli->query("Delete from `Events` where `UserID` = '$user' and `GameID` = '$gameid' and `Date` = '".$row['DateEntered']."'");
		}
	}
	$mysqli->query("Delete from `Sub-Experiences` where `ID` = '$subexpid' and `GameID` = '$gameid'");
	Close($mysqli, $result);
}

function RemoveEvent($eventid, $userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Events` where `ID` = '".$eventid."' and `UserID` = '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			$gameid = $row['GameID'];
			$mysqli->query("Delete from `Sub-Experiences` where `ID` = '".$row['S_XPID']."'");
		}
	}
	$mysqli->query("Delete from `Events` where `ID` = '$eventid' and `UserID` = '".$userid."'");
	
	//Check if that was all that is left
	$found = false;
	if ($result = $mysqli->query("select * from `Events` where `GameID` = '".$gameid."' and `UserID` = '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			$found = true;
		}
	}
	
	if(!$found){
		$result = $mysqli->query("Delete from `Experiences` where `UserID` = '$user' and `GameID` = '$gameid'");
	}
	
	Close($mysqli, $result);
	
	return $gameid;
}

function SaveJournalEntry($subject, $journal, $gameid){
	$userid = $_SESSION['logged-in']->_id;
	$update = false;
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `LongForm` where `UserID` = '".$userid."' and `GameID` = '".$gameid."'")) {
		while($row = mysqli_fetch_array($result)){
			$update = true;
		}
	}

	if($update)
		$mysqli->query("UPDATE `LongForm` SET `Subject` = '".mysqli_real_escape_string($mysqli, $subject)."', `Body` = '".mysqli_real_escape_string($mysqli, $journal)."' where `UserID` = '".$userid."' and `GameID` = '".$gameid."'");
	else
		$mysqli->query("INSERT INTO `LongForm` (`UserID`, `GameID`, `Subject`, `Body`) VALUES ('".$userid."', '".$gameid."' ,'".mysqli_real_escape_string($mysqli, $subject)."' ,'".mysqli_real_escape_string($mysqli, $journal)."')");

	Close($mysqli, $result);
}

function GetLongFormForUser($gameid, $userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `LongForm` where `UserID` = '".$userid."' and `GameID` = '".$gameid."'")) {
		while($row = mysqli_fetch_array($result)){
			$journal = $row;
		}
	}
	return $journal;
}

?>
