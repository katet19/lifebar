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
		$(".load-steam-import").on("click", function(){
			ImportSteamGames(userid, false);	
		});
		$(".collection-box-container").on("click", function(){
			DisplayCollectionDetails($(this).attr("data-id"), 'UserCollection', userid, false);	
		});
		$(".collection-add").on("click", function(){
			DisplayCollectionPopUp(-1);	
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

function DisplayCollectionDetails(collectionid, from, fromid, isNew){
	window.scrollTo(0, 0);
	var windowWidth = $(window).width();
    $("#profile").css({"display":"inline-block", "left": -windowWidth});
    $("#activity, #discover, #game, #profiledetails, #settings, #admin, #notifications, #user, #landing").css({"display":"none"});
	$("#activity, #discover, #game, #profiledetails, #settings, #admin, #notifications, #user, #landing").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
	$("#profile").velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
  	ShowLoader($("#profileInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayCollectionDetails", collectionid: collectionid },
     type: 'post',
     success: function(output) {
 		$("#profileInnerContainer").html(output);
 		if(isNew)
 			EnableEditMode(from, fromid, 0);
 		
 		var collectionName = $.trim($(".collection-details-name").text().replace(" ","_"));
 		location.hash = "collection/"+collectionid+"/"+fromid+"/"+collectionName;
 		AttachCollectionDetailsEvents(fromid, from);
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


function AttachCollectionDetailsEvents(fromid, from){
	var userid = fromid;
	var offset = parseInt($(".collection-pagination-offset").attr("data-offset"));
	if(offset == 0)
		$(".collection-prev").hide();
	else
		$(".collection-prev").show();
		
	var totalgames = parseInt($(".collection-pagination-total").html());
	if((offset + 25) >= totalgames)
		$(".collection-next").hide();
	else
		$(".collection-next").show();
		
	$(".backButton, .collection-next, .collection-prev, #collection-search, .edit-mode-btn, .import-game-image, .collection-search-icon, .collection-game-add-to-collection").unbind();
  	$(".backButton").on("click", function(){
  		if(from == "UserCollection")
 			DisplayUserCollection(fromid);
 		else if(from == "SteamImport")
 			ImportSteamGames(userid, false, false);
 		else if(from == "Profile")
 			ShowUserContent(userid,false);
 		else if(from == "Activity")
 			ShowActivityHome();
 	});
	$(".collection-next").on("click", function(){
		var curroffset = $(this).parent().parent().find(".collection-pagination-offset").attr("data-offset");
		var offsetInfo = $(".collection-pagination-offset").html().split(" - ");
		var offset = parseInt(offsetInfo[1]);
		var total = parseInt($(".collection-pagination-total").html());
		var max = offset + 25;
		if(max > total)
			max = total;
		$(".collection-pagination-offset").html(offset+" - "+max);
		$(this).parent().parent().find(".collection-pagination-offset").attr("data-offset", offset);
		GetNextPageCollection(offset, userid, from);
	});
	$(".collection-prev").on("click", function(){
		var curroffset = $(this).parent().parent().find(".collection-pagination-offset").attr("data-offset");
		var offset = parseInt(curroffset) - 25;
		var min = offset - 25;
		if(min < 0)
			offset = 0;
		$(".collection-pagination-offset").html(offset+" - "+curroffset);
		$(this).parent().parent().find(".collection-pagination-offset").attr("data-offset", offset);
		GetNextPageCollection(offset, userid, from);
	});
	$("#collection-search").on('keypress keyup', function (e) {
		if (e.keyCode === 13) { 
			e.stopPropagation(); 	
			if($("#collection-search").val() != '' && $("#collection-search").val() != 'Search Collection'){
				SearchCollection($("#collection-search").val(), 0, userid, from);
			}
		} 
	});
	$(".collection-search-icon").on('click', function (e) {
		if($("#collection-search").val() != '' && $("#collection-search").val() != 'Search Collection'){
			SearchCollection($("#collection-search").val(), 0, userid, from);
		}
	});
	$(".edit-mode-btn").on("click", function(){
		EnableEditMode(from, fromid, 250);
	});
	$(".import-game-image").on("click", function(){
		ShowGame($(this).attr("data-id"), $("#profile"));	
	});
	$(".collection-game-add-to-collection").on("click", function(){
		DisplayCollectionPopUp($(this).attr("data-id"));
	});
}

function EnableEditMode(from, fromid, transition){
	$(".edit-mode-btn, .collection-search-icon, .collection-search-box, .collection-details-container, .collection-tier-container-placeholder").hide(transition);
	$(".collection-game-xp-progress-bar, .backContainer, .collection-game-add-to-collection").hide(transition);
	$(".collection-game-tier-container").css({"float":"left","left":"0","margin-left":"0"});
	$(".collection-edit-mode, .collection-edit-save-refresh, .collection-edit-delete, .collection-edit-exit, .collection-edit-random-cover, .import-list-header-add-collection").show(transition);	
	$(".collection-xp-details-container").css({"padding-right":"10px"});
	$(".import-list-header-search").hide();
	$(".collection-games-container").removeClass("s12");
	$(".collection-games-container").addClass("s8");
	$('.collection-game-details-container').removeClass("m4");
	$('.collection-xp-details-container').removeClass("m8");
	$('.collection-game-details-container').addClass("m8");
	$('.collection-xp-details-container').addClass("m4");
	$(".import-list-header-exp").html("&nbsp;");
	AttachEditModeEvents(fromid, from);
}

function AttachEditModeEvents(fromid, from){
	$(".collection-remove-game, .collection-cover-game, .collection-game-search-btn, #collection-add-game-search, .collection-edit-delete, .collection-edit-save-refresh, .collection-edit-exit").unbind();
	$(".collection-remove-game").on("click", function(){
		RemoveGameFromCollection($(".collection-details-name").attr('data-id'), $(this).attr("data-id"), $(this));
	});
	$(".collection-cover-game").on("click", function(){
		SetGametoCover($(".collection-details-name").attr('data-id'), $(this).attr("data-id"));
	});
	$(".collection-edit-random-cover").on("click", function(){
		SetGametoCover($(".collection-details-name").attr('data-id'), -1);
	});
	$('.collection-game-search-btn').on('click', function(e){ 
		e.stopPropagation(); 
		SearchForGameForCollection($(this).parent().parent().find("#collection-add-game-search").val(), $(".collection-details-name").attr('data-id'));
	});
	$("#collection-add-game-search").on('keypress keyup', function (e) {
		if (e.keyCode === 13) { 
			SearchForGameForCollection($(this).parent().parent().find("#collection-add-game-search").val(), $(".collection-details-name").attr('data-id'));
		}
	});
	$(".collection-edit-delete").on("click", function(){
		ShowPopUp("<div class='row'><div class='col s12' style='position:relative;'><div class='collection-mgmt-header'>Confirm deletion of Collection ("+$(".collection-details-name").text()+")</div></div></div><div class='row'><div class='col s12'><div class='btn collection-confirm-delete'>Yes, Delete My Collection</div></div></div>");
		$(".collection-confirm-delete").on("click", function(){
			var collectionid = $(".collection-details-name").attr('data-id');
			$.ajax({ url: '../php/webService.php',
		     data: {action: "DeleteCollection", collectionid: collectionid },
		     type: 'post',
		     success: function(output) {
				$("#universalPopUp").closeModal();
				DisplayUserCollection(fromid);
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
	});
	$(".collection-edit-save-refresh").on("click", function(){
		var collectionid = $(".collection-details-name").attr('data-id');
		var collectionName = $(this).parent().parent().find(".frm-collection-name").val();
		var collectionDesc = $(this).parent().parent().find(".frm-collection-desc").val();
		$.ajax({ url: '../php/webService.php',
	     data: {action: "UpdateCollection", collectionid: collectionid, collectionName: collectionName, collectionDesc: collectionDesc },
	     type: 'post',
	     success: function(output) {
			DisplayCollectionDetails(collectionid, from, fromid, false);
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
	
	$(".collection-edit-exit").on("click", function(){
		var collectionid = $(".collection-details-name").attr('data-id');
		DisplayCollectionDetails(collectionid, from, fromid, false);
	});

}

function GetNextPageCollection(offset, userid, from){
 	$(".collection-games-container").html("<div class='progress' style='height: 8px;margin-top: -5px;background-color:#C5CAE9;margin-bottom:75px;'><div class='indeterminate' style='background-color:#3F51B5;'></div></div>");
 	window.scrollTo(0, 0);
	var collectionid = $(".collection-details-name").attr('data-id');
	var editMode = false;
	if($(".collection-edit-mode").is(":visible"))
		editMode = true;
		
	$.ajax({ url: '../php/webService.php',
     data: {action: "NextPageCollection", collectionid: collectionid, userid: userid, offset: offset, editMode: editMode },
     type: 'post',
     success: function(output) {
 		$(".collection-games-container").html(output);
 		if(editMode){
			EnableEditMode(from, userid, 0);
 		}
     	AttachCollectionDetailsEvents(userid, from);
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

function SearchCollection(searchstring, offset, userid, from){
 	$(".collection-games-container").html("<div class='progress' style='height: 8px;margin-top: -5px;background-color:#C5CAE9;margin-bottom:75px;'><div class='indeterminate' style='background-color:#3F51B5;'></div></div>");
 	window.scrollTo(0, 0);
	var collectionid = $(".collection-details-name").attr('data-id');
	var editMode = false;
	if($(".collection-edit-mode").is(":visible"))
		editMode = true;
		
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplaySearchCollection", collectionid: collectionid, searchstring: searchstring, offset: offset, userid: userid, editMode: editMode },
     type: 'post',
     success: function(output) {
 		$(".collection-games-container").html(output);
 		$(".collection-remove-game, .collection-cover-game").unbind();
		$(".collection-remove-game").on("click", function(){
			RemoveGameFromCollection($(".collection-details-name").attr('data-id'), $(this).attr("data-id"), $(this));
		});
		$(".collection-cover-game").on("click", function(){
			SetGametoCover($(".collection-details-name").attr('data-id'), $(this).attr("data-id"));
		});
     	AttachCollectionDetailsEvents(userid, from);
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

function DisplayCollectionPopUp(gameid){
	ShowPopUp("");
	ShowLoader($("#universalPopUp"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayCollectionManagement", gameid: gameid },
     type: 'post',
     success: function(output) {
 		$("#universalPopUp").html(output); 
 		AttachCollectionManagementEvents(gameid);
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

function AttachCollectionManagementEvents(gameid){
	$(".frm-collection-create-btn").on("click", function(){
		var name = $(this).parent().parent().parent().find(".frm-collection-name").val();
		var desc = $(this).parent().parent().parent().find(".frm-collection-desc").val();
		ValidateCollectionName(name, desc, gameid);	
	});
	$(".collection-add-game-from-popup-add").on("click", function(){
		var collectionID = $(this).parent().attr("data-id");
		AddGameToCollectionFromCollectionManage(collectionID, gameid);
		$(this).html("<i class='fa fa-check'></i>");
		$(this).removeClass("collection-add-game-from-popup-add");
		$(this).addClass("collection-add-game-from-popup-remove");
	});
}

function ValidateCollectionName(collectionName, desc, gameid){
	$(".frm-collection-create-btn").hide(200);
	ShowLoader($(".frm-collection-validation"), 'small', "<br>");
	$(".frm-collection-validation").show(200);
	$.ajax({ url: '../php/webService.php',
	     data: {action: "ValidateCollectionName", collectionName: collectionName },
	     type: 'post',
	     success: function(output) {
			if($.trim(output) == "NEW"){

				$.ajax({ url: '../php/webService.php',
				     data: {action: "CreateCollection", collectionName: collectionName, collectionDesc: desc, gameid: gameid },
				     type: 'post',
				     success: function(output) {
				     	var collectionID = parseInt($.trim(output));
				     	if(collectionID > 0){
				     		$("#universalPopUp").closeModal();
				     		DisplayCollectionDetails(collectionID, 'UserCollection', $(".userContainer").attr("data-id"), true);
				     	}else{
				     		//throw an error here!	
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
			}else{
				$(".frm-collection-validation").html("Collection Name must be unique per user.");
				$(".frm-collection-validation").show(200);
				$(".frm-collection-create-btn").show(200);
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

function SearchForGameForCollection(search, collectionID){
	var searchResults = $(".collection-edit-mode").find(".collection-game-search-results");
	searchResults.parent().css({"z-index":"10"});
	searchResults.show(250);
	ShowLoader($(".collection-edit-mode").find(".collection-game-search-results"), 'small', "<br><br><br>");
		
	$.ajax({ url: '../php/webService.php',
     data: {action: "AdminGameSearch", search: search },
     type: 'post',
     success: function(output) {
		$(".collection-edit-mode").find(".collection-game-search-results").html(output);
		$(".collection-game-search-results li").each(function(){
			$(this).append("<div class='btn add-to-collection-from-search'>Add</div>");	
		});
 		$(".collection-game-search-results li").on('click', function(e){
 			e.stopPropagation(); 
 			/*var gbid = $(this).attr("data-gbid");
 			var image = $(this).attr("data-image");
 			AddGameToCollection(collectionID, gbid);*/
 			
 		});
  		$(".add-to-collection-from-search").on('click', function(e){
 			e.stopPropagation(); 
 			var gbid = $(this).parent().attr("data-gbid");
 			var image = $(this).parent().attr("data-image");
 			AddGameToCollection(collectionID, gbid);
 			$(this).removeClass("btn");
 			$(this).removeClass("add-to-collection-from-search");
 			$(this).addClass("collection-added-from-search");
 			$(this).html("Added");
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

function AddGameToCollectionFromCollectionManage(collectionID, gameid){
	$.ajax({ url: '../php/webService.php',
     data: {action: "AddGameToCollectionFromCollectionManger", gameid: gameid, collectionID: collectionID },
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
}

function AddGameToCollection(collectionID, gbid){
	$.ajax({ url: '../php/webService.php',
     data: {action: "AddGameToCollection", gbid: gbid, collectionID: collectionID },
     type: 'post',
     success: function(output) {
     	
		$(".collection-games-container").prepend(output);
		$(".collection-remove-game, .collection-cover-game").unbind();
		$(".collection-remove-game").on("click", function(){
			RemoveGameFromCollection($(".collection-details-name").attr('data-id'), $(this).attr("data-id"), $(this));
		});
		$(".collection-cover-game").on("click", function(){
			SetGametoCover($(".collection-details-name").attr('data-id'), $(this).attr("data-id"));
		});
		
		if($(".no-games-found").length > 0)
			$(".no-games-found").remove();	
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

function RemoveGameFromCollection(collectionID, gameid, element){
 	element.parent().parent().css({"background-color": "#C62828"});
	element.parent().parent().find(".col").css({"opacity":"0"});
	element.parent().parent().append("<div style='text-align:center;color:white;font-size:1.5em;line-height:69px;position:absolute;top:0;left:0;width:100%'>Game removed from Collection</div>");
	var row = element.parent().parent().parent();
	setTimeout(function(){
				row.hide(250);
			}
		,500);
	$.ajax({ url: '../php/webService.php',
     data: {action: "RemoveGameFromCollection", gameid: gameid, collectionID: collectionID },
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
}

function SetGametoCover(collectionID, gameid){
	$.ajax({ url: '../php/webService.php',
     data: {action: "SetCollectionCover", gameid: gameid, collectionid: collectionID },
     type: 'post',
     success: function(output) {
     	if(gameid > 0){
	     	var coverimage = $.trim(output);
	     	$(".collection-cover-image").css({"background":""});
	     	$(".collection-cover-image").css({"background":"url("+coverimage+") 25% 50%", "background-size":"cover"});
	     	window.scrollTo(0, 0);
			Toast("Updated cover image for this Collection.");
     	}else{
     		Toast("Updated cover image to randomly pick from Collection each time");
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