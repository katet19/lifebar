<?php
	$deployed = fopen("deployed.js", 'w+');
	if($deployed){
		echo "Opened deployed js file <br>";
		$chart = file_get_contents("library/chart.js");
		fwrite($deployed, $chart);
		
		$materialize = file_get_contents("library/materialize.js");
		fwrite($deployed, $materialize);
		
		$modernizr = file_get_contents("library/modernizr.js");
		fwrite($deployed, $modernizr);
		
		$vel = file_get_contents("library/velocity.min.js");
		fwrite($deployed, $vel);
		
		$activity = file_get_contents("activity.js");
		fwrite($deployed, $activity);
		
		$admin = file_get_contents("admin.js");
		fwrite($deployed, $admin);
		
		$analytics = file_get_contents("analytics.js");
		fwrite($deployed, $analytics);
		
		$discover = file_get_contents("discover.js");
		fwrite($deployed, $discover);
		
		$game = file_get_contents("game.js");
		fwrite($deployed, $game);
		
		$landing = file_get_contents("landing.js");
		fwrite($deployed, $landing);
		
		$login = file_get_contents("login.js");
		fwrite($deployed, $login);
		
		$main = file_get_contents("main.js");
		fwrite($deployed, $main);
		
		$navigation = file_get_contents("navigation.js");
		fwrite($deployed, $navigation);
		
		$notification = file_get_contents("notification.js");
		fwrite($deployed, $notification);
		
		$profile = file_get_contents("profile.js");
		fwrite($deployed, $profile);
		
		$user = file_get_contents("user.js");
		fwrite($deployed, $user);
		
		$xp = file_get_contents("xp.js");
		fwrite($deployed, $xp);
		
		echo "Finished deploying JS";
		fclose($deployed);
	}else{
		echo "Failed to open deployed JS file<br>";
	}
?>