<?php 
function DisplayNavigation(){
	DisplayHeaderNavigation();
}

function DisplayHeaderNavigation(){ ?>
	<div class="identificationContainer">
		<div class="mobileContainer" data-activates="slide-out">
			<div class="mobileNav"><i class="mdi-navigation-menu small" style='color:white'></i></div>
			<div class="mobileTab"></div>
			<div class="searchContainerMobile">
				<i class="SearchBtn mdi-action-search small" style="color:white;vertical-align:middle;padding: 0 0.5em;"></i>
				<div class="searchInput"><input type="text" placeholder="Search" style='border: none !important;color:white;margin: 0;font-size: 1.2em;'></div>
				<i class="closeMobileSearch mdi-content-clear right" style="cursor:pointer;position: absolute;right: 0.3em;top: 0.15em;font-size:1.5em;"></i>
			</div>
		</div>
	</div>
	<div id="navigation-header">
		<div class="row" style='margin:0;'>
			<div class="col s3 m2 l3">
				<?php DisplayLifeBarLogo(true); ?>
			</div>
		    <div class="col s5 m6 l5">
		      <ul class="tabs mainNav">
		      	<li class="tab col s3"><a href="#discover" class="<?php if($_SESSION['logged-in'] == null){ ?>active<?php } ?> waves-effect waves-light">Discover</a></li>
		        <li class="tab col s3"><a href="#activity" class="<?php if($_SESSION['logged-in'] != null){ ?>active<?php } ?> waves-effect waves-light">Activity</a></li>
		        <li class="tab col s3"><a href="#profile" class="waves-effect waves-light">Profile</a></li>
		        <?php if($_SESSION['logged-in'] != null){ ?><li class="tab col s3"><a href="#notifications" style='display:none;' class="waves-effect waves-light">Notifications</a></li><?php } ?>
		      </ul>
			</div>
			<div class="col s4 m4 l4">
				<?php if($_SESSION['logged-in'] != null){ ?>
					<div class="userContainer" data-id="<?php echo $_SESSION['logged-in']->_id; ?>">
						<?php if($_SESSION['logged-in'] != null){ ?>
							<div class="searchContainer">
								<i class="SearchBtn mdi-action-search small" style="color:white;vertical-align:middle;padding: 0 0.5em;"></i>
								<div class="searchInput"><input type="text" placeholder="Search" style='border: none !important;color:white;margin: 0;font-size: 1.2em;'></div>
							</div>
						<?php }else{ ?>
							<div class="searchContainerAnonymous">
								<i class="SearchBtn mdi-action-search small" style="color:white;vertical-align:middle;padding: 0 0.5em;"></i>
								<div class="searchInput"><input type="text" placeholder="Search" style='border: none !important;color:white;margin: 0;font-size: 1.2em;'></div>
							</div>
						<?php } ?>
						<div class='userBug supportButton'><i class="mdi-action-bug-report"></i></div>
						<div class="userNotificiations"><i class="mdi-social-notifications-none"></i></div>
						<div class="userAvatar" style="background:url(<?php echo $_SESSION['logged-in']->_thumbnail; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
						<div class="userNameTitle" style="display:none;"><?php $_SESSION['logged-in']->_username; ?> </div>
						<div class="userTotalXP" style="display:none;"><div class="userTotalXPLabel"><?php echo $_SESSION['logged-in']->_weave->_totalXP;?></div> <span>XP</span></div>
						<div class="userAccountNavButton"><i class="mdi-navigation-expand-more"></i></div>
						<ul id='userAccountNav' class='dropdown-content'>
							<li><a href="#!" class="settingsButton">Settings</a></li>
							<?php if($_SESSION['logged-in']->_security == "Admin" || $_SESSION['logged-in']->_security == "AdminMenuOnly"){ ?>
							<li><a href="#!" class="adminButton">Admin</a></li>
							<?php } ?>
							<li><a href="#!" class="supportForumButton">Support</a></li>
							<li><a href="#!" class="signOutButton">Sign out</a></li>
						  </ul>
					</div>
				<?php }else{ ?>
					<div class="loginContainer">
					  <a id="loginButton" class="waves-effect waves-light btn-flat modal-trigger" href="#loginModal" style='color: white;margin-top: 4px;margin-bottom: 5px;'>Login</a>
					  <a id="signupButton" class="waves-effect waves-light btn-flat modal-trigger" href="#signupModal" style='margin-right: 5px;color: white;margin-bottom: 5px;margin-top:4px;'>Signup</a>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
<?php }

function DisplaySideDrawer(){ ?>
  <ul id="slide-out" class="side-nav full">
	<li class="side-nav-logo" style="display:none">
		<div class="logoIcon"><img src="http://polygonalweave.com/Images/Generic/WeaveLogoDark.svg" style="width:2.5em" ></div>
		<div class="logoTitle">Save Game</div>
  	</li>
  	<li class="side-nav-logo">
  		<?php if($_SESSION['logged-in'] != null){ ?>
	  		<a href="#!">
				<div class="userAvatar" style="background:url(<?php echo $_SESSION['logged-in']->_thumbnail; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
				<div class="userNameTitle"><?php echo $_SESSION['logged-in']->_username; ?></div>
			</a>
		<?php }else{ ?>
			<a id="loginButtonSideNav" class="waves-effect waves-light btn" href="#loginModal">Login</a>
			<a id="signupButtonSideNav" class="waves-effect waves-light btn" href="#signupModal">Signup</a>
		<?php } ?>
  	</li>
  	<li class="divider"></li>
    <li><a href="#activity">Activity</a></li>
    <li><a href="#profile">Profile</a></li>
    <li><a href="#discover">Discover</a></li>
    <?php if($_SESSION['logged-in'] != null){ ?>
    	<li><a href="#notifications">Notifications</a></li>
	    <li class="divider"></li>
		<li><a href="#!" class="settingsButton">Settings</a></li>
		<?php if($_SESSION['logged-in']->_security == "Admin" || $_SESSION['logged-in']->_security == "AdminMenuOnly"){ ?>
			<li><a href="#!" class="adminButton">Admin</a></li>
		<?php } ?>
		<li><a href="#!" class="supportForumButton">Support</a></li>
		<li><a href="#!" class="supportButton">Report Bug</a></li>
		<li><a href="#!" class="signOutButton">Sign out</a></li>
	<?php } ?>
  </ul>
<?php }

function DisplayMobileBackNav(){ ?>
	<div id="MobileBackContainer">
		<div class="backButton waves-effect waves-light"><i class="mdi-navigation-arrow-back small" style="color:white;vertical-align:middle;padding: 0 0.5em;"></i> <a class="btn-flat backButtonLabel" style="color:white;margin: 0;padding: 0 2em;font-size: 1.5em;vertical-align: middle;" >Back</a></div>
	</div>
<?php }

function DisplayUniversalPopUp(){ ?>
	<div id="universalPopUp" class="modal" style="background-color:white;"></div>
<?php }

function DisplayUniversalBottomSheet(){ ?>
	<div id="universalBottomSheet" class="modal bottom-sheet" style="background-color:white;"></div>
<?php 
}

function DisplayBattleProgressSheet(){ ?>
	<div id="BattleProgess" class="modal bottom-sheet" style="background-color:white;"></div>
<?php
}

function DisplayLifeBarLogo($showtag){ ?>
	<div class="logoContainer <?php if(!$showtag){ ?>logoLandingPage<?php } ?>">
		<div class="logoImage">
			<img src='http://polygonalweave.com/Images/Generic/LifebarLogoTestTopDull.png' />
			<?php if($showtag){ ?> <div class="logoAlpha">alpha</div><?php } ?>
		</div>
	</div>
	<?php /*
	<div class="logoContainer">
		<div class="logoTitle"><b>Life</b>bar</div>
		<div class="logoAlpha">alpha</div>
		<div class="logoLifebar">
			<div class="logoLifebarHealth"></div>
			<div class="logoLifebarDamage"></div>
		</div>
	</div>*/
}
