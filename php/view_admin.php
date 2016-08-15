<?php
function DisplayAdmin($userid){ 
	$admindata = GetExperienceData();
	$gamedata = GetGamesData();
	$userdata = GetUsersData(); ?>
	<div class="col s12">
		<div class="row">
  			<!-- 
  			
  			XP Management
  			
  			-->
			<div class="col s12">
				<div class="admin-category-header" style='margin-top:0px;'>
					XP Management
				</div>
			</div>
			<div class="col s12 m6 l4">
	          <div class="card admin-card">
	            <div class="card-content">
	              <span class="card-title"><i class="mdi-editor-mode-comment" style='margin: 0 5px;'></i> Pending Reviews <div class="admin-counter"><?php echo $admindata[7]; ?></div></span>
	            </div>
	            <div class="card-action">
	              <a href="#" class="admin-action admin-run-feed-collector">RUN FEED COLLECTOR</a>
	              <a href="#" class="admin-action admin-manage-pending-reviews">Manage</a>
	            </div>
	          </div>
  			</div>
			<div class="col s12 m6 l4">
	          <div class="card admin-card">
	            <div class="card-content">
	              <span class="card-title"><i class="mdi-editor-mode-comment" style='margin: 0 5px;'></i> IGN Archive Reviews <div class="admin-counter"><?php echo number_format(IGNRemaining()); ?> left</div></span>
	            </div>
	            <div class="card-action">
	              <!--<a href="#" class="admin-action admin-ign-scrape">PAGE SCRAPE</a>-->
	              <a href="#" class="admin-action admin-ign-map-reviewed">UNCERTAIN</a>
	              <a href="#" class="admin-action admin-ign-map">MAP To GAMES</a>
	            </div>
	          </div>
  			</div>
			<div class="col s6 m3 l2">
		        <div class="card-panel admin-card" style='height: 140px;'>
		          <div class="cyan-text text-darken-2">
		          	<div style='  font-size: 3em;'><?php echo number_format($admindata[0]); ?></div>
		          	<div style='  font-weight: bold;'>Total XP</div>
		          </div>
		        </div>
			</div>
  			<!-- 
  			
  			Game Management
  			
  			-->
  			<div class="col s12">
				<div class="admin-category-header">
					Game Management
				</div>
			</div>
			<div class="col s12 m6 l4">
					<div class="card admin-card">
						<div class="card-content">
							<span class="card-title"><i class="mdi-action-cached" style='margin: 0 5px;'></i> Backlog Game Updater</span>
						</div>
						<div class="card-action">
							<a href="#" class="admin-action admin-run-game-updater">MANUAL RUN</a>
						</div>
					</div>
			</div>
			<div class="col s12 m6 l4">
					<div class="card admin-card">
						<div class="card-content">
							<span class="card-title"><i class="mdi-action-cached" style='margin: 0 5px;'></i> Search Cache</span>
						</div>
						<div class="card-action">
							<input id='search-cache-input' style='margin-right:10px;'> <a href="#" class="admin-action clear-search-cache-btn">CLEAR SEARCH</a>
						</div>
					</div>
			</div>
			<div class="col s12 m6 l4">
	          <div class="card admin-card">
	            <div class="card-content">
	              <span class="card-title"><i class="mdi-action-question-answer" style='margin: 0 5px;'></i> Daily Reflection Schedule</span>
	            </div>
	            <div class="card-action">
	              <a href="#" class="admin-action admin-ref-pts-sch">MANAGE SCHEDULE</a>
	            </div>
	          </div>
  			</div>
			<div class="col s12 m6 l4">
	          <div class="card admin-card">
	            <div class="card-content">
	            	<?php $reportedTotals = GetImportReported(); ?>
	              <span class="card-title"><i class="fa fa-steam" style='margin: 0 5px;'></i> Reported from Import <div class="admin-counter"><?php echo number_format($reportedTotals[1]); ?></div></span>
	            </div>
	            <div class="card-action">
            	  <div class="admin-counter" style='float:left;color:rgba(0,0,0,0.5);'><?php echo number_format($reportedTotals[0]); ?> Total</div>
	              <a href="#" class="admin-action admin-manage-reported-games">MANAGE GAMES</a>
	            </div>
	          </div>
  			</div>
			<div class="col s6 m3 l2">
		        <div class="card-panel admin-card" style='height: 140px;'>
		          <div class="cyan-text text-darken-2">
		          	<div style='  font-size: 3em;'><?php echo number_format($gamedata[0]); ?></div>
		          	<div style='  font-weight: bold;'>Total Games</div>
		          </div>
		        </div>
			</div>
			
  	  		<!-- 
  			
  			User Management
  			
  			-->
  			<div class="col s12">
				<div class="admin-category-header">
					User Management
				</div>
			</div>
			<div class="col s12 m6 l4">
	          <div class="card admin-card">
	            <div class="card-content">
	              <span class="card-title"><i class="mdi-communication-email" style='margin: 0 5px;'></i> Export Email List</span>
	            </div>
	            <div class="card-action">
	              <a href="#" class="admin-action admin-export-email">Get List</a>
	            </div>
	          </div>
  			</div>
  			<div class="col s12 m6 l4">
	          <div class="card admin-card">
	            <div class="card-content">
	              <span class="card-title"><i class="mdi-action-assignment-ind" style='margin: 0 5px;'></i> Calculate User Weave</span>
	            </div>
	            <div class="card-action">
	              <a href="#" class="admin-action admin-run-calc-user-weave">MANUAL RUN</a>
	            </div>
	          </div>
  			</div>
  			<div class="col s6 m3 l2">
		        <div class="card-panel admin-card" style='height: 140px;'>
		          <div class="deep-purple-text text-darken-1">
		          	<div style='  font-size: 3em;'><?php echo number_format($userdata[0]); ?></div>
		          	<div style='  font-weight: bold;'>Users</div>
		          </div>
		        </div>
			</div>
			<div class="col s6 m3 l2">
		        <div class="card-panel admin-card" style='height: 140px;'>
		          <div class="deep-purple-text text-darken-1">
		          	<div style='  font-size: 3em;'><?php echo number_format($userdata[1]); ?></div>
		          	<div style='  font-weight: bold;'>Critics</div>
		          </div>
		        </div>
			</div>
			<div class="col s6 m3 l2">
		        <div class="card-panel admin-card" style='height: 140px;'>
		          <div class="deep-purple-text text-darken-1">
		          	<div style='  font-size: 3em;'><?php echo number_format($userdata[4]); ?></div>
		          	<div style='  font-weight: bold;'>User XP (24 Hours)</div>
		          </div>
		        </div>
			</div>
			<div class="col s6 m3 l2">
		        <div class="card-panel admin-card" style='height: 140px;'>
		          <div class="deep-purple-text text-darken-1">
		          	<div style='  font-size: 3em;'><?php echo number_format($userdata[3]); ?></div>
		          	<div style='  font-weight: bold;'>User XP (7 Days)</div>
		          </div>
		        </div>
			</div>
			<div class="col s6 m3 l2">
		        <div class="card-panel admin-card" style='height: 140px;'>
		          <div class="deep-purple-text text-darken-1">
		          	<div style='  font-size: 3em;'><?php echo number_format($userdata[2]); ?></div>
		          	<div style='  font-weight: bold;'>User XP (30 Days)</div>
		          </div>
		        </div>
			</div>


	  		<!-- 
  			
  			Database Management
  			
  			-->
			<div class="col s12">
				<div class="admin-category-header">
					Database Management
				</div>
			</div>
  			<div class="col s12 m6 l4">
	          <div class="card admin-card">
	            <div class="card-content">
	              <span class="card-title"><i class="mdi-device-data-usage" style='margin: 0 5px;'></i> See Database Threads</span>
	            </div>
	            <div class="card-action">
	              <a href="#" class="admin-action admin-db-threads">See Live Threads</a>
	            </div>
	          </div>
  			</div>
		</div>
	</div>
<?php
}

