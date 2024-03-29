<?php
require_once "includes.php";

function GetPlayedImport($userid){
	$mysqli = Connect();
	$mapped = null;
	if ($result = $mysqli->query("select * from `ImportAudit` where `UserID` = '".$userid."' and `Type` = 'Steam' and `MappedID` > 0 and `Ignore` = 'No' and `TimePlayed` > 0 group by `MappedID` order by `Title` LIMIT 0,40")) {
		while($row = mysqli_fetch_array($result)){
			$game = GetGameByGBIDFull($row['MappedID']);
			$import = null;
			$import['Title'] = $game->_title;
			$import['ImportImage'] = $row['Image'];
			$import['GameID'] = $game->_id;
			$import['GBID'] = $game->_gbid;
			$import['TimePlayed'] = $row['TimePlayed'];
			
			if($import != null){
				$mapped[] = $import;
			}
		}
	}
	Close($mysqli, $result);
	$mapped['Collection'] = GetCollectionByNameForUser('Steam Played',$userid,$userid);
	return $mapped;
}

function GetBacklogImport($userid){
	$mysqli = Connect();
	$mapped = null;
	if ($result = $mysqli->query("select * from `ImportAudit` where `UserID` = '".$userid."' and `Type` = 'Steam' and `MappedID` > 0 and `Ignore` = 'No' and `TimePlayed` = 0 group by `MappedID` order by `Title` LIMIT 0,40")) {
		while($row = mysqli_fetch_array($result)){
			$game = GetGameByGBIDFull($row['MappedID']);
			$import = null;
			$import['Title'] = $game->_title;
			$import['ImportImage'] = $row['Image'];
			$import['GameID'] = $game->_id;
			$import['GBID'] = $game->_gbid;
			$import['TimePlayed'] = $row['TimePlayed'];
			
			if($import != null){
				$mapped[] = $import;
			}
		}
	}
	Close($mysqli, $result);
	$mapped['Collection'] = GetCollectionByNameForUser('Steam Backlog',$userid,$userid);
	return $mapped;
}

function MapGameToLifebar($importID, $gbid, $auditID){
	$mysqli = Connect();
	if($_SESSION['logged-in']->_security == "Admin")
		$strength = 2;
	else
		$strength = 1;
		
	$game = GetGameByGBIDFull($gbid);
	$foundgame = false;
	if ($result = $mysqli->query("select * from `GamesMapper` where `GBID` = '".$gbid."' and `Steam` = '".$importID."'")) {
		while($row = mysqli_fetch_array($result)){
			$foundgame = true;
			$strength = $row['MapStrength'] + $strength;
		}
	}
	if($foundgame)
		$result = $mysqli->query("update `GamesMapper` set `MapStrength` = '".$strength."' where `GBID` = '".$gbid."' and `Steam` = '".$importID."'");
	else
		$result = $mysqli->query("insert into `GamesMapper` (`GameID`,`GBID`,`Steam`,`MapStrength`) values ('".$game->_id."','".$gbid."','".$importID."','".$strength."')");
		
	$mysqli->query("delete from `GamesMapperReport` where `ImportID` = '".$importID."'");
		
	if($strength >= 2){
		$mysqli->query("update `ImportAudit` set `MappedID` = '".$gbid."' where `ImportID` = '".$importID."' and `Type` = 'Steam'");
	}else{
		$mysqli->query("update `ImportAudit` set `MappedID` = '".$gbid."' where `ID` = '".$auditID."'");
	}
	
	Close($mysqli, $result);
}

