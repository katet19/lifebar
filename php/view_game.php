<?php 
function DisplayGame($gbid){
	$game = GetGameByGBIDFull($gbid);
	$critics = GetCriticXPForGame($game->_id, $_SESSION['logged-in']->_id);
	$othercritics = GetOutsideCriticXPForGame($game->_id, $_SESSION['logged-in']->_id);
	$myusers = GetMyUsersXPForGame($game->_id, $_SESSION['logged-in']->_id);
	$otherusers = GetOutsideUsersXPForGame($game->_id, $_SESSION['logged-in']->_id);
	$myxp = GetExperienceForUserByGame($_SESSION['logged-in']->_id, $game->_id);
	ShowGameHeader($game, $critics, $othercritics, $myusers, $otherusers, $myxp);
	ShowGameContent($game, $critics, $othercritics, $myusers, $otherusers, $myxp);
}

function DisplayGameViaID($gameid){
	$game = GetGame($gameid);
	$critics = GetCriticXPForGame($game->_id, $_SESSION['logged-in']->_id);
	$othercritics = GetOutsideCriticXPForGame($game->_id, $_SESSION['logged-in']->_id);
	$myusers = GetMyUsersXPForGame($game->_id, $_SESSION['logged-in']->_id);
	$otherusers = GetOutsideUsersXPForGame($game->_id, $_SESSION['logged-in']->_id);
	$myxp = GetExperienceForUserByGame($_SESSION['logged-in']->_id, $game->_id);
	ShowGameHeader($game, $critics, $othercritics, $myusers, $otherusers, $myxp);
	ShowGameContent($game, $critics, $othercritics, $myusers, $otherusers, $myxp);
}

function ShowGameContent($game, $critics, $othercritics, $users, $otherusers, $myxp){ 
?>
	<div id="gameContentContainer" data-gbid="<?php echo $game->_gbid; ?>" data-title="<?php echo urlencode($game->_title); ?>" data-id="<?php echo $game->_id; ?>" class="row">
		<div id="game-critic-tab" class="col s12 game-tab"><?php ShowCritics($critics, $othercritics, $game, $myxp); ?></div>
		<div id="game-user-tab" class="col s12 game-tab"><?php ShowUsers($users, $otherusers); ?></div>
		<?php if(isset($_SESSION['logged-in']->_id)){ ?>
			<div id="game-myxp-tab" class="col s12 game-tab"><?php if($myxp->_tier != 0){ ShowMyXP($myxp); } ?></div>
		<?php } ?>
	</div>
	<?php DisplayGameInfo($game); ?>
<?php }

function ShowCritics($critics, $othercritics, $game, $myxp){
	if($_SESSION['logged-in']->_id > 0){
		$count = 1;
		$allcritics = array();
		foreach($critics as $critic){
			$allcritics[$critic->_id] = GetTotalAgreesForXP($critic->_id);
		}
		
		arsort($allcritics);
		$allcritics = array_keys($allcritics);
		?>
		<div class="row game-tab-subheader-container" >
			<div class="col s12">
				<div class="game-tab-subheader">Following</div>
			</div>
		</div>
		<?php
		if(sizeof($allcritics) > 0){
			while($count <= sizeof($allcritics)){
				DisplayCriticQuoteCard($critics[$allcritics[$count-1]]);
				$count++;
			}
		}else{
			?>
			<div class="row info-container" >
				<div class="col s12">
					<?php if($myxp->_bucketlist != "Yes" && sizeof($othercritics) == 0){ ?>
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
					<?php }else{ ?>
						<div class="info-label">None of the Critics you follow have XP for this game yet</div>
					<?php } ?>
				</div>
			</div>
			<?php
		}
		
			?>
			<div class="row game-tab-subheader-container" >
				<div class="col s12">
					<div class="game-tab-subheader" style='margin-top: <?php if(sizeof($allcritics) > 0){ echo "4em"; }else{ echo "0em"; } ?>'>Other Critics</div>
				</div>
			</div>
			<?php
			
			if(sizeof($othercritics) > 0){
				foreach($othercritics as $usr){
						DisplayCriticQuoteCard($usr);
				}
			}else{
				?>
				<div class="row info-container" >
					<div class="col s12">
						<?php if(sizeof($allcritics) > 0){ ?>
							<div class="info-label">No one outside of the Critics you follow have XP for this game yet</div>
						<?php }else{ ?>
							<div class="info-label">Critics haven't published reviews for this game yet</div>
						<?php } ?>
					</div>
				</div>
				<?php
			}
		}else{
			if(sizeof($othercritics) > 0){
				foreach($othercritics as $usr){
						DisplayCriticQuoteCard($usr);
				}
			}else{
				?>
				<div class="row info-container" >
					<div class="col s12">
						<div class="info-label">Critics haven't published reviews for this game yet</div>
					</div>
				</div>
				<?php	
			}
		}
		
}

