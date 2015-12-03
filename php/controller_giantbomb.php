<?php
require_once "includes.php";

function RecentlyReleasedFromGB(){
	$gbapikey = '44af5d519adc1c95be92deec4169db0c57116e03';
	$request = 'http://www.giantbomb.com/api/games/?api_key='.$gbapikey.'&filter=original_release_date:'.date('Y-m-d', strtotime('-30 days')).'%2000:00:00|'.date('Y-m-d').'%2000:00:00&format=json&limit=30';
	$curl = curl_init($request);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
    		$info = curl_getinfo($curl);
    		curl_close($curl);
   		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	$decoded = json_decode($curl_response);
	if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    		die('error occured: ' . $decoded->response->errormessage);
	}
	$gameresults = array();
	$myuser = $_SESSION['logged-in'];
	foreach($decoded->results as $game){
		//if($game->original_release_date != ""){
			$release = explode(" ",$game->original_release_date);
			$dates = explode("-",$game->original_release_date);
	
			$gameplatforms = "";
			foreach($game->platforms as $platform){
				$gameplatforms = $gameplatforms.$platform->name."\r\n";
			}
			
			$gamerating = "";
			if($game->original_game_rating != ""){
				foreach($game->original_game_rating as $rating){
					$rated = explode(" ", $rating->name);
					if($rated[1] == "E" || $rated[1] == "T" || $rated[1] == "M" || $rated[1] == "E10" || $rated[1] == "AO" || $rated[1] == "EC"){
							$gamerating = $rated[1];
					}
				}
			}			
			$localgame = GetGameByGBID($game->id);
			$truegameid = "-1";
			
			if($localgame != ""){
				$truegameid = $localgame->_id;
			}
			
			$game = new Game($truegameid,
				$game->id, 
				$game->name,
				$gamerating,
				$release[0],
				"",
				$gameplatforms,
				$dates[0],
				$game->image->super_url,
				$game->image->small_url,
				"No",
				"",
				"",
				"",
				"",
				"",
				"");
			$gameresults[] = $game;
	}
	
	return $gameresults;
}	

