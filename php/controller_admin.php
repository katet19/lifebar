<?php
require_once "includes.php";

//RemoveUser(10162);
//ClearEmptyUsers();

//$list = array(8263,8898,8897,8894,8893,9031,9032,9128,9129,9205,9206,9247);
//foreach($list as $old){
	//UpdateUser($old,8146);
//}

//CleanUpWatched();

function CleanUpWatched(){
	$mysqli = Connect();
	if ($result = $mysqli->query("SELECT e.`UserID`, e.`GameID`, (select count(*) from `Sub-Experiences` where `UserID` = e.`UserID` and `GameID` = e.`GameID` and `Type` = 'Watched') as `Watched`, (select count(*) from `Sub-Experiences` where `UserID` = e.`UserID` and `GameID` = e.`GameID` and `Type` = 'Played') as `Played` FROM `Experiences` e WHERE e.`Tier` > 0 order by e.`GameID`, e.`UserID` LIMIT 0,500")) {
		while($row = mysqli_fetch_array($result)){
			if($row['Played'] == 0 && $row['Watched'] > 0){
				echo "Clear Tier for ".$row['UserID']." on ".$row["GameID"]."<br>";
				$mysqli->query("UPDATE `Experiences` SET `Tier` = 0 where `UserID` = '".$row['UserID']."' and `GameID` = '".$row['GameID']."'");
			}
		}
	}
}


function AssociateLikesToEvents(){
	$mysqli = Connect();
	$count = 1;
	if ($result = $mysqli->query("select * from `Liked` where `EventID` = 0 order by `ID` desc limit 0, 500")) {
		while($row = mysqli_fetch_array($result)){
			$found = false;
			echo "<b>".$count."</b>. ".$row['UserQuoted']." ".$row['GameID']." ".$row['UserLiked'];
			if ($result2 = $mysqli->query("select * from `Events` where `GameID` = '".$row['GameID']."' and `UserID` = '".$row['UserQuoted']."' and `Event` in ('TIERCHANGED','QUOTECHANGED','ADDED','UPDATE','FINISHED') and `Date` < '".$row['Date']."' order by `Date` LIMIT 0,1")) {
				if($result2->num_rows == 1){
					while($row2 = mysqli_fetch_array($result2)){
						echo "<br><b>EVENT DATA</b>: ".$row2['UserID']." ".$row2['Event']." ".$row2['Quote'];
						echo "<br>UPDATED: eventID ".$row2['ID']." now linked to liked ".$row['ID'];
						$mysqli->query("update `Liked` set `EventID` = '".$row2['ID']."' where `ID` = '".$row['ID']."'");
						echo "<br><hr>";
						$count++;
						$found = true;
					}
				}
			}
			
			if(!$found){
				echo "<br><span style='color:red;font-weight:bold;'>NOTHING LINKED</span>";
				echo "<br><hr>";
				$mysqli->query("update `Liked` set `EventID` = '-2' where `ID` = '".$row['ID']."'");
				$count++;
			}
		}
	}
}


