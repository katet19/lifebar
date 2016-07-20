<?php

function SubmitDailyForm($userid, $question, $subquestion, $formtype, $approved, $objectid, $objectType, $default, $items, $itemurls, $itemtype, $finished){
	$mysqli = Connect();
	$mysqli->query("insert into `Forms` (`Header`,`SubHeader`,`FormType`,`CreatedBy`,`Approved`,`ObjectID`, `ObjectType`,`Finished`) values ('".mysqli_real_escape_string($mysqli, $question)."','".mysqli_real_escape_string($mysqli, $subquestion)."','$formtype','$userid','$approved','$objectid','$objectType','$finished')");
	if ($result = $mysqli->query("select * from `Forms` where `CreatedBy` = '".$userid."' order by `ID` DESC LIMIT 0,1")) {
		while($row = mysqli_fetch_array($result)){
			$formID = $row['ID'];
		}
	}
	$itemarray = explode("||",$items);
	$itemurlarray = explode("||",$itemurls);
	$i=0;
	foreach($itemarray as $item){
		if($item != ''){
			$mysqli->query("insert into `FormItems` (`Choice`,`FormID`,`Type`,`IsDefault`,`URL`) values ('".mysqli_real_escape_string($mysqli, $item)."','$formID','$itemtype','$default','".mysqli_real_escape_string($mysqli, $itemurlarray[$i])."')");
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