function RequestGameFromGiantBomb($searchstring){
	$gbapikey = '44af5d519adc1c95be92deec4169db0c57116e03';
	//if(strrpos($searchstring, " ")){
		//$request = 'http://www.giantbomb.com/api/games/?api_key='.$gbapikey.'&format=json&sort=original_release_date:desc&filter=name:'.$searchstring;
		$request = 'http://www.giantbomb.com/api/search/?api_key='.$gbapikey.'&format=json&limit=40&query='.$searchstring.'&resources=game';
		$request = str_replace(" ", "+", $request);
	//}else{
	//	$request = 'http://www.giantbomb.com/api/search/?api_key='.$gbapikey.'&format=json&sort=original_release_date:desc&query="'.$searchstring.'"&resources=game';
	//	$request = str_replace(" ", "%20", $request);
	//}
	
	//echo $request;
	$curl = curl_init($request);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
    		$info = curl_getinfo($curl);
    		curl_close($curl);
   		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	$decoded = json_decode($curl_response);
	if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    		die('error occured: ' . $decoded->response->errormessage);
	}
	$gameresults = array();
	$myuser = $_SESSION['logged-in'];
	foreach($decoded->results as $game){
		if($game->original_release_date != ""){
			$release = explode(" ",$game->original_release_date);
			$dates = explode("-",$game->original_release_date);
	
			$gameplatforms = "";
			if($game->platforms  != ""){
				foreach($game->platforms as $platform){
					$gameplatforms = $gameplatforms.$platform->name."\r\n";
				}
			}
			
			$gamerating = "";
			if($game->original_game_rating != ""){
				foreach($game->original_game_rating as $rating){
					$rated = explode(" ", $rating->name);
					if($rated[1] == "E" || $rated[1] == "T" || $rated[1] == "M" || $rated[1] == "E10" || $rated[1] == "AO" || $rated[1] == "EC"){
							$gamerating = $rated[1];
					}
				}
			}			
			$localgame = GetGameByGBID($game->id);
			$truegameid = "-1";
			
			if($localgame != ""){
				$truegameid = $localgame->_id;
			}
			
			$game = new Game($truegameid,
				$game->id, 
				$game->name,
				$gamerating,
				$release[0],
				"",
				$gameplatforms,
				$dates[0],
				$game->image->super_url,
				$game->image->small_url,
				"",
				"",
				"",
				"",
				"",
				"",
				"");
			$gameresults[] = $game;
		}else if($game->expected_release_day != "" && $game->expected_release_month != "" && $game->expected_release_year != ""){
			$release = $game->expected_release_year."-".$game->expected_release_month."-".$game->expected_release_day;
	
			$gameplatforms = "";
			foreach($game->platforms as $platform){
				$gameplatforms = $gameplatforms.$platform->name."\r\n";
			}
			
			$gamerating = "";
			if($game->original_game_rating != ""){
				foreach($game->original_game_rating as $rating){
					$rated = explode(" ", $rating->name);
					if($rated[1] == "E" || $rated[1] == "T" || $rated[1] == "M" || $rated[1] == "E10" || $rated[1] == "AO" || $rated[1] == "EC"){
							$gamerating = $rated[1];
					}
				}
			}			
			$localgame = GetGameByGBID($game->id);
			$truegameid = "-1";
			
			if($localgame != ""){
				$truegameid = $localgame->_id;
			}
			
			$game = new Game($truegameid,
				$game->id, 
				$game->name,
				$gamerating,
				$release,
				"",
				$gameplatforms,
				$game->expected_release_year,
				$game->image->super_url,
				$game->image->small_url,
				"No",
				"",
				"",
				"",
				"",
				"",
				"");
			$gameresults[] = $game;

		}else{
			if($game->expected_release_year <= "0000")
				$game->expected_release_year = "2050";
			if($game->expected_release_month <= "00")
				$game->expected_release_month = "12";
			if($game->expected_release_day <= "00")
				$game->expected_release_day = "31";	
			$release = $game->expected_release_year."-".$game->expected_release_month."-".$game->expected_release_day;
	
			$gameplatforms = "";
			if($game->platforms != ""){
				foreach($game->platforms as $platform){
					$gameplatforms = $gameplatforms.$platform->name."\r\n";
				}
			}
			
			$gamerating = "";
			if($game->original_game_rating != ""){
				foreach($game->original_game_rating as $rating){
					$rated = explode(" ", $rating->name);
					if($rated[1] == "E" || $rated[1] == "T" || $rated[1] == "M" || $rated[1] == "E10" || $rated[1] == "AO" || $rated[1] == "EC"){
							$gamerating = $rated[1];
					}
				}
			}			
			$localgame = GetGameByGBID($game->id);
			$truegameid = "-1";
			
			if($localgame != ""){
				$truegameid = $localgame->_id;
			}
			
			$game = new Game($truegameid,
				$game->id, 
				$game->name,
				$gamerating,
				$release,
				"",
				$gameplatforms,
				$game->expected_release_year,
				$game->image->super_url,
				$game->image->small_url,
				"No",
				"",
				"",
				"",
				"",
				"",
				"");
			$gameresults[] = $game;
		}
	}
	
	return $gameresults;
}


function RequestGiantBombImage($gameid){
	$gbapikey = '44af5d519adc1c95be92deec4169db0c57116e03';
	
	$request = 'http://www.giantbomb.com/api/game/3030-'.$gameid.'/?api_key='.$gbapikey.'&format=json&field_list=image';
	$curl = curl_init($request);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
    		$info = curl_getinfo($curl);
    		curl_close($curl);
   		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	$decoded = json_decode($curl_response);
	if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    		die('error occured: ' . $decoded->response->errormessage);
	}
	$game = $decoded->results;
	$images[] = $game->image->super_url;
	$images[] = $game->image->small_url;
	$images[] = $game->image->medium_url;
	return $images;
	/*
    [icon_url] => http://static.giantbomb.com/uploads/square_avatar/1/14230/2165185-starbound.jpg
    [medium_url] => http://static.giantbomb.com/uploads/scale_medium/1/14230/2165185-starbound.jpg
    [screen_url] => http://static.giantbomb.com/uploads/screen_medium/1/14230/2165185-starbound.jpg
    [small_url] => http://static.giantbomb.com/uploads/scale_small/1/14230/2165185-starbound.jpg
    [super_url] => http://static.giantbomb.com/uploads/scale_large/1/14230/2165185-starbound.jpg
    [thumb_url] => http://static.giantbomb.com/uploads/scale_avatar/1/14230/2165185-starbound.jpg
    [tiny_url] => http://static.giantbomb.com/uploads/square_mini/1/14230/2165185-starbound.jpg
	
	*/
}

