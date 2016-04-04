<?php function DisplayAnalyzeTab($user, $myxp, $game){
	if($myxp->_tier <= 0){
		BuildWarningMsg($user);
	}
	BuildExperienceSpectrum($user, $myxp, $game);
	BuildFranchiseGames($game, $user->_id, $myxp->_tier);
	BuildDeveloperGames($game, $user->_id, $myxp->_tier);
	BuildPublisherGames($game, $user->_id, $myxp->_tier);
	?>
	<div class="row">
		<?php 	
			BuildCompletedCard($game);
		  	BuildBookmarkedCard($game); 
			BuildAvgAgeCard($game); 
		?>
	</div>
	<?php
	BuildCommunitySpectrum($user, $myxp, $game);
	BuildAgeGraph($user, $myxp, $game);
	//BuildSimilarGames($game, $user->_id, $myxp);
} ?>

<?php function BuildExperienceSpectrum($user, $myxp, $game){
	$tierdata = explode("||", $user->_weave->_overallTierTotal);
	
	$lifetime[] = $tierdata[0];
	$lifetime[] = $tierdata[1];
	$lifetime[] = $tierdata[2];
	$lifetime[] = $tierdata[3];
	$lifetime[] = $tierdata[4];
	$lifetimeTotal = $tierdata[0] + $tierdata[1] + $tierdata[2] + $tierdata[3] + $tierdata[4];
	$thisyear = GetMyTiersByYear($user->_id, $game->_year);
	$thisYearTotal = $thisyear[1] + $thisyear[2] + $thisyear[3] + $thisyear[4] + $thisyear[5];
	$bygenre = GetMyTiersByGenre($user->_id, $game->_genre);
	$byGenreTotal = $bygenre[1] + $bygenre[2] + $bygenre[3] + $bygenre[4] + $bygenre[5];
	if($lifetimeTotal > 0){
	?>
		<div class="row">
			<div class="col s12 analyze-card z-depth-1" style='width:100%;padding-bottom: 1em !important;' >
				<div class='analyze-card-header'>
					<div class='analyze-card-title'>Personal Experience Spectrum</div>
					<div class='analyze-card-sub-title'>compared to your other game experiences</div>
				</div>
				<div class="row">
					<div class="col s12 m12 l10">
						<canvas class="GraphExpSpectrum" style='margin:0.5em 20px 1em'  
							data-overallTotal="<?php echo $lifetimeTotal; ?>" data-t1="<?php echo $lifetime[0] ;?>" data-t2="<?php echo $lifetime[1] ;?>" data-t3="<?php echo $lifetime[2] ;?>" data-t4="<?php echo $lifetime[3] ;?>" data-t5="<?php echo $lifetime[4]; ?>"
							<?php if(sizeof($thisyear) > 4){ ?> data-yearTotal="<?php echo $thisYearTotal; ?>" data-year="<?php echo $game->_year; ?>" data-yt1="<?php echo $thisyear[1] ;?>" data-yt2="<?php echo $thisyear[2] ;?>" data-yt3="<?php echo $thisyear[3] ;?>" data-yt4="<?php echo $thisyear[4] ;?>" data-yt5="<?php echo $thisyear[5]; ?>" <?php } ?>
							<?php if(sizeof($bygenre) > 4){ ?> data-genreTotal="<?php echo $byGenreTotal; ?>" data-genre="<?php echo $game->_genre; ?>" data-gt1="<?php echo $bygenre[1] ;?>" data-gt2="<?php echo $bygenre[2] ;?>" data-gt3="<?php echo $bygenre[3] ;?>" data-gt4="<?php echo $bygenre[4] ;?>" data-gt5="<?php echo $bygenre[5]; ?>" <?php } ?>
						></canvas>
						<div class="analyze-graph-helper" style='float:left;padding:0 18px;'>WORST</div>
						<div class="analyze-graph-helper" style='float:right;padding:0 0;'>BEST</div>
					</div>
					<div class="col s12 m12 l2">
						<div class="analyze-exp-key">
							<div class="analyze-line-item" style='background-color:rgba(85, 85, 147, 0.9);'>
								<div class="analyze-line-desc"><i class="mdi-action-account-circle left" style='margin-bottom: 15px;'></i> Lifetime</div>
							</div>
							<div class="analyze-line-item" style='background-color:rgba(0, 150, 136, 0.9)'>
								<div class="analyze-line-desc"><i class="mdi-editor-insert-invitation left" style='margin-bottom: 15px;'></i> <?php if($game->_year == 0){ ?>Unreleased games<?php }else{ ?>Released in <?php echo $game->_year;  } ?></div>
							</div>
							<div class="analyze-line-item" style='background-color:rgba(233, 30, 99, 0.9);'>
								<div class="analyze-line-desc"><i class="mdi-action-label left" style='margin-bottom: 15px;'></i>  <?php echo $game->_genre; ?></div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	<?php
	}
} 

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
		<div class="row">
			<div class="col s12 analyze-card z-depth-1" style='width:100%;padding-bottom: 1em !important;' >
				<div class='analyze-card-header'>
					<div class='analyze-card-title'>Community Spectrum</div>
					<div class='analyze-card-sub-title'>compared to other users & critics</div>
				</div>
				<div class="row">
					<div class="col s12 m12 l10">
						<canvas class="GraphCommunityUsers" style='margin:0.5em 20px 1em'  
							<?php if(sizeof($following) > 1){ ?> data-followingTotal="<?php echo $followingTotal; ?>" data-ft1="<?php echo $following[1] ;?>" data-ft2="<?php echo $following[2] ;?>" data-ft3="<?php echo $following[3] ;?>" data-ft4="<?php echo $following[4] ;?>" data-ft5="<?php echo $following[5]; ?>" <?php } ?>
							<?php if(sizeof($critics) > 1){ ?> data-criticTotal="<?php echo $criticTotal; ?>" data-yt1="<?php echo $critics[1] ;?>" data-yt2="<?php echo $critics[2] ;?>" data-yt3="<?php echo $critics[3] ;?>" data-yt4="<?php echo $critics[4] ;?>" data-yt5="<?php echo $critics[5]; ?>" <?php } ?>
							<?php if(sizeof($users) > 1){ ?> data-usersTotal="<?php echo $usersTotal; ?>" data-gt1="<?php echo $users[1] ;?>" data-gt2="<?php echo $users[2] ;?>" data-gt3="<?php echo $users[3] ;?>" data-gt4="<?php echo $users[4] ;?>" data-gt5="<?php echo $users[5]; ?>" <?php } ?>
						></canvas>
						<div class="analyze-graph-helper" style='float:left;padding:0 18px;'>WORST</div>
						<div class="analyze-graph-helper" style='float:right;padding:0 0;'>BEST</div>
						<!--<div class="analyze-exp-spectrum-tier">
							<?php if($myxp->_tier == 5){ ?>
								<div class="analyze-exp-spectrum-game-piece">
									<i class="mdi-communication-location-on tierTextColor<?php echo $myxp->_tier; ?>"></i>
									<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
								</div>
							<?php } ?>
						</div>
						<div class="analyze-exp-spectrum-tier" style='left:25.7%;'>
							<?php if($myxp->_tier == 4){ ?>
								<div class="analyze-exp-spectrum-game-piece">
									<i class="mdi-communication-location-on tierTextColor<?php echo $myxp->_tier; ?>"></i>
									<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
								</div>
							<?php } ?>
						</div>
						<div class="analyze-exp-spectrum-tier" style='left:49.3%;'>
							<?php if($myxp->_tier == 3){ ?>
								<div class="analyze-exp-spectrum-game-piece">
									<i class="mdi-communication-location-on tierTextColor<?php echo $myxp->_tier; ?>"></i>
									<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
								</div>
							<?php } ?>
						</div>
						<div class="analyze-exp-spectrum-tier" style='left:72.7%;'>
							<?php if($myxp->_tier == 2){ ?>
								<div class="analyze-exp-spectrum-game-piece">
									<i class="mdi-communication-location-on tierTextColor<?php echo $myxp->_tier; ?>"></i>
									<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
								</div>
							<?php } ?>
						</div>
						<div class="analyze-exp-spectrum-tier" style='left:96.3%;'>
							<?php if($myxp->_tier == 1){ ?>
								<div class="analyze-exp-spectrum-game-piece">
									<i class="mdi-communication-location-on tierTextColor<?php echo $myxp->_tier; ?>"></i>
									<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
								</div>
							<?php } ?>
						</div>-->
					</div>
					<div class="col s12 m12 l2">
						<div class="analyze-exp-key">
							<div class="analyze-line-item" style='background-color:rgba(63, 81, 181, 0.9);'>
								<div class="analyze-line-desc"><i class='mdi-social-public left' style='margin-bottom: 15px;'></i> Members</div>
							</div>
							<?php if($user->_id > 0){ ?>
								<div class="analyze-line-item" style='background-color:rgba(76, 175, 80, 0.9);'>
									<div class="analyze-line-desc"><i class='mdi-social-people left' style='margin-bottom: 15px;'></i> Following</div>
								</div>
							<?php } ?>
							<div class="analyze-line-item"  style='background-color:rgba(255, 87, 34, 0.9)';>
								<div class="analyze-line-desc"><i class='mdi-social-location-city left' style='margin-bottom: 15px;'></i> Critics</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
} 

