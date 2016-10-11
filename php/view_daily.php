<?php 

function ShowFormResults($formid, $choices, $gamePage){
	$results = GetFormResults($formid);
	if($results['TOTAL'] > 0){
		?>
		<canvas class="ResultsDougnut analyze-doughnut-relational" 
		data-total="<?php echo $results['TOTAL']; ?>"
		<?php $i=0; $colors = GetRandomColors(false);
		foreach($results['FORMITEMS'] as $item){
			if($item['TOTAL'] > 0){
				echo "data-e".$i."='".$item['TOTAL']."' ";
				echo "data-ed".$i."='".$item['Choice']."' ";
				echo "data-ec".$i."='".$colors[$i][0]."' ";
				echo "data-ech".$i."='".$colors[$i][1]."' ";
				$legend[$i] = [$item['TOTAL'], $item['Choice'], $colors[$i][0], $item['ID'] ];
				$i++;
			}
		}?>
		></canvas>
		<div class="analyze-doughnut-key">
			<?php 
			$i = 0;
			if(sizeof($legend) > 0){
				foreach($legend as $ref){ 
					if($i == 0){ echo "<div style='float:left;'>"; }else if($i == 6){ echo "</div><div style='float:left;'>"; } ?>
					<div class="analyze-doughnut-item">
						<?php if(sizeof($choices) > 0 && in_array($ref[3], $choices)){ ?>
							<i class='fa fa-check' style='color:<?php echo $ref[2]; ?>;display:inline-block;background: rgba(255,255,255,0.8); border-radius: 50%;padding: 1px;' ></i>
						<?php 
						}else{ ?>
							<div class="analyze-doughnut-block" style='background-color:<?php echo $ref[2]; ?>;'></div>
						<?php } ?>
						<div class="analyze-doughnut-desc" <?php if(!$gamePage){ ?>style='color:white;'<?php } ?>>
							<?php echo $ref[1]; ?> - <?php echo round(($ref[0] / $results['TOTAL']) * 100); ?>%
						</div>
					</div>
					<?php 
					$i++;
				}
			}
			 echo "</div>"; ?>
		</div>
		<?php
	}
}

function GetRandomColors($random){
	$colors = array();
	$colors[] = ["#3F51B5","#7E57C2"];
	$colors[] = ["#FF5252","#42A5F5"];
	$colors[] = ["#E91E63","#EC407A"];
	$colors[] = ["#FFEB3B","#FFEE58"];
	$colors[] = ["#009688","#26A69A"];
	$colors[] = ["#FF9800","#FFA726"];
	$colors[] = ["#8BC34A","#9CCC65"];
	$colors[] = ["#00BCD4","#26C6DA"];
	$colors[] = ["#F44336","#EF5350"];
	$colors[] = ["#CDDC39","#D4E157"];
	$colors[] = ["#3F51B5","#5C6BC0"];
	$colors[] = ["#FF5722","#FF7043"];
	$colors[] = ["#4CAF50","#66BB6A"];
	$colors[] = ["#795548","#8D6E63"];
	$colors[] = ["#607D8B","#78909C"];
	$colors[] = ["#03A9F4","#29B6F6"];
	$colors[] = ["#FFC107","#FFCA28"];	
	
	if($random)
		shuffle($colors);
		
	return $colors;
}


