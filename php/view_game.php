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
	$id = $_SESSION['logged-in']->_id;
	$allusers = GetAllUserXPForGame($game->_id);
	$rating = 0;
	$ratingCount = 0;
	foreach($allusers as $allxp){
		if($allxp->_tier == 1)
			$ratingScore = 5;
		else if($allxp->_tier == 2)
			$ratingScore = 4;
		else if($allxp->_tier == 3)
			$ratingScore = 3;
		else if($allxp->_tier == 4)
			$ratingScore = 2;
		else if($allxp->_tier == 5)
			$ratingScore = 1;
		
		$rating = $rating + $ratingScore;
		$ratingCount++;
		$ratingScore = 0;
	}
	if($rating > 0 && $rating > 0)
		$toprating = round(($rating / $ratingCount), 1);
	else
		$toprating = 0;

	?>
	<div id="gameContentContainer" data-gbid="<?php echo $game->_gbid; ?>" data-title="<?php echo urlencode($game->_title); ?>" data-id="<?php echo $game->_id; ?>" class="row">
		<div class="game-activity">
			<div class="row" style='margin-left: 1rem;margin-right: 1rem;'>
				<div class="game-activity-col col s12 m4 l4">
					<div class="card-panel white game-activity-col-card" data-id="<?php echo $game->_id; ?>">
						<div class="game-activity-title"><i class='material-icons' style='font-size: 1.25em;position: relative;top: 2px;margin-right: 3px;'>star</i> Rating</div>
						<div class="game-activity-content">
							<?php if($myxp->_tier > 0){ ?>
								<div class="nav-game-action-btn <?php if($myxp->_tier > 0){ echo "tierTextColor".$myxp->_tier; } ?>" style='position: relative;font-size:2.5em;cursor:pointer;'>
									<?php DisplayStarSequence($myxp->_tier, true); ?>
								</div>
								<div class="game-activity-content-sub-header">
									<?php if($toprating == $myxp->_tier){ ?>
										You <b>agree</b> with most other members.
									<?php }else if($toprating > $myxp->_tier){ ?>
										You rated <b>higher</b> than other members.
									<?php }else if($toprating > 0){ ?>
										You rated <b>lower</b> than other members.
									<?php } ?>
									<br>
									Community Average: <b>
									<?php echo $toprating;  ?></b> <i class="material-icons" style='font-size: 1.2em;margin-left: 8px;position: relative;top: 3px;'>people</i> <?php echo $ratingCount;?>
								</div>
							<?php }else{ ?>
								<div class="nav-game-action-btn" style='position: relative;top: 3px;cursor:pointer;font-size:2.5em;cursor:pointer;'>
									<i class="material-icons star-icon star-icon-1">star_border</i>
									<i class="material-icons star-icon star-icon-2">star_border</i>
									<i class="material-icons star-icon star-icon-3">star_border</i>
									<i class="material-icons star-icon star-icon-4">star_border</i>
									<i class="material-icons star-icon star-icon-5">star_border</i>
								</div>
									<div class="game-activity-content-sub-header">
										<?php if($toprating > 0){ ?>
											Community Average: 
											<b><?php echo $toprating;  ?></b> <i class="material-icons" style='font-size: 1.2em;margin-left: 8px;position: relative;top: 3px;'>people</i> <?php echo $ratingCount;?>
										<?php }else{ ?>
											You haven't rated this game yet
										<?php } ?>
									</div>
							<?php } ?>
						</div>
        			</div>
				</div>
				<div class="game-activity-col col s12 m4 l4"  data-action="xp" data-id='<?php echo $game->_id; ?>'>
					<div class="card-panel white game-activity-col-card" data-gameid="<?php echo $game->_id; ?>">
						<div class="game-activity-title"><i class='material-icons' style='font-size: 1.25em;position: relative;top: 2px;margin-right: 3px;'>subject</i> Details</div>
						<div class="game-activity-content game-nav-title" style='top:6px;font-size:1.25em;'>
							<?php DisplayGameCardXPDetailSummary($myxp); ?> 
						</div>
						<div class="game-activity-content-sub-header">
								<?php if(sizeof($myxp->_playedxp) > 0 || sizeof($myxp->_watchedxp) > 0){
									$totalXp = sizeof($myxp->_playedxp) + sizeof($myxp->_watchedxp);
									if($totalXp > 1){
										echo "You have entered <b>".$totalXp."</b> details";
									}else{
										echo "You have entered <b>1</b> detail";
									}
								}else{
									echo "Add played or watched details";
								} ?>
						</div>
					</div>
				</div>
				<div class="game-activity-col  col s12 m4 l4" data-action="xp" data-id='<?php echo $game->_id; ?>'>
					<div class="card-panel white game-activity-col-card">
						<div class="game-activity-title"><i class='material-icons' style='font-size: 1.25em;position: relative;top: 2px;margin-right: 3px;'>swap_vert</i> Ranking</div>
							<?php if($myxp->_rank > 0){ ?>
								<div class="game-activity-content">
									<div class="game-activity-rank-content">
										<div class="game-activity-rank-item">
											<?php echo $myxp->_rank; ?>
										</div>
										<div class="game-activity-rank-title">
											<?php echo $game->_year; ?>
										</div>
									</div>
									<div class="game-activity-rank-content">
										<div class="game-activity-rank-item">
											/
										</div>
										<div class="game-activity-rank-title">
											&nbsp;
										</div>
									</div>
									<div class="game-activity-rank-content">
										<div class="game-activity-rank-item">
											<?php echo $myxp->_rank; ?>
										</div>
										<div class="game-activity-rank-title">
											All-Time
										</div>
									</div>
								</div>
								<div class="game-activity-content-sub-header">
								</div>
							<?php }else{ ?>
								<div class="game-activity-content">
									<div class="game-activity-rank-content">
										<div class="game-activity-rank-item">
											Unranked
										</div>
										<div class="game-activity-rank-title">
											&nbsp;
										</div>
									</div>
								</div>
								<div class="game-activity-content-sub-header">
									You haven't ranked this game yet
								</div>
							<?php } ?>
					</div>
				</div>
			</div>
			<div class="row game-community-graph" style='margin-left:1rem;margin-right:1rem;'>
				<?php BuildCommunitySpectrum($_SESSION['logged-in'], $myxp, $game); ?>
			</div>
			<div class="activity-top-level" style='position:absolute;width:100%;' data-id='<?php echo $game->_id; ?>' >
				<?php DisplayMainActivity($game->_id, "Game Activity"); ?>
			</div>	
		</div>
	</div>
