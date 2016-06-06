<?php 
function DisplayGame($gbid){
	$game = GetGameByGBIDFull($gbid);
	$myxp = GetExperienceForUserByGame($_SESSION['logged-in']->_id, $game->_id);
	$myxp->_bucketlist = IsGameBookmarkedFromCollection($game->_id);
	ShowGameHeader($game, $myxp, -1);
	ShowGameContent($game, $myxp, -1);
}

function DisplayGameViaID($gameid, $userid){
	$game = GetGame($gameid);
	$myxp = GetExperienceForUserByGame($_SESSION['logged-in']->_id, $game->_id);
	$myxp->_bucketlist = IsGameBookmarkedFromCollection($gameid);
	if($userid > 0){
		$otherxp = GetExperienceForUserByGame($userid, $game->_id);
		ShowGameHeader($game, $myxp, $otherxp);
		ShowGameContent($game, $myxp, $otherxp);
	}else{
		ShowGameHeader($game, $myxp, -1);
		ShowGameContent($game, $myxp, -1);
	}
}

function ShowGameContent($game, $myxp, $otherxp){ 
?>
	<div id="gameContentContainer" data-gbid="<?php echo $game->_gbid; ?>" data-title="<?php echo urlencode($game->_title); ?>" data-id="<?php echo $game->_id; ?>" class="row">
		<div id="game-community-tab" class="col s12 game-tab">
			<?php ShowCommunity($game, $_SESSION['logged-in']->_id, $myxp); ?>
			<div class="col s12 m12 l10" id='game-width-box'></div>
		</div>
		<div id="game-analyze-tab" class="col s12 game-tab"><?php DisplayAnalyzeTab($_SESSION['logged-in'], $myxp, $game); ?></div>
		<?php if(isset($_SESSION['logged-in']->_id)){ ?>
			<div id="game-myxp-tab" class="col s12 game-tab">
				<?php if($myxp->_tier != 0){ ShowMyXP($myxp); } ?>
			</div>
		<?php } ?>
		<div id="game-userxp-tab" class="col s12 game-tab" <?php if($otherxp == -1){ ?> style='display:none;' <?php } ?> >
			<?php if($otherxp != -1){
					ShowUserXP($otherxp);
					}?>
		</div>
	</div>
	<?php DisplayGameInfo($game); ?>
<?php }

function ShowCommunity($game, $id, $myxp){
	if($id != ""){
		$verified = GetVerifiedXPForGame($game->_id, $id);
		$curated = GetCuratedXPForGame($game->_id, $id);
		$myusers = GetMyUsersXPForGame($game->_id, $id);
	}else{
		$id = -1;
	}
	$otherverified = GetOutsideVerifiedXPForGame($game->_id, $id);
	$othercurated = GetOutsideCuratedXPForGame($game->_id, $id);
	$otherusers = GetOutsideUsersXPForGame($game->_id, $id);
	if($id != ""){
		?>
		<?php if(sizeof($verified) > 0){ ?>
		<div class='game-community-box'>
			<div class='game-community-box-header'><div class="game-community-verified mdi-action-done"></div> Verified</div>
			<div class='row'>
				<?php DisplayAllCommunityCards($verified, "Critic"); ?>
			</div>
		</div>
		<?php }
		if(sizeof($myusers) > 0){ ?>
		<div class='game-community-box'>
			<div class='game-community-box-header'><i class="mdi-social-people" style='display:inline-block;font-size:1.4em;'></i> <div style='display: inline-block;vertical-align: text-bottom;'>Members</div></div>
			<?php DisplayAllCommunityCards($myusers, "Users"); ?>
		</div>
		<?php }
		if(sizeof($curated) > 0){ ?>
		<div class='game-community-box'>
			<div class='game-community-box-header'><i class="mdi-file-folder-shared" style='display:inline-block;font-size:1.4em;'></i> <div style='display: inline-block;vertical-align: text-bottom;'>Curated</div></div>
			<?php DisplayAllCommunityCards($curated, "Critic");	?>
		</div>
		<?php }else if(sizeof($othercurated) == 0 && sizeof($otherverified) == 0 && sizeof($verified) == 0){ ?>
			<?php if($myxp->_bucketlist != "Yes"){ ?>
				<?php if($game->_released < date('Y-m-d', strtotime('-8 day'))){ ?>
					<div class="info-label">Bookmark this game to keep track of your favorites</div>
				<?php }else{ ?>
					<div class="info-label">Bookmark this game to get notified when critics start publishing reviews!</div>
				<?php } ?>
				<?php if($_SESSION['logged-in']->_id > 0){ ?>
					<div class="btn waves-effect waves-light no-critic-bookmark"><i class="mdi-action-bookmark left"></i> Bookmark</div>
				<?php }else{ ?>
					<div class="btn waves-effect waves-light fab-login"><i class="mdi-action-bookmark left"></i> Bookmark</div>
				<?php } ?>
			<?php } ?>
		<?php }
	}?>
	<?php if(sizeof($otherverified) > 0 || sizeof($othercurated) > 0 || sizeof($otherusers) > 0){ ?>
		<?php if($_SESSION['logged-in']->_id > 0){ ?>
			<div class='game-community-bigbreak'>
				NOT FOLLOWING
			</div>
		<?php } ?>
		<?php if(sizeof($otherverified) > 0){ ?>
		<div class='game-community-box'>
			<div class='game-community-box-header'><div class="game-community-verified mdi-action-done"></div> Verified</div>
			<?php DisplayAllCommunityCards($otherverified, "Critic");	?>
		</div>
			<?php }
		if(sizeof($othercurated) > 0){ ?>
		<div class='game-community-box'>
			<div class='game-community-box-header'><i class="mdi-file-folder-shared" style='display:inline-block;font-size:1.4em;'></i> <div style='display: inline-block;vertical-align: text-bottom;'>Curated</div></div>
			<?php DisplayAllCommunityCards($othercurated, "Critic");	?>
		</div>
		<?php }
		if(sizeof($otherusers) > 0){ ?>
		<div class='game-community-box'>
			<div class='game-community-box-header'><i class="mdi-social-people" style='display:inline-block;font-size:1.4em;'></i> <div style='display: inline-block;vertical-align: text-bottom;'>Members</div></div>
			<?php DisplayAllCommunityCards($otherusers, "Users"); ?>
		</div>
	<?php }
	}
}