function GetVideoDetailsGiantBomb($videoid){
	$gbapikey = '44af5d519adc1c95be92deec4169db0c57116e03';
	
	$request = 'http://www.giantbomb.com/api/video/2300-'.$videoid.'/?api_key='.$gbapikey.'&format=json';
	$curl = curl_init($request);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
    		$info = curl_getinfo($curl);
    		curl_close($curl);
   		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	$decoded = json_decode($curl_response);
	if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    		die('error occured: ' . $decoded->response->errormessage);
	}
	$video = $decoded->results;
	return $video;
}

function RequestUSReleaseFromGiantBombByID($gameid){
	$gbapikey = '44af5d519adc1c95be92deec4169db0c57116e03';
	$request = 'http://www.giantbomb.com/api/releases/?api_key='.$gbapikey.'&format=json&filter=game:'.$gameid;
	$curl = curl_init($request);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
    		$info = curl_getinfo($curl);
    		curl_close($curl);
   		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	$decoded = json_decode($curl_response);
	if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    		die('error occured: ' . $decoded->response->errormessage);
	}
	$releases = $decoded->results;
	$earliestyear = date('Y');
	$i=0;
	while(isset($releases[$i])){
		if($releases[$i]->region->name == "United States"){
			$relarray = explode("-",$releases[$i]->release_date);
			if($releases[$i]->expected_release_year <= $earliestyear && ($releases[$i]->expected_release_day != "" && $releases[$i]->expected_release_month != "" && $releases[$i]->expected_release_year != "")){
				$release = $releases[$i]->expected_release_year."-".$releases[$i]->expected_release_month."-".$releases[$i]->expected_release_day;
				$earliestyear = $releases[$i]->expected_release_year;
			}else if(isset($relarray[0]) && $relarray[0] != "" && $relarray[0] <= $earliestyear){
				$release = $releases[$i]->release_date;
				$earliestyear = $relarray[0];
			}
		}
		$i++;
	}
	return $release;
}

