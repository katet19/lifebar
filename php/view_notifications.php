<?php
function DisplayMyNotifications($userid){ 
	$notifications = GetUserNotifications($userid);?>
		<div class="col s12 notification-remove-padding" style='margin-top:1.5em;'>
			<?php DisplayNotificationList($notifications); ?>
		</div>
		<?php
}

function DisplayNotificationList($notifications){ 
	$lastvisit = GetLastTimeVisited($_SESSION['logged-in']->_id);
	
	UpdateLastTimeVisited($_SESSION['logged-in']->_id);
 	UpdateTotalNew($_SESSION['logged-in']->_id, 0);
?>
	<div class="row">
		<div class="col s12 notification-remove-padding">
			<?php foreach($notifications as $notification){
					if($notification->_category == "General"){ 
						DisplayGeneralNotification($notification, $lastvisit);
					}else if($notification->_category == "Releases"){
						DisplayReleaseNotification($notification, $lastvisit);
					}else if($notification->_category == "Games"){
						DisplayGameNotification($notification, $lastvisit);
					}else if($notification->_category == "Critics"){
						DisplayCriticNotification($notification, $lastvisit);
					}else if($notification->_category == "Users"){
						DisplayUserNotification($notification, $lastvisit);
					}else if($notification->_category == "Agree"){ 
						DisplayAgreeNotification($notification, $lastvisit);
					}
			} ?>
		</div>
	</div>
<?php }

function DisplayGeneralNotification($details, $lastvisit){ ?>
		<?php if($details->_type == "info" || $details->_type == "link"){ ?>
			<div class="notification-card notification-general">
				<div class="row" style="margin: 10px 0 0;">
					<div class="col s3 m2">
						<div class="notification-card-icon" style="background-color:<?php echo $details->_color; ?>;color:white;font-size:70px;height:100px;border-radius:10px;">
							<i class="<?php echo $details->_icon; ?>" style='line-height: 100px;'> </i>
						</div>
					</div>
					<div class="col s9 m10">
						<div class="notification-card-game-sentence"><?php echo $details->_title; ?></div>
						<div class="notification-card-game-caption"><?php echo $details->_desc; ?></div>
						<div class="notification-card-time"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($details->_date); ?> - #general</div>
						<?php if($details->_date > $lastvisit){ ?><div class="notification-card-new-label">NEW</div><?php } ?>
						<div class="notification-card-actions">
							<div class="notification-card-action btn-flat waves-effect notification-dismiss" data-id="<?php echo $details->_id; ?>">DISMISS</div>
						</div>
					</div>
				</div>
			</div>	
		<?php } ?>
<?php }

