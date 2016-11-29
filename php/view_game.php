<?php 
function DisplayGame($gbid){
	$game = GetGameByGBIDFull($gbid);
	$myxp = GetExperienceForUserByGame($_SESSION['logged-in']->_id, $game->_id);
	$videoxp = GetGameVideoXP($game->_id);
	$myxp->_bucketlist = IsGameBookmarkedFromCollection($game->_id);
	ShowGameNav($gbid);
	ShowGameHeader($game, $myxp, -1, $videoxp);
	ShowGameContent($game, $myxp, -1, $videoxp);
}

function DisplayGameViaID($gameid, $userid){
	$game = GetGame($gameid);
	$myxp = GetExperienceForUserByGame($_SESSION['logged-in']->_id, $game->_id);
	$myxp->_bucketlist = IsGameBookmarkedFromCollection($gameid);
	$videoxp = GetGameVideoXP($game->_id);
	if($userid > 0){
		$otherxp = GetExperienceForUserByGame($userid, $game->_id);
		ShowGameNav($game->_gbid);
		ShowGameHeader($game, $myxp, $otherxp, $videoxp);
		ShowGameContent($game, $myxp, $otherxp, $videoxp);
	}else{
		ShowGameNav($game->_gbid);
		ShowGameHeader($game, $myxp, -1, $videoxp);
		ShowGameContent($game, $myxp, -1, $videoxp);
	}
}

function ShowGameNav($gbid){
	$id = $_SESSION['logged-in']->_id;
	?>
	<ul id="game-slide-out">
		<li data-tab="game-back-tab" class="game-back-tab HideForDesktop" style='padding-top:15px;'><i class='game-nav-icons mdi-navigation-arrow-back left'></i> <span>Go Back</span></li>
		<li data-tab="game-back-tab" class='game-back-tab HideForDesktop' style='padding:0;margin-bottom:15px;border-bottom:1px solid gray;background:transparent !important;'></div>
		<li data-tab="game-dashboard-tab" data-nav="Dashboard" class="game-dashboard-tab game-tab-first"><i class='game-nav-icons mdi-action-dashboard left'></i> <span>Dashboard</span></li>
		<li data-tab="game-myxp-tab" data-nav="MyXP" class='game-myxp-tab'><i class='game-nav-icons mdi-action-account-circle left'></i> <span>My XP</span></li>
		<!--<li data-tab="game-longform-tab" class="game-longform-tab" style='display:none;padding-left: 35px;'><i class='game-nav-icons mdi-editor-mode-edit left'></i> <span>Journal</span></li>-->
		<li data-tab="game-community-tab" data-nav="Community" class="game-community-tab"><i class="game-nav-icons mdi-social-people left"></i> <span>Community</span></li>
		<?php if($id > 0){ ?>
			<li data-tab="game-community-others-tab" data-nav="DiscoverCommunity" class="game-community-others-tab" style='display:none;padding-left: 35px;'><i class="game-nav-icons mdi-social-public left"></i> <span>Discover More</span></li>
		<?php } ?>
		<li data-tab="game-analyze-tab" data-nav="Report" class="game-analyze-tab"><i class="game-nav-icons mdi-action-assessment left"></i> <span>Report</span></li>
		<li data-tab="game-video-tab" data-nav="Watch" class="game-video-tab"><i class="game-nav-icons mdi-action-visibility left"></i> <span>Watch</span></li>
		<li data-tab="game-reflectionpoints-tab" data-nav="ReflectionPoints" class="game-reflectionpoints-tab"><i class="game-nav-icons mdi-action-question-answer left"></i> <span>Reflection Points</span></li>
		<li data-tab="game-collections-tab" data-nav="Collections" class="game-collections-tab"><i class="game-nav-icons mdi-av-my-library-add left"></i> <span>Collections</span></li>
		<li data-tab="game-similargames-tab" data-nav="SimilarGames" class="game-similargames-tab"><i class="game-nav-icons mdi-action-list left"></i> <span>Similar Games</span></li>
		<li data-tab="game-userxp-tab" class='game-user-tab' style='display:none;margin-top:15px;border-bottom:1px solid gray;background:transparent !important;'></div>
		<li data-tab="game-userxp-tab" class='game-user-tab' style='display:none;margin-top:15px;'><i class="game-nav-icons mdi-social-person left"></i> <span>USER NAME</span></li>
	</ul>
	<?php
}

