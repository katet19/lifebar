function InitializeLogin(){
	$("#loginButton, #loginButtonSideNav").on('click', function(e){ $('#loginModal').openModal(); $("#username").focus(); });
	$("#signupButton, #signupButtonSideNav").on('click', function(e){ $('#signupModal').openModal(); });
	$('.signOutButton').on("click", function(e){ Logout(); });
	$("select").material_select();
	AttachLoginEvents();
	AttachSignUpEvents();
	AttachForgotPasswordEvents();
	CheckForPasswordReset();
}

function AttachSignUpEvents(){
	$("#SignupSubmitBtn").on("click", function(e){
		var errors = "";
		if($("#signup_username").val() === "")
			errors = errors + "Username cannot be blank<br>";
		if($("#signup_email").val() === "")
			errors = errors + "Email cannot be blank<br>";
		if($("#signup_password").val() === "" || $("#signup_confirm_password").val() === "")
			errors = errors + "Password cannot be blank<br>";
		if($("#signup_password").val() !== $("#signup_confirm_password").val())
			errors = errors + "Passwords do not match<br>";
		if($("#signup_username").val().indexOf(' ') >= 0)
			errors = errors + "Username can not have spaces<br>";
			
		if(errors === "")
			VerifyNewUserData($("#signup_username").val(), $("#signup_email").val());	
		else{
			$("#signupModal").find(".validation").html(errors);
			$("#signupModal").find(".validation").show();
		}
			
	});
}

function Signup(username, password, email, first, last, birthyear){
	ShowLoader($("#SignupSubmitBtn"), 'small', '');
	$.ajax({ url: '../php/webService.php',
         data: {action: "Signup", username: username, password: password, email: email, first: first, last: last, birthyear: birthyear },
         type: 'post',
         success: function(output) {
         			GAEvent('Signup', email);
         			setCookie("RememberMe", $.trim(output), 14);
          			window.location.hash = "#notifications";
					window.location.reload(true);
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

function VerifyNewUserData(username, email){
	var errors = "";
	$.ajax({ url: '../php/webService.php',
         data: {action: "VerifyNewUser", username: username, email: email },
         type: 'post',
         success: function(output) {
         			if(output.indexOf("Username is already used") >= 0){
         				errors = "Username is already used<br>";
         			}else if(output.indexOf("Email is already used") >= 0){
         				errors = errors + "Email is already used<br>";
         			}
         			if(errors !== ""){
         				$("#signupModal").find(".validation").html(errors);
						$("#signupModal").find(".validation").show();
         			}else{
         				Signup($("#signup_username").val(), $("#signup_password").val(), $("#signup_email").val(), $("#first_name").val(), $("#last_name").val(), $("#birthyear").val());
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

function CheckForPasswordReset(){
	var forgottenkey = getParameterByName('forgotkey');
	if(forgottenkey !== "" && forgottenkey !== undefined){
		$('#passwordResetModal').openModal();
		$("#ResetLoginBtn").on("click", function(e){
			if($("#resetpassword").val() === $("#confirmresetpassword").val() && $("#resetpassword").val() !== "")
				ResetPassword(forgottenkey, $("#resetpassword").val());	
			else
				DisplayLoginValidation(3);
		});
	}
}

function ResetPassword(key, password){
	$.ajax({ url: '../php/webService.php',
         data: {action: "ResetForgottenPassword", key: key, password: password },
         type: 'post',
         success: function(output) {
         				window.location = window.location.pathname; 
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

function AttachForgotPasswordEvents(){
	$(".forgotPasswordBtn").on("click", function(e){ 
		$(".forgotPassword").show(250); 
		$("#ForgotLoginSubmitBtn").on("click", function(e){
			if($("#forgotemail").val() !== "")
				RequestLoginReset($("#forgotemail").val());
			else
				DisplayLoginValidation(2);
		});
	});
}

function AttachLoginEvents(){
	$("#LoginSubmitBtn").on("click", function(e){
		Login($("#username").val(), $("#password").val());	
	});
	$("#username, #password").on('keypress keyup', function (e) {
		if (e.keyCode === 13) { 
			e.stopPropagation(); 	
			Login($("#username").val(), $("#password").val()); 
		} 
		
	});
	$(".google-login").on("click", function(e){
		$.ajax({ url: '../php/webService.php',
		         data: {action: "LoginGoogle" },
		         type: 'post',
		         success: function(output) {
		  			$("#loginModal").find(".validation").html(output);
		  			$("#loginModal, #passwordResetModal").find(".validation").show();
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
	$(".twitter-login").on("click", function(e){
		$.ajax({ url: '../php/webService.php',
		         data: {action: "LoginTwitter" },
		         type: 'post',
		         success: function(output) {
		  			$("#loginModal").find(".validation").html(output);
		  			$("#loginModal, #passwordResetModal").find(".validation").show();
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
		$(".facebook-login").on("click", function(e){
		$.ajax({ url: '../php/webService.php',
		         data: {action: "LoginFacebook" },
		         type: 'post',
		         success: function(output) {
		  			$("#loginModal").find(".validation").html(output);
		  			$("#loginModal, #passwordResetModal").find(".validation").show();
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
		$(".steam-login").on("click", function(e){
		$.ajax({ url: '../php/webService.php',
		         data: {action: "LoginSteam" },
		         type: 'post',
		         success: function(output) {
		  			$("#loginModal").find(".validation").html(output);
		  			$("#loginModal, #passwordResetModal").find(".validation").show();
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

function Login(user, pw){
	if(user === "" || pw === ""){
		DisplayLoginValidation(0);
	}else{
		ShowLoader($(".validation"), 'small');
		$.ajax({ url: '../php/webService.php',
	         data: {action: "Login", user: user, pw: pw },
	         type: 'post',
	         success: function(output) {
	         			if(output.indexOf("INCORRECT USERNAME OR PASSWORD") >= 0){
	         				DisplayLoginValidation(1);
	         			}else{
	         				GAEvent('Login', user);
	         				setCookie("RememberMe", $.trim(output), 14);
	         				location.hash = "#activity";
	         				location.reload();
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
}

function DisplayLoginValidation(errorCode){
	if(errorCode === 0)
		$("#loginModal").find(".validation").html("Username/Password fields can not be blank");
	else if(errorCode == 1)
		$("#loginModal").find(".validation").html("Incorrect Username or Password");
	else if(errorCode == 2)
		$("#loginModal").find(".validation").html("Missing email address to reset password");
	else if(errorCode == 3)
		$("#passwordResetModal").find(".validation").html("Passwords do not match or are empty");
		
	$("#loginModal, #passwordResetModal").find(".validation").show();
}

function RequestLoginReset(email){
	ShowLoader($(".forgotPasswordBtn div"), 'small', '');
	$(".forgotPassword").html("");
	$.ajax({ url: '../php/webService.php',
     data: {action: "ForgotPassword", email: email },
     type: 'post',
     success: function(output) {
     			GAEvent('Login', 'Reset Password:'+email);
	 			$(".forgotPassword").html("<div style='text-align:center'>Please check your email to reset your password</div>");
 				$(".forgotPasswordBtn div").remove();
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

function Logout(){
	$.ajax({ url: '../php/webService.php',
     data: {action: "Logout" },
     type: 'post',
     success: function(output) {
     	setCookie("RememberMe", "removeMe", -1);
 		location.reload();
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
