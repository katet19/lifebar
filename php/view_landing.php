<?php
	function ShowLanding(){ ?>
<!-- 	<div class="pressbtn-container">-->
<!-- 	Pre Loader -->
 	<div id="loader-wrapper">
 	    <div id="loader"></div>
	 
 	    <div class="loader-section section-left"></div>
 	    <div class="loader-section section-right"></div>
	 
 	</div>
	
<!-- 	Hero
// 	<div class="section no-pad-bot" id="index-banner">
// 	    <div class="container">
// 	        <h1 class="text_h center header cd-headline letters type">
// 	            <span>I </span> 
// 	            <span class="cd-words-wrapper waiting">
// 	                <b class="is-visible">&nbsp;play</b>
// 	                <b>&nbsp;watch</b>
// 	                <b>&nbsp;critique</b>
// 	                <b>&nbsp;stream</b>
// 	                <b>&nbsp;download</b>
// 	                <b>&nbsp;experience</b>
// 	            </span>
// 	            <span style='padding-left:20px;'> games</span>
// 	        </h1>
// 	    </div>
// 	</div>
// 	<div class="pressbtn-title">Press Any Button To Continue</div>
// </div> -->
		
		<! -- ********** HEADER ********** -->
	<div id="h">
		<div class="container">
      <div class="col m4" style="text-align:left;">
        <?php DisplayLifeBarLogo(false); ?>
      </div>
			<div class="row" style="margin-bottom: 0px">
			    <li><div class="btn btn-lg btn-info btn-login">Login</div> <div class="btn btn-lg btn-info btn-register">Sign Up</div></li>
          <!-- <li><button class="btn btn-lg btn-info btn-register">Sign Up</button></li> -->
			    <div class="col m10 offset-m1 mt">
			    	<h5>Visualize and share</h5>
			    	<h1 class="mb">YOUR LIFE WITH VIDEO GAMES</h1>
			    </div>
			    <div class="col m12 mt hidden-xs" style="padding-top:80px">
			    	<img src="Images/LandingPage/Profile.png" id="landing_profile" class="responsive-img center-align z-depth-1" alt="">
			    </div>
			</div>
		</div><! --/container -->
	</div><! --/h -->

	<! -- ********** FIRST ********** -->
	<div id="w">
		<div class="row nopadding">
			<div class="col m5 offset-m1 offset-l2 left-align">
				<h4>Played or Watched</h4>
				<p>We consume media in many different ways.  Video games are no longer confined to a couch and TV.</p>
				<p>Share all of your experiences from the first controller you picked up, to the last Let's Play you watched.</p>
				<p>It all comes together to form your unique gaming profile, along with a personalized <strong>lifebar</strong>.</p>
				<p class="mt"><a href="#landing-sign-up" class="btn btn-info btn-theme">Get Started Now</a></p>
			</div>

			<div class="col m6 l5" style="padding:0">
				<img src="Images/LandingPage/XP.png" id="landing_xp" class="responsive-img right z-depth-1" alt="">
			</div>
		</div><! --/row -->
	</div><! --/container -->


	<! -- ********** BLUE SECTION - PICTON ********** -->
	<div id="picton">
		<div class="row nopadding">
			<div class="col m6 l5" style="padding:0">
				<img src="Images/LandingPage/Discover.png" id="landing_discover" class="responsive-img left z-depth-1" alt="">
			</div>
			<div class="col m5 l6 left-align">
				<h4>Discover New and Old</h4>
				<p>Check in on the latest releases or simply walk down memory lane.</p>
				<p>Every experience counts, whether it was 15 minutes ago, or 15 years ago.</p>
				<p>Easily track upcoming games, while being notified of similar games that you may have played as a child.</p>
				<p class="mt"><a href="#landing-sign-up"  class="btn btn-white btn-theme">Discover Games Now</a></p>
			</div>

		</div><! --row -->
	</div><! --/Picton -->


	<! -- ********** BLUE SECTION - CURIOUS ********** -->
	<div id="curious">
		<div class="row nopadding">
			<div class="col m5 offset-m1 offset-l2 left-align">
				<h4>Don't Aggregate.  Personalize.</h4>
				<p>No longer do you need to read an aggregated score from people you know nothing about to inform you about the games you love.</p>
				<p>Follow friends and popular personalities to see what they think about the last game they played, or maybe even memories from their past.</p>
				<p>Find similar tastes and watch how it may shape your future experiences.</p>
				<p class="mt"><a href="#landing-sign-up" class="btn btn-white btn-theme">Find Out What People Are Saying</a></p>
			</div>

			<div class="col m6 l5" style="padding:0">
				<img src="Images/LandingPage/Activity.png" id="landing_activity" class="responsive-img right z-depth-1" alt="">
			</div>
		</div><! --/row -->
	</div><! --/curious -->

	<! -- ********** BLUE SECTION - MALIBU ********** -->
	<div id="malibu">
		<div class="row nopadding">
			<div class="col m6 l5" style="padding:0">
				<img src="Images/LandingPage/Notifications.png" id="landing_notifications" class="responsive-img left z-depth-1" alt="">
			</div>
			<div class="col m5 l6 left-align">
				<h4>Stay Updated with Notifications</h4>
				<p>Are others having similar experiences than you?  Are there new thoughts regarding an upcoming release you're interested in?</p>
				<p>Be alerted when someone agrees with you.  Get notifications on upcoming releases.  Read game suggestions based on previous experiences.</p>
				<p>Leave no stone unturned as we guide you through your ongoing life with video games!</p>
				<p class="mt"><a href="#landing-sign-up"  class="btn btn-white btn-theme">Share Your Experiences Now</a></p>
			</div>

		</div><! --row -->
	</div><! --/Malibu -->


	<! -- ********** BLUE SECTION - JELLY ********** -->
	<!-- <div id="jelly">
		<div class="row nopadding">
			<div class="col-md-5 col-md-offset-1 mt">
				<h4>Don't Wait, Try Us Now</h4>
				<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
				<p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
				<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
				<p class="mt"><button class="btn btn-white btn-theme">Sign Up Now | 14 Days Free</button></p>
			</div>

			<div class="col-md-6 mt centered">
				<h1 data-effect="slide-bottom">24</h1>
				<h3>$ per month</h3>
			</div>
		</div><! --/row
	</div><! --/Jelly -->

	<! -- ********** FOOTER ********** -->
	<div id="landing-sign-up" class="section scrollspy">
		<div class="container">
			<div class="row">
				<div class="col m8 offset-m2 centered">
					<h4 class="mb">Sign Up Now to Find Out More.</h4>
					<form action="//polygonalweave.us8.list-manage.com/subscribe/post?u=4bc595c55268d466c30ef72dd&amp;id=0b032cbe1c" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
	    				<div id="mc_embed_signup_scroll">
	    					<input type="email" name="EMAIL" value="" class="email subscribe-input" id="mce-EMAIL" placeholder="Enter your e-mail address..." required>
	    					<div style="position: absolute; left: -5000px;"><input type="text" name="b_4bc595c55268d466c30ef72dd_0b032cbe1c" tabindex="-1" value=""></div>
							<button class='btn btn-lg btn-info btn-sub subscribe-submit' value="Subscribe" name="subscribe" id='mc-embedded-subscribe' type="submit">Yes, Please</button>
						</div>
					</form>

				</div>

			</div><! --/row -->
		</div><! --/container -->
	</div><! --/F -->
	

	<! -- ********** CREDITS ********** -->
	<div id="c">
		<div class="container">
			<div class="row">
				<div class="col m6 offset-m3 centered">
					<p>Copyright 2015 | LifeBar.io</p>
				</div>
			</div>
		</div><! --/container -->
	</div><! --/C -->
	
	<script>
		$(document).ready(function(){
    		$('.scrollspy').scrollSpy();
  		});
	</script>
		
		
<?php }

function DisplayLifeBarLogoLanding(){ ?>
	<div class="logoContainer">
		<div class="logoTitle"><b>Life</b>bar</div>
		<div class="logoAlpha" style='bottom:5px'>alpha</div>
		<div class="logoLifebar">
			<div class="logoLifebarHealth"></div>
			<div class="logoLifebarDamage"></div>
		</div>
	</div>
<?php
}
?>