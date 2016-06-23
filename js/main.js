$(function() {
	InitializeNavigation();
	InitializeLogin();
	InitializeDiscover();
	ResizeEvents();
	IsTouchDevice();
	$(window).resize(function() {
		ResizeEvents();
	});

	//$(window).bind('hashchange', function(){
	//	ManageHashEvents();
	//});
});


 function getParameterByName(name, url) {
      if (!url) url = window.location.href;
      name = name.replace(/[\[\]]/g, "\\$&");
      var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
          results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
      return decodeURIComponent(results[2].replace(/\+/g, " "));
  }

function ManageHashEvents(){
	if(GLOBAL_HASH_REDIRECT == ""){
		var data = location.hash.split('/');
		if(location.hash == "#discover"){
			ShowDiscoverHome();
		}else if(location.hash == "#notifications"){
			ShowNotificationsHome();
		}else if(location.hash == "#admin"){
			ShowAdminHome();
		}else if(location.hash == "#activity"){
			ShowActivityHome();
		}else if(data[0] == "#game" && data[1] > 0){
			ShowGame(data[1], '', true, true);
		}else if(data[0] == "#profile" && data[1] > 0){
			ShowUserProfile(data[1], false, true);
		}else if(data[0] == "#landing"){
			ShowLanding();
		}else if(location.hash == "#!"){
			//do nothing
		}else{
			if($("#loginButton").length > 0){
				ShowLanding();
			}else{
				ShowActivityHome();
			}
		}
	}else{
		GLOBAL_HASH_REDIRECT = "";
	}
}

function NiceScroll(element, offset){
	if($(window).width() > 599){
		$('html, body').animate({
	        scrollTop: element.offset().top - offset
	    }, 500);
	}else{
		$('html, body').animate({
	        scrollTop: element.offset().top
	    }, 500);
	}
}

function ResizeEvents(){
		$('.mobileContainer').unbind();
		if($( window ).width() < 993)
			$('.mobileContainer').sideNav();

		if($(window).width() < 600){
		
			
		}

		if($(window).width() >= 993 && !$("#sideContainer").is(":visible")){
			$("#sideContainer").css({"display":"inline-block"});
			$("#sideContainer").velocity({"right":"0"}, {duration: 300, queue: false, easing: 'easeOutQuad', delay: 80});
		}
			
		//window.scrollTo(0, 0);
}

