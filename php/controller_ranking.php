<?php
require_once "includes.php";

function GetRankedList($userid){
	$mysqli = Connect();
	$ranklist = array();
	$myquery = "select g.*, e.`Rank`, e.`Tier` from `Experiences` e, `Games` g where e.`UserID` = '".$userid."' and g.`ID` = e.`GameID` and e.`Rank` > 0 order by `Rank`,`Title`";
	if ($result = $mysqli->query($myquery)) {
		while($row = mysqli_fetch_array($result)){
			$ranklist[] = GameObject($row);
		}
	}
	Close($mysqli, $result);
	return $ranklist;
}
?>