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
            }
        } ?>
    </div>
    <?php
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
                <div class="dashboard-question-header"><?php echo $dashitem['HEADER']; ?></div> 
            </div>
        </div>
    <?php }else{ ?>
        <div class='col s12 m6 l4'>
            <div class="dashboard-card">
                <div class="dashboard-question-header"><?php echo $dashitem['MONTHDAY']; ?></div>
                <div class="dashboard-question-header"><?php echo $dashitem['YEAR']; ?></div>
            </div>
        </div>
    <?php }
}
?>