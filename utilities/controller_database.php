<?php
function Connect(){
	$mysqli = new mysqli("localhost", "lifeba8_appuser", "a41e684b36ea57", "lifeba8_db");
	if($mysqli->connect_error) 
     		die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
     		
	return $mysqli;
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
?>