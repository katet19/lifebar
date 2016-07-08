<?php require_once 'controller_database.php';

if(isset($_GET['id']) && isset($_GET['type'])){
	Unsub($_GET['id'], $_GET['type']);
	?>
	<div style='background-color:rgb(237, 236, 236);width:100%;text-align:center;position:relative;height:100%;'>
		<div style='display:inline-block;width:500px;background-color:#fff;margin-top:50px;'>
			<div style='width:100%;text-align:center;padding:15px 0;background-color:#D32F2F;'><img style='max-height:40px;' src='http://lifebar.io/Images/Generic/LifebarLogoTestTopDullEmail.png'></div>
			<div style='font-size:1em;padding: 50px;'>You have been unsubscribed from <?php echo $_GET['type']; ?> emails!</div>
		</div>
	</div>
	<?php
}

function Unsub($userid, $type){
	$mysqli = Connect();
	$found = false;
	if ($result = $mysqli->query("SELECT * FROM  `EmailUnsub` where `UserID` = '".$userid."' and `Type` = '".$type."' LIMIT 0,1")) {
		while($row = mysqli_fetch_array($result)){
			$found = true;
		}
	}
	if(!$found){
		$mysqli->query("INSERT INTO `EmailUnsub` (`UserID`,`Type`) VALUES ('".$userid."','".$type."')");
	}
}