function TrashGameToLifebar($importID, $gbid, $auditID){
	$mysqli = Connect();
		
	$foundgame = false;
	if ($result = $mysqli->query("select * from `GamesMapper` where `Steam` = '".$importID."'")) {
		while($row = mysqli_fetch_array($result)){
			$foundgame = true;
		}
	}
	if($foundgame)
		$result = $mysqli->query("update `GamesMapper` set `Visible` = 'No' where`GBID` = '".$gbid."' and `Steam` = '".$importID."'");
	else
		$result = $mysqli->query("insert into `GamesMapper` (`GameID`,`GBID`,`Steam`,`MapStrength`,`Visible`) values ('".$game->_id."','".$gbid."','".$importID."','".$strength."','No')");
		
	
	$mysqli->query("delete from `GamesMapperReport` where `ImportID` = '".$importID."'");
	
	if($strength >= 2){
		$mysqli->query("update `ImportAudit` set `Ignore` = 'Yes' where `ImportID` = '".$importID."' and `Type` = 'Steam'");
	}else{
		$mysqli->query("update `ImportAudit` set `Ignore` = 'Yes' where `ID` = '".$auditID."'");
	}
	
	Close($mysqli, $result);
}

function IgnoreGameFromMyImport($importID, $gbid, $auditID){
	$mysqli = Connect();
		
	$mysqli->query("update `ImportAudit` set `Ignore` = 'Yes' where `ID` = '".$auditID."'")	;
	
	Close($mysqli, $result);
}

function ReportGameFromMyImport($importID, $gbid, $auditID){
	$mysqli = Connect();
		
	$result = $mysqli->query("insert into `GamesMapperReport` (`ImportID`,`UserID`) values ('".$importID."','".$_SESSION['logged-in']->_id."')");
	
	$foundgame = false;
	if ($result = $mysqli->query("select * from `GamesMapper` where `Steam` = '".$importID."'")) {
		while($row = mysqli_fetch_array($result)){
			$foundgame = true;
		}
	}
	if($foundgame)
		$result = $mysqli->query("update `GamesMapper` set `Visible` = 'No' where`GBID` = '".$gbid."' and `Steam` = '".$importID."'");
	else
		$result = $mysqli->query("insert into `GamesMapper` (`GameID`,`GBID`,`Steam`,`MapStrength`,`Visible`) values ('".$game->_id."','".$gbid."','".$importID."','".$strength."','No')");
		
	if($strength >= 2){
		$mysqli->query("update `ImportAudit` set `Ignore` = 'Rprt' where `ImportID` = '".$importID."' and `Type` = 'Steam'");
	}else{
		$mysqli->query("update `ImportAudit` set `Ignore` = 'Rprt' where `ID` = '".$auditID."'");
	}
	
	Close($mysqli, $result);
}

function GetSteamMapped($userid, $offSet){
	$mysqli = Connect();
	$mapped = null;
	if ($result = $mysqli->query("select * from `ImportAudit` where `UserID` = '".$userid."' and `Type` = 'Steam' and `MappedID` > 0 and `Ignore` = 'No' group by `MappedID` order by `Title` LIMIT ".$offSet.",25")) {
		while($row = mysqli_fetch_array($result)){
			$game = GetGameByGBIDFull($row['MappedID']);
			$import = null;
			$import['Title'] = $game->_title;
			$import['ImportImage'] = $row['Image'];
			$import['GameID'] = $game->_id;
			$import['GBID'] = $game->_gbid;
			$import['TimePlayed'] = $row['TimePlayed'];
			
			if($import != null)
				$mapped[] = $import;
		}
	}
	Close($mysqli, $result);
	return $mapped;
}

function GetSteamMappedBacklog($userid){
	$mysqli = Connect();
	$backlog = null;
	if ($result = $mysqli->query("select * from `ImportAudit` where `UserID` = '".$userid."' and `Type` = 'Steam' and `MappedID` > 0 and `Ignore` = 'No' and `TimePlayed` = 0 group by `MappedID` order by `Title`")) {
		while($row = mysqli_fetch_array($result)){
			$game = GetGameByGBIDFull($row['MappedID']);
			$import = null;
			$import['GameID'] = $game->_id;
			$import['GBID'] = $game->_gbid;
			
			if($import != null)
				$backlog[] = $import;
		}
	}
	Close($mysqli, $result);
	return $backlog;
}

