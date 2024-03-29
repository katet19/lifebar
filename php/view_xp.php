<?php
function ShowTierModal($gameid){
	$xp = GetExperienceForUserCompleteOrEmptyGame($_SESSION['logged-in']->_id, $gameid);
	$game = $xp->_game;
	?>
	<div class="row">
		<div class="col s12">
			<div class="fixed-close-modal-btn-top"><i class="material-icons" style='font-size: 1.2em;vertical-align: sub;'>arrow_forward</i></div>
			<div class="GameHeaderContainer" style='height:10vh;'>
				<div class="GameHeaderBackground" style="height:10vh;background: -moz-linear-gradient(bottom, rgba(0,0,0,0.5) 40%, rgba(0,0,0,0.7) 100%, rgba(0,0,0,0.7) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -webkit-gradient(linear, left bottom, left top, color-stop(40%,rgba(0,0,0,0.5)), color-stop(100%,rgba(0,0,0,0.7)), color-stop(101%,rgba(0,0,0,0.7))), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -webkit-linear-gradient(bottom, rgba(0,0,0,0.5) 40%,rgba(0,0,0,0.7) 100%,rgba(0,0,0,0.7) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -o-linear-gradient(bottom, rgba(0,0,0,0.5) 40%,rgba(0,0,0,0.7) 100%,rgba(0,0,0,0.7) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
				<div class="modal-header">
						<div style='font-size:0.7em;'>Star & Ranking</div><div style='font-weight:300;'><?php echo $game->_title;?></div>
				</div>
			</div>			
			<div class="modal-content-container">
				<?php 
					$ranklist = GetSmartTierList($gameid, $_SESSION['logged-in']->_id); 
					ShowTierList($ranklist, $game, $xp->_tier);
				?>
			</div>	
			<div class="modal-save-container">
					<div class="save-btn modal-btn-pos">Save</div>
					<div class="cancel-btn modal-btn-pos">Cancel</div>
			</div>
		</div>
	</div>
	<?php
}

function ShowTierList($tierlist, $currgame, $tier){
	if(sizeof($tierlist) > 0){
		$filter = explode(",", $tierlist[0][3]);
		$count = 1;
		?>
		<div class="modal-rank-filter">
			<?php if(sizeof($filter) > 0){
				 foreach($filter as $filteritem){
				?>
				<div class="modal-rank-filter-item"><?php echo $filteritem; ?></div>
				<?php
				} 
			} ?>
		</div>
		<ul class="collapsible tier-modal-collapsible-container" data-collapsible="accordion">
			<li>
				<div class="collapsible-header <?php if($tier == 1){ echo 'active'; } ?> tier1BGHover"><div class="tier-modal-text">FANTASTIC</div> <?php DisplayStarSequence(1); ?></div>
				<div class="collapsible-body tier-modal-body" style='border-bottom:2px solid #0A67A3;'>
					<?php DisplayTierAddButton($tier, 1, $currgame);
					  foreach($tierlist as $tieritem){
							if($tieritem[2] == 1 && $tieritem[0]->_id != $currgame->_id){
								ShowTierListItem($tieritem, $currgame, false, $count);
								$count++;
							}
						} ?>
				</div>
			</li>
			<li>
				<div class="collapsible-header <?php if($tier == 2){ echo 'active'; } ?> tier2BGHover"><div class="tier-modal-text">GOOD</div> <?php DisplayStarSequence(2); ?></div>
				<div class="collapsible-body tier-modal-body" style='border-bottom:2px solid #00B25C;'>
					<?php DisplayTierAddButton($tier, 2, $currgame);
					foreach($tierlist as $tieritem){
						if($tieritem[2] == 2 && $tieritem[0]->_id != $currgame->_id){
							ShowTierListItem($tieritem, $currgame, false, $count);
							$count++;
						}
					} ?>
				</div>
			</li>
			<li>
				<div class="collapsible-header <?php if($tier == 3){ echo 'active'; } ?> tier3BGHover"><div class="tier-modal-text">AVERAGE</div> <?php DisplayStarSequence(3); ?></div>
				<div class="collapsible-body tier-modal-body" style='border-bottom:2px solid #FF8E00;'>
					<?php DisplayTierAddButton($tier, 3, $currgame);
					foreach($tierlist as $tieritem){
						if($tieritem[2] == 3 && $tieritem[0]->_id != $currgame->_id){
							ShowTierListItem($tieritem, $currgame, false, $count);
							$count++;
						}
					} ?>
				</div>
			</li>
			<li>
				<div class="collapsible-header <?php if($tier == 4){ echo 'active'; } ?> tier4BGHover"><div class="tier-modal-text">POOR</div> <?php DisplayStarSequence(4); ?></div>
				<div class="collapsible-body tier-modal-body" style='border-bottom:2px solid rgb(255, 65, 0);'>
					<?php DisplayTierAddButton($tier, 4, $currgame);
					foreach($tierlist as $tieritem){
						if($tieritem[2] == 4 && $tieritem[0]->_id != $currgame->_id){
							ShowTierListItem($tieritem, $currgame, false, $count);
							$count++;
						}
					} ?>
				</div>
			</li>
			<li>
				<div class="collapsible-header <?php if($tier == 5){ echo 'active'; } ?> tier5BGHover"><div class="tier-modal-text">TERRIBLE</div> <?php DisplayStarSequence(5); ?></div>
				<div class="collapsible-body tier-modal-body" style='border-bottom:2px solid #DB0058;'>
					<?php DisplayTierAddButton($tier, 5, $currgame);
					foreach($tierlist as $tieritem){
						if($tieritem[2] == 5 && $tieritem[0]->_id != $currgame->_id){
							ShowTierListItem($tieritem, $currgame, false, $count);
							$count++;
						}
					} ?>
				</div>
			</li>
		</ul>
		<?php
	}
}

function DisplayTierAddButton($tier, $currtier, $currgame){
	$game[] = $currgame;
	$game[] = 0;
	$game[] = $tier;
	?>
	<div class="modal-rank-button-item" style='padding: 0;'>
		<?php
		if($tier == $currtier){
		?>
			<div class="btn tier-modal-add-btn" style='display:none;'><i class="material-icons left" style='vertical-align: bottom;'>add_box</i> <span style='text-transform:none;'>Add without ranking</span></div>
			<div class="tier-modal-current-game">
				<?php ShowTierListItem($game, null, true, -1); ?>
			</div>
		<?php
		}else if($tier > 0){
		?>
			<div class="btn tier-modal-add-btn"><i class="material-icons" style='vertical-align: bottom;'>add_box</i> <span style='text-transform:none;'>Add without ranking</span></div>
			<div class="tier-modal-current-game" style='display:none;'>
				<?php ShowTierListItem($game, null, true, -1); ?>
			</div>
		<?php
		}else{
		?>
			<div class="btn tier-modal-add-btn"><i class="material-icons" style='vertical-align: bottom;'>add_box</i> <span style='text-transform:none;'>Add without ranking</span></div>
			<div class="tier-modal-current-game" style='display:none;'>
				<?php ShowTierListItem($game, null, true, -1); ?>
			</div>
		<?php
		}
		?>
	</div>
	<?php
}