function ShowUsers($users, $otherusers){
	if($_SESSION['logged-in']->_id > 0){
		$count = 1;
		$allusers = array();
		foreach($users as $user){
			$allusers[$user->_id] = GetTotalAgreesForXP($user->_id);
		}
		
		arsort($allusers);
		$allusers = array_keys($allusers);
		?>
		<div class="row game-tab-subheader-container" >
			<div class="col s12">
				<div class="game-tab-subheader">Following</div>
			</div>
		</div>
		<?php
		if(sizeof($allusers) > 0){
			while($count <= sizeof($allusers)){
				DisplayUserQuoteCard($users[$allusers[$count-1]]);
				$count++;
			}
		}else{
			?>
			<div class="row info-container" >
				<div class="col s12">
					<div class="info-label">None of the users you follow have XP for this game yet</div>
				</div>
			</div>
			<?php
		}
		
			?>
			<div class="row game-tab-subheader-container" >
				<div class="col s12">
					<div class="game-tab-subheader" style='margin-top: <?php if(sizeof($allusers) > 0){ echo "4em"; }else{ echo "0em"; } ?>'>Other Users</div>
				</div>
			</div>
			<?php
			
			if(sizeof($otherusers) > 0){
				foreach($otherusers as $usr){
						DisplayUserQuoteCard($usr);
				}
			}else{
				?>
				<div class="row info-container" >
					<div class="col s12">
						<div class="info-label">No one outside of the people you follow have XP for this game yet</div>
					</div>
				</div>
				<?php
			}
		}else{
			if(sizeof($otherusers) > 0){
				foreach($otherusers as $usr){
						DisplayUserQuoteCard($usr);
				}
			}else{
				?>
				<div class="row info-container" >
					<div class="col s12">
						<div class="info-label">No one has entered XP for this game yet</div>
					</div>
				</div>
				<?php	
			}
		}
}

function ShowGameTabs($critics, $othercritics, $users, $otherusers, $myxp){
	$totalusers = sizeof($users) + sizeof($otherusers);
	$totalcritics = sizeof($critics) + sizeof($othercritics);
	if(sizeof($otherusers) > 50)
		$totalusers = $totalusers."+";
	?>
	<div id="game-navigation-header">
		<div class="row" style='margin:0;'>
		    <div class="col s12 m8" style="padding:0;">
		      <ul class="tabs gameNav" style="background-color:transparent">
		      	<li class="tab col s3 criticGameTab" style='background-color:transparent'><a href="#game-critic-tab" class="active waves-effect waves-light">Critics <?php if($totalcritics > 0){ echo "<div class='HideForMobile'>(".$totalcritics.")</div>"; } ?></a></li>
		        <li class="tab col s3" style='background-color:transparent'><a href="#game-user-tab" class="waves-effect waves-light">Users <?php if(sizeof($totalusers) > 0){ echo "<div class='HideForMobile'>(".$totalusers.")</div>"; } ?></a></li>
		        <li class="tab col s3 userGameTab" style='background-color:transparent;<?php if(!isset($_SESSION['logged-in']->_id) || $myxp->_tier == 0){ echo "display:none;"; } ?>'><a href="#game-myxp-tab" class="waves-effect waves-light">My XP</a></li>
		      </ul>
			</div>
		</div>
	</div>
	<?php
}

