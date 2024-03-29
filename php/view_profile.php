<?php
function DisplayWeave($userid){
	$conn = GetConnectedToList($_SESSION['logged-in']->_id);
	$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
	$user = GetUser($userid);
	DisplayUserHeader($user, $conn, $mutualconn);
}

function DisplayUserHeader($user, $conn, $mutualconn){ 
	if($user->_weave->_preferredXP > 0)
		$xp = GetExperienceForUserComplete($user->_id, $user->_weave->_preferredXP);
	else
	{
		$gameid = explode("||",$user->_weave->_recentXP);
		$xp = GetExperienceForUserComplete($user->_id, $gameid[1]);
	}

	if($xp->_gameid > 0)
		$highlightedgame = GetGame($xp->_gameid);
	else
	{
		$highlightedgame = GetGame(8640);
		$highlightedgame->_image = "https://www.technobuffalo.com/wp-content/uploads/2013/09/Hyper-Light-Drifter-9.jpg";
	}
	?>
	<div class="row">
		<div class="col s12">
			<div class="fixed-close-modal-btn"><i class="material-icons" style='font-size: 1.2em;vertical-align: sub;'>arrow_forward</i></div>
			<div class="GameHeaderContainer ProfileHeaderContainer">
				<div class="GameHeaderBackground ProfileHeaderContainer" style="    background: -moz-linear-gradient(bottom, rgba(0,0,0,0) 40%, rgba(0,0,0,0.5) 100%, rgba(0,0,0,0.5) 101%), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;
    background: -webkit-gradient(linear, left bottom, left top, color-stop(40%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.5)), color-stop(101%,rgba(0,0,0,0.5))), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;
    background: -webkit-linear-gradient(bottom, rgba(0,0,0,0) 40%,rgba(0,0,0,0.5) 100%,rgba(0,0,0,0.5) 101%), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;
    background: -o-linear-gradient(bottom, rgba(0,0,0,0) 40%,rgba(0,0,0,0.5) 100%,rgba(0,0,0,0.5) 101%), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
				<div class="modal-header" style='top:10px;bottom:5px;overflow: initial;'>
					<?php DisplayLifeBarForUserProfile($user); ?>
					<div class="user-profile-container">
						<div class="user-profile-item-container z-depth-1">
							<div class="user-profile-data"><?php echo number_format($user->_weave->_totalFollowers); ?></div>
							<div class="user-profile-label">Followers</div>
						</div>
						<div class="user-profile-item-container z-depth-1">
							<div class="user-profile-data"><?php echo number_format($user->_weave->_totalXP); ?></div>
							<div class="user-profile-label">Games</div>
						</div>
						<div class="user-profile-item-container z-depth-1">
							<div class="user-profile-data"><?php echo number_format($user->_weave->_totalAgrees); ?></div>
							<div class="user-profile-label">1ups</div>
						</div>
					</div>
					<!--<div class="user-profile-admin-btn-container">	
						<?php if($_SESSION['logged-in']->_security == "Admin"){ ?>
								<div class="btn user-profile-btn user-manage-badge" data-userid='<?php echo $user->_id; ?>'><i class="material-icons left">lock</i> <span>Badge Access</span></div>
								<div class="btn user-profile-btn user-set-title" data-userid='<?php echo $user->_id; ?>'><i class="material-icons left">assignment</i> <span>Change Title</span></div>
								<div class="btn user-profile-btn user-set-role" data-userid='<?php echo $user->_id; ?>'><i class="material-icons left">assignment_ind</i> <span>Change Role</span></div>
								<div class="btn user-profile-btn user-run-weave-cal-btn" data-userid='<?php echo $user->_id; ?>'><i class="material-icons left">cached</i> <span>Run Weave Calc</span></div>
						<?php } ?>
					</div>-->

					<?php if($user->_security == "Journalist"){ ?>
						<div class="col s12 critic-disclaimer">
							<i class="fa fa-exclamation-triangle" style='color:#FF9800'></i> <?php echo DisplayNameReturn($user); ?>'s Profile is curated by Lifebar and is strictly based off their published reviews 
						</div>
					<?php } ?>
				</div>	
			<div  class="GameHeaderActionBar">
						<div class="card-title activator grey-text text-darken-4">
								<div class="game-action-bar-list row" style='' data-gbid='<?php echo $game->_gbid;?>' data-id='<?php echo $game->_id; ?>'>
										<?php if(in_array($user->_id, $conn)){ ?>
											<div class="col">
												<div class="game-action-bar-item user-profile-unfollow-btn" data-id="<?php echo $user->_id; ?>" data-name="<?php echo DisplayNameReturn($user); ?>">
													<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">person_outline</i>
													<span class="game-action-bar-item-title">Unfollow</span>
												</div>
											</div>
										<?php }else if($user->_id != $_SESSION['logged-in']->_id){ ?>
											<div class="col user-profile-follow-highlight">
												<div class="game-action-bar-item user-profile-follow-btn" data-id="<?php echo $user->_id; ?>" data-name="<?php echo DisplayNameReturn($user); ?>">
													<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">person_add</i>
													<span class="game-action-bar-item-title">Follow</span>
												</div>
											</div>
										<?php } ?>
									<!--<div class="col">
										<div class="game-action-bar-item game-action-share" data-gameid="<?php echo $game->_id; ?>">
											<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">share</i>
											<span class="game-action-bar-item-title">Share</span>
										</div>
									</div>-->
									<?php if($_SESSION['logged-in']->_security == "Admin"){ ?>
										<div class="col">
											<div class="game-action-bar-item user-manage-badge" data-userid='<?php echo $user->_id; ?>'>
												<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">lock</i>
												<span class="game-action-bar-item-title">Badge Access</span>
											</div>
										</div>
										<div class="col">
											<div class="game-action-bar-item user-set-title" data-userid='<?php echo $user->_id; ?>'>
												<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">assignment</i>
												<span class="game-action-bar-item-title">Title</span>
											</div>
										</div>
										<div class="col">
											<div class="game-action-bar-item user-set-role" data-userid='<?php echo $user->_id; ?>'>
												<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">assignment_ind</i>
												<span class="game-action-bar-item-title">Role</span>
											</div>
										</div>
										<div class="col">
											<div class="game-action-bar-item user-run-weave-cal-btn" data-userid='<?php echo $user->_id; ?>'>
												<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">cached</i>
												<span class="game-action-bar-item-title">Calc Profile</span>
											</div>
										</div>
										<?php if($user->_security == "Journalist"){ ?>
											<div class="col">
												<div class="game-action-bar-item user-add-small-image-btn" data-userid='<?php echo $user->_id; ?>'>
													<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">cloud_upload</i>
													<span class="game-action-bar-item-title">Upload small</span>
												</div>
											</div>
											<div class="col">
												<div class="game-action-bar-item user-add-large-image-btn" data-userid='<?php echo $user->_id; ?>'>
													<i class="material-icons" style="font-size:1.75em;vertical-align: middle;">cloud_upload</i>
													<span class="game-action-bar-item-title">Upload large</span>
												</div>
											</div>
										<?php } ?>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<div class="profile-tab">
				<div class="profile-tab-header profile-activity profile-tab-header-active" data-tab="profile-activity-tab">Activity</div>
				<div class="profile-tab-header profile-rank" data-tab="profile-ranked-tab">Ranking</div>
			</div>
			<div class="profile-tab-body activity-top-level activity-profile profile-activity-tab" style='position:absolute;top: 378px;' data-id='<?php echo $user->_id; ?>' >
				<?php DisplayMainActivity($user->_id, "My Activity"); ?>
			</div>
			<div class="profile-tab-body rank-profile profile-ranked-tab" style='display:none;'>
				<?php $rankedlist = GetMyRankedList($user->_id, -1, '', ''); 
					if(sizeof($rankedlist) > 0){ 
						ShowProfileRankingList($rankedlist);
					}else{
						echo "User hasn't created a ranked list yet";
					}		
				?>
			</div>	
		</div>
	</div>
<?php
}

