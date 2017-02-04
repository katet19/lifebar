function ShowRanking(){
    $("#game").css({"display":"inline-block", "right": "-75%"});
	if($(window).width() > 599){
		$("#navigation-header").css({"display":"block"});
		$("#navigationContainer").css({"-webkit-box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)", "box-shadow":"0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)"});
	}
	SCROLL_POS = $(window).scrollTop();
	$('body').css({'top': -($('body').scrollTop()) + 'px'}).addClass("bodynoscroll");
	$("body").append("<div class='lean-overlay' id='materialize-lean-overlay-1' style='z-index: 1002; display: block; opacity: 0.5;'></div>");
	$("#game.outerContainer").css({ "right": 0 });
    ShowLoader($("#gameInnerContainer"), 'big', "<br><br><br>");
		$.ajax({ url: '../php/webService.php',
	     data: {action: "DisplayRanking" },
	     type: 'post',
	     success: function(output) {
	 		$("#gameInnerContainer").html(output);
            $(".fixed-close-modal-btn, .lean-overlay").unbind();
            $(".fixed-close-modal-btn, .lean-overlay").on('click', function(){
                $("#game").css({ "right": "-75%" }); 
                $(".lean-overlay").each(function(){ $(this).remove(); } );
                setTimeout(function(){ $("#game").css({"display":"none"}); $('body').removeClass("bodynoscroll").css({'top': $(window).scrollTop(SCROLL_POS) + 'px'}); }, 300);
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