<?php }


function BuildCommunitySpectrum($user, $myxp, $game){
	if($user->_id > 0){
		$following = GetFollowingTiersForGame($game->_id, $user->_id);
		$followingTotal =  $following[1] + $following[2] + $following[3] + $following[4] + $following[5];
	}
	$critics = GetCriticTiersForGame($game->_id);
	$criticTotal = $critics[1] + $critics[2] + $critics[3] + $critics[4] + $critics[5];
	$users = GetUserTiersForGame($game->_id);
	$usersTotal = $users[1] + $users[2] + $users[3] + $users[4] + $users[5];
	if($followingTotal + $crticTotal + $usersTotal > 3){
	?>
		<div class="col s12">
			<div class="card-panel white" style='position:relative;'>
				<div class="game-activity-title"><i class='material-icons' style='font-size: 1.25em;position: relative;top: 2px;margin-right: 3px;'>timeline</i> Community Rating Breakdown</div>
				<div class="row" style='margin-bottom:0px;'>
					<div class="col s12" style='margin-top:30px;padding:0;'>
						<canvas class="GraphCommunityUsers" style='margin:0.5em 20px 1em'  
							<?php if(sizeof($following) > 1){ ?> data-followingTotal="<?php echo $followingTotal; ?>" data-ft1="<?php echo $following[1] ;?>" data-ft2="<?php echo $following[2] ;?>" data-ft3="<?php echo $following[3] ;?>" data-ft4="<?php echo $following[4] ;?>" data-ft5="<?php echo $following[5]; ?>" <?php } ?>
							<?php if(sizeof($critics) > 1){ ?> data-criticTotal="<?php echo $criticTotal; ?>" data-yt1="<?php echo $critics[1] ;?>" data-yt2="<?php echo $critics[2] ;?>" data-yt3="<?php echo $critics[3] ;?>" data-yt4="<?php echo $critics[4] ;?>" data-yt5="<?php echo $critics[5]; ?>" <?php } ?>
							<?php if(sizeof($users) > 1){ ?> data-usersTotal="<?php echo $usersTotal; ?>" data-gt1="<?php echo $users[1] ;?>" data-gt2="<?php echo $users[2] ;?>" data-gt3="<?php echo $users[3] ;?>" data-gt4="<?php echo $users[4] ;?>" data-gt5="<?php echo $users[5]; ?>" <?php } ?>
						></canvas>
						<div class="analyze-exp-spectrum-tier">
							<?php if($myxp->_tier == 5){ ?>
								<div class="analyze-exp-spectrum-game-piece" style='left:0;'>
									<i class="material-icons tierTextColor<?php echo $myxp->_tier; ?>" style='position: absolute;top: -20px;left: -11px;'>location_on</i>
									<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
								</div>
							<?php } ?>
						</div>
						<div class="analyze-exp-spectrum-tier" style='left:27.4%;'>
							<?php if($myxp->_tier == 4){ ?>
								<div class="analyze-exp-spectrum-game-piece">
									<i class="material-icons tierTextColor<?php echo $myxp->_tier; ?>" style='position: absolute;top: -20px;left: -11px;'>location_on</i>
									<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
								</div>
							<?php } ?>
						</div>
						<div class="analyze-exp-spectrum-tier" style='left:49.3%;'>
							<?php if($myxp->_tier == 3){ ?>
								<div class="analyze-exp-spectrum-game-piece">
									<i class="material-icons tierTextColor<?php echo $myxp->_tier; ?>" style='position: absolute;top: -20px;left: -11px;'>location_on</i>
									<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
								</div>
							<?php } ?>
						</div>
						<div class="analyze-exp-spectrum-tier" style='left:auto;right:calc(60px + 23%)'>
							<?php if($myxp->_tier == 2){ ?>
								<div class="analyze-exp-spectrum-game-piece">
									<i class="material-icons tierTextColor<?php echo $myxp->_tier; ?>" style='position: absolute;top: -20px;left: -11px;'>location_on</i>
									<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
								</div>
							<?php } ?>
						</div>
						<div class="analyze-exp-spectrum-tier" style='left:auto;right:60px;'>
							<?php if($myxp->_tier == 1){ ?>
								<div class="analyze-exp-spectrum-game-piece">
									<i class="material-icons tierTextColor<?php echo $myxp->_tier; ?>" style='position: absolute;top: -20px;left: -11px;'>location_on</i>
									<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="col s12">
						<div class="analyze-exp-key">
							<div class="analyze-line-item" style='background-color:rgba(196, 77, 88, 0.9);'>
								<div class="analyze-line-desc"><i class='material-icons left' style='font-size: 1.5em;'>public</i> Members</div>
							</div>
							<?php if($user->_id > 0){ ?>
								<div class="analyze-line-item" style='background-color:rgba(78, 205, 196, 0.9)'>
									<div class="analyze-line-desc"><i class='material-icons left' style='font-size: 1.5em;'>people</i> Following</div>
								</div>
							<?php } ?>
							<div class="analyze-line-item"  style='background-color:rgba(85, 98, 112, 0.9)';>
								<div class="analyze-line-desc"><i class='material-icons left' style='font-size: 1.5em;'>school</i> Critics</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
}

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
	<div class="col s12" style='text-align:left;position:relative;top:0px;'>
		<div class="row" style='margin-bottom:0px;'>
			<div class="col s12">
				<div class="modal-xp-header" style='margin:0;'>How was the overall experience?</div>
			</div>
			<div class="col s2 offset-s1 modal-xp-emoji-icon tierTextColor5 <?php if($subxp != null && $subxp->_archivetier == 5){?>modal-xp-emoji-icon-active<?php } ?>" data-tier="5">
				<i class="material-icons" style='font-size:1em;'>sentiment_very_dissatisfied</i>
			</div>
			<div class="col s2 modal-xp-emoji-icon tierTextColor4 <?php if($subxp != null && $subxp->_archivetier == 4){?>modal-xp-emoji-icon-active<?php } ?>" data-tier="4">
				<i class="material-icons" style='font-size:1em;'>sentiment_dissatisfied</i>
			</div>
			<div class="col s2 modal-xp-emoji-icon tierTextColor3 <?php if($subxp != null && $subxp->_archivetier == 3){?>modal-xp-emoji-icon-active<?php } ?>" data-tier="3">
				<i class="material-icons" style='font-size:1em;'>sentiment_neutral</i>
			</div>
			<div class="col s2 modal-xp-emoji-icon tierTextColor2 <?php if($subxp != null && $subxp->_archivetier == 2){?>modal-xp-emoji-icon-active<?php } ?>" data-tier="2">
				<i class="material-icons" style='font-size:1em;'>sentiment_satisfied</i>
			</div>
			<div class="col s2 modal-xp-emoji-icon tierTextColor1 <?php if($subxp != null && $subxp->_archivetier == 1){?>modal-xp-emoji-icon-active<?php } ?>" data-tier="1">
				<i class="material-icons" style='font-size:1em;'>sentiment_very_satisfied</i>
			</div>
		</div>
	</div>
	<div class="input-field col s12" style="margin-top: 30px;">
		<textarea id="myxp-quote" class="materialize-textarea myxp-quote" length="140" maxlength="140" <?php if($existing){?>disabled style='background-color:#ddd;padding: 5px;color: gray;'<?php } ?>><?php if($existing){ echo $subxp->_archivequote; } ?></textarea>
		<label for="myxp-quote" <?php if($existing){ echo "class='active' style='top: 0.5em;'"; } ?> ><?php if($existing){ echo "Summary (disabled)"; }else{ echo "Summarize your experience"; } ?></label>
    </div>
	<div class="save-btn disabled modal-btn-pos save-watched-xp" style='margin: 1em 0;' data-gameid='<?php echo $xp->_game->_id; ?>'>Save Details</div>
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
					$vurl = str_replace("watch?v=","",$video['URL']);
					$vidArray = explode("/", $vurl);
					$video['URL'] = $url.end($vidArray);
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
	$game = GetGame($gameid);
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
			<div class="fixed-close-modal-btn"><i class="material-icons" style='font-size: 1.2em;vertical-align: sub;'>arrow_forward</i></div>
			<div class="GameHeaderContainer" style='height:10vh;'>
				<div class="GameHeaderBackground" style="height:10vh;background: -moz-linear-gradient(bottom, rgba(0,0,0,0.5) 40%, rgba(0,0,0,0.7) 100%, rgba(0,0,0,0.7) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -webkit-gradient(linear, left bottom, left top, color-stop(40%,rgba(0,0,0,0.5)), color-stop(100%,rgba(0,0,0,0.7)), color-stop(101%,rgba(0,0,0,0.7))), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -webkit-linear-gradient(bottom, rgba(0,0,0,0.5) 40%,rgba(0,0,0,0.7) 100%,rgba(0,0,0,0.7) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -o-linear-gradient(bottom, rgba(0,0,0,0.5) 40%,rgba(0,0,0,0.7) 100%,rgba(0,0,0,0.7) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
				<div class="modal-header">
						<div style='font-size:0.7em;'>Watch Video</div><div style='font-weight:300;'><?php echo $game->_title;?></div>
				</div>
			</div>	
			<div class="col m12 video-card" style='margin-bottom:20px;margin-top:11vh;' data-gameid="<?php echo $gameid; ?>" data-source="<?php echo $video['Source']; ?>" data-url="<?php echo htmlentities($video['URL']); ?>" data-length="<?php echo $video['Length']; ?>" data-year="<?php echo date("Y"); ?>" data-quarter="<?php echo $quarter; ?>">
				<?php DisplayEmbeddedVideo($video); ?>
			</div>
			<div class="col m12 video-card" data-gameid="<?php echo $gameid; ?>" data-source="<?php echo $video['Source']; ?>" data-url="<?php echo htmlentities($video['URL']); ?>" data-length="<?php echo $video['Length']; ?>" data-year="<?php echo date("Y"); ?>" data-quarter="<?php echo $quarter; ?>">
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
	<div class="col m12 video-card z-depth-1" data-gameid="<?php echo $gameid; ?>" data-source="<?php echo $video['Source']; ?>" data-url="<?php echo htmlentities($video['URL']); ?>" data-length="<?php echo $video['Length']; ?>" data-year="<?php echo date("Y"); ?>" data-quarter="<?php echo $quarter; ?>" style='height:330px;'>
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

function ShowGameHeader($game, $myxp, $otherxp){
	?>
	<div class="fixed-close-modal-btn"><i class="material-icons" style='font-size: 1.2em;vertical-align: sub;'>arrow_forward</i></div>
	<div class="GameHeaderContainer">
		<div class="GameHeaderBackground" style="background: -moz-linear-gradient(bottom, rgba(0,0,0,0) 40%, rgba(0,0,0,0.5) 100%, rgba(0,0,0,0.5) 101%), url(<?php echo $game->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left bottom, left top, color-stop(40%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.5)), color-stop(101%,rgba(0,0,0,0.5))), url(<?php echo $game->_image; ?>) 50% 25%;background: -webkit-linear-gradient(bottom, rgba(0,0,0,0) 40%,rgba(0,0,0,0.5) 100%,rgba(0,0,0,0.5) 101%), url(<?php echo $game->_image; ?>) 50% 25%;background: -o-linear-gradient(bottom, rgba(0,0,0,0) 40%,rgba(0,0,0,0.5) 100%,rgba(0,0,0,0.5) 101%), url(<?php echo $game->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		<div class="GameTitle"><?php echo $game->_title; ?></div>
		<div class="GameMeta">
			<?php $platforms = explode("\n", $game->_platforms); 
				$platforms = array_filter($platforms);
				foreach($platforms as $platform){ 
					if($platform != ""){ 
						$platform = str_replace(array("\n", "\t", "\r"), '', $platform); ?>
						<div style="margin-bottom:5px;color:white;">
							<?php echo $platform;?>
						</div>
				<?php 	} 
				} ?>
		</div>
		<div  class="GameHeaderActionBar">
	          <div class="card-title activator grey-text text-darken-4">
				<div class="game-action-bar-list row" style='' data-gbid='<?php echo $game->_gbid;?>' data-id='<?php echo $game->_id; ?>'>
					<div class="col">
						<div class="game-action-bar-item game-action-bookmark" data-gameid="<?php echo $game->_id; ?>">
							<?php if($myxp->_bucketlist == "Yes"){ ?>
								<i class="material-icons red-text" style="font-size:1.75em;vertical-align: middle;">bookmark</i>
								<span class="game-action-bar-item-title">Bookmark</span>
							<?php }else{ ?>
								<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">bookmark_border</i>
								<span class="game-action-bar-item-title">Bookmark</span>
							<?php } ?>
						</div>
					</div>
					<?php if(sizeof($myxp->_playedxp) > 0 || sizeof($myxp->_watchedxp) > 0){ ?>
						<div class="col">
							<div class="game-action-bar-item game-action-pin-to-profile" data-gameid="<?php echo $game->_id; ?>">
							<?php if($myxp->_username->_weave->_preferredXP == $game->_id){ ?>
								<i class="material-icons blue-text" style="font-size:1.75em;vertical-align: middle;">photo_album</i>
								<span class="game-action-bar-item-title">Pin to Profile</span>
							<?php }else{ ?>
								<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">photo</i>
								<span class="game-action-bar-item-title">Pin to Profile</span>
							<?php } ?>
							</div>
						</div>
					<?php } ?>
					<div class="col">
						<div class="game-action-bar-item game-action-share" data-gameid="<?php echo $game->_id; ?>">
							<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">share</i>
							<span class="game-action-bar-item-title">Share</span>
						</div>
					</div>
					<?php if($_SESSION['logged-in']->_security == "Admin"){ ?>
						<div class="col">
							<div class="game-action-bar-item game-action-file-upload" data-gameid="<?php echo $game->_id; ?>" data-year="<?php echo $game->_year; ?>">
								<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">file_upload</i>
								<span class="game-action-bar-item-title">Upload Image</span>
							</div>
						</div>
						<div class="col">
							<div class="game-action-bar-item game-action-gb-update" data-gameid="<?php echo $game->_id; ?>">
								<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">update</i>
								<span class="game-action-bar-item-title">Update from GB</span>
							</div>
						</div>
						<!--<div class="col">
							<div class="game-action-bar-item game-action-new-ref-pt">
								<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">forum</i>
								<span class="game-action-bar-item-title">New Ref Pt</span>
							</div>
						</div>-->
					<?php } ?>
				</div>
			  </div>
		</div>
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


function DisplayGameCard($game, $count, $classId, $type = ""){
	$xp = GetExperienceForUserCompleteOrEmptyGame($_SESSION['logged-in']->_id, $game->_id); ?>
	<div class="col s6 m4 l3" style='position:relative;'>
   		 <div class="collection-quick-add-container z-depth-2">
 			Empty Text
 		 </div>
	      <div class="card game-discover-card <?php echo $classId; ?>"  data-count="<?php echo $count; ?>" data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
	        <a class="card-image waves-effect waves-block card-game-image" href="/#game/<?php echo $game->_id; ?>/<?php echo urlencode($game->_title); ?>/" onclick="var event = arguments[0] || window.event; event.stopPropagation();" style="width:100%;background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
			<div class="game-card-image-title">
				<?php echo $game->_title; ?>
			</div>
			</a>
			<div class="card-game-secondary-actions">
				<div class="game-card-secondary-action-container">
					<div class="game-card-secondary-action-i game-card-quick-bookmark <?php if($xp->_bucketlist == "Yes"){ ?>nav-game-action-isBookmarked<?php } ?>" data-gbid='<?php echo $game->_gbid;?>' data-id='<?php echo $game->_id; ?>'>
						<?php if($xp->_bucketlist == "Yes"){ ?>
							<i class="material-icons">bookmark</i>
						<?php }else{ ?>
							<i class="material-icons">bookmark_border</i>
						<?php } ?>
					</div>
					<!--<div class="game-card-secondary-action-i game-card-quick-collection-add" data-gbid='<?php echo $game->_gbid;?>' data-id='<?php echo $game->_id; ?>'>
						<i class="material-icons">library_add</i>
					</div>-->
					<!--<div class="game-card-secondary-action-i game-card-quick-share" data-gbid='<?php echo $game->_gbid;?>' data-id='<?php echo $game->_id; ?>'>
						<i class="material-icons">share</i>
					</div>-->
					<?php if($type == "Lifebar Backlog"){ ?>
						<div class="game-card-secondary-action-i game-card-quick-dismiss" data-gbid='<?php echo $game->_gbid;?>' data-id='<?php echo $game->_id; ?>'>
							<i class="material-icons">remove_circle_outline</i></span>
						</div>
					<?php } ?>
				</div>
				<div class="game-card-image-title">
					<?php echo $game->_title; ?>
				</div>
			</div>
	        <div class="card-content">
	          <div class="card-title activator grey-text text-darken-4">
				<div class="nav-game-actions row" data-gbid='<?php echo $game->_gbid;?>' data-id='<?php echo $game->_id; ?>'>
					<div class="col s12 game-card-action-pick">
						<?php if($xp->_tier > 0){ ?>
							<div class="nav-game-action-btn <?php if($xp->_tier > 0){ echo "tierTextColor".$xp->_tier; } ?>">
								<?php DisplayStarSequence($xp->_tier, true); ?>
							</div>
						<?php }else{ ?>
							<div class="nav-game-action-btn">
								<i class="material-icons star-icon star-icon-1">star_border</i>
								<i class="material-icons star-icon star-icon-2">star_border</i>
								<i class="material-icons star-icon star-icon-3">star_border</i>
								<i class="material-icons star-icon star-icon-4">star_border</i>
								<i class="material-icons star-icon star-icon-5">star_border</i>
							</div>
						<?php } ?>
					</div>
				</div>
			  	<div class="game-nav-title game-card-action-pick" data-action="xp" data-id='<?php echo $game->_id; ?>'>
					<?php DisplayGameCardXPDetailSummary($xp); ?> 
				</div>
			  </div>
	        </div>
	      </div>
      </div>
<?php }

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

function DisplayGameCardXPDetailSummary($xp){ 
 		if(sizeof($xp->_playedxp) > 0){ 
		  	  	if($xp->_playedxp[0]->_completed == "101")
					$percent = 100;
				else
					$percent = $xp->_playedxp[0]->_completed; ?>
				<div class="game-card-summary-prog-title">
					<?php if($percent < 100){ echo $percent."%"; }else{ ?>
						<i class="material-icons" style='font-size: 1.5em;vertical-align: middle;margin-top: -2px;margin-left: 3px;'>check</i>
					<?php } ?>
				</div>
				<div class="game-card-summary-prog-bar-container">
					<div class="game-card-summary-prog-bar tier<?php echo $xp->_playedxp[0]->_archivetier; ?>BG" style='width:<?php echo $percent; ?>%'>
						<?php if($percent >= 100){ ?><div style='color:white;font-size:0.7em;display:none;'>Completed</div><?php } ?>
					</div>
				</div>
  	  <?php }else if(sizeof($xp->_watchedxp) > 0){ 
  	  		$length = "";
    		$watched = $xp->_watchedxp[0];
				$length = $watched->_length;
    			if($watched->_length == "Watched a speed run"){
    				$icon = "directions_walk";
				}else if($watched->_length == "Watched a complete single player playthrough" || $watched->_length == "Watched a complete playthrough"){
    				$icon = "beenhere";
				}else if($watched->_length == "Watched competitive play"){
					$icon = "headset_mic";
    			}else if($watched->_length == "Watched multiple hours" || $watched->_length == "Watched gameplay" || $watched->_length == "Watched an hour or less"){
    				$icon = "videogame_asset";
    			}else if($watched->_length == "Watched promotional gameplay"){
					$icon = "movie_creation";
				}else if($watched->_length == "Watched a developer diary"){
    				$icon = "class";
    			}else{
					$icon = "theaters";
    			}
    		?>
			<div class="game-card-summary-watch-container game-card-action-pick" data-action="xp"  data-id='<?php echo $xp->_game->_id; ?>'>
				<div class="game-card-summary-watch">
					<i class="material-icons tierTextColor<?php echo $watched->_archivetier; ?>" style='font-size:1.75em;vertical-align: middle;'><?php echo $icon; ?></i>
					<span class="game-card-summary-watch-length"><?php echo $length; ?></span>
				</div>
			</div>
		<?php
		}else if(sizeof($xp->_postedxp) > 0){ ?>
			<div class="game-card-summary-watch-container game-card-action-pick" data-action="xp"  data-id='<?php echo $xp->_game->_id; ?>'>
				<div class="game-card-summary-watch">
					<i class="material-icons" style='font-size:1.75em;vertical-align: middle;'>format_quote</i>
					<span class="game-card-summary-watch-length">Posted <?php echo ConvertTimeStampToRelativeTime($xp->_postedxp[0]->_entereddate);?></span>
				</div>
			</div>
		<?php
		}else{ ?>
			<div class="game-card-summary-watch-container game-card-action-pick" data-action="xp"  data-id='<?php echo $xp->_game->_id; ?>'>
				<div class="game-card-summary-add-xp">
					<i class="material-icons game-card-summary-add-xp-icon">add_circle</i>
					<span class="game-card-summary-add-xp-text">ADD DETAILS</span>
				</div>
			</div>
		<?php
		}
}


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
