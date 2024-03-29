<?php
function ShowGameDashboard($game, $myxp, $videoxp, $refpts, $collections, $similar, $community){
    ?>
    <div class="row">
        <?php
        $dashboarditems = BuildGameDashboard($game, $myxp, $_SESSION['logged-in']->_id, $videoxp, $refpts, $collections, $similar, $community);
        foreach($dashboarditems as $dashitem){
            if($dashitem['TYPE'] == 'HavePlayed'){
                DisplayHavePlayedCard($game);
            }else if($dashitem['TYPE'] == 'Released'){
                DisplayReleasedCard($dashitem);
            }else if($dashitem['TYPE'] == 'Platforms'){
                DisplayPlatformsCard($dashitem['PLATFORMS'], $dashitem['GBID']);
            }else if($dashitem['TYPE'] == 'MissingWatched'){
                DisplayMissingWatchedCard($dashitem);
            }else if($dashitem['TYPE'] == 'HaveQuote'){
                DisplayHaveQuoteCard($game);
            }else if($dashitem['TYPE'] == 'HaveReflectionPoint'){
                DisplayHaveReflectionPointCard($dashitem);
            }else if($dashitem['TYPE'] == 'HaveProgress'){
                DisplayHaveProgressCard($dashitem);
            }else if($dashitem['TYPE'] == 'Developer'){
                DisplayDeveloperCard($dashitem['DEVELOPERS'], $dashitem['GBID']);
            }else if($dashitem['TYPE'] == 'Collection'){
                DisplayCollectionCard($dashitem['COLLECTIONS']);
            }else if($dashitem['TYPE'] == 'Similar'){
                DisplaySimilarCard($dashitem);
            }else if($dashitem['TYPE'] == 'CommunityCompare'){
                DisplayCommunityCompareCard($dashitem, $myxp->_tier);
            }else if($dashitem['TYPE'] == 'RelativeToYear'){
                DisplayRelativeToYearCard($dashitem, $game->_year, $myxp->_tier);
            }
        } ?>
    </div>
    <?php
}

function DisplayRelativeToYearCard($dashitem, $year, $tier){
    $tierbreakdown = $dashitem['RELATIVETOYEAR'];
    if(sizeof($tierbreakdown) > 0){
        ?>
        <div class='col s12 m6'>
            <div class="dashboard-card">
                <div class="dashboard-card-tiny-header">Tier breakdown from <?php echo $year; ?></div>
                <div class="dashboard-doughnut-container" style='margin-top: 25px;'>
                    <div class="dashboard-card-game-small tier<?php echo $tier; ?>BG" >
                        <div class="dashboard-card-relative-label">TIER</div>
                        <div class="dashboard-card-relative-tier"><?php echo $tier; ?></div>
                    </div>
                    <?php BuildRelationalDoughnut($tierbreakdown, $tier, ""); ?>
                </div>
            </div>
        </div>
        <?php
    }
}

