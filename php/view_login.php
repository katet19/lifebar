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
	      <div class="col s11 validation" style='text-align: center;color:red;'></div>
	      <div class="col s11 forgotPasswordBtn" style='text-align: center;margin-top:2em;'>
	      	<div class="waves-effect btn-flat s4" style='color:#212121 !important;'>Forgot Password</div>
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

function DisplayLandingSignup($param){
	echo $param;
	?>
    <div class="row">
		  <div class="col s12 m5 offset-m1 z-depth-3" style='border-radius: 10px;background-color: #303F9F;padding: 30px 0;'>
					<div class="row">
	  	   		<div class="social-login-header" style='color:white;font-size:1.2em;margin:10px 0;'>
						 <?php if(trim($param) == '?autogenerate'){?>
						 		<span style='padding:0 15px;display:inline-block'>Thanks for your time & providing valuable feedback! Hit sign up and get started right away.</span>
						 <?php }else{ ?>
	  	   				Sign up for the beta to start building your lifebar today!
						 <?php } ?>
	  	   		</div>
	  	   	</div>
		    <div class="row">
		      <div class="input-field col s11" style='text-align:left;color: white;'>
		        <i class="mdi-action-account-circle prefix"></i>
		        <input id="signup_username" type="text" value="<?php if(trim($param) == '?autogenerate'){ echo uniqid("Gamer"); } ?>">
		        <label for="signup_username" <?php if(trim($param) == '?autogenerate'){ echo "class='active'"; } ?> style='color:white;'>Username</label>
		      </div>
	      	</div>
	    	<div class="row">
	  	      <div class="input-field col s11" style='text-align:left;color: white;'>
		        <i class="mdi-action-lock prefix"></i>
		        <input id="signup_password" type="password" value="<?php if(trim($param) == '?autogenerate'){ echo "123456"; } ?>">
		        <label for="signup_password" <?php if(trim($param) == '?autogenerate'){ echo "class='active'"; } ?> style='color:white;'>Password</label>
		      </div>
	      	</div>
	  	    <div class="row">
		      <div class="input-field col s11" style='text-align:left;color: white;'>
		        <i class="mdi-communication-email prefix"></i>
		        <input id="signup_email" type="text" value="<?php if(trim($param) == '?autogenerate'){ echo "test_user@notreal.com"; } ?>">
		        <label for="signup_email" <?php if(trim($param) == '?autogenerate'){ echo "class='active'"; } ?> style='color:white;'>Email</label>
		      </div>
	      	</div>
		    <div class="row">
			    <div class="col s11" style='text-align: center;margin-top:1em;'>
			    	<div class="col s12" style='margin-bottom: 10px;color: white;font-size: 0.8em;'>I am over 13 years old and accept the <span class="signup-tos-link" style='cursor:pointer;text-decoration:underline;'>Terms of Use</span></div>
						<div class="waves-effect btn-large" id="SignupSubmitBtnLanding">SIGN UP</div>
			    </div>
			    <div class="col s11 validation" style='text-align: center;color:white;margin: 30px 0 10px;'></div>
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

function DisplayTermsOfService(){
	?>
	<div class="row" style='height:100%;overflow:auto;padding:25px 20px;'>
		<div class="col s12">
			<h5>Terms and conditions of use</h5>
			<h6>By accessing and using lifebar.io and any other site, application or embedded content owned or operated by Lifebar Limited (the “Website”), you accept and agree to be bound by the following terms and conditions (“Terms”):</h6>
			<ol>
				<li><strong>Use</strong>: You may only use the Website in accordance with these Terms. All rights not expressly granted to you in these Terms are reserved by us.</li>
				<li><strong>Responsibility</strong>: You will be responsible for all activity that occurs as a result of your use of the Website. We disclaim any and all liability (including for negligence) for the content, opinions, statements or other information posted to, or use of, the Website by its users.</li>
				<li><strong>Provision of information</strong>: In order to use the services provided on the Website, you must be at least 13 years of age. When you register to use the Website, you agree to provide true, accurate, current and complete information about yourself as prompted by the Website (“Registration Information”), and to maintain and promptly update your Registration Information in order to ensure that it remains true, accurate, current and complete.</li>
				<li><strong>Community policy</strong>: You must be courteous and respectful of others’ opinions, and you must not post unwelcome, aggressive, suggestive or otherwise inappropriate remarks directed at another member of the Website.</li>
				<li><strong>No spam or multiple accounts</strong>: You must not use the Website or encourage others to use the Website to create multiple accounts, deceive or mislead other users, disrupt discussions, game the Website’s mechanics, alter consensus, post spam or otherwise violate our community policy.</li>
				<li><strong>No malicious use</strong>: You agree to access the Website through the interface we provide. You must not use the Website for any malicious means, or abuse, harass, threaten, intimidate or impersonate any other user of the Website. You must not request or attempt to solicit personal or identifying information from another member of the Website.</li>
				<li><strong>Removal of content</strong>: We reserve the right to remove any content posted to the Website which we consider (in our absolute discretion) to be offensive, objectionable, unlawful or otherwise in breach of these Terms.</li>
				<li><strong>Fees</strong>: We may charge subscription fees for the use of certain services offered on the Website (“Fees”). We may change the amount of Fees payable from time to time. We will communicate any changes in Fees, and any changes will only take effect at the end of your current subscription period. If you cancel your account, you will not be entitled to a refund for any Fees you have already paid.</li>
				<li><strong>No illegal use</strong>: You may not use the Website for any unlawful purpose, or post any information that is in breach of any confidentiality obligation, copyright, trade mark or other intellectual property or proprietary rights of any person.</li>
				<li><strong>Intellectual property</strong>: You agree that we own all of the intellectual property rights in the Website. You grant us a non-exclusive, royalty-free, irrevocable, perpetual and sub-licensable right to use, reproduce, distribute and display (including for commercial purposes) on the Website and in other media any content or material that you post on the Website, and any name(s) and/or avatar under which you post such content. Other than this right, we claim no intellectual property rights in relation to the information or content that you upload onto the Website. Any content you post to the Website should be original, and not infringe anyone else’s intellectual property rights. You warrant that you own or are authorised to use and publish any content that you post.</li>
				<li><strong>Indemnity</strong>: You indemnify, and will keep indemnified, us against all forms of liability, actions, proceedings, demands, costs, charges and expenses which we may howsoever incur or be subject to or suffer as a result of the use by you of the Website.</li>
				<li><strong>Amendments</strong>: We reserve the right to amend these Terms at any time, including by changing the amount of any Fees payable for any of our services, and may also add new features that will be subject to these Terms. If these changes are material we will communicate the changes to users, and by continuing to use the Website, you will be taken to have agreed to the changes.</li>
				<li><strong>Third-party applications</strong>: We may provide a platform for third parties’ applications, websites and services to make products and services available to you (“Third Party Applications”) and your use of any Third Party Applications will be subject to their terms of use. You agree that we will not be liable for the behaviour, features or content of any Third Party Applications.</li>
				<li><strong>Termination or suspension of accounts</strong>: If you do not abide by these Terms, we may terminate or suspend your account.</li>
				<li><strong>Technical support and malfunctions</strong>: We will try to promptly address (during normal business hours) all technical issues that arise on the Website. However, we will not be liable for any loss suffered as a result of any partial or total breakdown of the Website or any technical malfunctions.</li>
				<li><strong>Governing law and jurisdiction</strong>: All users of the Website agree that the laws of Minnesota shall govern and apply to these Terms and each user’s use of the Website, and all users submit to the exclusive jurisdiction of the Minnesota courts for any matter or dispute arising in relation to these Terms.</li>
			</ol>
			<br>
			</div>
			<div class="col s12" style='text-align:center'>
				<div class="tos-close-btn btn">Close</div>
			</div>
		</div>
	</div>
	<?php
}

function DisplayPrivacyPolicy(){
	?>
	<div class="row" style='height:100%;overflow:auto;padding:25px 20px;'>
		<div class="col s12">
			<h5>Privacy Policy</h5>
			<ol>
				<li><strong>Your privacy</strong>: Lifebar is committed to ensuring the privacy of your information.</li>
				<li><strong>Application</strong>: This Privacy Policy applies to all personal information submitted by you on <em>lifebar.io</em> (the “Website”) and any information that may be automatically retrieved through your use of the Website.</li>
				<li><strong>Consent</strong>: By accessing and using the Website, you consent to the collection, use, disclosure, storage and processing of your information in accordance with this Privacy Policy.</li>
				<li><strong>Changes to Privacy Policy</strong>: We may amend or update this Privacy Policy from time to time, with or without notice to you. You agree to be bound by the Privacy Policy that is in effect at the time you access and use the Website.</li>
				<li><strong>Personal information</strong>: In order to use particular services that we offer, you may need to submit certain personal information such as your email address, name and date of birth. You may be asked to submit further information from time to time. If you connect your Facebook, Google or Twitter accounts to your account with Lifebar, we will access these accounts to identify which of your friends on those services are also using Lifebar.</li>
				<li><strong>Use of information</strong>: The personal information you provide us will only be used in relation to the services we provide you, to communicate with you in relation to our services or to co-operate with any government, industry or regulatory authorities.</li>
				<li><strong>Disclosure of information</strong>: Unless you have expressly authorised us to do so, we will not disclose your personal information to any third party except where disclosure relates to the purposes for which the information was collected (as stated in paragraph 6 above).</li>
				<li><strong>Access to and updates of information</strong>: You may request at any time to see the personal information that we hold on your behalf or to correct or update any of your personal information (to the extent that you are unable to do so yourself on the Website).</li>
				<li><strong>Storage of information</strong>: We will securely store your personal information in the United States, although you acknowledge and agree that your personal information may be transferred outside of the United States in connection with the services we offer.</li>
				<li><strong>Retention</strong>: We will hold your personal information both before and after the termination or your account, but only for as long as we are lawfully entitled to do so.</li>
				<li><strong>Security</strong>: You must keep any login, password or account information relating to your use of the Website secure at all times, and must immediately notify us of any unauthorized use of such information or any other breach of security. We will not be liable for any loss or damage if you fail to comply with this security obligation.</li>
			</ol>
			<br>
			</div>
			<div class="col s12" style='text-align:center'>
				<div class="tos-close-btn btn">Close</div>
			</div>
		</div>
	</div>
	<?php
}
