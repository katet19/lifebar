<?php require_once 'controller_database.php';

ProcessEmailQue();

function ProcessEmailQue(){
	$mysqli = Connect();
	$sentOneUpEmail  = array();
	$body = '';
	$prevUser = '';
	$prevTo = '';
	$prevSubject = '';
	$prevFrom = '';
	$count = 0;
	if ($result = $mysqli->query("SELECT * FROM  `Email` where `Type` = '1up' order by `UserID`")) {
		while($row = mysqli_fetch_array($result)){
			if($prevTo != $row['ToField'] && $prevTo != ''){
				$body = GetHeader().$body.GetFooter($prevUser, '1up');
				if($count > 1){
					$prevSubject = "You have been given 1ups!";
					$prevFrom = "Lifebar Notifications";
				}
				SendEmailWithFrom($prevTo, $prevSubject, $body, $prevFrom);
				
				$sentOneUpEmail[] = $row['UserID'];
				$body = '';
				$prevSubject = '';
				$count = 0;
			}
				
				
			if($count > 0){
				$body."<div style='border-bottom:1px solid gray;width:100%;margin-bottom:30px;'></div>";
				$body = $body.$row['Body'];	
			}else{
				$body = $body.$row['Body'];
			}
			
			$count++;
			$prevUser = $row['UserID'];
			$prevTo = $row['ToField'];
			$prevFrom = $row['FromField'];
			$prevSubject = $row['Subject'];	
			$mysqli->query("DELETE FROM  `Email` where `ID` = '".$row['ID']."'");
		}
	}
	//Catch the last one in the loop
	if($prevTo != ''){
		$body = GetHeader().$body.GetFooter($prevUser, '1up');
		if($count > 1){
			$prevSubject = "You have been given 1ups!";
			$prevFrom = "Lifebar Notifications";
		}
		SendEmailWithFrom($prevTo, $prevSubject, $body, $prevFrom);
		$sentOneUpEmail[] = $row['UserID'];
	}
	
	$sentFollowingEmail  = array();
	if ($result = $mysqli->query("SELECT * FROM  `Email` where `Type` = 'Following' order by `UserID`")) {
		while($row = mysqli_fetch_array($result)){
			if(!in_array($row['UserID'], $sentOneUpEmail)){
				
				
			}
			$sentFollowingEmail[] = $row['UserID'];
		}
	}
}

function GetHeader(){
	$body = $body."<div style='background-color:rgb(237, 236, 236);width:100%;text-align:center;position:relative;'>";
	$body = $body."<div style='display:inline-block;width:500px;background-color:#fff;'>";
	$body = $body."<div style='width:100%;text-align:center;padding:15px 0;background-color:#D32F2F;'><img style='max-height:40px;' src='http://lifebar.io/Images/Generic/LifebarLogoEmail.png'></div>";
	return $body;
}

function GetFooter($userid, $type){
	$body = $body."<div style='border-bottom:1px solid lightgray;width:100%;margin-bottom:30px;'></div>";
	$body = $body."<div style='color:#d3d3d3;text-align:center;padding:0 50px;font-size:0.9em;'>PO BOX 2321 Hanska MN 56401</div>";
	$body = $body."<a href='http://lifebar.io/utilities/unsubscribe.php?id=".$userid."&type=".$type."' style='text-decoration:underline;text-align:center;font-size:0.8em;color:rgba(0,0,0,0.8);margin-bottom:30px;display: inline-block;'>unsubscribe from this list</a>";
	$body = $body."</div>";
	$body = $body."</div>";
	return $body;
}

function SendEmailWithFrom($to, $subject, $message, $from){
	$headers = "From: ".$from." <notify@lifebar.io>\r\n";
	$headers .= "Reply-To: notify@lifebar.io\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	mail($to, $subject, $message, $headers);
}

?>