function AssociateEventsToSubXP($offset){
	$mysqli = Connect();
	$count = 1;
	if ($result = $mysqli->query("select * from `Events` where `Event` in ('TIERCHANGED','QUOTECHANGED','ADDED','UPDATE','FINISHED') and `S_XPID` = 0 order by `ID` desc limit ".$offset.", 500")) {
		while($row = mysqli_fetch_array($result)){
			$found = false;
			echo "<b>".$count."</b>. ".$row['UserID']." ".$row['GameID']." ".$row['Event']." ".$row['Tier'];
			if ($result2 = $mysqli->query("select * from `Sub-Experiences` where `GameID` = '".$row['GameID']."' and `UserID` = '".$row['UserID']."'")) {
				if($result2->num_rows == 1){
					while($row2 = mysqli_fetch_array($result2)){
						echo "<br><b>EVENT DATA</b>: ".$row2['UserID']." ".$row2['Type']." ".$row2['GameID']." ".$row2['ArchiveTier'];
						echo "<br>UPDATED: s_xp ".$row2['ID']." now linked to event ".$row['ID'];
						$mysqli->query("update `Events` set `S_XPID` = '".$row2['ID']."' where `ID` = '".$row['ID']."'");
						echo "<br><hr>";
						$count++;
						$found = true;
					}
				}
			}
			
			if(!$found){
				if ($result2 = $mysqli->query("select * from `Sub-Experiences` where `DateEntered` = '".$row['Date']."'")) {
					while($row2 = mysqli_fetch_array($result2)){
						echo "<br><span style='color:blue;font-weight:bold;'>EVENT DATA BY DATE</span>: ".$row2['UserID']." ".$row2['Type']." ".$row2['GameID']." ".$row2['ArchiveTier'];
						echo "<br>UPDATED: s_xp ".$row2['ID']." now linked to event ".$row['ID'];
						$mysqli->query("update `Events` set `S_XPID` = '".$row2['ID']."' where `ID` = '".$row['ID']."'");
						echo "<br><hr>";
						$count++;
						$found = true;
					}
				}	
			}
			
			if(!$found && $row['Event'] == 'QUOTECHANGED'){
				echo "<br><span style='color:green;font-weight:bold;'>LINK NOT REQUIRED</span>";
				echo "<br><hr>";
				$mysqli->query("update `Events` set `S_XPID` = '-1' where `ID` = '".$row['ID']."'");
				$count++;
				$found = true;
			}
			
			if(!$found){
				echo "<br><span style='color:red;font-weight:bold;'>NOTHING LINKED</span>";
				echo "<br><hr>";
				$mysqli->query("update `Events` set `S_XPID` = '-2' where `ID` = '".$row['ID']."'");
				$count++;
			}
		}
	}
}

//FixGameImageURLS();
//FindMissingNames();
function FindMissingNames(){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Users` where `Username` like '%[CRITIC]%'")) {
		while($row = mysqli_fetch_array($result)){
			$name = str_replace('[CRITIC]','',$row['Username']);
			$name = preg_split('/(?=[A-Z])/', $name, -1, PREG_SPLIT_NO_EMPTY);
			$first = $name[0];
			$last = '';
			if(sizeof($name) > 2){
				$i = 1;
				while($i < sizeof($name)){
					$last = $last.$name[$i];
					$i++;
				}
			}else{
				$last = $name[1];
			}
			
			//echo $first." ".$last."<BR>";
			$mysqli->query("update `Users` set `First` = '".$first."', `Last` = '".$last."', `Birthdate` = '1983-01-01' where `ID` = '".$row['ID']."'");
		}
	}
}

function ClearSearchCache($searchstring){
	$mysqli = Connect();
	$mysqli->query("delete from `Search_Cache` where `Search` like '%".$searchstring."%'");
	Close($mysqli, $result);
}


function FixGameImageURLS(){
	$mysqli = Connect();
	if ($result = $mysqli->query("select `ImageLarge`, `ID`, `ImageSmall` from `Games` where `ImageLarge` like '%polygonalweave%' or `ImageSmall` like '%polygonalweave%'")) {
		while($row = mysqli_fetch_array($result)){
			$small = $row['ImageSmall'];
			$large = $row['ImageLarge'];
			
			$small = str_replace("polygonalweave.com","lifebar.io", $small);
			$large = str_replace("polygonalweave.com","lifebar.io", $large);
			echo $small." || ".$large."<BR>";
			$mysqli->query("update `Games` set `ImageLarge` = '".$large."', `ImageSmall` = '".$small."' where `ID` = '".$row['ID']."'");
		}
	}
}

function FixPlatformMilestones(){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Milestones` where `Category` = 'Platform'")) {
		while($row = mysqli_fetch_array($result)){
			$validation = $row['Validation'];
			
			$newvalidation = str_replace("`PlatformIDs` like '%", "`PlatformIDs` like '%,", $validation);
			$newvalidation = str_replace(",,", ",", $newvalidation);
			$newvalidation = mysqli_real_escape_string($mysqli, $newvalidation);
			$mysqli->query("update `Milestones` set `Validation` = '".$newvalidation."' where `ID` = '".$row['ID']."'");
		}
	}
}

