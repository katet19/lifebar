<?php

function AccountDetails(){
	?>
	
	<div class="row" style='margin-bottom:50px;'>
		<div class="col s10 offset-s1" style='text-align:left;'>
			<div class='onboarding-big-welcome'>Welcome to Lifebar <span style='color:#3F51B5'><?php echo $_SESSION['logged-in']->_username; ?></span>!</div>
			<div class='onboarding-sub-welcome'>Lifebar is the best place to <span style='font-weight:500;'>capture</span> and <span style='font-weight:500;'>celebrate</span> your life playing & watching video games!</div>
			<!--<div class='onboarding-sub-sub-welcome'><i class="material-icons onboarding-sub-welcome-icon">share</i> Share your thoughts about games, past & present, with friends and fellow gamers.</div> -->
			<div class='onboarding-sub-sub-welcome'><i class="material-icons onboarding-sub-welcome-icon">visibility</i> Add your watched experiences, whether it's your thoughts on the latest Mario 64 speed run or the most interesting E3 reveals you can quickly save and share with others</div>
			<div class='onboarding-sub-sub-welcome'><i class="material-icons onboarding-sub-welcome-icon">gamepad</i>Journal your time playing a game and capture your thoughts and feelings as you play from start to finish.</div>
			<div class='onboarding-sub-sub-welcome'><i class="material-icons onboarding-sub-welcome-icon">swap_vert</i> Rank your all time games list and rank intersting sub-lists based on year, genre & platform</div>
			<div class='onboarding-sub-sub-welcome'><i class="material-icons onboarding-sub-welcome-icon">whatshot</i> Follow your favorite gaming personalities & friends to create a personalized gaming activity feed</div>
			<div class='onboarding-sub-welcome' style='margin-top:10px;'>Tell us a little bit about your yourself and we'll help you get started</div>
		</div>
	</div>
  	<div class="row">
      	<div class="input-field col s10 offset-s1 m6 offset-m1">
	        <img class="prefix" src='http://lifebar.io/Images/Generic/steam-badge.png' style='height:45px;width:45px;'></i>
	        <input id="steam_id" style='margin-left: 4rem;font-size:1.25em;' type="text" value="<?php echo $_SESSION['logged-in']->_steam; ?>">
	        <label for="steam_id" style='margin-left: 4rem;text-align:left;'  <?php if($_SESSION['logged-in']->_steam != ""){ echo "class='active'"; } ?>>Steam ID</label>
	  	</div>
  	</div>
  	<div class="row">
		<div class="input-field col s10 offset-s1 m6 offset-m1">
	        <img class="prefix" src='http://lifebar.io/Images/Generic/xbox-badge.png' style='height:45px;width:45px;border-radius: 5px;'></i>
	        <input id="xbox_id" style='margin-left: 4rem;font-size:1.25em;' type="text" value="<?php echo $_SESSION['logged-in']->_xbox; ?>">
	        <label for="xbox_id"  style='margin-left: 4rem;text-align:left;' <?php if($_SESSION['logged-in']->_xbox != ""){ echo "class='active'"; } ?>>Xbox Live ID</label>
	  	</div>
  	</div>
  	<div class="row">
	  	<div class="input-field col s10 offset-s1 m6 offset-m1">
		    <img class="prefix" src='http://lifebar.io/Images/Generic/playstation-badge.png' style='height:45px;width:45px;'>
		    <input id="psn_id" style='margin-left: 4rem;font-size:1.25em;' type="text" value="<?php echo $_SESSION['logged-in']->_psn; ?>">
		    <label for="psn_id" style='margin-left: 4rem;text-align:left;'  <?php if($_SESSION['logged-in']->_psn != ""){ echo "class='active'"; } ?>>PSN ID</label>
	  	</div>
  	</div>
    <div class="row">
      	<div class="input-field col s6 offset-s1 m6 offset-m1">
      		<?php if($_SESSION['logged-in']->_birthdate != ''){ $now = Date('Y');$age = $now - $_SESSION['logged-in']->_birthdate; }else{ $age = 25; } ?>
      		<div style='float: left;margin-right: 10px;line-height: 50px;font-size: 1.5em;font-weight: 400;display: inline-block;'>Age</div>
	        <input id="age_id" style='font-size:1.25em;width:50px;float:left;text-align:center;' type="text" value="<?php echo $age; ?>">
		  	<div class="" style='position: relative;height: 40px;float:left;width:75%;'>
		  		<div class="onboarding-description" style='display:none;left: 0;text-align: left;position: absolute;bottom: 0;right:0;'>Your age is used to build an accurate timeline of your gaming history</div>
		  	</div>
  	  	</div>
  	</div>
  	<div class="onboarding-top-level" style='margin-top:100px;'>
  		<div class="btn onboarding-next" style='font-weight:bold;'>Next</div>
  	</div>
	<?php
}