function GetSteamMappedPlayed($userid){
	$mysqli = Connect();
	$played = null;
	if ($result = $mysqli->query("select * from `ImportAudit` where `UserID` = '".$userid."' and `Type` = 'Steam' and `MappedID` > 0 and `Ignore` = 'No' and `TimePlayed` > 0 group by `MappedID` order by `Title`")) {
		while($row = mysqli_fetch_array($result)){
			$game = GetGameByGBIDFull($row['MappedID']);
			$import = null;
			$import['GameID'] = $game->_id;
			$import['GBID'] = $game->_gbid;
			
			if($import != null)
				$played[] = $import;
		}
	}
	Close($mysqli, $result);
	return $played;
}

function GetSteamReported($userid, $offSet){
	$mysqli = Connect();
	$reported = null;
	if ($result = $mysqli->query("select * from `ImportAudit` i, `GamesMapperReport` r where i.`UserID` = '".$userid."' and i.`Type` = 'Steam' and i.`Ignore` = 'Rprt' and i.`ImportID` = r.`ImportID` group by `Title` order by `Title`")) {
		while($row = mysqli_fetch_array($result)){
			$import = null;
			$import['SteamTitle'] = $row['Title'];
			$import['ImportImage'] = $row['Image'];
			$import['ReportedOn'] = $row['Reported'];
			$import['Priority'] = $row['Priority'];
			
			if($import != null)
				$reported[] = $import;
		}
	}
	Close($mysqli, $result);
	return $reported;
}

function GetImportReportedPriority($offSet){
	$mysqli = Connect();
	$reported = null;
	if ($result = $mysqli->query("select * from `ImportAudit` i, `GamesMapperReport` r where i.`Type` = 'Steam' and i.`ImportID` = r.`ImportID` group by r.`ImportID` order by r.`Priority` DESC LIMIT 0,25")) {
		while($row = mysqli_fetch_array($result)){
			$import = null;
			$import['SteamTitle'] = $row['Title'];
			$import['ImportImage'] = $row['Image'];
			$import['ReportedOn'] = $row['Reported'];
			$import['Priority'] = $row['Priority'];
			$import['ImportID'] = $row['ImportID'];
			
			if($import != null)
				$reported[] = $import;
		}
	}
	Close($mysqli, $result);
	return $reported;
}

function GetSteamUnMapped($userid, $offSet){
	$mysqli = Connect();
	$unmapped = null;
	if ($result = $mysqli->query("select * from `ImportAudit` where `UserID` = '".$userid."' and `Type` = 'Steam' and `MappedID` = 0  and `Ignore` = 'No' order by `Title` LIMIT ".$offSet.",25")) {
		while($row = mysqli_fetch_array($result)){
			if($row['PossibleMap'] > 0){
				$game = GetGameByGBIDFull($row['PossibleMap'], $mysqli);
				$import = null;
				$import['Title'] = $game->_title;
				$import['SteamTitle'] = $row['Title'];
				$import['ImportImage'] = $row['Image'];
				$import['LifebarImage'] = $game->_imagesmall;
				$import['GameID'] = $game->_id;
				$import['GBID'] = $game->_gbid;
				$import['ImportID'] = $row['ImportID'];
				$import['TimePlayed'] = $row['TimePlayed'];
				$import['Year'] = $game->_year;
				$import['AuditID'] = $row['ID'];
			}else{
				$import = null;
				$import['SteamTitle'] = $row['Title'];
				$import['ImportImage'] = $row['Image'];
				$import['TimePlayed'] = $row['TimePlayed'];
				$import['ImportID'] = $row['ImportID'];
				$import['AuditID'] = $row['ID'];
			}
			if($import != null)
				$unmapped[] = $import;
		}
	}
	Close($mysqli, $result);
	return $unmapped;
}