function DisplayEmailExport(){
	$emails = GetEmailList();
	?>
	<textarea>
		<?php echo implode("\n",$emails); ?>
	</textarea>
	<?php
}

function DisplayDBThreads(){
	$threads = GetDBThreads();
	foreach($threads as $thread){ ?>
	<div>
		<div><b><?php echo $thread[0]; ?>:</b> <?php echo $thread[1]; ?></div>	
	</div>
	<?php }
}


function DisplayPendingReviews(){
	$rssfeeds = GetUnReviewedRSSFeeds();
	$alljournalists = GetJournalists(); ?>
	<div class="col s12 m6 admin-review-container">
		<div class="row" style='overflow: auto;height: 100%;  padding-top: 20px;'>
			<?php foreach($rssfeeds as $feed){ ?>
				<div class="col s12">
		          <div class="card admin-card">
		            <div class="card-content">
		              <span class="card-title"><i class="mdi-communication-chat" style='margin: 0 5px;vertical-align:middle;'></i> <?php echo $feed[1]; ?> <div style='vertical-align: middle;  display: inline-block;  font-size: 0.5em;font-weight: bold;'><a href='<?php echo $feed[2]; ?>' target='_blank'><?php echo $feed[3]; ?></a></div></span>
			          <div class="admin-import-action" style='display:none;'>
			          	<div class="row">
				          	<div class='col s12 m9 l10'>
						        <input class="admin-review-search" type="text" value="<?php echo $feed[1]; ?>">
						        <ul class='admin-review-search-results' class='dropdown-content'>
						        	
						        </ul>
					        </div>
					        <div class="col s12 m3 l2">
					        	<div class='btn admin-review-search-btn'><i class="mdi-action-search small"></i></div>
					        </div>
			        	</div>
				        <div class="row">
				          	<div class='col s12 m6'>
	  							<select id="admin-review-user" class="browser-default">
	  								<option value="NO" selected>Please select critic</option>
								  <?php 
								  foreach ($alljournalists as $journalist) { ?>
								  	<option value="<?php echo $journalist->_id; ?>"><?php echo $journalist->_first." ".$journalist->_last; if($journalist->_security == "Authenticated"){ echo " (Authenticated)"; }  ?></option>
								  <?php }?>
								</select>
			          		</div>
				          	<div class='col s6 m3'>
						        <input id="admin-review-first" type="text">
						        <label for="admin-review-first">First</label>
					        </div>
					        <div class='col s6 m3'>
						        <input id="admin-review-last" type="text">
						        <label for="admin-review-last">Last</label>
					        </div>
				        </div>
    			        <div class="row">
					        <div class='col s12 m6'>
						        <select id="admin-review-tier" class="browser-default">
						        	<option value="NO" selected>Please select tier</option>
						        	<option value="0">T0 - No Score Available</option>
						        	<option value="1">T1 - 9.1+, 91+, 5/5</option>
						        	<option value="2">T2 - 8+, 80+, 4/5</option>
						        	<option value="3">T3 - 6+, 60+, 3/5</option>
						        	<option value="4">T4 - 3+, 30+, 2/5</option>
						        	<option value="5">T5 - 0+, 0, 1/5</option>
						        </select>
					        </div>
				        </div>
	        	        <div class="col input-field s12">
					  	    <script>
						      function countChar(val) {
						        var len = val.value.length;
						        if (len > 140) {
						          val.value = val.value.substring(0, 140);
						        } else {
						          $('#charNum').html(len);
						        }
						        ValidateXPEntry();
						      };
						    </script>
					        <textarea id="admin-review-quote" class="materialize-textarea" onkeyup="countChar(this)" maxlength="140"></textarea>
					        <label for="admin-review-quote" >Enter a summary of your experience here</label>
					        <div class="myxp-quote-counter"><span id='charNum'>0</span>/140</div>
					      </div>
				       </div>
		            </div>
		            <div class="card-action">
		              <a href="#" class="admin-action admin-review-dismiss" data-id='<?php echo $feed[0]; ?>'>DISMISS</a>
		              <a href="#" class="admin-action admin-review-import" data-id='<?php echo $feed[0]; ?>' data-link='<?php echo $feed[2]; ?>'>LOAD RSS</a>
		            </div>
		          </div>
	  			</div>
			<?php } ?>
		</div>
	</div>
	<div class="col s12 m6 admin-review-iframe-container">
		<div class="row">
			<div class="card admin-card" style='  position: fixed;  top: 125px;  bottom: 10px;  right: 0;  left: 50%;'>
				<iframe id="admin-review-iframe" src="" height='100%' width='100%' style='width:100%;height:100%;'></iframe>
			</div>
		</div>
	</div>
<?php
}

