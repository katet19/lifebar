function AttachXPEvents(){
	$(".myxp-GraphLabel").on('click', function(){
		if(!$(this).hasClass("myxp-selected-tier")){
			var oldselection = $(".myxp-selected-tier");	
			var oldcount = parseInt(oldselection.attr("data-count"));
			if(oldcount > 0)
				oldcount = oldcount - 1;
			oldselection.attr("data-count", oldcount);
			oldselection.removeClass("myxp-selected-tier tier1BG tier2BG tier3BG tier4BG tier5BG");
			$(this).addClass("myxp-selected-tier tier"+ $(this).attr("data-tier") +"BG");
			var newcount = parseInt($(this).attr("data-count"));
			newcount++;
			$(this).attr("data-count", newcount);
			var total = parseInt($(".firsttier").attr("data-total"));
			if(total > 0){
				var oldgraph = Math.ceil(oldcount / total * 70);
				var newgraph = Math.ceil(newcount / total * 70);
			}else{
				oldgraph = 0;
				newgraph = 70;
			}
			$(this).next().css({"width": newgraph+"%"});
			oldselection.next().css({"width": oldgraph+"%"});
			ValidateXPEntry();
		}
	});
	$(".myxp-GraphBar").on('click', function(){
		var tier = $(this).parent().find(".myxp-GraphLabel").attr("data-tier");
		var year = $(".myxp-tiercontainer").attr("data-year");
		DisplayTierDetails(tier, year);	
	});
	$(".myxp-delete-btn").on('click', function(){
		var subxp = $(this).attr("data-id");
		DeleteXP(subxp);	
	});
	ListenAndBuildWatchedSentence();
	ListenAndBuildPlayedSentence();
	$(".myxp-cancel").on('click', function(){
		CancelXPEntry();
	});
	$(".myxp-save").on('click', function(){
		SaveXPEntry();
	});
}

function BuildSentenceOnLoad(){
	$(".myxp-sentence-year").html(" of "+$("#myxp-year").val());
	$(".myxp-sentence-quarter").html("during "+$("input[type=radio][name=dategroup]:checked").attr("data-text"));
	
	if($("#myxp-percent-completed").length > 0){
		$(".myxp-sentence-intro").html("I played");
		if($("#myxp-percent-completed").val() == 101){
			$(".myxp-sentence-intro").html("I played");
			$(".myxp-sentence-completion").html("multiple playthroughs");
		}else if($("#myxp-percent-completed").val() == 100){
			$(".myxp-sentence-completion").html("");
			$(".myxp-sentence-intro").html("I finished");
		}else{
			$(".myxp-sentence-intro").html("I played");
			$(".myxp-sentence-completion").html("through "+$("#myxp-percent-completed").val()+"%");
		}
	}
	
	if($('.myxp-platforms').length > 0){
		$('.myxp-platforms').each(function() {
			if(this.checked){
				if($(".myxp-sentence-platform").html() !== ""){
					$(".myxp-sentence-platform").html($(".myxp-sentence-platform").html() + " " + $(this).attr("data-text"));
				}else{
					$(".myxp-sentence-platform").html("on "+$(this).attr("data-text"));
				}
			}
		});
	}
	
	$(".myxp-sentence-src").html("on "+$("#myxp-view-source").val());
	
	if($('input[type=radio][name=viewingitemgroup]').length > 0){
		$(".myxp-sentence-intro").html("I watched");
		$(".myxp-sentence-exp").html($("input[type=radio][name=viewingitemgroup]:checked").attr("data-text"));
	}
	
	if($("#myxp-form-url").val() !== "")
			$(".myxp-sentence-src-url").html("<i class='mdi-content-link'><i>");
			
	ValidateXPEntry();
}

