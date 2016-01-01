<?php require 'includes.php'; ?>
<!DOCTYPE HTML>
<html>
<title>Lifebar</title>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<meta http-equiv="X-Frame-Options" content="deny">
<meta http-equiv="Cache-control" content="public">
<meta name="theme-color" content="#D32F2F">
<link rel="shortcut icon" href="http://polygonalweave.com/fav.ico" type="image/x-icon" />
<link href="../css/library/materialize.css" rel="stylesheet" type="text/css" />
<link href="../css/deployed.css" rel="stylesheet" type="text/css" />
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
  ga('send', 'pageview');

</script>
</body>
</html>
