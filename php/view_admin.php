<?php
function DisplayAdmin($userid){ 
	$admindata = GetExperienceData();
	$gamedata = GetGamesData();
	$userdata = GetUsersData(); ?>
	<div class="col s12" style='margin-top:1.5em;'>
		<div class="row">
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
	              <span class="card-title"><i class="mdi-action-assignment-ind" style='margin: 0 5px;'></i> Calculate User Weave</span>
	            </div>
	            <div class="card-action">
	              <a href="#" class="admin-action admin-run-calc-user-weave">MANUAL RUN</a>
	            </div>
	          </div>
  			</div>
  			<div class="col s12 m6 l4">
	          <div class="card admin-card">
	            <div class="card-content">
	              <span class="card-title"><i class="mdi-social-pages" style='margin: 0 5px;'></i> Manage Milestones <div class="admin-counter"><?php echo GetMilestonesCount(); ?> milestones</div></span>
	            </div>
	            <div class="card-action">
	              <a href="#" class="admin-action admin-badge-new">NEW</a>
	              <a href="#" class="admin-action admin-badge-search">SEARCH/EDIT</a>
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
  			<?php if($_SESSION['logged-in']->_id == 7 || $_SESSION['logged-in']->_id == 7828){
  				$rewards = GetJoesRewards();?>
	  			<div class="col s12 m6 l4">
		          <div class="card admin-card">
		            <div class="card-content">
		              <span class="card-title"><i class="mdi-action-wallet-giftcard" style='margin: 0 5px;'></i> Joe's Rewards <div class="admin-counter"><?php echo $rewards[2]; ?>%</div></span>
		            </div>
		            <div class="card-action">
						<div>Reward: <b><?php echo $rewards[1]; ?></b></div>
		            </div>
		          </div>
	  			</div>
  			<?php } ?>
		</div>
		<?php DisplayAdminInfo($userid, $admindata, $gamedata, $userdata); ?>
	</div>
<?php
}

function DisplayAdminInfo($userid, $data, $gamedata, $userdata){
	?>
	<div class="row">
		<div class="col s6 m3 l2">
	        <div class="card-panel deep-purple darken-1">
	          <div class="white-text">
	          	<div style='  font-size: 3em;'><?php echo number_format($userdata[0]); ?></div>
	          	<div style='  font-weight: bold;'>Users</div>
	          </div>
	        </div>
		</div>
		<div class="col s6 m3 l2">
	        <div class="card-panel deep-purple darken-1">
	          <div class="white-text">
	          	<div style='  font-size: 3em;'><?php echo number_format($userdata[2]); ?></div>
	          	<div style='  font-weight: bold;'>User XP (30 Days)</div>
	          </div>
	        </div>
		</div>
		<div class="col s6 m3 l2">
	        <div class="card-panel deep-purple darken-1">
	          <div class="white-text">
	          	<div style='  font-size: 3em;'><?php echo number_format($userdata[3]); ?></div>
	          	<div style='  font-weight: bold;'>User XP (7 Days)</div>
	          </div>
	        </div>
		</div>
		<div class="col s6 m3 l2">
	        <div class="card-panel deep-purple darken-1">
	          <div class="white-text">
	          	<div style='  font-size: 3em;'><?php echo number_format($userdata[4]); ?></div>
	          	<div style='  font-weight: bold;'>User XP (24 Hours)</div>
	          </div>
	        </div>
		</div>
		<div class="col s6 m3 l2">
	        <div class="card-panel cyan darken-2">
	          <div class="white-text">
	          	<div style='  font-size: 3em;'><?php echo number_format($userdata[1]); ?></div>
	          	<div style='  font-weight: bold;'>Critics</div>
	          </div>
	        </div>
		</div>
		<div class="col s6 m3 l2">
	        <div class="card-panel cyan darken-2">
	          <div class="white-text">
	          	<div style='  font-size: 3em;'><?php echo number_format($data[0]); ?></div>
	          	<div style='  font-weight: bold;'>Total XP</div>
	          </div>
	        </div>
		</div>
		<div class="col s6 m3 l2">
	        <div class="card-panel cyan darken-2">
	          <div class="white-text">
	          	<div style='  font-size: 3em;'><?php echo number_format($gamedata[0]); ?></div>
	          	<div style='  font-weight: bold;'>Total Games</div>
	          </div>
	        </div>
		</div>
	</div>
	<?Php
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
								  	<option value="<?php echo $journalist->_id; ?>"><?php echo $journalist->_first." ".$journalist->_last; ?></option>
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
	foreach($games as $game){
		?>
		<li data-gbid='<?php echo $game->_gbid; ?>'><?php echo $game->_title." (".$game->_year.")"; ?></li>
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
?>