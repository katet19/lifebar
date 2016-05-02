<?php
function DisplayUserCollection($userid){
	$user = GetUser($userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $name = $user->_first." ".$user->_last; }else{ $name = $user->_username; }
	DisplayBackButton($name."'s Collections");
	$autocollections = GetPersonalAutoCollections($userid);
	$personalcollections = GetPersonalCollections($userid);
	?>
	<div class="row" style='margin-top:4em;margin-left:2.5%;margin-right:2.5%;text-align:left;'>
		<div class="col s12" style='border-bottom:1px solid rgba(0,0,0,0.4);'>
			<div class="row" style='margin-top:2em;'>
				<div class="col s12 ability-tracking-header">Auto Collections</div>
				<div class="col s12">Automated Collections made by Lifebar. <?php if($userid == $_SESSION['logged-in']->_id){ ?> Auto Collections are created as you add experiences and can be removed at anytime. <?php } ?></div>
				<div class="col s12">
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
		<div class="col s12">
			<div class="row" style='margin-top:2em;'>
				<div class="col s12 ability-tracking-header"><?php if($userid == $_SESSION['logged-in']->_id){ ?>Personal Collections<? }else{ echo $name;?>'s Personal Collections<?php } ?></div>
				<div class="col s12">Customized Collections made by <?php if($userid == $_SESSION['logged-in']->_id){ ?>you<?php }else{ echo $name; } ?>. 	Group games with a common theme or idea into personalized categories.</div>
				<div class="col s12">
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
	<!--
	<div class="row" style='margin-top:4em;margin-left:2.5%;margin-right:2.5%;text-align:left;padding-bottom:20px;'>
		<div class="col s12">
			<div class="row" style='margin-top:2em;'>
				<div class="col s12 ability-tracking-header"><?php if($userid == $_SESSION['logged-in']->_id){ ?>Your Followed Collections<? }else{ echo $name;?>'s Followed Collections<?php } ?></div>
				<div class="col s12"><?php if($userid == $_SESSION['logged-in']->_id){ ?>Collections you are currently following from other members. You will recieve notifications when these collections are updated.<?php }else{ echo $name." is following these Collections from other members."; } ?></div>
				<div class="col s12">
					<?php if(sizeof($collections) > 0){
						foreach($collections as $collection){
							DisplayCollection($collection);	
						}
					}else{
						echo "You haven't created any collections yet!";
					}?>
					<div class="collection-box collection-add"><i class="mdi-content-add"></i></div>
				</div>
			</div>
		</div>
	</div>
	-->
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
		?>
		<div class="backContainer" style='background:transparent;text-shadow: 1px 1px 5px rgba(0,0,0,0.3);position:absolute;top:0;'>
			<div class="backButton waves-effect waves-light"><i class="mdi-navigation-arrow-back small" style="color:white;vertical-align:middle;padding: 0 0.5em;"></i> <a class="btn-flat backButtonLabel" style="color:white;margin: 0;padding: 0;" >Back</a></div>
		</div>
		<?php
		$totalsize = GetCollectionSize($collectionID);
		if(sizeof($collection->_games) >= 1 && $collection->_cover == ''){
			$key = array_rand($collection->_games, 1);
			$one = $collection->_games[$key];
			$size = getimagesize($one->_game->_image);
			if($size[0] < 900){
				$key = array_rand($collection->_games, 1);
				$one = $collection->_games[$key];
				$size = getimagesize($one->_game->_image);
				if($size[0] < 900){
					$key = array_rand($collection->_games, 1);
					$one = $collection->_games[$key];
				}
			}
			
			$coverimage = $one->_game->_image;
		}else if($collection->_cover != ''){
			$coverimage = $collection->_cover;	
		}
	
		?>
		<div class="row collection-cover-image" style='background: url(<?php echo $coverimage; ?>) 25% 50%;background-size: cover;margin-bottom:0;height: 300px;margin-top: -15px;'>
			<div style="position:absolute;top:0px;left:0;width:100%;height:300px;background: -moz-linear-gradient(top, rgba(0,0,0,0) 40%, rgba(0,0,0,0.9) 100%, rgba(0,0,0,0.9) 101%);background:-webkit-gradient(linear, left top, left bottom, color-stop(40%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.9)), color-stop(101%,rgba(0,0,0,0.9)));z-index:3"></div>
			<div class="col s12 m6" style='z-index: 4;color: white;position: absolute;left: 0;top: 200px;'>
				<div class="collection-details-container">
					<div class="collection-details-name" data-id='<?php echo $collection->_id; ?>'>
						<?php echo $collection->_name; ?>
					</div>
					<div class="collection-details-desc">
						<?php echo $collection->_desc; ?>
					</div>
				</div>
				<?php if($edit){ ?>
					<div class="row collection-edit-mode" style='margin-top: -154px;margin-bottom: 0px; padding-top: 30px; margin-left: 50px; background-color: rgba(0,0,0,0.8);border-radius: 10px 10px 0 0;'>
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
			<?php } ?>
			<div class="col s12 m6" style='z-index:4;color:white;position: absolute;right: 0;top: 200px;'>
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
					<div class="collection-details-total-num">
						<?php echo $collection->_numOfSubs; ?>
					</div>
					<div class='collection-details-total-lbl'>
						following
					</div>
				</div>
				<div class="collection-details-total">
					<div class="collection-details-total-num">
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
					<div class="col s4 import-list-header">Game</div>
					<div class="col s3 import-list-header import-list-header-exp">Experience</div>
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
			<?php DisplayCollectionDetailGames($collection->_games, $edit); ?>
			</div>
			<?php if($edit){ ?>
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
	$collectiongames = GetCollectionGamesWithXP($collectionid, $userid, $offset, 25);
	if(($editMode == "true" || $editMode == true) && $userid == $_SESSION['logged-in']->_id)
		$editMode = true;
	else
		$editMode = false;
		
	DisplayCollectionDetailGames($collectiongames, $editMode);
}

function DisplaySearchCollection($collectionid, $search, $offset, $userid, $editMode){
	$collectiongames = GetCollectionGamesBySearch($collectionid, $search, $offset, 25, $userid);
	if(($editMode == "true" || $editMode == true) && $userid == $_SESSION['logged-in']->_id)
		$editMode = true;
	else
		$editMode = false;
		
	DisplayCollectionDetailGames($collectiongames, $editMode);
}

function DisplayCollectionDetailGames($collectiongames, $edit){
	$i = 0;
	if($collectiongames !=null && sizeof($collectiongames) > 0){
		foreach($collectiongames as $xp){
			?>
			<div class="col s12" style='height:70px;padding: 0;'>
				<div class="row" style='margin:0;position:relative;border-bottom: 1px solid rgba(0,0,0,0.3);<?php if($i % 2 == 0){?>background-color:#ddd;<?php } ?>'>
					<div class="col s12 m4 collection-game-details-container" style='padding:0;position: relative;'>
						<div class="import-game-image z-depth-1" data-id="<?php echo $xp->_game->_gbid; ?>" style="cursor: pointer;width:200px;height:69px;background: url(<?php echo $xp->_game->_imagesmall; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;" ></div>
						<div class="import-game-name" style='left:220px;padding:0;' data-id="<?php echo $xp->_game->_gbid; ?>">
							<?php echo $xp->_game->_title; ?>
						</div>
					</div>
					<div class="col s12 m8 collection-xp-details-container" style='position:relative;padding-right: 200px;'>
						<?php if($edit){ 
								DisplayRemoveOption($xp->_game->_id); 
								DisplayPinOption($xp->_game->_id);
							  } 
						?>
						<?php if(sizeof($xp->_playedxp) > 0 ||  sizeof($xp->_watchedxp) > 0){ 
							if(sizeof($xp->_playedxp) > 0){
	  					  	  	if($xp->_playedxp[0]->_completed == "101")
									$percent = 100;
								else
									$percent = $xp->_playedxp[0]->_completed;
									?>
      							 	<div class="collection-game-xp-progress-bar">
      							 		<div class="collection-game-xp-progress-filled tier<?php echo $xp->_tier; ?>BG" style='width:<? echo $percent; ?>%'></div>
      							 	</div>
  		 		  	  	       		<div class="collection-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1" style='left: <? echo $percent; ?>%;margin-left: -20px;'>
							          	<div class="collection-game-tier" title="<?php echo "Tier ".$xp->_tier." - Completed"; ?>">
						    				<i class="mdi-hardware-gamepad"></i>
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
	    								 	<div class="collection-game-xp-progress-bar"></div>
								          	<div class="collection-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1">
								          		<div class="collection-game-tier" title="<?php echo "Tier ".$xp->_tier." - ".$length; ?>">
						          					<i class="mdi-action-visibility"></i>
									          	</div>
								          	</div>
							          	

								     	  <?php }else{ ?>
								     	  	<div class="collection-game-xp-progress-bar"></div>
								      		<div class="collection-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1">
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
								}
						 }else{ ?>
						 	<div class="collection-game-xp-progress-bar"></div>
 		  	  	       		<div class="collection-game-tier-container collection-tier-container-placeholder z-depth-1">
					          	<div class="collection-game-tier" title="Not Started">
				    				<i class="mdi-hardware-gamepad"></i>
					          	</div>
				          	</div>
						 <?php } ?>
						 <div class="btn orange darken-2 collection-game-add-to-collection" data-id="<?php echo $xp->_game->_id; ?>"><i class="mdi-av-my-library-add"></i></div>
					</div>
				</div>
			</div>
			<?php
			$i++;
		}
	}else{
		?>
		<div class="col s12 no-games-found" style='height:70px;padding: 0;font-size:1.75em;font-weight:400;'>
			0 games were found
		</div>
		<?php
	}
}

function DisplayCollectionManagement($gameid, $userid){
	//Create collection
	if($gameid == -1){
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
				<div class="btn frm-collection-create-btn">Create Collection</div>
				<div class="frm-collection-validation"></div>
			</div>
		</div>
		<?php
	}else{
	//Adding a game to collection
		?>
		<div class="row">
			<div class="col s6">
				<div class="row" style="margin:0;">
					<div class="col s12">
						<div class="collection-mgmt-header">
							Add game to an existing Collection
						</div>
					</div>
				</div>
				<div class="row collection-add-to-existing-collection-container">
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
			</div>
			<div class="col s6">
				<div class="row" style="margin:0;">
					<div class="col s12">
						<div class="collection-mgmt-header">
							Create new Collection
						</div>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s11" style='width:calc(100%-2em);margin-left:2em;'>
				        <input id="frm-collection-name" class="frm-collection-name" type="text">
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
						<div class="btn frm-collection-create-btn">Create Collection & Add Game</div>
						<div class="frm-collection-validation"></div>
					</div>
				</div>
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
	<div class="col s12" style='height:70px;padding: 0;'>
		<div class="row" style='margin:0;position:relative;border-bottom: 1px solid rgba(0,0,0,0.3);'>
			<div class="col s12 m4" style='padding:0;position: relative;'>
				<div class="import-game-image z-depth-1" style="cursor: pointer;width:200px;height:69px;background: url(<?php echo $game->_imagesmall; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;" ></div>
				<div class="import-game-name" style='left:220px;padding:0;'>
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
