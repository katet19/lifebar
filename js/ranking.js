function ShowRanking(){
  	ShowLoader($("#activityInnerContainer"), 'big', "<br><br><br>");
  	var windowWidth = $(window).width();
    $("#activity").css({"display":"inline-block", "left": -windowWidth});
    $("#discover, #admin, #profiledetails, #settings, #notifications, #user, #landing").css({"display":"none"});
    $("#discover, #admin, #profiledetails, #settings, #notifications, #user, #landing").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#activity").velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#gameInnerContainer").html("");
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
    ShowLoader($("#gameInnerContainer"), 'big', "<br><br><br>");
		$.ajax({ url: '../php/webService.php',
	     data: {action: "DisplayRanking" },
	     type: 'post',
	     success: function(output) {
	 		$("#activityInnerContainer").html(output);
             UpdateAccordionCounter();
             AttachFilterEvents();
              $('.dropdown-button').dropdown({
                    inDuration: 300,
                    outDuration: 225,
                    constrainWidth: false, // Does not change width of dropdown to that of the activator
                    hover: true, // Activate on hover
                    gutter: 0, // Spacing from edge
                    belowOrigin: false, // Displays dropdown below the button
                    alignment: 'left', // Displays dropdown with edge aligned to the left of button
                    stopPropagation: false // Stops event propagation
                    }
                );
             $('.collapsible').collapsible();
             //AttachDragAndDropEvents();
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

function AttachFilterEvents(){
    $(".year-dropdown-filter-item").on('click', function() { 
        $(".year-dropdown-selected").text($(this).text());
        FilterLists();
	});
    $(".genre-dropdown-filter-item").on('click', function() { 
        $(".genre-dropdown-selected").text($(this).text());
        FilterLists();
	});
    $(".platform-dropdown-filter-item").on('click', function() { 
        $(".platform-dropdown-selected").text($(this).text());
        FilterLists();
	});
}

function FilterLists(){
    var year = $(".year-dropdown-selected").text();
    var genre = $(".genre-dropdown-selected").text();
    var platform = $(".platform-dropdown-selected").text();
    if(genre.indexOf("All-Genre") != -1 && platform.indexOf("All-Platform") != -1 && year == "All-Time"){
        $(".rank-container").each(function(){
            $(this).parent().removeClass("hide-game-rank");
        });
    }else{
        $(".rank-container").each(function(){
            var genrehide = true;
            var yearhide = true;
            var platformhide = true;

            if($(this).attr("data-year").indexOf(year) != -1 || year.indexOf("All-Time") != -1){
                yearhide = false;
            }

            if($(this).attr("data-genre").indexOf(genre) != -1 || genre.indexOf("All-Genre") != -1){
                genrehide = false;
            }

            if($(this).attr("data-platform").indexOf(platform) != -1 || platform.indexOf("All-Platform") != -1){
                platformhide = false;
            }

            if(genrehide || yearhide || platformhide){
                $(this).parent().addClass("hide-game-rank");
            }else{
                $(this).parent().removeClass("hide-game-rank");
            }
        });
    }
    UpdateAccordionCounter();
}

function UpdateAccordionCounter(){
    $(".rank-modal-body").each(function(){
        var counter = 0;
        $(this).find(".rank-container").each(function(){
            if(!$(this).parent().hasClass("hide-game-rank"))
                counter++;
        });
        $(this).parent().find(".collapsible-header .rank-modal-text").text(counter);
    });
}

function AttachDragAndDropEvents(){
    var rankitems = document.querySelectorAll('.rank-item-container');
    var dragSrcEl = null;
    [].forEach.call(rankitems, function(rankitems) {
        rankitems.addEventListener('dragstart', handleDragStart, false);
        rankitems.addEventListener('dragenter', handleDragEnter, false);
        rankitems.addEventListener('dragover', handleDragOver, false);
        rankitems.addEventListener('dragleave', handleDragLeave, false);
        rankitems.addEventListener('drop', handleDrop, false);
        rankitems.addEventListener('dragend', handleDragEnd, false);
    });

    function handleDragStart(e) {
        this.style.color = '#3F51B5';
        
        dragSrcEl = this;

        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.innerHTML);
    }

    function handleDragOver(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }
        e.dataTransfer.dropEffect = 'move'; 

        return false;
    }

    function handleDragEnter(e) {
        this.classList.add('over');
    }

    function handleDragLeave(e) {
        this.classList.remove('over');
    }

    function handleDrop(e) {
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        if (dragSrcEl != this) {
            dragSrcEl.style.color = 'inherit';
            dragSrcEl.innerHTML = this.innerHTML;
            this.innerHTML = e.dataTransfer.getData('text/html');
        }
        return false;
    }

    function handleDragEnd(e) {
        [].forEach.call(rankitems, function (rankitem) {
            rankitem.classList.remove('over');
        });
    }
}

