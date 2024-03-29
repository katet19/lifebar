<?php function BuildGameDashboard($game, $myxp, $userid, $videoxp, $refpts, $collections, $similar, $community){
    $dashboarditems = array();
    
    //Add Played? Card
    $cardhasplayed = false;
    if(sizeof($myxp->_playedxp) == 0 && date('Y') >= $game->_year && $game->_year != 0){
        unset($dashitem);
        $dashitem['TYPE'] = 'HavePlayed';
        $dashboarditems[] = $dashitem;
        $cardhasplayed = true;
    }else if(sizeof($myxp->_playedxp) > 0 && $myxp->_playedxp[0]->_completed < 100){
        unset($dashitem);
        $dashitem['TYPE'] = 'HaveProgress';
        $dashitem['UNFINISHED'] = $myxp->_playedxp[0]->_completed;
        $dashboarditems[] = $dashitem;
    }

    //Add Reflection Point Card
    if(!$cardhasplayed && sizeof($refpts) > 0 ){
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
    if(sizeof($myxp->_playedxp) > 0 && !$cardhasplayed && $myxp->_quote == '' && $myxp->_tier > 0){
        unset($dashitem);
        $dashitem['TYPE'] = 'HaveQuote';
        $dashboarditems[] = $dashitem;
    }

    //Add Release Date Card
    unset($dashitem);
    $dashitem['TYPE'] = 'Released';
    if($game->_year == 0){
		$dashitem['HEADER'] = "?";
    }else{
        $dashitem['MONTHDAY'] = ConvertDateToActivityFormat($game->_released);
        $dashitem['YEAR'] = $game->_year;
	} 
    if($game->_year >= $_SESSION['logged-in']->_birthdate && date('Y') > $game->_year)
        $dashitem['AGE'] = $game->_year - $_SESSION['logged-in']->_birthdate;
    
    $dashitem['GBID'] = $game->_gbid;
    $dashboarditems[] = $dashitem;

    //Add Platform Card
    unset($dashitem);
    $dashitem['TYPE'] = 'Platforms';
	$dashitem['PLATFORMS'] = GetPlatformsForGame($userid, $game->_gbid);
    $dashitem['GBID'] = $game->_gbid;
    $dashboarditems[] = $dashitem;

    //Add Relative to Year Card
    if($myxp->_tier > 0 && $game->_year > 0){
        unset($dashitem);
        $dashitem['TYPE'] = 'RelativeToYear';
        $dashitem['RELATIVETOYEAR'] = GetTierBreakdownLight($_SESSION['logged-in']->_id, $game->_year);
        $dashboarditems[] = $dashitem;
    }

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

    //Add Developer
    unset($dashitem);
    $dashitem['TYPE'] = 'Developer';
	$dashitem['DEVELOPERS'] = GetDevelopersForGame($userid, $game->_gbid);
    $dashitem['GBID'] = $game->_gbid;
    $dashboarditems[] = $dashitem;

    //Add Collection Count
    if(sizeof($collections) > 0){
        unset($dashitem);
        $dashitem['TYPE'] = 'Collection';
        $dashitem['COLLECTIONS'] = sizeof($collections);
        $dashboarditems[] = $dashitem;
    }

    //Community
    if(sizeof($community) > 0){
        unset($dashitem);
        $dashitem['TYPE'] = 'CommunityCompare';
        $tiers = array();
        foreach($community as $xp)
            $tiers[$xp->_tier] = $tiers[$xp->_tier] + 1;

        $dashitem['TIERS'] = $tiers;
        $dashboarditems[] = $dashitem;
    }

    //Add Similar Games
    if(sizeof($similar) > 0){
        unset($dashitem);
        $dashitem['TYPE'] = 'Similar';
        $dashitem['SIMILAR'] = sizeof($similar);
        $xpTally = 0;
        foreach($similar as $sim){
             $xp = GetExperienceForUserComplete($_SESSION['logged-in']->_id, $sim->_id);
             if(sizeof($xp->_playedxp) > 0 || sizeof($xp->_watchedxp) > 0)
                $xpTally++;
        }
        if($xpTally > 0)
            $dashitem['TOTALXP'] = $xpTally;
        $dashboarditems[] = $dashitem;
    }


    return $dashboarditems;
} ?>