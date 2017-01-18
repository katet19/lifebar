function ShowOnboarding(){
	$("#onboarding-header").css({"display":"inline-block"});
	$(".mainNav, .userContainer").css({"display":"none"});
	$("#navigationContainer").css({"-webkit-box-shadow":"none", "box-shadow":"none"});

	var windowWidth = $(window).width();
	if($(window).width() < 599){
		$(".identificationContainer").css({"display":"none"});
	}
	if($(".onboarding-account-step").length > 0){
		GAPage('Onboarding1of3', '/onboarding1of3');
		$(".onboarding-next").unbind();
		$(".onboarding-next").on("click", function(e){
			SaveOnboardingAccount();
			e.stopPropagation();
			ShowSocial();
		});
	}else if($(".onboarding-social-step").length > 0){
		$(".onboarding-next").unbind();
 		$(".onboarding-next").on("click", function(e){
 			SaveOnboardingSocial();
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
		
 		$(".onboarding-pub").on("click", function(){
 			if($(this).hasClass("onboarding-pub-active")){
 				$(this).removeClass("onboarding-pub-active");
 				$(this).find(".pref-checkmark").css({"opacity":"0"});
 			}else{
 				$(this).addClass("onboarding-pub-active");
 				$(this).find(".pref-checkmark").css({"opacity":"1"});
 			}
 		});
	}else if($(".onboarding-game-step").length > 0){
 		$(".onboarding-next").unbind();
 		$(".onboarding-next").on("click", function(e){
 			e.stopPropagation();
 			$(".mainNav, .userContainer").css({"display":"inherit"});
 			$("#onboarding-header").css({"display":"none"});
 			var windowWidth = $(window).width();
			if($(window).width() < 599){
 				$(".identificationContainer").css({"display":"inline-block"});
			}
 			ShowDiscoverHome();
 		});
		$(".game-card-action-pick, .game-discover-card .card-image").unbind();
		$(".game-card-action-pick").on("click", function(e){
			e.stopPropagation();
			if($(this).attr("data-action") == "xp" && $(".lean-overlay").length == 0)
				GameCardAction($(this).attr("data-action"), $(this).attr("data-id"));
		});
		$(".game-discover-card .card-image").on("click", function(e){ 
			e.stopPropagation(); 
			CloseSearch();
			$(".searchInput input").val('');
			$('html').unbind();
			$('html').click(function(){
				if($("#userAccountNav").is(":visible"))
					$("#userAccountNav").hide(250);
			});
			ShowGame($(this).parent().attr("data-gbid"), $("#discover")); 
		});
		$(".card-game-secondary-actions").on("click", function(e){ 
			e.stopPropagation(); 
			CloseSearch();
			$(".searchInput input").val('');
			$('html').unbind();
			$('html').click(function(){
				if($("#userAccountNav").is(":visible"))
					$("#userAccountNav").hide(250);
			});
			ShowGame($(this).parent().attr("data-gbid"), $("#discover")); 
		});
		AttachStarEvents();
	}
}

function SaveOnboardingAccount(){
	var steam = $("#steam_id").val();
	var xbox = $("#xbox_id").val();
	var psn = $("#psn_id").val();
	var age = $("#age_id").val();
	$.ajax({ url: '../php/webService.php',
	     data: {action: "SaveAccountInfo", steam: steam, xbox: xbox, psn: psn, age: age },
	     type: 'post',
	     success: function(output) {

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
	ShowLoader($("#discoverInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "ShowOnboardingSocial" },
     type: 'post',
     success: function(output) {
     	$(".onboarding-progress").html("Step: 2 of 3");
 		$("#discoverInnerContainer").html(output);
 		UpdateBrowserHash("onboarding");
 		$(".onboarding-next").unbind();
 		$(".onboarding-next").on("click", function(e){
 			SaveOnboardingSocial();
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
		
 		$(".onboarding-pub").on("click", function(){
 			if($(this).hasClass("onboarding-pub-active")){
 				$(this).removeClass("onboarding-pub-active");
 				$(this).find(".pref-checkmark").css({"opacity":"0"});
 			}else{
 				$(this).addClass("onboarding-pub-active");
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

function SaveOnboardingSocial(){
	var following = '';
	var pubs = '';
	$(".searchfollow").each(function(){
		if(this.checked)
			following = following + $(this).attr("data-id") + ",";	
	});
	$(".criticquickfollow").each(function(){
		if(this.checked)
			following = following + $(this).attr("data-id") + ",";	
	});
	$(".userquickfollow").each(function(){
		if(this.checked)
			following = following + $(this).attr("data-id") + ",";	
	});
	$(".onboarding-pub-active").each(function(){
		pubs = pubs + $.trim($(this).find("div").text()) + ",";
	});
	$.ajax({ url: '../php/webService.php',
	     data: {action: "SaveSocialInfo", following: following, pubs: pubs  },
	     type: 'post',
	     success: function(output) {

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
	ShowLoader($("#discoverInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "ShowOnboardingGamingPref" },
     type: 'post',
     success: function(output) {
     	$(".onboarding-progress").html("Step: 3 of 3");
     	$(".onboarding-next").html("FINISH");
 		$("#discoverInnerContainer").html(output);
 		UpdateBrowserHash("onboarding");
 		$(".onboarding-next").unbind();
 		$(".onboarding-next").on("click", function(e){
 			e.stopPropagation();
 			$(".mainNav, .userContainer").css({"display":"inherit"});
 			$("#onboarding-header").css({"display":"none"});
 			var windowWidth = $(window).width();
			if($(window).width() < 599){
 				$(".identificationContainer").css({"display":"inline-block"});
			}
 			ShowDiscoverHome();
 		});
		$(".game-card-action-pick, .game-discover-card .card-image").unbind();
		$(".game-card-action-pick").on("click", function(e){
			e.stopPropagation();
			if($(this).attr("data-action") == "xp" && $(".lean-overlay").length == 0)
				GameCardAction($(this).attr("data-action"), $(this).attr("data-id"));
		});
		$(".game-discover-card .card-image").on("click", function(e){ 
			e.stopPropagation(); 
			CloseSearch();
			$(".searchInput input").val('');
			$('html').unbind();
			$('html').click(function(){
				if($("#userAccountNav").is(":visible"))
					$("#userAccountNav").hide(250);
			});
			ShowGame($(this).parent().attr("data-gbid"), $("#discover")); 
		});
		$(".card-game-secondary-actions").on("click", function(e){ 
			e.stopPropagation(); 
			CloseSearch();
			$(".searchInput input").val('');
			$('html').unbind();
			$('html').click(function(){
				if($("#userAccountNav").is(":visible"))
					$("#userAccountNav").hide(250);
			});
			ShowGame($(this).parent().attr("data-gbid"), $("#discover")); 
		});
		AttachStarEvents();
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

function SaveOnboardingGamingPref(){
	var prefs = '';
	$(".onboarding-pref-image-active").each(function(){
		prefs = prefs + $(this).parent().attr("data-objectid") + ",";	
	});
	if(prefs == '')
		prefs = 'Platform_145,Platform_146,Platform_94';
	$.ajax({ url: '../php/webService.php',
	     data: {action: "SaveGamingPrefInfo", prefs: prefs },
	     type: 'post',
	     success: function(output) {

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
	     	searchbox.find(".searchfollow").each(function(){
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
