<?php
function DisplayUserCollection($userid){
	$user = GetUser($userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $name = $user->_first." ".$user->_last; }else{ $name = $user->_username; }
	DisplayBackButton($name."'s Collections");
	$autocollections = GetPersonalAutoCollections($userid);
	$personalcollections = GetPersonalCollections($userid);
	$subcollections = GetSubscribedCollections($userid);
	?>
	<div class="row" style='margin-top:4em;margin-left:2.5%;margin-right:2.5%;text-align:left;'>
		<div class="col s12" style='border-bottom:1px solid rgba(0,0,0,0.4);'>
			<div class="row" style='margin-top:2em;'>
				<div class="col s12 ability-tracking-header">Auto Collections</div>
				<div class="col s12">Automated Collections made by Lifebar. <?php if($userid == $_SESSION['logged-in']->_id){ ?> Some are pre-built like Bookmarked & Lifebar Backlog and others are created as you add experiences <?php } ?></div>
				<div class="col s12 collection-list-container">
					<?php if(sizeof($autocollections) > 0){
						foreach($autocollections as $collection){
							DisplayCollection($collection);	
						}
					}
					?>
				</div>
				<?php if($userid == $_SESSION['logged-in']->_id){ ?>
					<div class="col s12">
						<div class="btn <?php if(AlreadyImportedSteam($userid)){ ?>load-steam-import<?php }else{ ?>import-steam<?php } ?>"><i class="fa fa-steam" style='font-size: 1em;margin-right: 10px;'></i> <?php if(AlreadyImportedSteam($userid)){ ?>Load Steam Import Results<?php }else{ ?>Import Steam Library<?php } ?></div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="row" style='margin-left:2.5%;margin-right:2.5%;text-align:left;'>
		<div class="col s12" style='border-bottom:1px solid rgba(0,0,0,0.4);'>
			<div class="row" style='margin-top:2em;'>
				<div class="col s12 ability-tracking-header"><?php if($userid == $_SESSION['logged-in']->_id){ ?>Personal Collections<? }else{ echo $name;?>'s Personal Collections<?php } ?></div>
				<div class="col s12">Customized Collections made by <?php if($userid == $_SESSION['logged-in']->_id){ ?>you<?php }else{ echo $name; } ?>. 	Group games with a common theme or idea into personalized categories.</div>
				<div class="col s12 collection-list-container">
					<?php if(sizeof($personalcollections) > 0){
						foreach($personalcollections as $collection){
							DisplayCollection($collection);	
						}
					}?>
					<?php if($userid == $_SESSION['logged-in']->_id){ ?>
						<div class="collection-box collection-add"><i class="mdi-content-add"></i></div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row" style='margin-left:2.5%;margin-right:2.5%;text-align:left;'>
		<div class="col s12">
			<div class="row" style='margin-top:2em;'>
				<div class="col s12 ability-tracking-header"><?php if($userid == $_SESSION['logged-in']->_id){ ?>Your Followed Collections<? }else{ echo $name;?>'s Followed Collections<?php } ?></div>
				<div class="col s12"><?php if($userid == $_SESSION['logged-in']->_id){ ?>Collections you are currently following from other members. You will recieve notifications when these collections are updated.<?php }else{ echo $name." is following these Collections from other members."; } ?></div>
				<div class="col s12 collection-list-container">
					<?php if(sizeof($subcollections) > 0){
						foreach($subcollections as $collection){
							DisplayCollection($collection);	
						}
					}?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function DisplayCollection($collection){ 
	if(sizeof($collection->_games) >= 3){
		$coverimage = $collection->_games[0]->_imagesmall;
		$two = $collection->_games[1];
		$three = $collection->_games[2];
	}else if(sizeof($collection->_games) >= 2){
		$coverimage = $collection->_games[0]->_imagesmall;
		$two = $collection->_games[1];
	}else{
		$coverimage = $collection->_games[0]->_imagesmall;
	}
	
	if($collection->_coversmall != ''){
		$coverimage = $collection->_coversmall;
	}
	?>
	<div class="collection-box-container" data-id='<?php echo $collection->_id; ?>'>
		<div class="collection-box z-depth-2" style="margin-top: 60px;z-index:4;background: -moz-linear-gradient(top, rgba(0,0,0,0.6) 40%, rgba(0,0,0,0.6) 100%, rgba(0,0,0,0.6) 101%), url(<?php echo $coverimage; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(40%,rgba(0,0,0,0.6)), color-stop(100%,rgba(0,0,0,0.6)), color-stop(101%,rgba(0,0,0,0.6))), url(<?php echo $coverimage; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(0,0,0,0.6) 40%,rgba(0,0,0,0.6) 100%,rgba(0,0,0,0.6) 101%), url(<?php echo $coverimage; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(0,0,0,0.6) 40%,rgba(0,0,0,0.6) 100%,rgba(0,0,0,0.6) 101%), url(<?php echo $coverimage; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
			<div class="collection-name">
				<div class="collection-total">
					<?php echo number_format(sizeof($collection->_games)); ?>
				</div>
				<?php echo $collection->_name; ?>
				<div class="collection-desc">
					<?php echo $collection->_desc; ?>
				</div>
			</div>
		</div>
		<?php if($two != ''){ ?>
			<div class="collection-box z-depth-1" style="margin:0;width:180px;height:180px;left:40px;top:-230px;z-index:3;background: url(<?php echo $two->_imagesmall; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		<?php } ?>
		<?php if($three != ''){ ?>
			<div class="collection-box z-depth-1" style="margin:0;width:160px;height:160px;left:80px;top:-430px;z-index:2;background: url(<?php echo $three->_imagesmall; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		<?php } ?>
	</div>
<?php
}

function DisplayCollectionDetails($collectionID){
	$collection = GetCollectionByIDForUser($collectionID, $_SESSION['logged-in']->_id);
	
	if($collection != null && $collection != ""){
		if($_SESSION['logged-in']->_id == $collection->_owner)
			$edit = true;
		else
			$edit = false;
			
		if($collection->_createdby == -3)
			$disableRemove = true;
		else
			$disableRemove = false;
		?>
		<div class="backContainer" style='background:transparent;text-shadow: 1px 1px 5px rgba(0,0,0,0.3);position:absolute;top:0;'>
			<div class="backButton waves-effect waves-light"><i class="mdi-navigation-arrow-back small" style="color:white;vertical-align:middle;padding: 0 0.5em;"></i> <a class="btn-flat backButtonLabel" style="color:white;margin: 0;padding: 0;" >Back</a></div>
		</div>
		<?php
		$totalsize = GetCollectionSize($collectionID, $collection->_owner, $collection->_rule);
		if(sizeof($collection->_games) >= 1 && $collection->_cover == ''){
			$key = array_rand($collection->_games, 1);
			$one = $collection->_games[$key];
			$coverimage = $one->_game->_image;
		}else if($collection->_cover != ''){
			$coverimage = $collection->_cover;	
		}
	
		?>
		<div class="row collection-cover-image" style='background: url(<?php echo $coverimage; ?>) 25% 50%;background-size: cover;margin-bottom:0;height: 300px;margin-top: -15px;'>
			<div class="collection-image-gradient" style="position:absolute;top:0px;left:0;width:100%;height:300px;z-index:3"></div>
			<div class="col s12 m6" style='z-index: 4;color: white;position: absolute;left: 0;top: 200px;'>
				<div class="collection-details-container">
					<div class="collection-details-name" data-id='<?php echo $collection->_id; ?>'>
						<?php echo $collection->_name; ?>
						<div class="collection-share" data-id='<?php echo $collection->_id; ?>' data-owner='<?php echo $collection->_owner; ?>'><i class="mdi-social-share"></i></div>
					</div>
					<div class="collection-details-desc">
						<?php echo $collection->_desc; ?>
					</div>
				</div>
				<?php if($edit && $collection->_createdby > 0){ ?>
					<div class="row collection-edit-mode collection-edit-properties-container">
						<div class="col s12">
							<div class="row">
								<div class="input-field col s12">
							        <input id="frm-collection-name" class="active frm-collection-name" type="text" value="<?php echo $collection->_name; ?>">
							        <label for="frm-collection-name" class="active">Collection Name</label>
								</div>
							</div>
							<div class="row" style='margin: 0;'>
								<div class="input-field col s12">
									<textarea id="frm-collection-desc" class="frm-collection-desc materialize-textarea" style='height: 22px;'><?php echo $collection->_desc; ?></textarea>
					          		<label for="frm-collection-desc" class="active">Description of your Collection (optional)</label>
								</div>
							</div>
							<div class="row">
								<div class="btn collection-edit-save-refresh">Save & Refresh</div>
								<div class="btn collection-edit-delete">Delete Collection</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<?php if($edit){ ?>
				<div class="btn edit-mode-btn">Edit Collection</div>
	        	<div class="btn collection-edit-exit">Back to Collection</div>
	        	<div class="btn collection-edit-random-cover">Random Cover Images</div>
	        	<?php if($collection->_createdby == -1){ ?>
	        		<div class="btn collection-edit-delete" style='position: absolute;top: 110px;right: 14px;width: 225px;'>Delete Collection</div>
	        	<?php } ?>
			<?php }else if($_SESSION['logged-in']->_id > 0){ 
					$following = IsUserSubscribed($_SESSION['logged-in']->_id, $collection->_id);
					if($following){	?>
						<div class="btn collection-follow-btn" data-following="yes">Un-follow</div>
					<?php }else{ ?>
						<div class="btn collection-follow-btn" data-following="no">Follow</div>
					<?php } ?>
			<?php } ?>
			<div class="col s12 m6 collection-details-total-container" style='z-index:4;color:white;position: absolute;right: 0;top: 200px;'>
				<div class="collection-details-total">
					<div class="collection-details-total-string">
						<?php  
							echo ConvertDateToLongRelationalEnglish($collection->_lastUpdate); 
						?>
					</div>
					<div class='collection-details-total-lbl'>
						last updated
					</div>
				</div>
				<div class="collection-details-total">
					<div class="collection-details-total-string">
						<?php 
							$user = GetUser($collection->_owner); 
							echo $user->_username; 
						?>
					</div>
					<div class='collection-details-total-lbl'>
						owner
					</div>
				</div>
				<div class="collection-details-total">
					<div class="collection-details-total-num collection-following-counter">
						<?php echo $collection->_numOfSubs; ?>
					</div>
					<div class='collection-details-total-lbl'>
						following
					</div>
				</div>
				<div class="collection-details-total">
					<div class="collection-details-total-num collection-total-counter">
						<?php echo $totalsize; ?>
					</div>
					<div class='collection-details-total-lbl'>
						games
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12 collection-games-header-container">
				<div class="row" style='margin:10px 0;'>
					<div class="col s4 import-list-header HideForMobile">Game</div>
					<div class="col s3 import-list-header HideForMobile import-list-header-exp">Experience</div>
					<?php if($edit){ ?>
						<div class="col s5 import-list-header-add-collection import-list-header" style='text-align: center;'>Add Games to Collection</div>
					<?php } ?>
					<div class="col s5 import-list-header import-list-header-search" style='height: 47px;position: relative;text-align: right;z-index: 0;'>
						<i class="mdi-action-search small collection-search-icon"></i>
						<div class='collection-search-box'>
							<input id="collection-search" type="text" placeholder="Search Collection" style='border: none !important;color:white;margin: 0;display:inline-block;'>
						</div>
					</div>
				</div>
			</div>
			<div class="col s12 collection-games-container">
			<?php DisplayCollectionDetailGames($collection->_games, $edit, $disableRemove); ?>
			</div>
			<?php if($edit && $collection->_createdby != -3){ ?>
				<div class="col s12 m4 collection-edit-mode collection-game-search-container" style='position:relative;height: 70px;z-index:5;margin-top: 15px;'>
		          	<div class="row">
			          	<div class='input-field' style='display: inline-block;width: calc(100% - 40px);'>
					        <input id="collection-add-game-search" type="text" value="">
					        <label for="collection-add-game-search">Search for Games</label>
				        </div>
			        	<div class='btn-flat collection-game-search-btn' style='padding: 5px;cursor: pointer;margin-bottom: 0px;color: rgba(0,0,0,0.6);vertical-align: middle;'><i class="mdi-action-search"></i></div>
		        	</div>
		        	<div class="row">
		        		<div class="col s12">
				        	<ul class='collection-game-search-results'>
			        		</ul>
		        		</div>
		        	</div>
				</div>
			<?php }else if($collection->_createdby == -3){ ?>
					<div class="col s12 m4 collection-edit-mode collection-game-search-container" style='position:relative;height: 70px;z-index:5;margin-top: 15px;'>
			          	<div class="row">
	          				<div class="col s12 import-results-subheader" style='font-size:1em;display:block;'>
								<i class="fa fa-exclamation-triangle" style='color:#FF9800;margin-right:5px'></i>This is a rule based Auto-Collection, so you cannot manually modify.
							</div>
		          		</div>
		          		<div class="row">
  							<div class="col s12 import-results-subheader" style='font-size:1.1em;display:block;'>
								<div style='font-weight:400'>Rule for this collection:</div>
								<div style='font-weight:300;font-style:italic'><?php echo $collection->_ruledesc; ?></div>
							</div>
		          		</div>
	          		</div>
			<?php } ?>
			<div class="collection-paging">
				<div class="collection-pagination">
					<div class='btn collection-prev'><i class="fa fa-chevron-left"></i></div>
				</div>
				<div class="collection-pagination-desc">
					<span class="collection-pagination-offset" data-offset="0">0 - <?php if($totalsize > 25){ echo "25"; }else{ echo $totalsize; } ?></span> of <span class='collection-pagination-total'><?php echo $totalsize; ?></span>
				</div>
				<div class="collection-pagination">
					<div class='btn collection-next'><i class="fa fa-chevron-right"></i></div>
				</div>
			</div>
		</div>
<?php }else{ ?>
	<div class="row">
		<div class="col s12 import-results-subheader" style='margin-top: 75px;padding: 0 40px !important;font-size:1.5em;'>
			<i class="fa fa-exclamation-triangle" style='color:#FF9800'></i> Sorry, but this Collection no longer exists!
		</div>
	</div>
<?php
	}
}

function DisplayCollectionDetailGamesPagination($collectionid, $userid, $offset, $editMode){
	$quickcollection = GetCollectionByID($collectionid);
	$collectiongames = GetCollectionGamesWithXP($collectionid, $_SESSION['logged-in']->_id, $quickcollection->_owner, $quickcollection->_rule, $offset, 25);
	if(($editMode == "true" || $editMode == true) && $userid == $_SESSION['logged-in']->_id)
		$editMode = true;
	else
		$editMode = false;
		
	if($quickcollection->_createdby == -3)
		$disableRemove = true;
	else
		$disableRemove = false;
		
	DisplayCollectionDetailGames($collectiongames, $editMode, $disableRemove);
}

function DisplaySearchCollection($collectionid, $search, $offset, $userid, $editMode){
	$quickcollection = GetCollectionByID($collectionid);
	$collectiongames = GetCollectionGamesBySearch($collectionid, $search, $offset, 25, $_SESSION['logged-in']->_id, $quickcollection->_owner, $quickcollection->_rule);
	if(($editMode == "true" || $editMode == true) && $userid == $_SESSION['logged-in']->_id)
		$editMode = true;
	else
		$editMode = false;
		
	if($quickcollection->_createdby == -3)
		$disableRemove = true;
	else
		$disableRemove = false;
		
	DisplayCollectionDetailGames($collectiongames, $editMode, $disableRemove);
}

function DisplayCollectionDetailGames($collectiongames, $edit, $disableRemove){
	$i = 0;
	if($collectiongames !=null && sizeof($collectiongames) > 0){
		foreach($collectiongames as $xp){
			?>
			<div class="col s12 collection-games-container-row" data-id="<?php echo $xp->_game->_id; ?>">
				<div class="row collection-games-container-sub-row" style='margin:0;position:relative;border-bottom: 1px solid rgba(0,0,0,0.3);<?php if($i % 2 == 0){?>background-color:#ddd;<?php } ?>'>
			 		<div class="collection-quick-add-container z-depth-2">
			 			Empty Text
			 		</div>
					<div class="col s12 m4 collection-game-details-container" style='padding:0;position: relative;'>
						<a class="import-game-image z-depth-1" href="/#game/<?php echo $xp->_game->_id; ?>/<?php echo urlencode($xp->_game->_title); ?>/" data-id="<?php echo $xp->_game->_gbid; ?>" style="cursor: pointer;width:200px;height:69px;background: url(<?php echo $xp->_game->_imagesmall; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;" onclick="var event = arguments[0] || window.event; event.stopPropagation();"></a>
						<div class="collection-game-name" style='left:220px;padding:0;' data-id="<?php echo $xp->_game->_gbid; ?>" data-gid="<?php echo $xp->_game->_id; ?>">
							<?php echo $xp->_game->_title; ?>
						</div>
					</div>
					<div class="col s12 m8 collection-xp-details-container" style='z-index:0;'>
						<?php if($edit){ 
								if(!$disableRemove)
									DisplayRemoveOption($xp->_game->_id); 
								DisplayPinOption($xp->_game->_id);
							  } 
						?>
						<?php if(sizeof($xp->_playedxp) > 0){ 
	  					  	  	if($xp->_playedxp[0]->_completed == "101")
									$percent = 100;
								else
									$percent = $xp->_playedxp[0]->_completed;
									?>
      							 	<div class="collection-game-xp-progress-bar" data-gameid='<?php echo $xp->_game->_id; ?>'>
		  						 		<div class="collection-game-xp-progress-tick" data-prog="10" style='left:10%;'><div class="collection-tick">10%</div></div>
		  						 		<div class="collection-game-xp-progress-tick" data-prog="20" style='left:20%;'><div class="collection-tick">20%</div></div>
		  						 		<div class="collection-game-xp-progress-tick" data-prog="30" style='left:30%;'><div class="collection-tick">30%</div></div>
		  						 		<div class="collection-game-xp-progress-tick" data-prog="40" style='left:40%;'><div class="collection-tick">40%</div></div>
		  						 		<div class="collection-game-xp-progress-tick" data-prog="50" style='left:50%;'><div class="collection-tick">50%</div></div>
		  						 		<div class="collection-game-xp-progress-tick" data-prog="60" style='left:60%;'><div class="collection-tick">60%</div></div>
		  						 		<div class="collection-game-xp-progress-tick" data-prog="70" style='left:70%;'><div class="collection-tick">70%</div></div>
		  						 		<div class="collection-game-xp-progress-tick" data-prog="80" style='left:80%;'><div class="collection-tick">80%</div></div>
		  						 		<div class="collection-game-xp-progress-tick" data-prog="90" style='left:90%;'><div class="collection-tick">90%</div></div>
		  						 		<div class="collection-game-xp-progress-tick" data-prog="100" style='left:100%;'><div class="collection-tick">100%</div></div>
      							 		<div class="collection-game-xp-progress-filled tier<?php echo $xp->_tier; ?>BG" style='width:<? echo $percent; ?>%'  data-default="<? echo $percent; ?>"></div>
      							 	</div>
  		 		  	  	       		<div class="collection-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1" data-default="<?php echo $xp->_tier; ?>" style='left: <? echo $percent; ?>%;margin-left: -20px;'>
							          	<div class="collection-game-tier" title="<?php echo "Tier ".$xp->_tier." - Completed"; ?>">
						    				<i class="mdi-hardware-gamepad"></i>
							          	</div>
						          	</div>

							<?php 
						 }else if($_SESSION['logged-in']->_id > 0){ ?>
						 	<div class="collection-game-xp-progress-bar" data-gameid='<?php echo $xp->_game->_id; ?>'>
  						 		<div class="collection-game-xp-progress-tick" data-prog="10" style='left:10%;'><div class="collection-tick">10%</div></div>
  						 		<div class="collection-game-xp-progress-tick" data-prog="20" style='left:20%;'><div class="collection-tick">20%</div></div>
  						 		<div class="collection-game-xp-progress-tick" data-prog="30" style='left:30%;'><div class="collection-tick">30%</div></div>
  						 		<div class="collection-game-xp-progress-tick" data-prog="40" style='left:40%;'><div class="collection-tick">40%</div></div>
  						 		<div class="collection-game-xp-progress-tick" data-prog="50" style='left:50%;'><div class="collection-tick">50%</div></div>
  						 		<div class="collection-game-xp-progress-tick" data-prog="60" style='left:60%;'><div class="collection-tick">60%</div></div>
  						 		<div class="collection-game-xp-progress-tick" data-prog="70" style='left:70%;'><div class="collection-tick">70%</div></div>
  						 		<div class="collection-game-xp-progress-tick" data-prog="80" style='left:80%;'><div class="collection-tick">80%</div></div>
  						 		<div class="collection-game-xp-progress-tick" data-prog="90" style='left:90%;'><div class="collection-tick">90%</div></div>
  						 		<div class="collection-game-xp-progress-tick" data-prog="100" style='left:100%;'><div class="collection-tick">100%</div></div>
  						 		<div class="collection-game-xp-progress-filled" style='width:0%' data-default="0"></div>
						 	</div>
 		  	  	       		<div class="collection-game-tier-container collection-tier-container-placeholder z-depth-1" style='left:0' data-default="00">
					          	<div class="collection-game-tier" title="Not Started">
				    				<i class="mdi-hardware-gamepad"></i>
					          	</div>
				          	</div>
						 <?php }else{ ?>
						 	<a class="signUpFromCollection btn-flat" style='margin-top: 15px;' href='#signupModal'>SIGN UP TO ENTER YOUR XP</a>
						 <?php }
						 
						 if(sizeof($xp->_watchedxp) > 0){ 
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
					          	<div class="collection-game-tier-container-watched tier<?php echo $xp->_tier; ?>BG z-depth-1" data-gameid='<?php echo $xp->_game->_id; ?>'>
					          		<div class="collection-game-tier" title="<?php echo "Tier ".$xp->_tier." - ".$length; ?>">
			          					<i class="mdi-action-visibility"></i>
						          	</div>
					          	</div>

					     	  <?php }else{ ?>
					      		<div class="collection-game-tier-container-watched tier<?php echo $xp->_tier; ?>BG z-depth-1" data-gameid='<?php echo $xp->_game->_id; ?>'>
					  			  	<div class="c100 mini <?php if($xp->_tier == 1){ echo "tierone"; }else if($xp->_tier == 2){ echo "tiertwo"; }else if($xp->_tier == 3){ echo "tierthree"; }else if($xp->_tier == 4){ echo "tierfour"; }else if($xp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$xp->_tier." - ".$length; ?>" style='background-color:white;'>
								  	  <span class='tierTextColor<?php echo $xp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-action-visibility"></i></span>
									  <div class="slice">
									    <div class="bar minibar"></div>
									    <div class="fill"></div>
									  </div>
									</div>
					   			</div>
						  	  <?php
						  	  		}
								}else if($_SESSION['logged-in']->_id > 0){
									?>
			 		  	  	       	<div class="collection-game-tier-container-watched collection-tier-container-placeholder z-depth-1" data-gameid='<?php echo $xp->_game->_id; ?>'>
							          	<div class="collection-game-tier" title="Not Watched">
						    				<i class="mdi-action-visibility"></i>
							          	</div>
						          	</div>
									<?php
								}
						 	if($_SESSION['logged-in']->_id > 0){ ?>
						 	<div class="btn orange darken-2 collection-game-add-to-collection" data-id="<?php echo $xp->_game->_id; ?>">
						 		<i class="mdi-av-my-library-add"></i>
					 		</div>
						 <?php } ?>
					</div>
					<div class="col s12 collection-xp-entry-container" style='z-index:0;'></div>
				</div>
			</div>
			<?php
			$i++;
		}
	}else{
		?>
		<div class="col s12 no-games-found" style='margin-top:15px;height:70px;padding: 0;font-size:1.75em;font-weight:400;'>
			0 games were found
		</div>
		<?php
	}
}

function DisplayCollectionManagement($gameid, $userid, $quickAdd, $gbid){
	//Create collection
	if($gameid == -1 && $gbid == ''){
		?>
		<div class="row">
			<div class="col s12">
				<div class="collection-mgmt-header">
					Create a new Collection
				</div>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s11" style='width:calc(100%-2em);margin-left:2em;'>
		        <input id="frm-collection-name" class='frm-collection-name' type="text">
		        <label for="frm-collection-name">Collection Name</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s11" style='width:calc(100%-2em);margin-left:2em;'>
				<textarea id="frm-collection-desc" class="materialize-textarea frm-collection-desc"></textarea>
          		<label for="frm-collection-desc">Description of your Collection (optional)</label>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="btn frm-collection-create-btn orange darken-2">Create Collection</div>
				<div class="frm-collection-validation"></div>
			</div>
		</div>
		<?php
	}else{
		if($gameid == -1){
			$game = GetGameByGBIDFull($gbid);
			$gameid = $game->_id;
		}
		?>
		<div class="row collection-add-to-existing-collection-container" <?php if($quickAdd == "false"){?> style='height:inherit' <?php } ?> >
			<div class="col s12">
				<?php $collectionList = GetCollectionListForUserAndGame($userid, $gameid); 
				if(sizeof($collectionList) > 0){
					foreach($collectionList as $listitem){ ?>
						<div class="collection-add-game-from-popup" data-id='<?php echo $listitem['ID']; ?>'>
							<div class="collection-add-game-from-popup-name"><?php echo $listitem['Name']; ?></div>
							<?php if($listitem['Exists']){ ?>
								<div class="btn-flat collection-add-game-from-popup-remove waves-effect"><i class='fa fa-check' ></i></div>
							<?php }else{ ?>
								<div class="btn-flat collection-add-game-from-popup-add waves-effect">Add</div>
							<?php } ?>
						</div>
					<?php } 
				}?>
			</div>
		</div>
		<div class="row" style='padding:15px 0 10px 10px; border-top: 1px solid #ddd;'>
			<div class="col s12 collection-validation-quick"></div>
			<div class="input-field col s8">
		        <input id="frm-collection-name" class="frm-collection-name" type="text" style='margin-bottom: 0;'>
		        <label for="frm-collection-name">New Collection</label>
			</div>
			<div class="col s4">
				<div class="btn frm-collection-create-btn collection-quick-add-btn orange darken-2">Create</div>
			</div>
		</div>
		<?php
	}
}

function DisplayRemoveOption($gameid, $display = false){
	?>
	<div class="btn collection-edit-mode collection-remove-game" <?php if($display){ echo "style='display:block'"; } ?> data-id='<?php echo $gameid; ?>' title='Remove game from collection'><i class="mdi-content-remove-circle-outline"></i></div>
	<?php
}

function DisplayPinOption($gameid, $display = false){
	?>
	<div class="btn collection-edit-mode collection-cover-game" <?php if($display){ echo "style='display:block'"; } ?> data-id='<?php echo $gameid; ?>' title='Set this game as the collection cover image'><i class="mdi-image-collections"></i></div>
	<?php
}

function DisplayAddedGameToCollection($gameid){
	$game = GetGame($gameid);
	?>
	<div class="col s12 collection-games-container-row" style='height:70px;padding: 0;'>
		<div class="row collection-games-container-sub-row" style='margin:0;position:relative;border-bottom: 1px solid rgba(0,0,0,0.3);'>
			<div class="col s12 m4 collection-game-details-container" style='padding:0;position: relative;'>
				<div class="import-game-image z-depth-1" style="cursor: pointer;width:200px;height:69px;background: url(<?php echo $game->_imagesmall; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;" ></div>
				<div class="collection-game-name" style='left:220px;padding:0;'>
					<?php echo $game->_title; ?>
				</div>
			</div>
			<div class="col s12 m8" style='position:relative;'>
				<?php 
						DisplayRemoveOption($game->_id, true); 
						DisplayPinOption($game->_id, true);
				?>
			</div>
		</div>
	</div>
	<?php
}

function DisplayCollectionPlayedEdit($gameid, $userid){
	$exp = GetExperienceForUserByGame($userid, $gameid);
	$xp = $exp->_playedxp[0];
	$date = explode('-',$xp->_date);
	$year = date("Y");  
	$releaseyear = $exp->_game->_year;
	$platforms = explode("\n", $exp->_game->_platforms);
	if(sizeof($platforms) > 0){
	?>
	<div class="row collection-myxp-played-container">
		<div class="col s12 m6">
			<div class="row">
				<div class="col s12 m12 l10" style='text-align:left;position:relative;top:15px;'>
					<div class="collection-game-myxp-gutter"><i class="mdi-social-poll left"></i></div>
					<div class="collection-game-myxp-header">Tier</div>
					<div class="collection-game-myxp-container" >
					 	<div class="collection-myxp-tier-container">
					  	    <div class="collection-myxp-tier t5 tierBorderColor5 <?php if($exp->_tier == 5){ echo "tierBorderColor5selected myxp-selected-tier"; } ?>" data-tier='5' >5 <div class="collection-myxp-label" style='color: #DB0058;'>Worst</div></div>
						    <div class="collection-myxp-tier t4 tierBorderColor4 <?php if($exp->_tier == 4){ echo "tierBorderColor4selected myxp-selected-tier"; } ?>" data-tier='4' >4</div>
						    <div class="collection-myxp-tier t3 tierBorderColor3 <?php if($exp->_tier == 3){ echo "tierBorderColor3selected myxp-selected-tier"; } ?>" data-tier='3' >3</div>
					  	    <div class="collection-myxp-tier t2 tierBorderColor2 <?php if($exp->_tier == 2){ echo "tierBorderColor2selected myxp-selected-tier"; } ?>" data-tier='2' >2</div>
					  	    <div class="collection-myxp-tier t1 tierBorderColor1 <?php if($exp->_tier == 1){ echo "tierBorderColor1selected myxp-selected-tier"; } ?>" data-tier='1' >1 <div class="collection-myxp-label" style='left:15px;color: #0A67A3;'>Best</div></div>
					  	</div>
				  	</div>
				</div>
				<div class="col s12 m12 l10" style='text-align:left;position:relative;top:150px'>
					<div class="collection-game-myxp-gutter"><i class="mdi-hardware-gamepad left"></i></div>
					<div class="collection-game-myxp-header">Platform</div>
					<div class="collection-game-myxp-container" style='right:50px;'>
						<select class="myxp-platform">
						<?php $myplatforms = explode("\n", $xp->_platform);
						foreach($platforms as $platform){ 
							if($platform != ""){ ?>
								    <option value="<?php echo trim($platform); ?>"
										<?php 
										if(sizeof($myplatforms) > 0){
											foreach($myplatforms as $myplatform){
												if(trim($myplatform) != ""){
													if(stristr(trim($platform), trim($myplatform)) || sizeof($platforms) == 1){ echo ' selected'; }
												}
											} 
										} ?>
								    ><?php echo trim($platform); ?></option>
	
						<?php 	} 
						} ?>
						</select>
					</div>
				</div>
				<div class="col s12 m12 l10" style='text-align:left;position:relative;top:285px;'>
					<div class="collection-game-myxp-gutter"><i class="mdi-notification-event-note left"></i></div>
					<div class="collection-game-myxp-header">Date</div>
					<div class="collection-game-myxp-container" style="right:250px;">
					  <select class="myxp-year" name="myxp-year">
						<?php 
							if($exp->_game->_year == 0){
								$officialrelease = "";
								?>
								<option value="<?php echo $year; ?>"  selected><?php echo $year; ?> </option>
								<?php
							}else{
								$officialrelease =  ConvertDateToLongRelationalEnglish($exp->_game->_released);
								while($year >= $releaseyear && ($year - $birthyear) > 2){?>
								<option value="<?php echo $year; ?>"  <?php if($date[0] == $year){ echo "selected"; } ?>><?php echo $year; ?> <?php if($year == $releaseyear){ echo " - US Release (".$officialrelease.")"; } ?> </option>
								<?php
									$year = $year - 1;
								}
							} 
	
						 ?>
						</select>
					
					 	<?php $month = date('n');
				 			if($month > '0' && $month <= '3'){
								$quarter = "1";
							}else if($month > '3' && $month <= '6'){
								$quarter = "2";
							}else if($month > '6' && $month <= '9'){
								$quarter = "3";
							}else if($month > '9' && $month <= '12'){
								$quarter = "4";
							}else if($month == 0){
								$quarter = "0";
							}
					 	?>
					 	<div class="collection-myxp-quarter-container">
					  	    <div class="q0 <?php if($date[1] == 0){ echo "collection-myxp-quarter-selected"; }else if($quarter == 0){ echo "collection-myxp-quarter-selected"; } ?>" style="display:none" data-text="q0">Q0</div>
						    <div class="collection-myxp-quarter q1 <?php if($date[1] == 1){ echo "collection-myxp-quarter-selected"; }else if($quarter == 1){ echo "collection-myxp-quarter-selected"; } ?>" data-text="q1">Q1</div>
						    <div class="collection-myxp-quarter q2 <?php if($date[1] == 4){ echo "collection-myxp-quarter-selected"; }else if($quarter == 2){ echo "collection-myxp-quarter-selected"; } ?>" data-text="q2">Q2</div>
					  	    <div class="collection-myxp-quarter q3 <?php if($date[1] == 7){ echo "collection-myxp-quarter-selected"; }else if($quarter == 3){ echo "collection-myxp-quarter-selected"; } ?>" data-text="q3">Q3</div>
					  	    <div class="collection-myxp-quarter q4 <?php if($date[1] == 10){ echo "collection-myxp-quarter-selected"; }else if($quarter == 4){ echo "collection-myxp-quarter-selected"; } ?>" data-text="q4">Q4</div>
					  	</div>
				  	</div>
				</div>
			</div>
		</div>
		<div class="col s12 m6">
		    <div class="row">
		      <div class="input-field col s12 collection-quote-played-container">
		  		<div class="collection-game-myxp-gutter"><i class="mdi-editor-format-quote quoteflip left" style='font-size: 1.5em;margin-top: -8px;'></i></div>
				<div class="collection-game-myxp-header">Summary</div>
				<div class="collection-game-myxp-container" style='top: 75px;'>
			  	    <script>
				      function countChar(val) {
				        var len = val.value.length;
				        if (len > 140) {
				          val.value = val.value.substring(0, 140);
				        } else {
				          $('#charNumCollection').html(len);
				        }
				        ValidateXPEntry();
				      };
				    </script>
			        <textarea id="myxp-collection-quote" class="myxp-quote materialize-textarea" onkeyup="countChar(this)" maxlength="140"><?php /*echo $exp->_quote;*/ ?></textarea>
			        <label for="myxp-collection-quote" <?php if($exp->_quote != ""){ echo "class='active'"; } ?> ><?php if($exp->_tier > 0){ ?>Update your experience (optional)<?php }else{ ?>Enter a summary of your experience here (optional)<?php } ?></label>
			        <?php if($exp->_tier > 0){ ?>
			        	<a class="waves-effect waves-light btn disabled myxp-post" style='padding: 0 1em;float:right;margin-left:50px;margin-top: -10px;'><i class="mdi-editor-mode-edit left"></i>Post</a>
			        <?php } ?>
			        <div class="myxp-quote-counter" style='float: left;margin-top: -15px;font-size:1em;'><span id='charNumCollection'>0<?php /*echo strlen($exp->_quote);*/ ?></span>/140</div>
			        <?php if($exp->_quote != ''){ ?>
				        <div class="collection-myxp-last-quote">
				        	<div style='font-weight:500;'>Last time:</div>
				        	<i><?php echo $exp->_quote; ?></i>
				        </div>
			        <?php } ?>
		        </div>
		      </div>
		    </div>
		</div>
	</div>
    <div class="row">
    	<div class="col <?php if($exp->_tier > 0 ){ ?> s12 m6<?php }else{ ?>s12<?php } ?>" <?php if($exp->_tier > 0 ){ ?>style='text-align: left;margin-left: 40px;'<?php } ?>>
    		<a class="waves-effect waves-light btn disabled myxp-save"><i class="mdi-content-save left"></i>Save</a>
			<a class="waves-effect waves-light btn myxp-cancel" style='margin-left:2em;'><i class="mdi-navigation-close left"></i>Cancel</a>
    	</div>
    </div>
	<?php }else{
		?>
		<div style='font-weight:500;'>This game doesn't have any record of what platforms you might have played it on.</div> 
		<div>It might be because the developer hasn't announced the platforms yet or it could simply be missing from our database. <br><br>Please contact support@lifebar.io and let us know if you think it's incorrect!</div>
		<?php
	}
}

function DisplayCollectionWatchedEdit($gameid, $userid){
	$exp = GetExperienceForUserByGame($userid, $gameid);
	$year = date("Y");  
	$releaseyear = $exp->_game->_year;
	?>
	<div class="row collection-myxp-watched-container">
		<div class="col s12 m6">
			<div class="row">
				<div class="col s12 m12 l10" style='text-align:left;position:relative;top:15px;'>
					<div class="collection-game-myxp-gutter"><i class="mdi-social-poll left"></i></div>
					<div class="collection-game-myxp-header">Tier</div>
					<div class="collection-game-myxp-container" >
					 	<div class="collection-myxp-tier-container">
					  	    <div class="collection-myxp-tier t5 tierBorderColor5 <?php if($exp->_tier == 5){ echo "tierBorderColor5selected myxp-selected-tier"; } ?>" data-tier='5' >5 <div class="collection-myxp-label" style='color: #DB0058;'>Worst</div></div>
						    <div class="collection-myxp-tier t4 tierBorderColor4 <?php if($exp->_tier == 4){ echo "tierBorderColor4selected myxp-selected-tier"; } ?>" data-tier='4' >4</div>
						    <div class="collection-myxp-tier t3 tierBorderColor3 <?php if($exp->_tier == 3){ echo "tierBorderColor3selected myxp-selected-tier"; } ?>" data-tier='3' >3</div>
					  	    <div class="collection-myxp-tier t2 tierBorderColor2 <?php if($exp->_tier == 2){ echo "tierBorderColor2selected myxp-selected-tier"; } ?>" data-tier='2' >2</div>
					  	    <div class="collection-myxp-tier t1 tierBorderColor1 <?php if($exp->_tier == 1){ echo "tierBorderColor1selected myxp-selected-tier"; } ?>" data-tier='1' >1 <div class="collection-myxp-label" style='left:15px;color: #0A67A3;'>Best</div></div>
					  	</div>
				  	</div>
				</div>
				<div class="col s12 m12 l10" style='text-align:left;position:relative;top:150px'>
					<div class="collection-game-myxp-gutter"><i class="mdi-action-visibility left"></i></div>
					<div class="collection-game-myxp-header">Viewing Experience</div>
					<div class="collection-game-myxp-container" style='right:50px;top: 35px;'>
			  			  <div class="col s6 m6 l4 watched-type-box <?php if($watchedxp->_length == 'Watched an hour or less' || $watchedxp->_length == 'Watched multiple hours' || $watchedxp->_length == 'Watched gameplay'){ echo 'watched-type-box-selected'; } ?>"  data-text="Watched gameplay">
			  			    <div class="watched-type-box-header"><i class="fa fa-gamepad"></i> Gameplay</div>
						    <div class="watched-type-box-desc">Gameplay from a third party, like a Let's Play or Quick Look</div>
					  	  </div >
			  			  <div class="col s6 m6 l4 watched-type-box <?php if($watchedxp->_length == 'Watched a speed run'){ echo 'watched-type-box-selected'; } ?>"  data-text="Watched a speed run">
			  			    <div class="watched-type-box-header"><i class="mdi-maps-directions-walk"></i> Speedrun</div>
						    <div class="watched-type-box-desc">A playthrough with the intent of finishing as fast as possible</div>
					  	  </div >
			  			  <div class="col s6 m6 l4 watched-type-box <?php if($watchedxp->_length == 'Watched competitive play'){ echo 'watched-type-box-selected'; } ?>"  data-text="Watched competitive play">
			  			    <div class="watched-type-box-header"><i class="mdi-hardware-headset-mic"></i> Competitive</div>
						    <div class="watched-type-box-desc">Professional level play at tournaments or league play</div>
					  	  </div >
			  			  <div class="col s6 m6 l4 watched-type-box <?php if($watchedxp->_length == 'Watched a complete single player playthrough' || $watchedxp->_length == 'Watched a complete playthrough'){ echo 'watched-type-box-selected'; } ?>"  data-text="Watched a complete playthrough">
			  			    <div class="watched-type-box-header"><i class="mdi-maps-beenhere"></i> Playthrough</div>
						    <div class="watched-type-box-desc">A complete playthrough of a game's core content</div>
					  	  </div >
  			  			  <div class="col s6 m6 l4 watched-type-box <?php if($watchedxp->_length == 'Watched trailer(s)'){ echo 'watched-type-box-selected'; } ?>"  data-text="Watched trailer(s)">
			  			    <div class="watched-type-box-header"><i class="mdi-action-theaters"></i> Trailer</div>
					  	  </div>
  			  			  <div class="col s6 m6 l4 watched-type-box <?php if($watchedxp->_length == 'Watched a developer diary'){ echo 'watched-type-box-selected'; } ?>"  data-text="Watched a developer diary">
			  			    <div class="watched-type-box-header"><i class="mdi-action-class"></i> Dev Diary</div>
					  	  </div>
  			  			  <div class="col s6 m6 l4 watched-type-box <?php if($watchedxp->_length == 'Watched promotional gameplay'){ echo 'watched-type-box-selected'; } ?>"  data-text="Watched promotional gameplay">
			  			    <div class="watched-type-box-header"><i class="mdi-image-movie-creation"></i> Promotional</div>
					  	  </div>
					</div>
				</div>
				<div class="col s12 m12 l10" style='text-align:left;position:relative;top:330px'>
					<div class="collection-game-myxp-gutter"><i class="mdi-av-videocam left"></i></div>
					<div class="collection-game-myxp-header">Source</div>
					<div class="collection-game-myxp-container" style='right:50px;'>
						<select class="myxp-view-source">
							<option  value=''>Please select</option>
							<option  value='Destructoid' <?php if($watchedxp->_source == "Destructoid"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Destructoid' && $watchedxp->_source == ""){ echo " selected"; $found = true; } ?>>Destructoid</option>
							<option  value='Edge' <?php if($watchedxp->_source == "Edge"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Edge' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Edge</option>
							<option  value='EGM' <?php if($watchedxp->_source == "EGM"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'EGM' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>EGM</option>
							<option  value='Eurogamer' <?php if($watchedxp->_source == "Eurogamer"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Eurogamer' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Eurogamer</option>
							<option  value='Game Informer' <?php if($watchedxp->_source == "Game Informer"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Game Informer' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Game Informer</option>
							<option  value='Gamesradar' <?php if($watchedxp->_source == "Gamesradar"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Gamesradar' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Gamesradar</option>
							<option  value='Gamespot' <?php if($watchedxp->_source == "Gamespot"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Gamespot' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Gamespot</option>
							<option  value='Gametrailers' <?php if($watchedxp->_source == "Gametrailers"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Gametrailers' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Gametrailers</option>
			                <option  value='Giant Bomb' <?php if($watchedxp->_source == "Giantbomb" || $watchedxp->_source == "Giant Bomb"){ echo " selected"; $found = true; }else if(($_SESSION['logged-in']->_defaultwatched == 'Giantbomb' || $_SESSION['logged-in']->_defaultwatched == "Giant Bomb") && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Giant Bomb</option>
							<option  value='IGN' <?php if($watchedxp->_source == "IGN"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'IGN' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>IGN</option>
							<option  value='Joystiq' <?php if($watchedxp->_source == "Joystiq"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Gamespot' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Joystiq</option>
							<option  value='Other' <?php if($watchedxp->_source == "Other"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Other' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Other</option>
							<option  value='Polygon' <?php if($watchedxp->_source == "Polygon"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Polygon' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Polygon</option>
							<option  value='Twitch' <?php if($watchedxp->_source == "Twitch"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Twitch' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Twitch</option>
							<option  value='Watched a Friend' <?php if($watchedxp->_source == "Watched a Friend"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Watched a Friend' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Watched a Friend</option>
							<option  value='UStream' <?php if($watchedxp->_source == "UStream"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'UStream' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>UStream</option>
							<option  value='YouTube' <?php if($watchedxp->_source == "YouTube"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'YouTube' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>YouTube</option>
							<?php if($found == false && $watchedxp->_source != ""){ ?>
								<option value='<?php echo $watchedxp->_source; ?>' selected><?php echo $watchedxp->_source; ?></option>
							<?php }else if($found == false && $_SESSION['logged-in']->_defaultwatched != ""){?>
								<option value='<?php echo $_SESSION['logged-in']->_defaultwatched; ?>' selected><?php echo $_SESSION['logged-in']->_defaultwatched; ?></option>
							<?php } ?>
						 </select>
					 </div>
				</div>
				<div class="col s12 m12 l10" style='text-align:left;position:relative;top:465px;'>
					<div class="collection-game-myxp-gutter"><i class="mdi-notification-event-note left"></i></div>
					<div class="collection-game-myxp-header">Date</div>
					<div class="collection-game-myxp-container" style="right:250px;">
					  <select class="myxp-year" name="myxp-year">
						<?php 
							if($exp->_game->_year == 0){
								$officialrelease = "";
								?>
								<option value="<?php echo $year; ?>"  selected><?php echo $year; ?> </option>
								<?php
							}else{
								$officialrelease =  ConvertDateToLongRelationalEnglish($exp->_game->_released);
								while($year >= $releaseyear && ($year - $birthyear) > 2){?>
								<option value="<?php echo $year; ?>"  <?php if($date[0] == $year){ echo "selected"; } ?>><?php echo $year; ?> <?php if($year == $releaseyear){ echo " - US Release (".$officialrelease.")"; } ?> </option>
								<?php
									$year = $year - 1;
								}
							} 
	
						 ?>
						</select>
					
					 	<?php $month = date('n');
				 			if($month > '0' && $month <= '3'){
								$quarter = "1";
							}else if($month > '3' && $month <= '6'){
								$quarter = "2";
							}else if($month > '6' && $month <= '9'){
								$quarter = "3";
							}else if($month > '9' && $month <= '12'){
								$quarter = "4";
							}else if($month == 0){
								$quarter = "0";
							}
					 	?>
					 	<div class="collection-myxp-quarter-container">
					  	    <div class="q0 <?php if($date[1] == 0){ echo "collection-myxp-quarter-selected"; }else if($quarter == 0){ echo "collection-myxp-quarter-selected"; } ?>" style="display:none" data-text="q0">Q0</div>
						    <div class="collection-myxp-quarter q1 <?php if($date[1] == 1){ echo "collection-myxp-quarter-selected"; }else if($quarter == 1){ echo "collection-myxp-quarter-selected"; } ?>" data-text="q1">Q1</div>
						    <div class="collection-myxp-quarter q2 <?php if($date[1] == 4){ echo "collection-myxp-quarter-selected"; }else if($quarter == 2){ echo "collection-myxp-quarter-selected"; } ?>" data-text="q2">Q2</div>
					  	    <div class="collection-myxp-quarter q3 <?php if($date[1] == 7){ echo "collection-myxp-quarter-selected"; }else if($quarter == 3){ echo "collection-myxp-quarter-selected"; } ?>" data-text="q3">Q3</div>
					  	    <div class="collection-myxp-quarter q4 <?php if($date[1] == 10){ echo "collection-myxp-quarter-selected"; }else if($quarter == 4){ echo "collection-myxp-quarter-selected"; } ?>" data-text="q4">Q4</div>
					  	</div>
				  	</div>
				</div>
			</div>
		</div>
		<div class="col s12 m6">
		    <div class="row">
		      <div class="input-field col s12 collection-quote-watched-container">
		  		<div class="collection-game-myxp-gutter"><i class="mdi-editor-format-quote quoteflip left" style='font-size: 1.5em;margin-top: -8px;'></i></div>
				<div class="collection-game-myxp-header">Summary</div>
				<div class="collection-game-myxp-container" style='top: 75px;'>
			  	    <script>
				      function countChar(val) {
				        var len = val.value.length;
				        if (len > 140) {
				          val.value = val.value.substring(0, 140);
				        } else {
				          $('#charNumCollection').html(len);
				        }
				        ValidateXPEntry();
				      };
				    </script>
			        <textarea id="myxp-collection-quote" class="myxp-quote materialize-textarea" onkeyup="countChar(this)" maxlength="140"><?php /*echo $exp->_quote;*/ ?></textarea>
			        <label for="myxp-collection-quote" <?php if($exp->_quote != ""){ echo "class='active'"; } ?> ><?php if($exp->_tier > 0){ ?>Update your experience (optional)<?php }else{ ?>Enter a summary of your experience here (optional)<?php } ?></label>
			        <?php if($exp->_tier > 0){ ?>
			        	<a class="waves-effect waves-light btn disabled myxp-post" style='padding: 0 1em;float:right;margin-left:50px;margin-top: -10px;'><i class="mdi-editor-mode-edit left"></i>Post</a>
			        <?php } ?>
			        <div class="myxp-quote-counter" style='float: left;margin-top: -15px;font-size:1em;'><span id='charNumCollection'>0<?php /*echo strlen($exp->_quote);*/ ?></span>/140</div>
			        <?php if($exp->_quote != ''){ ?>
				        <div class="collection-myxp-last-quote">
				        	<div style='font-weight:500;'>Last time:</div>
				        	<i><?php echo $exp->_quote; ?></i>
				        </div>
			        <?php } ?>
		        </div>
		      </div>
		    </div>
		</div>
	</div>
    <div class="row">
    	<div class="col <?php if($exp->_tier > 0 ){ ?> s12 m6<?php }else{ ?>s12<?php } ?>" <?php if($exp->_tier > 0 ){ ?>style='text-align: left;margin-left: 40px;'<?php } ?>>
    		<a class="waves-effect waves-light btn disabled myxp-save"><i class="mdi-content-save left"></i>Save</a>
			<a class="waves-effect waves-light btn myxp-cancel" style='margin-left:2em;'><i class="mdi-navigation-close left"></i>Cancel</a>
    	</div>
    </div>
	<?php 
}
