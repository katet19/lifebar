<?php
function DisplayLargeMilestone($milestone){ 
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
<div class="badge-large" data-id="<?php echo $milestone->_id; ?>" >
  <div class="c100 <?php if($currentlevel == 1){ echo "one"; }else if($currentlevel == 2){ echo "two"; }else if($currentlevel == 3){ echo "three"; }else if($currentlevel == 4){ echo "four"; }else if($currentlevel == 5){ echo "five"; }  ?> p<?php echo $percent; ?> small">
	  <?php if($currentlevel == 1){ 
	  		if($percent == 100){?>
	  			<span style="background: -moz-linear-gradient(top, rgba(105,105,103,0.75) 0%, rgba(105,105,103,0.75) 100%, rgba(105,105,103,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(105,105,103,0.75)), color-stop(100%,rgba(105,105,103,0.75)), color-stop(101%,rgba(105,105,103,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(105,105,103,0.75) 0%,rgba(105,105,103,0.75) 100%,rgba(105,105,103,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(105,105,103,0.75) 0%,rgba(105,105,103,0.75) 100%,rgba(105,105,103,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $progress; ?>/<?php echo $threshold; ?></span>
	  		<?php }else{ ?>
	  			<span style="background: -moz-linear-gradient(top, rgba(105,105,103,0) 0%, rgba(105,105,103,0.75) 100%, rgba(105,105,103,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(105,105,103,0)), color-stop(100%,rgba(105,105,103,0.75)), color-stop(101%,rgba(105,105,103,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(105,105,103,0) 0%,rgba(105,105,103,0.75) 100%,rgba(105,105,103,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(105,105,103,0) 0%,rgba(105,105,103,0.75) 100%,rgba(105,105,103,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $progress; ?>/<?php echo $threshold; ?></span>
	  		<?php } ?>
	  <?php }else if($currentlevel == 2){
	  		if($percent == 100){?>
	  			<span style="background: -moz-linear-gradient(top, rgba(77,181,60,0.75) 0%, rgba(77,181,60,0.75) 100%, rgba(77,181,60,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(77,181,60,0.75)), color-stop(100%,rgba(77,181,60,0.75)), color-stop(101%,rgba(77,181,60,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(77,181,60,0.75) 0%,rgba(77,181,60,0.75) 100%,rgba(77,181,60,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(77,181,60,0.75) 0%,rgba(77,181,60,0.75) 100%,rgba(77,181,60,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $progress; ?>/<?php echo $threshold; ?></span>
	  		<?php }else{ ?>
	  			<span style="background: -moz-linear-gradient(top, rgba(77,181,60,0) 0%, rgba(77,181,60,0.75) 100%, rgba(77,181,60,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(77,181,60,0)), color-stop(100%,rgba(77,181,60,0.75)), color-stop(101%,rgba(77,181,60,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(77,181,60,0) 0%,rgba(77,181,60,0.75) 100%,rgba(77,181,60,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(77,181,60,0) 0%,rgba(77,181,60,0.75) 100%,rgba(77,181,60,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $progress; ?>/<?php echo $threshold; ?></span>
	  		<?php } ?>
	  <?php }else if($currentlevel == 3){
  	  		if($percent == 100){?>
	  			<span style="background: -moz-linear-gradient(top, rgba(48,123,187,0.75) 0%, rgba(48,123,187,0.75) 100%, rgba(48,123,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(48,123,187,0.75)), color-stop(100%,rgba(48,123,187,0.75)), color-stop(101%,rgba(48,123,187,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(48,123,187,0.75) 0%,rgba(48,123,187,0.75) 100%,rgba(48,123,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(48,123,187,0.75) 0%,rgba(48,123,187,0.75) 100%,rgba(48,123,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $progress; ?>/<?php echo $threshold; ?></span>
	  		<?php }else{ ?>
	  			<span style="background: -moz-linear-gradient(top, rgba(48,123,187,0) 0%, rgba(48,123,187,0.75) 100%, rgba(48,123,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(48,123,187,0)), color-stop(100%,rgba(48,123,187,0.75)), color-stop(101%,rgba(48,123,187,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(48,123,187,0) 0%,rgba(48,123,187,0.75) 100%,rgba(48,123,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(48,123,187,0) 0%,rgba(48,123,187,0.75) 100%,rgba(48,123,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $progress; ?>/<?php echo $threshold; ?></span>
	  		<?php } ?>
	  <?php }else if($currentlevel == 4){
	  		if($percent == 100){?>
	  			<span style="background: -moz-linear-gradient(top, rgba(158,47,187,0.75) 0%, rgba(158,47,187,0.75) 100%, rgba(158,47,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(158,47,187,0.75)), color-stop(100%,rgba(158,47,187,0.75)), color-stop(101%,rgba(158,47,187,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(158,47,187,0.75) 0%,rgba(158,47,187,0.75) 100%,rgba(158,47,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(158,47,187,0.75) 0%,rgba(158,47,187,0.75) 100%,rgba(158,47,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $progress; ?>/<?php echo $threshold; ?></span>
	  		<?php }else{ ?>
	  			<span style="background: -moz-linear-gradient(top, rgba(158,47,187,0) 0%, rgba(158,47,187,0.75) 100%, rgba(158,47,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(158,47,187,0)), color-stop(100%,rgba(158,47,187,0.75)), color-stop(101%,rgba(158,47,187,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(158,47,187,0) 0%,rgba(158,47,187,0.75) 100%,rgba(158,47,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(158,47,187,0) 0%,rgba(158,47,187,0.75) 100%,rgba(158,47,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $progress; ?>/<?php echo $threshold; ?></span>
	  		<?php } ?>	  
  	  <?php }else if($currentlevel == 5){
	  		if($percent == 100){?>
	  			<span style="background: -moz-linear-gradient(top, rgba(230,229,21,0.75) 0%, rgba(230,229,21,0.75) 100%, rgba(230,229,21,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(230,229,21,0.75)), color-stop(100%,rgba(230,229,21,0.75)), color-stop(101%,rgba(230,229,21,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(230,229,21,0.75) 0%,rgba(230,229,21,0.75) 100%,rgba(230,229,21,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(230,229,21,0.75) 0%,rgba(230,229,21,0.75) 100%,rgba(230,229,21,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $progress; ?>/<?php echo $threshold; ?></span>
	  		<?php }else{ ?>
	  			<span style="background: -moz-linear-gradient(top, rgba(230,229,21,0) 0%, rgba(230,229,21,0.75) 100%, rgba(230,229,21,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(230,229,21,0)), color-stop(100%,rgba(230,229,21,0.75)), color-stop(101%,rgba(230,229,21,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(230,229,21,0) 0%,rgba(230,229,21,0.75) 100%,rgba(230,229,21,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(230,229,21,0) 0%,rgba(230,229,21,0.75) 100%,rgba(230,229,21,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $progress; ?>/<?php echo $threshold; ?></span>
	  		<?php } ?>
	  <?php } ?>
	  <div class="slice">
	    <div class="bar"></div>
	    <div class="fill"></div>
	  </div>
	</div>
	<div class="badge-large-name"><?php echo $milestone->_name; ?></div>
	<div class="badge-large-description"><?php echo $milestone->_description; ?></div>
</div>
<?php
}