function ShowGameHeader($game, $critics, $othercritics, $users, $otherusers, $myxp){
	?>
	<div class="GameHeaderContainer">
		<div class="GameHeaderBackground" style="background: -moz-linear-gradient(top, rgba(0,0,0,0) 40%, rgba(0,0,0,0.4) 100%, rgba(0,0,0,0.4) 101%), url(<?php echo $game->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(40%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.4)), color-stop(101%,rgba(0,0,0,0.4))), url(<?php echo $game->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(0,0,0,0) 40%,rgba(0,0,0,0.4) 100%,rgba(0,0,0,0.4) 101%), url(<?php echo $game->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(0,0,0,0) 40%,rgba(0,0,0,0.4) 100%,rgba(0,0,0,0.4) 101%), url(<?php echo $game->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		<?php DisplayGameBackNav(); ?>
		<div class="GameMyStatusIcons">
			<i class="mdi-action-bookmark mybookmark" <?php if($myxp->_bucketlist != "Yes"){ echo "style='display:none;'"; } ?>></i>
			<i class="mdi-av-my-library-books myowned" <?php if($myxp->_owned != "Yes"){ echo "style='display:none;'"; } ?>></i>
			<div class="HideForDesktop ShowInfoBtn" style='padding: 0 0.5em;margin: 0 0 0 0.5em;z-index-101;' data-gameid='<?php echo $game->_gbid; ?>'><i class="mdi-action-info"></i></div>
		</div>
		<div class="GameTitle"><?php echo $game->_title; ?></div>
		<?php ShowGameTabs($critics, $othercritics, $users, $otherusers, $myxp); ?>
		<div class="fixed-action-btn" id="game-fab">
			<?php ShowMyGameFAB($game->_id); ?>
		</div>

	</div>
	<?php
}

function ShowMyGameFAB($gameid){
	$myxp = GetExperienceForUserByGame($_SESSION['logged-in']->_id, $gameid);
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
	    	<?php if(sizeof($myxp->_playedxp) > 0 || sizeof($myxp->_watchedxp) > 0){ ?>
	    	<li><span class="GameHiddenActionLabel">Equip XP to Profile</span><a class="btn-floating indigo darken-3 game-set-fav-btn" data-gameid='<?php echo $myxp->_game->_id; ?>'><i class="mdi-action-extension"></i></a></li>
	    	<?php } ?>
    	  	<li><span class="GameHiddenActionLabel">Remove bookmark</span><a class="btn-floating grey darken-1 game-remove-bookmark-btn" <?php if($myxp->_bucketlist != "Yes"){ echo "style='display:none;'"; } ?> data-gameid='<?php echo $myxp->_game->_id; ?>'><i class="mdi-action-bookmark"></i></a></li>
	      	<li><span class="GameHiddenActionLabel">Bookmark this game</span><a class="btn-floating red darken-4 game-add-bookmark-btn" <?php if($myxp->_bucketlist == "Yes"){ echo "style='display:none;'"; } ?> data-gameid='<?php echo $myxp->_game->_id; ?>'><i class="mdi-action-bookmark"></i></a></li>
	      	
	      	<li><span class="GameHiddenActionLabel">Don't save for later</span><a class="btn-floating grey darken-1 game-remove-owned-btn" <?php if($myxp->_owned != "Yes"){ echo "style='display:none;'"; } ?> data-gameid='<?php echo $myxp->_game->_id; ?>'><i class="mdi-av-my-library-add"></i></a></li>
	      	<li><span class="GameHiddenActionLabel">Save for later</span><a class="btn-floating orange darken-2 game-add-owned-btn" <?php if($myxp->_owned == "Yes"){ echo "style='display:none;'"; } ?> data-gameid='<?php echo $myxp->_game->_id; ?>'><i class="mdi-av-my-library-add"></i></a></li>
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
	<!--<div class="row">
		<div class="col s12 GameInfoHeader"><i class="mdi-action-info" style='font-size: 1.5em;vertical-align: sub;'></i> INFORMATION</div>
	</div>-->
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
<?php } ?>
