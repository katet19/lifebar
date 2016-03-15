<?php function DisplayAnalyzeTab($user, $myxp, $game){
	BuildExperienceSpectrum($user, $myxp);
	BuildSimilarGames($game, $user->_id);
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
	?>
	<div class="row">
		<div class="col s12 analyze-card z-depth-1" style='width:100%;padding-bottom: 1em !important;' >
		<div class='analyze-card-header'>
			<div class='analyze-card-title'>Similar Games</div>
		</div>
		<?php $similargames = explode(",",$game->_similar);
		foreach($similargames as $similar){ 
			
				$simgame = GetGameByGBID($similar);
				$simxp = GetExperienceForUserComplete($userid, $simgame->_gbid);
				if($simgame != ''){ ?>
				<div class="col s12 game-list-item" data-tier='<?php echo $simxp->_tier; ?>' data-year='<?php echo $simxp->_year; ?>' data-title="<?php echo $simxp->_title; ?>" >
		     		<div class="analyze-card-list-item" data-gameid="<?php echo $simxp->_gameid; ?>" data-gbid="<?php echo $simxp->_gbid; ?>" style='background-color:white;'>
				        <div class="card-image-list" style="width:100%;background:url(<?php echo $simgame->_image; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
				        <div class="card-game-tier-vert tier<?php echo $simxp->_tier; ?>BG"></div>
				        <div class="card-game-list-title">
				        	<?php echo $simgame->_title; ?> 
				        	<div class="analyze-game-list-details"><?php if($simgame->_year > 0){ echo $simgame->_year; } ?></div>
			        	</div>
		      		</div>
		     	</div>
			<?php
			} 
		}?>
		</div>
	</div>
	<?php
}
?>