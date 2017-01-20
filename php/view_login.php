<?php
function DisplayLogin(){
    	require_once("social_login.php");
    	GoogleOAuth();
    	FacebookOAuth();
	?>
  <div id="loginModal" class="modal" style="background-color:white;overflow-x: hidden;">
    <div class="row loginModalPadding">
	  <div class="col s12 m6 login-modal-divider">
	    <div class="row">
	      <div class="input-field col s11">
	        <i class="mdi-action-account-circle prefix"></i>
	        <input id="username" type="text">
	        <label for="username">Username / Email</label>
	      </div>
      	</div>
      	<div class="row">
  	      <div class="input-field col s11">
	        <i class="mdi-action-lock prefix"></i>
	        <input id="password" type="password">
	        <label for="password">Password</label>
	      </div>
      	</div>
	  	<div class="row">
	      <div class="col s11" style='text-align: center;font-size: 1.5em;'>
	    		<a href="#" class="waves-effect btn-flat" id="LoginSubmitBtn">Login</a>
	      </div>
	      <div class="col s11 validation" style='text-align: center;color:red;display:none;'></div>
	      <div class="col s11 forgotPasswordBtn" style='text-align: center;margin-top:2em;'>
	      	<div class="waves-effect btn-flat s4">Forgot Password</div>
	      </div>
	      <div class="input-field col s11 forgotPassword" style="display:none">
	        <i class="mdi-communication-email prefix"></i>
	        <input id="forgotemail" type="text">
	        <label for="forgotemail">Email to reset password</label>
	    	<a href="#" class="waves-effect btn-flat" id="ForgotLoginSubmitBtn">Send Email</a>
	      </div>
		</div>
  	   </div>
  	   <div class="col s12 m6 social-login-container" >
  	   	<div class="row">
  	   		<div class="modular-social-login-header">
  	   			Sign in using a third party account
  	   		</div>
  	   	</div>
  	   	<div class="row" style='text-align: center;'>
  	   		<div class='social-login-btn twitter-login'>
 				<i class="fa fa-twitter"></i>
  	   		</div>
  	   		<div class="social-login-btn google-login">
  	   			<i class="fa fa-google"></i>
  	   		</div>
   	   		<div class="social-login-btn facebook-login">
  	   			<i class="fa fa-facebook"></i>
  	   		</div>
	   		<!--<div class="social-login-btn steam-login">
  	   			<i class="fa fa-steam"></i>
  	   		</div>-->
  	   	</div>
   	   	<div class="row">
  	   		<div class="col 12">
  	   			<div class="social-validation">
  	   				
  	   			</div>
  	   		</div>
  	   	</div>
  	   </div>
  	</div>
  </div>
	<?php
}

function DisplayLandingSignup(){
	?>
    <div class="row">
		  <div class="col s12 m5 offset-m1" style='border-radius: 10px;background-color: #3F51B5;padding: 30px 0;'>
					<div class="row">
	  	   		<div class="social-login-header" style='color:white;font-size:1.2em;margin:10px 0;'>
	  	   			Sign up for the beta to start building your lifebar today!
	  	   		</div>
	  	   	</div>
		    <div class="row">
		      <div class="input-field col s11" style='text-align:left;color: white;'>
		        <i class="mdi-action-account-circle prefix"></i>
		        <input id="signup_username" type="text">
		        <label for="signup_username" style='color:white;'>Username</label>
		      </div>
	      	</div>
	    	<div class="row">
	  	      <div class="input-field col s11" style='text-align:left;color: white;'>
		        <i class="mdi-action-lock prefix"></i>
		        <input id="signup_password" type="password">
		        <label for="signup_password" style='color:white;'>Password</label>
		      </div>
	      	</div>
	  	    <div class="row">
		      <div class="input-field col s11" style='text-align:left;color: white;'>
		        <i class="mdi-communication-email prefix"></i>
		        <input id="signup_email" type="text">
		        <label for="signup_email" style='color:white;'>Email</label>
		      </div>
	      	</div>
		    <div class="row">
			    <div class="col s11" style='text-align: center;margin-top:1em;'>
			    	<div class="waves-effect btn-large" id="SignupSubmitBtnLanding">SIGN UP</div>
			    </div>
			    <div class="col s11 validation" style='text-align: center;color:white;display:none;margin: 30px 0 10px;'></div>
	      	</div>
	  	</div>
	   <div class="col s12 m5" >
	  	   	<div class="row">
	  	   		<div class="social-login-third-party-header" style='color:white;font-size:1.2em;'>
	  	   			Signup / Login using a third party account
	  	   		</div>
	  	   	</div>
	  	   	<div class="row" style='text-align: center;'>
	  	   		<div class='social-login-btn twitter-login'>
	 				<i class="fa fa-twitter"></i>
	  	   		</div>
	  	   		<div class="social-login-btn google-login">
	  	   			<i class="fa fa-google"></i>
	  	   		</div>
	   	   		<div class="social-login-btn facebook-login">
	  	   			<i class="fa fa-facebook"></i>
	  	   		</div>
	  	   	</div>
	  	   	<div class="row">
	  	   		<div class="col 12">
	  	   			<div class="social-validation">
	  	   				
	  	   			</div>
	  	   		</div>
	  	   	</div>
					 <div class="btn waves-effect waves-light landing-login-mobile" style='z-index:100;background-color: rgba(0,0,0,0.3);'>Login</div>
  	   </div>
  </div>
	<?php
}

