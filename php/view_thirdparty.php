<?php
function LoadThirdPartyTools(){
	//LoadGoogleAnalytics();
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
		FreshWidget.init("", {"queryString": "&widgetType=popup&formTitle=Feedback+%26+Support&helpdesk_ticket[requester]=<?php echo $myuser->_email; ?>", "widgetType": "popup", "buttonType": "text", "buttonText": "Feedback", "buttonColor": "#FFF", "buttonBg": "#474747", "alignment": "2", "offset": "45%", "formHeight": "500px", "url": "https://polygonalweave.freshdesk.com"} );
	</script>
<?php
}

?>