function UpdateUser($old, $truth){
	$mysqli = Connect();
	$mysqli->query("update `Experiences` set `UserID` = '".$truth."' where `UserID` = '".$old."'");
	$mysqli->query("update `Sub-Experiences` set `UserID` = '".$truth."' where `UserID` = '".$old."'");
	$mysqli->query("update `Events` set `UserID` = '".$truth."' where `UserID` = '".$old."'");
	$mysqli->query("update `Connections` set `Celebrity` = '".$truth."' where `Celebrity` = '".$old."'");
	$mysqli->query("update `ImportReview` set `AuthorID` = '".$truth."' where `AuthorID` = '".$old."'");
	$mysqli->query("delete from `Users` where `ID` = '".$old."'");
	Close($mysqli, $result);
}

function ClearEmptyUsers(){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Users` where `Username` = '[CRITIC]'")) {
		while($row = mysqli_fetch_array($result)){
			RemoveUser($row["ID"]);
			$total++;
		}
	}
	Close($mysqli, $result);
	echo "Removed ".$total;
}

function RemoveUser($userid){
	$mysqli = Connect();
	$mysqli->query("Delete from `Connections` where `Fan` = '".$userid."' or `Celebrity` = '".$userid."'");
	$mysqli->query("Delete from `Sub-Experiences` where `Fan` = '".$userid."'");
	$mysqli->query("Delete from `Experiences` where `UserID` = '".$userid."'");
	$mysqli->query("Delete from `Quests` where `UserID` = '".$userid."'");
	$mysqli->query("Delete from `Events` where `UserID` = '".$userid."'");
	$mysqli->query("Delete from `Users` where `ID` = '".$userid."'");
	$mysqli->query("Delete from `ImportReview` where `AuthorID` = '".$userid."'");
	$mysqli->query("Delete from `UserPref` where `UserID` = '".$userid."'");
	$mysqli->query("Delete from `UsersSync` where `UserID` = '".$userid."'");
	Close($mysqli, $result);
}

function MoveWatched(){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Experiences` where `Watched` = 'Yes'")) {
		while($row = mysqli_fetch_array($result)){
			$expid = $row['ID'];
			$userid = $row['UserID'];
			$gameid = $row["GameID"]; 
			$date = $row["ExperienceDate"];
			$source = $row["WatchedSource"];
			$length = $row["WatchedLength"];
			$quote = $row['Quote'];
			$tier = $row['Tier'];
			$mysqli->query("INSERT INTO `Sub-Experiences` (`ExpID`,`UserID`,`GameID`,`Date`,`Source`,`Length`,`ArchiveQuote`,`ArchiveTier`) VALUES ('$expid', '$userid', '$gameid', '$date', '$source', '$length', '$quote', '$tier')");
		}
	}
	Close($mysqli, $result);
}