function DisplayAdminGameSearchResults($search){
	$games = SearchForGame($search); 
	if(sizeof($games) > 0){
		foreach($games as $game){
			?>
			<li data-gbid='<?php echo $game->_gbid; ?>' data-image='<?php echo $game->_imagesmall; ?>'><img src='<?php echo $game->_imagesmall; ?>' style='height:50px;width:50px;margin-right: 10px;vertical-align: middle;float:left;'> <span class='actual-title'><?php echo $game->_title." (".$game->_year.")"; ?></span><span style='display:block;font-weight:300;height:20px;overflow:hidden;'><?php echo $game->_platforms; ?></span></li>
			<?php
		}
	}else{
		?>
		<div style='font-size:1.25em;margin-top:25px;font-weight:400;'>0 games were found.</div>
		<?php
	}
	
}

function DisplayAdminBadgeGameSearchResults($search){
	$games = SearchForGame($search); 
	foreach($games as $game){
		?>
		<li data-gameid='<?php echo $game->_id; ?>' data-image='<?php echo $game->_imagesmall; ?>'><?php echo $game->_title." (".$game->_year.")"; ?></li>
		<?php
	}
	
}

function DisplayManualMapping(){
	$unmapped = GetIGNUnmapped();
	$alljournalists = GetJournalists(); ?>
	<div class="col s12 admin-review-container">
		<div class="row" style='overflow: auto;height: 100%;  padding-top: 20px;'>
			<?php foreach($unmapped as $review){ ?>
				<div class="col s12 m6">
		          <div class="card admin-card">
		            <div class="card-content">
		              <span class="card-title"><i class="mdi-communication-chat" style='margin: 0 5px;vertical-align:middle;'></i> <?php echo $review['Title']; ?> <div style='vertical-align: middle;  display: inline-block;  font-size: 0.5em;font-weight: bold;'><a href='<?php echo $review['Link']; ?>' target='_blank'>Link</a></div></span>
			          <div class="admin-import-action">
			          	<div class="row">
				          	<div class='col s12 m9 l10'>
						        <input class="admin-review-search" type="text" value="<?php echo $review['Title']; ?>">
						        <ul class='admin-review-search-results dropdown-content'>
						        	
						        </ul>
					        </div>
					        <div class="col s12 m3 l2">
					        	<div class='btn admin-review-search-btn'><i class="mdi-action-search small"></i></div>
					        </div>
					        <div class='col s12'>
				        		<b>Quote:</b> <input value='<?php echo $review['Quote']; ?>' type="text" name='manualmapquote' id='map_<?php echo $review['ID']; ?>' />
				        	</div>
					        <div class='col s12'>
				        		<b>Critic:</b> <?php echo $review['AuthorName']; ?>
				        	</div>
					        <div class='col s12'>
				        		<b>Tier:</b> <?php echo $review['Tier']; ?>
				        	</div>
				        	<div class='col s12'>
				        		<b>Year Review Published:</b> <?php echo $review['Year']; ?>
				        	</div>
			        	</div>
		            </div>
		            <div class="card-action">
		           	  <a href="#" class="admin-action admin-ign-dismiss-map" data-id='<?php echo $review['ID']; ?>'>IS DUPLICATE</a>
		              <a href="#" class="admin-action admin-ign-later-map" data-id='<?php echo $review['ID']; ?>'>REVIEW LATER</a>
		              <a href="#" class="admin-action admin-ign-save-map" data-id='<?php echo $review['ID']; ?>' data-criticid='<?php echo $review['AuthorID']; ?>' data-tier="<?php echo $review['Tier']; ?>" data-link="<?php echo $review['Link']; ?>">MAP TO GAME</a>
		            </div>
		          </div>
	  			</div>
  			</div>
			<?php } ?>
		</div>
	</div>
<?php
}


