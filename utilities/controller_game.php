<?php require_once 'controller_database.php';
require_once 'model_game.php';

function ClearOldSearchCache(){
	$mysqli = Connect();
	$date = date("Y-m-d", strtotime ( '-90 days' ));
	$mysqli->query("delete from `Search_Cache` where `Stamp` <= '".$date."'");
}

function AddImage($gbid){
	$game = "";
	$mysqli = Connect();
	$images = RequestGiantBombImage($gbid);
	$large = $images[0];
	$small = $images[1];
	$mysqli->query("update `Games` set `ImageLarge` = '".$large."', `ImageSmall` = '".$small."' where `ID` = '".$row['ID']."'");	
}

function AddSmallImage($gbid){
	$game = "";
	$mysqli = Connect();
	$images = RequestGiantBombImage($gbid);
	$large = $images[0];
	$small = $images[1];
	$medium = $images[2];
	$mysqli->query("update `Games` set `ImageSmall` = '".$small."' where `GBID` = '".$gbid."'");	
}

function FindGamesSmallImage(){
	$mysqli = Connect();
	$nothing = true;
	if ($result = $mysqli->query("select * from `Games` where `ImageSmall` == '' LIMIT 0,15")) {
		while($row = mysqli_fetch_array($result)){
			AddSmallImage($row["GBID"]);
		}
	}else{
		echo "<h1>Collected All Small Images</h1>";
	}
	
		if ($result = $mysqli->query("select count(*) as c from `Games` where `ImageSmall` == ''")) {
			while($row = mysqli_fetch_array($result)){
				echo $row["c"];
			}
		}
}