function ShowTierListItem($gameitem, $currgame, $isActive, $count){
	$game = $gameitem[0];
	$rank = $gameitem[1];
	$tier = $gameitem[2];
	?>
	<div class="modal-rank-group">
		<div class="modal-rank-active-game" <?php if($currgame->_id == $game->_id){ echo "style='display:block;'"; } ?> data-internalrank="<?php echo $count; ?>">
			<div class="modal-rank-item-rank" <?php if($count < 0){ echo "style='opacity:0;'"; } ?> ><?php echo $count; ?></div>
			<div class="modal-rank-item-title"><?php echo $currgame->_title; ?></div>
			<div class="modal-rank-item-subtitle">
			<?php 
					if($currgame->_year > 0)
						echo $currgame->_year;
					else
						echo "????";
						
					$developers = array_filter(explode("\n", $currgame->_developer));
					if(sizeof($developers) > 0){
					echo " <span style='font-weight:500;font-size:1.1em;'>|</span> ";
					echo implode("- ", $developers);
					}
					$publishers = array_filter(explode("\n", $currgame->_publisher));
					if(sizeof($developers) > 0  && sizeof($publishers) > 0)
					echo " <span style='font-weight:500;font-size:1.1em;'>|</span> ";
					if(sizeof($publishers) > 0){
					echo implode("- ", $publishers);
					} 
			?>
			</div>
		</div>
		<?php if($currgame->_id != $game->_id){ ?>
			<div class="modal-rank-item" data-internalrank="<?php echo $count; ?>" data-truerank="<?php echo $rank; ?>">
				<div class="modal-rank-item-insert-btn">
					<div class="row modal-rank-item-hover-col-title"><div class="modal-rank-item-arrow"></div>INSERT</div>
				</div>
				<div class="modal-rank-item-rank" <?php if($count < 0){ echo "style='opacity:0;'"; } ?> ><?php echo $count; ?></div>
				<div class="modal-rank-item-truerank"><?php if($count > 0){ echo "#".$rank; } ?></div>
				<div class="modal-rank-item-title"><?php echo $game->_title; ?></div>
				<div class="modal-rank-item-subtitle">
				<?php 
					if($game->_year > 0)
						echo $game->_year;
					else
						echo "????";
						
					$developers = array_filter(explode("\n", $game->_developer));
					if(sizeof($developers) > 0){
						echo " <span style='font-weight:500;font-size:1.1em;'>|</span> ";
						echo implode("- ", $developers);
					}
					$publishers = array_filter(explode("\n", $game->_publisher));
					if(sizeof($developers) > 0  && sizeof($publishers) > 0)
						echo " <span style='font-weight:500;font-size:1.1em;'>|</span> ";
					if(sizeof($publishers) > 0){
						echo implode("- ", $publishers);
					} 
				?>
				</div>
				<div class="divider" style='margin-top: 5px;'></div>
			</div>
		<?php } ?>
	</div>
	<?php
}

function ShowXPModal($gameid){
	$xp = GetExperienceForUserCompleteOrEmptyGame($_SESSION['logged-in']->_id, $gameid);
	$game = $xp->_game;
	?>
	<div class="row">
		<div class="col s12">
			<div class="fixed-close-modal-btn-top"><i class="material-icons" style='font-size: 1.2em;vertical-align: sub;'>arrow_forward</i></div>
			<div class="GameHeaderContainer" style='height:10vh;'>
				<div class="GameHeaderBackground" style="height:10vh;background: -moz-linear-gradient(bottom, rgba(0,0,0,0.5) 40%, rgba(0,0,0,0.7) 100%, rgba(0,0,0,0.7) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -webkit-gradient(linear, left bottom, left top, color-stop(40%,rgba(0,0,0,0.5)), color-stop(100%,rgba(0,0,0,0.7)), color-stop(101%,rgba(0,0,0,0.7))), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -webkit-linear-gradient(bottom, rgba(0,0,0,0.5) 40%,rgba(0,0,0,0.7) 100%,rgba(0,0,0,0.7) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -o-linear-gradient(bottom, rgba(0,0,0,0.5) 40%,rgba(0,0,0,0.7) 100%,rgba(0,0,0,0.7) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
				<div class="modal-header">
						<div style='font-size:0.7em;'>Experience Details</div><div style='font-weight:300;'><?php echo $game->_title;?></div>
				</div>
			</div>	
			<div class="modal-content-container">
				<?php
					ShowXPSelector($xp);
				?>
			</div>		
		</div>
	</div>
	<?php
}

function ShowXPSelector($xp){
	?>
	<ul class="collapsible tier-modal-collapsible-container" data-collapsible="accordion">
		<li>
			<div class="collapsible-header xp-modal-header"><i class="material-icons tier-modal-icon">gamepad</i>Add a <b>played</b> experience</div>
			<div class="collapsible-body">
				<?php 
				if(sizeof($xp->_playedxp) > 0){
					$temp = $xp->_playedxp[0];
					$subexp = new SubExperience($temp->_id."a",'','',$xp->_game->_id,'','','','','','','','',$temp->_date,$temp->_completed,'',$temp->_platform,$temp->_platformids,'','');
					ShowXPPlayedSelector($xp, $subexp); 
				}else{
					ShowXPPlayedSelector($xp); 
				}?>
			</div>
		</li>
		<li>
			<div class="collapsible-header xp-modal-header"><i class="material-icons tier-modal-icon">visibility</i>Add a <b>watched</b> experience</div>
			<div class="collapsible-body">
				<?php ShowXPWatchedSelector($xp); ?>
			</div>
		</li>
		<li>
			<div class="collapsible-header xp-modal-header"><i class="material-icons tier-modal-icon">format_quote</i>Post your latest <b>thoughts</b></div>
			<div class="collapsible-body">
				<?php ShowXPPostSelector($xp); ?>
			</div>
		</li>
		<?php if(sizeof($xp->_playedxp) > 0 || sizeof($xp->_watchedxp) > 0){
			?>
				<div class="collapsible-history-divider">History</div>
			<?php
		}
		
		if(sizeof($xp->_playedxp) > 0){
			foreach($xp->_playedxp as $played){
			?>
				<li>
					<div class="collapsible-header xp-modal-header"><i class="material-icons tier-modal-icon tierTextColor<?php echo $played->_archivetier; ?>">gamepad</i>Played <?php echo $played->_completed;?>% <div class="collapsible-header-time"><?php echo ConvertTimeStampToRelativeTime($played->_entereddate);?></div></div>
					<div class="collapsible-body">
						<?php ShowXPPlayedSelector($xp, $played); ?>
					</div>
				</li>
			<?php
			}
		}

		if(sizeof($xp->_watchedxp) > 0){
			foreach($xp->_watchedxp as $watched){
				$length = $watched->_length;
    			if($watched->_length == "Watched a speed run"){
    				$icon = "directions_walk";
				}else if($watched->_length == "Watched a complete single player playthrough" || $watched->_length == "Watched a complete playthrough"){
    				$icon = "beenhere";
				}else if($watched->_length == "Watched competitive play"){
					$icon = "headset_mic";
    			}else if($watched->_length == "Watched multiple hours" || $watched->_length == "Watched gameplay" || $watched->_length == "Watched an hour or less"){
    				$icon = "videogame_asset";
    			}else if($watched->_length == "Watched promotional gameplay"){
					$icon = "movie_creation";
				}else if($watched->_length == "Watched a developer diary"){
    				$icon = "class";
    			}else{
					$icon = "theaters";
    			}
			?>
				<li>
					<div class="collapsible-header xp-modal-header"><i class="material-icons tier-modal-icon tierTextColor<?php echo $watched->_archivetier; ?>"><?php echo $icon; ?></i> <?php echo $length; ?> <div class="collapsible-header-time"><?php echo ConvertTimeStampToRelativeTime($watched->_entereddate);?></div></div>
					<div class="collapsible-body">
						<?php ShowXPWatchedSelector($xp, $watched); ?>
					</div>
				</li>
			<?php
			}
		}

		if(sizeof($xp->_postedxp) > 0){
			foreach($xp->_postedxp as $posted){
			?>
				<li>
					<div class="collapsible-header xp-modal-header"><i class="material-icons tier-modal-icon">format_quote</i>Posted <div class="collapsible-header-time"><?php echo ConvertTimeStampToRelativeTime($posted->_entereddate);?></div></div>
					<div class="collapsible-body">
						<?php ShowXPPostSelector($xp, $posted); ?>
					</div>
				</li>
			<?php
			}
		} ?>
	</ul>
	<?php
}