function DisplayLifeBarForUserProfile($user){
	$total = $user->_weave->_lifebarXP;
	$currLevel = GetCurrentLevel($total);
	$minmax = GetMinMaxLevel($currLevel);
	$currWidth =  round((($total - $minmax[0]) / ($minmax[1] - $minmax[0])) * 100);
	?>
	<div class="lifebar-container" data-level="<?php echo $currLevel; ?>" data-min="<?php echo $minmax[0]; ?>" data-max="<?php echo $minmax[1]; ?>" data-xp="<?php echo $total; ?>">
        <div class="lifebar-username-profile">
			<span class="card-title activator">
				<span style="font-weight:300;"><?php echo DisplayNameReturn($user); ?></span> 
				<?php if($user->_security != "Journalist"){ ?>
					<div class="lifebar-bar-level-header-profile"><span style='font-weight:bold;'><?php echo $currLevel; ?></span></div>
				<?php } ?>
			</span>
        </div>
		<div class='lifebar-image'>
			<div class="lifebar-avatar-min z-depth-1" style="height:60px;width:60px;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:3;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
				<?php if($user->_badge != ""){ ?><img class="srank-badge-lifebar" style='top: 40px !important;right: -10px !important;' src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
			</div>
	    </div>
    </div>
<?php
}

function DisplayCriticWeave($userid, $user, $conn, $mutualconn){
	$hiddenusername = $user->_first." ".$user->_last;
	?>
	<div id="profileContentContainer" class="row" data-name="<?php echo urlencode($user->_username); ?>">
		<div class="fixed-close-modal-btn"><i class="material-icons" style='font-size: 1.2em;vertical-align: sub;'>arrow_forward</i></div>
		<!-- Lifebar -->
		<div class="col s12 lifebar-top-level">
			<?php 	
				if($user->_weave->_preferredXP > 0)
					$xp = GetExperienceForUserComplete($user->_id, $user->_weave->_preferredXP);
				else{
					$gameid = explode("||",$user->_weave->_recentXP);
					$xp = GetExperienceForUserComplete($user->_id, $gameid[1]);
				}
				$highlightedgame = GetGame($xp->_gameid);
			?>
			<div class="row z-depth-1" style='padding:25px;background: -moz-linear-gradient(top, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.75) 100%, rgba(0,0,0,0.75) 101%), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,rgba(0,0,0,0.2)), color-stop(100%,rgba(0,0,0,0.75)), color-stop(101%,rgba(0,0,0,0.75))), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(0,0,0,0.2) 50%,rgba(0,0,0,0.75) 100%,rgba(0,0,0,0.75) 101%), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(0,0,0,0.2) 50%,rgba(0,0,0,0.75) 100%,rgba(0,0,0,0.75) 101%), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;'>
				<div class="lifebar-card col s12">
					<?php 
						DisplayUserLifeBarRound($user, $conn, $mutualconn, false);
					?>
				</div>
					<!-- Equipped Games -->
					<div class="col s12 m12 l8">
						<div class="row">
							<div class="profile-card profile-highlighted-game-container col s12" style='background:transparent;'>
								<?php 
									DisplaySlot1Game($xp); 
								?>
							</div>
						</div>
					</div>
					<!--
					<div class="col s12 m12 l4">
						<div class="row">
							<div class="profile-card profile-highlighted-game-container-small col s12 z-depth-1">
								<?php
									if($user->_weave->_subpreferredXP1 > 0)
										$xpsub1 = GetExperienceForUserComplete($user->_id, $user->_weave->_subpreferredXP1);
									DisplayEquippedGame($xpsub1); 
								?>
							</div>
							<div class="profile-card profile-highlighted-game-container-small col s12 z-depth-1">
								<?php
									if($user->_weave->_subpreferredXP2 > 0)
										$xpsub2 = GetExperienceForUserComplete($user->_id, $user->_weave->_subpreferredXP2);
									DisplayEquippedGame($xpsub2); 
								?>
							</div>
						</div>
					</div>
					-->
			</div>
		</div>
		<!-- Disclaimer -->
		<div class="col s12 critic-disclaimer">
			<i class="fa fa-exclamation-triangle" style='color:#FF9800'></i> <?php echo $hiddenusername; ?>'s Profile is curated by Lifebar and is strictly based off their published reviews 
		</div>
		<!-- Skills -->
		<div class="col s12 m6 l4 no-right-padding">
			<div class="row">
				<div class="profile-card badge-card-container col s12 z-depth-1" style="height:445px;">
					<div class="badge-card-container-header" style="height:initial;width:100%;">Skills <span class='profile-card-info' title="Skills is based on the genre(s) of the games experienced"><i class="mdi-action-info"></i></span></div>
					<?php DisplayUserSkills($userid); ?>
				</div>
			</div>
		</div>
		
		<!-- Abilities, Knowledge -->
        <div class="col s12 m6 l8 no-right-padding" style="padding-right: 0;">
            <div class="row">
        		<div class="col s12 no-right-padding">
        			<div class="row" style='margin-bottom:0'>
        				<div class="profile-card badge-card-container col s12 z-depth-1 ability-critic-height" style="height:170px;">
        					<div class="badge-card-container-header" style="height:initial;width:100%;">Abilities</div>
        					<?php DisplayAbilitiesCritic($userid); ?>
        				</div>
        			</div>
        		</div>
        		<div class="col s12 no-right-padding">
        			<div class="row">
        				<div class="profile-card badge-card-container col s12 z-depth-1" style="height:260px;">
        					<div class="badge-card-container-header" style="height:initial;width:100%;">Knowledge <span class='profile-card-info' title="Knowledge is based on experiences related to specific gaming franchsies"><i class="mdi-action-info"></i></span></div>
        					<?php $total = DisplayKnowledgeHighlightsCritic($userid); ?>
        					<?php if($total > 6){ ?>
        						<div class="badge-card-container-view-more knowledge-view-more">View More</div>
        					<?php } ?>
        				</div>
        			</div>
        		</div>
            </div>
        </div>
		
		<!-- Checkpoints -->
		<div class="col s12 m12 l3">
			<div class="row">
				<div class="profile-card badge-card-container col s12 z-depth-1" style="height:1073px;">
					<div class="badge-card-container-header" style="height:initial;width:100%;">Checkpoints <span class='profile-card-info ' title="Checkpoints are your most recent experiences"><i class="mdi-action-info"></i></span></div>
					<?php $latestxp = DisplayUserCheckpoints($userid, $conn, $mutualconn, $hiddenusername); ?>
				</div>
			</div>
		</div>
		
		<!-- Best, Developers -->
		<div class="col s12 m12 l9">
			<div class="row" style='margin-bottom: 0;'>
				<div class="profile-card badge-card-container col s12 z-depth-1" style="height:550px;">
					<div class="badge-card-container-header profile-best-title z-depth-1" style="height:initial;">Favorites</div>
					<?php DisplayBestXPForUser($userid, $conn, $mutualconn, $hiddenusername, $latestxp); ?>
					<div class="profile-best-view-more">VIEW MORE</div>
				</div>
				<div class="col s12 m12 l12" style="padding-left:0 !important;padding-right: 0 !important;">
					<div class="row">
						<div class="profile-card badge-card-container col s12 z-depth-1" style='height: 508px;'>
							<div class="badge-card-container-header" style="height:initial;width:100%;margin-bottom:5px;">Influencers <span class='profile-card-info ' title="Influencers are developers that you have given XP. The top ten are the developers with the most XP"><i class="mdi-action-info"></i></span></div>
							<?php DisplayProfileDevelopersTopTen($user); ?>
							<div class="badge-card-container-view-more developer-view-more">View More</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Graph -->
		<div class="col s12 m12 l12">
			<div class="row">
				<div class="profile-card badge-card-container col s12 z-depth-1" style="height:360px;">
					<div class="badge-card-container-header" style="height:initial;margin-bottom: 30px;width:100%">Experience Curve <span class='profile-card-info ' title="The experience curve shows a lifetime of experiences based on the tier that has been given."><i class="mdi-action-info"></i></span></div>
					<?php DisplayProfileTierGraph($user); ?>
				</div>
			</div>
		</div>
		
		<div class="fixed-action-btn" id="user-fab" style="right:3em;">
			<?php if($_SESSION['logged-in']->_realnames == "True" && in_array($userid, $mutualconn)){ $hiddenusername = $user->_first." ".$user->_last; }else{ $hiddenusername = $user->_username; }
				  if($user->_security == "Journalist")
				  	$isCritic = true;
			  	  else
			  	  	$isCritic = false;
				  	
				  ShowWeaveFAB($userid, $conn, $mutualconn, $isCritic, $hiddenusername); ?>
		</div>
	</div>
	<?php
}
	
	
	
function DisplayUserWeave($userid, $user, $conn, $mutualconn){	
	if($_SESSION['logged-in']->_realnames == "True" && in_array($userid, $mutualconn)){ $hiddenusername = $user->_first." ".$user->_last; }else{ $hiddenusername = $user->_username; } ?>
	<div id="profileContentContainer" class="row" data-name="<?php echo urlencode($user->_username); ?>">
		<div class="fixed-close-modal-btn"><i class="material-icons" style='font-size: 1.2em;vertical-align: sub;'>arrow_forward</i></div>
		<!-- Lifebar -->
		<div class="col s12 lifebar-top-level">
			<?php 	
				if($user->_weave->_preferredXP > 0)
					$xp = GetExperienceForUserComplete($user->_id, $user->_weave->_preferredXP);
				else{
					$gameid = explode("||",$user->_weave->_recentXP);
					$xp = GetExperienceForUserComplete($user->_id, $gameid[1]);
				}
				$highlightedgame = GetGame($xp->_gameid);
			?>
			<?php if($highlightedgame->_id > 0){ ?>
			<div class="row z-depth-1" style='padding:25px;background: -moz-linear-gradient(top, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.75) 100%, rgba(0,0,0,0.75) 101%), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,rgba(0,0,0,0.2)), color-stop(100%,rgba(0,0,0,0.75)), color-stop(101%,rgba(0,0,0,0.75))), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(0,0,0,0.2) 50%,rgba(0,0,0,0.75) 100%,rgba(0,0,0,0.75) 101%), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(0,0,0,0.2) 50%,rgba(0,0,0,0.75) 100%,rgba(0,0,0,0.75) 101%), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;'>
			<?php }else{ ?>
			<div class="row z-depth-1 newprofile-highlightedgame-bg">
			<?php } ?>
				<div class="lifebar-card col s12">
					<?php 
						DisplayUserLifeBarRound($user, $conn, $mutualconn, false);
					?>
				</div>
					<!-- Equipped Games -->
					<div class="col s12 m12 l8">
						<div class="row">
							<div class="profile-card profile-highlighted-game-container col s12" style='background:transparent;'>
								<?php 
								if($xp->_id > 0)
									DisplaySlot1Game($xp); 
								else{
									?>
									<div class='newprofile-highlightedgame'>
										Pin up to 3 game experiences here to show off what you would like others to see when they visit your Profile.
										<div class="newprofile-link-discover">Discover Games Now</div>
									</div>
									<?php
								}
								?>
							</div>
						</div>
					</div>
					<?php if($user->_weave->_totalXP > 0){ ?>
						<div class="col s12 m12 l4">
							<div class="row">
								<div class="profile-card profile-highlighted-game-container-small col s12 z-depth-1" <?php if($xp->_id <= 0){ echo "style='opacity:0.4;'"; } ?>>
									<?php
										if($user->_weave->_subpreferredXP1 > 0)
											$xpsub1 = GetExperienceForUserComplete($user->_id, $user->_weave->_subpreferredXP1);
										DisplayEquippedGame($xpsub1); 
									?>
								</div>
								<div class="profile-card profile-highlighted-game-container-small col s12 z-depth-1" <?php if($xp->_id <= 0){ echo "style='opacity:0.4;'"; } ?>>
									<?php
										if($user->_weave->_subpreferredXP2 > 0)
											$xpsub2 = GetExperienceForUserComplete($user->_id, $user->_weave->_subpreferredXP2);
										DisplayEquippedGame($xpsub2); 
									?>
								</div>
							</div>
						</div>
					<?php } ?>
			</div>
		</div>
		
		
		<!-- Collections -->
		<div class="col s12 m12 l7">
			<div class="row">
				<div class="profile-card badge-card-container col s12 z-depth-1" style='height: 445px;'>
					<div class="badge-card-container-header" style="height:initial;width:100%;margin-bottom:5px;">Collections <span class='profile-card-info ' title="Collections are groups of games defined by users"><i class="mdi-action-info"></i></span></div>
					<?php DisplayUserCollections($user); ?>
				</div>
			</div>
		</div>
		
		<!-- Knowledge -->
		<div class="col s12 m12 l5 no-right-padding">
			<div class="row">
				<div class="profile-card badge-card-container col s12 z-depth-1" style="height:445px;">
					<div class="badge-card-container-header" style="height:initial;width:100%;">Knowledge <span class='profile-card-info ' title="Knowledge is based on experiences related to specific gaming franchsies"><i class="mdi-action-info"></i></span></div>
					<?php DisplayKnowledgeHighlights($userid); ?>
					<div class="badge-card-container-view-more knowledge-view-more">View More</div>
				</div>
			</div>
		</div>
		
		<!-- Gear -->
		<div class="col s12">
			<div class="row">
				<div class="profile-card badge-card-container col s12 z-depth-1 gear-container">
					<div class="badge-card-container-header" style="height:initial;width:100%;">Gear <span class='profile-card-info ' title="Gear is based on your played experiences on specific platforms"><i class="mdi-action-info"></i></span></div>
					<?php DisplayGear($userid); ?>
					<div class="badge-card-container-view-more gear-view-more">View More</div>
				</div>
			</div>
		</div>
		
		<!-- Checkpoints, MyLibrary -->
		<div class="col s12 m12 l3">
			<div class="row">
			</div>
			<div class="row">
				<div class="profile-card badge-card-container col s12 z-depth-1" style="height:1215px;">
					<div class="badge-card-container-header" style="height:initial;width:100%;">Checkpoints <span class='profile-card-info ' title="Checkpoints are your most recent experiences"><i class="mdi-action-info"></i></span></div>
					<?php $latestxp = DisplayUserCheckpoints($userid, $conn, $mutualconn, $hiddenusername); ?>
				</div>
			</div>
		</div>
		<!-- Best, Upcoming, Abilities, Skills -->
		<div class="col s12 m12 l9">
			<div class="row" style='margin-bottom: 0;'>
				<div class="profile-card badge-card-container col s12 z-depth-1" style="height:550px;">
					<div class="badge-card-container-header profile-best-title z-depth-1" style="height:initial;">Favorites</div>
					<?php DisplayBestXPForUser($userid, $conn, $mutualconn, $hiddenusername, $latestxp); ?>
					<div class="profile-best-view-more">VIEW MORE</div>
				</div>
				<div class="col s12 m6" style="padding-left: 0 !important;">
					<div class="row">
						<div class="profile-card badge-card-container col s12 z-depth-1" style="height:445px;">
							<div class="badge-card-container-header" style="height:initial;width:100%;">Abilities</div>
							<?php DisplayAbilities($userid); ?>
							<!--<div class="badge-card-container-view-more abilities-view-more">View Details</div>-->
						</div>
					</div>
				</div>
				<div class="col s12 m6" style="padding-right:0 !important;">
					<div class="row">
						<div class="profile-card badge-card-container col s12 z-depth-1" style="height:445px;">
							<div class="badge-card-container-header" style="height:initial;width:100%;">Skills <span class='profile-card-info ' title="Skills is based on the genre(s) of the games experienced"><i class="mdi-action-info"></i></span></div>
							<?php DisplayUserSkills($userid); ?>
						</div>
					</div>
				</div>
				<div class="col s12 m12 l12" style="padding-left:0 !important;padding-right: 0 !important;">
					<div class="row">
						<div class="profile-card badge-card-container col s12 z-depth-1" style="height:508px;">
							<div class="badge-card-container-header" style="height:initial;width:100%;">Upcoming Quests <span class='profile-card-info ' title="Upcoming Quests are games that have been bookmarked but haven't been released yet"><i class="mdi-action-info"></i></span></div>
							<?php DisplayUpcomingQuests($userid); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- PlayWatchBoth -->
		<div class="col s12 m12 l8">
			<div class="row">
				<div class="profile-card badge-card-container col s12 z-depth-1" style="height:360px;">
					<div class="badge-card-container-header" style="height:initial;margin-bottom: 30px;width:100%">Experience Curve <span class='profile-card-info ' title="The experience curve shows a lifetime of experiences based on the tier that has been given."><i class="mdi-action-info"></i></span></div>
					<?php DisplayProfileTierGraph($user); ?>
				</div>
				<div class="profile-card badge-card-container col s12 z-depth-1">
					<div class="badge-card-container-header" style="height:initial;margin-bottom: 30px;width:100%;">Played vs. Watched</div>
					<?php DisplayPlayWatchBoth($user); ?>
				</div>
			</div>
		</div>
		<!-- Developers -->
		<div class="col s12 m12 l4">
			<div class="row">
				<div class="profile-card badge-card-container col s12 z-depth-1" style='height: 525px;'>
					<div class="badge-card-container-header" style="height:initial;width:100%;margin-bottom:5px;">Influencers <span class='profile-card-info ' title="Influencers are developers that you have given XP. The top ten are the developers with the most XP"><i class="mdi-action-info"></i></span></div>
					<?php DisplayProfileDevelopersTopTen($user); ?>
					<div class="badge-card-container-view-more developer-view-more">View More</div>
				</div>
			</div>
		</div>
		

		
		<?php /*
		<div class="col s12"> <hr>TO BE REMOVED OR CHANGED<br><br> </div>
		<!-- TO BE REMOVED OR CHANGED -->
		<!-- User Stats -->
		<div class="col s12 m12 l4">
			<div class="row">
				<div class="profile-card badge-card-container col s12 z-depth-1" style='height: initial;'>
					<?php DisplayXPTierGraph($user); ?>
				</div>
				<div class="profile-card badge-card-container col s12 z-depth-1">
					<?php DisplayPlayWatchBoth($user); ?>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l8">
			<div class="row">
				<div class="profile-card profile-timeline-container col s12 z-depth-1">
				<?php DisplayTimeline($user); ?>
				</div>
			</div>
		</div>
		<div class="col s12 m6 l4" style="padding-right:0 !important;">
			<div class="row">
				<div class="profile-card badge-card-container col s12 z-depth-1" style="height:508px;">
					<div class="badge-card-container-header profile-worst-title z-depth-1" style="height:initial;">Worst</div>
					<?php DisplayWorstXPForUser($userid, $conn, $mutualconn, $hiddenusername, $latestxp); ?>
					<div class="profile-worst-view-more">VIEW MORE</div>
				</div>
			</div>
		</div>
		<!--
		<div class="col s12 m7">
			<?php  
				$finished = GetRecentlyFinishedMilestones($userid);
				$nearly = GetNearlyFinishedMilestones($userid); 
				$started = GetRecentlyStartedMilestones($userid); 
				$countfin = sizeof($finished);
				$countnear = sizeof($nearly);
				$countstart = sizeof($started);
				if($countfin < $countnear && $countfin < $countstart ){
					$showlink = "Done";
				}else if($countnear < $countstart){
					$showlink = "Near";
				}else{
					$showlink = "New";
				}
			?>
			<div class="row">
				<div class="profile-card badge-card-container col s12 z-depth-1">
					<div class="badge-card-container-header">Done</div>
				<?php
					foreach($finished as $badge){
						DisplaySmallMilestone($badge);
					}
					if($showlink == "Done"){ DisplayViewMilestoneLink($userid); }
				?>
				</div>
				<div class="profile-card badge-card-container col s12 z-depth-1">
					<div class="badge-card-container-header">Near</div>
				<?php
					foreach($nearly as $badge){
						DisplaySmallMilestone($badge);
					}
					if($showlink == "Near"){ DisplayViewMilestoneLink($userid); }
				?>
				</div>
				<div class="profile-card badge-card-container col s12 z-depth-1">
					<div class="badge-card-container-header">New</div>
				<?php
					foreach($started as $badge){
						DisplaySmallMilestone($badge);
					}
					if($showlink == "New"){ DisplayViewMilestoneLink($userid); }
				?>
				</div>
				
			</div>
		</div>
		<br>
		-->
		*/ ?>
		
		
		<div class="fixed-action-btn" id="user-fab" style="right:3em;">
			<?php if($_SESSION['logged-in']->_realnames == "True" && in_array($userid, $mutualconn)){ $hiddenusername = $user->_first." ".$user->_last; }else{ $hiddenusername = $user->_username; }
				  if($user->_security == "Journalist")
				  	$isCritic = true;
			  	  else
			  	  	$isCritic = false;
				  	
				  ShowWeaveFAB($userid, $conn, $mutualconn, $isCritic, $hiddenusername); ?>
		</div>
	</div>
	<?php
}

function DisplayUserLifeBarRound($user, $conn, $mutualconn, $light){ 
	$connCounts = GetConnectedCounts($user->_id);
	$lifebar = GetLifeBarSize($user);
	$lifetime = explode("||", $user->_weave->_overallTierTotal);
	$total = $lifetime[0] + $lifetime[1] + $lifetime[2] + $lifetime[3] + $lifetime[4];
	if($total == 0){
		if($lifetime[0] != 0)
			$tier1 = round(($lifetime[0] / $total) * 100);
		if($lifetime[1] != 0)
			$tier2 = round(($lifetime[1] / $total) * 100);
		if($lifetime[2] != 0)
			$tier3 = round(($lifetime[2] / $total) * 100);
		if($lifetime[3] != 0)
			$tier4 = round(($lifetime[3] / $total) * 100);
		if($lifetime[4] != 0)
			$tier5 = round(($lifetime[4] / $total) * 100);
	}else{
		$total = $tier1 = $tier2 = $tier3 = $tier4 = $tier5 = 0;
	}
	?>
	<div class="lifebar-container-circle">
        <div class="lifebar-bar-container-min" style='width: <?php echo $lifebar[0]; ?>;color:white;top: -16px;margin-left: 50px;'>
        	<div class="lifebar-fill-min-circle" data-position="bottom" style='width: <?php if($lifebar[1] > 6){ echo $lifebar[1]; }else{ echo '6'; } ?>%;'></div>
        	<div class="lifebar-dmg-min" data-position="bottom"></div>
	    	<div class='lifebar-1ups-min' <?php if($light){ echo "style='color: rgba(0,0,0,0.5);'"; } ?>>
	    		<?php if($user->_psn != "" || $user->_xbox != "" || $user->_steam != ""){ ?>
			    	<div class='lifebar-usernames'>
			    		 <?php if($user->_psn != ""){ ?>
			    			<div class="lifebar-username-title-min" <?php if($light){ echo "style='color: rgba(0,0,0,0.5);'"; } ?> title="<?php echo $user->_psn; ?>" ><img src='http://lifebar.io/Images/Generic/playstation-badge.png' class='lifebar-username-id-badge'></div>
			    		<?php } ?>
			    		<?php if($user->_xbox != ""){ ?>
			    			<div class="lifebar-username-title-min" <?php if($light){ echo "style='color: rgba(0,0,0,0.5);'"; } ?> title="<?php echo $user->_xbox; ?>"><img src='http://lifebar.io/Images/Generic/xbox-badge.png' class='lifebar-username-id-badge'></div>
			    		<?php } ?>
			    		<?php if($user->_steam != ""){ ?>
			    			<div class="lifebar-username-title-min" <?php if($light){ echo "style='color: rgba(0,0,0,0.5);'"; } ?> title="<?php echo $user->_steam; ?>"><img src='http://lifebar.io/Images/Generic/steam-badge.png' class='lifebar-username-id-badge'></div>
			    		<?php } ?>
			    	</div>
		    	<?php } ?>
    			<div class="lifebar-1up-count"><i class='mdi-action-accessibility'></i> x <?php if($user->_weave->_totalAgrees > 0){ echo $user->_weave->_totalAgrees; }else{ echo "0"; } ?></div>
	    	</div>
        </div>
        <div class="lifebar-username-min" style='top:35px;'>
        	<?php if($user->_security == "Journalist"){ ?>
          		<span class="card-title activator" <?php if($light){ echo "style='color: rgba(0,0,0,0.5);'"; } ?>>
          			<span style="font-weight:500;"><?php echo $user->_first." ".$user->_last; ?></span> 
          			<?php if($user->_website != ''){ ?>
          				<a href='<?php if (preg_match("#https?://#", $user->_website) === 0) { $user->_website = 'http://'.$user->_website;} echo $user->_website; ?>' <?php if($light){ echo "style='color: rgba(0,0,0,0.5);cursor:pointer;text-transform: inherit;'"; }else{ echo "style='cursor:pointer;color:white;text-transform: inherit;'"; } ?> target="_blank"><?php echo $user->_title; ?></a>
      				<?php }else{ ?>
      					<span><?php echo $user->_title; ?></span>
      				<?php } ?>
          		</span>
        	<?php }else if($user->_security == "Authenticated"){ ?>
	          	<span class="card-title activator" <?php if($light){ echo "style='color: rgba(0,0,0,0.5);'"; } ?>>
	          		<span style="font-weight:500;"><?php echo $user->_first." ".$user->_last; ?> </span> 
        			<?php if($user->_website != ''){ ?>
          				<a href='<?php if (preg_match("#https?://#", $user->_website) === 0) { $user->_website = 'http://'.$user->_website;} echo $user->_website; ?>' <?php if($light){ echo "style='color: rgba(0,0,0,0.5);cursor:pointer;text-transform: inherit;'"; }else{ echo "style='cursor:pointer;color:white;text-transform: inherit;'"; } ?> target="_blank"><?php echo $user->_title; ?></a>
      				<?php }else{ ?>
      					<span><?php echo $user->_title; ?></span>
      				<?php } ?>
	  				<span class='authenticated-mark-lifebar mdi-action-done ' title="Verified Account"></span>
	          	</span>
        	<?php }else{ ?>
        		<span class="card-title activator" <?php if($light){ echo "style='color: rgba(0,0,0,0.5);'"; } ?>><span style="font-weight:500;"><?php echo $user->_username; ?></span> <span><?php if(($_SESSION['logged-in']->_realnames == "True" && in_array($user->_id, $mutualconn)) || $_SESSION['logged-in']->_id == $user->_id){ echo $user->_first." ".$user->_last; } ?></span></span>
        	<?php } ?>
        	<?php if($user->_twitter != "" && !$light){ ?>
        		<div style='margin-top: -5px;'>
        			<a class="lifebar-twitter" href='https://twitter.com/<?php echo $user->_twitter; ?>' target="_blank">@<?php echo $user->_twitter; ?></a>
        		</div>
        	<?php } ?>
        </div>
		<div class='lifebar-image'>
			<div class="lifebar-circle-fill"></div>
			<!--<div class="lifebar-circle-container"></div>-->
			<div class="lifebar-avatar-min z-depth-1" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:3;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
				<?php if($user->_badge != ""){ ?><img class="srank-badge-lifebar" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
			</div>
	    </div>
    	<div class="lifebar-dots-min" style='top: 10px;'>
    		<?php $bigdots = $lifebar[2];
    			  $littledots = $lifebar[3];
    			  $giantdots = $lifebar[4];
      			while($giantdots > 0){ $founddots = true; ?>
    				<div class="lifebar-giantdot-min" title="500 XP"></div>
				<?php
					$giantdots--;
    			}
    			while($bigdots > 0){ $founddots = true; ?>
    				<div class="lifebar-bigdot-min" title="100 XP"></div>
				<?php
					$bigdots--;
    			}
    			$firstlittle = true;
    			while($littledots > 0){ $founddots = true; ?>
    				<div class="lifebar-littledot-min" <?php if($firstlittle){ echo "style=''"; $firstlittle =false; } ?> title="25 XP"></div>
				<?php
					$littledots--;
    			}
    			?>
    			<div class="lifebar-dots-xp-min" style='<?php if(!$founddots){ echo "margin-left:0;"; } ?><?php if($light){ echo "color: rgba(0,0,0,0.5);"; } ?>'><?php if($user->_weave->_totalXP > 0){ echo $user->_weave->_totalXP; }else{ echo "0"; } ?><span style='font-weight:300'>XP</span></div>
    	</div>
    </div>
<?php
}

function DisplaySlot1Game($xp){
	$highlightedgame = $xp->_game; 
	if(isset($xp->_game)){ ?>
		<div class="profile-highlighted-game waves-effect waves-block waves-light" data-gbid="<?php echo $highlightedgame->_gbid; ?>" style="background:transparent;"></div>	
		<div class="profile-highlighted-game-quote">
	      		<?php if($xp->_link != '' && $xp->_authenticxp == "No"){ ?>
						<div class="profile-highlighted-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1">
					      	<div class="profile-highlighted-game-tier" title="Tier <?php echo $xp->_tier; ?> - Curated Review">
			   					<i class="mdi-editor-format-quote"></i>
			   				</div>
		   				</div>
		   			<?php }else{
		   					DisplayProfileTierIcon($xp, true, true);
	   					  } ?>
			<?php echo $xp->_quote; ?>
	    	<?php if( $xp->_authenticxp == "Yes"){ ?> 
	      		<div class='authenticated-mark-lifebar-slot1 mdi-action-done ' title="Verified Account"></div>
	  		<?php } ?>
	  		<?php if( $xp->_link != ""){ ?> 
	      		<a href='<?php echo $xp->_link; ?>' target='_blank' onclick="var event = arguments[0] || window.event; event.stopPropagation();" ><div class="btn-flat waves-effect readBtnProfile">READ</div></a>
	  		<?php } ?>
		</div>
		<div class="profile-highlighted-game-name">
			<?php echo $highlightedgame->_title; ?>
		</div>
	<?php }
}

function DisplayEquippedGame($xp){
	$highlightedgame = $xp->_game; 
	
	if(isset($xp->_game)){ ?>
		<div class="profile-highlighted-game waves-effect waves-block waves-light" data-gbid="<?php echo $highlightedgame->_gbid; ?>" style="background: -moz-linear-gradient(top, rgba(0,0,0,0) 20%, rgba(0,0,0,0.75) 100%, rgba(0,0,0,0.75) 101%), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(20%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.75)), color-stop(101%,rgba(0,0,0,0.75))), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(0,0,0,0) 20%,rgba(0,0,0,0.75) 100%,rgba(0,0,0,0.75) 101%), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(0,0,0,0) 20%,rgba(0,0,0,0.75) 100%,rgba(0,0,0,0.75) 101%), url(<?php echo $highlightedgame->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>	
		<div class="profile-highlighted-game-quote">
		      		<?php if($xp->_link != ''  && $xp->_authenticxp == "No"){ ?>
  					<div class="profile-highlighted-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1">
		      			<div class="profile-highlighted-game-tier" title="Tier <?php echo $xp->_tier; ?> - Curated Review">
		   					<i class="mdi-editor-format-quote"></i>
   				      	</div>
	      			</div>
		   			<?php }else{
	   						DisplayProfileTierIcon($xp, false, true);
      					  } ?>
			<?php echo $xp->_quote; ?>
	    	<?php if( $xp->_authenticxp == "Yes"){ ?> 
	      		<div class='authenticated-mark mdi-action-done ' title="Verified Account"></div>
	  		<?php } ?>
		</div>
		<div class="profile-highlighted-game-name">
			<?php echo $highlightedgame->_title; ?>
		</div>
	<?php }else{ ?>
		<div class="profile-highlighted-game-empty"></div>
	<?php
	}
}

function DisplayXPTierGraph($user){
	?>
	<div class="badge-card-container-header" style="height:initial;">Experience by Tier</div>
	<?php 
	$newuser = false;
	if(sizeof($total) == 0){
		$tempuser = GetUser(7);
		$lifetime = explode("||", $tempuser->_weave->_overallTierTotal);
		$total = $lifetime[0] + $lifetime[1] + $lifetime[2] + $lifetime[3] + $lifetime[4];
		$newuser = true;
	}else{
		$lifetime = explode("||", $user->_weave->_overallTierTotal);
		$total = $lifetime[0] + $lifetime[1] + $lifetime[2] + $lifetime[3] + $lifetime[4];
	}
	
	if($newuser){
		?>
		<div class="newprofile-overlay-container">
			<div class="newprofile-header">Experience by Tier</div>
			<div class="newprofile-skills">
				See what your overall experience with video games looks like. Is it mostly positive?
			</div>
		</div>
		<?php
	}
	
	?>
	<div class="profile-graphtiers">
    	<canvas class="GraphTierPieChart" style='margin:1.5em 0px 1em;' data-t1="<?php echo $lifetime[0] ;?>" data-t2="<?php echo $lifetime[1] ;?>" data-t3="<?php echo $lifetime[2] ;?>" data-t4="<?php echo $lifetime[3] ;?>" data-t5="<?php echo $lifetime[4]; ?>"></canvas>
		<div class="profile-graphtiers-key">
			<div class="keyitem"><div class='keybox' style='background-color:#0A67A3;'></div> Tier 1 - <b><?php echo round(($lifetime[0] / $total) * 100); ?>%</b></div>
			<div class="keyitem"><div class='keybox' style='background-color:#00B25C;'></div> Tier 2 - <b><?php echo round(($lifetime[1] / $total) * 100); ?>%</b></div>
			<div class="keyitem"><div class='keybox' style='background-color:#FF8E00;'></div> Tier 3 - <b><?php echo round(($lifetime[2] / $total) * 100); ?>%</b></div>
			<div class="keyitem"><div class='keybox' style='background-color:#FF4100;'></div> Tier 4 - <b><?php echo round(($lifetime[3] / $total) * 100); ?>%</b></div>
			<div class="keyitem"><div class='keybox' style='background-color:#DB0058;'></div> Tier 5 - <b><?php echo round(($lifetime[4] / $total) * 100); ?>%</b></div>
		</div>
	</div>
	<?php
}

function DisplayPlayWatchBoth($user){
	?>
	<div class="profile-playwatch-container">
		<?php 
			$played = $user->_weave->_percentagePlayed;
			$watched = $user->_weave->_percentageWatched;
			$both = $user->_weave->_percentageBoth;
			$total = $user->_weave->_totalXP;
			
			$newuser = false;
			if(sizeof($total) == 0){
				$tempuser = GetUser(7);
				$played = $tempuser->_weave->_percentagePlayed;
				$watched = $tempuser->_weave->_percentageWatched;
				$both = $tempuser->_weave->_percentageBoth;
				$total = $tempuser->_weave->_totalXP;
				$newuser = true;
			}
			
			
			if($total > 0){
	    		$percplayed = floor((($played - $both) / $total) * 100);
	    		$percwatched = floor((($watched - $both) / $total) * 100);
	    		$percboth = floor((($both) / $total) * 100);
			}
			
			if($newuser){
				?>
				<div class="newprofile-overlay-container">
					<div class="newprofile-header">Played vs. Watched</div>
					<div class="newprofile-skills" style='margin-top: -15px;'>
						Do you watch more games played by others or play them yourself?
					</div>
				</div>
				<?php
			}
		?>
		<?php if($percplayed > 0){ ?>
		<div class="userprofile-graph-played" style="width:<?php echo $percplayed-0.333;?>%;display:inline-block;float:left;" title="<?php echo "Only played ".($played-$both)." games"; ?>">
			<div class="z-depth-1" style='background-color:#71277D;width:100%;display:block;height: 20px;border-radius: 7px 0 0 7px;margin-bottom: 7px;'></div>
			<div class="profile-playwatch-label">Played <?php echo $percplayed; ?>%</div>
		</div>
		<?php } ?>
		<?php if($percboth > 0){ ?>
		<div class="userprofile-graph-both" style="width:<?php echo $percboth-0.333; ?>%;display: inline-block;float: left;" title="<?php echo "Played and watched ".$both." games"; ?>">
			<div class="z-depth-1" style='background-color:#E0214E;width:100%;display:block;height: 20px;margin-bottom: 7px;'></div>
			<div class="profile-playwatch-label">Both <?php echo $percboth; ?>%</div>
		</div>
		<?php } ?>
		<?php if($percwatched > 0){ ?>
		<div class="userprofile-graph-watched" style="width:<?php echo $percwatched-0.333; ?>%;display: inline-block;float: left;" title="<?php echo "Only watched ".($watched-$both)." games"; ?>">
			<div class="z-depth-1" style='background-color:#1E8DBF;width:100%;display:block;height: 20px;border-radius: 0 7px 7px 0;margin-bottom: 7px;'></div>
			<div class="profile-playwatch-label">Watched <?php echo $percwatched; ?>%</div>
		</div>
		<?php } ?>
	</div>
	
	<?php
}

function DisplayProfileTierGraph($user){
	$newuser = false;
	if(sizeof($user->_weave->_totalXP) == 0){
		$tempuser = GetUser(7);
		$tierdata = explode("||", $tempuser->_weave->_overallTierTotal);
		$newuser = true;
	}else{
		$tierdata = explode("||", $user->_weave->_overallTierTotal);
	}
	
	$lifetime[] = $tierdata[0];
	$lifetime[] = $tierdata[1];
	$lifetime[] = $tierdata[2];
	$lifetime[] = $tierdata[3];
	$lifetime[] = $tierdata[4];
	
	if($newuser){
		?>
		<div class="newprofile-overlay-container">
			<div class="newprofile-header">Experience Curve</div>
			<div class="newprofile-skills">
				See what your overall experience with video games looks like. Is it mostly positive?
			</div>
		</div>
		<?php
	}

		?>
	<canvas class="GraphCriticUsers" style='margin:0.5em 20px 1em' data-t1Y="<?php echo $curryear[0] ;?>" data-t2Y="<?php echo $curryear[1] ;?>" data-t3Y="<?php echo $curryear[2] ;?>" data-t4Y="<?php echo $curryear[3] ;?>" data-t5Y="<?php echo $curryear[4] ;?>"  data-t1="<?php echo $lifetime[0] ;?>" data-t2="<?php echo $lifetime[1] ;?>" data-t3="<?php echo $lifetime[2] ;?>" data-t4="<?php echo $lifetime[3] ;?>" data-t5="<?php echo $lifetime[4]; ?>"></canvas>
	<div class="graphTierBadges">Tier 5</div>
	<div class="graphTierBadges" style='left:26%;'>Tier 4</div>
	<div class="graphTierBadges" style='left:50%;'>Tier 3</div>
	<div class="graphTierBadges" style='left:72%;'>Tier 2</div>
	<div class="graphTierBadges" style='left:93%;'>Tier 1</div>
	<?php
}

function DisplayTimeline($user){
	?>
	<div class="badge-card-container-header" style="height:initial;">Timeline</div>
	<?php 
		$lifetime = CalculateLifetimeGraph($user->_id); 
	?>
	<canvas class="GraphLifeTime" style='margin:0.5em 10px 1em' data-played="<?php echo implode(",",$lifetime[0]); ?>" data-watched="<?php echo implode(",",$lifetime[1]); ?>" data-birth="<?php echo implode(",", $lifetime[3]); ?>" ></canvas>
	<?php	
}

function ShowWeaveFAB($userid, $conn, $mutualconn, $critic, $username){
	if($_SESSION['logged-in']->_id > 0){ ?>
		<?php if($userid != $_SESSION['logged-in']->_id){ ?>
	    <a class="btn-floating btn-large <?php if(in_array($userid, $conn)){ echo "user-unfollow-btn blue darken-2"; }else{ echo "user-follow-btn blue darken-2"; } ?> "  data-userid='<?php echo $userid; ?>' data-username='<?php echo $username; ?>'>
	      <?php if(in_array($userid, $conn)){ ?>
	      	<span class="GameHiddenActionLabelBigFab">Unfollow</span>
	      <?php }else{ ?>
    	  	<span class="GameHiddenActionLabelBigFab">Follow</span>
    	  <?php } ?>
	      <i class="large mdi-social-group"></i>
	    </a>
	    <ul>
	      	<?php if($_SESSION['logged-in']->_security == "Admin"){ ?>
	      		<?php if($critic){ ?>
	      			<li><span class="GameHiddenActionLabel">Upload smaller image</span><a class="btn-floating light-green darken-3 user-add-small-image-btn" data-userid='<?php echo $userid; ?>'><i class="mdi-file-cloud-upload"></i></a></li>
	        		<li><span class="GameHiddenActionLabel">Upload larger image</span><a class="btn-floating orange darken-3 user-add-large-image-btn" data-userid='<?php echo $userid; ?>'><i class="mdi-file-cloud-upload"></i></a></li>
	        	<?php } ?>
	        	<li><span class="GameHiddenActionLabel">Manage User Badge Access</span><a class="btn-floating blue user-manage-badge" data-userid='<?php echo $userid; ?>'><i class="fa fa-certificate"></i></a></li>
        		<li><span class="GameHiddenActionLabel">Change users Title</span><a class="btn-floating purple darken-4 user-set-title" data-userid='<?php echo $userid; ?>'><i class="mdi-action-assignment"></i></a></li>
        		<li><span class="GameHiddenActionLabel">Change users Role</span><a class="btn-floating teal user-set-role" data-userid='<?php echo $userid; ?>'><i class="mdi-action-assignment-ind"></i></a></li>
	        	<li><span class="GameHiddenActionLabel">Run Weave Calculator</span><a class="btn-floating red darken-3 user-run-weave-cal-btn" data-userid='<?php echo $userid; ?>'><i class="mdi-action-cached"></i></a></li>
	      	<?php } ?>
  		      	<li><span class="GameHiddenActionLabel">Share profile page</span><a class="btn-floating indigo darken-2 user-share-btn" data-userid='<?php echo $userid; ?>' data-user-name='<?php echo $username; ?>'><i class="mdi-social-share"></i></a></li>
	    <?php }else if($userid == $_SESSION['logged-in']->_id){ ?>
			    <a class="btn-floating btn-large indigo darken-2 user-share-btn"  data-userid='<?php echo $userid; ?>' data-username='<?php echo $username; ?>'>
					<span class="GameHiddenActionLabelBigFab">Share profile page</span>
			      	<i class="large mdi-social-share"></i>
			    </a>
			    <ul>
			      	<?php if($_SESSION['logged-in']->_security == "Admin"){ ?>
			      		<?php if($critic){ ?>
			      			<li><span class="GameHiddenActionLabel">Upload smaller image</span><a class="btn-floating light-green darken-3 user-add-small-image-btn" data-userid='<?php echo $userid; ?>'><i class="mdi-file-cloud-upload"></i></a></li>
			        		<li><span class="GameHiddenActionLabel">Upload larger image</span><a class="btn-floating orange darken-3 user-add-large-image-btn" data-userid='<?php echo $userid; ?>'><i class="mdi-file-cloud-upload"></i></a></li>
			        	<?php } ?>
			        	<li><span class="GameHiddenActionLabel">Manage User Badge Access</span><a class="btn-floating blue user-manage-badge" data-userid='<?php echo $userid; ?>'><i class="fa fa-certificate"></i></a></li>
		        		<li><span class="GameHiddenActionLabel">Change users Title</span><a class="btn-floating purple darken-4 user-set-title" data-userid='<?php echo $userid; ?>'><i class="mdi-action-assignment"></i></a></li>
		        		<li><span class="GameHiddenActionLabel">Change users Role</span><a class="btn-floating teal user-set-role" data-userid='<?php echo $userid; ?>'><i class="mdi-action-assignment-ind"></i></a></li>
			        	<li><span class="GameHiddenActionLabel">Run Weave Calculator</span><a class="btn-floating red darken-3 user-run-weave-cal-btn" data-userid='<?php echo $userid; ?>'><i class="mdi-action-cached"></i></a></li>
	      	<?php } ?>
	    <?php } ?>
	    </ul>
	<?php }
}

function DisplayUserCheckpoints($userid, $conn, $mutualconn, $hiddenusername){
	$latestxp = GetLastestXPForUser($userid);
	$newuser = false;
	if(sizeof($latestxp) == 0){
		$latestxp = GetLastestXPForUser(7);
		$newuser = true;
	}
	
	if($newuser){
		?>
		<div class="newprofile-overlay-container">
			<div class="newprofile-header">Checkpoints</div>
			<div class="newprofile-skills">
				Games you have experienced will be documented in chronological order so you can revist them at anytime.
				<div class="newprofile-skills-list">
					<div class="newprofile-sub-header">Latest Experiences:</div>
					<ol>
						<?php $games = GetTrendingGamesCategory(); 
						foreach($games as $game){ ?>
							<li class="newprofile-best-item" data-id="<?php echo $game->_gbid; ?>"><?php echo $game->_title;?></li>
						<?php } ?>
					</ol>
				</div>
			</div>
		</div>
		<?php
	}
	
	if(sizeof($latestxp) > 0){
		foreach($latestxp as $xp){
			if($newuser)
				$exp = GetExperienceForUserByGame(7, $xp->_gameid);
			else
				$exp = GetExperienceForUserByGame($userid, $xp->_gameid);
				
			if(sizeof($exp->_playedxp) > 0)
				$played = true;
			else
				$played = false;
			if(sizeof($exp->_watchedxp) > 0)
				$watched = true;
			else
				$watched = false;
				
				$hiddenusername = '';
				if($user->_security == "Journalist"  || $user->_security == "Authenticated")
					 $hiddenusername = $user->_first." ".$user->_last;
				else if($_SESSION['logged-in']->_realnames == "True" && in_array($user->_id, $conn))
					$hiddenusername = $user->_first." ".$user->_last; 
				else
					$hiddenusername = $user->_username;	
			
			DisplayCheckpoint($exp->_game, $exp, false, true, $exp->_authenticxp);
		}
	}
	return $latestxp;
}

function DisplayBestXPForUser($userid, $conn, $mutualconn, $hiddenusername, $latestxp){
	$bestxp = GetBestXPForUser($userid, $latestxp);
	$newuser = false;
	if(sizeof($bestxp) == 0){
		$newuser= true;
		$bestxp = GetBestXPForUser(7, $latestxp);
	}
	
	if($newuser){
		?>
		<div class="newprofile-overlay-container">
			<div class="newprofile-header" style='margin: 20px 0px 35px 47px;'>Favorites</div>
			<div class="newprofile-skills"> 
				<div class="newprofile-leftside">
				All of your favorite experiences will be showcased for others to get a better idea of what you like.
				</div>
				<div class="newprofile-skills-list newprofile-rightside">
					<div class="newprofile-sub-header" style='margin-top:0;'>Favorite Experiences:</div>
					<ol>
						<li class="newprofile-best-item" data-id="1539"><?php echo "Half-life 2";?></li>
						<li class="newprofile-best-item" data-id="36989"><?php echo "The Last of Us";?></li>
						<li class="newprofile-best-item" data-id="13053"><?php echo "Final Fantasy VII";?></li>
						<li class="newprofile-best-item" data-id="32317"><?php echo "BioShock Infinite";?></li>
						<li class="newprofile-best-item" data-id="21590"><?php echo "Mass Effect 2";?></li>
						<li class="newprofile-best-item" data-id="22420"><?php echo "Uncharted 2: Among Thieves";?></li>
					</ol>
				</div>
			</div>
		</div>
		<?php
	}
	$first = true;
	foreach($bestxp as $exp){
		if(sizeof($exp->_playedxp) > 0)
			$played = true;
		else
			$played = false;
		if(sizeof($exp->_watchedxp) > 0)
			$watched = true;
		else
			$watched = false;

			$hiddenusername = '';
			if($user->_security == "Journalist"  || $user->_security == "Authenticated")
				 $hiddenusername = $user->_first." ".$user->_last;
			else
				$hiddenusername = $user->_username;	
			
		if($first){ ?>
			<div class="profile-best-game waves-effect waves-block waves-light" data-gbid="<?php echo $exp->_game->_gbid; ?>" style="background: -moz-linear-gradient(top, rgba(0,0,0,0) 40%, rgba(0,0,0,0.5) 100%, rgba(0,0,0,0.5) 101%), url(<?php echo $exp->_game->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.5)), color-stop(101%,rgba(0,0,0,0.5))), url(<?php echo $exp->_game->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.5) 100%,rgba(0,0,0,0.5) 101%), url(<?php echo $exp->_game->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.5) 100%,rgba(0,0,0,0.5) 101%), url(<?php echo $exp->_game->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>	
			<div class="profile-best-game-name">
				<?php echo $exp->_game->_title; ?>
			</div>
			<div class="profile-best-game-quote">
	      		<?php if($exp->_link != ''  && $exp->_authenticxp == "No"){ ?>
						<div class="profile-best-game-tier-container tier<?php echo $exp->_tier; ?>BG z-depth-1">
					      	<div class="profile-highlighted-game-tier" title='Tier <?php echo $exp->_tier; ?> - Curated Review' style='margin-left: 3px;'>
					   				<i class="mdi-editor-format-quote"></i>
			   				</div>
		   				</div>
		   			<?php }else{
		   					DisplayProfileTierIcon($exp, false, false);
	   					  } ?>
				<?php echo $exp->_quote; ?>
		    	<?php if( $exp->_authenticxp == "Yes"){ ?> 
		      		<div class='authenticated-mark-lifebar mdi-action-done ' title="Verified Account" style='font-size: 0.7em;'></div>
		  		<?php } ?>
  		  		<?php if( $exp->_link != ""){ ?> 
	      			<a href='<?php echo $exp->_link; ?>' target='_blank' onclick="var event = arguments[0] || window.event; event.stopPropagation();" ><div class="btn-flat waves-effect readBtnProfile" style='margin-bottom: -2px;'>READ</div></a>
	  			<?php } ?>
			</div> 
			<?php $first = false; 
		}else{ ?>
			<div class="profile-best-left-card-container">
				<?php DisplayUserProfileGameCard($exp->_game, $exp, true, false); ?>
			</div>
			<?php
		}
	}
}

function DisplayWorstXPForUser($userid, $conn, $mutualconn, $hiddenusername, $latestxp){
	$worstxp = GetWorstXPForUser($userid, $latestxp);
	if(sizeof($worstxp) > 0){
		$first = true;
		foreach($worstxp as $exp){
			if(sizeof($exp->_playedxp) > 0)
				$played = true;
			else
				$played = false;
			if(sizeof($exp->_watchedxp) > 0)
				$watched = true;
			else
				$watched = false;
				
				$hiddenusername = '';
				if($user->_security == "Journalist"  || $user->_security == "Authenticated")
					 $hiddenusername = $user->_first." ".$user->_last;
				else if($_SESSION['logged-in']->_realnames == "True" && in_array($user->_id, $conn))
					$hiddenusername = $user->_first." ".$user->_last; 
				else
					$hiddenusername = $user->_username;	
			if($first){ ?>
				<div class="profile-best-game waves-effect waves-block waves-light" data-gbid="<?php echo $exp->_game->_gbid; ?>" style="background: -moz-linear-gradient(top, rgba(0,0,0,0) 40%, rgba(0,0,0,0.5) 100%, rgba(0,0,0,0.5) 101%), url(<?php echo $exp->_game->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.5)), color-stop(101%,rgba(0,0,0,0.5))), url(<?php echo $exp->_game->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.5) 100%,rgba(0,0,0,0.5) 101%), url(<?php echo $exp->_game->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.5) 100%,rgba(0,0,0,0.5) 101%), url(<?php echo $exp->_game->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>	
				<div class="profile-worst-game-name">
					<?php echo $exp->_game->_title; ?>
				</div>
				<div class="profile-worst-game-quote">
					<div class="profile-highlighted-game-tier-container tier<?php echo $exp->_tier; ?>BG z-depth-1">
				      	<div class="profile-highlighted-game-tier" style='margin-left: 3px;'>
				      		<?php if($exp->_link != ''){ ?>
				   				<i class="mdi-editor-format-quote"></i>
				   			<?php }else if(sizeof($exp->_playedxp) > 0){ ?>
				        			<i class="mdi-av-games"></i>
				      		<?php }else if(sizeof($exp->_watchedxp) > 0){ ?>
				      			<i class="mdi-action-visibility"></i>
				      		<?php } ?>
				      	</div>
			      	</div>
					"<?php echo $exp->_quote; ?>"
				</div> 
				<?php $first = false; 
			}
		}
	}
}

 function DisplayCheckpoint($game, $xp, $depth, $responsive, $authenticxp){ ?>
 	<div class="col s12 checkpoint-container waves-effect waves-block" data-gbid='<?php echo $game->_gbid; ?>'>
   		<?php DisplayProfileCheckPointTierIcon($xp); ?>
		<div class="checkpoint-name" >
			<div><?php echo $game->_title; ?></div><div class="checkpoint-name-sub"><?php if($game->_year != 0){ echo $game->_year." - "; } ?><?php echo $game->_developer; ?></div>
		</div>
		<div class="checkpoint-quote">
			<?php echo $xp->_quote; ?>
	    	<?php if( $authenticxp == "Yes"){ ?> 
	      		<div class='authenticated-mark mdi-action-done ' title="Verified Account"></div>
	  		<?php } ?>
	  		<?php if( $xp->_link != ""){ ?> 
  				<a href='<?php echo $xp->_link; ?>' target='_blank' onclick="var event = arguments[0] || window.event; event.stopPropagation();" ><div class="btn-flat waves-effect readBtnCheckpoint">READ</div></a>
  			<?php } ?>
		</div>
	</div>
 <?php }

function DisplayUserProfileGameCard($game, $xp, $depth, $responsive){ ?>
	<div class="col s12 <?php if($responsive){ echo 'm6 l12'; } ?>" <?php if(!$responsive){ echo "style='float:initial'"; } ?> >
	      <div class="card userprofile-discover-card" <?php if($depth =="" || $depth == false){ ?> style="box-shadow:initial;webkit-box-shadow:initial;"<?php } ?> data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
	        <div class="userprofile-game-card-image waves-effect waves-tier<?php echo $tier; ?> waves-block" style="width:100%;background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
	        </div>
	        <div class="card-content tier<?php echo $xp->_tier; ?>BG">
	          <?php DisplayGameCardTierIcon($xp); ?>
	          <span class="card-title activator" style='color:white;'><div class="nav-game-title"><?php echo $game->_title; ?></div> <!--<i class="mdi-navigation-more-vert right" style='position: absolute;font-size: 1.5em;right: 0.25em;top: 15px;z-index:2; text-shadow: 1px 1px 5px black;color:white;'></i>--></span>
	        </div>
	        <div class="card-reveal tier<?php echo $xp->_tier; ?>BG" style="width:100%;color:white;">
	        </div>
	      </div>
      </div>
<?php } 

function DisplayUpcomingQuests($userid){
	$xps = GetAnticipatedGames($userid, 4);
	$newuser = false;
	if(sizeof($xps) == 0 ){
		$xps = GetAnticipatedGames(7, 4);
		$newuser = true;
	}
	
		
	if($newuser){
		?>
		<div class="newprofile-overlay-container">
			<div class="newprofile-header">Upcoming Quests</div>
			<div class="newprofile-skills">
				<div class="newprofile-leftside">
					As you bookmark games, a running list of upcoming releases will be generated to let you know what is on the horizon. Don't miss out on any of the games you are interested in again!
				</div>
				<div class="newprofile-skills-list newprofile-rightside">
					<div class="newprofile-sub-header" style='margin-top:0;'>Popular bookmarked games:</div>
					<ol>
						<?php foreach($xps as $xp){ ?>
							<li class="newprofile-best-item" data-id="<?php echo $xp->_game->_gbid; ?>"><?php echo $xp->_game->_title;?></li>
						<?php } ?>
					</ol>
				</div>
			</div>
		</div>
		<?php
	}
	
	foreach($xps as $xp){ 
	  	$game = $xp->_game; 
	  
  		$today = new DateTime(date("Y-m-d"));
		$release = new DateTime($game->_released);
		$interval = $today->diff($release);
		$diff = $interval->format("%r%a");
		if($interval->y > 0){
			if($interval->y > 1)
				$coming =  $interval->y." years";
			else
				$coming =  $interval->y." year"; 
		}else if($interval->m > 0){
			if($interval->m > 1)
				$coming = $interval->m." months";
			else
				$coming = $interval->m." month";
		}else if($diff > 0){
			if($interval->d > 1)
				$coming = $interval->d." days";
			else
				$coming = $interval->d." day";
		}
		  ?>
		  <div class="col s12 m12 l3 calendar-card"  data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
		    <div class="calendar-image waves-effect waves-block" style="background:url(<?php echo $game->_imagesmall; ?>) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;">
		    	<div class="calendar-release"><?php echo date("F jS",strtotime($game->_released)); ?></div>
		    	<div class="feed-card-level-game_title feed-activity-game-link feed-bookmark-title" data-gbid="<?php echo $game->_gbid; ?>">
		    		<?php echo $game->_title; ?>
	    			<div class="calendar-countdown z-depth-1"><?php echo $coming; ?></div>
	    		</div>
		    </div>
		  </div>
	<?php
	}
} 

function DisplayEquipXP($gameid, $showtitle){
	$user = GetUser($_SESSION['logged-in']->_id);
	$equip1 = $user->_weave->_preferredXP;
	$equip2 = $user->_weave->_subpreferredXP1;
	$equip3 = $user->_weave->_subpreferredXP2;
	
	$game1 = GetGame($equip1);
	$game2 = GetGame($equip2);
	$game3 = GetGame($equip3);
	$newgame = GetGame($gameid);
	?>
	<div class="equip-xp-container" data-newgame="<?php echo $gameid; ?>" data-newgame-image="<?php echo $newgame->_imagesmall; ?>">
		<div class="equip-xp-header">Pin <?php if($showtitle){ echo $newgame->_title; }else{ echo "your XP"; } ?></div>
		<div class="equip-xp-subheader">Show off the games you are currently playing or want to highlight when people visit your Profile.</div>
		<div class="equip-xp-games">
			<div class="equip-xp-game" data-slot="1" data-previous="<?php if($gameid != $equip1){ echo $game1->_id; } ?>">
				<?php if(isset($game1->_imagesmall)){?>
					<div class="equip-xp-game-image" data-previous="<?php if($gameid != $equip1){ echo $game1->_imagesmall; } ?>" style="background:url(<?php echo $game1->_imagesmall; ?>) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;">
						<div class="equip-slot">Slot 1</div>
						<div class="equip-xp-game-title" data-previous="<?php if($gameid != $equip1){ echo $game1->_title; } ?>"><?php echo $game1->_title; ?></div>
					</div>
				<?php }else{ ?>
					<div class="equip-xp-game-empty-image">
						<div class="equip-slot">Slot 1</div>
						<div class="equip-xp-game-title" style='display:none;'></div>
					</div>
				<?php }?>
				
				<?php if($gameid == $equip1){ ?>
					<div class="equip-xp-game-btn btn">Un-pin</div>
				<?php }else{ ?>
					<div class="equip-xp-game-btn btn">Pin</div>
				<?php } ?>
			</div>
			<div class="equip-xp-game" data-slot="2" data-previous="<?php if($gameid != $equip2){ echo $game2->_id; } ?>">
				<?php if(isset($game2->_imagesmall)){?>
					<div class="equip-xp-game-image z-depth-1" data-previous="<?php if($gameid != $equip2){ echo $game2->_imagesmall; } ?>" style="background:url(<?php echo $game2->_imagesmall; ?>) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;">
						<div class="equip-slot">Slot 2</div>
						<div class="equip-xp-game-title" data-previous="<?php if($gameid != $equip2){ echo $game2->_title; } ?>"><?php echo $game2->_title; ?></div>
					</div>
				<?php }else{ ?>
					<div class="equip-xp-game-empty-image">
						<div class="equip-slot">Slot 2</div>
						<div class="equip-xp-game-title" style='display:none;'></div>
					</div>
				<?php }?>
				
				<?php if($gameid == $equip2){ ?>
					<div class="equip-xp-game-btn btn">Un-pin</div>
				<?php }else{ ?>
					<div class="equip-xp-game-btn btn">Pin</div>
				<?php } ?>
			</div>
			<div class="equip-xp-game" data-slot="3" data-previous="<?php if($gameid != $equip3){ echo $game3->_id; } ?>">
				
				<?php if(isset($game3->_imagesmall)){?>
					<div class="equip-xp-game-image z-depth-1" data-previous="<?php if($gameid != $equip3){ echo $game3->_imagesmall; } ?>" style="background:url(<?php echo $game3->_imagesmall; ?>) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;">
						<div class="equip-slot">Slot 3</div>
						<div class="equip-xp-game-title" data-previous="<?php if($gameid != $equip3){ echo $game3->_title; } ?>"><?php echo $game3->_title; ?></div>
					</div>
				<?php }else{ ?>
					<div class="equip-xp-game-empty-image">
						<div class="equip-slot">Slot 3</div>
						<div class="equip-xp-game-title" style='display:none;'></div>
					</div>
				<?php }?>
				
				<?php if($gameid == $equip3){ ?>
					<div class="equip-xp-game-btn btn">Un-pin</div>
				<?php }else{ ?>
					<div class="equip-xp-game-btn btn">Pin</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
}

function DisplayUserSkills($userid){
	$skills = GetUserSkills($userid);
	$newuser = false;
	if(sizeof($skills) < 5){
		$skills = GetUserSkills(7);
		$newuser = true;
	}
	ksort($skills);
	foreach ($skills as $skill){
		$labels[] = $skill[0];
		$values[] = $skill[1];
	}
	if($newuser){
		?>
		<div class="newprofile-overlay-container">
			<div class="newprofile-header">Skills</div>
			<div class="newprofile-skills">
				Add your experiences and see what genres might influence you the most
				<div class="newprofile-skills-list">
					<div class="newprofile-sub-header">Popular Genres:</div>
					<ol>
						<li class="newprofile-skills-item" data-id="First-Person Shooter"><?php echo "First-Person Shooter";?></li>
						<li class="newprofile-skills-item" data-id="Action"><?php echo "Action";?></li>
						<li class="newprofile-skills-item" data-id="Adventure"><?php echo "Adventure";?></li>
						<li class="newprofile-skills-item" data-id="Sports"><?php echo "Sports";?></li>
						<li class="newprofile-skills-item" data-id="Simulation"><?php echo "Simulation";?></li>
					</ol>
				</div>
			</div>
		</div>
		<?php
	}
	?>
	<canvas class="GraphSkills" style='margin:0.5em 10px 1em' data-labels="<?php echo implode(",",$labels); ?>" data-values="<?php echo implode(",",$values); ?>" ></canvas>
	<?php
}

function DisplayAbilities($userid){
	$abilities = GetAbilities($userid);
	if($abilities[1][2] == 0 && $abilities[2][2] == 0 && $abilities[3][2] == 0){
		?>
		<div class="newprofile-overlay-container">
			<div class="newprofile-header">Abilities</div>
			<div class="ability-container ability-spy waves-effect waves-block" style='cursor:pointer; margin: 15px 5%;padding: 10px 2.5% 20px;background-color: white;'>
				<div class="ability-title">Spy</div>
				<div class="ability-level">lvl <?php echo $abilities[0][0];?></div>
				<div class="ability-sub-count"><?php if($abilities[0][2] > 0){ $count = $abilities[0][2]; }else{ $count = 0; } echo $count."/".$abilities[0][1]; ?></div>
				<div class="ability-sub-title" title="The number of people you are currently following">following</div>
				<div class="ability-progress-bar">
					<div class="ability-level<?php echo $abilities[0][0]; ?>" style='width:<?php echo round(($abilities[0][2]/$abilities[0][1]) * 100); ?>%'></div>
				</div>
			</div>
			<div class="newprofile-skills" style='font-size: 1.25em;font-weight: 400;'>
				We have taken the luxury of connecting you with 10 of the top critics, which you can unfollow at anytime.<br><br>As you receive 1ups, gain followers, follow others and bookmark games your abilities will rise.
			</div>
		</div>
		<?php
	}
	?>
	<div class="ability-container ability-spy waves-effect waves-block" style='cursor:pointer;'>
		<div class="ability-title">Spy</div>
		<div class="ability-level">lvl <?php echo $abilities[0][0];?></div>
		<div class="ability-sub-count"><?php if($abilities[0][2] > 0){ $count = $abilities[0][2]; }else{ $count = 0; } echo $count."/".$abilities[0][1]; ?></div>
		<div class="ability-sub-title" title="The number of people you are currently following">following</div>
		<div class="ability-progress-bar">
			<div class="ability-level<?php echo $abilities[0][0]; ?>" style='width:<?php echo round(($abilities[0][2]/$abilities[0][1]) * 100); ?>%'></div>
		</div>
	</div>
	<div class="ability-container ability-charisma waves-effect waves-block" style='cursor:pointer;'>
		<div class="ability-title">Charisma </div>
		<div class="ability-level">lvl <?php echo $abilities[2][0];?></div>
		<div class="ability-sub-count"><?php if($abilities[2][2] > 0){ $count = $abilities[2][2]; }else{ $count = 0; } echo $count."/".$abilities[2][1]; ?></div>
		<div class="ability-sub-title " title="The number of 1ups other people have given you">1ups</div>
		<div class="ability-progress-bar">
			<div class="ability-level<?php echo $abilities[2][0]; ?>" style='width:<?php echo round(($abilities[2][2]/$abilities[2][1]) * 100); ?>%'></div>
		</div>
	</div>
	<div class="ability-container ability-leadership waves-effect waves-block" style='cursor:pointer;'>
		<div class="ability-title">Leadership</div>
		<div class="ability-level">lvl <?php echo $abilities[1][0];?></div>
		<div class="ability-sub-count"><?php if($abilities[1][2] > 0){ $count = $abilities[1][2]; }else{ $count = 0; } echo $count."/".$abilities[1][1]; ?></div>
		<div class="ability-sub-title " title="The number of people who are following you">followers</div>
		<div class="ability-progress-bar">
			<div class="ability-level<?php echo $abilities[1][0]; ?>" style='width:<?php echo round(($abilities[1][2]/$abilities[1][1]) * 100); ?>%'></div>
		</div>
	</div>
	<div class="ability-container ability-tracking waves-effect waves-block" style='cursor:pointer;'>
		<div class="ability-title">Tracking</div>
		<div class="ability-level">lvl <?php echo $abilities[3][0];?></div>
		<div class="ability-sub-count"><?php if($abilities[3][2] > 0){ $count = $abilities[3][2]; }else{ $count = 0; } echo $count."/".$abilities[3][1]; ?></div>
		<div class="ability-sub-title  " title="The number of games that you have bookmarked">bookmarks</div>
		<div class="ability-progress-bar">
			<div class="ability-level<?php echo $abilities[3][0]; ?>" style='width:<?php echo round(($abilities[3][2]/$abilities[3][1]) * 100); ?>%'></div>
		</div>
	</div>
	<?php
}

function DisplayAbilitiesCritic($userid){
	$abilities = GetAbilities($userid);
	?>
	<div class="ability-container ability-container-critic ability-charisma waves-effect waves-block" style='cursor:pointer;'>
		<div class="ability-title">Charisma </div>
		<div class="ability-level">lvl <?php echo $abilities[2][0];?></div>
		<div class="ability-sub-count"><?php if($abilities[2][2] > 0){ $count = $abilities[2][2]; }else{ $count = 0; } echo $count."/".$abilities[2][1]; ?></div>
		<div class="ability-sub-title " title="The number of 1ups other people have given you">1ups</div>
		<div class="ability-progress-bar">
			<div class="ability-level<?php echo $abilities[2][0]; ?>" style='width:<?php echo round(($abilities[2][2]/$abilities[2][1]) * 100); ?>%'></div>
		</div>
	</div>
	<div class="ability-container ability-container-critic ability-leadership waves-effect waves-block" style='cursor:pointer;'>
		<div class="ability-title">Leadership</div>
		<div class="ability-level">lvl <?php echo $abilities[1][0];?></div>
		<div class="ability-sub-count"><?php if($abilities[1][2] > 0){ $count = $abilities[1][2]; }else{ $count = 0; } echo $count."/".$abilities[1][1]; ?></div>
		<div class="ability-sub-title " title="The number of people who are following you">followers</div>
		<div class="ability-progress-bar">
			<div class="ability-level<?php echo $abilities[1][0]; ?>" style='width:<?php echo round(($abilities[1][2]/$abilities[1][1]) * 100); ?>%'></div>
		</div>
	</div>
	<?php
}

function DisplayKnowledgeHighlights($userid){
	$knowledgebase = GetKnowledgeHighlights($userid);
	$newuser = false;
	if(sizeof($knowledgebase) == 0){
		$knowledgebase = GetKnowledgeHighlights(7);
		$newuser = true;
	}
	
	if($newuser){
		?>
		<div class="newprofile-overlay-container">
			<div class="newprofile-header">Knowledge</div>
			<div class="newprofile-skills">
				As you add experiences, your knowledge in that franchise will grow. See if you can experience every game in a franchise!
				<div class="newprofile-skills-list">
					<div class="newprofile-sub-header">Popular Franchises:</div>
					<ol>
						<li class="newprofile-knowledge-item" data-id="Super Mario"><?php echo "Super Mario";?></li>
						<li class="newprofile-knowledge-item" data-id="Call of Duty"><?php echo "Call of Duty";?></li>
						<li class="newprofile-knowledge-item" data-id="Star Wars"><?php echo "Star Wars";?></li>
						<li class="newprofile-knowledge-item" data-id="Final Fantasy"><?php echo "Final Fantasy";?></li>
						<li class="newprofile-knowledge-item" data-id="Halo"><?php echo "Halo";?></li>
					</ol>
				</div>
			</div>
		</div>
		<?php
	}
	
	$i  = 0;
	while($i < 6 && $i <= sizeof($knowledgebase)){
		DisplayKnowledge($knowledgebase[$i], "relative");
		$i++;
	}
}

function DisplayKnowledgeHighlightsCritic($userid){
	$knowledgebase = GetKnowledgeHighlights($userid);
	
	$i  = 0;
	while($i < 6 && $i <= sizeof($knowledgebase)){
		DisplayKnowledge($knowledgebase[$i], "fixed");
		$i++;
	}
	
	return sizeof($knowledgebase);
}

function DisplayKnowledge($knowledge, $size){
	if($knowledge->_progress != ''){
		if($knowledge->_progress->_percentlevel1 < 100){
			$percentage = $knowledge->_progress->_percentlevel1;
			$progress = $knowledge->_progress->_progresslevel1;
			$max = $knowledge->_level1;
		}else if($knowledge->_progress->_percentlevel2 < 100){
			$percentage = $knowledge->_progress->_percentlevel2;
			$progress = $knowledge->_progress->_progresslevel2;
			$max = $knowledge->_level2;
		}else if($knowledge->_progress->_percentlevel3 < 100){
			$percentage = $knowledge->_progress->_percentlevel3;
			$progress = $knowledge->_progress->_progresslevel3;
			$max = $knowledge->_level3;
		}else if($knowledge->_progress->_percentlevel4 < 100){
			$percentage = $knowledge->_progress->_percentlevel4;
			$progress = $knowledge->_progress->_progresslevel4;
			$max = $knowledge->_level4;
		}else if($knowledge->_progress->_percentlevel5 < 100){
			$percentage = $knowledge->_progress->_percentlevel5;
			$progress = $knowledge->_progress->_progresslevel5;
			$max = $knowledge->_level5;
		}
		
		if($knowledge->_level5 > 0){
			$max = $knowledge->_level5;
			$progress = $knowledge->_progress->_progresslevel5;
		}else if($knowledge->_level4 > 0){
			$max = $knowledge->_level4;
			$progress = $knowledge->_progress->_progresslevel4;
		}else if($knowledge->_level3 > 0){
			$max = $knowledge->_level3;
			$progress = $knowledge->_progress->_progresslevel3;
		}else if($knowledge->_level2 > 0){
			$max = $knowledge->_level2;
			$progress = $knowledge->_progress->_progresslevel2;
		}else if($knowledge->_level1 > 0){
			$max = $knowledge->_level1;
			$progress = $knowledge->_progress->_progresslevel1;
		}
		
		$percentage = round(($progress / $max) * 100);
		?>
		<div class="col <?php if($size == "relative"){ echo "s4"; }else{ echo "knowledge-fixed"; } ?>" <?php if($size == "gamedash"){ echo "style='vertical-align:top;float:none;margin-left:auto;margin-right:auto;display:inline-block;'"; } ?> >
			<div class="knowledge-container" <?php if($size == "fixed"){ echo "style='margin-bottom: 28px;'"; } ?> data-progid="<?php echo $knowledge->_progress->_id; ?>" data-id="<?php echo $knowledge->_id; ?>" data-objectid="<?php echo $knowledge->_objectid; ?>">
				<?php if($knowledge->_image == ""){ ?>
					<div class="knowledge-image" style='text-align: center;background-color: orange;padding-top: 5px;margin-bottom: 5px;'><i class="bp-item-image-icon mdi-content-flag"></i>
				<?php }else{ ?>
					<div class="knowledge-image" style="background:url(<?php echo $knowledge->_image; ?>) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;">
				<?php } ?>
					<div class="knowledge-title">
						<?php echo $knowledge->_name; ?>
					</div>
				</div>
				<div class="knowledge-progress-bar">
					<div class="knowledge-progress" style='width:<?php echo $percentage; ?>%'></div>
					<div class="knowledge-progress-label">
						<?php echo $progress."/".$max; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

function DisplayGear($userid){
	?>
	<div class='profile-gear-container'>
		<?php
		$allgear = GetGear($userid);
		
		$newuser = false;
		if(sizeof($allgear) == 0){
			$allgear = GetGear(7);
			$newuser = true;
		}
		
		if($newuser){
			?>
			<div class="newprofile-overlay-container">
				<div class="newprofile-header">Gear</div>
				<div class="newprofile-skills">
					<div class="newprofile-leftside">
						Played experiences for specific platforms will appear here. Show what platforms you have the most experience with.
					</div>
					<div class="newprofile-skills-list newprofile-rightside">
						<div class="newprofile-sub-header" style='margin-top:0;'>Popular Gear:</div>
						<ol>
							<li class="newprofile-gear-item" data-id="Playstation 4"><?php echo "Playstation 4";?></li>
							<li class="newprofile-gear-item" data-id="Xbox One"><?php echo "Xbox One";?></li>
							<li class="newprofile-gear-item" data-id="Wii U"><?php echo "Wii U";?></li>
						</ol>
					</div>
				</div>
			</div>
			<?php
		}
		
		foreach($allgear as $gear){
			if($gear->_progress->_progresslevel1 > 0)
				DisplayGearMilestone($gear);
		} ?>
	</div>
	<?php
}

function DisplaySpyAbility($userid, $abilities, $mutualconn, $conn){
	$user = GetUser($userid);
	$spying = GetConnectedTo($userid);
	$showpreview = true;
	if($abilities == '')
		$abilities = GetAbilities($userid);
	if($mutualconn == ''){
		$conn = GetConnectedToList($_SESSION['logged-in']->_id);
		$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
		$showpreview = false;
	}
	?>
	<div class="ability-container-header">
		<div class="ability-title">Spy</div>
		<div class="ability-level">lvl <?php echo $abilities[0][0];?></div>
		<div class="ability-sub-count"><?php echo $abilities[0][2]."/".$abilities[0][1]; ?></div>
		<div class="ability-sub-title " title="The number of people you are currently following">following</div>
		<div class="ability-progress-bar-dark">
			<div class="ability-level<?php echo $abilities[0][0]; ?>" style='width:<?php echo round(($abilities[0][2]/$abilities[0][1]) * 100); ?>%'></div>
		</div>
	</div>
	<div class="row ability-display-details">
		<div class="badge-card-ability-avatar" style="border-radius:50%;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		<div class="ability-arrow"><i class="mdi-navigation-arrow-forward"></i></div>
		<div class="ability-avatar-box">
			<?php 
			foreach($spying as $spy){
				?>
				<div class="badge-card-ability-avatar " data-id="<?php echo $spy->_id; ?>" title="<?php if($spy->_security == "Journalist"){ echo $spy->_first." ".$spy->_last; }else{ echo $spy->_username; } ?>" style="border-radius:50%;background:url(<?php echo $spy->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
				</div>
			<?php } ?>
		</div>
	</div>
	<?php
}

function DisplayLeadershipAbility($userid, $abilities, $mutualconn, $conn){
	$user = GetUser($userid);
	$leader = GetConnectedToMe($userid); 
	$showpreview = true;
	if($abilities == '')
		$abilities = GetAbilities($userid);
	if($mutualconn == ''){
		$conn = GetConnectedToList($_SESSION['logged-in']->_id);
		$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
		$showpreview = false;
	}
	?>
	<div class="ability-container-header">
		<div class="ability-title">Leadership</div>
		<div class="ability-level">lvl <?php echo $abilities[1][0];?></div>
		<div class="ability-sub-count"><?php echo $abilities[1][2]."/".$abilities[1][1]; ?></div>
		<div class="ability-sub-title" title="The number of people who are following you">followers</div>
		<div class="ability-progress-bar-dark">
			<div class="ability-level<?php echo $abilities[1][0]; ?>" style='width:<?php echo round(($abilities[1][2]/$abilities[1][1]) * 100); ?>%'></div>
		</div>
	</div>
	<div class="row ability-display-details">
		<div class="badge-card-ability-avatar" style="border-radius:50%;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		<div class="ability-arrow"><i class="mdi-navigation-arrow-back"></i></div>
		<div class="ability-avatar-box">
			<?php 
			foreach($leader as $lead){
				?>
				<div class="badge-card-ability-avatar" data-id="<?php echo $lead->_id; ?>" title="<?php if($lead->_security == "Journalist"){ echo $lead->_first." ".$lead->_last; }else{ echo $lead->_username; } ?>" style="border-radius:50%;background:url(<?php echo $lead->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
				</div>
			<?php } ?>
		</div>
	</div>
	<?php
}

function DisplayCharismaAbility($userid, $abilities, $mutualconn, $conn){
	$user = GetUser($userid);
	//$agrees = GetAgreesForUser($userid, 0);
	$showpreview = true;
	if($abilities == '')
		$abilities = GetAbilities($userid);
	if($mutualconn == ''){
		$conn = GetConnectedToList($_SESSION['logged-in']->_id);
		$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
		$showpreview = false;
	}
	?>
	<div class="ability-container-header">
		<div class="ability-title">Charisma </div>
		<div class="ability-level">lvl <?php echo $abilities[2][0];?></div>
		<div class="ability-sub-count"><?php echo $abilities[2][2]."/".$abilities[2][1]; ?></div>
		<div class="ability-sub-title" title="The number of 1ups other people have given you">1ups</div>
		<div class="ability-progress-bar-dark">
			<div class="ability-level<?php echo $abilities[2][0]; ?>" style='width:<?php echo round(($abilities[2][2]/$abilities[2][1]) * 100); ?>%'></div>
		</div>
	</div>
		<div class="row ability-display-details">
			<br>
			COMING SOON
		</div>
	<?php	
}

function DisplayTrackingAbility($userid, $abilities, $mutualconn, $conn){
	$user = GetUser($userid);
	$unreleased = GetAnticipatedGamesAbilities($userid, 500);
	$thisyear = GetAnticipatedGamesThisYear($userid, 500);
	$pastyears = GetAnticipatedGamesInPast($userid, 500);
	if($abilities == '')
		$abilities = GetAbilities($userid);
	if($mutualconn == ''){
		$conn = GetConnectedToList($_SESSION['logged-in']->_id);
		$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
	}
	?>
	<div class="ability-container-header">
		<div class="ability-title">Tracking</div>
		<div class="ability-level">lvl <?php echo $abilities[3][0];?></div>
		<div class="ability-sub-count"><?php echo $abilities[3][2]."/".$abilities[3][1]; ?></div>
		<div class="ability-sub-title" title="The number of games that you have bookmarked">bookmarks</div>
		<div class="ability-progress-bar-dark">
			<div class="ability-level<?php echo $abilities[3][0]; ?>" style='width:<?php echo round(($abilities[3][2]/$abilities[3][1]) * 100); ?>%'></div>
		</div>
	</div>
	<div style='height:100%;overflow-x:hidden;overflow-y:auto;'>
		<?php if(sizeof($unreleased)> 0){ ?>
			<div class="row ability-display-details" style='padding: 6em 2.5% 2em;position:relative;'>
				<div class="col s12 ability-tracking-header">Unreleased</div>
				<?php foreach($unreleased as $unrelease){
					DisplaySmallGameCard($unrelease);	
				}?>
			</div>
			<?php } ?>
			<?php if(sizeof($thisyear)> 0){ ?>
			<div class="row ability-display-details" style='padding: 6em 2.5% 2em;position:relative;'>
				<div class="col s12 ability-tracking-header">This Year</div>
				<?php foreach($thisyear as $year){
					DisplaySmallGameCard($year);	
				}?>
			</div>
			<?php } ?>
			<?php if(sizeof($pastyears)> 0){ ?>
				<div class="row ability-display-details" style='padding: 6em 2.5% 2em;position:relative;'>
					<div class="col s12 ability-tracking-header">Years Past</div>
					<?php foreach($pastyears as $past){
						DisplaySmallGameCard($past);	
					}?>
				</div>
			<?php } ?>
		</div>
	<?php	
}

function DisplayAbilitiesViewMore($userid){
	$user = GetUser($userid);
	$conn = GetConnectedToList($_SESSION['logged-in']->_id);
	$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
	$abilities = GetAbilities($userid);
	
	$name = "";
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $name = $user->_first." ".$user->_last; }else{ $name = $user->_username; }
	DisplayBackButton($name."'s abilities");
	?>
	<div class="row" style='margin-top:4em;text-align:left;'>
		<div class="col s12">
			<div class="row">
				<div class="col s12 profile-card">
					<?php DisplaySpyAbility($userid, $abilities, $mutualconn, $conn); ?>
				</div>
			</div>
			<div class="row">
				<div class="col s12 profile-card">
					<?php	DisplayCharismaAbility($userid, $abilities, $mutualconn, $conn); ?>
				</div>
			</div>
			<div class="row">
				<div class="col s12 profile-card">
					<?php	DisplayLeadershipAbility($userid, $abilities, $mutualconn, $conn); ?>
				</div>
			</div>
			<div class="row">
				<div class="col s12 profile-card">
					<?php	DisplayTrackingAbility($userid, $abilities, $mutualconn, $conn); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function DisplayBackButton($text){
	?>
	<div class="backContainer">
		<div class="backButton waves-effect"><i class="mdi-navigation-arrow-back small" style="color:rgba(0,0,0,0.7);vertical-align:middle;padding: 0 0.5em;"></i> <a class="btn-flat backButtonLabel" style="color:rgba(0,0,0,0.7);margin: 0;padding: 0 2em;" ><?php echo $text; ?></a></div>
	</div>
	<?php
}

function DisplayKnowledgeViewMore($userid){
	$user = GetUser($userid);
	$knowledgehighlights = GetKnowledgeHighlights($userid);
	$knowledgethisyear = GetKnowledgeThisYear($userid);
	$knowledgeyearspast = GetKnowledgeYearsPast($userid);
	$name = "";
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $name = $user->_first." ".$user->_last; }else{ $name = $user->_username; }
	DisplayBackButton($name."'s knowledge");
	?>
	<div class="row" style='margin-top:4em;text-align:left;'>
		<div class="col s12">
			<div class="row">
				<?php if(sizeof($knowledgehighlights)> 0){ ?>
				<div class="row" style='margin-top:2em;'>
					<div class="col s12 ability-tracking-header">Recent</div>
					<?php foreach($knowledgehighlights as $knowledge){
						DisplayKnowledge($knowledge, "fixed");	
					}?>
				</div>
				<?php } ?>
				<?php if(sizeof($knowledgethisyear)> 0){ ?>
				<div class="row" style='margin-top:3em;'>
					<div class="col s12 ability-tracking-header">This Year</div>
					<?php foreach($knowledgethisyear as $knowledge){
						DisplayKnowledge($knowledge, "fixed");	
					}?>
				</div>
				<?php } ?>
				<?php if(sizeof($knowledgeyearspast)> 0){ ?>
					<div class="row" style='margin-top:3em;'>
						<div class="col s12 ability-tracking-header">Years Past</div>
						<?php foreach($knowledgeyearspast as $knowledge){
							DisplayKnowledge($knowledge, "fixed");	
						}?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php	
}

function DisplayKnowledgeDetails($userid, $knowledgeid, $progressid){
	$user = GetUser($user);
	$details = GetKnowledgeDetails($knowledgeid, $userid);
	$knowledge = GetKnowledgeForUser($progressid, $userid);
	if($knowledge->_progress->_percentlevel1 < 100){
		$percentage = $knowledge->_progress->_percentlevel1;
		$progress = $knowledge->_progress->_progresslevel1;
		$max = $knowledge->_level1;
	}else if($knowledge->_progress->_percentlevel2 < 100){
		$percentage = $knowledge->_progress->_percentlevel2;
		$progress = $knowledge->_progress->_progresslevel2;
		$max = $knowledge->_level2;
	}else if($knowledge->_progress->_percentlevel3 < 100){
		$percentage = $knowledge->_progress->_percentlevel3;
		$progress = $knowledge->_progress->_progresslevel3;
		$max = $knowledge->_level3;
	}else if($knowledge->_progress->_percentlevel4 < 100){
		$percentage = $knowledge->_progress->_percentlevel4;
		$progress = $knowledge->_progress->_progresslevel4;
		$max = $knowledge->_level4;
	}else if($knowledge->_progress->_percentlevel5 < 100){
		$percentage = $knowledge->_progress->_percentlevel5;
		$progress = $knowledge->_progress->_progresslevel5;
		$max = $knowledge->_level5;
	}
	
	if($knowledge->_level5 > 0){
		$max = $knowledge->_level5;
		$progress = $knowledge->_progress->_progresslevel5;
	}else if($knowledge->_level4 > 0){
		$max = $knowledge->_level4;
		$progress = $knowledge->_progress->_progresslevel4;
	}else if($knowledge->_level3 > 0){
		$max = $knowledge->_level3;
		$progress = $knowledge->_progress->_progresslevel3;
	}else if($knowledge->_level2 > 0){
		$max = $knowledge->_level2;
		$progress = $knowledge->_progress->_progresslevel2;
	}else if($knowledge->_level1 > 0){
		$max = $knowledge->_level1;
		$progress = $knowledge->_progress->_progresslevel1;
	}
	
	$percentage = round(($progress / $max) * 100);
	?>
	<div class="ability-container-header">
		<div class="ability-title"><?php echo $knowledge->_name; ?> </div>
		<div class="ability-sub-count"><?php echo $progress."/".$max; ?></div>
		<div class="ability-progress-bar-dark">
			<div class="ability-level2" style='width:<?php echo $percentage; ?>%'></div>
		</div>
	</div>
	<div class="row ability-display-details">
	<?php
		foreach($details as $detail){
			DisplaySmallGameCard($detail);	
		}
	?>
	</div>
<?php
}

function DisplayGearViewMore($userid){
	$platforms = GetPlatformMilestones($userid); 
	$user = GetUser($userid);
	$name = "";
	if($user->_security == "Journalist"){ $name = $user->_first." ".$user->_last; }else{ $name = $user->_username; }
	DisplayBackButton($name."'s Gear");
	?>
	<div class="ability-container" style="margin:15px 2.5%;width:95%;padding:0;">
		<div class="ability-title"><?php echo $knowledge->_name; ?> </div>
		<div class="row" style='margin-top:2em;'>
			<?php
				foreach($platforms as $platform){ 
					DisplayGearMilestone($platform);	
				}
			?>
		</div>
	</div>
<?php
}

function DisplayGearDetails($userid, $platform, $progress){
		$milestone = GetPlatformMilestone($userid, $platform);
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
		
		$games = GetPlatformGames($platform, $userid);
		?>
		<div class="ability-container-header">
			<div class="ability-title"><?php echo $milestone->_name; ?> </div>
			<div class="ability-level">lvl <?php echo $currentlevel;?></div>
			<div class="ability-sub-count"><?php echo $progress."/".$threshold; ?></div>
			<div class="ability-progress-bar-dark">
				<div class="ability-level<?php echo $currentlevel; ?>" style='width:<?php echo $percent; ?>%'></div>
			</div>
		</div>
		<div class="row ability-display-details">
		<?php
			foreach($games as $game){
				DisplaySmallGameCard($game);	
			}
		?>
		</div>
	<?php
}

function DisplayBest($userid){
	$recentgames = GetBestXPForUserAll($userid, "recent"); 
	$yeargames = GetBestXPForUserAll($userid, "year");
	$pastgames = GetBestXPForUserAll($userid, "past"); 
	$user = GetUser($userid);
	$name = "";
	if($user->_security == "Journalist"){ $name = $user->_first." ".$user->_last; }else{ $name = $user->_username; }
	DisplayBackButton($name."'s Favorites");
	?>
	<div class="row" style='margin-top:4em;text-align:left;'>
		<div class="col s12">
			<div class="row">
				<?php if(sizeof($recentgames)> 0){ ?>
				<div class="row" style='margin-top:2em;'>
					<div class="col s12 ability-tracking-header">Recent</div>
					<?php foreach($recentgames as $game){
						DisplaySmallGameCard($game);	
					}?>
				</div>
				<?php } ?>
				<?php if(sizeof($yeargames)> 0){ ?>
				<div class="row" style='margin-top:3em;'>
					<div class="col s12 ability-tracking-header">This Year</div>
					<?php foreach($yeargames as $game){
						DisplaySmallGameCard($game);	
					}?>
				</div>
				<?php } ?>
				<?php if(sizeof($pastgames)> 0){ ?>
					<div class="row" style='margin-top:3em;'>
						<div class="col s12 ability-tracking-header">Years Past</div>
						<?php foreach($pastgames as $game){
							DisplaySmallGameCard($game);	
						}?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php	
}

function DisplayProfileDevelopersTopTen($user){
	$developers = GetDeveloperMilestones($user->_id, 10, "top");
	
	$newuser = false;
	if(sizeof($developers) == 0){
		$developers = GetDeveloperMilestones(7, 10, "top");
		$newuser = true;
	}
	
	if($newuser){
		?>
		<div class="newprofile-overlay-container">
			<div class="newprofile-header">Influencers</div>
			<div class="newprofile-skills">
				Find out what Developers you have the most experience with and have been the most influential in you gaming habits.
				
				<div class="newprofile-skills-list">
					<div class="newprofile-sub-header">Popular developers:</div>
					<ol>
						<?php $i = 0;
							while($i < 5){ ?>
							<li class="newprofile-dev-item" data-id="<?php echo $developers[$i]->_name; ?>"><?php echo $developers[$i]->_name;?></li>
						<?php $i++; 
						} ?>
					</ol>	
				</div>
			</div>
		</div>
		<?php
	}
	
	$count = 1;
	foreach($developers as $dev){
		if($dev->_progress->_percentlevel1 < 100){
			$percentage = $dev->_progress->_percentlevel1;
			$progress = $dev->_progress->_progresslevel1;
			$max = $dev->_level1;
		}else if($dev->_progress->_percentlevel2 < 100){
			$percentage = $dev->_progress->_percentlevel2;
			$progress = $dev->_progress->_progresslevel2;
			$max = $dev->_level2;
		}else if($dev->_progress->_percentlevel3 < 100){
			$percentage = $dev->_progress->_percentlevel3;
			$progress = $dev->_progress->_progresslevel3;
			$max = $dev->_level3;
		}else if($dev->_progress->_percentlevel4 < 100){
			$percentage = $dev->_progress->_percentlevel4;
			$progress = $dev->_progress->_progresslevel4;
			$max = $dev->_level4;
		}else if($dev->_progress->_percentlevel5 < 100){
			$percentage = $dev->_progress->_percentlevel5;
			$progress = $dev->_progress->_progresslevel5;
			$max = $dev->_level5;
		}
		
		if($dev->_level5 > 0){
			$max = $dev->_level5;
			$progress = $dev->_progress->_progresslevel5;
		}else if($dev->_level4 > 0){
			$max = $dev->_level4;
			$progress = $dev->_progress->_progresslevel4;
		}else if($dev->_level3 > 0){
			$max = $dev->_level3;
			$progress = $dev->_progress->_progresslevel3;
		}else if($dev->_level2 > 0){
			$max = $dev->_level2;
			$progress = $dev->_progress->_progresslevel2;
		}else if($dev->_level1 > 0){
			$max = $dev->_level1;
			$progress = $dev->_progress->_progresslevel1;
		}
		
		$percentage = round(($progress / $max) * 100);
		?>
		<div class="developer-profile-item waves-effect waves-block" data-id="<?php echo $dev->_id; ?>"data-objectid="<?php echo $dev->_objectid; ?>" data-progid="<?php echo $dev->_progress->_id; ?>" >
			<div class="developer-profile-info">
				<div class="developer-profile-count"><?php echo $count; ?></div>
				<div class="developer-profile-title"><?php echo $dev->_name; ?></div>
			</div>
			<div class="developer-progress-bar">
				<div class="knowledge-progress" style='width:<?php echo $percentage; ?>%'></div>
				<div class="knowledge-progress-label">
					<?php echo $progress."/".$max; ?>
				</div>
			</div>
		</div>
		<?php
		$count++;
	}
}

function DisplayDevelopervViewMore($userid){
	$user = GetUser($userid);
	$developerhighlights = GetDeveloperMilestones($userid, 10, "recent");
	$developerthisyear = GetDeveloperMilestones($userid, 50, "year");
	$developeryearspast = GetDeveloperMilestones($userid, 50, "past");
	$name = "";
	if($user->_security == "Journalist"){ $name = $user->_first." ".$user->_last; }else{ $name = $user->_username; }
	DisplayBackButton($name."'s influencers");
	?>
	<div class="row" style='margin-top:4em;text-align:left;'>
		<div class="col s12">
			<div class="row">
				<?php if(sizeof($developerhighlights)> 0){ ?>
				<div class="row" style='margin-top:2em;'>
					<div class="col s12 ability-tracking-header">Recent</div>
					<?php foreach($developerhighlights as $dev){
						DisplayKnowledge($dev, "fixed");	
					}?>
				</div>
				<?php } ?>
				<?php if(sizeof($developerthisyear)> 0){ ?>
				<div class="row" style='margin-top:3em;'>
					<div class="col s12 ability-tracking-header">This Year</div>
					<?php foreach($developerthisyear as $dev){
						DisplayKnowledge($dev, "fixed");	
					}?>
				</div>
				<?php } ?>
				<?php if(sizeof($developeryearspast)> 0){ ?>
					<div class="row" style='margin-top:3em;'>
						<div class="col s12 ability-tracking-header">Years Past</div>
						<?php foreach($developeryearspast as $dev){
							DisplayKnowledge($dev, "fixed");	
						}?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
}

function DisplayDeveloperDetails($userid, $developer, $progress){
		$dev = GetDeveloperMilestoneForUser($progress, $userid);
		$details = GetDeveloperGames($developer, $userid);
		if($dev->_progress->_percentlevel1 < 100){
			$percentage = $dev->_progress->_percentlevel1;
			$progress = $dev->_progress->_progresslevel1;
			$max = $dev->_level1;
		}else if($dev->_progress->_percentlevel2 < 100){
			$percentage = $dev->_progress->_percentlevel2;
			$progress = $dev->_progress->_progresslevel2;
			$max = $dev->_level2;
		}else if($dev->_progress->_percentlevel3 < 100){
			$percentage = $dev->_progress->_percentlevel3;
			$progress = $dev->_progress->_progresslevel3;
			$max = $dev->_level3;
		}else if($dev->_progress->_percentlevel4 < 100){
			$percentage = $dev->_progress->_percentlevel4;
			$progress = $dev->_progress->_progresslevel4;
			$max = $dev->_level4;
		}else if($dev->_progress->_percentlevel5 < 100){
			$percentage = $dev->_progress->_percentlevel5;
			$progress = $dev->_progress->_progresslevel5;
			$max = $dev->_level5;
		}
		
		if($dev->_level5 > 0){
			$max = $dev->_level5;
			$progress = $dev->_progress->_progresslevel5;
		}else if($dev->_level4 > 0){
			$max = $dev->_level4;
			$progress = $dev->_progress->_progresslevel4;
		}else if($dev->_level3 > 0){
			$max = $dev->_level3;
			$progress = $dev->_progress->_progresslevel3;
		}else if($dev->_level2 > 0){
			$max = $dev->_level2;
			$progress = $dev->_progress->_progresslevel2;
		}else if($dev->_level1 > 0){
			$max = $dev->_level1;
			$progress = $dev->_progress->_progresslevel1;
		}
		
		$percentage = round(($progress / $max) * 100);
		?>
		<div class="ability-container-header">
			<div class="ability-title"><?php echo $dev->_name; ?> </div>
			<div class="ability-sub-count"><?php echo $progress."/".$max; ?></div>
			<div class="ability-progress-bar-dark">
				<div class="ability-level2" style='width:<?php echo $percentage; ?>%'></div>
			</div>
		</div>
		<div class="row ability-display-details">
		<?php
			foreach($details as $detail){
				DisplaySmallGameCard($detail);	
			}
		?>
		</div>
		<?php
}

function FeedDivider($label, $type){
	if($type == "Date")
		$label = explode(" ", ConvertDateToActivityFormat($label));
	?>
	<div class="row feed-date-divider" data-date="<?php echo $label; ?>" style='margin-bottom:0px;'>
		<div class="col s12">
			<div class="mylibrary-divider">
				<?php
					if($type == 'Alpha')
						echo $label;
					else if($type == "Date")
						echo $datetime[0];?>
			</div>
			<div class="mylibrary-divider-bullet"></div>
		</div>
	</div>
	<?php
}

function DisplayProfileActivity($userid){
	$user = GetUser($userid);
	$name = "";
	if($user->_security == "Journalist"){ $name = $user->_first." ".$user->_last; }else{ $name = $user->_username; }
	//DisplayBackButton($name."'s activity");
	?>	
	<div class='row' style='margin-top:20px'>
		<div class="activity-top-level" data-id='<?php echo $user->_id; ?>' >
			<?php 
				DisplayMainActivity($userid, "My Activity");
			?>
		</div>
		<?php
			DisplayProfileActivitySecondaryContent($user, $name);
		?>
	</div>
<?php	
}

function DisplayProfileActivityEndless($userid, $page, $date){
	DisplayActivityEndless($userid, $page, $date, "My Activity");
}

function DisplayProfileActivitySecondaryContent($user, $name){ ?>
	<div id="sideContainer" class="col s3" style='padding: 0 1.75rem;'>
		<div class="row activity-secondary-content">
			<div class="col s12">
				<div class="activity-filter-label"><i class="mdi-content-filter-list"></i> Filter Activity Feed</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
			  	    <div id="activity-people-i-follow" class="activity-category-selector" style='font-size:1.25rem;' data-filter="All"><i class="mdi-social-people left"></i>People I Follow</div>
				</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
					<div id="activity-only-users-i-follow" class="activity-category-selector" style='font-size:1.25rem;' data-filter="Only Users I Follow"><i class="mdi-social-people-outline left"></i>Only Users I Follow</div>
				</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
					<div id="activity-all-users" class="activity-category-selector" style='font-size:1.25rem;' data-filter="All Users"><i class="mdi-social-public left"></i>All Users</div>
				</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
					<div id="activity-only-critic-i-follow" class="activity-category-selector" style='font-size:1.25rem;' data-filter="Only Critics I Follow"><i class="mdi-action-subject left"></i>Only Personalities I Follow</div>
				</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
					<div id="activity-all-critics" class="activity-category-selector" style='font-size:1.25rem;' data-filter="All Critics"><i class="mdi-social-location-city left"></i>All Personalities</div>
				</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
			  	    <div id="activity-mine" class="activity-category-selector" style='font-size:1.25rem;' data-filter="My Activity"><i class="mdi-social-person left"></i> My Activity</div>
				</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
			  	    <div id="activity-someoneelse" class="activity-category-selector activity-category-selected" style='font-size:1.25rem;' data-id='<?php echo $user->_id; ?>' data-filter="<?php echo $name."'s"; ?> Activity"><i class="mdi-social-person left"></i><?php echo $name."'s"; ?>  Activity</div>
				</div>
			</div>
		</div>
	</div>
<?php
}

function DisplayProfileTierIcon($xp, $big, $slot){ 
	if(sizeof($xp->_playedxp) > 0){
		if($xp->_playedxp[0]->_completed == "101")
			$percent = 100;
		else
			$percent = $xp->_playedxp[0]->_completed;
			
		if($percent == 100){ ?>
			<div class="<?php if($slot){ ?>profile-highlighted-game-tier-container<?php }else{ ?> profile-best-game-tier-container<?php } ?> tier<?php echo $xp->_tier; ?>BG z-depth-1">
	      		<div class="profile-highlighted-game-tier" title="<?php echo "Tier ".$xp->_tier." - Completed"; ?>" <?php if(!$slot){ ?>style='margin-left: 3px;' <?php } ?>>
			  		<i class="mdi-hardware-gamepad"></i>
		  		</div>
	  		</div>
	  	<?php }else{ ?>
			<div class="<?php if($slot){ ?>profile-highlighted-game-tier-container<?php }else{ ?> profile-best-game-tier-container<?php } ?> z-depth-1">
			  <div class="c100 mini <?php if($big){ ?>biggerminiTierIcon<?php } ?> <?php if($xp->_tier == 1){ echo "tierone"; }else if($xp->_tier == 2){ echo "tiertwo"; }else if($xp->_tier == 3){ echo "tierthree"; }else if($xp->_tier == 4){ echo "tierfour"; }else if($xp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$xp->_tier." - ".$percent."% finished"; ?>" style='background-color:white;'>
			  	  <span class='tierTextColor<?php echo $xp->_tier; ?> <?php if($big){ ?>tierInProgessLarge<?php }else{ ?>tierInProgress<?php } ?>' style='background-color:white;'><i class="mdi-hardware-gamepad"></i></span>
				  <div class="slice">
				    <div class="bar <?php if($big){ ?>bigbar<?php }else{ ?>minibar<?php } ?>"></div>
				    <div class="fill <?php if($big && $percent > 50){ ?>bigbar<?php } ?>"></div>
				  </div>
				</div>
			</div>
		<?php
	  	}
	}else{
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
		
		if($percent == 101){ ?>
  			<div class="<?php if($slot){ ?>profile-highlighted-game-tier-container<?php }else{ ?> profile-best-game-tier-container<?php } ?> tier<?php echo $xp->_tier; ?>BG z-depth-1">
	      		<div class="profile-highlighted-game-tier" title="<?php echo "Tier ".$xp->_tier." - ".$length; ?>">
	      			<i class="mdi-action-visibility"></i>
      			</div>
  			</div>
		<?php }else{ ?>
			<div class="<?php if($slot){ ?>profile-highlighted-game-tier-container<?php }else{ ?> profile-best-game-tier-container<?php } ?> z-depth-1">
			  <div class="c100 mini <?php if($big){ ?>biggerminiTierIcon<?php } ?> <?php if($xp->_tier == 1){ echo "tierone"; }else if($xp->_tier == 2){ echo "tiertwo"; }else if($xp->_tier == 3){ echo "tierthree"; }else if($xp->_tier == 4){ echo "tierfour"; }else if($xp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$xp->_tier." - ".$length; ?>" style='background-color:white;'>
			  	  <span class='tierTextColor<?php echo $xp->_tier; ?> <?php if($big){ ?>tierInProgessLarge<?php }else{ ?>tierInProgress<?php } ?>' style='background-color:white;'><i class="mdi-action-visibility"></i></span>
				  <div class="slice">
				    <div class="bar <?php if($big){ ?>bigbar<?php }else{ ?>minibar<?php } ?>"></div>
				    <div class="fill <?php if($big && $percent > 50){ ?>bigbar<?php } ?>"></div>
				  </div>
				</div>
			</div>
		<?php }
	}
}

function DisplayProfileCheckPointTierIcon($xp){ 
	if($xp->_link != '' && $xp->_authenticxp == "No"){ ?>
		<div class="checkpoint-tier tier<?php echo $xp->_tier; ?>BG">
			<div class="checkpoint-icon-xp" style="margin-top:0" title='Tier <?php echo $xp->_tier; ?> - Curated Review'><i class="mdi-editor-format-quote"></i></div>
		</div>
	<?php }else if(sizeof($xp->_playedxp) > 0){
		if($xp->_playedxp[0]->_completed == "101")
			$percent = 100;
		else
			$percent = $xp->_playedxp[0]->_completed;
			
		if($percent == 100){ ?>
			<div class="checkpoint-tier tier<?php echo $xp->_tier; ?>BG z-depth-1">
	      		<div class="checkpoint-icon-xp" title='Tier <?php echo $xp->_tier; ?> - Completed'  style="margin-top:0">
			  		<i class="mdi-hardware-gamepad"></i>
		  		</div>
	  		</div>
	  	<?php }else{ ?>
			<div class="checkpoint-tier z-depth-1">
			  <div class="c100 mini <?php if($xp->_tier == 1){ echo "tierone"; }else if($xp->_tier == 2){ echo "tiertwo"; }else if($xp->_tier == 3){ echo "tierthree"; }else if($xp->_tier == 4){ echo "tierfour"; }else if($xp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$xp->_tier." - ".$percent."% finished"; ?>" style='background-color:white;<?php if($big){ ?>font-size:60px;<?php } ?>'>
			  	  <span class='tierTextColor<?php echo $xp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-hardware-gamepad"></i></span>
				  <div class="slice">
				    <div class="bar minibar"></div>
				    <div class="fill"></div>
				  </div>
				</div>
			</div>
		<?php
	  	}
	}else{
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
		
		if($percent == 101){ ?>
  			<div class="checkpoint-tier tier<?php echo $xp->_tier; ?>BG z-depth-1">
	      		<div class="checkpoint-icon-xp" title="<?php echo "Tier ".$xp->_tier." - ".$length; ?>"  style="margin-top:0">
	      			<i class="mdi-action-visibility"></i>
      			</div>
  			</div>
		<?php }else{ ?>
			<div class="checkpoint-tier z-depth-1">
			  <div class="c100 mini <?php if($xp->_tier == 1){ echo "tierone"; }else if($xp->_tier == 2){ echo "tiertwo"; }else if($xp->_tier == 3){ echo "tierthree"; }else if($xp->_tier == 4){ echo "tierfour"; }else if($xp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$xp->_tier." - ".$length; ?>" style='background-color:white;<?php if($big){ ?>font-size:60px;<?php } ?>'>
			  	  <span class='tierTextColor<?php echo $xp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-action-visibility"></i></span>
				  <div class="slice">
				    <div class="bar minibar"></div>
				    <div class="fill"></div>
				  </div>
				</div>
			</div>
		<?php }
	}
}

function DisplayUserCollections($user){
	$collections = GetLatestCollectionForUser($user->_id);
	$newuser = false;
	if(sizeof($collections) <= 2){
		if(sizeof($collections[0]->_games) == 0 && sizeof($collections[1]->_games) == 0){
			$newuser = true;
		}	
	} 
	if($newuser){
		$collections = GetLatestCollectionForUser(7);
		?>
		<div style='margin-left:25px'>
			<?php
			if(sizeof($collections) > 0){
				foreach($collections as $collection){
					DisplayCollection($collection);
				}
			}
			?>
		</div>
		<?php
	?>
	<div class="newprofile-overlay-container">
		<div class="newprofile-header">Collections</div>
		<div class="newprofile-skills" style='font-size:1.2em;'>
			Collections help you group games in meaningful ways. Whether it's a bucket list, games you are looking forward to or a top ten from your childhood, you can build & share it!
		</div>
		<div class="newprofile-skills" style='font-size:1.2em;'>
			Lifebar also auto creates collections for you! Bookmarking a game will automatically add to your Bookmark collection and if you try Steam import we create a collection from your Steam Library to help you build your Lifebar quickly. 
			<?php if($user->_id == $_SESSION['logged-in']->_id){ ?>
				<br>Give importing a try! 
				<span class="view-collections btn" style='margin-left: 20px;font-size: 0.8em;padding: 0 1rem;background-color: #E91E63;'><i class='fa fa-steam' style='font-size: 1em;margin-right: 10px;'></i> Import Steam Library</span> 
			<?php } ?>
		</div>
		<div class="badge-card-container-view-more view-collections" style='left: 0;text-align: right;z-index: 10;bottom: 0;height: 50px;color:white;'>View & Create Collections</div>
	</div>
	<?php
	}else{
	?>
		<div style='margin-left:25px'>
			<?php
			if(sizeof($collections) > 0){
				foreach($collections as $collection){
					DisplayCollection($collection);
				}
			}
			?>
		</div>
		<div class="badge-card-container-view-more view-collections" style='left: 0;text-align: right;background-color: white;z-index: 10;bottom: 0;height: 50px;'>View Collections</div>
	<?php
	}
}
?>
