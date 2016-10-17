
function InitializeNavigation(){
	AttachSideNav();
	var pagedata = location.hash.split('/');
	NavigateToPage(pagedata, true);
	AttachBrowserStateHandling();
	/*
		OLD
	*/
	UserAccountNav();
	AttachTabLoadingEvents();
	CheckForNotifications();
	CheckForUpdates();
}

function AttachBrowserStateHandling(){
	document.onmouseover = function() {
		//User's mouse is inside the page.
		window.innerDocClick = true;
	}

	document.onmouseleave = function() {
		//User's mouse has left the page.
		window.innerDocClick = false;
	}

	window.onhashchange = function() {
		if (!window.innerDocClick) {
			history.back();
        	window.location = document.referrer;
			var pagedata = window.location.split('/');
			NavigateToPage(pagedata, true);
		}
	}
}

function AttachSideNav(){
	$(".nav-icon").on("click", function(){
		if($(".nav-display-slide-out").length == 0)
			DisplaySideNav();
		else
			HideSideNav();
	});
	$("#nav-slide-out li").on("click", function(){
		$(".nav-slide-out-selected-page").removeClass("nav-slide-out-selected-page");
		$(this).addClass("nav-slide-out-selected-page");
		NavigateToPage($(this).find("a").attr("href"));
	});
}

function DisplaySideNav(){
	$("#nav-slide-out").addClass("nav-display-slide-out");
	$(".outerContainer").addClass("outerContainer-slide-out");
	$(".navigation-menu").addClass("navigation-menu-slide-out");
	$(".navigation-menu-logo").addClass("navigation-menu-logo-slide-out");
	$(".navigation-lifebar").addClass("navigation-lifebar-slide-out");
}

function HideSideNav(){
	$("#nav-slide-out").removeClass("nav-display-slide-out");
	$(".outerContainer").removeClass("outerContainer-slide-out");
	$(".navigation-menu").removeClass("navigation-menu-slide-out");
	$(".navigation-menu-logo").removeClass("navigation-menu-logo-slide-out");
	$(".navigation-lifebar").removeClass("navigation-lifebar-slide-out");
}

function NavigateToPage(page, fromURL = false){
	if(fromURL){
		if(page[0] == "#collection"){
			DisplayCollectionDetails(page[1], "UserCollection", page[2]);
		}else if(page[0] == "#game" && page[1] > 0){
		  if(page[3] == "User")
		    page[3] = "User/"+page[4]+"/"+page[5];
			ShowGame(page[1], '', true, false, page[3]);
		}else if(page[0] == "#profile" && page[1] > 0){
			ShowUserProfile(page[1], false);
		}else if(page[0] == "#search" && page[1] != ''){
			Search(page[1]);
		}else if(page[0] == "#discover" || page == "#daily")
			ShowDiscoverHome();
		else if(page[0] == "#activity")
			ShowActivityHome();
		else if(page[0] == "#notifications")
			ShowNotificationsHome();
		else if(page[0][0] == "#admin")
			ShowAdminHome();
		else if(page[0] == "#landing")
			ShowLanding();
		else if(page[0] == "#profile")
			ShowUserProfile($(".userContainer").attr("data-id"), true);
		else
			ShowDiscoverHome();
	}else{
		if(page == "#discover" || page == "#daily")
			ShowDiscoverHome();
		else if(page == "#activity")
			ShowActivityHome();
		else if(page == "#notifications")
			ShowNotificationsHome();
		else if(page == "#admin")
			ShowAdminHome();
		else if(page == "#landing")
			ShowLanding();
		else if(page == "#profile")
			ShowUserProfile($(".userContainer").attr("data-id"), true);
		else
			ShowDiscoverHome();
	}
}

/*

OLD STUFF

*/

function ManuallyNavigateToTab(tab){
	$("#navigation-header .row .col .tabs .tab a").each(function(){
		if($(this).attr("href") == tab){
			$(this).trigger("click");
		}
	});
}

