<?php
function Connect($mysqli = null){
	if($mysqli != null){
		return $mysqli;
	}else{
		$mysqli = new mysqli("localhost", "polygo6_weaving", "dv+kzs3Ek7BH", "polygo6_weave"); // or die('Error: '.mysql_error());
        //$mysqli = new mysqli("localhost", "lifeba8_appuser", "a41e684b36ea57", "lifeba8_dev");
		if($mysqli->connect_error){ 
			usleep(50);
			ConnectTryTwo();
		}
	     		
		return $mysqli;
	}
}
function ConnectTryTwo(){
	$mysqli = new mysqli("localhost", "lifeba8_appuser", "a41e684b36ea57", "lifeba8_dev");
	if($mysqli->connect_error){ 
		usleep(50);
		ConnectTryThree();
	}
     		
	return $mysqli;
}
function ConnectTryThree(){
	$mysqli = new mysqli("localhost", "lifeba8_appuser", "a41e684b36ea57", "lifeba8_dev");
	if($mysqli->connect_error){ 
		usleep(400);
		ConnectTryFour();
	}
     		
	return $mysqli;
}
function ConnectTryFour(){
	$mysqli = new mysqli("localhost", "lifeba8_appuser", "a41e684b36ea57", "lifeba8_dev");
	if($mysqli->connect_error){ 
		die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
	}
	return $mysqli;
}
function Close($mysqli, $result){
	if($result != '' && $result != null && $result != false && $result != true)
		mysqli_free_result($result);

	if($mysqli != null)	
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
