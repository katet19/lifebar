<?php

//Controllers
require_once 'controller_database.php';

SaveStats();
LoopThroughPubs();

function SaveStats(){
	echo "<hr><h4>SNAPSHOT OF DATABASE NUMBERS</h4>";
	$mysqli = Connect();
	if($result = $mysqli->query("select count(*) as `cnt` from `Users` where `Access` != 'Journalist' and `Access` != 'Authenticated'")){	
		while($row = mysqli_fetch_array($result)){
			$users = $row['cnt'];
		}
	}
	if($result = $mysqli->query("select count(*) as `cnt` from `Users` where `Access` = 'Journalist'")){	
		while($row = mysqli_fetch_array($result)){
			$critics = $row['cnt'];
		}
	}
	if($result = $mysqli->query("select count(*) as `cnt` from `Users` where `Access` = 'Authenticated'")){	
		while($row = mysqli_fetch_array($result)){
			$verified = $row['cnt'];
		}
	}
	if($result = $mysqli->query("select count(*) as `cnt` from `Events` where `Event` = 'ADDED' or `Event` = 'UPDATE'")){	
		while($row = mysqli_fetch_array($result)){
			$xp = $row['cnt'];
		}
	}
	if($result = $mysqli->query("select count(*) as `cnt` from `Games`")){	
		while($row = mysqli_fetch_array($result)){
			$games = $row['cnt'];
		}
	}
	if($result = $mysqli->query("select count(*) as `cnt` from `Experiences` where `Quote` != ''")){	
		while($row = mysqli_fetch_array($result)){
			$summary = $row['cnt'];
		}
	}
	if($result = $mysqli->query("select count(*) as `cnt` from `Users` where `GoogleOAuthID` != ''")){	
		while($row = mysqli_fetch_array($result)){
			$google = $row['cnt'];
		}
	}
	if($result = $mysqli->query("select count(*) as `cnt` from `Users` where `TwitterOAuthID` != ''")){	
		while($row = mysqli_fetch_array($result)){
			$twitter = $row['cnt'];
		}
	}
	if($result = $mysqli->query("select count(*) as `cnt` from `Users` where `FacebookOAuthID` != ''")){	
		while($row = mysqli_fetch_array($result)){
			$facebook = $row['cnt'];
		}
	}
	if($result = $mysqli->query("select count(*) as `cnt` from `Liked`")){	
		while($row = mysqli_fetch_array($result)){
			$ups = $row['cnt'];
		}
	}
	
	$mysqli->query("insert into `LifebarStats` (`TotalUsers`,`TotalXP`,`TotalGames`,`TotalCritics`,`TotalGoogle`,`TotalTwitter`,`TotalFacebook`,`Total1ups`,`TotalVerified`,`TotalSummary`) VALUES ('$users','$xp','$games','$critics','$google','$twitter','$facebook','$ups','$verified','$summary')");
}

//CollectRSSFeeds('http://rss2json.com/api.json?rss_url=http%3A%2F%2Fattackofthefanboy.com%2Freviews%2Ffeed%2F', 'AttackOfTheFanboy');

function CollectRSSFeeds($url, $pub){
	if($url != "" && $url != null){
		if($pub == "Gamespot" || $pub == "GameInformer" || $pub == "GameGrin" || $pub == "AttackOfTheFanboy" || $pub == "DarkZero" || $pub == "GiantBomb" || $pub == "Power Up Gaming"){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$userAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent );
			curl_setopt($ch, CURLOPT_URL, $url);
			$result = curl_exec($ch);
			curl_close($ch);
			
			echo "<hr><h4>".$pub."************</h4>";
			$obj = json_decode($result);
			foreach($obj->items as $feeditem){
				//echo $feeditem->title;
				//echo $feeditem->link."<br>";
				SaveReview($feeditem->title, $feeditem->link, $pub);
			}
			//Give RSSTOJSON request some time to breathe
			Sleep(3);
		}else{
			$thefeed = simplexml_load_file($url);
		
			if($pub == "IGN" || $pub == "Joystiq" || $pub == "Escapist" || $pub == "The Guardian" || $pub == "GamesRadar" || $pub == "N3rdabl3"){
				echo "<hr><h4>".$pub."************</h4>";
				foreach($thefeed->channel->item as $feeditem){
					SaveReview($feeditem->title, $feeditem->link, $pub);
				}
			}else if($pub == "Polygon" || $pub == "Destructoid"){
				echo "<hr><h4>".$pub."************</h4>";
				foreach($thefeed->entry as $feeditem){
					SaveReview($feeditem->title, $feeditem->link->attributes()->href, $pub);
				}
			}
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
		echo "<b>SKIP:</b> <span style='color:darkgray;'>".$title."</span><br>";
		$indb = true;
	}
	
	if($indb == false){	
		echo "<b>NEW:</b> <span style='color:darkgreen;'>".$title."</span><br>";
		$result = $mysqli->query("insert into `PublisherReviews` (`Title`, `RSS`,`Publication`) values ('".$title."','".$rss."','".$publication."')");	
	}
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
		echo "<b>SKIP:</b> '".$title."' was already added<br>";
		$indb = true;
	}
	
	if($indb == false){	
		echo "<b>NEW:</b> <span style='color:darkgreen;'>".$title."' was added from ".$publication." </span><br>";
		$result = $mysqli->query("insert into `PublisherVideos` (`Title`, `RSS`,`Publication`, `Image`, `Duration`) values ('".$title."','".$rss."','".$publication."','".$image."', '".$duration."')");	
	}
}

function LoopThroughPubs(){
	echo "Running RSS Feed Collector \n\r \n\r";
	$pubs = GetPubFeeds();
	foreach($pubs as $pub){
		CollectRSSFeeds($pub[2], $pub[1]);
		//if($pub[3] != "")
			//CollectVideoFeeds($pub[3], $pub[1]);
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
	return $pubs;	
}

?>