<?php
function DisplayUserCollection($userid){
	$user = GetUser($userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $name = $user->_first." ".$user->_last; }else{ $name = $user->_username; }
	DisplayBackButton($name."'s Collections");
	?>
	<div class="row" style='margin-top:4em;margin-left:2.5%;margin-right:2.5%;text-align:left;'>
		<div class="col s12" style='border-bottom:1px solid rgba(0,0,0,0.4);'>
			<div class="row" style='margin-top:2em;'>
				<div class="col s12 ability-tracking-header">Auto Collections</div>
				<div class="col s12">Automated Collections made by Lifebar. <?php if($userid == $_SESSION['logged-in']->_id){ ?> Auto Collections are created as you add experiences and can be removed at anytime. <?php } ?></div>
				<div class="col s12">
					<?php if(sizeof($collections) > 0){
						foreach($collections as $collection){
							DisplayCollection($collection);	
						}
					}
					
					//check if already an import exists
					?>
						<div class="collection-box collection-empty">
							<i class="fa fa-steam" style='font-size: 1em;margin-right: 10px;'></i> Import your Steam library to quickly build your Lifebar
						</div>
					<?php ?>
					<!--<div class="collection-box collection-add"><i class="mdi-content-add"></i></div>-->
				</div>
				<div class="col s12">
					<div class="btn import-steam"><i class="fa fa-steam" style='font-size: 1em;margin-right: 10px;'></i> Import Steam Library</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row" style='margin-left:2.5%;margin-right:2.5%;text-align:left;'>
		<div class="col s12">
			<div class="row" style='margin-top:2em;'>
				<div class="col s12 ability-tracking-header"><?php if($userid == $_SESSION['logged-in']->_id){ ?>Personal Collections<? }else{ echo $name;?>'s Personal Collections<?php } ?></div>
				<div class="col s12">Customized Collections made by <?php if($userid == $_SESSION['logged-in']->_id){ ?>you<?php }else{ echo $name; } ?>. 	Group games with a common theme or idea into personalized categories.</div>
				<div class="col s12">
					<?php if(sizeof($collections) > 0){
						foreach($collections as $collection){
							DisplayCollection($collection);	
						}
					}?>
					<div class="collection-box collection-add"><i class="mdi-content-add"></i></div>
				</div>
			</div>
		</div>
	</div>
	<!--
	<div class="row" style='margin-top:4em;margin-left:2.5%;margin-right:2.5%;text-align:left;padding-bottom:20px;'>
		<div class="col s12">
			<div class="row" style='margin-top:2em;'>
				<div class="col s12 ability-tracking-header"><?php if($userid == $_SESSION['logged-in']->_id){ ?>Your Followed Collections<? }else{ echo $name;?>'s Followed Collections<?php } ?></div>
				<div class="col s12"><?php if($userid == $_SESSION['logged-in']->_id){ ?>Collections you are currently following from other members. You will recieve notifications when these collections are updated.<?php }else{ echo $name." is following these Collections from other members."; } ?></div>
				<div class="col s12">
					<?php if(sizeof($collections) > 0){
						foreach($collections as $collection){
							DisplayCollection($collection);	
						}
					}else{
						echo "You haven't created any collections yet!";
					}?>
					<div class="collection-box collection-add"><i class="mdi-content-add"></i></div>
				</div>
			</div>
		</div>
	</div>
	-->
	<?php
}
?>