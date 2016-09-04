<?php function BuildGameDashboard($game, $myxp, $userid, $videoxp, $refpts){
    $dashboarditems = array();
    
    //Add Played? Card
    $cardhasplayed = false;
    if(sizeof($myxp->_playedxp) == 0 && sizeof($myxp->_watchedxp) == 0){
        unset($dashitem);
        $dashitem['TYPE'] = 'HavePlayed';
        $dashboarditems[] = $dashitem;
        $cardhasplayed = true;
    }

    //Add Reflection Point Card
    if(!$cardhasplayed && sizeof($refpts) > 0){
        $tally = 0;
        foreach($refpts as $pt){
            $hasResults = HasFormResults($_SESSION['logged-in']->_id, $pt['ID']);
            if(!$hasResults){
                $tally++;
            }
        }
        if($tally > 0){
            unset($dashitem);
            $dashitem['TYPE'] = 'HaveReflectionPoint';
            $dashitem['TOTAL'] = $tally;
            $dashboarditems[] = $dashitem;
        }
    }

    //Add Summary Card
    if(!$cardhasplayed && $myxp->_quote == ''){
        unset($dashitem);
        $dashitem['TYPE'] = 'HaveQuote';
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

    //Add Watched Card
    if(sizeof($videoxp) > 0){
		$tally = 0;
        foreach($videoxp as $video){
            $found = false;
			foreach($myxp->_watchedxp as $watched){
				if($watched->_url == $video['URL']){
                    $found = true;
                    break;
				}	
			}
            if(!$found){
                $tally++;
            }
		}
        unset($dashitem);
        if($tally > 0)
        $dashitem['TYPE'] = 'MissingWatched';
        $dashitem['WATCHED'] = $tally;
        $dashboarditems[] = $dashitem;
    }

    //Add Developer & Publisher Info


    return $dashboarditems;
} ?>