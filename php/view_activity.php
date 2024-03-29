<?php
function DisplayActivity($userid, $filter){
?>	
	<div class='row' style='margin-top:20px'>
		<div class="activity-top-level">
			<?php 
				DisplayMainActivity($userid, $filter);
			?>
		</div>
	</div>
<?php	
}

function DisplayMainActivity($userid, $filter){
	if($userid > 0)
		$myfeed = GetMyFeed($userid, 0, $filter);
	else
		$myfeed = GetMyFeed(0, 0, $filter);
		
	$conn = GetConnectedToList($_SESSION['logged-in']->_id);
	$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
	
	$curr_date_array = explode(" ", $myfeed[0][0]->_date);
	$curr_date = $curr_date_array[0];
	$last_user = 0;
	$last_type = "";
	$groupfeed = array();
	$group = array();
	foreach($myfeed as $feeditem){
		$temp_date_array = explode(" ", $feeditem[0]->_date);
		$temp_date = $temp_date_array[0];
		if($temp_date != $curr_date){
			if(sizeof($group) > 0)
				$groupfeed[] = $group;
			$curr_date = $temp_date;
			unset($group);
			$last_type = "";
			$last_user = 0;
		}

		$date = explode(" ",$feeditem[0]->_date);
		$time = explode(":",$date[1]);
		
		if($feeditem[5] == $last_type || $feeditem[5]."-".$feeditem[2]->_id == $last_type || $feeditem[5]."-".$time[0] == $last_type){
			if($last_user == $feeditem[0]->_userid){
				$group[] = $feeditem;
			}else{
				if(sizeof($group) > 0)
					$groupfeed[] = $group;
				unset($group);
				$group[] = $feeditem;
				$last_type = $feeditem[5];
				$last_user = $feeditem[0]->_userid;
			}
		}else{
			if(sizeof($group) > 0){
				if(sizeof($group) == 1 && ($feeditem[5] == "XP" || $feeditem[5] == "BUCKETLIST" || $feeditem[5] == "QUOTECHANGED" || $feeditem[5] == "TIERCHANGED") 
					&& $group[0][1]->_gbid == $feeditem[1]->_gbid){
						if($feeditem[5] == "BUCKETLIST"){
							$groupfeed[] = $group;
							unset($group);
							//Do not add bookmark to feed
						}else{
							//Ignore the event before and only add the 2nd event
							unset($group);
							$group[] = $feeditem;
							$last_type = $feeditem[5];
							$last_user = $feeditem[0]->_userid;
						}
				}else{
					$groupfeed[] = $group;
					unset($group);
					$group[] = $feeditem;
					if($feeditem[5] == "COLLECTIONUPDATE")
						$last_type = $feeditem[5]."-".$feeditem[2]->_id;
					else if($feeditem[5] == "RANK"){
						$date = explode(" ",$feeditem[0]->_date);
						$time = explode(":",$date[1]);
						$last_type = $feeditem[5]."-".$time[0];
					}
					else
						$last_type = $feeditem[5];
						
					$last_user = $feeditem[0]->_userid;
				}
			}else{
				$groupfeed[] = $group;
				unset($group);
				$group[] = $feeditem;
				if($feeditem[5] == "COLLECTIONUPDATE")
					$last_type = $feeditem[5]."-".$feeditem[2]->_id;
				else if($feeditem[5] == "RANK"){
					$date = explode(" ",$feeditem[0]->_date);
					$time = explode(":",$date[1]);
					$last_type = $feeditem[5]."-".$time[0];
				}
				else
					$last_type = $feeditem[5];

				$last_user = $feeditem[0]->_userid;
			}
		}
	}
	//The last group will get missed in the loop
	if(sizeof($group) > 0)
		$groupfeed[] = $group;
	
	?>
		<div class="col s12" style='position: relative;z-index: 1;'> 
		<?php
			$curr_date_array = explode(" ", $groupfeed[0][0][0]->_date);
			$curr_date = $curr_date_array[0];
			FeedDateDivider($curr_date);
			foreach($groupfeed as $feeditem){
				$temp_date_array = explode(" ", $feeditem[0][0]->_date);
				$temp_date = $temp_date_array[0];
				if($temp_date != $curr_date){
					FeedDateDivider($temp_date);
					$curr_date = $temp_date;
				}
				
				if($feeditem[0][5] == "XP"){
					FeedXPItem($feeditem, $conn, $mutualconn);
				}else if($feeditem[0][5] == "CONNECTIONS"){
					FeedConnectionItem($feeditem, $conn, $mutualconn);
				}else if($feeditem[0][5] == "BUCKETLIST"){
					FeedBookmarkItem($feeditem, $conn, $mutualconn);
				}else if($feeditem[0][5] == "TIERCHANGED"){
					FeedTierChangedItem($feeditem, $conn, $mutualconn);
				}else if($feeditem[0][5] == "QUOTECHANGED"){
					FeedQuoteChangedItem($feeditem, $conn, $mutualconn);
				}else if($feeditem[0][5] == "GAMERELEASE"){
					FeedGameReleasesItem($feeditem);
				}else if($feeditem[0][5] == "COLLECTIONCREATION"){
					FeedCollectionCreationItem($feeditem, $conn, $mutualconn);
				}else if($feeditem[0][5] == "COLLECTIONFOLLOW"){
					FeedCollectionFollowItem($feeditem, $conn, $mutualconn);
				}else if($feeditem[0][5] == "STEAMIMPORT"){
					FeedSteamImport($feeditem, $conn, $mutualconn);
				}else if($feeditem[0][5] == "COLLECTIONUPDATE"){
					FeedCollectionUpdate($feeditem, $conn, $mutualconn);
				}else if($feeditem[0][5] == "RANK"){
					FeedRankItem($feeditem, $conn, $mutualconn);
				}
			}
		?>
		<div id="feed-endless-loader" style='position:absolute;bottom:0;left:0;right:0;height:10px;' data-page="46" data-date="<?php echo $curr_date; ?>" data-filter="<?php echo $filter; ?>" ></div>
		</div>
		<div class="feed-vert-line"></div>
	<?php
}

