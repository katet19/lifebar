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
	$last = GetLastImportForUser($_SESSION['logged-in']->_id);
	
	if($importSuccess != "FAILED"){
		?>
		<div class="row" style='margin:75px 0.75rem;'>
			<div class="profile-card col s12 m6 l4 z-depth-1">
				<div class="import-modal-header">
					<i class="fa fa-steam-square"></i> Steam Import Results
				</div>
				<div class="col s12 import-results-subheader" style='margin-top: 50px;'>
					Games Imported <span class='import-mapped-total' style='font-weight:bold;float:right;margin-right: 5px;'><?php echo $totals[1]; ?></span>
					<hr>
					<span class='import-results-desc'>Games that matched our database and were directly added to either your Played or your Backlog Collections.</span>
				</div>
				<div class="col s12 import-results-subheader">
					Unmapped Games <span class='import-unmapped-total' style='font-weight:bold;float:right;margin-right: 5px;'><?php echo $totals[0]; ?></span>
					<hr>
					<span class='import-results-desc'>Games that don't have a direct match yet in our database yet, but might exist.</span>
				</div>
				<div class="col s12 import-results-subheader">
					Reported Games <span class='import-report-total' style='font-weight:bold;float:right;margin-right: 5px;'><?php echo $totals[2]; ?></span>
					<hr>
					<span class='import-results-desc'>Games are reported when we have confirmed they don't exist in our database yet or aren't a game!</span>
				</div>
				<div class="col s12 import-results-subheader">
					Last Sync'd <span style='font-weight:bold;float:right;margin-right: 5px;'><?php echo ConvertTimeStampToRelativeTime($last); ?></span>
				</div>
			</div>
			<div class="col s12 m6 l8">
				<div class="row">
					<div class="profile-card col s12 z-depth-1" style='padding:3em 0px 2em 0px !important;position:relative;'>
						<div class="import-modal-header">
							 Auto-Collections Created/Updated
						</div>
						<?php 
						$played = GetCollectionByName('Steam Played', $_SESSION['logged-in']->_id);
					  	$backlog = GetCollectionByName('Steam Backlog', $_SESSION['logged-in']->_id);
					  	 if($played != ''){
					  		DisplayCollection($played);
						 }
						 if($backlog != ''){ 
							DisplayCollection($backlog);
						 } ?>
					 </div>
				 </div>
	 		</div>
	 		<div class="btn import-steam-reimport" style='position: fixed;top: 68px;right: 10px;z-index: 10;' data-id='<?php echo $_SESSION['logged-in']->_id; ?>'>Sync your Steam Library</div>
		</div>
		<?php if($totals[0] > 0 ){ ?>
		<div class="row" style='margin-top: 100px;'>
			<div class="col s12 import-results-subheader">
				Unmapped Games <span class='import-unmapped-total' style='background-color: #D32F2F;font-weight: bold;color: white;padding: 0 5px;font-size: 0.9em;margin-left: 15px;border-radius: 5px;'><?php echo $totals[0]; ?></span>
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
					Reported Games <span class='import-report-total' style='background-color: #D32F2F;font-weight: bold;color: white;padding: 0 5px;font-size: 0.9em;margin-left: 15px;border-radius: 5px;'><?php echo $totals[2]; ?></span>
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
	$reportedGames = GetSteamReported($_SESSION['logged-in']->_id, $offSet);
	if(sizeof($reportedGames) > 0){
		$newCol = false;
		$totalReported = sizeof($reportedGames);
		$cutOff = round($totalReported  / 2);
	?>
		<div class="col s12">
			<div class="row" style='margin:10px 0;'>
				<?php if($totalReported > 25){ ?>
					<div class="col s4 import-list-header">Games</div>
					<div class="col s2 import-list-header" style='text-align:right;padding-right:5%;'># of Reports</div>
					<div class="col s4 import-list-header">Games</div>
					<div class="col s2 import-list-header" style='text-align:right;padding-right:5%;'># of Reports</div>
				<?php }else{ ?>
					<div class="col s4 import-list-header">Games</div>
					<div class="col s2 import-list-header" style='text-align:right;padding-right:5%;'># of Reports</div>
					<div class="col s6 import-list-header">&nbsp;</div>
				<?php } ?>
			</div>
		</div>
		<div class="col s6" style='text-align:left;'>
		<?php $i = 0; 
		foreach($reportedGames as $game){ 
				if($totalReported > 25 && $cutOff < $i && $newCol == false){
					?>
					</div>
					<div class="col s6" style='text-align:left;'>
					<?php
					$newCol = true;
				} ?>
				<div style='width:100%;text-align: left;padding-left: 20px;line-height: 40px;font-size: 1em;border-bottom: 1px solid rgba(0,0,0,0.3);<?php if($i % 2 == 0){?>background-color:#ddd;<?php } ?>'>
					<?php echo $game['SteamTitle']; ?>
					<div style='float:right;margin-right:5%;display:inline-block;'><?php echo $game['Priority']; ?></div>
				</div>
		<?php
			$i++;
		} 
	}
}
