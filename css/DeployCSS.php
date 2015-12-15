<?php
	$deployed = fopen("deployed.css", 'w+');
	if($deployed){
		echo "Opened deployed CSS file <br>";
		$circle = file_get_contents("library/circle.css");
		fwrite($deployed, $circle);
		
		$materialize = file_get_contents("library/materialize.css");
		fwrite($deployed, $materialize);
		
		$activity = file_get_contents("activity.css");
		fwrite($deployed, $activity);
		
		$admin = file_get_contents("admin.css");
		fwrite($deployed, $admin);
		
		$analytics = file_get_contents("analytics.css");
		fwrite($deployed, $analytics);
		
		$badge = file_get_contents("badge.css");
		fwrite($deployed, $badge);
		
		$discover = file_get_contents("discover.css");
		fwrite($deployed, $discover);
		
		$game = file_get_contents("game.css");
		fwrite($deployed, $game);
		
		$graph = file_get_contents("graph.css");
		fwrite($deployed, $graph);
		
		$landing = file_get_contents("landing.css");
		fwrite($deployed, $landing);
		
		$main = file_get_contents("main.css");
		fwrite($deployed, $main);
		
		$navigation = file_get_contents("navigation.css");
		fwrite($deployed, $navigation);
		
		$notification = file_get_contents("notification.css");
		fwrite($deployed, $notification);
		
		$profile = file_get_contents("profile.css");
		fwrite($deployed, $profile);
		
		$user = file_get_contents("user.css");
		fwrite($deployed, $user);
		
		$weave = file_get_contents("weave.css");
		fwrite($deployed, $weave);
		
		$xp = file_get_contents("xp.css");
		fwrite($deployed, $xp);
		
		echo "Finished deploying CSS";
		fclose($deployed);
	}else{
		echo "Failed to open deployed CSS file<br>";
	}
?>