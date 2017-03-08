function ShowRanking(){
  	ShowLoader($("#rankingInnerContainer"), 'big', "<br><br><br>");
  	var windowWidth = $(window).width();
    $("#ranking").css({"display":"inline-block", "left": -windowWidth});
    $("#discover, #activity, #admin, #profiledetails, #settings, #notifications, #user, #landing").css({"display":"none"});
    $("#discover, #activity, #admin, #profiledetails, #settings, #notifications, #user, #landing").velocity({ "left": windowWidth }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#ranking").velocity({ "left": 0 }, {duration: 200, queue: false, easing: 'easeOutQuad'});
	$("#rankingInnerContainer").html("");
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
    ShowLoader($("#rankingInnerContainer"), 'big', "<br><br><br>");
		$.ajax({ url: '../php/webService.php',
	     data: {action: "DisplayRanking" },
	     type: 'post',
	     success: function(output) {
	 		$("#rankingInnerContainer").html(output);
             UpdateAccordionCounter(true);
             if($(".rank-header-title-count").text() != "0"){
                 ToggleUnrankedModal();
             }
             $(".rank-header-title").on("click", function(){
                ToggleUnrankedModal();
             });
             $(".rank-save-btn").on("click", function(){
                SaveRankedList();
             });
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
             AttachDragAndDropEvents();
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

function SaveRankedList(){
    if(!$(".rank-save-btn").hasClass("disabled")){
        var rankedList = "";
        $(".rank-list-container").find(".rank-container").each(function(){
            var gameid = $(this).attr("data-id");
            if(gameid > 0){
                rankedList = rankedList + gameid + ",";
            }
        });

        $.ajax({ url: '../php/webService.php',
            data: {action: 'SaveUserRankedList', rankedList: rankedList },
            type: 'post',
            success: function(output) {
                Toast("Saved your Rankings");
                $(".rank-save-btn").addClass("disabled");
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

function ToggleSaveRankedBtn(){
    if($(".rank-list-container").find(".rank-container").length > 1){
        $(".rank-save-btn").removeClass("disabled");
    }else{
        $(".rank-save-btn").addClass("disabled");
    }
}

function ToggleUnrankedModal(){
    if($(".rank-unranked-list-container-active").length > 0){
        $(".rank-unranked-list-container-active").removeClass("rank-unranked-list-container-active");
        $(".rank-unranked-list-container .rank-header-title i").text("expand_less");
    }else{
        $(".rank-unranked-list-container").addClass("rank-unranked-list-container-active");
        $(".rank-unranked-list-container .rank-header-title i").text("expand_more");
    }
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
    $(".xp-dropdown-filter-item").on('click', function() { 
        $(".xp-dropdown-selected").text($(this).text());
        FilterLists();
	});
}

function UpdateRankedPositions(showingAll){
    var localcount = 1;
    var globalcount = 1;
    $(".rank-list-container").find(".rank-container").each(function(){
        if(!$(this).hasClass("rank-drag-drop-placeholder") && !$(this).hasClass("hide-game-rank")){
            if(showingAll){
                $(this).find(".rank-count-container").html("<div class='rank-count-main'>" + localcount + "</div>");
            }else{
                $(this).find(".rank-count-container").html("<div class='rank-count-main'>" + localcount + "</div> <div class='rank-count-secondary'>" + globalcount + "</div>");
            }

            var image = $(this).attr("data-image");
            $(this).find(".rank-image").css({"background":"url("+ image +") 50% 25%","background-size":"cover"});
            $(this).find(".rank-item-title").css({"padding-left":"130px"});

            var lastTime = parseInt($(this).attr("data-loaded-rank"));
            if(lastTime == 0){
                $(this).find(".rank-history").html("<span class='rank-history-new'>NEW</span>");
            }else if(lastTime > globalcount){
                var diff = lastTime - globalcount;
                $(this).find(".rank-history").html("<span class='rank-history-green'><i class='material-icons left' style='margin-right: 5px;'>keyboard_arrow_up</i>"+ diff +"</span>");
            }else if(lastTime < globalcount){
                var diff = globalcount - lastTime;
                $(this).find(".rank-history").html("<span class='rank-history-red'><i class='material-icons left' style='margin-right: 5px;'>keyboard_arrow_down</i> "+ diff  +"</span>");
            }else{
                $(this).find(".rank-history").html("");
            }

            localcount++;
        }
        globalcount++;
    });

    if(localcount == 1){
        $(".rank-drag-drop-placeholder").find(".rank-drag-drop-text").text("DRAG HERE TO START LIST");
    }else{
        $(".rank-drag-drop-placeholder").find(".rank-drag-drop-text").text("DRAG HERE TO ADD TO THE BOTTOM OF LIST");
    }
}

function FilterLists(){
    var year = $(".year-dropdown-selected").text();
    var genre = $(".genre-dropdown-selected").text();
    var platform = $(".platform-dropdown-selected").text();
    var xp = $(".xp-dropdown-selected").text();
    var showingAll = true;
    if(genre.indexOf("All-Genre") != -1 && platform.indexOf("All-Platform") != -1 && year == "All-Time" && xp == "All-Experiences"){
        $(".rank-container").each(function(){
            $(this).removeClass("hide-game-rank");
        });
    }else{
        showingAll = false;
        $(".rank-container").each(function(){
            var genrehide = true;
            var yearhide = true;
            var platformhide = true;
            var xphide = true;

            if($(this).attr("data-year").indexOf(year) != -1 || year.indexOf("All-Time") != -1){
                yearhide = false;
            }

            if($(this).attr("data-genre").indexOf(genre) != -1 || genre.indexOf("All-Genre") != -1){
                genrehide = false;
            }

            if($(this).attr("data-platform").indexOf(platform) != -1 || platform.indexOf("All-Platform") != -1){
                platformhide = false;
            }

            if($(this).attr("data-xp").indexOf(xp) != -1 || xp.indexOf("All-Experiences") != -1){
                xphide = false;
            }

            if(genrehide || yearhide || platformhide || xphide){
                $(this).addClass("hide-game-rank");
            }else{
                $(this).removeClass("hide-game-rank");
            }
        });

        $(".rank-drag-drop-placeholder").removeClass("hide-game-rank");
    }
    UpdateAccordionCounter(showingAll);
}

function UpdateAccordionCounter(showingAll){
    var totalcount = 0;
    $(".rank-modal-body").each(function(){
        var counter = 0;
        $(this).find(".rank-container").each(function(){
            if(!$(this).hasClass("hide-game-rank")){
                counter++;
                totalcount++;
            }
        });
        $(this).parent().find(".collapsible-header .rank-modal-text").text(counter);
        $(".rank-header-title-count").text(totalcount.toLocaleString('en-US'));
    });
    UpdateRankedPositions(showingAll);
}

function AttachDragAndDropEvents(){
    var rankitems = document.querySelectorAll('.rank-container');
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
        dragSrcEl = this;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.innerHTML);
    }

    function handleDragOver(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }
        e.dataTransfer.dropEffect = 'move'; 
        this.classList.add('overbottom');

        return false;
    }

    function handleDragEnter(e) {
        this.classList.add('overbottom');
    }

    function handleDragLeave(e) {
        this.classList.remove('overbottom');
    }

    function handleDrop(e) {
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        if (dragSrcEl != this) {
            dragSrcEl.style.color = 'inherit';
            this.parentNode.insertBefore(dragSrcEl, this);

            dragSrcEl.className += " rank-move-highlight";

            setTimeout(function(){ dragSrcEl.classList.remove("rank-move-highlight"); }, 750);

            var year = $(".year-dropdown-selected").text();
            var genre = $(".genre-dropdown-selected").text();
            var platform = $(".platform-dropdown-selected").text();
            var xp = $(".xp-dropdown-selected").text();
            var showingAll = false;
            if(genre.indexOf("All-Genre") != -1 && platform.indexOf("All-Platform") != -1 && year == "All-Time" && xp == "All-Experiences"){
                showingAll = true;
            }

            UpdateAccordionCounter(showingAll);
            ToggleSaveRankedBtn();
        }
        return false;
    }

    function handleDragEnd(e) {
        [].forEach.call(rankitems, function (rankitem) {
            rankitem.classList.remove('overbottom');
        });
    }
}