function DisplayManualMappingInReview(){
	$unmapped = GetIGNUnmappedAndNeedsReview();
	$alljournalists = GetJournalists(); ?>
	<div class="col s12 admin-review-container">
		<div class="row" style='overflow: auto;height: 100%;  padding-top: 20px;'>
			<?php foreach($unmapped as $review){ ?>
				<div class="col s12 m6">
		          <div class="card admin-card">
		            <div class="card-content">
		              <span class="card-title"><i class="mdi-communication-chat" style='margin: 0 5px;vertical-align:middle;'></i> <?php echo $review['Title']; ?> <div style='vertical-align: middle;  display: inline-block;  font-size: 0.5em;font-weight: bold;'><a href='<?php echo $review['Link']; ?>' target='_blank'>Link</a></div></span>
			          <div class="admin-import-action">
			          	<div class="row">
				          	<div class='col s12 m9 l10'>
						        <input class="admin-review-search" type="text" value="<?php echo $review['Title']; ?>">
						        <ul class='admin-review-search-results dropdown-content'>
						        	
						        </ul>
					        </div>
					        <div class="col s12 m3 l2">
					        	<div class='btn admin-review-search-btn'><i class="mdi-action-search small"></i></div>
					        </div>
					        <div class='col s12'>
				        		<b>Quote:</b> <input value='<?php echo $review['Quote']; ?>' type="text" name='manualmapquote' id='map_<?php echo $review['ID']; ?>' />
				        	</div>
					        <div class='col s12'>
				        		<b>Critic:</b> <?php echo $review['AuthorName']; ?>
				        	</div>
					        <div class='col s12'>
				        		<b>Tier:</b> <?php echo $review['Tier']; ?>
				        	</div>
				        	<div class='col s12'>
				        		<b>Year Review Published:</b> <?php echo $review['Year']; ?>
				        	</div>
			        	</div>
		            </div>
		            <div class="card-action">
		              <a href="#" class="admin-action admin-ign-dismiss-map" data-id='<?php echo $review['ID']; ?>'>DISMISS</a>
		              <a href="#" class="admin-action admin-ign-save-map" data-id='<?php echo $review['ID']; ?>' data-criticid='<?php echo $review['AuthorID']; ?>' data-tier="<?php echo $review['Tier']; ?>" data-link="<?php echo $review['Link']; ?>">MAP TO GAME</a>
		            </div>
		          </div>
	  			</div>
  			</div>
			<?php } ?>
		</div>
	</div>
<?php
}

