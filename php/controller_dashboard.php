<?php function BuildGameDashboard($game, $myxp, $userid){
    $dashboarditems = array();
    
    //Add Played? Card
    if(sizeof($myxp->_playedxp) == 0 && sizeof($myxp->_watchedxp) == 0){
        unset($dashitem);
        $dashitem['TYPE'] = 'HavePlayed';
        $dashboarditems[] = $dashitem;
    }

    //Add Release Date Card
    unset($dashitem);
    $dashitem['TYPE'] = 'Released';
    if($game->_year == 0){
		$dashitem['HEADER'] = "Release date not announced";
    }else{
		$dashitem['MONTHDAY'] = ConvertDateToLongRelationalEnglish($game->_released);
        $dashitem['YEAR'] = $game->_year;
	} 
    $dashboarditems[] = $dashitem;

    return $dashboarditems;
} ?>