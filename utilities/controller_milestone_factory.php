<?php require_once 'controller_database.php';
require_once 'controller_game.php';
require_once 'controller_giantbomb.php';

//AddMilestoneForDevelopers();
//AddMilestoneForFranchise();
//AddMilestoneForPlatforms();
//FixPlatforms();
//SetImagesForDevelopers();
//SetImagesForFranchises();

function FixPlatforms(){
	$query = "select * from `Sub-Experiences` where `Type` = 'Played' and `Platform` != ''";
	$mysqli = Connect();
	if ($result = $mysqli->query($query)){
		while($row = mysqli_fetch_array($result)){
			$platforms = explode("\n", $row['Platform']);
			unset($platformID);
			foreach($platforms as $platform){ 
				$platform = trim($platform);
				if($platform != ""){
					$platformquery = "select * from `Link_Platforms` where `Name` = '".$platform."'";
					if ($platformresult = $mysqli->query($platformquery)){
						while($platformrow = mysqli_fetch_array($platformresult)){
							echo $platform." has an ID of".$platformrow['GBID']." (".$row['ID'].")<br>";	
							$platformID[] = $platformrow['GBID'];
						}
					}
				}
			}
			if(sizeof($platformID) > 0){
				$mysqli->query("update `Sub-Experiences` set `PlatformIDs` = '".implode(",",$platformID)."' where `ID` = '".$row['ID']."'");
			}
		}
	}
}

function AddMilestoneForPlatforms(){
	$count = 1;
	//loop through all franchises
	$query = "select * from `Link_Platforms`";
	$mysqli = Connect();
	if ($result = $mysqli->query($query)){
		while($row = mysqli_fetch_array($result)){
			//loop through all ranks of a milestone
			$platform = $row['Name'];
			$desc = "Play games on the ".$platform."";
			$type = "Played";
			$category = "Platform";
			$objectid = $row['GBID'];
			$subquery = "select count(*) as cnt from `Game_Platforms` where `PlatformID` = '".$row['GBID']."' ";
			if ($subresult = $mysqli->query($subquery)){
				while($subrow = mysqli_fetch_array($subresult)){
					$total = $subrow['cnt'];
				}
			}
				
			if($total <= 5){
				$level1 = 5;
				$level2 = 0;
				$level3 = 0;
				$level4 = 0;
				$level5 - 0;
			}else{
				$level5 = round($total * 0.8);
				$level4 = round($level5 / 2);
				if($level4 >= 200){
					$level3 = round($level4 / 2);
					$level2 = round($level3 / 2);
					$level1 = round($level2 / 2);
					if($level1 > 10){
						$level1 = 10;
						$level2 = 30;
						$level3 = 75;
						$level4 = 200;
					}
				}else{
					$level5 =0;
					$level4 = round($total * 0.8);
					$level3 = round($level4 / 2);
					if($level3 >= 50){
						$level2 = round($level3 / 2);
						$level1 = round($level2 / 2);
						if($level1 > 10){
							$level1 = 10;
							$level2 = 30;
							$level3 = 50;
						}
					}else{
						$level4 = 0;
						$level3 = round($total * 0.8);
						$level2 = round($level3 / 2);
						if($level2 >= 10){
							$level1 = 5;
						}else{
							$level3 = 0;
							$level2 = round($total * 0.8);
							$level1 = round($level2 / 2);
						}
					}
					
				}
			}
			
			$validation = "SELECT count(*) as cnt from `Sub-Experiences` e WHERE `UserID` = [UserID] AND `Type` =  'Played' AND `Archived` = 'No' AND (`PlatformIDs` = '".$objectid."' OR `PlatformIDs` like '%,".$objectid."' OR `PlatformIDs` like '%".$objectid.",%' OR `PlatformIDs` like '".$objectid.",%')";
			//Save
			echo $count.") ".$platform." has ".$total." games on their platform. Level 1(".$level1.") Level 2(".$level2.") Level 3(".$level3.") Level 4(".$level4.") Level 5(".$level5.")<br>";
			$images = RequestGiantBombPlatformImage($objectid);
			SaveNewMilestone($platform, $desc, $type, $validation, $level1, $level2, $level3, $level4, $level5, $category, $subcategory,$objectid, $images[1]);
			$count++;
		}
	}
}

