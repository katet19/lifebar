<?php
	function ShowLanding(){ ?>
 	<div id="loader-wrapper">
 	    <div id="loader"></div>
 	    <div class="loader-section section-left"></div>
 	    <div class="loader-section section-right"></div>
 	</div>
	
	<div class="landing-lifebar-container">
      <div class="landing-lifebar-half-circle"></div>
      <div class="landing-lifebar-bar"></div>
        <div class="landing-lifebar-circle">
          <span class="landing-L"><strong>L</strong></span>
          <div class="landing-lifebar-account">
            <i class="mdi-social-person landing-generic" style='color:white;font-size:2.5em;'></i>
            <div class="landing-manAvatar"><img src="http://lifebar.io/Images/LandingPage/manfront.png" /></div>
          </div>
        </div>
        <div class="landing-lifebar-text">
          <div class="box fade-in one"><strong>i</strong></div>
          <div class="box fade-in two"><strong>f</strong></div>
          <div class="box fade-in three"><strong>e</strong></div>
          <div class="box fade-in four">b</div>
          <div class="box fade-in five">a</div>
          <div class="box fade-in six">r</div>
      	</div>
      <div class="landing-xp-text"></div>
    </div>
    <div class="landing-lifebar-container-mobile">
    	<?php DisplayLifeBarLogoLanding(true); ?>
    </div>
    
    <a href="#landing-sign-up" id="download-button" class="landing-sign-up btn waves-effect waves-light blue" style='z-index:100'>Sign Up</a>
    <div class="landing-login btn waves-effect waves-light blue" style='z-index:100'>Login</div>
    
  <div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">
      <div class="container">
        <br><br>
        <h1 class="header center text-white main-header">YOUR LIFE WITH VIDEO GAMES</h1>
        <div class="row center">

          <h5 class="header col s12 light">Discover, share and visualize gaming as never before</h5>
        </div>
        <div class="row center">
          <a href="#landing-sign-up" id="download-button" class="btn waves-effect waves-light blue landing-sign-up-mobile" style='z-index:100'>Sign Up</a>
          <div class="btn waves-effect waves-light blue landing-login-mobile" style='z-index:100'>Login</div>
        </div>
        <br><br>
      </div>
    </div>
    <img src="http://lifebar.io/Images/LandingPage/playstation-controller.jpg" class="preloadimage" alt="Unsplashed background img 1" style="z-index:5;">
  </div>

	<!-- Play Section -->
    <div class="section">
      <section id="landing-one" style='background-color:#3F51B5;'>
        <div class="landing-monitor"><img src="http://lifebar.io/Images/LandingPage/monitor.png" /></div>
        <div class="landing-controller"><img src="http://lifebar.io/Images/LandingPage/DualShock3-in-Hand.png" /></div>
        <div class="landing-manBack"><img src="http://lifebar.io/Images/LandingPage/manback.png" /></div>
        <div class="innerText innerS1" style='top:375px;'>
          <div class="row">
	        <div class="col s12 m12">
	          <div class="">
	            <div class="card-content">
	              <span class="card-title" style='color:white;'>
	              	<h2 style='font-weight:200;font-size: 4.5em;line-height: 1.2em;'>Play games.<br><strong>Earn experience!</strong></h2>
	          	  </span>
	              <ul class="landing-features" style='color:white;'>
	                  <li style='font-size:1.5em;margin-bottom: 0.25em;'>Whether you played <b>20 years ago</b> or <b>5 minutes ago</b>.</li>
	                  <li style='font-size:1.5em;margin-bottom: 0.25em;'>Write a<b> short blurb</b> about your experience playing.</li>
	                  <li style='font-size:1.5em;margin-bottom: 0.25em;'>Assign a <b>tier relative to other games</b> you have experienced</li>
	              </ul>
	            </div>
	          </div>
	        </div>
	      </div>
      	</div>
      </section>
      
      <!-- Watch Section -->
      <section id="landing-two" style='background:url(http://lifebar.io/Images/LandingPage/LandingPageDota2.jpg) 0% 50%;background-size:cover;margin-top:1em;'>
        <div class="innerText innerS2">
          <div class="row">
	        <div class="col s12 m12">
	          <div>
	            <div class="card-content">
	              <span class="card-title" style='color:white;'>
	            	<h2 style='font-weight:200;font-size: 4.5em;line-height: 1.2em;'>Watch others play.<br><strong>Earn experience!</strong></h2>
		           </span> 
	               <ul class="landing-features" style='color:white;'>
		                <li style='font-size:1.5em;margin-bottom: 0.25em;'><b>Playing isn't the only way</b> to experience games</b></li>
		                <li style='font-size:1.5em;margin-bottom: 0.25em;'>Watch anything from <b> beta coverage</b> to a <b> complete playthrough.</b></li>
		                <li style='font-size:1.5em;margin-bottom: 0.25em;'>All experiences count towards<b> growing your Lifebar</b>.</li>
		            </ul>
		          </div>
		        </div> 
		      </div>
			</div>
        </div>
        <div class="landing-macbook" ><img src="http://lifebar.io/Images/LandingPage/mackbook-pro-retina.png" /></div>
        <div class="landing-gameGif"><img src="http://lifebar.io/Images/LandingPage/fallout4.gif" /></div>
        <div class="landing-manFront"><img src="http://lifebar.io/Images/LandingPage/manfront2.png" /></div>
      </section>
      
      <!-- Numbers Section -->
      <section id="landing-three" style='background:url(http://lifebar.io/Images/LandingPage/LandingPageGraphs.jpg) 0% 50%;background-size:cover;margin-top:1em;'>
        <div class="landingGraphs">
        	<div class="landingGraph1 z-depth-3" style='background-color: white;text-align: left;padding: 25px 0 0 40px;margin-bottom:40px;left:-100%'>
        		<?php DummyGraphData1(); ?>
        	</div>
        	<div class="landingGraph2 z-depth-3" style='background-color: white;text-align: left;padding: 25px 0 0 40px;margin-bottom:40px;'>
        		<?php DummyGraphData2(); ?>
        	</div>
        	<div class="landingGraph3 z-depth-3" style='background-color: white;text-align: left;padding: 25px 0 0 40px;margin-bottom:40px;'>
        		<?php DummyGraphData3(); ?>
        	</div>
        </div>
	    <div class="innerText innerS3">
	  		<div class="row">
		    	<div class="col s12 m12">
		          	<div>
			            <div class="card-content">
				          <span class="card-title" style='color:white;'>
				            <h2 style='font-weight:200;'>Watch the <br><strong>numbers go up!</strong></h2>
				          </span>
				          <ul class="landing-features" style='color:white;'>
				                <li style='font-size:1.5em;margin-bottom: 0.25em;'>As your experience grows, so does <b> your profile</b></li>
				                <li style='font-size:1.5em;margin-bottom: 0.25em;'>See what <b>franchises and developers</b> you have spent the most time with.</li>
				                <li style='font-size:1.5em;margin-bottom: 0.25em;'>Find out what <b>trends define your gaming habits</b></li>
				          </ul>
			          	</div>
		        	</div>
		      	</div>
		    </div>
		</div>
      </section>
	  <!-- Personalize Section -->
      <section id="landing-four" style='background-color:transparent;margin-top:1em;'>
       	<div class="innerText innerS4">
          <div class="row">
        	<div class="col s12 m12">
          		<div>
            		<div class="card-content">
              			<span class="card-title">
            				<h2 style='font-weight:200;'>Don't aggregate. <strong>Personalize.</strong></h2>
        	 			</span>
			            <ul class="landing-features">
			                <li style='font-size:1.5em;margin-bottom: 0.25em;'>You can easily<strong> follow your friends and popular personalities.</strong></li>
			                <li style='font-size:1.5em;margin-bottom: 0.25em;'>Access over <strong>10,000 critic experiences and counting!</strong></li>
			                <li style='font-size:1.5em;margin-bottom: 0.25em;'>Get the scoop on the<strong> latest AAA releases</strong> and <strong>unknown indie darlings.</strong></li>
			            </ul>
          			</div>
        		</div>
    	 	</div>
		  </div>
        </div>
      	<div class="landingActivity">
      		<?php DummyActivityData(); ?>
  		</div>
      </section>
    </div>


  <!--  ********** FOOTER ********** -->
	<div id="landing-sign-up" class="section scrollspy">
    	<div class="row center">
			<div class="col offset-m2 centered" style="width:70%">
				<?php DisplayLandingSignup(); ?>
			</div>
      	</div>
	</div>


	<!-- ********** CREDITS ********** -->
	<div id="landing-c" style='background-color:#D32F2F'>
  		<div class="row center">
			<div class="col s12 centered"  style='color:white;'>
				<div style='margin-bottom:15px;'>
					<a href='https://twitter.com/lifebario' target='_blank' style='display:inline-block;'><img src='http://lifebar.io/Images/LandingPage/twitter-box.png'></a>
					<a href='https://www.facebook.com/lifebario' target='_blank' style='display:inline-block;'><img src='http://lifebar.io/Images/LandingPage/facebook-box.png'></a>
					<a href='http://lifebar.tumblr.com' target='_blank' style='display:inline-block;'><img src='http://lifebar.io/Images/LandingPage/tumblr.png'></a>
				</div>
				<div>Copyright 2016 | LifeBar.io</div>
			</div>
  		</div>
	</div>


  <!--  Scripts-->

    <script src="http://lifebar.io/js/landing-main.js"></script>
    <script src="http://lifebar.io/js/landing-init.js"></script>

	
	<script>
		$(document).ready(function(){
    		$('.scrollspy').scrollSpy();
    		$('.parallax').parallax();
  		});
	</script>
		
		
<?php }

