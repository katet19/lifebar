<?php
function DisplayUserCard($user, $count, $classId, $myConnections){ 
	$conn = GetConnectedToList($_SESSION['logged-in']->_id);
	$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
?>
   <div class="col <?php if($count == -1){ echo "s6 m5 l4"; }else{ echo "s6 m3 l2"; } ?>" >
      <div class="card user-discover-card <?php echo $classId; ?>" data-count="<?php echo $count; ?>" data-id="<?php echo $user->_id; ?>" >
        <div class="card-image waves-effect waves-block">
        	<div class="col s12 valign-wrapper">
        		<div class="user-avatar" style="width:90px;border-radius:50%;margin-left: auto;margin-right: auto;margin-top:15px;height:90px;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
        			<?php if($user->_badge != ""){ ?><img class="srank-badge" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
        		</div>
        		<?php DisplayUserPreviewCard($user, $conn, $mutualconn); ?>
        	</div>
        </div>
        <div class="card-content">
	      	<?php if($user->_security == "Authenticated"){ ?> 
	      		<div class='authenticated-mark mdi-action-done ' title="Verified Account" style='float:right;'></div>
	  		<?php } ?>
        	<?php if($user->_security == "Journalist" || $user->_security == "Authenticated"){ ?>
          	<span class="card-title activator grey-text text-darken-4"><?php echo $user->_first." ".$user->_last; ?><span class="subNameInfo"><?php echo $user->_title ?></span></span>
        	<?php }else{ ?>
        	<span class="card-title activator grey-text text-darken-4"><?php echo $user->_username; ?><span class="subNameInfo"><?php if($_SESSION['logged-in']->_realnames == "True" && in_array($user->_id, $mutualconn)){ echo $user->_first." ".$user->_last; } ?></span></span>
        	<?php } ?>
        </div>
      </div>
  </div>
<?php }

function DisplayCriticQuoteCard($exp){ 
	$conn = GetConnectedToList($_SESSION['logged-in']->_id);
	$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
	$user = $exp->_username;
	$agrees = GetAgreesForXP($exp->_id);
	$agreedcount = array_shift($agrees);
	
	$hiddenusername = '';
	if($user->_security == "Journalist" || $user->_security == "Authenticated")
		 $hiddenusername = $user->_first." ".$user->_last;
	else
		$hiddenusername = $user->_username;
?>
   <div class="col s12 critic-container">
   		<div class="critic-name-container col s12 m12 l4" data-id="<?php echo $user->_id; ?>">
   			<?php DisplayUserPreviewCard($user, $conn, $mutualconn); ?>
			<div class="user-avatar" style="width:45px;border-radius:50%;display: inline-block;float:left;margin-left: 0.5em;margin-top:15px;height:45px;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
	        <?php if($user->_badge != ""){ ?><img class="srank-badge-review" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
	        <div class="user-name">
	        	<?php if($user->_security == "Journalist" || $user->_security == 'Authenticated'){ ?>
	          	<span class="card-title activator grey-text text-darken-4" style="font-weight:bold;"><?php echo $user->_first." ".$user->_last; ?><span class="subNameInfo"><?php echo $user->_title; ?></span></span>
	        	<?php }else{ ?>
	        	<span class="card-title activator grey-text text-darken-4" style="font-weight:bold;"><?php echo $user->_username; ?><span class="subNameInfo"><?php if($_SESSION['logged-in']->_realnames == "True" && in_array($user->_id, $mutualconn)){ echo $user->_first." ".$user->_last; } ?></span></span>
	        	<?php } ?>
	        </div>
        	<?php if($user->_security == "Journalist"){ 
        			$percent = 100; ?>
	          		<div class="game-community-tier-position tier<?php echo $exp->_tier; ?>BG z-depth-1">
	      			  	<div class="c100 mini <?php if($exp->_tier == 1){ echo "tierone"; }else if($exp->_tier == 2){ echo "tiertwo"; }else if($exp->_tier == 3){ echo "tierthree"; }else if($exp->_tier == 4){ echo "tierfour"; }else if($exp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$exp->_tier." - ".$percent."% finished"; ?>" style='background-color:white;'>
					  	  <span class='tierTextColor<?php echo $exp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-editor-format-quote"></i></span>
						  <div class="slice">
						    <div class="bar minibar"></div>
						    <div class="fill"></div>
						  </div>
						</div>
					</div>
			<?php }else if(sizeof($exp->_playedxp) > 0){ 
		  	  	if($exp->_playedxp[0]->_completed == "101")
					$percent = 100;
				else
					$percent = $exp->_playedxp[0]->_completed;
					
				if($percent == 100){ ?>
  	  	       		<div class="game-community-tier-position tier<?php echo $exp->_tier; ?>BG z-depth-1">
			          	<div class="card-game-tier" style='margin-left:0px;' title="<?php echo "Tier ".$exp->_tier." - Completed"; ?>">
		    				<i class="mdi-hardware-gamepad"></i>
			          	</div>
		          	</div>
	          	<?php }else{ ?>
	          		<div class="game-community-tier-position tier<?php echo $exp->_tier; ?>BG z-depth-1">
	      			  	<div class="c100 mini <?php if($exp->_tier == 1){ echo "tierone"; }else if($exp->_tier == 2){ echo "tiertwo"; }else if($exp->_tier == 3){ echo "tierthree"; }else if($exp->_tier == 4){ echo "tierfour"; }else if($exp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$exp->_tier." - ".$percent."% finished"; ?>" style='background-color:white;'>
					  	  <span class='tierTextColor<?php echo $exp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-hardware-gamepad"></i></span>
						  <div class="slice">
						    <div class="bar minibar"></div>
						    <div class="fill"></div>
						  </div>
						</div>
					</div>
	          	<?php } ?>
          	<?php }else if(sizeof($exp->_watchedxp > 0)){
	  			$percent = 20;
	  	  		$length = "";
	    		foreach($exp->_watchedxp as $watched){
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
	    		
	    		if($percent == 101){
	    		?>
		          <div class="game-community-tier-position tier<?php echo $exp->_tier; ?>BG z-depth-1">
		          	<div class="card-game-tier" style='margin-left:0px;' title="<?php echo "Tier ".$exp->_tier." - ".$length; ?>">
		          			<i class="mdi-action-visibility"></i>
		          	</div>
				   </div>
  	  			<?php }else{ ?>
		      		<div class="game-community-tier-position tier<?php echo $exp->_tier; ?>BG z-depth-1">
		  			  	<div class="c100 mini <?php if($exp->_tier == 1){ echo "tierone"; }else if($exp->_tier == 2){ echo "tiertwo"; }else if($exp->_tier == 3){ echo "tierthree"; }else if($exp->_tier == 4){ echo "tierfour"; }else if($exp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$exp->_tier." - ".$length; ?>" style='background-color:white;'>
					  	  <span class='tierTextColor<?php echo $exp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-action-visibility"></i></span>
						  <div class="slice">
						    <div class="bar minibar"></div>
						    <div class="fill"></div>
						  </div>
						</div>
		   			</div>
	          	<?php } 
          	}?>
		</div>
        <div class="critic-quote-container col s12 m12 l8">
			<div class="critic-quote">
				<div class="critic-quote-icon"><i class="mdi-editor-format-quote"></i></div>
				<span class='agreeBtnCount badge-lives' <?php if($agreedcount > 0){ echo "style='display:inline-block'"; } ?> ><?php if($agreedcount > 0){ echo $agreedcount;  } ?></span>
				<?php echo $exp->_quote;?>
			</div>
		</div>
		<div class="critic-action-container col s12">
			<?php if($user->_security == 'Authenticated'){ ?>
				<div class="btn-flat waves-effect detailsBtn" data-uid="<?php echo $exp->_game->_id."-".$user->_id; ?>">
					<?php $watched = false; $played = false; unset($details);
						foreach($exp->_playedxp as $playedxp){
							$played = true;
							$details[] = "<i class='mdi-hardware-gamepad left tierTextColor".$exp->_tier."'></i> ".BuildPlayedSentence($playedxp);
						}
						foreach($exp->_watchedxp as $watchedxp){
							$watched = true;
							$details[] = "<i class='mdi-action-visibility left tierTextColor".$exp->_tier."'></i> ".BuildWatchedSentence($watchedxp);
						}
					?>
					<?php BuildDetailsPopUp($exp, $details, $conn); ?>
					<?php if($watched){?>
						<i class="mdi-action-visibility left tierTextColor<?php echo $exp->_tier; ?>"></i>
					<?php } ?>
					<?php if($played){?>
						<i class="mdi-hardware-gamepad left tierTextColor<?php echo $exp->_tier; ?>"></i>
					<?php } ?>
					DETAILS
				</div>
			<?php } ?>
			<?php if($exp->_link != ''){ ?>
				<a href='<?php echo $exp->_link; ?>' target='_blank' ><div class="btn-flat waves-effect readBtn">READ</div></a>
			<?php } ?>
			<?php if($_SESSION['logged-in']->_id != $user->_id){ ?>
				<div class="btn-flat waves-effect <?php if(in_array($user->_id, $agrees) || $_SESSION['logged-in']->_id <= 0){ echo "disagreeBtn"; }else{ echo "agreeBtn"; } ?>" data-expid="<?php echo $exp->_id; ?>" data-agreedwith="<?php echo $user->_id; ?>" data-gameid="<?php echo $exp->_gameid; ?>" data-username="<?php echo $hiddenusername ?>"><?php if(in_array($user->_id, $agrees)){ echo "- 1up"; }else if($_SESSION['logged-in']->_id > 0){  echo "+ 1up"; } ?></div>
			<?php } ?>
		</div>
  	</div>
<?php }

function DisplayUserQuoteCard($exp){
	$conn = GetConnectedToList($_SESSION['logged-in']->_id);
	$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
	$user = $exp->_username;
	$agrees = GetAgreesForXP($exp->_id);
	$agreedcount = array_shift($agrees);
	
	$hiddenusername = '';
	if($user->_security == "Journalist")
		 $hiddenusername = $user->_first." ".$user->_last;
	else
		$hiddenusername = $user->_username;
?>
   <div class="col s12 user-container">
   		<div class="critic-name-container col s12 m12 l4" data-id="<?php echo $user->_id; ?>">
   			<?php DisplayUserPreviewCard($user, $conn, $mutualconn); ?>
			<div class="user-avatar" style="width:45px;border-radius:50%;display: inline-block;float:left;margin-left: 0.5em;margin-top:15px;height:45px;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
				<?php if($user->_badge != ""){ ?><img class="srank-badge-review" src='http://lifebar.io/Images/Badges/<?php echo $user->_badge; ?>'></img><?php } ?>
			</div>
	        <div class="user-name">
	        	<?php if($user->_security == "Journalist"){ ?>
	          	<span class="card-title activator grey-text text-darken-4" style="font-weight:bold;"><?php echo $user->_first." ".$user->_last; ?><span class="subNameInfo"><?php echo $user->_title; ?></span></span>
	        	<?php }else{ ?>
	        	<span class="card-title activator grey-text text-darken-4" style="font-weight:bold;"><?php echo $user->_username; ?><span class="subNameInfo"><?php if($_SESSION['logged-in']->_realnames == "True" && in_array($user->_id, $mutualconn)){ echo $user->_first." ".$user->_last; } ?></span></span>
	        	<?php } ?>
	        </div>
        	<?php if(sizeof($exp->_playedxp) > 0){ 
		  	  	if($exp->_playedxp[0]->_completed == "101")
					$percent = 100;
				else
					$percent = $exp->_playedxp[0]->_completed;
					
				if($percent == 100){ ?>
  	  	       		<div class="game-community-tier-position tier<?php echo $exp->_tier; ?>BG z-depth-1">
			          	<div class="card-game-tier" style='margin-left:0px;' title="<?php echo "Tier ".$exp->_tier." - Completed"; ?>">
		    				<i class="mdi-hardware-gamepad"></i>
			          	</div>
		          	</div>
	          	<?php }else{ ?>
	          		<div class="game-community-tier-position tier<?php echo $exp->_tier; ?>BG z-depth-1">
	      			  	<div class="c100 mini <?php if($exp->_tier == 1){ echo "tierone"; }else if($exp->_tier == 2){ echo "tiertwo"; }else if($exp->_tier == 3){ echo "tierthree"; }else if($exp->_tier == 4){ echo "tierfour"; }else if($exp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$exp->_tier." - ".$percent."% finished"; ?>" style='background-color:white;'>
					  	  <span class='tierTextColor<?php echo $exp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-hardware-gamepad"></i></span>
						  <div class="slice">
						    <div class="bar minibar"></div>
						    <div class="fill"></div>
						  </div>
						</div>
					</div>
	          	<?php } ?>
          	<?php }else if(sizeof($exp->_watchedxp > 0)){
	  			$percent = 20;
	  	  		$length = "";
	    		foreach($exp->_watchedxp as $watched){
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
	    		
	    		if($percent == 101){
	    		?>
		          <div class="game-community-tier-position tier<?php echo $exp->_tier; ?>BG z-depth-1">
		          	<div class="card-game-tier" style='margin-left:0px;' title="<?php echo "Tier ".$exp->_tier." - ".$length; ?>">
		          			<i class="mdi-action-visibility"></i>
		          	</div>
				   </div>
  	  			<?php }else{ ?>
		      		<div class="game-community-tier-position tier<?php echo $exp->_tier; ?>BG z-depth-1">
		  			  	<div class="c100 mini <?php if($exp->_tier == 1){ echo "tierone"; }else if($exp->_tier == 2){ echo "tiertwo"; }else if($exp->_tier == 3){ echo "tierthree"; }else if($exp->_tier == 4){ echo "tierfour"; }else if($exp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$exp->_tier." - ".$length; ?>" style='background-color:white;'>
					  	  <span class='tierTextColor<?php echo $exp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-action-visibility"></i></span>
						  <div class="slice">
						    <div class="bar minibar"></div>
						    <div class="fill"></div>
						  </div>
						</div>
		   			</div>
	          	<?php } 
          	}?>
		</div>
        <div class="critic-quote-container col s12 m12 l8">
			<div class="critic-quote">
				<div class="critic-quote-icon"><i class="mdi-editor-format-quote"></i></div>
				<span class='agreeBtnCount badge-lives' <?php if($agreedcount > 0){ echo "style='display:inline-block'"; } ?> ><?php if($agreedcount > 0){ echo $agreedcount;  } ?></span>
				<?php echo $exp->_quote;?>
			</div>
		</div>
		<div class="critic-action-container col s12">
			<div class="btn-flat waves-effect detailsBtn" data-uid="<?php echo $exp->_game->_id."-".$user->_id; ?>">
				<?php $watched = false; $played = false; unset($details);
					foreach($exp->_playedxp as $playedxp){
						$played = true;
						$details[] = "<i class='mdi-hardware-gamepad left tierTextColor".$exp->_tier."'></i> ".BuildPlayedSentence($playedxp);
					}
					foreach($exp->_watchedxp as $watchedxp){
						$watched = true;
						$details[] = "<i class='mdi-action-visibility left tierTextColor".$exp->_tier."'></i> ".BuildWatchedSentence($watchedxp);
					}
				?>
				<?php BuildDetailsPopUp($exp, $details, $conn); ?>
				<?php if($watched){?>
					<i class="mdi-action-visibility left tierTextColor<?php echo $exp->_tier; ?>"></i>
				<?php } ?>
				<?php if($played){?>
					<i class="mdi-hardware-gamepad left tierTextColor<?php echo $exp->_tier; ?>"></i>
				<?php } ?>
				DETAILS
			</div>
			<?php if($_SESSION['logged-in']->_id != $user->_id){ ?>
				<div class="btn-flat waves-effect <?php if(in_array($user->_id, $agrees) || $_SESSION['logged-in']->_id <= 0){ echo "disagreeBtn"; }else{ echo "agreeBtn"; } ?>" data-expid="<?php echo $exp->_id; ?>" data-agreedwith="<?php echo $user->_id; ?>" data-gameid="<?php echo $exp->_gameid; ?>" data-username="<?php echo $hiddenusername ?>"><?php if(in_array($user->_id, $agrees)){ echo "- 1up"; }else if($_SESSION['logged-in']->_id > 0){  echo "+ 1up"; } ?></div>
			<?php } ?>
		</div>
  	</div>
<?php }

function DisplayGlobalLatestXP(){ 
	$exps = GetGlobalLatestXP();
	$count = 1;
	$conn = GetConnectedToList($_SESSION['logged-in']->_id);
	$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
	foreach($exps as $exp){ 
		$user = $exp->_username;
		$agrees = GetAgreesForXP($exp->_id);
		$agreedcount = array_shift($agrees);
	
		$hiddenusername = '';
		if($user->_security == "Journalist")
			 $hiddenusername = $user->_first." ".$user->_last;
		else
			$hiddenusername = $user->_username;
		?>
		<div class="col s12 latest-xp-list-item latest-xp-count-<?php echo $count; ?>" >
	   		<div class="latest-xp-name-container col s12" data-id="<?php echo $user->_id; ?>">
	   			<?php DisplayUserPreviewCard($user, $conn, $mutualconn); ?>
				<div class="user-avatar" style="width:45px;border-radius:50%;display: inline-block;float:left;margin-left: 0.5em;margin-top:15px;height:45px;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		        <div class="user-name">
		        	<?php if($user->_security == "Journalist"){ ?>
		          	<span class="card-title activator grey-text text-darken-4" style="font-weight:bold;"><?php echo $user->_first." ".$user->_last; ?> <span class="subNameInfo" style="display:inline-block"><?php echo $user->_title; ?></span><span class="subNameContext"><?php if(sizeof($exp->_playedxp) > 0){ echo " played "; }else if(sizeof($exp->_watchedxp) > 0){ echo " watched "; }else{ echo " experienced "; } ?> </span><span class="subNameGameTitle latest-xp-game-name" data-gameid="<?php echo $exp->_game->_id; ?>" data-gbid="<?php echo $exp->_game->_gbid; ?>" ><?php echo $exp->_game->_title; ?></span></span>
		        	<?php }else{ ?>
		        	<span class="card-title activator grey-text text-darken-4" style="font-weight:bold;"><?php echo $user->_username; ?> <span class="subNameInfo" style="display:inline-block"><?php if(($_SESSION['logged-in']->_realnames == "True" && in_array($user->_id, $mutualconn)) || $user->_id == $_SESSION['logged-in']->_id){ echo "(".$user->_first." ".$user->_last.")"; } ?></span><span class="subNameContext"><?php if(sizeof($exp->_playedxp) > 0){ echo " played "; }else if(sizeof($exp->_watchedxp) > 0){ echo " watched "; }else{ echo " experienced "; } ?> </span><span class="subNameGameTitle latest-xp-game-name" data-gameid="<?php echo $exp->_game->_id; ?>" data-gbid="<?php echo $exp->_game->_gbid; ?>" ><?php echo $exp->_game->_title; ?></span></span>
		        	<?php } ?>
		        </div>
	        </div>
	        <div class="critic-quote-container col s12">
				<div class="latest-xp-quote">
					<div class="critic-quote-icon"><i class="mdi-editor-format-quote tierTextColor<?php echo $exp->_tier; ?>"></i></div>
					<span class='agreeBtnCount badge-lives' <?php if($agreedcount > 0){ echo "style='display:inline-block'"; } ?> ><?php if($agreedcount > 0){ echo $agreedcount;  } ?></span>
					<?php echo $exp->_quote;?>
					<div class="latest-xp-user-timestamp"><?php echo ConvertTimeStampToRelativeTime($exp->_date); ?></div>
				</div>
			</div>
			<div class="latest-xp-action-container col s12">
				<?php if($user->_security == "Journalist"){ ?>
					<a href='<?php echo $exp->_link; ?>' target='_blank' ><div class="btn-flat waves-effect readBtn">READ</div></a>
				<?php }else{ ?>
					<div class="btn-flat waves-effect detailsBtn" data-uid="<?php echo $exp->_game->_id."-".$user->_id; ?>">
						<?php $watched = false; $played = false; unset($details);
							foreach($exp->_playedxp as $playedxp){
								$played = true;
								$details[] = "<i class='mdi-hardware-gamepad left tierTextColor".$exp->_tier."'></i> ".BuildPlayedSentence($playedxp);
							}
							foreach($exp->_watchedxp as $watchedxp){
								$watched = true;
								$details[] = "<i class='mdi-action-visibility left tierTextColor".$exp->_tier."'></i> ".BuildWatchedSentence($watchedxp);
							}
						?>
						<?php BuildDetailsPopUp($exp, $details, $conn); ?>
						<?php if($watched){?>
							<i class="mdi-action-visibility left tierTextColor<?php echo $exp->_tier; ?>"></i>
						<?php } ?>
						<?php if($played){?>
							<i class="mdi-hardware-gamepad left tierTextColor<?php echo $exp->_tier; ?>"></i>
						<?php } ?>
						DETAILS
					</div>
				<?php } ?>
				<?php if($_SESSION['logged-in']->_id != $user->_id){ ?>
					<div class="btn-flat  waves-effect <?php if(in_array($user->_id, $agrees) || $_SESSION['logged-in']->_id <= 0){ echo "disagreeBtn"; }else{ echo "agreeBtn"; } ?>" data-expid="<?php echo $exp->_id; ?>" data-agreedwith="<?php echo $user->_id; ?>" data-gameid="<?php echo $exp->_gameid; ?>" data-username="<?php echo $hiddenusername ?>"><?php if(in_array($user->_id, $agrees)){ echo "- 1up"; }else if($_SESSION['logged-in']->_id > 0){  echo "+ 1up"; } ?></div>
				<?php } ?>
			</div>
	  	</div>
	<?php 
		$count++;
	} 
}	 

function DisplayUserPreviewCard($user, $conn, $mutualconn){ ?>
	<div class="user-preview-card">
		<div class="card user-preview-card-container" data-id="<?php echo $user->_id; ?>"> 
	        <div class="card-content">
				<?php DisplayUserLifeBarRound($user, $conn, $mutualconn, true); ?>
	        </div>
	        <div class="card-action">
	        	<?php if($_SESSION['logged-in']->_security == "Admin"){ ?>
	        		<div style='float: left;color: rgba(0,0,0,0.4);margin: 5px 1em 5px;padding: 0 5% 0 0;line-height: 36px;'><?php echo $user->_email; ?></div>
	        	<?php } ?>
	        	<?php if(in_array($user->_id, $conn) || $user->_id == $_SESSION['logged-in']->_id || $_SESSION['logged-in']->_id < 1){ ?>
	        		
	        	<?php }else{ ?>
	        		<div class="btn-flat user-preview-card-follow-action" data-userid="<?php echo $user->_id; ?>" data-name="<?php if($user->_security == "Journalist" || $user->_security == "Authenticated"){ echo $user->_first." ".$user->_last; }else{ echo $user->_username; } ?>"> FOLLOW</div>
	        		
	        	<?php } ?>
	        	<div class="btn-flat user-preview-card-view-activity" data-userid="<?php echo $user->_id; ?>">ACTIVITY</div>
	        	<div class="btn-flat user-preview-card-view-profile" data-userid="<?php echo $user->_id; ?>">PROFILE</div>
	        </div>
	      </div>
      </div>
<?php
}

function BuildDetailsPopUp($exp, $details, $conn){
	$user = $exp->_username;
	?>
	<div id="<?php echo $exp->_game->_id."-".$user->_id; ?>" class="modal detailsBtnModal dynamicModal" style="background-color:white;">
		<div class="row">
	   		<div class="latest-xp-name-container col s10">
				<div class="user-avatar" style="width:45px;border-radius:50%;display: inline-block;float:left;margin-left: 0.5em;margin-top:15px;height:45px;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		        <div class="user-name">
		        	<span class="card-title activator grey-text text-darken-4" style="font-weight:bold;"><?php if($user->_security == 'Authenticated'){ echo $user->_first." ".$user->_last; }else{ echo $user->_username; } ?> <span class="subNameInfo" style="display:inline-block"><span class="subNameContext"><?php if(sizeof($exp->_playedxp) > 0){ echo " played "; }else if(sizeof($exp->_watchedxp) > 0){ echo " watched "; }else{ echo " experienced "; } ?> </span><span class="subNameGameTitle latest-xp-game-name" data-gameid="<?php echo $exp->_game->_id; ?>" data-gbid="<?php echo $exp->_game->_gbid; ?>" ><?php echo $exp->_game->_title; ?></span></span>
		        </div>
	        </div>
	        <div class="col s2 closeDetailsModal">
	        	<i class="mdi-navigation-close"></i>
	        </div>
		</div>
		<div class="row" class="details-pop-up-text" style='margin: 20px;'>
	        <div class="critic-quote-container col s12">
				<div class="latest-xp-quote">
					<div class="critic-quote-icon"><i class="mdi-editor-format-quote tierTextColor<?php echo $exp->_tier; ?>"></i></div>
					<?php echo $exp->_quote;?>
					<?php if($exp->_authenticxp == "Yes"){ ?> 
			      		<div class='authenticated-mark mdi-action-done ' title="Verified Account"></div>
			  		<?php } ?>
				</div>
			</div>
		</div>
		<?php if(sizeof($details) > 0){ ?>
			<div class="row" class="details-pop-up-text" style='margin: 0 20px 20px;text-align:left;'>
				<div class="col s12">
					<?php foreach( $details as $detail){
						echo $detail."<br><br>";
					}
					?>		
				</div>
			</div>
		<?php } ?>
	</div>
	<?php
}


function DisplayUniversalUserPreview(){ ?>
	<div id="universalUserPreview" class="modal"></div>
<?php }


function BuildPlayedSentence($exp){
	$sentence = "I played "; 
	$date = explode('-',$exp->_date);
	if($exp->_completed > 0){
		if($exp->_completed < 100){
			$sentence = $sentence." through ".$exp->_completed."%";
		}else if($exp->_completed == 100){
			$sentence = "I finished";
		}else if($exp->_completed == 101){
			$sentence = "I played multiple playthroughs";
		}
	}
	if($exp->_date > 0){
		if($date[1] > '0' && $date[1] <= '3'){ $quarter = "Q1 (Jan/Feb/Mar)"; }
		else if($date[1] > '3' && $date[1] <= '6'){ $quarter = "Q2 (Apr/May/Jun)"; }
		else if($date[1] > '6' && $date[1] <= '9'){ $quarter = "Q3 (Jul/Aug/Sep)"; }
		else if($date[1] > '9' && $date[1] <= '12'){ $quarter = "Q4 (Oct/Nov/Dec)"; }
		else if($date[1] == 0){ $quarter = ""; }
		if($quarter != "")
			$sentence = $sentence." during ".$quarter." of ".$date[0];
		else
			$sentence = $sentence." in ".$date[0];
	}
	if($exp->_platform != ""){
		$myplatforms = str_replace("\n", " ", $exp->_platform);
		$sentence = $sentence." on ". $myplatforms;
	}
	
	return $sentence;
}

function BuildWatchedSentence($exp){
	$sentence = "I "; 
	$date = explode('-',$exp->_date);
	if($exp->_length != ""){
		$sentence = $sentence.strtolower($exp->_length);
	}
	if($exp->_date > 0){
		if($date[1] > '0' && $date[1] <= '3'){ $quarter = "Q1 (Jan/Feb/Mar)"; }
		else if($date[1] > '3' && $date[1] <= '6'){ $quarter = "Q2 (Apr/May/Jun)"; }
		else if($date[1] > '6' && $date[1] <= '9'){ $quarter = "Q3 (Jul/Aug/Sep)"; }
		else if($date[1] > '9' && $date[1] <= '12'){ $quarter = "Q4 (Oct/Nov/Dec)"; }
		else if($date[1] == 0){ $quarter = ""; }
		if($quarter != "")
			$sentence = $sentence." during ".$quarter." of ".$date[0];
		else
			$sentence = $sentence." in ".$date[0];
	}
	if($exp->_source != ""){
		$sentence = $sentence." on ".$exp->_source;
	}
	if($exp->_url != ""){
		$sentence = $sentence."<span class='WatchedSourceIcon icon-windows' style='color:#474747;margin-left:0.5em;' data-link='".$exp->_url."'></span>";
	}
	
	return $sentence;
}

function DisplayRoleManagement($userid){
	$user = GetUser($userid);
	?>
	  <label class="myxp-form-box-header" style='font-size: 1.25em;font-weight: 300;'>User Roles</label>
	  <div class="myxp-form-select-item" style='margin: 10px 25%;text-align: left;'>
	      <input name="rolegroup" class="with-gap" type="radio" id="user-role" <?php if($user->_security == 'User'){ echo "checked"; }?> />
	    <label for="user-role">User</label>
	    </div >
	  <div class="myxp-form-select-item" style='margin: 10px 25%;text-align: left;'>
	    <input name="rolegroup" class="with-gap" type="radio" id="authenticated-role" <?php if($user->_security == 'Authenticated'){ echo "checked"; }?> />
	    <label for="authenticated-role">Authenticated Journalist</label>
	    </div >
	    <div class="myxp-form-select-item" style='margin: 10px 25%;text-align: left;'>
	      <input name="rolegroup" class="with-gap" type="radio" id="journalist-role" <?php if($user->_security == 'Journalist'){ echo "checked"; }?> />
	    <label for="journalist-role">Journalist</label>
	    </div >
	    <div class='btn save-role-change' style='margin-top: 25px;'>Save Role Changes</div>
	<?php
}

?>
