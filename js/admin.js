function ShowAdminHome(){
	ShowAdminMainContent();
	//ShowAdminSecondaryContent();
}

function ShowAdminMainContent(){
	var windowWidth = $(window).width();
    $("#admin").css({"display":"inline-block", "left": -windowWidth});
    $("#activity, #discover, #analytics, #game, #notifications, #user, #profile").css({"display":"none"});
	$("#activity, #discover, #analytics, #game, #notifications, #user, #profile").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#admin").velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	window.scrollTo(0, 0);
  	ShowLoader($("#adminInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayAdmin" },
     type: 'post',
     success: function(output) {
     			$("#adminInnerContainer").html(output);
				AttachAdminEvents();
      			Waves.displayEffect();
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

function ShowAdminSecondaryContent(){
  	ShowLoader($("#sideContainer"), 'big', "<br><br><br>");
  	ClearSideContent();
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayAdminSecondaryContent" },
     type: 'post',
     success: function(output) {
     			$("#sideContainer").html(output);
				//AttachEvents
     			SideContentPush($("#sideContainer").html());
     			SideContentEventPush(AttachDiscoverSecondaryEvents);
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

function AttachAdminEvents(){
	$(".admin-run-feed-collector, .admin-manage-pending-reviews").unbind();
	$(".admin-run-feed-collector").on('click', function(){
		ShowPopUp("<iframe src='http://lifebar.io/utilities/controller_rssFeeds.php' style='width:100%;min-height:600px;height:100%;'/>");	
	});
	$(".admin-run-game-updater").on('click', function(){
		ShowPopUp("<iframe src='http://lifebar.io/utilities/controller_gameUpdater.php' style='width:100%;min-height:600px;height:100%;'/>");	
	});
	$(".admin-run-calc-user-weave").on('click', function(){
		ShowPopUp("<iframe src='http://lifebar.io/utilities/controller_weave.php' style='width:100%;min-height:600px;height:100%;'/>");	
	});
	$(".admin-manage-pending-reviews").on('click', function(){
		GLOBAL_HASH_REDIRECT = "NO";
		DisplayPendingReviews();
	});
	$(".admin-ign-scrape").on('click', function(){
		ShowPopUp("<iframe src='http://lifebar.io/php/controller_reviewTranslator.php?run=Y' style='width:100%;min-height:600px;height:100%;'/>");	
	});
	$(".admin-ign-map").on('click', function(){
		GLOBAL_HASH_REDIRECT = "NO";
		DisplayUnmappedManager();
	});
	$(".admin-ign-map-reviewed").on('click', function(){
		GLOBAL_HASH_REDIRECT = "NO";
		DisplayUnmappedManagerReviewed();
	});
	$(".admin-badge-new").on('click', function(){
		GLOBAL_HASH_REDIRECT = "NO";
		DisplayBadgeNew();
	});
	$(".admin-badge-search").on('click', function(){
		GLOBAL_HASH_REDIRECT = "NO";
		DisplayBadgeManagement();
	});
	$(".admin-export-email").on("click", function(){
		GLOBAL_HASH_REDIRECT = "NO";
		DisplayEmailExport();
	});
	
}

function DisplayEmailExport(){
	ShowLoader($("#adminInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayEmailExport" },
     type: 'post',
     success: function(output) {
 		ShowPopUp(output);
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

function DisplayBadgeManagement(){
	ShowLoader($("#adminInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayBadgeManagement" },
     type: 'post',
     success: function(output) {
 		$("#adminInnerContainer").html(output);
		AttachBadgeManagmentEvents();
  		Waves.displayEffect();
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

function DisplayBadgeNew(){
	ShowLoader($("#adminInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayNewBadgeForm" },
     type: 'post',
     success: function(output) {
 		$("#adminInnerContainer").html(output);
		AttachBadgeManagmentEvents();
  		Waves.displayEffect();
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

var CONST_LAST_SEARCH = "";
function AttachBadgeManagmentEvents(){
	$("#badge_games, #badge_games_search, #badge_game_list_container").hide();
	
	$('.admin-review-search-btn').on('click', function(e){ 
		e.stopPropagation(); 
		SearchForGameBadge($(this).parent().parent().find(".admin-review-search").val(), $(this).parent().parent());
	});
	$(".admin-review-search").on('keypress keyup', function (e) {
		if (e.keyCode === 13) { 
			SearchForGameBadge($(this).parent().parent().find(".admin-review-search").val(), $(this).parent().parent());
		}
	});
	$('html').click(function(){
		if($(".admin-review-search-results").is(":visible"))
			$(".admin-review-search-results").hide(250);
	});
	$(".remove-game-from-badge").click(function(){
		$(this).parent().remove();
	});
	$(".badge-file-upload").click(function(){
		var html = "<div><span>Upload a custom badge image. Use the same name in the full http path in the previous form. </span><br><iframe src='http://lifebar.io/utilities/FileImageUploaderBadge.php' style='width:100%;border:none;'></iframe>";
		ShowPopUp(html);	
	});
	$("#badge_type").on('change', function() { 
		if($(this).val() == "Played" || $(this).val() == "Watched" || $(this).val() == "XP"){
			$("#badge_games, #badge_games_search, #badge_game_list_container").show();
		}else{
			$("#badge_games, #badge_games_search, #badge_game_list_container").hide();
		}
	});
	
	$('.tooltipped').tooltip({delay: 30});
	
	$("#badge_level").on('change', function() { 
		$(".c100").removeClass("one");
		$(".c100").removeClass("two");
		$(".c100").removeClass("three");
		$(".c100").removeClass("four");
		$(".c100").removeClass("five");
		if($(this).val() == "1"){
			$(".c100").addClass("one");
		}else if($(this).val() == "2"){
			$(".c100").addClass("two");
		}else if($(this).val() == "3"){
			$(".c100").addClass("three");
		}else if($(this).val() == "4"){
			$(".c100").addClass("four");
		}else if($(this).val() == "5"){
			$(".c100").addClass("five");
		}
	});
	
	$("#badge_custom_image").on('keyup', function(){
		var element = $(".badge-small .small span");
		if($("#badge_level").val() == "1"){
			element.css({"background":"url("+$("#badge_custom_image").val()+") 50% 25%","-webkit-background-size":"cover","background-size":"cover","-moz-background-size":"cover","-o-background-size":"cover"});
		}else if($("#badge_level").val() == "2"){
			element.css({"background":"url("+$("#badge_custom_image").val()+") 50% 25%","-webkit-background-size":"cover","background-size":"cover","-moz-background-size":"cover","-o-background-size":"cover"});
		}else if($("#badge_level").val() == "3"){
			element.css({"background":"url("+$("#badge_custom_image").val()+") 50% 25%","-webkit-background-size":"cover","background-size":"cover","-moz-background-size":"cover","-o-background-size":"cover"});
		}else if($("#badge_level").val() == "4"){
			element.css({"background":"url("+$("#badge_custom_image").val()+") 50% 25%","-webkit-background-size":"cover","background-size":"cover","-moz-background-size":"cover","-o-background-size":"cover"});
		}else if($("#badge_level").val() == "5"){
			element.css({"background":"url("+$("#badge_custom_image").val()+") 50% 25%","-webkit-background-size":"cover","background-size":"cover","-moz-background-size":"cover","-o-background-size":"cover"});
		}
		
	});
	
	$("#badge_name").on('keyup', function(){
		$(".badge-small-name").html($("#badge_name").val());	
	});
	
	$(".badge-small, .badge-large").on('click', function(){
		var badgeid = $(this).attr("data-id");
		TestBadge(badgeid);
	});

	$(".admin-new-badge-save").click(function(){
		var errors = "";
		var name = $("#badge_name").val();
		var desc = $("#badge_desc").val();
		var type = $("#badge_type").val();
		var diff = $("#badge_level").val();
		var threshold = $("#badge_threshold").val();
		var customimage = $("#badge_custom_image").val();
		var badgeimage = "";
		if(customimage != "http://lifebar.io/Images/Badges/PASTENAMEHERE.jpg" && customimage != ""){
			badgeimage = customimage;
		}
		var script = $("#badge_script").val();
		var category = $("#badge_category").val();
		var subcategory = $("#badge_sub_category").val();
		
		if(script == ""){
			errors = errors + "Validation script cannot be empty <br>";
		}
		if(threshold == "" || threshold < 1){
			errors = errors + "Threshold cannot be empty and must be greater than 0 <br>";
		}
		if(name == ""){
			errors = errors + "Badge name cannot be empty <br>";
		}
		if(desc == ""){
			errors = errors + "Badge description cannot be empty <br>";
		}
		if(badgeimage == ""){
			errors = errors + "Badge image cannot be empty <br>";
		}
		
		if(errors == "")
			SaveBadge(name, desc, type, diff, threshold, badgeimage, script, category, subcategory);
		else
			ToastError(errors);
	});
	
	$('.collapsible').collapsible({
      accordion : true // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    });
	
	$(".first-badge").trigger("click");
	/*$(".badge-category-header").on('click', function(){
			var category = $(this).html();
			ShowLoader($(this).next(), 'small', "<br>");
			DisplayBadgeForCategory(category, $(this).next());
	});*/
}

function DisplayBadgeForCategory(category, element){
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayBadgesForCategory", category: category },
     type: 'post',
     success: function(output) {
		element.html(output);
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

function SaveBadge(name, desc, type, diff, threshold, image, validation, category, subcategory){
	//ShowLoader($("#adminInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "SaveBadge", name: name, desc: desc, type: type, diff: diff, threshold: threshold, image: image, validation: validation, category: category, subcategory: subcategory },
     type: 'post',
     success: function(output) {
 		if($.trim(output) == "Passed"){
 			Toast("Badge Created");
 			DisplayBadgeNew();
 		}else{
 			ToastError($.trim(output));
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

function TestBadge(badgeid){
	//ShowLoader($("#adminInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "TestSpecificBadge", badgeid: badgeid },
     type: 'post',
     success: function(output) {
		Toast("Refresh to see results of badge test")
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

function DisplayUnmappedManager(){
	ShowLoader($("#adminInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayUnmappedManager" },
     type: 'post',
     success: function(output) {
 		$("#adminInnerContainer").html(output);
		AttachUnmappedManagerEvents();
  		Waves.displayEffect();
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

function DisplayUnmappedManagerReviewed(){
	ShowLoader($("#adminInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayManualMappingInReview" },
     type: 'post',
     success: function(output) {
 		$("#adminInnerContainer").html(output);
		AttachUnmappedManagerEvents();
  		Waves.displayEffect();
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

function DisplayPendingReviews(){
	ShowLoader($("#adminInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayPendingReviews" },
     type: 'post',
     success: function(output) {
 		$("#adminInnerContainer").html(output);
		AttachPendingReviewsEvents();
  		Waves.displayEffect();
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

function AttachUnmappedManagerEvents(){
	$('.admin-review-search-btn').on('click', function(e){ 
		e.stopPropagation(); 
		SearchForGame($(this).parent().parent().find(".admin-review-search").val(), $(this).parent().parent());
	});
	$(".admin-review-search").on('keypress keyup', function (e) {
		if (e.keyCode === 13) { 
			SearchForGame($(this).parent().parent().find(".admin-review-search").val(), $(this).parent().parent());
		}
	});
	$('html').click(function(){
		if($(".admin-review-search-results").is(":visible"))
			$(".admin-review-search-results").hide(250);
	});
	$(".admin-ign-dismiss-map").on('click', function(){
		var id = $(this).attr("data-id");
		var element = $(this);
		$.ajax({ url: '../php/webService.php',
	     data: {action: "DismissMapping", id: id },
	     type: 'post',
	     success: function(output) {
			element.parent().parent().parent().parent().hide(500);
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
	$(".admin-ign-save-map").on("click", function(e){
		var id = $(this).attr("data-id");
		var element = $(this);
		var gameid = element.parent().parent().parent().find(".admin-review-search-selected").attr("data-gbid");
		if(gameid > 0 || $(this).parent().parent().find("#map_"+id).val() == "" ){
			$.ajax({ url: '../php/webService.php',
		     data: {action: "MapReviewToGame", id: id, quote: $(this).parent().parent().find("#map_"+id).val(), gameid: gameid, link: $(this).attr("data-link"), criticid: $(this).attr("data-criticid"), tier: $(this).attr("data-tier") },
		     type: 'post',
		     success: function(output) {
				element.parent().parent().parent().parent().hide(500);
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
			if(gameid <= 0)
				Toast("Please map a game using the search field");
			else
				Toast("Please enter a quote for this game");
		}
	});
	$(".admin-ign-later-map").on("click", function(e){
		var id = $(this).attr("data-id");
		var element = $(this);
		$.ajax({ url: '../php/webService.php',
	     data: {action: "SaveMappingForLater", id: id },
	     type: 'post',
	     success: function(output) {
			element.parent().parent().parent().parent().hide(250);
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

function AttachPendingReviewsEvents(){
	$("select").material_select();
	var h =  $("#adminInnerContainer").height();
	$("#admin-review-iframe").css({'height': h });
	$('.admin-review-search-btn').on('click', function(e){ 
		e.stopPropagation(); 
		SearchForGame($(this).parent().parent().find(".admin-review-search").val(), $(this).parent().parent());
	});
	$(".admin-review-search").on('keypress keyup', function (e) {
		if (e.keyCode === 13) { 
			SearchForGame($(this).parent().parent().find(".admin-review-search").val(), $(this).parent().parent());
		}
	});
	$('html').click(function(){
		if($(".admin-review-search-results").is(":visible"))
			$(".admin-review-search-results").hide(250);
	});
	$(".admin-review-import").on('click', function(){
		if($(this).html() == "LOAD RSS"){
			$(".current-review").parent().parent().find(".admin-import-action").css({"display":"none"});
			$(".current-review").html("LOAD RSS");
			$(".current-review").parent().find(".admin-review-dismiss").html("DISMISS");
			$(".current-review").removeClass("current-review");
			var link = $(this).attr("data-link");
			$('#admin-review-iframe').attr('src',link);
			$(this).addClass("current-review");
			$(this).html("SAVE REVIEW");
			$(this).parent().find(".admin-review-dismiss").html("CANCEL");
			$(this).parent().parent().find(".admin-import-action").css({"display":"inline-block"});
			if($(window).width() < 600){
				$(".admin-card").hide();
				$(this).parent().parent().show();
				$(".admin-review-iframe-container").show();
				$(".admin-review-iframe-container").find(".admin-card").show();
			}
		}else if($(this).html() == "SAVE REVIEW"){
			SaveReview($(this).parent().parent());
		}
	});
	$(".admin-review-dismiss").on('click', function(){
		if($(this).html() == "CANCEL"){
			$(".current-review").parent().parent().find(".admin-import-action").css({"display":"none"});
			$(".current-review").html("LOAD RSS");
			$(".current-review").parent().find(".admin-review-dismiss").html("DISMISS");
			$(".current-review").removeClass("current-review");
			$('#admin-review-iframe').attr('src',"");
			if($(window).width() < 600){
				$(".admin-card").show();
				$(".admin-review-iframe-container").hide();
			}
		}else if($(this).html() == "DISMISS"){
			DismissPendingReview($(this).attr("data-id"), $(this).parent().parent().parent());
		}
	});
}

function SearchForGame(search, element){
	element.find(".admin-review-search-results").show(250);
	ShowLoader(element.find(".admin-review-search-results"), 'small', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "AdminGameSearch", search: search },
     type: 'post',
     success: function(output) {
		element.find(".admin-review-search-results").html(output);
 		$(".admin-review-search-results li").on('click', function(){
 			var gbid = $(this).attr("data-gbid");	
 			$(this).addClass("admin-review-search-selected");
 			element.find(".admin-review-search").val($(this).html());
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

function SearchForGameBadge(search, element){
	if(CONST_LAST_SEARCH == search){
		element.find(".admin-review-search-results").show(250);
	}else{
		element.find(".admin-review-search-results").show(250);
		ShowLoader(element.find(".admin-review-search-results"), 'small', "<br><br><br>");
		CONST_LAST_SEARCH = search;
		$.ajax({ url: '../php/webService.php',
	     data: {action: "AdminBadgeGameSearch", search: search },
	     type: 'post',
	     success: function(output) {
			element.find(".admin-review-search-results").html(output);
	 		$(".admin-review-search-results li").on('click', function(){
	 			var gameid = $(this).attr("data-gameid");	
	 			var image = $(this).attr("data-image");
	 			element.find("#badge_game_list").append("<li class='game-badge-list-item-selection' data-gameid='"+gameid+"' data-image='"+image+"'>"+$(this).html()+" <div class='game-badge-list-item remove-game-from-badge'>REMOVE</div> <div class='game-badge-list-item use-game-image-for-badge'>USE IMAGE</div></li>");
	 			
 				var games = "";
				$(".game-badge-list-item-selection").each(function(){
					games = games + "'" + $(this).attr("data-gameid") +"',";
				});	
				games = games.slice(0,-1);
				var newvalue = $(".game-badge-build-query").html(games);
	 			
	 			$(".remove-game-from-badge").click(function(){
						$(this).parent().remove();
		 				var games = "";
						$(".game-badge-list-item-selection").each(function(){
							games = games + "'" + $(this).attr("data-gameid") +"',";
						});	
						games = games.slice(0,-1);
						var newvalue = $(".game-badge-build-query").html(games);
				});
	 			$(".use-game-image-for-badge").click(function(){
	 				$(".game-badge-list-item-selection-usingimage").removeClass("game-badge-list-item-selection-usingimage");
					$("#badge_custom_image").val($(this).parent().attr("data-image"));
					$(this).parent().addClass("game-badge-list-item-selection-usingimage");
					var element = $(".badge-small .small span");
					if($("#badge_level").val() == "1"){
						element.css({"background":"url("+$("#badge_custom_image").val()+") 50% 25%","-webkit-background-size":"cover","background-size":"cover","-moz-background-size":"cover","-o-background-size":"cover"});
					}else if($("#badge_level").val() == "2"){
						element.css({"background":"url("+$("#badge_custom_image").val()+") 50% 25%","-webkit-background-size":"cover","background-size":"cover","-moz-background-size":"cover","-o-background-size":"cover"});
					}else if($("#badge_level").val() == "3"){
						element.css({"background":"url("+$("#badge_custom_image").val()+") 50% 25%","-webkit-background-size":"cover","background-size":"cover","-moz-background-size":"cover","-o-background-size":"cover"});
					}else if($("#badge_level").val() == "4"){
						element.css({"background":"url("+$("#badge_custom_image").val()+") 50% 25%","-webkit-background-size":"cover","background-size":"cover","-moz-background-size":"cover","-o-background-size":"cover"});
					}else if($("#badge_level").val() == "5"){
						element.css({"background":"url("+$("#badge_custom_image").val()+") 50% 25%","-webkit-background-size":"cover","background-size":"cover","-moz-background-size":"cover","-o-background-size":"cover"});
					}
				});
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
}

function SaveReview(element){
	var tier = element.find("#admin-review-tier").val();
	var link = element.find(".admin-review-import").attr("data-link");
	var quote = element.find("#admin-review-quote").val();
	var journalist = element.find("#admin-review-user").val();
	var first = element.find("#admin-review-first").val();
	var last = element.find("#admin-review-last").val();
	var gameid = element.find(".admin-review-search-selected").attr("data-gbid");
	var id = element.find(".admin-review-dismiss").attr("data-id");
	if(tier != "NO" && link != "" && quote != "" && (journalist != "NO" || (first != "" && last != "")) && journalist != "" && gameid != "" && gameid != undefined && id != ""){
		$.ajax({ url: '../php/webService.php',
	     data: {action: "SavePendingReview", tier: tier, link: link, quote: quote, journalist: journalist, first: first, last: last, gameid: gameid, id: id },
	     type: 'post',
	     success: function(output) {
			Toast("Saved Pending Review");
			element.parent().remove();
			if($(window).width() < 600){
				$(".admin-card").show();
				$(".admin-review-iframe-container").hide();
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
	}else{
		Toast("Missing criteria for saving review");
		//alert(tier+"||"+link+"||"+quote+"||"+journalist+"||"+gameid+"||"+id);
	}
}

function DismissPendingReview(id, element){
	$.ajax({ url: '../php/webService.php',
     data: {action: "DismissPendingReview", id: id },
     type: 'post',
     success: function(output) {
		Toast("Dismissed Pending Review");
		element.remove();
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

function HideAdminContainer(){
	var windowWidth = $(window).width();
    $("#admin").css({"display":"none"});
	$("#admin").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
}
