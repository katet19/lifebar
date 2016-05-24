function ShowGame(id, currentTab, isID, browserNav, gameTab){
	if(gameTab == "" || gameTab == undefined)
		gameTab = "Community";
	LoadGame(id, currentTab, isID, browserNav, gameTab);
}

function LoadGame(gbid, currentTab, isID, browserNav, gameTab){
	var windowWidth = $(window).width();
	GLOBAL_TAB_REDIRECT = "Game";
	ManuallyNavigateToTab("#games");
	$(".active").removeClass("active");
    $("#game").css({"display":"inline-block", "left": -windowWidth});
    $("#activity, #discover, #profile, #profiledetails, #settings, #admin, #notifications, #user, #landing").css({"display":"none"});
	$("#activity, #discover, #profile, #profiledetails, #settings, #admin, #notifications, #user, #landing").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
	$("#game").velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	window.scrollTo(0, 0);
	if(!isID){
		ShowLoader($("#gameInnerContainer"), 'big', "<br><br><br>");
		$.ajax({ url: '../php/webService.php',
	     data: {action: "DisplayGame", gbid: gbid },
	     type: 'post',
	     success: function(output) {
	 		$("#gameInnerContainer").html(output);
	 		var gameid = $("#gameContentContainer").attr("data-id");
	 		var title = $("#gameContentContainer").attr("data-title");
	 		GLOBAL_HASH_REDIRECT = "NO";
	 		location.hash = "game/"+gameid+"/"+title+"/"+gameTab;
	 		AttachGameEvents(currentTab);
	 		AttachScrollEvents();
 	 		if(browserNav)
 	 			GLOBAL_HASH_REDIRECT = "";
	 		GLOBAL_TAB_REDIRECT = "";
	 		GAPage('Game', '/game/'+title);
	 		
 	 		if(gameTab == "Community"){
	 		
	 		}else if(gameTab == "Analyze"){
	 			$("#analyze-tab-nav").click();
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
	 		GLOBAL_HASH_REDIRECT = "NO";
	 		
	 		location.hash = "game/"+gameid+"/"+title+"/"+gameTab;
		 	AttachGameEvents(currentTab);
		 	AttachScrollEvents();
  	 		if(browserNav)
 	 			GLOBAL_HASH_REDIRECT = "";
		 	GLOBAL_TAB_REDIRECT = "";
		 	GAPage('Game', '/game/'+title);
		 	
  	 		if(gameTab == "Community"){
	 		
	 		}else if(gameTab == "Analyze"){
	 			$("#analyze-tab-nav").click();
	 		}else if (gameTab.indexOf("User") >= 0){
	 			$("#userxp-tab-nav").click();
	 			DisplayExpSpectrum($("#game-userxp-tab"));
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
    $("#game").css({"display":"inline-block", "left": -windowWidth});
    $("#activity, #discover, #analytics, #admin, #notifications, #user, #landing").css({"display":"none"});
	$("#activity, #discover, #analytics, #admin, #notifications, #user, #landing").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#game").velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	window.scrollTo(0, 0);
	ShowLoader($("#gameInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayGame", gbid: gbid },
     type: 'post',
     success: function(output) {
 		$("#gameInnerContainer").html(output);
 		var gameid = $("#gameContentContainer").attr("data-id");
 		var title = $("#gameContentContainer").attr("data-title");
 		GLOBAL_HASH_REDIRECT = "NO";
 		
   		if(gameTab == "Community"){
 		
 		}else if(gameTab == "Analyze"){
 			$("#analyze-tab-nav").click();
 		}
 		
 		location.hash = "game/"+gameid+"/"+title+"/"+gameTab;
 		AttachGameEvents(currentTab);
 		AttachScrollEvents();
 		if(type == "played"){
 			AddPlayedFabEvent();
 		}else{
 			AddWatchedFabEvent();
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
	$(".backButtonLabel").html("Back");
	
	if($(window).width() < 600){
		$(".backButtonLabel").css({"padding":"0"});
		$("#gameInnerContainer .backContainer").show();
		$("#gameInnerContainer .backContainer").css({"top":"7px", "position":"absolute"});
	}
	
	$('.gameNav').tabs();
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
 		ShowUserPreviewCard($(this).find(".user-preview-card"));
 	});
 	AttachAgrees();
  	Waves.displayEffect();
  	
  	$(".detailsBtn").on('click', function(e){ 
  		var userid = $(this).attr("data-uid");
  		var username = $(this).attr("data-uname");
  		DisplayUserDetails(userid, username);
  	});
	
 	$(".backButton").on('click', function(){
		BackOutOfGame(currentTab);
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
 	
 	AttachFloatingIconEvent(iconOnHover);
	AttachFloatingIconButtonEvents();
	AttachCriticBookmark();
	AttachAnalyzeEvents();
}

function DisplayUserDetails(userid, username){
	$("#userxp-tab-nav").html(username);
 	$("#userxp-tab-nav").parent().show(250);
 	$("#game-userxp-tab").css({"display":"block"});
	$("#userxp-tab-nav").click();
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
	 	$(".myxp-profile-tier-quote").on('click', function(){
			ShowUserProfile($(this).attr("data-userid"));
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
			AddWatchedFabEvent();
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
		var expid = $(this).attr("data-expid");
		var gameid = $(this).attr("data-gameid");
		var agreedwith = $(this).attr("data-agreedwith");
		var username = $(this).attr("data-username");
		SaveAgree(gameid, agreedwith, expid, username);
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
		var expid = $(this).attr("data-expid");
		var gameid = $(this).attr("data-gameid");
		var agreedwith = $(this).attr("data-agreedwith");
		var username = $(this).attr("data-username");
		RemoveAgree(gameid, agreedwith, expid, username);
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

function SaveAgree(gameid, agreedwith, expid, username){
	$.ajax({ url: '../php/webService.php',
     data: {action: 'SaveAgreed', gameid: gameid, agreedwith: agreedwith, expid: expid },
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

function RemoveAgree(gameid, agreedwith, expid, username){
	$.ajax({ url: '../php/webService.php',
     data: {action: 'RemoveAgreed', gameid: gameid, agreedwith: agreedwith, expid: expid },
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

function BackOutOfGame(currentTab){
 	if($(window).width() < 600){
 		$(".backButtonLabel").css({"padding":"0 2em"});
 	}else{
 		$("#gameInnerContainer .backContainer").delay(200).velocity({"opacity":"0"});
 	}
 	$(document).unbind("scroll");
   	$(".backButtonLabel").removeClass("GameBackButtonDisappear");
   	var windowWidth = $(window).width();
   	if(currentTab == "" || currentTab == undefined){
   		ShowDiscoverHome();
		currentTab = $("#discover");
   	}
    currentTab.css({"display":"inline-block", "left": -windowWidth});
    $("#game").css({"display":"none"});
	$("#game").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	currentTab.velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	
	var tabname = currentTab.attr("id");
	if(tabname == "discover"){
		GLOBAL_TAB_REDIRECT = "GameNav";
		ManuallyNavigateToTab("#discover");
		GLOBAL_TAB_REDIRECT = "";
	}
	
	if(tabname == "activity"){
		GLOBAL_TAB_REDIRECT = "GameNav";
		ManuallyNavigateToTab("#activity");
		GLOBAL_TAB_REDIRECT = "";
	}
	
	
	//window.scrollTo(0, 0);
	$("#sideContainer").html(SideContentPop());
	var method = SideContentEventPop();
	if(method != undefined)
		method();
	GLOBAL_HASH_REDIRECT = "NO";
	//location.hash = "";
}

function HideGameContainer(){
	var windowWidth = $(window).width();
    $("#game").css({"display":"none"});
	$("#game").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
}

function AttachScrollEvents(){
	$(document).unbind("scroll");
	$(document).on("scroll", function(){
        if($(document).scrollTop() > 5){
        	if($(window).width() > 599){
	          	$(".GameHeaderContainer, .GameHeaderBackground").addClass("GameHeaderShrink");
	          	$(".GameHeaderBackground").addClass("GameBackgroundOpacity");
	          	$(".backButtonLabel").addClass("GameBackButtonDisappear");
	          	$(".GameTitle").addClass("GameTitleToBack");
	          	$(".game-tab").addClass("game-tab-shrink");
        	}
        }
        else
        {
        	if($(window).width() > 599){
	        	$(".GameHeaderContainer, .GameHeaderBackground").removeClass("GameHeaderShrink");
	        	$(".GameHeaderBackground").removeClass("GameBackgroundOpacity");
	        	$(".backButtonLabel").removeClass("GameBackButtonDisappear");
	        	$(".GameTitle").removeClass("GameTitleToBack");
	        	$(".game-tab").removeClass("game-tab-shrink");
        	}
        }
    });
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
					        highlight: "#1398f0",
					        label: "Tier 1"
					    },
					     {
					        value: parseInt($(this).attr("data-t2")),
					        color:"#00B25C",
					        highlight: "#00d771",
					        label: "Tier 2"
					    },
					     {
					        value: parseInt($(this).attr("data-t3")),
					        color:"#FF8E00",
					        highlight: "#ffac46",
					        label: "Tier 3"
					    },
					     {
					        value: parseInt($(this).attr("data-t4")),
					        color:"#FF4100",
					        highlight: "#ff632f",
					        label: "Tier 4"
					    },
					     {
					        value: parseInt($(this).attr("data-t5")),
					        color:"#DB0058",
					        highlight: "#ff247b",
					        label: "Tier 5"
					    }
		    ];
	    if($(window).width() >=600){
	    	$(this).attr('height', 175);
	    	$(this).attr('width', 175);
	    }else{
	    	$(this).attr('height', 125);
	    	$(this).attr('width', 125);
	    }
      	var tierChart = new Chart(experiencedUsersGraph).Doughnut(data, { animation: false, showTooltips: true });
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
		if($("#game-community-tab").is(":visible"))
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
		if($("#game-community-tab").is(":visible"))
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