function DisplayLifeBarLogoLanding($showtag){ ?>
	<div class="landinglogoContainer <?php if(!$showtag){ ?>logoLandingPage<?php } ?>">
		<div class="logoImage">
			<img src='http://lifebar.io/Images/Generic/LifebarLogoTestTopDull.png' />
			<?php if($showtag){ ?> <div class="logoAlpha">alpha</div><?php } ?>
		</div>
	</div>
<?php
}

function DummyGraphData1(){ ?>
	<div class="bp-progress-item">
		<div class="bp-item-image z-depth-1" style="background:url(http://static.giantbomb.com/uploads/scale_medium/0/4/11870-capcom.jpg) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;"></div>
		<div class="bp-progress-item-details">
			<div class="bp-progress-item-name">Capcom</div>
			<div class="bp-progress-item-desc">Experience games developed by Capcom</div>
		</div>
		<div class="bp-progress-item-bar">
			<div class="bp-progress-item-bar-before" style="right:25%"></div>
			<div class="bp-progress-item-bar-after" style="left: 75%; width: 0%;" data-width="20%"></div>
		</div>
		<div class="bp-progress-item-levelup">Level 2 <span style="font-weight:300;">(28 / 32)</span></div>
	</div>

<?php	
}

function DummyGraphData2(){ ?>
	<div class="bp-progress-item">
		<div class="bp-item-image z-depth-1" style="background:url(http://static.giantbomb.com/uploads/scale_medium/1/13097/295179-marvelvscapcom1.jpg) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;"></div>
		<div class="bp-progress-item-details">
			<div class="bp-progress-item-name">Marvel vs. Capcom</div>
			<div class="bp-progress-item-desc">Experience games from the Marvel vs. Capcom franchise</div>
		</div>
		<div class="bp-progress-item-bar">
			<div class="bp-progress-item-bar-before" style="right:75%"></div>
			<div class="bp-progress-item-bar-after" style="left: 25%; width: 0%;" data-width="75%"></div>
		</div>
		<div class="bp-progress-item-levelup" data-newlevel="3" data-levelup="Yes">Level 2</span></div>
	</div>

<?php	
}