function RequestGameFromGiantBombByID($gameid){
	$gbapikey = '44af5d519adc1c95be92deec4169db0c57116e03';
	
	$request = 'http://www.giantbomb.com/api/game/3030-'.$gameid.'/?api_key='.$gbapikey.'&format=json';
	$curl = curl_init($request);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
    		$info = curl_getinfo($curl);
    		curl_close($curl);
   		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	$decoded = json_decode($curl_response);
	if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    		die('error occured: ' . $decoded->response->errormessage);
	}
	$newgame = "";
	$game = $decoded->results;
	$alreadyout = false;
	$release = RequestUSReleaseFromGiantBombByID($gameid);
		
	if($release != ""){
		$alreadyout = true;
		$tempdates = explode("-",$release);
		$game->expected_release_year = $tempdates[0];
	}else if($game->original_release_date== "" && $game->expected_release_day != "" && $game->expected_release_month != "" && $game->expected_release_year != ""){
		$release = $game->expected_release_year."-".$game->expected_release_month."-".$game->expected_release_day;
		$alreadyout = true;
	}else{
		$release = explode(" ",$game->original_release_date);
		$dates = explode("-",$game->original_release_date);
	}
	$gameplatforms = "";
	if($game->platforms != ""){
		foreach($game->platforms as $platform){
			$gameplatforms = $gameplatforms.$platform->name."\r\n";
		}
	}
	
	$gamerating = "";
	if($game->original_game_rating != ""){
		foreach($game->original_game_rating as $rating){
			$rated = explode(" ", $rating->name);
			if($rated[1] == "E" || $rated[1] == "T" || $rated[1] == "M" || $rated[1] == "E10" || $rated[1] == "AO" || $rated[1] == "EC"){
					$gamerating = $rated[1];
			}
		}
	}			
	$truegameid = "-1";
	
		$publishers = "";
	if($game->publishers != ""){
		foreach($game->publishers as $publisher){
			$publishers = $publishers.$publisher->name."\r\n";
		}
	}
	
	$developers = "";
	if($game->developers != ""){
		foreach($game->developers as $developer){
			$developers = $developers.$developer->name."\r\n";
		}
	}
	
	$aliases = $game->aliases;
	
	$themes = "";
	if($game->themes != ""){
		foreach($game->themes as $theme){
			$themes = $themes.$theme->name."\r\n";
		}
	}
	
	$franchises = "";
	if($game->franchises != ""){
		foreach($game->franchises as $franchise){
			$franchises = $franchises.$franchise->name."\r\n";
		}
	}
	
	$genres = "";
	if($game->genres != ""){
		foreach($game->genres as $genre){
			$genres = $genres.$genre->name."\r\n";
		}
	}
	
	$similar_games = "";
	if($game->similar_games != ""){
		foreach($game->similar_games as $similar_game){
			$similar_games = $similar_games.$similar_game->id.",";
		}
	}
	
	//New system
	$mysqli = Connect();
	if($game->platforms != ""){
		foreach($game->platforms as $platform){
			SavePlatform($platform->name, $platform->id, -1, $gameid, $mysqli);
		}
	}
	
	if($game->publishers != ""){
		foreach($game->publishers as $publisher){
			SavePublisher($publisher->name, $publisher->id, -1, $gameid, $mysqli);
		}
	}
	
	if($game->developers != ""){
		foreach($game->developers as $developer){
			SaveDeveloper($developer->name, $developer->id, -1, $gameid, $mysqli);
		}
	}
	
	if($game->themes != ""){
		foreach($game->themes as $theme){
			SaveTheme($theme->name, $theme->id, -1, $gameid, $mysqli);
		}
	}

	if($game->franchises != ""){
		foreach($game->franchises as $franchise){
			SaveFranchise($franchise->name, $franchise->id, -1, $gameid, $mysqli);
		}
	}
	
	
	if($game->genres != ""){
		foreach($game->genres as $genre){
			SaveGenre($genre->name, $genre->id, -1, $gameid, $mysqli);
		}
	}

	if($game->locations != ""){
		foreach($game->locations as $location){
			SaveLocation($location->name, $location->id, -1, $gameid, $mysqli, $mysqli);
		}
	}
	
	if($game->objects != ""){
		foreach($game->objects as $object){
			SaveObject($object->name, $object->id, -1, $gameid, $mysqli);
		}
	}

	if($game->concepts != ""){
		foreach($game->concepts as $concept){
			SaveConcept($concept->name, $concept->id, -1, $gameid, $mysqli);
		}
	}
	
	if($game->people != ""){
		foreach($game->people as $person){
			SavePerson($person->name, $person->id, -1, $gameid, $mysqli);
		}
	}
	

	if($alreadyout){
		$addedgame = InsertGame($game->id, mysqli_real_escape_string($mysqli, $game->name), $gamerating, $release, mysqli_real_escape_string($mysqli, $genres), $gameplatforms, $game->expected_release_year, mysqli_real_escape_string($mysqli, $publishers), mysqli_real_escape_string($mysqli, $developers), mysqli_real_escape_string($mysqli, $aliases), mysqli_real_escape_string($mysqli, $themes), mysqli_real_escape_string($mysqli, $franchises), mysqli_real_escape_string($mysqli, $similar_games),$game->image->super_url,$game->image->small_url);
	}else{
		$addedgame = InsertGame($game->id, mysqli_real_escape_string($mysqli, $game->name), $gamerating, $release[0], mysqli_real_escape_string($mysqli, $genres), $gameplatforms, $dates[0], mysqli_real_escape_string($mysqli, $publishers), mysqli_real_escape_string($mysqli, $developers), mysqli_real_escape_string($mysqli, $aliases), mysqli_real_escape_string($mysqli, $themes), mysqli_real_escape_string($mysqli, $franchises),mysqli_real_escape_string($mysqli, $similar_games),$game->image->super_url,$game->image->small_url);
	}
	
	Close($mysqli, $result);
	return $addedgame;
}


