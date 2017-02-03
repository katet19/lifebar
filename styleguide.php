<?php 
require 'includes.php'; 
?>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700|Merriweather' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="css/vendor/style-guide/scss/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="css/vendor/style-guide/scss/style-guide.css"> <!-- Resource style -->
	<script src="css/vendor/style-guide/js/modernizr.js"></script> <!-- Modernizr -->
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
	<!--<link href="../css/dashboard.css" rel="stylesheet" type="text/css" />-->
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
	<link href="../css/lifebar.webflow.css" rel="stylesheet" type="text/css" />
	<link href="../css/site.css" rel="stylesheet" type="text/css" />
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
  	
	<title>Style Guide | Lifebar</title>
</head>
<body id="applicationContainer">
	<div id="navigationContainer">
		<div id="navigation-header" style="display: block;">
			<div class="row navigation-row">
				<div class="col navigation-col navigation-menu navigation-menu-slide-out">
					<i class="material-icons nav-icon">menu</i>
					<div class="navigation-menu-logo navigation-menu-logo-slide-out"><b>Life</b>bar</div>
				</div>
			</div>
		</div>
	</div>
	<main>
		<div id="contentContainer">
			<h1>Style Guide 1.0</h1>

			<section id="branding" class="cd-branding">
				<h2>Branding</h2>

				<ul>
					<li><img src="css/vendor/style-guide/assets/branding/touch-icon-1.jpg" alt="Touch Icon 1"></li>
					<li><img src="css/vendor/style-guide/assets/branding/touch-icon-2.jpg" alt="Touch Icon 2"></li>
					<li><img src="css/vendor/style-guide/assets/branding/favicon-1.jpg" alt="Favicon 1"></li>
					<li><img src="css/vendor/style-guide/assets/branding/favicon-2.jpg" alt="Favicon 2"></li>
				</ul> 
			</section>

			<section id="colors" class="cd-colors">
				<h2>Colors</h2>

				<ul>
					<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Primary</div>
					</li>

					<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Primary Dark</div>
					</li>

					<li class="cd-box">
						<div class="cd-color-swatch">Primary Light</div>
					</li>

					<li class="cd-box">
						<div class="cd-color-swatch">Primary Text</div>
					</li>

					<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Text</div>
					</li>
					<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Secondary Text</div>
					</li>

					<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Accent</div>
					</li>

					<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Accent Hover</div>
					</li>

					<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Cancel Hover</div>
					</li>

					<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Save</div>
					</li>
										<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Save Hover</div>
					</li>

					<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Tier 1</div>
					</li>

					<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Tier 2</div>
					</li>

					<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Tier 3</div>
					</li>

					<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Tier 4</div>
					</li>
					<li class="cd-box">
						<div class="cd-color-swatch" style="color:white">Tier 5</div>
					</li>

				</ul>
			</section>

			<section id="typography" class="cd-typography">
				<h2>Typography</h2>

				<div class="cd-box">
					<h1>Heading, <span></span></h1>
					<p>Aa - <span></span>. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis voluptate distinctio reprehenderit, autem deleniti ad <a href="#0">voluptatum eaque</a>. Optio ea aperiam nisi distinctio nemo repellat, voluptate fugiat. Quidem neque illum, blanditiis!</p>
				</div>
			</section>

			<section id="buttons" class="cd-buttons">
				<h2>Buttons</h2>

				<div class="cd-box">
					<button class="btn">Normal</button>
					<button class="btn-save">Save</button>
					<button class="btn-flat">Flat</button>
					<button class="btn btn-outline">Outline</button>
				</div>

			</section>

			<section id="icons" class="cd-icons">
				<h2>Icons</h2>

				<ul class="cd-box">
					<li class="cd-icon-1"></li>
					<li class="cd-icon-2"></li>
					<li class="cd-icon-3"></li>
					<li class="cd-icon-4"></li>
					<li class="cd-icon-5"></li>
					<li class="cd-icon-6"></li>
					<li class="cd-icon-7"></li>
					<li class="cd-icon-8"></li>
				</ul>
			</section>

			<section id="form" class="cd-form">
				<h2>Form</h2>
				
				<div class="cd-box">
					<form>
						<fieldset>
							<input type="text">
							<input class="success" type="text">
							<input class="alert" type="text">
						</fieldset>

						<fieldset>
							<div class="cd-input-wrapper">
								<input type="radio" name="radioButton" id="radio1">
								<label class="radio-label" for="radio1">Choice 1</label>
							</div>

							<div class="cd-input-wrapper">
								<input type="checkbox" id="checkbox1">
								<label class="checkbox-label" for="checkbox1">Option 1</label>
							</div>

							<div class="cd-input-wrapper cd-select">
								<select name="select-this" id="select-this">
								<option value="0">Select</option>
								<option value="1">Option 1</option>
								<option value="2">Option 2</option>
								<option value="2">Option 3</option>
								</select>
							</div>
						</fieldset>
					</form>
				</div>
			</section>
		</div>
	</main>
	<ul id="nav-slide-out" class="nav-display-slide-out">
		<li class="" id="nav-brand"><a href="#branding"><i class="material-icons">explore</i> Branding</a></li>
		<li id="nav-colors" class="nav-slide-out-selected-page"><a href="#colors"><i class="material-icons">whatshot</i> Colors</a></li>
		<div class="divider"></div>
		<li id="nav-notifications" style="position:relative;"><a href="#notifications"><i class="material-icons">notifications_none</i> Notifications</a></li>
		<li id="nav-settings"><a href="#settings"><i class="material-icons">settings</i> Settings</a></li>
		<li id="nav-admin"><a href="#admin" class="adminButton"><i class="material-icons">security</i> Admin</a></li>
		<div class="divider"></div>
		<li><a href="#!" class="supportBlogButton"><i class="material-icons">feedback</i> Feedback</a></li>
		<div class="divider"></div>
		<li><a href="#logout" class="signOutButton"><i class="material-icons">exit_to_app</i> Sign out</a></li>
	</ul>
<script src="css/vendor/style-guide/js/jquery-2.1.1.js"></script>
<script src="css/vendor/style-guide/js/main.js"></script> <!-- Resource jQuery -->
</body>
</html>