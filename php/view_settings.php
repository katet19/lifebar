<?php
function DisplayUserSettings(){
	?>
	<div id="userSettings" data-id="<?php echo $_SESSION['logged-in']->_id; ?>">
    <div class="row" style=' margin-top: 1em;margin-right:1em;'>
		<div class="col s12 settings-header" style='margin-top:0px;'>Account</div>
	  <form class="col s12">
	    <div class="row">
	      <div class="input-field col s12">
	        <i class="mdi-action-account-circle prefix"></i>
	        <input id="settings_username" type="text" value="<?php echo $_SESSION['logged-in']->_username; ?>">
	        <label for="settings_username" <?php if($_SESSION['logged-in']->_username != ""){ echo "class='active'"; } ?>>Username</label>
	      </div>
      	</div>
    	<div class="row">
  	      <div class="input-field col s12 m12 l6">
	        <i class="mdi-action-lock prefix"></i>
	        <input id="settings_password" type="password">
	        <label for="settings_password">Change Password</label>
	      </div>
  	      <div class="input-field col s12 m12 l6">
	        <i class="mdi-action-lock-outline prefix"></i>
	        <input id="settings_confirm_password" type="password">
	        <label for="settings_confirm_password">Confirm Change Password</label>
	      </div>
      	</div>
  	    <div class="row">
	      <div class="input-field col s12">
	        <i class="mdi-communication-email prefix"></i>
	        <input id="settings_email" type="text" value="<?php echo $_SESSION['logged-in']->_email; ?>">
	        <label for="settings_email"  <?php if($_SESSION['logged-in']->_email != ""){ echo "class='active'"; } ?>>Email</label>
	      </div>
      	</div>
      	<div class="row">
	      <div class="input-field col s12 m12 l6">
	        <i class="mdi-action-perm-contact-cal prefix"></i>
	        <input id="first_name" type="text" value="<?php echo $_SESSION['logged-in']->_first; ?>">
	        <label for="first_name"  <?php if($_SESSION['logged-in']->_first != ""){ echo "class='active'"; } ?>>First Name</label>
	      </div>
  	      <div class="input-field col s12 m12 l6">
	        <i class="mdi-action-perm-contact-cal prefix"></i>
	        <input id="last_name" type="text" value="<?php echo $_SESSION['logged-in']->_last; ?>">
	        <label for="last_name"  <?php if($_SESSION['logged-in']->_last != ""){ echo "class='active'"; } ?>>Last Name</label>
	      </div>
      	</div>
	    <div class="row">
    	  <div class="col s12 settings-header">Profile Image</div>
	      <div class="col s12 m4" style='text-align: center;'>
	  		  <div class="avatar-item">
		  	    <input name="avatargroup" class="with-gap" type="radio" id="gravatar" <?php if($_SESSION['logged-in']->_image == "Gravatar"){ echo "checked"; } ?>  />
			    <label for="gravatar">Use your Profile image from <a href="http://gravatar.com" target="_blank">Gravatar</a></label>
		  	  </div >
    		  <div class="user-avatar" style="width:90px;border-radius:50%;margin-left: 25px;margin-right: auto;height:90px;text-align:left;display:inline-block;background:url(<?php echo get_gravatar($_SESSION['logged-in']->_email); ?>) 50% 25%;z-index:0;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
      	  </div>
  			<div class="col s12 m4">
  	    		  <div class="avatar-item">
			  	    <input name="avatargroup" class="with-gap" type="radio" id="uploaded" <?php if($_SESSION['logged-in']->_image == "Uploaded"){ echo "checked"; } ?> />
				    <label for="uploaded">Upload your own image</a></label>
			  	  </div >
				<div style='color: rgba(0,0,0,0.75);margin-left: 50px;'>
  					<ul>
  						<li style='list-style-type: disc;'>JPGs only</li>
  						<li style='list-style-type: disc;'>Best size: 90 x 90</li>
  					</ul>
  				</div>
      			<iframe src='http://lifebar.io/php/view_imageUploader.php?id=<?php echo $_SESSION['logged-in']->_id; ?>' style='width:100%;border:none;'></iframe>
      		</div>
  			<div class="col s12 m4">
	    		  <div class="avatar-item">
			  	    <input name="avatargroup" class="with-gap" type="radio" id="weburlradio" <?php if($_SESSION['logged-in']->_image != "Gravatar" && $_SESSION['logged-in']->_image != "Uploaded"){ echo "checked"; } ?> />
				    <label for="weburlradio">Use a web URL</a></label>
			  	  </div >
		        <input id="weburl" type="text" value="<?php if($_SESSION['logged-in']->_image != "Gravatar" && $_SESSION['logged-in']->_image != "Uploaded"){ echo $_SESSION['logged-in']->_image; }else{ echo "http://"; } ?>">
		        <label for="weburl"  <?php if($_SESSION['logged-in']->_image != "Gravatar" && $_SESSION['logged-in']->_image != "Uploaded"){ echo "class='active'"; } ?>>Web URL</label>
  			</div>
	      </div>
      	</div>
	    <div class="row" style='margin-right:1em;'>
	    	<div class="col s12 settings-header">Gamertags</div>
	    	<div class="row">
		      <div class="input-field col s12 m6 l4">
		        <i class="mdi-hardware-gamepad prefix"></i>
		        <input id="steam_id" type="text" value="<?php echo $_SESSION['logged-in']->_steam; ?>">
		        <label for="steam_id"  <?php if($_SESSION['logged-in']->_steam != ""){ echo "class='active'"; } ?>>Steam ID</label>
		      </div>
	  	      <div class="input-field col s12 m6 l4">
		        <i class="mdi-hardware-gamepad prefix"></i>
		        <input id="xbox_id" type="text" value="<?php echo $_SESSION['logged-in']->_xbox; ?>">
		        <label for="xbox_id"  <?php if($_SESSION['logged-in']->_xbox != ""){ echo "class='active'"; } ?>>Xbox Live ID</label>
		      </div>
		      <div class="input-field col s12 m6 l4">
		        <i class="mdi-hardware-gamepad prefix"></i>
		        <input id="psn_id" type="text" value="<?php echo $_SESSION['logged-in']->_psn; ?>">
		        <label for="psn_id"  <?php if($_SESSION['logged-in']->_psn != ""){ echo "class='active'"; } ?>>PSN ID</label>
		      </div>
	      	</div>
	      	<?php if($_SESSION['logged-in']->_security == "Authenticated" || $_SESSION['logged-in']->_security == "Journalist"){ ?>
      		<div class="col s12 settings-header">Critic Configuration</div>
		    	<div class="row">
			      <div class="input-field col s12 m6 l4">
			        <i class="mdi-action-wallet-membership prefix"></i>
			        <input id="title_id" type="text" value="<?php echo $_SESSION['logged-in']->_title; ?>">
			        <label for="title_id"  <?php if($_SESSION['logged-in']->_title != ""){ echo "class='active'"; } ?>>Publication / Website Name / Title</label>
			      </div>
  			      <div class="input-field col s12 m6 l4">
			        <i class="mdi-social-domain prefix"></i>
			        <input id="personalweb_id" type="text" value="<?php echo $_SESSION['logged-in']->_website; ?>">
			        <label for="personalweb_id"  <?php if($_SESSION['logged-in']->_website != ""){ echo "class='active'"; } ?>>URL to publication or website</label>
			      </div>
		  	      <div class="input-field col s12 m6 l4">
			        <i class="mdi-social-share prefix"></i>
			        <input id="twitter_id" type="text" value="<?php echo $_SESSION['logged-in']->_twitter; ?>">
			        <label for="twitter_id"  <?php if($_SESSION['logged-in']->_twitter != ""){ echo "class='active'"; } ?>>Twitter Handle</label>
			      </div>
		      	</div>
	      	<?php } ?>
	    	<div class="col s12 settings-header">Lifebar configuration</div>
	    	<div class="col s12 m6">
		    	<div class="row">
		    		<div class="col s1" style='padding: 1em 0.2em;text-align:center;'>
		    			<i class="mdi-social-cake small"></i>
		    		</div>
			    	<div class="col s9 m6">
			    	  <label>Birth year</label>
					  <select id="birthyear" class="browser-default">
					    <?php for($i = date("Y"); $i > 1930; $i--){ ?>
					    	<option value="<?php echo $i; ?>" <?php if($i == $_SESSION['logged-in']->_birthdate){ echo "selected"; } ?>><?php echo $i; ?></option>
					    <?php } ?>
					  </select>
					</div>
				</div>
			</div>
	    	<div class="col s12 m6">
		    	<div class="row">
		    		<div class="col s1" style='padding: 1em 0.2em;text-align:center;'>
		    			<i class="mdi-action-visibility small"></i>
		    		</div>
			    	<div class="col s9 m6">
			    	  <label>Default Watched Source</label>
					  <select id="defaultWatchedSource" class="browser-default">
						<option  value='Destructoid' <?php if($_SESSION['logged-in']->_defaultwatched == 'Destructoid'){ echo "selected"; $found = true; } ?>>Destructoid</option>
						<option  value='Edge' <?php if($_SESSION['logged-in']->_defaultwatched == 'Edge'){echo "selected"; $found = true; } ?>>Edge</option>
						<option  value='EGM' <?php if($_SESSION['logged-in']->_defaultwatched == 'EGM'){echo "selected"; $found = true; } ?>>EGM</option>
						<option  value='Eurogamer' <?php if($_SESSION['logged-in']->_defaultwatched == 'Eurogamer'){echo "selected"; $found = true; } ?>>Eurogamer</option>
						<option  value='Game Informer' <?php if($_SESSION['logged-in']->_defaultwatched == 'Game Informer'){echo "selected"; $found = true; } ?>>Game Informer</option>
						<option  value='Gamesradar' <?php if($_SESSION['logged-in']->_defaultwatched == 'Gamesradar'){echo "selected"; $found = true; } ?>>Gamesradar</option>
						<option  value='Gamespot' <?php if($_SESSION['logged-in']->_defaultwatched == 'Gamespot'){echo "selected"; $found = true; } ?>>Gamespot</option>
						<option  value='Gametrailers' <?php if($_SESSION['logged-in']->_defaultwatched == 'Gametrailers'){echo "selected"; $found = true; } ?>>Gametrailers</option>
                        <option  value='Giant Bomb' <?php if($_SESSION['logged-in']->_defaultwatched == 'Giantbomb' || $_SESSION['logged-in']->_defaultwatched == 'Giant Bomb'){echo "selected"; $found = true; } ?>>Giant Bomb</option>
						<option  value='IGN' <?php if($_SESSION['logged-in']->_defaultwatched == 'IGN'){echo "selected"; $found = true; } ?>>IGN</option>
						<option  value='Joystiq' <?php if($_SESSION['logged-in']->_defaultwatched == 'Gamespot'){echo "selected"; $found = true; } ?>>Joystiq</option>
						<option  value='Other' <?php if($_SESSION['logged-in']->_defaultwatched == 'Other'){echo "selected"; $found = true; } ?>>Other</option>
						<option  value='Polygon' <?php if($_SESSION['logged-in']->_defaultwatched == 'Polygon'){echo "selected"; $found = true; } ?>>Polygon</option>
						<option  value='Twitch' <?php if($_SESSION['logged-in']->_defaultwatched == 'Twitch'){echo "selected"; $found = true; } ?>>Twitch</option>
						<option  value='Watched a Friend' <?php if($_SESSION['logged-in']->_defaultwatched == 'Watched a Friend'){echo "selected"; $found = true; } ?>>Watched a Friend</option>
						<option  value='UStream' <?php if($_SESSION['logged-in']->_defaultwatched == 'UStream'){echo "selected"; $found = true; } ?>>UStream</option>
						<option  value='YouTube' <?php if($_SESSION['logged-in']->_defaultwatched == 'YouTube'){echo "selected"; $found = true; } ?>>YouTube</option>
					  </select>
					</div>
				</div>
			</div>
      	</div>
	  	<div class="row">
	       	<div class="col s12 validation" style='text-align: center;color:red;display:none;'></div>
	  	</div>
	  	<div class="row">
	      <div class="col s12" style='text-align: center;font-size: 1.5em;'>
	    	<a href="#" class="waves-effect btn-flat" id="SaveUserSettingsSubmitBtn">Save</a>
	      </div>
		</div>
  </div>
	
	<?php
}
?>
