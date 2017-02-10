<<<<<<< HEAD
function ShowUserProfile(id, mine, browserNav) {
    ShowUserContent(id, mine);
}

function ShowUserContent(userid, mine, browserNav) {
    var windowWidth = $(window).width();
    $("#profile").css({ "display": "inline-block", "left": windowWidth });
    if ($(window).width() > 599) {
        $("#navigation-header").css({ "display": "block" });
        $("#navigationContainer").css({ "-webkit-box-shadow": "0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow": "0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)" });
    }
    $('body').css({ 'overflow-y': 'hidden' });
    if ($("#profile").hasClass("outerContainer-slide-out"))
        $("#profile.outerContainer-slide-out").css({ "left": "225px" });
    else
        $("#profile.outerContainer").css({ "left": 0 });

    ShowLoader($("#profileInnerContainer"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayWeave", userid: userid },
        type: 'post',
        success: function(output) {
            $("#profileInnerContainer").html(output);
            Waves.displayEffect();
            var name = $("#profileContentContainer").attr("data-name");
            DisplayCriticGraph();
            DisplayLifeTimeChart();
            DisplaySkillsChart();
            AttachProfileEvents(userid);
            AttachFabHoverEvent();
            AttachFloatingIconWeaveButtonEvents();
            if (browserNav)
                UpdateBrowserHash("profile/" + userid + "/" + name);

            if (!mine)
                GAPage('Profile', '/profile/' + name);
            else
                GAPage('Profile', '/profile/personal/' + name);
        },
=======
function ShowUserProfile(id, mine, browserNav){
	if(!$("#profile").is(":visible"))
		ShowUserContent(id,mine);
}

function ShowUserContent(userid, mine, browserNav){
	var windowWidth = $(window).width();
    $("#profile").css({"display":"inline-block", "right": "-75%"});
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
	SCROLL_POS = $(window).scrollTop();
	$('body').css({'top': -($('body').scrollTop()) + 'px'}).addClass("bodynoscroll");
	$("body").append("<div class='lean-overlay' id='materialize-lean-overlay-1' style='z-index: 1002; display: block; opacity: 0.5;'></div>");
	$("#profile.outerContainer").css({ "right": 0 });

  	ShowLoader($("#profileInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayWeave", userid: userid },
     type: 'post',
     success: function(output) {
			$("#profileInnerContainer").html(output);
			Waves.displayEffect();
			AttachShowUserActivityEvents();
     },
>>>>>>> Akuma
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

<<<<<<< HEAD
function AttachProfileEvents(userid) {
    $(".fixed-close-modal-btn").on('click', function() {
        var windowWidth = $(window).width();
        $("#profile").css({ "left": windowWidth });
        setTimeout(function() { $("#profile").css({ "display": "none" });
            $('body').css({ 'overflow-y': 'scroll' }); }, 300);
    });
    $(".userprofile-card-avatar").on("click", function(e) {
        e.stopPropagation();
        ShowUserProfile($(this).attr("data-userid"));
    });
    $(".userprofile-game-card-image").on('click', function(e) {
        e.stopPropagation();
        var game = $(this).parent().attr("data-gbid");
        setTimeout(function() { ShowGame(game, $("#profile")); }, 500);
    });
    $(".card-tier-container").on('click', function() {
        $(this).addClass("card-tier-container-active");
        $(this).parent().parent().find(".card-tier").hide();
        $(this).find(".card-tier-details").addClass("card-tier-details-active");
        $(this).find(".mdi-content-add-circle").on("click", function(e) {
            e.stopPropagation();
            $(this).parent().parent().parent().removeClass("card-tier-container-active");
            $(this).parent().parent().removeClass("card-tier-details-active");
            $(this).parent().parent().parent().parent().find(".card-tier").show();
        });
    });
    $(".badge-view-more").on('click', function() {
        DisplayUserBadges(userid);
    });
    $(".feed-bookmark-card, .feed-activity-game-link, .feed-release-card, .calendar-card, .profile-highlighted-game, .profile-best-game, .checkpoint-container").on("click", function(e) {
        e.stopPropagation();
        var game = $(this).attr("data-gbid");
        setTimeout(function() { ShowGame(game, $("#profile")); }, 500);
    });
    $(".profile-highlighted-game-quote, .profile-highlighted-game-name").on("click", function(e) {
        e.stopPropagation();
        var game = $(this).parent().find(".profile-highlighted-game").attr("data-gbid");
        setTimeout(function() { ShowGame(game, $("#profile")); }, 500);
    });
    $(".ability-spy").on("click", function() {
        DisplaySpy(userid);
    });
    $(".ability-charisma").on("click", function() {
        DisplayCharisma(userid);
    });
    $(".ability-leadership").on("click", function() {
        DisplayLeadership(userid);
    });
    $(".ability-tracking").on("click", function() {
        DisplayTracking(userid);
    });
    $(".abilities-view-more").on("click", function() {
        DisplayAbilitiesViewMore(userid);
    });
    $(".knowledge-view-more").on("click", function() {
        DisplayKnowledgeViewMore(userid);
    });
    $(".knowledge-container").on("click", function() {
        DisplayKnowledgeDetails(userid, $(this).attr("data-objectid"), $(this).attr("data-progid"));
    });
    $(".gear-view-more").on("click", function() {
        DisplayGearViewMore(userid);
    });
    $(".badge-small").on("click", function() {
        var id = $(this).attr("data-objectid");
        var progid = $(this).attr("data-progid");
        DisplayGearDetails(userid, id, progid);
    });
    $(".profile-best-view-more").on("click", function() {
        DisplayBestViewMore(userid);
    });
    $(".developer-view-more").on("click", function() {
        DisplayDeveloperViewMore(userid);
    });
    $(".developer-profile-item").on("click", function() {
        DisplayDeveloperDetails(userid, $(this).attr("data-objectid"), $(this).attr("data-progid"));
    });
    $(".mylibrary").on("click", function() {
        DisplayMyLibrary(userid);
    });
    $(".view-collections").on("click", function() {
        DisplayUserCollection(userid);
    });
    $(".collection-box-container").on("click", function() {
        DisplayCollectionDetails($(this).attr("data-id"), 'Profile', userid);
    });
    /*
     * New Profile
     */
    $(".newprofile-link-discover").on('click', function() {
        ShowDiscoverHome();
    });
    $(".newprofile-skills-item").on("click", function() {
        AdvancedSearch('', '', '', '', '', $(this).attr("data-id"), '');
    });
    $(".newprofile-knowledge-item").on('click', function() {
        AdvancedSearch('', '', '', '', '', '', $(this).attr("data-id"));
    });
    $(".newprofile-gear-item").on('click', function() {
        AdvancedSearch('', $(this).attr("data-id"), '', '', '', '', '');
    });
    $(".newprofile-dev-item").on('click', function() {
        AdvancedSearch('', '', '', '', $(this).attr("data-id"), '', '');
    });
    $(".newprofile-best-item").on('click', function() {
        ShowGame($(this).attr("data-id"), $("#profile"));
    });
=======
function AttachProfileEvents(userid){
	$(".fixed-close-modal-btn, .lean-overlay").unbind();
	$(".fixed-close-modal-btn, .lean-overlay").on('click', function(){
		$("#profile").css({ "right": "-75%" }); 
		$(".lean-overlay").each(function(){ $(this).remove(); } );
		setTimeout(function(){ $("#profile").css({"display":"none"}); $('body').removeClass("bodynoscroll").css({'top': $(window).scrollTop(SCROLL_POS) + 'px'}); }, 300);
	});
 	$(".userprofile-card-avatar").on("click", function(e){
  		e.stopPropagation();
 		ShowUserProfile($(this).attr("data-userid"));
 	});
 	$(".userprofile-game-card-image").on('click', function(e){
 		e.stopPropagation();
 	 	var game = $(this).parent().attr("data-gbid");
	 	setTimeout(function(){ ShowGame(game, $("#profile")); }, 500);
 	});
	$(".card-tier-container").on('click', function(){
		$(this).addClass("card-tier-container-active");
		$(this).parent().parent().find(".card-tier").hide();
		$(this).find(".card-tier-details").addClass("card-tier-details-active");
		$(this).find(".mdi-content-add-circle").on("click", function(e){
			e.stopPropagation();
			$(this).parent().parent().parent().removeClass("card-tier-container-active");
			$(this).parent().parent().removeClass("card-tier-details-active");
			$(this).parent().parent().parent().parent().find(".card-tier").show();
		});
	});
	$(".badge-view-more").on('click', function(){
		DisplayUserBadges(userid);		
	});
	 $(".feed-bookmark-card, .feed-activity-game-link, .feed-release-card, .calendar-card, .profile-highlighted-game, .profile-best-game, .checkpoint-container").on("click", function(e){
	 	e.stopPropagation();
	 	var game = $(this).attr("data-gbid");
	 	setTimeout(function(){ ShowGame(game, $("#profile")); }, 500);
	 });
 	 $(".profile-highlighted-game-quote, .profile-highlighted-game-name").on("click", function(e){
	 	e.stopPropagation();
	 	var game = $(this).parent().find(".profile-highlighted-game").attr("data-gbid");
	 	setTimeout(function(){ ShowGame(game, $("#profile")); }, 500);
	 });
	 $(".ability-spy").on("click", function(){
 		DisplaySpy(userid);
	 });
	 $(".ability-charisma").on("click", function(){
	 	DisplayCharisma(userid);
	 });
 	 $(".ability-leadership").on("click", function(){
	 	DisplayLeadership(userid);
	 });
  	 $(".ability-tracking").on("click", function(){
	 	DisplayTracking(userid);
	 });
	 $(".abilities-view-more").on("click", function(){
 		DisplayAbilitiesViewMore(userid);
	 });
	 $(".knowledge-view-more").on("click", function(){
	 	DisplayKnowledgeViewMore(userid);
	 });
	 $(".knowledge-container").on("click", function(){
	 	DisplayKnowledgeDetails(userid, $(this).attr("data-objectid"), $(this).attr("data-progid"));
	 });
	 $(".gear-view-more").on("click", function(){
	 	DisplayGearViewMore(userid);
	 });
  	$(".badge-small").on("click", function(){
 		var id = $(this).attr("data-objectid");
 		var progid = $(this).attr("data-progid");
 		DisplayGearDetails(userid, id, progid);
 	});
 	$(".profile-best-view-more").on("click", function(){
 		DisplayBestViewMore(userid);	
 	});
 	 $(".developer-view-more").on("click", function(){
	 	DisplayDeveloperViewMore(userid);
	 });
 	 $(".developer-profile-item").on("click", function(){
	 	DisplayDeveloperDetails(userid, $(this).attr("data-objectid"), $(this).attr("data-progid"));
	 });
	 $(".mylibrary").on("click", function(){
	 	DisplayMyLibrary(userid);
	 });
 	 $(".view-collections").on("click", function(){
 		DisplayUserCollection(userid);
	 });
 	 $(".collection-box-container").on("click", function(){
		 DisplayCollectionDetails($(this).attr("data-id"), 'Profile', userid);	
	 });
	 /*
	 * New Profile
	 */
	 $(".newprofile-link-discover").on('click', function(){
 		ShowDiscoverHome();
	 });
	 $(".newprofile-skills-item").on("click", function(){
	 	AdvancedSearch('', '', '', '', '', $(this).attr("data-id"), '');
	 });
	 $(".newprofile-knowledge-item").on('click', function(){
	 	AdvancedSearch('', '', '', '', '', '', $(this).attr("data-id"));
	 });
 	 $(".newprofile-gear-item").on('click', function(){
	 	AdvancedSearch('', $(this).attr("data-id"), '', '', '', '', '');
	 });
  	 $(".newprofile-dev-item").on('click', function(){
	 	AdvancedSearch('', '', '', '', $(this).attr("data-id"), '', '');
	 });
  	 $(".newprofile-best-item").on('click', function(){
	 	ShowGame($(this).attr("data-id"), $("#profile"));
	 });
>>>>>>> Akuma
}

function EndlessMyLibraryLoader(userid) {
    ShowLoader($("#mylibrary-endless-loader"), 'big', "<br><br><br>");
    $("#mylibrary-endless-loader").append("<br><br><br>");
    var page = $("#mylibrary-endless-loader").attr("data-page");
    var group = $("#mylibrary-endless-loader").attr("data-date");
    var filter = $("#mylibrary-endless-loader").attr("data-filter");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayMyLibraryEndless", userid: userid, page: page, group: group, filter: filter },
        type: 'post',
        success: function(output) {
            $("#mylibrary-endless-loader").before(output);
            $("#mylibrary-endless-loader").html("");
            $("#mylibrary-endless-loader").attr("data-page", parseInt(page) + 100);
            var lastdate = $("#mylibrary-endless-loader").parent().find(".feed-date-divider:last").attr("data-date");
            $("#mylibrary-endless-loader").attr("data-date", lastdate);
            AttachMyLibraryEvents(userid);
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function DisplayTracking(userid) {
    ShowProfileDetails("<div class='universalBottomSheetLoading'></div>");
    ShowLoader($(".universalBottomSheetLoading"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayTrackingAbility", userid: userid },
        type: 'post',
        success: function(output) {
            $("#BattleProgess").html(output);
            $(".card-game-small").on("click", function(e) {
                e.stopPropagation();
                var game = $(this).attr("data-gbid");
                ShowGame(game, $("#profile"));
                $("#lean-overlay").trigger("click");
            });

        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function DisplayLeadership(userid) {
    ShowProfileDetails("<div class='universalBottomSheetLoading'></div>");
    ShowLoader($(".universalBottomSheetLoading"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayLeadershipAbility", userid: userid },
        type: 'post',
        success: function(output) {
            $("#BattleProgess").html(output);
            $(".badge-card-ability-avatar").on("click", function() {
                ShowUserProfile($(this).attr("data-id"));
                $("#lean-overlay").trigger("click");
            });
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function DisplayCharisma(userid) {
    ShowProfileDetails("<div class='universalBottomSheetLoading'></div>");
    ShowLoader($(".universalBottomSheetLoading"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayCharismaAbility", userid: userid },
        type: 'post',
        success: function(output) {
            $("#BattleProgess").html(output);

        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function DisplaySpy(userid) {
    ShowProfileDetails("<div class='universalBottomSheetLoading'></div>");
    ShowLoader($(".universalBottomSheetLoading"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplaySpyAbility", userid: userid },
        type: 'post',
        success: function(output) {
            $("#BattleProgess").html(output);
            $(".badge-card-ability-avatar").on("click", function() {
                ShowUserProfile($(this).attr("data-id"));
                $("#lean-overlay").trigger("click");
            });
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function DisplayAbilitiesViewMore(userid) {
    window.scrollTo(0, 0);
    ShowLoader($("#profileInnerContainer"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayAbilitiesViewMore", userid: userid },
        type: 'post',
        success: function(output) {
            $("#profileInnerContainer").html(output);
            $(".backButton").on("click", function() {
                ShowUserProfile(userid);
            });
            $(".badge-card-ability-avatar").on("click", function() {
                ShowUserProfile($(this).attr("data-userid"));
            });
            $(".card-game-small").on("click", function(e) {
                e.stopPropagation();
                var game = $(this).attr("data-gbid");
                ShowGame(game, $("#profile"));
            });
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function DisplayMyLibrary(userid) {
    window.scrollTo(0, 0);
    ShowLoader($("#profileInnerContainer"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayMyLibrary", userid: userid, filter: 'Alpha' },
        type: 'post',
        success: function(output) {
            $("#profileInnerContainer").html(output);
            AttachMyLibraryEvents(userid);
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function AttachMyLibraryEvents(userid) {
    $(".card-game-list").on("click", function(e) {
        e.stopPropagation();
        var game = $(this).attr("data-gbid");
        ShowGame(game, $("#profile"));
        $("#lean-overlay").trigger("click");
    });
    $(".backButton").on("click", function() {
        ShowUserProfile(userid);
    });
    $(window).scroll(function() {
        if (isScrolledIntoView($("#mylibrary-endless-loader"))) {
            if ($("#mylibrary-endless-loader").html() == "")
                EndlessMyLibraryLoader(userid);
        }
    });
    $('.mylib-tier').change(function() {
        if (this.checked) {
            MyLibraryToggleTier(true, $(this).attr("data-tier"));
        } else {
            MyLibraryToggleTier(false, $(this).attr("data-tier"));
        }
    });
}

function MyLibraryToggleTier(display, tier) {
    $(".game-list-item").each(function(e) {
        var tempTier = $(this).attr("data-tier");
        if (tier == tempTier) {
            if (display)
                $(this).show();
            else
                $(this).hide();
        }
    });
}

function DisplayKnowledgeViewMore(userid) {
    window.scrollTo(0, 0);
    ShowLoader($("#profileInnerContainer"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayKnowledgeViewMore", userid: userid },
        type: 'post',
        success: function(output) {
            $("#profileInnerContainer").html(output);
            $(".backButton").on("click", function() {
                ShowUserProfile(userid);
            });
            $(".knowledge-container").on("click", function() {
                DisplayKnowledgeDetails(userid, $(this).attr("data-objectid"), $(this).attr("data-progid"));
            });
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function DisplayKnowledgeDetails(userid, objectid, progressid) {
    ShowProfileDetails("<div class='universalBottomSheetLoading'></div>");
    ShowLoader($(".universalBottomSheetLoading"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayKnowledgeDetails", userid: userid, objectid: objectid, progressid: progressid, },
        type: 'post',
        success: function(output) {
            $("#BattleProgess").html(output);
            $(".card-game-small").on("click", function(e) {
                e.stopPropagation();
                var game = $(this).attr("data-gbid");
                ShowGame(game, $("#profile"));
                $("#lean-overlay").trigger("click");
            });
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function DisplayGearViewMore(userid) {
    window.scrollTo(0, 0);
    ShowLoader($("#profileInnerContainer"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayGearViewMore", userid: userid },
        type: 'post',
        success: function(output) {
            $("#profileInnerContainer").html(output);
            $(".backButton").on("click", function() {
                ShowUserProfile(userid);
            });
            $(".card-game-small").on("click", function(e) {
                e.stopPropagation();
                var game = $(this).attr("data-gbid");
                ShowGame(game, $("#profile"));
                $("#lean-overlay").trigger("click");
            });
            $(".badge-small").on("click", function() {
                var id = $(this).attr("data-objectid");
                var progid = $(this).attr("data-progid");
                DisplayGearDetails(userid, id, progid);
            });
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function DisplayGearDetails(userid, objectid, progressid) {
    ShowProfileDetails("<div class='universalBottomSheetLoading'></div>");
    ShowLoader($(".universalBottomSheetLoading"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayGearDetails", userid: userid, objectid: objectid, progressid: progressid, },
        type: 'post',
        success: function(output) {
            $("#BattleProgess").html(output);
            $(".card-game-small").on("click", function(e) {
                e.stopPropagation();
                var game = $(this).attr("data-gbid");
                ShowGame(game, $("#profile"));
                $("#lean-overlay").trigger("click");
            });
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });

}

function DisplayBestViewMore(userid) {
    window.scrollTo(0, 0);
    ShowLoader($("#profileInnerContainer"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayBestViewMore", userid: userid },
        type: 'post',
        success: function(output) {
            $("#profileInnerContainer").html(output);
            $(".backButton").on("click", function() {
                ShowUserProfile(userid);
            });
            $(".card-game-small").on("click", function(e) {
                e.stopPropagation();
                var game = $(this).attr("data-gbid");
                ShowGame(game, $("#profile"));
                $("#lean-overlay").trigger("click");
            });
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function DisplayDeveloperViewMore(userid) {
    window.scrollTo(0, 0);
    ShowLoader($("#profileInnerContainer"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayDeveloperViewMore", userid: userid },
        type: 'post',
        success: function(output) {
            $("#profileInnerContainer").html(output);
            $(".backButton").on("click", function() {
                ShowUserProfile(userid);
            });
            $(".knowledge-container").on("click", function() {
                DisplayDeveloperDetails(userid, $(this).attr("data-objectid"), $(this).attr("data-progid"));
            });
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}


function DisplayDeveloperDetails(userid, objectid, progressid) {
    ShowProfileDetails("<div class='universalBottomSheetLoading'></div>");
    ShowLoader($(".universalBottomSheetLoading"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayDeveloperDetails", userid: userid, objectid: objectid, progressid: progressid, },
        type: 'post',
        success: function(output) {
            $("#BattleProgess").html(output);
            $(".card-game-small").on("click", function(e) {
                e.stopPropagation();
                var game = $(this).attr("data-gbid");
                ShowGame(game, $("#profile"));
                $("#lean-overlay").trigger("click");
            });
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function AttachNavInThread() {
    $(".thread-game-full-w-leftnav").on("click", function() {
        var current = $(this).parent().find(".thread-nav-active");
        current.removeClass("thread-nav-active");
        current.addClass("thread-nav-right");
        setTimeout(function() { current.addClass("thread-nav-hidden");
            current.removeClass("thread-nav-right"); }, 1000);
        var prev = current.prev();
        prev.removeClass("thread-nav-hidden");
        prev.addClass("thread-nav-active");
        var ondeck = prev.prev();
        if (!ondeck.hasClass("thread-nav-hidden")) {
            $(this).parent().find(".thread-game-full-w-leftnav").hide();
        } else {
            $(this).parent().find(".thread-game-full-w-leftnav").show();
        }
        $(this).parent().find(".thread-game-full-w-rightnav").show();
    });
    $(".thread-game-full-w-rightnav").on("click", function() {
        var current = $(this).parent().find(".thread-nav-active");
        current.removeClass("thread-nav-active");
        current.addClass("thread-nav-left");
        setTimeout(function() { current.addClass("thread-nav-hidden");
            current.removeClass("thread-nav-left"); }, 1000);
        var next = current.next();
        next.removeClass("thread-nav-hidden");
        next.addClass("thread-nav-active");
        var ondeck = next.next();
        if (!ondeck.hasClass("thread-nav-hidden")) {
            $(this).parent().find(".thread-game-full-w-rightnav").hide();
        } else {
            $(this).parent().find(".thread-game-full-w-rightnav").show();
        }
        $(this).parent().find(".thread-game-full-w-leftnav").show();
    });

}

function DisplayTierGraphChart() {
    if ($(".GraphTiers").length > 0) {
        $(".GraphTiers").each(function() {
            var experiencedUsersGraph = $(this).get(0).getContext("2d");
            var data = {
                labels: ["Tier 1", "Tier 2", "Tier 3", "Tier 4", "Tier 5"],
                datasets: [{
                    label: "Lifetime XP",
                    fillColor: "#A7A7CC",
                    strokeColor: "#555593",
                    pointColor: "#555593",
                    pointStrokeColor: "rgba(71,71,71,1)",
                    pointHighlightFill: "rgba(71,71,71,1)",
                    pointHighlightStroke: "rgba(71,71,71,1)",
                    data: [$(this).attr("data-t1"), $(this).attr("data-t2"), $(this).attr("data-t3"), $(this).attr("data-t4"), $(this).attr("data-t5")]
                }]
            };
            $(this).attr('width', $(this).parent().width() - 25);
            $(this).attr('height', 200);

            var temp = new Chart(experiencedUsersGraph).Line(data, { datasetStrokeWidth: 4, showScale: true, bezierCurve: true, pointDot: false, scaleFontColor: "rgba(71,71,71,1)", scaleShowGridLines: false, multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>", animation: true });

        });
    }

    if ($(".SmallerGraphTiers").length > 0) {
        $(".SmallerGraphTiers").each(function() {
            var experiencedUsersGraph = $(this).get(0).getContext("2d");
            var data = {
                labels: ["Tier 1", "Tier 2", "Tier 3", "Tier 4", "Tier 5"],
                datasets: [{
                    label: "Lifetime XP",
                    fillColor: "rgb(255, 0, 97)",
                    strokeColor: "rgb(255, 0, 97)",
                    pointColor: "rgb(255, 0, 97)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgb(255, 0, 97)",
                    data: [$(this).attr("data-t1"), $(this).attr("data-t2"), $(this).attr("data-t3"), $(this).attr("data-t4"), $(this).attr("data-t5")]
                }]
            };
            $(this).attr('width', $(this).parent().width() - 10);
            $(this).attr('height', 200);

            var temp = new Chart(experiencedUsersGraph).Line(data, { datasetStrokeWidth: 4, showScale: true, bezierCurve: true, pointDot: false, scaleShowGridLines: false, multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>", animation: true });

        });
    }

}

function AttachFabHoverEvent() {
    var iconOnHover = "";
    if ($(".fixed-action-btn .user-unfollow-btn").length > 0)
        iconOnHover = "mdi-social-person-outline";
    else
        iconOnHover = "mdi-social-person-add";

    AttachFloatingIconEvent(iconOnHover);
}

function AttachFloatingIconWeaveButtonEvents() {
    $(".user-add-small-image-btn").on('click', function() {
        var html = "<div><span>User ID: " + $(this).attr("data-userid") + "</span><br><iframe src='http://lifebar.io/utilities/FileImageUploaderSmall.php' style='width:100%;border:none;'></iframe>";
        ShowPopUp(html);
    });
    $(".user-add-large-image-btn").on('click', function() {
        var html = "<div><span>User ID: " + $(this).attr("data-userid") + "</span><br><iframe src='http://lifebar.io/utilities/FileImageUploaderLarge.php' style='width:100%;border:none;'></iframe>";
        ShowPopUp(html);
    });
    $(".user-run-weave-cal-btn").on('click', function() {
        Toast("Calculating user's weave");
        $.ajax({
            url: '../php/webService.php',
            data: { action: "RunWeaveCalculator", userid: $(this).attr("data-userid") },
            type: 'post',
            success: function(output) {
                Toast("Finished calculating and updating weave. Please refresh to see changes.")
            },
            error: function(x, t, m) {
                if (t === "timeout") {
                    ToastError("Server Timeout");
                } else {
                    ToastError(t);
                }
            },
            timeout: 45000
        });
    });
    $(".user-share-btn").on("click", function() {
        ShowShareModal("user", $(this).attr("data-userid"));
    });
    $(".user-set-title").on("click", function() {
        var userid = $(this).attr("data-userid");
        var html = "<div class='row'><div class='col s12 input-field'><input type=text name='updatetitle' id='updatetitle' /><label for='updatetitle'>Title/Publication</label></div><div class='btn wave-effect update-title-btn'>Update Title<div></div>";
        ShowPopUp(html);
        $(".update-title-btn").on('click', function() {
            $.ajax({
                url: '../php/webService.php',
                data: { action: "SaveTitle", userid: userid, title: $("#updatetitle").val() },
                type: 'post',
                success: function(output) {
                    Toast("Users Title/Publication updated. Please refresh to see changes.")
                },
                error: function(x, t, m) {
                    if (t === "timeout") {
                        ToastError("Server Timeout");
                    } else {
                        ToastError(t);
                    }
                },
                timeout: 45000
            });
        });
    });
    $(".user-set-role").on("click", function() {
        var userid = $(this).attr("data-userid");
        DisplayRoleManagement(userid);
    });
    $(".user-manage-badge").on("click", function() {
        var userid = $(this).attr("data-userid");
        DisplayManageBadge(userid);
    });
    AttachFollowFABEvents();
}

function AttachFollowFABEvents() {
    $(".user-unfollow-btn, .user-follow-btn").unbind();
    $(".user-unfollow-btn").on('click', function() {
        UnfollowUserFromFab($(this).attr("data-userid"), $(this).attr("data-username"));
        $(this).removeClass("user-unfollow-btn");
        $(this).addClass("user-follow-btn");
        $(this).find(".GameHiddenActionLabelBigFab").html("Follow");
        $(this).find(".large").removeClass("mdi-social-person-outline");
        $(this).find(".large").addClass("mdi-social-group");
        AttachFabHoverEvent();
        AttachFollowFABEvents();
    });
    $(".user-follow-btn").on('click', function() {
        FollowUserFromFab($(this).attr("data-userid"), $(this).attr("data-username"));
        $(this).removeClass("user-follow-btn");
        $(this).addClass("user-unfollow-btn");
        $(this).find(".GameHiddenActionLabelBigFab").html("Unfollow");
        $(this).find(".large").removeClass("mdi-social-person-add");
        $(this).find(".large").addClass("mdi-social-group");
        AttachFabHoverEvent();
        AttachFollowFABEvents();
    });
}

function DisplayRoleManagement(userid) {
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayRoleManagement", userid: userid },
        type: 'post',
        success: function(output) {
            ShowPopUp(output);
            AttachRoleManagementEvents(userid);
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function AttachRoleManagementEvents(userid) {
    $(".save-role-change").on('click', function() {
        var role = $("input[type=radio][name=rolegroup]:checked").attr("id");
        $.ajax({
            url: '../php/webService.php',
            data: { action: "UpdateRoleManagement", userid: userid, role: role },
            type: 'post',
            success: function(output) {
                Toast('Role has been updated');
                $("#universalPopUp").closeModal();
                HideFocus();
            },
            error: function(x, t, m) {
                if (t === "timeout") {
                    ToastError("Server Timeout");
                } else {
                    ToastError(t);
                }
            },
            timeout: 45000
        });
    });
}

function DisplayManageBadge(userid) {
    ShowProfileDetails("<div class='universalBottomSheetLoading'></div>");
    ShowLoader($(".universalBottomSheetLoading"), 'big', "<br><br><br>");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "DisplayAdminControlsForUser", userid: userid },
        type: 'post',
        success: function(output) {
            $("#BattleProgess").html(output);
            AttachManageBadgeEvents(userid);
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function AttachManageBadgeEvents(userid) {
    AttachGiveBadge(userid);
    AttachRemoveBadge(userid);
    EquipBadge(userid);
    UnequipBadge(userid);
}

function AttachGiveBadge(userid) {
    $(".badge-give").unbind();
    $(".badge-give").on("click", function() {
        var btn = $(this);
        $.ajax({
            url: '../php/webService.php',
            data: { action: "AdminGiveBadge", userid: userid, badgeid: $(this).attr("data-badgeid") },
            type: 'post',
            success: function(output) {
                btn.parent().parent().find(".badge-image-container").addClass("badge-active");
                btn.addClass("badge-remove");
                btn.removeClass("badge-give");
                btn.html("Remove");
                Toast("Badge Given");
                AttachManageBadgeEvents(userid);
            },
            error: function(x, t, m) {
                if (t === "timeout") {
                    ToastError("Server Timeout");
                } else {
                    ToastError(t);
                }
            },
            timeout: 45000
        });
    });
}

function AttachRemoveBadge(userid) {
    $(".badge-remove").unbind();
    $(".badge-remove").on("click", function() {
        var btn = $(this);
        $.ajax({
            url: '../php/webService.php',
            data: { action: "AdminRemoveBadge", userid: userid, badgeid: $(this).attr("data-badgeid") },
            type: 'post',
            success: function(output) {
                btn.parent().parent().find(".badge-image-container").removeClass("badge-active");
                btn.addClass("badge-give");
                btn.removeClass("badge-remove");
                btn.html("Give");
                Toast("Badge Removed");
                AttachManageBadgeEvents(userid);
            },
            error: function(x, t, m) {
                if (t === "timeout") {
                    ToastError("Server Timeout");
                } else {
                    ToastError(t);
                }
            },
            timeout: 45000
        });
    });
}

<<<<<<< HEAD
function UnequipBadge(userid) {
    $(".badge-unequip").unbind();
    $(".badge-unequip").on("click", function() {
        var btn = $(this);
        $.ajax({
            url: '../php/webService.php',
            data: { action: "UnequipBadge", userid: userid, badgeid: $(this).attr("data-badgeid") },
            type: 'post',
            success: function(output) {
                btn.addClass("badge-equip");
                btn.removeClass("badge-unequip");
                btn.html("Equip");
                Toast("Badge Unequipped");
                if ($(".avatar-preview").length > 0) {
                    UpdateAvatarBadge("REMOVE");
                }

                AttachManageBadgeEvents(userid);
            },
            error: function(x, t, m) {
                if (t === "timeout") {
                    ToastError("Server Timeout");
                } else {
                    ToastError(t);
                }
            },
            timeout: 45000
        });
    });
}

function EquipBadge(userid) {
    $(".badge-equip").unbind();
    $(".badge-equip").on("click", function() {
        var btn = $(this);
        $.ajax({
            url: '../php/webService.php',
            data: { action: "EquipBadge", userid: userid, badgeid: $(this).attr("data-badgeid") },
            type: 'post',
            success: function(output) {
                btn.addClass("badge-unequip");
                btn.removeClass("badge-equip");
                btn.html("Unequip");
                Toast("Badge Equipped");
                if ($(".avatar-preview").length > 0) {
                    UpdateAvatarBadge(output);
                }
                AttachManageBadgeEvents(userid);
            },
            error: function(x, t, m) {
                if (t === "timeout") {
                    ToastError("Server Timeout");
                } else {
                    ToastError(t);
                }
            },
            timeout: 45000
        });
    });
=======
function UnequipBadge(userid){
	$(".badge-unequip").unbind();
	$(".badge-unequip").on("click", function(){
		var btn = $(this);
		$.ajax({ url: '../php/webService.php',
	     data: {action: "UnequipBadge", userid: userid, badgeid: $(this).attr("data-badgeid") },
	     type: 'post',
	     success: function(output) {
			btn.addClass("badge-equip");
	     	btn.removeClass("badge-unequip");
	     	btn.html("Equip");
			Toast("Badge Unequipped");			
			AttachManageBadgeEvents(userid);
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

function EquipBadge(userid){
	$(".badge-equip").unbind();
	$(".badge-equip").on("click", function(){
		var btn = $(this);
		$.ajax({ url: '../php/webService.php',
	     data: {action: "EquipBadge", userid: userid, badgeid: $(this).attr("data-badgeid") },
	     type: 'post',
	     success: function(output) {
			$(".badge-unequip").each(function(){
				$(this).removeClass("badge-unequip");
				$(this).html("Equip");
			});
 	     	btn.addClass("badge-unequip");
	     	btn.removeClass("badge-equip");
	     	btn.html("Unequip");
			Toast("Badge Equipped");
			AttachManageBadgeEvents(userid);
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
>>>>>>> Akuma
}

function FollowUserFromFab(followid, name) {
    $.ajax({
        url: '../php/webService.php',
        data: { action: "FollowUser", followid: followid },
        type: 'post',
        success: function(output) {
            Toast("You are now following " + name);
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function UnfollowUserFromFab(followid, name) {
    $.ajax({
        url: '../php/webService.php',
        data: { action: "UnfollowUser", followid: followid },
        type: 'post',
        success: function(output) {
            Toast("You are no longer following " + name);
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

function DisplayTierPieChart() {
    if ($(".GraphTierPieChart").length > 0) {
        $(".GraphTierPieChart").each(function() {
            var experiencedUsersGraph = $(this).get(0).getContext("2d");
            var data = [{
                    value: parseInt($(this).attr("data-t5")),
                    color: "#0A67A3",
                    highlight: "#1398f0",
                    label: "Tier 5"
                },
                {
                    value: parseInt($(this).attr("data-t4")),
                    color: "#00B25C",
                    highlight: "#00d771",
                    label: "Tier 4"
                },
                {
                    value: parseInt($(this).attr("data-t3")),
                    color: "#FF8E00",
                    highlight: "#ffac46",
                    label: "Tier 3"
                },
                {
                    value: parseInt($(this).attr("data-t2")),
                    color: "#FF4100",
                    highlight: "#ff632f",
                    label: "Tier 2"
                },
                {
                    value: parseInt($(this).attr("data-t1")),
                    color: "#DB0058",
                    highlight: "#ff247b",
                    label: "Tier 1"
                }
            ];
            if ($(window).width() >= 600) {
                $(this).attr('height', 200);
            } else {
                $(this).attr('height', 125);
            }
            var tierChart = new Chart(experiencedUsersGraph).Doughnut(data, { animation: false, showTooltips: true });
        });
    }
}

function DisplayCriticGraph() {
    if ($(".GraphCriticUsers").length > 0) {
        $(".GraphCriticUsers").each(function() {
            var experiencedUsersGraph = $(this).get(0).getContext("2d");
            var data = {
                labels: ["", "", "", "", ""],
                datasets: [{
                    label: "Lifetime XP",
                    fillColor: "rgba(85, 85, 147, 0.41)",
                    strokeColor: "rgba(85, 85, 147, 0.9)",
                    pointColor: "rgba(85, 85, 147, 0.9)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(71,71,71,1)",
                    data: [$(this).attr("data-t5"), $(this).attr("data-t4"), $(this).attr("data-t3"), $(this).attr("data-t2"), $(this).attr("data-t1")]
                }]
            };
            $(this).attr('width', $(this).parent().width() - 40);
            $(this).attr('height', 250);
            var temp = new Chart(experiencedUsersGraph).Line(data, { animation: false, datasetStrokeWidth: 4, showScale: true, bezierCurve: true, pointDot: false, scaleShowGridLines: false, multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>", animation: true });

        });
    }
}

function DisplaySkillsChart() {
    if ($(".GraphSkills").length > 0) {
        $(".GraphSkills").each(function() {
            var skillGraph = $(this).get(0).getContext("2d");
            var dataLabels = $(this).attr('data-labels');
            var labelArray = dataLabels.split(",");
            var dataValues = $(this).attr('data-values');
            var valueArray = dataValues.split(",");
            var allLabels = [];
            var allValues = [];
            $.each(labelArray, function(key, value) {
                allLabels.push(value);
            });
            $.each(valueArray, function(key, value) {
                allValues.push(value);
            });

            var data = {
                labels: allLabels,
                datasets: [{
                    label: "Skills",
                    fillColor: "rgba(156, 39, 176, 0.5)",
                    strokeColor: "rgba(156, 39, 176, 0.5)",
                    pointColor: "rgba(156, 39, 176, 0.5)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(71,71,71,1)",
                    data: allValues
                }]
            };
            $(this).attr('width', $(this).parent().width() - 25);
            $(this).attr('height', 360);
            var temp = new Chart(skillGraph).Radar(data, { animation: false, multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>", scaleShowLine: false, angleLineColor: "rgba(0,0,0,.2)", pointLabelFontSize: 14 });
        });
    }
}

function DisplayLifeTimeChart() {
    if ($(".GraphLifeTime").length > 0) {
        $(".GraphLifeTime").each(function() {
            var lifetimeGraph = $(this).get(0).getContext("2d");
            var dataBirth = $(this).attr("data-birth");
            var birthArray = dataBirth.split(",");
            var dataPlayed = $(this).attr("data-played");
            var playedArray = dataPlayed.split(",");
            var dataWatched = $(this).attr("data-watched");
            var watchedArray = dataWatched.split(",");
            var allLabels = [];
            var allPlayed = [];
            var allWatched = [];
            $.each(birthArray, function(key, value) {
                allLabels.push(value);
            });
            $.each(playedArray, function(key, value) {
                allPlayed.push(value);
            });
            $.each(watchedArray, function(key, value) {
                allWatched.push(value);
            });

            var data = {
                labels: allLabels,
                datasets: [{
                        label: "Played XP",
                        fillColor: "rgba(156, 39, 176, 0.1)",
                        strokeColor: "rgb(156, 39, 176)",
                        pointColor: "rgb(156, 39, 176)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(71,71,71,1)",
                        data: allPlayed
                    },
                    {
                        label: "Watched XP",
                        fillColor: "rgba(3, 169, 244, 0.1)",
                        strokeColor: "rgb(3, 169, 244)",
                        pointColor: "rgb(3, 169, 244)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(71,71,71,1)",
                        data: allWatched
                    }
                ]
            };
            $(this).attr('width', $(this).parent().width() - 25);
            $(this).attr('height', 380);
            var temp = new Chart(lifetimeGraph).Line(data, { animation: false, datasetStrokeWidth: 4, showScale: true, bezierCurve: true, pointDot: false, scaleShowGridLines: false, multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>" });

        });
    }
}

function ShowUserActivity(userid) {
    ShowLoader($("#activityInnerContainer"), 'big', "<br><br><br>");
    var windowWidth = $(window).width();
    $("#activity").css({ "display": "inline-block", "left": -windowWidth });
    $("#discover, #admin, #profiledetails, #settings, #notifications, #user, #landing").css({ "display": "none" });
    $("#discover, #admin, #profiledetails, #settings, #notifications, #user, #landing").velocity({ "left": windowWidth }, { duration: 200, queue: false, easing: 'easeOutQuad' });
    $("#activity").velocity({ "left": 0 }, { duration: 200, queue: false, easing: 'easeOutQuad' });
    if ($(window).width() > 599) {
        $("#navigation-header").css({ "display": "block" });
        $("#navigationContainer").css({ "-webkit-box-shadow": "0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow": "0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)" });
    }
    $.ajax({
        url: '../php/webService.php',
        data: { action: "ShowUserProfileActivity", userid: userid },
        type: 'post',
        success: function(output) {
            $("#activityInnerContainer").html(output);
            window.scrollTo(0, 0);
            Waves.displayEffect();
            AttachShowUserActivityEvents();
        },
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}

<<<<<<< HEAD
function AttachShowUserActivityEvents() {
    $(".user-discover-card").on("click", function(e) {
        e.stopPropagation();
        ShowUserProfile($(this).attr("data-id"));
    });
    $(".large-avatar, .user-avatar").on("click", function(e) {
        e.stopPropagation();
        ShowUserProfile($(this).attr("data-id"));
    });
    $(".feed-bookmark-card, .feed-activity-game-link, .feed-release-card").on("click", function(e) {
        e.stopPropagation();
        ShowGame($(this).attr("data-gbid"), $("#activity"));
    })
    $(".feed-card-image").on("click", function(e) {
        e.stopPropagation();
        ShowGame($(this).parent().attr("data-gbid"), $("#activity"));
    })
    AttachAgreesFromActivity();
    AttachSecondaryEvents();
    $(window).unbind("scroll");
    $(window).scroll(function() {
        if (isScrolledIntoView($("#feed-endless-loader"))) {
            if ($("#feed-endless-loader").html() == "")
                EndlessUserAcitivtyLoader($(".activity-top-level").attr("data-id"));
        }
    });
}

function EndlessUserAcitivtyLoader(userid) {
    ShowLoader($("#feed-endless-loader"), 'big', "<br><br><br>");
    $("#feed-endless-loader").append("<br><br><br>");
    var page = $("#feed-endless-loader").attr("data-page");
    var date = $("#feed-endless-loader").attr("data-date");
    var filter = $("#feed-endless-loader").attr("data-filter");
    $.ajax({
        url: '../php/webService.php',
        data: { action: "ShowUserProfileActivityEndless", userid: userid, page: page, date: date },
        type: 'post',
        success: function(output) {
            $("#feed-endless-loader").before(output);
            $("#feed-endless-loader").html("");
            $("#feed-endless-loader").attr("data-page", parseInt(page) + 45);
            var lastdate = $("#feed-endless-loader").parent().find(".feed-date-divider:last").attr("data-date");
            $("#feed-endless-loader").attr("data-date", lastdate);
            AttachShowUserActivityEvents(userid);
        },
=======
function AttachShowUserActivityEvents(){
		$(".fixed-close-modal-btn, .lean-overlay, .feed-avatar, .user-avatar").unbind();
		$(".fixed-close-modal-btn, .lean-overlay").on('click', function(){
			$("#profile").css({ "right": "-75%" }); 
			$(".lean-overlay").each(function(){ $(this).remove(); } );
			$(".feed-avatar, .user-avatar").on("click", function(e){
				e.stopPropagation();
				ShowUserProfile($(this).attr("data-id"));
			});
			setTimeout(function(){ $("#profile").css({"display":"none"}); $('body').removeClass("bodynoscroll").css({'top': $(window).scrollTop(SCROLL_POS) + 'px'}); }, 300);
		});
		AttachAgreesFromActivity();
		$("#profileInnerContainer").unbind("scroll");
		$("#profileInnerContainer").find(".feed-avatar-col").css({"opacity":"0"});
		$("#profileInnerContainer").find(".game-discover-card").css({"height":"200px"});
		$("#profileInnerContainer").find(".game-discover-card .card-content").each(function(){
			if($(this).parent().height() != 165)
				$(this).hide();
		});
		$("#profileInnerContainer").find(".watchBtn").hide();
		$("#profileInnerContainer").scroll(function(){
		if(isScrolledIntoView($("#profileInnerContainer").find("#feed-endless-loader"))){
			if($("#profileInnerContainer").find("#feed-endless-loader").html() == "")
				EndlessUserAcitivtyLoader($("#profileInnerContainer").find(".activity-top-level").attr("data-id"));
		}
		}); 
}

function EndlessUserAcitivtyLoader(userid){
	ShowLoader($("#profileInnerContainer").find("#feed-endless-loader"), 'big', "<br><br><br>");
	$("#profileInnerContainer").find("#feed-endless-loader").append("<br><br><br>");
	var page = $("#profileInnerContainer").find("#feed-endless-loader").attr("data-page");
	var date = $("#profileInnerContainer").find("#feed-endless-loader").attr("data-date");
	var filter = $("#profileInnerContainer").find("#feed-endless-loader").attr("data-filter");
	$.ajax({ url: '../php/webService.php',
     data: {action: "ShowUserProfileActivityEndless", userid: userid, page: page, date: date },
     type: 'post',
     success: function(output) {
		$("#profileInnerContainer").find("#feed-endless-loader").before(output);
		$("#profileInnerContainer").find("#feed-endless-loader").html("");
		$("#profileInnerContainer").find("#feed-endless-loader").attr("data-page", parseInt(page) + 45);
		var lastdate = $("#profileInnerContainer").find("#feed-endless-loader").parent().find(".feed-date-divider:last").attr("data-date");
		$("#profileInnerContainer").find("#feed-endless-loader").attr("data-date", lastdate);
		AttachShowUserActivityEvents(userid);
     },
>>>>>>> Akuma
        error: function(x, t, m) {
            if (t === "timeout") {
                ToastError("Server Timeout");
            } else {
                ToastError(t);
            }
        },
        timeout: 45000
    });
}