function ShowXPPlayedSelector($xp, $specificPlayed = null){
	ShowEmojiSelector($specificPlayed);
	ShowXPQuote($specificPlayed);
	ShowPercentagePlayed($specificPlayed);
	ShowXPPlatformSelector($xp, $specificPlayed);
	ShowAdvancedOptions($xp, true, $specificPlayed);
	if($specificPlayed != null && $specificPlayed->_archivetier > 0){ ?>
		<div class="save-btn disabled modal-btn-pos save-played-xp" style='margin: 2em 0;' data-xpid='<?php echo $specificPlayed->_id; ?>' data-gameid='<?php echo $xp->_game->_id; ?>'>Update Details</div>
		<div class="cancel-btn modal-btn-pos delete-xp" style='margin: 2em 0;' data-xpid='<?php echo $specificPlayed->_id; ?>' data-gameid='<?php echo $xp->_game->_id; ?>' title='Delete Details'><i class='material-icons' style='margin-top: 5px;'>delete_forever</i></div>
	<?php
	}else{
	?>
		<div class="save-btn disabled modal-btn-pos save-played-xp" style='margin: 2em 0;' data-gameid='<?php echo $xp->_game->_id; ?>'>Save Details</div>
		<div class="cancel-btn modal-btn-pos cancel-xp" style='margin: 2em 0;'>Cancel</div>
	<?php
	}
}

function ShowXPWatchedSelector($xp, $specificPlayed = null){
	ShowEmojiSelector($specificPlayed);
	ShowXPQuote($specificPlayed);
	ShowWatchType($specificPlayed);
	ShowWatchedURL($specificPlayed);
	ShowAdvancedOptions($xp, false, $specificPlayed);
	if($specificPlayed != null && $specificPlayed->_archivetier > 0){ ?>
		<div class="save-btn disabled modal-btn-pos save-watched-xp" style='margin: 2em 0;' data-xpid='<?php echo $specificPlayed->_id; ?>' data-gameid='<?php echo $xp->_game->_id; ?>'>Update Details</div>
		<div class="cancel-btn modal-btn-pos delete-xp" style='margin: 2em 0;' data-xpid='<?php echo $specificPlayed->_id; ?>' data-gameid='<?php echo $xp->_game->_id; ?>' title='Delete Details'><i class='material-icons' style='margin-top: 5px;'>delete_forever</i></div>
	<?php
	}else{
	?>
		<div class="save-btn disabled modal-btn-pos save-watched-xp" style='margin: 2em 0;' data-gameid='<?php echo $xp->_game->_id; ?>'>Save Details</div>
		<div class="cancel-btn modal-btn-pos cancel-xp" style='margin: 2em 0;'>Cancel</div>
	<?php
	}
}

function ShowWatchedURL($specificPlayed = null){ ?>
	<div class="row">
		<div class="input-field  col s10 offset-s1" style='text-align: left;'>
			<input id="watchedurl" class='watchedurl' type="text" <?php if($specificPlayed != null && $specificPlayed->_url != ""){ echo "value='".$specificPlayed->_url."' disabled style='background-color:#ddd;padding: 0 5px;color: gray;'"; } ?> >
			<label for="watchedurl" <?php if($specificPlayed != null && $specificPlayed->_url != ""){ echo "class='active' style='top: 0.5em;'"; } ?>>Watched URL address (http)</label>
		</div>	
	</div>
	<?php
}

function ShowWatchType($specificPlayed = null){
	if($specificPlayed != null && $specificPlayed->_length != ''){
		$length = $specificPlayed->_length;
		if($watched->_length == "Watched a speed run"){
			$icon = "directions_walk";
		}else if($watched->_length == "Watched a complete single player playthrough" || $watched->_length == "Watched a complete playthrough"){
			$icon = "beenhere";
		}else if($watched->_length == "Watched competitive play"){
			$icon = "headset_mic";
		}else if($watched->_length == "Watched multiple hours" || $watched->_length == "Watched gameplay" || $watched->_length == "Watched an hour or less"){
			$icon = "videogame_asset";
		}else if($watched->_length == "Watched promotional gameplay"){
			$icon = "movie_creation";
		}else if($watched->_length == "Watched a developer diary"){
			$icon = "class";
		}else{
			$icon = "theaters";
		}
	}
	?>
	<div class="row">
		<div class="col s10 offset-s1">
			<div class="modal-xp-header" style='margin-bottom:20px;'>What type of video did you watch?</div>
		</div>
		<div class="col s10 offset-s1" style='text-align: left;'>
			<div class="row>">
				<?php $icons[] = "directions_walk"; $types[] = "Watched a speed run";
				$icons[] = "beenhere"; $types[] = "Watched a complete playthrough";
				$icons[] = "headset_mic"; $types[] = "Watched competitive play";
				$icons[] = "videogame_asset"; $types[] = "Watched gameplay";
				$icons[] = "movie_creation"; $types[] = "Watched promotional gameplay";
				$icons[] = "class"; $types[] = "Watched a developer diary";
				$icons[] = "theaters"; $types[] = "Watched trailer(s)";
				$i = 0;
				while($i < 7){ ?>
					<div class="col s12" style="margin-bottom:5px;">
						<input type="radio" id="<?php echo $icons[$i].$specificPlayed->_id;?>" name="watched-radio-<?php echo $specificPlayed->_id; ?>" class="myxp-platforms  <?php if($types[$i] == $length){ echo 'myxp-platform-checked'; } ?>" data-text="<?php echo $types[$i];?>" />
						<label for="<?php echo $icons[$i].$specificPlayed->_id;?>" style='line-height: 15px;height:35px;'><i class='material-icons' style='padding-right: 5px;position: relative;top: -5px;vertical-align: text-top;'><?php echo $icons[$i];?></i> <?php echo $types[$i];?></label>
					</div>
				<?php $i++;
				} ?>
			</div>
		</div>
	</div>
	<?php
}

function ShowXPPostSelector($xp, $subxp = null){
	ShowXPPost(true, $subxp);
	if($subxp != null && $subxp->_archivequote != ''){ ?>
		<div class="cancel-btn modal-btn-pos delete-xp" style='margin: 2em 0;' data-xpid='<?php echo $subxp->_id; ?>' data-gameid='<?php echo $xp->_game->_id; ?>' title='Delete Details'><i class='material-icons' style='margin-top: 5px;'>delete_forever</i></div>
	<?php
	}else{
	?>
		<div class="save-btn disabled modal-btn-pos save-post-xp" style='margin: 2em 0;' data-gameid='<?php echo $xp->_game->_id; ?>'>Post</div>
		<div class="cancel-btn modal-btn-pos cancel-xp" style='margin: 2em 0;'>Cancel</div>
	<?php
	}
}

