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
                    <div class="rank-container" draggable="true"
                        data-genre="<?php echo $item->_genre;?>"
                        data-platform="<?php echo $item->_platforms;?>"
                        data-year="<?php echo $item->_year;?>"
                        data-loaded-rank="<?php echo $item->_rank;?>"
                        data-rank="<?php echo $item->_rank;?>"
                        data-id="<?php echo $item->_id;?>"
                        data-gbid="<?php echo $item->_gbid;?>"
                        data-image="<?php echo $item->_imagesmall; ?>"
                        data-xp="<?php echo $item->_xptype; ?>"
                    >
                        <div class="rank-count-container">
                        </div>
                        <div class="rank-item-container">
                            <div class="rank-item-title rank-item-title-w-image">
                                <?php echo $item->_title; ?>
                            </div>
                        </div>
                        <div class="rank-image" style='background:url(<?php echo $item->_imagesmall; ?>) 50% 25%;'></div>
                        <div class="rank-history"></div>
                        <div class="rank-remove-btn">REMOVE</div>
                    </div>
            <?php $count++; 
            }
            ?>
                <div class="rank-container rank-drag-drop-placeholder" draggable="true"
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
                        <?php }else{ ?> 
                            <span class="rank-drag-drop-text">DRAG HERE TO ADD TO THE BOTTOM OF LIST</span>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
        <?php if(sizeof($rankedlist) == 0){ ?>
            <div class="rank-welcome-container">
                <div class="col s12 onboarding-game-step" style='text-align:left;'>
                    <div class='onboarding-big-welcome' style='font-size: 1.5em;margin-toP: 0px;'>Build your All-Time Ranked List!</div>
                    <?php if(sizeof($unrankedlist) > 25){ ?>
                        <div class='onboarding-sub-sub-welcome'><i class='material-icons green-text left' style='font-size:1.5em;'>thumb_up</i> Ready to go!</div>
                        <div class='onboarding-sub-sub-welcome'>You have a healthy list of games rated & ready to be ranked. Use the filters <i class='material-icons indigo-text' style='font-size:1.5em;position: relative;top: 5px;'>filter_list</i> on the right to help narrow your view and start ranking your personal list!</div>
                    <?php }else if(sizeof($unrankedlist) > 5){ ?>
                        <div class='onboarding-sub-sub-welcome'><i class='material-icons orange-text left'  style='font-size:1.5em;'>warning</i> You are off to a good start, but...</div>
                        <div class='onboarding-sub-sub-welcome'>Before you start ranking, we recommend you have a good list of games already rated. Focusing on a genre, year or your Lifebar backlog will help you build a list of games that are ready to be ranked.</div>
                    <?php }else{ ?>
                        <div class='onboarding-sub-sub-welcome'><i class='material-icons red-text left'  style='font-size:1.5em;'>warning</i> We are missing some key info to get you started</div>
                        <div class='onboarding-sub-sub-welcome'>Explore <i class='material-icons indigo-text' style='font-size: 1em;position: relative;top: 2px;margin-right: -2px;margin-left: 3px;'>explore</i> Discover, review your <i class='material-icons indigo-text' style='font-size: 1em;position: relative;top: 2px;margin-right: -2px;margin-left: 3px;'>whatshot</i> Activity feed or search for games you have experienced and give them a star rating first. Once you have given them a star rating, you can rank them here.</div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <div class="rank-current-selection"></div>
        <div class="rank-filter-list-container z-depth-1">
            <div class="rank-header-title z-depth-1"><i class="material-icons">filter_list</i> Filter List</div>
            <?php 
            ShowFilterList($userid, sizeof($rankedlist));
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

function ShowProfileRankingList($rankedlist){
    $count = 1; 
    ?>
    <div class="rank-list-container-profile">
            <?php
            foreach($rankedlist as $item){ ?>
                    <div class="rank-container-profile"
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
                            <div class='rank-count-main'><?php echo $count; ?></div>
                        </div>
                        <div class="rank-item-container">
                            <div class="rank-item-title rank-item-title-w-image">
                                <?php echo $item->_title; ?>
                            </div>
                        </div>
                        <div class="rank-image" style='background:url(<?php echo $item->_imagesmall; ?>) 50% 25%;background-size:cover;'></div>
                        <div class="rank-history"></div>
                    </div>
            <?php $count++; 
            }
            ?>
        </div>
    <?php
}

function ShowActivityRankingList($rankedlist){
    $currPos = "-1";
    ?>
    <div class="rank-list-container-activity">
            <?php
            foreach($rankedlist as $item){
                    if($item[1] != $currPos + 1 && $currPos != -1){?>
                        <div class='feed-ranking-gap'>
                            <i class='material-icons'>more_vert</i>
                        </div>
                    <?php } ?>
                    <div class="rank-container-activity"
                        data-genre="<?php echo $item[0]->_genre;?>"
                        data-platform="<?php echo $item[0]->_platforms;?>"
                        data-year="<?php echo $item[0]->_year;?>"
                        data-loaded-rank="<?php echo $item[0]->_rank;?>"
                        data-rank="<?php echo $item[1];?>"
                        data-id="<?php echo $item[0]->_id;?>"
                        data-gbid="<?php echo $item[0]->_gbid;?>"
                        data-image="<?php echo $item[0]->_imagesmall; ?>"
                    >
                        <div class="rank-count-container">
                            <div class='rank-count-main'><?php echo $item[1]; ?></div>
                        </div>
                        <div class="rank-item-container">
                            <div class="rank-item-title rank-item-title-w-image">
                                <?php echo $item[0]->_title; ?>
                            </div>
                        </div>
                        <div class="rank-image" style='background:url(<?php echo $item[0]->_imagesmall; ?>) 50% 25%;background-size:cover;'></div>
                        <div class="rank-history">
                            <?php if($item[2] == "NEW"){ ?>
                                <span class='rank-history-new'>NEW</span>
                            <?php }else if($item[2] > 0){ ?>
                                <span class='rank-history-green'><i class='material-icons left' style='margin-right: 5px;'>keyboard_arrow_up</i><?php echo $item[2]; ?></span>
                            <?php }else if($item[2] < 0){ ?>
                                <span class='rank-history-red'><i class='material-icons left' style='margin-right: 5px;'>keyboard_arrow_down</i><?php echo $item[2]; ?></span>
                            <?php } ?>
                        </div>
                    </div>
            <?php 
                $currPos = $item[1];
            }
            ?>
        </div>
        <div class="btn-flat" style='float: right;margin-top: 10px;'>VIEW FULL RANKED LIST</div>
    <?php
}

function ShowFilterList($userid, $sizeOfList){
?>
        <div class="rank-filter-search">
            <div class="rank-filter-search-wrapper">
                <i class="material-icons left rank-filter-search-btn">search</i>
            </div>
            <span style='input-field'>
                <input type="text" class="rank-filter-search-field" placeholder="Search your catalog">
            </span>
            <i class="rank-filter-search-clear-btn material-icons">close</i>
        </div>
		<ul class="collapsible tier-modal-collapsible-container" style='margin-top:120px !important;' data-collapsible="accordion">
            <li>
				<div class="collapsible-header rank-collapsible-header filter-line-header" id="rank-filter-year" data-filter="ALL" style='font-size:1em;'>Year</div>
				<div class="collapsible-body filter-modal-body">
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
				<div class="collapsible-body filter-modal-body">
                    <?php $genres = GetGenresByExperience($userid); 
                    if(sizeof($genres) > 0){
                        foreach($genres as $genre){
                            ?>
                                <div class='col s12 genre-dropdown-filter-item filter-line-item' data-genre="<?php echo $genre; ?>">
                                    <input type="checkbox" class="genre-dropdown-checkbox" id="<?php echo str_replace(" ", "_", $genre); ?>" />
                                    <label for="<?php echo str_replace(" ", "_", $genre); ?>"><?php echo $genre; ?></label>
                                </div>
                            <?php
                        }
                    }
                    ?>
				</div>
			</li>
            <li>
				<div class="collapsible-header rank-collapsible-header filter-line-header" id="rank-filter-platform" data-filter="ALL" style='font-size:1em;'>Platform</div>
				<div class="collapsible-body filter-modal-body">
                    <?php $platforms = GetPlatformsByExperience($userid); 
                        if(sizeof($platforms) > 0){
                            foreach($platforms as $platform){
                                ?>
                                <div class='col s12 platform-dropdown-filter-item filter-line-item' data-platform="<?php echo $platform; ?>">
                                    <input type="checkbox" class="platform-dropdown-checkbox" id="<?php echo str_replace(" ", "_", $platform); ?>" />
                                    <label for="<?php echo str_replace(" ", "_", $platform); ?>"><?php echo $platform; ?></label>
                                </div>
                                <?php
                            }
                        }
                    ?>
				</div>
			</li>
            <li>
				<div class="collapsible-header rank-collapsible-header filter-line-header" id="rank-filter-xp" data-filter="ALL" style='font-size:1em;'>Experience Details</div>
				<div class="collapsible-body filter-modal-body">
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
            <li>
				<div class="collapsible-header rank-collapsible-header filter-line-header" id="rank-filter-type" data-filter="<?php if($sizeOfList > 30){ ?>Minimize<?php }else{ ?>Hide<?php } ?>" style='font-size:1em;'>Advanced Settings</div>
				<div class="collapsible-body filter-modal-body">
                    <div class='col s12 filter-type-item filter-line-item' data-type="Hide">
                        <input type="radio" name="filter-type-radio" class="filter-type-radio" id="HideFilter" <?php if($sizeOfList <= 30){ ?>checked<?php } ?> />
                        <label for="HideFilter">Hide games that are filtered</label>
                        <div style='font-size:0.8em;margin-left: 35px;'>Best for when you want a clean view of a sub-list of your all time games. Less distracting overall.</div>
                    </div>
                    <div class='col s12 filter-type-item filter-line-item' data-type="Minimize">
                        <input type="radio" name="filter-type-radio" class="filter-type-radio" id="MinimizeFilter" <?php if($sizeOfList > 30){ ?>checked<?php } ?> />
                        <label for="MinimizeFilter">Minimize games that are filtered</label>
                        <div style='font-size:0.8em;margin-left: 35px;'>Ideal once your all-time list gets longer and being able to keep the entire scope of your list in mind is important.</div>
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
    <div class="rank-container" draggable="true"
        data-genre="<?php echo $item->_genre;?>"
        data-platform="<?php echo $item->_platforms;?>"
        data-year="<?php if($item->_year == 0){ echo "Unreleased"; }else{ echo $item->_year; } ?>"
        data-loaded-rank="<?php echo $item->_rank;?>"
        data-rank="<?php echo $item->_rank;?>"
        data-id="<?php echo $item->_id;?>"
        data-gbid="<?php echo $item->_gbid;?>"
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
        <div class="rank-image"></div>
        <div class="rank-history"></div>
        <div class="rank-remove-btn">REMOVE</div>
    </div>
    <?php
}
?>