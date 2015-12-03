<?php
require_once "importfiles.php";
	
//LinkAccountToSteam('jonschoen');

	
function RequestGameListForSteamUser($userid){
	$steamapikey = '175E64BE2960BB17FDC601B1A72DB821';
	
	
	
	$request = 'http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key='.$steamapikey.'&steamid=76561197960434622&format=json';

	$request = str_replace(" ", "%20", $request);
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
	
	print_r($decoded->results);
	$myuser = $_SESSION['logged-in'];

}

function LinkAccountToSteam($steamvanity){
	
	$steamapikey = '175E64BE2960BB17FDC601B1A72DB821';
	
	$request = 'http://api.steampowered.com/ISteamUser/ResolveVanityURL/v0001/?key='.$steamapikey.'&vanityurl='.$steamvanity;

	$request = str_replace(" ", "%20", $request);
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
	$mysqli = Connect();			
	$_SESSION['logged-in'] = $myuser;
	$mysqli->query("UPDATE `Users` SET `Steam` = '".$decoded->response->steamid."', `SteamName` = '".$steamvanity."' where `ID` = '".$myuser->_id."' ");
}

function GetSteamIDFromVanity(){
	$myuser = $_SESSION['logged-in'];
	$steamid = 0;
	$mysqli = Connect();
		if ($result = $mysqli->query("select * from `Users` where `ID` = '".$myuser->_id."'")) {
			while($row = mysqli_fetch_array($result)){
					$steamid = $row["Steam"];
			}
		}
	return $steamid;
}

function GetGamesLinkedToSteam($steamid){
	$steamapikey = '175E64BE2960BB17FDC601B1A72DB821';
	$request = 'http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key='.$steamapikey.'&steamid='.$steamid.'&format=json&include_appinfo=1';

	$request = str_replace(" ", "%20", $request);
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
	//print_r($decoded->response);
	$allgames = $decoded->response->games;
	$discoveredgames = array();
	$missinggames = array();
	$allthegames = array();
	$myuser = $_SESSION['logged-in'];
	foreach($allgames as $game){
		//if($game->playtime_forever > 0){
			 $found = SearchForGameLocalFirst(str_replace("'","", $game->name));
			 if($found != null){
			 	$discoveredgames[] = $found;
			 }else{
			 	$missinggames[] = $game->name;
			 }
		//}
	}
	$allthegames[] = $discoveredgames;
	$allthegames[] = $missinggames;
	
	return $allthegames;
}
?>