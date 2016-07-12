<?php

function AccountDetails(){
	?>
	
	<div class="row" style='margin-bottom:50px;'>
		<div class="col s12 m6 offset-m3" style='text-align:left;'>
			<div class='onboarding-big-welcome'>Welcome to Lifebar <?php echo $_SESSION['logged-in']->_username; ?>!</div>
			<div class='onboarding-sub-welcome'>Lifebar is the best place to <span style='font-weight:500;'>save</span> and <span style='font-weight:500;'>share</span> your life playing & watching video games!</div>
			<div class='onboarding-sub-welcome'>Tell us a little bit about your yourself and we'll help you get set up.</div>
		</div>
		<!--<div class="col s12 m3">
			<div class="onboarding-image-welcome" style="background-image: url('http://lifebar.io/Images/LandingBeta/HyperLightDrifterBoxArt.png');"></div>
			<div class="onboarding-image-welcome" style="background-image: url('http://lifebar.io/Images/LandingBeta/doom-boxart.jpg');"></div>
			<div class="onboarding-image-welcome" style="background-image: url('http://lifebar.io/Images/LandingBeta/AndrewReinerCard.png');"></div>
			<div class="onboarding-image-welcome" style="background-image: url('http://lifebar.io/Images/LandingBeta/Stardew_valley_boxart.png');"></div>
			<div class="onboarding-image-welcome" style="background-image: url('http://lifebar.io/Images/LandingBeta/ChrisCarterCard.png');"></div>
		</div>-->
	</div>
  	<div class="row">
      	<div class="input-field col s12 m6 offset-m3">
	        <img class="prefix" src='http://lifebar.io/Images/Generic/steam-badge.png' style='height:45px;width:45px;'></i>
	        <input id="steam_id" style='margin-left: 4rem;font-size:1.25em;' type="text" value="<?php echo $_SESSION['logged-in']->_steam; ?>">
	        <label for="steam_id" style='margin-left: 4rem;'  <?php if($_SESSION['logged-in']->_steam != ""){ echo "class='active'"; } ?>>Steam ID</label>
	  	</div>
  	</div>
  	<div class="row">
		<div class="input-field col s12 m6 offset-m3">
	        <img class="prefix" src='http://lifebar.io/Images/Generic/xbox-badge.png' style='height:45px;width:45px;border-radius: 5px;'></i>
	        <input id="xbox_id" style='margin-left: 4rem;font-size:1.25em;' type="text" value="<?php echo $_SESSION['logged-in']->_xbox; ?>">
	        <label for="xbox_id"  style='margin-left: 4rem;' <?php if($_SESSION['logged-in']->_xbox != ""){ echo "class='active'"; } ?>>Xbox Live ID</label>
	  	</div>
  	</div>
  	<div class="row">
	  	<div class="input-field col s12 m6 offset-m3">
		    <img class="prefix" src='http://lifebar.io/Images/Generic/playstation-badge.png' style='height:45px;width:45px;'>
		    <input id="psn_id" style='margin-left: 4rem;font-size:1.25em;' type="text" value="<?php echo $_SESSION['logged-in']->_psn; ?>">
		    <label for="psn_id" style='margin-left: 4rem;'  <?php if($_SESSION['logged-in']->_psn != ""){ echo "class='active'"; } ?>>PSN ID</label>
	  	</div>
  	</div>
    <div class="row">
      	<div class="input-field col s3 m2 offset-m3">
	        <input id="age_id" type="text" value="">
	        <label for="age_id">Age</label>
	  	</div>
	  	<div class="col s5 m4" style='position: relative;height: 40px;'>
	  		<div class="onboarding-description" style='left: 0;text-align: left;position: absolute;bottom: 0;'>Your age is used to build an accurate timeline of your gaming history</div>
	  	</div>
  	</div>
  	<div class="onboarding-top-level" style='margin-top:100px;'>
  		<div class="btn onboarding-next">Next</div>
  		<div class="btn-flat onboarding-skip">Skip</div>
  	</div>
	<?php
}

