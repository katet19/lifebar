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
            $(".rank-container").hover(function(){ 
                    $(this).addClass("rank-container-hover");
                }, function(){ 
                    $(this).removeClass("rank-container-hover");
                }
            );
             UpdateAccordionCounter(true, false);
             if($(".rank-header-title-count").text() != "0" && $(window).width() > 599){
                 ToggleUnrankedModal();
             }
             $(".rank-unranked-list-container .rank-header-title").on("click", function(){
                ToggleUnrankedModal();
             });
             $(".rank-filter-list-container .rank-header-title").on("click", function(){
                ToggleFilterModal();
             });
             $(".rank-save-btn").on("click", function(){
                SaveRankedList();
             });
             $(".rank-container").on("click", function(){
                ToggleGameRankSelection($(this));
             });
            $(".rank-image").on("click", function(e){
                e.stopPropagation(); 
                ShowGame($(this).parent().attr("data-gbid"), $("#discover")); 
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
        var count = 1;
        $(".rank-list-container").find(".rank-container").each(function(){
            var gameid = $(this).attr("data-id");
            var change = $(this).attr("data-history");
            if(change == undefined)
                change = "";

            if(gameid > 0){
                rankedList = rankedList + gameid + "||" + count + "||" + change + ",";
            }
            $(this).attr("data-loaded-rank", count);
            count++;
        });

        $.ajax({ url: '../php/webService.php',
            data: {action: 'SaveUserRankedList', rankedList: rankedList },
            type: 'post',
            success: function(output) {
                Toast("Saved your Rankings");
                $(".rank-save-btn").addClass("disabled");
                $(".rank-history").html("");
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

function ToggleGameRankSelection(game){
    if(!game.hasClass("rank-drag-drop-placeholder")){
        if(game.hasClass('active')){
            $(".rank-remove-btn").hide();
            game.removeClass("active");
            $(".rank-list-insert-btn").removeClass("rank-list-insert-btn-expand");
            $(".rank-current-selection").removeClass("rank-current-selection-active");
            $(".rank-save-btn").removeClass("rank-save-btn-shift-up");
            setTimeout(function(){ $(".rank-list-insert-btn").remove(); }, 300);
            if($(".rank-drag-drop-placeholder").find(".rank-drag-drop-text").text() == "ADD TO THE END OF THE LIST"){
                $(".rank-drag-drop-placeholder").find(".rank-drag-drop-text").text("DRAG HERE TO ADD TO THE BOTTOM OF LIST");
            }else{
                $(".rank-drag-drop-placeholder").find(".rank-drag-drop-text").text("DRAG GAME HERE TO START LIST");
            }
        }else{
            $(".rank-container.active").removeClass("active");
            var currentID = -1;
            $(".rank-list-insert-btn").remove();
            $(".rank-current-selection").text(game.find(".rank-item-title").text());
            $(".rank-current-selection").addClass("rank-current-selection-active");
            $(".rank-save-btn").addClass("rank-save-btn-shift-up");
            if($(".rank-drag-drop-placeholder").find(".rank-drag-drop-text").text() == "DRAG HERE TO ADD TO THE BOTTOM OF LIST"){
                $(".rank-drag-drop-placeholder").find(".rank-drag-drop-text").text("ADD TO THE END OF THE LIST");
            }else{
                $(".rank-drag-drop-placeholder").find(".rank-drag-drop-text").text("CLICK HERE TO START LIST");
            }
            $(".rank-remove-btn").hide();

            $(".rank-list-container").find(".rank-container").each(function(){
                $(this).removeClass("rank-list-interact");
                if($(this).attr("data-id") != game.attr("data-id") && currentID != game.attr("data-id") && !$(this).hasClass("rank-drag-drop-placeholder") && !$(this).hasClass("hide-game-rank")){
                    $("<div class='rank-list-insert-btn'><i class='material-icons left' style='position:relative;font-size:1.5em;'>add_box</i> INSERT HERE</div>").insertBefore($(this));
                }
                
                currentID = $(this).attr("data-id");
            });
            AttachSelectedGameEvents();

            if($(window).width() <= 600 && $(".rank-unranked-list-container-active").length > 0){
                ToggleUnrankedModal();
            }

            game.addClass("active");
            if(game.parent().hasClass("rank-list-container")){
                game.find(".rank-remove-btn").show();
            }
        }
    }
}

function AttachSelectedGameEvents(){
    $(".rank-list-insert-btn").addClass("rank-list-insert-btn-expand");
    $(".rank-list-insert-btn, .rank-drag-drop-placeholder, .rank-current-selected, .rank-remove-btn").unbind();
    $(".rank-remove-btn").on("click", function(e){
        e.stopImmediatePropagation();
        ToggleGameRankSelection($(this).parent());
        $(this).parent().remove();
    });
    $(".rank-list-insert-btn, .rank-drag-drop-placeholder").on("click", function(){
        var game =  $(".rank-container.active");
        game.removeClass('active');
        $(".rank-current-selection").removeClass("rank-current-selection-active");
        $(".rank-save-btn").removeClass("rank-save-btn-shift-up");
        game.insertBefore($(this));

        game.addClass("rank-move-highlight");

        setTimeout(function(){ game.removeClass("rank-move-highlight"); }, 750);

        var year = $(".year-dropdown-selected").text();
        var genre = $(".genre-dropdown-selected").text();
        var platform = $(".platform-dropdown-selected").text();
        var xp = $(".xp-dropdown-selected").text();
        var showingAll = false;
        if(genre.indexOf("All-Genre") != -1 && platform.indexOf("All-Platform") != -1 && year == "All-Time" && xp == "All-Experiences"){
            showingAll = true;
        }
        $(".rank-list-insert-btn").remove();
        $(".rank-drag-drop-placeholder").unbind();
        UpdateAccordionCounter(showingAll, false);
        ToggleSaveRankedBtn();
    });
    $(".rank-current-selection").on("click", function(){
        $(".rank-container.active").removeClass("active");
        $(".rank-list-insert-btn").removeClass("rank-list-insert-btn-expand");
        $(".rank-current-selection").removeClass("rank-current-selection-active");
        $(".rank-save-btn").removeClass("rank-save-btn-shift-up");
        setTimeout(function(){ $(".rank-list-insert-btn").remove(); }, 300);
        if($(".rank-drag-drop-placeholder").find(".rank-drag-drop-text").text() == "ADD TO THE END OF THE LIST"){
            $(".rank-drag-drop-placeholder").find(".rank-drag-drop-text").text("DRAG HERE TO ADD TO THE BOTTOM OF LIST");
        }else{
            $(".rank-drag-drop-placeholder").find(".rank-drag-drop-text").text("DRAG GAME HERE TO START LIST");
        }
    });
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
        $(".rank-filter-list-container").removeClass("rank-filter-list-container-active");
        $(".rank-header-title-count").removeClass("rank-header-title-count-active");
        if($(window).width() > 599){
            $(".rank-list-container, .rank-welcome-container").css({"width":"calc(100% - 60px)"});
            $(".rank-drag-drop-placeholder").css({"width":"calc(100% - 60px)"});
            $(".rank-save-btn").removeClass("rank-save-btn-shift");
            $(".rank-header-container").removeClass("rank-header-container-active");
            $(".rank-current-selection").removeClass("rank-current-selection-active-shift");
        }
    }else{
        $(".rank-unranked-list-container").addClass("rank-unranked-list-container-active");
        $(".rank-filter-list-container").addClass("rank-filter-list-container-active");
        $(".rank-header-title-count").addClass("rank-header-title-count-active");
        if($(window).width() > 599){
            $(".rank-list-container, .rank-welcome-container").css({"width":"60%"});
            $(".rank-drag-drop-placeholder").css({"width":"60%)"});
            $(".rank-save-btn").addClass("rank-save-btn-shift");
            $(".rank-header-container").addClass("rank-header-container-active");
            $(".rank-current-selection").addClass("rank-current-selection-active-shift");
        }
    }
}

function ToggleFilterModal(){
    if($(".rank-filter-list-container-active").length > 0 && $(".rank-unranked-list-container-active").length == 0){
        $(".rank-filter-list-container-active").removeClass("rank-filter-list-container-active");
        if($(window).width() > 599){
            $(".rank-list-container, .rank-welcome-container").css({"width":"calc(100% - 60px)"});
            $(".rank-drag-drop-placeholder").css({"width":"calc(100% - 60px)"});
            $(".rank-save-btn").removeClass("rank-save-btn-shift");
            $(".rank-header-container").removeClass("rank-header-container-active");
            $(".rank-current-selection").removeClass("rank-current-selection-active-shift");
        }
    }else{
        $(".rank-filter-list-container").addClass("rank-filter-list-container-active");
        $(".rank-unranked-list-container-active").removeClass("rank-unranked-list-container-active");
        if($(window).width() > 599){
            $(".rank-list-container, .rank-welcome-container").css({"width":"60%"});
            $(".rank-drag-drop-placeholder").css({"width":"60%)"});
            $(".rank-save-btn").addClass("rank-save-btn-shift");
            $(".rank-header-container").addClass("rank-header-container-active");
            $(".rank-current-selection").addClass("rank-current-selection-active-shift");
        }
    }
}

function AttachFilterEvents(){
    $(".year-dropdown-filter-item, .genre-dropdown-filter-item, .platform-dropdown-filter-item, .xp-dropdown-filter-item").unbind();
    $(".year-dropdown-filter-item").on('click', function() {
       var years = ""; 
        $(this).parent().find(".year-dropdown-filter-item").each(function(){
            if($(this).find("input:checked").length > 0)
                years = $(this).attr("data-year")+","+years;
        });
        if(years == "")
            years = "ALL";
        $("#rank-filter-year").attr('data-filter', years);
        FilterLists();
	});
    $(".genre-dropdown-filter-item").on('click', function() {
        var genre = ""; 
        $(this).parent().find(".genre-dropdown-filter-item").each(function(){
            if($(this).find("input:checked").length > 0)
                genre = $(this).attr("data-genre")+","+genre;
        });
        if(genre == "")
            genre = "ALL";
        $("#rank-filter-genre").attr('data-filter', genre);
        FilterLists();
	});
    $(".platform-dropdown-filter-item").on('click', function() {
        var platforms = ""; 
        $(this).parent().find(".platform-dropdown-filter-item").each(function(){
            if($(this).find("input:checked").length > 0)
                platforms = $(this).attr("data-platform")+","+platforms;
        });
        if(platforms == "")
            platforms = "ALL";
        $("#rank-filter-platform").attr('data-filter', platforms);
        FilterLists();
	});
    $(".xp-dropdown-filter-item").on('click', function() {
        var xp = ""; 
        $(this).parent().find(".xp-dropdown-filter-item").each(function(){
            if($(this).find("input:checked").length > 0)
                xp = $(this).attr("data-xp")+","+xp;
        });
        if(xp == "")
            xp = "ALL";
        $("#rank-filter-xp").attr('data-filter', xp);
        FilterLists();
	});
    $(".filter-type-item").on('click', function() {
        $("#rank-filter-type").attr('data-filter', $(this).attr("data-type"));
        if($(this).attr("data-type") == "Hide")
        {
            $(".rank-list-container .minimize-game-rank").addClass("hide-game-rank");
            $(".rank-list-container .minimize-game-rank").removeClass("minimize-game-rank");
        }else{
            $(".rank-list-container .hide-game-rank").addClass("minimize-game-rank");
            $(".rank-list-container .hide-game-rank").removeClass("hide-game-rank");
        }
        FilterLists();
	});
    $(".rank-filter-search-field").on('keypress keyup', function (e) {
        if (e.keyCode === 13) { 
            e.stopPropagation(); 	
            $(".rank-filter-search-wrapper").click();
        }
        if($(this).val() != ""){
            $(".rank-filter-search-clear-btn").show();
        }else{
            $(".rank-filter-search-clear-btn").hide();
            FilterLists();
        }
    }); 
    $(".rank-filter-search-clear-btn").on('click', function(){
        $(".rank-filter-search-field").val("");
        $(".rank-filter-search-clear-btn").hide();
        FilterLists();
    });
    $(".rank-filter-search-wrapper").on('click', function(){
        FilterLists();
    });
}

function UpdateRankedPositions(showingAll){
    var localcount = 1;
    var globalcount = 1;
    $(".rank-list-container").find(".rank-container").each(function(){
        if(!$(this).hasClass("rank-drag-drop-placeholder") && !$(this).hasClass(getFilterType())){
            if(showingAll){
                $(this).find(".rank-count-container").html("<div class='rank-count-main'>" + localcount + "</div>");
            }else{
                $(this).find(".rank-count-container").html("<div class='rank-count-main'>" + localcount + "</div> <div class='rank-count-secondary'>" + globalcount + "</div>");
            }

            var image = $(this).attr("data-image");
            $(this).find(".rank-image").css({"background":"url("+ image +") 50% 25%","background-size":"cover"});
            $(this).find(".rank-item-title").addClass("rank-item-title-w-image");

            var lastTime = parseInt($(this).attr("data-loaded-rank"));
            if(lastTime == 0){
                $(this).find(".rank-history").html("<span class='rank-history-new'>NEW</span>");
                $(this).attr("data-history", "NEW");
            }else if(lastTime > globalcount){
                var diff = lastTime - globalcount;
                $(this).find(".rank-history").html("<span class='rank-history-green'><i class='material-icons left' style='margin-right: 5px;'>keyboard_arrow_up</i>"+ diff +"</span>");
                $(this).attr("data-history", diff);
            }else if(lastTime < globalcount){
                var diff = globalcount - lastTime;
                $(this).find(".rank-history").html("<span class='rank-history-red'><i class='material-icons left' style='margin-right: 5px;'>keyboard_arrow_down</i> "+ diff  +"</span>");
                $(this).attr("data-history", -diff);
            }else{
                $(this).find(".rank-history").html("");
                $(this).attr("data-history", "");
            }
             $(this).stop();
             
            localcount++;
        }
        globalcount++;
    });

    if(localcount == 1){
        $(".rank-drag-drop-placeholder").find(".rank-drag-drop-text").text("DRAG GAME HERE TO START LIST");
    }else{
        $(".rank-welcome-container").hide();
        $(".rank-drag-drop-placeholder").find(".rank-drag-drop-text").text("DRAG HERE TO ADD TO THE BOTTOM OF LIST");
    }
}

function FilterLists(){
    $(".rank-container.active").removeClass("active");
    $(".rank-list-insert-btn").remove();

    var year = $("#rank-filter-year").attr("data-filter").split(",");
    var genre = $("#rank-filter-genre").attr("data-filter").split(",");
    var platform = $("#rank-filter-platform").attr("data-filter").split(",");
    var xp = $("#rank-filter-xp").attr("data-filter").split(",");
    var search = $(".rank-filter-search-field").val();
    var showingAll = true;
    $(".rank-header-container").html("");
    if(genre == "ALL" && platform == "ALL" && year == "ALL" && xp == "ALL" && (search == "" || search == "Search your catalog")){
        $(".rank-container").each(function(){
            $(this).removeClass("hide-game-rank");
            $(this).removeClass("minimize-game-rank");
        });
    }else{
        if(search != "" && search != "Search your catalog"){
            $(".rank-header-container").append("<div class='filter-chip'>Search: '" + search + "'</div>");
        }
        for(var i = 0; i < year.length - 1; i++){
            $(".rank-header-container").append("<div class='filter-chip'>" + year[i] + "</div>");
        }

        for(var i = 0; i < genre.length - 1; i++){
            $(".rank-header-container").append("<div class='filter-chip'>" + genre[i] + "</div>");
        }

        for(var i = 0; i < platform.length - 1; i++){
            $(".rank-header-container").append("<div class='filter-chip'>" + platform[i] + "</div>");
        }

        for(var i = 0; i < xp.length - 1; i++){
            $(".rank-header-container").append("<div class='filter-chip'>" + xp[i] + "</div>");
        }
        $(".filter-chip").unbind();
        $(".filter-chip").on("click", function(){
            if($(this).text().indexOf("Search:") != -1){
                $(".rank-filter-search-field").val("");
                $(".rank-filter-search-clear-btn").hide();
                FilterLists();
            }else
                $("#"+$(this).text().replace(" ","_")).click();
            
            $(this).remove();
        });
        
        showingAll = false;
        $(".rank-container").each(function(){
            var genrehide = true;
            var yearhide = true;
            var platformhide = true;
            var xphide = true;
            var searchhide = true;
            var currRow = $(this);
            var isUnranked = false;
            if($(this).parent().hasClass("rank-modal-body"))
                isUnranked = true;

            if(year.length > 0 && ($.inArray($(this).attr("data-year"), year) != -1 || year[0].indexOf("ALL") != -1)){
                yearhide = false;
            }

            if(search == "" && search == "Search your catalog"){
                searchhide = false;
            }else{
                if($(this).find(".rank-item-title").text().toLowerCase().indexOf(search.toLowerCase()) != -1)
                    searchhide = false;
            }

            if(platform.length > 0){  
                if(platform[0].indexOf("ALL") == -1){       
                    for(var i = 0; i < platform.length - 1; i++){
                        if(currRow.attr("data-platform").indexOf(platform[i]) != -1)
                            platformhide = false;
                    }
                }else{
                    platformhide = false;
                }
            }

            if(genre.length > 0){  
                if(genre[0].indexOf("ALL") == -1){       
                    for(var i = 0; i < genre.length - 1; i++){
                        if(currRow.attr("data-genre").indexOf(genre[i]) != -1)
                            genrehide = false;
                    }
                }else{
                    genrehide = false;
                }
            }

            if(xp.length > 0){  
                if(xp[0].indexOf("ALL") == -1){       
                    for(var i = 0; i < xp.length - 1; i++){
                        if(currRow.attr("data-xp").indexOf(xp[i]) != -1)
                            xphide = false;
                    }
                }else{
                    xphide = false;
                }
            }

            if(genrehide || yearhide || platformhide || xphide || searchhide){
                if(isUnranked)
                    $(this).addClass("hide-game-rank");
                else
                    $(this).addClass(getFilterType());
            }else{
                if(isUnranked)
                    $(this).removeClass("hide-game-rank");
                else
                    $(this).removeClass(getFilterType());
            }
        });

        $(".rank-drag-drop-placeholder").removeClass(getFilterType());
    }
    UpdateAccordionCounter(showingAll, true);
}

function UpdateAccordionCounter(showingAll, highlight){
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
        $(this).stop();
    });
    
    if(highlight){
        $(".rank-unranked-list-container .rank-header-title").css({"left":"-120px", "background-color":"lightyellow"});
        setTimeout(function(){ $(".rank-unranked-list-container .rank-header-title").css({"left":"-60px", "background-color":"white"}); }, 1000);
    }

    UpdateRankedPositions(showingAll);
}

function getFilterType(){
    var type = $("#rank-filter-type").attr("data-filter");
    if(type == "Minimize")
        return "minimize-game-rank";
    else
        return "hide-game-rank";
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
        $(".rank-container.active").removeClass("active");
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
        if (dragSrcEl != this && this.parentNode.className == "rank-list-container") {
            dragSrcEl.style.color = 'inherit';
            this.parentNode.insertBefore(dragSrcEl, this);
            $(".rank-list-insert-btn").removeClass("rank-list-insert-btn-expand");
            setTimeout(function(){ $(".rank-list-insert-btn").remove(); }, 300);

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

            UpdateAccordionCounter(showingAll, false);
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

