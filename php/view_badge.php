<?php require_once "includes.php";

function DisplayAdminControlsForUser($userid){
	$allbadges = GetAllBadges();
	$userbadges = GetAllBadgeForUserList($userid);
	$user = GetUser($userid);
	?>
	<div class="row">
		<div class="col s12">
			<div class='analyze-card-header'>
				<div class='analyze-card-title'><i class="fa fa-certificate"></i> Admin Badge Management</div>
			</div>
		</div>
		<div class="col s12">
			<?php 
			foreach($allbadges as $badge){
				$active = false;
				if(sizeof($userbadges) > 0){
					if(in_array($badge->_id, $userbadges))
						$active = true;
				}

			?>
				<div class="badge-card z-depth-1">
					<div class="badge-image-container <?php if($active){ ?> badge-active <?php } ?>">
						<img class="badge-preview" src='http://lifebar.io/Images/Badges/<?php echo $badge->_file; ?>'></img>
					</div>
					<div class="badge-details-container">
						<div class="badge-name"><?php echo $badge->_title; ?></div>
						<div class="badge-desc"><?php echo $badge->_description; ?></div>
						<?php if($active){ ?>
							<div class="btn-flat badge-btn badge-remove" data-badgeid='<?php echo $badge->_id; ?>'>Remove</div>
							<?php if($badge->_file == $user->_badge){ ?>
								<div class="btn-flat badge-btn badge-unequip" data-badgeid='<?php echo $badge->_id; ?>' style='left: 5px;right: inherit;'>Unequip</div>
							<?php }else{ ?>
								<div class="btn-flat badge-btn badge-equip" data-badgeid='<?php echo $badge->_id; ?>' style='left: 5px;right: inherit;'>Equip</div>
							<?php } ?>
						<?php }else{ ?>
							<div class="btn-flat badge-btn badge-give" data-badgeid='<?php echo $badge->_id; ?>'>Give</div>
						<?php } ?>
					</div>
				</div>
			<?php
			}
			?>
		</div>
	</div>
	<?php
}
?>