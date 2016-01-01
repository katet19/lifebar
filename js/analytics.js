function ShowAnalyticsHome(){
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
	ShowAnalyticsMainContent();
	ShowAnalyticsSecondaryContent();
}

function ShowAnalyticsMainContent(){
  	ShowLoader($("#analyticsInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayAnalytics" },
     type: 'post',
     success: function(output) {
     			$("#analyticsInnerContainer").html(output);
				//Attach Events
      			Waves.displayEffect();
      			DisplayTierChart();
      			DisplayPlatformsDoughnutGraph();
      			DisplayLifeTimeChart();
  	 			$(".analytics-overall-leaders").on("click", function(e){
 			 		e.stopPropagation();
 					ShowUserPreviewCard($(this).find(".user-preview-card"));
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

function ShowAnalyticsSecondaryContent(){
  	ShowSideLoader();
  	ClearSideContent();
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayAnalyticsSecondaryContent" },
     type: 'post',
     success: function(output) {
     			$("#sideContainer").html(output);
				//AttachEvents
				AttachAnalyticsNavigation();
     			SideContentPush($("#sideContainer").html());
     			SideContentEventPush(AttachAnalyticsNavigation);
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

function AttachAnalyticsNavigation(){
	$("#analytics-personal").on('click', function(e){
		$(".analytics-category-selected").removeClass("analytics-category-selected");
		$(this).addClass("analytics-category-selected");
		ShowUserAnalytics();
	});
	$("#analytics-global").on('click', function(e){
		$(".analytics-category-selected").removeClass("analytics-category-selected");
		$(this).addClass("analytics-category-selected");
		ShowPolygonalAnalytics();
	});
}

function ShowUserAnalytics(){
  	ShowLoader($("#analyticsInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayUserAnalytics" },
     type: 'post',
     success: function(output) {
     			$("#analyticsInnerContainer").html(output);
				//Attach Events
      			Waves.displayEffect();
      			DisplayTierChart();
      			DisplayPlatformsDoughnutGraph();
      			DisplayLifeTimeChart();
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

function ShowPolygonalAnalytics(){
  	ShowLoader($("#analyticsInnerContainer"), 'big', "<br><br><br>");
	$.ajax({ url: '../php/webService.php',
     data: {action: "DisplayPolygonalAnalytics" },
     type: 'post',
     success: function(output) {
     			$("#analyticsInnerContainer").html(output);
				//Attach Events
      			Waves.displayEffect();
      			DisplayTierChart();
      			DisplayPlatformsDoughnutGraph();
      			DisplayLifeTimeChart();
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

function DisplayTierChart(){
	if($(".GraphCriticUsers").length > 0){
		$(".GraphCriticUsers").each(function(){
			var experiencedUsersGraph = $(this).get(0).getContext("2d");
			var data = {
		    labels: ["Tier 1", "Tier 2", "Tier 3", "Tier 4", "Tier 5"],
		    datasets: [
		        {
		            label: "Lifetime XP",
		            fillColor: "rgba(255, 0, 97,0.1)",
		            strokeColor: "rgb(255, 0, 97)",
		            pointColor: "rgb(255, 0, 97)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(71,71,71,1)",
		            data: [$(this).attr("data-t1"), $(this).attr("data-t2"), $(this).attr("data-t3"), $(this).attr("data-t4"), $(this).attr("data-t5")]
		        }
		    ]
		};
		$(this).attr('width', $(this).parent().width()-10);
        $(this).attr('height', 250);
		var temp = new Chart(experiencedUsersGraph).Line(data, { datasetStrokeWidth : 4, showScale: true, bezierCurve : true, pointDot : false, scaleShowGridLines : false, multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>", animation: true });
      	
		});
	}
}

function DisplayLifeTimeChart(){
	if($(".GraphLifeTime").length > 0){
		$(".GraphLifeTime").each(function(){
			var lifetimeGraph = $(this).get(0).getContext("2d");
			var dataBirth = $(this).attr("data-birth");
      		var birthArray = dataBirth.split(",");
  			var dataPlayed = $(this).attr("data-played");
      		var playedArray = dataPlayed.split(",");
  			var dataWatched = $(this).attr("data-watched");
      		var watchedArray = dataWatched.split(",");
      		var allLabels = [];
      		var allPlayed = [];
      		var allWatched = [];
	      	$.each( birthArray, function( key, value ) {
	      		allLabels.push(value);
	      	});
  	      	$.each( playedArray, function( key, value ) {
	      		allPlayed.push(value);
	      	});
  	      	$.each( watchedArray, function( key, value ) {
	      		allWatched.push(value);
	      	});
			
			var data = {
		    labels: allLabels,
		    datasets: [
		        {
		            label: "Played XP",
		            fillColor: "rgba(156, 39, 176, 0.1)",
		            strokeColor: "rgb(156, 39, 176)",
		            pointColor: "rgb(156, 39, 176)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(71,71,71,1)",
		            data: allPlayed
		        },
		        {
		            label: "Watched XP",
		            fillColor: "rgba(3, 169, 244, 0.1)",
		            strokeColor: "rgb(3, 169, 244)",
		            pointColor: "rgb(3, 169, 244)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(71,71,71,1)",
		            data: allWatched
		        }
		    ]
		};
		$(this).attr('width', $(this).parent().width()-10);
        $(this).attr('height', 250);
		var temp = new Chart(lifetimeGraph).Line(data, { datasetStrokeWidth : 4, showScale: true, bezierCurve : true, pointDot : false, scaleShowGridLines : false, multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>", animation: true });
      	
		});
	}
}

function DisplayPlatformsDoughnutGraph(){
	if($("#allPlatforms").length > 0){
      	var allPlatforms = $("#allPlatforms").get(0).getContext("2d");
      	var dataPlatforms = $("#allPlatforms").attr("data-platforms");
      	var platformArray = dataPlatforms.split(",");
      	var allLabels = [];
      	var allTotals = [];
      	var count = 0;
      	var otherTotal = 0;
      	$.each( platformArray, function( key, value ) {
      		var parts = value.split(":");
		    count++;
		    if(count >= 5){
		    	otherTotal++;
		    }else{
			    allLabels.push(parts[0]);
			    allTotals.push(parseInt(parts[1]));
		    }
		});
		    
	     var allPlatformData = [
		    {
		        value: allTotals[0],
		        color: "rgba(100,152,157,1)",
		        highlight: "rgba(100,152,157,0.9)",
		        label: allLabels[0]
		    },
		    {
		        value: allTotals[1],
				color: "rgba(153,199,186,1)",
		        highlight: "rgba(153,199,186,0.9)",
		        label: allLabels[1]
		    },
		    {
		        value: allTotals[2],
				color: "rgba(208,231,200,1)",
		        highlight: "rgba(208,231,200,0.9)",
		        label: allLabels[2]
		    },
		    {
		        value: allTotals[3],
				color: "rgba(250,251,213,1)",
		        highlight: "rgba(250,251,213,0.9)",
		        label: allLabels[3]
		    },
		    {
		        value: otherTotal,
		        color:"rgba(95,93,102,1)",
		        highlight: "rgba(95,93,102,0.9)",
		        label: "Other"
		    }
		];
		$("#allPlatforms").attr('height', 300);
		var allPlatformsBarChart = new Chart(allPlatforms).Doughnut(allPlatformData, { showTooltips: true });
	}
}
