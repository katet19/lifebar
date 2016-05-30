<?php
function DeployCSS(){
	$deployed = fopen("css/deployed.css", 'w+');
	if($deployed){
		echo "Opened deployed CSS file <br>";
		$circle = file_get_contents("css/library/circle.css");
		fwrite($deployed, $circle);
		
		//$materialize = file_get_contents("library/materialize.css");
		//fwrite($deployed, $materialize);
		
		$activity = file_get_contents("css/activity.css");
		fwrite($deployed, $activity);
		
		$admin = file_get_contents("css/admin.css");
		fwrite($deployed, $admin);
		
		$analytics = file_get_contents("css/analyze.css");
		fwrite($deployed, $analytics);
		
		$badge = file_get_contents("css/badge.css");
		fwrite($deployed, $badge);
		
		$discover = file_get_contents("css/discover.css");
		fwrite($deployed, $discover);
		
		$game = file_get_contents("css/game.css");
		fwrite($deployed, $game);
		
		$graph = file_get_contents("css/graph.css");
		fwrite($deployed, $graph);
		
		/*$landing = file_get_contents("css/landing.css");
		fwrite($deployed, $landing);
		
		$landingstyle = file_get_contents("css/landing-style.css");
		fwrite($deployed, $landingstyle);*/
		
		$main = file_get_contents("css/main.css");
		fwrite($deployed, $main);
		
		$navigation = file_get_contents("css/navigation.css");
		fwrite($deployed, $navigation);
		
		$notification = file_get_contents("css/notification.css");
		fwrite($deployed, $notification);
		
		$profile = file_get_contents("css/profile.css");
		fwrite($deployed, $profile);
		
		$user = file_get_contents("css/user.css");
		fwrite($deployed, $user);
		
		$weave = file_get_contents("css/weave.css");
		fwrite($deployed, $weave);
		
		$xp = file_get_contents("css/xp.css");
		fwrite($deployed, $xp);
        
        $collection = file_get_contents("css/collection.css");
		fwrite($deployed, $collection);
        
        $import = file_get_contents("css/import.css");
		fwrite($deployed, $import);
		
		echo "Finished deploying CSS";
		fclose($deployed);
	}else{
		echo "Failed to open deployed CSS file<br>";
	}
}
?>