function GetSteamUnMappedRow($userid, $offSet){
	$mysqli = Connect();
	$unmapped = null;
	if ($result = $mysqli->query("select * from `ImportAudit` where `UserID` = '".$userid."' and `Type` = 'Steam' and `MappedID` = 0  and `Ignore` = 'No' order by `Title` LIMIT ".$offSet.",1")) {
		while($row = mysqli_fetch_array($result)){
			if($row['PossibleMap'] > 0){
				$game = GetGameByGBIDFull($row['PossibleMap'], $mysqli);
				$import = null;
				$import['Title'] = $game->_title;
				$import['SteamTitle'] = $row['Title'];
				$import['ImportImage'] = $row['Image'];
				$import['LifebarImage'] = $game->_imagesmall;
				$import['GameID'] = $game->_id;
				$import['GBID'] = $game->_gbid;
				$import['ImportID'] = $row['ImportID'];
				$import['TimePlayed'] = $row['TimePlayed'];
				$import['Year'] = $game->_year;
				$import['AuditID'] = $row['ID'];
			}else{
				$import = null;
				$import['SteamTitle'] = $row['Title'];
				$import['ImportImage'] = $row['Image'];
				$import['TimePlayed'] = $row['TimePlayed'];
				$import['ImportID'] = $row['ImportID'];
				$import['AuditID'] = $row['ID'];
			}
			if($import != null)
				$unmapped[] = $import;
		}
	}
	Close($mysqli, $result);
	return $unmapped;
}

function GetReportedRow($offSet){
	$mysqli = Connect();
	$reported = null;
	if ($result = $mysqli->query("select * from `ImportAudit` i, `GamesMapperReport` r where i.`Type` = 'Steam' and i.`ImportID` = r.`ImportID` group by r.`ImportID` order by r.`Priority` DESC LIMIT ".$offSet.",1")) {
		while($row = mysqli_fetch_array($result)){
			$import = null;
			$import['SteamTitle'] = $row['Title'];
			$import['ImportImage'] = $row['Image'];
			$import['ReportedOn'] = $row['Reported'];
			$import['Priority'] = $row['Priority'];
			$import['ImportID'] = $row['ImportID'];
			
			if($import != null)
				$reported[] = $import;
		}
	}
	Close($mysqli, $result);
	return $reported;
}

function GetSteamTotals($userid){
	$mysqli = Connect();
	$unmapped = 0;
	$mapped = 0;
	$reported = 0;
	if ($result = $mysqli->query("select count(*) as cnt from `ImportAudit` where `UserID` = '".$userid."' and `Type` = 'Steam' and `MappedID` = 0 and `Ignore` = 'No'")) {
		while($row = mysqli_fetch_array($result)){
			$unmapped = $row['cnt'];
		}
	}
	
	if ($result = $mysqli->query("select count(*) as cnt from `ImportAudit` where `UserID` = '".$userid."' and `Type` = 'Steam' and `MappedID` > 0 and `Ignore` = 'No'")) {
		while($row = mysqli_fetch_array($result)){
			$mapped = $row['cnt'];
		}
	}
	
	if ($result = $mysqli->query("select count(*) as cnt from `ImportAudit` i, `GamesMapperReport` r where i.`UserID` = '".$userid."' and i.`Type` = 'Steam' and i.`Ignore` = 'Rprt' and i.`ImportID` = r.`ImportID`")) {
		while($row = mysqli_fetch_array($result)){
			$reported = $row['cnt'];
		}
	}
	
	$totals[0] = number_format($unmapped);
	$totals[1] = number_format($mapped);
	$totals[2] = number_format($reported);
	
	Close($mysqli, $result);
	return $totals;	
}