function SocialDetails(){
	$critics = GetActivePersonalities();
	$users = GetUsersWithPopularQuotes('');
	$pubs = GetAllPublications();
	?>
	<div class="col s10 offset-s1">
		<div class='onboarding-big-welcome'>Follow friends and personalities</div>
		<div class='onboarding-sub-welcome'>Light up your activity feed with <span style='font-weight:500;'>insight</span> from fellow gamers!</div>
	</div>
	<div class="row" style='margin-bottom:5px;'>
		<div class="col s10 offset-s1" style='height: 47px;position: relative;text-align: right;z-index: 0;margin-top: 20px;'>
			<i class="mdi-action-search small onboarding-search-icon" style='color:rgba(0,0,0,0.9);left: 10px;right:inherit;'></i>
			<div class='onboarding-search-box z-depth-1' style='background-color:white;width:100%;color:rgba(0,0,0,0.9);'>
				<input id="onboarding-search" type="text" style='border: none !important;color:rgba(0,0,0,0.9);margin: 0;display:inline-block;'>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col s12">
			<div class='onboarding-description'>Search for <span style='font-weight:400;'>personalities</span> by name or <span style='font-weight:400;'>friends</span> by username/gamertag</div>
		</div>
	</div>
	<div class="row">
		<div class="col s12 search-results" style="margin-top:20px;margin-bottom:50px;display:none;">
			
		</div>
		<div class="col s12 search-results-selected" style="margin-bottom:50px;display:none;">
			<div class="onboarding-follow-header">Following from Search Results</div>
		</div>
	</div>
	<div class="row">
		<div class="col s12" style='position: relative;margin-top: 50px;'>
			<div class="onboarding-follow-header">Personalities you might like</div>
	        	<input type="checkbox" id="onboarding-follow-personalities-all" />
	        	<label for="onboarding-follow-personalities-all" class="onboarding-follow-personalities-all">Follow All</label>
		</div>
		<div class="col s12">
			<?php foreach($critics as $critic){
				DisplayFollowUserCard($critic, false, false, false);	
			}?>
		</div>
	</div>
	<div class="row">
		<div class="col s12" style='position: relative;margin-top: 50px;'>
			<div class="onboarding-follow-header">Follow personalities from your favorite sites</div>
		</div>
		<div class="col s12">
			<?php foreach($pubs as $pub){
				?>
				<div class="col s4 m3 l2">
					<div class="onboarding-pub z-depth-1" style="background:url(http://lifebar.io/Images/Generic/Logos/<?php echo strtolower(str_replace(" ","",$pub)).".png"; ?>) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;">
						<i class="pref-checkmark fa fa-check" style='top: 5px;right: 5px;font-size: 1.25em;'></i>
     					<div style="display: block;vertical-align: middle;height: 45px;padding-top: 10px;overflow: hidden;color:white;line-height: 14px;">
							<?php echo $pub; ?>
						</div>
					</div>
				</div>
				<?php
			}?>
		</div>
	</div>
	<div class="row">
		<div class="col s12" style='position: relative;margin-top: 75px;'>
			<div class="onboarding-follow-header">Members you might like</div>
	        	<input type="checkbox" id="onboarding-follow-users-all" />
	        	<label for="onboarding-follow-users-all" class="onboarding-follow-personalities-all">Follow All</label>
		</div>
		<div class="col s12">
			<?php 
			$count = 0; $list = array();
			while($count < 8){
				DisplayFollowUserCard($users[0][$count], false, true, false);
				$list[] = $users[0][$count]->_id;
				$count++;
			}?>
			<div>
				<div class="btn-flat onboarding-member-view-more" style='margin-top:20px;font-weight:bold;' data-alreadyshowing='<?php echo implode(",",$list); ?>'><i class="fa fa-refresh" style='margin-right:10px;'></i> View More Lifebar Members</div>
			</div>
		</div>
	</div>
  	<div class="onboarding-top-level" style='margin-top:100px;'>
  		<div class="btn onboarding-next" style='font-weight:bold;'>Next</div>
  	</div>
	<?php
}

function ViewMoreMembers($exclude){
		$users = GetUsersWithPopularQuotes($exclude);
		$count = 0; $list = array();
		while($count < 8){
			DisplayFollowUserCard($users[0][$count], false, true, false);
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
		<div class="col s10 offset-s1" style='text-align:left;'>
			<div class='onboarding-big-welcome'>Your gaming preferences/history</div>
			<div class='onboarding-sub-welcome'>Help us customize your experience by picking a few platforms, franchises & developers you are <span style='font-weight:500;'>most familiar</span> with.</div>
		</div>
	</div>
	<div class="row">
		<div class="col s12">
			<div class="row">
				<?php foreach($details as $detail){ ?>
				<div class="col s6 m4 l3">
					<div class="knowledge-container" style='background-color:#0a67a3;' data-id="<?php echo $detail->_id; ?>" data-objectid="<?php echo $detail->_category."_".$detail->_objectid; ?>">
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
  		<div class="btn onboarding-next" style='font-weight:bold;'>Finish</div>
  	</div>
	<?php
}

function ViewOnboardingUserSearch($searchstring){
	$results = SearchForUser($searchstring);
	if(sizeof($results) > 0){
		foreach($results as $result){ 
			DisplayFollowUserCard($result, false, false, false);
		}
	}else{
		echo "<div style='font-size:1.25em;'>0 results found</div>";
	}
}
