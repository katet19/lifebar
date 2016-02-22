<?php require_once "includes.php";
	
	//Google IDs
	//$clientsecret = "ACY9DzEM-PBcAeEMOSseXGyX";
	//$clientID = "1042911347563-rfqq1ej3uoobbimv57aingil32l5q4tb.apps.googleusercontent.com";
	//$api = "AIzaSyCN-AViQetgURmPuCj89YsQEtTqezF43wo";
	
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
		    'scope' : 'profile https://www.googleapis.com/auth/plus.profile.emails.read'
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
			 		var image = resp['image']['url'];
			        var username = resp['displayName'].replace(" ", "_");
			        var id_token = result['id_token'];
		        
		    		$.ajax({ url: '../php/webService.php',
				     data: {action: "ThirdPartyLogin", email: email, image: image, username: username, whoAmI: 'Google', thirdpartyID: id_token  },
				     type: 'post',
				     success: function(output) {
				     			GAEvent('Third Party Login', 'Google');
		          				location.hash = "#activity";
		 						location.reload();
				      },
					    error: function(x, t, m) {
					        if(t==="timeout") {
					            ToastError("Server Timeout");
					        } else {
					            ToastError(t);
					        }
						},
						timeout:45000
					});
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