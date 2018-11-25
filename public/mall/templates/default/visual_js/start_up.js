$(function(){
	$(document).on('click','.first_column .up',function(e){
		var _this = $(this);
		var level = _this.data('level');
		var catid = _this.data('catid');
		var isclick = _this.data('isclick');
		var cs = _this.parents('tr').attr('class');
		var id = _this.parents('tr').nextAll('.'+ cs).attr('id');
		var index = _this.parents('tr').index();
		var next_index = $('#'+id).index();
		var fale = false;
		if(_this.hasClass('down')){
			fale = false;
		}else{
			fale = true;
		}
		
		if(fale){
			_this.addClass('down');
			_this.parents('tr').siblings('.' + catid + '_' + (level + 1)).show();
		}else{
			_this.removeClass('down');
			//判断是否是最后一个元素，最后一个元素没有相邻节点
			if(next_index != -1){
				for(i=(index+1);i<next_index;i++){
					var tr = _this.parents('table').find('tr').eq(i);
					tr.hide();
					tr.find('i.up').removeClass('down');
				}
			}else{
				var prev_tr = _this.parents('tr').prevAll('.'+cs);
				var new_cs = prev_tr.eq(prev_tr.length-1).prev().attr('class');
				var l = _this.parents('tr').nextAll('.'+ cs).length;
				if(l<1){
					var next_index = _this.parents('tr').nextAll('.'+new_cs).index();
					if(next_index != -1){
						for(i = index+1;i<next_index;i++){
							var tr = _this.parents('table').find('tr').eq(i);
							tr.hide();
							tr.find('i.up').removeClass('down');
						}
					}else{
						 _this.parents('tr').nextAll().hide();
						 _this.parents('tr').find('i.up').removeClass('down');
					}
				}
			}
		}
		
		//判断是否是分类异步加载
		if(level != null && catid != null){
			if(isclick == 0){
				Ajax.call(window.location.href.replace(/\?(.)+/g,'')+'?act=ajax_cache_list', '&cat_id=' + catid + "&level=" + level, ajax_category_response, 'GET', 'JSON');
			}
		}
		
		e.stopPropagation();
	});
});

function ajax_category_response(result){
	$("#icon_" + result.parent_level + "_" + result.cat_id).data('isclick', 1);
	$("#" + result.parent_level + "_" + result.cat_id).after(result.cat_html);
}





















