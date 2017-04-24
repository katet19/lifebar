<?php
function DisplayMyLibrary($userid){
    ?>
    <div class="row">
        <div class="rank-header-container">
        </div>
        <div class="col s12">
            <?php
                $count = 1;
                $games = GetUserLibrary($userid);
                if(sizeof($games) > 0){
                    ?>
                    <div class="mylib-list-container">
                        <?php
                        foreach($games as $item){ ?>
                                <div class="mylib-container"
                                    data-genre="<?php echo $item->_genre;?>"
                                    data-platform="<?php echo $item->_platforms;?>"
                                    data-year="<?php if($item->_year > 0){ echo $item->_year; }else{ echo "Unreleased"; }?>"
                                    data-loaded-rank="<?php echo $item->_rank;?>"
                                    data-rank="<?php echo $item->_rank;?>"
                                    data-id="<?php echo $item->_id;?>"
                                    data-gbid="<?php echo $item->_gbid;?>"
                                    data-image="<?php echo $item->_imagesmall; ?>"
                                    data-xp="<?php echo $item->_xptype; ?>"
                                    data-star="<?php echo TierToStar($item->_tier); ?>Stars"
                                >
                                    <div class="rank-item-container">
                                        <div class="mylib-item-title mylib-item-title-w-image">
                                            <?php echo $item->_title; ?>
                                        </div>
                                        <div class="mylib-secondary-content">
                                            <p><?php if($item->_year > 0){ echo $item->_year; }else{ echo "Unreleased"; }?></p>
                                            <p><?php echo $item->_genre;?></p>
                                            <p><?php echo $item->_platforms;?></p>
                                        </div>
                                    </div>
                                    <div class="mylib-image <?php if($count < 50){ ?>displayImage<?php } ?>" 
                                        <?php if($count < 50){ ?>
                                            style='background:url(<?php echo $item->_imagesmall; ?>) 50% 25%;background-size:cover;'>
                                        <?php }else{ ?>
                                            style='display:none;background-size:cover;'>
                                        <?php } ?>
                                    </div>
                                </div>
                        <?php
                        $count++;
                        }
                        ?>
                    </div>
                    <?php
                }else{ ?>
                    <div class="mylib-welcome-container">
                        <div class="col s12 onboarding-game-step" style='text-align:left;'>
                            <div class='onboarding-big-welcome' style='font-size: 1.5em;margin-toP: 0px;'>Your Library is empty!</div>
                            <div class='onboarding-sub-sub-welcome'>Take a look at <i class='material-icons indigo-text' style='font-size: 1em;position: relative;top: 2px;margin-right: -2px;margin-left: 3px;'>explore</i> Discover, review your <i class='material-icons indigo-text' style='font-size: 1em;position: relative;top: 2px;margin-right: -2px;margin-left: 3px;'>whatshot</i> Activity feed or search for games you have experienced. Adding a bookmark, giving a star rating or entering played/watched details will add the game to your Library.</div>
                        </div>
                    </div>
                <?php }
            ?>
        </div>
    </div>
    <div class="mylib-filter-list-container z-depth-1" style='top:80px !important;'>
        <div class="rank-header-title z-depth-1"><i class="material-icons">filter_list</i> Filter Library</div>
        <?php 
        ShowMyLibraryFilter($userid);
        ?>
    </div>
    <div class="btn-floating btn-large mylib-to-top-btn"><i class="material-icons left" style='font-size:2em;position: relative;top: 0px;'>vertical_align_top</i></div>
    <?php
}

function ShowMyLibraryFilter($userid){
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
                    <div class='col s12 xp-dropdown-filter-item filter-line-item' data-xp="Watched">
                        <input type="checkbox" class="xp-dropdown-checkbox" id="Watched" />
                        <label for="Watched">Watched</label>
                    </div>
				</div>
			</li>
            <li>
				<div class="collapsible-header rank-collapsible-header filter-line-header" id="rank-filter-star" data-filter="ALL" style='font-size:1em;'>Star Rating</div>
				<div class="collapsible-body filter-modal-body">
                    <div class='col s12 star-dropdown-filter-item filter-line-item' data-star="5Stars">
                        <input type="checkbox" class="star-dropdown-checkbox" id="5Stars" />
                        <label for="5Stars">5 Stars</label>
                    </div>
                    <div class='col s12 star-dropdown-filter-item filter-line-item' data-star="4Stars">
                        <input type="checkbox" class="star-dropdown-checkbox" id="4Stars" />
                        <label for="4Stars">4 Stars</label>
                    </div>
                    <div class='col s12 star-dropdown-filter-item filter-line-item' data-star="3Stars">
                        <input type="checkbox" class="star-dropdown-checkbox" id="3Stars" />
                        <label for="3Stars">3 Stars</label>
                    </div>
                    <div class='col s12 star-dropdown-filter-item filter-line-item' data-star="2Stars">
                        <input type="checkbox" class="star-dropdown-checkbox" id="2Stars" />
                        <label for="2Stars">2 Stars</label>
                    </div>
                    <div class='col s12 star-dropdown-filter-item filter-line-item' data-star="1Star">
                        <input type="checkbox" class="star-dropdown-checkbox" id="1Star" />
                        <label for="1Star">1 Stars</label>
                    </div>
                    <div class='col s12 star-dropdown-filter-item filter-line-item' data-star="0Stars">
                        <input type="checkbox" class="star-dropdown-checkbox" id="0Stars" />
                        <label for="0Stars">No Rating</label>
                    </div>
				</div>
			</li>
        </ul>
<?php
}

?>