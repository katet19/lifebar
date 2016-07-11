function ShowOnboarding(){
	var windowWidth = $(window).width();
    $("#onboarding").css({"display":"inline-block", "left": -windowWidth});
    $("#activity, #discover, #analytics, #admin, #notifications, #user, #game, .mainNav, .userContainer").css({"display":"none"});
    $("#navigationContainer").css({"-webkit-box-shadow":"none", "box-shadow":"none"});
	$("#activity, #discover, #analytics, #admin, #notifications, #user, #game").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#onboarding").velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
		ShowLoader($("#onboardingInnerContainer"), 'big', "<br><br><br>");
		$.ajax({ url: '../php/webService.php',
	     data: {action: "ShowOnboardingAccount" },
	     type: 'post',
	     success: function(output) {
	 		$("#onboardingInnerContainer").html(output);
	 		location.hash = "onboarding";
	 		$(".onboarding-accountdetails-next").on("click", function(){
	 			//Save here
	 			ShowSocial();
	 		});
 	 		$(".onboarding-accountdetails-skip").on("click", function(){
	 			ShowSocial();
	 		});
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
}

function ShowSocial(){
	ShowLoader($("#onboardingInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "ShowOnboardingSocial" },
     type: 'post',
     success: function(output) {
 		$("#onboardingInnerContainer").html(output);
 		location.hash = "onboarding";
 		$(".onboarding-social-next").on("click", function(){
 			ShowGamingPref();
 		});
  		$(".onboarding-social-skip").on("click", function(){
 			ShowGamingPref();
 		});
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
}

function ShowGamingPref(){
	ShowLoader($("#onboardingInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "ShowOnboardingGamingPref" },
     type: 'post',
     success: function(output) {
 		$("#onboardingInnerContainer").html(output);
 		location.hash = "onboarding";
 		$(".onboarding-gamingpref-next").on("click", function(){
 			$(".mainNav, .userContainer").css({"display":"inherit"});
 			ShowDiscoverHome();
 		});
  		$(".onboarding-gamingpref-skip").on("click", function(){
  			$(".mainNav, .userContainer").css({"display":"inherit"});
 			ShowDiscoverHome();
 		});
 		$(".onboarding-pref-image").on("click", function(){
 			if($(this).hasClass("onboarding-pref-image-active"))
 				$(this).removeClass("onboarding-pref-image-active");	
 			else
 				$(this).addClass("onboarding-pref-image-active");	
 		});
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
}