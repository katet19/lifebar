<?php

function SaveFormChoices($userid, $formid, $formitemid, $gameid, $objectid, $objectType){
	$mysqli = Connect();
	$updating=false;
	$allitems = explode("||", $formitemid);

	//Calculate Current Results
	if ($result = $mysqli->query("select * from `FormResults` where `FormID` = '".$formid."'")) {
		while($row = mysqli_fetch_array($result)){
			$formresult[$row['FormItemID']]  = $formresult[$row['FormItemID']] + 1;
		}
	}
	if(sizeof($formresult) > 0){
		while (list($key, $val) = each($formresult)) {
	    	$lastresults[] = $key.",".$val;
		}
		$results = implode("||", $lastresults);
	}else{
		$results = '';
	}


	if(sizeof($allitems) == 1){
		if ($result = $mysqli->query("select * from `FormResults` where `UserID` = '".$userid."' and `FormID` = '".$formid."'")) {
			while($row = mysqli_fetch_array($result)){
				$updating = true;
				$choiceID  = $row['ID'];
			}
		}

		if($updating){
			$date = date('Y-m-d H:i:s');
			$mysqli->query("UPDATE `FormResults` set `FormItemID`='".$allitems[0]."', `Date`='".$date."',`LastResults`='$results' where `ID` = '".$choiceID."'");
		}else{
			$mysqli->query("insert into `FormResults` (`UserID`,`FormID`,`FormItemID`,`GameID`,`ObjectID`, `ObjectType`,`LastResults`) values ('$userid','$formid','".$allitems[0]."','$gameid','$objectid','$objectType','$results')");
		}

	}else if (sizeof($allitems) > 1){
		$mysqli->query("delete from `FormResults` where `UserID` = '".$userid."' and `FormID` = '".$formid."'");
		foreach($allitems as $myitem){
			if($myitem != ''){
				$mysqli->query("insert into `FormResults` (`UserID`,`FormID`,`FormItemID`,`GameID`,`ObjectID`, `ObjectType`,`LastResults`) values ('$userid','$formid','$myitem','$gameid','$objectid','$objectType','$results')");
			}
		}
	}

	Close($mysqli, $result);
}

function GetFormChoices($userid, $formid){
	$mysqli = Connect();
	$choices = array();
	if ($result = $mysqli->query("select `FormItemID` from `FormResults` where `UserID` = '".$userid."' and `FormID` = '".$formid."'")) {
		while($row = mysqli_fetch_array($result)){
			$choices[] = $row['FormItemID'];	
		}
	}
	return $choices;
}

function HasFormResults($userid, $formid){
	$mysqli = Connect();
	$done = false;
	if ($result = $mysqli->query("select * from `FormResults` where `UserID` = '".$userid."' and `FormID` = '".$formid."'")) {
		while($row = mysqli_fetch_array($result)){
			$done = true;	
		}
	}
	return $done;
}

function SubmitDailyForm($userid, $question, $subquestion, $formtype, $approved, $objectid, $objectType, $default, $items, $itemurls, $itemtype, $finished){
	$mysqli = Connect();
	$mysqli->query("insert into `Forms` (`Header`,`SubHeader`,`FormType`,`CreatedBy`,`Approved`,`ObjectID`, `ObjectType`,`Finished`) values ('".mysqli_real_escape_string($mysqli, $question)."','".mysqli_real_escape_string($mysqli, $subquestion)."','$formtype','$userid','$approved','$objectid','$objectType','$finished')");
	if ($result = $mysqli->query("select * from `Forms` where `CreatedBy` = '".$userid."' order by `ID` DESC LIMIT 0,1")) {
		while($row = mysqli_fetch_array($result)){
			$formID = $row['ID'];
		}
	}
	$itemarray = explode("@@@",$items);
	$itemurlarray = explode("||",$itemurls);
	$i=0;
	foreach($itemarray as $item){
		$subitems = explode("^^^",$item);
		if($subitems[1] != ''){
			$submeta = explode("||", $subitems[1]);
		}else{
			$submeta[] = '0';
			$submeta[] = '';
		}
		if($subitems[0] != ''){
			$mysqli->query("insert into `FormItems` (`Choice`,`FormID`,`Type`,`IsDefault`,`URL`,`ObjID`,`ObjType`) values ('".mysqli_real_escape_string($mysqli, $subitems[0])."','$formID','$itemtype','$default','".mysqli_real_escape_string($mysqli, $itemurlarray[$i])."','".$submeta[1]."','".$submeta[0]."')");
			if($default != "No")
				$default = "No";
		}
		$i++;
	}
	Close($mysqli, $result);
}