function AddMilestoneForFranchise(){
	$count = 1;
	//loop through all franchises
	$query = "select * from `Link_Franchises` where `Name` = 'Assassin\'s Creed'";
	$mysqli = Connect();
	if ($result = $mysqli->query($query)){
		while($row = mysqli_fetch_array($result)){
			//loop through all ranks of a milestone
			$franchise = mysqli_real_escape_string($mysqli, $row['Name']);
			$desc = "Experience games from the ".$franchise." franchise";
			$type = "XP";
			$category = "Franchises";
			$objectid = $row['GBID'];
			$subquery = "select count(*) as cnt from `Game_Franchises` where `FranchiseID` = '".$row['GBID']."' ";
			if ($subresult = $mysqli->query($subquery)){
				while($subrow = mysqli_fetch_array($subresult)){
					$total = $subrow['cnt'];
				}
			}
			
			if($total >= 3){
				$level1 = 3;
				$level2 = 0;
				$level3 = 0;
				$level4 = 0;
				$level5 = 0;
				
				if($total <= 3){
					$level1 = 3;
				}else{
					$level4 = round($total);
					$level3 = round($level4 / 2);
					if($level3 > 3){
						$level2 = round($level3 / 2);
						if($level2 > 3){
							$level1 = round($level2 / 2);
						}else{
							$level1 = $level2;
							$level2 = $level3;
							$level3 = $level4;
							$level4 = 0;
							$level5 = 0;
						}	
					}else{
						$level4 = 0;
						$level3 = 0;
						$level2 = round($total);
						$level1 = round($level2 / 2);
					}
				}
				
				$validation = "SELECT count(*) as cnt from `Experiences` e, `Games` g, `Game_Franchises` d WHERE `UserID` = [UserID] AND `Tier` >  0 AND d.`FranchiseID` = '".$objectid."' AND d.`GBID` = g.`GBID` and g.`ID` = e.`GameID`";
				//Save
				echo $count.") ".$franchise." has ".$total." games under their belt. Level 1(".$level1.") Level 2(".$level2.") Level 3(".$level3.") Level 4(".$level4.") Level 5(".$level5.")<br>";
				$images = RequestGiantBombFranchiseImage($objectid);
				SaveNewMilestone($franchise, $desc, $type, $validation, $level1, $level2, $level3, $level4, $level5, $category, $subcategory,$objectid, $images[2]);
				$count++;
			}else{
				//If there are less than 3 games in a franchise, skip!
				//echo "Skipping ".$franchise." has only ".$total." games.<br>";
			}
		}
	}
}

function SetImagesForFranchisesFromGB(){
	$query = "select * from `Milestones` where `Category` = 'Franchises' LIMIT 900,100";
	$mysqli = Connect();
	if ($result = $mysqli->query($query)){
		while($row = mysqli_fetch_array($result)){
			$images = RequestGiantBombFranchiseImage($row['ObjectID']);
			if(isset($images) && $images[2] != ""){
				UpdateImage($row['ID'], $images[2]);
			}else if(isset($images) && $images[1] != ""){
				UpdateImage($row['ID'], $images[1]);
			}
		}
	}
}

function SetImagesForFranchises(){
	$query = "select * from `Milestones` where `Category` = 'Franchises' and `Image` = ''";
	$mysqli = Connect();
	if ($result = $mysqli->query($query)){
		while($row = mysqli_fetch_array($result)){
			$query2 = "select `ImageSmall` from `Game_Franchises`f, `Games`g where `FranchiseID` = '".$row['ObjectID']."' and f.`GBID` = g.`GBID`";
			if ($result2 = $mysqli->query($query2)){
				while($row2 = mysqli_fetch_array($result2)){
					if($row2["ImageSmall"] != ""){
						echo "Image: ".$row2["ImageSmall"]."<br>";
						UpdateImage($row['ID'], $row2["ImageSmall"]);
					}
				}
			}
		}
	}
}