function ShowLoader(element,size,vertical){
	if(size == "")
		size = "big";
		element.html(vertical+"<div class='preloader-wrapper "+size+" active'><div class='spinner-layer spinner-blue'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div><div class='spinner-layer spinner-red'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div><div class='spinner-layer spinner-yellow'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div><div class='spinner-layer spinner-green'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
}

function ShowSideLoader(){
	$("#sideContainer").html("<div class='progress progress-side-bar'><div class='indeterminate'></div></div>");
}

function Toast(msg){
	toast(msg, 5000);
}

function ToastProgress(msg){
	toast(msg, 5000);
}


function ToastError(msg){
	toast(msg, 5000);
}

function ToastUpdate(){
	var msg = "New updates available! <span onclick='location.reload(true);' style='cursor:pointer;color:#FF8E00;font-weight: bold;padding: 0 10px 0 20px;'>REFRESH</span>";
	toast(msg, 3540000);
}

function ToastRight(msg){
		toastr.options = {
		  "debug": false,
		  "positionClass": "toast-bottom-right",
		  "onclick": null,
		  "fadeIn": 300,
		  "fadeOut": 1000,
		  "timeOut": 5000,
		  "extendedTimeOut": 1000
		};
		toastr.success(msg);
}

function ToastRightError(msg){
		toastr.options = {
		  "debug": false,
		  "positionClass": "toast-bottom-right",
		  "onclick": null,
		  "fadeIn": 300,
		  "fadeOut": 1000,
		  "timeOut": 120000,
		  "extendedTimeOut": 10000
		};
		toastr.success(msg);
}

function SideContentPush(content){
	 SIDE_CONTENT.push(content);
}

function SideContentPop(){
	if(SIDE_CONTENT.length == 1)
		return SIDE_CONTENT[0];
	else
	 	return SIDE_CONTENT.pop();
}

function SideContentEventPush(method){
	 SIDE_CONTENT_EVENTS.push(method);
}

function SideContentEventPop(){
	if(SIDE_CONTENT_EVENTS.length == 1)
		return SIDE_CONTENT_EVENTS[0];
	else
		return SIDE_CONTENT_EVENTS.pop();
}

function ClearSideContent(){
	SIDE_CONTENT_EVENT = new Array();
	SIDE_CONTENT = new Array();
}

function AttachFloatingIconEvent(icon) {
	if(icon == null)
		icon = "mdi-hardware-gamepad";
	
    // jQuery reverse
    jQuery.fn.reverse = [].reverse;
    $('.fixed-action-btn').unbind();
    $('.fixed-action-btn').each(function (i) {
      var $this = $(this);
      $this.find('ul a.btn-floating').velocity(
        { scaleY: ".4", scaleX: ".4", translateY: "40px"},
        { duration: 0 });
	 
      var timer;
      $this.hover(
        function() {
	      	$this.find("ul").css({"display":"block"});
	      	$this.addClass("activeFAB");
        	$this.find("a .large").velocity({ rotateZ: "360deg"}, 80, function(){ $this.find("a .large").removeClass("mdi-content-add"); $this.find("a .large").addClass(icon); });
        	
          var time = 0;
          $this.find('ul a.btn-floating').reverse().each(function () {
            $(this).velocity(
              { opacity: "1", scaleX: "1", scaleY: "1", translateY: "0"},
              { duration: 80, delay: time });
            time += 40;
          });
        }, function() {
          var time = 0;
          $this.find('ul a.btn-floating').velocity("stop", true);
          $this.find('ul a.btn-floating').velocity(
            { opacity: "0", scaleX: ".4", scaleY: ".4", translateY: "40px"},
            { duration: 80 });
        	$this.find("a .large").velocity({ rotateZ: "-360deg"}, 80, function(){ $this.find("a .large").removeClass(icon); $this.find("a .large").addClass("mdi-content-add"); });
        	$this.find("ul").css({"display":"none"});
        	$this.removeClass("activeFAB");
        }
      );
    });
    /*
    $('.fixed-action-btn').each(function (i) {
      var $this = $(this);
     
      $this.find('ul a.btn-floating').velocity(
        { scaleY: ".4", scaleX: ".4", translateY: "40px"},
        { duration: 0 });
	 
      var timer;
      $this.on('click', function(e) {
      	  $this.addClass("activeFAB");
      	  $this.find("ul").css({"display":"block"});
          $this.find("a .large").velocity({ rotateZ: "360deg"}, 80, function(){ $this.find("a .large").removeClass("mdi-content-add"); $this.find("a .large").addClass(icon); });
          e.stopPropagation();
          var time = 0;
          $this.find('ul a.btn-floating').reverse().each(function () {
            $(this).velocity(
              { opacity: "1", scaleX: "1", scaleY: "1", translateY: "0"},
              { duration: 80, delay: time });
            time += 40;
          });
          
          $("html").on('click', function() {
	          var time = 0;
	          $this.find('ul a.btn-floating').velocity("stop", true);
	          $this.find('ul a.btn-floating').velocity(
	            { opacity: "0", scaleX: ".4", scaleY: ".4", translateY: "40px"},
	            { duration: 80 });
	        	$this.find("a .large").velocity({ rotateZ: "-360deg"}, 80, function(){ $this.find("a .large").removeClass(icon); $this.find("a .large").addClass("mdi-content-add"); });
	          $this.find("ul").css({"display":"none"});
	          $this.removeClass("activeFAB");
          	}
	      );
          
        });
    });
    */
}

function isScrolledIntoView(elem)
{
	if(elem.offset() != undefined){
	    var docViewTop = $("#applicationContainer").scrollTop();
	    var docViewBottom = docViewTop + $( window ).height();
	    var elemTop = $(elem).offset().top;
	    var elemBottom = elemTop + $(elem).height();
    	return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
	}
}

function isLandingScrolledIntoView(elem)
{
    var $elem = $(elem);
    var $window = $(window);

    var docViewTop = $window.scrollTop();
    var docViewBottom = docViewTop + $window.height();

    var elemTop = $elem.offset().top;
    var elemBottom = elemTop + $elem.height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

function IsTouchDevice() {  
  try {  
    document.createEvent("TouchEvent");  
    IS_TOUCH_DEVICE =  true;  
  } catch (e) {  
    IS_TOUCH_DEVICE = false;  
  }  
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function GAEvent(category, action){
	ga('send', {
	  hitType: 'event',
	  eventCategory: category,
	  eventAction: action,
	  eventLabel: 'Alpha'
	});
}

function GAPage(title, page){
	ga('send', {
	  hitType: 'pageview',
	  page: page,
      title: title
	});
}

//Globals
var GLOBAL_VERSION = 100000;
var GLOBAL_TAB_REDIRECT = "";
var GLOBAL_HASH_REDIRECT = "";
var SIDE_CONTENT_EVENTS = new Array();
var SIDE_CONTENT = new Array();
var IS_TOUCH_DEVICE = false;
var SCROLL_POS = 0;
