<?php
require_once "includes.php";

function DisplayRanking($userid){
    $rankedlist = GetMyRankedList($userid, -1, '', '');
    $unrankedlist = GetMyUnrankedList($userid, -1, '', '');
    $count = 1; 
    ?>
    <div class="row" style='position:absolute;width:100%;'>
        <div class="rank-header-container z-depth-1">
            <div class="rank-header-intro"><i class='material-icons left' style='font-size: 1.5em;'>filter_list</i> Filter</div>
            <a class='dropdown-button btn-flat year-dropdown-selected' href='#' data-activates='year-dropdown'>All-Time</a>
            <ul id='year-dropdown' class='dropdown-content'>
                <li class='year-dropdown-filter-item'>All-Time</li>
                    <?php $years = GetYearsByExperience($userid); 
                        foreach($years as $year){
                            ?>
                            <li class='year-dropdown-filter-item'><?php echo $year; ?></li>
                            <?php
                        }
                    ?>
            </ul>
            <a class='dropdown-button btn-flat genre-dropdown-selected' href='#' data-activates='genre-dropdown'>All-Genre</a>
            <ul id='genre-dropdown' class='dropdown-content'>
                <li class='genre-dropdown-filter-item'>All-Genre</li>
                    <?php $genres = GetGenresByExperience($userid); 
                        foreach($genres as $genre){
                            ?>
                            <li class='genre-dropdown-filter-item'><?php echo $genre; ?></li>
                            <?php
                        }
                    ?>
            </ul>
            <a class='dropdown-button btn-flat platform-dropdown-selected' href='#' data-activates='platform-dropdown'>All-Platform</a>
            <ul id='platform-dropdown' class='dropdown-content'>
                <li class='platform-dropdown-filter-item'>All-Platform</li>
                    <?php $platforms = GetPlatformsByExperience($userid); 
                        foreach($platforms as $platform){
                            ?>
                            <li class='platform-dropdown-filter-item'><?php echo $platform; ?></li>
                            <?php
                        }
                    ?>
            </ul>
        </div>
        <div class="rank-list-container">
            <?php
            foreach($rankedlist as $item){ ?>
                    <div class="col s12 rank-container" draggable="true"
                        data-genre="<?php echo $item->_genre;?>"
                        data-platform="<?php echo $item->_platforms;?>"
                        data-year="<?php echo $item->_year;?>"
                        data-loaded-rank="<?php echo $item->_rank;?>"
                        data-rank="<?php echo $item->_rank;?>"
                    >
                        <div class="rank-count">
                        </div>
                        <div class="rank-item-container">
                            <div class="rank-item-title">
                                <?php echo $item->_title; ?>
                            </div>
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
                >
                <div class="rank-count">
                </div>
                <div class="rank-item-container">
                    <div class="rank-item-title">
                        Drag here to add to bottom of list
                    </div>
                </div>
            </div>

        </div>
        <div class="rank-unranked-list-container z-depth-1">
            <div class="rank-header-title"><span class="rank-header-title-count"></span> Unranked Games <i class="material-icons">expand_more</i></div>
            <?php 
            ShowUnRankedList($unrankedlist);
            ?>
        </div>
    </div>
    <?php
}

function ShowUnRankedList($unrankedlist){
	if(sizeof($unrankedlist) > 0){
		$filter = explode(",", $tierlist[0][3]);
		$count = 1;
		?>
		<ul class="collapsible tier-modal-collapsible-container" data-collapsible="accordion">
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
    >
        <div class="rank-count">
        </div>
        <div class="rank-item-container">
            <div class="rank-item-title">
                <?php echo $item->_title; ?>
            </div>
        </div>
    </div>
    <?php
}
?>