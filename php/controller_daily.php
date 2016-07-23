<?php

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
