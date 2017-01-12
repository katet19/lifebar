<?php
	function ShowLanding(){ ?>
  <div style='position:absolute;top:0;left:0;right:0;bottom:0;z-index: -1;background: url(http://i.giphy.com/2zakdpTkRX5YI.gif) no-repeat center center fixed;background-size: cover;'>
  </div>
  <div class="btn waves-effect waves-light landing-login-mobile" style='z-index:100;background-color: rgba(0,0,0,0.3);'>Login</div>
  <div class="row">
    <div class="col s12" style='color:white;'>
      <h1><span style='font-weight:bold;'>Life</span>bar</h1>
    </div>
  </div>
  <div class='row'>
    <div class="col s12">
      <?php DisplayLandingSignup(); ?>
    </div>
  </div>
<?php }
