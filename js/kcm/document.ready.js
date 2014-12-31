$(function(){
		var base = window.location.protocol + "//" + window.location.host;
		var maxHeight = 400;
		
		$('#main_menu ul').addClass('with-js');
		$('#main_menu ul').hoverIntent({
			sensitivity: 3, // number = sensitivity threshold (must be 1 or higher)
			interval: 0, // number = milliseconds for onMouseOver polling interval
			timeout: 1000, // number = milliseconds delay before onMouseOut
			selector: 'li',
			over: function() {
				$(this).find('ul').fadeIn('fast');
				$(this).find("ul li ul").hide();
			},
			out: function(){$(this).find("ul").fadeOut('slow');}
		});
        $("#main_menu ul li ul > li").hover(function() {

            var $container = $(this),
            $list = $container.find("ul"),
            $anchor = $container.find("a"),
            height = $list.height() * 1.1,       // make sure there is enough room at the bottom
            multiplier = height / maxHeight;     // needs to move faster if list is taller

            // need to save height here so it can revert on mouseout            
            $container.data("origHeight", $container.height());

            // so it can retain it's rollover color all the while the dropdown is open
            $anchor.addClass("hover");


            // don't do any animation if list shorter than max
            if (multiplier > 1) {
                $container
                .css({
                    //height: maxHeight,
                    //overflow: "hidden"
                })
                .mousemove(function(e) {
                    var offset = $container.offset();
                    var relativeY = ((e.pageY - offset.top) * multiplier) - ($container.data("origHeight") * multiplier);
                    if (relativeY > $container.data("origHeight")) {
                        $list.css("top", -relativeY + $container.data("origHeight"));
                    };
                });
            }

        }, function() {

            var $el = $(this);

            // put things back to normal
            $el
            .height($(this).data("origHeight"))
            .find("ul")
            .css({ top: 0 })
            .show()
            .find("a")
            .removeClass("hover");    
        }); 
		
		/*load news and noticeboard*/
		loadGen(base,"news","news");
		loadGen(base,"notice","noticeboard");			
		quickmessage();
		//soulsend();
		
		$('.carousel #slides').slides({
				preload: true,
				preloadImage: base + '/images/system/ajax.gif',
				play: 13000,
				pause: 2500,
				hoverPause: true,
				animationStart: function(current){
					$('.caption').animate({
						bottom:-35
					},100);
					if (window.console && console.log) {
						// example return of current slide number
						console.log('animationStart on slide: ', current);
					};
				},
				animationComplete: function(current){
					$('.caption').animate({
						bottom:0
					},200);
					if (window.console && console.log) {
						// example return of current slide number
						console.log('animationComplete on slide: ', current);
					};
				},
				slidesLoaded: function() {
					$('.caption').animate({
						bottom:0
					},200);
				}
			});	 				
	}
);

