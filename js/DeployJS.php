<?php
	$deployed = fopen("deployed.js", 'w+');
	if($deployed){
		echo "Opened deployed js file <br>";
		$activity = file_get_contents("activity.js");
		fwrite($deployed, str_replace(" ","",$activity));
		
		$admin = file_get_contents("admin.js");
		fwrite($deployed, str_replace(" ","",$admin));
		
		$analytics = file_get_contents("analytics.js");
		fwrite($deployed, str_replace(" ","",$analytics));
		
		$discover = file_get_contents("discover.js");
		fwrite($deployed, str_replace(" ","",$discover));
		
		$game = file_get_contents("game.js");
		fwrite($deployed, str_replace(" ","",$game));
		
		$landing = file_get_contents("landing.js");
		fwrite($deployed, str_replace(" ","",$landing));
		
		$login = file_get_contents("login.js");
		fwrite($deployed, str_replace(" ","",$login));
		
		$main = file_get_contents("main.js");
		fwrite($deployed, str_replace(" ","",$main));
		
		$navigation = file_get_contents("navigation.js");
		fwrite($deployed, str_replace(" ","",$navigation));
		
		$notification = file_get_contents("notification.js");
		fwrite($deployed, str_replace(" ","",$notification));
		
		$profile = file_get_contents("profile.js");
		fwrite($deployed, str_replace(" ","",$profile));
		
		$user = file_get_contents("user.js");
		fwrite($deployed, str_replace(" ","",$user));
		
		$xp = file_get_contents("xp.js");
		fwrite($deployed, str_replace(" ","",$xp));
		
		$chart = file_get_contents("library/chart.js");
		fwrite($deployed, str_replace(" ","",$chart));
		
		$materialize = file_get_contents("library/materialize.js");
		fwrite($deployed, str_replace(" ","",$materialize));
		
		$modernizr = file_get_contents("library/modernizr.js");
		fwrite($deployed, str_replace(" ","",$modernizr));
		
		$vel = file_get_contents("library/velocity.min.js");
		fwrite($deployed, $vel);
		
		echo "Finished deploying JS";
		fclose($deployed);
	}else{
		echo "Failed to open deployed JS file<br>";
	}
?>