function SocialDetails(){
	$critics = GetActivePersonalities();
	$users = GetUsersWithPopularQuotes('');
	?>
	<div class="col s12 m6 offset-m3" style='text-align:left;'>
		<div class='onboarding-big-welcome'>Follow friends and personalities</div>
		<div class='onboarding-sub-welcome'>You can follow other gamers to see what they <span style='font-weight:500;'>think</span> about the games they have been playing/watching. Follow a bunch to make your activity feed awesome!</div>
	</div>
	<div class="row" style='margin-bottom:5px;'>
		<div class="col s12 m6 l4 offset-l4 offset-m4" style='height: 47px;position: relative;text-align: right;z-index: 0;margin-top: 20px;'>
			<i class="mdi-action-search small collection-search-icon" style='color:rgba(0,0,0,0.9);left: 10px;right:inherit;'></i>
			<div class='collection-search-box z-depth-1' style='background-color:white;width:100%;color:rgba(0,0,0,0.9);'>
				<input id="collection-search" type="text" style='border: none !important;color:rgba(0,0,0,0.9);margin: 0;display:inline-block;'>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col s12 m6 l4 offset-l4 offset-m4">
			<div class='onboarding-description'>Search for your favorite personalities by name or friends by username/email</div>
		</div>
	</div>
	<div class="row">
		<div class="col s12 m6 l4 offset-l4 offset-m4 search-results" style="margin:20px 0 50px;display:none;">
			
		</div>
	</div>
	<div class="row">
		<div class="col s12 m8 offset-m2" style='position: relative;margin-top: 50px;'>
			<div class="onboarding-follow-header">Personalities you might like</div>
	        	<input type="checkbox" id="onboarding-follow-personalities-all"   checked />
	        	<label for="onboarding-follow-personalities-all" class="onboarding-follow-personalities-all">Follow all personalities</label>
		</div>
		<div class="col s12 m8 offset-m2">
			
			<?php foreach($critics as $critic){
				DisplayFollowUserCard($critic, true, false);	
			}?>
		</div>
	</div>
	<div class="row">
		<div class="col s12 m8 offset-m2" style='position: relative;margin-top: 75px;'>
			<div class="onboarding-follow-header">Members you might like</div>
	        	<input type="checkbox" id="onboarding-follow-users-all" />
	        	<label for="onboarding-follow-users-all" class="onboarding-follow-personalities-all">Follow All</label>
		</div>
		<div class="col s12 m8 offset-m2">
			<?php 
			$count = 0; $list = array();
			while($count < 8){
				DisplayFollowUserCard($users[0][$count], false, true);
				$list[] = $users[0][$count]->_id;
				$count++;
			}?>
			<div>
				<div class="btn-flat onboarding-member-view-more" style='margin-top:20px;font-weight:bold;' data-alreadyshowing='<?php echo implode(",",$list); ?>'><i class="fa fa-refresh" style='margin-right:10px;'></i> View More Lifebar Members</div>
			</div>
		</div>
	</div>
  	<div class="onboarding-top-level" style='margin-top:100px;'>
  		<div class="btn onboarding-next">Next</div>
  		<div class="btn-flat onboarding-skip">Skip</div>
  	</div>
	<?php
}

function ViewMoreMembers($exclude){
		$users = GetUsersWithPopularQuotes($exclude);
		$count = 0; $list = array();
		while($count < 8){
			DisplayFollowUserCard($users[0][$count], false, true);
			$list[] = $users[0][$count]->_id;
			$count++;
		}?>
		<div>
			<div class="btn-flat onboarding-member-view-more" style='margin-top:40px;font-weight:bold;' data-alreadyshowing='<?php echo implode(",",$list).",".$exclude; ?>'><i class="fa fa-refresh" style='margin-right:10px;'></i> View More Lifebar Members</div>
		</div>
	<?php
}

function GamingPrefDetails(){
	$details = GetOnboardingMilestones();
	?>
	<div class="row">
		<div class="col s12 m6 offset-m3" style='text-align:left;'>
			<div class='onboarding-big-welcome'>Your gaming preferences/history</div>
			<div class='onboarding-sub-welcome'>Help us customize your experience by picking a few platforms, franchises & developer's you are most familiar with.</div>
		</div>
	</div>
	<div class="row">
		<div class="col s12 m10 offset-m1">
			<div class="row">
				<?php foreach($details as $detail){ ?>
				<div class="col s6 m4 l3">
					<div class="knowledge-container" style='background-color:#0a67a3;' data-id="<?php echo $detail->_id; ?>" data-objectid="<?php echo $detail->_objectid; ?>">
						<?php if($detail->_image == ""){ ?>
							<div class="onboarding-pref-image" style='text-align: center;background-color: orange;padding-top: 5px;margin-bottom: 5px;'><i class="bp-item-image-icon mdi-content-flag"></i>
						<?php }else{ ?>
							<div class="onboarding-pref-image" style="background:url(<?php echo $detail->_image; ?>) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;">
						<?php } ?>
							<i class="pref-checkmark fa fa-check"></i>
							<div class="onboarding-pref-image-title">
								<?php echo $detail->_name; ?>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
  	<div class="onboarding-top-level" style='margin-top:100px;'>
  		<div class="btn onboarding-next">Finish</div>
  		<div class="btn-flat onboarding-skip">Skip</div>
  	</div>
	<?php
}

function ViewOnboardingUserSearch($searchstring){
	?>
	Search Results <?php echo $searchstring; ?>
	<?php
}
