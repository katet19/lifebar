<?php
function DisplayStartSteamImport($userid){
	$oldsteamid = GetSteamIDForUser($userid);
	?>
	<div class="import-modal-header"><i class="fa fa-steam-square"></i> Steam Import</div>
	<div class="row" style='margin-top: 50px;'>
		<div class="input-field col s12">
	        <i class="fa fa-steam prefix <?php if($oldsteamid[0] > 0){ ?>active<?php } ?>" style='margin-top: 10px;font-size: 1.5rem;'></i>
	        <input id="steamname" type="text" value="<?php if($oldsteamid[0] > 0){ echo $oldsteamid[1]; } ?>">
	        <label for="steamname" <?php if($oldsteamid[0] > 0){ ?> class="active" <?php } ?>>Steam VanityID / ProfileID</label>
		</div>
		<div class="col s12" style='text-align: left;padding-left: 60px;'>
			<div style='font-weight:500;'>How do I get my Steam VanityID or ProfileID?</div>
			<div style='padding-left:30px;margin-bottom:10px;'>Go to http://steampowered.com <a href='http://steampowered.com' target='_blank'><i class="fa fa-external-link" style='font-size: 0.8em;font-weight: bold;'></i></a> and Edit your Profile. Your VanityID can be set in the Custom URL form: <span style='font-weight:400;'>http://steamcommunity.com/id/<span class="blue-text" style='font-weight:500;'>VanityID</span></span> </div>
			<div style='padding-left:30px;'>Go to http://steamcommunity.com <a href='http://steamcommunity.com' target='_blank'><i class="fa fa-external-link" style='font-size: 0.8em;font-weight: bold;'></i></a> and view your Profile. Look at your browser address bar to see your ProfileID: <span style='font-weight:400;'>http://steamcommunity.com/profiles/<span class="blue-text" style='font-weight:500;'>7XXXXXXXXXXXXXXX</span></span></div>
		</div>
		<?php if($oldsteamid[0] > 0){ ?>
			<div class="col s12" style='margin: 30px 15% 0px 15%;text-align: left;width: 70%;'>
			 	<input type="checkbox" id="importFullReset" class='importFullReset'> 
			 	<label for="importFullReset" style='color:rgba(0,0,0,0.9);'>Clear current import data and re-import Steam Library. This will re-map based on our latest mappings and you will lose all of your ignore's.</label>
			 </div>
		<?php } ?>
	</div>
	<div class="row" style='margin-top:50px;'>
		<div class="col s12">
			<div class="btn start-steam-import"><?php if($oldsteamid[0] > 0){ ?>Sync with Steam<?php }else{ ?>Import<?php } ?></div>
			<div class="import-validation"></div>
		</div>
	</div>
	<?php
}

