$(function() {
	$('.index-sitemap > a').bind("click",
	function() {
		$(".sitemap-menu-arrow").slideDown("slow");
		$(".sitemap-menu").slideDown("slow");
	});
	$('.add-quickmenu > a').bind("click",
	function() {
		$(".sitemap-menu-arrow").slideDown("slow");
		$(".sitemap-menu").slideDown("slow");
	});
	$('#closeSitemap').bind("click",
	function() {
		$(".sitemap-menu-arrow").slideUp("fast");
		$(".sitemap-menu").slideUp("fast");
	});
});

//头部导航下拉菜单相关
$(function() {
		$(".ncsc-nav dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});

});


(function($) {
//凸显触及图片样式
	$.fn.jfade = function(settings) {

		var defaults = {
			start_opacity: "1",
			high_opacity: "1",
			low_opacity: ".1",
			timing: "500"
		};
		var settings = $.extend(defaults, settings);
		settings.element = $(this);

		//set opacity to start
		$(settings.element).css("opacity", settings.start_opacity);
		//mouse over
		$(settings.element).hover(

		//mouse in
		function() {
			$(this).stop().animate({
				opacity: settings.high_opacity
			},
			settings.timing); //100% opacity for hovered object
			$(this).siblings().stop().animate({
				opacity: settings.low_opacity
			},
			settings.timing); //dimmed opacity for other objects
		},

		//mouse out
		function() {
			$(this).stop().animate({
				opacity: settings.start_opacity
			},
			settings.timing); //return hovered object to start opacity
			$(this).siblings().stop().animate({
				opacity: settings.start_opacity
			},
			settings.timing); // return other objects to start opacity
		});
		return this;
	}
})(jQuery);

