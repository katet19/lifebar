<?php require_once 'controller_database.php';
require_once 'model_game.php';

function GetGameByGBID($gbid){
	$game = "";
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Games` where `GBID` = ".$gbid)) {
		while($row = mysqli_fetch_array($result)){
			if($row["Title"] != ""){
				$game = array('ID' => $row["ID"], 'Name' => $row["Title"]);
			}
		}
	}
	
	if($game == ""){
		$game = array('Error' => 'GBID does not exist in Lifebar DB');
	}
	
	return $game;
}

if(isset($_GET['GBID']) && $_GET['GBID'] > 0){
	echo json_encode(GetGameByGBID($_GET['GBID']));
}else{
	echo json_encode(array('Error' => 'Invalid GBID'));
}

?>