function ListenAndBuildPlayedSentence(){
	$('select').material_select();
	$("#myxp-year").on('change', function() { 
		$(".myxp-sentence-year").html(" of "+$(this).val());
		ValidateXPEntry();
	});
	$("input[type=radio][name=dategroup]").change(function() {
		$(".myxp-sentence-quarter").html("during "+$(this).attr("data-text"));	
		ValidateXPEntry();
	});
	$('.myxp-platforms').change(function() {
		if(this.checked){
			$(".myxp-sentence-intro").html("I played");
			if($(".myxp-sentence-platform").html() !== ""){
				$(".myxp-sentence-platform").html($(".myxp-sentence-platform").html() + " " + $(this).attr("data-text"));
			}else{
				$(".myxp-sentence-platform").html("on "+$(this).attr("data-text"));
			}
		}else{
			var remove = $(this).attr("data-text");
			$(".myxp-sentence-platform").html($(".myxp-sentence-platform").html().replace(remove, ""));
			if($.trim($(".myxp-sentence-platform").html()) == "on")
				$(".myxp-sentence-platform").html("");
		}
		ValidateXPEntry();
	});
	$("#myxp-percent-completed").on("change", function() {
		if($(this).val() == 101){
			$(".myxp-sentence-intro").html("I played");
			$(".myxp-sentence-completion").html("multiple playthroughs");
		}else if($(this).val() == 100){
			$(".myxp-sentence-completion").html("");
			$(".myxp-sentence-intro").html("I finished");
		}else{
			$(".myxp-sentence-intro").html("I played");
			$(".myxp-sentence-completion").html("through "+$(this).val()+"%");
		}
		$(".myxp-sentence-year").html(" of "+$("#myxp-year").val());
		$(".myxp-sentence-quarter").html("during "+$("input[type=radio][name=dategroup]:checked").attr("data-text"));
		ValidateXPEntry();
	});
	
}

function ListenAndBuildWatchedSentence(){
	$('select').material_select();
	$("#myxp-view-source").on('change', function() { 
		if($(this).val() == "YouTube" || $(this).val() == "Twitch" || $(this).val() == "UStream" || $(this).val() == "Other"){
			$("#myxp-source-link").parent().css({"display":"block"});
		}else{
			$("#myxp-source-link").parent().css({"display":"none"});
		}
		$(".myxp-sentence-src").html("on "+$(this).val());
	});
	$('input[type=radio][name=viewingitemgroup]').change(function() {
		$(".myxp-sentence-intro").html("I watched");
		$(".myxp-sentence-exp").html($(this).attr("data-text"));
		$(".myxp-sentence-year").html(" of "+$("#myxp-year").val());
		$(".myxp-sentence-quarter").html("during "+$("input[type=radio][name=dategroup]:checked").attr("data-text"));
		if($("#myxp-view-source").val() !== "")
			$(".myxp-sentence-src").html("on "+$("#myxp-view-source").val());
		ValidateXPEntry();
	});
	$("#myxp-year").on('change', function() { 
		$(".myxp-sentence-year").html(" of "+$(this).val());
		ValidateXPEntry();
	});
	$("input[type=radio][name=dategroup]").change(function() {
		$(".myxp-sentence-quarter").html("during "+$(this).attr("data-text"));
		ValidateXPEntry();
	});
	$("#myxp-form-url").on('keyup', function(){
		if($(this).val() !== "")
			$(".myxp-sentence-src-url").html("<i class='mdi-content-link'><i>");
		else
			$(".myxp-sentence-src-url").html("");
	});
	$("#myxp-source-link").on('keyup', function(){
		if($(this).val() !== "")
			$(".myxp-sentence-src").html("on "+$(this).val() +"'s channel");
		else
			$(".myxp-sentence-src").html("");
	});
}