function DisplayReleaseNotification($details, $lastvisit){ ?>
		<?php if($details->_type == "info"){ ?>
			<div class="notification-card notification-release">
				<div class="row" style="margin: 10px 0 0;">
					<div class="col s3 m2">
						<div class="notification-card-icon" style="background-color:<?php echo $details->_color; ?>;color:white;font-size:70px;height:100px;border-radius:10px;">
							<i class="<?php echo $details->_icon; ?>" style='line-height: 100px;'> </i>
						</div>
					</div>
					<div class="col s9 m10">
						<div class="notification-card-game-sentence"><?php echo $details->_title; ?></div>
						<div class="notification-card-game-caption"><?php echo $details->_desc; ?></div>
						<div class="notification-card-time"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($details->_date); ?> - #release</div>
						<?php if($details->_date > $lastvisit){ ?><div class="notification-card-new-label">NEW</div><?php } ?>
						<div class="notification-card-actions">
							<div class="notification-card-action btn-flat waves-effect notification-dismiss" data-id="<?php echo $details->_id; ?>">DISMISS</div>
						</div>
					</div>
				</div>
			</div>		
		<?php }else if($details->_type == "linkwithbg"){ 
				if($details->_valtwo == "Release"){
					$today = new DateTime(date("Y-m-d"));
					$release = new DateTime($details->_actiontwo);
					$interval = $today->diff($release);
					$diff = $interval->format("%r%a");
					if($diff > 365){
						$details->_title = str_replace("soon", " in ".$interval->y." year(s) ".$interval->m." month(s) and ".$interval->d." day(s)", $details->_title);
					}else if($diff > 31){
						$details->_title = str_replace("soon", " in ".$interval->m." month(s) and ".$interval->d." day(s)", $details->_title);
					}else if($diff == 0){
						$details->_title = str_replace("releasing soon", " out today!", $details->_title);
					}else if($diff < 0 && $diff > -7){
						$details->_title = str_replace("is releasing soon", " was released this week", $quest->_title);
					}else if($diff <= -7){
						$details->_title = str_replace("is releasing soon", " was released on ".ConvertDateToRelationalEnglish($details->_actiontwo), $details->_title);
					}else{
						$details->_title = str_replace("soon", " in ".$interval->days." day(s)", $details->_title);	
					}
				}
		?>
			<div class="notification-card notification-release">
				<div class="row" style="margin: 10px 0 0;">
					<div class="col s3 m2">
						<div class="notification-card-icon-image" style="100%;background:url(<?php if($details->_icon == ""){ echo "http://lifebar.io/Images/Generic/POLY08s.jpg"; }else{ echo $details->_icon; } ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
					</div>
					<div class="col s9 m10">
						<div class="notification-card-game-sentence"><?php echo $details->_title; ?></div>
						<div class="notification-card-game-caption"><?php echo $details->_desc; ?></div>
						<div class="notification-card-time"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($details->_date); ?> - #release</div>
						<?php if($details->_date > $lastvisit){ ?><div class="notification-card-new-label">NEW</div><?php } ?>
						<div class="notification-card-actions">
							<div class="notification-card-action btn-flat waves-effect notification-viewgame" data-id="<?php echo $details->_coreid; ?>" >VIEW GAME</div>
							<div class="notification-card-action btn-flat waves-effect notification-dismiss" data-id="<?php echo $details->_id; ?>">DISMISS</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
<?php } 

function DisplayGameNotification($details, $lastvisit){ 
	if($details->_type == "gamewithbg"){
	?>
	<div class="notification-card notification-game">
		<div class="row" style="margin: 10px 0 0;">
			<div class="col s3 m2">
				<div class="notification-card-icon-image" style="width:100%;background:url(<?php if($details->_icon == ""){ echo "http://lifebar.io/Images/Generic/POLY08s.jpg"; }else{ echo $details->_icon; } ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
			</div>
			<div class="col s9 m10">
				<div class="notification-card-game-sentence"><?php echo $details->_title; ?></div>
				<div class="notification-card-game-caption"><?php echo $details->_desc; ?></div>
				<div class="notification-card-time"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($details->_date); ?> - #suggestion</div>
				<?php if($details->_date > $lastvisit){ ?><div class="notification-card-new-label">NEW</div><?php } ?>
				<div class="notification-card-actions">
					<div class="notification-card-action btn-flat waves-effect notification-viewgame" data-id="<?php echo $details->_coreid; ?>" >VIEW GAME</div>
					<div class="notification-card-action btn-flat waves-effect notification-dismiss" data-id="<?php echo $details->_id; ?>" >DISMISS</div>
				</div>
			</div>
		</div>
	</div>
	<?php }else if($details->_type == "info"){ ?>
		<div class="notification-card notification-game">
			<div class="row" style="margin: 10px 0 0;">
				<div class="col s3 m2">
					<div class="notification-card-icon" style="background-color:<?php echo $details->_color; ?>;color:white;font-size:70px;height:100px;border-radius:10px;">
						<i class="<?php echo $details->_icon; ?>" style='line-height: 100px;'> </i>
					</div>
				</div>
				<div class="col s9 m10">
					<div class="notification-card-game-sentence"><?php echo $details->_title; ?></div>
					<div class="notification-card-game-caption"><?php echo $details->_desc; ?></div>
					<div class="notification-card-time"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($details->_date); ?> - #suggestion</div>
					<?php if($details->_date > $lastvisit){ ?><div class="notification-card-new-label">NEW</div><?php } ?>
					<div class="notification-card-actions">
						<div class="notification-card-action btn-flat waves-effect notification-dismiss" data-id="<?php echo $details->_id; ?>">DISMISS</div>
					</div>
				</div>
			</div>
		</div>	
	<?php } ?>
<?php }

function DisplayCriticNotification($details, $lastvisit){ ?>
	<?php if($details->_type == "info" || $details->_type == "link"){ ?>
		<div class="notification-card notification-critic">
			<div class="row" style="margin: 10px 0 0;">
				<div class="col s3 m2">
					<div class="notification-card-icon" style="background-color:<?php echo $details->_color; ?>;color:white;font-size:70px;height:100px;border-radius:10px;">
						<i class="<?php echo $details->_icon; ?>" style='line-height: 100px;'> </i>
					</div>
				</div>
				<div class="col s9 m10">
					<div class="notification-card-game-sentence"><?php echo $details->_title; ?></div>
					<div class="notification-card-game-caption"><?php echo $details->_desc; ?></div>
					<div class="notification-card-time"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($details->_date); ?> - #review</div>
					<?php if($details->_date > $lastvisit){ ?><div class="notification-card-new-label">NEW</div><?php } ?>
					<div class="notification-card-actions">
						<div class="notification-card-action btn-flat waves-effect notification-dismiss" data-id="<?php echo $details->_id; ?>">DISMISS</div>
					</div>
				</div>
			</div>
		</div>	
	<?php }else if($details->_type == "criticlink"){
		$user = GetUser($details->_coreid);
		$conn = GetConnectedToList($_SESSION['logged-in']->_id);
		$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
	?>
		<div class="notification-card notification-critic">
			<div class="row" style="margin: 10px 0 0;">
				<div class="col s3 m2">
					<div class="user-avatar" data-id="<?php echo $user->_id; ?>" style="width:90px;border-radius:50%;margin-left: auto;margin-right: auto;height:90px;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
				</div>
				<div class="col s9 m10">
					<div class="notification-card-game-sentence"><?php echo $details->_title; ?></div>
					<div class="notification-card-game-caption"><?php echo $details->_desc; ?></div>
					<div class="notification-card-time"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($details->_date); ?> - #review</div>
					<?php if($details->_date > $lastvisit){ ?><div class="notification-card-new-label">NEW</div><?php } ?>
					<div class="notification-card-actions">
						<div class="notification-card-action btn-flat waves-effect notification-dismiss" data-id="<?php echo $details->_id; ?>">DISMISS</div>
					</div>
				</div>
			</div>
		</div>
	<?php }else if($details->_type == "criticreviews"){ ?>
		<div class="notification-card notification-critic">
			<div class="row" style="margin: 10px 0 0;">
			<div class="col s3 m2">
				<div class="notification-card-icon-image" style="width:100%;background:url(<?php if($details->_icon == ""){ echo "http://lifebar.io/Images/Generic/POLY08s.jpg"; }else{ echo $details->_icon; } ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
			</div>
				<div class="col s9 m10">
					<div class="notification-card-game-sentence"><?php echo $details->_title; ?></div>
					<div class="notification-card-game-caption"><?php echo $details->_desc; ?></div>
					<div class="notification-card-time"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($details->_date); ?> - #review</div>
					<?php if($details->_date > $lastvisit){ ?><div class="notification-card-new-label">NEW</div><?php } ?>
					<div class="notification-card-actions">
						<div class="notification-card-action btn-flat waves-effect notification-viewgame" data-id="<?php echo $details->_coreid; ?>" >VIEW REVIEWS</div>
						<div class="notification-card-action btn-flat waves-effect notification-dismiss" data-id="<?php echo $details->_id; ?>">DISMISS</div>
					</div>
				</div>
			</div>
		</div>	
	<?php } ?>
<?php }

function DisplayUserNotification($details, $lastvisit){ ?>
	<?php if($details->_type == "info"){ ?>
			<div class="notification-card notification-user">
				<div class="row" style="margin: 10px 0 0;">
					<div class="col s3 m2">
						<div class="notification-card-icon" style="background-color:<?php echo $details->_color; ?>;color:white;font-size:70px;height:100px;border-radius:10px;">
							<i class="<?php echo $details->_icon; ?>" style='line-height: 100px;'> </i>
						</div>
					</div>
					<div class="col s9 m10">
						<div class="notification-card-game-sentence"><?php echo $details->_title; ?></div>
						<div class="notification-card-game-caption"><?php echo $details->_desc; ?></div>
						<div class="notification-card-time"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($details->_date); ?> - #connections</div>
						<?php if($details->_date > $lastvisit){ ?><div class="notification-card-new-label">NEW</div><?php } ?>
						<div class="notification-card-actions">
							<div class="notification-card-action btn-flat waves-effect notification-dismiss" data-id="<?php echo $details->_id; ?>">DISMISS</div>
						</div>
					</div>
				</div>
			</div>	
	<?php }else if($details->_type == "link"){ 
		$user = GetUser($details->_coreid); 
        $conn = GetConnectedToList($_SESSION['logged-in']->_id);
		$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
        ?>
		<div class="notification-card notification-user">
			<div class="row" style="margin: 10px 0 0;">
				<div class="col s3 m2">
					<div class="user-avatar" data-id="<?php echo $user->_id; ?>" style="width:90px;border-radius:50%;margin-left: auto;margin-right: auto;height:90px;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
				</div>
				<div class="col s9 m10">
					<div class="notification-card-game-sentence"><?php echo $details->_title; ?></div>
					<div class="notification-card-game-caption"><?php echo $details->_desc; ?></div>
					<div class="notification-card-time"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($details->_date); ?> - #connections</div>
					<?php if($details->_date > $lastvisit){ ?><div class="notification-card-new-label">NEW</div><?php } ?>
					<div class="notification-card-actions">
						<div class="notification-card-action btn-flat waves-effect notification-dismiss" data-id="<?php echo $details->_id; ?>">DISMISS</div>
					</div>
				</div>
			</div>
		</div>	
	<?php }else if($details->_type == "criticlink"){
		$user = GetUser($details->_coreid);
		$conn = GetConnectedToList($_SESSION['logged-in']->_id);
		$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
	?>
		<div class="notification-card notification-user">
			<div class="row" style="margin: 10px 0 0;">
				<div class="col s3 m2">
					<div class="user-avatar" data-id="<?php echo $user->_id; ?>" style="width:90px;border-radius:50%;margin-left: auto;margin-right: auto;height:90px;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
				</div>
				<div class="col s9 m10">
					<div class="notification-card-game-sentence"><?php echo $details->_title; ?></div>
					<div class="notification-card-game-caption"><?php echo $details->_desc; ?></div>
					<div class="notification-card-time"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($details->_date); ?> - #connections</div>
					<?php if($details->_date > $lastvisit){ ?><div class="notification-card-new-label">NEW</div><?php } ?>
					<div class="notification-card-actions">
						<div class="notification-card-action btn-flat waves-effect notification-dismiss" data-id="<?php echo $details->_id; ?>">DISMISS</div>
					</div>
				</div>
			</div>
		</div>
<?php }
}

function DisplayAgreeNotification($details, $lastvisit){ ?>
	<?php if($details->_type == "info"){ ?>
		<div class="notification-card notification-agree">
			<div class="row" style="margin: 10px 0 0;">
				<div class="col s3 m2">
					<div class="notification-card-icon" style="background-color:<?php echo $details->_color; ?>;color:white;font-size:70px;height:100px;border-radius:10px;">
						<i class="<?php echo $details->_icon; ?>" style='line-height: 100px;'> </i>
					</div>
				</div>
				<div class="col s9 m10">
					<div class="notification-card-game-sentence"><?php echo $details->_title; ?></div>
					<div class="notification-card-game-caption"><?php echo $details->_desc; ?></div>
					<div class="notification-card-time"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($details->_date); ?> - #1ups</div>
					<?php if($details->_date > $lastvisit){ ?><div class="notification-card-new-label">NEW</div><?php } ?>
					<div class="notification-card-actions">
						<div class="notification-card-action btn-flat waves-effect notification-dismiss" data-id="<?php echo $details->_id; ?>">DISMISS</div>
					</div>
				</div>
			</div>
		</div>	
	<?php }else if($details->_type == "agree"){ ?>
		<div class="notification-card notification-agree">
			<div class="row" style="margin: 10px 0 0;">
				<div class="col s3 m2">
					<div class="notification-card-icon-image" style="width:100%;background:url(<?php if($details->_icon == ""){ echo "http://lifebar.io/Images/Generic/POLY08s.jpg"; }else{ echo $details->_icon; } ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
				</div>
				<div class="col s9 m10">
					<div class="notification-card-game-sentence"><?php echo $details->_title; ?></div>
					<div class="notification-card-game-caption"><?php echo $details->_desc; ?></div>
					<div class="notification-card-time"><i class="mdi-action-schedule"></i> <?php echo ConvertTimeStampToRelativeTime($details->_date); ?> - #1ups</div>
					<?php if($details->_date > $lastvisit){ ?><div class="notification-card-new-label">NEW</div><?php } ?>
					<div class="notification-card-actions">
						<div class="notification-card-action btn-flat waves-effect notification-viewgame" data-id="<?php echo $details->_valtwo; ?>" >VIEW GAME</div>
						<div class="notification-card-action btn-flat waves-effect notification-dismiss" data-id="<?php echo $details->_id; ?>">DISMISS</div>
					</div>
				</div>
			</div>
		</div>	
	<?php } ?>
<?php } ?>