function ShowGameContent($game, $myxp, $otherxp, $videoxp){ 
	$id = $_SESSION['logged-in']->_id;
	if($id != ""){
		$verified = GetVerifiedXPForGame($game->_id, $id);
		$curated = GetCuratedXPForGame($game->_id, $id);
		$myusers = GetMyUsersXPForGame($game->_id, $id);
		$allusers = $verified + $curated + $myusers;
	}else{
		$id = -1;
	}
	$otherverified = GetOutsideVerifiedXPForGame($game->_id, $id);
	$othercurated = GetOutsideCuratedXPForGame($game->_id, $id);
	$otherusers = GetOutsideUsersXPForGame($game->_id, $id);
	if($id > 0)
		$allusers = $allusers + $otherverified + $othercurated + $otherusers;
	else
		$allusers = $otherverified + $othercurated + $otherusers;

	$refpts = GetReflectionPointsForGame($game->_id);
	$collections = GetCollectionsForGame($game->_id);
	$similarlist = explode(',', $game->_similar);
	foreach($similarlist as $sim){
		if($sim > 0){
			$similar[] = GetGameByGBIDFull($sim);
		}
	} ?>
	<div id="gameContentContainer" data-gbid="<?php echo $game->_gbid; ?>" data-title="<?php echo urlencode($game->_title); ?>" data-id="<?php echo $game->_id; ?>" class="row">
		<div id="game-dashboard-tab" class="col s12 game-tab game-tab-active">
			<?php ShowGameDashboard($game, $myxp, $videoxp, $refpts, $collections, $similar, $allusers); ?>
			<div class="col s12 m12 l10" id='dashboard-game-width-box'></div>
		</div>
		<?php if($id > 0){ ?>
			<div id="game-community-tab" class="col s12 game-tab">
				<?php ShowCommunityFollowing($game, $_SESSION['logged-in']->_id, $myxp, $verified, $curated, $myusers); ?>
				<div class="col s12 m12 l10" id='game-width-box'></div>
			</div>
			<div id="game-community-others-tab" class="col s12 game-tab">
				<?php ShowCommunityEveryoneElse($game, $_SESSION['logged-in']->_id, $myxp, $otherverified, $othercurated, $otherusers); ?>
			</div>
		<?php }else{ ?>
			<div id="game-community-tab" class="col s12 game-tab">
				<?php ShowCommunityEveryoneElse($game, $_SESSION['logged-in']->_id, $myxp, $otherverified, $othercurated, $otherusers); ?>
				<div class="col s12 m12 l10" id='game-width-box'></div>
			</div>
		<?php } ?>
		<div id="game-analyze-tab" class="col s12 game-tab"><?php DisplayAnalyzeTab($_SESSION['logged-in'], $myxp, $game); ?></div>
		<div id="game-video-tab" class="col s12 game-tab" style='z-index:2;'>
			<?php ShowGameVideos($videoxp, $myxp); ?>
		</div>
		<div id="game-myxp-tab" class="col s12 game-tab">
			<?php 
			if($_SESSION['logged-in']->_id > 0){
				ShowMyXP($myxp, $_SESSION['logged-in']->_id, '', '');
			}else{
			?>
				<div class="info-label">Sign Up/Login to enter your experience with this game.</div>
				<div class="btn waves-effect waves-light fab-login"><i class="mdi-editor-mode-edit left"></i> Login</div>
			<?php
			} ?>
			<div class="col s12 m12 l10" id='myxp-game-width-box'></div>
		</div>
		<div id="game-userxp-tab" class="col s12 game-tab">
			<?php if($otherxp != -1){
					ShowUserXP($otherxp);
					}?>
		</div>
		<div id="game-reflectionpoints-tab" class='col s12 game-tab'>
			<?php ShowReflectionPoints($refpts); ?>
		</div>
		<?php /*
		<div id="game-longform-tab" class="col s12 game-tab">
			<?php ShowLongForm($game); ?>
		</div>
		*/ ?>
		<div id="game-similargames-tab" class='col s12 game-tab'>
			<?php ShowSimilarGames($similar); ?>
		</div>
		<div id="game-collections-tab" class='col s12 game-tab'>
			<?php ShowGameCollections($collections, $game); ?>
		</div>
	</div>
<?php }

function ShowSimilarGames($similar){
	if(sizeof($similar) > 0){ 
		foreach($similar as $sim){
			DisplayGameCard($sim, 0, 0);
		}
	}else{ ?>
		<div class="info-label">As of right now, we don't have any games that we think are similar.</div>
	<?php }
}

function ShowGameCollections($collections, $game){
	if(sizeof($collections) > 0){ ?>
		<div class="game-collection-container">
			<?php
			foreach($collections as $collection){
				DisplayCollection($collection);
			} ?>
		</div>
	<?php }else if($_SESSION['logged-in']->_id > 0){ ?>
		<div class="info-label">This game isn't part of a Collection yet. </div>
		<div class="btn waves-effect waves-light game-collection-btn orange darken-2" data-gameid="<?php echo $game->_id; ?>"><i class="mdi-av-my-library-add left"></i> Add to Collection</div>
	<?php }else{ ?>
		<div class="info-label">This game isn't part of a Collection yet. </div>
		<div class="btn waves-effect waves-light fab-login orange darken-2"><i class="mdi-av-my-library-add left"></i> Add to Collection</div>
	<?php
	}
}

