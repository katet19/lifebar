<?php function DisplayDiscoverTab(){ 
	if(!HasOnboardingPrefs($_SESSION['logged-in']->_id)){
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
					DisplayHorizontalGameList($zdepth, $item['CATEGORY'], $item['GAMES'], $item['TYPE'], $item['COLOR']);
				else if($item["DTYPE"] == "USERLIST")
					DisplayHorizontalUserList($zdepth, $item['CATEGORY'], $item['USERS'], $item['TYPE'], $item['COLOR'], $connections);
				
				$zdepth--;
			}
		?>
	</div>	
<?php
}

function DisplayHorizontalGameList($zdepth, $category, $games, $type, $color){ ?>
	    <div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
	      	<div class="discoverCategoryHeader" data-category="<?php echo $category; ?>">
	      		<i class="mdi-notification-event-note categoryIcon" style="background-color: <?php echo $color; ?>;"></i>
	      		<?php echo $category; ?>
	      		<div class="ViewBtn"><a class="waves-effect waves-light btn" style='background-color:<?php echo $color; ?>;'>View</a></div>
	      	</div>
	      	<?php $count = 1;
	  		foreach($games as $game){
	  			DisplayGameCard($game, $count, $type);
				$count++; 
			} ?>
	    </div>
	<?php
}

function DisplayHorizontalUserList($zdepth, $category, $users, $type, $color, $connections){ ?>
    <div class="col s12 discoverCategory" style='z-index:<?php echo $zdepth--; ?>'>
      	<div class="discoverCategoryHeader" data-category="<?php echo $category; ?>">
    		<i class="mdi-social-whatshot categoryIcon" style="background-color: <?php echo $color; ?>;"></i>
      		<?php echo $category; ?>
      		<div class="ViewBtn"><a class="waves-effect waves-light btn" style='background-color:<?php echo $color; ?>;'>View</a></div>
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