function DailyForm($game, $user, $refptid){
	if($refptid > 0){
		$refpt = GetReflectionPoint($refptid);
		$game = GetGame($refpt['ObjectID']);
	}
	?>
	<div class="row" style='overflow:auto;height:100%;'>
		<div class="col s12">
			<div class='analyze-card-header'>
				<div class='analyze-card-title'>Submit your own Reflection Point!</div>
				<div class='analyze-card-sub-title'>Have a great idea for a fun or insightful reflection point? Submit one and we will take a look</div>
			</div>
		</div>
		<div class="row" style='margin-bottom: 0;'>
			<div class="input-field col s10 offset-s1">
				<textarea id="daily-question" class="materialize-textarea" style='font-size: 1.25em;' maxlength="250" data-gameid="<?php echo $game->_id; ?>" data-formid='<?php echo $refpt['ID']; ?>'><?php echo $refpt['Header']; ?></textarea>
				<label for="daily-question" style='font-weight: 500;' <?php if($refpt['Header'] != ''){ echo "class='active'"; } ?>>Question</label>
			</div>
			<div class="input-field col s10 offset-s1">
				<textarea id="daily-subquestion" class="materialize-textarea" maxlength="250"><?php echo $refpt['SubHeader']; ?></textarea>
				<label for="daily-subquestion" style='font-weight: 500;' <?php if($refpt['SubHeader'] != ''){ echo "class='active'"; } ?>>Sub-description (add any extra bits of info)</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s10 offset-s1">
			  <label class="daily-type" style='position: relative;float: left;font-weight: 500;left: 0;'>Type of response</label>
			  <div class="myxp-form-select-item" style="display:inline-block;float: left;">
		  	    <input name="typeofresponse" class="with-gap" type="radio" id="typeofresponseradio" data-type='radio' <?php if($refpt['Items'][0]['Type'] == 'radio' || $refptid == ''){ ?>checked<?php } ?> />
			    <label for="typeofresponseradio">Single-pick (radio)</label>
		  	  </div>
			  <div class="myxp-form-select-item" style="display:inline-block;float: left;">
		  	    <input name="typeofresponse" class="with-gap" type="radio" id="typeofresponsedropdown" data-type='dropdown' <?php if($refpt['Items'][0]['Type'] == 'dropdown'){ ?>checked<?php } ?> />
			    <label for="typeofresponsedropdown">Single-pick (dropdown)</label>
		  	  </div>
			  <div class="myxp-form-select-item"style="display:inline-block;float: left;">
		  	    <input name="typeofresponse" class="with-gap" type="radio" id="typeofresponsesinglegrid" data-type='grid-single' <?php if($refpt['Items'][0]['Type'] == 'grid-single'){ ?>checked<?php } ?> />
			    <label for="typeofresponsesinglegrid">Single-pick (image grid)</label>
		  	  </div>
			  <div class="myxp-form-select-item"style="display:inline-block;float: left;">
		  	    <input name="typeofresponse" class="with-gap" type="radio" id="typeofresponsecheckbox" data-type='checkbox' <?php if($refpt['Items'][0]['Type'] == 'checkbox'){ ?>checked<?php } ?> />
			    <label for="typeofresponsecheckbox">Multi-pick (checkbox)</label>
		  	  </div>
			  <div class="myxp-form-select-item"style="display:inline-block;float: left;">
		  	    <input name="typeofresponse" class="with-gap" type="radio" id="typeofresponsemultigrid" data-type='grid-multi' <?php if($refpt['Items'][0]['Type'] == 'grid-multi'){ ?>checked<?php } ?> />
			    <label for="typeofresponsemultigrid">Multi-pick (image grid)</label>
		  	  </div>
			</div>
		</div>
		<div class="row" style='margin-top: 60px;'>
			<div class="input-field col s8 offset-s1">
				<select id='gbmetadata' class="browser-default">
					<?php
					$gamemeta = GetRelatedDataForGame($game->_gbid);
					foreach($gamemeta as $meta){
						?>
						<option value='<?php echo $meta[1]; ?>'><?php echo $meta[0]; ?></option>
						<?php
					}
					?>
				</select>
			</div>
			<div class="col s2">
				<div class="btn set-current-to-response" style='padding: 0 1rem;font-size: 0.8em;height: 28px;line-height: 28px;'>Add Response</div>
			</div>
		</div>
		<div class="row" style='margin-top: 50px;'>
			<div class="input-field col s10 offset-s1">
				<label class="daily-type" style='position: relative;float: left;left: 0;color: #9e9e9e;top: 0.8rem;font-weight: 500;font-size: 1rem;width: 100%;cursor: text;text-align: left;margin-bottom: 30px;'>Responses</label>
			</div>
			<div class="col s10 offset-s1" data-count="<?php if($refpt != ''){ echo sizeof($refpt['Items']); }else{ echo "1"; } ?>">
				<div class="row">
					<?php if($refpt != ''){
						$count = 1;
						foreach($refpt['Items'] as $respItem){
							?>
								<div class="input-field">
							        <input id="dailyresponse<?php echo $count; ?>" class='daily-response-items <?php if($respItem['ObjID']){ echo "daily-repsonse-items-with-meta"; } ?>' data-existID='<?php echo $respItem['ID']; ?>' type="text" value="<?php echo $respItem['Choice']; ?>" data-meta='<?php echo $respItem['ObjType']."||".$respItem['ObjID']; ?>' >
						        	<label for="dailyresponse<?php echo $count; ?>" <?php if($respItem['Choice'] != ''){ echo "class='active'"; } ?>>Response #<?php echo $count; ?></label>
					        	</div>
								<div class="input-field" style='display:hidden'>
							        <input id="daily-response-url<?php echo $count; ?>" class='daily-response-items-url' type="text" value="<?php echo $respItem['URL']; ?>" >
						        	<label for="daily-response-url<?php echo $count; ?>" <?php if($respItem['URL'] != ''){ echo "class='active'"; } ?>>Response #<?php echo $count; ?> Image URL</label>
					        	</div>
							<?php
							$count++;
						}
					}else{ ?>
						<div class="input-field">
					        <input id="daily-response" class='daily-response-items' type="text" value="" data-meta='' >
				        	<label for="daily-response">Response #1</label>
			        	</div>
						<div class="input-field" style='display:hidden'>
					        <input id="daily-response-url" class='daily-response-items-url' type="text" value="" >
				        	<label for="daily-response-url">Response #1 Image URL</label>
			        	</div>
		        	<?php } ?>
	        	</div>
	    		<div class='btn daily-add-another' style='float:left;padding: 0 1rem;font-size: 0.8em;height: 28px;line-height: 28px;'>Add another Response</div>
			</div>
		</div>
		<div class="row">
			<div class="col s10 offset-s1">
				<input type="checkbox" id="daily-default" <?php if($refpt['Items'][0]['IsDefault'] == "Yes"){ echo "checked"; } ?>>
				<label for="daily-default" style='float:left;'>Make Response #1 the default choice</label>
			</div>
		</div>
		<div class="row">
			<div class="col s10 offset-s1">
				<input type="checkbox" id="daily-finished" <?php if($refpt['Finished'] == "Yes"){ echo "checked"; } ?>>
				<label for="daily-finished" style='float:left;'>Only show for members that have finished the game</label>
			</div>
		</div>
		<div class="row">
			<div class="col s10 offset-s1" style='margin-top: 75px;'>
				<div class='btn <?php if($refpt != ''){ ?>update-daily<?php }else{ ?>submit-daily<?php } ?>'><?php if($refpt != ''){ ?>Update Reflection Point<?php }else{ ?>Submit for review<?php } ?></div>
				<div class='btn cancel-daily' style='background-color:#F44336'>Cancel</div>
				<?php if($refpt != ''){ ?>
					<div class='btn save-as-daily'>Save as New Reflection Point</div>
					<div class='btn-flat delete-daily' style='color:red;'>Delete</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
}

function DisplayGamePageReflectionPoint($item){
	$game = GetGame($item['ObjectID']);
	$showsplrwrng = false;
	if($item['Finished'] == 'Yes'){
		$xp = HasFinished($_SESSION['logged-in']->_id, $game->_id);
		if($xp < 1)
			$showsplrwrng = true;
	}
	
	$choicesMade =  GetFormChoices($_SESSION['logged-in']->_id, $item['ID']);
	$hasResults = HasFormResults($_SESSION['logged-in']->_id, $item['ID']);

	?>
	<div class='row'>
	    <div class="col s12" style='margin: 5px 10px;position:relative;'>
				<div class="refpt-header-question">
					<?php echo $item['Header']; ?> 
				</div>
				<div class="refpt-answers-container" data-type="<?php echo $item['Items'][0]['Type']; ?>">
					<?php if(!$showsplrwrng){ ?>
						<div class="row">
							<div class="col s12" style='text-align:left;'>
								<div class="refpt-header-subquestion-hidden">
									<?php echo $item['SubHeader']; ?>
								</div>
								<?php 
									$imagehorizontal = false;
									$horizontal = false;
									if(sizeof($item['Items']) >= 5 && $item['Items'][0]['Type'] != 'grid-single' && $item['Items'][0]['Type'] != 'grid-multi'){ $horizontal = true; }else if(sizeof($item['Items']) >= 7 && ($item['Items'][0]['Type'] == 'grid-single' || $item['Items'][0]['Type'] == 'grid-multi')){ $imagehorizontal = true; }else{ $horizontal = false; } $first = true;
									foreach($item['Items'] as $response){
										?>
										<div class="daily-item-row input-field <?php if($imagehorizontal){ ?>daily-resp-grid daily-response-item-small<?php }else if($response['Type'] == 'grid-single' || $response['Type'] == 'grid-multi'){ ?>daily-resp-grid daily-response-item-dynm-<?php echo sizeof($item['Items']); } ?>" <?php if($horizontal && $response['Type'] != 'grid-single' && $response['Type'] != 'grid-multi' && $response['Type'] != 'dropdown'){ ?>style='width:40%;display:inline-block;'<?php }else if($horizontal && $response['Type'] == 'dropdown'){ ?>style='width:80%;'<?php } ?> data-objid="<?php echo $response['ObjID']; ?>" data-objtype="<?php echo $response['ObjType']; ?>" data-formitemid="<?php echo $response['ID']; ?>" data-formid="<?php echo $response['FormID']; ?>" data-gameid="<?php echo $game->_id; ?>">
											<?php if($response['Type'] == 'dropdown' && $first){ ?><select id="daily-response-dropdown"><?php } ?>
											<?php if($response['Type'] == 'radio'){ ?>
												<input type='radio' class='with-gap' name="dailyresposne" id="response<?php echo $response['ID']; ?>" <?php if($response['IsDefault'] == 'Yes' || in_array($response['ID'], $choicesMade)){ ?> checked <?php } ?> >
												<label for="response<?php echo $response['ID']; ?>" class="refpt-response-label-radio"><?php echo $response["Choice"]; ?></label>
											<?php }else if($response['Type'] == 'dropdown'){ ?>
												<?php if($response['IsDefault'] == 'No' && $response['Type'] == 'dropdown' && $first){ ?> <option value="Please Select">Please Select</option> <?php } ?>
												<option value="<?php echo $response["ID"]; ?>" <?php if(in_array($response['ID'], $choicesMade)){ echo "selected"; } ?> ><?php echo $response["Choice"]; ?></option>
											<?php }else if($response['Type'] == 'checkbox'){ ?>
												<input type="checkbox" class='response-checkbox' id="response<?php echo $response['ID']; ?>" <?php if($response['IsDefault'] == 'Yes' || in_array($response['ID'], $choicesMade)){ ?> checked <?php } ?> >
												<label for="response<?php echo $response['ID']; ?>" class="refpt-response-label"><?php echo $response["Choice"]; ?></label>
											<?php }else if($response['Type'] == 'grid-single'){ ?>
													<div class="knowledge-container" style='background-color:#FFF;' data-id="<?php echo $response['ID']; ?>">
														<div class="daily-pref-image z-depth-1 singlegrid daily-response-item-dynm-h-<?php echo sizeof($item['Items']); ?> <?php if(in_array($response['ID'], $choicesMade)){ echo "daily-pref-image-active"; } ?>" style="background:url(<?php echo $response['URL']; ?>) 50% 5%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;">
															<i class="daily-checkmark fa fa-check"></i>
															<div class="daily-pref-image-title">
																<?php echo $response["Choice"]; ?>
															</div>
														</div>
													</div>
											<?php }else if($response['Type'] == 'grid-multi'){ ?>
													<div class="knowledge-container" style='background-color:#FFF;' data-id="<?php echo $response['ID']; ?>">
														<div class="daily-pref-image z-depth-1 multigrid daily-response-item-dynm-h-<?php echo sizeof($item['Items']); ?> <?php if(in_array($response['ID'], $choicesMade)){ echo "daily-pref-image-active"; } ?>" style="background:url(<?php echo $response['URL']; ?>) 50% 5%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;">
															<i class="daily-checkmark fa fa-check"></i>
															<div class="daily-pref-image-title">
																<?php echo $response["Choice"]; ?>
															</div>
														</div>
													</div>
											<?php } ?>
										</div>
										<?php
										$first = false;
									}
								?>
								<?php if($response['Type'] == 'dropdown'){ ?></select><?php }?>
								</div>
							<div class="col s12" style='margin-top: 40px;text-align:left;' >
								<?php if($hasResults){ ?>
									<div class='btn submit-refpt-response'>Update</div>
								<?php }else{ ?>
									<div class='btn submit-refpt-response'>Save</div>
								<?php } ?>
								<div class="btn-flat share-refpt-response" data-id='<?php echo $item['ID']; ?>'><i class="mdi-social-share left" style='font-size: 1.5em;'></i> Share</div>
								<?php if($_SESSION['logged-in']->_security == 'Admin'){ ?>
									<span class='btn-flat edit-ref-pt' style='font-weight:500;' data-id='<?php echo $item['ID']; ?>'>Edit Reflection Point</span>
								<?php } ?>
							</div>
						</div>
					<?php }else{ ?>
						<div class="row" style='margin-top:175px;'>
							<div class="col s12" style='text-align:left;'>
								<div class="daily-header-subquestion-hidden" style='font-weight: bold;font-size: 1.5em;text-transform: uppercase;'>
									<i class="mdi-alert-warning" style="color:orangered;font-size: 1.5em;vertical-align: sub;"></i>	
									Spoiler Warning!
								</div>
								<div style='font-size: 1.25em;font-weight: 400;margin-bottom: 40px;margin-top: -25px;'>You haven't finished this game yet. This reflection point will spoil your playthrough until you finish.</div>
								<div class="btn view-game-spoiler" data-id="<?php echo $game->_gbid; ?>">Update your experience now</div>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="refpt-answers-results-container" <?php if(!$hasResults){ ?>style='display:none;'<?php } ?>><?php ShowFormResults($item['ID'], $choicesMade, true); ?></div>
		</div>
	</div>
	<?php
}