function ShowLongForm($game){
	//Make a request to get the longform version from the DB
	$longform = GetLongFormForUser($game->_id, $_SESSION['logged-in']->_id);
	if($_SESSION['logged-in']->_id > 0){
	?>
	<div class='game-community-box z-depth-1'>
		<div class='row'>
			<div id="myGameJournalDisplay">
				<?php if($longform['Subject'] != ''){ ?><div class="journal-subject-header"><?php echo $longform['Subject']; ?></div><?php } ?>
				<div class='journal-body'><?php echo $longform['Body']; ?></div>
			</div>
			<div class="edit-game-journal-container" <?php if($longform['Body'] != ''){ ?> style='display:none;' <?php } ?>>
				<div class="input-field" style='margin:20px 0 10px;'>
					<input type='text' id="myxp-journal-subject" value='<?php echo $longform['Subject']; ?>'>
					<label for="myxp-journal-subject" <?php if($longform['Subject'] != ''){ ?> class='active' <?php } ?>>Journal Header (optional)</label>
				</div>
				<textarea id="myGameJournalPanel"><?php echo $longform['Body']; ?></textarea>
				<script>
					tinymce.init({ selector:'#myGameJournalPanel', height: 300, body_class: 'tinymce-default-format' });
				</script>
				<br>
				<div class="btn myxp-save-journal" data-gameid="<?php echo $game->_id; ?>">Save Journal</div>
				<?php if($longform['Body'] != ''){ ?><div class="btn myxp-cancel-journal red">Cancel</div><?php } ?>
			</div>
		</div>
		<?php if($longform['Body'] != ''){ ?><br><div class='btn myxp-journal-edit-btn'>Edit Journal</div><?php } ?>
	</div>
	<?php
	}else{
		?>
		<div class="info-label">Sign Up/Login to write your thoughts on your time with this game.</div>
		<div class="btn waves-effect waves-light fab-login"><i class="mdi-editor-mode-edit left"></i> Login</div>
		<?php
	}
}

function ShowReflectionPoints($refpts){ 
	if(sizeof($refpts) > 0){
		foreach($refpts as $pt){ ?>
			<div class='game-community-box z-depth-1'>
				<?php DisplayGamePageReflectionPoint($pt); ?>
			</div>
		<?php
		}
	}else{
		?>
		<div class="info-label">There aren't any reflection points yet. <!--Have an idea for one?--></div>
		<!--<div class="btn waves-effect waves-light supportButton"><i class="mdi-action-question-answer left"></i> Suggest a Reflection Point</div>-->
		<?php
	}
}

function ShowCommunityFollowing($game, $id, $myxp, $verified, $curated, $myusers){
	if($id != ""){
		?>
		<?php if(sizeof($verified) > 0){ ?>
		<div class='game-community-box z-depth-1'>
			<div class='game-community-box-header'><div class="game-community-verified mdi-action-done"></div> Verified</div>
			<div class='row'>
				<?php DisplayAllCommunityCards($verified, "Critic"); ?>
			</div>
		</div>
		<?php }
		if(sizeof($myusers) > 0){ ?>
		<div class='game-community-box z-depth-1'>
			<div class='game-community-box-header'><i class="mdi-social-people" style='display:inline-block;font-size:1.4em;'></i> <div style='display: inline-block;vertical-align: text-bottom;'>Members</div></div>
			<?php DisplayAllCommunityCards($myusers, "Users"); ?>
		</div>
		<?php }
		if(sizeof($curated) > 0){ ?>
		<div class='game-community-box z-depth-1'>
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
				<div class="btn waves-effect waves-light no-critic-bookmark"><i class="mdi-action-bookmark left"></i> Bookmark</div>
			<?php } ?>
		<?php }
	}else{
		?>
		<div class="info-label">Bookmark this game to keep track of your favorites</div>
		<div class="btn waves-effect waves-light fab-login"><i class="mdi-action-bookmark left"></i> Login</div>
		<?php
	}
}

function ShowCommunityEveryoneElse($game, $id, $myxp, $otherverified, $othercurated, $otherusers){
		if(sizeof($otherverified) > 0){ ?>
		<div class='game-community-box z-depth-1'>
			<div class='game-community-box-header'><div class="game-community-verified mdi-action-done"></div> Verified</div>
			<?php DisplayAllCommunityCards($otherverified, "Critic");	?>
		</div>
			<?php }
		if(sizeof($othercurated) > 0){ ?>
		<div class='game-community-box z-depth-1'>
			<div class='game-community-box-header'><i class="mdi-file-folder-shared" style='display:inline-block;font-size:1.4em;'></i> <div style='display: inline-block;vertical-align: text-bottom;'>Curated</div></div>
			<?php DisplayAllCommunityCards($othercurated, "Critic");	?>
		</div>
		<?php }
		if(sizeof($otherusers) > 0){ ?>
		<div class='game-community-box z-depth-1'>
			<div class='game-community-box-header'><i class="mdi-social-people" style='display:inline-block;font-size:1.4em;'></i> <div style='display: inline-block;vertical-align: text-bottom;'>Members</div></div>
			<?php DisplayAllCommunityCards($otherusers, "Users"); ?>
		</div>
	<?php }
}

