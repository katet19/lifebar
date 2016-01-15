<?php
function Connect(){
	$mysqli = new mysqli("localhost", "polygo6_weaving", "dv+kzs3Ek7BH", "polygo6_weave"); // or die('Error: '.mysql_error());
	if($mysqli->connect_error){ 
		usleep(50);
		$mysqli = new mysqli("localhost", "polygo6_weaving", "dv+kzs3Ek7BH", "polygo6_weave"); 
		if($mysqli->connect_error){ 
			usleep(50);
			$mysqli = new mysqli("localhost", "polygo6_weaving", "dv+kzs3Ek7BH", "polygo6_weave"); 
			if($mysqli->connect_error){ 
				usleep(400);
				$mysqli = new mysqli("localhost", "polygo6_weaving", "dv+kzs3Ek7BH", "polygo6_weave"); 
				if($mysqli->connect_error){ 
					die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
				}
			}
		}
	}
     		
	return $mysqli;
}
function Close($mysqli, $result){
	if($result != '' && $result != null && $result != false && $result != true){
		mysqli_free_result($result);
	}
		
	mysqli_close($mysqli);
}
function getIp() {
    $ip = $_SERVER['REMOTE_ADDR'];
 
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
 
    return $ip;
}
function SaveIP(){
	$ip = getIp();
	if(!IPAlreadyLogged()){
		$mysqli = Connect();
		$mysqli->query("INSERT INTO `IPCache` (`IP`) VALUES ('".$ip."')");	
	}
}
function IPAlreadyLogged(){
	$ip = getIp();
	$mysqli = Connect();
	$found = false;
	if ($result = $mysqli->query("select * from `IPCache` where `IP` = '".$ip."'")) {
		while($row = mysqli_fetch_array($result)){
			$found = true;
		}
	}
	return $found;
}

function TestScript($script){
	$mysqli = Connect();
	$script = BindVariables($script);
	$result = $mysqli->query($script);
	if (!$result) {
	    echo 'Failed: ' . mysql_error();
	    echo "<BR> Script: " .  $script;
	    return false;
	}else{
		echo "Passed";
		return true;
	}
}

function BindVariables($script){
	$script = str_replace("[UserID]", "'7'", $script); //Jonathan users ID
	$script = str_replace("[GameID]", "'821'", $script); //Destiny game ID
	return $script;
}
?>