function ShowMyLibrary(){
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
	     data: {action: "DisplayMyLibrary" },
	     type: 'post',
	     success: function(output) {
	 		$("#rankingInnerContainer").html(output);
            $(".mylib-filter-list-container .rank-header-title").on("click", function(){
                ToggleMyLibraryFilterModal();
             });
            if($(window).width() > 599){
                 ToggleMyLibraryFilterModal();
            }
            $(".mylib-container").on("click", function(e){
                e.stopPropagation(); 
                ShowGame($(this).attr("data-gbid"), $("#discover")); 
            });
            AttachMyLibraryFilterEvents();
            $(".mylib-to-top-btn").on("click", function(e){
                $("html, body").animate({scrollTop : 0},800);
            });
             $('.collapsible').collapsible();
                $(window).unbind("scroll");
                $(window).scroll(function(){
                    $(".mylib-container").each(function(){ 
                        if(!$(this).hasClass("hide-game-rank")){
                            var imgcontainer = $(this).find(".mylib-image");
                            if(!imgcontainer.hasClass("displayImage")){
                                if(isScrolledIntoView($(this))){
                                    var image = $(this).attr("data-image");
                                    imgcontainer.css({"background":"url("+ image +") 50% 25%","background-size":"cover"});
                                    imgcontainer.show();
                                }
                            }
                        }
                    });

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

function AttachMyLibraryFilterEvents(){
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
        FilterMyLibraryList();
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
        FilterMyLibraryList();
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
        FilterMyLibraryList();
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
        FilterMyLibraryList();
	});
    $(".star-dropdown-filter-item").on('click', function() {
        var star = ""; 
        $(this).parent().find(".star-dropdown-filter-item").each(function(){
            if($(this).find("input:checked").length > 0)
                star = $(this).attr("data-star")+","+star;
        });
        if(star == "")
            star = "ALL";
        $("#rank-filter-star").attr('data-filter', star);
        FilterMyLibraryList();
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
            FilterMyLibraryList();
        }
    }); 
    $(".rank-filter-search-clear-btn").on('click', function(){
        $(".rank-filter-search-field").val("");
        $(".rank-filter-search-clear-btn").hide();
        FilterMyLibraryList();
    });
    $(".rank-filter-search-wrapper").on('click', function(){
        FilterMyLibraryList();
    });
}

function ToggleMyLibraryFilterModal(){
    if($(".mylib-filter-list-container-active").length > 0){
        $(".mylib-filter-list-container-active").removeClass("mylib-filter-list-container-active");
        if($(window).width() > 599){
            $(".mylib-list-container").css({"width":"calc(100% - 60px)"});
            $(".mylib-header-container").removeClass("mylib-header-container-active");
            $(".mylib-to-top-btn").removeClass("mylib-to-top-btn-shift");
        }
    }else{
        $(".mylib-filter-list-container").addClass("mylib-filter-list-container-active");
        if($(window).width() > 599){
            $(".mylib-list-container").css({"width":"60%"});
            $(".mylib-header-container").addClass("mylib-header-container-active");
            $(".mylib-to-top-btn").addClass("mylib-to-top-btn-shift");
        }
    }
}

function FilterMyLibraryList(){
    $(".mylib-container.active").removeClass("active");

    var year = $("#rank-filter-year").attr("data-filter").split(",");
    var genre = $("#rank-filter-genre").attr("data-filter").split(",");
    var platform = $("#rank-filter-platform").attr("data-filter").split(",");
    var xp = $("#rank-filter-xp").attr("data-filter").split(",");
    var star = $("#rank-filter-star").attr("data-filter").split(",");
    var search = $(".rank-filter-search-field").val();
    var showingAll = true;
    $(".rank-header-container").html("");
    if(genre == "ALL" && platform == "ALL" && year == "ALL" && xp == "ALL" && star == "ALL" && (search == "" || search == "Search your catalog")){
        $(".mylib-container").each(function(){
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

        for(var i = 0; i < star.length - 1; i++){
            $(".rank-header-container").append("<div class='filter-chip'>" + star[i] + "</div>");
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
        $(".mylib-container").each(function(){
            var genrehide = true;
            var yearhide = true;
            var platformhide = true;
            var xphide = true;
            var starhide = true;
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
                if($(this).find(".mylib-item-title").text().toLowerCase().indexOf(search.toLowerCase()) != -1)
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

            if(star.length > 0){  
                if(star[0].indexOf("ALL") == -1){       
                    for(var i = 0; i < star.length - 1; i++){
                        if(currRow.attr("data-star").indexOf(star[i]) != -1)
                            starhide = false;
                    }
                }else{
                    starhide = false;
                }
            }

            if(genrehide || yearhide || platformhide || xphide || searchhide || starhide){
                $(this).addClass("hide-game-rank");
            }else{
                $(this).removeClass("hide-game-rank");
            }

            if(!$(this).hasClass("hide-game-rank")){
                var imgcontainer = $(this).find(".mylib-image");
                if(!imgcontainer.hasClass("displayImage")){
                    if(isScrolledIntoView($(this))){
                        var image = $(this).attr("data-image");
                        imgcontainer.css({"background":"url("+ image +") 50% 25%","background-size":"cover"});
                        imgcontainer.show();
                    }
                }
            }
        });
    }
}