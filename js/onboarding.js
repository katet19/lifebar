function ShowOnboarding(){
	var windowWidth = $(window).width();
    $("#onboarding").css({"display":"inline-block", "left": -windowWidth});
    $("#onboarding-header").css({"display":"inline-block"});
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
	 		$(".onboarding-next, .onboarding-skip").unbind();
	 		$(".onboarding-next").on("click", function(e){
	 			//Save here
	 			e.stopPropagation();
	 			ShowSocial();
	 		});
 	 		$(".onboarding-skip").on("click", function(e){
 	 			e.stopPropagation();
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
 		$(".onboarding-next, .onboarding-skip").unbind();
 		$(".onboarding-next").on("click", function(e){
 			e.stopPropagation();
 			ShowGamingPref();
 		});
  		$(".onboarding-skip").on("click", function(e){
  			e.stopPropagation();
 			ShowGamingPref();
 		});
 		$(".onboarding-member-view-more").on("click", function(){
 			var exclude = $(this).attr("data-alreadyshowing");	
 			ViewMoreMembers(exclude, $(this).parent());
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
 		$(".onboarding-next, .onboarding-skip").unbind();
 		$(".onboarding-next").on("click", function(e){
 			e.stopPropagation();
 			$(".mainNav, .userContainer").css({"display":"inherit"});
 			$("#onboarding-header").css({"display":"none"});
 			ShowDiscoverHome();
 		});
  		$(".onboarding-skip").on("click", function(e){
  			$(".mainNav, .userContainer").css({"display":"inherit"});
  			$("#onboarding-header").css({"display":"none"});
  			e.stopPropagation();
 			ShowDiscoverHome();
 		});
 		$(".onboarding-pref-image").on("click", function(){
 			if($(this).hasClass("onboarding-pref-image-active")){
 				$(this).removeClass("onboarding-pref-image-active");
 				$(this).find(".pref-checkmark").css({"opacity":"0"});
 			}else{
 				$(this).addClass("onboarding-pref-image-active");
 				$(this).find(".pref-checkmark").css({"opacity":"1"});
 			}
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

function ViewMoreMembers(exclude, parent){
	ShowLoader(parent, 'small', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "OnboardingViewMore", exclude: exclude },
     type: 'post',
     success: function(output) {
     	parent.append(output);
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
