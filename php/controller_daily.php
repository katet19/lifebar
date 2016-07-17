<?php

function SubmitDailyForm($userid, $question, $approved, $objectid, $objectType, $default, $items, $itemtype){
	$mysqli = Connect();
	$mysqli->query("insert into `Forms` (`Header`,`CreatedBy`,`Approved`,`ObjectID`, `ObjectType`) values ('".mysqli_real_escape_string($mysqli, $question)."','$userid','$approved','$objectid','$objectType')");
	if ($result = $mysqli->query("select * from `Forms` where `CreatedBy` = '".$userid."' order by `ID` DESC LIMIT 0,1")) {
		while($row = mysqli_fetch_array($result)){
			$formID = $row['ID'];
		}
	}
	$itemarray = explode("||",$items);
	foreach($itemarray as $item){
		if($item != ''){
			$mysqli->query("insert into `FormItems` (`Choice`,`FormID`,`Type`,`IsDefault`) values ('".mysqli_real_escape_string($mysqli, $item)."','$formID','$itemtype','$default')");
			if($default != "No")
				$default = "No";
		}
	}
	Close($mysqli, $result);
}