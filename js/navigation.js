
function InitializeNavigation(){
	$('.mainNav').tabs();
	$("#slide-out li").on('click', function(e){ SideNavigation($(this)); });
	UserAccountNav();
	AttachTabLoadingEvents();
	CheckForNotifications();
	CheckForUpdates();
}

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
  			var id = $(".userContainer").attr("data-id");
			ShowUserProfile(id, true);
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
		HideFocus();
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
     			$(".userNotificiations").html("<i class='mdi-social-notifications'></i><div class='notifications-new-badge'>NEW</div>");
     		}else{
     			$(".userNotificiations").html("<i class='mdi-social-notifications-none'></i>");
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
