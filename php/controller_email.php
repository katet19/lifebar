<?php
require_once "includes.php";

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

?>
