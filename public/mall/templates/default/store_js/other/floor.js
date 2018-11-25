(function($){
	$.fn.jfloor = function(itemHeight,bHeight){
		if(itemHeight == null){
			var itemHeight = 0;
		}
		if(bHeight == null){
			var bHeight = 0;
		}
		return this.each(function(){
			var floors,flooritem,axis,layer,floorsTop;
			floors = $(this).find(".floors");
			flooritem = floors.find(".fment");
			axis = $(this).find(".goods-detail-mt");
			layer = axis.find(".tab_item");
			floorsTop =  parseInt(floors.offset().top-itemHeight);
			
			layer.click(function(){
				var index = layer.index(this);
				var top = parseInt(flooritem.eq(index).offset().top-itemHeight);
				$("body,html").stop().animate({scrollTop:top});
			});
			$(window).scroll(function(){
				var top = $(document).scrollTop();
				
				if(top >= floorsTop-itemHeight){
					axis.css({'top':0,'position':'fixed'});
				}else{
					axis.css({'top':0,'position':'absolute'});
				}
				
				for(var i=0;i<flooritem.length;i++){
					var flooritemTop =  parseInt(flooritem.eq(i).offset().top-itemHeight);
					if(top >= flooritemTop-bHeight){
						layer.eq(i).addClass("current").siblings().removeClass("current");
					}
				}
			});
		});
	}
})(jQuery);