<?php
function customError($level, $msg, $file, $line){
	$subject = "Error: ".$level;
	$to = "lifebar.ttzfu@zapiermail.com";
	$msg = $msg."</b> in ".$file." @ line ".$line;
	SendEmail($to, $subject, $msg);
	echo "<div class='error_msg'>Whoops, something didn't go as planned.<br> Please, hit F5 or refresh and we will take a look.</div>";
}
?>