function UpdateGameFromGiantBombByID($gameid, $reviewed){
	$gbapikey = '44af5d519adc1c95be92deec4169db0c57116e03';
	
	$request = 'http://www.giantbomb.com/api/game/3030-'.$gameid.'/?api_key='.$gbapikey.'&format=json';
	$curl = curl_init($request);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
    		$info = curl_getinfo($curl);
    		curl_close($curl);
   		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	$decoded = json_decode($curl_response);
	if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    		die('error occured: ' . $decoded->response->errormessage);
	}
	$newgame = "";
	$game = $decoded->results;
	$alreadyout = false;
	$release = RequestUSReleaseFromGiantBombByID($gameid);
		
	if($release != ""){
		$alreadyout = true;
		$tempdates = explode("-",$release);
		$game->expected_release_year = $tempdates[0];
	}else if($game->original_release_date== "" && $game->expected_release_day != "" && $game->expected_release_month != "" && $game->expected_release_year != ""){
		$release = $game->expected_release_year."-".$game->expected_release_month."-".$game->expected_release_day;
		$alreadyout = true;
	}else{
		$release = explode(" ",$game->original_release_date);
		$dates = explode("-",$game->original_release_date);
	}

	$gameplatforms = "";
	if($game->platforms != ""){
		foreach($game->platforms as $platform){
			$gameplatforms = $gameplatforms.$platform->name."\r\n";
		}
	}
	
	$gamerating = "";
	if($game->original_game_rating != ""){
		foreach($game->original_game_rating as $rating){
			$rated = explode(" ", $rating->name);
			if($rated[1] == "E" || $rated[1] == "T" || $rated[1] == "M" || $rated[1] == "E10" || $rated[1] == "AO" || $rated[1] == "EC"){
					$gamerating = $rated[1];
			}
		}
	}			

	//NEW FIELDS
	$publishers = "";
	if($game->publishers != ""){
		foreach($game->publishers as $publisher){
			$publishers = $publishers.$publisher->name."\r\n";
		}
	}
	
	$developers = "";
	if($game->developers != ""){
		foreach($game->developers as $developer){
			$developers = $developers.$developer->name."\r\n";
		}
	}
	
	$aliases = $game->aliases;
	
	$themes = "";
	if($game->themes != ""){
		foreach($game->themes as $theme){
			$themes = $themes.$theme->name."\r\n";
		}
	}
	
	$franchises = "";
	if($game->franchises != ""){
		foreach($game->franchises as $franchise){
			$franchises = $franchises.$franchise->name."\r\n";
		}
	}
	
	$genres = "";
	if($game->genres != ""){
		foreach($game->genres as $genre){
			$genres = $genres.$genre->name."\r\n";
		}
	}
	
	$similar_games = "";
	if($game->similar_games != ""){
		foreach($game->similar_games as $similar_game){
			$similar_games = $similar_games.$similar_game->id.",";
		}
	}
	$mysqli = Connect();
	if($alreadyout){
		UpdateGame($gameid, mysqli_real_escape_string($mysqli, $game->name), $gamerating, $release, mysqli_real_escape_string($mysqli, $genres), $gameplatforms, $game->expected_release_year,mysqli_real_escape_string($mysqli, $publishers), mysqli_real_escape_string($mysqli, $developers), mysqli_real_escape_string($mysqli, $aliases), mysqli_real_escape_string($mysqli, $themes), mysqli_real_escape_string($mysqli, $franchises), mysqli_real_escape_string($mysqli, $similar_games));
	}else{
		UpdateGame($gameid, mysqli_real_escape_string($mysqli, $game->name), $gamerating, $release[0], mysqli_real_escape_string($mysqli, $genres), $gameplatforms, $dates[0],mysqli_real_escape_string($mysqli, $publishers), mysqli_real_escape_string($mysqli, $developers), mysqli_real_escape_string($mysqli, $aliases), mysqli_real_escape_string($mysqli, $themes), mysqli_real_escape_string($mysqli, $franchises), mysqli_real_escape_string($mysqli, $similar_games));
	}
	
	//foreach($game->videos as $video){
	//	if(stripos($video->name,'Quick Look') !== false || stripos($video->name,'Unfinished') !== false ){
	//		$viddetails = GetVideoDetailsGiantBomb($video->id);
	//		InsertVideoForGame($gameid, 'GiantBomb', $viddetails->site_detail_url, $viddetails->name, $viddetails->id, 'GiantBomb', $viddetails->image->screen_url, 0, $viddetails->length_seconds);
	//	}
	//}
	Close($mysqli, $result);
	if($reviewed == "Sorta"){
		AddSmallImage($gameid);
		//AddImage($gameid);	
		echo $game->name." was updated and image was collected from GB<br>";
	}else{
		echo $game->name." was updated<br>";
	}
		
}

