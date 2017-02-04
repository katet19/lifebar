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

function DisplayBadgeManagementForUser($userid){
	$userbadges = GetAllBadgesForUser($userid);
	$user = GetUser($userid);
	?>
	<div class="col s12" style='margin-top:1em;padding:0 1rem;'>
		<?php 
		if(sizeof($userbadges) > 0){
			foreach($userbadges as $badge){
			?>
				<div class="badge-card">
					<div class="badge-image-container">
						<img class="badge-preview" src='http://lifebar.io/Images/Badges/<?php echo $badge->_file; ?>'></img>
					</div>
					<div class="badge-details-container">
						<div class="badge-name"><?php echo $badge->_title; ?></div>
						<div class="badge-desc"><?php echo $badge->_description; ?></div>
						<?php if($badge->_file == $user->_badge){ ?>
							<div class="btn-flat badge-btn badge-unequip" data-badgeid='<?php echo $badge->_id; ?>' style='padding: 0 1rem;'>Unequip</div>
						<?php }else{ ?>
							<div class="btn-flat badge-btn badge-equip" data-badgeid='<?php echo $badge->_id; ?>' style='padding: 0 1rem;'>Equip</div>
						<?php } ?>
					</div>
				</div>
			<?php
			}
		}else{
			?>
			<div>
				<img src='http://lifebar.io/Images/Badges/SBadge.svg' style='width:50px;height:50px;padding:5px;filter: grayscale(100%);'>
				<img src='http://lifebar.io/Images/Badges/alpha.png' style='width:50px;height:50px;padding:5px;filter: grayscale(100%);'>
				<img src='http://lifebar.io/Images/Badges/2dcon2016.png' style='width:50px;height:50px;padding:5px;filter: grayscale(100%);'>
			</div>
			<div style=''>Earn badges by using & exploring Lifebar! <br>Come back here to equip them once they have been earned.</div>
		<?php
		}
		?>
	</div>
	<?php
}
?>
