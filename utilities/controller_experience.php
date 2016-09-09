<?php
require_once "controller_database.php";
require_once "model_experience.php";

function GetSubExperiences($userid, $gameid, $type){
	$sexp = array();
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Sub-Experiences` where `UserID` = '".$userid."' and `GameID` = '".$gameid."' and `Type` = '".$type."' order by `ID` DESC")) {
		while($row = mysqli_fetch_array($result)){
			$subexp = "";
			$subexp = new SubExperience($row['ID'], 
				$row['ExpID'], 
				$row['UserID'], 
				$row['GameID'],
				$row['Type'], 
				$row['Source'], 
				$row['Date'], 
				$row['URL'],
				$row['Length'], 
				$row['Thoughts'], 
				$row['ArchiveQuote'], 
				$row['ArchiveTier'], 
				$row['DateEntered'], 
				$row['Completed'], 
				$row['Mode'], 
				$row['Platform'],
				$row['DLC'],
				$row['Alpha'],
				$row['Beta'],
				$row['Early Access'],
				$row['Demo'],
				$row['Streamed'],
				$row['Archived']);
				
				if($subexp != "")
					$sexp[] = $subexp;
		}
	}
	return $sexp;
}

?>