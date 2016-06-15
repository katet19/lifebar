<?php require 'includes.php'; 

if($GLOBALS["DownForMaintenance"]  && $_SESSION['logged-in']->_security != 'Admin'){ ?>
	<html>
	<title>Lifebar - Down for Maintenance</title>
	<head>
		<link href="../css/library/materialize.css" rel="stylesheet" type="text/css" />
		<link href="../css/main.css" rel="stylesheet" type="text/css" />
	</head>
	<body style='background-color:#D32F2F;color:white;text-align:center;padding:50px 25px'>
		<div><?php DisplayLifeBarLogo(false); ?></div>
	</body>
	</html>
<?php }else{ ?>
	<!DOCTYPE HTML>
	<html>
	<title>Lifebar | Your gaming life</title>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta http-equiv="X-Frame-Options" content="deny">
	<meta http-equiv="Cache-control" content="public">
	<meta name="theme-color" content="#D32F2F">
	<meta name="Description" content="Lifebar represents your life playing and watching video games. With Lifebar you will be able to discover new games, share your experiences and visualize a lifetime of gaming.">
	<link rel="shortcut icon" href="http://lifebar.io/fav.ico" type="image/x-icon" />
	<link href="../css/library/materialize.css" rel="stylesheet" type="text/css" />
	<link href="../css/font/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="../css/library/webflow.css" rel="stylesheet" type="text/css" />
	<link href="../css/deployed.css" rel="stylesheet" type="text/css" />
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
		<div id="activity" class="col s12 m12 l9 outerContainer" style='padding: 0'>
			<div id="activityInnerContainer" class="innerContainer" style="top:0; background-color:rgb(237, 236, 236)"></div>
		</div>
		<div id="profile" class="col s12 outerContainer" style="padding:0;display:none;left: 0px;  bottom: 0;  top: 75px;position: absolute;  width: 100%;  z-index: 3;">
			<div id="profileInnerContainer" class="innerContainer" style="top:0; background-color:rgb(237, 236, 236);min-height: 150%;"></div>
		</div>
		<div id="profiledetails" class="col s12 outerContainer" style="padding:0;display:none;left: 0px;  bottom: 0;  top: 75px;position: absolute;  width: 100%;  z-index: 3;">
			<div id="profiledetailsInnerContainer" class="innerContainer" style="top:0; background-color:rgb(237, 236, 236);min-height: 150%;"></div>
		</div>
		<div id="discover" class="col s12 outerContainer" style='padding: 0;'>
			<div id="discoverInnerContainer" class="innerContainer" style="top:0; background-color:rgb(237, 236, 236)"></div>
		</div>
		<div id="notifications" class="col s12 m12 l9 outerContainer" style='padding: 0;display:none;'>
			<div id="notificationsInnerContainer" class="innerContainer" style="top:0; background-color:rgb(237, 236, 236)"></div>
		</div>
		<div id="game" class="col s12 m12 l9 outerContainer" style="padding:0;">
			<div id="gameInnerContainer" class="innerContainer" style="top:0; background-color:rgb(237, 236, 236);"></div>
		</div>
		<div id="settings" class="col s12 m12 l9 outerContainer" style="padding:0;display:none;left: 0px;  bottom: 0;  top: 75px;position: absolute;  width: 100%;  z-index: 3;display:none;">
			<div id="settingsInnerContainer" class="innerContainer" style="top:0; background-color:rgb(237, 236, 236);min-height: 150%;"></div>
		</div>
		<div id="admin" class="col s12 m12 l9 outerContainer" style="padding:0;display:none;left: 0px;  bottom: 0;  top: 110px;position: absolute;  width: 100%;  z-index: 3;">
			<div id="adminInnerContainer" class="innerContainer" style="top:0; background-color:rgb(237, 236, 236);min-height:100%;"></div>
		</div>
		<div id="landing" class="col s12 m12 l9 outerContainer" style="padding:0;display:none;left: 0px;  bottom: 0;  top: 0px;position: absolute;  width: 100%;  z-index: 99;">
			<div id="landingInnerContainer" class="innerContainer" style="top:0; background-color:rgb(237, 236, 236);min-height:100%;"></div>
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
	<?php DisplayUniversalUserPreview(); ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="../js/deployed.js"></script>
	<script type="text/javascript" src="../js/library/ScrollMagic.js"></script>
	<script type="text/javascript" src="../js/library/TweenMax.min.js"></script>
	<script type="text/javascript" src="../js/library/animation.gsap.min.js"></script>
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
