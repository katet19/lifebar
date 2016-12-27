function ShowGame(id, currentTab, isID, browserNav, gameTab){
	if(gameTab == "" || gameTab == undefined)
		gameTab = "Dashboard";
	LoadGame(id, currentTab, isID, browserNav, gameTab);
}

function LoadGame(gbid, currentTab, isID, browserNav, gameTab){
	$("#game").css({"display":"inline-block", "right": "-75%"});
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
	SCROLL_POS = $(window).scrollTop();
	$('body').css({'top': -($('body').scrollTop()) + 'px'}).addClass("bodynoscroll");
	$("body").append("<div class='lean-overlay' id='materialize-lean-overlay-1' style='z-index: 1002; display: block; opacity: 0.5;'></div>");
	$("#game.outerContainer").css({ "right": 0 });

	if(!isID){
		ShowLoader($("#gameInnerContainer"), 'big', "<br><br><br>");
		$.ajax({ url: '../php/webService.php',
	     data: {action: "DisplayGame", gbid: gbid },
	     type: 'post',
	     success: function(output) {
	 		$("#gameInnerContainer").html(output);
	 		var gameid = $("#gameContentContainer").attr("data-id");
	 		var title = $("#gameContentContainer").attr("data-title");
	 		AttachGameEvents(currentTab);
	 		GAPage('Game', '/game/'+title);
	 		
			if(gameTab == "Community")
				SwitchGameContent($(".game-community-tab"));
			else if(gameTab == "Report")
				SwitchGameContent($(".game-analyze-tab"));
			else if(gameTab == "Watch")
				SwitchGameContent($(".game-video-tab"));
			else if(gameTab == "MyXP")
				SwitchGameContent($(".game-myxp-tab"));
			else if(gameTab == "MyXPPlayed")
				AddPlayedFabEvent();
			else if(gameTab == "MyXPWatched")
				AddWatchedFabEvent('','','','','');
			else if(gameTab == "ReflectionPoints")
				SwitchGameContent($(".game-reflectionpoints-tab"));
			else if(gameTab == "SimilarGames")
				SwitchGameContent($(".game-similargames-tab"));
			else if(gameTab == "Collections")
				SwitchGameContent($(".game-collections-tab"));
			else if (gameTab.indexOf("User") >= 0){
				 var userdata = gameTab.split('/');
				 var userid = userdata[1];
				 var username = userdata[2];
	 			DisplayUserDetails(userid, username);
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
		ShowLoader($("#gameInnerContainer"), 'big', "<br><br><br>");
		var otherid = -1;
		if(gameTab.indexOf("User") >= 0){
			actionTaken = "DisplayGameViaIDWithUser";
			var gameData = gameTab.split('/');
			if(gameData[1].length > 0)
				otherid = gameData[1];
		}else{
			actionTaken = "DisplayGameViaID";
		}
		
		$.ajax({ url: '../php/webService.php',
		 data: {action: actionTaken, gameid: gbid, otherid: otherid },
		 type: 'post',
		 success: function(output) {
		 	$("#gameInnerContainer").html(output);
 		 	var gameid = $("#gameContentContainer").attr("data-id");
	 		var title = $("#gameContentContainer").attr("data-title");
		 	AttachGameEvents(currentTab);
		 	GAPage('Game', '/game/'+title);
		 	
			if(gameTab == "Community")
				SwitchGameContent($(".game-community-tab"));
			else if(gameTab == "Report")
				SwitchGameContent($(".game-analyze-tab"));
			else if(gameTab == "Watch")
				SwitchGameContent($(".game-video-tab"));
			else if(gameTab == "MyXP")
				SwitchGameContent($(".game-myxp-tab"));
			else if(gameTab == "MyXPPlayed")
				AddPlayedFabEvent();
			else if(gameTab == "MyXPWatched")
				AddWatchedFabEvent('','','','','');
			else if(gameTab == "ReflectionPoints")
				SwitchGameContent($(".game-reflectionpoints-tab"));
			else if(gameTab == "SimilarGames")
				SwitchGameContent($(".game-similargames-tab"));
			else if(gameTab == "Collections")
				SwitchGameContent($(".game-collections-tab"));
	 		else if (gameTab.indexOf("User") >= 0){
				 var userdata = gameTab.split('/');
				 var userid = userdata[1];
				 var username = userdata[2];
	 			DisplayUserDetails(userid, username);
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
}

function LoadGameDirect(gbid, currentTab, type, gameTab){
	var windowWidth = $(window).width();
    $("#game").css({"display":"inline-block", "right": "-75%"});
    $("#activity, #discover, #analytics, #admin, #notifications, #user, #landing, #profile").css({"display":"none"});
	$("#activity, #discover, #analytics, #admin, #notifications, #user, #landing, #profile").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
    $("#game.outerContainer").css({ "right": 0 });
	SCROLL_POS = $(window).scrollTop();
	$('body').css({'top': -($('body').scrollTop()) + 'px'}).addClass("bodynoscroll");
	$("body").append("<div class='lean-overlay' id='materialize-lean-overlay-1' style='z-index: 1002; display: block; opacity: 0.5;'></div>");
	ShowLoader($("#gameInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayGame", gbid: gbid },
     type: 'post',
     success: function(output) {
 		$("#gameInnerContainer").html(output);
 		var gameid = $("#gameContentContainer").attr("data-id");
 		var title = $("#gameContentContainer").attr("data-title");
 		GLOBAL_HASH_REDIRECT = "NO";
 		
   		if(gameTab == "Community")
			SwitchGameContent($(".game-community-tab"));
 		else if(gameTab == "Report")
 			SwitchGameContent($(".game-analyze-tab"));
 		else if(gameTab == "Watch")
		 	SwitchGameContent($(".game-video-tab"));
		else if(gameTab == "MyXP")
			SwitchGameContent($(".game-myxp-tab"));
		else if(gameTab == "MyXPPlayed")
				AddPlayedFabEvent();
		else if(gameTab == "MyXPWatched")
				AddWatchedFabEvent('','','','','');
		else if(gameTab == "ReflectionPoints")
			SwitchGameContent($(".game-reflectionpoints-tab"));
		else if(gameTab == "SimilarGames")
			SwitchGameContent($(".game-similargames-tab"));
		else if(gameTab == "Collections")
			SwitchGameContent($(".game-collections-tab"));
		else if (gameTab.indexOf("User") >= 0){
			var userdata = gameTab.split('/');
			var userid = userdata[1];
			var username = userdata[2];
			DisplayUserDetails(userid, username);
		}
 		
 		AttachGameEvents(currentTab);
 		if(type == "played"){
 			AddPlayedFabEvent();
 		}else{
 			AddWatchedFabEvent('','','','','');
 		}
 		GAPage('Game', '/game/'+title);
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

function AttachGameEvents(currentTab){
	$(".fixed-close-modal-btn, .lean-overlay").unbind();
	$(".fixed-close-modal-btn, .lean-overlay").on('click', function(){
		$("#game").css({ "right": "-75%" }); 
		$(".lean-overlay").each(function(){ $(this).remove(); } );
		setTimeout(function(){ $("#game").css({"display":"none"}); $('body').removeClass("bodynoscroll").css({'top': $(window).scrollTop(SCROLL_POS) + 'px'}); }, 300);
	});
	
	if($(window).width() < 600){
		$(".backButtonLabel").css({"padding":"0"});
		$("#gameInnerContainer .backContainer").show();
		$("#gameInnerContainer .backContainer").css({"top":"7px", "position":"absolute"});
	}

	if($(window).width() < 992){
		$(".GameTitle").on("click", function(e){
			e.stopPropagation();
			DisplayGameNav();
		});
	}

	AttachGameCardEvents();
	
	$("#game-slide-out li").on("click", function(){
		SwitchGameContent($(this));
	});
	$(".game-tab-first").click();
	var iconOnHover="";
	if($(".fixed-action-btn .game-add-played-btn").length > 0)
		iconOnHover = "mdi-hardware-gamepad";
	else if($(".fixed-action-btn .game-add-watched-btn").length > 0)
		iconOnHover = "mdi-action-visibility";
	else
		iconOnHover = "mdi-action-bookmark";
		
	$(".ShowInfoBtn").on('click', function(){
  		var html = $('#infoModal').html();
  		ShowPopUp(html);
	});
	AttachEditEvents();
 	$(".critic-name-container, .myxp-details-agree-listitem").on("click", function(e){
  		e.stopPropagation();
 		ShowUserProfile($(this).attr("data-id"));
 	});
 	AttachAgrees();
  	Waves.displayEffect();
  	
  	$(".detailsBtn").on('click', function(e){ 
  		var userid = $(this).attr("data-uid");
  		var username = $(this).attr("data-uname");
  		DisplayUserDetails(userid, username);
  	});
	 	
 	$(".ptalk-link-games").on("click", function(){
 		window.open("http://tidbits.io/c/games");
 	});
 	
 	$(".myxp-share-tier-quote").unbind();
 	$(".myxp-share-tier-quote").on('click', function(){
		var gameid = $("#gameContentContainer").attr("data-id");
		ShowShareModal("userxp", gameid+"-"+$(this).attr("data-userid"));
	});
 	$(".myxp-profile-tier-quote").on('click', function(){
		ShowUserProfile($(this).attr("data-userid"));
	});
	$(".edit-ref-pt").on("click", function(){
		var refptID = $(this).attr("data-id");
		EditReflectionPopUp(refptID);
	});
	$(".myxp-save-journal").on("click",function(){
		SaveJournalEntry($(this).attr("data-gameid"));
	});
	$(".myxp-journal-edit-btn").on("click", function(){
		ShowJournalEdit();
	});
	$(".myxp-cancel-journal").on("click", function(){
		HideJournalEdit();
	});
	$(".collection-box-container").on("click", function(e){
		e.stopPropagation();
		DisplayCollectionDetails($(this).attr("data-id"), 'GamePage', $(this).attr("data-userid"), false);	
	});
	$(".dashboard-collection-view").on("click", function(e){
		SwitchGameContent($(".game-collections-tab"));
	});
	$(".dashboard-similar-view").on("click", function(e){
		SwitchGameContent($(".game-similargames-tab"));
	});
	$(".dashboard-community-view").on("click", function(e){
		SwitchGameContent($(".game-community-tab"));
	});
	$(".game-ref-pt-btn").on("click", function(e){
		SwitchGameContent($(".game-reflectionpoints-tab"));
	});
	$(".knowledge-container").on("click", function(){
		 DisplayDeveloperDetails($(".userContainer").attr("data-id"), $(this).attr("data-objectid"), $(this).attr("data-progid"));
	 });
  	$(".badge-small").on("click", function(){
 		var id = $(this).attr("data-objectid");
 		var progid = $(this).attr("data-progid");
 		DisplayGearDetails($(".userContainer").attr("data-id"), id, progid);
 	});
	$(".game-unwatched-btn").on("click", function(){
		SwitchGameContent($(".game-video-tab"));
		$(".video-is-watched").hide();
		$(".video-show-watched").show();
	});
	$(".video-show-watched").on('click', function(){
		$(this).hide();
		$(".video-is-watched").show(250);
	});
	AttachEventsForReflectionPoints();
	$("select").material_select();
	DisplayFormResultsGraph();
 	AttachFloatingIconEvent(iconOnHover);
	AttachFloatingIconButtonEvents();
	AttachMyXPEvents();
	AttachCriticBookmark();
	AttachAnalyzeEvents();
	AttachVideoEvents();
	AttachWatchFromXP();
	AttachGameCardEvents();
}

function AttachGameCardEvents(){
	$(".game-card-quick-collection, .game-card-quick-played, .game-card-quick-watched, .game-card-quick-bookmark, .game-discover-card .card-image, .game-nav-title, .game-card-action-pick").unbind();
	$(".game-card-action-pick").on("click", function(){
		if($(this).attr("data-action") == "xp")
			GameCardAction($(this).attr("data-action"), $(this).attr("data-id"));
	});
	$(".game-card-quick-collection").on("click", function(e){
		e.stopPropagation();
		DisplayCollectionQuickForm($(this).parent().parent().parent(), $(this).parent().attr("data-id"), $(this).parent().attr("data-gbid"), true);
	});
	$(".game-card-quick-played").on("click", function(e){
		ShowGame($(this).parent().attr("data-gbid"), $("#discover"), false, false, 'MyXPPlayed');
	});
	$(".game-card-quick-watched").on("click", function(e){
		ShowGame($(this).parent().attr("data-gbid"), $("#discover"), false, false, 'MyXPWatched');
	});
	$(".game-card-quick-bookmark").on("click", function(e){
		if($(this).find(".nav-game-action-isBookmarked").length > 0){
			SubmitBookmark("RemoveBookmark", $(this).parent().attr("data-id"));
			$(this).find(".nav-game-action-btn").removeClass("nav-game-action-isBookmarked");
		}else{
			SubmitBookmark("AddBookmark", $(this).parent().attr("data-id"));
			$(this).find(".nav-game-action-btn").addClass("nav-game-action-isBookmarked");
		}
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

function AttachStarEvents(){
	$(".star-icon").hover(function(){
		$(this).text("star");
		var element = $(this).parent();
		if($(this).hasClass("star-icon-5")){
			element.find(".star-icon-4").text("star");
			element.find(".star-icon-3").text("star");
			element.find(".star-icon-2").text("star");
			element.find(".star-icon-1").text("star");
		}else if($(this).hasClass("star-icon-4")){
			element.find(".star-icon-5").text("star_border");
			element.find(".star-icon-3").text("star");
			element.find(".star-icon-2").text("star");
			element.find(".star-icon-1").text("star");
		}else if($(this).hasClass("star-icon-3")){
			element.find(".star-icon-5").text("star_border");
			element.find(".star-icon-4").text("star_border");
			element.find(".star-icon-2").text("star");
			element.find(".star-icon-1").text("star");
		}else if($(this).hasClass("star-icon-2")){
			element.find(".star-icon-5").text("star_border");
			element.find(".star-icon-4").text("star_border");
			element.find(".star-icon-3").text("star_border");
			element.find(".star-icon-1").text("star");
		}else if($(this).hasClass("star-icon-1")){
			element.find(".star-icon-5").text("star_border");
			element.find(".star-icon-4").text("star_border");
			element.find(".star-icon-3").text("star_border");
			element.find(".star-icon-2").text("star_border");
		}
	},
	function(){
		$(this).text("star_border");
		var element = $(this).parent();
		if($(this).hasClass("star-icon-5")){
			element.find(".star-icon-4").text("star_border");
			element.find(".star-icon-3").text("star_border");
			element.find(".star-icon-2").text("star_border");
			element.find(".star-icon-1").text("star_border");
		}else if($(this).hasClass("star-icon-4")){
			element.find(".star-icon-3").text("star_border");
			element.find(".star-icon-2").text("star_border");
			element.find(".star-icon-1").text("star_border");
		}else if($(this).hasClass("star-icon-3")){
			element.find(".star-icon-2").text("star_border");
			element.find(".star-icon-1").text("star_border");
		}else if($(this).hasClass("star-icon-2")){
			element.find(".star-icon-1").text("star_border");
		}else{

		}
		element.find(".star-icon-pre").text("star");
	});
	$(".star-icon").on("click", function(){
		var gameid = $(this).parent().parent().parent().attr("data-id");
		var tier = 0;
		var element = $(this).parent();
		var star = $(this);
		if($(this).hasClass("star-icon-5")){
			tier = 1;
		}
		else if($(this).hasClass("star-icon-4")){
			tier = 2;
		}
		else if($(this).hasClass("star-icon-3")){
			tier = 3;
		}
		else if($(this).hasClass("star-icon-2")){
			tier = 4;
		}
		else if($(this).hasClass("star-icon-1")){
			tier = 5;
		}

		element.find(".star-icon").each(function(){
			$(this).removeClass("tierTextColor1");
			$(this).removeClass("tierTextColor2");
			$(this).removeClass("tierTextColor3");
			$(this).removeClass("tierTextColor4");
			$(this).removeClass("tierTextColor5");
			$(this).removeClass("star-icon-pre");
		});
		if(star.hasClass("star-icon-5")){
			element.find(".star-icon-5").text("star"); element.find(".star-icon-5").addClass("tierTextColor1 star-icon-pre");
			element.find(".star-icon-4").text("star"); element.find(".star-icon-4").addClass("tierTextColor1 star-icon-pre");
			element.find(".star-icon-3").text("star"); element.find(".star-icon-3").addClass("tierTextColor1 star-icon-pre");
			element.find(".star-icon-2").text("star"); element.find(".star-icon-2").addClass("tierTextColor1 star-icon-pre");
			element.find(".star-icon-1").text("star"); element.find(".star-icon-1").addClass("tierTextColor1 star-icon-pre");
		}
		else if(star.hasClass("star-icon-4")){
			element.find(".star-icon-5").text("star_border");
			element.find(".star-icon-4").text("star"); element.find(".star-icon-4").addClass("tierTextColor2 star-icon-pre");
			element.find(".star-icon-3").text("star"); element.find(".star-icon-3").addClass("tierTextColor2 star-icon-pre");
			element.find(".star-icon-2").text("star"); element.find(".star-icon-2").addClass("tierTextColor2 star-icon-pre");
			element.find(".star-icon-1").text("star"); element.find(".star-icon-1").addClass("tierTextColor2 star-icon-pre");
		}
		else if(star.hasClass("star-icon-3")){
			element.find(".star-icon-5").text("star_border");
			element.find(".star-icon-4").text("star_border"); 
			element.find(".star-icon-3").text("star"); element.find(".star-icon-3").addClass("tierTextColor3 star-icon-pre");
			element.find(".star-icon-2").text("star"); element.find(".star-icon-2").addClass("tierTextColor3 star-icon-pre");
			element.find(".star-icon-1").text("star"); element.find(".star-icon-1").addClass("tierTextColor3 star-icon-pre");
		}
		else if(star.hasClass("star-icon-2")){
			element.find(".star-icon-5").text("star_border"); 
			element.find(".star-icon-4").text("star_border");
			element.find(".star-icon-3").text("star_border");
			element.find(".star-icon-2").text("star"); element.find(".star-icon-2").addClass("tierTextColor4 star-icon-pre");
			element.find(".star-icon-1").text("star"); element.find(".star-icon-1").addClass("tierTextColor4 star-icon-pre");
		}
		else if(star.hasClass("star-icon-1")){
			element.find(".star-icon-5").text("star_border");
			element.find(".star-icon-4").text("star_border");
			element.find(".star-icon-3").text("star_border");
			element.find(".star-icon-2").text("star_border");
			element.find(".star-icon-1").text("star"); element.find(".star-icon-1").addClass("tierTextColor5 star-icon-pre");
		}

		$.ajax({ url: '../php/webService.php',
				data: {action: "SaveStarRank", gameid: gameid, tier: tier, rank: -1 },
				type: 'post',
				success: function(output) {
					//GameCardAction("tier", gameid);
					var datasplit = $.trim(output).split("|****|");
					var contentsplit = datasplit[0].split("|**|");
					var xpGain = parseInt(datasplit[1]);
					for (var i in contentsplit) {
						if($.trim(contentsplit[i]) != ""){
							ToastProgress(contentsplit[i]);
						}
					}
					var timeoutcounter = 1000;
					$(".bp-progress-item-bar").each(function(){
						var after = $(this).find(".bp-progress-item-bar-after");
						var before = $(this).find(".bp-progress-item-bar-before");
						var lvlup = $(this).parent().find(".bp-progress-item-levelup");
						setTimeout(function(e){
							var width = after.attr("data-width");
							after.css({"width":width});
							if(lvlup.attr("data-levelup") == "Yes"){
								lvlup.html("LEVEL UP!");
								setTimeout(function(e){
									before.css({"background-color":"#66BB6A"});
									after.css({"background-color":"#66BB6A"});
									lvlup.html("Level " + lvlup.attr("data-newlevel"));
								}, 1000);
							}
						}, timeoutcounter);
						if(lvlup.attr("data-levelup") == "Yes")
							timeoutcounter = timeoutcounter + 1750;
						else
							timeoutcounter = timeoutcounter + 1000;
					});
					
					var lifebar = $("#navigation-header").find(".lifebar-container");
					var min = lifebar.attr('data-min');
					var max = lifebar.attr('data-max');
					var newXp = parseInt(lifebar.attr('data-xp')) + xpGain;
					if(newXp > max){
						var newLevel = parseInt(lifebar.find(".lifebar-bar-level-header span").text()) + 1;
						lifebar.find(".lifebar-bar-level-header span").text(newLevel);
						ToastProgress("<div style='min-width:400px;text-align:left;'><div class='levelupToastText' style='padding:10px 0;font-size:2em;font-weight:bold;'>LEVEL UP</div> <div>You have reached Level <span style='font-weight:bold'>" + newLevel + "</span></div></div>");
						min = Math.ceil(Math.pow((newLevel-1) / 0.45, 2));
						max = Math.ceil(Math.pow(newLevel / 0.45, 2)) - 1;
						lifebar.attr('data-min', min);
						lifebar.attr('data-max', max);
					}
					lifebar.attr('data-xp', newXp);
					var newLength = Math.round(((newXp - min) / (max - min)) * 100);
					$(".lifebar-fill-min-circle").css({"width": newLength+"%"});
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

function GameCardAction(action, gameid){
	if(action == "more" || action == "bookmark"){

	}else{
		$("#gamemini.outerContainer").css({"display":"inline-block", "right": "-40%"});
		SCROLL_POS = $(window).scrollTop();
		$('body').css({'top': -($('body').scrollTop()) + 'px'}).addClass("bodynoscroll");
		$("#gamemini.outerContainer").css({ "right": "0" });
		ShowLoader($("#gameminiInnerContainer"), 'big', "<br><br><br>");
		$("body").append("<div class='lean-overlay' id='materialize-lean-overlay-1' style='z-index: 1002; display: block; opacity: 0.5;'></div>");

		if(action == "tier"){
			$.ajax({ url: '../php/webService.php',
				data: {action: "ShowTierModal", gameid: gameid },
				type: 'post',
				success: function(output) {
					$("#gameminiInnerContainer").html(output);
					$('.collapsible').collapsible();
					$(".fixed-close-modal-btn, .lean-overlay, .cancel-btn").unbind();
					$(".fixed-close-modal-btn, .lean-overlay, .cancel-btn").on('click', function(){
						HideFocus();
						$("#gamemini").css({ "right": "-40%" }); 
						$(".lean-overlay").each(function(){ $(this).remove(); } );
						setTimeout(function(){ $("#gamemini").css({"display":"none"}); $('body').removeClass("bodynoscroll").css({'top': $(window).scrollTop(SCROLL_POS) + 'px'}); }, 300);
					});
					$(".tier-modal-add-btn").on("click", function(){
						$(".tier-modal-current-game").each(function(){
							$(this).hide();
						});
						$(".tier-modal-add-btn").each(function(){
							$(this).show();
						});
						$(this).hide();
						$(this).parent().find(".tier-modal-current-game").show(250);
					});
					$(".modal-rank-item").on("click",function(){
						var rank = $(this).attr("data-internalrank");
						$(".modal-rank-item").each(function(){
							var thisrank = parseInt($(this).attr("data-internalrank"));
							$(this).find(".modal-rank-item-rank").text(thisrank);
							if(thisrank >= rank){
								thisrank = thisrank + 1;	
								$(this).find(".modal-rank-item-rank").text(thisrank);
							}
						});
						$(".modal-rank-active-game").hide(100);
						$(this).parent().find(".modal-rank-active-game").show(300); 
					});
					$(".save-btn").on("click", function(){
						
						$.ajax({ url: '../php/webService.php',
							data: {action: "SaveStarRank", gameid: gameid, tier: tier, rank: rank },
							type: 'post',
							success: function(output) {
								$(".cancel-btn").click();
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
		}else if(action == "xp"){
			$.ajax({ url: '../php/webService.php',
				data: {action: "ShowXPModal", gameid: gameid },
				type: 'post',
				success: function(output) {
					$("#gameminiInnerContainer").html(output);
					$('.collapsible').collapsible();
					$('textarea#myxp-quote').characterCounter();
					$(".fixed-close-modal-btn, .lean-overlay, .cancel-btn").unbind();
					$(".fixed-close-modal-btn, .lean-overlay, .cancel-btn").on('click', function(){
						var windowWidth = $(window).width();
						HideFocus();
						$("#gamemini").css({ "right": "-40%" }); 
						$(".lean-overlay").each(function(){ $(this).remove(); } );
						setTimeout(function(){ $("#gamemini").css({"display":"none"}); $('body').removeClass("bodynoscroll").css({'top': $(window).scrollTop(SCROLL_POS) + 'px'}); }, 300);
					});
					$('select').material_select();
					$(".modal-xp-header-advanced").on("click", function(){
						if($(".modal-xp-advanced-options-container").hasClass("modal-xp-advanced-options-container-active")){
							$(".modal-xp-advanced-options-container").removeClass("modal-xp-advanced-options-container-active");
							$(this).find(".material-icons").text("add");
						}else{
							$(".modal-xp-advanced-options-container").addClass("modal-xp-advanced-options-container-active");
							$(this).find(".material-icons").text("remove");
						}
					});
					$(".modal-xp-emoji-icon").on('click', function(){
						$(".modal-xp-emoji-icon-active").removeClass("modal-xp-emoji-icon-active");
						$(this).addClass("modal-xp-emoji-icon-active");
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
}

function SwitchGameContent(elem){
	if($("#game-slide-out li.active").attr("data-tab") != elem.attr("data-tab")){
		$("#game-slide-out li.active").removeClass("active");
		elem.addClass("active");
		$(".game-tab-active").removeClass("game-tab-active");
		$("#"+elem.attr("data-tab")).addClass("game-tab-active");
		$(".video-is-watched").show();
		$(".video-show-watched").hide();
		if((elem.attr("data-tab") == "game-community-tab" || elem.attr("data-tab") == "game-community-others-tab") && $("#game-community-others-tab .game-community-box").length > 0)
		{
			$(".game-longform-tab").hide(100);
			$(".game-community-others-tab").show(100);
		}
		else if(elem.attr("data-tab") == "game-longform-tab" || elem.attr("data-tab") == "game-myxp-tab")
		{
			$(".game-community-others-tab").hide(100);
			$(".game-longform-tab").show(100);
			var box = $("#game-myxp-tab").find(".myxp-details-container").last();
			if(box.length > 0)
				$(".myxp-vert-line").css({"bottom": (box.height() - 30)+"px"});
		}
		else
		{
			$(".game-longform-tab").hide(100);
			$(".game-community-others-tab").hide(100);
		}

		var gameid = $("#gameContentContainer").attr("data-id");
		var title = $("#gameContentContainer").attr("data-title");
		GLOBAL_HASH_REDIRECT = "URL";
		UpdateBrowserHash("game/"+gameid+"/"+title+"/"+elem.attr("data-nav"));
	}
}

function DisplayGameNav(){
	$("#game-slide-out").show();
	$("#game-slide-out").css({"left":"0"});
	$('html').click(function(){
		if($("#game-slide-out").is(":visible")){
			$("#game-slide-out").css({"left":"-100%"});
			setTimeout(function(){ $("#game-slide-out").hide() }, 300);
		}
	});
}

function SaveJournalEntry(gameid){
	var journal = tinyMCE.activeEditor.getContent({format : 'html'});
	var subject = $("#myxp-journal-subject").val();
	$.ajax({ url: '../php/webService.php',
     data: {action: "SaveJournalEntry", journal: journal, gameid: gameid, subject: subject },
     type: 'post',
     success: function(output) {
		 Toast("Saved Journal Entry");
		 $(".journal-body").html(journal);
		 if(subject != '')
		 	$(".journal-subject-header").html(subject);
		 HideJournalEdit();
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

function ShowJournalEdit(){
	$("#myGameJournalDisplay").hide();
	$(".myxp-journal-edit-btn").hide();
	$(".edit-game-journal-container").show();
}

function HideJournalEdit(){
	$("#myGameJournalDisplay").show();
	$(".myxp-journal-edit-btn").show();
	$(".edit-game-journal-container").hide();
}

function DisplayUserDetails(userid, username){
	$(".game-user-tab span").html(username);
	$(".game-user-tab").show();
	$(".game-user-tab").attr("data-nav", "User/"+userid+"/"+username);
	SwitchGameContent($(".game-user-tab"));
	ShowLoader($("#game-userxp-tab"), 'big', "<br><br><br>");
	var gameid = $("#gameContentContainer").attr("data-id");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayUserDetails", gameid: gameid, userid: userid },
     type: 'post',
     success: function(output) {
		$("#game-userxp-tab").html(output);
		DisplayExpSpectrum($("#game-userxp-tab"));
	 	$(".myxp-share-tier-quote").unbind();
	 	$(".myxp-share-tier-quote").on('click', function(){
			var gameid = $("#gameContentContainer").attr("data-id");
			ShowShareModal("userxp", gameid+"-"+$(this).attr("data-userid"));
		});
		var box = $("#game-userxp-tab").find(".myxp-details-container").last();
		$(".myxp-vert-line-details").css({"bottom": (box.height() - 40)+"px"});
	 	$(".myxp-profile-tier-quote").on('click', function(){
			ShowUserProfile($(this).attr("data-userid"));
		});
		AttachAgreesFromGame();
		$(".shareBtn").on('click', function(){
			ShowShareModal("event", $(this).attr("data-eventid"));
		});
		AttachWatchFromXP();
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

function AttachWatchFromXP(){
	$(".watchBtn").on("click", function(e){
 		e.stopPropagation();
		ShowProfileDetails("<div class='universalBottomSheetLoading'></div>");
		ShowLoader($(".universalBottomSheetLoading"), 'big', "<br><br><br>");
  		var gameid = $(this).attr("data-gameid");
  		var url = $(this).attr("data-url");
 		$.ajax({ url: '../php/webService.php',
	     data: {action: "DisplayVideoForGame", url: url, gameid: gameid },
	     type: 'post',
	     success: function(output) {
 			$("#BattleProgess").html(output); 
 			$(".myxp-video-goto-full").hide();
 			AttachActivityVideoEvents();
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

function AttachAgreesFromGame(){
	$(".agreeBtn").unbind();
	$(".disagreeBtn").unbind();
	AttachAgreeFromGame();
	AttachDisagreeFromGame();
}

function AttachAgreeFromGame(){
	$(".agreeBtn").on('click', function(){
		var eventid = $(this).attr("data-eventid");
		var gameid = $(this).attr("data-gameid");
		var agreedwith = $(this).attr("data-agreedwith");
		var username = $(this).attr("data-username");
		SaveAgree(gameid, agreedwith, eventid, username);
		$(this).removeClass("agreeBtn");
		$(this).addClass("disagreeBtn");
		$(this).html("- 1up");
		AttachAgreesFromGame();
	});
}

function AttachDisagreeFromGame(){
	$(".disagreeBtn").on('click', function(){
		var eventid = $(this).attr("data-eventid");
		var gameid = $(this).attr("data-gameid");
		var agreedwith = $(this).attr("data-agreedwith");
		var username = $(this).attr("data-username");
		RemoveAgree(gameid, agreedwith, eventid, username);
		$(this).removeClass("disagreeBtn");
		$(this).addClass("agreeBtn");
		$(this).html("+ 1up");
		AttachAgreesFromGame();
	});
}

function AttachAnalyzeEvents(){
	DisplayExpSpectrum($("#game-analyze-tab"));
	DisplayAgeGraph();
	DisplayRelationalGraphs();
	AnalyzeViewMoreButtons();
	$(".analyze-card-list-item").on('click', function(){ 
		var game = $(this).attr("data-gbid");
		ShowGame($(this).attr("data-gbid"), $("#discover"));
	});
	$(".analyze-doughnut-view-games").on("click", function(){
		if($(this).attr("data-type") == "Franchise")
	 		DisplayKnowledgeDetails($(this).attr("data-userid"), $(this).attr("data-objectid"), $(this).attr("data-progid"));
 		else if($(this).attr("data-type") == "Developer")
	 		DisplayDeveloperDetails($(this).attr("data-userid"), $(this).attr("data-objectid"), $(this).attr("data-progid"));
	 });
}

function AttachVideoEvents(){
	$(".collection-myxp-tier").on('click', function(){
		if(!$(this).hasClass("myxp-selected-tier")){
			var parent = $(this).parent();
			var oldselection = parent.find(".myxp-selected-tier");
			oldselection.removeClass("myxp-selected-tier tierBorderColor1selected tierBorderColor2selected tierBorderColor3selected tierBorderColor4selected tierBorderColor5selected");
			$(this).addClass("myxp-selected-tier tierBorderColor"+ $(this).attr("data-tier") +"selected");
			var tier = $(this).attr("data-tier");
			ValidateVideoXPEntry($(this));
		}
	});
	$(".myxp-quote").bind('input propertychange', function() {
		ValidateVideoXPEntry($(this));
	});
	$(".myxp-video-goto-full").on("click", function(){
		var element = $(this).parent().parent().parent().parent();
		var length = element.attr("data-length");
		var source = element.attr("data-source");
		var url = element.attr("data-url");
		var tier = element.find(".myxp-selected-tier").attr("data-tier");
		var summary = element.find(".myxp-quote").val();
		AddWatchedFabEvent(url, source, length, tier, summary);
	});
	$(".myxp-post").on("click", function(){
		if(!$(this).hasClass("disabled")){
			var button = $(this);
			var element = $(this).parent().parent().parent().parent();
			var length = element.attr("data-length");
			var source = element.attr("data-source");
			var url = element.attr("data-url");
			var tier = element.find(".myxp-selected-tier").attr("data-tier");
			var summary = element.find(".myxp-quote").val();
			var quarter = element.attr("data-quarter");
			var year = element.attr("data-year");
			var gameid = $("#gameContentContainer").attr("data-id");
			$.ajax({ url: '../php/webService.php',
			     data: {action: 'SaveWatchedVideo', gameid: gameid, quote: summary, tier: tier, viewsrc: source, viewing: length, url: url, quarter: quarter, year: year },
			     type: 'post',
			     success: function(output) {
					var contentsplit = $.trim(output).split("|**|");
					if(contentsplit[2] == "true"){
						ShowBattleProgress(contentsplit[0]);
					}else{
						Toast("Saved your watched XP");
					}
					RefreshAnalytics();
					button.addClass("disabled");
					if($(".userGameTab").length == 0){
						$(".userVideoTab").after("<li class='tab col s3 userGameTab' style='background-color:transparent;'><a href='#game-myxp-tab' class='waves-effect waves-light'>My XP</a></li>");
					}else
						$(".userGameTab").show(250);
					$("#game-myxp-tab").hide();
	         		$("#game-myxp-tab").html(contentsplit[1]);
	         		AttachEditEvents();
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

function ValidateVideoXPEntry(button){
	var element = button.parent().parent().parent().parent();
	var tier = element.find(".myxp-selected-tier").attr("data-tier");
	if(tier > 0)
		element.find(".myxp-post").removeClass("disabled");
}

function AttachCriticBookmark(){
	$(".no-critic-bookmark").on('click', function(e){
		SubmitBookmark("AddBookmark", $(".game-add-bookmark-btn").attr("data-gameid"));	
	});
}

function AnalyzeViewMoreButtons(){
	$(".analyze-card").each(function(){ 
		if($(this).find(".analyze-view-more-hide").length > 0){
			$(this).find(".analyze-view-more-button").css({"display":"inline-block"});
		}
	});
	$(".analyze-view-more-button").on("click", function(){
		$(this).parent().parent().parent().find(".analyze-view-more-hide").addClass("analyze-view-more-show");
		$(this).parent().parent().parent().find(".analyze-view-more-hide").removeClass("analyze-view-more-hide");
		$(this).parent().parent().find(".analyze-view-less-button").css({"display":"inline-block"});
		$(this).css({"display":"none"});
	});
	$(".analyze-view-less-button").on("click", function(){
		$(this).parent().parent().parent().find(".analyze-view-more-show").addClass("analyze-view-more-hide");
		$(this).parent().parent().parent().find(".analyze-view-more-show").removeClass("analyze-view-more-show");
		$(this).parent().parent().find(".analyze-view-more-button").css({"display":"inline-block"});
		$(this).css({"display":"none"});
	});
}

function AttachFormCreationEvents(){
	$(".daily-response-items-url").parent().hide();
	AttachQuickAddFormCreationEvents();
	$("input[type=radio][name=typeofresponse]").on('change', function(){
		if($(this).attr("data-type") == "grid-single" || $(this).attr("data-type") == "grid-multi"){
			$(".daily-response-items-url").parent().show();
		}else{
			$(".daily-response-items-url").parent().hide();
		}	
	});
	$(".set-current-to-response").on("click", function(){
		var currentVal = $(this).parent().parent().find("#gbmetadata").val();
		var currentTxt = $(this).parent().parent().find("#gbmetadata option:selected").text();
		var found = false;
		$(".daily-response-items").each(function(){
			if($(this).val() == '' && found == false){
				$(this).attr("data-meta", currentVal);
				$(this).val(currentTxt);
				$(this).next().addClass("active");
				$(this).addClass("daily-repsonse-items-with-meta");
				found = true;
			}
		});
		if(found == false){
			var count = $(".daily-add-another").parent().attr("data-count");
			count++;
			$(".daily-add-another").parent().attr("data-count", count);
	        $(".daily-add-another").before("<div class='row'><div class='input-field'><input id='dailyresponse"+count+"' class='daily-response-items' type='text' value='' data-meta='"+currentVal+"' ><label for='dailyresponse"+count+"' class='active'>Response #"+count+"</label></div><div class='input-field' style='display:hidden'><input id='daily-response-url"+count+"' class='daily-response-items-url' type='text' value='' ><label for='daily-response-url"+count+"'>Response #"+count+" Image URL</label></div></div>");
			if($("input[type=radio][name=typeofresponse]:checked").attr("data-type") == "grid-single" || $("input[type=radio][name=typeofresponse]:checked").attr("data-type") == "grid-multi"){
				$(".daily-response-items-url").parent().show();
			}else{
				$(".daily-response-items-url").parent().hide();
			}
			$("#dailyresponse"+count+"").val(currentTxt);
			$("#dailyresponse"+count+"").addClass("daily-repsonse-items-with-meta");
			AttachQuickAddFormCreationEvents();
		}
	})
	$(".submit-daily, .save-as-daily").on("click", function(){
		var question = $("#daily-question").val();
		var subquestion = $("#daily-subquestion").val();
		var type = $("input[type=radio][name=typeofresponse]:checked").attr("data-type");
		var responses = '';
		var responseurls = '';
		var gameid = $("#daily-question").attr("data-gameid");
		var defaultResponse = "No";
		var finished = "No";
		$("#daily-finished").each(function(){
			if(this.checked	)
				finished = "Yes";
		});
		$("#daily-default").each(function(){
			if(this.checked	)
				defaultResponse = "Yes";
		});
		$(".daily-response-items").each(function(){
			responses = responses + $(this).val()+"^^^"+$(this).attr("data-meta")+"@@@";	
		});
		$(".daily-response-items-url").each(function(){
			responseurls = responseurls + $(this).val()+"||";	
		});
		
		$.ajax({ url: '../php/webService.php',
		     data: {action: 'SubmitDailyForm', question: question, subquestion: subquestion, type: type, responses: responses, responseurls: responseurls, defaultResponse: defaultResponse, gameid: gameid, finished: finished },
		     type: 'post',
		     success: function(output) {
     			$("#BattleProgess").closeModal();
  				HideFocus();
		     	Toast("Reflection Point Submitted!");
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
	$(".update-daily").on("click", function(){
		var question = $("#daily-question").val();
		var subquestion = $("#daily-subquestion").val();
		var type = $("input[type=radio][name=typeofresponse]:checked").attr("data-type");
		var responses = '';
		var responseurls = '';
		var formid = $("#daily-question").attr("data-formid");
		var defaultResponse = "No";
		var finished = "No";
		$("#daily-finished").each(function(){
			if(this.checked	)
				finished = "Yes";
		});
		$("#daily-default").each(function(){
			if(this.checked	)
				defaultResponse = "Yes";
		});
		$(".daily-response-items").each(function(){
			responses = responses + $(this).val()+"^^^"+$(this).attr("data-meta")+"^^^"+$(this).attr("data-existID")+"@@@";	
		});
		$(".daily-response-items-url").each(function(){
			responseurls = responseurls + $(this).val()+"||";	
		});
		
		$.ajax({ url: '../php/webService.php',
		     data: {action: 'UpdateDailyForm', question: question, subquestion: subquestion, type: type, responses: responses, responseurls: responseurls, defaultResponse: defaultResponse, formid: formid, finished: finished },
		     type: 'post',
		     success: function(output) {
     			$("#BattleProgess").closeModal();
  				HideFocus();
		     	Toast("Reflection Point Updated!");
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
	$(".cancel-daily").on("click", function(){
		$("#BattleProgess").closeModal();
  		HideFocus();
	});
	$(".delete-daily").on("click", function(){
		var formid = $("#daily-question").attr("data-formid");
		$.ajax({ url: '../php/webService.php',
		     data: {action: 'DeleteDaily', formid: formid },
		     type: 'post',
		     success: function(output) {
				Toast("Delete Reflection Point");
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
	});
	
}

function AttachQuickAddFormCreationEvents(){
	$(".daily-add-another, .daily-response-items").unbind();
	$(".daily-add-another").on("click", function(){
		var count = $(this).parent().attr("data-count");
		count++;
		$(this).parent().attr("data-count", count);
        $(this).before("<div class='row'><div class='input-field'><input id='dailyresponse"+count+"' class='daily-response-items' type='text' value='' data-meta='' ><label for='dailyresponse"+count+"'>Response #"+count+"</label></div><div class='input-field' style='display:hidden'><input id='daily-response-url"+count+"' class='daily-response-items-url' type='text' value='' ><label for='daily-response-url"+count+"'>Response #"+count+" Image URL</label></div></div>");
		$("#dailyresponse"+count).focus();
		if($("input[type=radio][name=typeofresponse]:checked").attr("data-type") == "grid-single" || $("input[type=radio][name=typeofresponse]:checked").attr("data-type") == "grid-multi"){
			$(".daily-response-items-url").parent().show();
		}else{
			$(".daily-response-items-url").parent().hide();
		}
		AttachQuickAddFormCreationEvents();
	});
	$(".daily-response-items").on('keydown keypress', function(e){
		if(e.keyCode == 9)
		{
			var count = $(".daily-add-another").parent().attr("data-count");
			count++;
			$(".daily-add-another").parent().attr("data-count", count);
	        $(".daily-add-another").before("<div class='row'><div class='input-field'><input id='dailyresponse"+count+"' class='daily-response-items' type='text' value='' data-meta='' ><label for='dailyresponse"+count+"'>Response #"+count+"</label></div><div class='input-field' style='display:hidden'><input id='daily-response-url"+count+"' class='daily-response-items-url' type='text' value='' ><label for='daily-response-url"+count+"'>Response #"+count+" Image URL</label></div></div>");
			if($("input[type=radio][name=typeofresponse]:checked").attr("data-type") == "grid-single" || $("input[type=radio][name=typeofresponse]:checked").attr("data-type") == "grid-multi"){
				$(".daily-response-items-url").parent().show();
			}else{
				$(".daily-response-items-url").parent().hide();
			}
			AttachQuickAddFormCreationEvents();
		}
	});
}


function SubmitBookmark(serviceValue, gameid){
	$.ajax({ url: '../php/webService.php',
     data: {action: serviceValue, gameid: gameid },
     type: 'post',
     success: function(output) {
     	if(serviceValue == "AddBookmark"){
			ToastError("Added Bookmark");
			$(".game-remove-bookmark-btn").show();
			$(".game-add-bookmark-btn").hide();
			$(".GameMyStatusIcons .mybookmark").show();
			GAEvent('Game', 'Add Bookmark');
		}else{
			ToastError("Removed Bookmark");
			$(".game-remove-bookmark-btn").hide();
			$(".game-add-bookmark-btn").show();
			$(".GameMyStatusIcons .mybookmark").hide();
			GAEvent('Game', 'Remove Bookmark');
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


function RequestUpdateFromGiantBomb(gameid){
	Toast("Updating Game...");
	$.ajax({ url: '../php/webService.php',
     data: {action: "RequestUpdateFromGiantBomb", gameid: gameid },
     type: 'post',
     success: function(output) {
		Toast("Game has been updated, refresh to see latest");
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

function AttachFloatingIconButtonEvents(){
	$(".game-remove-bookmark-btn").on('click touchend', function(){
		if($(".game-collection-btn").css("opacity") == 1){
			SubmitBookmark("RemoveBookmark", $(this).attr("data-gameid"));
		}
	});
	$(".game-add-bookmark-btn").on('click touchend', function(){
		if($(".game-collection-btn").css("opacity") == 1){
			SubmitBookmark("AddBookmark", $(this).attr("data-gameid"));
		}
	});
	$(".game-collection-btn").on('click', function(){
		DisplayCollectionPopUp($(this).attr("data-gameid"));	
	});
	$(".game-add-watched-btn").on('click touchend', function(){
		if($(".game-collection-btn").css("opacity") == 1){
			AddWatchedFabEvent('','','','','');
		}
	});

	$(".game-add-played-btn").on('click touchend', function(){
		if($(".game-collection-btn").css("opacity") == 1){
			AddPlayedFabEvent();		
		}
	});
	$(".game-update-info-btn").on('click', function(){
		RequestUpdateFromGiantBomb($(this).attr("data-gameid"));	
	});
	$(".game-add-image-btn").on('click', function(){
		var html = "<div><span>Game ID: "+$(this).attr("data-gameid")+"</span> <span>Year: "+$(this).attr("data-gameyear")+"</span></div><br><iframe src='http://lifebar.io/utilities/FileUploader.php' style='width:100%;border:none;'></iframe>";
		ShowPopUp(html);	
	});
	$(".game-create-reflection-point").on('click', function(){
		DisplayReflectionPopUp($(this).attr("data-gameid"));
	});
	$(".game-share-btn").on("click", function(){
		var gameid = $("#gameContentContainer").attr("data-id");
		ShowShareModal("game", gameid);
	});
	$(".fab-login").on('click', function(){
		 $('#signupModal').openModal();
		 GAEvent('Game', 'Login');
	});
	$(".game-set-fav-btn").on("click", function(){
		if($(".game-set-fav-btn").css("opacity") == 1){
			DisplayEquipXP();
		}	
	});
}

function AttachMyXPEvents(){
 	$(".game-add-watched-btn-fast").on('click', function(){
		AddWatchedFabEvent('','','','','');
	});
	$(".game-add-played-btn-fast").on('click', function(){
		if($("#loginButton").length > 0)
			$("#loginButton").click();
		else
			AddPlayedFabEvent();		
	});
	$(".userGameTab").on("click", function(){
		setTimeout(function(){
  			var box = $("#game-myxp-tab").find(".myxp-details-container").last().parent();
			if(box.innerHeight() > 0)
				$(".myxp-vert-line").css({"bottom": (box.innerHeight() + 40)+"px"});
		}
		,100);
	});
 	$(".removeEventBtn").on('click', function(){
		DeleteEvent($(this).attr("data-eventid"));
	});
 	$(".shareBtn").on('click', function(){
		ShowShareModal("event", $(this).attr("data-eventid"));
	});
}

function DisplayEquipXP(){
	var gameid = $("#gameContentContainer").attr("data-id");
	$.ajax({ url: '../php/webService.php',
     data: {action: 'DisplayEquipXP', gameid: gameid },
     type: 'post',
     success: function(output) {
		ShowBottomSheet(output);
		AttachEquipXPEvents(gameid);
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

function AttachEquipXPEvents(gameid){
	$(".equip-xp-game-btn").on('click', function(){
		$(this).parent().find(".equip-xp-game-image").css({"opacity":"0"});
		if($(this).text() == "Pin"){
			$(this).text("Un-pin");
			var image = $(".equip-xp-container").attr("data-newgame-image");
			$(this).parent().find(".equip-xp-game-image").css({"background":"url("+image+") 50% 50%", "background-size":"cover", "opacity":"1"});
			$(this).parent().find(".equip-xp-game-empty-image").css({"background":"url("+image+") 50% 50%", "background-size":"cover", "opacity":"1"});
			$(this).parent().find(".equip-xp-game-title").text($(".GameTitle").text());
			$(this).parent().find(".equip-xp-game-title").show();
			var slot = $(this).parent().attr("data-slot");
			UpdatePreferredXP(gameid, slot);
		}else{
			$(this).text("Pin");
			var oldgameid = $(this).parent().attr("data-previous");
			var image = $(this).parent().find(".equip-xp-game-image").attr("data-previous");
			$(this).parent().find(".equip-xp-game-image").css({"background":"url("+image+") 50% 50%", "background-size":"cover", "opacity":"1"});
			$(this).parent().find(".equip-xp-game-title").text($(this).parent().find(".equip-xp-game-title").attr("data-previous"));
			var slot = $(this).parent().attr("data-slot");
			UpdatePreferredXP(oldgameid, slot);
		}
	});
}

function AttachAgrees(){
	$(".agreeBtn").unbind();
	$(".disagreeBtn").unbind();
	AttachAgree();
	AttachDisagree();
}

function AttachAgree(){
	$(".agreeBtn").on('click', function(){
		var eventid = $(this).attr("data-eventid");
		var gameid = $(this).attr("data-gameid");
		var agreedwith = $(this).attr("data-agreedwith");
		var username = $(this).attr("data-username");
		SaveAgree(gameid, agreedwith, eventid, username);
		var btncount = $(this).parent().parent().find(".agreeBtnCount");
		var total = parseInt(btncount.html(), 10);
		btncount.css({"display":"inline-block"});
		total = total || 0;
		total = total + 1;
		btncount.html(total);
		$(this).removeClass("agreeBtn");
		$(this).addClass("disagreeBtn");
		$(this).html("- 1up");
		MoveUpPostAgree($(this).parent().parent(), total);
		AttachAgrees();
		GAEvent('Game', 'Add 1up to User');
	});
}

function AttachDisagree(){
	$(".disagreeBtn").on('click', function(){
		var eventid = $(this).attr("data-eventid");
		var gameid = $(this).attr("data-gameid");
		var agreedwith = $(this).attr("data-agreedwith");
		var username = $(this).attr("data-username");
		RemoveAgree(gameid, agreedwith, eventid, username);
		var btncount = $(this).parent().parent().find(".agreeBtnCount");
		var total = parseInt(btncount.html(), 10);
		total = total || 0;
		total = total - 1;
		btncount.html(total);
		if(total == 0)
			btncount.css({"display":"none"});
		$(this).removeClass("disagreeBtn");
		$(this).addClass("agreeBtn");
		$(this).html("+ 1up");
		MoveDownPostAgree($(this).parent().parent(), total);
		AttachAgrees();
		GAEvent('Game', 'Remove 1up from User');
	});
}

function SaveAgree(gameid, agreedwith, eventid, username){
	$.ajax({ url: '../php/webService.php',
     data: {action: 'SaveAgreed', gameid: gameid, agreedwith: agreedwith, eventid: eventid },
     type: 'post',
     success: function(output) {
		Toast("You appreciated "+username+"'s thoughts ");
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

function RemoveAgree(gameid, agreedwith, eventid, username){
	$.ajax({ url: '../php/webService.php',
     data: {action: 'RemoveAgreed', gameid: gameid, agreedwith: agreedwith, eventid: eventid },
     type: 'post',
     success: function(output) {
		Toast("You no longer appreciate "+username+"'s thoughts ");
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

function UpdatePreferredXP(gameid, slot){
 	var title = $("#gameContentContainer").attr("data-title");
	$.ajax({ url: '../php/webService.php',
     data: {action: 'UpdatePreferredXP', gameid: gameid, slot:slot },
     type: 'post',
     success: function(output) {
		//Toast("Equipped XP for "+title+" to your profile");
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

function MoveUpPostAgree(xp, count){
	var switched = false;
	if(xp.hasClass("critic-container")){
		$("#game-critic-tab").find(".critic-container").each(function(){
			var $this = $(this);
			var btncount = $this.find(".agreeBtnCount");
			var total = parseInt(btncount.html(), 10);
			total = total || 0;
			if(count > total && switched == false){
				$this.before("<div class='col s12 critic-container'>"+xp.html()+"</div>");
				xp.remove();
				NiceScroll($this.prev(), 300);
				$this.prev().css({"background-color":"#a5d6a7"});
				setTimeout(function(){
						$this.prev().css({"background-color":"white"});
					}
					,500);
				switched = true;
			}
		});
	}else{
		$("#game-user-tab").find(".user-container").each(function(){
			var $this = $(this);
			var btncount = $this.find(".agreeBtnCount");
			var total = parseInt(btncount.html(), 10);
			total = total || 0;
			if(count > total && switched == false){
				$this.before("<div class='col s12 user-container'>"+xp.html()+"</div>");
				xp.remove();
				NiceScroll($this.prev(), 300);
				$this.prev().css({"background-color":"#a5d6a7"});
				setTimeout(function(){
						$this.prev().css({"background-color":"white"});
					}
					,500);
				switched = true;
			}
		});
	}
}

function MoveDownPostAgree(xp, count){
	var switched = false;
	if(xp.hasClass("critic-container")){
		$($("#game-critic-tab").find(".critic-container").get().reverse()).each(function(){
			var $this = $(this);
			var btncount = $this.find(".agreeBtnCount");
			var total = parseInt(btncount.html(), 10);
			total = total || 0;
			if(count < total && switched == false){
				$this.after("<div class='col s12 critic-container'>"+xp.html()+"</div>");
				xp.remove();
				NiceScroll($this.next(), 300);
				$this.next().css({"background-color":"#ef9a9a"});
				setTimeout(function(){
						$this.next().css({"background-color":"white"});
					}
					,500);
				switched = true;
			}
		});
	}else{
		$($("#game-user-tab").find(".user-container").get().reverse()).each(function(){
			var $this = $(this);
			var btncount = $this.find(".agreeBtnCount");
			var total = parseInt(btncount.html(), 10);
			total = total || 0;
			if(count < total && switched == false){
				$this.after("<div class='col s12 user-container'>"+xp.html()+"</div>");
				xp.remove();
				NiceScroll($this.next(), 300);
				$this.next().css({"background-color":"#ef9a9a"});
				setTimeout(function(){
						$this.next().css({"background-color":"white"});
					}
					,500);
				switched = true;
			}
		});
	}
}

function AttachEditEvents(){
	$(".myxp-edit-played").on('click', function(){
		UpdatePlayedEvent();
	});
	$(".myxp-edit-watched").on('click', function(){
		var watchid = $(this).attr("data-id");
		UpdateWatchedEvent(watchid);
	});
	$(".myxp-edit-tier-quote").on('click', function(){
		UpdateTierQuoteEvent();	
	});
}


function DisplayReflectionPopUp(gameid){
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayDailyCreationForm", gameid: gameid },
     type: 'post',
     success: function(output) {
 		ShowBattleProgress(output); 
 		AttachFormCreationEvents();
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

function EditReflectionPopUp(refptid){
	$.ajax({ url: '../php/webService.php',
     data: {action: "EditDailyCreationForm", refptid: refptid },
     type: 'post',
     success: function(output) {
 		ShowBattleProgress(output); 
 		AttachFormCreationEvents();
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

function AttachEventsForReflectionPoints(){	
	$(".daily-pref-image,.submit-daily-response, .share-daily-response").unbind();
 	$(".daily-pref-image").on("click", function(){
		 var parentElem = $(this).parent().parent().parent();
 		if($(this).hasClass("daily-pref-image-active")){
 			$(this).removeClass("daily-pref-image-active");
 			$(this).find(".daily-checkmark").css({"opacity":"0"});
 		}else{
 			if($(this).hasClass("singlegrid")){
 				var current = parentElem.find(".daily-pref-image-active");
 				current.removeClass("daily-pref-image-active");
 				current.find(".daily-checkmark").css({"opacity":"0"});
 			}
 			$(this).addClass("daily-pref-image-active");
 			$(this).find(".daily-checkmark").css({"opacity":"1"});
 		}
 	});
 	$(".share-refpt-response").on("click", function(){
		ShowShareModal("reflectionpoint", $(this).attr("data-id"));
	});
	$(".submit-refpt-response").on('click', function(){
		if($("#loginButton").length > 0){
			$('#signupModal').openModal(); $("#username").focus();
		}else{
			var parentElem = $(this).parent().parent().parent().parent();
			parentElem.find(".refpt-answers-results-container").show();
			SaveReflectionPointSubmission(parentElem);
		}
	});
}

function SaveReflectionPointSubmission(elem){
	var formType = elem.find(".refpt-answers-container").attr("data-type");
	var formitemid = 0;
	var formid = 0;
	var objectid = 0;
	var objectType = '';
	var gameid = 0;
	if(formType == 'grid-single'){
		formitemid = elem.find(".daily-pref-image-active").parent().parent().attr("data-formitemid");
		formid = elem.find(".daily-pref-image-active").parent().parent().attr("data-formid");
		objectid = elem.find(".daily-pref-image-active").parent().parent().attr("data-objid");
		objectType = elem.find(".daily-pref-image-active").parent().parent().attr("data-objtype");
		gameid = elem.find(".daily-pref-image-active").parent().parent().attr("data-gameid");
	}else if(formType == 'radio'){
		formitemid = elem.find("input[type=radio][name=dailyresposne]:checked").parent().attr("data-formitemid");
		formid = elem.find("input[type=radio][name=dailyresposne]:checked").parent().attr("data-formid");
		objectid = elem.find("input[type=radio][name=dailyresposne]:checked").parent().attr("data-objid");
		objectType = elem.find("input[type=radio][name=dailyresposne]:checked").parent().attr("data-objtype");
		gameid = elem.find("input[type=radio][name=dailyresposne]:checked").parent().attr("data-gameid");
	}else if(formType == 'dropdown'){
		formitemid = elem.find("#daily-response-dropdown").val();
		formid = elem.find("#daily-response-dropdown").parent().parent().attr("data-formid");
		objectid = elem.find("#daily-response-dropdown").parent().parent().attr("data-objid");
		objectType = elem.find("#daily-response-dropdown").parent().parent().attr("data-objtype");
		gameid = elem.find("#daily-response-dropdown").parent().parent().attr("data-gameid");
	}else if(formType == 'grid-multi'){
		formitemid = '';
		elem.find(".daily-pref-image-active").each(function(){
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
		elem.find(".response-checkbox").each(function(){
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
         data: {action: "SubmitDailyChoice", formid: formid, formitemid: formitemid, gameid: gameid, objectid: objectid, objectType: objectType, gamepage: "true" },
         type: 'post',
         success: function(output) {
         	elem.find(".refpt-answers-results-container").html(output);
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

function GameCardActions(element){
	if(element.hasClass("card-game-add-btn")){
		element.addClass("card-game-add-tier-container-active");
		element.parent().parent().find(".card-game-tier").hide();
		element.find(".card-tier-details").addClass("card-tier-details-active");
		element.find(".played-option").on('click', function(e){
			LoadGameDirect(element.parent().parent().attr("data-gbid"), $("#discover"), "played")
		});
		element.find(".watched-option").on('click', function(e){
			LoadGameDirect(element.parent().parent().attr("data-gbid"), $("#discover"), "watched")
		});
		element.find(".mdi-content-clear").on("click", function(e){
			e.stopPropagation();
			$(this).parent().parent().parent().removeClass("card-game-add-tier-container-active");
			$(this).parent().parent().removeClass("card-tier-details-active");
			$(this).parent().parent().parent().parent().find(".card-game-tier").show();
		});
		element.find(".login-option").on('click', function(){
			$('#loginModal').openModal();	
		});
		element.find(".signup-option").on('click', function(){
			$('#signupModal').openModal();	
		});
	}else if(element.hasClass("card-game-collection-add")){
		DisplayCollectionQuickForm(element.parent(), element.parent().parent().attr("data-gameid"), element.parent().parent().attr("data-gbid"), true);
	}else{
		element.addClass("card-game-tier-container-active");
		element.parent().parent().find(".card-game-tier").hide();
		element.parent().parent().find(".c100").hide();
		element.find(".card-tier-details").addClass("card-tier-details-active");
		element.find(".mdi-content-clear").on("click", function(e){
			e.stopPropagation();
			$(this).parent().parent().parent().removeClass("card-game-tier-container-active");
			$(this).parent().parent().removeClass("card-tier-details-active");
			$(this).parent().parent().parent().parent().find(".card-game-tier").show();
			$(this).parent().parent().parent().parent().find(".c100").show();
		});
	}
}

function DisplayRelationalGraphs(){
	if($(".GraphRelational").length > 0){
		$(".GraphRelational").each(function(){
			var experiencedUsersGraph = $(this).get(0).getContext("2d");
			var data = {
		    labels: ["", "", "", "", ""],
		    datasets: [
		        {
		            label: "Lifetime XP",
		            fillColor: "rgba(85, 85, 147, 0.41)",
		            strokeColor: "rgba(85, 85, 147, 0.9)",
		            pointColor: "rgba(85, 85, 147, 0.9)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(71,71,71,1)",
		            data: [$(this).attr("data-t5"), $(this).attr("data-t4"), $(this).attr("data-t3"), $(this).attr("data-t2"), $(this).attr("data-t1")]
		        }
		    ]
		};
		$(this).attr('width', $(this).parent().parent().parent().parent().width()-40);
        $(this).attr('height', 250);
		var temp = new Chart(experiencedUsersGraph).Line(data, { animation: false, datasetStrokeWidth : 4, showScale: true, bezierCurve : true, pointDot : false, scaleShowGridLines : false, multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>", animation: true });
		});
	}
	
	if($(".DougnutRelational").length > 0){
		$(".DougnutRelational").each(function(){
			var experiencedUsersGraph = $(this).get(0).getContext("2d");
			var data = [
					    {
					        value: parseInt($(this).attr("data-t1")),
					        color:"#0A67A3",
					        highlight: "#0A67A3"
					    },
					     {
					        value: parseInt($(this).attr("data-t2")),
					        color:"#00B25C",
					        highlight: "#00B25C"
					    },
					     {
					        value: parseInt($(this).attr("data-t3")),
					        color:"#FF8E00",
					        highlight: "#FF8E00"
					    },
					     {
					        value: parseInt($(this).attr("data-t4")),
					        color:"#FF4100",
					        highlight: "#FF4100"
					    },
					     {
					        value: parseInt($(this).attr("data-t5")),
					        color:"#DB0058",
					        highlight: "#DB0058"
					    }
		    ];
	    if($(window).width() >=600){
	    	$(this).attr('height', 175);
	    	$(this).attr('width', 175);
	    }else{
	    	$(this).attr('height', 125);
	    	$(this).attr('width', 125);
	    }
      	var tierChart = new Chart(experiencedUsersGraph).Doughnut(data, { animation: false, showTooltips: false });
		});
	}
}

function DisplayExpSpectrum(elem){
	if(elem.find(".GraphExpSpectrum").length > 0){
		elem.find(".GraphExpSpectrum").each(function(){
			var experiencedUsersGraph = $(this).get(0).getContext("2d");
			var lifetimeTotal = $(this).attr("data-overalltotal");
			var yearTotal = $(this).attr("data-yeartotal");
			var genreTotal = $(this).attr("data-genretotal");
			
			//Your Lifetime XP
			var ft5 = ($(this).attr("data-t5") > 0) ? Math.round(($(this).attr("data-t5") / lifetimeTotal) * 100) : 0;
			var ft4 = ($(this).attr("data-t4") > 0) ? Math.round(($(this).attr("data-t4") / lifetimeTotal) * 100) : 0;
			var ft3 = ($(this).attr("data-t3") > 0) ? Math.round(($(this).attr("data-t3") / lifetimeTotal) * 100) : 0;
			var ft2 = ($(this).attr("data-t2") > 0) ? Math.round(($(this).attr("data-t2") / lifetimeTotal) * 100) : 0;
			var ft1 = ($(this).attr("data-t1") > 0) ? Math.round(($(this).attr("data-t1") / lifetimeTotal) * 100) : 0;
			
			//Your XP from games released
			var yt5 = ($(this).attr("data-yt5") > 0) ? Math.round(($(this).attr("data-yt5") / yearTotal) * 100) : 0;
			var yt4 = ($(this).attr("data-yt4") > 0) ? Math.round(($(this).attr("data-yt4") / yearTotal) * 100) : 0;
			var yt3 = ($(this).attr("data-yt3") > 0) ? Math.round(($(this).attr("data-yt3") / yearTotal) * 100) : 0;
			var yt2 = ($(this).attr("data-yt2") > 0) ? Math.round(($(this).attr("data-yt2") / yearTotal) * 100) : 0;
			var yt1 = ($(this).attr("data-yt1") > 0) ? Math.round(($(this).attr("data-yt1") / yearTotal) * 100) : 0;
			
			//Your XP from
			var gt5 = ($(this).attr("data-gt5") > 0) ? Math.round(($(this).attr("data-gt5") / genreTotal) * 100) : 0;
			var gt4 = ($(this).attr("data-gt4") > 0) ? Math.round(($(this).attr("data-gt4") / genreTotal) * 100) : 0;
			var gt3 = ($(this).attr("data-gt3") > 0) ? Math.round(($(this).attr("data-gt3") / genreTotal) * 100) : 0;
			var gt2 = ($(this).attr("data-gt2") > 0) ? Math.round(($(this).attr("data-gt2") / genreTotal) * 100) : 0;
			var gt1 = ($(this).attr("data-gt1") > 0) ? Math.round(($(this).attr("data-gt1") / genreTotal) * 100) : 0;
			
			var data = {
		    labels: ["Tier 5", "Tier 4", "Tier 3", "Tier 2", "Tier 1"],
		    datasets: [
		        {
		            label: "Lifetime XP",
		            fillColor: "rgba(85, 85, 147, 0.41)",
		            strokeColor: "rgba(85, 85, 147, 0.9)",
		            pointColor: "rgba(85, 85, 147, 0.9)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(71,71,71,1)",
		            data: [ft5, ft4, ft3, ft2, ft1]
		        },
		        {
		            label: ($(this).attr("data-year") == 0) ? "XP for unreleased games" : "XP from games released in "+$(this).attr("data-year"),
		            fillColor: "rgba(0, 150, 136, 0.41)",
		            strokeColor: "rgba(0, 150, 136, 0.9)",
		            pointColor: "rgba(0, 150, 136, 0.9)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(71,71,71,1)",
		            data: [yt5, yt4, yt3, yt2, yt1]
		        },
		        {
		            label: "XP from "+$(this).attr("data-genre"),
		            fillColor: "rgba(233, 30, 99, 0.41)",
		            strokeColor: "rgba(233, 30, 99, 0.9)",
		            pointColor: "rgba(233, 30, 99, 0.9)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(71,71,71,1)",
		            data: [gt5, gt4, gt3, gt2, gt1]
		        }
		    ]
		};
		if($("#game-dashboard-tab").is(":visible"))
			$(this).attr('width', $("#dashboard-game-width-box").width() - 40);
		else if($("#game-community-tab").is(":visible"))
			$(this).attr('width', $("#game-width-box").width() - 40);
		else if($("#game-myxp-tab").is(":visible"))
			$(this).attr('width', $("#myxp-game-width-box").width() - 40);
		else
			$(this).attr('width', $(this).parent().width() - 40);
			
		if($(window).width() > 599)
        	$(this).attr('height', 300);
    	else
    		$(this).attr('height', 150);
		var temp = new Chart(experiencedUsersGraph).Line(data, { animation: false, datasetStrokeWidth : 4, showScale: true, bezierCurve : true, pointDot : true, scaleShowGridLines : false, multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>%", animation: true });
		});
	}
	if(elem.find(".GraphCommunityUsers").length > 0){
		elem.find(".GraphCommunityUsers").each(function(){
			var communityGraph = $(this).get(0).getContext("2d");
			var followingTotal = $(this).attr("data-followingTotal");
			var criticTotal = $(this).attr("data-criticTotal");
			var usersTotal = $(this).attr("data-usersTotal");
			
			//Xp from people you follow
			var ft5 = ($(this).attr("data-ft5") > 0) ? Math.round(($(this).attr("data-ft5") / followingTotal) * 100) : 0;
			var ft4 = ($(this).attr("data-ft4") > 0) ? Math.round(($(this).attr("data-ft4") / followingTotal) * 100) : 0;
			var ft3 = ($(this).attr("data-ft3") > 0) ? Math.round(($(this).attr("data-ft3") / followingTotal) * 100) : 0;
			var ft2 = ($(this).attr("data-ft2") > 0) ? Math.round(($(this).attr("data-ft2") / followingTotal) * 100) : 0;
			var ft1 = ($(this).attr("data-ft1") > 0) ? Math.round(($(this).attr("data-ft1") / followingTotal) * 100) : 0;
			
			//Xp from Critics
			var yt5 = ($(this).attr("data-yt5") > 0) ? Math.round(($(this).attr("data-yt5") / criticTotal) * 100) : 0;
			var yt4 = ($(this).attr("data-yt4") > 0) ? Math.round(($(this).attr("data-yt4") / criticTotal) * 100) : 0;
			var yt3 = ($(this).attr("data-yt3") > 0) ? Math.round(($(this).attr("data-yt3") / criticTotal) * 100) : 0;
			var yt2 = ($(this).attr("data-yt2") > 0) ? Math.round(($(this).attr("data-yt2") / criticTotal) * 100) : 0;
			var yt1 = ($(this).attr("data-yt1") > 0) ? Math.round(($(this).attr("data-yt1") / criticTotal) * 100) : 0;
			
			//Xp from Users
			var gt5 = ($(this).attr("data-gt5") > 0) ? Math.round(($(this).attr("data-gt5") / usersTotal) * 100) : 0;
			var gt4 = ($(this).attr("data-gt4") > 0) ? Math.round(($(this).attr("data-gt4") / usersTotal) * 100) : 0;
			var gt3 = ($(this).attr("data-gt3") > 0) ? Math.round(($(this).attr("data-gt3") / usersTotal) * 100) : 0;
			var gt2 = ($(this).attr("data-gt2") > 0) ? Math.round(($(this).attr("data-gt2") / usersTotal) * 100) : 0;
			var gt1 = ($(this).attr("data-gt1") > 0) ? Math.round(($(this).attr("data-gt1") / usersTotal) * 100) : 0;
			
			var data = {
		    labels: ["Tier 5", "Tier 4", "Tier 3", "Tier 2", "Tier 1"],
		    datasets: [
		        {
		            label: "Following",
		            fillColor: "rgba(76, 175, 80, 0.41)",
		            strokeColor: "rgba(76, 175, 80, 0.9)",
		            pointColor: "rgba(76, 175, 80, 0.9)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(71,71,71,1)",
		            data: [ft5, ft4, ft3, ft2, ft1]
		        },
		        {
		            label: "Critics",
		            fillColor: "rgba(255, 87, 34, 0.41)",
		            strokeColor: "rgba(255, 87, 34, 0.9)",
		            pointColor: "rgba(255, 87, 34, 0.9)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(71,71,71,1)",
		            data: [yt5, yt4, yt3, yt2, yt1]
		        },
		        {
		            label: "Members",
		            fillColor: "rgba(63, 81, 181, 0.41)",
		            strokeColor: "rgba(63, 81, 181, 0.9)",
		            pointColor: "rgba(63, 81, 181, 0.9)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(71,71,71,1)",
		            data: [gt5, gt4, gt3, gt2, gt1]
		        }
		    ]
		};
		if($("#game-dashboard-tab").is(":visible"))
			$(this).attr('width', $("#dashboard-game-width-box").width() - 40);
		else if($("#game-community-tab").is(":visible"))
			$(this).attr('width', $("#game-width-box").width() - 40);
		else if($("#game-myxp-tab").is(":visible"))
			$(this).attr('width', $("#myxp-game-width-box").width() - 40);
		else
			$(this).attr('width', $(this).parent().width() - 40);
		
		if($(window).width() > 599)
        	$(this).attr('height', 300);
    	else
    		$(this).attr('height', 150);
		var temp = new Chart(communityGraph).Line(data, { animation: false, datasetStrokeWidth : 4, showScale: true, bezierCurve : true, pointDot : true, scaleShowGridLines : false, multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>%", animation: true });
		});
	}
}

function DisplayAgeGraph(){
	if($(".GraphAgeUsers").length > 0){
		$(".GraphAgeUsers").each(function(){
			var ageGraph = $(this).get(0).getContext("2d");
			var group1Total = $(this).attr("data-group1Total");
			var group2Total = $(this).attr("data-group2Total");
			var group3Total = $(this).attr("data-group3Total");
			var group4Total = $(this).attr("data-group4Total");
			var group5Total = $(this).attr("data-group5Total");
			//Grpoup 1
			var g1t5 = ($(this).attr("data-1t5") > 0) ? $(this).attr("data-1t5") : 0;
			var g1t4 = ($(this).attr("data-1t4") > 0) ? $(this).attr("data-1t4") : 0;
			var g1t3 = ($(this).attr("data-1t3") > 0) ? $(this).attr("data-1t3") : 0;
			var g1t2 = ($(this).attr("data-1t2") > 0) ? $(this).attr("data-1t2") : 0;
			var g1t1 = ($(this).attr("data-1t1") > 0) ? $(this).attr("data-1t1") : 0;
			//Grpoup 2
			var g2t5 = ($(this).attr("data-2t5") > 0) ? $(this).attr("data-2t5") : 0;
			var g2t4 = ($(this).attr("data-2t4") > 0) ? $(this).attr("data-2t4") : 0;
			var g2t3 = ($(this).attr("data-2t3") > 0) ? $(this).attr("data-2t3") : 0;
			var g2t2 = ($(this).attr("data-2t2") > 0) ? $(this).attr("data-2t2") : 0;
			var g2t1 = ($(this).attr("data-2t1") > 0) ? $(this).attr("data-2t1") : 0;
			//Group 3
			var g3t5 = ($(this).attr("data-3t5") > 0) ? $(this).attr("data-3t5") : 0;
			var g3t4 = ($(this).attr("data-3t4") > 0) ? $(this).attr("data-3t4") : 0;
			var g3t3 = ($(this).attr("data-3t3") > 0) ? $(this).attr("data-3t3") : 0;
			var g3t2 = ($(this).attr("data-3t2") > 0) ? $(this).attr("data-3t2") : 0;
			var g3t1 = ($(this).attr("data-3t1") > 0) ? $(this).attr("data-3t1") : 0;
			//Group 4
			var g4t5 = ($(this).attr("data-4t5") > 0) ? $(this).attr("data-4t5") : 0;
			var g4t4 = ($(this).attr("data-4t4") > 0) ? $(this).attr("data-4t4") : 0;
			var g4t3 = ($(this).attr("data-4t3") > 0) ? $(this).attr("data-4t3") : 0;
			var g4t2 = ($(this).attr("data-4t2") > 0) ? $(this).attr("data-4t2") : 0;
			var g4t1 = ($(this).attr("data-4t1") > 0) ? $(this).attr("data-4t1") : 0;
			//Group 5
			var g5t5 = ($(this).attr("data-5t5") > 0) ? $(this).attr("data-5t5") : 0;
			var g5t4 = ($(this).attr("data-5t4") > 0) ? $(this).attr("data-5t4") : 0;
			var g5t3 = ($(this).attr("data-5t3") > 0) ? $(this).attr("data-5t3") : 0;
			var g5t2 = ($(this).attr("data-5t2") > 0) ? $(this).attr("data-5t2") : 0;
			var g5t1 = ($(this).attr("data-5t1") > 0) ? $(this).attr("data-5t1") : 0;
			
			var data = {
		    labels: ["Childhood", "Teens", "20's", "30's", "40+"],
		    datasets: [
		        {
		            label: "Tier 5",
		            fillColor: "rgba(219, 0, 88, 0.9)",
		            strokeColor: "rgba(219, 0, 88, 0.9)",
		            highlightFill: "rgba(219, 0, 88, 0.41)",
		            highlightStroke: "rgba(219, 0, 88, 0.41)",
		            data: [g1t5, g2t5, g3t5, g4t5, g5t5]
		        }, 
		        {
		            label: "Tier 4",
		            fillColor: "rgba(255, 65, 0, 0.9)",
		            strokeColor: "rgba(255, 65, 0, 0.9)",
		            highlightFill: "rgba(255, 65, 0, 0.41)",
		            highlightStroke: "rgba(255, 65, 0, 0.41)",
		            data: [g1t4, g2t4, g3t4, g4t4, g5t4]
		        },
		        {
		            label: "Tier 3",
		            fillColor: "rgba(255, 142, 0, 0.9)",
		            strokeColor: "rgba(255, 142, 0, 0.9)",
		            highlightFill: "rgba(255, 142, 0, 0.41)",
		            highlightStroke: "rgba(255, 142, 0, 0.41)",
		            data: [g1t3, g2t3, g3t3, g4t3, g4t5]
		        },
		        {
		            label: "Tier 2",
		            fillColor: "rgba(0, 178, 92, 0.9)",
		            strokeColor: "rgba(0, 178, 92, 0.9)",
		            highlightFill: "rgba(0, 178, 92, 0.41)",
		            highlightStroke: "rgba(0, 178, 92, 0.41)",
		            data: [g1t2, g2t2, g3t2, g4t2, g4t2]
		        },
		        {
		            label: "Tier 1",
		            fillColor: "rgba(10, 103, 163, 0.9)",
		            strokeColor: "rgba(10, 103, 163, 0.9)",
		            highlightFill: "rgba(10, 103, 163, 0.41)",
		            highlightStroke: "rgba(10, 103, 163, 0.41)",
		            data: [g1t1, g2t1, g3t1, g4t1, g4t1]
		        }
	        ]
		};
		$(this).attr('width', $(this).parent().parent().parent().width()-40);
        $(this).attr('height', 300);
		var temp = new Chart(ageGraph).Bar(data, { barStrokeWidth : 2, multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>" });
		});
	}
}