function BuildAgeGraph($user, $myxp, $game){
	$tierData = GetTiersForGameWithDate($game->_id);
	$group1Total = $tierData[1] + $tierData[2] + $tierData[3] + $tierData[4] + $tierData[5];
	$group2Total = $tierData[6] + $tierData[7] + $tierData[8] + $tierData[9] + $tierData[10];
	$group3Total = $tierData[11] + $tierData[12] + $tierData[13] + $tierData[14] + $tierData[15];
	$group4Total = $tierData[16] + $tierData[17] + $tierData[18] + $tierData[19] + $tierData[20];
	$group5Total = $tierData[21] + $tierData[22] + $tierData[23] + $tierData[24] + $tierData[25];
	$overallTotal = $group1Total + $group2Total + $group3Total + $group4Total + $group5Total;
	if($overallTotal > 20){
	?>
		<div class="row">
			<div class="col s12 analyze-card z-depth-1" style='width:100%;padding-bottom: 1em !important;' >
				<div class='analyze-card-header'>
					<div class='analyze-card-title'>Experience Breakdown by Age</div>
					<div class='analyze-card-sub-title'>compare different age groups from verified critics and users</div>
				</div>
				<canvas class="GraphAgeUsers" style='margin:0.5em 20px 1em'  
					<?php if(sizeof($group1Total) > 0){ ?> data-group1Total="<?php echo $group1Total; ?>" data-1t1="<?php echo $tierData[1] ;?>" data-1t2="<?php echo $tierData[2] ;?>" data-1t3="<?php echo $tierData[3] ;?>" data-1t4="<?php echo $tierData[4] ;?>" data-1t5="<?php echo $tierData[5]; ?>" <?php } ?>
					<?php if(sizeof($group2Total) > 0){ ?> data-group2Total="<?php echo $group2Total; ?>" data-2t1="<?php echo $tierData[6] ;?>" data-2t2="<?php echo $tierData[7] ;?>" data-2t3="<?php echo $tierData[8] ;?>" data-2t4="<?php echo $tierData[9] ;?>" data-2t5="<?php echo $tierData[10]; ?>" <?php } ?>
					<?php if(sizeof($group3Total) > 0){ ?> data-group3Total="<?php echo $group3Total; ?>" data-3t1="<?php echo $tierData[11] ;?>" data-3t2="<?php echo $tierData[12] ;?>" data-3t3="<?php echo $tierData[13] ;?>" data-3t4="<?php echo $tierData[14] ;?>" data-3t5="<?php echo $tierData[15]; ?>" <?php } ?>
					<?php if(sizeof($group4Total) > 0){ ?> data-group4Total="<?php echo $group4Total; ?>" data-4t1="<?php echo $tierData[16] ;?>" data-4t2="<?php echo $tierData[17] ;?>" data-4t3="<?php echo $tierData[18] ;?>" data-4t4="<?php echo $tierData[19] ;?>" data-4t5="<?php echo $tierData[20]; ?>" <?php } ?>
					<?php if(sizeof($group5Total) > 0){ ?> data-group5Total="<?php echo $group5Total; ?>" data-5t1="<?php echo $tierData[21] ;?>" data-5t2="<?php echo $tierData[22] ;?>" data-5t3="<?php echo $tierData[23] ;?>" data-5t4="<?php echo $tierData[24] ;?>" data-5t5="<?php echo $tierData[25]; ?>" <?php } ?>
				></canvas>
			</div>
		</div>
	<?php
	}
}

