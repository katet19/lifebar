<?php
function DisplayUserCollection($userid){
	$user = GetUser($userid);
	if($user->_security == "Journalist" || $user->_security == "Authenticated"){ $name = $user->_first." ".$user->_last; }else{ $name = $user->_username; }
	DisplayBackButton($name."'s Collections");
	$autocollections = GetPersonalAutoCollections($userid);
	?>
	<div class="row" style='margin-top:4em;margin-left:2.5%;margin-right:2.5%;text-align:left;'>
		<div class="col s12" style='border-bottom:1px solid rgba(0,0,0,0.4);'>
			<div class="row" style='margin-top:2em;'>
				<div class="col s12 ability-tracking-header">Auto Collections</div>
				<div class="col s12">Automated Collections made by Lifebar. <?php if($userid == $_SESSION['logged-in']->_id){ ?> Auto Collections are created as you add experiences and can be removed at anytime. <?php } ?></div>
				<div class="col s12">
					<?php if(sizeof($autocollections) > 0){
						foreach($autocollections as $collection){
							DisplayCollection($collection);	
						}
					}
					?>
				</div>
				<div class="col s12">
					<div class="btn <?php if(AlreadyImportedSteam($userid)){ ?>load-steam-import<?php }else{ ?>import-steam<?php } ?>"><i class="fa fa-steam" style='font-size: 1em;margin-right: 10px;'></i> <?php if(AlreadyImportedSteam($userid)){ ?>Load Steam Import Results<?php }else{ ?>Import Steam Library<?php } ?></div>
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

function DisplayCollection($collection){ 
	if(sizeof($collection->_games) >= 3){
		$one = $collection->_games[0];
		$two = $collection->_games[1];
		$three = $collection->_games[2];
	}else if(sizeof($collection->_games) >= 2){
		$one = $collection->_games[0];
		$two = $collection->_games[1];
	}else{
		$one = $collection->_games[0];
	}?>
	<div class="collection-box-container">
		<div class="collection-box z-depth-2" style="margin-top: 60px;z-index:4;background: -moz-linear-gradient(top, rgba(0,0,0,0.6) 40%, rgba(0,0,0,0.6) 100%, rgba(0,0,0,0.6) 101%), url(<?php echo $one->_imagesmall; ?>) 50% 25%;background: -webkit-gradient(linear, left top, left bottom, color-stop(40%,rgba(0,0,0,0.6)), color-stop(100%,rgba(0,0,0,0.6)), color-stop(101%,rgba(0,0,0,0.6))), url(<?php echo $one->_imagesmall; ?>) 50% 25%;background: -webkit-linear-gradient(top, rgba(0,0,0,0.6) 40%,rgba(0,0,0,0.6) 100%,rgba(0,0,0,0.6) 101%), url(<?php echo $one->_imagesmall; ?>) 50% 25%;background: -o-linear-gradient(top, rgba(0,0,0,0.6) 40%,rgba(0,0,0,0.6) 100%,rgba(0,0,0,0.6) 101%), url(<?php echo $one->_imagesmall; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
			<div class="collection-name">
				<div class="collection-total">
					<?php echo number_format(sizeof($collection->_games)); ?>
				</div>
				<?php echo $collection->_name; ?>
				<div class="collection-desc">
					<?php echo $collection->_desc; ?>
				</div>
			</div>
		</div>
		<?php if($two != ''){ ?>
			<div class="collection-box z-depth-1" style="margin:0;width:180px;height:180px;left:40px;top:-230px;z-index:3;background: url(<?php echo $two->_imagesmall; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		<?php } ?>
		<?php if($three != ''){ ?>
			<div class="collection-box z-depth-1" style="margin:0;width:160px;height:160px;left:80px;top:-430px;z-index:2;background: url(<?php echo $three->_imagesmall; ?>) 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
		<?php } ?>
	</div>
<?php
}
?>
