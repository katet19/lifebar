<?php 
require 'includes.php'; 
if($GLOBALS["DownForMaintenance"]){ ?> <!--&& $_SESSION['logged-in']->_security != 'Admin'){?>-->
	<html>
	<title>Lifebar - Down for Maintenance</title>
	<head>
		<link href="../css/library/materialize.css" rel="stylesheet" type="text/css" />
		<link href="../css/main.css" rel="stylesheet" type="text/css" />
	</head>
	<body style='background-color:#3F51B5;color:white;text-align:center;padding:50px 25px'>
		<div><?php DisplayLifeBarLogo(false); ?></div>
	</body>
	</html>
<?php }else{ ?>
<!DOCTYPE HTML>
<html>
	<?php if(isset($_GET['game'])){ 
		$game = GetGame($_GET['game']);
		?>
		<title>Lifebar | <?php echo $game->_title; ?></title>
		<?php
	}else{
		?>
		<title>Lifebar | We Heart Games ;)</title>
		<?php
	} 
?>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta http-equiv="X-Frame-Options" content="deny">
<meta http-equiv="Cache-control" content="public">
<meta name="theme-color" content="#3F51B5">
	<?php if(isset($_GET['game'])){ ?>
		<meta name="description" content="<?php echo $game->_title." "; 
				if($game->_developer != ''){ echo "developed by ".$game->_developer." "; }
				if($game->_platforms != ''){ echo "released for ".trim($game->_platforms)." "; }
				?> 
		">
		<?php
	}else{
		?>
		<meta name="description" content="Lifebar is a platform to celebrate & capture your life with games. From your earliest memories to your latest speed run, Lifebar captures it all.">
		<?php
	}
	?>

<link rel="shortcut icon" href="http://lifebar.io/fav.ico" type="image/x-icon" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="../css/library/materialize.css" rel="stylesheet" type="text/css" />
<link href="../css/library/circle.css" rel="stylesheet" type="text/css" />
<link href="../css/library/webflow.css" rel="stylesheet" type="text/css" />
<link href="../css/fonts/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="../css/navigation.css" rel="stylesheet" type="text/css" />
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<link href="../css/activity.css" rel="stylesheet" type="text/css" />
<link href="../css/admin.css" rel="stylesheet" type="text/css" />
<link href="../css/analyze.css" rel="stylesheet" type="text/css" />
<link href="../css/dashboard.css" rel="stylesheet" type="text/css" />
<link href="../css/discover.css" rel="stylesheet" type="text/css" />
<link href="../css/user.css" rel="stylesheet" type="text/css" />
<link href="../css/profile.css" rel="stylesheet" type="text/css" />
<link href="../css/graph.css" rel="stylesheet" type="text/css" />
<link href="../css/game.css" rel="stylesheet" type="text/css" />
<link href="../css/xp.css" rel="stylesheet" type="text/css" />
<link href="../css/notification.css" rel="stylesheet" type="text/css" />
<link href="../css/weave.css" rel="stylesheet" type="text/css" />
<link href="../css/badge.css" rel="stylesheet" type="text/css" />
<link href="../css/collection.css" rel="stylesheet" type="text/css" />
<link href="../css/import.css" rel="stylesheet" type="text/css" />
<link href="../css/ranking.css" rel="stylesheet" type="text/css" />
<link href="../css/lifebar.webflow.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script type="text/javascript" src="../js/library/chart.js"></script>
<script type="text/javascript" src="../js/library/materialize.js"></script>
<script type="text/javascript" src="../js/library/modernizr.js"></script>
<script type="text/javascript" src="../js/library/velocity.min.js"></script>
<script type="text/javascript" src="../js/library/TweenMax.min.js"></script>
<script type="text/javascript" src="../js/library/ScrollMagic.js"></script>
<script type="text/javascript" src="../js/library/animation.gsap.min.js"></script>
<script type="text/javascript" src="../js/activity.js"></script>
<script type="text/javascript" src="../js/admin.js"></script>
<script type="text/javascript" src="../js/discover.js"></script>
<script type="text/javascript" src="../js/login.js"></script>
<script type="text/javascript" src="../js/main.js"></script>
<script type="text/javascript" src="../js/navigation.js"></script>
<script type="text/javascript" src="../js/user.js"></script>
<script type="text/javascript" src="../js/game.js"></script>
<script type="text/javascript" src="../js/xp.js"></script>
<script type="text/javascript" src="../js/notification.js"></script>
<script type="text/javascript" src="../js/landing.js"></script>
<script type="text/javascript" src="../js/profile.js"></script>
<script type="text/javascript" src="../js/collection.js"></script>
<script type="text/javascript" src="../js/import.js"></script>
<script type="text/javascript" src="../js/onboarding.js"></script>
<script type="text/javascript" src="../js/ranking.js"></script>
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "WebSite",
  "name": "Lifebar",
  "alternateName": "Lifebar.io",
  "url": "http://www.lifebar.io",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "http://lifebar.io/#search/{search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Organization",
  "url": "http://www.lifebar.io",
  "logo": "http://www.lifebar.io/Images/lifebarlogoforsocial.png"
}
</script>
</head>
<body id="applicationContainer">
	<div id="navigationContainer">
		<?php DisplayNavigation(); ?>
	</div>
