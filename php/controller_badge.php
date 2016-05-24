<?php
require_once "includes.php";

function GetAllBadges(){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Badges`")) {
		while($row = mysqli_fetch_array($result)){
			$badge = new Badge($row["ID"],
				$row["Title"],
				$row["URL"],
				$row["File"],
				$row["Description"]);
			$badges[] = $badge;
		}
	}
	Close($mysqli, $result);
	return $badges;
}

function GetBadge($badgeid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Badges` where `ID` = '".$badgeid."'")) {
		while($row = mysqli_fetch_array($result)){
			$badge = new Badge($row["ID"],
				$row["Title"],
				$row["URL"],
				$row["File"],
				$row["Description"]);
		}
	}
	Close($mysqli, $result);
	return $badge;
}

function GetAllBadgesForUser($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Badges_Users` where `UserID` = '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			$badge = GetBadge($row["BadgeID"]);
			$badges[] = $badge;
		}
	}
	Close($mysqli, $result);
	return $badges;
}

function GetAllBadgeForUserList($userid){
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Badges_Users` where `UserID` = '".$userid."'")) {
		while($row = mysqli_fetch_array($result)){
			$badges[] = $row["BadgeID"];
		}
	}
	Close($mysqli, $result);
	return $badges;
}

function GiveBadgeAccess($userid, $badgeid){
	$mysqli = Connect();
	$mysqli->query("insert into `Badges_Users` (`UserID`,`BadgeID`) VALUES ('".$userid."','".$badgeid."')");
	Close($mysqli, $result);
}

function RemoveBadgeAccess($userid, $badgeid){
	$mysqli = Connect();
	$mysqli->query("DELETE FROM `Badges_Users` where `UserID` = '".$userid."' and `BadgeID` = '".$badgeid."'");
	Close($mysqli, $result);
}

function UnequipBadge($userid, $badgeid){
	$mysqli = Connect();
	$mysqli->query("update `Users` set `Badge` = '' where `ID` = '".$userid."'");
	Close($mysqli, $result);
}

function EquipBadge($userid, $badgeid){
	$mysqli = Connect();
	$badge = GetBadge($badgeid);
	echo "update `Users` set `Badge` = '".$badge->_file."' where `UserID` = '".$userid."'";
	$mysqli->query("update `Users` set `Badge` = '".$badge->_file."' where `ID` = '".$userid."'");
	Close($mysqli, $result);
}
?>
