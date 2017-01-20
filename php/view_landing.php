<?php
	function ShowLanding(){ ?>
  <div style='position:fixed;top:0;left:0;right:0;bottom:0;z-index: -1;background: url(http://i.giphy.com/2zakdpTkRX5YI.gif) no-repeat center center fixed;background-size: cover;'>
  </div>
  <div class="row">
    <div class="col s12" style='color:white;'>
      <div style='font-weight: 300;text-align: left;font-size: 2.5em;position:fixed;'><span style='font-weight:bold;'>Life</span>bar</div>
    </div>
    <div class="col s8 offset-s2 m7 offset-m2 l6 offset-l3" style='margin-top:150px;'>
      <div class='landing-intro-text'>
      Lifebar is a social platform to <b>capture</b> & <b>celebrate</b> your life with video games!
      </div>
    </div>
  </div>
  <div class='row' style='margin:0;'>
    <div class="col s12">
      <?php DisplayLandingSignup(); ?>
    </div>
  </div>
<?php }