function UpdateFranchise($id){
	$franchise = RequestGiantBombFranchise($id);
	print_r($franchise);
}


function AddMilestoneForDevelopers(){
	$count = 1;
	//loop through all developers
	$query = "select * from `Link_Developers`";
	$mysqli = Connect();
	if ($result = $mysqli->query($query)){
		while($row = mysqli_fetch_array($result)){
			//loop through all ranks of a milestone
			$developer = $row['Name'];
			$desc = "Experience games developed by ".$developer;
			$type = "XP";
			$category = "Developers";
			$objectid = $row['GBID'];
			$subquery = "select count(*) as cnt from `Game_Developers` where `DeveloperID` = '".$row['GBID']."' ";
			if ($subresult = $mysqli->query($subquery)){
				while($subrow = mysqli_fetch_array($subresult)){
					$total = $subrow['cnt'];
				}
			}
			
			if($total >= 5){
				$level1 = 5;
				$level2 = 0;
				$level3 = 0;
				$level4 = 0;
				$level5 = 0;
				
				if($total <= 8){
					$level1 = 5;
				}else{
					$level4 = round($total * 0.8);
					$level3 = round($level4 / 2);
					if($level3 > 5){
						$level2 = round($level3 / 2);
						if($level2 > 5){
							$level1 = round($level2 / 2);
						}else{
							$level1 = $level2;
							$level2 = $level3;
							$level3 = $level4;
							$level4 = 0;
							$level5 = 0;
						}	
					}else{
						$level4 = 0;
						$level3 = 0;
						$level2 = round($total * 0.8);
						$level1 = round($level2 / 2);
					}
				}
				
				$validation = "SELECT count(*) as cnt from `Experiences` e, `Games` g, `Game_Developers` d WHERE `UserID` = [UserID] AND `Tier` >  0 AND d.`DeveloperID` = '".$objectid."' AND d.`GBID` = g.`GBID` and g.`ID` = e.`GameID`";
				//Save
				echo $count.") ".$developer." has ".$total." games under their belt. Level 1(".$level1.") Level 2(".$level2.") Level 3(".$level3.") Level 4(".$level4.") Level 5(".$level5.")<br>";
				SaveNewMilestone($developer, $desc, $type, $validation, $level1, $level2, $level3, $level4, $level5, $category, $subcategory,$objectid);
				$count++;
			}else{
				//If there are less than 5 games by a developer, skip!
				//echo "Skipping ".$developer." they only have ".$total." games under their belt.<br>";
			}
		}
	}
}

function SetImagesForDevelopers(){
	$query = "select * from `Milestones` where `Category` = 'Developers' LIMIT 700,100";
	$mysqli = Connect();
	if ($result = $mysqli->query($query)){
		while($row = mysqli_fetch_array($result)){
			$images = RequestGiantBombDeveloperImage($row['ObjectID']);
			if(isset($images) && $images[2] != ""){
				UpdateImage($row['ID'], $images[2]);
			}else if(isset($images) && $images[1] != ""){
				UpdateImage($row['ID'], $images[1]);
			}
		}
	}
}



function SaveNewMilestone($name, $desc, $type, $validation, $level1, $level2, $level3, $level4, $level5, $category, $subcategory,$objectid, $image){
	$mysqli = Connect();
	if($subcategory == "")
		$subcategory = -1;
	$mysqli->query("insert into `Milestones` (`Name`,`Description`,`Type`,`Validation`,`Level1`,`Level2`,`Level3`,`Level4`,`Level5`,`Category`,`Parent`,`ObjectID`,`Image`) values ('$name','$desc','$type','".mysqli_real_escape_string($mysqli, $validation)."','$level1','$level2','$level3','$level4','$level5','$category','$subcategory','$objectid','$image')");
}

function UpdateImage($id, $image){
	$mysqli = Connect();
	$mysqli->query("update `Milestones` set `Image` = '".$image."' where `ID` = '".$id."'");
}

?>