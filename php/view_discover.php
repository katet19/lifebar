<?php function DisplayDiscoverTab(){ 
	if(!HasOnboardingPrefs($_SESSION['logged-in']->_id) && $_SESSION['logged-in']->_id > 0){
		AccountDetails();
	}else{ ?>
	<div class="discover-top-level">
		<?php DisplayDynamicDiscover(); ?>
	</div>
	<?php
	}
}

function DisplayDynamicDiscover(){
	$zdepth = 25;
  	$connections = GetConnectedToList($_SESSION['logged-in']->_id);
  	$discoverItems = BuildDiscoverFlow($_SESSION['logged-in']->_id);
?>
	<div class="row discover-row">
		<?php 
			foreach($discoverItems as $item){
				if($item["DTYPE"] == "GAMELIST")
					DisplayHorizontalGameList($zdepth, $item['CATEGORY'], $item['GAMES'], $item['TYPE'], $item['COLOR'], $item['CATEGORYDESC']);
				else if($item["DTYPE"] == "USERLIST")
					DisplayHorizontalUserList($zdepth, $item['CATEGORY'], $item['USERS'], $item['TYPE'], $item['COLOR'], $item['CATEGORYDESC'], $connections);
				else if($item['DTYPE'] == 'DAILY')
					DisplayDailyHeader($zdepth, $item);
				else if($item['DTYPE'] == 'WATCHLIST')
					DisplayHorizontalWatchList($zdepth, $item);
				else if($item['DTYPE'] == 'MEMBERLIST')
					DisplayHorizontalUserWithDetailsList($zdepth, $item);
				else if($item['DTYPE'] == 'INVITEFRIENDS')
					DisplayInviteFriends($_SESSION['logged-in']->_id);
				else if($item['DTYPE'] == 'COLLECTION')
					DisplayCollectionHighlighted($_SESSION['logged-in']->_id, $item['COLLECTION']);
					
				$zdepth--;
			}
		?>
	</div>	
<?php
}

function DisplayCollectionHighlighted($userid, $collection){
	$games = $collection->_games; shuffle($games);
	if($collection->_coversmall != ''){
		$coverimage = $collection->_coversmall;
	}else{
		$coverimage = $collection->_games[0]->_imagesmall;
	}
	$user = GetUser($collection->_createdby);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){
		$username = $user->_first." ".$user->_last;
	}else{
		$username = $user->_username;
	}
	
	$conn = GetConnectedToList($_SESSION['logged-in']->_id);
	$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
	
	$totalsize = sizeof($games);
	?>
	<div class="col s12 discoverCategory discover-category-collection" style='z-index:<?php echo $zdepth--; ?>'>
		<div class="discover-collection-header" style='margin-top: 50px;'>
			<div style="height:500px;width:60%;float:left;z-index:0;background:-webkit-linear-gradient(left, rgba(0,0,0,0.5) 20%, rgba(0,0,0,1.0) 100%), url(<?php echo $coverimage; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
				<div class="collection-details-total discover-collection-user" style='cursor:pointer;display:inline-block;position:absolute;color:white;font-size: 2.5em;margin: 0;left: 15%;float: none;top: 150px;'>
					<div class="collection-details-total-num collection-total-counter" data-id="<?php echo $user->_id; ?>">
						<div class="user-avatar" style="display: inline-block;width:100px;border-radius:50%;margin-left: auto;margin-right: auto;margin-top:15px;height:100px;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
						<?php DisplayUserPreviewCard($user, $conn, $mutualconn); ?>
					</div>
					<div class='collection-details-total-lbl' style='font-size: 0.6em;font-weight: 500;margin-top:-30px;'>
						<span style='font-size: 0.7em;display: block;font-weight: 300;'>created by</span>
						<?php echo $username; ?>
						
					</div>
				</div>
			</div>
			<div style='background-color:black;width:40%;z-index:0;float:right;height:500px;'></div>
	      	<div class="discoverCategoryHeader" style='color: white;margin: 1em 1.5em 1.5em 0.5em;z-index:1;position: absolute;left: 0;right: 0;'>
	      		<div class="discoverCatName" style='border-bottom-color: white;font-weight: 400;z-index:1;'>
		      		<?php echo $collection->_name; ?> 
		      		<div class="discoverCatSubName">
	      				<?php echo $collection->_desc; ?>
	      			</div>
	  			</div>
	      	</div>
			<div class="discover-collection-owner-container" style='z-index:1;'>
				<div class="discover-collection-owner"></div>
			</div>
			<div class='discover-collection-game-list' style='z-index:1;'>
				<?php $count = 0; 
				while(sizeof($games) > $count && $count < 12){ ?>
					<a class="discover-collection-game-image z-depth-1" href="/#game/<?php echo $games[$count]->_id; ?>/<?php echo urlencode($games[$count]->_title); ?>/" data-id="<?php echo $games[$count]->_gbid; ?>" style="cursor: pointer;background: url(<?php echo $games[$count]->_imagesmall; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;" onclick="var event = arguments[0] || window.event; event.stopPropagation();"></a>
					<?php
					$count++;
				} ?>
				<div class="discover-collection-game-image-view z-depth-1" data-cid="<?php echo $collection->_id; ?>" data-ownerid="<?php echo $collection->_createdby; ?>">
					<span>View Collection</span>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function DisplayInviteFriends($userid){
	if($userid > 0){
	?>
	<div class="col s12 discoverCategory">
		<div class="row">
			<div class="discover-gnow-card col s12 m10 offset-m1 l8 offset-l2 z-depth-1">
				<div class="discover-gnow-title"><i class="mdi-action-question-answer"></i> <span>Social</span></div>
				<div class="discover-gnow-header" style="height:initial;width:100%;margin-bottom:5px;">Tell your friends & family about Lifebar to really get your activity feed flowing</div>
				<div class="btn-flat discover-gnow-action waves-effect discover-invite-users">Invite people you know</div>
			</div>
		</div>
	</div>
	<?php
	}
}

