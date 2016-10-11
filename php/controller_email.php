<?php
require_once "includes.php";

function AddInviteEmail($user, $email, $custom){
	$subject = $user->_username." invited you to Lifebar!";
	$from = $user->_username." [via Lifebar]";
	$link = "http://lifebar.io";
	$message = $message."<h1 style='color:#0a67a3;'>".$user->_username." wants you to join them on Lifebar!</h1>";
	if($custom != ''){
		$message = $message."<p style='font-size:16px;font-weight:400;'>".$user->_username." says:</p>";
		$message = $message."<p style='font-size:16px;font-style:italic;text-align:left;'>".$custom."</p><br><br>";
	}
	$message = $message."<a href='http://lifebar.io' style='text-decoration:none;font-weight:bold;font-size:1.25em;color:white;background-color:#F50057;cursor:pointer;text-align:center;padding:5px 0px;width:100px;margin-bottom:30px;display:inline-block;'>Sign Up!</a>";
	$message = $message."<p style='font-size:1.5em;text-align:left;'>Lifebar will help you <b>discover</b> new games, <b>share</b> your experiences and <b>visualize</b> a lifetime of gaming.</p>";
	$message = $message."<div style='text-align:left;'>";
	$message = $message."<p style='color:#0a67a3;font-size:20px;'>Play Games. Watch Games. <b>Earn XP!</b></p>";
	$message = $message."Whether you played a game or watched others play it, your time spent informs your thoughts on that game. At Lifebar we acknowledge they are different types of experiences, but want to recognize the way we experience games is changing rapidly and capturing that variety is important.<br><br>";
	$message = $message."<p style='color:#0a67a3;font-size:20px;'>A profile that <b>reflects</b> your gaming life</p>";
	$message = $message."As you enter gaming experiences your profile will display franchises and developers you tend to favor, what you have recently played/watched, your favorite games, anticipated new releases and more!<br><br>";
	$message = $message."<p style='color:#0a67a3;font-size:20px;'>Don't aggregate. <b>Personalize.</b></p>";
	$message = $message."Your activity feed will show only the critic reviews that you trust and enjoy reading. As your friends play games, share their feelings about games they have watched, bookmark games for later and update their thoughts you will see it all on your personalized activity feed.<br><br>";
	$message = $message."<p style='color:#0a67a3;font-size:20px;font-weight:500;'>Try out the <b>beta</b>!</p>";
	$message = $message."Lifebar is a work in progress, we are proud of what we have built so far, but we know there is so much more that could be done. Please join us in building a service that captures what we experience, why we experience it and how we experience video games!<br><br>";
	$message = $message."</div>";
	$message = $message."<a href='http://lifebar.io' style='text-decoration:none;font-weight:bold;font-size:1.25em;color:white;background-color:#F50057;cursor:pointer;text-align:center;padding:5px 15px;margin-bottom:30px;display:inline-block;'>Start building your Lifebar today!</a><br>";
	$message = $message."Thanks,<br>The Lifebar Team<br><br>";
	InsertToEmailQue(trim($email), $from, $user->_id, $subject, $message, 'Invite', '');
	
}

function AddAgreedEmail($gameid, $userid, $agreedwith, $eventid){
	if(UserIsSub($agreedwith, '1up')){
		$activeUser = GetUser($userid);
		$emailUser = GetUser($agreedwith);
		$event = GetEvent($eventid);
		$game = GetGame($gameid);
		$username = DisplayNameReturn($activeUser);
		$subject = $username." gave you a 1up!";
		$from = $username." [via Lifebar]";
		$body = $body."<div style='width:100%;padding:20px 0;text-align:center;'><div style='width:80px;border-radius:50%;display: inline-block;height:80px;background:url(".$activeUser->_thumbnail.") 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;'></div></div>";
		$body = $body."<div style='font-size:1.5em;padding: 0 50px 20px;'><b>".$username."</b> <MINDTHEGAP> appreciated your thoughts on <i>".$game->_title."</i> and gave you a 1up!</div>";
		$body = $body."<a href='http://lifebar.io/1/u.php?i=e".$eventid."&ga=email' style='text-decoration:none;font-weight:bold;font-size:1.25em;color:white;background-color:#F50057;cursor:pointer;text-align:center;padding:5px 0px;width:100px;margin-bottom:30px;display:inline-block;'>VIEW</a>";
		$body = $body."<div style='color: #212121 !important;padding: 20px 50px 50px;'><b>\"</b>".$event->_quote."<b>\"</b></div>";
		InsertToEmailQue($emailUser->_email, $from, $agreedwith, $subject, $body, '1up', $eventid);
	}
}

function AddFollowingEmail($userid, $following){
	if(UserIsSub($following, 'Following')){
		$activeUser = GetUser($userid);
		$emailUser = GetUser($following);
		$username = DisplayNameReturn($activeUser);
		$subject = $username." is following you!";
		$from = $username." [via Lifebar]";
		$body = $body."<div style='width:100%;padding:20px 0;text-align:center;'><div style='width:80px;border-radius:50%;display: inline-block;height:80px;background:url(".$activeUser->_thumbnail.") 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;'></div></div>";
		$body = $body."<div style='font-size:1.5em;padding: 0 50px 20px;'><b>".$username."</b> wants to see what you think about games, who you follow and what collections you will build!</div>";
		$body = $body."<a href='http://lifebar.io/1/u.php?i=u".$userid."&ga=email' style='text-decoration:none;font-weight:bold;font-size:1.25em;color:white;background-color:#F50057;cursor:pointer;text-align:center;padding:5px 0px;width:100px;margin-bottom:30px;display:inline-block;'>VIEW PROFILE</a>";
		InsertToEmailQue($emailUser->_email, $from, $following, $subject, $body, 'Following', $userid);
	}
}

