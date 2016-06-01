<?php
function ShowMyNewXP($gameid, $playedorwatched, $editid){
	$exp = GetExperienceForUserByGame($_SESSION['logged-in']->_id, $gameid);
	if($exp->_tier == 0){
		ShowTierQuote($exp, $gameid, false);
	}
	
	if($playedorwatched == "Played"){
		ShowEditPlayed($exp);
	}else if($playedorwatched == "Watched"){
		ShowEditWatched($exp, -1);
	}
	
	ShowMyXPFAB();
}
	
function ShowTierQuote($exp, $gameid, $edit){
	if($exp == null)
		$exp = GetExperienceForUserByGame($_SESSION['logged-in']->_id, $gameid);
		
	?>
	<div class="myxp-edit-container z-depth-1">
	    <div class="row">
		  <div class="col s1"><i class="mdi-editor-format-quote prefix quoteflip" style='font-size:2em;'></i></div>
	      <div class="input-field col s10">
	  	    <script>
		      function countChar(val) {
		        var len = val.value.length;
		        if (len > 140) {
		          val.value = val.value.substring(0, 140);
		        } else {
		          $('#charNum').html(len);
		        }
		        ValidateXPEntry();
		      };
		    </script>
	        <textarea id="myxp-quote" class="materialize-textarea" onkeyup="countChar(this)" maxlength="140"><?php echo $exp->_quote; ?></textarea>
	        <label for="myxp-quote" <?php if($exp->_quote != ""){ echo "class='active'"; } ?> >Enter a summary of your experience here</label>
	        <div class="myxp-quote-counter"><span id='charNum'><?php echo strlen($exp->_quote); ?></span>/140</div>
	      </div>
	    </div>
	    <div class="row myxp-tiercontainer" data-year="<?php echo $exp->_game->_year; ?>">
	    	<div class="col s12"><span> Choose a tier relative to other games you have experienced <?php if($exp->_game->_year == 0){ echo "for a date unknown"; } else { echo "in ".$exp->_game->_year; } ?></span></div>
	    	<div class="col s12 myxp-GraphContainer">
	    		<?php ShowTierGraphSelection($exp, 200); ?>
	    	</div>
	    </div>
	    <?php 
	    	if($edit){
				ShowDelete($exp->_id);
	    	} ?>
	</div>
	<?php 
	if($_SESSION['logged-in']->_security == "Authenticated" || $_SESSION['logged-in']->_security == "Journalist"){ ?>
	<br>
	<div class="myxp-edit-container z-depth-1">
		<div class="row">
			<div class="col s12 myxp-sentence" style="margin-left: 20px;">
					Link to published review
			</div>
		</div>
		<div class="row myxp-form-box">
			<div class="input-field col s12 m12 l8">
		        <input id="myxp-form-critic-link" type="text" <?php if($exp->_link != ""){ ?> value="<?php echo $exp->_link; ?>" <?php } ?> >
	        	<label for="myxp-form-critic-link" <?php if($exp->_link != ""){ ?> class="active"<?php } ?>>Source your review</label>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php
	if($edit){
		ShowMyXPFAB();
	}
}

function ShowDelete($subxpid){ ?>
	<br>
	<div class="row">
		<div class="col s12">
			<div class="waves-effect btn-flat myxp-delete-btn" style='float: left;' data-id='<?php echo $subxpid; ?>'><i class="mdi-content-remove-circle-outline" style="vertical-align: sub;"></i> <b>Remove Experience</b></div>
		</div>
	</div>
<?php
}
	
	
function ShowEditPlayed($exp){ 
	$xp = $exp->_playedxp[0];
?>
<br>
<div class="myxp-edit-container z-depth-1">
    <div class="row">
    	<div class="col s2 m1 myxp-sentence">
    		<i class="mdi-hardware-gamepad myxp-sentence-icon"></i>
		</div>
		<div class="col s10 m11 myxp-sentence">
			<div class="myxp-sentence-intro">I played...</div> <div class="myxp-sentence-completion"></div> <div class="myxp-sentence-quarter"></div> <div class="myxp-sentence-year"></div> <div class="myxp-sentence-platform"></div>
		</div>
	</div>
	<div class="row myxp-form-box">
		<div class="col s12 myxp-form-hr"></div>
	</div>
	<div class="row myxp-form-box">
		<div class="col s12 m12 l8">
			<label>Played completion</label>
			<select id="myxp-percent-completed">
				<option value='0'>Please select</option>
				<option value='10' <?php if($xp->_completed == 10){ echo "selected"; } ?>>10%</option>
				<option value='20' <?php if($xp->_completed == 20){ echo "selected"; } ?>>20%</option>
				<option value='30' <?php if($xp->_completed == 30){ echo "selected"; } ?>>30%</option>
				<option value='40' <?php if($xp->_completed == 40){ echo "selected"; } ?>>40%</option>
				<option value='50' <?php if($xp->_completed == 50){ echo "selected"; } ?>>50%</option>
				<option value='60' <?php if($xp->_completed == 60){ echo "selected"; } ?>>60%</option>
				<option value='70' <?php if($xp->_completed == 70){ echo "selected"; } ?>>70%</option>
				<option value='80' <?php if($xp->_completed == 80){ echo "selected"; } ?>>80%</option>
				<option value='90' <?php if($xp->_completed == 90){ echo "selected"; } ?>>90%</option>
				<option value='100' <?php if($xp->_completed == 100){ echo "selected"; } ?>>Finished</option>
				<option value='101' <?php if($xp->_completed == 101){ echo "selected"; } ?>>Multiple playthroughs</option>
			</select>
		</div>
	</div>
	<div class="row myxp-form-box">
		<div class="col s12 m12 l8">
		  <label>Experienced Year</label>
		  <select id="myxp-year">
			<?php 
				$date = explode('-',$xp->_date);
				$year = date("Y");  
				$releaseyear = $exp->_game->_year;
				
				if($exp->_game->_year == 0){
					$officialrelease = "";
				}else{
					$officialrelease =  ConvertDateToLongRelationalEnglish($exp->_game->_released);
				} 
				while($year >= $releaseyear && ($year - $birthyear) > 2){?>
					<option value="<?php echo $year; ?>"  <?php if($date[0] == $year){ echo "selected"; } ?>><?php echo $year; ?> <?php if($year == $releaseyear){ echo " - US Release (".$officialrelease.")"; } ?> </option>
				<?php
					$year = $year - 1;
				}
			 ?>
			</select>
		 </div>
	 </div>
	 <div class="row myxp-form-box myxp-quarter" >
		 <div class="col s12 m12 l8" style="padding:0;">
		 	<?php $month = date('n');
	 			if($month > '0' && $month <= '3'){
					$quarter = "1";
				}else if($month > '3' && $month <= '6'){
					$quarter = "2";
				}else if($month > '6' && $month <= '9'){
					$quarter = "3";
				}else if($month > '9' && $month <= '12'){
					$quarter = "4";
				}else if($month == 0){
					$quarter = "0";
				}
		 	?>
			  <label class="myxp-form-box-header">Experienced Quarter</label>
			  <div class="myxp-form-select-item" style="display:none">
		  	    <input name="dategroup" class="with-gap" type="radio" id="q0" <?php if($date[1] == 0){ echo "checked"; }else if($quarter == 0){ echo "checked"; } ?> data-text="" />
		  	  </div >
			  <div class="myxp-form-select-item">
		  	    <input name="dategroup" class="with-gap" type="radio" id="q1" <?php if($date[1] == 1){ echo "checked"; }else if($quarter == 1){ echo "checked"; } ?> data-text="<b style='opacity:0.7;'>Q1</b> (Jan/Feb/Mar)" />
			    <label for="q1"><b>Q1</b> (Jan/Feb/Mar)</label>
		  	  </div >
			  <div class="myxp-form-select-item">
			    <input name="dategroup" class="with-gap" type="radio" id="q2" <?php if($date[1] == 4){ echo "checked"; }else if($quarter == 2){ echo "checked"; } ?> data-text="<b style='opacity:0.7;'>Q2</b> (Apr/May/Jun)" />
			    <label for="q2"><b>Q2</b> (Apr/May/Jun)</label>
		  	  </div >
		  	  <div class="myxp-form-select-item">
		  	    <input name="dategroup" class="with-gap" type="radio" id="q3" <?php if($date[1] == 7){ echo "checked"; }else if($quarter == 3){ echo "checked"; } ?> data-text="<b style='opacity:0.7;'>Q3</b> (Jul/Aug/Sep)" />
			    <label for="q3"><b>Q3</b> (Jul/Aug/Sep)</label>
		  	  </div >
			  <div class="myxp-form-select-item">
			    <input name="dategroup" class="with-gap"type="radio" id="q4" <?php if($date[1] == 10){ echo "checked"; }else if($quarter == 4){ echo "checked"; } ?> data-text="<b style='opacity:0.7;'>Q4</b> (Oct/Nov/Dec)" />
			    <label for="q4"><b>Q4</b> (Oct/Nov/Dec)</label>
			  </div>
	  	</div>
	</div>
	<div class="row myxp-form-box" >
		<div class="col s12" style="padding:0;">
			<label class="myxp-form-box-header">Platforms played</label>
			<?php $platforms = explode("\n", $exp->_game->_platforms); $myplatforms = explode("\n", $xp->_platform);
			foreach($platforms as $platform){ 
				if($platform != ""){ ?>
					<div style="display:block;margin-bottom:5px;margin-left:20px">
					    <input type="checkbox" id="<?php echo $platform;?>" class="myxp-platforms" data-text="<?php echo $platform;?>" 
							<?php 
							if(sizeof($myplatforms) > 0){
								foreach($myplatforms as $myplatform){
									if(trim($myplatform) != ""){
										if(stristr(trim($platform), trim($myplatform)) || sizeof($platforms) == 1){ echo 'checked'; }
									}
								} 
							} ?>
					    />
						<label for="<?php echo $platform;?>"><?php echo $platform;?></label>
					</div>
			<?php 	} 
			} ?>
		</div>
	</div>
	<?php if($xp->_completed > 0){
		ShowDelete($xp->_id);
	} ?>
</div>
<br>
<div class="myxp-edit-container z-depth-1">
	<div class="row">
		<div class="col s12 myxp-sentence" style="margin-left: 20px;">
			My played details	
		</div>
	</div>
	<div class="row myxp-form-box">
		<div class="col s12">
			<label class="myxp-form-box-header">Modes experienced</label>
			<div style="display:block;margin-bottom:5px;margin-left:20px">
			    <input type="checkbox" id="singleplayer" <?php if($xp->_mode == "Single Player" || $xp->_mode == "Single and Multiplayer"){ echo "checked"; } ?> />
				<label for="singleplayer">Single Player</label>
			</div>
			<div style="display:block;margin-bottom:5px;margin-left:20px">
			    <input type="checkbox" id="multiplayer" <?php if($xp->_mode == "Multiplayer" || $xp->_mode == "Single and Multiplayer"){ echo "checked"; } ?> />
				<label for="multiplayer">Mutliplayer</label>
			</div>
		</div> 
	</div>
	<div class="row myxp-form-box">
		<div class="col s12">
			<label class="myxp-form-box-header">Played before release</label>
			<div style="display:block;margin-bottom:5px;margin-left:20px">
			    <input type="checkbox" id="alpha" <?php if($xp->_alpha == "1"){ echo "checked"; } ?> />
				<label for="alpha">Alpha</label>
			</div>
			<div style="display:block;margin-bottom:5px;margin-left:20px">
			    <input type="checkbox" id="beta" <?php if($xp->_beta == "1"){ echo "checked"; } ?>/>
				<label for="beta">Beta</label>
			</div>
			<div style="display:block;margin-bottom:5px;margin-left:20px">
			    <input type="checkbox" id="earlyaccess" <?php if($xp->_earlyaccess == "1"){ echo "checked"; } ?>/>
				<label for="earlyaccess">Early Access</label>
			</div>
		</div> 
	</div>
	<div class="row myxp-form-box">
		<div class="col s12">
			<label class="myxp-form-box-header">Played a small slice of the game</label>
			<div style="display:block;margin-bottom:5px;margin-left:20px">
			    <input type="checkbox" id="demo" <?php if($xp->_demo == "1"){ echo "checked"; } ?>/>
				<label for="demo">Demo</label>
			</div>
		</div> 
	</div>
	<div class="row myxp-form-box">
		<div class="col s12">
			<label class="myxp-form-box-header">Played the post release content</label>
			<div style="display:block;margin-bottom:5px;margin-left:20px">
			    <input type="checkbox" id="dlc" <?php if($xp->_dlc == "true"){ echo "checked"; } ?>/>
				<label for="dlc">DLC</label>
			</div>
		</div> 
	</div>
	<div class="row myxp-form-box">
		<div class="col s12">
			<label class="myxp-form-box-header">Streamed my experience</label>
			<div style="display:block;margin-bottom:5px;margin-left:20px">
			    <input type="checkbox" id="streamed" <?php if($xp->_streamed == "true"){ echo "checked"; } ?>/>
				<label for="streamed">I streamed myself</label>
			</div>
		</div> 
	</div>
</div>
<?php
}

function ShowFastEditWatched($gameid, $watchid){
	$exp = GetExperienceForUserByGame($_SESSION['logged-in']->_id, $gameid);
	ShowEditWatched($exp, $watchid);
}

function ShowEditWatched($exp, $watchid){ 
	if($watchid > 0){
		foreach($exp->_watchedxp as $watched){
			if($watched->_id == $watchid)
				$watchedxp = $watched;
		}
	}
?>
<br>
<div class="myxp-edit-container z-depth-1" <?php if($watchid > 0){ echo "data-watchid='".$watchid."'"; } ?> >
    <div class="row">
    	<div class="col s2 m1 myxp-sentence">
    		<i class="mdi-action-visibility myxp-sentence-icon"></i>
    	</div>
		<div class="col s10 m11 myxp-sentence">
			<div class="myxp-sentence-intro">I watched...</div> <div class="myxp-sentence-exp"></div> <div class="myxp-sentence-quarter"></div> <div class="myxp-sentence-year"></div> <div class="myxp-sentence-src"></div> <div class="myxp-sentence-src-url"></div>
		</div>
	</div>
	<div class="row myxp-form-box">
		<div class="col s12 myxp-form-hr"></div>
	</div>
	<div class="row myxp-form-box myxp-form-box-extra">
		<div class="col s12 m12 l12" style="padding:0;">
			  <label class="myxp-form-box-header">Your viewing experience</label>
  			  <div class="myxp-form-select-item" style='margin-bottom: 0.5em;'>
  			    <input name="viewingitemgroup" class="with-gap" type="radio" id="watchedtrailer" data-text="trailer(s)" <?php if($watchedxp->_length == 'Watched trailer(s)'){ echo 'checked'; } ?> />
			    <label for="watchedtrailer" style='font-weight:bold;'>Watched trailer(s)</label>
			    <div class="myxp-view-desc">Any promotional trailers released containing gameplay or otherwise</div>
		  	  </div >
  			  <div class="myxp-form-select-item" style='margin-bottom: 0.5em;'>
  			    <input name="viewingitemgroup" class="with-gap" type="radio" id="watcheddeveloper" data-text="a developer diary" <?php if($watchedxp->_length == 'Watched a developer diary'){ echo 'checked'; } ?> />
			    <label for="watcheddeveloper" style='font-weight:bold;'>Watched a developer diary</label>
			    <div class="myxp-view-desc">Content where mechanics, characters, levels, etc. are revealed and discussed</div>
		  	  </div >
  			  <div class="myxp-form-select-item" style='margin-bottom: 0.5em;'>
  			    <input name="viewingitemgroup" class="with-gap" type="radio" id="watchedpromotional" data-text="promotional gameplay" <?php if($watchedxp->_length == 'Watched promotional gameplay'){ echo 'checked'; } ?> />
			    <label for="watchedpromotional" style='font-weight:bold;'>Watched promotional gameplay</label>
			    <div class="myxp-view-desc">Gameplay that is being shared by the developer or publisher of the game</div>
		  	  </div >
			  <div class="myxp-form-select-item" style='margin-bottom: 0.5em;'>
  			    <input name="viewingitemgroup" class="with-gap" type="radio" id="watchedanhourorless" data-text="gameplay" <?php if($watchedxp->_length == 'Watched an hour or less' || $watchedxp->_length == 'Watched multiple hours' || $watchedxp->_length == 'Watched gameplay'){ echo 'checked'; } ?> />
			    <label for="watchedanhourorless" style='font-weight:bold;'>Watched gameplay</label>
		  	  	<div class="myxp-view-desc">Gameplay from a third party, like a Let's Play or Quick Look</div>
		  	  </div >
  			  <div class="myxp-form-select-item" style='margin-bottom: 0.5em;'>
  			    <input name="viewingitemgroup" class="with-gap" type="radio" id="competitiveplay" data-text="competitive play" <?php if($watchedxp->_length == 'Watched competitive play'){ echo 'checked'; } ?> />
			    <label for="competitiveplay" style='font-weight:bold;'>Watched competitive play</label>
			    <div class="myxp-view-desc">Professional level play at tournaments or league play</div>
  			  </div >
			  <div class="myxp-form-select-item" style='margin-bottom: 0.5em;'>
			    <input name="viewingitemgroup" class="with-gap" type="radio" id="speedrun" data-text="a speed run" <?php if($watchedxp->_length == 'Watched a speed run'){ echo 'checked'; } ?> />
			    <label for="speedrun" style='font-weight:bold;'>Watched a speed run</label>
			    <div class="myxp-view-desc">A complete playthrough with the intention of finishing as fast as possible</div>
			  </div>
			  <div class="myxp-form-select-item" style='margin-bottom: 0.5em;'>
		    	<input name="viewingitemgroup" class="with-gap" type="radio" id="completesingleplay" data-text="a complete playthrough" <?php if($watchedxp->_length == 'Watched a complete single player playthrough' || $watchedxp->_length == 'Watched a complete playthrough'){ echo 'checked'; } ?> />
			    <label for="completesingleplay" style='font-weight:bold;'>Watched a complete playthrough</label>
			    <div class="myxp-view-desc">A complete playthrough of a game's core content, like a campaign or reaching the level cap</div>
			  </div >
		  </div>
	</div>
	<div class="row myxp-form-box">
		<div class="col s12 m12 l8">
		  <label>Experienced Year</label>
		  <select id="myxp-year">
			<?php 
				$date = explode('-',$watchedxp->_date);
				$year = date("Y");  
				$releaseyear = $exp->_game->_year;
				$releaseyear = $releaseyear - 5;
				if($exp->_game->_year == 0){
					$officialrelease = "";
				}else{
					$officialrelease =  ConvertDateToLongRelationalEnglish($exp->_game->_released);
				} 
				while($year >= $releaseyear && ($year - $birthyear) > 2){?>
					<option value="<?php echo $year; ?>"  <?php if($date[0] == $year){ echo "selected"; } ?>><?php echo $year; ?> <?php if($year == $exp->_game->_year){ echo " - US Release (".$officialrelease.")"; } ?> </option>
				<?php
					$year = $year - 1;
				}
			 ?>
			</select>
		 </div>
	 </div>
	 <div class="row myxp-form-box myxp-quarter">
		 <div class="col s12 m12 l8" style="padding:0;">
		 	<?php $month = date('n');
	 			if($month > '0' && $month <= '3'){
					$quarter = "1";
				}else if($month > '3' && $month <= '6'){
					$quarter = "2";
				}else if($month > '6' && $month <= '9'){
					$quarter = "3";
				}else if($month > '9' && $month <= '12'){
					$quarter = "4";
				}else if($month == 0){
					$quarter = "0";
				}
		 	?>
			  <label class="myxp-form-box-header">Experienced Quarter</label>
  			  <div class="myxp-form-select-item" style="display:none">
		  	    <input name="dategroup" class="with-gap" type="radio" id="q0" <?php if($date[1] == 0){ echo "checked"; }else if($quarter == 0){ echo "checked"; } ?> data-text="" />
		  	  </div >
			  <div class="myxp-form-select-item">
		  	    <input name="dategroup" class="with-gap" type="radio" id="q1" <?php if($date[1] == 1){ echo "checked"; }else if($quarter == 1){ echo "checked"; } ?> data-text="<b style='opacity:0.7;'>Q1</b> (Jan/Feb/Mar)" />
			    <label for="q1"><b>Q1</b> (Jan/Feb/Mar)</label>
		  	  </div >
			  <div class="myxp-form-select-item">
			    <input name="dategroup" class="with-gap" type="radio" id="q2" <?php if($date[1] == 4){ echo "checked"; }else if($quarter == 2){ echo "checked"; } ?> data-text="<b style='opacity:0.7;'>Q2</b> (Apr/May/Jun)" />
			    <label for="q2"><b>Q2</b> (Apr/May/Jun)</label>
		  	  </div >
		  	  <div class="myxp-form-select-item">
		  	    <input name="dategroup" class="with-gap" type="radio" id="q3" <?php if($date[1] == 7){ echo "checked"; }else if($quarter == 3){ echo "checked"; } ?> data-text="<b style='opacity:0.7;'>Q3</b> (Jul/Aug/Sep)" />
			    <label for="q3"><b>Q3</b> (Jul/Aug/Sep)</label>
		  	  </div >
			  <div class="myxp-form-select-item">
			    <input name="dategroup" class="with-gap"type="radio" id="q4" <?php if($date[1] == 10){ echo "checked"; }else if($quarter == 4){ echo "checked"; } ?> data-text="<b style='opacity:0.7;'>Q4</b> (Oct/Nov/Dec)" />
			    <label for="q4"><b>Q4</b> (Oct/Nov/Dec)</label>
			  </div>
	  	</div>
	</div>
	<div class="row myxp-form-box">
		<div class="col s12 m12 l8">
			<label>Your viewing source</label>
			<select id="myxp-view-source">
				<option  value=''>Please select</option>
				<option  value='Destructoid' <?php if($watchedxp->_source == "Destructoid"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Destructoid' && $watchedxp->_source == ""){ echo " selected"; $found = true; } ?>>Destructoid</option>
				<option  value='Edge' <?php if($watchedxp->_source == "Edge"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Edge' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Edge</option>
				<option  value='EGM' <?php if($watchedxp->_source == "EGM"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'EGM' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>EGM</option>
				<option  value='Eurogamer' <?php if($watchedxp->_source == "Eurogamer"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Eurogamer' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Eurogamer</option>
				<option  value='Game Informer' <?php if($watchedxp->_source == "Game Informer"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Game Informer' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Game Informer</option>
				<option  value='Gamesradar' <?php if($watchedxp->_source == "Gamesradar"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Gamesradar' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Gamesradar</option>
				<option  value='Gamespot' <?php if($watchedxp->_source == "Gamespot"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Gamespot' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Gamespot</option>
				<option  value='Gametrailers' <?php if($watchedxp->_source == "Gametrailers"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Gametrailers' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Gametrailers</option>
                <option  value='Giant Bomb' <?php if($watchedxp->_source == "Giantbomb" || $watchedxp->_source == "Giant Bomb"){ echo " selected"; $found = true; }else if(($_SESSION['logged-in']->_defaultwatched == 'Giantbomb' || $_SESSION['logged-in']->_defaultwatched == "Giant Bomb") && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Giant Bomb</option>
				<option  value='IGN' <?php if($watchedxp->_source == "IGN"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'IGN' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>IGN</option>
				<option  value='Joystiq' <?php if($watchedxp->_source == "Joystiq"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Gamespot' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Joystiq</option>
				<option  value='Other' <?php if($watchedxp->_source == "Other"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Other' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Other</option>
				<option  value='Polygon' <?php if($watchedxp->_source == "Polygon"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Polygon' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Polygon</option>
				<option  value='Twitch' <?php if($watchedxp->_source == "Twitch"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Twitch' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Twitch</option>
				<option  value='Watched a Friend' <?php if($watchedxp->_source == "Watched a Friend"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'Watched a Friend' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>Watched a Friend</option>
				<option  value='UStream' <?php if($watchedxp->_source == "UStream"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'UStream' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>UStream</option>
				<option  value='YouTube' <?php if($watchedxp->_source == "YouTube"){ echo " selected"; $found = true; }else if($_SESSION['logged-in']->_defaultwatched == 'YouTube' && $watchedxp->_source == ""){ echo "selected"; $found = true; } ?>>YouTube</option>
				<?php if($found == false && $watchedxp->_source != ""){ ?>
					<option value='<?php echo $watchedxp->_source; ?>' selected><?php echo $watchedxp->_source; ?></option>
				<?php }else if($found == false && $_SESSION['logged-in']->_defaultwatched != ""){?>
					<option value='<?php echo $_SESSION['logged-in']->_defaultwatched; ?>' selected><?php echo $_SESSION['logged-in']->_defaultwatched; ?></option>
				<?php } ?>
			 </select>
			 <div class="input-field col s12 m12 l8" style='display:none;margin-bottom: 20px;'>
			 	<input type='text' id='myxp-source-link'>
			 	<label for="myxp-source-link">Name of content producer</label>
			 </div>
		 </div>
	</div>
	<div class="row myxp-form-box">
		<div class="input-field col s12 m12 l8">
	        <input id="myxp-form-url" type="text" <?php if($watchedxp->_url != ""){ ?> value="<?php echo $watchedxp->_url; ?>" <?php } ?> >
        	<label for="myxp-form-url" <?php if($watchedxp->_url != ""){ ?> class="active"<?php } ?>>Viewed source url</label>
		</div>
	</div>
	<?php if($watchid > 0){
		ShowDelete($watchedxp->_id);
	} ?>
</div>
<?php
	if($watchid > 0){
		ShowMyXPFAB();
	}

}

function ShowTierGraphSelection($exp, $size){
	$tiertally = GetTierBreakdownLight($_SESSION['logged-in']->_id, $exp->_game->_year);
	$total = $tiertally[0];
	if($total == "")
		$total = 0;
	$t1 = $tiertally[1];
	$t2 = $tiertally[2];
	$t3 = $tiertally[3];
	$t4 = $tiertally[4];
	$t5 = $tiertally[5];
	
	if($t1 != 0)
		$relativeT1 = ceil($t1 / $total * 70);
	else
		$relativeT1 = 0;
	if($t2 != 0)
		$relativeT2 = ceil($t2 / $total * 70);
	else
		$relativeT2 = 0;
	if($t3 != 0)
		$relativeT3 = ceil($t3 / $total * 70);
	else
		$relativeT3 = 0;
	if($t4 != 0)
		$relativeT4 = ceil($t4 / $total * 70);
	else
		$relativeT4 = 0;
	if($t5 != 0)
		$relativeT5 = ceil($t5 / $total * 70);
	else
		$relativeT5 = 0;
	?>
	<div style="float: left; margin-left: 4rem; font-weight: 500;margin-bottom: 15px;">
		<?php echo "Best"; ?>
	</div>
	<div class="myxp-GraphBarContainer firsttier" data-total="<?php echo $total; ?>">
		<div data-tier="1" data-count="<?php echo $t1; ?>" class="myxp-GraphLabel btn-flat waves-effect waves-light <?php if($exp->_tier == 1){ echo "myxp-selected-tier tier1BG"; }?>"><i class="mdi-content-add left" style='vertical-align: sub;font-size: 1em;'></i>Tier 1</div>
		<div class="myxp-GraphBar tier1BG" style="width:<?php echo $relativeT1; ?>%;"></div>
	</div>
	<div class="myxp-GraphBarContainer">
		<div data-tier="2" data-count="<?php echo $t2; ?>" class="myxp-GraphLabel btn-flat waves-effect waves-light <?php if($exp->_tier == 2){ echo "myxp-selected-tier tier2BG"; }?>"><i class="mdi-content-add left" style='vertical-align: sub;font-size: 1em;'></i>Tier 2</div>
		<div class="myxp-GraphBar tier2BG" style="width:<?php echo $relativeT2; ?>%;"></div>
	</div>
	<div class="myxp-GraphBarContainer">
		<div data-tier="3" data-count="<?php echo $t3; ?>" class="myxp-GraphLabel btn-flat waves-effect waves-light <?php if($exp->_tier == 3){ echo "myxp-selected-tier tier3BG"; }?>"><i class="mdi-content-add left" style='vertical-align: sub;font-size: 1em;'></i>Tier 3</div>
		<div class="myxp-GraphBar tier3BG" style="width:<?php echo $relativeT3; ?>%;"></div>
	</div>
		<div class="myxp-GraphBarContainer">
		<div data-tier="4" data-count="<?php echo $t4; ?>" class="myxp-GraphLabel btn-flat waves-effect waves-light <?php if($exp->_tier == 4){ echo "myxp-selected-tier tier4BG"; }?>"><i class="mdi-content-add left" style='vertical-align: sub;font-size: 1em;'></i>Tier 4</div>
		<div class="myxp-GraphBar tier4BG" style="width:<?php echo $relativeT4; ?>%;"></div>
	</div>
	<div class="myxp-GraphBarContainer">
		<div data-tier="5" data-count="<?php echo $t5; ?>" class="myxp-GraphLabel btn-flat waves-effect waves-light <?php if($exp->_tier == 5){ echo "myxp-selected-tier tier5BG"; }?>"><i class="mdi-content-add left" style='vertical-align: sub;font-size: 1em;'></i>Tier 5</div>
		<div class="myxp-GraphBar tier5BG" style="width:<?php echo $relativeT5; ?>%;"></div>
	</div>
	<div style="float: left; margin-left: 4rem; font-weight: 500;">
		<?php echo "Worst"; ?>
	</div>
	<?php
}

function ShowMyXPFAB(){
	?>
	<?php if($_SESSION['logged-in']->_id > 0){ ?>
		<div class="fixed-action-btn" id="myxp-fab">
			<a class="waves-effect waves-light btn myxp-cancel HideForTabletAndMobile"><i class="mdi-navigation-close left"></i>Cancel</a>
			<a class="waves-effect waves-light btn disabled myxp-save HideForTabletAndMobile"><i class="mdi-content-save left"></i>Save</a>
			<a class="waves-effect waves-light btn myxp-cancel HideForDesktop"><i class="mdi-navigation-close"></i></a>
			<a class="waves-effect waves-light btn disabled myxp-save HideForDesktop">Save</a>
	  	</div>
<?php } 
}

function DisplayTierGameDetails($year, $tier, $userid){ 
	$exps = GetTierBreakdownYearTier($userid, $year, $tier);
	?>
	<div class="row">
		<div class="col s10 myxp-tier-breakdown-label">Your Tier <?php echo $tier; ?> experiences from games released <?php if($year == 0){ echo "for a date unknown"; } else { echo "in ".$year; } ?></div>
        <div class="col s2 closeDetailsModal">
        	<i class="mdi-navigation-close"></i>
        </div>
		<?php foreach($exps as $exp){ ?>
				<div class="col s12 m6" style='text-align:left;'>
					<?php if(sizeof($exp->_playedxp) > 0){ ?>
						<i class="mdi-hardware-gamepad tierTextColor<?php echo $tier; ?>"></i>
					<?php } ?>
					<?php if(sizeof($exp->_watchedxp) > 0){ ?>
						<i class="mdi-action-visibility tierTextColor<?php echo $tier; ?>"></i>
					<?php } ?>
					<?php echo $exp->_game->_title; ?>
				</div>
		<?php } ?>
	</div>
<?php }

function DisplayMyXP($gameid){
	$game = GetGame($gameid);
	$myxp = GetExperienceForUserByGame($_SESSION['logged-in']->_id, $game->_id);
	if($myxp->_tier != 0){ ShowMyXP($myxp); }
}

function ShowMyXP($exp){ 
		$agrees = GetAgreesForXP($exp->_id);
		$agreedcount = array_shift($agrees);
		?>
	<div class="myxp-details-container z-depth-1">
	    <div class="row" style='margin: 0;'>
		  <div class="col s3 m2">
		  	<div class="myxp-details-tier tierTextColor<?php echo $exp->_tier; ?>">TIER<div class="myxp-details-tier-number"><?php echo $exp->_tier; ?></div></div>
		  </div>
  	      <div class="col s9 m10">
	        <div class="myxp-details-quote-container"><i class="mdi-editor-format-quote prefix quoteflip" style='font-size:2em;'></i><span class="myxp-details-quote"><?php echo $exp->_quote; ?></span></div>
	      </div>
	    </div>
	    <div class="row" style='margin: 0;'>
	    	<div class="myxp-edit-tier-quote btn-flat waves-effect"><i class="mdi-content-create left" style="vertical-align: sub;"></i> Update</div>
	    	<div class="myxp-share-tier-quote btn-flat waves-effect" data-userid='<?php echo  $exp->_userid; ?>'><i class="mdi-social-share left" style="vertical-align: sub;"></i> Share</div>
	    </div>
    </div>
    <br>
    <?php if($agreedcount > 0){ ?>
    <div class="myxp-details-container z-depth-1">
    	<div class="row" style='margin: 0;'>
		  <div class="col s3 m2">
		  	<div class="myxp-details-agree-count"><?php echo $agreedcount; ?>up</div>
		  </div>
	    	<div class="col s9 m10">
		    	<div class="myxp-details-agree-list">
		    		<?php
		    			$i = 0;
		    			while($i < sizeof($agrees) && $i < 25){ ?>
		    			<div class="myxp-details-agree-listitem">
		    				<?php $user = GetUser($agrees[$i]); 
		    					$conn = GetConnectedToList($_SESSION['logged-in']->_id);
								$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
		    				?>
		    				<div class="user-avatar" style="margin-top:0;width:45px;border-radius:50%;display: inline-block;float:left;margin-left: 0.5em;height:45px;background:url(<?php echo $user->_thumbnail; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		    				<?php DisplayUserPreviewCard($user, $conn, $mutualconn); ?>
		    			</div>
		    		<?php	
		    		$i++;
		    		} ?>
		    	</div>
	    	</div>
	    </div>
    </div>
    <br>
    <?php } ?>
    <div class="myxp-details-container z-depth-1" style="padding:0;">
	    <?php 
	    if(sizeof($exp->_playedxp) > 0){
    		$played = $exp->_playedxp[0];
	    	?>
	    	<div class="row" style='border-bottom: 1px solid #ddd;padding: 2em 0 0;'>
	    		<div class="col s0 m2"><i class='mdi-hardware-gamepad' style='font-size:2em;color:white;'></i></div>
	    		<div class="col s12 m10 myxp-details-items">
	    			<?php BuildPlayedVisualSentence($played, $exp->_userid, $exp->_tier,'',''); ?><br><div class="myxp-edit-played btn-flat waves-effect"><i class="mdi-content-create left" style="vertical-align: sub;"></i> Update</div>
	    		</div>
	    	</div>
    	<?php
	    }
	    
	    foreach($exp->_watchedxp as $watched){?>
	    	<div class="row" style='border-bottom: 1px solid #ddd;padding: 2em 0 0;'>
	    		<div class="col s0 m2"><i class='mdi-action-visibility' style='font-size:2em;color:white;'></i></div>
	    		<div class="col s12 m10 myxp-details-items">
	    			<?php echo BuildWatchedVisualSentence($watched, $exp->_userid, $exp->_tier,'','');	?><br><div class="myxp-edit-watched btn-flat waves-effect" data-id="<?php echo $watched->_id; ?>"><i class="mdi-content-create left" style="vertical-align: sub;"></i> Edit</div>
	    		</div>
	    	</div>
    	<?php
	    }
	    ?>
    </div>
    <div class="col s12 m12 l10" id='myxp-game-width-box'></div>
<?php }

function BuildPlayedVisualSentence($exp, $userid, $tier, $gamename, $gbid){
	$date = explode('-',$exp->_date);
	if($exp->_completed > 0){
		if($exp->_completed < 100){
			$completedSentence = $exp->_completed."% Completed";
		}else if($exp->_completed == 100){
			$completedSentence = "Finished";
		}else if($exp->_completed == 101){
			$exp->_completed = 100;
			$completedSentence = "Multiple playthroughs";
		}
	}
	
	if($exp->_date > 0){
		if($date[1] > '0' && $date[1] <= '3'){ $quarter = "Q1"; }
		else if($date[1] > '3' && $date[1] <= '6'){ $quarter = "Q2"; }
		else if($date[1] > '6' && $date[1] <= '9'){ $quarter = "Q3"; }
		else if($date[1] > '9' && $date[1] <= '12'){ $quarter = "Q4"; }
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
	
	
	
	?>
	<div class="visual-sentence-perct">
	  	<div class="c100 mini p<?php echo $exp->_completed; ?> <?php if($tier == 1){ echo "tierone"; }else if($tier == 2){ echo "tiertwo"; }else if($tier == 3){ echo "tierthree"; }else if($tier == 4){ echo "tierfour"; }else if($tier == 5){ echo "tierfive"; }  ?> z-depth-1" title="<?php echo $completedSentence; ?>" style='float: none;margin-left: auto;margin-right: auto;background-color: white;'>
	  	  <span style='<?php if($exp->_completed != 100){ ?>background-color:transparent;<?php } ?>' class='visualsentence-tier-display <?php if($exp->_completed == 100){ ?>tier<?php echo $tier; ?>BG<?php } ?>'><i class="mdi-hardware-gamepad <?php if($exp->_completed != 100){ ?>tierTextColor<?php echo $tier; } ?> <?php if($exp->_completed == 100){ ?>style='color:white;<?php } ?>"></i></span>
		  <div class="slice">
		    <div class="bar minibar"></div>
		    <div class="fill"></div>
		  </div>
		</div>
		<div class="badge-small-name" style='width:auto;'><? echo $completedSentence; ?></div>
	</div>
	<? if($gamename != '' && $gbid != ''){ ?>
		<div class="visual-sentence-label" style='padding: 0 0px 0 20px;'>
			<div class="feed-card-level-game_title feed-activity-game-link" data-gbid="<?php echo $gbid; ?>"><?php echo $gamename; ?></div>
		</div>
	<?php } ?>
	<div class="visual-sentence-label">on</div>
	<?php $platforms = explode(",",$exp->_platformids);
		if(sizeof($platforms) == 1){ ?>
	<div class="visual-sentence-perct">
		<?php 
			$milestone = GetPlatformMilestone($userid, $exp->_platformids);
			DisplayPlatformMilestone($milestone, false);
		?>
	</div>
	<?php }else{ ?>
		<div class="visual-sentence-label" style='text-align:center; text-align: center;vertical-align: middle;font-weight: 500;padding:0px;'>
			<?php echo sizeof($platforms); ?>
			<div class="visual-sentence-sublabel">platforms</div>
		</div>
	<?php } ?>
	<div class="visual-sentence-label">during</div>
	<div class="visual-sentence-year"><?php echo $date[0]; ?></div>
	
	<?php
}

function BuildPlayedVisualActivitySentence($exp, $userid, $tier, $gamename, $gbid){
	$date = explode('-',$exp->_date);
	if($exp->_completed > 0){
		if($exp->_completed < 100){
			$completedSentence = $exp->_completed."% Completed";
		}else if($exp->_completed == 100){
			$completedSentence = "Finished";
		}else if($exp->_completed == 101){
			$exp->_completed = 100;
			$completedSentence = "Multiple playthroughs";
		}
	}
	
	if($exp->_date > 0){
		if($date[1] > '0' && $date[1] <= '3'){ $quarter = "Q1"; }
		else if($date[1] > '3' && $date[1] <= '6'){ $quarter = "Q2"; }
		else if($date[1] > '6' && $date[1] <= '9'){ $quarter = "Q3"; }
		else if($date[1] > '9' && $date[1] <= '12'){ $quarter = "Q4"; }
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
	
	
	
	?>
	<div class="feed-visual-sentence-perct" style="float:left;margin-right:15px;">
	  	<div class="c100 mini p<?php echo $exp->_completed; ?> <?php if($tier == 1){ echo "tierone"; }else if($tier == 2){ echo "tiertwo"; }else if($tier == 3){ echo "tierthree"; }else if($tier == 4){ echo "tierfour"; }else if($tier == 5){ echo "tierfive"; }  ?> z-depth-1" title="<?php echo $completedSentence; ?>" style='float: none;margin-left: auto;margin-right: auto;background-color: white;'>
	  	  <span style='<?php if($exp->_completed != 100){ ?>background-color:transparent;<?php } ?>' class='visualsentence-tier-display <?php if($exp->_completed == 100){ ?>tier<?php echo $tier; ?>BG<?php } ?>'><i class="mdi-hardware-gamepad <?php if($exp->_completed != 100){ ?>tierTextColor<?php echo $tier; } ?> <?php if($exp->_completed == 100){ ?>style='color:white;<?php } ?>"></i></span>
		  <div class="slice">
		    <div class="bar minibar"></div>
		    <div class="fill"></div>
		  </div>
		</div>
		<div class="badge-small-name" style='width:auto;margin-left:0;'><? echo $completedSentence; ?></div>
	</div>
	<? if($gamename != '' && $gbid != ''){ ?>
		<div class="feed-visual-sentence-label">
			<div class="feed-card-level-game_title feed-activity-game-link" data-gbid="<?php echo $gbid; ?>"><?php echo $gamename; ?></div>
		</div>
	<?php } ?>
	<div class="feed-visual-sentence-label">on</div>
	<?php $platforms = explode(",",$exp->_platformids);
	if(sizeof($platforms) == 1){ ?>
	<div class="feed-visual-sentence-perct" style='padding: 0 5px;'>
		<?php 
			$milestone = GetPlatformMilestone($userid, $exp->_platformids);
			DisplayPlatformMilestone($milestone, true);
		?>
	</div>
	<?php }else{ ?>
		<div class="feed-visual-sentence-label" style='text-align: center;vertical-align: middle;font-weight: 500;padding: 0px;height: 50px;padding-top: 7px;'>
			<?php echo sizeof($platforms); ?>
			<div class="visual-sentence-sublabel" style="font-size:0.6em">platforms</div>
		</div>
	<?php } ?>
	<div class="feed-visual-sentence-label">during</div>
	<div class="feed-visual-sentence-year"><?php echo $date[0]; ?></div>
	
	<?php
}

function DisplayPlatformMilestone($milestone, $smallNames){ 
	//Calculate current level
	if($milestone->_progress->_percentlevel4 == 100 && $milestone->_level5 > 0){
		$progress = $milestone->_progress->_progresslevel5;
		$currentlevel = 5;
		$percent = $milestone->_progress->_percentlevel5;
		$threshold = $milestone->_level5;
	}else if($milestone->_progress->_percentlevel3 == 100  && $milestone->_level4 > 0){
		$progress = $milestone->_progress->_progresslevel4;
		$currentlevel = 4;
		$percent = $milestone->_progress->_percentlevel4;
		$threshold = $milestone->_level4;
	}else if($milestone->_progress->_percentlevel2 == 100  && $milestone->_level3 > 0){
		$progress = $milestone->_progress->_progresslevel3;
		$currentlevel = 3;
		$percent = $milestone->_progress->_percentlevel3;
		$threshold = $milestone->_level3;
	}else if($milestone->_progress->_percentlevel1 == 100  && $milestone->_level2 > 0){
		$progress = $milestone->_progress->_progresslevel2;
		$currentlevel = 2;
		$percent = $milestone->_progress->_percentlevel2;
		$threshold = $milestone->_level2;
	}else{
		$progress = $milestone->_progress->_progresslevel1;
		$currentlevel = 1;
		$percent = $milestone->_progress->_percentlevel1;
		$threshold = $milestone->_level1;
	}
?>
	<div style='text-align:center;display:block;'>
	  <div class="c100 mini <?php if($currentlevel == 1){ echo "one"; }else if($currentlevel == 2){ echo "two"; }else if($currentlevel == 3){ echo "three"; }else if($currentlevel == 4){ echo "four"; }else if($currentlevel == 5){ echo "five"; }  ?> p<?php echo $percent; ?> z-depth-1" title="<?php echo $progress; ?> / <?php  echo $threshold; ?>" style='float: none;margin-left: auto;margin-right: auto;background-color: white;'>
	  	  <span style="width: 35px;height:35px;top: 3px;left: 3px;background: -moz-linear-gradient(top, rgba(0,0,0,0) 0%, rgba(0,0,0,0.75) 100%, rgba(0,0,0,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.75)), color-stop(101%,rgba(0,0,0,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(0,0,0,0) 0%,rgba(0,0,0,0.75) 100%,rgba(0,0,0,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(0,0,0,0) 0%,rgba(0,0,0,0.75) 100%,rgba(0,0,0,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><span style='opacity:0'><?php  $progress; ?>/<?php  $threshold; ?></span></span>
		  <div class="slice">
		    <div class="bar minibar"></div>
		    <div class="fill"></div>
		  </div>
		</div>
	</div>
	<div class="badge-small-name visual-sentence-game" style='width:auto;'><?php if($smallNames){ echo ConvertPlatformToShortHand($milestone->_name); }else{ echo $milestone->_name;  } ?></div>
<?php
}
function BuildWatchedVisualSentence($exp, $userid, $tier, $gamename, $gbid){
	if($exp->_length == "Watched a speed run" || $exp->_length == "Watched a complete single player playthrough" || $exp->_length == "Watched a complete playthrough"){
		$exp->_completed = 101;
	}else if($exp->_completed < 100 && ($exp->_length == "Watched multiple hours" || $exp->_length == "Watched gameplay" || $exp->_length == "Watched an hour or less")){
		$exp->_completed = 100;
	}else if($exp->_completed < 50 && ($exp->_length == "Watched promotional gameplay" || $exp->_length == "Watched a developer diary")){
		$exp->_completed = 50;
	}else{
		$exp->_completed = 20;
	}

	if($exp->_source != ""){
		$sentence = $sentence." on ".$exp->_source;
	}
	if($exp->_url != ""){
		$link = $exp->_url;
	}
	
	
	$date = explode('-',$exp->_date);
	
	if($exp->_date > 0){
		if($date[1] > '0' && $date[1] <= '3'){ $quarter = "Q1"; }
		else if($date[1] > '3' && $date[1] <= '6'){ $quarter = "Q2"; }
		else if($date[1] > '6' && $date[1] <= '9'){ $quarter = "Q3"; }
		else if($date[1] > '9' && $date[1] <= '12'){ $quarter = "Q4"; }
		else if($date[1] == 0){ $quarter = ""; }
		if($quarter != "")
			$sentence = $sentence." during ".$quarter." of ".$date[0];
		else
			$sentence = $sentence." in ".$date[0];
	}
	
	?>
	<div class="visual-sentence-perct">
	  	<div class="c100 mini p<?php echo $exp->_completed; ?> <?php if($tier == 1){ echo "tierone"; }else if($tier == 2){ echo "tiertwo"; }else if($tier == 3){ echo "tierthree"; }else if($tier == 4){ echo "tierfour"; }else if($tier == 5){ echo "tierfive"; }  ?> z-depth-1" title="<?php echo strtolower($exp->_length); ?>" style='float: none;margin-left: auto;margin-right: auto;background-color: white;'>
	  	  <span style='<?php if($exp->_completed != 101){ ?>background-color:transparent;<?php } ?>left: 0px !important;' class='visualsentence-tier-display <?php if($exp->_completed == 101){ ?>tier<?php echo $tier; ?>BG<?php } ?>'><i class="mdi-action-visibility <?php if($exp->_completed != 101){ ?>tierTextColor<?php echo $tier; } ?> <?php if($exp->_completed == 101){ ?>style='color:white;<?php } ?>"></i></span>
		  <div class="slice">
		    <div class="bar minibar"></div>
		    <div class="fill"></div>
		  </div>
		</div>
		<div class="badge-small-name" style='width:auto;margin-left:0;'>Watched</div>
	</div>
	<? if($gamename != '' && $gbid != ''){ ?>
		<div class="visual-sentence-label" style='padding: 0 0px 0 20px;'>
			<div class="feed-card-level-game_title feed-activity-game-link" data-gbid="<?php echo $gbid; ?>"><?php echo $gamename; ?></div>
		</div>
	<?php } ?>
	<div class="visual-sentence-label">on</div>
	<div class="visual-sentence-label" style='font-weight:400;padding:0px;'><?php echo $exp->_source; if($link != ''){ echo "<a href='$link' style='margin-left:10px;' target='_blank'><i class='fa fa-film'></i></a>"; }?></div>
	<div class="visual-sentence-label">during</div>
	<div class="visual-sentence-year"><?php echo $date[0]; ?></div>
	
	<?php
}

function BuildWatchedVisualActivitySentence($exp, $userid, $tier, $gamename, $gbid){
	if($exp->_length == "Watched a speed run" || $exp->_length == "Watched a complete single player playthrough" || $exp->_length == "Watched a complete playthrough"){
		$exp->_completed = 101;
	}else if($exp->_completed < 100 && ($exp->_length == "Watched multiple hours" || $exp->_length == "Watched gameplay" || $exp->_length == "Watched an hour or less")){
		$exp->_completed = 100;
	}else if($exp->_completed < 50 && ($exp->_length == "Watched promotional gameplay" || $exp->_length == "Watched a developer diary")){
		$exp->_completed = 50;
	}else{
		$exp->_completed = 20;
	}

	if($exp->_source != ""){
		$sentence = $sentence." on ".$exp->_source;
	}
	if($exp->_url != ""){
		$link = $exp->_url;
	}
	
	
	$date = explode('-',$exp->_date);
	
	if($exp->_date > 0){
		if($date[1] > '0' && $date[1] <= '3'){ $quarter = "Q1"; }
		else if($date[1] > '3' && $date[1] <= '6'){ $quarter = "Q2"; }
		else if($date[1] > '6' && $date[1] <= '9'){ $quarter = "Q3"; }
		else if($date[1] > '9' && $date[1] <= '12'){ $quarter = "Q4"; }
		else if($date[1] == 0){ $quarter = ""; }
		if($quarter != "")
			$sentence = $sentence." during ".$quarter." of ".$date[0];
		else
			$sentence = $sentence." in ".$date[0];
	}
	
	?>
	<div class="feed-visual-sentence-perct" style="float:left;margin-right:15px;">
	  	<div class="c100 mini p<?php echo $exp->_completed; ?> <?php if($tier == 1){ echo "tierone"; }else if($tier == 2){ echo "tiertwo"; }else if($tier == 3){ echo "tierthree"; }else if($tier == 4){ echo "tierfour"; }else if($tier == 5){ echo "tierfive"; }  ?> z-depth-1" title="<?php echo strtolower($exp->_length); ?>" style='float: none;margin-left: auto;margin-right: auto;background-color: white;'>
	  	  <span style='<?php if($exp->_completed != 101){ ?>background-color:transparent;<?php } ?>left: 0px !important;' class='visualsentence-tier-display <?php if($exp->_completed == 101){ ?>tier<?php echo $tier; ?>BG<?php } ?>'><i class="mdi-action-visibility <?php if($exp->_completed != 101){ ?>tierTextColor<?php echo $tier; } ?> <?php if($exp->_completed == 101){ ?>style='color:white;<?php } ?>"></i></span>
		  <div class="slice">
		    <div class="bar minibar"></div>
		    <div class="fill"></div>
		  </div>
		</div>
		<div class="badge-small-name" style='width:auto;margin-left:0;'>Watched</div>
	</div>
	<? if($gamename != '' && $gbid != ''){ ?>
		<div class="feed-visual-sentence-label" style='margin-top: 10px;'>
			<div class="feed-card-level-game_title feed-activity-game-link" data-gbid="<?php echo $gbid; ?>"><?php echo $gamename; ?></div>
		</div>
	<?php } ?>
	<div class="feed-visual-sentence-label">on</div>
	<div class="feed-visual-sentence-label" style='font-weight:400;padding:0px;'><?php echo $exp->_source; if($link != ''){ echo "<a href='$link' style='margin-left:10px;' target='_blank'><i class='fa fa-film'></i></a>"; }?></div>
	<div class="feed-visual-sentence-label">during</div>
	<div class="feed-visual-sentence-year"><?php echo $date[0]; ?></div>
	
	<?php
}

