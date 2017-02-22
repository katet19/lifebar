function ShowUserSettings(){
	$(".lean-overlay").each(function(){ $(this).remove(); } );
	$("#gamemini.outerContainer").css({"display":"inline-block", "right": "-40%"});
	SCROLL_POS = $(window).scrollTop();
	$('body').css({'top': -($('body').scrollTop()) + 'px'}).addClass("bodynoscroll");
	$("#gamemini.outerContainer").css({ "right": "0" });
	ShowLoader($("#gameminiInnerContainer"), 'big', "<br><br><br>");
	$("body").append("<div class='lean-overlay' id='materialize-lean-overlay-1' style='z-index: 1000; display: block; opacity: 0.5;'></div>");

	$.ajax({ url: '../php/webService.php',
         data: {action: "UserSettings" },
         type: 'post',
         success: function(output) {
			$("#gameminiInnerContainer").html(output);
			$('.collapsible').collapsible();
			$('select').material_select();
			$(".fixed-save-close-modal-btn, .lean-overlay, .delete-xp").unbind();
			$(".lean-overlay").on('click', function(){
				var windowWidth = $(window).width();
				HideFocus();
				$("#gamemini").css({ "right": "-40%" }); 
				$(".lean-overlay").each(function(){ $(this).remove(); } );
				setTimeout(function(){ $("#gamemini").css({"display":"none"}); $('body').removeClass("bodynoscroll").css({'top': $(window).scrollTop(SCROLL_POS) + 'px'}); }, 300);
			});
			$(".fixed-save-close-modal-btn").on('click', function(){
				UserSettingsValidation();
				var windowWidth = $(window).width();
				HideFocus();
				$("#gamemini").css({ "right": "-40%" }); 
				$(".lean-overlay").each(function(){ $(this).remove(); } );
				setTimeout(function(){ $("#gamemini").css({"display":"none"}); $('body').removeClass("bodynoscroll").css({'top': $(window).scrollTop(SCROLL_POS) + 'px'}); }, 300);
			});

            AttachUserSettingEvents();
            GAPage('Settings', '/settings');
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

function AttachUserSettingEvents(){
    $('input[type=radio][name=avatargroup]').change(function() {
    	UpdateAvatarPreview();
    });
    $(".apply-promo-code").on("click",function(){
    	var code = $("#settings_promo").val();
		$.ajax({ url: '../php/webService.php',
	         data: {action: "PromoCode", promo: code },
	         type: 'post',
	         success: function(output) {
				$(".settings-promo-msg").html(output);
				RefreshBadgeMgmt();
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
    AttachManageBadgeEvents($("#userSettings").attr("data-id"));
}

function UpdateAvatarPreview(){
	var image = $(".avatar-preview").find(".lifebar-avatar-min");
	var imagepicked = $("input[type=radio][name=avatargroup]:checked").attr("id");
	if(imagepicked == "gravatar"){
		var newimage = $("input[type=radio][name=avatargroup]:checked").attr("data-image");
		image.css({"background":"url("+newimage+") 25% 50%","background-size":"cover"});
	}else if(imagepicked == "weburlradio"){
		var newimage = $("#weburl").val();
		image.css({"background":"url("+newimage+") 25% 50%","background-size":"cover"});
	}else if(imagepicked == "uploaded"){
		var newimage = "http://lifebar.io/Images/Avatars/"+$("#userSettings").attr("data-id")+".jpg";
		image.css({"background":"url("+newimage+") 25% 50%","background-size":"cover"});
	}
}

function UpdateAvatarBadge(badge){
	var image = $(".avatar-preview").find(".lifebar-avatar-min");
	image.find("img").remove();
	if(badge != "" && badge != "REMOVE")
		image.append("<img class='srank-badge-lifebar' src='"+badge+"'></img>");
}

function RefreshBadgeMgmt(){
	$.ajax({ url: '../php/webService.php',
         data: {action: "AsyncMyBadges" },
         type: 'post',
         success: function(output) {
			$(".avatar-badge-mgmt").html(output);
			AttachManageBadgeEvents($("#userSettings").attr("data-id"));
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

function UserSettingsValidation(){
	var errors = "";
	if($("#settings_username").val() === "")
		errors = errors + "Username cannot be blank<br>";
	if($("#settings_email").val() === "")
		errors = errors + "Email cannot be blank<br>";
	if($("#settings_password").val() !== $("#settings_confirm_password").val())
		errors = errors + "Passwords do not match<br>";
	if($("#settings_username").val().indexOf(' ') >= 0)
		errors = errors + "Username can not have spaces<br>";
		
	if(errors === ""){
		VerifyUserData($("#settings_username").val(), $("#settings_email").val());	
	}else{
		$("#userSettings").find(".validation").html(errors);
		$("#userSettings").find(".validation").show();
	}
	
}

function VerifyUserData(username, email){
	var errors = "";
	$.ajax({ url: '../php/webService.php',
         data: {action: "VerifyNewUser", username: username, email: email },
         type: 'post',
         success: function(output) {
         			if(output.indexOf("Username is already used") >= 0 && $("#settings_username").val() !== $("#settings_username").attr('value')){
         				errors = "Username is already used<br>";
         			}else if(output.indexOf("Email is already used") >= 0 && $("#settings_email").val() !== $("#settings_email").attr('value')){
         				errors = errors + "Email is already used<br>";
         			}
         			if(errors !== ""){
         				$("#userSettings").find(".validation").html(errors);
						$("#userSettings").find(".validation").show();
         			}else{
         				var image = $("input[type=radio][name=avatargroup]:checked").attr("id");
         				if(image == "gravatar")
         					image = "Gravatar";
     					else if(image == "uploaded")
     						image = "Uploaded";
 						else if(image == "weburlradio")
 							image = $("#weburl").val();
         				SaveUserSettings($("#userSettings").attr("data-id"), $("#settings_username").val(), $("#settings_password").val(), $("#settings_email").val(), $("#first_name").val(), $("#last_name").val(), $("#birth_year").val(), $("#defaultWatchedSource").val(), $("#steam_id").val(), $("#psn_id").val(), $("#xbox_id").val(), $("#title_id").val(), $("#personalweb_id").val(), $("#twitter_id").val(), image);
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

function SaveUserSettings(userid, username, password, email, first, last, birthyear, source, steam, psn, xbox, title, weburl, twitter, image){
	$("#userSettings").find(".validation").show();
	ShowLoader($("#userSettings").find(".validation"), 'small', '');
	$.ajax({ url: '../php/webService.php',
         data: {action: "SaveUserSettings", userid: userid, username: username, email: email, password: password, first: first, last: last, birthyear: birthyear, source: source, steam: steam, psn: psn, xbox: xbox, title: title, weburl: weburl, twitter: twitter, image: image },
         type: 'post',
         success: function(output) {
         	$("#userSettings").find(".validation").hide();
         	Toast("User Settings Saved");
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

function FollowUser(followid, elem, name){
	elem.velocity({"opacity":"0"}, function(){ elem.css({"display":"none"}); });
	$.ajax({ url: '../php/webService.php',
     data: {action: "FollowUser", followid: followid },
     type: 'post',
     success: function(output) {
 		Toast("You are now following " + name); 
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

function DismissUser(dismiss, elem, name){
	elem.velocity({"opacity":"0"}, function(){ elem.css({"display":"none"}); });
	$.ajax({ url: '../php/webService.php',
     data: {action: "DismissUser", dismiss: dismiss },
     type: 'post',
     success: function(output) {
 		Toast("Dismissed " + name + ", they will no longer be suggested"); 
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