function UpdateDailyForm($formid, $question, $subquestion, $default, $items, $itemurls, $itemtype, $finished){
	$mysqli = Connect();
	$mysqli->query("UPDATE `Forms` set `Header`='".mysqli_real_escape_string($mysqli, $question)."', `SubHeader`='".mysqli_real_escape_string($mysqli, $subquestion)."',`Finished`='$finished' where `ID` = '".$formid."'");
	$itemarray = explode("@@@",$items);
	$itemurlarray = explode("||",$itemurls);
	$i=0;
	foreach($itemarray as $item){
		$subitems = explode("^^^",$item);
		if($subitems[1] != ''){
			$submeta = explode("||", $subitems[1]);
		}else{
			$submeta[] = '0';
			$submeta[] = '';
			$submeta[] = '';
		}
		if($subitems[0] != ''){
			if($subitems[2] > 0){
				$mysqli->query("UPDATE `FormItems` set `Choice`='".mysqli_real_escape_string($mysqli, $subitems[0])."',`Type`='$itemtype',`IsDefault`='$default',`URL`='".mysqli_real_escape_string($mysqli, $itemurlarray[$i])."',`ObjID`='".$submeta[1]."',`ObjType`='".$submeta[0]."' where `ID` = '".$subitems[2]."'");
			}else{
				$mysqli->query("insert into `FormItems` (`Choice`,`FormID`,`Type`,`IsDefault`,`URL`,`ObjID`,`ObjType`) values ('".mysqli_real_escape_string($mysqli, $subitems[0])."','$formid','$itemtype','$default','".mysqli_real_escape_string($mysqli, $itemurlarray[$i])."','".$submeta[1]."','".$submeta[0]."')");
			}
			if($default != "No")
				$default = "No";
		}
		$i++;
	}
	Close($mysqli, $result);
}

function GetFormResults($formid){
	$mysqli = Connect();
	$refpts = array();	
	if ($result = $mysqli->query("select * from `Forms` where `ID` = '".$formid."'")) {
		while($row = mysqli_fetch_array($result)){
			$refpts["FORM"] = $row;
		}
	}
	$total = 0;
	$formitems = array();
	if ($result = $mysqli->query("select * from `FormItems` where `FormID` = '".$formid."'")) {
		while($row = mysqli_fetch_array($result)){
			if ($result2 = $mysqli->query("select count(*) as 'cnt' from `FormResults` where `FormItemID` = '".$row['ID']."'")) {
				while($row2 = mysqli_fetch_array($result2)){
					$row['TOTAL'] = $row2['cnt'];
					$total = $total + $row2['cnt'];
				}
			}
			$formitems[] = $row;
		}
	}
	$refpts["FORMITEMS"] = $formitems;
	$refpts["TOTAL"] = $total;
	
	Close($mysqli, $result);
	return $refpts;
}

function GetReflectionPointsForGame($gameid){
	$mysqli = Connect();
	$refpts = array();	if ($result = $mysqli->query("select * from `Forms` where `ObjectID` = '".$gameid."' and `ObjectType` = 'Game'")) {
		while($row = mysqli_fetch_array($result)){
			$refpts[] = $row;
		}
	}
	Close($mysqli, $result);
	return $refpts;
}


function GetReflectionPoint($id){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Forms` where `ID` = '".$id."'")) {
		while($row = mysqli_fetch_array($result)){
			$refpts = $row;
		}
	}
	
	if(sizeof($refpts) > 0 ){
		$query = "SELECT * FROM `FormItems` where `FormID` = '".$refpts["ID"]."' order by `ID`"; 
		if ($result = $mysqli->query($query)) {
			while($row = mysqli_fetch_array($result)){
				$items[] = $row;
			}
		}
		$refpts['Items'] = $items;
	}
	
	Close($mysqli, $result);
	return $refpts;
}

function DeleteReflectionPoint($id){
	$mysqli = Connect();
	$mysqli->query("delete from `Forms` where `ID` = '".$id."'");
	$mysqli->query("delete from `FormItems` where `FormID` = '".$id."'");
	Close($mysqli, $result);
}

function GetUpcomingRefPts(){
	$mysqli = Connect();
	$currdate = Date("Y-m-d");
	$date = Date("Y-m-d", strtotime($currdate." +1 day"));
	$refpts = array();	if ($result = $mysqli->query("select f.*, g.`Title` from `Forms` f, `Games` g where f.`FormType` = 'Daily' and f.`Daily` >=  '".$date."' and f.`ObjectID` = g.`ID` order by `Daily`")) {
		while($row = mysqli_fetch_array($result)){
			$refpts[] = $row;
		}
	}
	Close($mysqli, $result);
	return $refpts;
}

function SaveRefPtSchedule($schedule){
	$mysqli = Connect();
	$refpts = explode("||", $schedule);
	if(sizeof($refpts) > 0){
		foreach($refpts as $pt){
			$pcs = explode(",", $pt);
			if(sizeof($pcs) > 0 && $pcs[0] > 0){
				$mysqli->query("Update `Forms` set `Daily` = '".$pcs[1]."' where `ID` = '".$pcs[0]."'");
			}
		}
	}
	Close($mysqli, $result);
}

function GetRefPtSearch($new, $search){
	$mysqli = Connect();
	$refpts = array();	
	$query = "select * from `Forms` f, `Games` g where `FormType` = 'Daily' and f.`ObjectID` =g.`ID`";
	if($new == "true" && $search != '')
		$query = $query . " and `Daily` = '0000-00-00' and `Header` like '%".$search."%'";
	else if($new == "false" && $search != '')
		$query = $query . " and `Daily` != '0000-00-00' and `Header` like '%".$search."%'";
	else
		$query = $query . " and `Daily` = '0000-00-00' ";
	
	if ($result = $mysqli->query($query)) {
		while($row = mysqli_fetch_array($result)){
			$refpts[] = $row;
		}
	}
	Close($mysqli, $result);
	return $refpts;
}
