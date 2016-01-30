<?php
function DeployJS($version){
	$newversion = $version + 1;
	$deployed = fopen("js/deployed.js", 'w+');
	if($deployed){
		echo "Opened deployed js file <br>";
		$chart = file_get_contents("js/library/chart.js");
		fwrite($deployed, $chart);
		
		$materialize = file_get_contents("js/library/materialize.js");
		fwrite($deployed, $materialize);
		
		$modernizr = file_get_contents("js/library/modernizr.js");
		fwrite($deployed, $modernizr);
		
		$vel = file_get_contents("js/library/velocity.min.js");
		fwrite($deployed, $vel);
		
		$activity = file_get_contents("js/activity.js");
		fwrite($deployed, $activity);
		
		$admin = file_get_contents("js/admin.js");
		fwrite($deployed, $admin);
		
		$analytics = file_get_contents("js/analytics.js");
		fwrite($deployed, $analytics);
		
		$discover = file_get_contents("js/discover.js");
		fwrite($deployed, $discover);
		
		$game = file_get_contents("js/game.js");
		fwrite($deployed, $game);
		
		$landing = file_get_contents("js/landing.js");
		fwrite($deployed, $landing);
		
		$login = file_get_contents("js/login.js");
		fwrite($deployed, $login);
		
		$main = file_get_contents("js/main.js");
		fwrite($deployed, $main);
		
		$updateVer = "var GLOBAL_VERSION=".$newversion.";";
		fwrite($deployed, $updateVer);
		
		$navigation = file_get_contents("js/navigation.js");
		fwrite($deployed, $navigation);
		
		$notification = file_get_contents("js/notification.js");
		fwrite($deployed, $notification);
		
		$profile = file_get_contents("js/profile.js");
		fwrite($deployed, $profile);
		
		$user = file_get_contents("js/user.js");
		fwrite($deployed, $user);
		
		$xp = file_get_contents("js/xp.js");
		fwrite($deployed, $xp);
		
		echo "Finished deploying JS";
		fclose($deployed);
	}else{
		echo "Failed to open deployed JS file<br>";
	}
}
?>
