<?php
require_once "includes.php";

function GetLifeBarSize($user){
	$total = $user->_weave->_totalXP;
	if($total >= 100){
		$length = "100%";
	}else if($total >= 75){
		$length =  "80%";
	}else if($total >= 50){
		$length =  "60%";
	}else if($total >= 25){
		$length =  "40%";
	}else{
		$length =  "25%";
	}
	
	while($total > 0){
		if($total > 500){
			$giantdots++;
			$total = $total - 500;
		}else if($total > 100){
			$bigdots++;
			$total = $total - 100;
		}else if($total > 25){
			$total = $total - 25;
			$littledots++;
		}else{
			$barlength = floor(($total/25) * 100);
			$total = 0;
		}
	}
	$lifebar[] = $length;
	$lifebar[] = $barlength;
	$lifebar[] = $bigdots;
	$lifebar[] = $littledots;
	$lifebar[] = $giantdots;
	
	return $lifebar;
}

function GetUserSkills($userid){
	$mysqli = Connect();
	$myskills = array();
	if ($result = $mysqli->query("SELECT COUNT( * ) AS cnt,  `GenreID` FROM  `Game_Genre` gg WHERE gg.`GBID` IN ( SELECT  `GBID` FROM  `Sub-Experiences` e,  `Games` g WHERE e.`UserID` =  '".$userid."' AND e.`Archived` =  'No' AND e.`Type` =  'Played' AND e.`GameID` = g.`ID`) GROUP BY  `GenreID` ORDER BY  `cnt` DESC LIMIT 0 , 5")){
		while($row = mysqli_fetch_array($result)){
			unset($skill);
			$skill[] = GetGenreByID($row['GenreID'], $mysqli);
			$skill[] = $row['cnt'];
			$myskills[$row['GenreID']] = $skill;
		}
	}
	Close($mysqli, $result);
	return $myskills;
}

function GetBookmarkedCount($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select count(*) as cnt from `Experiences` where `BucketList`='Yes' and `UserID` = '$userid'")) {
		while($row = mysqli_fetch_array($result)){
			$cnt = $row['cnt'];
		}
	}
	Close($mysqli, $result);
	return $cnt;
}

function GetAbilities($userid){
	$user = GetUser($userid);
	$connections = GetConnectedCounts($userid);
	$following = $connections[1];
	$followers = $connections[0];
	$bookmarked = GetBookmarkedCount($userid);
	$agrees = $user->_weave->_totalAgrees;
	
	/* Spy */
	if($following >= 250){
		$spy[] = 5;
		$spy[] = 250;
	}else if($following >= 125){
		$spy[] = 4;
		$spy[] = 250;
	}else if($following >= 75){
		$spy[] = 3;
		$spy[] = 125;
	}else if($following >= 40){
		$spy[] = 2;
		$spy[] = 75;
	}else if($following >= 10){
		$spy[] = 1;
		$spy[] = 40;
	}else{
		$spy[] = 1;
		$spy[] = 10;
	}
	$spy[] = $following;
	$abilities[] = $spy;
	
	/* Leadership */
	if($followers >= 200){
		$leader[] = 5;
		$leader[] = 200;
	}else if($followers >= 100){
		$leader[] = 4;
		$leader[] = 200;
	}else if($followers >= 50){
		$leader[] = 3;
		$leader[] = 100;
	}else if($followers >= 20){
		$leader[] = 2;
		$leader[] = 50;
	}else if($followers >= 5){
		$leader[] = 1;
		$leader[] = 20;
	}else{
		$leader[] = 1;
		$leader[] = 5;
	}
	$leader[] = $followers;
	$abilities[] = $leader;
	
	/* Charm */
	if($agrees >= 1000){
		$charm[] = 5;
		$charm[] = 1000;
	}else if($agrees >= 500){
		$charm[] = 4;
		$charm[] = 1000;
	}else if($agrees >= 200){
		$charm[] = 3;
		$charm[] = 500;
	}else if($agrees >= 75){
		$charm[] = 2;
		$charm[] = 200;
	}else if($agrees >= 25){
		$charm[] = 1;
		$charm[] = 75;
	}else{
		$charm[] = 1;
		$charm[] = 25;
	}
	$charm[] = $agrees;
	$abilities[] = $charm;
	
	/* Tracker */
	if($bookmarked >= 300){
		$tracker[] = 5;
		$tracker[] = 300;
	}else if($bookmarked >= 150){
		$tracker[] = 4;
		$tracker[] = 300;
	}else if($bookmarked >= 75){
		$tracker[] = 3;
		$tracker[] = 150;
	}else if($bookmarked >= 25){
		$tracker[] = 2;
		$tracker[] = 75;
	}else if($bookmarked >= 10){
		$tracker[] = 1;
		$tracker[] = 25;
	}else{
		$tracker[] = 1;
		$tracker[] = 10;
	}
	
	$tracker[] = $bookmarked;
	$abilities[] = $tracker;
	
	
	return $abilities;
}

function GetKnowledgeHighlights($userid){
 	$franchises = GetFranchiseMilestones($userid, 7, "recent");
 	return $franchises;
}

function GetKnowledgeThisYear($userid){
 	$franchises = GetFranchiseMilestones($userid, 50, "year");
 	return $franchises;
}

function GetKnowledgeYearsPast($userid){
 	$franchises = GetFranchiseMilestones($userid, 50, "past");
 	return $franchises;
}

function GetKnowledgeDetails($knowledgeid, $userid){
	$details = GetKnowledgeGames($knowledgeid, $userid);
	return $details;
}

function GetKnowledgeForUser($knowledgeid, $userid){
	$franchise = GetFranchiseMilestoneForUser($knowledgeid, $userid);
	return $franchise;
}

function GetGear($userid){
 	$platforms = GetPlatformMilestones($userid);
 	return $platforms;
}

?>
