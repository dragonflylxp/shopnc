// JavaScript Document
$(function(){
	//用户中心弹出框效果
	$(document).on('click',"*[data-dialog='goods_collect_dialog']",function(){
		var ok_title, cl_title;
		var url = $(this).data('url'); //删除连接地址
		var title = $(this).data('title');
		var width = $(this).data('width');
		var height = $(this).data('height');
		var padding = $(this).data('padding');
		var divId = $(this).data('divid');
		var id = $(this).data('goodsid');
		var hrefCont = '';
		var foot = true;
		var cBtn = true;
		if(id > 0){
			
			Ajax.call(url, 'id=' + id, function(data){
				if(divId == 'goods_collect'){
					if(data.error > 0){
						if(data.error == 2){
            				$.notLogin("get_ajax_content.php?act=get_login_dialog", data.url);
							return false;
						}else if(data.error == 1){
							cl_title = json_languages.cancel;
						}
						
						var content = '<div id="' + divId + '">' + 
							'<div class="tip-box icon-box">' +
								'<span class="warn-icon m-icon"></span>' + 
								'<div class="item-fore">' +
									'<h3 class="rem ftx-04">' + data.message + '</h3>' +
								'</div>' +
							'</div>' +
						'</div>';
						
						ok_title = json_languages.determine;
					}else{
						$(".choose-btn-coll").addClass('selected');
						$(".choose-btn-icon").addClass('icon-collection-alt');
						$(".choose-btn-icon").removeClass('icon-collection');
						$("#collect_count").html(data.collect_count);
						ok_title = json_languages.My_collection;
						var content = $("#dialog_goods_collect").html(); 
						hrefCont = "user.php?act=collection_list";
						cBtn = false;
					}
				}
	
				pb({
					id:divId,
					title:title,
					width:width,
					height:height,
					ok_title:ok_title, 	//按钮名称
					cl_title:cl_title, 	//按钮名称
					content:content, 	//调取内容
					drag:false,
					foot:foot,
					cl_cBtn:cBtn, 
					onOk:function(){
						location.href = hrefCont;
					}
				});	
				
				$('#' + divId + ' .pb-ft .pb-ok').addClass('color_df3134');
				
			}, 'GET', 'JSON');
		}else{
			ok_title = json_languages.determine;
			cl_title = json_languages.cancel;
			if(divId == 'delete_goods_collect'){
				var content = $("#delete_goods_collect").html(); 
			}else if(divId == "delete_brand_collect"){
				var content = $("#delete_brand_collect").html(); 
			}
			
			pb({
				id:divId,
				title:title,
				width:width,
				height:height,
				ok_title:ok_title, 	//按钮名称
				cl_title:cl_title, 	//按钮名称
				content:content, 	//调取内容
				drag:false,
				foot:foot,
				onOk:function(){
					location.href = url;
				}
			});	
			
			$('#' + divId + ' .pb-ft .pb-ok').addClass('color_df3134');
		}		
	});
	
	//用户中心弹出框效果
	$(document).on('click',"*[data-dialog='goods_del_booking']",function(){
		
		var url = $(this).data('url'); //删除连接地址
		var confirmtitle = $(this).data('confirmtitle');
		var width = $(this).data('width');
		var height = $(this).data('height');
		var divId = $(this).data('divid');
		
		var content = '<div id="' + divId + '">' + 
							'<div class="tip-box icon-box">' +
								'<span class="warn-icon m-icon"></span>' + 
								'<div class="item-fore">' +
									'<h3 class="rem ftx-04">' + confirmtitle + '</h3>' +
								'</div>' +
							'</div>' +
						'</div>';
		
		pb({
			id:divId,
			title:json_languages.title,
			width:width,
			height:height,
			ok_title:json_languages.determine, 	//按钮名称
			cl_title:json_languages.cancel, 	//按钮名称
			content:content, 	//调取内容
			drag:false,
			foot:true,
			onOk:function(){
				location.href = url;
			}
		});	
		
		$('#' + divId + ' .pb-ft .pb-ok').addClass('color_df3134');	
	});
});
