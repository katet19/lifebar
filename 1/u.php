<?php require_once 'controller_database.php';
require_once 'controller_game.php';
require_once 'controller_user.php';


if(isset($_GET['i'])){
	$type = substr($_GET['i'],0,1);
	$id = substr($_GET['i'],1);
	makeOpenGraph($type, $id);
}

function makeOpenGraph($type, $id) {
	if($type == 'g'){
		$og = ConvertGametoOG(GetGame($id));
	}else if($type == 'u'){
		$og = ConvertUsertoOG(GetUser($id));
	}else if($type == 'x'){
		$ids = explode("-", $id);
		if(sizeof($ids) > 1){
			$game = GetGame($ids[0]);
			$user = GetUser($ids[1]);
			$og = ConvertGamePlusUsertoOG($user, $game);
		}else{
			$og['LINK'] = "http://lifebar.io";
		}
	}
    ?>
    <!DOCTYPE html>
    <html itemscope itemtype="http://schema.org/Thing">
        <head>
        	<?php if(strstr($_SERVER['HTTP_USER_AGENT'], "Google")){ ?>
        		
        	<?php echo $_SERVER['HTTP_USER_AGENT']; }else{  ?>
        		<meta http-equiv="refresh" content="0;URL=<?php echo $og['LINK']; ?>">
        	<?php } ?>
        	<!-- Regular Open Graph -->
            <meta property="og:title" content="<?php echo $og["TITLE"]; ?>" />
            <meta property="og:site_name" content="Lifebar" />
            <meta property="og:description" content="<?php echo $og["DESC"]; ?>" />
            <meta property="og:image" content="<?php echo $og["IMAGE"]; ?>" />
            <meta property="og:image:width" content="400" />
            <meta property="og:image:height" content="400" />
            <!-- Facebook -->
            <meta property="fb:app_id" content="517085435138347" />
            <!-- Twitter. -->
            <meta name="twitter:card" content="summary" />
			<meta name="twitter:site" content="@lifebar" />
			<meta name="twitter:title" content="<?php echo $og["TITLE"]; ?>" />
			<meta name="twitter:description" content="<?php echo $og["DESC"]; ?>" />
			<meta name="twitter:image" content="<?php echo $og["IMAGE"]; ?>" />
			<!-- Google -->
			<meta itemprop="name" content="<?php echo $og["TITLE"]; ?>">
			<meta itemprop="description" content="<?php echo $og["DESC"]; ?>">
			<meta itemprop="image" content="<?php echo $og["IMAGE"]; ?>">
        </head>
        <body>
			Connecting to Lifebar.io
        </body>
    </html>
    <?php
}

function ConvertGametoOG($game){
	$og["ID"] = $game->_id;
	$og["TITLE"] = $game->_title;
	$og["DESC"] = "Check out analytics and what others are saying about ".$game->_title;
	$og["IMAGE"] = $game->_imagesmall; 
	$og["LINK"] = "http://lifebar.io/#game/".$game->_id."/".htmlspecialchars($game->_title)."/";
	
	return $og;
}

function ConvertGamePlusUsertoOG($user, $game){
	$og["ID"] = $user->_id;
	
	if($user->_security == "Journalist" || $user->_security == "Authenticated")
		$og["TITLE"] = $user->_first." ".$user->_last;
	else
		$og["TITLE"] = $user->_username;
	
	if($user->_security == "Journalist")
		$og["DESC"] = htmlspecialchars("Check out ".$og["TITLE"]."'s curated experience with ".$game->_title);
	else
		$og["DESC"] = htmlspecialchars("Check out ".$og["TITLE"]."'s experience with ".$game->_title);
		
	$og["IMAGE"] = $game->_imagesmall; 
	$og["LINK"] = "http://lifebar.io/#game/".$game->_id."/".htmlspecialchars($game->_title)."/User/".$user->_id."/";
	
	return $og;
}

function ConvertUsertoOG($user){
	$og["ID"] = $user->_id;
	
	if($user->_security == "Journalist" || $user->_security == "Authenticated")
		$og["TITLE"] = $user->_first." ".$user->_last;
	else
		$og["TITLE"] = $user->_username;
	
	if($user->_security == "Journalist")
		$og["DESC"] = htmlspecialchars("Check out ".$og["TITLE"]."'s curated gaming profile");
	else
		$og["DESC"] = htmlspecialchars("Check out ".$og["TITLE"]."'s gaming profile");
		
	$og["IMAGE"] = $user->_avatar;
	$og["LINK"] = "http://lifebar.io/#profile/".$user->_id."/".htmlspecialchars($user->_username)."/";
	
	return $og;
}
?>