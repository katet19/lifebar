<?php require_once 'includes.php';

function AddImage($gbid){
	$game = "";
	$mysqli = Connect();
	$images = RequestGiantBombImage($gbid);
	$large = $images[0];
	$small = $images[1];
	$mysqli->query("update `Games` set `ImageLarge` = '".$large."', `ImageSmall` = '".$small."' where `ID` = '".$row['ID']."'");	
	Close($mysqli, $result);
}

function AddSmallImage($gbid){
	$game = "";
	$mysqli = Connect();
	$images = RequestGiantBombImage($gbid);
	$large = $images[0];
	$small = $images[1];
	if($small == "")
		$small = $images[2];
	$mysqli->query("update `Games` set `ImageSmall` = '".$small."' where `GBID` = '".$gbid."'");	
	Close($mysqli, $result);
}

function FindGamesSmallImage(){
	$mysqli = Connect();
	$nothing = true;
	if ($result = $mysqli->query("select * from `Games` where `ImageSmall` = '' LIMIT 0,15")) {
		while($row = mysqli_fetch_array($result)){
			AddSmallImage($row["GBID"]);
		}
	}else{
		echo "<h1>Collected All Small Images</h1>";
	}
	
	if ($result = $mysqli->query("select count(*) as c from `Games` where `ImageSmall` = ''")) {
		while($row = mysqli_fetch_array($result)){
			echo $row["c"];
		}
	}
	Close($mysqli, $result);
}


function GetGame($gameid, $pconn = null){
	$game = "";
	$mysqli = Connect($pconn);
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
	if($pconn == null)
		Close($mysqli, $result);

	return $game;
}

//DONT USE UNLESS YOU KNOW IT EXISTS, THERE IS A BETTER METHOD THAT ADDS
function GetGameByGBID($gbid, $pconn = null){
	$game = "";
	$mysqli = Connect($pconn);
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
	if($pconn == null)
		Close($mysqli, $result);
	
	return $game;
}

function GetGameByGBIDFull($gbid, $pconn = null){
	$game = "";
	$mysqli = Connect($pconn);
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
	if($pconn == null)
		Close($mysqli, $result);
	
	if($game == ""){
		$game = RequestGameFromGiantBombByID($gbid);
	}

	return $game;
}

function SearchForGame($search){
	$gameresults = GetSearchCache($search);
	
	if($gameresults == false){
		$gameresults = RequestGameFromGiantBomb($search);
		AddToSearchCache($search, $gameresults);
	}else{
		
	}

	return $gameresults;
}

function GetSearchCache($search){
	$mysqli = Connect();
	$games = false;
	if ($result = $mysqli->query("select * from `Search_Cache` where `Search` = '".$search."'")) {
		while($row = mysqli_fetch_array($result)){
			$list = $row['Results'];
			$eachgame = explode("||", $list);
			foreach($eachgame as $gamestring){
				$game = explode("~", $gamestring);
				$gamedata = new Game($game[0],
					$game[1], 
					$game[2],
					$game[3],
					$game[4],
					$game[5],
					$game[6],
					$game[7],
					$game[8],
					$game[9],
					$game[10],
					$game[11],
					$game[12],
					$game[13],
					$game[14],
					$game[15],
					$game[16]);
				$games[] = $gamedata;
			}
		}
	}
	Close($mysqli, $result);
	return $games;
}