function ShowAdvancedOptions($xp, $isPlayed, $subxp = null){
	?>
	<div class="row">
		<div class="col s10 offset-s1">
			<div class="modal-xp-header-advanced"><i class="material-icons left" style='margin-top: -1px;'>add</i> Additional Details</div>
			<div class="modal-xp-advanced-options-container">
				<?php ShowDateSelector($xp, $subxp); ?>
				<?php if($isPlayed){ ShowHoursPicker($subxp); } ?>
			</div>
		</div>
	</div>
	<?php
}

function ShowHoursPicker($subxp){ ?>
	<div class="input-field col s12">
		<input id="playedhours" class='playedhours' type="number" <?php if($subxp != null && $subxp->_hours > 0){ echo "value='".$subxp->_hours."'"; } ?> >
		<label for="playedhours" <?php if($subxp != null && $subxp->_hours > 0){ echo "class='active'"; } ?>>Hours Played</label>	
	</div>
	<?php
}

function ShowDateSelector($xp, $subxp = null){
	?>
	<div class="col s12">
		<div class="modal-xp-header">Which year was this experience?</div>
		<select id="myxp-year">
		<?php 
			
			$year = date("Y");
			if($subxp != null){
				$date = explode("-",$subxp->_date);
			}else{
				$date[] = $xp->_game->_year;
			} 
			$releaseyear = $xp->_game->_year;
			$releaseyear = $releaseyear - 5;
			if($xp->_game->_year == 0){
				$officialrelease = "";
				$releaseyear = $year - 5;
			}else{
				$officialrelease =  ConvertDateToLongRelationalEnglish($xp->_game->_released);
			} 
			while($year >= $releaseyear && ($year - $birthyear) > 2){?>
				<option value="<?php echo $year; ?>"  <?php if($date[0] == $year){ echo "selected"; } ?>><?php echo $year; ?> <?php if($year == $xp->_game->_year  && $officialrelease != ''){ echo " - US Release (".$officialrelease.")"; } ?> </option>
			<?php
				$year = $year - 1;
			}
			?>
		</select>
	</div>
	<?php
}

function ShowXPPlatformSelector($xp, $subxp = null){ 
	?>
	<div class="row">
		<div class="col s10 offset-s1">
			<div class="modal-xp-header">Which platform did you play on?</div>
		</div>
		<div class="col s10 offset-s1" style='text-align: left;'>
			<div class="row>">
				<?php $platforms = explode("\n", $xp->_game->_platforms); 
				if($subxp != null && $subxp->_platform != ''){
					$myplatforms = explode("\n", $subxp->_platform);
					$myplatforms = array_filter($myplatforms);
				}else{
					$myplatforms = [];
				}
				$platforms = array_filter($platforms);
				
				foreach($platforms as $platform){ 
					if($platform != ""){ $platform = str_replace(array("\n", "\t", "\r"), '', $platform);
						$checked = "";
						if(sizeof($myplatforms) > 0){
							foreach($myplatforms as $myplatform){
								if(trim($myplatform) != ""){
									if(stristr($platform, str_replace(array("\n", "\t", "\r"), '', $myplatform))){ $checked= 'myxp-platform-checked'; }
								}
							} 
						}else if(sizeof($platforms) == 1){ $checked = 'myxp-platform-checked'; } ?>
						<div class="col s6" style="margin-bottom:5px;">
							<input type="radio" id="<?php echo trim($platform.$subxp->_id);?>" name="platform-radio-<?php echo $subxp->_id; ?>" data-text="<?php echo $platform;?>" class="myxp-platforms  <?php echo $checked; ?>"/>
							<label for="<?php echo trim($platform.$subxp->_id);?>" style='line-height: 15px;'><?php echo $platform;?></label>
						</div>
				<?php 	} 
				} ?>
			</div>
		</div>
	</div>
	<?php
}

function ShowPercentagePlayed($subxp = null){
	?>
	<div class="row">
		<div class="col s10 offset-s1">
			<div class="modal-xp-header">About how much of the game you have played?</div>
		</div>
		<div class="input-field col s10 offset-s1">
			<p class="range-field" style='margin: 1rem 0 0;padding: 0.5rem 0 0;'>
				<input type="range" id="xp-percentage-played-range" class="xp-percentage-played-range" min="0" max="100" <?php if($subxp != null && $subxp->_completed > 0){ echo "value='".$subxp->_completed."'"; }else{ ?> value="0" <?php } ?>/>
			</p>
		</div>
	</div>
	<?php
}

function ShowXPQuote($subxp = null){
	$existing = false;
	if($subxp != null && $subxp->_archivequote != ""){ $existing = true; }
	?>
	<div class="row">
		<div class="input-field col s10 offset-s1">
		<textarea id="myxp-quote" class="materialize-textarea myxp-quote" length="140" maxlength="140" <?php if($existing){?>disabled style='background-color:#ddd;padding: 5px;color: gray;'<?php } ?>><?php if($existing){ echo $subxp->_archivequote; } ?></textarea>
		<label for="myxp-quote" <?php if($existing){ echo "class='active' style='top: 0.5em;'"; } ?> ><?php if($existing){ echo "Summary (disabled)"; }else{ echo "Summarize your experience (not required)"; } ?></label>
		</div>
	</div>
	<?php
}

function ShowXPPost($withSpace = false, $subxp = null){
	$existing = false;
	if($subxp != null && $subxp->_archivequote != ""){ $existing = true; }
	?>
	<div class="row" <?php if($withSpace){ ?> style='margin-top:10px;'<?php } ?>>
		<div class="input-field col s10 offset-s1">
		<textarea id="<?php if($withSpace){ ?>myxp-post<?php }else{ ?>myxp-quote<?php } ?>" class="materialize-textarea myxp-post" length="140" maxlength="140" <?php if($existing){?>disabled style='background-color:#ddd;padding: 5px;color: gray;'<?php } ?>><?php if($existing){ echo $subxp->_archivequote; } ?></textarea>
		<label for="<?php if($withSpace){ ?>myxp-post<?php }else{ ?>myxp-quote<?php } ?>" <?php if($existing){ echo "class='active' style='top: 0.5em;'"; } ?> ><?php if($existing){ echo "Post (disabled)"; }else{ echo "Enter your post here"; } ?></label>
		</div>
	</div>
	<?php
}

function ShowEmojiSelector($subxp = null){
	?>
	<div class="row">
		<div class="col s10 offset-s1">
			<div class="modal-xp-header">How was the overall experience?</div>
		</div>
		<div class="col s2 offset-s1 modal-xp-emoji-icon tierTextColor5 <?php if($subxp != null && $subxp->_archivetier == 5){?>modal-xp-emoji-icon-active<?php } ?>" data-tier="5">
			<i class="material-icons" style='font-size:1em;'>sentiment_very_dissatisfied</i>
		</div>
		<div class="col s2 modal-xp-emoji-icon tierTextColor4 <?php if($subxp != null && $subxp->_archivetier == 4){?>modal-xp-emoji-icon-active<?php } ?>" data-tier="4">
			<i class="material-icons" style='font-size:1em;'>sentiment_dissatisfied</i>
		</div>
		<div class="col s2 modal-xp-emoji-icon tierTextColor3 <?php if($subxp != null && $subxp->_archivetier == 3){?>modal-xp-emoji-icon-active<?php } ?>" data-tier="3">
			<i class="material-icons" style='font-size:1em;'>sentiment_neutral</i>
		</div>
		<div class="col s2 modal-xp-emoji-icon tierTextColor2 <?php if($subxp != null && $subxp->_archivetier == 2){?>modal-xp-emoji-icon-active<?php } ?>" data-tier="2">
			<i class="material-icons" style='font-size:1em;'>sentiment_satisfied</i>
		</div>
		<div class="col s2 modal-xp-emoji-icon tierTextColor1 <?php if($subxp != null && $subxp->_archivetier == 1){?>modal-xp-emoji-icon-active<?php } ?>" data-tier="1">
			<i class="material-icons" style='font-size:1em;'>sentiment_very_satisfied</i>
		</div>
	</div>
	<?php
}