function DisplayAllCommunityCards($users, $type){
	$count = 1;
	$sortarray = array();
	foreach($users as $v){
		$sortarray[$v->_id] = GetTotalAgreesForXP($v->_id);
	}
	arsort($sortarray);
	$sortarray = array_keys($sortarray);
	
	while($count <= sizeof($sortarray)){
		if($type == "Critic")
			DisplayCriticQuoteCard($users[$sortarray[$count-1]]);
		else
			DisplayUserQuoteCard($users[$sortarray[$count-1]]);
		$count++;
	}
}

function ShowGameTabs($myxp, $otherxp){
	?>
	<div id="game-navigation-header">
		<div class="row" style='margin:0;'>
		    <div class="col s12 m8" style="padding:0;">
		      <ul class="tabs gameNav" style="background-color:transparent">
		      	<li class="tab col s3 criticGameTab" style='background-color:transparent'><a href="#game-community-tab" class="active waves-effect waves-light">Community</a></li>
		        <li class="tab col s3 userAnalyzeTab" style='background-color:transparent'><a href="#game-analyze-tab" id='analyze-tab-nav' class="waves-effect waves-light">Analyze</a></li>
		        <?php if(isset($_SESSION['logged-in']->_id) && $myxp->_tier != 0){ ?> 
		        	<li class="tab col s3 userGameTab preExistingGameTab" style='background-color:transparent;<?php if(!isset($_SESSION['logged-in']->_id) || $myxp->_tier == 0){ echo "display:none;"; } ?>'><a href="#game-myxp-tab" class="waves-effect waves-light">My XP</a></li>
		        <?php } ?>
		        <li class="tab col s3" style='background-color:transparent;<?php if($otherxp == -1){ echo "display:none;"; } ?>'>
		        	<a href="#game-userxp-tab" id='userxp-tab-nav' class="waves-effect waves-light">
		        		<?php if($otherxp->_username->_security == "Journalist" || $otherxp->_username->_security == "Authenticated"){
		        			echo $otherxp->_username->_first." ".$otherxp->_username->_last; 
		        		}else{
		        			echo $otherxp->_username->_username; 
		        		}?>
		        	</a>
	        	</li>
		      </ul>
			</div>
		</div>
	</div>
	<?php
}

function ShowGameHeader($game, $myxp, $otherxp){
	?>
	<div class="GameHeaderContainer">
		<div class="GameHeaderBackground" style="background: -moz-linear-gradient(top, rgba(0,0,0,0) 40%, rgba(0,0,0,0.4) 100%, rgba(0,0,0,0.4) 101%), url(<?php echo $game->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(40%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.4)), color-stop(101%,rgba(0,0,0,0.4))), url(<?php echo $game->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(0,0,0,0) 40%,rgba(0,0,0,0.4) 100%,rgba(0,0,0,0.4) 101%), url(<?php echo $game->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(0,0,0,0) 40%,rgba(0,0,0,0.4) 100%,rgba(0,0,0,0.4) 101%), url(<?php echo $game->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		<?php DisplayGameBackNav(); ?>
		<div class="GameMyStatusIcons">
			<i class="mdi-action-bookmark mybookmark" <?php if($myxp->_bucketlist != "Yes"){ echo "style='display:none;'"; } ?>></i>
			<div class="HideForDesktop ShowInfoBtn" style='padding: 0 0.5em;margin: 0 0 0 0.5em;z-index-101;' data-gameid='<?php echo $game->_gbid; ?>'><i class="mdi-action-info"></i></div>
		</div>
		<div class="GameTitle"><?php echo $game->_title; ?></div>
		<?php ShowGameTabs($myxp, $otherxp); ?>
		<div class="fixed-action-btn" id="game-fab">
			<?php ShowMyGameFAB($game->_id, $myxp); ?>
		</div>

	</div>
	<?php
}