function InsertToEmailQue($to, $from, $userid, $subject, $body, $type, $coreid){
	$mysqli = Connect();
	if($to != '' && $from != '' && $userid != '' && $subject != '' && $body != '' && $type != ''){
		$result = $mysqli->query("INSERT INTO `Email` (`ToField`,`FromField`,`UserID`,`Subject`,`Body`,`Type`,`CoreID`,`Expiration`) values ('$to','$from','$userid','".mysqli_real_escape_string($mysqli, $subject)."', '".mysqli_real_escape_string($mysqli, $body)."', '$type', '$coreid', '$expiration')");
	}
	Close($mysqli, $result);
}

function UserIsSub($userid, $type){
	$mysqli = Connect();
	$sub = true;
	if ($result = $mysqli->query("SELECT * FROM  `EmailUnsub` where `UserID` = '".$userid."' and `Type` = '".$type."'")) {
		while($row = mysqli_fetch_array($result)){
			$sub = false;
		}
	}
	Close($mysqli, $result);
	return $sub;
}


function SignupEmail($to){
	$subject = "Welcome to Lifebar!";
	$link = "http://lifebar.io";
	$message = $message."<h1 style='color:#0a67a3;'>Welcome to the Lifebar beta!</h1>";
	$message = $message."<p style='font-size:16px;'>Lifebar represents your life playing and watching video games. With Lifebar you will be able to <b>discover</b> new games, <b>share</b> your experiences and <b>visualize</b> a lifetime of gaming.</p>";
	$message = $message."<p style='color:#e01b84;font-size:20px;'>Play Games. Watch Games. <b>Earn XP!</b></p>";
	$message = $message."Whether you played a game or watched others play it, your time spent informs your thoughts on that game. At Lifebar we acknowledge they are different types of experiences, but want to recognize the way we experience games is changing rapidly and capturing that variety is important.<br><br>";
	$message = $message."<p style='color:#e01b84;font-size:20px;'>A <b>twist</b> on the user review</p>";
	$message = $message."Every experience entered is comprised of a tweet sized blurb, details on the type of experience(s) you had and placing the game into a Tier. The Tier structure is designed to help you focus on comparing your time with a game relative to other games. This isn’t your traditional star system. Instead it allows you to group the quality of your experiences each year in a meaningful new way.<br><br>";
	$message = $message."<p style='color:#e01b84;font-size:20px;'>A profile that <b>reflects</b> your gaming life</p>";
	$message = $message."As you enter your experiences your profile will expand and your Lifebar will grow. You can easily identify gamers who have been active by their Lifebar. Your profile will display franchises and developers you tend to favor, what you have recently played/watched, your favorite games, anticipated new releases and more!<br><br>";
	$message = $message."<p style='color:#e01b84;font-size:20px;'>Don't aggregate. <b>Personalize.</b></p>";
	$message = $message."Your activity feed will show only the critic reviews that you trust and enjoy reading. As your friends play games, share their feelings about games they have watched, bookmark games for later and update their thoughts you will see it all on your personalized activity feed.<br><br>";
	$message = $message."<h3>Thanks for signing up!</h3>";
	$message = $message."Lifebar is a work in progress, we are proud of what we have built so far, but we know there is so much more that could be done. Please join us in building a service that captures what we experience, why we experience it and how we experience video games!<br><br>";
	$message = $message."<h2><a href='http://lifebar.io' style='color:#0a67a3;'>Start building your Lifebar today!</a></h2>";
	$message = $message."Thanks,<br>The Lifebar Team<br><br>";
	SendEmail($to, $subject, $message);	
}


function SendForgotPasswordEmail($to){
	$mysqli = Connect();
	if ($result = $mysqli->query("SELECT * FROM  `Users` where `Email` = '".$to."'")) {
		while($row = mysqli_fetch_array($result)){
				$randomKey = hash('sha256',uniqid(mt_rand(), true).uniqid(mt_rand(), true));
				$mysqli->query("UPDATE `Users` SET `Key` = '".$randomKey."' WHERE `Email` ='".$to."'");
		}
		$subject = "Lifebar Account Reset";
		$link = "http://lifebar.io?forgotkey=".$randomKey;
		$message = $message."You have requested a password reset. If you didn't make this request you can ignore this email.<br><br>";
		$message = $message."<a href='".$link."'>Click to reset your Lifebar password</a><br><br>";
		SendEmail($to, $subject, $message);	
	}
	Close($mysqli, $result);
}

function SendEmail($to, $subject, $message){
	$headers = "From: contact@lifebar.io\r\n";
	$headers .= "Reply-To: contact@lifebar.io\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	mail($to, $subject, $message, $headers);
}

function SendEmailWithFrom($to, $subject, $message, $from){
	$headers = "From: ".$from." <notify@lifebar.io>\r\n";
	$headers .= "Reply-To: notify@lifebar.io\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	mail($to, $subject, $message, $headers);
}

?>
