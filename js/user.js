function ShowUserSettings(){
	ShowProfileDetails("<div class='universalBottomSheetLoading'></div>");
	$.ajax({ url: '../php/webService.php',
         data: {action: "UserSettings" },
         type: 'post',
         success: function(output) {
			$("#BattleProgess").html(output); 
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
    $("#SaveUserSettingsSubmitBtn").on('click', function(e){
    	e.stopPropagation();
    	UserSettingsValidation();	
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
         				SaveUserSettings($("#userSettings").attr("data-id"), $("#settings_username").val(), $("#settings_password").val(), $("#settings_email").val(), $("#first_name").val(), $("#last_name").val(), $("#birthyear").val(), $("#defaultWatchedSource").val(), $("#steam_id").val(), $("#psn_id").val(), $("#xbox_id").val());
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

function SaveUserSettings(userid, username, password, email, first, last, birthyear, source, steam, psn, xbox){
	$("#userSettings").find(".validation").show();
	ShowLoader($("#userSettings").find(".validation"), 'small', '');
	$.ajax({ url: '../php/webService.php',
         data: {action: "SaveUserSettings", userid: userid, username: username, email: email, password: password, first: first, last: last, birthyear: birthyear, source: source, steam: steam, psn: psn, xbox: xbox },
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

function ShowUserPreviewCard(usercard, element){
	ShowPopUpUserPreview(usercard.html());
	$(".user-preview-card-follow-action, .user-preview-card-view-profile").unbind();
	$(".user-preview-card-follow-action").on('click', function(e){
		FollowUser($(this).attr("data-userid"), $(this), $(this).attr("data-name"));
		$("#universalUserPreview").closeModal();
	});
	$(".user-preview-card-view-profile").on('click', function(e){
		if(element == undefined)
			element = $("#discover");
		ShowUserProfile($(this).attr("data-userid"));
		$("#universalUserPreview").closeModal();
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

/*
*
* Universal User Preview
*
*/
function ShowPopUpUserPreview(content){
	$("#universalUserPreview").html(content);
	$("#universalUserPreview").openModal();
  	$(".closeDetailsModal").unbind();
  	$(".closeDetailsModal").on('click', function(){
  		$("#universalUserPreview").closeModal();
  		HideFocus();
  	});
}
