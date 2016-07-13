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
     	$(".onboarding-progress").html("Step: 2 of 3");
 		$("#onboardingInnerContainer").html(output);
 		location.hash = "onboarding";
 		$(".onboarding-next, .onboarding-skip").unbind();
 		$(".onboarding-next").on("click", function(e){
 			e.stopPropagation();
 			ShowGamingPref();
 		});
 		$(".onboarding-member-view-more").on("click", function(){
 			var exclude = $(this).attr("data-alreadyshowing");	
 			ViewMoreMembers(exclude, $(this));
 		});
 		$("#onboarding-follow-personalities-all").change(function() {
			if(this.checked){
				$(".criticquickfollow").each(function(){
					if(!this.checked){
						this.checked = true;
					}	
				});
			}else{
				$(".criticquickfollow").each(function(){
					if(this.checked){
						this.checked = false;
					}	
				});
			}
 		});
 		$("#onboarding-follow-users-all").change(function() {
			if(this.checked){
				$(".userquickfollow").each(function(){
					if(!this.checked){
						this.checked = true;
					}	
				});
			}else{
				$(".userquickfollow").each(function(){
					if(this.checked){
						this.checked = false;
					}	
				});
			}
 		});
 		$("#onboarding-search").on('keypress keyup', function (e) {
			if (e.keyCode === 13) { 
				e.stopPropagation(); 	
				if($("#onboarding-search").val() != ''){
					SearchForUsers($("#onboarding-search").val());
				}
			} 
		});
		$(".onboarding-search-icon").on('click', function (e) {
			if($("#onboarding-search").val() != ''){
				e.stopPropagation(); 
				SearchForUsers($("#onboarding-search").val());
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

function ShowGamingPref(){
	ShowLoader($("#onboardingInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "ShowOnboardingGamingPref" },
     type: 'post',
     success: function(output) {
     	$(".onboarding-progress").html("Step: 3 of 3");
     	$(".onboarding-next").html("FINISH");
 		$("#onboardingInnerContainer").html(output);
 		location.hash = "onboarding";
 		$(".onboarding-next, .onboarding-skip").unbind();
 		$(".onboarding-next").on("click", function(e){
 			e.stopPropagation();
 			$(".mainNav, .userContainer").css({"display":"inherit"});
 			$("#onboarding-header").css({"display":"none"});
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

function SearchForUsers(searchstring){
	var searchbox = $(".search-results").css({"display":"inline-block"});
	if(searchbox.find(".search-results-loading").length == 0){
		searchbox.prepend("<div class='search-results-loading'></div>");
		ShowLoader($(".search-results-loading"), 'small', "<br><br><br>");
		$.ajax({ url: '../php/webService.php',
	     data: {action: "OnboardingUserSearch", searchstring: searchstring  },
	     type: 'post',
	     success: function(output) {
	     	var moved = false;
	     	$(".searchfollow").each(function(){
	     		if(this.checked == false){
	     			$(this).parent().parent().parent().parent().remove();
				}else{
					moved = true;
					$(this).parent().parent().parent().parent().detach().appendTo($(".search-results-selected"));
				}
	     	});
	     	if(moved)
	     		$(".search-results-selected").css({"display":"inline-block"});
	     	
	     	searchbox.html(output);
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
}

function ViewMoreMembers(exclude, element){
	var parent = element.parent();
	ShowLoader(parent, 'small', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "OnboardingViewMore", exclude: exclude },
     type: 'post',
     success: function(output) {
     	parent.html(output);
  		$(".onboarding-member-view-more").on("click", function(){
 			var exclude = $(this).attr("data-alreadyshowing");	
 			ViewMoreMembers(exclude, $(this));
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