function MovePlayed(){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Experiences` where `Played` = 'Yes'")) {
		while($row = mysqli_fetch_array($result)){
			$expid = $row['ID'];
			$userid = $row['UserID'];
			$gameid = $row["GameID"]; 
			$date = $row["ExperienceDate"];
			$completed = $row["Completed"];
			$platform = $row["Platform"];
			$mode = $row["ModesPlayed"];
			$quote = $row['Quote'];
			$tier = $row['Tier'];
			$mysqli->query("INSERT INTO `Sub-Experiences` (`ExpID`,`UserID`,`GameID`,`Date`,`Completed`,`Mode`,`Platform`,`Type`, `ArchiveQuote`,`ArchiveTier`) VALUES ('$expid', '$userid', '$gameid', '$date', '$completed', '$mode', '$platform', 'Played', '$quote', '$tier')");
		}
	}
	Close($mysqli, $result);
}


function MarkGameWNoImage($gameid){
	$mysqli = Connect();
	$mysqli->query("Update `Games` set `Reviewed` = 'NoImage' where `ID` = '".$gameid."'");
	Close($mysqli, $result);
}

function GetUnReviewedRSSFeeds(){
	$feeds = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `PublisherReviews` where `Reviewed` = 'No'")) {
		while($row = mysqli_fetch_array($result)){
			$feed = array();
			$feed[] = $row["ID"]; 
			$feed[] = $row["Title"];
			$feed[] = $row["RSS"];
			$feed[] = $row["Publication"];
			$feed[] = $row["Owner"];
			$feeds[] = $feed;
		}
	}
	Close($mysqli, $result);
	return $feeds;
}

function GetDBThreads(){
	$mysqli = Connect();
	if ($result = $mysqli->query("SHOW STATUS WHERE variable_name LIKE 'Threads_%'")) {
		while($row = mysqli_fetch_array($result)){
			$threads[] = $row;
		}
	}
	Close($mysqli, $result);
	return $threads;
}

function GetEmailList(){
	$emails = "";
	$mysqli = Connect();
    $date = date('Y-m-d', strtotime("now -7 days") );
	if ($result = $mysqli->query("select `Email` from `Users` where `Access` != 'Journalist' AND `Established` >= '".$date."'")) {
		while($row = mysqli_fetch_array($result)){
			$emails[] = $row['Email'];
		}
	}
	
	Close($mysqli, $result);
	return $emails;
}

function GetUnReviewedRSSVideo(){
	$myuser = $_SESSION['logged-in'];
	$feeds = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `PublisherVideos` where `Reviewed` = 'No'")) {
		while($row = mysqli_fetch_array($result)){
			unset($feed);
			$feed[] = $row["ID"]; 
			$feed[] = $row["Title"];
			$feed[] = $row["RSS"];
			$feed[] = $row["Publication"];
			$feed[] = $row["Image"];
			$feeds[] = $feed;
		}
	}
	Close($mysqli, $result);
	return $feeds;
}

function GetPurgatoryRSSVideo(){
	$myuser = $_SESSION['logged-in'];
	$feeds = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `PublisherVideos` where `Reviewed` = 'Purgatory'")) {
		while($row = mysqli_fetch_array($result)){
			unset($feed);
			$feed[] = $row["ID"]; 
			$feed[] = $row["Title"];
			$feed[] = $row["RSS"];
			$feed[] = $row["Publication"];
			$feed[] = $row["Image"];
			$feeds[] = $feed;
		}
	}
	Close($mysqli, $result);
	return $feeds;
}

function GetReviewRSSVideo(){
	$myuser = $_SESSION['logged-in'];
	$feeds = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `PublisherVideos` where `Reviewed` = 'Review'")) {
		while($row = mysqli_fetch_array($result)){
			unset($feed);
			$feed[] = $row["ID"]; 
			$feed[] = $row["Title"];
			$feed[] = $row["RSS"];
			$feed[] = $row["Publication"];
			$feed[] = $row["Image"];
			$feeds[] = $feed;
		}
	}
	Close($mysqli, $result);
	return $feeds;
}

function TakeOwnershipOfFeed($id, $myid){
	$mysqli = Connect();
	$result = $mysqli->query("update `PublisherReviews` set `Owner`='".$myid."' where `ID` = '".$id."'");
	Close($mysqli, $result);
}

function SaveRSSFeedAsReviewed($id){
	$mysqli = Connect();
	$result = $mysqli->query("update `PublisherReviews` set `Reviewed`='Yes' where `ID` = '".$id."'");
	Close($mysqli, $result);
}

function SaveRSSVideoAsReviewed($id){
	$mysqli = Connect();
	$result = $mysqli->query("update `PublisherVideos` set `Reviewed`='Yes' where `ID` = '".$id."'");
	Close($mysqli, $result);
}

function SaveRSSVideoAsPurgatory($id){
	$mysqli = Connect();
	$result = $mysqli->query("update `PublisherVideos` set `Reviewed`='Purgatory' where `ID` = '".$id."'");
	Close($mysqli, $result);
}

function SaveRSSVideoAsReview($id){
	$mysqli = Connect();
	$result = $mysqli->query("update `PublisherVideos` set `Reviewed`='Review' where `ID` = '".$id."'");
	Close($mysqli, $result);
}

function ImportVideoToDB($gbid, $feedid){
	$mysqli = Connect();
	$mysqli->query("update `PublisherVideos` set `Reviewed`='Yes' where `ID` = '".$feedid."'");	
	if ($result = $mysqli->query("select * from `PublisherVideos` where `ID` = '".$feedid."' ")) {
		while($row = mysqli_fetch_array($result)){
			if($row["Publication"] != "Gamespot" && $row["Publication"] != "Polygon" && $row["Publication"] != "GiantBomb" && $row["Publication"] != "IGN" && $row["Publication"] != "Joystiq" && $row["Publication"] != "GameInformer"  && $row["Publication"] != "Destructoid" && $row["Publication"] != "GamesRadar"){
				$pub = "YouTube";
			}else{
				$pub = $row["Publication"];
			}
			
			InsertVideoForGame($gbid, $pub, $row["RSS"], $row["Title"], -1, $row["Publication"], $row["Image"], -1, $row['Duration']);
		}
	}
	Close($mysqli, $result);
}

function GetUnReviewedGames(){
	$games = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Games` where `Reviewed` = 'No' Order By `Released` DESC LIMIT 0,100")) {
		while($row = mysqli_fetch_array($result)){
			$game = new Game($row["ID"], 
				$row["GBID"],
				$row["Title"],
				$row["Rated"],
				$row["Released"],
				$row["Genre"],
				$row["Platforms"],
				$row["Year"],
				"",
				"",
				$row["Highlight"],
				$row["Publisher"],
				$row["Developer"],
				$row["Alias"],
				$row["Theme"],
				$row["Franchise"],
				$row["Similar"]
				);
			$games[] = $game;
		}
	}
	Close($mysqli, $result);
	return $games;
}