<div id="contentContainer" class="row">
	<div id="game" class="col s12 outerContainer outerContainer-slide-out" style="padding:0;">
		<div id="gameInnerContainer" class="modalInnerContainer z-depth-3">
			<?php if(isset($_GET['game'])){ 
				DisplayGameViaID($_GET['game'], -1);	
			}
			?>
		</div>
	</div>
	<div id="gamemini" class="col s12 outerContainer" style="padding:0;">
		<div id="gameminiInnerContainer" class="modalInnerContainer z-depth-5"></div>
	</div>
	<div id="profile" class="col s12 outerContainer outerContainer-slide-out" style="padding:0;">
		<div id="profileInnerContainer" class="modalInnerContainer z-depth-3"></div>
	</div>
	<div id="activity" class="col s12 outerContainer outerContainer-slide-out" style='padding: 0'>
		<div id="activityInnerContainer" class="innerContainer"></div>
	</div>
	<div id="ranking" class="col s12 outerContainer outerContainer-slide-out" style='padding: 0'>
		<div id="rankingInnerContainer" class="innerContainer"></div>
	</div>
	<div id="profiledetails" class="col s12 outerContainer outerContainer-slide-out" style="padding:0;display:none;left: 0px;  bottom: 0;  top: 75px;position: absolute;  width: 100%;  z-index: 3;">
		<div id="profiledetailsInnerContainer" class="innerContainer" style="min-height: 150%;"></div>
	</div>
	<div id="discover" class="col s12 outerContainer outerContainer-slide-out" style='padding: 0;'>
		<div id="discoverInnerContainer" class="innerContainer"></div>
	</div>
	<div id="notifications" class="col s12 outerContainer outerContainer-slide-out" style='padding: 0;display:none;'>
		<div id="notificationsInnerContainer" class="innerContainer"></div>
	</div>
	<div id="settings" class="col s12 outerContainer outerContainer-slide-out" style="padding:0;display:none;left: 0px;  bottom: 0;  top: 75px;position: absolute;  width: 100%;  z-index: 3;display:none;">
		<div id="settingsInnerContainer" class="innerContainer" style="min-height: 150%;"></div>
	</div>
	<div id="admin" class="col s12 outerContainer outerContainer-slide-out" style="padding:0;display:none;">
		<div id="adminInnerContainer" class="innerContainer" style="min-height:100%;"></div>
	</div>
	<div id="landing" class="col s12 outerContainer outerContainer-slide-out" style="padding:0 !important;display:none;left: 0px;bottom: 0px;right: 0px;top: 0px;position: absolute;margin-left: 0px !important;width: 100%;z-index: 99;width: 100% !important;">
		<div id="landingInnerContainer" class="innerContainer" style="min-height:100%;background-color: rgba(0,0,0,0.7);"></div>
	</div>
</div>
<div id='fb-root'></div>
<?php LoadThirdPartyTools(); ?>
<?php DisplaySideDrawer(); ?>
<?php DisplayLogin(); ?>
<?php DisplaySignup(); ?>
<?php DisplayPasswordReset(); ?>
<?php DisplayUniversalPopUp(); ?>
<?php DisplayUniversalBottomSheet(); ?>
<?php DisplayBattleProgressSheet(); ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-52980217-1', 'auto');
</script>
</body>
</html>
<?php } ?>