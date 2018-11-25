/*
	详情页面商品详细介绍分类跟随滚动js
*/
(function(){
	var smint=null,sT=0,cha=0,toPos=0,index=0,movePos=0,curTop=0,nextTop=0,n=0;
	$.fn.smint =function( options ){
		$(this).addClass('smint');
		var settings = $.extend({
            'scrollSpeed '  : 500
            }, options);
			
		var stickyTop = $('.smint').offset().top;
		var stickyTopD = $('.desc').height();
		var top = $('.smint').position().top;
		var stickyMenu = function(){
			var scrollTop = $(window).scrollTop();
			$(document).on("click",".smint li",function(){
				clearInterval(smint);
				$(".smint li").removeClass("current");
				$(this).addClass("current");
				index=$(this).index();
				toPos = parseInt($(".fment").eq(index).offset().top);
				toPos-=40;
				MovePos(sT,toPos);
			})
			if (scrollTop > stickyTop&&scrollTop<(stickyTop+stickyTopD)) { 
				$('.smint').css({ 'position': 'fixed', 'top':0 }).addClass('common-fixed');			
				var length=$(".smint li").length;
				for(var i=0;i<length;i++){
					curTop=parseInt($(".fment").eq(i).offset().top);
					curTop-=40;
					nextTop=curTop+parseInt($(".fment").eq(i).height());
					if(scrollTop>=curTop&&scrollTop<nextTop){
						$(".smint li").removeClass("current");
						$(".smint li").eq(i).addClass("current");
						break;
					}else{
						$(".smint li").removeClass("current");
					}
				}
				} else {
					$('.smint').css({ 'position': 'absolute', 'top': top}).removeClass('common-fixed').children(".buy-group").css("display","none"); 
				}   
			};
			$(window).scroll(function() {
				 stickyMenu();
			});
			function goToPos(sT,toPos){
				sT=parseInt($(window).scrollTop());
				cha=toPos-sT;
				if(Math.abs(cha)<10){
					clearInterval(smint);
					smint=null;
					$(window).scrollTop(toPos);
				}else{
					movePos=cha*0.1
					$(window).scrollTop((sT+movePos));
				}
			}
			function MovePos(sT,toPos){
				smint = setInterval(function(){goToPos(sT,toPos);},10);
			}
	}
})();