function DummyGraphData3(){ ?>
	<div class="bp-progress-item">
		<div class="bp-item-image z-depth-1" style="background:url(http://static.giantbomb.com/uploads/scale_small/9/99864/2420176-psx_console_wcontroller.png) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;"></div>
		<div class="bp-progress-item-details">
			<div class="bp-progress-item-name">PlayStation</div>
			<div class="bp-progress-item-desc">Play games on the PlayStation</div>
		</div>
		<div class="bp-progress-item-bar">
			<div class="bp-progress-item-bar-before" style="right:56%"></div>
			<div class="bp-progress-item-bar-after" style="left: 44%; width: 0%;" data-width="15%"></div>
		</div>
		<div class="bp-progress-item-levelup">Level 3 <span style="font-weight:300;">(45 / 75)</span></div>
	</div>	

<?php	
}

function DummyActivityData(){ ?>
<div class="feed-vert-line" style='z-index:-1;left:150px;'></div>
<!-- First Row -->
<div class="row" style="margin-bottom: 30px;">
		<div class="feed-avatar-col">
    		<div class="feed-avatar" style="background:url(http://lifebar.io/Images/LandingPage/mgs.jpg) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp" style="background-color:#2196F3;"><i class="mdi-action-description"></i></div>
		</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<span class="feed-activity-user-link" data-id="7" >katet</span>
											played and watched 
																<span class="feed-activity-game-link" data-gbid="42912">Just Cause 3</span>
										<span class="feed-activity-when-info"><i class="mdi-action-schedule"></i> 5 minutes ago</span>
				</div>
			<div class="feed-activity-game-container">
<div class="feed-horizontal-card z-depth-1" data-gameid="8815" data-gbid="48207">
    <div class="feed-card-image waves-effect waves-block" style="display:inline-block;background:url(http://static.giantbomb.com/uploads/scale_small/8/82063/2699935-jc3.jpg) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    </div>
    <div class="feed-card-content">
      <div class="feed-card-icon tier3BG">
      	      		<i class="mdi-hardware-gamepad"></i>
      	  	  </div>
      <div class="feed-card-title grey-text text-darken-4">
      	      	"The traversal looks like it would be a blast, but I just don't know where I would traverse to.  I need more direction and motivation to play"
      </div>
      <div class="feed-action-container">
      									<div class="btn-flat waves-effect agreeBtn" data-expid="16884" data-agreedwith="7588" data-gameid="8815" data-username="Rewfus">+ 1up</div>
			      </div>
    </div>
  </div>
			</div>
		</div>
	</div>
<!-- Second Row -->
<div class="row" style="margin-bottom: 30px;">
		<div class="feed-avatar-col">
    		<div class="feed-avatar" style="background:url(http://lifebar.io/Images/LandingPage/ackbar.png) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp" style="background-color:#2196F3;"><i class="mdi-action-bookmark"></i></div>
		</div>
		<div class="feed-content-col">
			<div class="feed-activity-title">
									<span class="feed-activity-user-link" data-id="7588">TheAdmiral</span> 
					bookmarked 2 games 
					<span class="feed-activity-when-info"><i class="mdi-action-schedule"></i> 30 minutes ago</span>
							</div>
			<div class="feed-activity-game-container">
				  <div class="feed-bookmark-card z-depth-1" data-gameid="8869" data-gbid="27668">
    <div class="feed-bookmark-image waves-effect waves-block" style="display:inline-block;background:url(http://static.giantbomb.com/uploads/scale_small/0/1516/2499250-witnessposter_610a.jpg) 50% 50%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    	<i class="mdi-action-bookmark" style="  position: absolute;top: -19px;right: 20px;font-size: 3em;color: red;"></i>
    	<div class="feed-card-level-game_title feed-activity-game-link feed-bookmark-title" data-gbid="27668">The Witness</div>
    </div>
  </div>
  <div class="feed-bookmark-card z-depth-1" data-gameid="8678" data-gbid="48190">
    <div class="feed-bookmark-image waves-effect waves-block" style="display:inline-block;background:url(http://static.giantbomb.com/uploads/scale_small/18/187968/2699288-logo-burst-tablet.258zx.jpg) 50% 50%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    	<i class="mdi-action-bookmark" style="  position: absolute;top: -19px;right: 20px;font-size: 3em;color: red;"></i>
    	<div class="feed-card-level-game_title feed-activity-game-link feed-bookmark-title" data-gbid="48190">Overwatch</div>
    </div>
  </div>
			</div>
		</div>
	</div>

<!-- Third Row -->
<div class="row" style="margin-bottom: 30px;">
		<div class="feed-avatar-col">
    		<div class="feed-avatar" style="background:url(http://lifebar.io/Images/CriticAvatars/82s.png) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		</div>
		<div class="feed-activity-icon-col">
									<div class="feed-activity-icon-xp" style="background-color:#2196F3;"><i class="mdi-action-subject"></i></div>
						</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<span class="feed-activity-user-link" data-id="82">Chris Carter</span>
											reviewed
																<span class="feed-activity-game-link" data-gbid="46562">Tom Clancy's Rainbow Six: Siege</span>
										<span class="feed-activity-when-info"><i class="mdi-action-schedule"></i> 6 hours ago</span>
				</div>
			<div class="feed-activity-game-container">
				  <div class="feed-horizontal-card z-depth-1" data-gameid="8921" data-gbid="46562">
    <div class="feed-card-image waves-effect waves-block" style="display:inline-block;background:url(http://static.giantbomb.com/uploads/scale_small/18/187968/2739110-rsz_811ju-locbl_sl1500_.jpg) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    </div>
    <div class="feed-card-content">
      <div class="feed-card-icon tier2BG">
      	      		<i class="mdi-editor-format-quote"></i>
      	  	  </div>
      <div class="feed-card-title grey-text text-darken-4">
      	      	"While a few other major shooters have let me down this year, I think Siege is one of the games I'll be playing the most going forward."
      </div>
      <div class="feed-action-container">
      					<a href="http://www.destructoid.com/review-tom-clancy-s-rainbow-six-siege-323032.phtml" target="_blank"><div class="btn-flat waves-effect readBtn">READ</div></a>
						<div class="btn-flat waves-effect agreeBtn" data-expid="16884" data-agreedwith="7588" data-gameid="8815" data-username="Rewfus">+ 1up</div>
			      </div>
    </div>
  </div>
   			</div>
		</div>
	</div>
	
<!-- Fourth Row -->
<div class="row" style="margin-bottom: 30px;">
		<div class="feed-avatar-col">
    		<div class="feed-avatar" style="background:url(http://www.gravatar.com/avatar/70c0d02d66e05b7a72e61cf1350fc020?s=100&amp;d=mm&amp;r=g) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp" style="background-color:#2196F3;"><i class="mdi-action-description"></i></div>
		</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<span class="feed-activity-user-link" data-id="7588">Rewfus</span>
											updated their thoughts for 
																<span class="feed-activity-game-link" data-gbid="36067">Destiny</span>
										<span class="feed-activity-when-info"><i class="mdi-action-schedule"></i> Yesterday</span>
				</div>
			<div class="feed-activity-game-container">
				  <div class="feed-horizontal-card z-depth-1" data-gameid="821" data-gbid="36067">
    <div class="feed-card-image waves-effect waves-block" style="display:inline-block;background:url(http://static.giantbomb.com/uploads/scale_small/0/3699/2669576-destiny+v2.jpg) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    </div>
    <div class="feed-card-content">
      <div class="feed-card-title grey-text text-darken-4">
      	      	"The Taken King has given new life to this game in a way I never expected.  It almost upsets me more knowing it could have started this good."
      </div>
            <div class="feed-action-container">
  				<div class="btn-flat waves-effect agreeBtn" data-expid="16884" data-agreedwith="7588" data-gameid="8815" data-username="Rewfus">+ 1up</div>
	      </div>
    </div>
  </div>
			</div>
		</div>
	</div>
<?php } ?>