function DisplaySmallMilestone($milestone){ ?>
<div class="badge-small" data-id="<?php echo $milestone->_id; ?>" >
  <div class="c100 <?php if($milestone->_difficulty == 1){ echo "one"; }else if($milestone->_difficulty == 2){ echo "two"; }else if($milestone->_difficulty == 3){ echo "three"; }else if($milestone->_difficulty == 4){ echo "four"; }else if($milestone->_difficulty == 5){ echo "five"; }  ?> p<?php echo round(($milestone->_progress/$milestone->_threshold)*100); ?> small" title="<?php echo $milestone->_description; ?>">
	  <?php if($milestone->_difficulty == 1){ 
	  		if($milestone->_progress == $milestone->_threshold){?>
	  			<span style="background: -moz-linear-gradient(top, rgba(105,105,103,0.75) 0%, rgba(105,105,103,0.75) 100%, rgba(105,105,103,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(105,105,103,0.75)), color-stop(100%,rgba(105,105,103,0.75)), color-stop(101%,rgba(105,105,103,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(105,105,103,0.75) 0%,rgba(105,105,103,0.75) 100%,rgba(105,105,103,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(105,105,103,0.75) 0%,rgba(105,105,103,0.75) 100%,rgba(105,105,103,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $milestone->_progress; ?>/<?php echo $milestone->_threshold; ?></span>
	  		<?php }else{ ?>
	  			<span style="background: -moz-linear-gradient(top, rgba(105,105,103,0) 0%, rgba(105,105,103,0.75) 100%, rgba(105,105,103,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(105,105,103,0)), color-stop(100%,rgba(105,105,103,0.75)), color-stop(101%,rgba(105,105,103,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(105,105,103,0) 0%,rgba(105,105,103,0.75) 100%,rgba(105,105,103,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(105,105,103,0) 0%,rgba(105,105,103,0.75) 100%,rgba(105,105,103,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $milestone->_progress; ?>/<?php echo $milestone->_threshold; ?></span>
	  		<?php } ?>
	  <?php }else if($milestone->_difficulty == 2){
	  		if($milestone->_progress == $milestone->_threshold){?>
	  			<span style="background: -moz-linear-gradient(top, rgba(77,181,60,0.75) 0%, rgba(77,181,60,0.75) 100%, rgba(77,181,60,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(77,181,60,0.75)), color-stop(100%,rgba(77,181,60,0.75)), color-stop(101%,rgba(77,181,60,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(77,181,60,0.75) 0%,rgba(77,181,60,0.75) 100%,rgba(77,181,60,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(77,181,60,0.75) 0%,rgba(77,181,60,0.75) 100%,rgba(77,181,60,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $milestone->_progress; ?>/<?php echo $milestone->_threshold; ?></span>
	  		<?php }else{ ?>
	  			<span style="background: -moz-linear-gradient(top, rgba(77,181,60,0) 0%, rgba(77,181,60,0.75) 100%, rgba(77,181,60,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(77,181,60,0)), color-stop(100%,rgba(77,181,60,0.75)), color-stop(101%,rgba(77,181,60,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(77,181,60,0) 0%,rgba(77,181,60,0.75) 100%,rgba(77,181,60,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(77,181,60,0) 0%,rgba(77,181,60,0.75) 100%,rgba(77,181,60,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $milestone->_progress; ?>/<?php echo $milestone->_threshold; ?></span>
	  		<?php } ?>
	  <?php }else if($milestone->_difficulty == 3){
  	  		if($milestone->_progress == $milestone->_threshold){?>
	  			<span style="background: -moz-linear-gradient(top, rgba(48,123,187,0.75) 0%, rgba(48,123,187,0.75) 100%, rgba(48,123,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(48,123,187,0.75)), color-stop(100%,rgba(48,123,187,0.75)), color-stop(101%,rgba(48,123,187,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(48,123,187,0.75) 0%,rgba(48,123,187,0.75) 100%,rgba(48,123,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(48,123,187,0.75) 0%,rgba(48,123,187,0.75) 100%,rgba(48,123,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $milestone->_progress; ?>/<?php echo $milestone->_threshold; ?></span>
	  		<?php }else{ ?>
	  			<span style="background: -moz-linear-gradient(top, rgba(48,123,187,0) 0%, rgba(48,123,187,0.75) 100%, rgba(48,123,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(48,123,187,0)), color-stop(100%,rgba(48,123,187,0.75)), color-stop(101%,rgba(48,123,187,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(48,123,187,0) 0%,rgba(48,123,187,0.75) 100%,rgba(48,123,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(48,123,187,0) 0%,rgba(48,123,187,0.75) 100%,rgba(48,123,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $milestone->_progress; ?>/<?php echo $milestone->_threshold; ?></span>
	  		<?php } ?>
	  <?php }else if($milestone->_difficulty == 4){
	  		if($milestone->_progress == $milestone->_threshold){?>
	  			<span style="background: -moz-linear-gradient(top, rgba(158,47,187,0.75) 0%, rgba(158,47,187,0.75) 100%, rgba(158,47,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(158,47,187,0.75)), color-stop(100%,rgba(158,47,187,0.75)), color-stop(101%,rgba(158,47,187,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(158,47,187,0.75) 0%,rgba(158,47,187,0.75) 100%,rgba(158,47,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(158,47,187,0.75) 0%,rgba(158,47,187,0.75) 100%,rgba(158,47,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $milestone->_progress; ?>/<?php echo $milestone->_threshold; ?></span>
	  		<?php }else{ ?>
	  			<span style="background: -moz-linear-gradient(top, rgba(158,47,187,0) 0%, rgba(158,47,187,0.75) 100%, rgba(158,47,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(158,47,187,0)), color-stop(100%,rgba(158,47,187,0.75)), color-stop(101%,rgba(158,47,187,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(158,47,187,0) 0%,rgba(158,47,187,0.75) 100%,rgba(158,47,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(158,47,187,0) 0%,rgba(158,47,187,0.75) 100%,rgba(158,47,187,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $milestone->_progress; ?>/<?php echo $milestone->_threshold; ?></span>
	  		<?php } ?>	  
  	  <?php }else if($milestone->_difficulty == 5){
	  		if($milestone->_progress == $milestone->_threshold){?>
	  			<span style="background: -moz-linear-gradient(top, rgba(230,229,21,0.75) 0%, rgba(230,229,21,0.75) 100%, rgba(230,229,21,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(230,229,21,0.75)), color-stop(100%,rgba(230,229,21,0.75)), color-stop(101%,rgba(230,229,21,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(230,229,21,0.75) 0%,rgba(230,229,21,0.75) 100%,rgba(230,229,21,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(230,229,21,0.75) 0%,rgba(230,229,21,0.75) 100%,rgba(230,229,21,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $milestone->_progress; ?>/<?php echo $milestone->_threshold; ?></span>
	  		<?php }else{ ?>
	  			<span style="background: -moz-linear-gradient(top, rgba(230,229,21,0) 0%, rgba(230,229,21,0.75) 100%, rgba(230,229,21,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(230,229,21,0)), color-stop(100%,rgba(230,229,21,0.75)), color-stop(101%,rgba(230,229,21,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(230,229,21,0) 0%,rgba(230,229,21,0.75) 100%,rgba(230,229,21,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(230,229,21,0) 0%,rgba(230,229,21,0.75) 100%,rgba(230,229,21,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $milestone->_progress; ?>/<?php echo $milestone->_threshold; ?></span>
	  		<?php } ?>
	  <?php } ?>
	  <div class="slice">
	    <div class="bar"></div>
	    <div class="fill"></div>
	  </div>
	</div>
	<div class="badge-small-name"><?php echo $milestone->_name; ?></div>
</div>
<?php
}

