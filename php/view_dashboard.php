<?php
function ShowGameDashboard($game, $myxp){
    ?>
    <div class="row">
        <?php
        $dashboarditems = BuildGameDashboard($game, $myxp, $_SESSION['logged-in']->_id);
        foreach($dashboarditems as $dashitem){
            if($dashitem['TYPE'] == 'HavePlayed'){
                DisplayHavePlayedCard($game);
            }else if($dashitem['TYPE'] == 'Released'){
                DisplayReleasedCard($dashitem);
            }else if($dashitem['TYPE'] == 'Platforms'){
                DisplayPlatforms($dashitem['PLATFORMS']);
            }
        } ?>
    </div>
    <?php
}

function DisplayPlatforms($platforms){
    if(sizeof($platforms) > 0){
        ?>
        <div class='col <?php if(sizeof($platforms) > 6){ echo "s12"; }else if(sizeof($platforms) > 2){ echo "s12 m6"; }else{ echo "s12 m6 l4"; } ?>'>
            <div class="dashboard-card">
                <div class="dashboard-card-tiny-header">Platforms</div>
                <div class="dashboard-platform-container">
                <?php foreach($platforms as $platform){
                        DisplayGearMilestone($platform);
                    } ?>
                </div>
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

function DisplayReleasedCard($dashitem){
    if(isset($dashitem['HEADER'])){?>
        <div class='col s12 m6 l4'>
            <div class="dashboard-card">
                <div class="dashboard-card-tiny-header">US Release</div>
                <div class="dashboard-card-date-unknown"><?php echo $dashitem['HEADER']; ?></div> 
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
            </div>
        </div>
    <?php }
}
?>