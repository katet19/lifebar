<?php
function LoadThirdPartyTools(){
	//LoadGoogleAnalytics();
	//LoadFreshDeskWidget();
}

function LoadGoogleAnalytics(){ ?>
		<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	  ga('create', 'UA-52980217-1', 'auto');
	  ga('send', 'pageview');
	</script>
<?php 	
}

function LoadFreshDeskWidget(){?>
	<script type="text/javascript" src="http://assets.freshdesk.com/widget/freshwidget.js"></script>
	<script type="text/javascript">
	    FreshWidget.init("", {"queryString": "&widgetType=popup", "utf8": "✓", "widgetType": "popup", "buttonType": "text", "buttonText": "Support", "buttonColor": "white", "buttonBg": "#0a67a3", "alignment": "4", "offset": "235px", "formHeight": "500px", "url": "https://lifebar.freshdesk.com"} );
	</script>
<?php
}

?>