function DisplayGearMilestone($milestone){ 
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
<div class="badge-small" data-id="<?php echo $milestone->_id; ?>" data-progid="<?php echo $milestone->_progress->_id; ?>" data-objectid="<?php echo $milestone->_objectid; ?>" >
  <div class="c100 <?php if($currentlevel == 1){ echo "one"; }else if($currentlevel == 2){ echo "two"; }else if($currentlevel == 3){ echo "three"; }else if($currentlevel == 4){ echo "four"; }else if($currentlevel == 5){ echo "five"; }  ?> p<?php echo $percent; ?>" title="<?php echo $milestone->_description; ?>">
  	  <span style="background: -moz-linear-gradient(top, rgba(0,0,0,0) 0%, rgba(0,0,0,0.75) 100%, rgba(0,0,0,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.75)), color-stop(101%,rgba(0,0,0,0.75))), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(0,0,0,0) 0%,rgba(0,0,0,0.75) 100%,rgba(0,0,0,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(0,0,0,0) 0%,rgba(0,0,0,0.75) 100%,rgba(0,0,0,0.75) 101%), url(<?php echo $milestone->_image; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"><?php echo $progress; ?>/<?php echo $threshold; ?></span>
	  <div class="slice">
	    <div class="bar"></div>
	    <div class="fill"></div>
	  </div>
	</div>
	<div class="badge-small-name"><?php echo $milestone->_name; ?></div>
</div>
<?php
}