function DisplayHorizontalUserWithDetailsList($zdepth, $item){
	if($item['USERS'][0][3]->_id > 0){
	?>
		<div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
	      	<div class="discoverCategoryHeader">
	      		<div class="discoverCatName">
		      		<?php echo $item['CATEGORY']; ?>
		      		<div class="discoverCatSubName">
	      				<?php echo $item['CATEGORYDESC']; ?>
	      			</div>
	  			</div>
	      	</div>
	      	<div class="row">
				<div class="col s12">
					<?php 
					$count = 0;
					while($count < 4){
						if($item['USERS'][0][$count]->_id > 0)
							DisplayFollowUserCard($item['USERS'][0][$count], false, true, true);
						$count++;
					}?>
				</div>
			</div>
		</div>
	<?php
	}
}

function DisplayHorizontalWatchList($zdepth, $item){ 
	$videos = $item['VIDEOS']; ?>
	<div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
      	<div class="discoverCategoryHeader">
      		<div class="discoverCatName">
	      		<?php echo $item['CATEGORY']; ?>
	      		<div class="discoverCatSubName">
      				<?php echo $item['CATEGORYDESC']; ?>
      			</div>
  			</div>
      	</div>
      	<div class="row">
      		<div class="col s12 m6">
				<?php $first = true;
				foreach($videos as $video){
					$game = GetGame($video[1]);
					?>
					<div class="daily-watch-title <?php if($first){ echo "daily-watch-title-active"; } ?>" data-gameid ='<?php echo $video[1]; ?>' data-url="<?php echo $video[0]; ?>">
						<?php echo $game->_title; ?>
						<span class="daily-watch-title-xp" <?php if($first){ echo "style='display:block;'"; } ?>>ADD <i class="mdi-action-visibility"></i></span>
					</div>
					<?php
					if($first)
						$first = false;
				} ?>
				<div class="daily-watch-xp-entry" style='margin-top: -16px;'>
					
				</div>
			</div>
			<?php $first = true;
			foreach($videos as $video){
				$videoxp = GetVideoXPForGame($video[0], $video[1]);
				?>
				<div class="col s12 m6 daily-watch-video-box <?php if($first){ echo "daily-watch-video-box-active"; } ?>" data-gameid ='<?php echo $video[1]; ?>'>
					<?php DisplayEmbeddedVideo($videoxp); ?>
				</div>
			<?php if($first)
					$first = false;
				} ?>
		</div>
	</div>
<?php
}