function AddToSearchCache($search, $games){
	$mysqli = Connect();
	$gamecache = array();
	foreach($games as $game){
		$gs = "";
		$gs = $game->_id;
		$gs = $gs."~".$game->_gbid;
		$gs = $gs."~".mysqli_real_escape_string($mysqli, $game->_title);
		$gs = $gs."~".$game->_rated;
		$gs = $gs."~".$game->_released;
		$gs = $gs."~".mysqli_real_escape_string($mysqli, $game->_genre);
		$gs = $gs."~".mysqli_real_escape_string($mysqli, $game->_platforms);
		$gs = $gs."~".$game->_year;
		$gs = $gs."~".$game->_image;
		$gs = $gs."~".$game->_imagesmall;
		$gs = $gs."~".$game->_highlight;
		$gs = $gs."~".mysqli_real_escape_string($mysqli, $game->_publisher);
		$gs = $gs."~".mysqli_real_escape_string($mysqli, $game->_developer);
		$gs = $gs."~".$game->_alias; mysqli_real_escape_string($mysqli, $game->_alias);
		$gs = $gs."~".$game->_theme;
		$gs = $gs."~".mysqli_real_escape_string($mysqli, $game->_franchise);
		$gs = $gs."~".$game->_similar;
		$gs = $gs."~".$game->_t1;
		$gs = $gs."~".$game->_t2;
		$gs = $gs."~".$game->_t3;
		$gs = $gs."~".$game->_t4;
		$gs = $gs."~".$game->_t5;
		$gamecache[] = $gs;
	}
	$cachestring = implode("||", $gamecache);
	$mysqli->query("insert into `Search_Cache` (`Search`,`Results`) values ('".$search."','".$cachestring."')");
	Close($mysqli, $result);
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
		
	if ($result = $mysqli->query("select * from `Games` where ".implode(" and ", $query)." LIMIT 0, 25")) {
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
	Close($mysqli, $result);
	return $games;
}

function GetDiscoverQuery(){
	$mysqli = Connect();
	$queries = array();
	if($_SESSION['logged-in']->_id == 7){
		if ($result = $mysqli->query("select * from `DiscoverCategories` where `Type` = 'Custom' ORDER BY `ID` DESC LIMIT 0,5")) {
			while($row = mysqli_fetch_array($result)){
				$query[0] = $row['ID'];
				$query[1] = $row['Name'];
				$query[2] = $row['Description'];
				$queries[] = $query;
			}
		}
	}else if($_SESSION['logged-in']->_id != 0){
		if ($result = $mysqli->query("select * from `DiscoverCategories` where `Type` = 'Custom' ORDER BY RAND() LIMIT 0,5")) {
			while($row = mysqli_fetch_array($result)){
				$query[0] = $row['ID'];
				$query[1] = $row['Name'];
				$query[2] = $row['Description'];
				$queries[] = $query;
			}
		}	
	}else{
		if ($result = $mysqli->query("select * from `DiscoverCategories` where `Type` = 'Custom' ORDER BY RAND() LIMIT 0,5")) {
			while($row = mysqli_fetch_array($result)){
				$query[0] = $row['ID'];
				$query[1] = $row['Name'];
				$query[2] = $row['Description'];
				$queries[] = $query;
			}
		}
	}
	Close($mysqli, $result);
	return $queries;
}

function CustomDiscoverQuery($id){
	$mysqli = Connect();
	$games = array();
	if ($result = $mysqli->query("select * from `DiscoverCategories` where `ID` = '".$id."'")) {
		while($row = mysqli_fetch_array($result)){
			$query = $row['Query'];
		}
	}
		
	if ($result = $mysqli->query($query)) {
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
	Close($mysqli, $result);
	return $games;
}

function GetDiscoverCategories(){
	$mysqli = Connect();
	$categoryList = array();
	if ($result = $mysqli->query("select * from `DiscoverCategories` where `Enabled` = 'Yes' and `Type` = 'Highlighted' LIMIT 0,3")) {
		while($row = mysqli_fetch_array($result)){
			$games = array();
			$category = array();
			$query = $row['Query'];
			$custom = $row;
			if ($result2 = $mysqli->query($query)) {
				while($row2 = mysqli_fetch_array($result2)){
					$games[] = new Game($row2["ID"], 
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
						$row2["Tier1"],
						$row2["Tier2"],
						$row2["Tier3"],
						$row2["Tier4"],
						$row2["Tier5"]
						);
				}
			}
			$category[] = $custom;
			$category[] = $games;
			$categoryList[] = $category;
		}
	}
	
	if(sizeof($categoryList) < 3){
		if ($result = $mysqli->query("select * from `DiscoverCategories` where `Enabled` = 'Yes' and `Type` = 'Custom' ORDER BY RAND() LIMIT 0,3")) {
			while($row = mysqli_fetch_array($result)){
				$games = array();
				$category = array();
				$query = $row['Query'];
				$custom = $row;
				if ($result2 = $mysqli->query($query)) {
					while($row2 = mysqli_fetch_array($result2)){
						$games[] = new Game($row2["ID"], 
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
							$row2["Tier1"],
							$row2["Tier2"],
							$row2["Tier3"],
							$row2["Tier4"],
							$row2["Tier5"]
							);
					}
				}
				$category[] = $custom;
				$category[] = $games;
				$categoryList[] = $category;
			}
		}
	}
	
	Close($mysqli, $result);
	return $categoryList;
}

function SearchForGameLocalFirst($search){
	$mysqli = Connect();
	$games = array();
	if ($result = $mysqli->query("select * from `Games` where `Title` like '%".$search."%' LIMIT 0,25")) {
		while($row = mysqli_fetch_array($result)){
			unset($game);
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
	
	Close($mysqli, $result);
	return $games;
}

function RecentlyReleasedCategory(){
	$mysqli = Connect();
	$games = array();
	$past = date('Y-m-d', strtotime('-45 days'));
	if ($result = $mysqli->query("select * from `Games` where `Released` >= '".$past."' and `Released` <= '".date('Y-m-d')."' ORDER BY `Released` DESC LIMIT 0,6")) {
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
	Close($mysqli, $result);
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
	Close($mysqli, $result);
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
	Close($mysqli, $result);
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
			$feed[] = GetExperienceForGame($row['ID'], $mysqli);
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
	Close($mysqli, $result);
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
	Close($mysqli, $result);
	return $games;
}

function InsertGame($gbid, $title, $rated, $released, $genre, $platforms, $year, $publisher, $developer, $alias, $theme, $franchise, $similar, $imagelarge, $imagesmall){
	$mysqli = Connect();
	$result = $mysqli->query("insert into `Games` (`GBID`,`Title`,`Rated`,`Released`,`Platforms`,`Year`,`Genre`,`Publisher`,`Developer`,`Alias`,`Theme`,`Franchise`,`Similar`,`ImageLarge`,`ImageSmall`) values ('$gbid','$title','$rated','$released','$platforms','$year','$genre','$publisher','$developer','$alias','$theme','$franchise','$similar','$imagelarge','$imagesmall')");
	$game = GetGameByGBID($gbid, $mysqli);
	Close($mysqli, $result);
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
	Close($mysqli, $result);
}

function FindGamesToBeUpdated(){
	$mysqli = Connect();
	$nothing = true;
	if ($result = $mysqli->query("select * from `Games` where `CrawlerUpdated` = 0 LIMIT 0,30")) {
		while($row = mysqli_fetch_array($result)){
			if($nothing){ echo "Starting ID: ".$row["ID"]."<BR>"; }
			 UpdateGameFromGiantBombByID($row['GBID'], $row['Reviewed'], $mysqli);
			 $nothing = false;
		}
	}
	
	if($nothing){
		$mysqli->query("UPDATE `Games` SET `CrawlerUpdated` = 0");
		FindGamesToBeUpdated();
	}
	Close($mysqli, $result);
}

function CreateNewReleaseEvent($date){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Games` where `Released` = '".$date."' ")) {
		while($row = mysqli_fetch_array($result)){
			if(!IsGameReleasedAlready($row["ID"])){
				$result = $mysqli->query("insert into `Events` (`GameID`,`Event`,`Quote`, `Date`) values ('".$row["ID"]."','GAMERELEASE','".$row["Platforms"]."', '".$date." 00:00:00')");
			}
		}
	}
	Close($mysqli, $result);
}

function IsGameReleasedAlready($gameid){
	$mysqli = Connect();
	$gamereleased =false;
	if ($result = $mysqli->query("select * from `Events` where `Event` = 'GAMERELEASE' and `GameID` = '".$gameid."' ")) {
		while($row = mysqli_fetch_array($result)){
			if($row["GameID"] != null)
				$gamereleased = true;
		}
	}
	Close($mysqli, $result);
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
	Close($mysqli, $result);
	return $years;
}

function GetGameMetaDataIDs($gbid, $pconn = null){
	$mysqli = Connect($pconn);
	if ($result = $mysqli->query("select `ConceptID` from `Game_Concepts` where `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$concepts[] = $row['ConceptID'];
		}
	}
	if ($result = $mysqli->query("select `DeveloperID` from `Game_Developers` where `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$developers[] = $row['DeveloperID'];
		}
	}
	if ($result = $mysqli->query("select `FranchiseID` from `Game_Franchises` where `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$franchises[] = $row['FranchiseID'];
		}
	}
	if ($result = $mysqli->query("select `PlatformID` from `Game_Platforms` where `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$platforms[] = $row['PlatformID'];
		}
	}
	
	$metadata = new GameMeta($gbid,
							$franchises,
							$genres, 
							$developers, 
							$publishers, 
							$concepts, 
							$locations, 
							$people, 
							$platforms, 
							$themes);
	if($pconn == null)
        Close($mysqli, $result);
	return $metadata;
}

function GetGamesFranchiseGames($gbid, $pconn = null){
	$mysqli = Connect($pconn);
	$franchiseInfo = array();
	if ($result = $mysqli->query("select `FranchiseID` from `Game_Franchises` where `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$franchiseID = $row["FranchiseID"];
		}
	}
	
	if ($result = $mysqli->query("select `GBID` from `Game_Franchises` where `FranchiseID` = '".$franchiseID."' and `GBID` != '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$franchises[] = $row["GBID"];
		}
	}
	
	if ($result = $mysqli->query("select `Name` from `Link_Franchises` where `GBID` = '".$franchiseID."'")) {
		while($row = mysqli_fetch_array($result)){
			$franchiseName = $row["Name"];
		}
	}
	
	
	$franchiseInfo[] = $franchiseName;
	$franchiseInfo[] = $franchises;
	if($pconn == null)
        Close($mysqli, $result);
	return $franchiseInfo;
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
	Close($mysqli, $result);
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
	Close($mysqli, $result);
	return $genre;
}

function GetGenreByID($id, $pconn = null){
	$mysqli = Connect($pconn);
	if ($result = $mysqli->query("select * from `Link_Genre` where `GBID` = '".$id."'")) {
		while($row = mysqli_fetch_array($result)){
				$name = $row["Name"];
		}
	}
    if($pconn == null)
	   Close($mysqli, $result);
	return $name;
}

function GetFranchises(){
	$mysqli = Connect();
	$franchise = array();
	if ($result = $mysqli->query("select * from `Franchises` GROUP BY `Franchise`")) {
		while($row = mysqli_fetch_array($result)){
				$franchise[] = $row["Franchise"];
		}
	}
	Close($mysqli, $result);
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
	Close($mysqli, $result);
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
	Close($mysqli, $result);
	return $developer;
}

function MarkGameReviewed($gameid, $year){
	$mysqli = Connect();
	$result = $mysqli->query("update `Games` set `Reviewed` = 'Yes', `ImageLarge` = 'http://lifebar.io/Images/".$year."/".$gameid.".jpg' where `ID` = '".$gameid."'");
	Close($mysqli, $result);
}

function MarkGameHighlight($gameid, $highlight){
	$mysqli = Connect();
	$result = $mysqli->query("update `Games` set `Highlight` = '".$highlight."' where `ID` = '".$gameid."'");	
	Close($mysqli, $result);
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
	Close($mysqli, $result);
	return $videos;
}

function GetVideo($videoid){
	$mysqli = Connect();	
	if ($result = $mysqli->query("select * from `Videos` where `ID` = '".$videoid."'")) {
		while($row = mysqli_fetch_array($result)){
			$video = new GameVideo($row['ID'],$row['GameID'],$row['Source'],$row['URL'],$row['Desc'], $row['SourceID'], $row['Channel'], $row['Thumbnail'], $row['Views'], $row['Length']);
		}
	}
	Close($mysqli, $result);
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
	Close($mysqli, $result);
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

	Close($mysqli, $result);
}

function LastVideoCheck($gameid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Games` where `ID` = '".$gameid."'")) {
		while($row = mysqli_fetch_array($result)){
			$lastchecked = $row["VideoCheck"];
		}
	}
	Close($mysqli, $result);
	return $lastchecked;
}

function UpdateVideoCheck($gameid){
	$mysqli = Connect();
	$date = date("Y-m-d");
	$mysqli->query("UPDATE `Games` SET `VideoCheck` = '".$date."' WHERE `ID` = '".$gameid."'");
	Close($mysqli, $result);
}

function GetWatchedForGame($gameid){
	$mysqli = Connect();
	$watched = array();
	if ($result = $mysqli->query("select *, COUNT(*) from `Sub-Experiences` where `GameID` = '".$gameid."' and `Type` LIKE 'Watched' GROUP BY `URL`")) {
		while($row = mysqli_fetch_array($result)){
			$watched[] = new GameWatched($row['ID'],$row['Source'],$row['URL'],$row['COUNT(*)']);
		}
	}
	Close($mysqli, $result);
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
	Close($mysqli, $result);
}


?>