function BuildFranchiseGames($game, $userid, $myxptier){
	$franchises = GetGamesFranchiseGames($game->_gbid, $userid);
	if(sizeof($franchises) > 0){?>
		<div class="row">
			<div class="col s12 analyze-card z-depth-1" style='width:100%;padding-bottom: 1em !important;' >
				<div class='analyze-card-header'>
					<div class='analyze-card-title'>Franchises</div>
				</div>
		<?php
		foreach($franchises as $franchise){ 
		?>
				<div class="col s12" style='padding:0;margin-bottom:15px;'>
					<div class="col s12 m6" style='padding:0;'>
						<?php DisplayAnalyzeObjectHeader($franchise[1], $userid, "Franchise")?>
					</div>
					<div class="col s12 m6 analyze-doughnut-container">
						<?php BuildRelationalDoughnut($franchise[0], $myxptier, "Your XP in this franchise"); ?>
					</div>
				</div>
		<?php
		} ?>
			</div>
		</div>
	<?php }
}

function BuildDeveloperGames($game, $userid, $myxptier){
	$developers = GetGamesDeveloperGames($game->_gbid, $userid);
	if(sizeof($developers) > 0){?>
		<div class="row">
			<div class="col s12 analyze-card z-depth-1" style='width:100%;padding-bottom: 1em !important;' >
				<div class='analyze-card-header'>
					<div class='analyze-card-title'>Developers</div>
				</div>
		<?php
		foreach($developers as $developer){ 
		?>
				<div class="col s12" style='padding:0;margin-bottom:15px;'>
					<div class="col s12 m6" style='padding:0;'>
						<?php DisplayAnalyzeObjectHeader($developer[1], $userid, "Developer")?>
					</div>
					<div class="col s12 m6 analyze-doughnut-container">
						<?php BuildRelationalDoughnut($developer[0], $myxptier, "Your XP with this developer"); ?>
					</div>
				</div>
		<?php
		} ?>
			</div>
		</div>
	<?php }
}

