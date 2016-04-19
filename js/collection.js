function DisplayUserCollection(userid){
	window.scrollTo(0, 0);
  	ShowLoader($("#profileInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayUserCollection", userid: userid },
     type: 'post',
     success: function(output) {
 		$("#profileInnerContainer").html(output); 
 		$(".backButton").on("click", function(){
 			ShowUserProfile(userid);
 		});
		$(".import-steam").on("click", function(){
			StartSteamImport(userid, false);	
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