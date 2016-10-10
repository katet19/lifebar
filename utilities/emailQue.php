<?php require_once 'controller_database.php';

ProcessEmailQue();

function ProcessEmailQue(){
	$mysqli = Connect();
	$sentEmail  = array();
	$body = array();
	$message = '';
	$prevUser = '';
	$prevTo = '';
	$prevSubject = '';
	$prevFrom = '';
	$prevCoreID = '';
	$count = 0;
	$internalcount = 0;
	if ($result = $mysqli->query("SELECT * FROM  `Email` where `Type` = '1up' order by `UserID`, `CoreID`")) {
		while($row = mysqli_fetch_array($result)){
			if($prevTo != $row['ToField'] && $prevTo != ''){
				$imploded = implode($body);
				if($imploded != ''){
					$message = GetHeader().$imploded.GetFooter($prevUser, '1up');
					if($count > 1){
						$prevSubject = "You have been given 1ups!";
						$prevFrom = "Lifebar Notifications";
					}
					$message = str_replace("<MINDTHEGAP>", "", $message);	
					SendEmailWithFrom($prevTo, $prevSubject, $message, $prevFrom);
				}
				
				$sentEmail[] = $row['UserID'];
				unset($body);
				$message= '';
				$prevSubject = '';
				$count = 0;
				$internalcount = 0;
				$prevCoreID = '';
			}
			
			if($count >= 3){
				$mysqli->query("DELETE FROM `Email` where `ID` = '".$row['ID']."'");
			}else{
				if($prevCoreID != $row['CoreID']){	
					if($internalcount == 1){
						$message = str_replace("<MINDTHEGAP>", "and ".$internalcount." other", $message);	
					}else if($internalcount > 1){
						$message = str_replace("<MINDTHEGAP>", "and ".$internalcount." others", $message);
					}
					
					$body[] = $message;
					$message = '';
					$internalcount = 0;
					
					if($count > 0){
						$message = $message."<div style='border-bottom:1px solid gray;width:100%;margin-bottom:30px;'></div>";
						$message = $message.$row['Body'];	
					}else{
						$message = $message.$row['Body'];
					}
				}else{
					$internalcount++;
				}
				
				$count++;
				$prevUser = $row['UserID'];
				$prevTo = $row['ToField'];
				$prevFrom = $row['FromField'];
				$prevSubject = $row['Subject'];	
				$prevCoreID = $row['CoreID'];
				$mysqli->query("DELETE FROM  `Email` where `ID` = '".$row['ID']."'");
			}
		}
	}
	//Catch the last one in the loop
	if($prevTo != ''){
		if($internalcount == 1){
			$message = str_replace("<MINDTHEGAP>", "and ".$internalcount." other", $message);	
		}else if($internalcount > 1){
			$message = str_replace("<MINDTHEGAP>", "and ".$internalcount." others", $message);
		}
		$body[] = $message;
		
		$imploded = implode($body);
		if($imploded != ''){
			$message = GetHeader().$imploded.GetFooter($prevUser, '1up');
			if($count > 1){
				$prevSubject = "You have been given 1ups!";
				$prevFrom = "Lifebar Notifications";
			}
			$message = str_replace("<MINDTHEGAP>", "", $message);	
			SendEmailWithFrom($prevTo, $prevSubject, $message, $prevFrom);
			$sentEmail[] = $row['UserID'];
		}
	}
	
	
	//Reset vars for Following Emails
	$body = array();
	$message = '';
	$prevUser = '';
	$prevTo = '';
	$prevSubject = '';
	$prevFrom = '';
	$prevCoreID = '';
	$count = 0;
	$internalcount = 0;
	if ($result = $mysqli->query("SELECT * FROM  `Email` where `Type` = 'Following' order by `UserID`")) {
		while($row = mysqli_fetch_array($result)){
			if(!in_array($row['UserID'], $sentEmail)){
				if($prevTo != $row['ToField'] && $prevTo != ''){
					$imploded = implode($body);
					if($imploded != ''){
						$message = GetHeader().$imploded.GetFooter($prevUser, 'Following');
						if($count > 1){
							$prevSubject = "You have new followers!";
							$prevFrom = "Lifebar Notifications";
						}
						SendEmailWithFrom($prevTo, $prevSubject, $message, $prevFrom);
					}
					
					$sentEmail[] = $row['UserID'];
					unset($body);
					$message= '';
					$prevSubject = '';
					$count = 0;
					$internalcount = 0;
					$prevCoreID = '';
				}
				
				if($count >= 3){
					$mysqli->query("DELETE FROM `Email` where `ID` = '".$row['ID']."'");
				}else{
					if($prevCoreID != $row['CoreID']){	
						$body[] = $message;
						$message = '';
						
						if($count > 0){
							$message = $message."<div style='border-bottom:1px solid gray;width:100%;margin-bottom:30px;'></div>";
							$message = $message.$row['Body'];	
						}else{
							$message = $message.$row['Body'];
						}
					}
					
					$count++;
					$prevUser = $row['UserID'];
					$prevTo = $row['ToField'];
					$prevFrom = $row['FromField'];
					$prevSubject = $row['Subject'];	
					$prevCoreID = $row['CoreID'];
					$mysqli->query("DELETE FROM  `Email` where `ID` = '".$row['ID']."'");
				}
			}
		}
	}
	
	//Catch the last one in the loop
	if($prevTo != ''){
		$body[] = $message;
		$imploded = implode($body);
		if($imploded != ''){
			$message = GetHeader().$imploded.GetFooter($prevUser, 'Following');
			if($count > 1){
				$prevSubject = "You have new followers!";
				$prevFrom = "Lifebar Notifications";
			}
			SendEmailWithFrom($prevTo, $prevSubject, $message, $prevFrom);
		}
	}
}

function GetHeader(){
	$body = $body."<div style='background-color:rgb(237, 236, 236);width:100%;text-align:center;position:relative;'>";
	$body = $body."<div style='display:inline-block;width:500px;background-color:#fff;'>";
	$body = $body."<div style='width:100%;text-align:center;padding:15px 0;background-color:#673AB7;'><img style='max-height:40px;' src='http://lifebar.io/Images/Generic/LifebarLogoEmail.png'></div>";
	return $body;
}

function GetFooter($userid, $type){
	$body = $body."<div style='border-bottom:1px solid lightgray;width:100%;margin-bottom:30px;'></div>";
	$body = $body."<div style='color:#d3d3d3;text-align:center;padding:0 50px;font-size:0.9em;'>PO BOX 2321 Hanska MN 56401</div>";
	$body = $body."<a href='http://lifebar.io/utilities/unsubscribe.php?id=".$userid."&type=".$type."' style='text-decoration:underline;text-align:center;font-size:0.8em;color:rgba(0,0,0,0.8);margin-bottom:30px;display: inline-block;'>Don't receive these types of notifications</a>";
	$body = $body."</div>";
	$body = $body."</div>";
	$body = $body."<img style='display:none;' src='http://www.google-analytics.com/collect?v=1&tid=UA-52980217-1&cid=".$userid."&t=event&ec=email&ea=open&el=recipient_id&cs=".$type."Emails&cm=email&cn=".$type."_Campaign'>";
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
