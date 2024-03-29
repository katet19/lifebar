function ShowActivityHome(){
	ShowActivityContent("All");
}

function ShowActivityContent(filter){
	ResetContentContainers();
  	ShowLoader($("#activityInnerContainer"), 'big', "<br><br><br>");
  	var windowWidth = $(window).width();
    $("#activity").css({"display":"inline-block", "left": -windowWidth});
    $("#discover, #ranking, #admin, #profiledetails, #settings, #notifications, #user, #landing").css({"display":"none"});
    $("#discover, #rankig, #admin, #profiledetails, #settings, #notifications, #user, #landing").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#activity").velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#gameInnerContainer").html("");
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayActivity", filter: filter },
     type: 'post',
     success: function(output) {
     			$("#activityInnerContainer").html(output);
				AttachActivityEvents();
				AttachSecondaryEvents();
      			Waves.displayEffect();
      			GAPage('Activity - '+filter, '/activity');
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

function RefreshActivity(filter){
  	ShowLoader($(".activity-top-level"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "RefreshMainActivity", filter: filter },
     type: 'post',
     success: function(output) {
     			$(".activity-top-level").html(output);
				AttachActivityEvents();
      			Waves.displayEffect();
      			GAPage('Activity - '+filter, '/activity');
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

function AttachActivityEvents(){
	 $(".user-discover-card").on("click", function(e){
	  	e.stopPropagation();
		ShowUserProfile($(this).attr("data-id"));
	 });
 	 $(".feed-avatar, .user-avatar").on("click", function(e){
	  	e.stopPropagation();
	 	ShowUserProfile($(this).attr("data-id"));
	 });
  	 $(".feed-activity-user-link-action").on("click", function(e){
	  	e.stopPropagation();
	 	ShowUserProfile($(this).attr("data-id"));
	 });
	 $(".feed-bookmark-card, .feed-activity-game-link, .feed-release-card, .feed-game-discover-card").on("click", function(e){
	 	e.stopPropagation(); 
	 	ShowGame($(this).attr("data-gbid"), $("#activity"));
	 })
	 $(".feed-card-image").on("click", function(e){
	 	e.stopPropagation(); 
	 	ShowGame($(this).parent().attr("data-gbid"), $("#activity"));
	 })
	 $(".feed-activity-collection-link").on("click", function(e){
	 	e.stopPropagation();
		DisplayCollectionDetails($(this).attr("data-cid"), 'Activity', $(this).parent().parent().parent().find(".user-preview-card-container").attr("data-id"), false);	
	 });
 	 $(".collection-box-container").on("click", function(e){
 	 	e.stopPropagation();
		DisplayCollectionDetails($(this).attr("data-id"), 'Activity', $(this).parent().parent().parent().find(".user-preview-card-container").attr("data-id"), false);	
	 });
	AttachWatchFromXP();
	$(".shareBtn").on('click', function(){
		ShowShareModal("event", $(this).attr("data-eventid"));
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
	$(".rank-image").on("click", function(e){
		e.stopPropagation(); 
		ShowGame($(this).parent().attr("data-gbid"), $("#discover")); 
	});
	$(".view-ranked-list").on("click", function(){
		ShowUserProfile($(this).attr("data-id"), "", "RANK");
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
	 AttachAgreesFromActivity();
	 $(window).unbind("scroll");
	 $(window).scroll(function(){
	 	if(isScrolledIntoView($("#feed-endless-loader"))){
	 		if($("#feed-endless-loader").html() == "" && $("#activity").is(":visible"))
      			EndlessLoader();
	 	}
     });
}

function AttachActivityVideoEvents(){
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
		var element = $(this).parent().parent().parent();
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
			var element = $(this).parent().parent().parent();
			var length = element.attr("data-length");
			var source = element.attr("data-source");
			var url = element.attr("data-url");
			var tier = element.find(".myxp-selected-tier").attr("data-tier");
			var summary = element.find(".myxp-quote").val();
			var quarter = element.attr("data-quarter");
			var year = element.attr("data-year");
			var gameid = element.attr("data-gameid");
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
					button.addClass("disabled");
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

function AttachSecondaryEvents(){
 	$(".activity-category-selector").on('click', function(e){
		e.stopPropagation();
		var selected = $(this).attr("class");
		$(".activity-category-selected").removeClass("activity-category-selected");
		$(".activity-category-selector").each(function(){
			var category = $(this).attr("id");
			if(category == selected)
				$(this).addClass("activity-category-selected");
		});
		var icon = $(this).attr("data-icon");
		var iconloc = $(".activity-header-icon .activity-header-icon-picker");
		iconloc.removeClass();
		iconloc.addClass(icon);
		iconloc.addClass("activity-header-icon-picker");
		$(this).addClass("activity-category-selected");
		if($(this).attr("id") == "activity-someoneelse"){
			ShowUserActivity($(this).attr("data-id"));
		}else{
			RefreshActivity($(this).attr("data-filter"));
		}
	});
}

function EndlessLoader(){
	ShowLoader($("#feed-endless-loader"), 'big', "<br><br><br>");
	$("#feed-endless-loader").append("<br><br><br>");
	var page = $("#feed-endless-loader").attr("data-page");
	var date = $("#feed-endless-loader").attr("data-date");
	var filter = $("#feed-endless-loader").attr("data-filter");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayActivityEndless", page: page, date: date, filter: filter },
     type: 'post',
     success: function(output) {
		$("#feed-endless-loader").before(output);
		$("#feed-endless-loader").html("");
		$("#feed-endless-loader").attr("data-page", parseInt(page) + 45);
		var lastdate = $("#feed-endless-loader").parent().find(".feed-date-divider:last").attr("data-date");
		$("#feed-endless-loader").attr("data-date", lastdate);
		AttachActivityEvents();
		GAPage('ActivityEndlessLoader - '+filter, '/activity');
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

function AttachAgreesFromActivity(){
	$(".agreeBtn").unbind();
	$(".disagreeBtn").unbind();
	AttachAgreeFromActivity();
	AttachDisagreeFromActivity();
}

function AttachAgreeFromActivity(){
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
		AttachAgreesFromActivity();
	});
}

function AttachDisagreeFromActivity(){
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
		AttachAgreesFromActivity();
	});
}