function DisplayAdminManageReportedGames(){ ?>
	<div class="row">
		<div class="col s12 import-results-subheader">
			Reported Games
		</div>
		<div class="col s12 import-results-subheader-desc">
			Games that users reported while mapping their import. 
		</div>
		<div class="import-unmapped-games-container">
			<?php DisplayReportedGamesMapping(0);?>
		</div>
	</div>
<?php
}


function DisplayReportedGamesMapping($offSet){
	$reportedGames = GetImportReportedPriority($offSet);
	if(sizeof(reportedGames) > 0){?>
		<div class="col s12">
			<div class="row" style='margin:10px 0;'>
				<div class="col s4 import-list-header">Steam</div>
				<div class="col s8 import-list-header">Lifebar</div>
			</div>
		</div>
		<?php $i = 0;
		foreach($reportedGames as $game){
			DisplayReportedGameRow($game, $i);
			$i++;
		} 
	}
}

function GetNextReportedGameRow($offSet){
	$unmappedGame = GetReportedRow($offSet);
	if(isset($unmappedGame[0])){
		DisplayReportedGameRow($unmappedGame[0], $offset);
	}
}

function DisplayReportedGameRow($game, $i){ ?>
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
				<div class="col s4" style='padding-top: 15px;overflow: hidden;height: 70px;text-align:right' data-importid='<?php echo $game['ImportID']; ?>'>
					<?php if($game['GBID'] > 0){ ?>
						<div class='btn import-map-game' data-id='<?php echo $game['AuditID']; ?>' style='background-color:#2E7D32;color:white;padding: 0 1rem;' title='Map'><i class="fa fa-check-circle btn-import-action-icon"></i> <span class='btn-import-action-text'>Map</span></div>
					<?php }else{ ?>
						<div class='btn import-disabled-game' data-id='<?php echo $game['AuditID']; ?>' style='background-color:#2E7D32;color:white;padding: 0 1rem;opacity:0.3;' title='Map'><i class="fa fa-check-circle btn-import-action-icon"></i> <span class='btn-import-action-text'>Map</span></div>
					<?php } ?>
					<div class='btn import-map-to-skip-game' data-id='<?php echo $game['AuditID']; ?>' style='background-color:#673AB7;color:white;padding: 0 1rem;' title='Trash'><i class="fa fa-trash-o btn-import-action-icon"></i></div>
				</div>
			</div>
		</div>
	<?php
}

