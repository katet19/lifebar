function ShowAdminHome(){
	ShowAdminMainContent();
	//ShowAdminSecondaryContent();
}

function ShowAdminMainContent(){
	var windowWidth = $(window).width();
    $("#admin").css({"display":"inline-block", "left": -windowWidth});
    $("#activity, #ranking, #discover, #analytics, #notifications, #user").css({"display":"none"});
	$("#activity, #ranking, #discover, #analytics, #notifications, #user").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
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
	$(".admin-manage-issues").on('click', function(){
		GLOBAL_HASH_REDIRECT = "NO";
		DisplayUserFeedback();
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
	$(".admin-db-threads").on("click", function(){
		GLOBAL_HASH_REDIRECT = "NO";
		DisplayDBThreads();
	});
	$(".admin-manage-reported-games").on("click", function(){
		GLOBAL_HASH_REDIRECT = "NO";
		DisplayReportedGames();
	});
	$(".clear-search-cache-btn").on("click", function(){
		ClearSearchCache();	
	});
	$(".admin-ref-pts-sch").on("click", function(){
		GLOBAL_HASH_REDIRECT = "NO";
		DisplayRefPtSchedule();
	});
}


function ClearSearchCache(){
	var searchstring = $("#search-cache-input").val();
	$.ajax({ url: '../php/webService.php',
     data: {action: "ClearSearchCache", searchstring: searchstring },
     type: 'post',
     success: function(output) {
		Toast("Search cache was cleared for all searchs that contained '"+searchstring+"'");
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

function DisplayDBThreads(){
	ShowLoader($("#adminInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayDBThreads" },
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

function DisplayRefPtSchedule(){
	ShowLoader($("#adminInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayRefPtSchedule" },
     type: 'post',
     success: function(output) {
 		$("#adminInnerContainer").html(output);
		AttachRefPtEvents();
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

function AttachRefPtEvents(){
	$('.admin-schedule-insert-before').on("click", function(){
		DisplayRefPtPicker("", true, "before", $(this));
	});
	$('.admin-schedule-insert-after').on("click", function(){
		DisplayRefPtPicker("", true, "after", $(this));
	});
	$('.admin-schedule-insert-remove').on("click", function(){

	});
	$('.admin-schedule-save-all').on("click", function(){
		var savestring = "";
		$(".admin-schedule-ref-row").each(function(){
			var id = $(this).attr("data-id");
			var date = $(this).find(".admin-schedule-ref-date-input").val();
			savestring = savestring + id + "," + date + "||";
		});
		$.ajax({ url: '../php/webService.php',
		data: {action: "SaveRefPtSchedule", savestring: savestring },
		type: 'post',
		success: function(output) {
			Toast("Saved changes to DRP Schedule!")
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

function DisplayRefPtPicker(searchstring, isNew, position, elem){
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayRefPtPicker", searchstring: searchstring, isNew: isNew },
     type: 'post',
     success: function(output) {
 		ShowPopUp(output);
 		$(".ref-pt-search-picker").on('keypress keyup', function (e) {
			if (e.keyCode === 13) { 
				e.stopPropagation(); 	
				if(isNew)
					isNew = $("#ref-pt-search-new").is(':checked');
					
				DisplayRefPtPicker($(this).val(), isNew, position, elem);
			} 
		});
		$(".ref-pt-search-select").on("click", function(){
			var thisid = $(this).attr('data-id');
			var lastdate = $(".admin-schedule-ref-date-input").last().val();
			var inserted = "<div class='row admin-schedule-ref-row' data-id='"+thisid+"'>";
			inserted = inserted + "<div class='admin-schedule-ref-date'><input class='admin-schedule-ref-date-input' type='text' style='width:100px;' value='"+lastdate+"'><span>NEW</span></div>";
			inserted = inserted + "<div class='admin-schedule-ref-question'><span>"+$(this).parent().find("span").text()+"</span></div></div>";
			$(".admin-schedule-ref-row").last().after(inserted);
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

function DisplayReportedGames(){
	ShowLoader($("#adminInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayAdminManageReportedGames" },
     type: 'post',
     success: function(output) {
 		$("#adminInnerContainer").html(output);
		AttachReportedGamesEvents();
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

function AttachReportedGamesEvents(){
	$(".import-game-search-btn,.import-game-search, html, .import-map-game, .import-steam-reimport, .import-ignore-game, .import-map-to-skip-game, .import-report-game, .import-report-game, .unmapped-next, .mapped-prev, .unmapped-prev, .mapped-next, .backButton, .import-map-suggest-image").unbind();
	$('.import-game-search-btn').on('click', function(e){ 
		e.stopPropagation(); 
		SearchForGameReportedImport($(this).parent().parent().find(".import-game-search").val(), $(this).parent().parent(), '-1');
	});
	$(".import-game-search").on('keypress keyup', function (e) {
		if (e.keyCode === 13) { 
			SearchForGameReportedImport($(this).parent().parent().find(".import-game-search").val(), $(this).parent().parent(), '-1');
		}
	});
	$('html').click(function(){
		if($(".import-game-search-results").is(":visible"))
			$(".import-game-search-results").hide(250);
	});
	$(".import-map-game").on("click", function(){
		MapReportedGame($(this));
	});
	$(".import-map-to-skip-game").on("click", function(){
		var importID = $(this).parent().attr("data-importid");	
		$(this).parent().parent().css({"background-color": "#3F51B5"});
		$(this).parent().parent().find(".col").css({"opacity":"0"});
		$(this).parent().parent().append("<div style='text-align:center;color:white;font-size:1.5em;line-height:69px;position:absolute;top:0;left:0;width:100%'>Game will always be hidden</div>");
		var row = $(this).parent().parent().parent();
		setTimeout(function(){
			row.hide(250);
		}
		,500);
		
		var gbid = row.find(".import-game-search-selected").attr("data-gbid");
		var auditid = $(this).attr("data-id");
		$.ajax({ url: '../php/webService.php',
		     data: {action: "TrashGame", importID: importID, gbid: gbid, auditid:auditid },
		     type: 'post',
		     success: function(output) {
				GetNextReportedRow();
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
	$(".import-map-suggest-image").on("click", function(){
		ShowPopUp("<img style='height:100%;width:100%;' src='"+$(this).attr("data-image")+"'>");		
	});
}

function SearchForGameReportedImport(search, element, userid){
	var searchResults = element.parent().find(".import-game-search-results");
	searchResults.parent().css({"z-index":"1"});
	searchResults.show(250);
	var test = element.parent().find(".import-game-search-results");
	ShowLoader(element.parent().find(".import-game-search-results"), 'small', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "AdminGameSearch", search: search },
     type: 'post',
     success: function(output) {
		element.parent().find(".import-game-search-results").html(output);
 		$(".import-game-search-results li").on('click', function(){
 			var gbid = $(this).attr("data-gbid");
 			var image = $(this).attr("data-image");
 			if($(this).parent().parent().next().find(".import-warning-msg").length  > 0){
 				$(this).parent().parent().next().find(".import-warning-msg").remove();
 				$(this).parent().parent().next().find(".import-warning-msg-txt").remove();
 			}else{
 				$(this).parent().parent().next().find(".import-map-suggest-image").remove();
 			}
 			$(this).parent().parent().next().append("<div class='import-map-suggest-image' data-image='"+image+"' style='height:69px;width:200px;background:url("+image+") 50% 25%;-webkit-background-size: cover; background-size: cover; -moz-background-size: cover; -o-background-size: cover;'></div>");
 			$(this).addClass("import-game-search-selected");
 			element.find(".import-game-search").val($(this).find(".actual-title").text());
 			var disabled = element.parent().parent().parent().find(".import-disabled-game");
 			if(disabled.length > 0){
 				disabled.css({"opacity":"1"});
 				disabled.removeClass("import-disabled-game");
 				disabled.addClass("import-map-game");
 				$(".import-map-game").unbind();
 				$(".import-map-game").on("click", function(){
					MapReportedGame($(this));
				});
 			}
 			$(".import-map-suggest-image").unbind();
 			$(".import-map-suggest-image").on("click", function(){
				ShowPopUp("<img style='height:100%;width:100%;' src='"+$(this).attr("data-image")+"'>");		
			});
 			searchResults.parent().css({"z-index":"0"});
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

function GetNextReportedRow(){
	//var curroffset = $(".import-results-offset").attr("data-offset");
	var offset = 25;
	$.ajax({ url: '../php/webService.php',
     data: {action: "NextReportedRow", offset: offset },
     type: 'post',
     success: function(output) {
		$(".import-unmapped-games-container").append(output);
		AttachReportedGamesEvents();
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

function MapReportedGame(element){
		var importID = element.parent().attr("data-importid");	
		element.parent().parent().css({"background-color": "#2E7D32"});
		element.parent().parent().find(".col").css({"opacity":"0"});
		element.parent().parent().append("<div style='text-align:center;color:white;font-size:1.5em;line-height:69px;position:absolute;top:0;left:0;width:100%'>Game Mapped</div>");
		var row = element.parent().parent().parent();
		setTimeout(function(){
			row.hide(250);
		}
		,500);
		
		var gbid = row.find(".import-game-search-selected").attr("data-gbid");
		var auditid = element.attr("data-id");
		$.ajax({ url: '../php/webService.php',
		     data: {action: "MapGame", importID: importID, gbid: gbid, auditid:auditid },
		     type: 'post',
		     success: function(output) {
		     	GetNextReportedRow();
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

function DisplayUserFeedback(){
	ShowLoader($("#adminInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayUserFeedback" },
     type: 'post',
     success: function(output) {
 		$("#adminInnerContainer").html(output);

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
	//var h =  $("#adminInnerContainer").height();
	//$("#admin-review-iframe").css({'height': h });
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
 			element.find(".admin-review-search").val($(this).find(".actual-title").text());
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