function ShowMyGameFAB($gameid, $myxp){
	if($_SESSION['logged-in']->_id > 0){ ?>
	    <a class="btn-floating btn-large <?php if(sizeof($myxp->_playedxp) == 0 && $myxp->_game->_year > 0){ echo "game-add-played-btn red darken-2"; }else{ echo "game-add-watched-btn red darken-2"; } ?> "  data-gameid='<?php echo $myxp->_game->_id; ?>'>
	      <?php if(sizeof($myxp->_playedxp) == 0 && $myxp->_game->_year > 0){ ?>
	      	<span class="GameHiddenActionLabelBigFab">Add a played XP</span>
    	  <?php }else{ ?>
    	  	<span class="GameHiddenActionLabelBigFab">Add a watched XP</span>
    	  <?php } ?>
	      <i class="large mdi-content-add"></i>
	    </a>
	    <ul>
	      	<?php if($_SESSION['logged-in']->_security == "Admin"){ ?>
	      	<li><span class="GameHiddenActionLabel">Request update from GB</span><a class="btn-floating  red accent-4 game-update-info-btn" data-gameid='<?php echo $myxp->_game->_gbid; ?>'><i class="mdi-action-cached"></i></a></li>
	      	<li><span class="GameHiddenActionLabel">Upload hi-res jpg</span><a class="btn-floating light-green darken-3 game-add-image-btn" data-gameid='<?php echo $myxp->_game->_id; ?>' data-gameyear='<?php echo $myxp->_game->_year; ?>'><i class="mdi-file-cloud-upload"></i></a></li>
	      	<?php } ?>
	      	<li><span class="GameHiddenActionLabel">Add to Collection</span><a class="btn-floating orange darken-2 game-collection-btn" data-gameid='<?php echo $myxp->_game->_id; ?>'><i class="mdi-av-my-library-add"></i></a></li>
	    	<?php if(sizeof($myxp->_playedxp) > 0 || sizeof($myxp->_watchedxp) > 0){ ?>
	    	<li><span class="GameHiddenActionLabel">Pin XP to Profile</span><a class="btn-floating blue-grey darken-3 game-set-fav-btn" data-gameid='<?php echo $myxp->_game->_id; ?>'><i class="fa fa-thumb-tack"></i></a></li>
	    	<?php } ?>
    	  	<li><span class="GameHiddenActionLabel">Remove bookmark</span><a class="btn-floating grey darken-1 game-remove-bookmark-btn" <?php if($myxp->_bucketlist != "Yes"){ echo "style='display:none;'"; } ?> data-gameid='<?php echo $myxp->_game->_id; ?>'><i class="mdi-action-bookmark"></i></a></li>
	      	<li><span class="GameHiddenActionLabel">Bookmark this game</span><a class="btn-floating red darken-4 game-add-bookmark-btn" <?php if($myxp->_bucketlist == "Yes"){ echo "style='display:none;'"; } ?> data-gameid='<?php echo $myxp->_game->_id; ?>'><i class="mdi-action-bookmark"></i></a></li>
	      	
	      	<li><span class="GameHiddenActionLabel">Share game page</span><a class="btn-floating indigo darken-2 game-share-btn" data-gameid='<?php echo $myxp->_game->_id; ?>' data-game-name='<?php echo $myxp->_game->_title; ?>'><i class="mdi-social-share"></i></a></li>
	      	<?php if(sizeof($myxp->_playedxp) == 0 && $myxp->_game->_year > 0){ ?>
	      	<li><span class="GameHiddenActionLabelBigFab">Add a watched XP</span><a class="btn-floating game-add-watched-btn" style='width: 55.5px; height: 55.5px;'><i class="mdi-action-visibility" style='line-height: 55.5px;font-size: 1.6rem;'></i></a></li>
	      	<?php } ?>
	      	
	    </ul>
	<?php }else{ ?>
		<div class="fab-login waves-effect waves-light btn">Add your XP</div>
	<?php }
}