function DisplaySignup(){
	?>
  <div id="signupModal" class="modal" style="background-color:white;">
	    <div class="row" style="margin-top:40px;">
			  <div class="col s12 m6 login-modal-divider">
			    <div class="row">
			      <div class="input-field col s11">
			        <i class="mdi-action-account-circle prefix"></i>
			        <input id="signup_username" type="text">
			        <label for="signup_username">Username</label>
			      </div>
		      	</div>
		    	<div class="row">
		  	      <div class="input-field col s11">
			        <i class="mdi-action-lock prefix"></i>
			        <input id="signup_password" type="password">
			        <label for="signup_password">Password</label>
			      </div>
		      	</div>
		  	    <div class="row">
			      <div class="input-field col s11">
			        <i class="mdi-communication-email prefix"></i>
			        <input id="signup_email" type="text">
			        <label for="signup_email">Email</label>
			      </div>
		      	</div>
			    <div class="row">
			    	<div class="col s11">
				    	<div class="row">
				    		<div class="col s3 m1" style='padding: 1em 0em;'>
				    			<i class="mdi-social-cake small"></i>
				    		</div>
					    	<div class="col s9">
					    	  <label style='float:left;font-size:1em;'>Birth Year</label>
					    	  <div style="float: right;font-size: 0.7em;cursor: default;" title="It's not required, but your birth year is used to provide meaningful graphs and simplify entering your gaming experiences.">Why my birth year?</div>
							  <select id="birthyear">
							    <?php for($i = date("Y"); $i > 1930; $i--){ ?>
							    	<option value="<?php echo $i; ?>" <?php if($i == "1983"){ echo "selected"; } ?>><?php echo $i; ?></option>
							    <?php } ?>
							  </select>
							</div>
						</div>
					</div>
				    <div class="col s11" style='text-align: center;font-size: 1.5em;margin-top:1em;'>
				    	<div class="waves-effect btn-large" id="SignupSubmitBtn">SIGN UP</div>
				    </div>
				    <div class="col s11 validation" style='text-align: center;color:red;display:none;'></div>
		      	</div>
		  	</div>
		   <div class="col s12 m6 social-login-container" >
		  	   	<div class="row">
		  	   		<div class="social-login-header">
		  	   			Sign up using a third party account
		  	   		</div>
		  	   	</div>
		  	   	<div class="row" style='text-align: center;'>
		  	   		<div class='social-login-btn twitter-login'>
		 				<i class="fa fa-twitter"></i>
		  	   		</div>
		  	   		<div class="social-login-btn google-login">
		  	   			<i class="fa fa-google"></i>
		  	   		</div>
		   	   		<div class="social-login-btn facebook-login">
		  	   			<i class="fa fa-facebook"></i>
		  	   		</div>
			   		<!--<div class="social-login-btn steam-login">
		  	   			<i class="fa fa-steam"></i>
		  	   		</div>-->
		  	   	</div>
		  	   	<div class="row">
		  	   		<div class="col 12">
		  	   			<div class="social-validation">
		  	   				
		  	   			</div>
		  	   		</div>
		  	   	</div>
	  	   </div>
	  </div>
  </div>
	<?php
}

function DisplayPasswordReset(){
	?>
  <div id="passwordResetModal" class="modal" style="background-color:white;">
    <div class="row" style="margin-top:40px;">
	  <div class="col s12" >
      	<div class="row">
  	      <div class="input-field col s12">
	        <i class="mdi-action-lock prefix"></i>
	        <input id="resetpassword" type="password">
	        <label for="resetpassword">Password Reset</label>
	      </div>
      	</div>
        <div class="row">
  	      <div class="input-field col s12">
	        <i class="mdi-action-lock prefix"></i>
	        <input id="confirmresetpassword" type="password">
	        <label for="confirmresetpassword">Confirm Password Reset</label>
	      </div>
      	</div>
	  	<div class="row">
	      <div class="col s12" style='text-align: center;'>
	    	<a href="#" class="waves-effect btn-flat" id="ResetLoginBtn">Reset & Login</a>
	      </div>
	      <div class="col s12 validation" style='text-align: center;color:red;display:none;'></div>
		</div>
  	   </div>
  	</div>
  </div>
	<?php
}
?>
