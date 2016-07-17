<?php 
function DailyForm($game, $user){
	?>
	<div class="row">
		<div class="col s12">
			<div class='analyze-card-header'>
				<div class='analyze-card-title'>Submit your own Daily!</div>
				<div class='analyze-card-sub-title'>Have a great idea for a fun or insightful daily? Submit one and we will take a look</div>
			</div>
		</div>
		<div class="row" style='margin-bottom: 0;'>
			<div class="input-field col s10 offset-s1">
				<textarea id="daily-question" class="materialize-textarea" style='font-size: 1.25em;' maxlength="250"></textarea>
				<label for="daily-question" style='font-weight: 500;'>Question</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s10 offset-s1">
			  <label class="daily-type" style='position: relative;float: left;font-weight: 500;left: 0;'>Type of response</label>
			  <div class="myxp-form-select-item" style="display:inline-block;float: left;">
		  	    <input name="typeofresponse" class="with-gap" type="radio" id="typeofresponseradio" data-type='radio' checked />
			    <label for="typeofresponseradio">Single-pick (radio)</label>
		  	  </div>
			  <div class="myxp-form-select-item" style="display:inline-block;float: left;">
		  	    <input name="typeofresponse" class="with-gap" type="radio" id="typeofresponsedropdown" data-type='dropdown' />
			    <label for="typeofresponsedropdown">Single-pick (dropdown)</label>
		  	  </div>
			  <div class="myxp-form-select-item"style="display:inline-block;float: left;">
		  	    <input name="typeofresponse" class="with-gap" type="radio" id="typeofresponsecheckbox" data-type='checkbox' />
			    <label for="typeofresponsecheckbox">Multi-pick (checkbox)</label>
		  	  </div>
			</div>
		</div>
		<div class="row" style='margin-top: 50px;'>
			<div class="input-field col s10 offset-s1">
				<label class="daily-type" style='position: relative;float: left;left: 0;color: #9e9e9e;top: 0.8rem;font-weight: 500;font-size: 1rem;width: 100%;cursor: text;text-align: left;margin-bottom: 30px;'>Responses</label>
			</div>
			<div class="col s10 offset-s1" data-count="1">
				<div class="row">
					<div class="input-field">
				        <input id="daily-response" class='daily-response-items' type="text" value="" >
			        	<label for="daily-response">Response #1</label>
		        	</div>
	        	</div>
	    		<div class='btn daily-add-another' style='float:left;padding: 0 1rem;font-size: 0.8em;height: 28px;line-height: 28px;'>Add another Response</div>
			</div>
		</div>
		<div class="row">
			<div class="col s10 offset-s1">
				<input type="checkbox" id="daily-default">
				<label for="daily-default" style='float:left;'>Make Response #1 the default choice</label>
			</div>
		</div>
		<div class="row">
			<div class="col s10 offset-s1" style='margin-top: 75px;'>
				<div class='btn submit-daily'>Submit Daily For Review</div>
				<div class='btn cancel-daily' style='background-color:#F44336'>Cancel</div>
			</div>
		</div>
	</div>
	<?php
}