function ShowGameVideos($videoxp, $myxp){
	$i = 0;
	if(sizeof($videoxp) > 0){
	?>
	<div class="row">
		<?php
		foreach($videoxp as $video){
			$summary = '';
			$tier = '';
			foreach($myxp->_watchedxp as $watched){
				if($watched->_url == $video['URL']){
					$summary = $watched->_archivequote;
					$tier = $watched->_archivetier;
					break;
				}	
			}
			DisplayGameVideoCard($video, $i, $summary, $tier);
			$i++;
		} 
		?>
		<div class="col s12 video-show-watched" style='margin-top:50px;'>
			<div class="info-label ">Currently hiding your previously watched videos</div>
			<div class="btn waves-effect waves-light"><i class="mdi-action-visibility left"></i> Show all videos</div>
		</div>
	</div>
	<?php
	}else{
		?>
		<div class="info-label">Members haven't shared their watched experiences yet. Add your own!</div>
		<?php 	if($_SESSION['logged-in']->_id > 0){ ?>
			<div class="btn waves-effect waves-light game-add-watched-btn-fast"><i class="mdi-action-visibility left"></i> Add your own Watched XP</div>
		<?php }else{ ?>
			<div class="btn waves-effect waves-light fab-login"><i class="mdi-action-visibility left"></i> Login</div>
		<?php }
	}
}

