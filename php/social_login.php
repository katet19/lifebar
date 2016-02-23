<?php
	
	/*
	*
	*	Google
	*
	*/
	
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
					     	if(output.indexOf("Error") >= 0){
					     		$(".social-validation").html("ERROR: Unable to login with your Google account");
					     	}else{
				     			setCookie("RememberMe", $.trim(output), 14);
				     			GAEvent('Third Party Login', 'Google');
		          				location.hash = "#activity";
		 						location.reload();
					     	}
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
	<?php }
	
	
	
	/*
	*
	*	Twitter
	*
	*/
	require_once("library/Twitter/Config.php");
	require_once("library/Twitter/Consumer.php");
	require_once("library/Twitter/SignatureMethod.php");
	require_once("library/Twitter/HmacSha1.php");
	require_once("library/Twitter/Request.php");
	require_once("library/Twitter/Response.php");
	require_once("library/Twitter/Token.php");
	require_once("library/Twitter/TwitterOAuthException.php");
	require_once("library/Twitter/Util.php");
	require_once("library/Twitter/Util/JsonDecoder.php");
	require_once("library/Twitter/TwitterOAuth.php");
	use Abraham\TwitterOAuth\TwitterOAuth;
	define('OAUTH_CALLBACK', getenv('OAUTH_CALLBACK'));
	if($_GET['action'] == 'LoginTwitter'){
		TwitterOAuth();
	}
	if($_GET['action'] == 'LoginTwitter2'){
		if(isset($_GET['denied'])){
			$url = "http://ken.lifebar.io";
			header("Location: $url");
		}else{
			TwitterOAuthStep2();
		}
	}
	
	function TwitterOAuth(){
		session_start();
		$connection = new TwitterOAuth("LQud8mjA1xXQNPhyanWNbYRNl", "oo3gzYLplsIUFFHuxMLNB3nrROJGLioyDJq8y9Z0Ufyl6uCI94"); //, "4703887422-161apACPLvpulLpQOOjCEXylnpklO2s5YEcstvE", "fpPr7SB4rZ5xxDJnQZ68ofbdi6dcFxRNDcSmImTJDxDX0");
 		$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
 		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
		header("Location: $url");
	}
	
	function TwitterOAuthStep2(){
		session_start();
		$request_token = [];
		$request_token['oauth_token'] = $_SESSION['oauth_token'];
		$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
		
		if (isset($_GET['oauth_token']) && $request_token['oauth_token'] !== $_GET['oauth_token']) {
		    echo "Whoops, didn't get the token from Twitter. Please try again!";
		}else{
			$quickconnect = new TwitterOAuth("LQud8mjA1xXQNPhyanWNbYRNl", "oo3gzYLplsIUFFHuxMLNB3nrROJGLioyDJq8y9Z0Ufyl6uCI94", $request_token['oauth_token'], $request_token['oauth_token_secret']);
			$access_token = $quickconnect->oauth("oauth/access_token", ["oauth_verifier" => $_GET['oauth_verifier']]);
			$connection = new TwitterOAuth("LQud8mjA1xXQNPhyanWNbYRNl", "oo3gzYLplsIUFFHuxMLNB3nrROJGLioyDJq8y9Z0Ufyl6uCI94", $access_token['oauth_token'], $access_token['oauth_token_secret']);
			$user = $connection->get("account/verify_credentials");
			?>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
			<script type="text/javascript">
				function setCookie(cname, cvalue, exdays) {
				    var d = new Date();
				    d.setTime(d.getTime() + (exdays*24*60*60*1000));
				    var expires = "expires="+d.toUTCString();
				    document.cookie = cname + "=" + cvalue + "; " + expires;
				}
			
				$( document ).ready(function() {
					var user = '<?php echo $user->screen_name;?>';
					var image = '<?php echo $user->profile_image_url;?>';
					var id = '<?php echo $user->id;?>';
					$.ajax({ url: '../php/webService.php',
					     data: {action: "ThirdPartyLogin", email: '', image: image, username: user, whoAmI: 'Twitter', thirdpartyID: id  },
					     type: 'post',
					     success: function(output) {
					     	if(output.indexOf("Error") >= 0){
					     		$(".social-validation").html("ERROR: Unable to login with your Twitter account");
					     	}else{
				     			setCookie("RememberMe", $.trim(output), 14);
		 						location.href = "http://ken.lifebar.io";
					     	}
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
			</script>
			<?php
		}
	}
	
	
	/*
	*
	*	Facebook
	*
	*/
	
	function FacebookOAuth(){ ?>
	<script>
		window.fbAsyncInit = function() {
			    FB.init({
			        appId   : '517085435138347',
			        oauth   : true,
			        cookie  : true, // enable cookies to allow the server to access the session
			        xfbml   : true // parse XFBML
			    });
			
			  };

		function fb_login(){ 
            var user_email = '';
            var user_name = '';
            var user_image = '';

			
			FB.getLoginStatus(function(response) {
				if (response.status === 'connected') {
					access_token = response.authResponse.accessToken; //get access token
		            user_id = response.authResponse.userID; //get FB UID
		            FB.api('/me/?fields=picture,email,name', function(secondresponse) {
		            	user_image = secondresponse.picture.data.url;
		            	user_email = secondresponse.email;
		            	user_name = secondresponse.name.replace(" ", "_");
			    		$.ajax({ url: '../php/webService.php',
					     data: {action: "ThirdPartyLogin", email: user_email, image: user_image, username: user_name, whoAmI: 'Facebook', thirdpartyID: user_id  },
					     type: 'post',
					     success: function(output) {
					     	if(output.indexOf("Error") >= 0){
					     		$(".social-validation").html("ERROR: Unable to login with your Facebook account");
					     	}else{
				     			setCookie("RememberMe", $.trim(output), 14);
				     			GAEvent('Third Party Login', 'Facebook');
		          				location.hash = "#activity";
		 						location.reload();
					     	}
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
				           
				}else{
				    FB.login(function(response) {
				        if (response.authResponse) {
				            access_token = response.authResponse.accessToken; //get access token
				            user_id = response.authResponse.userID; //get FB UID
				            FB.api('/me/?fields=picture,email,name', function(secondresponse) {
				            	user_image = secondresponse.picture.data.url;
				            	user_email = secondresponse.email;
				            	user_name = secondresponse.name.replace(" ", "_");
					    		$.ajax({ url: '../php/webService.php',
							     data: {action: "ThirdPartyLogin", email: user_email, image: user_image, username: user_name, whoAmI: 'Facebook', thirdpartyID: user_id  },
							     type: 'post',
							     success: function(output) {
							     	if(output.indexOf("Error") >= 0){
							     		$(".social-validation").html("ERROR: Unable to login with your Facebook account");
							     	}else{
						     			setCookie("RememberMe", $.trim(output), 14);
						     			GAEvent('Third Party Login', 'Facebook');
				          				location.hash = "#activity";
				 						location.reload();
							     	}
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
				        } else {
				
				        }
				    }, {
				        scope: 'public_profile,email'
				    });
				}
				
			});
		}
		
		(function() {
		    var e = document.createElement('script');
		    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		    e.async = true;
		    document.getElementById('fb-root').appendChild(e);
		}());
	</script>
	<div id='fb-root'></div>
	<?php }