function FullUpdateViaGameID($gbid, $reviewed){
	$gbapikey = '44af5d519adc1c95be92deec4169db0c57116e03';
	//$gbid = 36067;
	$request = 'http://www.giantbomb.com/api/game/3030-'.$gbid.'/?api_key='.$gbapikey.'&format=json';
	$curl = curl_init($request);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
    		$info = curl_getinfo($curl);
    		curl_close($curl);
   		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	$decoded = json_decode($curl_response);
	if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    		die('error occured: ' . $decoded->response->errormessage);
	}
	$newgame = "";
	$game = $decoded->results;
	$alreadyout = false;
	$release = RequestUSReleaseFromGiantBombByID($gbid);
		
	if($release != ""){
		$alreadyout = true;
		$tempdates = explode("-",$release);
		$game->expected_release_year = $tempdates[0];
	}else if($game->original_release_date== "" && $game->expected_release_day != "" && $game->expected_release_month != "" && $game->expected_release_year != ""){
		$release = $game->expected_release_year."-".$game->expected_release_month."-".$game->expected_release_day;
		$alreadyout = true;
	}else{
		$release = explode(" ",$game->original_release_date);
		$dates = explode("-",$game->original_release_date);
	}
	
	$gamerating = "";
	if($game->original_game_rating != ""){
		foreach($game->original_game_rating as $rating){
			$rated = explode(" ", $rating->name);
			if($rated[1] == "E" || $rated[1] == "T" || $rated[1] == "M" || $rated[1] == "E10" || $rated[1] == "AO" || $rated[1] == "EC"){
					$gamerating = $rated[1];
			}
		}
	}			
	$truegameid = "-1";
	
	//Old system
	$gameplatforms = "";
	if($game->platforms != ""){
		foreach($game->platforms as $platform){
			$gameplatforms = $gameplatforms.$platform->name."\r\n";
		}
	}
	
	$publishers = "";
	if($game->publishers != ""){
		foreach($game->publishers as $publisher){
			$publishers = $publishers.$publisher->name."\r\n";
		}
	}
	
	$developers = "";
	if($game->developers != ""){
		foreach($game->developers as $developer){
			$developers = $developers.$developer->name."\r\n";
		}
	}
	
	$themes = "";
	if($game->themes != ""){
		foreach($game->themes as $theme){
			$themes = $themes.$theme->name."\r\n";
		}
	}
	
	$franchises = "";
	if($game->franchises != ""){
		foreach($game->franchises as $franchise){
			$franchises = $franchises.$franchise->name."\r\n";
		}
	}
	
	$genres = "";
	if($game->genres != ""){
		foreach($game->genres as $genre){
			$genres = $genres.$genre->name."\r\n";
		}
	}
	
	

	$mysqli = Connect();
	//New system
	if($game->platforms != ""){
		foreach($game->platforms as $platform){
			SavePlatform($platform->name, $platform->id, -1, $gbid, $mysqli);
		}
	}
	
	if($game->publishers != ""){
		foreach($game->publishers as $publisher){
			SavePublisher($publisher->name, $publisher->id, -1, $gbid, $mysqli);
		}
	}
	
	if($game->developers != ""){
		foreach($game->developers as $developer){
			SaveDeveloper($developer->name, $developer->id, -1, $gbid, $mysqli);
		}
	}
	
	$aliases = $game->aliases;
	
	if($game->themes != ""){
		foreach($game->themes as $theme){
			SaveTheme($theme->name, $theme->id, -1, $gbid, $mysqli);
		}
	}

	if($game->franchises != ""){
		foreach($game->franchises as $franchise){
			SaveFranchise($franchise->name, $franchise->id, -1, $gbid, $mysqli);
		}
	}
	
	
	if($game->genres != ""){
		foreach($game->genres as $genre){
			SaveGenre($genre->name, $genre->id, -1, $gbid, $mysqli);
		}
	}
	
	$similar_games = "";
	if($game->similar_games != ""){
		foreach($game->similar_games as $similar_game){
			$similar_games = $similar_games.$similar_game->id.",";
		}
	}

	if($game->locations != ""){
		foreach($game->locations as $location){
			SaveLocation($location->name, $location->id, -1, $gbid, $mysqli);
		}
	}
	
	if($game->objects != ""){
		foreach($game->objects as $object){
			SaveObject($object->name, $object->id, -1, $gbid, $mysqli);
		}
	}

	if($game->concepts != ""){
		foreach($game->concepts as $concept){
			SaveConcept($concept->name, $concept->id, -1, $gbid, $mysqli);
		}
	}
	
	if($game->people != ""){
		foreach($game->people as $person){
			SavePerson($person->name, $person->id, -1, $gbid, $mysqli);
		}
	}
	
	if($alreadyout){
		UpdateGame($gbid, mysqli_real_escape_string($mysqli, $game->name), $gamerating, $release, mysqli_real_escape_string($mysqli, $genres), $gameplatforms, $game->expected_release_year,mysqli_real_escape_string($mysqli, $publishers), mysqli_real_escape_string($mysqli, $developers), mysqli_real_escape_string($mysqli, $aliases), mysqli_real_escape_string($mysqli, $themes), mysqli_real_escape_string($mysqli, $franchises), mysqli_real_escape_string($mysqli, $similar_games));
	}else{
		UpdateGame($gbid, mysqli_real_escape_string($mysqli, $game->name), $gamerating, $release[0], mysqli_real_escape_string($mysqli, $genres), $gameplatforms, $dates[0],mysqli_real_escape_string($mysqli, $publishers), mysqli_real_escape_string($mysqli, $developers), mysqli_real_escape_string($mysqli, $aliases), mysqli_real_escape_string($mysqli, $themes), mysqli_real_escape_string($mysqli, $franchises), mysqli_real_escape_string($mysqli, $similar_games));
	}
	Close($mysqli, $result);
	
	if($reviewed == "Sorta"){
		AddSmallImage($gbid);
		//AddImage($gameid);	
		echo $game->name." was updated and image was collected from GB<br>";
	}else{
		echo $game->name." was updated<br>";
	}
}


