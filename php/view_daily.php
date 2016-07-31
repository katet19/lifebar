<?php 

function ShowFormResults($formid){
	$results = GetFormResults($formid);
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
			$legend[$i] = [$item['TOTAL'], $item['Choice'], $colors[$i][0] ];
			$i++;
		}
	}?>
	></canvas>
	<div class="analyze-doughnut-key">
		<div class="analyze-doughnut-header" style='color:white;'>
			Overall results from Reflection Point
		</div>
		<?php foreach($legend as $ref){ ?>
			<div class="analyze-doughnut-item">
				<div class="analyze-doughnut-block" style='background-color:<?php echo $ref[2]; ?>;'></div>
				<div class="analyze-doughnut-desc" style='color:white;'><?php echo $ref[1]; ?> - <?php echo round(($ref[0] / $results['TOTAL']) * 100); ?>%</div>
			</div>
		<?php } ?>
	</div>
	<?php
}

function GetRandomColors($random){
	$colors = array();
	$colors[] = ["#F44336","#EF5350"];
	$colors[] = ["#9C27B0","#AB47BC"];
	$colors[] = ["#3F51B5","#5C6BC0"];
	$colors[] = ["#2196F3","#42A5F5"];
	$colors[] = ["#009688","#26A69A"];
	$colors[] = ["#4CAF50","#66BB6A"];

	$colors[] = ["#CDDC39","#D4E157"];
	$colors[] = ["#FFEB3B","#FFF176"];
	$colors[] = ["#FFC107","#FFCA28"];
	$colors[] = ["#FF9800","#FFA726"];
	$colors[] = ["#FF5722","#FF7043"];
	$colors[] = ["#795548","#8D6E63"];
	$colors[] = ["#607D8B","#78909C"];
	
	$colors[] = ["#E91E63","#EC407A"];
	$colors[] = ["#00BCD4","#26C6DA"];
	$colors[] = ["#673AB7","#7E57C2"];
	$colors[] = ["#03A9F4","#29B6F6"];
	$colors[] = ["#8BC34A","#9CCC65"];
	/*$colors[] = ["#F44336","#EF5350"];
	$colors[] = ["#F44336","#EF5350"];
	$colors[] = ["#F44336","#EF5350"];
	$colors[] = ["#F44336","#EF5350"];
	$colors[] = ["#F44336","#EF5350"];
	$colors[] = ["#F44336","#EF5350"];
	$colors[] = ["#F44336","#EF5350"];
	$colors[] = ["#F44336","#EF5350"];
	$colors[] = ["#F44336","#EF5350"];
	$colors[] = ["#F44336","#EF5350"];
	$colors[] = ["#F44336","#EF5350"];*/
	
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