function GetLatestCrawled(){
	$games = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select `Title` from `Games` where `CrawlerUpdated` = '1' Order By `ID` DESC LIMIT 0,25")) {
		while($row = mysqli_fetch_array($result)){
			$games[]= $row["Title"];
		}
	}
	Close($mysqli, $result);
	return $games;
}

function GetGamesData(){
	$gdata = "";
	$mysqli = Connect();
	$result = $mysqli->query("select count(*) from `Games`");
	while($row = mysqli_fetch_array($result)){
		$gdata[] = $row["count(*)"]; 
	}
	$result = $mysqli->query("select count(*) from `Games` where `Reviewed` = 'No'");
	while($row = mysqli_fetch_array($result)){
		$gdata[] = $row["count(*)"];
		$gdata[] = round(($gdata[1] / $gdata[0]) * 100);
	}
	Close($mysqli, $result);
	return $gdata;
}

function GetUsersData(){
	$udata = "";
	$mysqli = Connect();
	//0 - All users
	$result = $mysqli->query("select count(*) from `Users` where `Access` != 'Journalist'");
	while($row = mysqli_fetch_array($result)){
		$udata[] = $row["count(*)"]; 
	}
	//1 - All Critics
	$result = $mysqli->query("select count(*) from `Users` where `Access` = 'Journalist'");
	while($row = mysqli_fetch_array($result)){
		$udata[] = $row["count(*)"]; 
	}
	//2 - All Users last 30 days
	$date = date('Y-m-d', strtotime("now -30 days") );
	$result = $mysqli->query("select count(*) from `Users` usr, `Sub-Experiences` exp where usr.`Access` != 'Journalist' and exp.`DateEntered` >= '".$date."' and exp.`UserID` = usr.`ID`");
	while($row = mysqli_fetch_array($result)){
		$udata[] = $row["count(*)"]; 
	}
	//3 - All Users last 7 days
	$date = date('Y-m-d', strtotime("now -7 days") );
	$result = $mysqli->query("select count(*) from `Users` usr, `Sub-Experiences` exp where usr.`Access` != 'Journalist' and exp.`DateEntered` >= '".$date."' and exp.`UserID` = usr.`ID`");
	while($row = mysqli_fetch_array($result)){
		$udata[] = $row["count(*)"]; 
	}
	//4 - All Users last 1 day
	$date = date('Y-m-d', strtotime("now -1 days") );
	$result = $mysqli->query("select count(*) from `Users` usr, `Sub-Experiences` exp where usr.`Access` != 'Journalist' and exp.`DateEntered` >= '".$date."' and exp.`UserID` = usr.`ID`");
	while($row = mysqli_fetch_array($result)){
		$udata[] = $row["count(*)"]; 
	}
	Close($mysqli, $result);

	return $udata;
}