function AlreadyImportedSteam($userid){
	$mysqli = Connect();
	$imported = false;
	if ($result = $mysqli->query("select count(*) as cnt from `ImportAudit` where `UserID` = '".$userid."' and `Type` = 'Steam'")) {
		while($row = mysqli_fetch_array($result)){
			if($row['cnt'] > 0)
			$imported = true;
		}
	}
	
	Close($mysqli, $result);
	return $imported;	
}

function GetGameMapping($id, $name, $pconn = null){
	$mysqli = Connect($pconn);
	$mapStrength = "none";
	if ($result = $mysqli->query("select * from `GamesMapper` where `Steam` = '".$id."'")) {
		while($row = mysqli_fetch_array($result)){
			if($row['Visible'] == 'No'){
				$reported = false;
				if ($result = $mysqli->query("select * from `GamesMapperReport` where `ImportID` = '".$id."'")) {
					while($row = mysqli_fetch_array($result)){
						$reported = true;
					}
				}
				if(!$reported){
					$gameid = -1;
					$gbid = -1;
					$mapStrength = "high";
				}else{
					$gameid = -1;
					$gbid = -1;
					$mapStrength = "medium";
				}
			}else if($row['GBID'] > 0){
				$gameid = $row['GameID'];
				$gbid = $row['GBID'];
				if($row['MapStrength'] >= 2){
					$mapStrength = "high";
				}else{
					$mapStrength = "medium";
				}
			}
		}
	}
	if($mapStrength == "none"){
		if ($result = $mysqli->query("select * from `Games` where `Title` = '".$name."' or `Alias` = '".$name."'")) {
			while($row = mysqli_fetch_array($result)){
				if($row['GBID'] > 0){
					$gameid = $row['ID'];
					$gbid = $row['GBID'];
					$mapStrength = "low";
				}
			}
		}
	}
	
	$mapping[0] = $gameid;
	$mapping[1] = $gbid;
	$mapping[2] = $mapStrength;
	
	if($pconn == null)
		Close($mysqli, $result);
		
	return $mapping;
}

function ImportLibraryForSteamUser($steamvanity, $fullreset){
	if(strlen($steamvanity) > 15 && ctype_digit($steamvanity))
		$userid = $steamvanity;
	else
		$userid = GetSteamID($steamvanity);
		
	$steamapikey = 'F380E48672B5996985B5EB0A9DACD9DB';
	if(strlen($userid) > 15 && ctype_digit($userid)){
		if($_SESSION['STEAMGAMES'] == ''){
			$request = 'http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key='.$steamapikey.'&steamid='.$userid.'&format=json&include_appinfo=1';
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
			
			$allgames = $decoded->response->games;
		}else{
			$allgames = $_SESSION['STEAMGAMES'];
			$_SESSION['STEAMGAMES'] = null;
		}
		
		if($allgames != ''){
			$newuser = SyncUserToSteam($_SESSION['logged-in']->_id, $userid, $steamvanity);
			AuditOfImport($allgames, $_SESSION['logged-in']->_id, $fullreset, $newuser);
			$steambacklog = DoesCollectionExist('Steam Backlog',$_SESSION['logged-in']->_id);
			if($steambacklog != null){
				ClearCollection($steambacklog);
				$backlog = GetSteamMappedBacklog($_SESSION['logged-in']->_id);
				BulkAddToCollection($steambacklog, $backlog);
				if($fullreset == false)
					UpdateTimeStampCollection($steambacklog);
				else
					UpdateVisibilityOfCollection($steambacklog, 'Yes');
			}else{
				$backlog = GetSteamMappedBacklog($_SESSION['logged-in']->_id);
				$backlogID = CreateCollection('Steam Backlog',"Steam games I have yet to start",$_SESSION['logged-in']->_id,'-1','Yes',$backlog);
			}
			
			$steamplayed = DoesCollectionExist('Steam Played',$_SESSION['logged-in']->_id);
			if($steamplayed != null){
				ClearCollection($steamplayed);
				$played = GetSteamMappedPlayed($_SESSION['logged-in']->_id);
				BulkAddToCollection($steamplayed, $played);
				if($fullreset == false)
					UpdateTimeStampCollection($steamplayed);
				else
					UpdateVisibilityOfCollection($steamplayed, 'Yes');
			}else{
				$played = GetSteamMappedPlayed($_SESSION['logged-in']->_id);
				$playedID = CreateCollection('Steam Played',"Games I have played from my Steam Library",$_SESSION['logged-in']->_id,'-1','Yes',$played);
			}
			
			if($fullreset || $steambacklog == null || $steamplayed == null){
				if($steambacklog == null)
					$steambacklog = $backlogID;
				if($steamplayed == null)
					$steamplayed = $playedID;
				$mysqli = Connect();
				$result = $mysqli->query("insert into `Events` (`UserID`,`GameID`,`Event`,`Quote`) values ('".$_SESSION['logged-in']->_id."','".sizeof($allgames)."','STEAMIMPORT','".$steamplayed."||".$steambacklog."')");
				Close($mysqli, $result);
			}
			
			return "SUCCESS";
		}else{
			return "FAILED";
		}
		
	}else{
		return "FAILED";
	}
}