function SaveConcept($name, $id, $gameid, $gbid, $mysqli){
	//insert new data
	$notfound = true;
	if ($result = $mysqli->query("select * from `Link_Concepts` where `GBID` = ".$id)) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Link_Concepts` (`GBID`,`Name`) values ('".$id."','".mysqli_real_escape_string($mysqli, $name)."')");
	}
	
	//insert new connection
	$notfound = true;
	if($result = $mysqli->query("select * from `Game_Concepts` where `ConceptID` = ".$id." and `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Game_Concepts` (`GameID`,`GBID`,`ConceptID`) values ('".$gameid."','".$gbid."','".$id."')");
	}
}

function SaveDeveloper($name, $id, $gameid, $gbid, $mysqli){
	//insert new data
	$notfound = true;
	if ($result = $mysqli->query("select * from `Link_Developers` where `GBID` = ".$id)) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Link_Developers` (`GBID`,`Name`) values ('".$id."','".mysqli_real_escape_string($mysqli, $name)."')");
	}
	
	//insert new connection
	$notfound = true;
	if($result = $mysqli->query("select * from `Game_Developers` where `DeveloperID` = ".$id." and `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Game_Developers` (`GameID`,`GBID`,`DeveloperID`) values ('".$gameid."','".$gbid."','".$id."')");
	}
}

function SaveFranchise($name, $id, $gameid, $gbid, $mysqli){
	//insert new data
	$notfound = true;
	if ($result = $mysqli->query("select * from `Link_Franchises` where `GBID` = ".$id)) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Link_Franchises` (`GBID`,`Name`) values ('".$id."','".mysqli_real_escape_string($mysqli, $name)."')");
	}
	
	//insert new connection
	$notfound = true;
	if($result = $mysqli->query("select * from `Game_Franchises` where `FranchiseID` = ".$id." and `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Game_Franchises` (`GameID`,`GBID`,`FranchiseID`) values ('".$gameid."','".$gbid."','".$id."')");
	}
}

function SaveGenre($name, $id, $gameid, $gbid, $mysqli){
	//insert new data
	$notfound = true;
	if ($result = $mysqli->query("select * from `Link_Genre` where `GBID` = ".$id)) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Link_Genre` (`GBID`,`Name`) values ('".$id."','".mysqli_real_escape_string($mysqli, $name)."')");
	}
	
	//insert new connection
	$notfound = true;
	if($result = $mysqli->query("select * from `Game_Genre` where `GenreID` = ".$id." and `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Game_Genre` (`GameID`,`GBID`,`GenreID`) values ('".$gameid."','".$gbid."','".$id."')");
	}
}