function DisplayDailyHeader($zdepth, $item){ 
	$game = GetGame($item['OBJECTID']);
	?>
	<div class='row' style='z-index:<?php echo $zdepth--; ?>'>
	    <div class="col s12" style='padding:0;margin: -5px 0 0;'>
			<div class="daily-header-image" data-normal="-webkit-gradient(linear, left top, left bottom, color-stop(20%,rgba(0,0,0,0.0)), color-stop(100%,rgba(0,0,0,0.7)), color-stop(101%,rgba(0,0,0,0.7))), url(<?php echo $game->_image; ?>) 50% 25%" data-webkit="-webkit-gradient(linear, left top, left bottom, color-stop(20%,rgba(0,0,0,0.4)), color-stop(100%,rgba(0,0,0,0.7)), color-stop(101%,rgba(0,0,0,0.7))), url(<?php echo $game->_image; ?>) 50% 25%" style="background: -moz-linear-gradient(top, rgba(0,0,0,0.0) 20%, rgba(0,0,0,0.7) 100%, rgba(0,0,0,0.7) 101%), url(<?php echo $game->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(20%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.7)), color-stop(101%,rgba(0,0,0,0.7))), url(<?php echo $game->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;" >
				<div class="daily-header-banner">Daily Reflection Point</div>
				<div class="daily-header-question">
					<?php echo $item['QUESTION']; ?> 
					<i class="mdi-action-question-answer daily-reply-button z-depth-2"></i>
				</div>
				<div class="daily-header-game-title" data-id="<?php echo $game->_gbid; ?>">
					<?php echo $game->_title; ?>
					<?php if($_SESSION['logged-in']->_security == 'Admin'){ ?>
						<span class='btn-flat edit-ref-pt' style='margin-bottom: 0;' data-id='<?php echo $item['ID']; ?>'>Edit</span>
					<?php } ?>
				</div>
				<div class="daily-answers-results-container">
					<?php 
						if(HasFormResults($_SESSION['logged-in']->_id, $item['ID']))
							ShowFormResults($item['ID']);
					?>
				</div>
				<div class="daily-answers-container" data-type="<?php echo $item['ITEMS'][0]['Type']; ?>">
					<div class="row" style='margin-top:175px;'>
						<div class="col s10 offset-s1" style='text-align:left;'>
							<div class="daily-header-subquestion-hidden"><?php echo $item['SUBQUESTION']; ?></div>
							<?php 
								$imagehorizontal = false;
								$horizontal = false;
								if(sizeof($item['ITEMS']) >= 5 && $item['ITEMS'][0]['Type'] != 'grid-single' && $item['ITEMS'][0]['Type'] != 'grid-multi'){ $horizontal = true; }else if(sizeof($item['ITEMS']) >= 7 && ($item['ITEMS'][0]['Type'] == 'grid-single' || $item['ITEMS'][0]['Type'] == 'grid-multi')){ $imagehorizontal = true; }else{ $horizontal = false; } $first = true;
								foreach($item['ITEMS'] as $response){
									?>
									<div class="daily-item-row input-field <?php if($imagehorizontal){ ?>daily-resp-grid daily-response-item-small<?php }else if($response['Type'] == 'grid-single' || $response['Type'] == 'grid-multi'){ ?>daily-resp-grid daily-response-item-dynm-<?php echo sizeof($item['ITEMS']); } ?>" <?php if($horizontal && $response['Type'] != 'grid-single' && $response['Type'] != 'grid-multi' && $response['Type'] != 'dropdown'){ ?>style='width:40%;display:inline-block;'<?php }else if($horizontal && $response['Type'] == 'dropdown'){ ?>style='width:80%;'<?php } ?> data-objid="<?php echo $response['ObjID']; ?>" data-objtype="<?php echo $response['ObjType']; ?>" data-formitemid="<?php echo $response['ID']; ?>" data-formid="<?php echo $response['FormID']; ?>" data-gameid="<?php echo $game->_id; ?>">
										<?php if($response['Type'] == 'dropdown' && $first){ ?><select id="daily-response-dropdown"><?php } ?>
										<?php if($response['Type'] == 'radio'){ ?>
											<input type='radio' class='with-gap' name="dailyresposne" id="response<?php echo $response['ID']; ?>" <?php if($response['IsDefault'] == 'Yes'){ ?> checked <?php } ?> >
											<label for="response<?php echo $response['ID']; ?>" class="daily-response-label-radio"><?php echo $response["Choice"]; ?></label>
										<?php }else if($response['Type'] == 'dropdown'){ ?>
											<?php if($response['IsDefault'] == 'No' && $response['Type'] == 'dropdown' && $first){ ?> <option value="Please Select">Please Select</option> <?php } ?>
											<option value="<?php echo $response["ID"]; ?>"><?php echo $response["Choice"]; ?></option>
										<?php }else if($response['Type'] == 'checkbox'){ ?>
											<input type="checkbox" class='response-checkbox' id="response<?php echo $response['ID']; ?>" <?php if($response['IsDefault'] == 'Yes'){ ?> checked <?php } ?> >
											<label for="response<?php echo $response['ID']; ?>" class="daily-response-label"><?php echo $response["Choice"]; ?></label>
										<?php }else if($response['Type'] == 'grid-single'){ ?>
												<div class="knowledge-container" style='background-color:#FFF;' data-id="<?php echo $response['ID']; ?>">
													<div class="daily-pref-image z-depth-1 singlegrid daily-response-item-dynm-h-<?php echo sizeof($item['ITEMS']); ?>" style="background:url(<?php echo $response['URL']; ?>) 50% 5%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;">
														<i class="daily-checkmark fa fa-check"></i>
														<div class="daily-pref-image-title">
															<?php echo $response["Choice"]; ?>
														</div>
													</div>
												</div>
										<?php }else if($response['Type'] == 'grid-multi'){ ?>
												<div class="knowledge-container" style='background-color:#FFF;' data-id="<?php echo $response['ID']; ?>">
													<div class="daily-pref-image z-depth-1 multigrid daily-response-item-dynm-h-<?php echo sizeof($item['ITEMS']); ?>" style="background:url(<?php echo $response['URL']; ?>) 50% 5%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;">
														<i class="daily-checkmark fa fa-check"></i>
														<div class="daily-pref-image-title">
															<?php echo $response["Choice"]; ?>
														</div>
													</div>
												</div>
										<?php } ?>
									</div>
									<?php
									$first = false;
								}
							?>
							<?php if($response['Type'] == 'dropdown'){ ?></select><?php } ?>
						</div>
					<div class="col s10 offset-s1" style='margin-top: 40px;text-align:left;' >
						<div class='btn submit-daily-response'>Save</div>
						<div class='btn cancel-daily-response' style='background-color:#F44336'>Cancel</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function DisplayHorizontalGameList($zdepth, $category, $games, $type, $color, $subcategorymsg){ ?>
	    <div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
	      	<div class="discoverCategoryHeader">
	      		<i class="mdi-notification-event-note categoryIcon" style="display:none;background-color: <?php echo $color; ?>;"></i>
	      		<div class="discoverCatName" data-category="<?php echo $category; ?>">
		      		<?php echo $category; ?>
		      		<div class="discoverCatSubName">
	      				<?php echo $subcategorymsg; ?>
	      			</div>
  	      			<?php if($type != ''){ ?>
		      			<div class="ViewBtn"><a class="waves-effect waves-light btn-flat" style='padding: 0;font-weight:500;'>View more</a></div>
		      		<?php } ?>
      			</div>
	      	</div>
	      	<?php $count = 1;
	  		foreach($games as $game){
	  			DisplayGameCard($game, $count, $type);
				$count++; 
			} ?>
	    </div>
	<?php
}

function DisplayHorizontalUserList($zdepth, $category, $users, $type, $color, $subcategorymsg, $connections){ ?>
    <div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
      	<div class="discoverCategoryHeader" data-category="<?php echo $category; ?>">
    		<i class="mdi-social-whatshot categoryIcon" style="display:none;background-color: <?php echo $color; ?>;"></i>
      		<div class="discoverCatName">
	      		<?php echo $category; ?>
	      		<div class="discoverCatSubName">
      				<?php echo $subcategorymsg; ?>
      			</div>
  	  			<?php if($type != ''){ ?>
      				<div class="ViewBtn"><a class="waves-effect waves-light btn-flat" style='padding: 0;font-weight:500;'>View more</a></div>
      			<?php } ?>
  			</div>
      	</div>
      	<?php 
      	$count = 1;
  		foreach($users as $user){
  			DisplayUserCard($user, $count, "categoryResults", $connections);
			$count++; 
		} 
		?>
    </div>
<?php
}

function DisplayGameDiscover(){ 
	$userCollectionIDs = ""; //Ryan this should be a comma delimited list of Collection IDs. EX: "'1242', '4530', '5335'";
	$cat = GetDiscoverCollectionCategories($userCollectionIDs);
	$zdepth = 20;
?>
 <div class="row discover-row">
    <div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
      	<div class="discoverCategoryHeader discoverCategoryHeaderFirst" data-category="Recent Releases">
      		<i class="mdi-notification-event-note categoryIcon" style="background-color: #009688;"></i>
      		Recent Releases
      		<div class="ViewBtn"><a class="waves-effect waves-light btn" style='background-color:#009688;'>View</a></div>
      	</div>
      	<?php $recentGames = RecentlyReleasedCategory(); 
      	$count = 1;
  		foreach($recentGames as $game){
  			DisplayGameCard($game, $count, "categoryResults");
			$count++; 
		} ?>
    </div>
    <div class="col discoverDivider"></div>
    <div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
    	<?php $firstcat = $cat[0]; ?>
      	<div class="discoverCategoryHeader" data-category="Custom Category" data-name="<?php echo $firstcat[0]["Name"]; ?>" data-catid="<?php echo $firstcat[0]["ID"]; ?>" data-userid="<?php echo $firstcat[0]["Owner"]; ?>">
      		<i class="categoryIcon" style="background-color: #c62828;font-style: normal;font-weight: 500;">E3</i>
    		<?php if($firstcat[0]["Description"] != ""){ ?>
      		<div class="discoverCatName">
      			<?php echo $firstcat[0]["Name"]; ?>
      			<div class="discoverCatSubName">
      				<?php echo $firstcat[0]["Description"]; ?>
      			</div>
  			</div>
  			<?php }else{ ?>
  				<?php echo $firstcat[0]["Name"]; ?>
  			<?php } ?>
      		<div class="ViewBtnCollection"><a class="waves-effect waves-light btn" style='background-color:#c62828;'>View</a></div>
      	</div>
      	<?php 
      	$i = 0;
      	shuffle($firstcat[1]);
  		while($i < 7){
			if(isset($firstcat[1][$i]))
  				DisplayGameCard($firstcat[1][$i], $i, "categoryResults");
  			$i++;
		} ?>
    </div>
    <div class="col discoverDivider"></div>
    <div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
      	<div class="discoverCategoryHeader" data-category="Active Personalities">
    		<i class="mdi-social-whatshot categoryIcon" style="background-color: rgb(255, 126, 0);"></i>
      		Active Personalities
      		<div class="ViewBtn"><a class="waves-effect waves-light btn" style='background-color:rgb(255, 126, 0);'>View</a></div>
      	</div>
      	<?php $activePersonalities = GetActivePersonalitiesCategory();
      	$connections = GetConnectedToList($_SESSION['logged-in']->_id);
      	$count = 1;
  		foreach($activePersonalities as $user){
  			DisplayUserCard($user, $count, "categoryResults", $connections);
			$count++; 
		} 
		?>
    </div>
	<?php $suggestedGames = GetUserSuggestedGames($_SESSION['logged-in']->_id);
	if(sizeof($suggestedGames) > 0){ $game = GetGame($suggestedGames[0]->_coreid); ?>
    <div class="col s12" style='padding:0;margin: 4em 0 0em;'>
		<div class="suggestedGameBackground valign-wrapper" style="background:url(<?php echo $game->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;" >
		 <div class="row valign" style='min-width:50%'>
	        <div class="col s12 m8 l8" style='float:right'>
	          <div class="card" style='background-color: white;'>
	            <div class="card-content">
	              <span class="card-title" style='color:black;'>Have you experienced?</span>
	              <p>
	              	<?php echo $suggestedGames[0]->_desc; ?>
	              </p>
	            </div>
	            <div class="card-action" style='text-align:right' data-gbid="<?php echo $game->_gbid; ?>">
	              <div class="suggested-game-link">Add your experience</div>
	            </div>
	          </div>
	        </div>
	      </div>
		</div>
    </div>
    <?php }else{ ?>
        <div class="col discoverDivider"></div>
    <?php } ?>
    <div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
      	<div class="discoverCategoryHeader" data-category="Trending Games">
      		<i class="mdi-action-trending-up categoryIcon" style="background-color: rgb(190, 0, 255);"></i>
      		Trending Games
      		<div class="ViewBtn"><a class="waves-effect waves-light btn" style="background-color: rgb(190, 0, 255);">View</a></div>
      	</div>
      	<?php $trendingGames = GetTrendingGamesCategory();
      	$count = 1;
  		foreach($trendingGames as $game){
  			DisplayGameCard($game, $count, "categoryResults");
			$count++; 
		} 
		?>
    </div>
    <div class="col discoverDivider"></div>
    <div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
      	<div class="discoverCategoryHeader" data-category="Active Members">
      		<i class="mdi-social-people categoryIcon" style="background-color: rgb(255, 0, 97);"></i>
      		Active Members
      		<div class="ViewBtn"><a class="waves-effect waves-light btn" style="background-color: rgb(255, 0, 97);">View</a></div>
      	</div>
    	<?php $experiencedUsers = GetExperiencedUsersCategory();
      	$count = 1;
  		foreach($experiencedUsers as $user){
  			DisplayUserCard($user, $count, "categoryResults", $connections);
			$count++; 
		} ?>
    </div>
    <?php if(sizeof($suggestedGames) > 1){ $game = GetGame($suggestedGames[1]->_coreid); ?>
    <div class="col s12" style='padding:0;margin: 4em 0 0em;'>
		<div class="suggestedGameBackground valign-wrapper" style="width:100%;background:url(<?php echo $game->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;" >
		 <div class="row valign" style='min-width:50%'>
	        <div class="col s12 m8 l8" style='float:right'>
	          <div class="card" style='background-color: white;'>
	            <div class="card-content">
	              <span class="card-title" style='color:black;'>Have you experienced?</span>
	              <p>
	              	<?php echo $suggestedGames[1]->_desc; ?>
	              </p>
	            </div>
	            <div class="card-action" style='text-align:right' data-gbid="<?php echo $game->_gbid; ?>">
	              <div class="suggested-game-link">Add your experience</div>
	            </div>
	          </div>
	        </div>
	      </div>
		</div>
    </div>
    <?php }else{ ?>
    	    <div class="col discoverDivider"></div>
    <?php } ?>
    <div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
    	<?php $custcat = $cat[1]; ?>
      	<div class="discoverCategoryHeader" data-category="Custom Category" data-name="<?php echo $custcat[0]["Name"]; ?>" data-catid="<?php echo $custcat[0]["ID"]; ?>" data-userid="<?php echo $custcat[0]["Owner"]; ?>">
      		<i class="categoryIcon" style="background-color: #c62828;font-style: normal;font-weight: 500;">E3</i>
      		<?php if($custcat[0]["Description"] != ""){ ?>
      		<div class="discoverCatName">
      			<?php echo $custcat[0]["Name"]; ?>
      			<div class="discoverCatSubName">
      				<?php echo $custcat[0]["Description"]; ?>
      			</div>
  			</div>
  			<?php }else{ ?>
  				<?php echo $custcat[0]["Name"]; ?>
  			<?php } ?>
      		<div class="ViewBtnCollection"><a class="waves-effect waves-light btn" style='background-color:#c62828;'>View</a></div>
      	</div>
      	<?php 
      	$i = 0;
      	shuffle($firstcat[1]);
  		while($i < 7){
  			if(isset($custcat[1][$i]))
  				DisplayGameCard($custcat[1][$i], $i, "categoryResults");
  			$i++;
		} ?>
    </div>
    <div class="col discoverDivider"></div>
    <?php
	$newusers = GetNewUsersCategory(6); ?>
    <div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
      	<div class="discoverCategoryHeader" data-category="New Members">
      		<i class="mdi-social-people categoryIcon" style="background-color:#2E7D32;"></i>
      		New Members
      		<div class="ViewBtn"><a class="waves-effect waves-light btn" style='background-color:#2E7D32;'>View</a></div>
      	</div>
    	<?php 
      	$count = 1;
  		foreach($newusers as $user){
  			DisplayUserCard($user, $count, "categoryResults", $connections);
			$count++; 
		} ?>
    </div>
    <div class="col discoverDivider"></div>
    <div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
    	<?php $custcat = $cat[2]; ?>
      	<div class="discoverCategoryHeader" data-category="Custom Category" data-name="<?php echo $custcat[0]["Name"]; ?>" data-catid="<?php echo $custcat[0]["ID"]; ?>" data-userid="<?php echo $custcat[0]["Owner"]; ?>">
      		<i class="categoryIcon" style="background-color: #c62828;font-style: normal;font-weight: 500;">E3</i>
      		<?php if($custcat[0]["Description"] != ""){ ?>
      		<div class="discoverCatName">
      			<?php echo $custcat[0]["Name"]; ?>
      			<div class="discoverCatSubName">
      				<?php echo $custcat[0]["Description"]; ?>
      			</div>
  			</div>
  			<?php }else{ ?>
  				<?php echo $custcat[0]["Name"]; ?>
  			<?php } ?>
      		<div class="ViewBtnCollection"><a class="waves-effect waves-light btn" style='background-color:#c62828;'>View</a></div>
      	</div>
      	<?php 
      	$i = 0;
      	shuffle($firstcat[1]);
  		while($i < 7){
  			if(isset($custcat[1][$i]))
  				DisplayGameCard($custcat[1][$i], $i, "categoryResults");
  			$i++;
		} ?>
    </div>
</div>
		    
<?php }

function DisplayDiscoverCategory($category, $catid){ 
		DisplayDiscoverBackNav("");
		if($category == "Recent Releases")
			DisplayCategoryRecentReleases();
		else if($category == "Active Personalities")
			DisplayCategoryActivePersonalities();
		else if($category == "New Members")
			DisplayCategoryNewUsers();
		else if($category == "Trending Games")
			DisplayCategoryPopularGames();
		else if($category == "Active Members")
			DisplayCategoryExperienceUsers();
		else if($category == "Best Experiences")
			DisplayCategoryBestExperiences();
		else if($category == "Custom Category")
			DisplayCustomQuery($catid);
}

function DisplayCategoryRecentReleases(){
	$games = RecentlyReleased();
	$dateGroup = "";
	$count = 1;
	$first = true; 
	?>
 	<div class="row discover-row CategoryDetailsContainer" >
	<?php
	foreach($games as $game){
		if($dateGroup != $game->_released){
			if($dateGroup != ""){ echo "</div>"; $first = false; }
			$dateGroup = $game->_released;?>
			<div class="row">
		      <div class="ReleaseDateHeader col s12">
		        <div class="card-panel"  <?php if($first){ echo "style='margin:0 0 0.25em;'"; }else{ echo "style='margin-bottom:0.25em;'"; } ?> >
		          <span style=""><i class="mdi-notification-event-note categoryIcon" style="background-color: #009688;display:none;"></i> <?php echo ConvertDateToLongRelationalEnglish($game->_released); ?></span>
		        </div>
		      </div>
			<?php
		}
		
  		DisplayGameCard($game, $count, "categoryResults");
  		$count++;
	} 
	?>
	</div>
	<?php
}

function DisplayCategoryActivePersonalities(){
	$users = GetActivePersonalities();
	$connections = GetConnectedToList($_SESSION['logged-in']->_id);
	$count = 1;?>
	 	<div class="row discover-row CategoryDetailsContainer" >
	<?php
	foreach($users as $user){
		DisplayUserCard($user, $count, "categoryResults", $connections);
		$count++;
	}
	?>
	</div>
	<?php
}

function DisplayCategoryNewUsers(){
	$users = GetNewUsersCategory(15);
	$connections = GetConnectedToList($_SESSION['logged-in']->_id);
	$count = 1;?>
	 	<div class="row discover-row CategoryDetailsContainer" >
	<?php
	foreach($users as $user){
		DisplayUserCard($user, $count, "categoryResults", $connections);
		$count++;
	}
	?>
	</div>
	<?php
}

function DisplayCategoryPopularGames(){
	$games = GetTrendingGames();
	$count = 1;?>
	 	<div class="row discover-row CategoryDetailsContainer" >
	<?php
	foreach($games as $game){
  		DisplayGameCard($game, $count, "categoryResults");
		$count++;
	}
	?>
	</div>
	<?php
}

function DisplayCategoryExperienceUsers(){
	$users = GetExperiencedUsers();
	$connections = GetConnectedToList($_SESSION['logged-in']->_id);
	$count = 1;?>
	 	<div class="row discover-row CategoryDetailsContainer" >
	<?php
	foreach($users as $user){
		DisplayUserCard($user, $count, "categoryResults", $connections);
		$count++;
	}
	?>
	</div>
	<?php
	
}

function DisplayCategoryBestExperiences(){
	$games = GetBestExperiences();
	$count = 1;
	?>
 	<div class="row discover-row CategoryDetailsContainer" >
	<?php
	foreach($games as $game){
	  	DisplayGameCard($game, $count, "categoryResults");
  		$count++;
	}
	?>
	</div>
<?php 	
}

function DisplayDiscoverNavigation(){ ?>
	<div id="discover-header">
		<div class="row" style='margin:0;'>
		    <div class="col s12" style="padding:0">
		      <ul class="tabs">
  		        <li class="tab col s6"><a href="#gameContainer" id='gameContainerTab' class="active"><img src="http://lifebar.io/Images/Generic/played.png" style='width: 1.75em;margin-top: 0.75em;'></a></li>
		        <li class="tab col s6"><a href="#peopleContainer" id='peopleContainerTab'><i class="mdi-social-people small"></i></a></li>
		      </ul>
			</div>
		</div>
	</div>
<?php
}

function DisplaySearchResults($searchstring){
	$games = SearchForGame($searchstring); 
	$users = SearchForUser($searchstring);
	$connections = GetConnectedToList($_SESSION['logged-in']->_id);
	$exactmatch = true;
	$first = true; $count = 1;
	DisplayDiscoverBackNav($searchstring);
	?>
	 <div class="row discover-row searchResultsContainer">
      	<?php foreach($games as $game){ 
			if($first && $game->_gbid > 0){ $first = false; ?>
	        <div class="col s12">
		      	<div class="searchHeader" style='margin-top:0em;'>
		      		Games <span style='font-size: 0.7em;vertical-align: middle;'>(<?php echo sizeof($games); ?>)</span>
		      		<div class="SeeAllBtn GameSeeAllBtn" data-context="gameResults"><a class="waves-effect waves-light btn"><i class="mdi-action-view-module left" style='font-size: 2em;display:none;'></i>See all</a></div>
		      	</div>
	        </div>
			<?php }
            if($game->_gbid > 0){
			 DisplayGameCard($game, $count, "gameResults");
			 $count++;
            }
      	} ?>
      	
      	
    	<?php $firstsecond = true; 
    	$count = 0;
    	foreach($users as $user){ 
    		if($firstsecond){ $firstsecond = false;?>
	         <div class="col s12">
		      	<div class="searchHeader" <?php if($first){ echo "style='margin-top:0em;'"; }?> >
		      		People <span style='font-size: 0.7em;vertical-align: middle;'>(<?php echo sizeof($users); ?>)</span>
		      		<div class="SeeAllBtn UserSeeAllBtn" data-context="userResults"><a class="waves-effect waves-light btn"><i class="mdi-action-view-module left" style='font-size: 2em;display:none'></i>See all</a></div>
		      	</div>
	        </div>
			<?php }
			DisplayUserCard($user, $count, "userResults", $connections);
			$count++;
      	} 
      	
      	if($first && $firstsecond){
      		?>
  	        <div class="col s12">
		      	<div class="searchHeader" style='margin-top:0em;text-align:center;' >
		      		Searched for "<?php echo $searchstring; ?>" and nothing was found
		      	</div>
	        </div>
      		<?php	
      	}
      	?>

  	</div>
<?php
} 

function DisplayDiscoverBackNav($searchstring){ ?>
	<div class="backContainer">
		<div class="backButton waves-effect"><i class="mdi-navigation-arrow-back small" style="color:rgba(0,0,0,0.7);vertical-align:middle;padding: 0 0.5em;"></i> <a class="btn-flat backButtonLabel" style="color:rgba(0,0,0,0.7);margin: 0;padding: 0 2em;" >Search results <?php if($searchstring != ""){ echo "for '".$searchstring."'"; } ?></a></div>
	</div>
<?php }

function DisplayDiscoverSecondaryContent(){ ?>
	<div id="sideContainer" class="col s3" style='padding: 0 1.75rem;'>
	    <div class="row" style="margin-top:40px;">
	    	<?php if($_SESSION['logged-in']->_id != 0){ ?>
	    	<div class="col s12 custom-discover-category-header">
				<i class="mdi-maps-local-offer left" style='font-size:2em;'></i>
				Custom Categories
				<div style="font-size:0.7em;">
					Hand crafted categories based on your XP.
				</div>
			</div>
			<?php $customcategories = GetDiscoverQuery();
			foreach($customcategories as $cat){ ?>
		    	<div class="col s12 custom-discover-category-label" data-catid='<?php echo $cat[0]; ?>'>
		    		<?php echo $cat[1]; ?>
		    		<div style='font-size:0.9em;color:rgba(0,0,0,0.8);font-weight:normal;'><?php echo $cat[2]; ?></div>
		    	</div>
	    	<?php } ?>
	
	    	
	    	
	    	<?php }else{ ?>
		    	<div class="col s12 custom-discover-category-header">
					<i class="mdi-maps-local-offer left" style='font-size:2em;'></i>
					Custom Categories
					<div style="font-size:0.7em;">
						Discover more with unique categories
					</div>
				</div>
				<?php $customcategories = GetDiscoverQuery();
				foreach($customcategories as $cat){ ?>
			    	<div class="col s12 custom-discover-category-label" data-catid='<?php echo $cat[0]; ?>'>
			    		<?php echo $cat[1]; ?>
			    		<div style='font-size:0.9em;color:rgba(0,0,0,0.8);font-weight:normal;'><?php echo $cat[2]; ?></div>
			    	</div>
		    	<?php } ?>
	    	<?php } ?>
	    	<!--<div class="col s12 xp-latest-header">
	    		Latest Experiences
	    	</div>
	    	<div class="col s12">
	    		<?php /*DisplayGlobalLatestXP();*/ ?>
	    	</div>
	    	<div class="col s12" style='margin-top: 40px;'>
	    		<a href="#" class="waves-effect btn-large ShowAdvancedSearch"><i class="mdi-action-settings" style='vertical-align: middle;'></i> Advanced Search</a>
	    	</div>-->
	    </div>
    </div>
<?php }

function DisplayAdvancedSearchBackNav(){ ?>
	<div class="backContainerSideContent">
		<div class="backButton waves-effect waves-light"><i class="mdi-navigation-arrow-back small" style="color:#474747;vertical-align:middle;padding: 0 0.5em;"></i> <a class="btn-flat backButtonLabel" style="color:#474747;margin: 0;padding: 0 2em;" >Advanced Search</a></div>
	</div>
<?php }

function DisplayAdvancedSearch(){ 
	DisplayAdvancedSearchBackNav();
?>
	<div class="row" style="margin-top:5em;">
		<div class="input-field col s12">
	        <i class="mdi-action-search prefix"></i>
	        <input id="advanced-search-text" type="text">
	        <label for="advanced-search-text">Search Text</label>
        </div>
    </div>
	<div class="row" style="position:relative;">
		<div class="input-field col s12">
	        <i class="mdi-hardware-phonelink prefix"></i>
	        <input id="advanced-search-platform" type="text">
	        <label for="advanced-search-platform">Platforms</label>
        </div>
        <div id="typeaheadResultsPlatform" class="z-depth-2 typeaheadAdvancedSearch"></div>
    </div>
	<div class="row" style="position:relative;">
		<div class="input-field col s12">
	        <i class="mdi-action-today prefix"></i>
	        <input id="advanced-search-year" type="text">
	        <label for="advanced-search-year">Year Released</label>
        </div>
    </div>
	<div class="row" style="position:relative;">
		<div class="input-field col s12">
	        <i class="mdi-maps-local-shipping prefix"></i>
	        <input id="advanced-search-publisher" type="text">
	        <label for="advanced-search-publisher">Publisher</label>
        </div>
        <div id="typeaheadResultsPublisher" class="z-depth-2 typeaheadAdvancedSearch"></div>
    </div>
	<div class="row" style="position:relative;">
		<div class="input-field col s12">
	        <i class="mdi-hardware-keyboard prefix"></i>
	        <input id="advanced-search-developer" type="text">
	        <label for="advanced-search-developer">Developer</label>
        </div>
        <div id="typeaheadResultsDeveloper" class="z-depth-2 typeaheadAdvancedSearch"></div>
    </div>
	<div class="row" style="position:relative;">
		<div class="input-field col s12">
	        <i class="mdi-action-view-list prefix"></i>
	        <input id="advanced-search-genre" type="text">
	        <label for="advanced-search-genre">Genre</label>
        </div>
        <div id="typeaheadResultsGenre" class="z-depth-2 typeaheadAdvancedSearch"></div>
    </div>
	<div class="row" style="position:relative;">
		<div class="input-field col s12">
	        <i class="mdi-content-flag prefix"></i>
	        <input id="advanced-search-franchise" type="text">
	        <label for="advanced-search-franchise">Franchise</label>
        </div>
        <div id="typeaheadResultsFranchise" class="z-depth-2 typeaheadAdvancedSearch"></div>
    </div>
    <div class="row" style="margin-bottom:7em;">
    	<div class="col s12">
    		<a href="#" class="waves-effect btn" id="AdvancedSearchBtn">Search</a>
    	</div>
    </div>
    <?php LoadFranchises(); ?>
    <?php LoadPlatforms(); ?>
    <?php LoadPublishers(); ?>
    <?php LoadDevelopers(); ?>
    <?php LoadGenres(); ?>
<?php } 

function DisplayAdvancedSearchResults($searchstring, $platform, $year, $publisher, $developer, $genre, $franchise){
	$games = AdvancedSearchForGame($searchstring, $platform, $year, $publisher, $developer, $genre, $franchise);
	$first = true; $count = 1;
	?>
	 <div class="row discover-row searchResultsContainer">
      	<?php foreach($games as $game){ 
			if($first){ $first = false; }
			DisplayGameCard($game, $count, "gameResults");
			$count++;
      	}
      	
      	if($first){
      		?>
  	        <div class="col s12">
		      	<div class="searchHeader" style='margin-top:0em;text-align:center;' >
		      		Advance search found nothing using the filters applied
		      	</div>
	        </div>
      		<?php	
      	}
      	?>
  	</div>
<?php
} 

function DisplayCustomQuery($id){
	$games = CustomDiscoverQuery($id);
	$count = 1;
	?>
	 <div class="row discover-row searchResultsContainer">
      	<?php foreach($games as $game){ 
			DisplayGameCard($game, $count, "gameResults");
			$count++;
      	}
      	?>
  	</div>
<?php
} 
?>
