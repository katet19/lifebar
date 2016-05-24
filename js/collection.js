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
 		
 		//Handle mobile
 		if($(window).width() <= 600){
 			$(".edit-mode-btn").text("EDIT");
 			$(".collection-details-container").parent().css({"top":"150px"});
 			$(".backContainer").css({"top":"15px"});
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
		
	$(".backButton, .collection-next, .collection-prev, #collection-search, .collection-game-xp-progress-bar, .edit-mode-btn, .import-game-image, .collection-search-icon, .collection-game-add-to-collection, .collection-follow-btn").unbind();
	$(".collection-follow-btn").on("click", function(){
		var following = $(this).attr("data-following");
		if(following == "yes"){
			$(this).attr("data-following", "no");
			$(this).text("Follow");
			UpdateCollectionSub("no");
		}else{
			$(this).attr("data-following", "yes");	
			$(this).text("Un-follow");
			UpdateCollectionSub("yes");
		}
	});
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
		DisplayCollectionQuickForm($(this), $(this).attr("data-id"));
	});
	$(".collection-share").on("click", function(){
		ShowShareModal("collection", $(this).attr("data-id"));
	});
	$(".collection-game-xp-progress-bar").on("click", function(){
		var xpcontainer = $(this).parent().parent().find(".collection-xp-entry-container");
		var maincontainer = $(this).parent().parent().parent();
		if(!xpcontainer.is(":visible"))
			DisplayCollectionPlayedEdit($(this).attr("data-gameid"), xpcontainer, maincontainer);
	});
	$(".collection-game-tier-container-watched").on("click", function(){
		var xpcontainer = $(this).parent().parent().find(".collection-xp-entry-container");
		var maincontainer = $(this).parent().parent().parent();
		if(!xpcontainer.is(":visible"))
			DisplayCollectionWatchedEdit($(this).attr("data-gameid"), xpcontainer, maincontainer);
	});
	$(".collection-game-xp-progress-tick").on("click", function(){
		if($(".collection-game-xp-progress-tick-selected").length > 0)
			$(".collection-game-xp-progress-tick-selected").removeClass("collection-game-xp-progress-tick-selected");
			
		$(this).addClass("collection-game-xp-progress-tick-selected");
		var progress = $(this).attr("data-prog");
		var tierContainer = $(this).parent().parent().parent().find(".collection-game-tier-container");
		var progContainer = $(this).parent().parent().parent().find(".collection-game-xp-progress-filled");
		tierContainer.css({"left":progress+"%", "margin-left":"-20px"});
		progContainer.css({"width":progress+"%"});
		ValidateCollectionXPEntry('played');
	});
}

function HideCollectionPlayedEdit(xpcontainer, maincontainer){
	maincontainer.removeClass("collectionEditGameMode");
	xpcontainer.hide();
	xpcontainer.html("");
	var tierContainer = maincontainer.find(".collection-game-tier-container");
	var tierWatchedContainer = maincontainer.find(".collection-game-tier-container-watched");
	var progContainer = maincontainer.find(".collection-game-xp-progress-filled");
	if(progContainer.attr("data-default") == "0")
		tierContainer.css({"left":progContainer.attr("data-default")+"%", "margin-left":"0px"});
	else
		tierContainer.css({"left":progContainer.attr("data-default")+"%", "margin-left":"-20px"});
	
	tierContainer.removeClass("tier1BG tier2BG tier3BG tier4BG tier5BG");
	tierWatchedContainer.removeClass("tier1BG tier2BG tier3BG tier4BG tier5BG");
	tierContainer.addClass("tier"+tierContainer.attr("data-default")+"BG");
	tierWatchedContainer.addClass("tier"+tierContainer.attr("data-default")+"BG");
	progContainer.removeClass("tier1BG tier2BG tier3BG tier4BG tier5BG");
	progContainer.addClass("tier"+tierContainer.attr("data-default")+"BG");
		
	progContainer.css({"width":progContainer.attr("data-default")+"%"});
	$(".collection-game-xp-progress-tick-selected").removeClass("collection-game-xp-progress-tick-selected");
	$(".collection-games-container-row").each(function(){
		$(this).css({"opacity":"1.0"});	
	});
}