function SaveLocation($name, $id, $gameid, $gbid, $mysqli){
	//insert new data
	$notfound = true;
	if ($result = $mysqli->query("select * from `Link_Locations` where `GBID` = ".$id)) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Link_Locations` (`GBID`,`Name`) values ('".$id."','".mysqli_real_escape_string($mysqli, $name)."')");
	}
	
	//insert new connection
	$notfound = true;
	if($result = $mysqli->query("select * from `Game_Locations` where `LocationID` = ".$id." and `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Game_Locations` (`GameID`,`GBID`,`LocationID`) values ('".$gameid."','".$gbid."','".$id."')");
	}
}

function SaveObject($name, $id, $gameid, $gbid, $mysqli){
	//insert new data
	$notfound = true;
	if ($result = $mysqli->query("select * from `Link_Objects` where `GBID` = ".$id)) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Link_Objects` (`GBID`,`Name`) values ('".$id."','".mysqli_real_escape_string($mysqli, $name)."')");
	}
	
	//insert new connection
	$notfound = true;
	if($result = $mysqli->query("select * from `Game_Objects` where `ObjectID` = ".$id." and `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Game_Objects` (`GameID`,`GBID`,`ObjectID`) values ('".$gameid."','".$gbid."','".$id."')");
	}
}

function SavePerson($name, $id, $gameid, $gbid, $mysqli){
	//insert new data
	$notfound = true;
	if ($result = $mysqli->query("select * from `Link_People` where `GBID` = ".$id)) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Link_People` (`GBID`,`Name`) values ('".$id."','".mysqli_real_escape_string($mysqli, $name)."')");
	}
	
	//insert new connection
	$notfound = true;
	if($result = $mysqli->query("select * from `Game_People` where `PeopleID` = ".$id." and `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Game_People` (`GameID`,`GBID`,`PeopleID`) values ('".$gameid."','".$gbid."','".$id."')");
	}
}

function SavePlatform($name, $id, $gameid, $gbid, $mysqli){
	//insert new data
	$notfound = true;
	if ($result = $mysqli->query("select * from `Link_Platforms` where `GBID` = ".$id)) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Link_Platforms` (`GBID`,`Name`) values ('".$id."','".mysqli_real_escape_string($mysqli, $name)."')");
	}
	
	//insert new connection
	$notfound = true;
	if($result = $mysqli->query("select * from `Game_Platforms` where `PlatformID` = ".$id." and `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Game_Platforms` (`GameID`,`GBID`,`PlatformID`) values ('".$gameid."','".$gbid."','".$id."')");
	}
}

function SavePublisher($name, $id, $gameid, $gbid, $mysqli){
	//insert new data
	$notfound = true;
	if ($result = $mysqli->query("select * from `Link_Publishers` where `GBID` = ".$id)) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Link_Publishers` (`GBID`,`Name`) values ('".$id."','".mysqli_real_escape_string($mysqli, $name)."')");
	}
	
	//insert new connection
	$notfound = true;
	if($result = $mysqli->query("select * from `Game_Publishers` where `PublisherID` = ".$id." and `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Game_Publishers` (`GameID`,`GBID`,`PublisherID`) values ('".$gameid."','".$gbid."','".$id."')");
	}
}

function SaveTheme($name, $id, $gameid, $gbid, $mysqli){
	//insert new data
	$notfound = true;
	if ($result = $mysqli->query("select * from `Link_Themes` where `GBID` = ".$id)) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Link_Themes` (`GBID`,`Name`) values ('".$id."','".mysqli_real_escape_string($mysqli, $name)."')");
	}
	
	//insert new connection
	$notfound = true;
	if($result = $mysqli->query("select * from `Game_Themes` where `ThemeID` = ".$id." and `GBID` = '".$gbid."'")) {
		while($row = mysqli_fetch_array($result)){
			$notfound = false;	
		}
	}
	if($notfound){
		$result = $mysqli->query("insert into `Game_Themes` (`GameID`,`GBID`,`ThemeID`) values ('".$gameid."','".$gbid."','".$id."')");
	}
}
?>