function DisplayActivityEndless($userid, $page, $current_date, $filter){
	if($userid > 0)
		$myfeed = GetMyFeed($userid, $page, $filter);
	else
		$myfeed = GetMyFeed(0, $page, $filter);
		
	$conn = GetConnectedToList($userid);
	$mutualconn = GetMutalConnections($userid);
	
	$last_user = 0;
	$last_type = "";
	$groupfeed = array();
	$group = array();
	foreach($myfeed as $feeditem){
		$temp_date_array = explode(" ", $feeditem[0]->_date);
		$temp_date = $temp_date_array[0];
		if($temp_date != $curr_date){
			if(sizeof($group) > 0)
				$groupfeed[] = $group;
			$curr_date = $temp_date;
			unset($group);
			$last_type = "";
			$last_user = 0;
		}
		
		$date = explode(" ",$feeditem[0]->_date);
		$time = explode(":",$date[1]);
		
		if($feeditem[5] == $last_type || $feeditem[5]."-".$feeditem[2]->_id == $last_type || $feeditem[5]."-".$time[0] == $last_type){
			if($last_user == $feeditem[0]->_userid){
				$group[] = $feeditem;
			}else{
				if(sizeof($group) > 0)
					$groupfeed[] = $group;
				unset($group);
				$group[] = $feeditem;
				$last_type = $feeditem[5];
				$last_user = $feeditem[0]->_userid;
			}
		}else{
			if(sizeof($group) > 0){
				if(sizeof($group) == 1 && ($feeditem[5] == "XP" || $feeditem[5] == "BUCKETLIST" || $feeditem[5] == "QUOTECHANGED" || $feeditem[5] == "TIERCHANGED") 
					&& $group[0][1]->_gbid == $feeditem[1]->_gbid){
						if($feeditem[5] == "BUCKETLIST"){
							$groupfeed[] = $group;
							unset($group);
							//Do not add bookmark to feed
						}else{
							//Ignore the event before and only add the 2nd event
							unset($group);
							$group[] = $feeditem;
							$last_type = $feeditem[5];
							$last_user = $feeditem[0]->_userid;
						}
				}else{
					$groupfeed[] = $group;
					unset($group);
					$group[] = $feeditem;
					if($feeditem[5] == "COLLECTIONUPDATE")
						$last_type = $feeditem[5]."-".$feeditem[2]->_id;
					else if($feeditem[5] == "RANK"){
						$date = explode(" ",$feeditem[0]->_date);
						$time = explode(":",$date[1]);
						$last_type = $feeditem[5]."-".$time[0];
					}
					else
						$last_type = $feeditem[5];
					$last_user = $feeditem[0]->_userid;
				}
			}else{
				$groupfeed[] = $group;
				unset($group);
				$group[] = $feeditem;
				if($feeditem[5] == "COLLECTIONUPDATE")
					$last_type = $feeditem[5]."-".$feeditem[2]->_id;
				else if($feeditem[5] == "RANK"){
					$date = explode(" ",$feeditem[0]->_date);
					$time = explode(":",$date[1]);
					$last_type = $feeditem[5]."-".$time[0];
				}
				else
					$last_type = $feeditem[5];
					
				$last_user = $feeditem[0]->_userid;
			}
		}
	}
	//The last group will get missed in the loop
	if(sizeof($group) > 0)
		$groupfeed[] = $group;
		$curr_date = $current_date;
		foreach($groupfeed as $feeditem){
			$temp_date_array = explode(" ", $feeditem[0][0]->_date);
			$temp_date = $temp_date_array[0];
			if($temp_date != $curr_date){
				FeedDateDivider($temp_date);
				$curr_date = $temp_date;
			}
			
			if($feeditem[0][5] == "XP"){
				FeedXPItem($feeditem, $conn, $mutualconn);
			}else if($feeditem[0][5] == "CONNECTIONS"){
				FeedConnectionItem($feeditem, $conn, $mutualconn);
			}else if($feeditem[0][5] == "BUCKETLIST"){
				FeedBookmarkItem($feeditem, $conn, $mutualconn);
			}else if($feeditem[0][5] == "TIERCHANGED"){
				FeedTierChangedItem($feeditem, $conn, $mutualconn);
			}else if($feeditem[0][5] == "QUOTECHANGED"){
				FeedQuoteChangedItem($feeditem, $conn, $mutualconn);
			}else if($feeditem[0][5] == "GAMERELEASE"){
				FeedGameReleasesItem($feeditem);
			}else if($feeditem[0][5] == "COLLECTIONCREATION"){
				FeedCollectionCreationItem($feeditem,$conn, $mutualconn);
			}else if($feeditem[0][5] == "COLLECTIONFOLLOW"){
				FeedCollectionFollowItem($feeditem, $conn, $mutualconn);
			}else if($feeditem[0][5] == "STEAMIMPORT"){
				FeedSteamImport($feeditem, $conn, $mutualconn);
			}else if($feeditem[0][5] == "COLLECTIONUPDATE"){
					FeedCollectionUpdate($feeditem, $conn, $mutualconn);
			}else if($feeditem[0][5] == "RANK"){
				FeedRankItem($feeditem, $conn, $mutualconn);
			}
		}
}