function DisplayCommunityCompareCard($dashitem, $mytier){
    if(sizeof($dashitem['TIERS']) > 0){
        arsort($dashitem['TIERS']);
        reset($dashitem['TIERS']);
        $toptier = key($dashitem['TIERS']);
        ?>
        <div class='col s12 m6 l4'>
            <div class="dashboard-card">
                <div class="dashboard-card-tiny-header">Community <?php if($mytier > 0){ ?>Consensus<?php } ?></div>
                <?php if($toptier == $mytier && $mytier > 0){ ?>
                    <div class="dashboard-calltonotice-container" style='margin-top:20px;'><i style='font-style: normal;margin-top: 0px;'><div class="dashboard-tier-label">TIER</div><div class='tier<?php echo $toptier; ?>BG dashboard-tier-block'><?php echo $toptier; ?></div></i> <span class="dashboard-card-date-info" style='top: -25px !important;'>You agree with other Lifebar members</span></div> 
                <?php }else if($toptier > $mytier && $mytier > 0){ ?>
                    <div class="dashboard-calltonotice-container" style='margin-top:20px;'><i style='font-style: normal;margin-top: 0px;'><div class="dashboard-tier-label">TIER</div><div class='tier<?php echo $toptier; ?>BG dashboard-tier-block' style='color:white;'><?php echo $toptier; ?></div></i> <span class="dashboard-card-date-info" style='top: -25px !important;'>You enjoyed the game more than other Lifebar members</span></div> 
                <?php }else if($mytier > 0){ ?>
                    <div class="dashboard-calltonotice-container" style='margin-top:20px;'><i style='font-style: normal;margin-top: 0px;'><div class="dashboard-tier-label">TIER</div><div class='tier<?php echo $toptier; ?>BG dashboard-tier-block'><?php echo $toptier; ?></div></i> <span class="dashboard-card-date-info" style='top: -25px !important;'>You enjoyed the game less than other Lifebar members</span></div> 
                <?php }else{ ?>
                    <div class="dashboard-calltonotice-container" style='margin-top:20px;'><i style='font-style: normal;margin-top: 0px;'><div class="dashboard-tier-label">TIER</div><div class='tier<?php echo $toptier; ?>BG dashboard-tier-block'><?php echo $toptier; ?></div></i> <span class="dashboard-card-date-info" style='top: -25px !important;'>The average Lifebar community tier</span></div>
                <?php } ?>
                <div class="btn dashboard-community-view">View Community</div>
            </div>
        </div>
        <?php
    }
}

function DisplayCollectionCard($collectionCount){
    if($collectionCount > 0){
    ?>
    <div class='col s12 m6 l4'>
        <div class="dashboard-card">
            <div class="dashboard-card-tiny-header">Collections</div>
            <div class="dashboard-calltonotice-container"><i class="mdi-av-my-library-add"></i> <span><?php echo $collectionCount; ?> Collection<?php if($collectionCount > 1){ echo "s"; } ?></span></div> 
            <div class="btn dashboard-collection-view">View Collection<?php if($collectionCount > 1){ echo "s"; } ?></div>
        </div>
    </div>
    <?php
    }
}

function DisplaySimilarCard($dashitem){
    $similarCount = $dashitem['SIMILAR'];
    if($similarCount > 0){
    ?>
    <div class='col s12 m6 l4'>
        <div class="dashboard-card">
            <div class="dashboard-card-tiny-header">Similar Games</div>
            <div class="dashboard-calltonotice-container">
            <i class="mdi-action-list" <?php if(isset($dashitem['TOTALXP'])){ ?>style='margin-top: -40px;'<?php } ?>></i> 
            <?php if(isset($dashitem['TOTALXP'])){ ?>
                <span class="dashboard-card-date-info">You have XP for <b><?php echo $dashitem['TOTALXP']; ?></b> of</span>
            <?php } ?>
            <span><?php echo $similarCount; ?> Similar Game<?php if($similarCount > 1){ echo "s"; } ?></span></div> 
            <div class="btn dashboard-similar-view">View Game<?php if($similarCount > 1){ echo "s"; } ?></div>
        </div>
    </div>
    <?php
    }
}

function DisplayDeveloperCard($developers, $gbid){
    if(sizeof($developers) > 0){
    ?>
        <div class='col <?php if(sizeof($developers) >= 5){ echo "s12"; }else if(sizeof($developers) >= 2){ echo "s12 m6"; }else{ echo "s12 m6 l4"; } ?>'>
            <div class="dashboard-card">
                <div class="dashboard-card-tiny-header">Developed By</div>
                <div class="dashboard-platform-container">
                    <?php foreach($developers as $developer){
                        DisplayKnowledge($developer, "gamedash");
                    } ?>
                </div>
                <a class="game-gb-ref" href="http://giantbomb.com/game/3030-<?php echo $gbid; ?>" target="_blank">Powered by <span>Giant Bomb</span> API</a> 
            </div>
        </div>
    <?php
    }
}

function DisplayHaveProgressCard($dashitem){
    ?>
    <div class='col s12 m6 l4'>
        <div class="dashboard-card dashboard-card-calltoaction game-add-played-btn-fast">
            <div class="dashboard-question-header">Any updates or progress?</div>
            <div class="dashboard-calltoaction-container"><span><i class="mdi-hardware-gamepad"></i> <span><?php echo $dashitem['UNFINISHED']; ?>% COMPLETE</span></div> 
        </div>
    </div>
    <?php  
}