function DisplayBattleProgressToasts($user, $myprogress, $gameid){
	if(isset($myprogress)){
		foreach($myprogress as $progress){ ?>
		<div class='bp-progress-item'>
			<?php if($progress->_image == ""){ ?>
				<div class='bp-item-image z-depth-1' style='text-align: center;background-color: orange;padding-top: 5px;margin-bottom: 5px;'><i class='bp-item-image-icon material-icons'>flag</i></div>
			<?php }else{ ?>
				<div class='bp-item-image z-depth-1' style='background:url(<?php echo $progress->_image; ?>) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;'></div>
			<?php } ?>
			<div class='bp-progress-item-details'>
				<div class='bp-progress-item-name'><?php  echo $progress->_name;?></div>
				<div class='bp-progress-item-desc'><?php if(stristr($progress->_desc, "develop")){ echo "Developer"; }else if(stristr($progress->_desc, "franchise")){ echo "Franchise"; }else if(stristr($progress->_desc, "platform")){ echo "Platform"; } ?></div>
			</div>
			<div class='bp-progress-item-bar'>
				<?php $before = round(($progress->_oldvalue/$progress->_threshold) * 100); ?>
				<div class='bp-progress-item-bar-before' style='right:<?php echo 100 - $before; ?>%'></div>
				<div class='bp-progress-item-bar-after' style='left:<?php echo $before;?>%;' data-width='<?php echo round(($progress->_newvalue/$progress->_threshold) * 100) - $before; ?>%'></div>
			</div>
			<?php if($progress->_oldlevel != $progress->_newlevel){ ?>
					<div class='bp-progress-item-levelup' data-newlevel='<?php echo $progress->_newlevel; ?>' data-levelup='Yes'>Level <?php echo $progress->_oldlevel; ?> </div>
			<?php }else{ ?>
					<div class='bp-progress-item-levelup'>Level <?php echo $progress->_oldlevel; ?> <span style='font-weight:300;'>(<?php echo $progress->_newvalue." / ".$progress->_threshold; ?>)</span></div>
			<?php } ?>
		</div>
		|**|
	<?php  } 
	} 

	AddSimilarGames($_SESSION['logged-in']->_id, $gameid); 
	/*if(isset($games)){ ?>
		<div class='bp-progress-item'>
			<span style='font-weight: bold;margin-right: 10px;font-size: 2em;vertical-align: middle;'><?php echo sizeof($games); ?></span> game(s) added to your Lifebar Backlog!
		</div>
	<?php }*/
}