function GetGame($gameid){
	$game = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Games` where `ID` = ".$gameid)) {
		while($row = mysqli_fetch_array($result)){
			$game = new Game($row["ID"], 
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
	

	return $game;
}

function GetGameByGBID($gbid){
	$game = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Games` where `GBID` = ".$gbid)) {
		while($row = mysqli_fetch_array($result)){
			if($row["Title"] != ""){
				$game = new Game($row["ID"], 
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
	}

	return $game;
}

function GetGameByGBIDFull($gbid){
	$game = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Games` where `GBID` = ".$gbid)) {
		while($row = mysqli_fetch_array($result)){
			if($row["Title"] != ""){
				$game = new Game($row["ID"], 
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
		
		if($game == ""){
			$game = RequestGameFromGiantBombByID($gbid);
		}
	}

	return $game;
}

function SearchForGame($search){
	$gameresults = array();
	
	$GBgames = RequestGameFromGiantBomb($search);
	
	foreach($GBgames as $gbgame){ $gameresults[] = $gbgame; }
		
	return $gameresults;
}

function AdvancedSearchForGame($search, $platform, $year, $publisher, $developer, $genre, $franchise){
	$mysqli = Connect();
	$games = array();
	if($search != "")
		$query[] = "`Title` LIKE '%".$search."%' ";
	if($platform != "")
		$query[] = "`Platforms` LIKE '%".$platform."%' ";
	if($year != "")
		$query[] = "`Year` = '".$year."' ";
	if($publisher != "")
		$query[] = "`Publisher` LIKE '%".$publisher."%' ";
	if($developer != "")
		$query[] = "`Developer` LIKE '%".$developer."%' ";
	if($genre != "")
		$query[] = "`Genre` LIKE '%".$genre."%' ";
	if($franchise != "")
		$query[] = "`Franchise` LIKE '%".$franchise."%' ";
		
	if ($result = $mysqli->query("select * from `Games` where ".implode(" and ", $query)." LIMIT 0, 50")) {
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
	return $games;
}

function SearchForGameLocalFirst($search){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Games` where `Title` = '".$search."' LIMIT 0,1")) {
		while($row = mysqli_fetch_array($result)){
			if($row["Title"] != ""){
				$game = new Game($row["ID"], 
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
	}
	
	/*if($game == ""){
		$GBgames = RequestGameFromGiantBomb($search);
		$firsttry = false;
		if(sizeof($GBgames) > 0){ $game = $GBgames[0]; }
	}*/

	return $game;
}

function RecentlyReleasedCategory(){
	$mysqli = Connect();
	$games = array();
	$past = date('Y-m-d', strtotime('-45 days'));
	if ($result = $mysqli->query("select * from `Games` where `Released` >= '".$past."' and `Released` <= '".date('Y-m-d')."' ORDER BY `Released` DESC LIMIT 0,4")) {
		while($row = mysqli_fetch_array($result)){
			if($row["Title"] != ""){
				$game = new Game($row["ID"], 
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
				$games[] = $game;
			}
		}
	}

	return $games;
}

function RecentlyReleased(){
	$mysqli = Connect();
	$games = array();
	$past = date('Y-m-d', strtotime('-45 days'));
	if ($result = $mysqli->query("select * from `Games` where `Released` >= '".$past."' and `Released` <= '".date('Y-m-d')."' ORDER BY `Released` DESC LIMIT 0,100")) {
		while($row = mysqli_fetch_array($result)){
			if($row["Title"] != ""){
				$game = new Game($row["ID"], 
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
				$games[] = $game;
			}
		}
	}

	return $games;
}

function RecentReleaseRandom($gameid){
	$mysqli = Connect();
	$past = date('Y-m-d', strtotime('-30 days'));
	if ($result = $mysqli->query("select * from `Games` where `ID` != '".$gameid."' and `Released` >= '".$past."' and `Released` <= '".date('Y-m-d')."' ORDER BY `Highlight` DESC, RAND() LIMIT 1")) {
		while($row = mysqli_fetch_array($result)){
			if($row["Title"] != ""){
				$game = new Game($row["ID"], 
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
	}

	return $game;
}

function NewVideoRandom($gameid){
	$newvideos = GetNewVideos();
	return $newvideos[array_rand($newvideos)];
}

function GetGameFeedByFilter($filter){
	$games = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Games` ".$filter)) {
		while($row = mysqli_fetch_array($result)){
			$feed = array();
			$feed[] = GetExperienceForGame($row['ID']);
			if($row["Title"] != ""){
				$game = new Game($row["ID"], 
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
				$feed[] = $game;
			}
			$games[] = $feed;	
		}
	}

	return $games;
}

function GetGamesByFilter($filter){
	$games = array();
	$mysqli = Connect();
	$result = $mysqli->query("select * from `Games` where ".$filter);
	if ($result) {
		while($row = mysqli_fetch_array($result)){
			if($row["Title"] != ""){
				$game = new Game($row["ID"], 
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
				$games[] = $game;
			}
		}
	}

	return $games;
}

function InsertGame($gbid, $title, $rated, $released, $genre, $platforms, $year, $publisher, $developer, $alias, $theme, $franchise, $similar, $imagelarge, $imagesmall){
	$mysqli = Connect();
	$result = $mysqli->query("insert into `Games` (`GBID`,`Title`,`Rated`,`Released`,`Platforms`,`Year`,`Genre`,`Publisher`,`Developer`,`Alias`,`Theme`,`Franchise`,`Similar`,`ImageLarge`,`ImageSmall`) values ('$gbid','$title','$rated','$released','$platforms','$year','$genre','$publisher','$developer','$alias','$theme','$franchise','$similar','$imagelarge','$imagesmall')");
	$game = GetGameByGBID($gbid);

	return $game;
}

function UpdateGame($gbid, $title, $rated, $released, $genre, $platforms, $year, $publisher, $developer, $alias, $theme, $franchise, $similar){
	$mysqli = Connect();
	$result = $mysqli->query("update `Games` SET `CrawlerUpdated`='2',`Title`='$title',`Rated`='$rated',`Released`='$released',`Platforms`='$platforms',`Year`='$year',`Genre`='$genre',`Publisher`='$publisher',`Developer`='$developer',`Alias`='$alias',`Theme`='$theme',`Franchise`='$franchise',`Similar`='$similar' where `GBID` = '$gbid'") or die;
	//Update Cards based on this game
	$result = $mysqli->query("select * from `Quests` q, `Game` g where g.`GBID` = '$gbid' and g.`ID` = q.`CoreID` and type='ComingSoon'");
	if ($result) {
		while($row = mysqli_fetch_array($result)){
			$result = $mysqli->query("Delete from `Quests` where `ID` = '".$row["q.ID"]."'");
			AddComingSoon($row["q.UserID"], $row["g.ID"]);
		}
	}

}

function FindGamesToBackfill(){
	$mysqli = Connect();
	echo "Start Backfill<br>";
	$i = 0;
	if ($result = $mysqli->query("select `GBID` from `GameImporter` LIMIT 0,1")){
		while($row = mysqli_fetch_array($result)){
			$id = $row['GBID'];
			while($i < 10){
				echo "GBID: ".$id;
				$nothing = true;
				if ($result2 = $mysqli->query("select * from `Games` where `GBID` = '".$id."' LIMIT 0,1")){
					while($row2 = mysqli_fetch_array($result2)){
						if($row2['GBID'] > 0){
							echo " - Already have ".$row2['Title']."<br>";
							$nothing = false;
						}
					}
				}
				
				if($nothing){
					$game = RequestGameFromGiantBombByID($id, '');
					echo " - Adding new game ".$game->_title."<br>";
	 				sleep(5);
 					$i++;
				}
				
				$id++;
			}
		}
	}
	$mysqli->query("update `GameImporter` set `GBID` = '".$id."' ");
	

}


function FindGamesToBeUpdated(){
	$mysqli = Connect();
	$nothing = true;
	if ($result = $mysqli->query("select * from `Games` where `Title` = '' ORDER BY `ID` DESC LIMIT 0,25")){ //`CrawlerUpdated` = 0 LIMIT 0,50")) {
		while($row = mysqli_fetch_array($result)){
			if($nothing){ echo "Starting ID: ".$row["ID"]."<BR>"; }else{ echo "Next ID: ".$row["ID"]."<BR>"; }
			 FullUpdateViaGameID($row['GBID'], $row['Reviewed']);
			 sleep(5);
			 $nothing = false;
		}
	}
	
	//if($nothing){
	//	$mysqli->query("UPDATE `Games` SET `CrawlerUpdated` = 0");
	//	FindGamesToBeUpdated();
	//}

}

function CreateNewReleaseEvent($date){
	$mysqli = Connect();
	echo "select * from `Games` where `Released` = '".$date."' ";
	if ($result = $mysqli->query("select * from `Games` where `Released` = '".$date."'")) {
		while($row = mysqli_fetch_array($result)){
			if(!IsGameReleasedAlready($row["ID"], $mysqli)){
				$mysqli->query("insert into `Events` (`GameID`,`Event`,`Quote`, `Date`) values ('".$row["ID"]."','GAMERELEASE','".$row["Platforms"]."', '".$date." 00:00:00')");
			}
		}
	}

}

function IsGameReleasedAlready($gameid, $mysqli){
	$gamereleased =false;
	if ($result = $mysqli->query("select * from `Events` where `Event` = 'GAMERELEASE' and `GameID` = '".$gameid."' ")) {
		while($row = mysqli_fetch_array($result)){
			if($row["GameID"] != null)
				$gamereleased = true;
		}
	}

	return $gamereleased;
}

function GetGameReleaseYears(){
	$mysqli = Connect();
	$years = array();
	if ($result = $mysqli->query("select * from `Games` where `Year` > 0 GROUP BY `Year` ORDER BY `Year` DESC")) {
		while($row = mysqli_fetch_array($result)){
				$year = array();
				$year[] = $row["Year"];
				if ($result2 = $mysqli->query("select * from `Games` where `Year` = '".$row["Year"]."' and `Released` <= '".date('Y-m-d')."' ORDER BY RAND() LIMIT 1")) {
					while($row2 = mysqli_fetch_array($result2)){
						if($row2["Title"] != ""){
							$game = new Game($row2["ID"], 
								$row2["GBID"],
								$row2["Title"],
								$row2["Rated"],
								$row2["Released"],
								$row2["Genre"],
								$row2["Platforms"],
								$row2["Year"],
								$row2["ImageLarge"],
								$row2["ImageSmall"],
								$row2["Highlight"],
								$row2["Publisher"],
								$row2["Developer"],
								$row2["Alias"],
								$row2["Theme"],
								$row2["Franchise"],
								$row2["Similar"],
								$row["Tier1"],
								$row["Tier2"],
								$row["Tier3"],
								$row["Tier4"],
								$row["Tier5"]
								);
								$year[] = $game;
						}
					}
				}
				$years[] = $year;
		}
	}

	return $years;
}


function GetPlatforms(){
	$mysqli = Connect();
	$platforms = array();
	if ($result = $mysqli->query("select * from `Platforms` ORDER BY `Released` DESC")) {
		while($row = mysqli_fetch_array($result)){
				$platform = array();
				$platform[] = $row["Title"];
				$platform[] = $row["Image"];
				$platform[] = $row["Alias1"];
				$platform[] = $row["Alias2"];
				$platform[] = $row["Alias3"];
				$platform[] = $row["Alias4"];
				$platform[] = $row["Alias5"];
				$platforms[] = $platform;
		}
	}

	return $platforms;
}

function GetGenres(){
	$mysqli = Connect();
	$genre = array();
	if ($result = $mysqli->query("select * from `Genres`")) {
		while($row = mysqli_fetch_array($result)){
				$genre[] = '"'.$row["Type"].'"';
		}
	}

	return $genre;
}

function GetFranchises(){
	$mysqli = Connect();
	$franchise = array();
	if ($result = $mysqli->query("select * from `Franchises` GROUP BY `Franchise`")) {
		while($row = mysqli_fetch_array($result)){
				$franchise[] = $row["Franchise"];
		}
	}

	return $franchise;
}

function GetPublishers(){
	$mysqli = Connect();
	$publisher = array();
	if ($result = $mysqli->query("select * from `Publishers` GROUP BY `Publisher`")) {
		while($row = mysqli_fetch_array($result)){
				$publisher[] = $row["Publisher"];
		}
	}

	return $publisher;
}

function GetDevelopers(){
	$mysqli = Connect();
	$developer = array();
	if ($result = $mysqli->query("select * from `Developers` GROUP BY `Developer`")) {
		while($row = mysqli_fetch_array($result)){
				$developer[] = $row["Developer"];
		}
	}

	return $developer;
}

function MarkGameReviewed($gameid, $year){
	$mysqli = Connect();
	//$result = $mysqli->query("update `Games` set `Reviewed` = 'Yes', `ImageLarge` = 'http://polygonalweave.com/Images/".$year."/".$gameid.".jpg', `ImageSmall` = 'http://polygonalweave.com/Images/".$year."/".$gameid."s.jpg' where `ID` = '".$gameid."'");
	$result = $mysqli->query("update `Games` set `Reviewed` = 'Yes', `ImageLarge` = 'http://lifebar.io/Images/".$year."/".$gameid.".jpg' where `ID` = '".$gameid."'");

}

function MarkGameHighlight($gameid, $highlight){
	$mysqli = Connect();
	$result = $mysqli->query("update `Games` set `Highlight` = '".$highlight."' where `ID` = '".$gameid."'");	

}

function GetHighlighted($date){
	if($date == ""){
		$games = GetGamesByFilter(" `Highlight` = 'Yes' and `Released` <= '".date('Y-m-d')."' ORDER BY RAND() LIMIT 50");
	}else{
		$games = GetGamesByFilter(" `Highlight` = 'Yes' and `Released` >= '".$date."' and `Released` <= '".date('Y-m-d')."' ORDER BY RAND() LIMIT 50");
	}
	return $games;
}

function ConvertPlatformToShortHand($platform){
	$platform = trim($platform);
	if($platform == "PlayStation 3" || $platform == "PlayStation Network (PS3)"){
		return "PS3";
	}else if($platform == "PC"){
		return "PC";	
	}else if($platform == "Genesis"){
		return "GEN";	
	}else if($platform == "Xbox"){
		return "XBX";	
	}else if($platform == "PlayStation 2"){
		return "PS2";	
	}else if($platform == "PlayStation Portable"){
		return "PSP";	
	}else if($platform == "iPhone"){
		return "iOS";	
	}else if($platform == "Browser"){
		return "BRW";	
	}else if($platform == "PlayStation"){
		return "PS1";	
	}else if($platform == "PlayStation 4" || $platform == "PlayStation Network (PS4)"){
		return "PS4";	
	}else if($platform == "Xbox 360" || $platform == "Xbox 360 Games Store"){
		return "360";	
	}else if($platform == "Xbox One"){
		return "XONE";	
	}else if($platform == "Wii" || $platform == "Wii Shop"){
		return "Wii";	
	}else if($platform == "Wii U"){
		return "WiiU";	
	}else if($platform == "NGC"){
		return "NGC";	
	}else if($platform == "Nintendo 64"){
		return "N64";	
	}else if($platform == "Nintendo 3DS" || $platform == "Nintendo 3DS eShop"){
		return "3DS";	
	}else if($platform == "Nintendo DS"){
		return "DS";	
	}else if($platform == "Game Boy Advance"){
		return "GBA";
	}else if($platform == "Nintendo Entertainment System" || $platform == "NES"){
		return "NES";	
	}else if($platform == "Super Nintendo Entertainment System" || $platform == "SNES"){
		return "SNES";	
	}else if($platform == "Commodore 64"){
		return "C64";
	}else if($platform == "Mac"){
		return "MAC";	
	}else if($platform == "Vita" || $platform == "PlayStation Network (Vita)"){
		return "VITA";
	}else if($platform == "PlayStation Network (PSP)"){
		return "PSP";	
	}else{
		return $platform;
	}
		
}

function ConvertDateToEnglish($date){
	$datesplit = explode('-',$date); 
	
	if($datesplit[1] == "1"){ $real = "January ".$datesplit[2]; }
	else if($datesplit[1] == "2"){ $real = "February ".$datesplit[2]; }
	else if($datesplit[1] == "3"){ $real = "March ".$datesplit[2]; }
	else if($datesplit[1] == "4"){ $real = "April ".$datesplit[2]; }
	else if($datesplit[1] == "5"){ $real = "May ".$datesplit[2]; }
	else if($datesplit[1] == "6"){ $real = "June ".$datesplit[2]; }
	else if($datesplit[1] == "7"){ $real = "July ".$datesplit[2]; }
	else if($datesplit[1] == "8"){ $real = "August ".$datesplit[2]; }
	else if($datesplit[1] == "9"){ $real = "September ".$datesplit[2]; }
	else if($datesplit[1] == "10"){ $real = "October ".$datesplit[2]; }
	else if($datesplit[1] == "11"){ $real = "November ".$datesplit[2]; }
	else if($datesplit[1] == "12"){ $real = "December ".$datesplit[2]; }
	
	return $real;
}

function RequestGameCaptcha(){
	$games = array();
	$games[] = 2012; //Titanfall
	$games[] = 2919; //Thief
	$games[] = 1717; //The Banner Saga
	$games[] = 831; //Warframe
	$games[] = 107; //Tomb Raider
	/*$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;
	$games[] = 2012;*/
	
	$rand = array_rand($games, 1);
	
	return $games[$rand];
}

function GetVideosForGame($gameid){
	$mysqli = Connect();	
	$videos = array();
	if ($result = $mysqli->query("select * from `Videos` where `GameID` = '".$gameid."' Order By `Source`")) {
		while($row = mysqli_fetch_array($result)){
			$videos[] = new GameVideo($row['ID'],$row['GameID'],$row['Source'],$row['URL'],$row['Desc'], $row['SourceID'], $row['Channel'], $row['Thumbnail'], $row['Views'], $row['Length']);
		}
	}

	return $videos;
}

function GetVideo($videoid){
	$mysqli = Connect();	
	if ($result = $mysqli->query("select * from `Videos` where `ID` = '".$videoid."'")) {
		while($row = mysqli_fetch_array($result)){
			$video = new GameVideo($row['ID'],$row['GameID'],$row['Source'],$row['URL'],$row['Desc'], $row['SourceID'], $row['Channel'], $row['Thumbnail'], $row['Views'], $row['Length']);
		}
	}

	return $video;
}

function GetNewVideos(){
	$mysqli = Connect();
	$newvideos = array();
	if ($result = $mysqli->query("select * from `Videos` where `Source` != 'Twitch' ORDER BY `ID` DESC LIMIT 0,50")) {
		while($row = mysqli_fetch_array($result)){
			$video = new GameVideo($row['ID'],$row['GameID'],$row['Source'],$row['URL'],$row['Desc'], $row['SourceID'], $row['Channel'], $row['Thumbnail'], $row['Views'], $row['Length']);
			$newvideos[] = $video;
		}
	}

	return $newvideos;	
}

function InsertVideoForGame($gameid, $source, $url, $desc, $sourceid, $channel, $image, $views, $length){
	$mysqli = Connect();
	$nothing = true;
	if ($result = $mysqli->query("select * from `Videos` where `URL` = '".$url."'")) {
		while($row = mysqli_fetch_array($result)){
			 $nothing = false;
		}
	}
	
	if($nothing)
		$mysqli->query("INSERT INTO `Videos` (`GameID`,`Source`,`URL`,`Desc`,`SourceID`,`Channel`,`Thumbnail`,`Views`,`Length`) VALUES ('".$gameid."','".$source."','".$url."', '".$desc."', '".$sourceid."', '".$channel."', '".$image."', '".$views."', '".$length."')");


}

function LastVideoCheck($gameid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Games` where `ID` = '".$gameid."'")) {
		while($row = mysqli_fetch_array($result)){
			$lastchecked = $row["VideoCheck"];
		}
	}

	return $lastchecked;
}

function UpdateVideoCheck($gameid){
	$mysqli = Connect();
	$date = date("Y-m-d");
	$mysqli->query("UPDATE `Games` SET `VideoCheck` = '".$date."' WHERE `ID` = '".$gameid."'");

}

function GetWatchedForGame($gameid){
	$mysqli = Connect();
	$watched = array();
	if ($result = $mysqli->query("select *, COUNT(*) from `Sub-Experiences` where `GameID` = '".$gameid."' and `Type` LIKE 'Watched' GROUP BY `URL`")) {
		while($row = mysqli_fetch_array($result)){
			$watched[] = new GameWatched($row['ID'],$row['Source'],$row['URL'],$row['COUNT(*)']);
		}
	}

	return $watched;
}

//Last run 2/9/15
//GatherMetaForTable("Franchise", 2);
//GatherMetaForTable("Publisher", 2);
//GatherMetaForTable("Developer", 2);
//GatherMetaForTable("Platforms", 1);

function GatherMetaForTable($meta, $min){
	$mysqli = Connect();
	$metadata = array();
	if($meta == "Platforms"){
		$querymeta = "Console";
	}else{
		$querymeta = $meta;	
	}
	$mysqli->query("TRUNCATE TABLE `".$querymeta."s`");
	if ($result = $mysqli->query("select `".$meta."`, COUNT(*) c from `Games` WHERE `".$meta."` != '' GROUP BY `".$meta."` HAVING c > ".$min)) {
		while($row = mysqli_fetch_array($result)){
			$metaarray = explode("\n", $row[$meta]);
			foreach($metaarray as $met){ 
				if($met != ""){
					$mysqli->query("INSERT INTO `".$querymeta."s` (`".$querymeta."`) VALUES ('".$met."')");
				}
			}
		}
	}

}


?>