function DisplayGameInfo($game){	?>
<div id="sideContainer" class="col s3" style='padding: 0 1.75rem;'>
	<div class="HideForDesktop">
		<?php DisplayGameInfoBackNav(); ?>
	</div>
	<?php if($game->_id != '33548' && $game->_id != '33541' && $game->_id != '33542' && $game->_id != '33547' && $game->_id != '33543'
	&& $game->_id != '33546' && $game->_id != '33540' && $game->_id != '33549' && $game->_id != '33544' && $game->_id != '33545') { ?>
		<div class="row" style='padding-top:3em;'>
			<div class="col s12 GameInfoLabel">Released:</div>
			<div class="col s12 GameInfoContent">
				<?php 
				if($game->_year == 0){
					echo "Release date not announced";
				}else{
					echo ConvertDateToLongRelationalEnglish($game->_released); ?> <?php echo $game->_year; ?>
				<?php } ?>
			</div>
		</div>
		<div class="row">
	<?php }else{ ?>
		<div class="row" style='padding-top:3em;'>
	<?php } ?>

		<div class="col s12 GameInfoLabel">Platforms:</div>
		<div class="col s12 GameInfoContent">
			<?php $platforms = explode("\n", $game->_platforms);
			echo implode(", ", array_filter(array_map('trim',$platforms)));?>
		</div>
	</div>
	<div class="row">
		<div class="col s12 GameInfoLabel">Developed By:</div>
		<div class="col s12 GameInfoContent">
			<?php $developers = explode("\n", $game->_developer);
			echo implode(", ", array_filter(array_map('trim',$developers)));?>
		</div>
	</div>
	<div class="row">
		<div class="col s12 GameInfoLabel">Published By:</div>
		<div class="col s12 GameInfoContent">
			<?php $publishers = explode("\n", $game->_publisher);
			echo implode(", ", array_filter(array_map('trim',$publishers)));?>
		</div>
	</div>
	<div class="row">
		<div class="col s12 GameInfoLabel">Categorized As:</div>
		<div class="col s12 GameInfoContent">
			<?php $genres = explode("\n", $game->_genre);
			echo implode(", ", array_filter(array_map('trim',$genres)));?>
		</div>
	</div>
	<div class="row">
		<div class="col s12" style='text-align:left;'>
			<div class="btn-flat" style='padding:0;'><a href="<?php echo "http://www.giantbomb.com/game/3030-".$game->_gbid; ?>" style='font-weight:bold;' target="_blank">VIEW MORE INFO @ GIANT BOMB</a></div>
		</div>
	</div>
</div>
	
	<div id="infoModal" class="modal dynamicModal" style="background-color:white;">
		<div class="row">
			<div class="col s12 m4 GameInfoLabel">Released:</div>
			<div class="col s12 m8 GameInfoContent">
				<?php 
				if($game->_year == 0){
					echo "Release date not announced";
				}else{
					echo ConvertDateToLongRelationalEnglish($game->_released); ?> <?php echo $game->_year; ?>
				<?php } ?>
			</div>
		</div>
		<div class="row">
			<div class="col s12 m4 GameInfoLabel">Platforms:</div>
			<div class="col s12 m8 GameInfoContent">
				<?php $platforms = explode("\n", $game->_platforms);
				echo implode(", ", array_filter(array_map('trim',$platforms)));?>
			</div>
		</div>
		<div class="row">
			<div class="col s12 m4 GameInfoLabel">Developed By:</div>
			<div class="col s12 m8 GameInfoContent">
				<?php $developers = explode("\n", $game->_developer);
				echo implode(", ", array_filter(array_map('trim',$developers)));?>
			</div>
		</div>
		<div class="row">
			<div class="col s12 m4 GameInfoLabel">Published By:</div>
			<div class="col s12 m8 GameInfoContent">
				<?php $publishers = explode("\n", $game->_publisher);
				echo implode(", ", array_filter(array_map('trim',$publishers)));?>
			</div>
		</div>
		<div class="row">
			<div class="col s12 m4 GameInfoLabel">Categorized As:</div>
			<div class="col s12 m8 GameInfoContent">
				<?php $genres = explode("\n", $game->_genre);
				echo implode(", ", array_filter(array_map('trim',$genres)));?>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="btn-flat"><a href="<?php echo "http://www.giantbomb.com/game/3030-".$game->_gbid; ?>" style='font-weight:bold;' target="_blank">VIEW MORE INFO @ GIANT BOMB</a></div>
			</div>
		</div>
	</div>

	
<?php }

function DisplayGameBackNav(){ ?>
	<div class="backContainer" style='background:transparent;text-shadow: 1px 1px 5px rgba(0,0,0,0.3);/*position:absolute;top:0;*/'>
		<div class="backButton waves-effect waves-light"><i class="mdi-navigation-arrow-back small" style="color:white;vertical-align:middle;padding: 0 0.5em;"></i> <a class="btn-flat backButtonLabel" style="color:white;margin: 0;padding: 0;" >Back</a></div>
	</div>
<?php }

function DisplayGameInfoBackNav(){ ?>
	<div class="backContainerSideContent">
		<div class="backButton waves-effect waves-light"><i class="mdi-navigation-arrow-back small" style="color:#474747;vertical-align:middle;padding: 0 0.5em;"></i> <a class="btn-flat backButtonLabel" style="color:#474747;margin: 0;padding: 0 2em;" >Back</a></div>
	</div>
<?php }