function BuildPublisherGames($game, $userid, $myxptier){
	$publishers = GetGamesPublisherGames($game->_gbid, $userid);
	if(sizeof($publishers) > 0){?>
		<div class="row">
			<div class="col s12 analyze-card z-depth-1" style='width:100%;padding-bottom: 1em !important;' >
				<div class='analyze-card-header'>
					<div class='analyze-card-title'>Publishers</div>
				</div>
		<?php
		foreach($publishers as $publisher){ 
		?>
				<div class="col s12" style='padding:0;margin-bottom:15px;'>
					<div class="col s12 m6" style='padding:0;'>
						<?php DisplayAnalyzeObjectHeader($publisher[1], $userid, "Publisher")?>
					</div>
					<div class="col s12 m6 analyze-doughnut-container">
						<?php BuildRelationalDoughnut($publisher[0], $myxptier, "Your XP with this publisher"); ?>
					</div>
				</div>
		<?php
		} ?>
			</div>
		</div>
	<?php }
}

function DisplayAnalyzeObjectHeader($milestone, $userid, $type){
	if($milestone->_progress->_percentlevel4 == 100 && $milestone->_level5 > 0){
		$progress = $milestone->_progress->_progresslevel5;
		$currentlevel = 5;
		$percent = $milestone->_progress->_percentlevel5;
		$threshold = $milestone->_level5;
	}else if($milestone->_progress->_percentlevel3 == 100  && $milestone->_level4 > 0){
		$progress = $milestone->_progress->_progresslevel4;
		$currentlevel = 4;
		$percent = $milestone->_progress->_percentlevel4;
		$threshold = $milestone->_level4;
	}else if($milestone->_progress->_percentlevel2 == 100  && $milestone->_level3 > 0){
		$progress = $milestone->_progress->_progresslevel3;
		$currentlevel = 3;
		$percent = $milestone->_progress->_percentlevel3;
		$threshold = $milestone->_level3;
	}else if($milestone->_progress->_percentlevel1 == 100  && $milestone->_level2 > 0){
		$progress = $milestone->_progress->_progresslevel2;
		$currentlevel = 2;
		$percent = $milestone->_progress->_percentlevel2;
		$threshold = $milestone->_level2;
	}else{
		$progress = $milestone->_progress->_progresslevel1;
		$currentlevel = 1;
		$percent = $milestone->_progress->_percentlevel1;
		$threshold = $milestone->_level1;
	}
	?>
	<div class="analyze-progress-item" style='text-align:left;'>
		<?php if($milestone->_image == ""){ ?>
			<div class="analyze-object-image-item z-depth-1" style='text-align: center;background-color: orange;padding-top: 30px;'><i class="bp-item-image-icon mdi-content-flag"></i></div>
		<?php }else{ ?>
			<div class="analyze-object-image-item z-depth-1" style="background:url(<?php echo $milestone->_image; ?>) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;vertical-align:middle;"></div>
		<?php } ?>
		<div class="analyze-progress-item-details">
			<div class="bp-progress-item-name" style='display: inline-block;vertical-align: middle;'><?php  echo $milestone->_name;?></div>
			<br>
			<div class='btn analyze-doughnut-view-games' data-type="<?php echo $type; ?>" data-userid="<?php echo $userid; ?>" data-progid="<?php echo $milestone->_progress->_id; ?>" data-id="<?php echo $milestone->_id; ?>" data-objectid="<?php echo $milestone->_objectid; ?>" style='font-size: 0.8em; height: 28px;line-height: 28px;padding: 0 1rem;margin-top: 10px;'>View Games</div>
		</div>
		<div class="bp-progress-item-bar" style='height:20px;margin-top: 10px;'>
			<div class="bp-progress-item-bar-before" style="width:<?php echo $percent; ?>%;height:20px;background-color: #66BB6A;"></div>
		</div>
		<div class="bp-progress-item-levelup">Level <?php echo $currentlevel; ?> <span style='font-weight:300;'>(<?php echo $progress." / ".$threshold; ?>)</span></div>
	</div>
	<?php
}