function DisplayNewMilestoneForm(){ ?>
	<div class="col s12 admin-review-container">
		<div class="row" style='overflow: auto;height: 100%;  padding-top: 20px;'>
			<div class="col s12 m6">
		          <div class="card admin-card">
		            <div class="card-content">
		              <span class="card-title">New Milestone</span>
			          <div class="admin-import-action">
			          	<div class="row">
  					        <div class='col s12 m6'>
				        		<b>Name:</b> <input value='' type="text" name='badge_name' id='badge_name' />
				        	</div>
  					        <div class='col s12'>
				        		<b>Description:</b> <input value='' type="text" name='badge_desc' id='badge_desc' />
				        	</div>
					        <div class='col s12 m3' style='margin-top:1.5em;'>
					        	<b>Type:</b>
						        <select id="badge_type" class="browser-default" style='margin-top:1em;'>
						        	<option value="General">General</option>
						        	<option value="Connection">Connection</option>
						        	<option value="1up">1up</option>
						        	<option value="Owned">Owned</option>
						        	<option value="Bookmark">Bookmark</option>
						        	<option value="XP">XP</option>
						        	<option value="Played">Played XP</option>
						        	<option value="Watched">Watched XP</option>
						        </select>
					        </div>
					        <div class='col s12 m3' style='margin-top:1.5em;'>
					        	<b>Level:</b>
						        <select id="badge_level" class="browser-default" style='margin-top:1em;'>
						        	<option value="1">Gray (gimmies)</option>
						        	<option value="2">Green (common)</option>
						        	<option value="3">Blue (rare)</option>
						        	<option value="4">Purple (legendary)</option>
						        	<option value="5">Gold (exotic)</option>
						        </select>
					        </div>
					        <div class='col s12 m3' style='margin-top:1.5em;'>
					        	<b>Category:</b>
						        <select id="badge_category" class="browser-default" style='margin-top:1em;'>
						        	<option value="Getting Started">Getting Started</option>
						        	<option value="Franchises">Franchises</option>
						        	<option value="Platforms">Platforms</option>
						        	<option value="Gaming Knowledge">Gaming Knowledge</option>
						        	<option value="Advanced Techniques">Advanced Techniques</option>
						        	<option value="Expert Player">Expert Player</option>
						        	<option value="Video">Video</option>
						        	<option value="My Lifetime">My Lifetime</option>
						        </select>
					        </div>
					        <div class='col s12 m3' style='margin-top:1.5em;'>
					        	<b>Sub-category:</b>
						        <select id="badge_sub_category" class="browser-default" style='margin-top:1em;'>
					        		<option value="">Only select if you need to</option>
						        	<option value="Beginner">Beginner</option>
						        	<option value="Advanced">Advanced</option>
						        	<option value="Expert">Expert</option>
						        	<option value="Developers">Developers</option>
						        	<option value="Publishers">Publishers</option>
						        	<option value="Childhood">Childhood</option>
						        	<option value="Teen">Teen</option>
						        	<option value="Adult">Adult</option>
						        </select>
					        </div>
					        <div class='col s10 m8' style="margin-top:2em;">
					        	<b>Completion Threshold:</b>
					        	<input value='50' type="text" name='badge_threshold' id='badge_threshold' />
					        </div>
					        <div class='col s10 m8' style="margin-top:2em;">
					        	<b>Image:</b>
					        	<input value='http://lifebar.io/Images/Badges/PASTENAMEHERE.jpg' type="text" name='badge_custom_image' id='badge_custom_image' />
					        </div>
				          	<div class='col s2 m4'>
				          		<div class='btn badge-file-upload' style='margin-top: 4em;'><i class="mdi-file-file-upload small"></i></div>	
			          		</div>
  					        <div class='col s12'>
				        		<b>Validation SQL:</b> <input value="" type="text" name='badge_script' id='badge_script' />
				        	</div>
				          	<div class='col s12 m9 l10' id="badge_games" style="margin-top:2em;">
				          		<b>Add games:</b>
						        <input class="admin-review-search" type="text" value="">
						        <ul class='admin-review-search-results dropdown-content'>
						        	
						        </ul>
					        </div>
					        <div class="col s12 m3 l2" id="badge_games_search">
					        	<div class='btn admin-review-search-btn' style="margin-top:4em;"><i class="mdi-action-search small"></i></div>
					        </div>
				          	<div class='col s12 m9 l10' id="badge_game_list_container">
				          		<ul style="marg-left:1em;">
				          			<li><b>Game List:</b></li>
				          			<li>`GameID` in (<span class="game-badge-build-query"></span>)</li>
				          			<ul style="margin-left:1em;" id="badge_game_list">
				          			</ul>
			          			</ul>
			          		</div>
			        	</div>
		            </div>
		            <div class="card-action">
		           	  <a href="#" class="admin-action admin-new-badge-save">SAVE</a>
	              	</div>
		          </div>
	  			</div>
  			</div>
  	  		<div class="col s12 m6">
		          <div class="card admin-card">
		            <div class="card-content">
		              <span class="card-title">Milestone Preview</span>
			          <div class="admin-import-action">
			          	<div class="row">
  					        <div class='col s12 m6'>
				        		<?php DisplaySmallMilestone(GetMilestones(1, $_SESSION['logged-in']->_id)); ?>
				        	</div>
			        	</div>
		            </div>
		          </div>
	  			</div>
  			</div>
  			<div class="col s12 m6">
		          <div class="card admin-card">
		            <div class="card-content">
		              <span class="card-title">Badge Creation Guide</span>
			          <div class="admin-import-action">
			          	<div class="row">
			        		<div class='col s12'>
				        		<b>Variable Key</b> <br><hr>
				        		<pre><code>[UserID] [GameID]</code></pre>
			        		</div>
				        	<div class='col s12' style='margin-top:2em;'>
				        		<b>Example Validation SQL</b> <br><hr>
				        		Games XP on a platform: <div><code>SELECT count( * ) AS cnt FROM `Sub-Experiences` s WHERE `UserID` = [UserID]  AND `Platform` LIKE '%PlayStation 4%' and `Archived` = 'No'  GROUP BY `UserID`</code></div>
				        		Check for games in a franchise: <div><code>SELECT count( * ) AS cnt FROM `Sub-Experiences` s, `Games` g WHERE `UserID` = [UserID] AND g.`ID` = s.`GameID` AND g.`Title` LIKE '%Final Fantasy%' GROUP BY `UserID`</code></div>
				        		Check for experiences: <div><code>SELECT count( * ) AS cnt FROM `Sub-Experiences` s WHERE `UserID` = [UserID] and `Archived` = 'No' GROUP BY `UserID`</code></div>
				        		User has experienced a group of games: <div><code>SELECT count( * ) AS cnt FROM `Sub-Experiences` s WHERE `UserID` = [UserID] AND `GameID` in ([GET GAME IDS FROM SEARCHING]) GROUP BY `UserID`</code></div>
				        		User watched GiantBomb quick looks: <div><code>SELECT count( * ) AS cnt FROM `Sub-Experiences` s WHERE `UserID` = [UserID] AND `URL` like '%giantbomb.com%' AND `URL` like '%quick-look%'  AND `Type` = 'Watched' GROUP BY `UserID` , `Type`</code></div>
				        		5 or more watched XP for a single game: <div><code>SELECT count( * ) AS cnt FROM `Sub-Experiences` s WHERE `UserID` = [UserID] AND `GameID` = [GameID] AND `Type` = 'Watched' GROUP BY `UserID` , `GameID` , `Type`</code></div>
				        		Play 5 or more of Bungies games: <div><code>SELECT count( * ) AS cnt FROM `Sub-Experiences` s, `Games` g WHERE `UserID` = [UserID] AND `Archived` = 'No' AND g.`Developer` like '%Bungie%' AND g.`ID` = s.`GameID` AND s.`Type` = 'Played' GROUP BY `UserID` </code></div>
				        	</div>
			        	</div>
		            </div>
		          </div>
	  			</div>
  			</div>
		</div>
	</div>
	
<?php
}

