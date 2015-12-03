<?php
require_once "importfiles.php";
	
//RequestGameListForXboxUser('dwalsh83');

	
function RequestGameListForXboxUser($gamertag){
	$request = 'https://xboxapi.com/json/games/'.str_replace(" ","+", $gamertag);
	$curl = curl_init($request);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
    		$info = curl_getinfo($curl);
    		curl_close($curl);
   		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	$games = json_decode($curl_response);
	print_r($games->Games);
}

function LinkAccountToXbox($gamertag){
	$mysqli = Connect();			
	$_SESSION['logged-in'] = $myuser;
	$mysqli->query("UPDATE `Users` SET `Xbox` = '".$gamertag."' where `ID` = '".$myuser->_id."' ");
}

function GetGamesLinkedToXbox($gamertag){
	$request = 'https://xboxapi.com/json/games/'.str_replace(" ","+", $gamertag);
	$curl = curl_init($request);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
    		$info = curl_getinfo($curl);
    		curl_close($curl);
   		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	$games = json_decode($curl_response);
	$discoveredgames = array();
	$missinggames = array();
	$allthegames = array();
	for($i = 0; $i < sizeof($games->Games); $i++){
		 $found = SearchForGameLocalFirst(str_replace("'","", $games->Games[$i]->Name));
		 if($found != null){
		 	$discoveredgames[] = $found;
		 }else{
		 	$missinggames[] = $games->Games[$i]->Name;
		 }
	}
	$allthegames[] = $discoveredgames;
	$allthegames[] = $missinggames;
	
	return $allthegames;
}
?>