function BuildSimilarGames($game, $userid, $myxp){
	$similargames = explode(",",$game->_similar);
	$similarData = array();
	$tierData = array(); $tierData[1]=0; $tierData[2]=0; $tierData[3]=0; $tierData[4]=0; $tierData[5]=0;
	if(sizeof($similargames) > 0){
		foreach($similargames as $games){ 
			unset($info);
			$quickgame = GetGameByGBID($games);
			$info[0] = $quickgame;
			$info[1] = GetExperienceForUserComplete($userid, $quickgame->_id);
			$similarData[] = $info;
			if(isset($info[1]) && $info[1]->_tier > 0){
				if($info[1]->_tier == 1)
					$tierData[1] = $tierData[1] + 1;
				else if($info[1]->_tier == 2)
					$tierData[2] = $tierData[2] + 1;
				else if($info[1]->_tier == 3)
					$tierData[3] = $tierData[3] + 1;
				else if($info[1]->_tier == 4)
					$tierData[4] = $tierData[4] + 1;
				else if($info[1]->_tier == 5)
					$tierData[5] = $tierData[5] + 1;
			}
		}
		
	?>
	<div class="row">
		<div class="col s12 analyze-card z-depth-1" style='width:100%;padding-bottom: 1em !important;' >
		<div class='analyze-card-header'>
			<div class='analyze-card-title'>Similar Games</div>
		</div>
		<div class="col s12" style='position:relative;'>
			<?php BuildRelationalGraph($tierData, $myxp->_tier); ?>
		</div>
		<?php 
		$count = 1;
		foreach($similarData as $games){ 
				unset($simgame);
				unset($simxp);
				$simgame = $games[0];
				$simxp = $games[1];
				if($simgame != ''){ ?>
				<div class="col s12 game-list-item" data-tier='<?php echo $simxp->_tier; ?>' data-year='<?php echo $simxp->_year; ?>' data-title="<?php echo $simxp->_title; ?>" >
		     		<div class="analyze-card-list-item <?php if($count > 5){ ?>analyze-view-more-hide<?php } ?>" data-gameid="<?php echo $simxp->_gameid; ?>" data-gbid="<?php echo $similar; ?>" style='background-color:white;'>
				        <div class="analyze-image-list" style="width:100%;background:url(<?php echo $simgame->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
				        <div class="analyze-game-list-title">
				        	<?php echo $simgame->_title; ?> 
				        	<div class="analyze-game-list-details"><?php if($simgame->_year > 0){ echo $simgame->_year; } ?></div>
			        	</div>
			      		<div class="analyze-game-my-tier">
				  			<?php if(sizeof($simxp->_playedxp) > 0){ 
						  	  	if($simxp->_playedxp[0]->_completed == "101")
									$percent = 100;
								else
									$percent = $simxp->_playedxp[0]->_completed;
									
								if($percent == 100){ ?>
				  	  	       		<div class="analyze-game-tier-position tier<?php echo $simxp->_tier; ?>BG z-depth-1">
							          	<div class="card-game-tier" title="<?php echo "Tier ".$simxp->_tier." - Completed"; ?>">
						    				<i class="mdi-hardware-gamepad"></i>
							          	</div>
						          	</div>
					          	<?php }else{ ?>
					          		<div class="analyze-game-tier-position tier<?php echo $simxp->_tier; ?>BG z-depth-1">
					      			  	<div class="c100 mini <?php if($simxp->_tier == 1){ echo "tierone"; }else if($simxp->_tier == 2){ echo "tiertwo"; }else if($simxp->_tier == 3){ echo "tierthree"; }else if($simxp->_tier == 4){ echo "tierfour"; }else if($simxp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$simxp->_tier." - ".$percent."% finished"; ?>" style='background-color:white;'>
									  	  <span class='tierTextColor<?php echo $simxp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-hardware-gamepad"></i></span>
										  <div class="slice">
										    <div class="bar minibar"></div>
										    <div class="fill"></div>
										  </div>
										</div>
									</div>
					          	<?php } ?>
				          	<?php }else if(sizeof($simxp->_watchedxp) > 0){
					  			$percent = 20;
					  	  		$length = "";
					    		foreach($simxp->_watchedxp as $watched){
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
						          <div class="analyze-game-tier-position tier<?php echo $simxp->_tier; ?>BG z-depth-1">
						          	<div class="card-game-tier" title="<?php echo "Tier ".$simxp->_tier." - ".$length; ?>">
						          			<i class="mdi-action-visibility"></i>
						          	</div>
								   </div>
				  	  			<?php }else{ ?>
						      		<div class="analyze-game-tier-position tier<?php echo $simxp->_tier; ?>BG z-depth-1">
						  			  	<div class="c100 mini <?php if($simxp->_tier == 1){ echo "tierone"; }else if($simxp->_tier == 2){ echo "tiertwo"; }else if($simxp->_tier == 3){ echo "tierthree"; }else if($simxp->_tier == 4){ echo "tierfour"; }else if($simxp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$simxp->_tier." - ".$length; ?>" style='background-color:white;'>
									  	  <span class='tierTextColor<?php echo $simxp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-action-visibility"></i></span>
										  <div class="slice">
										    <div class="bar minibar"></div>
										    <div class="fill"></div>
										  </div>
										</div>
						   			</div>
					          	<?php } 
				          	}?>
			      		</div>
		      		</div>
		     	</div>
			<?php $count++;
			} 
		}?>
		<div class='analyze-card-header' style='border-bottom:none;margin-bottom:10px;margin-top:10px;padding:initial;'>
			<div class='analyze-view-more-button btn' style='display:none;'>View More</div>
			<div class='analyze-view-less-button btn' style='display:none;'>Hide</div>
		</div>
		</div>
	</div>
	<?php
	}
}