function AuditOfImport($games, $userid, $fullreset, $newuser){
	$mysqli = Connect();
	
	if($fullreset)
		$mysqli->query("delete from `ImportAudit` where `UserID` = '$userid'");
		
	foreach($games as $game){
		$mapping = null;
		$skip = false;
		$alreadyExists = false;
		if($fullreset == false){
			if ($result = $mysqli->query("select * from `ImportAudit` where `ImportID` = '".$game->appid."' and `UserID` = '".$userid."'")) {
				while($row = mysqli_fetch_array($result)){
					$alreadyExists = true;		
				}
			}	
		}
		if($alreadyExists == false){
			$mapping = GetGameMapping($game->appid, $game->name, $mysqli);
			if($mapping[0] == -1 && $mapping[1] == -1){
				if($mapping[2] == "high")
					$skip = true;
				else
					$skip = 'trueWithReport';
			}else if($mapping[2] == "high"){
				$mappedID = $mapping[1];
				$possibleMap = '';
			}else if($mapping[2] == "medium" || $mapping[2] == "low"){
				$mappedID = '';
				$possibleMap = $mapping[1];
			}else{
				$mappedID = '';
				$possibleMap = '';
			}
			
			if($skip == 'trueWithReport'){
				$mysqli->query("insert into `ImportAudit` (`UserID`,`Title`,`Image`,`ImportID`,`TimePlayed`,`MappedID`,`PossibleMap`,`Ignore`) values ('".$userid."','".$game->name."','http://media.steampowered.com/steamcommunity/public/images/apps/".$game->appid."/".$game->img_logo_url.".jpg','".$game->appid."','".$game->playtime_forever."','".$mappedID."','".$possibleMap."', 'Rprt')");
				if($newuser)
					IncreaseReportPriority($game->appid, $mysqli);
			}else if($skip == false)
				$mysqli->query("insert into `ImportAudit` (`UserID`,`Title`,`Image`,`ImportID`,`TimePlayed`,`MappedID`,`PossibleMap`) values ('".$userid."','".$game->name."','http://media.steampowered.com/steamcommunity/public/images/apps/".$game->appid."/".$game->img_logo_url.".jpg','".$game->appid."','".$game->playtime_forever."','".$mappedID."','".$possibleMap."')");
		}
	}
	Close($mysqli, $result);
}

function CheckForExistingImport($userid){
	$mysqli = Connect();
	$exists = false;
	if ($result = $mysqli->query("select * from `ImportAudit` where `UserID` = '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			if($row['ID'] > 0)
				$exists = true;
		}
	}
	Close($mysqli, $result);
	return $exists;
}

