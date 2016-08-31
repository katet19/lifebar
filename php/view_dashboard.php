<?php
function ShowGameDashboard($game, $myxp){
	$dashboarditems = BuildGameDashboard($game, $myxp, $_SESSION['logged-in']->_id);
	foreach($dashboarditems as $dashitem){
	    if($dashitem['TYPE'] == 'HavePlayed'){
            DisplayHavePlayedCard($game);
        }else if($dashitem['TYPE'] == 'Released'){
            DisplayReleasedCard($dashitem);
        }
	}
}

function DisplayHavePlayedCard($game){
    ?>
    <div class='col s12 m6 l4 z-depth-1' style='background-color:white;'>
        <div class="dashboard-question-header">Have you played this game?</div>
        <div class="btn add-played-xp">Add my XP</div> 
    </div>
    <?php
}

function DisplayReleasedCard($dashitem){
    if(isset($dashitem['HEADER'])){?>
        <div class='col s12 m6 l4 z-depth-1' style='background-color:red;color:white;'>
            <div class="dashboard-question-header"><?php echo $dashitem['HEADER']; ?></div> 
        </div>
    <?php }else{ ?>
        <div class='col s12 m6 l4 z-depth-1' style='background-color:blue;color:white;'>
            <div class="dashboard-question-header"><?php echo $dashitem['MONTHDAY']; ?></div>
            <div class="dashboard-question-header"><?php echo $dashitem['YEAR']; ?></div>
        </div>
    <?php }
}
?>

<!--
No XP Yet
    Played? card
    Watched card if we have a video content
</ul>
<br>
<div>Have XP</div>
<ul>
    <li>Update Card if played</li>
    <li>Played? card if haven't played yet</li>
    <li>Watched card if any content that they haven't already watched</li>
    <li>Highlight 3 "notables". Like "1 of 5 games from 2012 you put in Tier 1" or "Lowest tier for this genre" or "15th game you finished this year"</li>
    <li>Compare to similar games that the user has XP</li>
</ul>
<br>
<div>Community has XP</div>
<ul>
    <li>Highlight Popular summaries (highest 1ups)</li>
    <li>Highlight users that put the game in the same tier as you</li>
    <li>Compare</li>
</ul>
<br>
<div>Universal (always show)</div>
<ul>
    <li>Release Date card</li>
    <li>Platforms card</li>
    <li>Developed & Published card</li>
    <li>Categorized card</li>
</ul>
-->