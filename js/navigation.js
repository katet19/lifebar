
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

	window.onpopstate = function(event) {
		if (!window.innerDocClick && window.location.hash != '' && GLOBAL_HASH_REDIRECT == "") {
			var pagedata = window.location.hash.split('/');
			NavigateToPage(pagedata, true);
		}
	}
}

function AttachSideNav(){
	if($(window).width() < 992){
		$("#nav-slide-out").removeClass("nav-display-slide-out");
	}
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
	if($(window).width() < 992){
		$(".lean-overlay").each(function(){ $(this).remove(); } );
		$("body").append("<div class='lean-overlay' id='materialize-lean-overlay-1' style='z-index: 1000; display: block; opacity: 0.5;'></div>");
		$(".lean-overlay").unbind();
		$(".lean-overlay").on('click', function(){
			HideFocus();
			HideSideNav();
			$(".lean-overlay").each(function(){ $(this).remove(); } );
		});
	}
}

function HideSideNav(){
	$("#nav-slide-out").removeClass("nav-display-slide-out");
	$(".outerContainer").removeClass("outerContainer-slide-out");
	$(".navigation-menu").removeClass("navigation-menu-slide-out");
	$(".navigation-menu-logo").removeClass("navigation-menu-logo-slide-out");
	$(".navigation-lifebar").removeClass("navigation-lifebar-slide-out");
	HideFocus();
	$(".lean-overlay").each(function(){ $(this).remove(); } );
}

function UpdateBrowserHash(hash){
	if(GLOBAL_HASH_REDIRECT != "URL"){
		GLOBAL_HASH_REDIRECT = "NO";
		location.hash = hash;
	}
	GLOBAL_HASH_REDIRECT = "";
}

function NavigateToPage(page, fromURL = false){
	if($("#loginButton").length > 0){
		GLOBAL_HASH_REDIRECT = "URL";
		$('body').css({'overflow-y':'scroll'});
		ShowLanding();
		GLOBAL_HASH_REDIRECT = "";
	}else{
		if(fromURL){
			GLOBAL_HASH_REDIRECT = "URL";
			$(".nav-slide-out-selected-page").removeClass("nav-slide-out-selected-page");
			if(page[0] == "#collection"){
				DisplayCollectionDetails(page[1], "UserCollection", page[2]);
			}else if(page[0] == "#collections"){
				$('body').css({'overflow-y':'scroll'});
				$("#nav-collections").addClass("nav-slide-out-selected-page");
				DisplayUserCollection($(".userContainer").attr("data-id"));
			}else if(page[0] == "#game" && page[1] > 0){
				if(page[3] == "User")
					page[3] = "User/"+page[4]+"/"+page[5];

				$("#nav-discover").addClass("nav-slide-out-selected-page");
				ShowDiscoverHome();
				ShowGame(page[1], '', true, false, page[3]);
			}else if(page[0] == "#profile" && page[1] > 0){
				$('body').css({'overflow-y':'scroll'});
				if(page[1] == $(".userContainer").attr("data-id"))
					$("#nav-profile").addClass("nav-slide-out-selected-page");
				
				$("#nav-discover").addClass("nav-slide-out-selected-page");
				ShowDiscoverHome();
				ShowUserProfile(page[1], false);
			}else if(page[0] == "#search" && page[1] != ''){
				$('body').css({'overflow-y':'scroll'});
				Search(page[1]);
			}else if(page[0] == "#discover" || page == "#daily"){
				$('body').css({'overflow-y':'scroll'});
				$("#nav-discover").addClass("nav-slide-out-selected-page");
				ShowDiscoverHome();
			}else if(page[0] == "#activity"){
				$('body').css({'overflow-y':'scroll'});
				$("#nav-activity").addClass("nav-slide-out-selected-page");
				ShowActivityHome();
			}else if(page[0] == "#settings"){
				$('body').css({'overflow-y':'scroll'});
				$("#nav-settings").addClass("nav-slide-out-selected-page");
				ShowUserSettings();
			}else if(page[0] == "#notifications"){
				$('body').css({'overflow-y':'scroll'});
				ShowNotificationsHome();
			}else if(page[0] == "#admin"){
				$('body').css({'overflow-y':'scroll'});
				$("#nav-admin").addClass("nav-slide-out-selected-page");
				ShowAdminHome();
			}else if(page[0] == "#landing"){
				$('body').css({'overflow-y':'scroll'});
				ShowLanding();
			}else if(page[0] == "#profile"){
				$("#nav-profile").addClass("nav-slide-out-selected-page");
				ShowUserProfile($(".userContainer").attr("data-id"), true);
			}else if(page[0] == "#ranking"){
				$("#nav-ranking").addClass("nav-slide-out-selected-page");
				ShowRanking();
			}else if(page[0] == "#mylibrary"){
				$("#nav-mylibrary").addClass("nav-slide-out-selected-page");
				ShowMyLibrary();
			}else{
				$('body').css({'overflow-y':'scroll'});
				ShowDiscoverHome();
			}
			GLOBAL_HASH_REDIRECT = "";
		}else{
			var windowWidth = $(window).width();
			if(windowWidth <= 991){
				HideSideNav();
			}
			GLOBAL_HASH_REDIRECT = "NO";
			$('body').css({'overflow-y':'scroll'});
			if(page == "#discover" || page == "#daily")
				ShowDiscoverHome();
			else if(page == "#activity")
				ShowActivityHome();
			else if(page == "#notifications")
				ShowNotificationsHome();
			else if(page == "#admin")
				ShowAdminHome();
			else if(page == "#settings")
				ShowUserSettings();
			else if(page == "#landing")
				ShowLanding();
			else if(page == "#profile")
				ShowUserProfile($(".userContainer").attr("data-id"), true);
			else if(page == "#ranking")
				ShowRanking();
			else if(page == "#mylibrary")
				ShowMyLibrary();
			else if(page == "#collections")
				DisplayUserCollection($(".userContainer").attr("data-id"));
			else if(page != "#logout" && page != "#feedback")
				ShowDiscoverHome();
			GLOBAL_HASH_REDIRECT = "";
		}
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
			$.ajax({ url: '../php/webService.php',
			     data: {action: 'DisplayFeedback', },
			     type: 'post',
			     success: function(output) {
					ShowPopUp(output);
					$(".myfeedback-submit").on("click", function(){
						var feedback = $("#myfeedback").val();
						Toast("Thanks for the feedback! We will respond as soon as we can.");
						$("#universalPopUp").closeModal();
						HideFocus();
						$.ajax({ url: '../php/webService.php',
								data: {action: 'SubmitFeedback', feedback: feedback },
								type: 'post',
								success: function(output) {

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
	$(".profileButton, .userAvatar, .userNameTitle").on('click', function(e){
		var id = $(".userContainer").attr("data-id");
		e.stopPropagation();
		HideFocus();
		$("#userAccountNav").hide(250);
		CloseSideNavigation();
 		ManuallyNavigateToTab("#profile");
	});
	$(".my-lifebar-image").on("click", function(){
		ShowUserProfile($(this).attr("data-id"));
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
     			$("#nav-notifications").html("<a href='#notifications'><i class='material-icons'>notifications</i> Notifications</a><div class='notifications-new-badge'>NEW</div>");
     		}else{
     			$("#nav-notifications").html("<a href='#notifications'><i class='material-icons'>notifications_none</i> Notifications</a>");
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
