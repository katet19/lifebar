<?php
function DisplayMyNotifications($userid){ 
	$notifications = GetUserNotifications($userid);?>
		<div class="col s12 notification-remove-padding" style='margin-top:1.5em;'>
			<?php DisplayNotificationList($notifications); ?>
		</div>
		<?php DisplayNotificationSecondaryContent();
}
?>

<?php function DisplayNotificationSecondaryContent(){
	$notifications = GetUserNotifications($_SESSION['logged-in']->_id);
	DisplayNotificationCategories($notifications);
} ?>

<?php function DisplayNotificationCategories($notifications){ 
	$lastvisit = GetLastTimeVisited($_SESSION['logged-in']->_id);
	
	foreach($notifications as $notification){
		if($notification->_category == "General"){ $general++; $all++; if($notification->_date > $lastvisit){ $newgeneral++; $newall++; } }
		if($notification->_category == "Releases"){ $releases++; $all++; if($notification->_date > $lastvisit){ $newreleases++; $newall++; } } 
		if($notification->_category == "Games"){ $games++; $all++; if($notification->_date > $lastvisit){ $newgames++; $newall++; } } 
		if($notification->_category == "Critics"){ $critics++; $all++; if($notification->_date > $lastvisit){ $newcritics++; $newall++; } } 
		if($notification->_category == "Users"){ $users++; $all++; if($notification->_date > $lastvisit){ $newusers++; $newall++; } } 
		if($notification->_category == "Agree"){ $agree++; $all++; if($notification->_date > $lastvisit){ $newagree++; $newall++; } } 
	}
?>
	<div id="sideContainer" class="col s3" style='padding: 0 1.75rem;'>
		<div class="row notification-secondary-content">
			<div class="col s12">
				<div class="notification-filter-label"><i class="mdi-content-filter-list"></i> Filter Notifications</div>
			</div>
			<div class="col s12">
				<div class="notification-category-box">
			  	    <div id="notification-all" class="notification-category-selector notification-category-selected" style='font-size:1.25rem;'><i class="mdi-social-notifications left"></i> Show All</div>
				    <div class="notification-category-total"><?php echo $all; ?></div>
				</div>
				<div class="notification-category-box">
			  	    <div id="notification-general" class="notification-category-selector" style='font-size:1.25rem;'><i class="mdi-action-settings left"></i> General</div>
				    <div class="notification-category-total"> <?php echo $general; ?></div><?php if($newgeneral > 0){ ?><div class="notification-category-new">NEW</div><?php } ?>
				</div>
			</div>
			<div class="col s12">
				<div class="notification-category-box">
					<div id="notification-agree" class="notification-category-selector" style='font-size:1.25rem;'><i class="mdi-action-favorite left"></i> 1up's</div>
					<div class="notification-category-total"><?php echo $agree; ?></div><?php if($newagree > 0){ ?><div class="notification-category-new">NEW</div><?php } ?>
				</div>
			</div>
			<div class="col s12">
				<div class="notification-category-box">
					<div id="notification-user" class="notification-category-selector" style='font-size:1.25rem;'><i class="mdi-social-people left"></i> Followers</div>
					<div class="notification-category-total"><?php echo $users; ?></div><?php if($newusers > 0){ ?><div class="notification-category-new">NEW</div><?php } ?>
				</div>
			</div>
			<div class="col s12">
				<div class="notification-category-box">
					<div id="notification-critic" class="notification-category-selector" style='font-size:1.25rem;'><i class="mdi-action-subject left"></i> Reviews</div>
					<div class="notification-category-total"><?php echo $critics; ?></div><?php if($newcritics > 0){ ?><div class="notification-category-new">NEW</div><?php } ?>
				</div>
			</div>
			<div class="col s12">
				<div class="notification-category-box">
					<div id="notification-game" class="notification-category-selector" style='font-size:1.25rem;'><i class="mdi-hardware-gamepad left"></i> Suggestions</div>
					<div class="notification-category-total"><?php echo $games; ?></div><?php if($newgames > 0){ ?><div class="notification-category-new">NEW</div><?php } ?>
				</div>
			</div>
			<div class="col s12">
				<div class="notification-category-box">
					<div id="notification-release" class="notification-category-selector" style='font-size:1.25rem;'><i class="mdi-editor-insert-invitation left"></i> Releases</div>
					<div class="notification-category-total"><?php echo $releases; ?></div><?php if($newreleases > 0){ ?><div class="notification-category-new">NEW</div><?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<?php function DisplayNotificationList($notifications){ 
	$lastvisit = GetLastTimeVisited($_SESSION['logged-in']->_id);
	
	UpdateLastTimeVisited($_SESSION['logged-in']->_id);
 	UpdateTotalNew($_SESSION['logged-in']->_id, 0);
?>
	<div class="row">
		<div class="col s12 notification-remove-padding">
			<div class="notification-header-box">
				<div class="notification-header-icon"><i class="notification-header-icon-picker mdi-social-notifications"></i></div>
				<div class="notification-header">
					<div class="notification-header-nav-btn"><span>All</span>
					  <ul id='notification-header-nav' class='dropdown-content'>
						<li><a href="#!" class="notification-all notificiation-filter-selected" style='color:rgba(0,0,0,0.8);' data-icon="mdi-social-notifications">All</a></li>
						<li><a href="#!" class="notification-general" style='color:rgba(0,0,0,0.8);' data-icon="mdi-action-settings">General</a></li>
						<li><a href="#!" class="notification-agree" style='color:rgba(0,0,0,0.8);' data-icon="mdi-action-favorite">1ups</a></li>
						<li><a href="#!" class="notification-user" style='color:rgba(0,0,0,0.8);' data-icon="mdi-social-people">Followers</a></li>
						<li><a href="#!" class="notification-critic" style='color:rgba(0,0,0,0.8);' data-icon="mdi-action-subject">Reviews</a></li>
						<li><a href="#!" class="notification-game" style='color:rgba(0,0,0,0.8);' data-icon="mdi-hardware-gamepad">Suggestions</a></li>
						<li><a href="#!" class="notification-release" style='color:rgba(0,0,0,0.8);' data-icon="mdi-editor-insert-invitation">Releases</a></li>
					  </ul>
					</div>
				</div>
				<div class="notification-header-icon notification-header-nav-btn" style='  margin: 0;  font-size: 1.25em;  line-height: 34px;'><i class="mdi-navigation-expand-more"></i></div>
			</div>
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
<?php } ?>

<?php function DisplayGeneralNotification($details, $lastvisit){ ?>
		<?php if($details->_type == "info" || $details->_type == "link"){ ?>
			<div class="notification-card notification-general">
				<div class="row" style="margin: 10px 0 0;">
					<div class="col s3 m2">
						<div class="notification-card-icon" style="color:<?php echo $details->_color; ?>;">
							<i class="<?php echo $details->_icon; ?>" style='line-height: 90px;'> </i>
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
<?php } ?>

<?php function DisplayReleaseNotification($details, $lastvisit){ ?>
		<?php if($details->_type == "info"){ ?>
			<div class="notification-card notification-release">
				<div class="row" style="margin: 10px 0 0;">
					<div class="col s3 m2">
						<div class="notification-card-icon" style="color:<?php echo $details->_color; ?>;">
							<i class="<?php echo $details->_icon; ?>" style='line-height: 90px;'> </i>
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
						<div class="notification-card-icon-image" style="100%;background:url(<?php if($details->_icon == ""){ echo "http://polygonalweave.com/Images/Generic/POLY08s.jpg"; }else{ echo $details->_icon; } ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
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
<?php } ?>

<?php function DisplayGameNotification($details, $lastvisit){ 
	if($details->_type == "gamewithbg"){
	?>
	<div class="notification-card notification-game">
		<div class="row" style="margin: 10px 0 0;">
			<div class="col s3 m2">
				<div class="notification-card-icon-image" style="width:100%;background:url(<?php if($details->_icon == ""){ echo "http://polygonalweave.com/Images/Generic/POLY08s.jpg"; }else{ echo $details->_icon; } ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
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
					<div class="notification-card-icon" style="color:<?php echo $details->_color; ?>;">
						<i class="<?php echo $details->_icon; ?>" style='line-height: 90px;'> </i>
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
<?php } ?>

<?php function DisplayCriticNotification($details, $lastvisit){ ?>
	<?php if($details->_type == "info" || $details->_type == "link"){ ?>
		<div class="notification-card notification-critic">
			<div class="row" style="margin: 10px 0 0;">
				<div class="col s3 m2">
					<div class="notification-card-icon" style="color:<?php echo $details->_color; ?>;">
						<i class="<?php echo $details->_icon; ?>" style='line-height: 90px;'> </i>
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
		$user = GetUser($details->_coreid); ?>
		<div class="notification-card notification-critic">
			<div class="row" style="margin: 10px 0 0;">
				<div class="col s3 m2">
					<div class="user-avatar" style="width:90px;border-radius:50%;margin-left: auto;margin-right: auto;height:90px;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
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
				<div class="notification-card-icon-image" style="width:100%;background:url(<?php if($details->_icon == ""){ echo "http://polygonalweave.com/Images/Generic/POLY08s.jpg"; }else{ echo $details->_icon; } ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
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
<?php } ?>

<?php function DisplayUserNotification($details, $lastvisit){ ?>
	<?php if($details->_type == "info"){ ?>
		<div class="notification-card notification-user">
			<div class="row" style="margin: 10px 0 0;">
				<div class="col s3 m2">
					<div class="notification-card-icon" style="color:<?php echo $details->_color; ?>;">
						<i class="<?php echo $details->_icon; ?>" style='line-height: 90px;'> </i>
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
	<?php }else if($details->_type == "link"){ 
		$user = GetUser($details->_coreid); ?>
		<div class="notification-card notification-user">
			<div class="row" style="margin: 10px 0 0;">
				<div class="col s3 m2">
					<div class="user-avatar" style="width:90px;border-radius:50%;margin-left: auto;margin-right: auto;height:90px;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
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
	<?php } ?>
<?php } ?>

<?php function DisplayAgreeNotification($details, $lastvisit){ ?>
	<?php if($details->_type == "info"){ ?>
		<div class="notification-card notification-agree">
			<div class="row" style="margin: 10px 0 0;">
				<div class="col s3 m2">
					<div class="notification-card-icon" style="color:<?php echo $details->_color; ?>;">
						<i class="<?php echo $details->_icon; ?>" style='line-height: 90px;'> </i>
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
					<div class="notification-card-icon-image" style="width:100%;background:url(<?php if($details->_icon == ""){ echo "http://polygonalweave.com/Images/Generic/POLY08s.jpg"; }else{ echo $details->_icon; } ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
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