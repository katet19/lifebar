function InitializeDiscover(){
	$(window).resize(function() {
		ResizeDiscoverEvents();
	});
	AttachSearchEvents();
}

function AttachSearchEvents(){
	$(".SearchBtn").on('click', function(e){
		var parent = $(this).parent();
		e.stopPropagation(); 
		if(parent.find(".searchInput").is(":visible") && $(this).val() !== "")
			Search(parent.find(".searchInput input").val());
		else
			OpenSearch();
	});
	$(".searchInput input").on('keypress keyup', function (e) {
		if (e.keyCode === 13) { 
			e.stopPropagation(); 
			if($(this).val() !== "")
				Search($(this).val());
		} 
		
	});
}

function Search(searchstring){
	//hide keyboard on mobile
	$(".searchInput input").blur();
	GLOBAL_TAB_REDIRECT = "Search";
  	var windowWidth = $(window).width();
    $("#discover").css({"display":"inline-block", "left": -windowWidth});
    $("#activity, #profile, #admin, #profiledetails, #settings, #notifications, #game, #user, #landing").css({"display":"none"});
    $("#activity, #profile, #admin, #profiledetails, #settings, #notifications, #game, #user, #landing").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#discover").velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#gameInnerContainer").html("");
	ManuallyNavigateToTab("#discover");
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
	ShowLoader($("#discoverInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
         data: {action: "Search", search: searchstring },
         type: 'post',
         success: function(output) {
            $("#discoverInnerContainer").html(output);
            GLOBAL_TAB_REDIRECT = "";
 			$(".backButton").on('click', function(){
 				$("#discoverInnerContainer .backContainer").delay(200).velocity({"opacity":"0"});
 			  	ManuallyNavigateToTab("#discover");
 				ShowDiscoverHome();
 				$(".searchInput input").val("");
 			});
 			$("#sideContainer").html("");
 			SideContentPush($("#sideContainer").html());
  			$(".user-discover-card").on("click", function(e){
 		   		e.stopPropagation();
				CloseSearch();
				//Clear search input
	 			$(".searchInput input").val('');
	 			$('html').unbind();
				$('html').click(function(){
					if($("#userAccountNav").is(":visible"))
						$("#userAccountNav").hide(250);
				});
 				ShowUserPreviewCard($(this).find(".user-preview-card"));
 			});
  			Waves.displayEffect();
 			//FilterResults();
 			$(".game-discover-card .card-image").on("click", function(e){ 
 				e.stopPropagation(); 
 				CloseSearch();
				//Clear search input
	 			$(".searchInput input").val('');
	 			$('html').unbind();
				$('html').click(function(){
					if($("#userAccountNav").is(":visible"))
						$("#userAccountNav").hide(250);
				});
 				ShowGame($(this).parent().attr("data-gbid"), $("#discover")); 
 			});
 			$(".card-game-tier-container").on("click", function(e){ e.stopPropagation(); GameCardActions($(this)); });
 			$(".SeeAllBtn").on('click',function(){
 				var context = $(this).attr("data-context");
 				$("."+context).show(250);
 				$(this).delay(200).velocity({"opacity":"0"}, function(){ $(this).remove(); });
 			});
 			
 			$('html').on('click', function(){
				CloseSearch();
				//Clear search input
	 			$(".searchInput input").val('');
	 			$('html').unbind();
				$('html').click(function(){
					if($("#userAccountNav").is(":visible"))
						$("#userAccountNav").hide(250);
				});
			});
 			GAPage('Search', '/search');
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
	
	if($(window).width() < 600 || ($(window).width() < 992 && $(".searchContainerAnonymous").length > 0 ) )
		CloseSearch();
}

function FilterCategories(){
		$(".categoryResults").show();
		if($(window).width() >= 993){
			$(".discoverCategory").each( function(){ 
				var total = 1;
				$(this).find(".categoryResults").each( function(){
					if(total > 6){
						$(this).hide();
					}
					total++;
				});
			});
		}else if($(window).width() >=600){
			$(".discoverCategory").each( function(){ 
				var total = 1;
				$(this).find(".categoryResults").each( function(){
					if(total > 4 && $(this).hasClass("user-discover-card")){
						$(this).hide();
					}else if(total > 4 && $(this).hasClass("game-discover-card")){
						$(this).hide();
					}
					total++;
				});
			});
		}else{
			$(".discoverCategory").each( function(){ 
				var total = 1;
				$(this).find(".categoryResults").each( function(){
					if(total > 2){
						$(this).hide();
					}
					total++;
				});
			});
		}
}

function FilterResults(){
	$(".GameSeeAllBtn").css({"display":"none"});
	$(".UserSeeAllBtn").css({"display":"none"});
	if($(".gameResults").length > 0 && $(".userResults").length > 0){
		var total = 1;
		if($(window).width() >= 993){
			$(".gameResults").each( function(){
				if(total > 6){
					$(this).hide();
					$(".GameSeeAllBtn").show();
				}
				total++;
			});
			total = 1;
			$(".userResults").each( function(){
				if(total > 6){
					$(this).hide();
					$(".UserSeeAllBtn").show();
				}
				total++;
			});
		}else if($(window).width() >=600){
			$(".gameResults").each( function(){
				if(total > 4){
					$(this).hide();
					$(".GameSeeAllBtn").show();
				}
				total++;
			});
			total = 1;
			$(".userResults").each( function(){
				if(total > 4){
					$(this).hide();
					$(".UserSeeAllBtn").show();
				}
				total++;
			});
		}else{
			$(".gameResults").each( function(){
				if(total > 2){
					$(this).hide();
					$(".GameSeeAllBtn").show();
				}
				total++;
			});
			total = 1;
			$(".userResults").each( function(){
				if(total > 2){
					$(this).hide();
					$(".UserSeeAllBtn").show();
				}
				total++;
			});
		}
	}
}

function OpenSearch(){
	if($(window).width() >= 993){
		$(".searchContainerAnonymous, .searchContainer").css({"width":"100%", "background-color" : "rgba(255,255,255,0.2)"});
		$(".userAccountNavButton, .userNotificiations, .userBug, .userAvatar").hide();
		$(".searchInput").css({"left":"3.5em"});
		$(".SearchBtn").css({"float":"left"});
	}else if($(window).width() >= 600){
		$(".searchContainerAnonymous, .searchContainer, .searchContainerMobile").css({"width":"100%", "background-color" : "rgba(255,255,255,0.2)"});
		$(".userAccountNavButton, .userNotificiations, .userBug, .userAvatar").hide();
		$(".searchInput").css({"left":"3.5em"});
		$(".SearchBtn").css({"float":"left"});
	}else{
		$(".searchContainerAnonymous, .searchContainer, .searchContainerMobile").css({"width":"100%", "background-color" : "rgba(255,255,255,0.2)"});
		$(".mobileTab, .mobileNav").hide();
		$(".searchInput").css({"left":"3em"});
	}
	$(".closeMobileSearch").show();
	$(".closeMobileSearch").on('click', function(e){
		e.stopPropagation();
		CloseSearch();
	});
	$(".searchInput").css({"display":"inline-block"});
	$(".searchInput").on('click', function(e){
		e.stopPropagation();
	});
	$(".searchInput input").focus();
	$(".loginContainer").hide();
}

function CloseSearch(){
	if($(window).width() >= 993){
		$(".searchContainerAnonymous, .searchContainer").css({"width":"150px", "background-color" : ""});
		setTimeout(function(){
			$(".userAccountNavButton, .userNotificiations, .userBug, .userAvatar, .loginContainer").show(100);
		}, 100);
		$(".searchInput").css({"left":"1em"});
		$(".SearchBtn").css({"float":"inherit"});
	}else if($(window).width() >= 600){
		$(".searchContainerAnonymous, .searchContainer").css({"width":"100px", "background-color" : ""});
		setTimeout(function(){
			$(".userAccountNavButton, .userBug, .userAvatar, .loginContainer").show(100);
		}, 100);
		$(".searchInput").css({"left":"1em"});
		$(".SearchBtn").css({"float":"inherit"});
	}else{
		$(".searchContainerAnonymous, .searchContainer, .searchContainerMobile").css({"width":"auto", "background-color" : ""});
		$(".mobileTab, .mobileNav").show();
		$(".searchInput").css({"left":"1em"});
		$(".searchInput input").val("");
	}
	$(".closeMobileSearch").hide();
	$(".searchInput").css({"display":""});
	$(".mainNav").parent().show();
	$(".userContainer").parent().show();
}

function ShowDiscoverHome(){
	if(location.hash != "#discover")
		location.hash = "#discover";
  	ShowLoader($("#discoverInnerContainer"), 'big', "<br><br><br>");
  	var windowWidth = $(window).width();
    $("#discover").css({"display":"inline-block", "left": -windowWidth});
    $("#activity, #profile, #admin, #profiledetails, #settings, #notifications, #game, #user, #landing").css({"display":"none"});
    $("#activity, #profile, #admin, #profiledetails, #settings, #notifications, #game, #user, #landing").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#discover").velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#gameInnerContainer").html("");
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayDiscoverHome" },
     type: 'post',
     success: function(output) {
     			$("#discoverInnerContainer").html(output);
     			if($(".onboarding-big-welcome").length > 0){
     				ShowOnboarding();
     			}else{
	     			//FilterCategories();
	 				AttachDiscoverHomeEvents();
	 				AttachDiscoverSecondaryEvents();
	      			Waves.displayEffect();
	      			GAPage('Discover', '/discover');
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
}

function ShowExtraSideContent(){
	//with advanced search button
	//var h_one = 250 + $(".latest-xp-count-1").height();
	var h_one = 145 + $(".latest-xp-count-1").height();
	var h_two = $(".latest-xp-count-2").height();
	var h_three = $(".latest-xp-count-3").height();
	var h_four = $(".latest-xp-count-4").height();
	
	if($("#sideContainer").height() > h_one)
		$(".latest-xp-count-1").show();
	if($("#sideContainer").height() > (h_one + h_two))
		$(".latest-xp-count-2").show();
	if($("#sideContainer").height() > (h_one + h_two + h_three))
		$(".latest-xp-count-3").show();
	if($("#sideContainer").height() > (h_one + h_two + h_three + h_four))
		$(".latest-xp-count-4").show();
}

function AttachDiscoverSecondaryEvents(){
  	Waves.displayEffect();
  	$(".ShowAdvancedSearch, .latest-xp-game-name, .detailsBtn").unbind();
  	$(".ShowAdvancedSearch").on('click', function(){ ShowAdvancedSearch(); });
  	$(".latest-xp-game-name").on("click", function(){
  		ShowGame($(this).attr("data-gbid"), $("#discover"));	
  	});
  	$(".detailsBtn").on('click', function(e){ 
  		var uniqueid = $(this).attr("data-uid"); 
  		var html = $('#'+uniqueid).html();
  		ShowPopUp(html);
  	});
   	$(".latest-xp-name-container").on('click', function(e){
   		e.stopPropagation();
 		ShowUserPreviewCard($(this).find(".user-preview-card"));		
 	});
  	ShowExtraSideContent();
  	AttachAgrees();
}

function ShowAdvancedSearch(){
	ShowSideContentFocus();
  	ShowLoader($("#sideContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayAdvancedSearch" },
     type: 'post',
     success: function(output) {
 			$("#sideContainer").html(output);
  			AttachAdvancedSearch();
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

function AttachDiscoverHomeEvents(){
	//Game
	$(".game-discover-card .card-image, .card-action a").on("click", function(e){ e.stopPropagation(); ShowGame($(this).parent().attr("data-gbid"), $("#discover")); });
 	$(".suggested-game-link").on("click", function(e){ e.stopPropagation(); ShowGame($(this).parent().attr("data-gbid"), $("#discover")); });
	$(".card-game-tier-container").on("click", function(e){ e.stopPropagation(); GameCardActions($(this)); });
	$("select").material_select();
 	$(".daily-header-question").on("click", function(){
 		DisplayQuestionsForDaily();	
 	});
 	$(".daily-watch-title").on("click", function(){
		if(!$(this).hasClass("daily-watch-title-active")){
			$(".daily-watch-title-active").find(".daily-watch-title-xp").hide();
			$(".daily-watch-title-active").removeClass("daily-watch-title-active");
			$(this).addClass("daily-watch-title-active");
			$(this).find(".daily-watch-title-xp").show();
			var selected = $(this).attr("data-gameid");
			$(".daily-watch-video-box-active").removeClass("daily-watch-video-box-active");
			$(".daily-watch-video-box").each(function(){
				if($(this).attr("data-gameid") == selected){
					$(this).addClass("daily-watch-video-box-active");
				}	
			});
		}	
 	});
	if($(".ResultsDougnut").length > 0){
 		$(".daily-header-question").css({"top":"75px"});
		$(".daily-header-game-title").css({"top":"50px"});
		var bg = $(".daily-header-image").attr("data-webkit");
		$(".daily-header-image").css({"background": bg });
		$(".daily-header-image").css({"background-size": "cover"});
		DisplayFormResultsGraph();	
	}
	$(".daily-header-game-title, .view-game-spoiler").on('click', function(){
		ShowGame($(this).attr("data-id"), $("#discover"));
	});
 	$(".follow-from-discover").on("click", function(){
		if($("#loginButton").length > 0){
			$('#signupModal').openModal(); $("#username").focus();
		}else{
			var userid = $(this).attr("data-id");
			var username = $(this).attr("data-name");
			FollowUser(userid, $(this), username);
		}
 	});
 	$(".discover-invite-users").on("click", function(){
 		ShowProfileDetails("<div class='universalBottomSheetLoading'></div>");
		ShowLoader($(".universalBottomSheetLoading"), 'big', "<br><br><br>");
		$.ajax({ url: '../php/webService.php',
	     data: {action: "DisplayInviteUsers" },
	     type: 'post',
	     success: function(output) {
	 			$("#BattleProgess").html(output); 
				AttachInviteUserEvents();
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
 	$(".discover-collection-game-image-view").on("click", function(){
 		DisplayCollectionDetails($(this).attr("data-cid"), 'Discover', $(this).attr("data-ownerid"), false);
 	});
 	$(".discover-collection-game-image").on("click", function(){
 		ShowGame($(this).attr("data-id"), $("#discover"));
 	});
 	AttachWatchedDiscoverXP();
 	$(".edit-ref-pt").on("click", function(){
		var refptID = $(this).attr("data-id");
		EditReflectionPopUp(refptID);
	});
	//User
 	$(".user-discover-card").on("click", function(e){
 	 	e.stopPropagation();
 		ShowUserPreviewCard($(this).find(".user-preview-card"));
 	});
  	$(".discover-collection-user").on("click", function(e){
 	 	e.stopPropagation();
 		ShowUserPreviewCard($(this).find(".user-preview-card"));
 	});
 	$(".ViewBtnCollection").on("click", function(){
 		DisplayCollectionDetails($(this).parent().attr("data-catid"), 'Discover', $(this).parent().attr("data-userid"), false);		
 	});
	//Category
	$(".ViewBtn").on("click", function(){
		GLOBAL_TAB_REDIRECT = "CategoryNav";
		ManuallyNavigateToTab("#discover");
		ShowLoader($("#discoverInnerContainer"), 'big', "<br><br><br>");
		window.scrollTo(0, 0);
		var category = $(this).parent().attr("data-category");
		var customName = $(this).parent().attr("data-name");
		var catid = $(this).parent().attr("data-catid");
		$.ajax({ url: '../php/webService.php',
	         data: {action: "DisplayDiscoverCategory", category: category, catid: catid },
	         type: 'post',
	         success: function(output) {
	            $("#discoverInnerContainer").html(output);
	            GLOBAL_TAB_REDIRECT = "";
	            if(category == "Custom Category")
	            	$(".backButtonLabel").html(customName);
	            else
					$(".backButtonLabel").html(category);
	 			$(".backButton").on('click', function(){
	 				$("#discoverInnerContainer .backContainer").delay(200).velocity({"opacity":"0"});
	 				window.scrollTo(0, 0);
	 			  	ManuallyNavigateToTab("#discover");
	 				ShowDiscoverHome();
	 			});
	 			$(".user-discover-card").on("click", function(e){
 			 		e.stopPropagation();
 					ShowUserPreviewCard($(this).find(".user-preview-card"));
	 			});
	 			$(".game-discover-card .card-image").on("click", function(e){ e.stopPropagation(); ShowGame($(this).parent().attr("data-gbid"), $("#discover")); });
	 			$(".card-game-tier-container").on("click", function(e){ GameCardActions($(this)); });
	 			$(".CategoryGameImageHighlight, .CategoryGameTitle").on("click", function(){
	 				ShowGame($(this).attr("data-gbid"), $("#discover"));
	 			});
	 			DisplayGraphs();
	 			AttachDiscoverSecondaryEvents();
	  			Waves.displayEffect();
	  			GAPage('Discover - '+customName, '/discover/'+customName);
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
		
		if($(window).width() < 600 || ($(window).width() < 992 && $(".searchContainerAnonymous").length > 0 ) )
			CloseSearch();
	});
}

function AttachWatchedDiscoverXP(){
	$(".daily-watch-title-xp").unbind();
	$(".daily-watch-title-xp").on("click", function(){
		if($("#loginButton").length > 0){
			$('#signupModal').openModal(); $("#username").focus();
		}else{
			var xpElement = $(this);
			$(".daily-watch-title").hide();
			$(".daily-watch-title-active").show();
			$(".daily-watch-xp-entry").show();
			ShowLoader($(".daily-watch-xp-entry"), 'big', "<br><br><br>");
			$.ajax({ url: '../php/webService.php',
				data: {action: "DisplayWatchedXPEntry", url: $(".daily-watch-title-active").attr("data-url"), gameid: $(".daily-watch-title-active").attr("data-gameid") },
				type: 'post',
				success: function(output) {
					$(".daily-watch-xp-entry").html(output);
					$(".myxp-video-goto-full").hide();
					AttachActivityVideoEvents();
					xpElement.html("CLOSE <i class='mdi-navigation-close'></i>");
					xpElement.css({"background-color":"#F44336"});
					$(".daily-watch-title-xp").unbind();
					$(".daily-watch-title-xp").on('click', function(){
						$(".daily-watch-title").show();
						$(".daily-watch-xp-entry").hide();
						$(".daily-watch-xp-entry").html("");
						xpElement.html("ADD <i class='mdi-action-visibility'></i>");
						xpElement.css({"background-color":"#0e4c7b"});
						AttachWatchedDiscoverXP();
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
 	});
}

function DisplayGraphs(){
	if($(".GraphExperiencedUsers").length > 0){
		$(".GraphExperiencedUsers").each(function(){
			var experiencedUsersGraph = $(this).get(0).getContext("2d");
			var data = {
		    labels: ["Tier 1", "Tier 2", "Tier 3", "Tier 4", "Tier 5"],
		    datasets: [
		        {
		            label: "Lifetime Experiences",
		            fillColor: "rgba(71, 71, 71,1)",
		            strokeColor: "rgb(255, 0, 97)",
		            pointColor: "rgb(255, 0, 97)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(71,71,71,1)",
		            data: [$(this).attr("data-t1"), $(this).attr("data-t2"), $(this).attr("data-t3"), $(this).attr("data-t4"), $(this).attr("data-t5")]
		        }
		    ]
		};
		$(this).attr('width', $(this).parent().width()-10);
        $(this).attr('height', $(this).parent().height()-5);
		var temp = new Chart(experiencedUsersGraph).Line(data, { datasetStrokeWidth : 2, pointDot : false, scaleShowGridLines : false, multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>", animation: false });
      	
		});
	}
}

function AttachAdvancedSearch(){
	$("#AdvancedSearchBtn").on('click', function(e){
		AdvancedSearch($("#advanced-search-text").val(),
			$("#advanced-search-platform").val(),
			$("#advanced-search-year").val(),
			$("#advanced-search-publisher").val(),
			$("#advanced-search-developer").val(),
			$("#advanced-search-genre").val(),
			$("#advanced-search-franchise").val()
		);
	});
	$("#advanced-search-text, #advanced-search-platform, #advanced-search-year, #advanced-search-publisher, #advanced-search-developer, #advanced-search-genre, #advanced-search-franchise").on('keypress keyup', function (e) {
		if (e.keyCode === 13) { 
			e.stopPropagation(); 	
			AdvancedSearch($("#advanced-search-text").val(),
				$("#advanced-search-platform").val(),
				$("#advanced-search-year").val(),
				$("#advanced-search-publisher").val(),
				$("#advanced-search-developer").val(),
				$("#advanced-search-genre").val(),
				$("#advanced-search-franchise").val()
			);
		} 
		
	});
 	$(".backButton").on('click', function(){
 		$("#discoverInnerContainer .backContainerSideContent").delay(200).velocity({"opacity":"0"});
        $(".backContainerSideContent").find(".backButtonLabel").html("Advanced Search");
 		HideSideContentFocus();
 	});
   	Waves.displayEffect();
	AdvancedSearchFilterEvents();
}

function AdvancedSearch(searchstring, platform, year, publisher, developer, genre, franchise){
	//hide keyboard on mobile
	$(".searchInput input").blur();
	GLOBAL_TAB_REDIRECT = "Search";
  	var windowWidth = $(window).width();
    $("#discover").css({"display":"inline-block", "left": -windowWidth});
    $("#activity, #profile, #admin, #profiledetails, #settings, #notifications, #game, #user, #landing").css({"display":"none"});
    $("#activity, #profile, #admin, #profiledetails, #settings, #notifications, #game, #user, #landing").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#discover").velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	ManuallyNavigateToTab("#discover");
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
	ShowLoader($("#discoverInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
         data: {action: "AdvancedSearch", search: searchstring, platform: platform, year: year, publisher: publisher, developer: developer, genre: genre, franchise: franchise },
         type: 'post',
         success: function(output) {
            $("#discoverInnerContainer").html(output);
            GLOBAL_TAB_REDIRECT = "";
			$(".backButtonLabel").html("Search Results");
 			$(".backButton").on('click', function(){
 				$("#discoverInnerContainer .backContainer").delay(200).velocity({"opacity":"0"});
 			  	ManuallyNavigateToTab("#discover");
 				ShowDiscoverHome();
 				$(".searchInput input").val("");
 			});
  			Waves.displayEffect();
  			$(".game-discover-card .card-image").on("click", function(e){ e.stopPropagation(); ShowGame($(this).parent().attr("data-gbid"), $("#discover")); });
  			$(".card-game-tier-container").on("click", function(e){ e.stopPropagation(); GameCardActions($(this)); });
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
	
	if($(window).width() < 600 || ($(window).width() < 992 && $(".searchContainerAnonymous").length > 0 ) )
		CloseSearch();
}

function CustomCategory(categoryid){
	//hide keyboard on mobile
	$(".searchInput input").blur();
	GLOBAL_TAB_REDIRECT = "Search";
 	HideSideContentFocus(true);
	ShowLoader($("#discoverInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
         data: {action: "CustomCategory", categoryid: categoryid },
         type: 'post',
         success: function(output) {
            $("#discoverInnerContainer").html(output);
            GLOBAL_TAB_REDIRECT = "";
			$(".backButtonLabel").html("Search Results");
 			$(".backButton").on('click', function(){
 				$("#discoverInnerContainer .backContainer").delay(200).velocity({"opacity":"0"});
 			  	ManuallyNavigateToTab("#discover");
 				ShowDiscoverHome();
 				$(".searchInput input").val("");
 			});
  			Waves.displayEffect();
  			$(".game-discover-card .card-image").on("click", function(e){ e.stopPropagation(); ShowGame($(this).parent().attr("data-gbid"), $("#discover")); });
  			$(".card-game-tier-container").on("click", function(e){ e.stopPropagation(); GameCardActions($(this)); });
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
	
	if($(window).width() < 600 || ($(window).width() < 992 && $(".searchContainerAnonymous").length > 0 ) )
		CloseSearch();
}

function AdvancedSearchFilterEvents(){
	$("#advanced-search-genre").on('keyup',function(){
		typeaheadMatches($(this).val(), typeahead_genres, "Genre");	
	});
	$("#advanced-search-developer").on('keyup',function(){
		typeaheadMatches($(this).val(), typeahead_developers, "Developer");	
	});
	$("#advanced-search-publisher").on('keyup',function(){
		typeaheadMatches($(this).val(), typeahead_publishers, "Publisher");	
	});
	$("#advanced-search-franchise").on('keyup',function(){
		typeaheadMatches($(this).val(), typeahead_franchises, "Franchise");	
	});
	$("#advanced-search-platform").on('keyup',function(){
		typeaheadMatches($(this).val(), typeahead_platforms, "Platform");	
	});
}

function typeaheadMatches(typed, data, filter) {
	if(typed.length > 1){
		var matches = "";
		for(var i = 0; i < data.length; i++){
			if(data[i].toLowerCase().indexOf(typed.toLowerCase()) >= 0){
				//matches.push(data[i]);
				matches = matches + "<li class='collection-item'>"+data[i]+"</li>";
			}
		}
		if(matches !== ""){
			$("#typeaheadResults"+filter+" .collection .collection-item").unbind();
			$("#typeaheadResults"+filter).html("<ul class='collection' style='margin:0'>"+matches+"</ul>");
			$("#typeaheadResults"+filter).show(250);
			$("#typeaheadResults"+filter+" .collection .collection-item").on('click',function(){
				$("#typeaheadResults"+filter).html("");
				$("#typeaheadResults"+filter).hide(250);
				$("#typeaheadResults"+filter).parent().find("input").val($(this).html());
			});
		}else{
			$("#typeaheadResults"+filter).html("");
			$("#typeaheadResults"+filter).hide(250);
		}
	}else{
		$("#typeaheadResults"+filter).html("");
		$("#typeaheadResults"+filter).hide(250);
	}
};

function ResizeDiscoverEvents(){
	//FilterCategories();
}

function DisplayQuestionsForDaily(){
	$(".daily-answers-container").css({"top":"0"});
	$(".daily-header-question").css({"top":"75px"});
	$(".daily-header-game-title").css({"top":"50px"});
	$(".daily-header-image").addClass('daily-header-image-active');
	var bg = $(".daily-header-image").attr("data-webkit");
	$(".daily-header-image").css({"background": bg });
	$(".daily-header-image").css({"background-size": "cover"});
	$(".daily-answers-results-container").hide();
	
	$(".daily-pref-image,.submit-daily-response,.cancel-daily-response").unbind();
 	$(".daily-pref-image").on("click", function(){
 		if($(this).hasClass("daily-pref-image-active")){
 			$(this).removeClass("daily-pref-image-active");
 			$(this).find(".daily-checkmark").css({"opacity":"0"});
 		}else{
 			if($(this).hasClass("singlegrid")){
 				var current = $(".daily-pref-image-active");
 				current.removeClass("daily-pref-image-active");
 				current.find(".daily-checkmark").css({"opacity":"0"});
 			}
 			$(this).addClass("daily-pref-image-active");
 			$(this).find(".daily-checkmark").css({"opacity":"1"});
 		}
 	});
	$(".submit-daily-response").on('click', function(){
		if($("#loginButton").length > 0){
			$('#signupModal').openModal(); $("#username").focus();
		}else{
			$(".daily-answers-container").css({"top":"100%"});
			$(".daily-header-image").removeClass('daily-header-image-active');
			$(".daily-answers-results-container").show();
			SaveSubmission();
		}
	});
	$(".cancel-daily-response").on('click', function(){
		if($(".ResultsDougnut").length == 0){
			$(".daily-header-question").css({"top":"250px"});
			$(".daily-header-game-title").css({"top":"225px"});
			$(".daily-header-image").css({"background": $(".daily-header-image").attr("data-normal")});
			$(".daily-header-image").css({"background-size": "cover"});
		}
		$(".daily-answers-container").css({"top":"100%"});
		$(".daily-header-image").removeClass('daily-header-image-active');
		$(".daily-answers-results-container").show();
	});
}

function SaveSubmission(){
	var formType = $(".daily-answers-container").attr("data-type");
	var formitemid = 0;
	var formid = 0;
	var objectid = 0;
	var objectType = '';
	var gameid = 0;
	if(formType == 'grid-single'){
		formitemid = $(".daily-pref-image-active").parent().parent().attr("data-formitemid");
		formid = $(".daily-pref-image-active").parent().parent().attr("data-formid");
		objectid = $(".daily-pref-image-active").parent().parent().attr("data-objid");
		objectType = $(".daily-pref-image-active").parent().parent().attr("data-objtype");
		gameid = $(".daily-pref-image-active").parent().parent().attr("data-gameid");
	}else if(formType == 'radio'){
		formitemid = $("input[type=radio][name=dailyresposne]:checked").parent().attr("data-formitemid");
		formid = $("input[type=radio][name=dailyresposne]:checked").parent().attr("data-formid");
		objectid = $("input[type=radio][name=dailyresposne]:checked").parent().attr("data-objid");
		objectType = $("input[type=radio][name=dailyresposne]:checked").parent().attr("data-objtype");
		gameid = $("input[type=radio][name=dailyresposne]:checked").parent().attr("data-gameid");
	}else if(formType == 'dropdown'){
		formitemid = $("#daily-response-dropdown").val();
		formid = $("#daily-response-dropdown").parent().parent().attr("data-formid");
		objectid = $("#daily-response-dropdown").parent().parent().attr("data-objid");
		objectType = $("#daily-response-dropdown").parent().parent().attr("data-objtype");
		gameid = $("#daily-response-dropdown").parent().parent().attr("data-gameid");
	}else if(formType == 'grid-multi'){
		formitemid = '';
		$(".daily-pref-image-active").each(function(){
			formitemid = formitemid + $(this).parent().parent().attr("data-formitemid") + "||";
			if(formid == 0){
				formid = $(this).parent().parent().attr("data-formid");
				objectid = $(this).parent().parent().attr("data-objid");
				objectType = $(this).parent().parent().attr("data-objtype");
				gameid = $(this).parent().parent().attr("data-gameid");
			}
		});	
	}else if(formType == 'checkbox'){
		formitemid = '';
		$(".response-checkbox").each(function(){
			if(this.checked){
				formitemid = formitemid + $(this).parent().attr("data-formitemid") + "||";
				if(formid == 0){
					formid = $(this).parent().attr("data-formid");
					objectid = $(this).parent().attr("data-objid");
					objectType = $(this).parent().attr("data-objtype");
					gameid = $(this).parent().attr("data-gameid");
				}
			}
		});		
	}
	
	$.ajax({ url: '../php/webService.php',
         data: {action: "SubmitDailyChoice", formid: formid, formitemid: formitemid, gameid: gameid, objectid: objectid, objectType: objectType },
         type: 'post',
         success: function(output) {
         	$(".daily-answers-results-container").html(output);
         	DisplayFormResultsGraph();
         	Toast("Saved your Reflection Point!");
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

function DisplayFormResultsGraph(){
	if($(".ResultsDougnut").length > 0){
		$(".ResultsDougnut").each(function(){
			var reflectionPointGraph = $(this).get(0).getContext("2d");
			var total = parseInt($(this).attr("data-total"));
			var i = 0;
			var data = [];
			while(i < total){
				data.push({
					value: ($(this).attr("data-e"+i) > 0) ? Math.round((parseInt($(this).attr("data-e"+i)) / total) * 100) : 0,
			        color:$(this).attr("data-ec"+i),
			        highlight: $(this).attr("data-ech"+i),
			        label: $(this).attr("data-ed"+i)
				});
				i++;
			}
			
	    if($(window).width() >=600){
	    	$(this).attr('height', 175);
	    	$(this).attr('width', 175);
	    }else{
	    	$(this).attr('height', 125);
	    	$(this).attr('width', 125);
	    }
      	var tierChart = new Chart(reflectionPointGraph).Doughnut(data, { animation: false, showTooltips: true });
		});
	}
}

function AttachInviteUserEvents(){
	$(".invite-cancel-btn").on("click", function(){
  		$("#BattleProgess").closeModal();
  		HideFocus();
	});
	$(".invite-send-btn").on("click", function(){
		var emails = $("#invite-to").val();
		var message = $("#invite-body").val();
		var err = '';
		if(emails == ''){
			err = "Please enter at least one email address <br>";	
		}
		
		if(err == ''){
			$.ajax({ url: '../php/webService.php',
		         data: {action: "SubmitInviteUsers", emails: emails, message: message  },
		         type: 'post',
		         success: function(output) {
		         	Toast("Invites will be sent shortly!");
         	  		$("#BattleProgess").closeModal();
  					HideFocus();
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
		}else{
			$(".invite-error-msg").show();
			$(".invite-error-msg").html(err);
		}
		
	});
}
 
