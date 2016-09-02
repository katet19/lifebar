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
		$dashitem['HEADER'] = "Unknown Release Date";
    }else{
        $dashitem['MONTHDAY'] = ConvertDateToActivityFormat($game->_released);
        $dashitem['YEAR'] = $game->_year;
	} 
    if($game->_year >= $_SESSION['logged-in']->_birthdate && date('Y') != $game->_year)
        $dashitem['AGE'] = $game->_year - $_SESSION['logged-in']->_birthdate;
    $dashboarditems[] = $dashitem;

    //Add Platform Card
    unset($dashitem);
    $dashitem['TYPE'] = 'Platforms';
	$dashitem['PLATFORMS'] = GetPlatformsForGame($userid, $game->_gbid);
    $dashboarditems[] = $dashitem;

    return $dashboarditems;
} ?>