function GetExperienceData(){
	$edata = "";
	$mysqli = Connect();
	$result = $mysqli->query("select count(*) from `Experiences`");
	while($row = mysqli_fetch_array($result)){
		$edata[] = $row["count(*)"]; 
	}
	$result = $mysqli->query("select count(*) from `Experiences` where `Tier` = '1'");
	while($row = mysqli_fetch_array($result)){
		$edata[] = $row["count(*)"]; 
	}
	$result = $mysqli->query("select count(*) from `Experiences` where `Tier` = '2'");
	while($row = mysqli_fetch_array($result)){
		$edata[] = $row["count(*)"]; 
	}
	$result = $mysqli->query("select count(*) from `Experiences` where `Tier` = '3'");
	while($row = mysqli_fetch_array($result)){
		$edata[] = $row["count(*)"]; 
	}
	$result = $mysqli->query("select count(*) from `Experiences` where `Tier` = '4'");
	while($row = mysqli_fetch_array($result)){
		$edata[] = $row["count(*)"]; 
	}
	$result = $mysqli->query("select count(*) from `Experiences` where `Tier` = '5'");
	while($row = mysqli_fetch_array($result)){
		$edata[] = $row["count(*)"]; 
	}
	$result = $mysqli->query("select count(*) from `Experiences` where `Link` != ''");
	while($row = mysqli_fetch_array($result)){
		$edata[] = $row["count(*)"]; 
	}
	$result = $mysqli->query("select count(*) from `PublisherReviews` where `Reviewed` = 'No'");
	while($row = mysqli_fetch_array($result)){
		$edata[] = $row["count(*)"]; 
	}
	$result = $mysqli->query("select count(*) from `PublisherVideos` where `Reviewed` = 'No'");
	while($row = mysqli_fetch_array($result)){
		$edata[] = $row["count(*)"]; 
	}
	$result = $mysqli->query("select count(*) from `PublisherVideos` where `Reviewed` = 'Purgatory'");
	while($row = mysqli_fetch_array($result)){
		$edata[] = $row["count(*)"]; 
	}
	$result = $mysqli->query("select count(*) from `PublisherVideos` where `Reviewed` = 'Review'");
	while($row = mysqli_fetch_array($result)){
		$edata[] = $row["count(*)"]; 
	}
	$result = $mysqli->query("select count(*) from `Feedback` where `Reviewed` = 'No'");
	while($row = mysqli_fetch_array($result)){
		$edata[] = $row["count(*)"]; 
	}
	Close($mysqli, $result);
	return $edata;
}

function GetFeedbackData(){
	$fdata = "";
	$mysqli = Connect();
	$result = $mysqli->query("select count(*) from `Feedback` where `Reviewed` = 'No' or `Reviewed` = 'In Progress' or `Reviewed` = 'Approved to be fixed'");
	while($row = mysqli_fetch_array($result)){
		$fdata[] = $row["count(*)"]; 
	}
	
	return $fdata;
}

function SubmitFeedback($feedback,$userid){
	$mysqli = Connect();
	$mysqli->query("insert into `Feedback` (`UserID`,`Feedback`) VALUES ('$userid', '$feedback')");
	Close($mysqli, $result);
}

function GetIGNUnmapped(){
	$mysqli = Connect();
	$myuser = $_SESSION['logged-in'];
	$result = $mysqli->query("select * from `ImportReview` where `GameID` = '0' and `Source` = 'IGN' and `AuthorName` != 'IGN Staff' and `Owner` != -1 LIMIT 0,25");
	while($row = mysqli_fetch_array($result)){
		$reviews[] = $row;
	}
	Close($mysqli, $result);
	return $reviews;
}