function DisplayGameCard($game, $count, $classId){
	$xp = GetExperienceForUserComplete($_SESSION['logged-in']->_id, $game->_id) ?>
	<div class="col s6 m3 l2">
	      <div class="card game-discover-card <?php echo $classId; ?>" data-count="<?php echo $count; ?>" data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
	        <div class="card-image waves-effect waves-block" style="width:100%;background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
	        </div>
	        <div class="card-content">
	          <?php if(sizeof($xp->_playedxp) > 0 ||  sizeof($xp->_watchedxp) > 0){ 
	          			DisplayGameCardTierIcon($xp);
	           	}else if($_SESSION['logged-in']->_id > 0){ ?>
		          <div class="card-game-tier-container z-depth-1 card-game-add-btn" style='background-color:gray;'>
		          	<div class="card-game-tier">
	          			<i class="mdi-content-add"></i>
		          	</div>
  	  	            <div class="card-tier-details">
	  		          <span class="card-tier-title" style='font-weight:500;'><?php echo $game->_title; ?><i class="mdi-content-clear right" style='cursor:pointer;position: absolute;right: 0.3em;top: 0.6em;font-size:1.5em;'></i></span>
					  <?php if($game->_year > 0){ ?>
					  <div class='btn-large played-option card-tier-add-options red' style='margin-top:20px;'><i class="mdi-av-games" style='vertical-align: middle;'></i> Played</div>
					  <div class='card-tier-or-label'>or</div>
  					  <div class='btn-large watched-option card-tier-add-options blue'><i class="mdi-action-visibility" style='vertical-align: middle;'></i> Watched</div>
					  <?php }else{ ?>
  					  <div class='btn-large watched-option card-tier-add-options blue' style='margin-top:20px;'><i class="mdi-action-visibility" style='vertical-align: middle;'></i> Watched</div>
					  <?php } ?>
					</div>
	          	   </div>
          	  <?php }else{ ?>
		          <div class="card-game-tier-container z-depth-1 card-game-add-btn" style='background-color:gray;'>
		          	<div class="card-game-tier">
	          			<i class="mdi-content-add"></i>
		          	</div>
  	  	            <div class="card-tier-details">
	  		          <span class="card-tier-title" style='font-weight:500;'><?php echo $game->_title; ?><i class="mdi-content-clear right" style='cursor:pointer;position: absolute;right: 0.3em;top: 0.6em;font-size:1.5em;'></i></span>
					  <div class='btn-large signup-option card-tier-add-options red' style='margin-top:20px;'><i class="mdi-av-games" style='vertical-align: middle;'></i> Played</div>
					  <div class='card-tier-or-label'>or</div>
  					  <div class='btn-large signup-option card-tier-add-options blue'><i class="mdi-action-visibility" style='vertical-align: middle;'></i> Watched</div>
					</div>
	          	   </div>
          	  <?php } ?>
	          <span class="card-title activator grey-text text-darken-4"><span class="nav-game-title" style="white-space: normal;"><?php echo $game->_title; ?></span> <!--<i class="mdi-navigation-more-vert right" style='position: absolute;font-size: 1.5em;right: 0.25em;top: 15px;z-index:2; text-shadow: 1px 1px 5px black;color:white;'></i>--></span>
	        </div>
	        <div class="card-reveal tier<?php echo $tier; ?>BG" style="width:100%;color:white;">
	        </div>
	      </div>
      </div>
<?php }