function AddWatchedFabEvent(){
	HideFab();
	var gameid = $("#gameContentContainer").attr("data-id");
	ShowLoader($("#game-myxp-tab"), 'big', "<br><br><br>");
	$(".userGameTab").show();
	$(".userGameTab a").trigger('click');
	$.ajax({ url: '../php/webService.php',
         data: {action: "DisplayAddWatched", gameid: gameid },
         type: 'post',
         success: function(output) {
         	$("#game-myxp-tab").html(output);
         	$("#myxp-quote").focus();
         	AttachXPEvents();
         	window.scrollTo(0, 10);
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

function AddPlayedFabEvent(){
	HideFab();
	$(".userGameTab").show();
	$(".userGameTab a").trigger('click');
	ShowLoader($("#game-myxp-tab"), 'big', "<br><br><br>");
	var gameid = $("#gameContentContainer").attr("data-id");
	$.ajax({ url: '../php/webService.php',
         data: {action: "DisplayAddPlayed", gameid: gameid },
         type: 'post',
         success: function(output) {	
         	$("#game-myxp-tab").html(output);
         	$("#myxp-quote").focus();
         	AttachXPEvents();
         	window.scrollTo(0, 10);
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

function UpdatePlayedEvent(){
	HideFab();
	ShowLoader($("#game-myxp-tab"), 'big', "<br><br><br>");
	var gameid = $("#gameContentContainer").attr("data-id");
	$.ajax({ url: '../php/webService.php',
         data: {action: "DisplayUpdatePlayed", gameid: gameid },
         type: 'post',
         success: function(output) {	
         	$("#game-myxp-tab").html(output);
         	AttachXPEvents();
         	BuildSentenceOnLoad();
         	window.scrollTo(0, 10);
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

function UpdateWatchedEvent(watchid){
	HideFab();
	ShowLoader($("#game-myxp-tab"), 'big', "<br><br><br>");
	var gameid = $("#gameContentContainer").attr("data-id");
	$.ajax({ url: '../php/webService.php',
         data: {action: "DisplayUpdateWatched", gameid: gameid, watchid: watchid },
         type: 'post',
         success: function(output) {	
         	$("#game-myxp-tab").html(output);
         	AttachXPEvents();
         	BuildSentenceOnLoad();
         	window.scrollTo(0, 10);
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

function UpdateTierQuoteEvent(){
	HideFab();
	ShowLoader($("#game-myxp-tab"), 'big', "<br><br><br>");
	var gameid = $("#gameContentContainer").attr("data-id");
	$.ajax({ url: '../php/webService.php',
         data: {action: "DisplayTierQuote", gameid: gameid },
         type: 'post',
         success: function(output) {	
         	$("#game-myxp-tab").html(output);
         	AttachXPEvents();
         	ValidateXPEntry();
         	window.scrollTo(0, 10);
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

function CancelXPEntry(){
	var gameid = $("#gameContentContainer").attr("data-id");
	ShowLoader($("#game-myxp-tab"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
         data: {action: "DisplayMyXP", gameid: gameid },
         type: 'post',
         success: function(output) {
         	if($.trim(output) !== ""){
         		$("#game-myxp-tab").html(output);
         		ShowFab();
         	}else{
         		$(".userGameTab").hide();
         		$(".criticGameTab a").trigger('click');
         		ShowFab();
         	}
         	AttachEditEvents();
         	window.scrollTo(0, 0);
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

function HideFab(){
	$("#game-fab").hide();
}

function ShowFab(){
	$("#game-fab").show();
}

function DisplayTierDetails(tier, year){
	ShowLoader($("#universalPopUp"), 'big', "<br>")
	$("#universalPopUp").openModal();
	$.ajax({ url: '../php/webService.php',
         data: {action: "GetTierBreakdownYearTier", year: year, tier: tier  },
         type: 'post',
         success: function(output) {
			$("#universalPopUp").html(output);
		  	$(".closeDetailsModal").unbind();
		  	$(".closeDetailsModal").on('click', function(){
		  		$("#universalPopUp").closeModal();
		  		HideFocus();
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

function ValidateXPEntry(){
	var validation = "";
	if($("#myxp-quote").length > 0){
		if($.trim($("#myxp-quote").val()) == "")
			validation = validation + "<li style='text-align:left;'><i class='mdi-alert-warning'></i> Summary of your experience required</li>";
	}
	if($(".myxp-tiercontainer").length > 0){
		if($(".myxp-selected-tier").length == 0)
			validation = validation + "<li style='text-align:left;'><i class='mdi-alert-warning'></i> Selecting a tier 1 - 5 is requried</li>";
	}
	if($("#myxp-percent-completed").length > 0){
		if($("#myxp-percent-completed").val() == "0")
			validation = validation + "<li style='text-align:left;'><i class='mdi-alert-warning'></i> Selecting a percentage completed is required</li>";
	}
	if($(".myxp-platforms").length > 0){
		var found = 0;
		$(".myxp-platforms").each(function(){
			if(this.checked)
				found++;
		});
		if(found == 0)
			validation = validation + "<li style='text-align:left;'><i class='mdi-alert-warning'></i> Selecting a platform played is required</li>";
	}
	if($('input[type=radio][name=viewingitemgroup]').length > 0){
		if($('input[type=radio][name=viewingitemgroup]:checked').length == 0)
			validation = validation + "<li style='text-align:left;'><i class='mdi-alert-warning'></i> Selecting a viewing experience is required</li>";
	}
	
	if(validation == "")
		$(".myxp-save").removeClass("disabled");
	else if(!$("#myxp-save").hasClass("disabled"))
		$(".myxp-save").addClass("disabled");
		
	return validation;
}

function SaveXPEntry(){
	if(!$(".myxp-save").hasClass("disabled")){
		$(".myxp-save").html("<div class='preloader-wrapper small active' style='vertical-align:text-top;margin-right:1em; width:15px; height:15px;'><div class='spinner-layer spinner-white-only'><div class='circle-clipper left'><div class='circle' style='border-width:2px;'></div></div><div class='gap-patch'><div class='circle' style='border-width:2px;'></div></div><div class='circle-clipper right'><div class='circle' style='border-width:2px;'></div></div></div></div> <span class='myxp-saving-label'>Saving XP</span>");
		var gameid = $("#gameContentContainer").attr("data-id");
		var quote = $("#myxp-quote").val();
		var tier = $(".myxp-selected-tier").attr("data-tier");
		var platforms = [];
		$('.myxp-platforms').each(function() {
			if(this.checked){
				platforms.push($(this).attr("data-text"));
			}
		});
		var completion = $("#myxp-percent-completed").val();
		var year = $("#myxp-year").val();
		var quarter = $("input[type=radio][name=dategroup]:checked").attr("id");
		var single = false;
		if($('#singleplayer:checked').length > 0)
			single = true;
		var multi = false;
		if($('#multiplayer:checked').length > 0)
			multi = true;
		var alpha = 0;
		if($('#alpha:checked').length > 0)
			alpha = 1;
		var beta = 0;
		if($('#beta:checked').length > 0)
			beta = 1;
		var early = 0;
		if($('#earlyaccess:checked').length > 0)
			early = 1;
		var demo = 0;
		if($('#demo:checked').length > 0)
			demo = 1;
		var dlc = false;
		if($('#dlc:checked').length > 0)
			dlc = true;
		var stream = false;
		if($('#streamed:checked').length > 0)
			stream = true;
		var viewing = $("input[type=radio][name=viewingitemgroup]:checked").attr("id");
		var viewsrc = $("#myxp-view-source").val();
		if((viewsrc == "YouTube" || viewsrc == "Twitch" || viewsrc == "UStream" || viewsrc == "Other") && $.trim($("#myxp-source-link").val()) != "")
			viewsrc = $("#myxp-source-link").val();
		var viewurl = $("#myxp-form-url").val();
		
		if($("#myxp-quote").length > 0 && $(".myxp-platforms").length > 0){
			$.ajax({ url: '../php/webService.php',
		         data: {action: "SavePlayedFull", gameid: gameid, quote: quote, tier: tier, platforms: platforms, completion: completion, year: year, quarter: quarter, single: single, multi: multi, alpha: alpha, beta: beta, early: early, demo: demo, dlc: dlc, stream: stream  },
		         type: 'post',
		         success: function(output) {
	         		DisplayBattleProgress(output);
	         		GetGameFAB();
		         	window.scrollTo(0, 0);
		         	AttachEditEvents();
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
		}else if($("#myxp-quote").length > 0 && $('input[type=radio][name=viewingitemgroup]').length > 0){
			$.ajax({ url: '../php/webService.php',
		         data: {action: "SaveWatchedFull", gameid: gameid, quote: quote, tier: tier, viewing: viewing, viewsrc: viewsrc, viewurl: viewurl, year: year, quarter: quarter  },
		         type: 'post',
		         success: function(output) {
	         		DisplayBattleProgress(output);
	         		GetGameFAB();
		         	window.scrollTo(0, 0);
		         	AttachEditEvents();
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
		}else if($(".myxp-platforms").length > 0){
			$.ajax({ url: '../php/webService.php',
		         data: {action: "SavePlayed", gameid: gameid, platforms: platforms, completion: completion, year: year, quarter: quarter, single: single, multi: multi, alpha: alpha, beta: beta, early: early, demo: demo, dlc: dlc, stream: stream  },
		         type: 'post',
		         success: function(output) {
	         		DisplayBattleProgress(output);
	         		GetGameFAB();
		         	window.scrollTo(0, 0);
		         	AttachEditEvents();
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
		}else if($('input[type=radio][name=viewingitemgroup]').length > 0){
			var update = $(".myxp-edit-container").attr("data-watchid");
			if(update > 0){
				$.ajax({ url: '../php/webService.php',
			         data: {action: "UpdateWatched", subxpid: update, gameid: gameid, viewing: viewing, viewsrc: viewsrc, viewurl: viewurl, year: year, quarter: quarter  },
			         type: 'post',
			         success: function(output) {
			         	DisplayBattleProgress(output);
		         		$("#game-myxp-tab").html(output);
		         		GetGameFAB();
			         	window.scrollTo(0, 0);
			         	AttachEditEvents();
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
			}else{
				$.ajax({ url: '../php/webService.php',
			         data: {action: "SaveWatched", gameid: gameid, viewing: viewing, viewsrc: viewsrc, viewurl: viewurl, year: year, quarter: quarter  },
			         type: 'post',
			         success: function(output) {
		         		DisplayBattleProgress(output);
		         		GetGameFAB();
			         	window.scrollTo(0, 0);
			         	AttachEditEvents();
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
		}else if($("#myxp-quote").length > 0){
			$.ajax({ url: '../php/webService.php',
		         data: {action: "SaveTierQuote", gameid: gameid, quote: quote, tier: tier  },
		         type: 'post',
		         success: function(output) {
	         		DisplayBattleProgress(output);
	         		AttachEditEvents();
					Waves.displayEffect();
		         	window.scrollTo(0, 0);
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

function DisplayBattleProgress(content){
	var contentsplit = $.trim(content).split("|**|");
	ShowBattleProgress(contentsplit[0]);
	$("#game-myxp-tab").html(contentsplit[1]);
}

function AttachBPEvents(){
	$(".bp-action-close").on('click', function(){
  		$("#BattleProgess").closeModal();
  		HideFocus();	
	});
	$(".bp-related-quests-image").on('click', function(){
  		$("#BattleProgess").closeModal();
  		HideFocus();
		ShowGame($(this).attr("data-gbid"), $("#discover"));
	});
	var timeoutcounter = 750;
	$(".bp-progress-item-bar").each(function(){
		var after = $(this).find(".bp-progress-item-bar-after");
		var before = $(this).find(".bp-progress-item-bar-before");
		var lvlup = $(this).parent().find(".bp-progress-item-levelup");
		//var left = after.attr("data-left");
		//after.css({"left":left});
		setTimeout(function(e){
			var width = after.attr("data-width");
			after.css({"width":width});
			if(lvlup.attr("data-levelup") == "Yes"){
				lvlup.html("LEVEL UP!");
				setTimeout(function(e){
					before.css({"background-color":"#66BB6A"});
					after.css({"background-color":"#66BB6A"});
					lvlup.html("Level " + lvlup.attr("data-newlevel"));
				}, 1000);
			}
		}, timeoutcounter);
		if(lvlup.attr("data-levelup") == "Yes")
			timeoutcounter = timeoutcounter + 2000;
		else
			timeoutcounter = timeoutcounter + 1000;
	});
	AttachEquipXPEvents($(".bp-container").attr("data-gameid"));
	$(".bp-container").unbind("scroll");
	$(".bp-container").on("scroll", function(){
		if($(".bp-container").scrollTop() > 5){
			$(".bp-header").addClass("bp-header-min");
			$(".bp-container").addClass("bp-container-min");
			$(".bp-top-row").addClass("bp-top-row-min");
			$(".lifebar-avatar-min").addClass("lifebar-avatar-min-min");
			$(".lifebar-dots-min").addClass("lifebar-dots-min-min");
			$(".lifebar-bar-container-min").addClass("lifebar-bar-container-min-min");
			$(".lifebar-username-min").addClass("lifebar-username-min-min");
			$(".lifebar-circle-fill").addClass("lifebar-circle-fill-min");
			setTimeout(function(){
				if($(".bp-container").scrollTop() > 5){
					//$(".lifebar-dots-min").hide();
					//$(".lifebar-bar-container-min").hide();
				}
			}, 500);
		}else{
			$(".bp-header").removeClass("bp-header-min");
			$(".bp-container").removeClass("bp-container-min");
			$(".bp-top-row").removeClass("bp-top-row-min");
			//$(".lifebar-dots-min").show(200);
			//$(".lifebar-bar-container-min").show(200);
			$(".lifebar-avatar-min").removeClass("lifebar-avatar-min-min");
			$(".lifebar-dots-min").removeClass("lifebar-dots-min-min");
			$(".lifebar-bar-container-min").removeClass("lifebar-bar-container-min-min");
			$(".lifebar-username-min").removeClass("lifebar-username-min-min");
			$(".lifebar-circle-fill").removeClass("lifebar-circle-fill-min");
		}
	});
}

function DeleteXP(subxpid){
	var gameid = $("#gameContentContainer").attr("data-id");
	HideFab();
	if($("#myxp-quote").length > 0){
		$.ajax({ url: '../php/webService.php',
	         data: {action: "RemoveEntireExperience", gameid: gameid  },
	         type: 'post',
	         success: function(output) {
         		$(".userGameTab").hide();
         		$(".criticGameTab a").trigger('click');
	         	AttachEditEvents();
	         	window.scrollTo(0, 0);
         		Toast("Removed All XP");
         		ShowXPDown();
         		ShowFab();
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
		$.ajax({ url: '../php/webService.php',
	         data: {action: "RemoveSubExperience", gameid: gameid, subxpid: subxpid  },
	         type: 'post',
	         success: function(output) {
         		$("#game-myxp-tab").html(output);
         		Toast("Removed XP");
         		AttachEditEvents();
				Waves.displayEffect();
	         	window.scrollTo(0, 0);
	         	ShowFab();
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

function CalculateWeave(){
	$.ajax({ url: '../php/webService.php',
         data: {action: "CalculateWeave" },
         type: 'post',
         success: function() {
         },
        error: function(x, t, m) {
	        if(t==="timeout") {
	            //ToastError("Server Timeout");
	        } else {
	            //ToastError(t);
	        }
    	},
    	timeout:450000
	});
}

function GetGameFAB(){
	var gameid = $("#gameContentContainer").attr("data-id");
	$.ajax({ url: '../php/webService.php',
         data: {action: "GetGameFAB", gameid: gameid  },
         type: 'post',
         success: function(output) {
     		$("#game-fab").html(output);
     		ShowFab();
 			var iconOnHover="";
			if($(".fixed-action-btn .game-add-played-btn").length > 0)
				iconOnHover = "mdi-hardware-gamepad";
			else if($(".fixed-action-btn .game-add-watched-btn").length > 0)
				iconOnHover = "mdi-action-visibility";
			else
				iconOnHover = "mdi-action-bookmark";
				
     		AttachFloatingIconEvent(iconOnHover);
			AttachFloatingIconButtonEvents();
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

function ShowXPUp(){
	var count = parseInt($(".userTotalXPLabel").html(), 10);
	count = count + 1;
	$(".userTotalXPLabel").html(count);
}

function ShowXPDown(){
	var count = parseInt($(".userTotalXPLabel").html(), 10);
	count = count - 1;
	$(".userTotalXPLabel").html(count);
}