function ShowRankModal($gameid){
	$game = GetGame($gameid);
	?>
	<div class="row">
		<div class="col s12">
			<div class="fixed-close-modal-btn-top"><i class="material-icons" style='font-size: 1.2em;vertical-align: sub;'>arrow_forward</i></div>
			<div class="GameHeaderContainer" style='height:10vh;'>
				<div class="GameHeaderBackground" style="height:10vh;background: -moz-linear-gradient(bottom, rgba(0,0,0,0.5) 40%, rgba(0,0,0,0.7) 100%, rgba(0,0,0,0.7) 101%), url(<?php echo $game->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left bottom, left top, color-stop(40%,rgba(0,0,0,0.5)), color-stop(100%,rgba(0,0,0,0.7)), color-stop(101%,rgba(0,0,0,0.7))), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -webkit-linear-gradient(bottom, rgba(0,0,0,0.5) 40%,rgba(0,0,0,0.7) 100%,rgba(0,0,0,0.7) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;background: -o-linear-gradient(bottom, rgba(0,0,0,0.5) 40%,rgba(0,0,0,0.7) 100%,rgba(0,0,0,0.7) 101%), url(<?php echo $game->_imagesmall; ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
				<div class="modal-header">
						<div style='font-size:0.7em;'>Quick Rank</div><div style='font-weight:300;'><?php echo $game->_title;?></div>
				</div>
			</div>
			<div class="modal-content-container">
				<?php 
					$ranklist = GetSmartRankList($gameid, $_SESSION['logged-in']->_id); 
					ShowRankList($ranklist, $game);
				?>
			</div>
			<div class="modal-save-container">
					<div class="save-btn modal-btn-pos">Save Rank</div>
					<div class="cancel-btn modal-btn-pos">Cancel</div>
			</div>			
		</div>
	</div>
	<?php
}

function ShowRankList($ranklist, $currgame){
	if(sizeof($ranklist) > 0){
		$filter = explode(",", $ranklist[0][3]);
		$count = 1;
		?>
		<div class="modal-rank-filter">
			<?php if(sizeof($filter) > 0){
				 foreach($filter as $filteritem){
				?>
				<div class="modal-rank-filter-item"><?php echo $filteritem; ?></div>
				<?php
				} 
			} ?>
		</div>
		<?php
		foreach($ranklist as $rankitem){
			$game = $rankitem[0];
			$rank = $rankitem[1];
			$tier = $rankitem[2];
			?>
			<div class="modal-rank-group">
				<div class="modal-rank-active-game" <?php if($currgame->_id == $game->_id){ echo "style='display:block;'"; } ?> data-internalrank="<?php echo $count; ?>">
					<div class="modal-rank-item-rank"><?php echo $count; ?></div>
					<div class="modal-rank-item-title"><?php echo $currgame->_title; ?></div>
					<div class="modal-rank-item-subtitle">
					<?php 
						  if($currgame->_year > 0)
							  echo $currgame->_year;
						  else
							  echo "????";
							  
						  $developers = array_filter(explode("\n", $currgame->_developer));
						  if(sizeof($developers) > 0){
						  	echo " <span style='font-weight:500;font-size:1.1em;'>|</span> ";
							echo implode("- ", $developers);
						  }
						  $publishers = array_filter(explode("\n", $currgame->_publisher));
						  if(sizeof($developers) > 0  && sizeof($publishers) > 0)
						  	echo " <span style='font-weight:500;font-size:1.1em;'>|</span> ";
						  if(sizeof($publishers) > 0){
						  	echo implode("- ", $publishers);
						  } 
					?>
					</div>
				</div>
				<?php if($currgame->_id != $game->_id){ ?>
					<div class="modal-rank-item" data-internalrank="<?php echo $count; ?>" data-truerank="<?php echo $rank; ?>">
						<div class="modal-rank-item-insert-btn">
							<div class="row modal-rank-item-hover-col-title"><div class="modal-rank-item-arrow"></div>INSERT</div>
						</div>
						<div class="modal-rank-item-rank"><?php echo $count; ?></div>
						<div class="modal-rank-item-truerank">#<?php echo $rank; ?></div>
						<div class="modal-rank-item-title"><?php echo $game->_title; ?></div>
						<div class="modal-rank-item-subtitle">
						<?php 
							if($game->_year > 0)
								echo $game->_year;
							else
								echo "????";
								
							$developers = array_filter(explode("\n", $game->_developer));
							if(sizeof($developers) > 0){
								echo " <span style='font-weight:500;font-size:1.1em;'>|</span> ";
								echo implode("- ", $developers);
							}
							$publishers = array_filter(explode("\n", $game->_publisher));
							if(sizeof($developers) > 0  && sizeof($publishers) > 0)
								echo " <span style='font-weight:500;font-size:1.1em;'>|</span> ";
							if(sizeof($publishers) > 0){
								echo implode("- ", $publishers);
							} 
						?>
						</div>
						<div class="divider" style='margin-top: 5px;'></div>
					</div>
				<?php } ?>
			</div>
			
			<?php
			$count++;
		}
	}
}

/*
*
* OLD STUFF
*
*/