function DisplaySteamGameImport($steamID, $forceImport, $fullreset){
	if($fullreset == '' || $fullreset == "false")
		$fullreset = false;
	
	$importSuccess = '';
	if(!CheckForExistingImport($_SESSION['logged-in']->_id) || $forceImport == "true")
		$importSuccess = ImportLibraryForSteamUser($steamID, $fullreset);
	$totals = GetSteamTotals($_SESSION['logged-in']->_id);
	DisplayBackButton("Import Steam Library");
	
	if($importSuccess != "FAILED"){
		?>
		<div class="row">
			<div class="col s12 import-results-subheader" style='margin-top: 75px;'>
				Total Games Imported <span class='import-mapped-total' style='font-weight:bold;'><?php echo $totals[1]; ?></span>
				<div class="btn import-steam-reimport" style='float:right;font-size: 0.6em;' data-id='<?php echo $_SESSION['logged-in']->_id; ?>'>Sync your Steam Library</div>
			</div>
			<?php 
			$played = GetCollectionByName('Steam Played',$_SESSION['logged-in']->_id); 
		  	$backlog = GetCollectionByName('Steam Backlog',$_SESSION['logged-in']->_id);
		  	if($played != ''){
				DisplayCollection($played);
			 } ?>
			
			<?php if($backlog != ''){ 
				DisplayCollection($backlog);
			 } ?>
			
			<!-- OLD WAY
			<div class="steam-collections-container">
				
			</div>
			<div class="import-mapped-games-container">
				<?php /*DisplayMappedGames(0);*/ ?>
			</div>
			<div class="import-results-paging">
				<div class="import-results-pagination">
					<div class='btn mapped-prev'><i class="fa fa-chevron-left"></i></div>
				</div>
				<div class="import-results-desc">
					<span class="import-results-offset" data-offset="0">0 - 25</span> of <span class='import-results-total'><?php echo $totals[1]; ?></span>
				</div>
				<div class="import-results-pagination">
					<div class='btn mapped-next'><i class="fa fa-chevron-right"></i></div>
				</div>
			</div> -->
		</div>
		<?php if($totals[0] > 0 ){ ?>
		<div class="row" style='margin-top: 100px;'>
			<div class="col s12 import-results-subheader">
				Unmapped Games <span class='import-unmapped-total' style='font-weight:bold;'><?php echo $totals[0]; ?></span>
			</div>
			<div class="col s12 import-results-subheader-desc">
				We were unable to locate an exact match for the following games in our database. Please help us build our mapping catalog by manually selecting the correct match or by reporting that there is not a match in our database.
			</div>
			<div class="import-unmapped-games-container">
				<?php DisplayUnMappedGames(0); ?>
			</div>
		</div>
		<?php } ?>
		<?php if($totals[2] > 0 ){ ?>
			<div class="row" style='margin-top: 100px;'>
				<div class="col s12 import-results-subheader">
					Reported Games <span class='import-report-total' style='font-weight:bold;'><?php echo $totals[2]; ?></span>
				</div>
				<div class="col s12 import-results-subheader-desc">
					We are unable to map these games from your Library because they don't exist in our database yet! We are working hard to get these games added as soon as we can.
				</div>
				<div class="import-reported-games-container">
					<?php DisplayReportedGames(0); ?>
				</div>
			</div>
		<?php
		}
	}else{
		?>
		<div class="row">
			<div class="col s12 import-results-subheader" style='margin-top: 75px;padding: 0 40px !important;font-size:1.5em;'>
				<i class="fa fa-exclamation-triangle" style='color:#B71C1C'></i> Unable to import Steam Library with that Profile ID or Vanity ID. Please try again!
				<div class="btn import-steam-reimport" style='float:right;font-size: 0.6em;' data-id='<?php echo $_SESSION['logged-in']->_id; ?>'>Retry Importing your Steam Library</div>
			</div>
		</div>
		<?php
	}
}

