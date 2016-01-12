function InitializeNavigation(){
	$('.mainNav').tabs();
	$("#slide-out li").on('click', function(e){ SideNavigation($(this)); });
	UserAccountNav();
	AttachTabLoadingEvents();
	CheckForNotifications();
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
	  	window.open("http://talk.polygonalweave.com");
	  });
	  $(".userPTalkHelp, .supportButton").on("click", function(){
	  	GAEvent('Support', 'Bug Reporting');
	  	window.open("https://gitreports.com/issue/Lifebario/support ");
	  });
  	  $(".supportForumButton").on("click", function(){
  	  	GAEvent('Support', 'Forum');
	  	window.open("https://github.com/Lifebar/support");
	  });
	  $(".logoContainer").on("click", function(){
	  	if($("#userAccountNav").length > 0){
			ManuallyNavigateToTab("#profile");
	  	}else{
  			ShowLanding();
	  	}
	  });
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
  			setTimeout(CheckForNotifications,60000);
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
