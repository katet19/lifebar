<?php function DisplayAnalyzeTab($user, $myxp, $game){
	BuildExperienceSpectrum($user, $myxp);
	BuildSimilarGames($game, $user->_id);
	BuildProfileProgress($game, $user->_id);
} ?>

<?php function BuildExperienceSpectrum($user, $myxp){
	$tierdata = explode("||", $user->_weave->_overallTierTotal);
	
	$lifetime[] = $tierdata[0];
	$lifetime[] = $tierdata[1];
	$lifetime[] = $tierdata[2];
	$lifetime[] = $tierdata[3];
	$lifetime[] = $tierdata[4];

	?>
	<div class="row">
		<div class="col s12 analyze-card z-depth-1" style='width:100%;padding-bottom: 1em !important;' >
			<div class='analyze-card-header'>
				<div class='analyze-card-title'>Experience Spectrum</div>
				<div class='analyze-card-sub-title'>compared to other game experiences</div>
			</div>
			<canvas class="GraphCriticUsers" style='margin:0.5em 20px 1em'  data-t1="<?php echo $lifetime[0] ;?>" data-t2="<?php echo $lifetime[1] ;?>" data-t3="<?php echo $lifetime[2] ;?>" data-t4="<?php echo $lifetime[3] ;?>" data-t5="<?php echo $lifetime[4]; ?>"></canvas>
			<div class="analyze-exp-spectrum-tier">
				<div class="analyze-exp-spectrum-tier-sub">Tier5</div>
				<?php if($myxp->_tier == 5){ ?>
					<div class="analyze-exp-spectrum-game-piece">
						<i class="mdi-communication-location-on tierTextColor<?php echo $myxp->_tier; ?>"></i>
						<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
					</div>
				<?php } ?>
			</div>
			<div class="analyze-exp-spectrum-tier" style='left:26%;'>
				<div class="analyze-exp-spectrum-tier-sub">Tier4</div>
				<?php if($myxp->_tier == 4){ ?>
					<div class="analyze-exp-spectrum-game-piece">
						<i class="mdi-communication-location-on tierTextColor<?php echo $myxp->_tier; ?>"></i>
						<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
					</div>
				<?php } ?>
			</div>
			<div class="analyze-exp-spectrum-tier" style='left:50%;'>
				<div class="analyze-exp-spectrum-tier-sub">Tier3</div>
				<?php if($myxp->_tier == 3){ ?>
					<div class="analyze-exp-spectrum-game-piece">
						<i class="mdi-communication-location-on tierTextColor<?php echo $myxp->_tier; ?>"></i>
						<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
					</div>
				<?php } ?>
			</div>
			<div class="analyze-exp-spectrum-tier" style='left:72%;'>
				<div class="analyze-exp-spectrum-tier-sub">Tier2</div>
				<?php if($myxp->_tier == 2){ ?>
					<div class="analyze-exp-spectrum-game-piece">
						<i class="mdi-communication-location-on tierTextColor<?php echo $myxp->_tier; ?>"></i>
						<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
					</div>
				<?php } ?>
			</div>
			<div class="analyze-exp-spectrum-tier" style='left:93%;'>
				<div class="analyze-exp-spectrum-tier-sub">Tier1</div>
				<?php if($myxp->_tier == 1){ ?>
					<div class="analyze-exp-spectrum-game-piece">
						<i class="mdi-communication-location-on tierTextColor<?php echo $myxp->_tier; ?>"></i>
						<div class="analyze-exp-spectrum-game-line tier<?php echo $myxp->_tier; ?>BG">.</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
} 

function BuildSimilarGames($game, $userid){
	$similargames = explode(",",$game->_similar);
	if(sizeof($similargames) > 0){
	?>
	<div class="row">
		<div class="col s12 analyze-card z-depth-1" style='width:100%;padding-bottom: 1em !important;' >
		<div class='analyze-card-header'>
			<div class='analyze-card-title'>Similar Games</div>
		</div>
		<?php 
		$count = 1;
		foreach($similargames as $similar){ 
				unset($simgame);
				unset($simxp);
				$simgame = GetGameByGBID($similar);
				$simxp = GetExperienceForUserComplete($userid, $simgame->_id);
				if($simgame != ''){ ?>
				<div class="col s12 game-list-item" data-tier='<?php echo $simxp->_tier; ?>' data-year='<?php echo $simxp->_year; ?>' data-title="<?php echo $simxp->_title; ?>" >
		     		<div class="analyze-card-list-item <?php if($count > 10){ ?>analyze-view-more-hide<?php } ?>" data-gameid="<?php echo $simxp->_gameid; ?>" data-gbid="<?php echo $similar; ?>" style='background-color:white;'>
				        <div class="analyze-image-list" style="width:100%;background:url(<?php echo $simgame->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
				        <div class="analyze-game-list-title">
				        	<?php echo $simgame->_title; ?> 
				        	<div class="analyze-game-list-details"><?php if($simgame->_year > 0){ echo $simgame->_year; } ?></div>
			        	</div>
			      		<div class="analyze-game-my-tier">
				  			<?php if(sizeof($simxp->_playedxp) > 0){ 
						  	  	if($simxp->_playedxp[0]->_completed == "101")
									$percent = 100;
								else
									$percent = $simxp->_playedxp[0]->_completed;
									
								if($percent == 100){ ?>
				  	  	       		<div class="analyze-game-tier-position tier<?php echo $simxp->_tier; ?>BG z-depth-1">
							          	<div class="card-game-tier" title="<?php echo "Tier ".$simxp->_tier." - Completed"; ?>">
						    				<i class="mdi-hardware-gamepad"></i>
							          	</div>
						          	</div>
					          	<?php }else{ ?>
					          		<div class="analyze-game-tier-position tier<?php echo $simxp->_tier; ?>BG z-depth-1">
					      			  	<div class="c100 mini <?php if($simxp->_tier == 1){ echo "tierone"; }else if($simxp->_tier == 2){ echo "tiertwo"; }else if($simxp->_tier == 3){ echo "tierthree"; }else if($simxp->_tier == 4){ echo "tierfour"; }else if($simxp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$simxp->_tier." - ".$percent."% finished"; ?>" style='background-color:white;'>
									  	  <span class='tierTextColor<?php echo $simxp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-hardware-gamepad"></i></span>
										  <div class="slice">
										    <div class="bar minibar"></div>
										    <div class="fill"></div>
										  </div>
										</div>
									</div>
					          	<?php } ?>
				          	<?php }else if(sizeof($simxp->_watchedxp) > 0){
					  			$percent = 20;
					  	  		$length = "";
					    		foreach($simxp->_watchedxp as $watched){
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
						          <div class="analyze-game-tier-position tier<?php echo $simxp->_tier; ?>BG z-depth-1">
						          	<div class="card-game-tier" title="<?php echo "Tier ".$simxp->_tier." - ".$length; ?>">
						          			<i class="mdi-action-visibility"></i>
						          	</div>
								   </div>
				  	  			<?php }else{ ?>
						      		<div class="analyze-game-tier-position tier<?php echo $simxp->_tier; ?>BG z-depth-1">
						  			  	<div class="c100 mini <?php if($simxp->_tier == 1){ echo "tierone"; }else if($simxp->_tier == 2){ echo "tiertwo"; }else if($simxp->_tier == 3){ echo "tierthree"; }else if($simxp->_tier == 4){ echo "tierfour"; }else if($simxp->_tier == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$simxp->_tier." - ".$length; ?>" style='background-color:white;'>
									  	  <span class='tierTextColor<?php echo $simxp->_tier; ?> tierInProgress' style='background-color:white;'><i class="mdi-action-visibility"></i></span>
										  <div class="slice">
										    <div class="bar minibar"></div>
										    <div class="fill"></div>
										  </div>
										</div>
						   			</div>
					          	<?php } 
				          	}?>
			      		</div>
		      		</div>
		     	</div>
			<?php $count++;
			} 
		}?>
		<div class='analyze-card-header' style='border-bottom:none;margin-bottom:10px;margin-top:30px;padding:inherit;'>
			<div class='analyze-view-more-button btn' style='display:none;'>View More</div>
			<div class='analyze-view-less-button btn' style='display:none;'>Hide</div>
		</div>
		</div>
	</div>
	<?php
	}
}

function BuildProfileProgress($game, $userid){
	?>
	<div class="row">
		<div class="col s12 analyze-card z-depth-1" style='width:100%;padding-bottom: 1em !important;' >
		<div class='analyze-card-header'>
			<div class='analyze-card-title'>Profile Progress</div>
		</div>
		
		</div>
	</div>
	<?php
}
?>