function ShowMyNewXP($gameid, $playedorwatched, $editid){
	$exp = GetExperienceForUserByGame($_SESSION['logged-in']->_id, $gameid);
	ShowTierQuote($exp, $gameid, false);
	
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
	        <textarea id="myxp-quote" class="materialize-textarea" onkeyup="countChar(this)" maxlength="140"></textarea>
	        <label for="myxp-quote" <?php if($exp->_quote != ""){ echo "class='active'"; } ?> >Enter a summary of your experience here (optional)</label>
	        <div class="myxp-quote-counter"><span id='charNum'>0</span>/140</div>
	      </div>
	    </div>
        <?php if($exp->_quote != ''){ ?>
	        <div class="col offset-s1 s10">
        		<div class="collection-myxp-last-quote" style='margin-bottom: 100px;margin-top: -20px;font-size: 1em;'>
        			<div style='font-weight:500;'>Last time:</div>
        			<i><?php echo $exp->_quote; ?></i>
        		</div>
    	    </div>
    	<?php } ?>
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
		  <label>Year Last Played</label>
		  <select id="myxp-year">
			<?php 
				$date = explode('-',$watchedxp->_date);
				$year = date("Y");  
				$releaseyear = $exp->_game->_year;
				$releaseyear = $releaseyear - 5;
				if($exp->_game->_year == 0){
					$officialrelease = "";
					$releaseyear = $year - 5;
				}else{
					$officialrelease =  ConvertDateToLongRelationalEnglish($exp->_game->_released);
				} 
				while($year >= $releaseyear && ($year - $birthyear) > 2){?>
					<option value="<?php echo $year; ?>"  <?php if($date[0] == $year){ echo "selected"; } ?>><?php echo $year; ?> <?php if($year == $exp->_game->_year  && $officialrelease != ''){ echo " - US Release (".$officialrelease.")"; } ?> </option>
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
		  <label>Year Watched</label>
		  <select id="myxp-year">
			<?php 
				$date = explode('-',$watchedxp->_date);
				$year = date("Y");  
				$releaseyear = $exp->_game->_year;
				$releaseyear = $releaseyear - 5;
				if($exp->_game->_year == 0){
					$officialrelease = "";
					$releaseyear = $year - 5;
				}else{
					$officialrelease =  ConvertDateToLongRelationalEnglish($exp->_game->_released);
				} 
				while($year >= $releaseyear && ($year - $birthyear) > 2){?>
					<option value="<?php echo $year; ?>"  <?php if($date[0] == $year){ echo "selected"; } ?>><?php echo $year; ?> <?php if($year == $exp->_game->_year  && $officialrelease != ''){ echo " - US Release (".$officialrelease.")"; } ?> </option>
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
	        <input id="myxp-form-url" type="text" <?php if($watchedxp->_url != ""){ ?> value="<?php echo htmlentities($watchedxp->_url); ?>" <?php } ?> >
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
	ShowMyXP($myxp, $_SESSION['logged-in']->_id, '', '');
}

function ShowMyXP($exp, $userid, $conn, $mutualconn){ 
	if($userid == $_SESSION['logged-in']->_id)
		$editAccess = true;
	else
		$editAccess = false;
		
	if($conn == ''){
		$conn = GetConnectedToList($_SESSION['logged-in']->_id);
		$mutualconn = GetMutalConnections($_SESSION['logged-in']->_id);
	}
	$user = GetUser($userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $username = $user->_first." ".$user->_last; }else{ $username = $user->_username; } 
	$events = GetEventsForGame($userid, $exp->_game->_id);
	if($editAccess)
		$size = sizeof($events) + 1;
	else
		$size = sizeof($events);
		
	$chunksize = 100 / $size;
	$pos = 0;
	$vertBG = array();
	
	?>
	<div class="col s12 z-depth-1" style='background-color:white;z-index:0;position: relative;<?php if(!$editAccess){ ?>margin-top: 50px;<?php } ?>'> 
		<?php if($editAccess){ 
			$vertBG[] =  "#fff ".($chunksize * $pos)."%";
			$pos++;
		?>
		<div class="row" style='margin-bottom: 30px;z-index:1;'>
			<div class="feed-avatar-col">
			</div>
			<div class="feed-activity-icon-col">
				<div class="myxp-vert-icon z-depth-2" style='background-color:white;padding-top: 3px;'>
					<i class="mdi-content-add" style='font-size:2em;color:rgba(0,0,0,0.7);margin-left: 2px;'></i>
				</div>
			</div>
			<div class="myxp-content-col">
				<?php if(sizeof($exp->_playedxp) > 0){ ?>
		    		<div class="col s12" style='text-align:left;margin-top: 20px;padding: 20px;background-color: white;'>
		    			<span style='font-size:1.5em;font-weight: 400;color:rgba(0,0,0,0.7)'>Post</span>
		    			<div class='btn-flat game-add-played-btn-fast' style='padding: 0 0.5rem;font-size: 1.3em;vertical-align: top;color: #1E88E5;font-weight: bold;margin-bottom:0;'>updates</div>
		    			<span style='font-size:1.5em;font-weight: 400;color:rgba(0,0,0,0.7)'> with your time playing or add a</span>
		    			<div class='btn-flat game-add-watched-btn-fast' style='padding: 0 0.5rem;font-size: 1.3em;vertical-align: top;color: #1E88E5;font-weight: bold;margin-bottom:0;'>watched</div>
		    			<span style='font-size:1.5em;font-weight: 400;color:rgba(0,0,0,0.7)'>experience</span>
		    		</div>
		    	<?php }else{ ?>
		    		<div class="col s12" style='text-align:left;margin-top: 20px;padding: 20px;background-color: white;'>
		    			<span style='font-size:1.5em;font-weight: 400;color:rgba(0,0,0,0.7)'>Add a</span>
		    			<div class='btn-flat game-add-watched-btn-fast' style='padding: 0 0.5rem;font-size: 1.3em;vertical-align: top;color: #1E88E5;font-weight: bold;margin-bottom:0;'>watched</div>
		    			<span style='font-size:1.5em;font-weight: 400;color:rgba(0,0,0,0.7)'>or</span>
		    			<div class='btn-flat game-add-played-btn-fast' style='padding: 0 0.5rem;font-size: 1.3em;vertical-align: top;color: #1E88E5;font-weight: bold;margin-bottom:0;'>played</div>
		    			<span style='font-size:1.5em;font-weight: 400;color:rgba(0,0,0,0.7)'>experience</span>
		    		</div>
		    	<?php } ?>
			</div>
		</div>
		<?php }
		$count = sizeof($events);
		foreach($events as $eventdata){
			$event = $eventdata[0];
			$xp = $eventdata[1];
			
			$agrees = GetAgreesForEvent($event['ID']);
			$agreedcount = array_shift($agrees);
			
			if($event['Tier'] > 0)
				$prevTier = $event['Tier'];
			else if($prevTier != '')
				$event['Tier'] = $prevTier;
			
			if($event['Tier'] == 1)
				$color = "#0A67A3";
			else if($event['Tier'] == 2)
				$color = "#00B25C";
			else if($event['Tier'] == 3)
				$color = "#FF8E00";
			else if($event['Tier'] == 4)
				$color = "#FF4100";
			else if($event['Tier'] == 5)
				$color = "#DB0058";
			

			$vertBG[] =  $color." ".($chunksize * $pos)."%";
			$pos++;
			
			if($event["Event"] == 'QUOTECHANGED'){
			?>
				<div class="row">
					<div class="feed-avatar-col">
					</div>
					<div class="feed-activity-icon-col">
						<?php if($xp != ''){
								DisplayMyXpTierIcon($xp, $event, $xp['Type']);
							 }else{ ?>
								<div class="myxp-vert-icon z-depth-2" style='background-color:white;'>
									<i class="mdi-editor-format-quote quoteflip left tierTextColor<?php echo $event['Tier']; ?>" style='font-size:2em;'></i>
								</div>
						<?php } ?>
					</div>
					<div class="myxp-content-col">
						<div class="col s12 myxp-details-container" <?php if($count == 1){ ?>style='border-bottom:none;'<?php } ?>>
					    	<div class="row" style='padding: 1em 0 0;margin-bottom:0;'>
					    		<div class="col s12 myxp-details-items">
					    			<div class="critic-quote-icon"><i class="mdi-editor-format-quote" style='color:rgba(0,0,0,0.8);'></i></div>
					    			<?php echo $event["Quote"]; ?>
					    			<span class="myxp-when-info"><i class="mdi-action-schedule"></i> Entered <?php echo ConvertTimeStampToRelativeTime($event['Date']);?></span>
					    		</div>
					    	</div>
		    		      	<div class="feed-action-container" style='position: relative;float: right;'>
								<?php if($agreedcount > 0){ ?>
									<div class="feed-horizontal-card" style='float: right;margin-top: 2px;font-size: 0.9em;' >
										<span class='feed-agrees-label agreeBtnCount badge-lives' style='padding: 3px 6px !important;line-height: 35px !important;height: 40px !important;'><?php echo $agreedcount; ?></span>
									</div>
								<?php } ?>
		    		      		<?php if($editAccess){ ?>
		    		      			<div class="btn-flat waves-effect removeEventBtn" data-eventid='<?php echo  $event['ID']; ?>' title='Remove this XP'><i class="mdi-content-remove-circle-outline left" style="vertical-align: sub;"></i></div>
		    		      		<?php } ?>
					      		<?php if($event['URL'] != '' && $_SESSION['logged-in']->_id > 0){ ?>
									<div data-url='<?php echo $event['URL']; ?>' data-gameid='<?php echo $event['GameID']; ?>' class="btn-flat waves-effect watchBtn">WATCH</div>
								<?php } ?>
								<?php if($_SESSION['logged-in']->_id != $user->_id && $event['Quote'] != ''){ ?>
									<div class="btn-flat waves-effect <?php if(in_array($_SESSION['logged-in']->_id, $agrees) || $_SESSION['logged-in']->_id <= 0){ echo "disagreeBtn"; }else{ echo "agreeBtn"; } ?>" data-eventid="<?php echo $event['ID']; ?>" data-agreedwith="<?php echo $userid; ?>" data-gameid="<?php echo $event['GameID']; ?>" data-username="<?php echo $username ?>"><?php if(in_array($_SESSION['logged-in']->_id, $agrees)){ echo "- 1up"; }else if($_SESSION['logged-in']->_id > 0){  echo "+ 1up"; } ?></div>
								<?php } ?>
								<div class="btn-flat waves-effect shareBtn" data-eventid='<?php echo  $event['ID']; ?>'><i class="mdi-social-share left" style="vertical-align: sub;"></i></div>
					      	</div>
				    	</div>
					</div>
				</div>
			<?php 
			}else if($event['Event'] == 'BUCKETLIST'){
				?>
				<div class="row">
					<div class="feed-avatar-col">
					</div>
					<div class="feed-activity-icon-col">
						<div class="myxp-vert-icon z-depth-2 tier0BG">
							<i class="mdi-action-bookmark left" style='font-size:2em;color:white;margin-left:10px;'></i>
						</div>
					</div>
					<div class="myxp-content-col">
						<div class="col s12 myxp-details-container" <?php if($count == 1){ ?>style='border-bottom:none;'<?php } ?>>
					    	<div class="row" style='padding: 1em 0 0;margin-bottom:0;'>
					    		<div class="col s12 myxp-details-items">
									Bookmarked for later
					    			<br><div class="myxp-edit-played btn-flat waves-effect"></div>
					    			<span class="myxp-when-info"><i class="mdi-action-schedule"></i> Entered <?php echo ConvertTimeStampToRelativeTime($event['Date']);?></span>
					    		</div>
					    	</div>
				    	</div>
					</div>
				</div>
			<?php
			}else if($event["Event"] == "TIERCHANGED"){ 
				$tierdata = explode(",",$event['Quote']);
				$before = $tierdata[0];
				$after = $tierdata[1];
			?>
				<div class="row">
					<div class="feed-avatar-col">
					</div>
					<div class="feed-activity-icon-col">
						<div class="myxp-vert-icon z-depth-2" style='background-color:white;'>
							<i class="mdi-action-swap-vert left tierTextColor<?php echo $event['Tier']; ?>" style='font-size:2em;'></i>
						</div>
					</div>
					<div class="myxp-content-col">
						<div class="col s12 myxp-details-container" <?php if($count == 1){ ?>style='border-bottom:none;'<?php } ?>>
					    	<div class="row" style='padding: 1em 0 0;margin-bottom:0;'>
					    		<div class="col s12 myxp-details-items">
						    		<div class="feed-tier-changed-before tierTextColor<?php echo $before; ?>"><div class="feed-tier-changed-label">TIER</div> <?php echo $before; ?></div>
						    		<?php if($before > $after){ ?>
						    		<i class="feed-tier-rising mdi-action-trending-neutral"></i>
						    		<?php }else{ ?>
						    		<i class="feed-tier-falling mdi-action-trending-neutral"></i>
						    		<?php } ?>
						    		<div class="feed-tier-changed-after tierTextColor<?php echo $after; ?>"><div class="feed-tier-changed-label">TIER</div> <?php echo $after; ?></div>
					    			<br><div class="myxp-edit-played btn-flat waves-effect"></div>
					    			<span class="myxp-when-info"><i class="mdi-action-schedule"></i> Entered <?php echo ConvertTimeStampToRelativeTime($event['Date']);?></span>
					    		</div>
					    	</div>
		    		      	<div class="feed-action-container" style='position: relative;float: right;display:none;'>
								<div class="btn-flat waves-effect" data-userid='<?php echo  $userid; ?>'></div>
								<?php if($agreedcount > 0){ ?>
									<div class="feed-horizontal-card" style='float: right;margin-top: 2px;font-size: 0.9em;' >
										<span class='feed-agrees-label agreeBtnCount badge-lives' style='padding: 3px 6px !important;line-height: 35px !important;height: 40px !important;'><?php echo $agreedcount; ?></span>
									</div>
								<?php } ?>
					      	</div>
				    	</div>
					</div>
				</div>
			<?php
			}else if($event["Event"] == 'ADDED' || $event["Event"] == "UPDATE" || $event["Event"] == "FINISHED"){
				?>
				<div class="row" style='margin-bottom: 30px;'>
					<div class="feed-avatar-col">
					</div>
					<div class="feed-activity-icon-col">
							<?php if($xp != ''){
									DisplayMyXpTierIcon($xp, $event, $xp['Type']);
								 }else{ ?>
									<div class="myxp-vert-icon z-depth-2" style='background-color:white;'>
										<i class="mdi-editor-format-quote quoteflip left tierTextColor<?php echo $event['Tier']; ?>" style='font-size:2em;'></i>
									</div>
							<?php } ?>
					</div>
					<div class="myxp-content-col">
						<div class="col s12 myxp-details-container" <?php if($count == 1){ ?>style='border-bottom:none;'<?php } ?>>
					    	<div class="row" style='padding: 1em 0 0;margin-bottom:0;'>
					    		<div class="col s12 myxp-details-items">
					    			<?php if($event["Quote"] == '' && $xp != ''){
					    					echo BuildMyXPSentence($xp, $_SESSION['logged-in']->_id, $xp['ArchiveTier'], $xp['Type']);
					    			}else{ ?>
					    				<div class="critic-quote-icon"><i class="mdi-editor-format-quote" style='color:rgba(0,0,0,0.8);'></i></div>
					    				<?php
					    				echo $event["Quote"]; 
				    				}?>
					    			<span class="myxp-when-info"><i class="mdi-action-schedule"></i> Entered <?php echo ConvertTimeStampToRelativeTime($event['Date']);?></span>
					    		</div>
					    	</div>
		    		      	<div class="feed-action-container" style='position: relative;float: right;'>
								<?php if($agreedcount > 0){ ?>
									<div class="feed-horizontal-card" style='float: right;margin-top: 2px;font-size: 0.9em;' >
										<span class='feed-agrees-label agreeBtnCount badge-lives' style='padding: 3px 6px !important;line-height: 35px !important;height: 40px !important;'><?php echo $agreedcount; ?></span>
									</div>
								<?php } ?>
  		    		      		<?php if($editAccess){ ?>
		    		      			<div class="btn-flat waves-effect removeEventBtn" data-eventid='<?php echo  $event['ID']; ?>' title='Remove this XP'><i class="mdi-content-remove-circle-outline left" style="vertical-align: sub;"></i></div>
		    		      		<?php } ?>
					      		<?php if($event['URL'] != '' && $_SESSION['logged-in']->_id > 0){ ?>
									<div data-url='<?php echo $event['URL']; ?>' data-gameid='<?php echo $event['GameID']; ?>' class="btn-flat waves-effect watchBtn">WATCH</div>
								<?php } ?>
								<?php if($_SESSION['logged-in']->_id != $user->_id && $event['Quote'] != ''){ ?>
									<div class="btn-flat waves-effect <?php if(in_array($_SESSION['logged-in']->_id, $agrees) || $_SESSION['logged-in']->_id <= 0){ echo "disagreeBtn"; }else{ echo "agreeBtn"; } ?>" data-eventid="<?php echo $event['ID']; ?>" data-agreedwith="<?php echo $userid; ?>" data-gameid="<?php echo $event['GameID']; ?>" data-username="<?php echo $username ?>"><?php if(in_array($_SESSION['logged-in']->_id, $agrees)){ echo "- 1up"; }else if($_SESSION['logged-in']->_id > 0){  echo "+ 1up"; } ?></div>
								<?php } ?>
								<div class="btn-flat waves-effect shareBtn" data-eventid='<?php echo  $event['ID']; ?>'><i class="mdi-social-share left" style="vertical-align: sub;"></i></div>
							  </div>
				    	</div>
					</div>
				</div>
				<?php
			}
			$count--;
		}
		?>
			<div class="<?php if($editAccess){ ?>myxp-vert-line<?php }else{ ?>myxp-vert-line-details<?php } ?> tier<?php echo $exp->_tier; ?>BG" style='background: -webkit-linear-gradient(top, <?php echo implode(",",$vertBG); ?>)'></div>
		</div>
		
<?php
}

function DisplayMyXpTierIcon($xp, $event, $type){ 
	if($type == 'Played'){
		if($xp['Completed'] == "101")
			$percent = 100;
		else
			$percent = $xp['Completed'];
			
		if($percent == 100){ ?>
			<div class="myxp-card-icon z-depth-2 tier<?php echo $event['Tier']; ?>BG"  title="<?php echo "Tier ".$event['Tier']." - Completed"; ?>" style='padding-top: 5px;'>
			  	<i class="mdi-hardware-gamepad"></i>
			</div>
	  	<?php }else{ ?>
			<div class="myxp-card-icon z-depth-2">
			  <div class="c100 mini <?php if($event['Tier'] == 1){ echo "tierone"; }else if($event['Tier'] == 2){ echo "tiertwo"; }else if($event['Tier'] == 3){ echo "tierthree"; }else if($event['Tier'] == 4){ echo "tierfour"; }else if($event['Tier'] == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$event['Tier']." - ".$percent."% finished"; ?>" style='background-color:white;font-size: 50px;'>
			  	  <span class='tierTextColor<?php echo $event['Tier']; ?> myxp-tier-in-progress' style='background-color:white;'><i class="mdi-hardware-gamepad"></i></span>
				  <div class="slice">
				    <div class="bar minibar"></div>
				    <div class="fill"></div>
				  </div>
				</div>
			</div>
		<?php
	  	}
	}else if ($type == "Watched"){
  		$percent = 20;
    	$length = "";
		if($xp['Length'] == "Watched a speed run" || $xp['Length'] == "Watched a complete single player playthrough" || $xp['Length'] == "Watched a complete playthrough"){
			$percent = 101;
			$length = $xp['Length'];
		}else if($percent < 100 && ($xp['Length'] == "Watched multiple hours" || $xp['Length'] == "Watched gameplay" || $xp['Length'] == "Watched an hour or less")){
			$percent = 100;
			$length = $xp['Length'];
		}else if($percent < 50 && ($xp['Length'] == "Watched promotional gameplay" || $xp['Length'] == "Watched a developer diary")){
			$percent = 50;
			$length = $xp['Length'];
		}else{
			$length = $xp['Length'];
		}
		
		if($percent == 101){ ?>
	      	<div class="myxp-card-icon z-depth-2 tier<?php echo $event['Tier']; ?>BG" title="<?php echo "Tier ".$event['Tier']." - ".$length; ?>" style='padding-top: 5px;'>
	      		<i class="mdi-action-visibility"></i>
		  	</div>
		<?php }else{ ?>
			<div class="myxp-card-icon z-depth-2">
			  <div class="c100 mini <?php if($event['Tier'] == 1){ echo "tierone"; }else if($event['Tier'] == 2){ echo "tiertwo"; }else if($event['Tier'] == 3){ echo "tierthree"; }else if($event['Tier'] == 4){ echo "tierfour"; }else if($event['Tier'] == 5){ echo "tierfive"; }  ?> p<?php echo $percent; ?>" title="<?php echo "Tier ".$event['Tier']." - ".$length; ?>" style='background-color:white;font-size: 50px;'>
			  	  <span class='tierTextColor<?php echo $event['Tier']; ?> myxp-tier-in-progress' style='background-color:white;'><i class="mdi-action-visibility"></i></span>
				  <div class="slice">
				    <div class="bar minibar"></div>
				    <div class="fill"></div>
				  </div>
				</div>
			</div>
		<?php }
	}else{
		?>
			<div class="myxp-vert-icon z-depth-2" style='background-color:white;'>
				<i class="mdi-editor-format-quote quoteflip left tierTextColor<?php echo $event['Tier']; ?>" style='font-size:2em;'></i>
			</div>
		<?php
	}
}

function BuildMyXPSentence($exp, $userid, $tier, $type){
	$date = explode('-',$exp['Date']);
	if($type == 'Played'){
		if($exp['Completed'] > 0){
			if($exp['Completed'] < 100){
				$completedSentence = "Played ".$exp['Completed']."%";
			}else if($exp['Completed'] == 100){
				$completedSentence = "Finished ";
			}else if($exp['Completed'] == 101){
				$exp['Completed'] = 100;
				$completedSentence = "Played multiple playthroughs ";
			}
		}
		?>
		<div class="myxp-visual-sentence-label"><?php echo $completedSentence; ?> on</div>
		<?php $platforms = str_replace("\n", " ", $exp['Platform']);
			if(sizeof($platforms) == 1){ ?>
				<div class="myxp-visual-sentence-label">
						<?php echo $platforms; ?>
				</div>
		<?php }else{ ?>
			<div class="myxp-visual-sentence-label" style='text-align:center; text-align: center;vertical-align: middle;font-weight: 500;padding:0px;'>
				<?php echo sizeof($platforms); ?>
				<div class="visual-sentence-sublabel">platforms</div>
			</div>
		<?php }
		}else{
			if($exp['Source'] != ""){
				$sentence = $exp['Length']." on ".$exp['Source'] ;
			}else{
				$sentence = $exp['Length'];
			}
			?>
			<div class='myxp-visual-sentence-label'><?php echo $sentence; ?></div>
	<?php } ?>
	<div class="myxp-visual-sentence-label">during</div>
	<div class="myxp-visual-sentence-label"><?php echo $date[0]; ?></div>
	
	<?php
}