function DisplayGameCardwithXP($game, $count, $classId, $xp){ ?>
	<div class="col s6 m4 l3">
	      <div class="card game-discover-card <?php echo $classId; ?>" data-count="<?php echo $count; ?>" data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
	        <div class="card-image waves-effect waves-block" style="width:100%;background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
	        </div>
	        <div class="card-content">
	          <?php if(sizeof($xp->_playedxp) > 0 ||  sizeof($xp->_watchedxp) > 0){ ?>
	          <div class="card-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1">
	          	<div class="card-game-tier">
	          		<?php if($xp->_link != ''){ ?>
       					<i class="mdi-editor-format-quote"></i>
       				<?php }else if(sizeof($xp->_playedxp) > 0){ ?>
  	          			<i class="mdi-av-games"></i>
	          		<?php }else if(sizeof($xp->_watchedxp) > 0){ ?>
	          			<i class="mdi-action-visibility"></i>
	          		<?php } ?>
	          	</div>
  	            <div class="card-tier-details">
  		          <span class="card-tier-title" style='font-weight:500;'><?php echo $game->_title; ?><i class="mdi-content-clear right" style='cursor:pointer;position: absolute;right: 0.3em;top: 0.6em;font-size:1.5em;'></i></span>
		          <p>
		          	"<?php echo $xp->_quote; ?>"
		          </p>
				</div>
          	  </div>
          	  <?php }else if($_SESSION['logged-in']->_id > 0){ ?>
		          <div class="card-game-tier-container z-depth-1 card-game-add-btn" style='background-color:gray;'>
		          	<div class="card-game-tier">
	          			<i class="mdi-content-add"></i>
		          	</div>
  	  	            <div class="card-tier-details">
	  		          <span class="card-tier-title" style='font-weight:500;'><?php echo $game->_title; ?><i class="mdi-content-clear right" style='cursor:pointer;position: absolute;right: 0.3em;top: 0.6em;font-size:1.5em;'></i></span>
					  <?php if($game->_year > 0){ ?>
					  <div class='btn-large played-option card-tier-add-options red' style='margin-top:20px;'><i class="mdi-av-games" style='vertical-align: middle;'></i> Played</div>
					  <div class='card-tier-or-label'>or</div>
  					  <div class='btn-large watched-option card-tier-add-options blue'><i class="mdi-action-visibility" style='vertical-align: middle;'></i> Watched</div>
					  <?php }else{ ?>
  					  <div class='btn-large watched-option card-tier-add-options blue' style='margin-top:20px;'><i class="mdi-action-visibility" style='vertical-align: middle;'></i> Watched</div>
					  <?php } ?>
					</div>
	          	   </div>
          	  <?php }else{ ?>
		          <div class="card-game-tier-container z-depth-1 card-game-add-btn" style='background-color:gray;'>
		          	<div class="card-game-tier">
	          			<i class="mdi-content-add"></i>
		          	</div>
  	  	            <div class="card-tier-details">
	  		          <span class="card-tier-title" style='font-weight:500;'><?php echo $game->_title; ?><i class="mdi-content-clear right" style='cursor:pointer;position: absolute;right: 0.3em;top: 0.6em;font-size:1.5em;'></i></span>
					  <div class='btn-large signup-option card-tier-add-options red' style='margin-top:20px;'><i class="mdi-av-games" style='vertical-align: middle;'></i> Played</div>
					  <div class='card-tier-or-label'>or</div>
  					  <div class='btn-large signup-option card-tier-add-options blue'><i class="mdi-action-visibility" style='vertical-align: middle;'></i> Watched</div>
					</div>
	          	   </div>
          	  <?php } ?>
	          <span class="card-title activator grey-text text-darken-4"><span class="nav-game-title" style="white-space: normal;"><?php echo $game->_title; ?></span> <!--<i class="mdi-navigation-more-vert right" style='position: absolute;font-size: 1.5em;right: 0.25em;top: 15px;z-index:2; text-shadow: 1px 1px 5px black;color:white;'></i>--></span>
	        </div>
	        <div class="card-reveal tier<?php echo $tier; ?>BG" style="width:100%;color:white;">
	        </div>
	      </div>
      </div>
<?php }

function DisplaySmallGameCard($xp){
	$game = $xp->_game; ?>
	<div class="col">
	      <div class="card card-game-small" data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
	        <div class="card-image-small" style="width:100%;background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
	        	<div class="card-game-small-title tier<?php echo $xp->_tier; ?>BG"><?php echo $game->_title; ?></div>
	        </div>
	      </div>
      </div>
<?php }

function DisplayGameInList($libraryxp){ ?>
	<div class="col s12 game-list-item" data-tier='<?php echo $libraryxp->_tier; ?>' data-year='<?php echo $libraryxp->_year; ?>' data-title="<?php echo $libraryxp->_title; ?>" >
	      <div class="card card-game-list" data-gameid="<?php echo $libraryxp->_gameid; ?>" data-gbid="<?php echo $libraryxp->_gbid; ?>" style='background-color:white;'>
	        <div class="card-image-list" style="width:100%;background:url(<?php echo $libraryxp->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
	        <div class="card-game-tier-vert tier<?php echo $libraryxp->_tier; ?>BG"></div>
	        <div class="card-game-list-title">
	        	<?php echo $libraryxp->_title; ?> 
	        	<div class="card-game-list-details"><?php if($libraryxp->_year > 0){ echo $libraryxp->_year; } ?></div>
        	</div>
	      </div>
      </div>
<?php }

