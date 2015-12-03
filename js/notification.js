function ShowNotificationsHome(){
	ShowNotificationMainContent();
}

function ShowNotificationMainContent(){
	$("#notifications").css({"display":"block"});
  	ShowLoader($("#notificationsInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayNotificationHome" },
     type: 'post',
     success: function(output) {
     			$("#notificationsInnerContainer").html(output);
     			 $(".indicator").css({"display":"none"});
     			AttachNotificationEvents();
      			Waves.displayEffect();
     },
        error: function(x, t, m) {
	        if(t==="timeout") {
	            ToastError("Server Timeout");
	        } else {
	            ToastError(t);
	        }
    	},
    	timeout:30000
	});
}

function AttachNotificationEvents(){
	$(".notification-dismiss").on('click', function(){
		var notificationid = $(this).attr("data-id");
		DismissNotification(notificationid, $(this).parent().parent().parent().parent());
	});
	$(".notification-viewgame").on('click', function(){
		var gameid = $(this).attr("data-id");
		ShowGame($(this).attr("data-id"), $("#notifications"), true);
	});
	$(".notification-card-icon-image").on('click', function(e){
		$(this).parent().parent().find(".notification-viewgame").trigger('click');	
	});
	$('.notification-header-nav-btn').on('click', function(e){ e.stopPropagation(); $("#notification-header-nav").show(250);});
	$('html').click(function(){
		if($("#notification-header-nav").is(":visible"))
			$("#notification-header-nav").hide(250);
	});
	$("#notification-header-nav li a").on('click', function(e){
		e.stopPropagation();
		$(".notificiation-filter-selected").removeClass("notificiation-filter-selected");
		var selected = $(this).attr("class");
		$(".notification-category-selected").removeClass("notification-category-selected");
		$(".notification-category-selector").each(function(){
			var category = $(this).attr("id");
			if(category == selected)
				$(this).addClass("notification-category-selected");
		});
		var icon = $(this).attr("data-icon");
		var iconloc = $(".notification-header-icon .notification-header-icon-picker");
		iconloc.removeClass();
		iconloc.addClass(icon);
		iconloc.addClass("notification-header-icon-picker");
		$(this).addClass("notificiation-filter-selected");
		$(".notification-card").hide();
		if(selected == "notification-all"){
			$(".notification-card").show();
		}else
			$("."+selected).show();
		$(".notification-header-nav-btn span").html($(this).html());
		$("#notification-header-nav").hide(250);
	});
	$(".notification-category-selector").on('click', function(){
		$(".notification-category-selected").removeClass("notification-category-selected");
		$(this).addClass("notification-category-selected");
		var selected = $(this).attr("id");
		$("."+selected).trigger("click");
	});
}

function DismissNotification(notificationid, notification){
	notification.removeClass("notification-card");
	var category = notification.attr("class");
	var alltotal = parseInt($("#notification-all").parent().find(".notification-category-total").html());
	alltotal = alltotal - 1;
	$("#notification-all").parent().find(".notification-category-total").html(alltotal);
	var cattotal = parseInt($("#"+category).parent().find(".notification-category-total").html());
	cattotal = cattotal - 1;
	$("#"+category).parent().find(".notification-category-total").html(cattotal);
	notification.remove(); 
	$.ajax({ url: '../php/webService.php',
     data: {action: "DismissNotification", notificationid: notificationid },
     type: 'post',
     success: function(output) {
     			Toast("Dismissed notification");
     			
     },
        error: function(x, t, m) {
	        if(t==="timeout") {
	            ToastError("Server Timeout");
	        } else {
	            ToastError(t);
	        }
    	},
    	timeout:30000
	});
	
}
