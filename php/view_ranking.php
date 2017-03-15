<?php
require_once "includes.php";

function DisplayRanking($userid){
    $rankedlist = GetMyRankedList($userid, -1, '', '');
    $unrankedlist = GetMyUnrankedList($userid, -1, '', '');
    $count = 1; 
    ?>
    <div class="row" style='position:absolute;width:100%;'>
        <div class="rank-header-container">
        </div>
        <div class="btn-floating btn-large disabled rank-save-btn"><i class="material-icons left" style='font-size:2em;position: relative;top: 0px;'>save</i> Save</div>
        <div class="rank-list-container">
            <?php
            foreach($rankedlist as $item){ ?>
                    <div class="col s12 rank-container" draggable="true"
                        data-genre="<?php echo $item->_genre;?>"
                        data-platform="<?php echo $item->_platforms;?>"
                        data-year="<?php echo $item->_year;?>"
                        data-loaded-rank="<?php echo $item->_rank;?>"
                        data-rank="<?php echo $item->_rank;?>"
                        data-id="<?php echo $item->_id;?>"
                        data-image="<?php echo $item->_imagesmall; ?>"
                        data-xp="<?php echo $item->_xptype; ?>"
                    >
                        <div class="rank-count-container">
                        </div>
                        <div class="rank-item-container">
                            <div class="rank-item-title" style="padding-left:130px;">
                                <?php echo $item->_title; ?>
                            </div>
                        </div>
                        <div class="rank-image" style='background:url(<?php echo $item->_imagesmall; ?>) 50% 25%;'>
                        </div>
                        <div class="rank-history">
                        </div>
                    </div>
            <?php $count++; 
            }
            ?>
                <div class="col s12 rank-container rank-drag-drop-placeholder" draggable="true"
                    data-genre=""
                    data-platform=""
                    data-year=""
                    data-loaded-rank=""
                    data-rank=""
                    data-xp=""
                >
                <div class="rank-count-container">
                </div>
                <div class="rank-item-container">
                    <div class="rank-item-title">
                        <i class='material-icons left' style='position:relative;font-size:1.5em;'>add_box</i> 
                        <?php if($count == 1){ ?>
                            <span class="rank-drag-drop-text">DRAG GAME HERE TO START LIST</span>
                            <div class="rank-welcome-container">
                                <?php if(sizeof($unrankedlist) > 10){ ?>
                                    <div class="col s12 onboarding-game-step" style='text-align:left;'>
                                        <div class='onboarding-big-welcome'>Rank your games!</div>
                                        <div class='onboarding-sub-welcome'>Ok, take a deep breathe...</div>
                                        <div class='onboarding-sub-sub-welcome'><i class="material-icons onboarding-sub-welcome-icon" style='margin-bottom:125px;'>gamepad</i>Nice work rating games you have experienced. Now comes the tough part of ranking them. We recommend you start small, maybe games from last year, or your favorite RPGs and then slowly expand the list. Use the filters to your advantage to narrow the scope.</div>
                                    </div>
                                <?php }else if(sizeof($unrankedlist) > 0){ ?>
                                    <div class="col s12 onboarding-game-step" style='text-align:left;'>
                                        <div class='onboarding-big-welcome'>Rank your games!</div>
                                        <div class='onboarding-sub-welcome'>You are off to a good start, but...</div>
                                        <div class='onboarding-sub-sub-welcome'><i class="material-icons onboarding-sub-welcome-icon">star</i> Search, explore Discover or review your Activity feed for games you have experienced and give them a star rating first. Once you have given them a star, you can rank them here.<br><br>Before you start ranking, we recommend you have a good list of games already rated. Try focusing on a genre or year to get a nice list to start with.</div>
                                    </div>
                                <?php }else{ ?>
                                    <div class="col s12 onboarding-game-step" style='text-align:left;'>
                                        <div class='onboarding-big-welcome'>Rank your games!</div>
                                        <div class='onboarding-sub-welcome'>We are missing some key info to get you started</div>
                                        <div class='onboarding-sub-sub-welcome'><i class="material-icons onboarding-sub-welcome-icon">star</i> Search, explore Discover or review your Activity feed for games you have experienced and give them a star rating first. Once you have given them a star, you can rank them here.<br><br>Before you start ranking, we recommend you have a good list of games already rated. Try focusing on a genre or year to get a nice list to start with.</div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php }else{ ?> 
                            <span class="rank-drag-drop-text">DRAG HERE TO ADD TO THE BOTTOM OF LIST</span>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="rank-filter-list-container z-depth-1">
            <div class="rank-header-title z-depth-1"><i class="material-icons">filter_list</i> Filter List</div>
            <?php 
            ShowFilterList($userid);
            ?>
        </div>
        <div class="rank-unranked-list-container z-depth-1">
            <div class="rank-header-title z-depth-1"><i class="material-icons">playlist_add</i><span class="rank-header-title-count"></span> Unranked Games</div>
            <?php 
            ShowUnRankedList($unrankedlist);
            ?>
        </div>
    </div>
    <?php
}

function ShowFilterList($userid){
?>
		<ul class="collapsible tier-modal-collapsible-container" style='margin-top: 51px !important;' data-collapsible="accordion">
			<li>
				<div class="collapsible-header rank-collapsible-header filter-line-header" id="rank-filter-year" data-filter="ALL" style='font-size:1em;'>Year</div>
				<div class="collapsible-body rank-modal-body">
                        <?php $years = GetYearsByExperience($userid); 
                        if(sizeof($years) > 0){
                            foreach($years as $year){
                                ?>
                                <div class='col s12 year-dropdown-filter-item filter-line-item' data-year="<?php echo $year; ?>">
                                    <input type="checkbox" class="year-dropdown-checkbox" id="<?php echo $year; ?>" />
                                    <label for="<?php echo $year; ?>"><?php echo $year; ?></label>
                                </div>
                                <?php
                            }
                        }
                        ?>
				</div>
			</li>
            <li>
				<div class="collapsible-header rank-collapsible-header filter-line-header" id="rank-filter-genre" data-filter="ALL" style='font-size:1em;'>Genre</div>
				<div class="collapsible-body rank-modal-body">
                    <?php $genres = GetGenresByExperience($userid); 
                    if(sizeof($genres) > 0){
                        foreach($genres as $genre){
                            ?>
                                <div class='col s12 genre-dropdown-filter-item filter-line-item' data-genre="<?php echo $genre; ?>">
                                    <input type="checkbox" class="genre-dropdown-checkbox" id="<?php echo $genre; ?>" />
                                    <label for="<?php echo $genre; ?>"><?php echo $genre; ?></label>
                                </div>
                            <?php
                        }
                    }
                    ?>
				</div>
			</li>
            <li>
				<div class="collapsible-header rank-collapsible-header filter-line-header" id="rank-filter-platform" data-filter="ALL" style='font-size:1em;'>Platform</div>
				<div class="collapsible-body rank-modal-body">
                    <?php $platforms = GetPlatformsByExperience($userid); 
                        if(sizeof($platforms) > 0){
                            foreach($platforms as $platform){
                                ?>
                                <div class='col s12 platform-dropdown-filter-item filter-line-item' data-platform="<?php echo $platform; ?>">
                                    <input type="checkbox" class="platform-dropdown-checkbox" id="<?php echo $platform; ?>" />
                                    <label for="<?php echo $platform; ?>"><?php echo $platform; ?></label>
                                </div>
                                <?php
                            }
                        }
                    ?>
				</div>
			</li>
            <li>
				<div class="collapsible-header rank-collapsible-header filter-line-header" id="rank-filter-xp" data-filter="ALL" style='font-size:1em;'>Experience Details</div>
				<div class="collapsible-body rank-modal-body">
                    <div class='col s12 xp-dropdown-filter-item filter-line-item' data-xp="Played">
                        <input type="checkbox" class="xp-dropdown-checkbox" id="played" />
                        <label for="played">Played</label>
                    </div>
                    <div class='col s12 xp-dropdown-filter-item filter-line-item' data-xp="Finished">
                        <input type="checkbox" class="xp-dropdown-checkbox" id="Finished" />
                        <label for="Finished">Finished</label>
                    </div>
                    <div class='col s12 xp-dropdown-filter-item filter-line-item' data-xp"Watched">
                        <input type="checkbox" class="xp-dropdown-checkbox" id="Watched" />
                        <label for="Watched">Watched</label>
                    </div>
				</div>
			</li>
        </ul>
<?php
}

function ShowUnRankedList($unrankedlist){
	if(sizeof($unrankedlist) > 0){
		$filter = explode(",", $tierlist[0][3]);
		$count = 1;
		?>
		<ul class="collapsible tier-modal-collapsible-container" style='margin-top: 51px !important;' data-collapsible="accordion">
			<li>
				<div class="collapsible-header rank-collapsible-header <?php if($unrankedlist[0]->_tier == 1){ echo 'active'; } ?> tier1BGHover" style='font-size:1em;'><div class="rank-modal-text">0</div> <?php DisplayStarSequence(1); ?></div>
				<div class="collapsible-body rank-modal-body" style='border-bottom:2px solid #0A67A3;'>
					<?php 
					  foreach($unrankedlist as $item){
							if($item->_tier == 1){
								ShowUnRankedItem($item);
								$count++;
							}
						} ?>
				</div>
			</li>
			<li>
				<div class="collapsible-header rank-collapsible-header <?php if($unrankedlist[0]->_tier == 2){ echo 'active'; } ?> tier2BGHover" style='font-size:1em;'><div class="rank-modal-text">0</div> <?php DisplayStarSequence(2); ?></div>
				<div class="collapsible-body rank-modal-body" style='border-bottom:2px solid #00B25C;'>
					<?php 
					  foreach($unrankedlist as $item){
							if($item->_tier == 2){
								ShowUnRankedItem($item);
								$count++;
							}
						} ?>
				</div>
			</li>
			<li>
				<div class="collapsible-header rank-collapsible-header <?php if($unrankedlist[0]->_tier == 3){echo 'active'; } ?> tier3BGHover" style='font-size:1em;'><div class="rank-modal-text">0</div> <?php DisplayStarSequence(3); ?></div>
				<div class="collapsible-body rank-modal-body" style='border-bottom:2px solid #FF8E00;'>
					<?php 
					  foreach($unrankedlist as $item){
							if($item->_tier == 3){
								ShowUnRankedItem($item);
								$count++;
							}
						} ?>
				</div>
			</li>
			<li>
				<div class="collapsible-header rank-collapsible-header <?php if($unrankedlist[0]->_tier == 4){ echo 'active'; } ?> tier4BGHover" style='font-size:1em;'><div class="rank-modal-text">0</div> <?php DisplayStarSequence(4); ?></div>
				<div class="collapsible-body rank-modal-body" style='border-bottom:2px solid rgb(255, 65, 0);'>
					<?php 
					  foreach($unrankedlist as $item){
							if($item->_tier == 4){
								ShowUnRankedItem($item);
								$count++;
							}
						} ?>
				</div>
			</li>
			<li>
				<div class="collapsible-header rank-collapsible-header <?php if($unrankedlist[0]->_tier == 5){ echo 'active'; } ?> tier5BGHover" style='font-size:1em;'><div class="rank-modal-text">0</div> <?php DisplayStarSequence(5); ?></div>
				<div class="collapsible-body rank-modal-body" style='border-bottom:2px solid #DB0058;'>
					<?php 
					  foreach($unrankedlist as $item){
							if($item->_tier == 5){
								ShowUnRankedItem($item);
								$count++;
							}
						} ?>
				</div>
			</li>
		</ul>
		<?php
	}
}

function ShowUnRankedItem($item){ ?>
    <div class="col s12 rank-container" draggable="true"
        data-genre="<?php echo $item->_genre;?>"
        data-platform="<?php echo $item->_platforms;?>"
        data-year="<?php if($item->_year == 0){ echo "Unreleased"; }else{ echo $item->_year; } ?>"
        data-loaded-rank="<?php echo $item->_rank;?>"
        data-rank="<?php echo $item->_rank;?>"
        data-id="<?php echo $item->_id;?>"
        data-image="<?php echo $item->_imagesmall; ?>"
        data-xp="<?php echo $item->_xptype;  ?>"
    >
        <div class="rank-count-container">
        </div>
        <div class="rank-item-container">
            <div class="rank-item-title">
                <?php echo $item->_title; ?>
            </div>
        </div>
        <div class="rank-image">
        </div>
        <div class="rank-history">
        </div>
    </div>
    <?php
}
?>