function CloseCollectionPlayedEdit(maincontainer, xpcontainer){
	$(".collectionEditGameMode").removeClass("collectionEditGameMode");
	xpcontainer.hide();
	xpcontainer.html("");
	var tierContainer = maincontainer.find(".collection-game-tier-container");
	var progContainer = maincontainer.find(".collection-game-xp-progress-filled");
	var progress = $(".collection-game-xp-progress-tick-selected").attr("data-prog");
	var tier = maincontainer.find(".myxp-selected-tier").attr("data-tier");
	progContainer.attr("data-default", progress);
	tierContainer.attr("data-default",tier);
	$(".collection-game-xp-progress-tick-selected").removeClass("collection-game-xp-progress-tick-selected");
	$(".collection-games-container-row").each(function(){
		$(this).css({"opacity":"1.0"});	
	});
}

function DisplayCollectionPlayedEdit(gameid, xpcontainer, maincontainer){
	if($(".collectionEditGameMode").length > 0){
		$(".collectionEditGameMode").css({"opacity":"0.2"});
		$(".collectionEditGameMode").find(".collection-xp-entry-container").hide();
		$(".collectionEditGameMode").removeClass("collectionEditGameMode");
	}
	maincontainer.addClass("collectionEditGameMode");
	ShowLoader(xpcontainer, 'small', "<br><br><br>");
	xpcontainer.show(250);
	$(".collection-games-container-row").each(function(){
		if($(this).attr("data-id") != maincontainer.attr("data-id"))
			$(this).css({"opacity":"0.2"});
		else
			$(this).css({"opacity":"1.0"});	
	});
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayCollectionPlayedEdit", gameid: gameid },
     type: 'post',
     success: function(output) {
		xpcontainer.html(output);
		$('select').material_select();
		AttachPlayedCollectionEditEvents();
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

function DisplayCollectionWatchedEdit(gameid, xpcontainer, maincontainer){
	if($(".collectionEditGameMode").length > 0){
		$(".collectionEditGameMode").css({"opacity":"0.2"});
		$(".collectionEditGameMode").find(".collection-xp-entry-container").hide();
		$(".collectionEditGameMode").removeClass("collectionEditGameMode");
	}
	maincontainer.addClass("collectionEditGameMode");
	ShowLoader(xpcontainer, 'small', "<br><br><br>");
	xpcontainer.show(250);
	$(".collection-games-container-row").each(function(){
		if($(this).attr("data-id") != maincontainer.attr("data-id"))
			$(this).css({"opacity":"0.2"});
		else
			$(this).css({"opacity":"1.0"});	
	});
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayCollectionWatchedEdit", gameid: gameid },
     type: 'post',
     success: function(output) {
		xpcontainer.html(output);
		$('select').material_select();
		AttachWatchedCollectionEditEvents();
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

function AttachPlayedCollectionEditEvents(){
	ToggleCollectionQuarter($(".myxp-year").val());
	ValidateCollectionXPEntry('played');
	$(".myxp-cancel").on('click', function(){
		var maincontainer = $(this).parent().parent().parent().parent().parent();
		var xpcontainer = maincontainer.find(".collection-xp-entry-container");
		HideCollectionPlayedEdit(xpcontainer, maincontainer);
	});
	$(".myxp-save").on("click", function(){
		if(!$(this).hasClass("disabled")){
			var maincontainer = $(this).parent().parent().parent().parent().parent();
			var xpcontainer = maincontainer.find(".collection-xp-entry-container");
			SaveCollectionXPEntry(maincontainer, xpcontainer, 'played');	
		}
	});
	$(".myxp-post").on("click", function(){
		if(!$(this).hasClass("disabled")){
			var maincontainer = $(this).parent().parent().parent().parent().parent().parent().parent().parent();
			var xpcontainer = maincontainer.find(".collection-xp-entry-container");
			SaveCollectionXPEntry(maincontainer, xpcontainer, 'post');	
		}
	});
	$(".collection-myxp-tier").on('click', function(){
		if(!$(this).hasClass("myxp-selected-tier")){
			var oldselection = $(".myxp-selected-tier");
			oldselection.removeClass("myxp-selected-tier tierBorderColor1selected tierBorderColor2selected tierBorderColor3selected tierBorderColor4selected tierBorderColor5selected");
			$(this).addClass("myxp-selected-tier tierBorderColor"+ $(this).attr("data-tier") +"selected");
			var tier = $(this).attr("data-tier");
			var xpTierContainer = $(this).parent().parent().parent().parent().parent().parent().parent().parent().find(".collection-game-tier-container");
			var xpProgBar = $(this).parent().parent().parent().parent().parent().parent().parent().parent().find(".collection-game-xp-progress-filled");
			xpTierContainer.removeClass("tier1BG tier2BG tier3BG tier4BG tier5BG");
			xpProgBar.removeClass("tier1BG tier2BG tier3BG tier4BG tier5BG");
			xpTierContainer.addClass("tier"+tier+"BG");
			xpProgBar.addClass("tier"+tier+"BG");
			
			ValidateCollectionXPEntry('played');
		}
	});
	$(".myxp-year").on('change', function() { 
		ToggleCollectionQuarter($(this).val());
		ValidateCollectionXPEntry('played');
	});
	$(".collection-myxp-quarter").on('click',function() {
		$(".collection-myxp-quarter-selected").removeClass("collection-myxp-quarter-selected");
		$(this).addClass("collection-myxp-quarter-selected");
		ValidateCollectionXPEntry('played');
	});
	$('.myxp-platforms').change(function() {
		ValidateCollectionXPEntry('played');
	});
	$(".myxp-quote").on('keypress keyup', function (e) {
		e.stopPropagation(); 
		if($(this).val() !== "")
			ValidateCollectionXPEntry('played');
	});
}

function AttachWatchedCollectionEditEvents(){
	ToggleCollectionQuarter($(".myxp-year").val());
	ValidateCollectionXPEntry('watched');
	$(".myxp-cancel").on('click', function(){
		var xpcontainer = $(this).parent().parent().parent().parent().find(".collection-xp-entry-container");
		var maincontainer = $(this).parent().parent().parent().parent().parent();
		HideCollectionPlayedEdit(xpcontainer, maincontainer);
	});
	$(".myxp-post").on("click", function(){
		if(!$(this).hasClass("disabled")){
			var maincontainer = $(this).parent().parent().parent().parent().parent().parent().parent().parent();
			var xpcontainer = maincontainer.find(".collection-xp-entry-container");
			SaveCollectionXPEntry(maincontainer, xpcontainer, 'post');	
		}
	});
	$(".myxp-save").on("click", function(){
		if(!$(this).hasClass("disabled")){
			var maincontainer = $(this).parent().parent().parent().parent().parent();
			var xpcontainer = $(this).parent().parent().parent().parent().find(".collection-xp-entry-container");
			SaveCollectionXPEntry(maincontainer, xpcontainer, 'watched');
		}
	});
	$(".collection-myxp-tier").on('click', function(){
		if(!$(this).hasClass("myxp-selected-tier")){
			var oldselection = $(".myxp-selected-tier");
			oldselection.removeClass("myxp-selected-tier tierBorderColor1selected tierBorderColor2selected tierBorderColor3selected tierBorderColor4selected tierBorderColor5selected");
			$(this).addClass("myxp-selected-tier tierBorderColor"+ $(this).attr("data-tier") +"selected");
			var tier = $(this).attr("data-tier");
			var xpTierContainer = $(this).parent().parent().parent().parent().parent().parent().parent().parent().find(".collection-game-tier-container-watched");
			xpTierContainer.removeClass("tier1BG tier2BG tier3BG tier4BG tier5BG");
			xpTierContainer.addClass("tier"+tier+"BG");
			
			ValidateCollectionXPEntry('watched');
		}
	});
	$(".myxp-year").on('change', function() { 
		ToggleCollectionQuarter($(this).val());
		ValidateCollectionXPEntry('watched');
	});
	$(".collection-myxp-quarter").on('click',function() {
		$(".collection-myxp-quarter-selected").removeClass("collection-myxp-quarter-selected");
		$(this).addClass("collection-myxp-quarter-selected");
		ValidateCollectionXPEntry('watched');
	});
	$(".myxp-quote").on('keypress keyup', function (e) {
		e.stopPropagation(); 
		if($(this).val() !== "")
			ValidateCollectionXPEntry('watched');
	});
	$(".watched-type-box").on('click', function(){
		$(".watched-type-box-selected").removeClass("watched-type-box-selected");
		$(this).addClass("watched-type-box-selected");
		ValidateCollectionXPEntry('watched');
	});
}

function ToggleCollectionQuarter(year){
	var currentYear = (new Date).getFullYear();
	if(year == currentYear -1){
		$(".collection-myxp-quarter").show(100);
		$(".collection-myxp-quarter-selected").removeClass("collection-myxp-quarter-selected");
		$(".q0").addClass("collection-myxp-quarter-selected");
	}else if(year != currentYear){
		$(".collection-myxp-quarter").hide(100);
		$(".collection-myxp-quarter-selected").removeClass("collection-myxp-quarter-selected");
		$(".q0").addClass("collection-myxp-quarter-selected");
	}else{
		$(".collection-myxp-quarter").show(100);
	}
}

function ValidateCollectionXPEntry(type){
	var validation = "";
	var container = $(".collectionEditGameMode");
	if(container.find(".myxp-selected-tier").length == 0)
		validation = validation + "<li style='text-align:left;'><i class='mdi-alert-warning'></i> Selecting a tier 1 - 5 is requried</li>";
			
	if(container.find(".collection-game-xp-progress-tick-selected").length == 0 && type == 'played')
		validation = validation + "<li style='text-align:left;'><i class='mdi-alert-warning'></i> Selecting a percentage completed is required</li>";
			
	if(container.find(".myxp-platforms").length > 0 && type == 'played'){
		if(container.find(".myxp-platform").val() == '')
			validation = validation + "<li style='text-align:left;'><i class='mdi-alert-warning'></i> Selecting a platform played is required</li>";
	}
	if(container.find(".watched-type-box-selected").length == 0 && type == 'watched'){
			validation = validation + "<li style='text-align:left;'><i class='mdi-alert-warning'></i> Selecting a viewing experience is required</li>";
	}

	if(validation == ""){
		container.find(".myxp-save").removeClass("disabled");
		
		if(container.find(".myxp-quote").val() != '')
			container.find(".myxp-post").removeClass("disabled");
	}else if(!container.find(".myxp-save").hasClass("disabled")){
		container.find(".myxp-save").addClass("disabled");
		container.find(".myxp-post").addClass("disabled");
	}
		
	return validation;
}

function SaveCollectionXPEntry(maincontainer, xpcontainer, type){
	if(!$(".myxp-save").hasClass("disabled")){
		var gameid = maincontainer.find(".collection-game-name").attr("data-gid");
		var quote = maincontainer.find(".myxp-quote").val();
		var tier = maincontainer.find(".myxp-selected-tier").attr("data-tier");
		var year = maincontainer.find(".myxp-year").val();
		var quarter = maincontainer.find(".collection-myxp-quarter-selected").attr("data-text");

		if(type == 'played'){
			$(".myxp-save").html("<div class='preloader-wrapper small active' style='vertical-align:text-top;margin-right:1em; width:15px; height:15px;'><div class='spinner-layer spinner-white-only'><div class='circle-clipper left'><div class='circle' style='border-width:2px;'></div></div><div class='gap-patch'><div class='circle' style='border-width:2px;'></div></div><div class='circle-clipper right'><div class='circle' style='border-width:2px;'></div></div></div></div> <span class='myxp-saving-label'>Saving XP</span>");
			var platform = [];
			platform.push(maincontainer.find(".myxp-platform").val());
			var completion = maincontainer.find(".collection-game-xp-progress-tick-selected").attr("data-prog");
			$.ajax({ url: '../php/webService.php',
		         data: {action: "SavePlayedCollection", gameid: gameid, quote: quote, tier: tier, platform: platform, completion: completion, year: year, quarter: quarter  },
		         type: 'post',
		         success: function(output) {
		         	GAEvent('XP', 'Collection Played');
	         		DisplayBattleProgressFromCollection(output, maincontainer, xpcontainer, type);
					Waves.displayEffect();
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
		}else if(type == 'watched'){
			var viewing = maincontainer.find(".watched-type-box-selected").attr("data-text");
			var viewsrc = maincontainer.find(".myxp-view-source").val();
			
			$(".myxp-save").html("<div class='preloader-wrapper small active' style='vertical-align:text-top;margin-right:1em; width:15px; height:15px;'><div class='spinner-layer spinner-white-only'><div class='circle-clipper left'><div class='circle' style='border-width:2px;'></div></div><div class='gap-patch'><div class='circle' style='border-width:2px;'></div></div><div class='circle-clipper right'><div class='circle' style='border-width:2px;'></div></div></div></div> <span class='myxp-saving-label'>Saving XP</span>");
			$.ajax({ url: '../php/webService.php',
		         data: {action: "SaveWatchedCollection", gameid: gameid, quote: quote, tier: tier, viewing: viewing, viewsrc: viewsrc, year: year, quarter: quarter  },
		         type: 'post',
		         success: function(output) {
		         	GAEvent('XP', 'Collection Watched');
	         		DisplayBattleProgressFromCollection(output, maincontainer, xpcontainer, type);
					Waves.displayEffect();
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
		}else if(type == 'post'){
			var completion = maincontainer.find(".collection-game-xp-progress-tick-selected").attr("data-prog");
			$(".myxp-post").html("<div class='preloader-wrapper small active' style='vertical-align:text-top;margin-right:1em; width:15px; height:15px;'><div class='spinner-layer spinner-white-only'><div class='circle-clipper left'><div class='circle' style='border-width:2px;'></div></div><div class='gap-patch'><div class='circle' style='border-width:2px;'></div></div><div class='circle-clipper right'><div class='circle' style='border-width:2px;'></div></div></div></div> <span class='myxp-saving-label'>Posting Update</span>");
			$.ajax({ url: '../php/webService.php',
		         data: {action: "PostUpdateFromCollection", gameid: gameid, quote: quote, tier: tier, completion: completion  },
		         type: 'post',
		         success: function(output) {
		         	GAEvent('XP', 'Collection Updated Post');
	         		DisplayBattleProgressFromCollection(output, maincontainer, xpcontainer, type);
					Waves.displayEffect();
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
	}else{
		ToastError(ValidateXPEntry());
	}
}

function DisplayBattleProgressFromCollection(content, maincontainer, xpcontainer, type){
	var contentsplit = $.trim(content).split("|**|");
	if(contentsplit[1] == "true"){
		ShowBattleProgress(contentsplit[0]);
	}else{
		if(type == "post")
			Toast("Posted an update to your thoughts on '"+maincontainer.find(".collection-game-name").text()+"'");
		else if(type == 'watched')
			Toast("Saved your watched XP for '"+maincontainer.find(".collection-game-name").text()+"'");
		else if(type == 'played')
			Toast("Updated your played XP for '"+maincontainer.find(".collection-game-name").text()+"'");
	}
	
	CloseCollectionPlayedEdit(maincontainer, xpcontainer);
}

function UpdateCollectionSub(following){
	var collectionid = $(".collection-details-name").attr('data-id');
	var myAction = "UnfollowCollection";
	if(following == "yes")
		myAction = "FollowCollection";
		
	$.ajax({ url: '../php/webService.php',
     data: {action: myAction, collectionid: collectionid },
     type: 'post',
     success: function(output) {
     	var total = parseInt($(".collection-following-counter").html());
		if(following == "yes"){
			Toast("Following '"+$(".collection-details-name").text()+"'");
			$(".collection-following-counter").html(total + 1);
		}else{
			Toast("Un-followed '"+$(".collection-details-name").text()+"'");
			$(".collection-following-counter").html(total - 1);
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

function EnableEditMode(from, fromid, transition){
	if($(".collection-edit-properties-container").length > 0){
		$(".collection-details-container").hide(transition);
	}
	$(".edit-mode-btn, .collection-search-icon, .collection-search-box, .collection-tier-container-placeholder").hide(transition);
	$(".collection-game-xp-progress-bar, .backContainer, .collection-game-add-to-collection").hide(transition);
	$(".collection-game-tier-container").css({"float":"left","left":"45px","margin-left":"0"});
	$(".collection-game-tier-container-watched").css({"left":"0px","right":"inherit"})
	$(".collection-edit-mode, .collection-edit-save-refresh, .collection-edit-delete, .collection-edit-exit").show(transition);	
	$(".collection-xp-details-container").css({"padding-right":"10px"});
	$(".import-list-header-search").hide();
	$(".collection-games-container").removeClass("s12");
	$(".collection-games-container").addClass("s8");
	$('.collection-game-details-container').removeClass("m4");
	$('.collection-xp-details-container').removeClass("m8");
	$('.collection-game-details-container').addClass("m8");
	$('.collection-xp-details-container').addClass("m4");
	$(".import-list-header-exp").html("&nbsp;");
	$(".collection-xp-details-container").addClass("collection-xp-details-container-edit");
	$(".collection-xp-details-container").removeClass("collection-xp-details-container");
	
	//Handle mobile
 	if($(window).width() <= 600){
 		$(".collection-edit-delete").text("DELETE");
 		$(".collection-details-total-container").hide();
 		$("#collection-add-game-search").parent().find("label").text("Search for Games to Add");
 		$(".collection-games-container").css({"margin-top":"65px"});
 	}else{
 		$(".collection-edit-random-cover, .import-list-header-add-collection").show(transition);
 	}
	
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
		ShowPopUp("<div class='row'><div class='col s12' style='position:relative;'><div class='collection-mgmt-header'>Confirm deletion of Collection <span style='font-weight:300;font-style:italic;'>"+$(".collection-details-name").text()+"</span></div></div></div><div class='row'><div class='col s12'><div class='btn collection-confirm-delete'>Yes, Delete My Collection</div></div></div>");
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

function DisplayCollectionQuickForm(element, gameid){
	var container = element.parent().parent().find(".collection-quick-add-container");
	ShowLoader(container, 'small', "<br><br><br>");
	container.show(250);
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayCollectionManagement", gameid: gameid, quickAdd: "true" },
     type: 'post',
     success: function(output) {
 		container.html(output); 
 		container.on("click",function(e){
			e.stopImmediatePropagation(); 
		});
 		$('html').click(function(){
			if(container.is(":visible")){
				container.hide(250);
				$('html').unbind();
			}
		});
 		AttachCollectionManagementEvents(gameid, "true");
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
	if($(window).width() > 600){
		$("#universalPopUp").css({"max-width":"40%"});
	}else{
		$("#universalPopUp").css({"max-width":"85%"});
	}
	ShowLoader($("#universalPopUp"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayCollectionManagement", gameid: gameid, quickAdd: "false" },
     type: 'post',
     success: function(output) {
 		$("#universalPopUp").html(output); 
 		AttachCollectionManagementEvents(gameid, "false");
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

function AttachCollectionManagementEvents(gameid, quickAdd){
	$(".frm-collection-create-btn").on("click", function(){
		var name = $(this).parent().parent().parent().find(".frm-collection-name").val();
		var desc = $(this).parent().parent().parent().find(".frm-collection-desc").val();
		ValidateCollectionName(name, desc, gameid, quickAdd);	
	});
	$(".collection-add-game-from-popup-add").on("click", function(){
		var collectionID = $(this).parent().attr("data-id");
		AddGameToCollectionFromCollectionManage(collectionID, gameid);
		$(this).html("<i class='fa fa-check'></i>");
		$(this).removeClass("collection-add-game-from-popup-add");
		$(this).addClass("collection-add-game-from-popup-remove");
	});
}

function ValidateCollectionName(collectionName, desc, gameid, quickAdd){
	$(".frm-collection-create-btn").hide(200);
	ShowLoader($(".frm-collection-validation"), 'small', "");
	$(".frm-collection-validation").show(200);
	$.ajax({ url: '../php/webService.php',
	     data: {action: "ValidateCollectionName", collectionName: collectionName },
	     type: 'post',
	     success: function(output) {
			if($.trim(output) == "NEW" && collectionName != ""){

				$.ajax({ url: '../php/webService.php',
				     data: {action: "CreateCollection", collectionName: collectionName, collectionDesc: desc, gameid: gameid },
				     type: 'post',
				     success: function(output) {
				     	var collectionID = parseInt($.trim(output));
				     	if(collectionID > 0){
				     		
				     		if(quickAdd == "false" && gameid == -1){
				     			$("#universalPopUp").closeModal();
				     			DisplayCollectionDetails(collectionID, 'UserCollection', $(".userContainer").attr("data-id"), true);
			     			}
			     			else
			     			{
				     			ShowLoader($(".collection-add-to-existing-collection-container"), 'small', "<br><br><br>");
								$.ajax({ url: '../php/webService.php',
							     data: {action: "DisplayCollectionManagement", gameid: gameid, quickAdd: quickAdd },
							     type: 'post',
							     success: function(output) {
							     	var parent = $(".collection-add-to-existing-collection-container").parent();
							     	$(".collection-add-to-existing-collection-container").remove();
							 		parent.html(output); 
							 		AttachCollectionManagementEvents(gameid, quickAdd);
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
				$(".frm-collection-validation, .collection-validation-quick").html("Collection Name must be unique per user.");
				$(".frm-collection-validation, .collection-validation-quick").show(200);
				$(".frm-collection-create-btn, .collection-validation-quick").show(200);
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
	
	if($(window).width()  < 600){
		searchResults.show(250);	
	}
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
 			if($(window).width() < 600){
 				searchResults.hide(250);
 			}
 			AddGameToCollection(collectionID, gbid);
 			$(this).removeClass("btn");
 			$(this).removeClass("add-to-collection-from-search");
 			$(this).addClass("collection-added-from-search");
 			$(this).html("Added");
 		});
 		
 		if($(window).width() < 600){
	 		$('html').click(function(){
				searchResults.hide(250);
				$('html').unbind();
			});
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
		
 		var total = parseInt($(".collection-total-counter").html());
 		$(".collection-total-counter").html(total + 1);
		
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
 			var total = parseInt($(".collection-total-counter").html());
 			$(".collection-total-counter").html(total - 1);
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
