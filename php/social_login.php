<?php require_once "includes.php";
	
	//Web Services
	if($_GET['action'] == 'LoginGoogle'){
		GoogleOAuth();
		//$clientsecret = "ACY9DzEM-PBcAeEMOSseXGyX";
		//$clientID = "1042911347563-rfqq1ej3uoobbimv57aingil32l5q4tb.apps.googleusercontent.com";
		//$api = "AIzaSyCN-AViQetgURmPuCj89YsQEtTqezF43wo";
	}
	if($_GET['action'] == 'LoginTwitter'){
		TwitterLogin();
	}
	if($_GET['action'] == 'LoginFacebook'){
		FacebookLogin();
	}
	if($_GET['action'] == 'LoginSteam'){
		SteamLogin();
	}
	
	function GoogleOAuth(){ ?>
		<div id="googleProfile"></div>
		<script type="text/javascript">
		 
		function googleLogout()
		{
		    gapi.auth.signOut();
		    location.reload();
		}
		function googleLogin() 
		{
		  var myParams = {
		    'clientid' : '1042911347563-rfqq1ej3uoobbimv57aingil32l5q4tb.apps.googleusercontent.com',
		    'cookiepolicy' : 'single_host_origin',
		    'callback' : 'googleLoginCallback',
		    'approvalprompt':'force',
		    'scope' : 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
		  };
		  gapi.auth.signIn(myParams);
		}
		 
		function googleLoginCallback(result)
		{
		    if(result['status']['signed_in'])
		    {
		        var request = gapi.client.plus.people.get(
		        {
		            'userId': 'me'
		        });
		        request.execute(function (resp)
		        {
		            var email = '';
		            if(resp['emails'])
		            {
		                for(i = 0; i < resp['emails'].length; i++)
		                {
		                    if(resp['emails'][i]['type'] == 'account')
		                    {
		                        email = resp['emails'][i]['value'];
		                    }
		                }
		            }
		 
		            var str = "Name:" + resp['displayName'] + "<br>";
		            str += "Image:" + resp['image']['url'] + "<br>";
		            str += "<img src='" + resp['image']['url'] + "' /><br>";
		 
		            str += "URL:" + resp['url'] + "<br>";
		            str += "Email:" + email + "<br>";
		            document.getElementById("googleProfile").innerHTML = str;
		        });
		 
		    }
		 
		}
		function googleOnLoadCallback()
		{
		    gapi.client.setApiKey('AIzaSyCN-AViQetgURmPuCj89YsQEtTqezF43wo');
		    gapi.client.load('plus', 'v1',function(){});
		}
		 
		    </script>
		 
		<script type="text/javascript">
		      (function() {
		       var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		       po.src = 'https://apis.google.com/js/client.js?onload=googleOnLoadCallback';
		       var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		     })();
		</script>
	<?php } ?>