function DisplayBattleProgress($user, $myprogress, $gameid){ 
	$shareData = GetShareLink($user->_id, "userxp", $gameid."-".$user->_id); ?>
	<div class='bp-container' data-gameid="<?php echo $gameid; ?>">
		<div class="row bp-top-row">
			<div class='bp-header'>
				<?php DisplayUserLifeBarRound($user, array(), array(), false); ?>
			    <div class="col s12" >
			    	<div class="row bp-share-container">
			    		<div class='bp-share-label'>Share XP:</div>
			  	   		<a href='http://twitter.com/intent/tweet?status=<?php echo $shareData[2]; ?>+<?php echo $shareData[4]; ?>' onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');GAEvent('BPShare', 'Twitter');return false;" class="social-share-btn" target="_blank" style='color:#fff;'>
			 				<i class="fa fa-twitter-square"></i>
			  	   		</a>
			  	   		<a href="https://plus.google.com/share?url=<?php echo $shareData[4]; ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');GAEvent('BPShare', 'Google');return false;" class="social-share-btn" target="_blank" style='color:#fff;' alt="Share on Google+">
			  	   			<i class="fa fa-google-plus-square"></i>
			  	   		</a>
			   	   		<a href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $shareData[4]; ?>&title=<?php echo $share; ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=600');GAEvent('BPShare', 'Facebook');return false;" class="social-share-btn" target="_blank" style='color:#fff;'>
			  	   			<i class="fa fa-facebook-square"></i>
			  	   		</a>
		 	   	   		<a href="http://www.tumblr.com/share?v=3&u=<?php echo $shareData[4]; ?>&t=<?php echo $shareData[2]; ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=800,width=600');GAEvent('BPShare', 'Tumblr');return false;" class="social-share-btn" target="_blank" style='color:#fff;'>
			  	   			<i class="fa fa-tumblr-square"></i>
			  	   		</a>
			    	</div>
			    </div>
			</div>
			<div class="bp-options">
				<div class="btn-flat bp-btn bp-action-close">Close</div>
			</div>
		</div>
		<div class="row" style='margin-top: 25px;display: inline-block;width: 100%;'>
			<div class="bp-progress col s12 m8">
				<?php if(isset($myprogress)){
					foreach($myprogress as $progress){ ?>
					<div class="bp-progress-item">
						<?php if($progress->_image == ""){ ?>
							<div class="bp-item-image z-depth-1" style='text-align: center;background-color: orange;padding-top: 5px;margin-bottom: 5px;'><i class="bp-item-image-icon material-icons">flag</i></div>
						<?php }else{ ?>
							<div class="bp-item-image z-depth-1" style="background:url(<?php echo $progress->_image; ?>) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;"></div>
						<?php } ?>
						<div class="bp-progress-item-details">
							<div class="bp-progress-item-name"><?php  echo $progress->_name;?></div>
							<div class="bp-progress-item-desc"><?php echo $progress->_desc; ?></div>
						</div>
						<div class="bp-progress-item-bar">
							<?php $before = round(($progress->_oldvalue/$progress->_threshold) * 100); ?>
							<div class="bp-progress-item-bar-before" style="right:<?php echo 100 - $before; ?>%"></div>
							<div class="bp-progress-item-bar-after" style="left:<?php echo $before;?>%;" data-width="<?php echo round(($progress->_newvalue/$progress->_threshold) * 100) - $before; ?>%"></div>
						</div>
						<?php if($progress->_oldlevel != $progress->_newlevel){ ?>
								<div class="bp-progress-item-levelup" data-newlevel="<?php echo $progress->_newlevel; ?>" data-levelup="Yes">Level <?php echo $progress->_oldlevel; ?> </div>
						<?php }else{ ?>
								<div class="bp-progress-item-levelup">Level <?php echo $progress->_oldlevel; ?> <span style='font-weight:300;'>(<?php echo $progress->_newvalue." / ".$progress->_threshold; ?>)</span></div>
						<?php } ?>
					</div>
				<?php  } 
				}?>
			</div>
			<?php 
				$games = AddSimilarGames($_SESSION['logged-in']->_id, $gameid); 
				if(isset($games) && sizeof($myprogress) > 0){ ?>
					<div class="bp-similar col s12 m4">
						<div class="bp-quick-header">New Quests Found</div>
		
								<?php foreach($games as $game){ ?>
									<div class="row">
										<div class="col s12 bp-related-quests-image z-depth-1" data-gbid="<?php echo $game->_gbid; ?>" style="background:url(<?php echo $game->_imagesmall; ?>) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;">
											<div class="bp-related-game-title"><?php echo $game->_title; ?></div>
										</div>
									</div>
								<?php
								} ?>
					</div>
				<?php }else if(isset($games)){ ?>
					<div class="bp-similar col s12">
						<div class="bp-quick-header">New Quests Found</div>
						<div class="row">
							<?php foreach($games as $game){ ?>
								<div class="col s12 m5 l3"> 
									<div class="bp-related-quests-image z-depth-1" data-gbid="<?php echo $game->_gbid; ?>" style="width:90%;margin-left:2.5%;margin-right:2.5%;background:url(<?php echo $game->_imagesmall; ?>) 50% 50%;-webkit-background-size: cover;background-size: cover;-moz-background-size: cover;-o-background-size: cover;margin-bottom:5px">
									<div class="bp-related-game-title"><?php echo $game->_title; ?></div>
								</div>
								</div>
							<?php
							} ?>
						</div>
					</div>
				<?php } ?>
			<div class="bp-equip col s12">
				<hr>
				<div class="bp-equip-container">
					<?php DisplayEquipXP($gameid, true); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function DisplayMilestoneAchieved($milestones){
	?>
	<div class="milestone-popup">
		<div class="milestone-header">Milestone Achieved!</div>
		<div class="milestone-containter">
			<?php
				foreach($milestones as $milestone){
					DisplayLargeMilestone($milestone);
				} ?>
		</div>
	</div>
	<?php
}

function DisplayViewMilestoneLink($userid){
	?>
	<div class="badge-view-more z-depth-1">VIEW ALL MILESTONES</div>
	<?php
}

function DisplayMilestonesTree($userid){ ?>
	  <ul class="collapsible popout" data-collapsible="accordion">
  	    <li>
	      <div class="collapsible-header badge-category-header first-badge">Getting Started</div>
	      <div class="collapsible-body badge-category-container"> <?php DisplayMilestonesCategory($userid, "Getting Started"); ?> </div>
	    </li>
	    <li>
	      <div class="collapsible-header badge-category-header">Advanced Techniques</div>
	      <div class="collapsible-body badge-category-container"> <?php DisplayMilestonesCategory($userid, "Advanced Techniques"); ?> </div>
	    </li>
	    <li>
	      <div class="collapsible-header badge-category-header">Expert Player</div>
	      <div class="collapsible-body badge-category-container"> <?php DisplayMilestonesCategory($userid, "Expert Player"); ?> </div>
	    </li>
	    <li>
	      <div class="collapsible-header badge-category-header">Gaming Knowledge</div>
	      <div class="collapsible-body badge-category-container"> <?php DisplayMilestonesCategory($userid, "Gaming Knowledge"); ?> </div>
	    </li>
	    <li>
	      <div class="collapsible-header badge-category-header">Video</div>
	      <div class="collapsible-body badge-category-container"> <?php DisplayMilestonesCategory($userid, "Video"); ?> </div>
	    </li>
	    <li>
	      <div class="collapsible-header badge-category-header">My Lifetime</div>
	      <div class="collapsible-body badge-category-container"> <?php DisplayMilestonesCategory($userid, "My Lifetime"); ?> </div>
	    </li>
	    <li>
	      <div class="collapsible-header badge-category-header">Franchises</div>
	      <div class="collapsible-body badge-category-container"> <?php DisplayMilestonesCategory($userid, "Franchises"); ?> </div>
	    </li>
	    <li>
	      <div class="collapsible-header badge-category-header">Platforms</div>
	      <div class="collapsible-body badge-category-container"> <?php DisplayMilestonesCategory($userid, "Platforms"); ?> </div>
	    </li>
	  </ul>
	<?php
}

function DisplayMilestonesCategory($userid, $category){
	$milestones = GetMilestonesForCategory($userid, $category);
	foreach($milestones as $milestone){
		DisplayLargeMilestone($milestone);
	}
}
?>
