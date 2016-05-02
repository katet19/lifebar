function ShowActivityHome(){
	ShowActivityContent("All");
}

function ShowActivityContent(filter){
  	ShowLoader($("#activityInnerContainer"), 'big', "<br><br><br>");
  	var windowWidth = $(window).width();
    $("#activity").css({"display":"inline-block", "left": -windowWidth});
    $("#discover, #profile, #admin, #profiledetails, #settings, #notifications, #game, #user, #landing").css({"display":"none"});
    $("#discover, #profile, #admin, #profiledetails, #settings, #notifications, #game, #user, #landing").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#activity").velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
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
	 	ShowUserPreviewCard($(this).find(".user-preview-card"), $("#activity"));
	 });
 	 $(".feed-avatar, .user-avatar").on("click", function(e){
	  	e.stopPropagation();
	 	ShowUserPreviewCard($(this).parent().find(".user-preview-card"), $("#activity"));
	 });
	 $(".feed-bookmark-card, .feed-activity-game-link, .feed-release-card").on("click", function(e){
	 	e.stopPropagation(); 
	 	ShowGame($(this).attr("data-gbid"), $("#activity"));
	 })
	 $(".feed-card-image").on("click", function(e){
	 	e.stopPropagation(); 
	 	ShowGame($(this).parent().attr("data-gbid"), $("#activity"));
	 })
 	 $(".collection-box-container").on("click", function(){
		DisplayCollectionDetails($(this).attr("data-id"), 'Activity', $(this).parent().parent().parent().find(".user-preview-card-container").attr("data-id"), false);	
	 });
	 AttachAgreesFromActivity();
	 $(window).unbind("scroll");
	 $(window).scroll(function(){
	 	if(isScrolledIntoView($("#feed-endless-loader"))){
	 		if($("#feed-endless-loader").html() == "")
      			EndlessLoader();
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
		AttachAgreesFromActivity();
	});
}

function AttachDisagreeFromActivity(){
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
		AttachAgreesFromActivity();
	});
}