function DisplayGameCardTierIcon($xp){ 
	if($xp->_link != '' && $xp->_authenticxp == "No"){ ?>
	          <div class="card-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1">
	          	<div class="card-game-tier" title='Tier <?php echo $xp->_tier; ?> - Curated Review'>
   					<i class="mdi-editor-format-quote"></i>
	          	</div>
  	            <div class="card-tier-details">
  		          <span class="card-tier-title" style='font-weight:500;'><?php echo $game->_title; ?><i class="mdi-content-clear right" style='cursor:pointer;position: absolute;right: 0.3em;top: 0.6em;font-size:1.5em;'></i></span>
		          <p>
		          	"<?php echo $xp->_quote; ?>"
		          </p>
				</div>
          	  </div>
  	  <?php }else if(sizeof($xp->_playedxp) > 0){ 
		  	  	if($xp->_playedxp[0]->_completed == "101")
					$percent = 100;
				else
					$percent = $xp->_playedxp[0]->_completed;
					
				if($percent == 100){ ?>
  	  	       		<div class="card-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1">
			          	<div class="card-game-tier" title="<?php echo "Tier ".$xp->_tier." - Completed"; ?>">
		    				<i class="mdi-hardware-gamepad"></i>
			          	</div>
	          	<?php }else{ ?>
	          		<div class="card-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1">
	      			  	<div class="c100 mini <?php if($xp->_tier == 1){ echo "tierone"; }else if($xp->_tier == 2){ echo "tiertwo"; }else if($xp->_tier == 3){ echo "tierthree"; }else if($xp->_tier == 4){ echo "tierfour"; }else if($xp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$xp->_tier." - ".$percent."% finished"; ?>" style='background-color:white;'>
					  	  <span class='tierTextColor<?php echo $xp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-hardware-gamepad"></i></span>
						  <div class="slice">
						    <div class="bar minibar"></div>
						    <div class="fill"></div>
						  </div>
						</div>
	          	<?php } ?>
	  	            <div class="card-tier-details">
	  		          <span class="card-tier-title" style='font-weight:500;'><?php echo $game->_title; ?><i class="mdi-content-clear right" style='cursor:pointer;position: absolute;right: 0.3em;top: 0.6em;font-size:1.5em;'></i></span>
			          <p>
			          	"<?php echo $xp->_quote; ?>"
			          </p>
					</div>
				</div>
  	  <?php }else if(sizeof($xp->_watchedxp) > 0){ 
  	  		$percent = 20;
  	  		$length = "";
    		foreach($xp->_watchedxp as $watched){
    			if($watched->_length == "Watched a speed run" || $watched->_length == "Watched a complete single player playthrough" || $watched->_length == "Watched a complete playthrough"){
    				$percent = 101;
    				$length = $watched->_length;
    			}else if($percent < 100 && ($watched->_length == "Watched multiple hours" || $watched->_length == "Watched gameplay" || $watched->_length == "Watched an hour or less")){
    				$percent = 100;
    				$length = $watched->_length;
    			}else if($percent < 50 && ($watched->_length == "Watched promotional gameplay" || $watched->_length == "Watched a developer diary")){
    				$percent = 50;
    				$length = $watched->_length;
    			}else{
    				$length = $watched->_length;
    			}
    		}
    		
    		if($percent == 101){
    		?>
	          <div class="card-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1">
	          	<div class="card-game-tier" title="<?php echo "Tier ".$xp->_tier." - ".$length; ?>">
	          			<i class="mdi-action-visibility"></i>
	          	</div>
  	            <div class="card-tier-details">
  		          <span class="card-tier-title" style='font-weight:500;'><?php echo $game->_title; ?><i class="mdi-content-clear right" style='cursor:pointer;position: absolute;right: 0.3em;top: 0.6em;font-size:1.5em;'></i></span>
		          <p>
		          	"<?php echo $xp->_quote; ?>"
		          </p>
				</div>
			   </div>
  	  <?php }else{ ?>
      		<div class="card-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1">
  			  	<div class="c100 mini <?php if($xp->_tier == 1){ echo "tierone"; }else if($xp->_tier == 2){ echo "tiertwo"; }else if($xp->_tier == 3){ echo "tierthree"; }else if($xp->_tier == 4){ echo "tierfour"; }else if($xp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$xp->_tier." - ".$length; ?>" style='background-color:white;'>
			  	  <span class='tierTextColor<?php echo $xp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-action-visibility"></i></span>
				  <div class="slice">
				    <div class="bar minibar"></div>
				    <div class="fill"></div>
				  </div>
				</div>
  	            <div class="card-tier-details">
  		          <span class="card-tier-title" style='font-weight:500;'><?php echo $game->_title; ?><i class="mdi-content-clear right" style='cursor:pointer;position: absolute;right: 0.3em;top: 0.6em;font-size:1.5em;'></i></span>
		          <p>
		          	"<?php echo $xp->_quote; ?>"
		          </p>
				</div>
   			</div>
  	  <?php
  	  		}
  	  }
}

function DisplayGameCardwithAgrees($users, $xp, $conn, $mutualconn, $showpreview){
	$game = $xp->_game;?>
	<div class="col s6 m4 l3">
	      <div class="card game-agree-card" data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
	        <div class="card-image waves-effect waves-block" style="background: -moz-linear-gradient(top, rgba(0,0,0,0.75) 40%, rgba(0,0,0,0.75) 100%, rgba(0,0,0,0.75) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,rgba(0,0,0,0.75)), color-stop(100%,rgba(0,0,0,0.75)), color-stop(101%,rgba(0,0,0,0.75))), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(0,0,0,0.75) 50%,rgba(0,0,0,0.75) 100%,rgba(0,0,0,0.75) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(0,0,0,0.75) 50%,rgba(0,0,0,0.75) 100%,rgba(0,0,0,0.75) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
	        	<div class="card-agree-quote"><?php echo $xp->_quote; ?></div>
	        </div>
	        <div class="card-content">
	          <span class="card-title activator grey-text text-darken-4"><span class="nav-game-title" style="white-space: normal;"><?php echo $game->_title; ?></span></span>
	          <div class="card-agree-users">
	          	<?php foreach($users as $userid){
	          		$user = GetUser($userid); ?>
  					<div class="badge-card-ability-avatar" data-id="<?php echo $user->_id; ?>" title="<?php if($user->_security == "Journalist"){ echo $user->_first." ".$user->_last; }else{ echo $user->_username; } ?>" style="border-radius:50%;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
						<?php if($showpreview){ DisplayUserPreviewCard($user, $conn, $mutualconn); } ?>
					</div>
	          	<?php } ?>
	          </div>
	        </div>
	      </div>
      </div>
<?php }