function FeedRankItem($feed, $conn, $mutualconn){
	$user = GetUser($feed[0][0]->_userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
	?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" data-id="<?php echo $user->_id; ?>" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_badge != ""){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
    		</div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp"><i class="material-icons" style='position: relative;top: 2px;'>swap_vert</i></div>
		</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
					<?php if($feed[0][0]->_tier == 1){  ?>
						started their All-Time Ranked List
					<?php }else{ ?>
						updated their All-Time Ranked List
					<?php } ?>
					<span class="feed-activity-when-info"><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
				</div>
			<div class="feed-activity-rank-container">
				<?php					
					ShowActivityRankingList($feed[0][1], $user->_id);
				?>
			</div>
		</div>
	</div>
<?php
}

function FeedDateDivider($date){
	$datetime = explode(" ", ConvertDateToActivityFormat($date));
	$year = explode('-',$date);
	$now = date('Y');
	if($date != ''){
		?>
		<div class="row feed-date-divider" data-date="<?php echo $date; ?>">
			<div class="col s12">
				<?php 	if($year[0] != $now){ ?>
					<div class="feed-date-divider-month">
						<?php echo $datetime[0]; ?>
					</div>
					<div class="feed-date-divider-bullet"></div>
					<div class="feed-date-divider-day">
						<?php echo $datetime[1]; ?>
						<span style='color:#3F51B5'>/</span>
						<span style="font-weight:100;"><?php echo $year[0]; ?></span>
					</div>
				<?php }else{ ?>
					<div class="feed-date-divider-month">
						<?php echo $datetime[0]; ?>
					</div>
					<div class="feed-date-divider-bullet"></div>
					<div class="feed-date-divider-day">
						<?php echo $datetime[1]; ?>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php }
}

function ConvertDateToActivityFormat($date){
	$datetime = explode(" ", $date);
	$datesplit = explode('-',$datetime[0]);
	
	if($datesplit[1] == "1"){ $real = "Jan ".$datesplit[2]; }
	else if($datesplit[1] == "2"){ $real = "Febr ".$datesplit[2]; }
	else if($datesplit[1] == "3"){ $real = "Mar ".$datesplit[2]; }
	else if($datesplit[1] == "4"){ $real = "April ".$datesplit[2]; }
	else if($datesplit[1] == "5"){ $real = "May ".$datesplit[2]; }
	else if($datesplit[1] == "6"){ $real = "June ".$datesplit[2]; }
	else if($datesplit[1] == "7"){ $real = "July ".$datesplit[2]; }
	else if($datesplit[1] == "8"){ $real = "Aug ".$datesplit[2]; }
	else if($datesplit[1] == "9"){ $real = "Sept ".$datesplit[2]; }
	else if($datesplit[1] == "10"){ $real = "Oct ".$datesplit[2]; }
	else if($datesplit[1] == "11"){ $real = "Nov ".$datesplit[2]; }
	else if($datesplit[1] == "12"){ $real = "Dec ".$datesplit[2]; }
	
	return $real;
}

function FeedXPItem($feed, $conn, $mutualconn){ 
	$user = GetUser($feed[0][0]->_userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" data-id="<?php echo $user->_id; ?>" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_badge != ""){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
    		</div>
		</div>
		<div class="feed-activity-icon-col">
				<?php if($user->_security == "Journalist"){ ?>
					<div class="feed-activity-icon-xp"><i class="material-icons" style='font-size: 1em;margin-top: 4px;'>subject</i></div>
				<?php }else if(sizeof($feed) > 0){ ?>
					<div class="feed-activity-icon-xp"><span style='font-size: 0.8em;font-weight: bold;line-height: 28px;position:relative;top:-1px;'>XP</span></div>
				<?php }else if(strtotime($feed[0][0]->_date) < strtotime("now -182 days")){ ?> 
					<div class="feed-activity-icon-xp"><i class="material-icons">access time</i></div>
				<?php }else if($feed[0][0]->_event == "FINISHED"){ ?>
					<div class="feed-activity-icon-xp"><i class="material-icons">done</i></div>
				<?php }else if(sizeof($feed[0][3]->_watchedxp) > 0 && sizeof($feed[0][3]->_playedxp) > 0){ ?>
					<div class="feed-activity-icon-xp"><i class="material-icons">create</i></div>
				<?php }else if(sizeof($feed[0][3]->_playedxp) > 0){ ?> 
					<div class="feed-activity-icon-xp"><span style='font-size: 0.8em;font-weight: bold;line-height: 28px;position:relative;top:-1px;'>XP</span></div>
				<?php }else if(sizeof($feed[0][3]->_watchedxp) > 0){ ?>
					<div class="feed-activity-icon-xp"><span style='font-size: 0.8em;font-weight: bold;line-height: 28px;position:relative;top:-1px;'>XP</span></div>
				<?php }else{ ?>
					<div class="feed-activity-icon-xp"><i class="material-icons">create</i></div>
				<?php } ?>
		</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
					<?php if(sizeof($feed) > 1){ 
                            if($user->_security == "Journalist" || ($user->_security == "Authenticated")){
                                $allreviews = true;
                                foreach($feed as $card){
                                    if($card[3]->_link == ''){
                                        $allreviews = false;
                                    }
                                }
                                if($allreviews)
                                    echo "reviewed ".sizeof($feed)." games";
                                else
                                    echo "added ".sizeof($feed)." new experiences";
                            }else{?>
						      added <?php echo sizeof($feed); ?> new experiences
                         <?php } ?>
					<?php }else if($user->_security == "Journalist" || ($user->_security == "Authenticated" && sizeof($feed) == 1 && $feed[0][3]->_link != '')){ ?>
						reviewed
					<?php }else if(strtotime($feed[0][0]->_date) < strtotime("now -182 days") && sizeof($feed[0][3]->_playedxp) > 0){ ?>
						reminisced about
					<?php }else if($feed[0][0]->_event == "FINISHED"){ ?>
						finished
					<?php }else if(sizeof($feed[0][3]->_watchedxp) > 0 && sizeof($feed[0][3]->_playedxp) > 0){ ?>
						played and watched
					<?php }else if(sizeof($feed[0][3]->_playedxp) > 0){ ?>
						played
					<?php }else if(sizeof($feed[0][3]->_watchedxp) > 0){ ?>
						watched
					<?php }else{ ?>
						experienced 
					<?php } ?>
					<?php if(sizeof($feed) == 1){ ?>
						<span class="feed-activity-game-link" data-gbid="<?php echo $feed[0][1]->_gbid; ?>"><?php echo $feed[0][1]->_title; ?></span>
					<?php } ?>
					<span class="feed-activity-when-info"><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
				</div>
			<div class="feed-activity-game-container">
				<?php
					if(sizeof($feed) > 1)
						$multiple = true;
					else
						$multiple = false;
						
					foreach($feed as $card){
						$event = $card[0];
						$game = $card[1];
						$xp = $card[3];
						$agrees = GetAgreesForEvent($event->_id);
						$agreedcount = array_shift($agrees);
						FeedGameXPCard($game, $user, $event, $xp, $agrees, $agreedcount, $multiple, $conn, $mutualconn);
				}
				?>
			</div>
		</div>
	</div>
<?php
}

function FeedGameXPCard($game, $user, $event, $xp, $agrees, $agreedcount, $multiple, $conn, $mutualconn){ 
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
		if($event->_quote == ''){ ?>
		<div class="col s6 m6 l4" style='position:relative;padding: 0 1rem 0 0;'>
			<a class="card game-discover-card feed-game-discover-card <?php echo $classId; ?>" href="/#game/<?php echo $game->_id; ?>/<?php echo urlencode($game->_title); ?>/" data-count="<?php echo $count; ?>" data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>" onclick="var event = arguments[0] || window.event; event.stopPropagation();">
				<div class="card-image waves-effect waves-block" style="height:100px !important;width:100%;background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
				</div>
				<div class="card-content" style='height:65px;'>
					<?php
					if(sizeof($xp->_playedxp) > 0){
						?>
						<div class="feed-action-details-small-emoji">
							<i class='material-icons tierTextColor<?php echo $event->_tier; ?>' style='font-size:1.3em;vertical-align: middle;margin-top:-4px;'>					
								<?php if($event->_tier == 1){ echo "sentiment_very_satisfied"; }else if($event->_tier == 2){ echo "sentiment_satisfied"; }else if($event->_tier == 3){ echo "sentiment_neutral"; }else if($event->_tier == 4){ echo "sentiment_dissatisfied"; }else if($event->_tier == 5){ echo "sentiment_very_dissatisfied"; } ?>						
							</i>
						</div>
						<?php
					}else if(sizeof($xp->_watchedxp) > 0){
						?>
						<div class="feed-action-details-small-emoji">
							<i class='material-icons tierTextColor<?php echo $event->_tier; ?>' style='font-size:1.3em;vertical-align: middle;margin-top:-4px;'>
								<?php if($event->_tier == 1){ echo "sentiment_very_satisfied"; }else if($event->_tier == 2){ echo "sentiment_satisfied"; }else if($event->_tier == 3){ echo "sentiment_neutral"; }else if($event->_tier == 4){ echo "sentiment_dissatisfied"; }else if($event->_tier == 5){ echo "sentiment_very_dissatisfied"; } ?>	
							</i>
						</div>
						<?php
					}
					?>
					<div class="card-title activator grey-text text-darken-4" style='height:55px;'>
						<div class="feed-card-small-title">
							<?php echo $game->_title; ?>
						</div>
						<div class="feed-action-details" style='top: 0;left: 5px;width: 95%;line-height: 10px;'>
							<?php
							$eventXP = "";
							$isWatched = false;
							if(sizeof($xp->_playedxp) > 0){
								foreach($xp->_playedxp as $playxp){
									if($playxp->_entereddate == $event->_date)
										$eventXP = $playxp;
								}
							}else if(sizeof($xp->_watchedxp) > 0 && $eventXP == ""){
								foreach($xp->_watchedxp as $watchxp){
									if($watchxp->_entereddate == $event->_date)
										$eventXP = $watchxp;
										$isWatched = true;
								}
							}

							if($eventXP == "" && sizeof($xp->_playedxp) > 0)
								$eventXP = $xp->_playedxp[0];


							if($eventXP != "" && !$isWatched){
									if($eventXP->_completed == "101")
										$percent = 100;
									else
										$percent = $eventXP->_completed; ?>
									<div class="feed-action-details-small-card">
										<i class="material-icons" style='font-size:1em;vertical-align: middle;'>games</i>
										<?php 
										if($percent < 100){ echo $percent."%"; }else{ ?>
											Finished
										<?php } 
										?>
									</div>
									<?php if($eventXP->_hours > 0){ ?>
										<div class="feed-action-details-small-card">
											<i class="material-icons" style='font-size:1em;vertical-align: middle;'>access_time</i>
											<?php echo $eventXP->_hours." hours"; ?>
										</div>
									<?php } ?>
									<?php if($user->_security != "Journalist"){ ?>
										<div class="feed-action-details-small-card">
											<i class="material-icons" style='font-size:1em;vertical-align: middle;'>tv</i>
											<?php echo $eventXP->_platform; ?>
										</div>
									<?php } ?>
							<?php
							}else if($eventXP != "" && $isWatched){
									$length = $eventXP->_length;
									if($length == "Watched a speed run"){
										$icon = "directions_walk";
									}else if($length == "Watched a complete single player playthrough" || $length == "Watched a complete playthrough"){
										$icon = "beenhere";
									}else if($length == "Watched competitive play"){
										$icon = "headset_mic";
									}else if($length == "Watched multiple hours" || $length == "Watched gameplay" || $length == "Watched an hour or less"){
										$icon = "videogame_asset";
									}else if($length == "Watched promotional gameplay"){
										$icon = "movie_creation";
									}else if($length == "Watched a developer diary"){
										$icon = "class";
									}else{
										$icon = "theaters";
									}
									?>
									<div class="feed-action-details-small-card">
										<i class="material-icons" style='font-size:1em;vertical-align: middle;'><?php echo $icon; ?></i>
										<?php echo $length; ?>
									</div>
							<?php } 
					
							?>
						</div>
					</div>
				</div>
			</a>
		</div>
	<?php }else if($agreedcount <= 2){ ?>
		<div class="feed-horizontal-card z-depth-1"  data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
	    <a class="feed-card-image waves-effect waves-block" href="/#game/<?php echo $game->_id; ?>/<?php echo urlencode($game->_title); ?>/" style="background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;" onclick="var event = arguments[0] || window.event; event.stopPropagation();">
	    </a>
	    <div class="feed-card-content">
	      <div class="feed-card-title">
			<div class="feed-card-level-game_title feed-activity-game-link" style='<?php if(!$multiple){ echo "display:none;"; } ?>' data-gbid="<?php echo $game->_gbid; ?>"><?php echo $game->_title; ?></div>
	  		<?php echo $event->_quote; ?>
	    	<?php if($user->_security == "Authenticated" && $xp->_authenticxp == "Yes"){ ?> 
	  		<i class='authenticated-mark material-icons' title="Verified Account">verified_user</i>
	  		<?php } ?>
			<div class="feed-action-details">
				<?php 
				DisplayFeedXPIcon($xp, $event);

				$eventXP = "";
				$isWatched = false;
				if(sizeof($xp->_playedxp) > 0){
					foreach($xp->_playedxp as $playxp){
						if($playxp->_entereddate == $event->_date)
							$eventXP = $playxp;
					}
				}else if(sizeof($xp->_watchedxp) > 0 && $eventXP == ""){
					foreach($xp->_watchedxp as $watchxp){
						if($watchxp->_entereddate == $event->_date)
							$eventXP = $watchxp;
							$isWatched = true;
					}
				}

				if($eventXP == "" && sizeof($xp->_playedxp) > 0)
					$eventXP = $xp->_playedxp[0];


				if($eventXP != "" && !$isWatched){
						if($eventXP->_completed == "101")
							$percent = 100;
						else
							$percent = $eventXP->_completed; ?>
						<div class="feed-action-details-card">
							<i class="material-icons" style='font-size:1em;vertical-align: middle;'>games</i>
							<?php 
							if($percent < 100){ echo $percent."%"; }else{ ?>
								Finished
							<?php } 
							?>
						</div>
						<?php if($eventXP->_hours > 0){ ?>
							<div class="feed-action-details-card">
								<i class="material-icons" style='font-size:1em;vertical-align: middle;'>access_time</i>
								<?php echo $eventXP->_hours." hours"; ?>
							</div>
						<?php } ?>
						<?php if($user->_security != "Journalist"){ ?>
						<div class="feed-action-details-card">
							<i class="material-icons" style='font-size:1em;vertical-align: middle;'>tv</i>
							<?php echo $eventXP->_platform; ?>
						</div>
						<?php } ?>
				<?php
				}else if($eventXP != "" && $isWatched){
						$length = $eventXP->_length;
						if($length == "Watched a speed run"){
							$icon = "directions_walk";
						}else if($length == "Watched a complete single player playthrough" || $length == "Watched a complete playthrough"){
							$icon = "beenhere";
						}else if($length == "Watched competitive play"){
							$icon = "headset_mic";
						}else if($length == "Watched multiple hours" || $length == "Watched gameplay" || $length == "Watched an hour or less"){
							$icon = "videogame_asset";
						}else if($length == "Watched promotional gameplay"){
							$icon = "movie_creation";
						}else if($length == "Watched a developer diary"){
							$icon = "class";
						}else{
							$icon = "theaters";
						}
						?>
						<div class="feed-action-details-card">
							<i class="material-icons" style='font-size:1.5em;vertical-align: middle;'><?php echo $icon; ?></i>
							<?php echo $length; ?>
						</div>
				<?php } 
		
				?>
		  	</div>
		  </div>
		  <div class='divider'></div>
	      <div class="feed-action-container">
				<div class="myxp-details-agree-container" <?php if($agreedcount > 0){ ?>style='display:block;'<?php } ?>>
					<div class='agreeBtnCount badge-lives' <?php if($agreedcount > 0){ ?>style='display:inline-block;'<?php } ?>><?php echo $agreedcount; ?></div>
					<div class="myxp-details-agree-list">
					<?php
						$i = 0;
						while($i < sizeof($agrees) && $i < 15){ ?>
						<div class="myxp-details-agree-listitem">
							<?php $useragree = GetUser($agrees[$i]); ?>
							<div class="user-avatar" style="margin-top:3px;width:30px;border-radius:50%;display: inline-block;float:left;height:30px;background:url(<?php echo $useragree->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;" title='<?php echo $useragree->_username; ?>'></div>
						</div>
					<?php	
					$i++;
					} ?>
					</div>
				</div>
	      		<?php if($xp->_link != ''){ ?>
					<a href='<?php echo $xp->_link; ?>' target='_blank' ><div class="btn-flat waves-effect readBtn">READ</div></a>
				<?php } ?>
	      		<?php if($event->_url != '' && $_SESSION['logged-in']->_id > 0){ ?>
					<div data-url='<?php echo $event->_url; ?>' data-gameid='<?php echo $game->_id; ?>' class="btn-flat waves-effect watchBtn">WATCH</div>
				<?php } ?>
				<?php if($_SESSION['logged-in']->_id != $user->_id && $event->_quote != ''){ ?>
					<div class="btn-flat waves-effect <?php if(in_array($_SESSION['logged-in']->_id, $agrees) || $_SESSION['logged-in']->_id <= 0){ echo "disagreeBtn"; }else{ echo "agreeBtn"; } ?>" data-eventid="<?php echo $event->_id; ?>" data-agreedwith="<?php echo $user->_id; ?>" data-gameid="<?php echo $xp->_gameid; ?>" data-username="<?php echo $username ?>"><?php if(in_array($_SESSION['logged-in']->_id, $agrees)){ echo "- 1up"; }else if($_SESSION['logged-in']->_id > 0){  echo "+ 1up"; } ?></div>
				<?php } ?>
				<!--<div class="shareBtn btn-flat waves-effect" data-userid='<?php echo  $event->_userid; ?>' data-eventid="<?php echo $event->_id; ?>"><i class="material-icons">share</i></div>-->
	      </div>
	    </div>
	  </div>
	<?php }else{ ?>
	  <div class="feed-horizontal-card z-depth-1"  data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
	    <a class="feed-card-image waves-effect waves-block" href="/#game/<?php echo $game->_id; ?>/<?php echo urlencode($game->_title); ?>/" style="background:url(<?php echo $game->_imagesmall; ?>) 50% 40%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;width:100%;height:150px;" onclick="var event = arguments[0] || window.event; event.stopPropagation();">
	    </a>
	    <div class="feed-card-content" style='width:100%;margin-top:150px;'>
	      <div class="feed-card-title-special">
			<div class="feed-card-level-game_title feed-activity-game-link" style='font-size:0.5em;<?php if(!$multiple){ echo "display:none;"; } ?>' data-gbid="<?php echo $game->_gbid; ?>"><?php echo $game->_title; ?></div>
	  		<?php echo $event->_quote; ?>
	    	<?php if($user->_security == "Authenticated" && $xp->_authenticxp == "Yes"){ ?> 
	  		<i class='authenticated-mark material-icons' title="Verified Account">verified_user</i>
	  		<?php } ?>
			<div class="feed-action-details">
				<?php 
				DisplayFeedXPIcon($xp, $event, true);

				$eventXP = "";
				$isWatched = false;
				if(sizeof($xp->_playedxp) > 0){
					foreach($xp->_playedxp as $playxp){
						if($playxp->_entereddate == $event->_date)
							$eventXP = $playxp;
					}
				}else if(sizeof($xp->_watchedxp) > 0 && $eventXP == ""){
					foreach($xp->_watchedxp as $watchxp){
						if($watchxp->_entereddate == $event->_date)
							$eventXP = $watchxp;
							$isWatched = true;
					}
				}

				if($eventXP == "" && sizeof($xp->_playedxp) > 0)
					$eventXP = $xp->_playedxp[0];


				if($eventXP != "" && !$isWatched){
						if($eventXP->_completed == "101")
							$percent = 100;
						else
							$percent = $eventXP->_completed; ?>
						<div class="feed-action-details-card">
							<i class="material-icons" style='font-size:0.7em;vertical-align: middle;'>games</i>
							<?php 
							if($percent < 100){ echo $percent."%"; }else{ ?>
								Finished
							<?php } 
							?>
						</div>
						<?php if($eventXP->_hours > 0){ ?>
							<div class="feed-action-details-card">
								<i class="material-icons" style='font-size:0.7em;vertical-align: middle;'>access_time</i>
								<?php echo $eventXP->_hours." hours"; ?>
							</div>
						<?php } ?>
						<?php if($user->_security != "Journalist"){ ?>
						<div class="feed-action-details-card">
							<i class="material-icons" style='font-size:0.7em;vertical-align: middle;'>tv</i>
							<?php echo $eventXP->_platform; ?>
						</div>
						<?php } ?>
				<?php
				}else if($eventXP != "" && $isWatched){
						$length = $eventXP->_length;
						if($length == "Watched a speed run"){
							$icon = "directions_walk";
						}else if($length == "Watched a complete single player playthrough" || $length == "Watched a complete playthrough"){
							$icon = "beenhere";
						}else if($length == "Watched competitive play"){
							$icon = "headset_mic";
						}else if($length == "Watched multiple hours" || $length == "Watched gameplay" || $length == "Watched an hour or less"){
							$icon = "videogame_asset";
						}else if($length == "Watched promotional gameplay"){
							$icon = "movie_creation";
						}else if($length == "Watched a developer diary"){
							$icon = "class";
						}else{
							$icon = "theaters";
						}
						?>
						<div class="feed-action-details-card">
							<i class="material-icons" style='font-size:1.3em;vertical-align: middle;'><?php echo $icon; ?></i>
							<?php echo $length; ?>
						</div>
				<?php } 
		
				?>
		  	</div>
		  </div>
		  <div class='divider'></div>
	      <div class="feed-action-container">
				<div class="myxp-details-agree-container" <?php if($agreedcount > 0){ ?>style='display:block;'<?php } ?>>
					<div class='agreeBtnCount badge-lives' <?php if($agreedcount > 0){ ?>style='display:inline-block;'<?php } ?>><?php echo $agreedcount; ?></div>
					<div class="myxp-details-agree-list">
					<?php
						$i = 0;
						while($i < sizeof($agrees) && $i < 15){ ?>
						<div class="myxp-details-agree-listitem">
							<?php $useragree = GetUser($agrees[$i]); ?>
							<div class="user-avatar" style="margin-top:3px;width:30px;border-radius:50%;display: inline-block;float:left;height:30px;background:url(<?php echo $useragree->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;" title='<?php echo $useragree->_username; ?>'></div>
						</div>
					<?php	
					$i++;
					} ?>
					</div>
				</div>
	      		<?php if($xp->_link != ''){ ?>
					<a href='<?php echo $xp->_link; ?>' target='_blank' ><div class="btn-flat waves-effect readBtn">READ</div></a>
				<?php } ?>
	      		<?php if($event->_url != '' && $_SESSION['logged-in']->_id > 0){ ?>
					<div data-url='<?php echo $event->_url; ?>' data-gameid='<?php echo $game->_id; ?>' class="btn-flat waves-effect watchBtn">WATCH</div>
				<?php } ?>
				<?php if($_SESSION['logged-in']->_id != $user->_id && $event->_quote != ''){ ?>
					<div class="btn-flat waves-effect <?php if(in_array($_SESSION['logged-in']->_id, $agrees) || $_SESSION['logged-in']->_id <= 0){ echo "disagreeBtn"; }else{ echo "agreeBtn"; } ?>" data-eventid="<?php echo $event->_id; ?>" data-agreedwith="<?php echo $user->_id; ?>" data-gameid="<?php echo $xp->_gameid; ?>" data-username="<?php echo $username ?>"><?php if(in_array($_SESSION['logged-in']->_id, $agrees)){ echo "- 1up"; }else if($_SESSION['logged-in']->_id > 0){  echo "+ 1up"; } ?></div>
				<?php } ?>
				<!--<div class="shareBtn btn-flat waves-effect" data-userid='<?php echo  $event->_userid; ?>' data-eventid="<?php echo $event->_id; ?>"><i class="material-icons">share</i></div>-->
	      </div>
	    </div>
	  </div>
 <?php
	}
}

function FeedConnectionItem($feed, $conn, $mutualconn){
	$user = GetUser($feed[0][0]->_userid);
	//quote will be following user id
	$followinguser = GetUser($feed[0][0]->_quote);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; }
	if($followinguser->_security == "Journalist" || $followinguser->_security == "Authenticated"){ $followingusername = $followinguser->_first." ".$followinguser->_last; }else{ $followingusername = $followinguser->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" data-id="<?php echo $user->_id; ?>" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_badge != ""){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
    		</div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp"><i class="material-icons" style='font-size: 1em;margin-top: 4px;'>group</i></div>
		</div>
		<div class="feed-content-col">
			<div class="feed-activity-title">
				<?php if(sizeof($feed) > 1){ ?>
					<span class='feed-activity-user-link' data-id='<?php echo $user->_id; ?>'><?php echo $username; ?></span> 
					followed <?php echo sizeof($feed); ?> people 
					<span class='feed-activity-when-info'><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i><?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date); ?></span>
				<?php }else{ ?>
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
					followed
					<span class="feed-activity-user-link-action" data-userid="<?php echo $followinguser->_id; ?>"><?php echo $followingusername; ?></span>
					<span class="feed-activity-when-info"><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
				<?php } ?>
			</div>
			<div class="feed-activity-game-container">
				<?php foreach($feed as $card){ 
					$event = $card[0];
					$game = $card[1];
					//quote will be following user id
					$following = GetUser($event->_quote);
					DisplayUserCard($following, 0, 0, 0);
					//FeedConnectionCard($user, $event, $following); 
				}?>
			</div>
		</div>
	</div>
<?php
}

function FeedConnectionCard($user, $event, $following){ 
	if($following->_security == "Journalist"){ $followingusername = $following->_first." ".$following->_last; }else{ $followingusername = $following->_username; } 
?>
  <div class="feed-bookmark-card z-depth-1"  data-userid="<?php echo $following->_id; ?>">
    <div class="feed-bookmark-image waves-effect waves-block" style="display:inline-block;background:url(<?php echo $following->_avatar; ?>) 50% 50%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    	<div class="feed-card-level-game_title feed-activity-game-link feed-bookmark-title"><?php echo $followingusername; ?></div>
    </div>
  </div>
<?php
}

function FeedCollectionUpdate($feed, $conn, $mutualconn){
	$user = GetUser($feed[0][0]->_userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" data-id="<?php echo $user->_id; ?>" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_badge != ""){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
    		</div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp"><i class="material-icons" style='font-size: 1em;margin-top: 4px;'>collections</i></div>
		</div>
		<div class="feed-content-col">
			<div class="feed-activity-title">
				<?php if(sizeof($feed) > 1){ ?>
					<span class='feed-activity-user-link' data-id='<?php echo $user->_id; ?>'><?php echo $username; ?></span> 
					added <?php echo sizeof($feed); ?> games to <span class="feed-activity-collection-link" data-cid="<?php echo $feed[0][2]->_id; ?>"><?php echo $feed[0][2]->_name; ?></span>
					<span class='feed-activity-when-info'><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i><?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date); ?></span>
				<?php }else{ ?>
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
					added
					<span class="feed-activity-game-link" data-gbid="<?php echo $feed[0][1]->_gbid; ?>"><?php echo $feed[0][1]->_title; ?></span>
					to <span class="feed-activity-collection-link" data-cid="<?php echo $feed[0][2]->_id; ?>"><?php echo $feed[0][2]->_name; ?></span>
					<span class="feed-activity-when-info"><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
				<?php } ?>
			</div>
			<div class="feed-activity-game-container">
				<?php foreach($feed as $card){ 
					$event = $card[0];
					$game = $card[1];
					DisplayGameCard($game, '', '');
				}?>
			</div>
		</div>
	</div>
<?php
}

function FeedGameCollectionCard($game, $user, $event){ ?>
	  <a class="feed-bookmark-card z-depth-1" href="/#game/<?php echo $game->_id; ?>/<?php echo urlencode($game->_title); ?>/"  data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>" onclick="var event = arguments[0] || window.event; event.stopPropagation();">
	    <div class="feed-bookmark-image waves-effect waves-block" style="display:inline-block;background:url(<?php echo $game->_imagesmall; ?>) 50% 50%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
	    	<div class="feed-card-level-game_title feed-activity-game-link feed-bookmark-title" data-gbid="<?php echo $game->_gbid; ?>"><?php echo $game->_title; ?></div>
	    </div>
	  </a>
<?php
}

function FeedBookmarkItem($feed, $conn, $mutualconn){
	$user = GetUser($feed[0][0]->_userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" data-id="<?php echo $user->_id; ?>" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_badge != ""){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
    		</div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp"><i class="material-icons" style="font-size: 1em;margin-top: 5px;">bookmark</i></div>
		</div>
		<div class="feed-content-col">
			<div class="feed-activity-title">
				<?php if(sizeof($feed) > 1){ ?>
					<span class='feed-activity-user-link' data-id='<?php echo $user->_id; ?>'><?php echo $username; ?></span> 
					bookmarked <?php echo sizeof($feed); ?> games 
					<span class='feed-activity-when-info'><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i><?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date); ?></span>
				<?php }else{ ?>
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
					bookmarked
					<span class="feed-activity-game-link" data-gbid="<?php echo $feed[0][1]->_gbid; ?>"><?php echo $feed[0][1]->_title; ?></span>
					<span class="feed-activity-when-info"><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
				<?php } ?>
			</div>
			<div class="feed-activity-game-container">
				<?php foreach($feed as $card){ 
					$event = $card[0];
					$game = $card[1];
					$xp = $card[3];
					FeedGameBookmarkCard($game, $user, $event, $xp); 
				}?>
			</div>
		</div>
	</div>
<?php
}

function FeedGameBookmarkCard($game, $user, $event, $xp){ ?>
	<a class="feed-bookmark-card z-depth-1" href="/#game/<?php echo $game->_id; ?>/<?php echo urlencode($game->_title); ?>/"  data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>" onclick="var event = arguments[0] || window.event; event.stopPropagation();">
	    <div class="feed-bookmark-image waves-effect waves-block" style="display:inline-block;background:url(<?php echo $game->_imagesmall; ?>) 50% 50%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
	    	<i class="material-icons" style='  position: absolute;top: -10px;right: 20px;font-size: 3em;color: red;'>bookmark</i>
	    	<div class="feed-card-level-game_title feed-activity-game-link feed-bookmark-title" data-gbid="<?php echo $game->_gbid; ?>"><?php echo $game->_title; ?></div>
	    </div>
	  </a>
<?php
}

function FeedTierChangedItem($feed, $conn, $mutualconn){
	$user = GetUser($feed[0][0]->_userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" data-id="<?php echo $user->_id; ?>" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_badge != ""){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
    		</div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp"><i class="mdi-action-swap-vert"></i></div>
		</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
					<?php if(sizeof($feed) > 1){ ?>
						 changed the tier of <?php echo sizeof($feed); ?> games
					<?php }else{ ?>
						changed the tier for 
					<?php } ?>
					<?php if(sizeof($feed) == 1){ ?>
						<span class="feed-activity-game-link" data-gbid="<?php echo $feed[0][1]->_gbid; ?>"><?php echo $feed[0][1]->_title; ?></span>
					<?php } ?>
					<span class="feed-activity-when-info"><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
				</div>
			<div class="feed-activity-game-container">
				<?php
					if(sizeof($feed) > 1)
						$multiple = true;
					else
						$multiple = false;
						
					foreach($feed as $card){
						$event = $card[0];
						$game = $card[1];
						$xp = $card[3];
						FeedTierChangedCard($game, $user, $event, $xp, $multiple);
				}
				?>
			</div>
		</div>
	</div>
<?php
}

function FeedTierChangedCard($game, $user, $event, $xp, $multiple){ 
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
	$tierdata = explode(",",$event->_quote);
	$before = $tierdata[0];
	$after = $tierdata[1];
	?>
  <div class="feed-horizontal-card z-depth-1"  data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
    <div class="feed-card-image waves-effect waves-block" style="background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    </div>
    <div class="feed-card-content">
      <div class="feed-card-title">
      	<?php /*if($multiple){*/ ?>
      		<div class="feed-card-level-game_title feed-activity-game-link" data-gbid="<?php echo $game->_gbid; ?>"><?php echo $game->_title; ?></div>
      	<?php /*} */ ?>
    		<div class="feed-tier-changed-before tierTextColor<?php echo $before; ?>"><div class="feed-tier-changed-label">TIER</div> <?php echo $before; ?></div>
    		<?php if($before > $after){ ?>
    		<i class="feed-tier-rising mdi-action-trending-neutral"></i>
    		<?php }else{ ?>
    		<i class="feed-tier-falling mdi-action-trending-neutral"></i>
    		<?php } ?>
    		<div class="feed-tier-changed-after tierTextColor<?php echo $after; ?>"><div class="feed-tier-changed-label">TIER</div> <?php echo $after; ?></div>
      </div>
    </div>
  </div>
 <?php
}

function FeedQuoteChangedItem($feed, $conn, $mutualconn){
	$user = GetUser($feed[0][0]->_userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" data-id="<?php echo $user->_id; ?>" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_badge != ""){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
    		</div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp"><i class="material-icons" style='font-size: 1em;margin-top: 4px;'>description</i></div>
		</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
					<?php if(sizeof($feed) > 1){ ?>
						posted <?php echo sizeof($feed); ?> times
					<?php }else{ ?>
						posted about  
					<?php } ?>
					<?php if(sizeof($feed) == 1){ ?>
						<span class="feed-activity-game-link" data-gbid="<?php echo $feed[0][1]->_gbid; ?>"><?php echo $feed[0][1]->_title; ?></span>
					<?php } ?>
					<span class="feed-activity-when-info"><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
				</div>
			<div class="feed-activity-game-container">
				<?php
					if(sizeof($feed) > 1)
						$multiple = true;
					else
						$multiple = false;
						
					foreach($feed as $card){
						$event = $card[0];
						$game = $card[1];
						$xp = $card[3];
						$agrees = GetAgreesForEvent($event->_id);
						$agreedcount = array_shift($agrees);
						FeedQuoteChangedCard($game, $user, $event, $xp, $multiple, $agrees, $agreedcount, $conn, $mutualconn);
				}
				?>
			</div>
		</div>
	</div>
<?php
}

function FeedQuoteChangedCard($game, $user, $event, $xp, $multiple, $agrees, $agreedcount, $conn, $mutualconn){
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
	?>
  <div class="feed-horizontal-card z-depth-1"  data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
    <div class="feed-card-image waves-effect waves-block" style="background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    </div>
    <div class="feed-card-content">
      <div class="feed-card-title">
      	<?php if($multiple){ ?>
      		<div class="feed-card-level-game_title feed-activity-game-link" data-gbid="<?php echo $game->_gbid; ?>"><?php echo $game->_title; ?></div>
      	<?php } ?>
      	"<?php echo $event->_quote; ?>"
    	<?php if($user->_security == "Authenticated" && $xp->_authenticxp == "Yes"){ ?> 
      		<div class='authenticated-mark mdi-action-done' title="Verified Account"></div>
  		<?php } ?>
      </div>
	  <div class='divider'></div>
      <div class="feed-action-container">
			<div class="myxp-details-agree-container" <?php if($agreedcount > 0){ ?>style='display:block;'<?php } ?>>
				<div class='agreeBtnCount badge-lives' <?php if($agreedcount > 0){ ?>style='display:inline-block;'<?php } ?>><?php echo $agreedcount; ?></div>
				<div class="myxp-details-agree-list">
				<?php
					$i = 0;
					while($i < sizeof($agrees) && $i < 15){ ?>
					<div class="myxp-details-agree-listitem">
						<?php $useragree = GetUser($agrees[$i]); ?>
						<div class="user-avatar" style="margin-top:3px;width:30px;border-radius:50%;display: inline-block;float:left;height:30px;background:url(<?php echo $useragree->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;" title='<?php echo $useragree->_username; ?>'></div>
					</div>
				<?php	
				$i++;
				} ?>
				</div>
			</div>
			<?php if($_SESSION['logged-in']->_id != $user->_id && $event->_quote != ''){ ?>
				<div class="btn-flat waves-effect <?php if(in_array($_SESSION['logged-in']->_id, $agrees) || $_SESSION['logged-in']->_id <= 0){ echo "disagreeBtn"; }else{ echo "agreeBtn"; } ?>" data-eventid="<?php echo $event->_id; ?>" data-agreedwith="<?php echo $user->_id; ?>" data-gameid="<?php echo $xp->_gameid; ?>" data-username="<?php echo $username ?>"><?php if(in_array($_SESSION['logged-in']->_id, $agrees)){ echo "- 1up"; }else if($_SESSION['logged-in']->_id > 0){  echo "+ 1up"; } ?></div>
			<?php } ?>
			<!--<div class="shareBtn btn-flat waves-effect" data-userid='<?php echo  $event->_userid; ?>' data-eventid="<?php echo $event->_id; ?>"><i class="material-icons">share</i></div>-->
       </div>
    </div>
  </div>
 <?php 
}

function FeedGameReleasesItem($feed){ ?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
			<div class="feed-avatar" data-id="<?php echo $user->_id; ?>" style='box-shadow:none;'></div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp"><i class="material-icons" style='font-size: 1em;margin-top: 4px;'>event</i></div>
		</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<?php if(sizeof($feed) > 1){ ?>
					<?php echo sizeof($feed); ?> new releases today
					<?php }else{ ?>
					<span class="feed-activity-game-link" data-gbid="<?php echo $feed[0][1]->_gbid; ?>"><?php echo $feed[0][1]->_title; ?> was released today</span>
					<?php } ?>
					<span class="feed-activity-when-info"><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
				</div>
			<div class="feed-activity-game-container">
				<?php foreach($feed as $card){ 
					$game = $card[1];
					DisplayGameCard($game, '', '');
				}?>
			</div>
		</div>
	</div>
<?php
}

function FeedGameReleaseDetailsCard($game){ ?>
  <div class="feed-release-card z-depth-1"  data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
    <div class="feed-release-image waves-effect waves-block" style="display:inline-block;background:url(<?php echo $game->_image; ?>) 50% 50%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    	<div class="feed-card-level-game_title feed-activity-game-link feed-release-title" data-gbid="<?php echo $game->_gbid; ?>"><?php echo $game->_title; ?></div>
    </div>
  </div>
<?php
}

function FeedCollectionCreationItem($feed, $conn, $mutualconn){
	$user = GetUser($feed[0][0]->_userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" data-id="<?php echo $user->_id; ?>" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_badge != ""){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
    		</div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp"><i class="material-icons" style='font-size: 1em;margin-top: 4px;'>collections</i></div>
		</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
					<?php if(sizeof($feed) > 1){ ?>
						created <?php echo sizeof($feed); ?> Collections
					<?php }else{ ?>
						created a new Collection 
					<?php } ?>
					<?php if(sizeof($feed) == 1){ ?>
						<span class="feed-activity-game-link" data-gbid="<?php echo $feed[0][1]->_gbid; ?>"><?php echo $feed[0][1]->_title; ?></span>
					<?php } ?>
					<span class="feed-activity-when-info"><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
				</div>
			<div class="feed-activity-game-container">
				<?php
					if(sizeof($feed) > 1)
						$multiple = true;
					else
						$multiple = false;
						
					foreach($feed as $card){
						$event = $card[0];
						$collection = $card[1];
						DisplayCollection($collection);
					} 
				?>
			</div>
		</div>
	</div>
<?php
}

function FeedCollectionFollowItem($feed, $conn, $mutualconn){
	$user = GetUser($feed[0][0]->_userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" data-id="<?php echo $user->_id; ?>" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_badge != ""){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
    		</div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp"><i class="mdi-image-collections"></i></div>
		</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
					<?php if(sizeof($feed) > 1){ ?>
						followed <?php echo sizeof($feed); ?> Collections
					<?php }else{ ?>
						followed a collection owned by 
					<?php } ?>
					<?php if(sizeof($feed) == 1){ 
						$onecollection = $feed[0][1];	
						$followinguser = GetUser($onecollection->_owner);
						if($followinguser->_security == "Journalist" || $followinguser->_security == "Authenticated"){ $followingusername = $followinguser->_first." ".$followinguser->_last; }else{ $followingusername = $followinguser->_username; }
					?>
						<span class="feed-activity-user-link-action" data-userid="<?php echo $followinguser->_id; ?>"><?php echo $followingusername; ?></span>
					<?php } ?>
					<span class="feed-activity-when-info"><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
				</div>
			<div class="feed-activity-game-container">
				<?php
					if(sizeof($feed) > 1)
						$multiple = true;
					else
						$multiple = false;
						
					foreach($feed as $card){
						$event = $card[0];
						$collection = $card[1];
						DisplayCollection($collection);
					} 
				?>
			</div>
		</div>
	</div>
<?php
}

function FeedSteamImport($feed, $conn, $mutualconn){
	$user = GetUser($feed[0][0]->_userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" data-id="<?php echo $user->_id; ?>" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_badge != ""){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
    		</div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp"><i class="fa fa-steam"></i></div>
		</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
						imported <?php echo number_format($feed[0][3]); ?> games from their Steam Library
					<span class="feed-activity-when-info"><i class="material-icons" style="font-size: 1em;position: relative;top: 2px;">schedule</i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
				</div>
			<div class="feed-activity-game-container">
				<?php
					$played = $feed[0][2];
					DisplayCollection($played);
					$backlog = $feed[0][1];
					DisplayCollection($backlog);
				?>
			</div>
		</div>
	</div>
<?php
}

function DisplayFeedXPIcon($xp, $event, $special = false){
	if(sizeof($xp->_playedxp) > 0){
		?>
		<div class="feed-action-details-card">
			<i class='material-icons tierTextColor<?php echo $event->_tier; ?>' style='<?php if($special){ echo "font-size: 1.3em;position: relative;top: 6px;left:-3px;"; }else{ echo "font-size: 1.6em;position: relative;top: 6px;left:-3px;"; } ?>'>
				<?php if($event->_tier == 1){ echo "sentiment_very_satisfied"; }else if($event->_tier == 2){ echo "sentiment_satisfied"; }else if($event->_tier == 3){ echo "sentiment_neutral"; }else if($event->_tier == 4){ echo "sentiment_dissatisfied"; }else if($event->_tier == 5){ echo "sentiment_very_dissatisfied"; } ?>						
			</i>
		</div>
		<?php
	}else if(sizeof($xp->_watchedxp) > 0){
		?>
		<div class="feed-action-details-card">
			<i class='material-icons tierTextColor<?php echo $event->_tier; ?>' style='<?php if($special){ echo "font-size: 1.3em;position: relative;top: 6px;left:-3px;"; }else{ echo "font-size: 1.6em;position: relative;top: 6px;left:-3px;"; } ?>'>
				<?php if($event->_tier == 1){ echo "sentiment_very_satisfied"; }else if($event->_tier == 2){ echo "sentiment_satisfied"; }else if($event->_tier == 3){ echo "sentiment_neutral"; }else if($event->_tier == 4){ echo "sentiment_dissatisfied"; }else if($event->_tier == 5){ echo "sentiment_very_dissatisfied"; } ?>	
			</i>
		</div>
		<?php
	}
}

function DisplayFeedTierIcon($xp, $event){ 
	if(sizeof($xp->_playedxp) > 0){
		if($xp->_playedxp[0]->_completed == "101")
			$percent = 100;
		else
			$percent = $xp->_playedxp[0]->_completed;
			
		if($percent == 100){ ?>
			<div class="feed-card-icon tier<?php echo $event->_tier; ?>BG"  title="<?php echo "Tier ".$event->_tier." - Completed"; ?>">
				<i class='material-icons' style='font-size:2em;color:white;'>
					<?php if($event->_tier == 1){ echo "sentiment_very_satisfied"; }else if($event->_tier == 2){ echo "sentiment_satisfied"; }else if($event->_tier == 3){ echo "sentiment_neutral"; }else if($event->_tier == 4){ echo "sentiment_dissatisfied"; }else if($event->_tier == 5){ echo "sentiment_very_dissatisfied"; }  ?>	
				</i>
			</div>
	  	<?php }else{ ?>
			<div class="feed-card-icon">
			  <div class="c100 mini <?php if($event->_tier == 1){ echo "tierone"; }else if($event->_tier == 2){ echo "tiertwo"; }else if($event->_tier == 3){ echo "tierthree"; }else if($event->_tier == 4){ echo "tierfour"; }else if($event->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?> z-depth-1" title="<?php echo "Tier ".$event->_tier." - ".$percent."% finished"; ?>" style='background-color:white;'>
			  	  <span class='tierTextColor<?php echo $event->_tier; ?> tierInProgress' style='background-color:white;'>
					<i class='material-icons' style='font-size:2em;'>
						<?php if($event->_tier == 1){ echo "sentiment_very_satisfied"; }else if($event->_tier == 2){ echo "sentiment_satisfied"; }else if($event->_tier == 3){ echo "sentiment_neutral"; }else if($event->_tier == 4){ echo "sentiment_dissatisfied"; }else if($event->_tier == 5){ echo "sentiment_very_dissatisfied"; }  ?>	
					</i>
				  </span>
				  <div class="slice">
				    <div class="bar minibar"></div>
				    <div class="fill"></div>
				  </div>
				</div>
			</div>
		<?php
	  	}
	}else{
  		$percent = 20;
    	$length = "";
		foreach($xp->_watchedxp as $watched){
			if($watched->_length == "Watched a speed run" || $watched->_length == "Watched a complete single player playthrough" || $watched->_length == "Watched a complete playthrough"){
				$percent = 101;
				$length = $watched->_length;
			}else if($percent < 100 && ($watched->_length == "Watched multiple hours" || $watched->_length == "Watched gameplay" || $watched->_length == "Watched an hour or less")){
				$percent = 100;
				$length = $watched->_length;
			}else if($percent < 50 && ($watched->_length == "Watched promotional gameplay" || $watched->_length == "Watched a developer diary")){
				$percent = 50;
				$length = $watched->_length;
			}else{
				$length = $watched->_length;
			}
		}
		
		if($percent == 101){ ?>
	      	<div class="feed-card-icon tier<?php echo $event->_tier; ?>BG" title="<?php echo "Tier ".$event->_tier." - ".$length; ?>">
					<i class='material-icons' style='font-size:2em;color:white;'>
						<?php DisplayXPFace($event->_tier);  ?>		
					</i>
		  	</div>
		<?php }else{ ?>
			<div class="feed-card-icon">
			  <div class="c100 mini <?php if($event->_tier == 1){ echo "tierone"; }else if($event->_tier == 2){ echo "tiertwo"; }else if($event->_tier == 3){ echo "tierthree"; }else if($event->_tier == 4){ echo "tierfour"; }else if($event->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?> z-depth-1" title="<?php echo "Tier ".$event->_tier." - ".$length; ?>" style='background-color:white;'>
			  	  <span class='tierTextColor<?php echo $event->_tier; ?> tierInProgress' style='background-color:white;'>
					<i class='material-icons' style='font-size:2em;'>
						<?php DisplayXPFace($event->_tier);  ?>	
					</i>
					</span>
				  <div class="slice">
				    <div class="bar minibar"></div>
				    <div class="fill"></div>
				  </div>
				</div>
			</div>
		<?php }
	}
}

function DisplayXPFace($tier){
	if($tier == 1){ 
		echo "sentiment_very_satisfied"; 
	}else if($tier == 2){ 
		echo "sentiment_satisfied"; 
	}else if($tier == 3){ 
		echo "sentiment_neutral";
	}else if($tier == 4){ 
		echo "sentiment_dissatisfied"; 
	}else if($tier == 5){ 
		echo "sentiment_very_dissatisfied";
	}
}

function DisplayTierBadge($tier){
	if($tier == 1){ 
		echo "looks_one"; 
	}else if($tier == 2){ 
		echo "looks_two"; 
	}else if($tier == 3){ 
		echo "looks_3"; 
	}else if($tier == 4){ 
		echo "looks_4"; 
	}else if($tier == 5){ 
		echo "looks_5"; 
	}
}

function DisplayStarBadge($tier){
	if($tier == 1){ 
		echo "star"; 
	}else if($tier == 2){ 
		echo "star"; 
	}else if($tier == 3){ 
		echo "star"; 
	}else if($tier == 4){ 
		echo "star"; 
	}else if($tier == 5){ 
		echo "star"; 
	}
}

function DisplayStarSequence($stars, $colors = false){
	if($stars == 1){ 
	?>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor1 star-icon star-icon-1 star-icon-pre"; } ?>" >star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor1 star-icon star-icon-2 star-icon-pre"; } ?>" >star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor1 star-icon star-icon-3 star-icon-pre"; } ?>" >star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor1 star-icon star-icon-4 star-icon-pre"; } ?>" >star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor1 star-icon star-icon-5 star-icon-pre"; } ?>" >star</i>
	<?php
	}else if($stars == 2){ 
	?>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor2 star-icon star-icon-1 star-icon-pre"; } ?>">star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor2 star-icon star-icon-2 star-icon-pre"; } ?>">star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor2 star-icon star-icon-3 star-icon-pre"; } ?>">star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor2 star-icon star-icon-4 star-icon-pre"; } ?>">star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "star-icon star-icon-5"; } ?>">star_border</i>
	<?php
	}else if($stars == 3){ 
	?>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor3 star-icon star-icon-1 star-icon-pre"; } ?>" >star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor3 star-icon star-icon-2 star-icon-pre"; } ?>" >star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor3 star-icon star-icon-3 star-icon-pre"; } ?>" >star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "star-icon star-icon-4"; } ?>" >star_border</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "star-icon star-icon-5"; } ?>" >star_border</i>
	<?php
	}else if($stars == 4){ 
	?>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor4 star-icon star-icon-1 star-icon-pre"; } ?>" >star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor4 star-icon star-icon-2 star-icon-pre"; } ?>" >star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "star-icon star-icon-3"; } ?>" >star_border</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "star-icon star-icon-4"; } ?>" >star_border</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "star-icon star-icon-5"; } ?>" >star_border</i>
	<?php
	}else if($stars == 5){ 
	?>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "tierTextColor5 star-icon star-icon-1 star-icon-pre"; } ?>" >star</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "star-icon star-icon-2"; } ?>" >star_border</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "star-icon star-icon-3"; } ?>" >star_border</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "star-icon star-icon-4"; } ?>" >star_border</i>
	<i class="material-icons tier-modal-icon <?php if($colors){ echo "star-icon star-icon-5"; } ?>" >star_border</i>
	<?php
	}
	
}
?>
