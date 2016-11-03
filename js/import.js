function StartSteamImport(userid, forceImport){
	ShowPopUp("");
	ShowLoader($("#universalPopUp"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayStartSteamImport", userid: userid },
     type: 'post',
     success: function(output) {
 		$("#universalPopUp").html(output); 
		$('.start-steam-import').on("click", function(){
			if($("#steamname").val() == ''){
				$(".import-validation").html("Please enter a valid Vanity ID or Profile ID");
			}else{
					if($('#importFullReset:checked').length > 0)
						ImportSteamGames(userid, forceImport, "true");
					else
						ImportSteamGames(userid, forceImport, "false");
			}
		});
		$("#steamname").on('keypress keyup', function (e) {
			if (e.keyCode === 13) { 
				e.stopPropagation(); 	
				if($("#steamname").val() == ''){
					$(".import-validation").html("Please enter a valid Vanity ID or Profile ID");
				}else{
					if($('#importFullReset:checked').length > 0)
						ImportSteamGames(userid, forceImport, "true");
					else
						ImportSteamGames(userid, forceImport, "false");
				}
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

function ImportSteamGames(userid, forceImport, fullreset){
	var steamID = $("#steamname").val();
	$("#universalPopUp").closeModal();
	var windowWidth = $(window).width();
	$(".indicator").css({"display":"none"});
	$(".active").removeClass("active");
    $("#profile").css({"display":"inline-block", "left": -windowWidth});
    $("#activity, #discover, #admin, #profiledetails, #settings, #notifications, #user, #landing").css({"display":"none"});
    $("#activity, #discover, #admin, #profiledetails, #settings, #notifications, #user, #landing").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#profile").velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
	
	
	window.scrollTo(0, 0);
	if(forceImport){
		$("#profileInnerContainer").html("<div style='width:100%;margin-top:100px;text-align:center;font-size:2em;'><i class='fa fa-steam'></i> Importing your game Library from Steam</div><div style='font-size:1em'>This can take awhile, possibly a few minutes, depending on the size of your game library.</div><br><div class='progress' style='width:80%;margin:0 10%;background-color:rgba(0,0,0,0.1);'><div class='determinate import-load-prog' style='width: 0%;background-color:rgba(0,0,0,0.8);'></div></div>");
		$.ajax({ url: '../php/webService.php',
	     data: {action: "GetEstimatedTimeToImport", steamname: steamID},
	     type: 'post',
	     success: function(output) {
			var totalGames = parseInt($.trim(output));
			var percent = 0;
			var totalTime = totalGames * 2.85;//22.5;
			var interval = Math.floor(totalTime / 100);
			var progress = setInterval(function(){ 
				$(".import-load-prog").css({"width":percent+"%"}); 
				percent++; 
				if(percent > 100){
					clearInterval(progress);
				}
			}, interval);

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
  	}else
  		ShowLoader($("#profileInnerContainer"), 'big', "<div style='width:100%;margin-top:100px;text-align:center;font-size:2em;'><i class='fa fa-steam'></i> Loading your Steam Library Import</div><div style='font-size:1em'></div><br><br><br>");
	
	$.ajax({ url: '../php/webService.php',
	     data: {action: "ImportSteamGames", steamname: steamID, forceImport: forceImport, fullreset: fullreset },
	     type: 'post',
	     success: function(output) {
	 		$("#profileInnerContainer").html(output);
	 		AttachImportSearchEvents(userid);
	 		$(".collection-box-container").on("click", function(){
	 			var collectionid = $(this).attr("data-id");	
	 			DisplayCollectionDetails(collectionid, "SteamImport", userid);
	 		});
	     },
	        error: function(x, t, m) {
		        if(t==="timeout") {
		            ToastError("Server Timeout");
		        } else {
		            ToastError(t);
		        }
	    	},
	    	timeout:1145000
		});
}

function AttachImportSearchEvents(userid){
	$(".import-game-search-btn,.import-game-search, html, .import-map-game, .import-steam-reimport, .import-ignore-game, .import-map-to-skip-game, .import-report-game, .import-report-game, .unmapped-next, .mapped-prev, .unmapped-prev, .mapped-next, .backButton, .import-map-suggest-image").unbind();
	$('.import-game-search-btn').on('click', function(e){ 
		e.stopPropagation(); 
		SearchForGameImport($(this).parent().parent().find(".import-game-search").val(), $(this).parent().parent(), userid);
	});
	$(".import-game-search").on('keypress keyup', function (e) {
		if (e.keyCode === 13) { 
			SearchForGameImport($(this).parent().parent().find(".import-game-search").val(), $(this).parent().parent(), userid);
		}
	});
	$('html').click(function(){
		if($(".import-game-search-results").is(":visible"))
			$(".import-game-search-results").hide(250);
	});
	$(".import-map-game").on("click", function(){
		MapGame($(this), userid);
	});
	
	$(".import-steam-reimport").on('click', function(){
		StartSteamImport($(this).attr("data-id"), true);
	});
	
	$(".import-ignore-game").on("click", function(){
		var importID = $(this).parent().attr("data-importid");	
		$(this).parent().parent().css({"background-color": "#C62828"});
		$(this).parent().parent().find(".col").css({"opacity":"0"});
		$(this).parent().parent().append("<div style='text-align:center;color:white;font-size:1.5em;line-height:69px;position:absolute;top:0;left:0;width:100%'>Game Ignored</div>");
		var row = $(this).parent().parent().parent();
		setTimeout(function(){
			row.hide(250);
		}
		,500);
		
		var gbid = row.find(".import-game-search-selected").attr("data-gbid");
		var auditid = $(this).attr("data-id");
		UpdateUITotals();
		$.ajax({ url: '../php/webService.php',
		     data: {action: "IgnoreGame", importID: importID, gbid: gbid, auditid:auditid },
		     type: 'post',
		     success: function(output) {
				GetNextUnmappedRow(userid);
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
		UpdateUITotals();
		$.ajax({ url: '../php/webService.php',
		     data: {action: "TrashGame", importID: importID, gbid: gbid, auditid:auditid },
		     type: 'post',
		     success: function(output) {
				GetNextUnmappedRow(userid);
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
	
	$(".import-report-game").on("click", function(){
		var importID = $(this).parent().attr("data-importid");	
		$(this).parent().parent().css({"background-color": "#EF6C00"});
		$(this).parent().parent().find(".col").css({"opacity":"0"});
		$(this).parent().parent().append("<div style='text-align:center;color:white;font-size:1.5em;line-height:69px;position:absolute;top:0;left:0;width:100%'>Game Reported to Admin's</div>");
		var row = $(this).parent().parent().parent();
		setTimeout(function(){
			row.hide(250);
		}
		,500);
		
		var gbid = row.find(".import-game-search-selected").attr("data-gbid");
		var auditid = $(this).attr("data-id");
		UpdateUITotals("reported");
		$.ajax({ url: '../php/webService.php',
		     data: {action: "ReportGame", importID: importID, gbid: gbid, auditid:auditid },
		     type: 'post',
		     success: function(output) {
				GetNextUnmappedRow(userid);
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
	$(".backButton").on("click", function(){
		DisplayUserCollection(userid);	
	});
}

function MapGame(element, userid){
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
		UpdateUITotals("mapped");
		$.ajax({ url: '../php/webService.php',
		     data: {action: "MapGame", importID: importID, gbid: gbid, auditid:auditid },
		     type: 'post',
		     success: function(output) {
		     	GetNextUnmappedRow(userid);
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

function UpdateUITotals(type){
	var total = parseInt($(".import-unmapped-total").html().replace(",","")) - 1;
	$(".import-unmapped-total").html(total);
	
	if(type == "reported"){
		if($(".import-report-total").length > 0){
			var rpttotal = parseInt($(".import-report-total").html().replace(",","")) + 1;
			$(".import-report-total").html(rpttotal);
		}
	}
	
	if(type == "mapped"){
		if($(".import-mapped-total").length > 0){
			var rpttotal = parseInt($(".import-mapped-total").html().replace(",","")) + 1;
			$(".import-mapped-total").html(rpttotal);	
		}
	}
}

function GetNextUnmappedRow(userid){
	//var curroffset = $(".import-results-offset").attr("data-offset");
	var offset = 25;
	$.ajax({ url: '../php/webService.php',
     data: {action: "NextUnmappedRow", offset: offset },
     type: 'post',
     success: function(output) {
		$(".import-unmapped-games-container").append(output);
		AttachImportSearchEvents(userid);
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

function SearchForGameImport(search, element, userid){
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
					MapGame($(this), userid);
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
