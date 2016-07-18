<?php

function SubmitDailyForm($userid, $question, $formtype, $approved, $objectid, $objectType, $default, $items, $itemtype, $finished){
	$mysqli = Connect();
	$mysqli->query("insert into `Forms` (`Header`,`FormType`,`CreatedBy`,`Approved`,`ObjectID`, `ObjectType`,`Finished`) values ('".mysqli_real_escape_string($mysqli, $question)."','$formtype','$userid','$approved','$objectid','$objectType','$finished')");
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
