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
 				ShowUserPreviewCard($(this).find(".user-preview-card"));
 			});
  			Waves.displayEffect();
 			FilterResults();
 			$(".game-discover-card .card-image").on("click", function(e){ 
 				e.stopPropagation(); 
 				CloseSearch();
				//Clear search input
	 			$(".searchInput input").val('');
	 			$('html').unbind();
 				ShowGame($(this).parent().attr("data-gbid"), $("#discover")); 
 			});
 			$(".card-game-tier-container").on("click", function(e){ GameCardActions($(this)); });
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
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayDiscoverHome" },
     type: 'post',
     success: function(output) {
     			$("#discoverInnerContainer").html(output);
     			FilterCategories();
 				AttachDiscoverHomeEvents();
 				AttachDiscoverSecondaryEvents();
      			Waves.displayEffect();
      			GAPage('Discover', '/discover');
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
	$(".card-game-tier-container").on("click", function(e){ GameCardActions($(this)); });
	//User
 	$(".user-discover-card").on("click", function(e){
 	 	e.stopPropagation();
 		ShowUserPreviewCard($(this).find(".user-preview-card"));
 	});
	//Category
	$(".ViewBtn, .discoverCategoryHeader .categoryIcon").on("click", function(){
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
  			$(".card-game-tier-container").on("click", function(e){ GameCardActions($(this)); });
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
  			$(".card-game-tier-container").on("click", function(e){ GameCardActions($(this)); });
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
	FilterCategories();
}
 
