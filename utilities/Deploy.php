<?php
	$deployed = fopen("deployed.js", 'w+');
	if($deployed){
		echo "Opened deployed js file <br>";
		$activity = file_get_contents("activity.js");
		$admin = file_get_contents("admin.js");
		$analytics = file_get_contents("analytics.js");
		if(!fwrite($deployed, $activity))
			echo "Failed to write to deployed js file";
		fwrite($deployed, $admin);
		fwrite($deployed, $analytics);
		fclose($deployed);
	}else{
		echo "Failed to open deployed JS file<br>";
	}
?>