function DisplayHaveReflectionPointCard($dashitem){
    ?>
    <div class='col s12 m6 l4'>
        <div class="dashboard-card dashboard-card-calltoaction game-ref-pt-btn">
            <div class="dashboard-question-header">Reflection Point</div>
            <div class="dashboard-calltoaction-container"><span><i class="mdi-action-question-answer"></i> <span><?php echo $dashitem['TOTAL']; ?> Unanswered</span></div> 
        </div>
    </div>
    <?php    
}

function DisplayMissingWatchedCard($dashitem){
    ?>
    <div class='col s12 m6 l4'>
        <div class="dashboard-card dashboard-card-calltoaction game-unwatched-btn">
            <div class="dashboard-question-header">Video</div>
            <div class="dashboard-calltoaction-container"><span><i class="mdi-action-visibility"></i> <span><?php echo $dashitem['WATCHED']; ?> Unwatched</span></div> 
        </div>
    </div>
    <?php
}

function DisplayPlatformsCard($platforms, $gbid){
    if(sizeof($platforms) > 0){
        ?>
        <div class='col <?php if(sizeof($platforms) >= 6){ echo "s12"; }else if(sizeof($platforms) >= 3){ echo "s12 m6"; }else{ echo "s12 m6 l4"; } ?>'>
            <div class="dashboard-card">
                <div class="dashboard-card-tiny-header">Platforms</div>
                <div class="dashboard-platform-container">
                <?php foreach($platforms as $platform){
                        DisplayGearMilestone($platform);
                    } ?>
                </div>
                <a class="game-gb-ref" href="http://giantbomb.com/game/3030-<?php echo $gbid; ?>" target="_blank">Powered by <span>Giant Bomb</span> API</a> 
            </div>
        </div>
        <?php
    }
}

function DisplayHavePlayedCard($game){
    ?>
    <div class='col s12 m6 l4'>
        <div class="dashboard-card dashboard-card-calltoaction game-add-played-btn-fast">
            <div class="dashboard-question-header">Have you played this game?</div>
            <div class="dashboard-calltoaction-container"><i class="mdi-hardware-gamepad"></i> <span>Add my XP</span></div> 
        </div>
    </div>
    <?php
}

function DisplayHaveQuoteCard($game){
    ?>
    <div class='col s12 m6 l4'>
        <div class="dashboard-card dashboard-card-calltoaction game-add-played-btn-fast">
            <div class="dashboard-question-header">What did you think?</div>
            <div class="dashboard-calltoaction-container"><i class="mdi-editor-format-quote"></i> <span>Add my summary</span></div> 
        </div>
    </div>
    <?php
}

function DisplayReleasedCard($dashitem){
    if(isset($dashitem['HEADER'])){?>
        <div class='col s12 m6 l4'>
            <div class="dashboard-card">
                <div class="dashboard-card-tiny-header">US Release</div>
                <div class="dashboard-card-date-unknown"><?php echo $dashitem['HEADER']; ?></div>
                <a class="game-gb-ref" href="http://giantbomb.com/game/3030-<?php echo $dashitem['GBID']; ?>" target="_blank">Powered by <span>Giant Bomb</span> API</a> 
            </div>
        </div>
    <?php }else{ ?>
        <div class='col s12 m6 l4'>
            <div class="dashboard-card">
                <div class="dashboard-card-tiny-header">US Release</div>
                <div class="dashboard-card-date-top"><?php echo $dashitem['MONTHDAY']; ?></div>
                <div class="dashboard-card-date-bottom"><?php echo $dashitem['YEAR']; ?></div>
                <?php if(isset($dashitem['AGE'])){ ?>
                    <div class="dashboard-card-date-info">You were about <b><?php echo $dashitem['AGE']; ?></b> yrs old</div>
                <?php } ?>
                <a class="game-gb-ref" href="http://giantbomb.com/game/3030-<?php echo $dashitem['GBID']; ?>" target="_blank">Powered by <span>Giant Bomb</span> API</a>
            </div>
        </div>
    <?php }
}
?>