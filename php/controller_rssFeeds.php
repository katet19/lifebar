<?php

//Controllers
require_once 'Controller_Database.php';

LoopThroughPubs();

function CollectRSSFeeds($url, $pub){
	if($url != "" && $url != null){
		$thefeed = simplexml_load_file($url);
		
		if($pub == "GiantBomb" || $pub == "Gamespot" || $pub == "IGN" || $pub == "Joystiq" || $pub == "Escapist" || $pub == "The Guardian" || $pub == "GameInformer" || $pub == "GamesRadar"){
			echo "<hr><br>******".$pub." reviews: <br>";
			foreach($thefeed->channel->item as $feeditem){
				SaveReview($feeditem->title, $feeditem->link, $pub);
			}
		}else if($pub == "Polygon" || $pub == "Destructoid"){
			echo "<hr><br>******".$pub." reviews: <br>";
			foreach($thefeed->entry as $feeditem){
				SaveReview($feeditem->title, $feeditem->link->attributes()->href, $pub);
			}
		}else{
			/*print_r($thefeed);
			foreach($thefeed->channel->item as $feeditem){
				echo "Title: ".$feeditem->title. "|| Link: ".$feeditem->link."<BR>";
			}*/
		}
		echo " \n\r \n\r";
	}
}

function SaveReview($title, $rss, $publication){
	$mysqli = Connect();
	$title = str_replace("'","",$title);
	$result = $mysqli->query("select * from `PublisherReviews` where `Title` = '".$title."' and `Publication` = '".$publication."'");
	$indb = false;
	while($row = mysqli_fetch_array($result)){
		//We already have this in the DB
		echo "'".$title."' has already been added <br>";
		$indb = true;
	}
	
	if($indb == false){	
		echo "NEW!! '".$title."' was added from ".$publication." <br>";
		$result = $mysqli->query("insert into `PublisherReviews` (`Title`, `RSS`,`Publication`) values ('".$title."','".$rss."','".$publication."')");	
	}
	Close($mysqli, $result);
}

//CollectVideoFeeds("http://www.joystiq.com/tag/@video/rss.xml", "Joystiq");

function CollectVideoFeeds($url, $pub){
	if($url != "" && $url != null){
		$thefeed = simplexml_load_file($url);
		
		if($pub == "TotalBiscuit" || $pub == "PewDiePie" || $pub == "AngryJoeShow" || $pub == "Rev3Games" || $pub  == "NerdCubed" || $pub == "GameInformer"){
			echo "<hr><br>******".$pub." videos: <br>";
			foreach($thefeed->channel->item as $feeditem){
				$str = explode("src=", $feeditem->description);
				$sstr = explode(">", $str[1]);
				$ssstr = str_replace("\"", "", $sstr[0]);
				if(strpos(strtolower($feeditem->title), "trailer") === false && strpos(strtolower($feeditem->title), "news") === false && strpos(strtolower($feeditem->title), "walkthrough") === false)
					SaveRssVideo($feeditem->title, $feeditem->link, $pub, $ssstr,-1);
			}
		}else if($pub == "Polygon"){
			echo "<hr><br>******".$pub." videos: <br>";
			foreach($thefeed->entry as $feeditem){
				$str = explode("/>", $feeditem->content);
				$sstr = explode("src=\"", $str[0]);
				$ssstr = str_replace("\"", "", $sstr[1]);
				if(strpos(strtolower($feeditem->title), "trailer") === false && strpos(strtolower($feeditem->title), "news") === false && strpos(strtolower($feeditem->title), "walkthrough") === false)
					SaveRssVideo($feeditem->title, $feeditem->link->attributes()->href, $pub, $ssstr,-1);
			}
		}else if($pub == "Gamespot"){
			echo "<hr><br>******".$pub." videos: <br>";
			foreach($thefeed->channel->item as $feeditem){
				$str = explode("src=", $feeditem->description);
				$sstr = explode(" width", $str[1]);
				$ssstr = str_replace("\"", "", $sstr[0]);
				if(strpos(strtolower($feeditem->title), "trailer") === false && strpos(strtolower($feeditem->title), "news") === false && strpos(strtolower($feeditem->title), "walkthrough") === false)
					SaveRssVideo($feeditem->title, $feeditem->link, $pub, $ssstr,-1);
			}
		}else if($pub == "IGN"){
			echo "<hr><br>******".$pub." videos: <br>";
			foreach($thefeed->channel->item as $feeditem){
				$str = $feeditem->content->thumbnail->attributes()->url;
				$duration = $feeditem->content->attributes()->duration;
				$duration = $duration * 60;
				if(strpos(strtolower($feeditem->title), "trailer") === false && strpos(strtolower($feeditem->title), "news") === false && strpos(strtolower($feeditem->title), "walkthrough") === false)
					SaveRssVideo($feeditem->title, $feeditem->link, $pub, $str, $duration);
			}
		}else if($pub == "Joystiq"){
			echo "<hr><br>******".$pub." videos: <br>";
			foreach($thefeed->channel->item as $feeditem){
				$str = explode("src=", $feeditem->description);
				$sstr = explode(" data-mep", $str[1]);
				if(strpos($sstr[0], '.jpg') !== false || strpos($sstr[0], '.png') !== false || strpos($sstr[0], '.gif') !== false ){
					$ssstr = str_replace("\"", "", $sstr[0]);
				}else{
					$sstr = explode(" alt=", $str[1]);	
					if(strpos($sstr[0], '.jpg') !== false || strpos($sstr[0], '.png') !== false || strpos($sstr[0], '.gif') !== false ){
						$ssstr = str_replace("\"", "", $sstr[0]);
					}else{
						$ssstr = "";	
					}
				}
				if(strpos(strtolower($feeditem->title), "trailer") === false && strpos(strtolower($feeditem->title), "news") === false && strpos(strtolower($feeditem->title), "walkthrough") === false)
					SaveRssVideo($feeditem->title, $feeditem->link, $pub, "", -1);
			}
		}
		echo " \n\r \n\r";
	}
}

function SaveRssVideo($title, $rss, $publication, $image, $duration){
	$mysqli = Connect();
	$title = str_replace("'","",$title);
	$result = $mysqli->query("select * from `PublisherVideos` where `Title` = '".$title."' and `Publication` = '".$publication."'");
	$indb = false;
	while($row = mysqli_fetch_array($result)){
		echo "'".$title."' has already been added <br>";
		$indb = true;
	}
	
	if($indb == false){	
		echo "NEW!! '".$title."' was added from ".$publication." <br>";
		$result = $mysqli->query("insert into `PublisherVideos` (`Title`, `RSS`,`Publication`, `Image`, `Duration`) values ('".$title."','".$rss."','".$publication."','".$image."', '".$duration."')");	
	}
	Close($mysqli, $result);
}

function LoopThroughPubs(){
	echo "Running RSS Feed Collector \n\r \n\r";
	$pubs = GetPubFeeds();
	foreach($pubs as $pub){
		CollectRSSFeeds($pub[2], $pub[1]);
		if($pub[3] != "")
			CollectVideoFeeds($pub[3], $pub[1]);
	}
}

//CollectVideoFeeds('http://www.polygon.com/rss/group/videos/index.xml', "Polygon");

function GetPubFeeds(){
	$pubs = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `PublisherFeeds`")) {
		while($row = mysqli_fetch_array($result)){
			unset($pub);
			$pub[0] = $row["ID"];
			$pub[1] = $row["Title"];
			$pub[2] = $row["URL"];
			$pub[3] = $row["VideoURL"];
			$pubs[] = $pub;
		}
	}
	Close($mysqli, $result);
	return $pubs;	
}

?>