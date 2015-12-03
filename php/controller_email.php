<?php
require_once "includes.php";

function CreateAlphaEmail($to, $key){
	$subject = "Welcome to the Polygonal Weave Alpha!";
	$link = "http://polygonalweave.com?utm_source=google&utm_medium=email&utm_campaign=alpha%20registration&key=".$key;
	$message = $message."Welcome to the Polygonal Weave Alpha!<br><br>";
	$message = $message."<a href='".$link."'>Click to activate your Polygonal Weave account and get started!</a><br><br>";
	$message = $message."We are looking for your feedback on a brand new web application where you can journal and share all of your gaming experiences. Whether you watched a lets play, tried a demo or finished a 40 hour campaign, your experiences come together to create your own personal weave. The diversity of gaming experiences has expanded in recent years and we wanted to make sure that anything and everything was covered. You can explore the experiences of others, including the most popular gaming personalities where you can view a curated weave based on their published reviews.<br><br>";
	$message = $message."At anytime during the alpha, if you have comments, a bug to report or questions please use the 'Feedback' tab on the right side of the page to send it our way. We want to hear everything you have to say.<br><br>";
	$message = $message."Thanks,<br>The Polygonal Weave Team<br><br>";
	SendEmail($to, $subject, $message);	
}

function SendForgotPasswordEmail($to){
	$mysqli = Connect();
	if ($result = $mysqli->query("SELECT * FROM  `Users` where `Email` = '".$to."'")) {
		while($row = mysqli_fetch_array($result)){
				$randomKey = hash('sha256',uniqid(mt_rand(), true).uniqid(mt_rand(), true));
				$mysqli->query("UPDATE `Users` SET `Key` = '".$randomKey."' WHERE `Email` ='".$to."'");
		}
		$subject = "Polygonal Weave Account Reset";
		$link = "http://material.polygonalweave.com?forgotkey=".$randomKey;
		$message = $message."You have requested a password reset. If you didn't make this request you can ignore this email.<br><br>";
		$message = $message."<a href='".$link."'>Click to reset your Polygonal Weave password</a><br><br>";
		SendEmail($to, $subject, $message);	
	}
	Close($mysqli, $result);
}

function SendEmail($to, $subject, $message){
	$headers = "From: support@polygonalweave.com\r\n";
	$headers .= "Reply-To: support@polygonalweave.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	mail($to, $subject, $message, $headers);
}

?>