function BuildWarningMsg($user){ ?>
	<div class="row">
		<div class="col s12" style='font-size: 1.25em;font-weight:500;'>
			<i class="mdi-alert-warning" style='color:orangered;font-size: 1.5em;vertical-align: sub;'></i> 
			<?php if($user->_id > 0){ ?>
				<span>Analysis of this game is limited without any XP</span>
			<?php }else{ ?>
				<span>Analysis of this game is limited without logging in</span>
			<?php } ?>
		</div>
	</div>
<?php
}

function BuildCompletedCard($game){
	$finishedData = GetGameCompletion($game->_id);
	?>
	<div class="col s12 m4 analyze-left-card">
		<div class="row">
			<div class="col s12 analyze-card z-depth-1" style='padding: 0 0 2em !important;' >
				<div class="analyze-data-element"><?php if($finishedData[1] > 0){echo round(($finishedData[0] / $finishedData[1]) * 100); }else{ echo "0"; } ?>%</div>
				<div class="analyze-data-desc">of those who played finished</div>
			</div>
		</div>
	</div>
	<?php
}

function BuildAvgAgeCard($game){
	$avg = GetAverageAgePlayed($game->_id);
	?>
		<div class="col s12 m4 analyze-right-card">
			<div class="row">
				<div class="col s12 analyze-card z-depth-1" style='padding: 0 0 2em !important;' >
					<?php 	if($avg != -1){ ?>
						<div class="analyze-data-element"><?php echo $avg;  ?></div>
						<div class="analyze-data-desc">average age when played</div>
					<?php }else{ ?>
						<div class="analyze-data-element">0</div>
						<div class="analyze-data-desc">people have played</div>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php
}

function BuildBookmarkedCard($game){
	$bookmarked = GetGameBookmarked($game->_id);
	?>
	<div class="col s12 m4 analyze-center-card">
		<div class="row">
			<div class="col s12 analyze-card z-depth-1" style='padding: 0 0 2em !important;' >
				<div class="analyze-data-element"><?php echo $bookmarked; ?></div>
				<div class="analyze-data-desc">user<?php if($bookmarked != 1){ ?>s<?php } ?> bookmarked</div>
			</div>
		</div>
	</div>
	<?php
}

function BuildRelationalDoughnut($tiers, $myxp, $msg){
	$total = $tiers[1] + $tiers[2] + $tiers[3] + $tiers[4] + $tiers[5];
	?>
	<canvas class="DougnutRelational analyze-doughnut-relational" data-t1="<?php echo $tiers[1] ;?>" data-t2="<?php echo $tiers[2] ;?>" data-t3="<?php echo $tiers[3] ;?>" data-t4="<?php echo $tiers[4] ;?>" data-t5="<?php echo $tiers[5]; ?>"></canvas>
	<div class="analyze-doughnut-key">
		<div class="analyze-doughnut-header">
			<?php echo $msg; ?>
		</div>
		<?php if($tiers[1] > 0){ ?>
		<div class="analyze-doughnut-item">
			<div class="analyze-doughnut-block tier1BG"></div>
			<div class="analyze-doughnut-desc <?php if($myxp == 1){ ?> tier1BG" style='color:white;'<?php }else{ ?>" <?php } ?>>Tier 1 - <?php echo round(($tiers[1] / $total) * 100); ?>%</div>
		</div>
		<?php }
		if($tiers[2] > 0){ ?>
		<div class="analyze-doughnut-item">
			<div class="analyze-doughnut-block tier2BG"></div>
			<div class="analyze-doughnut-desc <?php if($myxp == 2){ ?> tier2BG" style='color:white;'<?php }else{ ?>" <?php } ?>>Tier 2 - <?php echo round(($tiers[2] / $total) * 100); ?>%</div>
		</div>
		<?php }
		if($tiers[3] > 0){ ?>
		<div class="analyze-doughnut-item">
			<div class="analyze-doughnut-block tier3BG"></div>
			<div class="analyze-doughnut-desc <?php if($myxp == 3){ ?> tier3BG" style='color:white;'<?php }else{ ?>" <?php } ?>>Tier 3 - <?php echo round(($tiers[3] / $total) * 100); ?>%</div>
		</div>
		<?php }
		if($tiers[4] > 0){ ?>
		<div class="analyze-doughnut-item">
			<div class="analyze-doughnut-block tier4BG"></div>
			<div class="analyze-doughnut-desc <?php if($myxp == 4){ ?> tier4BG" style='color:white;'<?php }else{ ?>" <?php } ?>>Tier 4 - <?php echo round(($tiers[4] / $total) * 100); ?>%</div>
		</div>
		<?php }
		if($tiers[5] > 0){ ?>
		<div class="analyze-doughnut-item">
			<div class="analyze-doughnut-block tier5BG"></div>
			<div class="analyze-doughnut-desc <?php if($myxp == 5){ ?> tier5BG" style='color:white;'<?php }else{ ?>" <?php } ?>>Tier 5 - <?php echo round(($tiers[5] / $total) * 100); ?>%</div>
		</div>
		<?php } ?>
	</div>
	<?php
}
?>
