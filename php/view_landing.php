<?php
	function ShowLanding(){ ?>
  <div style='position:absolute;top:0;left:0;right:0;bottom:0;z-index:-1;opacity:0.5;'>
  </div>
  <div class="btn waves-effect waves-light landing-login-mobile" style='z-index:100;background-color: rgba(0,0,0,0.3);'>Login</div>
  <div class="row">
    <div class="col s12">
      <h1>Welcome to Lifebar</h1>
    </div>
  </div>
  <div class='row'>
    <div class="col s12">
      <?php DisplayLandingSignup(); ?>
    </div>
  </div>
<?php }