function DisplayMappedGames($offSet){
	$mappedGames = GetSteamMapped($_SESSION['logged-in']->_id, $offSet);
	if(sizeof($mappedGames) > 0){?>
		<div class="col s12">
			<div class="row" style='margin:10px 0;'>
				<div class="col s12 import-list-header">Game</div>
			</div>
		</div>
		<?php $i = 0;
		foreach($mappedGames as $game){
			$xp = GetExperienceForUserComplete($_SESSION['logged-in']->_id, $game['GameID']);
			?>
			<div class="col s12" style='height:70px;'>
				<div class="row" style='margin:0;position:relative;border-bottom: 1px solid rgba(0,0,0,0.3);<?php if($i % 2 == 0){?>background-color:#ddd;<?php } ?>'>
					<div class="col s3" style='padding:0;position: relative;'>
						<img class="import-game-image z-depth-1" src="<?php echo $game['ImportImage']; ?>" >
						<div class="import-game-name">
							<?php echo $game['Title']; ?>
						</div>
					</div>
					<div class="col s2">
						<div class='import-time-played'><?php echo $game['TimePlayed']; ?> minutes</div>
					</div>
					<div class="col s1" style='position:relative;min-width:100px;'>
						<?php if(sizeof($xp->_playedxp) > 0 ||  sizeof($xp->_watchedxp) > 0){ 
							if(sizeof($xp->_playedxp) > 0){
  						  	  	if($xp->_playedxp[0]->_completed == "101")
									$percent = 100;
								else
									$percent = $xp->_playedxp[0]->_completed;
									
								if($percent == 100){ ?>
				  	  	       		<div class="card-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1" style='position: relative;top: 13px;right: inherit;'>
							          	<div class="card-game-tier" title="<?php echo "Tier ".$xp->_tier." - Completed"; ?>">
						    				<i class="mdi-hardware-gamepad"></i>
							          	</div>
					          	<?php }else{ ?>
					          		<div class="card-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1" style='position: relative;top: 13px;right: inherit;'>
					      			  	<div class="c100 mini <?php if($xp->_tier == 1){ echo "tierone"; }else if($xp->_tier == 2){ echo "tiertwo"; }else if($xp->_tier == 3){ echo "tierthree"; }else if($xp->_tier == 4){ echo "tierfour"; }else if($xp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$xp->_tier." - ".$percent."% finished"; ?>" style='background-color:white;'>
									  	  <span class='tierTextColor<?php echo $xp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-hardware-gamepad"></i></span>
										  <div class="slice">
										    <div class="bar minibar"></div>
										    <div class="fill"></div>
										  </div>
										</div>
					          	<?php } ?>
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
								          <div class="card-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1" style='position: relative;top: 13px;right: inherit;'>
								          	<div class="card-game-tier" title="<?php echo "Tier ".$xp->_tier." - ".$length; ?>">
								          			<i class="mdi-action-visibility"></i>
								          	</div>
										   </div>
								     	  <?php }else{ ?>
								      		<div class="card-game-tier-container tier<?php echo $xp->_tier; ?>BG z-depth-1" style='position: relative;top: 13px;right: inherit;'>
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
						 &nbsp;
						 <?php } ?>
					</div>
					<div class="col s2">
						<?php if($game['TimePlayed'] > 0){ ?>
							<div class='import-collection-mapped-msg'>Added to '<b>Steam Played</b>' Collection</div>
						<?php }else{ ?>
							<div class='import-collection-mapped-msg'>Added to '<b>Steam Backlog</b>' Collection</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
			$i++;
		} 
	}
}

function DisplayUnMappedGames($offSet){
	$unmappedGames = GetSteamUnMapped($_SESSION['logged-in']->_id, $offSet);
	if(sizeof($unmappedGames) > 0){?>
		<div class="col s12">
			<div class="row" style='margin:10px 0;'>
				<div class="col s4 import-list-header">Steam</div>
				<div class="col s8 import-list-header">Lifebar</div>
			</div>
		</div>
		<?php $i = 0;
		foreach($unmappedGames as $game){
			DisplayUnmappedGameRow($game, $i);
			$i++;
		} 
	}
}

function GetNextUnmappedGameRow($offSet){
	$unmappedGame = GetSteamUnMappedRow($_SESSION['logged-in']->_id, $offSet);
	if(isset($unmappedGame[0])){
		DisplayUnmappedGameRow($unmappedGame[0], $offset);
	}
}

function DisplayUnmappedGameRow($game, $i){ ?>
	<div class="col s12" style='height:70px;'>
			<div class="row" style='margin:0;position:relative;border-bottom: 1px solid rgba(0,0,0,0.3);<?php if($i % 2 == 0){?>background-color:#ddd;<?php } ?>'>
				<div class="col s4" style='padding:0;overflow: hidden;height: 70px;position: relative;z-index: 0;'>
					<img class="import-game-image z-depth-1" src="<?php echo $game['ImportImage']; ?>" >
					<div class="import-game-name">
						<?php echo $game['SteamTitle']; ?>
					</div>
				</div>
				<div class="col s2" style='position:relative;height: 70px;z-index:0;'>
			        <ul class='import-game-search-results'>
			        	<?php if($game['GBID'] > 0){ ?>
			        		<li class='import-game-search-selected' data-gbid='<?php echo $game['GBID']; ?>'><?php echo $game['Title']." (".$game['Year'].")"; ?></li>
			        	<?php } ?>
			        </ul>
		          	<div class="row">
			          	<div class='col s12 m9 l10'>
					        <input class="import-game-search" type="text" value="<?php if($game['GBID'] > 0){ echo $game['Title']." (".$game['Year'].")"; }else{ echo $game['SteamTitle']; } ?>">
				        </div>
				        <div class="col s12 m3 l2" style='padding:0;'>
				        	<div class='btn-flat import-game-search-btn' style='margin-top: 15px;padding-left: 0px;color: rgba(0,0,0,0.6);'><i class="mdi-action-search"></i></div>
				        </div>
		        	</div>
				</div>
				<div class="col s2" style='overflow: hidden;height: 70px;'>
					<?php if($game['GBID'] > 0){ ?>
						<div class='import-map-suggest-image' data-image="<?php echo $game['LifebarImage']; ?>" style='height:69px;width:200px;background:url(<?php echo $game['LifebarImage']; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;'></div>
					<?php }else{ ?>
						<div class="import-warning-msg">
							<i class='mdi-content-report'></i>
						</div>
						<div class="import-warning-msg-txt">
							Search for a match
						</div>
					<?php } ?>
				</div>
				<div class="col s4" style='padding-top: 15px;overflow: hidden;height: 70px;' data-importid='<?php echo $game['ImportID']; ?>'>
					<?php if($game['GBID'] > 0){ ?>
						<div class='btn import-map-game' data-id='<?php echo $game['AuditID']; ?>' style='background-color:#2E7D32;color:white;padding: 0 1rem;' title='Map'><i class="fa fa-check-circle btn-import-action-icon"></i> <span class='btn-import-action-text'>Map</span></div>
					<?php }else{ ?>
						<div class='btn import-disabled-game' data-id='<?php echo $game['AuditID']; ?>' style='background-color:#2E7D32;color:white;padding: 0 1rem;opacity:0.3;' title='Map'><i class="fa fa-check-circle btn-import-action-icon"></i> <span class='btn-import-action-text'>Map</span></div>
					<?php } ?>
					<div class='btn import-ignore-game' data-id='<?php echo $game['AuditID']; ?>' style='background-color:#C62828;color:white;padding: 0 1rem;' title='Ignore'><i class="fa fa-ban btn-import-action-icon"></i> <span class='btn-import-action-text'>Ignore</span></div>
					<div class='btn import-report-game' data-id='<?php echo $game['AuditID']; ?>' style='background-color:#EF6C00;color:white;padding: 0 1rem;' title='Report'><i class="fa fa-exclamation-triangle btn-import-action-icon"></i> <span class='btn-import-action-text'>Report</span></div>
					<?php if($_SESSION['logged-in']->_security == "Admin"){ ?>
						<div class='btn import-map-to-skip-game' data-id='<?php echo $game['AuditID']; ?>' style='background-color:#673AB7;color:white;padding: 0 1rem;' title='Trash'><i class="fa fa-trash-o btn-import-action-icon"></i></div>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php
}

function DisplayReportedGames($offSet){
	$unmappedGames = GetSteamReported($_SESSION['logged-in']->_id, $offSet);
	if(sizeof($unmappedGames) > 0){?>
		<div class="col s12">
			<div class="row" style='margin:10px 0;'>
				<div class="col s12 import-list-header">Steam</div>
			</div>
		</div>
		<?php $i = 0;
		foreach($unmappedGames as $game){ ?>
			<div class="col s12" style='height:70px;'>
				<div class="row" style='margin:0;position:relative;border-bottom: 1px solid rgba(0,0,0,0.3);<?php if($i % 2 == 0){?>background-color:#ddd;<?php } ?>'>
					<div class="col s4" style='padding:0;overflow: hidden;height: 70px;position: relative;z-index: 0;'>
						<img class="import-game-image z-depth-1" src="<?php echo $game['ImportImage']; ?>" >
						<div class="import-game-name">
							<?php echo $game['SteamTitle']; ?>
						</div>
					</div>
					<div class="col s3">
						<div class="import-game-name">
							Reported <?php echo ConvertTimeStampToRelativeTime($game['ReportedOn']); ?>
						</div>
					</div>
				</div>
			</div>
		<?php
			$i++;
		} 
	}
}
