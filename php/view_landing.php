<?php
	function ShowLanding(){ ?>
  <div style='position:absolute;top:0;left:0;right:0;bottom:0;z-index: -1;background: url(http://i.giphy.com/2zakdpTkRX5YI.gif) no-repeat center center fixed;background-size: cover;'>
  </div>
  <div class="row">
    <div class="col s12" style='color:white;'>
      <h1 style='font-weight:300;'><span style='font-weight:bold;'>Life</span>bar</h1>
    </div>
    <div class="col s8 offset-s2">
      <div style='font-size: 2em;font-weight: 200;color:white;'>
      Lifebar is a soical platform to <b>caputre</b> & <b>celebrate</b> your life with games
      </div>
    </div>
  </div>
  <div class='row' style='position: absolute;bottom: 0;left: 0;right: 0;'>
    <div class="col s12">
      <?php DisplayLandingSignup(); ?>
    </div>
  </div>
<?php }