<?php
require_once "importfiles.php";

function GetNowPlayingOnTwitch($searchstring){
	$twitchID = '6yltaufpy6urmdle87t5fqurs0qcfqi';
	$searchstring = str_replace(" ", "+", $searchstring);
	$request = 'https://api.twitch.tv/kraken/videos/top?game='.$searchstring.'&period=month';
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
	
	$videos = array();		
	foreach($decoded->videos as $video){
		//$videos[] = new GameVideo(-1, -1, 'Twitch', $video->url, $video->title." (".$video->views." views)", $video->_id, $vide->);
	}
	return $videos;
}

function UpdateTwitchVideos($searchstring, $gameid){
	$twitchID = '6yltaufpy6urmdle87t5fqurs0qcfqi';
	$searchstring = str_replace(" ", "+", $searchstring);
	$request = 'https://api.twitch.tv/kraken/videos/top?game='.$searchstring.'&period=month';
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
		
	foreach($decoded->videos as $video){
		InsertVideoForGame($gameid, 'Twitch', $video->url, $video->title, $video->_id, $video->channel->display_name, $video->preview, $video->views, $video->length);
	}
}
?>