function GetImportReported(){
	$mysqli = Connect();
	$result = $mysqli->query("select count(*) from `GamesMapperReport`");
	while($row = mysqli_fetch_array($result)){
		$total[] = $row["count(*)"];
	}
	$result = $mysqli->query("select count(*) from `ImportAudit` i, `GamesMapperReport` r where i.`Type` = 'Steam' and i.`ImportID` = r.`ImportID`");
	while($row = mysqli_fetch_array($result)){
		$total[] = $row["count(*)"];
	}
	Close($mysqli, $result);
	return $total;
}

function GetIGNUnmappedAndNeedsReview(){
	$mysqli = Connect();
	$myuser = $_SESSION['logged-in'];
	$result = $mysqli->query("select * from `ImportReview` where `GameID` = '0' and `Source` = 'IGN' and `AuthorName` != 'IGN Staff' and `Owner` = -1 LIMIT 0,50");
	while($row = mysqli_fetch_array($result)){
		$reviews[] = $row;
	}
	Close($mysqli, $result);
	return $reviews;
}

function IGNRemaining(){
	$mysqli = Connect();
	$myuser = $_SESSION['logged-in'];
	$result = $mysqli->query("select count(*) from `ImportReview` where `GameID` = '0' and `Source` = 'IGN' and `AuthorName` != 'IGN Staff'");
	while($row = mysqli_fetch_array($result)){
		$total = $row["count(*)"];
	}
	Close($mysqli, $result);
	return $total;
}

function GetJoesRewards(){
	$mysqli = Connect();
	$myuser = $_SESSION['logged-in'];
	$result = $mysqli->query("select count(*) from `ImportReview` where `Owner` = '7828'");
	while($row = mysqli_fetch_array($result)){
		$rewards[] = $row["count(*)"];
		$total = $row["count(*)"];
	}
	$left = 8000; //IGNRemaining();
	if($total < $left){
		$next = "PlayStation 4";
		$percent = round(($total / $left)*100, 2);
	}
	
	$rewards[] = $next;
	$rewards[] = $percent;
	
	Close($mysqli, $result);
	return $rewards;
}

function MapReviewToGame($id, $quote, $tier, $links, $gameid, $criticid){
	$mysqli = Connect();
	$game = GetGameByGBIDFull($gameid);
	$result = $mysqli->query("Update `ImportReview` set `GameID` = '".$game->_id."', `Owner` = '7828' where `ID` = '".$id."'");
	Close($mysqli, $result);
	SubmitCriticExperience($criticid,$game->_id,$quote,$tier,$links);
}

function DismissMappingReview($id){
	$mysqli = Connect();
	$result = $mysqli->query("Update `ImportReview` set `GameID` = '-999', `Owner` = '7828' where `ID` = '".$id."'");
	Close($mysqli, $result);
}

function SaveIGNImportForLater($id){
	$mysqli = Connect();
	$result = $mysqli->query("Update `ImportReview` set `Owner` = '-1' where `ID` = '".$id."'");
	Close($mysqli, $result);
}

function GetUserFeedback(){
	$mysqli = Connect();
	$result = $mysqli->query("select * from `Feedback` where `Reviewed` != 'Yes'");
	while($row = mysqli_fetch_array($result)){
		$feedback[] = $row;
	}
	return $feedback;
}

function CheckVersion($myver){
	$mysqli = Connect();
	$result = $mysqli->query("select * from `Version` ORDER BY `ID` DESC LIMIT 0,1");
	while($row = mysqli_fetch_array($result)){
		$version = $row["ID"];
	}
	if($version > $myver){
		echo "UPDATE";
	}else{
		echo "CURRENT";
	}
}

function ManualErrorMessage($msg){
	$subject = "Manual Error";
	$to = "lifebar.fjs78@zapiermail.com";
	SendEmail($to, $subject, $msg);
}

?>
