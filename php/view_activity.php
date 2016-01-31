<?php
function DisplayActivity($userid, $filter){
?>	
	<div class='row' style='margin-top:20px'>
		<div class="activity-top-level">
			<?php 
				DisplayMainActivity($userid, $filter);
			?>
		</div>
		<?php
			DisplayActivitySecondaryContent($userid);
		?>
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
		
		if($feeditem[5] == $last_type){
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
			if(sizeof($group) > 0)
				$groupfeed[] = $group;
			unset($group);
			$group[] = $feeditem;
			$last_type = $feeditem[5];
			$last_user = $feeditem[0]->_userid;
		}
	}
	//The last group will get missed in the loop
	if(sizeof($group) > 0)
		$groupfeed[] = $group;
	
	?>
		<div class="col s12" style='position: relative;'> 
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
		
		if($feeditem[5] == $last_type){
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
			if(sizeof($group) > 0)
				$groupfeed[] = $group;
			unset($group);
			$group[] = $feeditem;
			$last_type = $feeditem[5];
			$last_user = $feeditem[0]->_userid;
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
			}
		}
}

function FeedDateDivider($date){
	$datetime = explode(" ", ConvertDateToActivityFormat($date));
	?>
	<div class="row feed-date-divider" data-date="<?php echo $date; ?>">
		<div class="col s12">
			<div class="feed-date-divider-month">
				<?php echo $datetime[0]; ?>
			</div>
			<div class="feed-date-divider-bullet"></div>
			<div class="feed-date-divider-day">
				<?php echo $datetime[1]; ?>
			</div>
		</div>
	</div>
	<?php
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
	if($user->_security == "Journalist"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_title == "SRANK"){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/SBadge.svg'></img><?php } ?>
    		</div>
			<?php DisplayUserPreviewCard($user, $conn, $mutualconn); ?>
		</div>
		<div class="feed-activity-icon-col">
				<?php if($user->_security == "Journalist"){ ?>
					<div class="feed-activity-icon-xp" style='background-color:#2196F3;'><i class="mdi-action-subject"></i></div>
				<?php }else if(sizeof($feed) > 0){ ?>
					<div class="feed-activity-icon-xp" style='background-color:#2196F3;'><span style='font-size: 0.8em;font-weight: bold;line-height: 28px;'>XP</span></div>
				<?php }else if(strtotime($feed[0][3]->_date) < strtotime("now -182 days")){ ?> 
					<div class="feed-activity-icon-xp" style='background-color:#2196F3;'><i class="mdi-device-access-time"></i></div>
				<?php }else if($feed[0][0]->_event == "FINISHED"){ ?>
					<div class="feed-activity-icon-xp" style='background-color:#2196F3;'><i class="mdi-action-done"></i></div>
				<?php }else if(sizeof($feed[0][3]->_watchedxp) > 0 && sizeof($feed[0][3]->_playedxp) > 0){ ?>
					<div class="feed-activity-icon-xp" style='background-color:#2196F3;'><i class="mdi-content-create"></i></div>
				<?php }else if(sizeof($feed[0][3]->_playedxp) > 0){ ?> 
					<div class="feed-activity-icon-xp" style='background-color:#2196F3;'><span style='font-size: 0.8em;font-weight: bold;line-height: 28px;'>XP</span></div>
				<?php }else if(sizeof($feed[0][3]->_watchedxp) > 0){ ?>
					<div class="feed-activity-icon-xp" style='background-color:#2196F3;'><span style='font-size: 0.8em;font-weight: bold;line-height: 28px;'>XP</span></div>
				<?php }else{ ?>
					<div class="feed-activity-icon-xp" style='background-color:#2196F3;'><i class="mdi-content-create"></i></div>
				<?php } ?>
		</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
					<?php if(sizeof($feed) > 1){ ?>
						 added <?php echo sizeof($feed); ?> new experiences
					<?php }else if($user->_security == "Journalist"){ ?>
						reviewed
					<?php }else if(sizeof($feed) > 1){ ?>
						 added <?php echo sizeof($feed); ?> new experiences
					<?php }else if(strtotime($feed[0][3]->_date) < strtotime("now -182 days")){ ?>
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
					<span class="feed-activity-when-info"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
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
						$agrees = GetAgreesForXP($xp->_id);
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
	if($user->_security == "Journalist"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
	?>
  <div class="feed-horizontal-card z-depth-1"  data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
    <div class="feed-card-image waves-effect waves-block" style="display:inline-block;background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    </div>
    <div class="feed-card-content">
      <div class="feed-card-icon tier<?php echo $event->_tier; ?>BG">
      	<?php if($user->_security == "Journalist"){ ?>
      		<i class="mdi-editor-format-quote"></i>
      	<?php }else if(sizeof($xp->_playedxp) > 0){ ?>
      		<i class="mdi-hardware-gamepad"></i>
      	<?php }else if(sizeof($xp->_watchedxp) > 0){ ?>
      		<i class="mdi-action-visibility"></i>
      	<?php } ?>
  	  </div>
      <div class="feed-card-title grey-text text-darken-4">
      	<?php if($multiple){ ?>
      		<div class="feed-card-level-game_title feed-activity-game-link" data-gbid="<?php echo $game->_gbid; ?>"><?php echo $game->_title; ?></div>
      	<?php } ?>
      	"<?php echo $event->_quote; ?>"
      </div>
      <div class="feed-action-container">
      		<?php if($xp->_link != ''){ ?>
			<a href='<?php echo $xp->_link; ?>' target='_blank' ><div class="btn-flat waves-effect readBtn">READ</div></a>
			<?php } ?>
			<?php if($_SESSION['logged-in']->_id != $user->_id){ ?>
				<div class="btn-flat waves-effect <?php if(in_array($_SESSION['logged-in']->_id, $agrees) || $_SESSION['logged-in']->_id <= 0){ echo "disagreeBtn"; }else{ echo "agreeBtn"; } ?>" data-expid="<?php echo $xp->_id; ?>" data-agreedwith="<?php echo $user->_id; ?>" data-gameid="<?php echo $xp->_gameid; ?>" data-username="<?php echo $username ?>"><?php if(in_array($_SESSION['logged-in']->_id, $agrees)){ echo "- 1up"; }else if($_SESSION['logged-in']->_id > 0){  echo "+ 1up"; } ?></div>
			<?php } ?>
      </div>
    </div>
  </div>
   <?php if($agreedcount > 0){ ?>
 	<div class="feed-horizontal-card z-depth-1 feed-agree-box" >
 		<span class='feed-agrees-label agreeBtnCount badge-lives'><?php echo $agreedcount; ?></span>
     	<div class="myxp-details-agree-list">
    		<?php
    			$i = 0;
    			while($i < sizeof($agrees) && $i < 15){ ?>
    			<div class="myxp-details-agree-listitem">
    				<?php $useragree = GetUser($agrees[$i]); ?>
    				<div class="user-avatar" style="margin-top:0;width:40px;border-radius:50%;display: inline-block;float:left;margin-left: 0.5em;height:40px;background:url(<?php echo $useragree->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
    				<?php DisplayUserPreviewCard($useragree, $conn, $mutualconn); ?>
    			</div>
    		<?php	
    		$i++;
    		} ?>
    	</div>
 	</div>
 <?php }
}

function FeedConnectionItem($feed, $conn, $mutualconn){
	$user = GetUser($feed[0][0]->_userid);
	//quote will be following user id
	$followinguser = GetUser($feed[0][0]->_quote);
	if($user->_security == "Journalist"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; }
	if($followinguser->_security == "Journalist"){ $followingusername = $followinguser->_first." ".$followinguser->_last; }else{ $followingusername = $followinguser->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_title == "SRANK"){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/SBadge.svg'></img><?php } ?>
    		</div>
			<?php DisplayUserPreviewCard($user, $conn, $mutualconn); ?>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp" style='background-color:#2196F3;'><i class="mdi-social-people"></i></div>
		</div>
		<div class="feed-content-col">
			<div class="feed-activity-title">
				<?php if(sizeof($feed) > 1){ ?>
					<span class='feed-activity-user-link' data-id='<?php echo $user->_id; ?>'><?php echo $username; ?></span> 
					followed <?php echo sizeof($feed); ?> people 
					<span class='feed-activity-when-info'><i class='mdi-action-schedule'></i><?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date); ?></span>
				<?php }else{ ?>
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
					followed
					<span class="feed-activity-game-link" data-userid="<?php echo $followinguser->_id; ?>"><?php echo $followingusername; ?></span>
					<span class="feed-activity-when-info"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
				<?php } ?>
			</div>
			<div class="feed-activity-game-container">
				<?php foreach($feed as $card){ 
					$event = $card[0];
					$game = $card[1];
					//quote will be following user id
					$following = GetUser($event->_quote);
					DisplayUserCard($following, -1, 0, 0);
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


function FeedBookmarkItem($feed, $conn, $mutualconn){
	$user = GetUser($feed[0][0]->_userid);
	if($user->_security == "Journalist"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_title == "SRANK"){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/SBadge.svg'></img><?php } ?>
    		</div>
			<?php DisplayUserPreviewCard($user, $conn, $mutualconn); ?>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp" style='background-color:#2196F3;'><i class="mdi-action-bookmark"></i></div>
		</div>
		<div class="feed-content-col">
			<div class="feed-activity-title">
				<?php if(sizeof($feed) > 1){ ?>
					<span class='feed-activity-user-link' data-id='<?php echo $user->_id; ?>'><?php echo $username; ?></span> 
					bookmarked <?php echo sizeof($feed); ?> games 
					<span class='feed-activity-when-info'><i class='mdi-action-schedule'></i><?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date); ?></span>
				<?php }else{ ?>
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
					bookmarked
					<span class="feed-activity-game-link" data-gbid="<?php echo $feed[0][1]->_gbid; ?>"><?php echo $feed[0][1]->_title; ?></span>
					<span class="feed-activity-when-info"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
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
  <div class="feed-bookmark-card z-depth-1"  data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
    <div class="feed-bookmark-image waves-effect waves-block" style="display:inline-block;background:url(<?php echo $game->_imagesmall; ?>) 50% 50%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    	<i class="mdi-action-bookmark" style='  position: absolute;top: -19px;right: 20px;font-size: 3em;color: red;'></i>
    	<div class="feed-card-level-game_title feed-activity-game-link feed-bookmark-title" data-gbid="<?php echo $game->_gbid; ?>"><?php echo $game->_title; ?></div>
    </div>
  </div>
<?php
}

function FeedTierChangedItem($feed, $conn, $mutualconn){
	$user = GetUser($feed[0][0]->_userid);
	if($user->_security == "Journalist"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_title == "SRANK"){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/SBadge.svg'></img><?php } ?>
    		</div>
			<?php DisplayUserPreviewCard($user, $conn, $mutualconn); ?>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp" style='background-color:#2196F3;'><i class="mdi-action-swap-vert"></i></div>
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
					<span class="feed-activity-when-info"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
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
	if($user->_security == "Journalist"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
	$tierdata = explode(",",$event->_quote);
	$before = $tierdata[0];
	$after = $tierdata[1];
	?>
  <div class="feed-horizontal-card z-depth-1"  data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
    <div class="feed-card-image waves-effect waves-block" style="display:inline-block;background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    </div>
    <div class="feed-card-content">
      <div class="feed-card-title grey-text text-darken-4">
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
	if($user->_security == "Journalist"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
    		<div class="feed-avatar" style="background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    			<?php if($user->_title == "SRANK"){ ?><img class="srank-badge-activity" src='http://lifebar.io/Images/Badges/SBadge.svg'></img><?php } ?>
    		</div>
			<?php DisplayUserPreviewCard($user, $conn, $mutualconn); ?>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp" style='background-color:#2196F3;'><i class="mdi-action-description"></i></div>
		</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<span class="feed-activity-user-link" data-id="<?php echo $user->_id; ?>"><?php echo $username; ?></span>
					<?php if(sizeof($feed) > 1){ ?>
						updated their thoughts for <?php echo sizeof($feed); ?> games
					<?php }else{ ?>
						updated their thoughts for 
					<?php } ?>
					<?php if(sizeof($feed) == 1){ ?>
						<span class="feed-activity-game-link" data-gbid="<?php echo $feed[0][1]->_gbid; ?>"><?php echo $feed[0][1]->_title; ?></span>
					<?php } ?>
					<span class="feed-activity-when-info"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
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
						FeedQuoteChangedCard($game, $user, $event, $xp, $multiple);
				}
				?>
			</div>
		</div>
	</div>
<?php
}

function FeedQuoteChangedCard($game, $user, $event, $xp, $multiple){
	if($user->_security == "Journalist"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
	?>
  <div class="feed-horizontal-card z-depth-1"  data-gameid="<?php echo $game->_id; ?>" data-gbid="<?php echo $game->_gbid; ?>">
    <div class="feed-card-image waves-effect waves-block" style="display:inline-block;background:url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
    </div>
    <div class="feed-card-content">
      <div class="feed-card-title grey-text text-darken-4">
      	<?php if($multiple){ ?>
      		<div class="feed-card-level-game_title feed-activity-game-link" data-gbid="<?php echo $game->_gbid; ?>"><?php echo $game->_title; ?></div>
      	<?php } ?>
      	"<?php echo $event->_quote; ?>"
      </div>
    </div>
  </div>
<?php	
}

function FeedGameReleasesItem($feed){ ?>
	<div class="row" style='margin-bottom: 30px;'>
		<div class="feed-avatar-col">
			<div class="feed-avatar" style='box-shadow:none;'></div>
		</div>
		<div class="feed-activity-icon-col">
			<div class="feed-activity-icon-xp" style='background-color:#2196F3;'><i class="mdi-action-event"></i></div>
		</div>
		<div class="feed-content-col">
				<div class="feed-activity-title">
					<?php if(sizeof($feed) > 1){ ?>
					<?php echo sizeof($feed); ?> new releases today
					<?php }else{ ?>
					<span class="feed-activity-game-link" data-gbid="<?php echo $feed[0][1]->_gbid; ?>"><?php echo $feed[0][1]->_title; ?> was released today</span>
					<?php } ?>
					<span class="feed-activity-when-info"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($feed[0][0]->_date);?></span>
				</div>
			<div class="feed-activity-game-container">
				<?php foreach($feed as $card){ 
					$game = $card[1];
					FeedGameReleaseDetailsCard($game);
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

function DisplayActivitySecondaryContent($userid){ 
	if($userid > 0){ ?>
	<div id="sideContainer" class="col s3" style='padding: 0 1.75rem;'>
		<div class="row activity-secondary-content">
			<div class="col s12">
				<div class="activity-filter-label"><i class="mdi-content-filter-list"></i> Filter Activity Feed</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
			  	    <div id="activity-people-i-follow" class="activity-category-selector activity-category-selected" style='font-size:1.25rem;' data-filter="All"><i class="mdi-social-people left"></i>People I Follow</div>
				</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
					<div id="activity-only-users-i-follow" class="activity-category-selector" style='font-size:1.25rem;' data-filter="Only Users I Follow"><i class="mdi-social-people-outline left"></i>Only Users I Follow</div>
				</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
					<div id="activity-all-users" class="activity-category-selector" style='font-size:1.25rem;' data-filter="All Users"><i class="mdi-social-public left"></i>All Users</div>
				</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
					<div id="activity-only-critic-i-follow" class="activity-category-selector" style='font-size:1.25rem;' data-filter="Only Critics I Follow"><i class="mdi-action-subject left"></i>Only Personalities I Follow</div>
				</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
					<div id="activity-all-critics" class="activity-category-selector" style='font-size:1.25rem;' data-filter="All Critics"><i class="mdi-social-location-city left"></i>All Personalities</div>
				</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
			  	    <div id="activity-mine" class="activity-category-selector" style='font-size:1.25rem;' data-filter="My Activity"><i class="mdi-social-person left"></i> My Activity</div>
				</div>
			</div>
			<!--<div class="col s12">
				<div class="activity-category-box">
					<div id="activity-popular-xp" class="activity-category-selector" style='font-size:1.25rem;' data-filter="Popular XP"><i class="mdi-action-thumb-up left"></i> Popular XP</div>
				</div>
			</div>-->
		</div>
	</div>
<?php
	}else{
	?>
	<div id="sideContainer" class="col s3" style='padding: 0 1.75rem;'>
		<div class="row activity-secondary-content">
			<div class="col s12">
				<div class="activity-filter-label"><i class="mdi-content-filter-list"></i> Filter Activity Feed</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
					<div id="activity-all-critics" class="activity-category-selector activity-category-selected" style='font-size:1.25rem;' data-filter="All"><i class="mdi-social-location-city left"></i>All Critics</div>
				</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
					<div id="activity-all-users" class="activity-category-selector" style='font-size:1.25rem;' data-filter="All Users"><i class="mdi-social-public left"></i>All Users</div>
				</div>
			</div>
			<div class="col s12">
				<div class="activity-category-box">
					<div id="activity-popular-xp" class="activity-category-selector" style='font-size:1.25rem;' data-filter="Popular XP"><i class="mdi-action-thumb-up left"></i> Popular XP</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
}
?>