function AttachTabLoadingEvents(){
	  $(".mainNav .tab a").on('click', function(){
		  if($(this).attr('href') == "#discover"){
		  	if(GLOBAL_TAB_REDIRECT == ""){
				ShowDiscoverHome();
		  	}
		  }else if($(this).attr('href') == "#notifications"){
	  		ShowNotificationsHome();
		  }else if($(this).attr('href') == "#activity"){
		  	ShowActivityHome();
		  }else if($(this).attr('href') == "#profile"){
			if($("#loginButton").length > 0){
				$('#signupModal').openModal(); $("#username").focus();
			}else{
				var id = $(".userContainer").attr("data-id");
				ShowUserProfile(id, true);
			}
		  }
	  });
	  $(".userNotificiations").on("click", function(){
	  	ManuallyNavigateToTab("#notifications");
	  });
	  $(".userPTalk").on("click", function(){
	  	window.open("http://tidbits.io/");
	  });
	  $(".userPTalkHelp, .supportButton").on("click", function(){
	  	var elem = $(".userContainer");
	  	var name = elem.attr("data-username");
	  	var email = elem.attr("data-email");
	  	//$(".freshwidget-button").trigger("click");
	  	ShowFreshDesk(name, email);
	  	//window.open("https://gitreports.com/issue/Lifebario/support?name="+name+"&email=Please%20do%20not%20include%20email");
	  });
  	  $(".supportForumButton").on("click", function(){
	  	window.open("https://lifebar.freshdesk.com");
	  });
	  $(".supportBlogButton").on("click", function(){
	  	window.open("https://medium.com/lifebar-io");
	  });
	  $(".logoContainer").on("click", function(){
	  	if($("#userAccountNav").length > 0){
			ManuallyNavigateToTab("#profile");
	  	}else{
  			ShowLanding();
	  	}
	  });
}

function ShowFreshDesk(name, email){
	window.open("https://lifebar.freshdesk.com/widgets/feedback_widget/new?&widgetType=embedded&screenshot=no&helpdesk_ticket[requester]="+email+"&formTitle=Lifebar+Help+%26+Support&helpdesk_ticket[name]="+name);
	//ShowPopUp("<iframe title='Feedback Form' class='freshwidget-embedded-form' id='freshwidget-embedded-form' src='https://lifebar.freshdesk.com/widgets/feedback_widget/new?&widgetType=embedded&screenshot=no' scrolling='no' height='500px' width='100%'' frameborder='0' ></iframe>");
}

/*
*
* ExtraSettingsNavigation
*
*/
function UserAccountNav(){
	$('.userAccountNavButton').on('click', function(e){ e.stopPropagation(); $("#userAccountNav").show(250);});
	$('html').click(function(){
		if($("#userAccountNav").is(":visible"))
			$("#userAccountNav").hide(250);
	});
	$(".settingsButton").on('click', function(e){
		e.stopPropagation();
		$("#userAccountNav").hide(250);
		CloseSideNavigation();
		ShowUserSettings();
	});
	$(".adminButton").on('click', function(e){
		e.stopPropagation();
		HideFocus();
		$("#userAccountNav").hide(250);
		CloseSideNavigation();
		ShowAdminHome();
	});
	$(".profileButton, .userAvatar, .userNameTitle").on('click', function(e){
		var id = $(".userContainer").attr("data-id");
		e.stopPropagation();
		HideFocus();
		$("#userAccountNav").hide(250);
		CloseSideNavigation();
 		ManuallyNavigateToTab("#profile");
	});
}

/*
*
* SideContent
*/

function HideFocus(){
	$("#lean-overlay").velocity({"opacity":0}, { complete: function(a){ 
													$("#lean-overlay").hide(); 
													$("#lean-overlay").remove(); 
													$(".my-lean-overlay").each(function(){
														$(this).hide(); 
														$(this).remove(); 
													});
													}
												}
	);
}

/*
*
* Universal Pop Up
*
*/
function ShowPopUp(content){
	$("#universalPopUp").css({"max-width":"55%"});
	$("#universalPopUp").html(content);
	$("#universalPopUp").openModal();
  	$(".closeDetailsModal").unbind();
  	$(".closeDetailsModal").on('click', function(){
  		$("#universalPopUp").closeModal();
  		HideFocus();
  	});
}