function DisplayGameVideoCard($video, $uniqueID = 0, $summary = '', $tier = ''){
	$month = date('n');
 	if($month > '0' && $month <= '3'){
		$quarter = "q1";
	}else if($month > '3' && $month <= '6'){
		$quarter = "q2";
	}else if($month > '6' && $month <= '9'){
		$quarter = "q3";
	}else if($month > '9' && $month <= '12'){
		$quarter = "q4";
	}else if($month == 0){
		$quarter = "q0";
	}
	?>
	<div class="col s12 m12 l6 <?php if($summary == '' && $tier == ''){ echo "video-is-unwatched"; }else{ echo "video-is-watched"; } ?>" style="margin-top:10px;">
		<div class="row">
			<div class="col s12 video-card z-depth-1" data-source="<?php echo $video['Source']; ?>" data-url="<?php echo htmlentities($video['URL']); ?>" data-length="<?php echo $video['Length']; ?>" data-year="<?php echo date("Y"); ?>" data-quarter="<?php echo $quarter; ?>">
				<div class="row">
					<?php DisplayEmbeddedVideo($video); ?>
				</div>
				<div class="row video-add-watch-container" style="height:375px;">
					<?php DisplayXPEntryAtVideo($video, $summary, $tier, $uniqueID); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function DisplayXPEntryAtVideo($video, $summary, $tier, $uniqueID){
	?>
	<div class="col s12" style='text-align:left;position:relative;top:15px;'>
		<div class="collection-game-myxp-gutter"><i class="mdi-social-poll left"></i></div>
		<div class="collection-game-myxp-header">Tier</div>
		<div class="collection-game-myxp-container" >
		 	<div class="collection-myxp-tier-container">
		  	    <div class="collection-myxp-tier t5 tierBorderColor5 <?php if($tier == 5){ echo "tierBorderColor5selected myxp-selected-tier"; } ?>" data-tier='5' >5 <div class="collection-myxp-label" style='color: #DB0058;'>Worst</div></div>
			    <div class="collection-myxp-tier t4 tierBorderColor4 <?php if($tier == 4){ echo "tierBorderColor4selected myxp-selected-tier"; } ?>" data-tier='4' >4</div>
			    <div class="collection-myxp-tier t3 tierBorderColor3 <?php if($tier == 3){ echo "tierBorderColor3selected myxp-selected-tier"; } ?>" data-tier='3' >3</div>
		  	    <div class="collection-myxp-tier t2 tierBorderColor2 <?php if($tier == 2){ echo "tierBorderColor2selected myxp-selected-tier"; } ?>" data-tier='2' >2</div>
		  	    <div class="collection-myxp-tier t1 tierBorderColor1 <?php if($tier == 1){ echo "tierBorderColor1selected myxp-selected-tier"; } ?>" data-tier='1' >1 <div class="collection-myxp-label" style='left:15px;color: #0A67A3;'>Best</div></div>
		  	</div>
	  	</div>
	</div>
	<div class="input-field col s12" style="margin-top: 140px;">
  		<div class="collection-game-myxp-gutter"><i class="mdi-editor-format-quote quoteflip left" style='font-size: 1.5em;margin-top: -8px;'></i></div>
		<div class="collection-game-myxp-header">Summary</div>
		<div class="collection-game-myxp-container" style='top: 75px;'>
	  	    <script>
		      function countChar<?php echo $uniqueID; ?>(val) {
		        var len = val.value.length;
		        if (len > 140) {
		          val.value = val.value.substring(0, 140);
		        } else {
		          $('#charNumCollection<?php echo $uniqueID; ?>').html(len);
		        }
		      };
		    </script>
	        <textarea id="myxp-collection-quote" class="myxp-quote materialize-textarea" onkeyup="countChar<?php echo $uniqueID; ?>(this)" maxlength="140"><?php echo $summary; ?></textarea>
	        <label for="myxp-collection-quote" <?php if($summary != ""){ echo "class='active'"; } ?> ><?php if($tier > 0){ ?>Update your experience (optional)<?php }else{ ?>Enter a summary of your experience here (optional)<?php } ?></label>
        	<?php if($_SESSION['logged-in']->_id > 0){ ?>
				<a class="waves-effect waves-light btn disabled myxp-post" style='padding: 0 1em;float:right;margin-left:50px;margin-top: -10px;'><i class="mdi-editor-mode-edit left"></i>POST</a>
        		<a class="waves-effect waves-light btn-flat myxp-video-goto-full" style='padding: 0 1em;float:right;margin-left:50px;margin-top: -10px;font-size:0.9em;font-weight:500;'><i class="mdi-content-forward left"></i>Go to full XP entry</a>
			<?php }else{ ?>
				<a class="waves-effect waves-light btn fab-login" style='padding: 0 1em;float:right;margin-left:50px;margin-top: -10px;'><i class="mdi-editor-mode-edit left"></i>LOGIN TO POST</a>
			<?php } ?>
	        <div class="myxp-quote-counter" style='float: left;margin-top: -15px;font-size:1em;'><span id='charNumCollection<?php echo $uniqueID; ?>'><?php echo strlen($summary); ?></span>/140</div>
        </div>
      </div>
	<?php
}

function DisplayEmbeddedVideo($video){?>
	<div class="videoWrapper">
		<?php if(strpos($video['URL'] , 'iframe') === false){
			if(strpos($video['URL'] , 'giantbomb.com') !== false){
				if(strpos($video['URL'] , 'giantbomb.com/videos/embed/') !== false){
					$url = $video['URL'];
				}else{
					$url = "http://www.giantbomb.com/videos/embed/";
					$vidArray = explode("-", $video['URL']);
					$video['URL'] = $url.end($vidArray);
				}
				?>
				<iframe data-cbsi-video width="640" height="400" src="<?php echo $video['URL']; ?>" frameborder="0" allowfullscreen></iframe>
			<?php }else if(strpos($video['URL'] , 'youtube.com') !== false || strpos($video['URL'] , 'youtu.be') !== false){
					$url = "https://www.youtube.com/embed/";
					$vidArray = explode("/", $video['URL']);
					$video['URL'] = $url.end(str_replace("watch?v=","",$vidArray));
					?>
					<iframe width="560" height="315" src="<?php echo $video['URL']; ?>" frameborder="0" allowfullscreen></iframe>
			<?php }else if(strpos($video['URL'], 'gamespot.com') !== false){
					$url = "http://www.gamespot.com/videos/embed/";
					$vidArray = explode("-", $video['URL']);
					$video['URL'] = $url.end($vidArray); ?>
					<iframe src="<?php echo $video['URL']; ?>" width="640" height="400" scrolling="no" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
			<?php }else if(strpos($video['URL'] , 'ign.com') !== false){ ?>
					<iframe src="http://widgets.ign.com/video/embed/content.html?url=<?php echo $video['URL']; ?>" width="468" height="263" scrolling="no" frameborder="0" allowfullscreen></iframe>
			<?php }
		}else{
			echo $video['URL'];
		} ?>
	</div>
<?php }

function DisplayVideoForGame($url, $gameid){
	$video = GetVideoXPForGame($url, $gameid);
	$xp = GetVideoMyXPForGame($url, $gameid);
	$tier = $xp->_archivetier;
	$summary = $xp->_archivequote;
	$month = date('n');
 	if($month > '0' && $month <= '3'){
		$quarter = "q1";
	}else if($month > '3' && $month <= '6'){
		$quarter = "q2";
	}else if($month > '6' && $month <= '9'){
		$quarter = "q3";
	}else if($month > '9' && $month <= '12'){
		$quarter = "q4";
	}else if($month == 0){
		$quarter = "q0";
	}
	?>
	<div class="col s12">
		<div class="row">
			<div class="col m12 l6 video-card" data-gameid="<?php echo $gameid; ?>" data-source="<?php echo $video['Source']; ?>" data-url="<?php echo htmlentities($video['URL']); ?>" data-length="<?php echo $video['Length']; ?>" data-year="<?php echo date("Y"); ?>" data-quarter="<?php echo $quarter; ?>">
				<?php DisplayEmbeddedVideo($video); ?>
			</div>
			<div class="col m12 l6 video-card" data-gameid="<?php echo $gameid; ?>" data-source="<?php echo $video['Source']; ?>" data-url="<?php echo htmlentities($video['URL']); ?>" data-length="<?php echo $video['Length']; ?>" data-year="<?php echo date("Y"); ?>" data-quarter="<?php echo $quarter; ?>">
				<?php DisplayXPEntryAtVideo($video, $summary, $tier, $uniqueID); ?>
			</div>
		</div>
	</div>
	<?php
}

function DisplayWatchedXPEntryAjax($url, $gameid){
	$video = GetVideoXPForGame($url, $gameid);
	$xp = GetVideoMyXPForGame($url, $gameid);
	$tier = $xp->_archivetier;
	$summary = $xp->_archivequote;
	$month = date('n');
 	if($month > '0' && $month <= '3'){
		$quarter = "q1";
	}else if($month > '3' && $month <= '6'){
		$quarter = "q2";
	}else if($month > '6' && $month <= '9'){
		$quarter = "q3";
	}else if($month > '9' && $month <= '12'){
		$quarter = "q4";
	}else if($month == 0){
		$quarter = "q0";
	}
	
	?>
	<div class="col m12 video-card z-depth-1" data-gameid="<?php echo $gameid; ?>" data-source="<?php echo $video['Source']; ?>" data-url="<?php echo htmlentities($video['URL']); ?>" data-length="<?php echo $video['Length']; ?>" data-year="<?php echo date("Y"); ?>" data-quarter="<?php echo $quarter; ?>" style='height:400px;'>
		<?php DisplayXPEntryAtVideo($video, $summary, $tier, $uniqueID); ?>
	</div>
	<?php
}

function DisplayAllCommunityCards($users, $type){
	$i = sizeof($users);
	foreach($users as $user){
		if($type == "Critic")
			DisplayCriticQuoteCard($user, $i);
		else
			DisplayUserQuoteCard($user, $i);
			
		$i--;
	}
}

function ShowGameHeader($game, $myxp, $otherxp, $videoxp){
	?>
	<div class="fixed-action-btn" id="game-fab">
		<?php ShowMyGameFAB($game->_id, $myxp); ?>
	</div>
	<div class="fixed-close-modal-btn"><i class="material-icons" style='font-size: 1.2em;vertical-align: sub;'>arrow_forward</i></div>
	<div class="GameHeaderContainer">
		<div class="GameHeaderBackground" style="background: -moz-linear-gradient(bottom, rgba(0,0,0,0) 40%, rgba(0,0,0,0.5) 100%, rgba(0,0,0,0.5) 101%), url(<?php echo $game->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left bottom, left top, color-stop(40%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.5)), color-stop(101%,rgba(0,0,0,0.5))), url(<?php echo $game->_image; ?>) 50% 25%;background: -webkit-linear-gradient(bottom, rgba(0,0,0,0) 40%,rgba(0,0,0,0.5) 100%,rgba(0,0,0,0.5) 101%), url(<?php echo $game->_image; ?>) 50% 25%;background: -o-linear-gradient(bottom, rgba(0,0,0,0) 40%,rgba(0,0,0,0.5) 100%,rgba(0,0,0,0.5) 101%), url(<?php echo $game->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		<div class="GameTitle"><i class="mdi-navigation-menu HideForDesktop" style='color: white;margin-right: 10px;font-size: 1.25em;vertical-align: bottom;'></i> <?php echo $game->_title; ?></div>
	</div>
	<?php
}

function ShowMyGameFAB($gameid, $myxp){
	if($_SESSION['logged-in']->_id > 0){ ?>
	    <a class="btn-floating btn-large <?php if(sizeof($myxp->_playedxp) == 0){ echo "game-add-played-btn red darken-2"; }else{ echo "game-add-watched-btn red darken-2"; } ?> "  data-gameid='<?php echo $myxp->_game->_id; ?>'>
	      <?php if(sizeof($myxp->_playedxp) == 0){ ?>
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
	      	<li><span class="GameHiddenActionLabel">Create a Reflection Point</span><a class="btn-floating pink game-create-reflection-point" data-gameid='<?php echo $myxp->_game->_gbid; ?>'><i class="mdi-action-question-answer"></i></a></li>
	      	<?php } ?>
	      	<li><span class="GameHiddenActionLabel">Add to Collection</span><a class="btn-floating orange darken-2 game-collection-btn" data-gameid='<?php echo $myxp->_game->_id; ?>'><i class="mdi-av-my-library-add"></i></a></li>
	    	<?php if(sizeof($myxp->_playedxp) > 0 || sizeof($myxp->_watchedxp) > 0){ ?>
	    	<li><span class="GameHiddenActionLabel">Pin XP to Profile</span><a class="btn-floating blue-grey darken-3 game-set-fav-btn" data-gameid='<?php echo $myxp->_game->_id; ?>'><i class="fa fa-thumb-tack"></i></a></li>
	    	<?php } ?>
    	  	<li><span class="GameHiddenActionLabel">Remove bookmark</span><a class="btn-floating grey darken-1 game-remove-bookmark-btn" <?php if($myxp->_bucketlist != "Yes"){ echo "style='display:none;'"; } ?> data-gameid='<?php echo $myxp->_game->_id; ?>'><i class="mdi-action-bookmark"></i></a></li>
	      	<li><span class="GameHiddenActionLabel">Bookmark this game</span><a class="btn-floating red darken-4 game-add-bookmark-btn" <?php if($myxp->_bucketlist == "Yes"){ echo "style='display:none;'"; } ?> data-gameid='<?php echo $myxp->_game->_id; ?>'><i class="mdi-action-bookmark"></i></a></li>
	      	
	      	<li><span class="GameHiddenActionLabel">Share game page</span><a class="btn-floating indigo darken-2 game-share-btn" data-gameid='<?php echo $myxp->_game->_id; ?>' data-game-name='<?php echo $myxp->_game->_title; ?>'><i class="mdi-social-share"></i></a></li>
	      	<?php if(sizeof($myxp->_playedxp) == 0){ ?>
	      	<li><span class="GameHiddenActionLabelBigFab">Add a watched XP</span><a class="btn-floating game-add-watched-btn" style='width: 55.5px; height: 55.5px;'><i class="mdi-action-visibility" style='line-height: 55.5px;font-size: 1.6rem;'></i></a></li>
	      	<?php } ?>
	      	
	    </ul>
	<?php }else{ ?>
		<div class="fab-login waves-effect waves-light btn">Add your XP</div>
	<?php }
}

function DisplayGameCard($game, $count, $classId){
	$xp = GetExperienceForUserCompleteOrEmptyGame($_SESSION['logged-in']->_id, $game->_id); ?>
	<div class="col s6 m4 l3" style='position:relative;'>
   		 <div class="collection-quick-add-container z-depth-2">
 			Empty Text
 		 </div>
	      <div class="card game-discover-card <?php echo $classId; ?>"  data-count="<?php echo $count; ?>" data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
	        <a class="card-image waves-effect waves-block" href="/#game/<?php echo $game->_id; ?>/<?php echo urlencode($game->_title); ?>/" onclick="var event = arguments[0] || window.event; event.stopPropagation();" style="width:100%;background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
	        </a>
	        <div class="card-content">
	          <div class="card-title activator grey-text text-darken-4">
				<div class="nav-game-actions row" data-gbid='<?php echo $game->_gbid;?>' data-id='<?php echo $game->_id; ?>'>
					<div class="col s3 game-card-action-pick" data-action="tier"><i class="material-icons nav-game-action-btn <?php if($xp->_tier > 0){ echo "tierTextColor".$xp->_tier; } ?>">
						<?php if($xp->_tier > 0){ 
							DisplayTierBadge($xp->_tier);
						}else{
							echo "add_box";
						} ?>
					</i></div>
					<div class="col s3 game-card-action-pick" data-action="xp"><i class="material-icons nav-game-action-btn <?php if(sizeof($xp->_playedxp) > 0 || sizeof($xp->_watchedxp) > 0){ echo " tierTextColor".$xp->_tier; } ?>">
						<?php if(sizeof($xp->_playedxp) > 0 || sizeof($xp->_watchedxp) > 0){ 
							DisplayXPFace($xp->_tier);	
						}else{
							echo "face";
						} ?>
					</i></div>
					<div class="col s3 game-card-action-pick" data-action="rank">
						<?php if($xp->_rank > 0){ 
							?>
							<div style='padding:4px 0 7px;'><?php echo $xp->_rank; ?></div>
							<?php	
						}else{
							?>
							<i class="material-icons nav-game-action-btn">swap_vert</i>
							<?php
						} ?>
					</div>
					<div class="col s3 game-card-action-pick" data-action="more"><i class="material-icons nav-game-action-btn">more_horiz</i></div>
				</div>
			  	<div class="game-nav-title" title="<?php echo $game->_title; ?>"><?php echo $game->_title; ?></div>
			  </div>
	        </div>
	      </div>
      </div>
<?php }

function DisplayGameCardWithDismiss($game, $count, $classId){
	if($game->_id > 0){
		$xp = GetExperienceForUserCompleteOrEmptyGame($_SESSION['logged-in']->_id, $game->_id); ?>
		<div class="col s6 m4 l3" style='position:relative;'>
			<div class="collection-quick-add-container z-depth-2">
				Empty Text
			</div>
			<div class="card game-discover-card <?php echo $classId; ?>" data-count="<?php echo $count; ?>" data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
				<a class="card-image waves-effect waves-block" href="/#game/<?php echo $game->_id; ?>/<?php echo urlencode($game->_title); ?>/" onclick="var event = arguments[0] || window.event; event.stopPropagation();" style="width:100%;background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
						<div class="game-card-quick-dismiss z-depth-1" data-gbid='<?php echo $game->_gbid;?>' data-id='<?php echo $game->_id; ?>'>
						<i class="material-icons" style='font-size: 1em;margin-top: 4px;'>remove_circle_outline</i> <span style='font-size: 0.7em; font-weight: 400; position: relative; top: -5px;'>Remove from Backlog</span></div>
				</a>
				<div class="card-content">
				<div class="card-title activator grey-text text-darken-4">
					<div class="nav-game-actions row" data-gbid='<?php echo $game->_gbid;?>' data-id='<?php echo $game->_id; ?>'>
						<div class="col s3 game-card-action-pick" data-action="tier"><i class="material-icons nav-game-action-btn <?php if($xp->_tier > 0){ echo "tierTextColor".$xp->_tier; } ?>">
							<?php if($xp->_tier > 0){ 
								if($xp->_tier == 1){ echo "looks_one"; }else if($xp->_tier == 2){ echo "looks_two"; }else if($xp->_tier == 3){ echo "looks_3"; }else if($xp->_tier == 4){ echo "looks_4"; }else if($xp->_tier == 5){ echo "looks_5"; }
							}else{
								echo "add_box";
							} ?>
						</i></div>
						<div class="col s3 game-card-action-pick" data-action="xp"><i class="material-icons nav-game-action-btn <?php if(sizeof($xp->_playedxp) > 0 || sizeof($xp->_watchedxp) > 0){ echo " tierTextColor".$xp->_tier; } ?>">
							<?php if(sizeof($xp->_playedxp) > 0 || sizeof($xp->_watchedxp) > 0){ 
								if($xp->_tier == 1){ echo "sentiment_very_satisfied"; }else if($xp->_tier == 2){ echo "sentiment_satisfied"; }else if($xp->_tier == 3){ echo "sentiment_neutral"; }else if($xp->_tier == 4){ echo "sentiment_dissatisfied"; }else if($xp->_tier == 5){ echo "sentiment_very_dissatisfied"; }
							}else{
								echo "face";
							} ?>
						</i></div>
						<div class="col s3 game-card-action-pick" data-action="rank">
							<?php if($xp->_rank > 0){ 
								?>
								<div style='padding:4px 0 7px;'><?php echo $xp->_rank; ?></div>
								<?php		
							}else{
								?>
								<i class="material-icons nav-game-action-btn">swap_vert</i>
								<?php
							} ?>
						</div>
						<div class="col s3 game-card-action-pick" data-action="more"><i class="material-icons nav-game-action-btn">more_horiz</i></div>
					</div>
					<div class="game-nav-title" title="<?php echo $game->_title; ?>"><?php echo $game->_title; ?></div>
				</div>
				</div>
			</div>
		</div>
<?php }
}

function DisplaySmallGameCard($xp, $showXP = true){
	$game = $xp->_game; ?>
	<div class="col">
	      <div class="card card-game-small" data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
	        <div class="card-image-small" style="width:100%;background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
	        	<div class="card-game-small-title tier<?php if($showXP){ echo $xp->_tier; } ?>BG"><?php echo $game->_title; ?></div>
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

function ShowUserXP($userxp){ 
	$user = $userxp->_username;
	$conn = GetConnectedToList($_SESSION['logged-in']->_id);
	$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
	//$agrees = 0 ;// GetAgreesForXP($userxp->_id);
	//$agreedcount = array_shift($agrees);
	?>
	<div class="row">
		<div class="col s12">
			<div class="myxp-details-container z-depth-1" style='padding: 25px 0 15px !important;'>
				<div class="row" style='margin: 0;'>
					<div class="col s12 userxp-details-lifebar">
						<?php DisplayUserLifeBarRound($user, $conn, $mutualconn, true); ?>
					</div>
		    	    <div class="row" style='margin: 0;'>
		    	    	<div class="myxp-profile-tier-quote btn-flat waves-effect" data-userid='<?php echo  $userxp->_userid; ?>'><i class="mdi-social-person left" style="vertical-align: sub;"></i> View Profile</div>
				    	<div class="myxp-share-tier-quote btn-flat waves-effect" data-userid='<?php echo  $userxp->_userid; ?>'><i class="mdi-social-share left" style="vertical-align: sub;"></i> Share</div>
				    </div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<?php ShowMyXP($userxp, $userxp->_userid, $conn, $mutualconn); ?>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<?php BuildExperienceSpectrum($user, $userxp, $userxp->_game); ?>
			</div>
		</div>
	</div>
<?php
}
?>