function GetSteamID($steamvanity){
	$steamapikey = 'F380E48672B5996985B5EB0A9DACD9DB';
	$steamvanity = str_replace("http://steamcommunity.com/id/", "", $steamvanity);
	$steamvanity = str_replace("https://steamcommunity.com/id/", "", $steamvanity);
	$steamvanity = str_replace("http://steamcommunity.com/profiles/", "", $steamvanity);
	$steamvanity = str_replace("https://steamcommunity.com/profiles/", "", $steamvanity);
	if(strlen($steamvanity) > 15 && ctype_digit($steamvanity)){
		return $steamvanity;
	}

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
	return $decoded->response->steamid;
}

function SyncUserToSteam($userid, $steamid, $steamvanity){
	$mysqli = Connect();
	$oldsteamid = GetSteamIDForUser($userid);
	$newuser = false;
	if($oldsteamid[0] == -1){
		$result = $mysqli->query("insert into `UsersSync` (`UserID`,`Type`,`SyncID`,`SyncVanity`) values ('$userid','Steam','$steamid','$steamvanity')");
		$newuser = true;
	}else{
		$timestamp = date('Y-m-d H:i:s', strtotime("now"));
		$result = $mysqli->query("update `UsersSync` set `SyncID` = '$steamid', `SyncVanity`= '$steamvanity', `LastSync` = '$timestamp' where `UserID` = '$userid' and `Type` = 'Steam'");
	}
	Close($mysqli, $result);
	return $newuser;
}

function GetEstimatedTimeToImport($steamvanity){
	if(strlen($steamvanity) > 15 && ctype_digit($steamvanity))
		$userid = $steamvanity;
	else
		$userid = GetSteamID($steamvanity);
		
	$steamapikey = 'F380E48672B5996985B5EB0A9DACD9DB';
	if(strlen($userid) > 15 && ctype_digit($userid)){
		$request = 'http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key='.$steamapikey.'&steamid='.$userid.'&format=json&include_appinfo=1';
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
		
		$allgames = $decoded->response->games;
		$_SESSION['STEAMGAMES'] = $allgames;
		echo sizeof($allgames);
	}else{
		echo "FAILED";
	}
}

function GetSteamIDForUser($userid){
	$mysqli = Connect();
	$steamid[0] = -1;
	if ($result = $mysqli->query("select * from `UsersSync` where `UserID` = '".$userid."' and `Type` = 'Steam'")) {
		while($row = mysqli_fetch_array($result)){
			$steamid[0] = $row['SyncID'];
			$steamid[1] = $row['SyncVanity'];
		}
	}
	Close($mysqli, $result);
	return $steamid;
}

function GetLastImportForUser($userid){
	$mysqli = Connect();
	$last = -1;
	if ($result = $mysqli->query("select * from `UsersSync` where `UserID` = '".$userid."' and `Type` = 'Steam'")) {
		while($row = mysqli_fetch_array($result)){
			$last = $row['LastSync'];
		}
	}
	Close($mysqli, $result);
	return $last;
}

function UpdateSyncDate($userid){
	$mysqli = Connect();
	$timestamp = date('Y-m-d H:i:s', strtotime("now"));
	$result = $mysqli->query("update `UsersSync` set `LastSync` = '$timestamp' where `UserID` = '$userid' and `Type` = 'Steam'");
	Close($mysqli, $result);
}

function IncreaseReportPriority($importID, $pconn = null){
	$mysqli = Connect($pconn);
	$total = -1;
		if ($result = $mysqli->query("select `Priority` from `GamesMapperReport` where `ImportID` = '".$importID."' LIMIT 0,1")) {
		while($row = mysqli_fetch_array($result)){
			$total = $row['Priority'] + 1;
		}
	}
	if($total > 0)
		$result = $mysqli->query("update `GamesMapperReport` set `Priority` = '$total' where `ImportID` = '$importID'");
	
	if($pconn == null)
		Close($mysqli, $result);
}

?>