/*
*
* Universal Bottomsheet
*
*/
function ShowBottomSheet(content){
	$("#universalBottomSheet").html(content);
	$("#universalBottomSheet").openModal();
  	$(".closeDetailsModal").unbind();
  	$(".closeDetailsModal").on('click', function(){
  		$("#universalBottomSheet").closeModal();
  		HideFocus();
  	});
}


/*
*
* BattleProgess
*
*/
function ShowBattleProgress(content){
	$("#BattleProgess").html(content);
	$("#BattleProgess").openModal();
  	$(".closeDetailsModal").unbind();
  	$(".closeDetailsModal").on('click', function(){
  		$("#BattleProgess").closeModal();
  		HideFocus();
  	});
  	AttachBPEvents();
}

/*
*
* ProfileDetails
*
*/
function ShowProfileDetails(content){
	$("#BattleProgess").html(content);
	$("#BattleProgess").openModal();
  	$(".closeDetailsModal").unbind();
  	$(".closeDetailsModal").on('click', function(){
  		$("#BattleProgess").closeModal();
  		HideFocus();
  	});
}


/*
*
* Social Sharing Modal
*
*/
function ShowShareModal(type, otherid){
	var loading = "<div id='share-container'></div>";
	ShowPopUp(loading);
	ShowLoader($("#share-container"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: 'DisplayShareModal', type: type, otherid: otherid },
     type: 'post',
     success: function(output) {
		$("#share-container").html(output);
		$(".share-sub-link").on("click", function(){
			$("#share-link").select();
    		document.execCommand("copy");	
    		GAEvent('UserShare', 'CopyLink');
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


/*
*
* SideNavigation
*
*/
function SideNavigation(navItem){
	var container = navItem.find("a").attr("href");
	$("#navigation-header .row .col .tabs .tab a").each(function(){
		if($(this).attr("href") == container){
			$(this).trigger("click");
		}
	});
	
	CloseSideNavigation();
}


function CloseSideNavigation(){
  $('#sidenav-overlay').animate({opacity: 0}, {duration: 300, queue: false, easing: 'easeOutQuad',
    complete: function() {
      $(this).remove();
    } });
  var menuWidth = $("#slide-out").width();
  $("#slide-out").velocity({left: -1 * (menuWidth + 10)}, {duration: 300, queue: false, easing: 'easeOutQuad'});
  enable_scroll();
}

function enable_scroll() {
  if (window.removeEventListener) {
    window.removeEventListener('DOMMouseScroll', wheel, false);
  }
  window.onmousewheel = document.onmousewheel = document.onkeydown = null;
}

function wheel(e) {
  preventDefault(e);
}

function CheckForNotifications(){
	$.ajax({ url: '../php/webService.php',
     data: {action: "CheckForNotifications" },
     type: 'post',
     success: function(output) {
     		if($.trim(output) == "1"){
     			$(".userNotificiations").html("<i class='material-icons user-notification-icon'>notifications</i><div class='notifications-new-badge'>NEW</div>");
     		}else{
     			$(".userNotificiations").html("<i class='material-icons user-notification-icon'>notifications_none</i>");
     		}
  			setTimeout(CheckForNotifications,300000);
     },
        error: function(x, t, m) {
	        if(t==="timeout") {
	            //ToastError("Server Timeout");
	        } else {
	            //ToastError(t);
	        }
    	},
    	timeout:45000
	});
}

function CheckForUpdates(){
	var version = GLOBAL_VERSION;
	$.ajax({ url: '../php/webService.php',
     data: {action: "CheckVersion", version: version },
     type: 'post',
     success: function(output) {
     		if($.trim(output) == "UPDATE"){
     			ToastUpdate();
     		}else{
  				setTimeout(CheckForUpdates,3660000);
     		}
     },
        error: function(x, t, m) {
	        if(t==="timeout") {
	            //ToastError("Server Timeout");
	        } else {
	            //ToastError(t);
	        }
    	},
    	timeout:45000
	});
}