function ShowUserXP($userxp){ 
	$user = $userxp->_username;
	$conn = GetConnectedToList($_SESSION['logged-in']->_id);
	$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
	$agrees = GetAgreesForXP($userxp->_id);
	$agreedcount = array_shift($agrees);
	?>
	<div class="row">
		<div class="col s12">
			<div class="myxp-details-container z-depth-1">
				<div class="row" style='margin: 0;'>
					<div class="col s12 userxp-details-lifebar">
						<?php DisplayUserLifeBarRound($user, $conn, $mutualconn, true); ?>
					</div>
				    <div class="row" style='margin: 0;'>
					  <div class="col s3 m2">
					  	<div class="myxp-details-tier tierTextColor<?php echo $userxp->_tier; ?>">TIER<div class="myxp-details-tier-number"><?php echo $userxp->_tier; ?></div></div>
					  </div>
			  	      <div class="col s9 m10">
				        <div class="myxp-details-quote-container"><i class="mdi-editor-format-quote prefix quoteflip" style='font-size:2em;'></i><span class="myxp-details-quote"><?php echo $userxp->_quote; ?></span></div>
				      </div>
				    </div>
		    	    <div class="row" style='margin: 0;'>
		    	    	<div class="myxp-profile-tier-quote btn-flat waves-effect" data-userid='<?php echo  $userxp->_userid; ?>'><i class="mdi-social-person left" style="vertical-align: sub;"></i> View Profile</div>
				    	<div class="myxp-share-tier-quote btn-flat waves-effect" data-userid='<?php echo  $userxp->_userid; ?>'><i class="mdi-social-share left" style="vertical-align: sub;"></i> Share</div>
				    </div>
				</div>
			</div>
			<br>
			<?php BuildExperienceSpectrum($user, $userxp, $userxp->_game); ?>
			<br>
		    <div class="myxp-details-container z-depth-1" style="padding:0;">
			    <?php 
			    if(sizeof($userxp->_playedxp) > 0){
		    		$played = $userxp->_playedxp[0];
			    	?>
			    	<div class="row" style='border-bottom: 1px solid #ddd;padding: 2em 0;'>
			    		<div class="col s0 m2"><i class='mdi-hardware-gamepad' style='font-size:2em;color:white;'></i></div>
			    		<div class="col s12 m10 myxp-details-items">
			    			<?php BuildPlayedVisualSentence($played, $userxp->_userid, $userxp->_tier, '', ''); ?>
			    		</div>
			    	</div>
		    	<?php
			    }
			    
			    foreach($userxp->_watchedxp as $watched){ ?>
			    	<div class="row" style='border-bottom: 1px solid #ddd;padding: 2em 0;'>
			    		<div class="col s0 m2"><i class='mdi-action-visibility' style='font-size:2em;color:white;'></i></div>
			    		<div class="col s12 m10 myxp-details-items">
			    			<?php echo BuildWatchedVisualSentence($watched, $userxp->_userid, $userxp->_tier, '' ,'');	?>
			    		</div>
			    	</div>
		    	<?php
			    }
			    ?>
		    </div>
			<br>
		    <?php if($agreedcount > 0){ ?>
			    <div class="myxp-details-container z-depth-1">
			    	<div class="row" style='margin: 0;'>
					  <div class="col s3 m2">
					  	<div class="myxp-details-agree-count"><?php echo $agreedcount; ?>up</div>
					  </div>
				    	<div class="col s9 m10">
					    	<div class="myxp-details-agree-list">
					    		<?php
					    			$i = 0;
					    			while($i < sizeof($agrees) && $i < 25){ ?>
					    			<div class="myxp-details-agree-listitem">
					    				<?php $userAgree = GetUser($agrees[$i]); ?>
					    				<div class="user-avatar" style="margin-top:0;width:45px;border-radius:50%;display: inline-block;float:left;margin-left: 0.5em;height:45px;background:url(<?php echo $userAgree->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
					    				<?php DisplayUserPreviewCard($userAgree, $conn, $mutualconn); ?>
					    			</div>
					    		<?php	
					    		$i++;
					    		} ?>
					    	</div>
				    	</div>
				    </div>
			    </div>
			    <br>
    		<?php } ?>
		</div>
	</div>
<?php
}
?>
