<?php

function DisplayTierBarGraph($t1, $t2, $t3, $t4, $t5, $height){
	$total = $t1 + $t2 + $t3 + $t4 + $t5;
	$relativeT1 = ceil($t1 / $total * 100) + 1;
	$relativeT2 = ceil($t2 / $total * 100) + 1;
	$relativeT3 = ceil($t3 / $total * 100) + 1;
	$relativeT4 = ceil($t4 / $total * 100) + 1;
	$relativeT5 = ceil($t5 / $total * 100) + 1;
	?>
	<div class="pwGraphContainer" <?php if($height > 0){ echo "style='height:".$height.";'"; } ?> >
		<div class="pwGraphBarContainer">
			<div class="pwGraphBar tier1BG z-depth-1" style="height:<?php echo $relativeT1; ?>%;left:0;"></div>
			<div class="pwGraphBar tier2BG z-depth-1" style="height:<?php echo $relativeT2; ?>%;left:20%;"></div>
			<div class="pwGraphBar tier3BG z-depth-1" style="height:<?php echo $relativeT3; ?>%;left:40%;"></div>
			<div class="pwGraphBar tier4BG z-depth-1" style="height:<?php echo $relativeT4; ?>%;left:60%;"></div>
			<div class="pwGraphBar tier5BG z-depth-1" style="height:<?php echo $relativeT5; ?>%;left:80%;"></div>
		</div>
		<div class="pwGraphBarLabelContainer">
			<div class="pwGraphLabel" style="left:0;">Tier 1</div>
			<div class="pwGraphLabel" style="left:20%;">Tier 2</div>
			<div class="pwGraphLabel" style="left:40%;">Tier 3</div>
			<div class="pwGraphLabel" style="left:60%;">Tier 4</div>
			<div class="pwGraphLabel" style="left:80%;">Tier 5</div>
		</div>
	</div>
	<?php
}
?>