function DisplayMilestoneManagement(){ ?>
	<div class="col s12 admin-review-container">
		<div class="row" style='overflow: auto;height: 100%;  padding-top: 20px;'>
			<?php DisplayMilestoneTree($_SESSION['logged-in']->_id); ?>
		</div>
	</div>
	
<?php
}

function DisplayRefPtSchedule(){
	?>
	<div class="row">
		<div class="col s12 import-results-subheader">
			Daily Reflection Point Scheduler
			<div class='btn admin-schedule-save-all' style="float:right;">UPDATE SCHEDULE</div>
		</div>
	</div>
	<?php
	$refPts = GetUpcomingRefPts();
	if(sizeof($refPts) > 0){
			$month = "";
			foreach($refPts as $ref){
				$weekday = Date("l", strtotime($ref['Daily']));
				$tempmonth = explode("-",$ref['Daily']);
				if($tempmonth[1] != $month){
					$monthword = Date("F", strtotime($ref['Daily']));
					?>
					<div class='import-results-subheader' style='margin: 50px 0 10px;padding-left: 20px;text-transform: uppercase;width: 100%;background-color: gray;color: white;'><?php echo $monthword; ?></div>
					<?php
					$month = $tempmonth[1];
				}
			?>
			<div class='row admin-schedule-ref-row' data-id='<?php echo $ref['ID']; ?>'>
				<div class='admin-schedule-ref-date'>
					<input class='admin-schedule-ref-date-input' type='text' style='width:100px;' value='<?php echo $ref['Daily']; ?>'>
					<span><?php echo $weekday; ?></span>
				</div>
				<div class='admin-schedule-ref-question'>
					<span><?php echo $ref['Header']; ?></span> <span>(<?php echo $ref['Title']; ?>)</span>
				</div>
				<div class='btn-flat admin-schedule-insert-remove'>remove</div>
				<div class='btn-flat admin-schedule-insert-before'>before</div>
				<div class='btn-flat admin-schedule-insert-after'>after</div>
			</div>
			<?php
			}
	}
}

function DisplayRefPtPicker($new, $search){
	$refpts = GetRefPtSearch($new, $search);
	?>
	<div class="row">
		<div class="col s8">
			<input type="text" id="ref-pt-search-picker" class="ref-pt-search-picker" value=''>
			<label for="ref-pt-search-picker">Search</label>
		</div>
		<div class="col s4">
			<input type="checkbox" id="ref-pt-search-new" class="ref-pt-search-new" checked>
			<label for="ref-pt-search-new">Unscheduled Only</label>
		</div>
	</div>
		<div class="row">
			<?php
			foreach($refpts as $pt){
			?>
			<div class="col s12" style='text-align:left;'>
				<span><?php echo $pt['Header']; ?></span> <span>(<?php echo $pt['Title']; ?>)</span>
				<div class='btn-flat ref-pt-search-select'>insert</div>
			</div>
			<?php } ?>
		</div>
	<?php
}
?>
