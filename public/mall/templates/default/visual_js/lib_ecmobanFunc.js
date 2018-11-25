/**
*by:511613932
 */
$(function(){
	/*************************************平台和商家 后台通用 start*************************************/
	
	/* 后台商品详情手机端上传图片或上传文字 */
	$("[ectype='mb_add_img'],[ectype='mb_add_txt']").on("click",function(){
		var ectype = $(this).attr("ectype"),
			title = "",
			log_type = '',
			content = "";
		if(ectype == "mb_add_img"){
			title = "添加图片";
			log_type = 'image';
		}else if(ectype == "mb_add_txt"){
			title = "添加文字";
			log_type = 'word';
		}
		
		if(log_type == 'word'){
			$.jqueryAjax('goods.php', 'act=gallery_album_dialog&log_type='+log_type, function(data){
				goods_visual_desc(title,815,data.content,function(){
                	append_mobile_text("#goodsMobile");
				});
			});
		}else {
			Ajax.call('dialog.php', "act=shop_banner&is_vis=1", function(result){
				goods_visual_desc("图片编辑器",915,result.content,function(){
					append_mobile_img("#goodsMobile");
				});
		
				//根据cookie默认选中图片库筛选方式
				album_select(1);
				
			}, 'POST', 'JSON');
		}
	});
	
	/*后台商品详情页 图片库中选择所需图片 弹窗*/
	$("[ectype='gallery_album']").on("click",function(){
		var inid = $(this).parents(".gallery_album").data("inid");
		var picId = "";
		var img_src = "";
		var obj = "";
		var is_vis = 2;
		
		if(inid != 'gallery_album'){
			is_vis = 1;
		}
		
		Ajax.call('dialog.php', "act=shop_banner&is_vis="+is_vis+"&image_type=1&inid=" + inid, function(result){
			goods_visual_desc("图片编辑器",915,result.content,function(){
				obj = $("*[ectype='pic_replace']").find("li.current");
				if(inid == "gallery_album_dsc"){
					obj.each(function(){
						picId = $(this).data("picid");
						var src = $(this).data('url');
						if(img_src){
							img_src += "," + src;
						}else{
							img_src = src
						}
					});
	
					insert_img(picId,inid,img_src);
				}else{
					obj.each(function(){
						picId = $(this).data("picid");
						insert_img(picId,inid);
					});
				}
			});
			
			//根据cookie默认选中图片库筛选方式
			album_select(is_vis,inid);
			
			$("[ectype='pic_list']").perfectScrollbar("destroy");
			$("[ectype='pic_list']").perfectScrollbar();
		}, 'POST', 'JSON');
	});
	
	/*后台 弹窗搜索商品 */
	$(document).on("click","*[ectype='changedgoods']",function(){
		ajaxchangedgoods(1);
	});
	
	/*后台 商品切换配件类型 */
    $(document).on("click","[ectype='group_checked']",function(){
        var id = $(this).parents("tr").data("gid");
		var group_id = $(this).data("value");
		Ajax.call('goods.php?is_ajax=1&act=edit_gorup_type', 'id=' + id + "&group_id=" + group_id, function(data){
			if(data.message){
				alert(data.message);
			}
		} , 'POST', 'JSON');
    });
	
	/*后台 删除商品配件 */
    $(document).on("click","[ectype='remove_group']",function(){
		var _this = $(this);
        var id = _this.parents("tr").data("gid");
		if(confirm("确定删除该配件？")){
			Ajax.call('goods.php?is_ajax=1&act=remove_group_type', 'id=' + id, function(data){
				if(data.message){
					alert(data.message);
				}else{
					_this.parents("tr").remove();
				}
			} , 'POST', 'JSON');
		}
    });
	
	/*后台 设置配件 */
	$(document).on("click","*[ectype='setupGroupGoods']",function(){
		var spec_attr = new Object(),
			_this = $(this);
	
		var goods_id = $("input[name='goods_id']").val();
		var group_goods = '';
		$("[ectype='group_list']").find("tr").each(function(){
			var val = $(this).data('goods');
			if(group_goods){
				group_goods = group_goods + "," + val;
			}else{
				group_goods = val;
			}
		});
		spec_attr.goods_ids = group_goods;
			
		Ajax.call('dialog.php?act=goods_info', "goods_type=1&search_type=goods&goods_id=" + goods_id + "&spec_attr="+$.toJSON(spec_attr) , function(data){
			var content = data.content;
			pb({
				id:"set_up_goods",
				title: "设置商品",
				width: 970,
				content: content,
				ok_title: "确定",
				cl_title: "取消",
				drag: true,
				foot: true,
				cl_cBtn: true,
				onOk: function(){
					var goods_ids = $("#set_up_goods").find("input[name='goods_ids']").val();
					
					Ajax.call('get_ajax_content.php','act=add_group_goods&goods_ids='+goods_ids+'&goods_id='+goods_id,function(data){
						if(data.error == 1){
							alert(data.message);
						}else{
							$("[ectype='group_list']").html(data.content);
							reset_select("[ectype='group_list']")
						}
						
					},'GET','JSON');
				}
			});
		}, 'POST', 'JSON');
	});
	
	/*************************************平台和商家 后台通用 end*************************************/
	
	/* file上传文件类型 封装函数 satrt*/
	$(document).on("change","input[class='type-file-file']",function(){	
		var state = $(this).data('state');
		var filepath=$(this).val();
		var extStart=filepath.lastIndexOf(".");
		var ext=filepath.substring(extStart,filepath.length).toUpperCase();

		if(state == 'txtfile'){
			if(ext!=".TXT"){
				alert("上传文件限于txt格式");
				$(this).attr('value','');
				return false;
			}
		}else if(state == 'imgfile'){
			if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
				alert("上传图片限于png,gif,jpeg,jpg格式");
				$(this).attr('value','');
				return false;
			}
		}else if(state == 'csvfile'){
			if(ext!=".CSV"){
				alert("上传文件限于csv格式");
				$(this).attr('value','');
				return false;
			}
		}else if(state == 'sqlfile'){
            if(ext!=".SQL"){
				alert("上传文件限于sql格式");
				$(this).attr('value','');
				return false;
			}
    	}
		
		$(this).siblings(".type-file-text").val(filepath);
	});

	$(".type-file-box").hover(function(){
		$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");
	});
	/* file上传文件类型 封装函数 end*/
	
	
	/*************************************平台、商家、商城前台 通用 start*************************************/
	/* jq仿select下拉选框 start */
	$(document).on("click",".imitate_select .cite",function(){
		$(".imitate_select ul").hide();
		$(this).parents(".imitate_select").find("ul").show();
		$(this).siblings("ul").perfectScrollbar("destroy");
		$(this).siblings("ul").perfectScrollbar();
	});
	
	$(document).on("click",".imitate_select li a",function(){
		var _this = $(this);
		var val = _this.data('value');
		var text = _this.html();
		_this.parents(".imitate_select").find(".cite").html(text);
		_this.parents(".imitate_select").find("input[type=hidden]").val(val);
		_this.parents(".imitate_select").find("ul").hide();
	});
	/* jq仿select下拉选框 end */
	/*************************************平台、商家、商城前台 通用 end*************************************/
});

/**
 * 
 * 公共js 函数库 start
 * $Author: sunle and kong $ 
 *
**/

/****************************jq仿select带返回函数 start*******************************/
jQuery.divselect = function(divselectid,inputselectid,fn) {
	var obj = "",
		txt = "",
		input = "",
		val = 0;
	
	$(document).on('click',divselectid+" .cite",function(event){
		event.stopImmediatePropagation();
		
		obj = $(this).parents(divselectid).find("ul");
		
		$(".imitate_select").find("ul").hide();

		if(obj.css("display")=="none"){
			obj.css("display","block");
		}else{
			obj.css("display","none");
		}
		
		obj.perfectScrollbar("destroy");
		obj.perfectScrollbar();
	});
	
	$(document).on("click",divselectid+" ul li a",function(event){
		event.stopImmediatePropagation();
		
		obj = $(this).parents(divselectid);
		input = obj.find(inputselectid);
		txt = $(this).text();
		val = $(this).data("value");
		
		obj.find(".cite").html(txt);
		
		obj.find("ul").hide();

		input.val(val);
		
		if(fn){
			fn($(this));
		}		
	});
	
	$(document).on("click",function(){
		$(divselectid+" ul").hide();
	});
};

/* jq仿select下拉 默认值赋值 */
function reset_select(obj){
	$(obj).find('.imitate_select').each(function(){
		var sel_this = $(this);
		var val = sel_this.children('input[type=hidden]').val();
		sel_this.find('a').each(function(){
			if($(this).attr('data-value') == val){
				sel_this.children('.cite').html($(this).html());
			}
		})
	});
}
/****************************jq仿select带返回函数 end*********************************/


/****************************后台商品详情-商品描述-电脑端手机端start**************************/
var pannel_div = "";

/* 后台商品详情-手机描述展示区域（添加图片）*/
function append_mobile_img(obj){
	var url = "",
		con = $(obj).find("*[ectype='pic_list']"),
		cur = con.find("li.current");
		
	if(cur.length>0){
		cur.each(function(){
			url = $(this).data("url");
			pannel_div = "<div class='section s-img'><div class='img'><img src='"+url+"' /></div><div class='tools'><a href='javascript:void(0);' class='move-up icon-arrow-up'></a><a href='javascript:void(0);' class='move-down icon-arrow-down'></a><a href='javascript:void(0);' class='move-remove'><i class='icon icon-remove'></i>删除</a><div class='cover'></div></div></div>";
			$(".section_warp").append(pannel_div);
		});
	}
	$(".section_warp").find(".section:first").find(".move-up").addClass("disabled");
	$(".section_warp").find(".section:last").find(".move-down").addClass("disabled");
	
	hiddenInput();
}

/* 后台商品详情-手机描述展示区域（添加文字）*/
function append_mobile_text(obj){
	var text = $(obj).find(".dialogTextarea").val();

	text = text.replace(",","，");
	text = text.replace("'","‘");
	text = text.replace('"',"“");
	text = text.replace('&',"&amp;");
        
	pannel_div = "<div class='section s-txt'><div class='txt'>"+text+"</div><div class='tools'><a href='javascript:void(0);' class='move-up icon-arrow-up'></a><a href='javascript:void(0);' class='move-down icon-arrow-down'></a><a href='javascript:void(0);' class='move-edit' ectype='move_edit_touch'><i class='icon icon-edit'></i>编辑</a><a href='javascript:void(0);' class='move-remove'><i class='icon icon-remove'></i>删除</a><div class='cover'></div></div></div>";
	$(".section_warp").append(pannel_div);
	$(".section_warp").find(".section:first").find(".move-up").addClass("disabled");
	$(".section_warp").find(".section:last").find(".move-down").addClass("disabled");
	
	hiddenInput();
}

/*
** 
** 后台商品详情-手机端详情内容操作 start
**
*/
//文字模块编辑

$(document).on("click","[ectype='move_edit_touch']",function(){
    var log_type = "word",
    content = $(this).parents(".section").find(".txt").html(),
    title = "添加文字";
    $.jqueryAjax('goods.php', 'act=gallery_album_dialog&log_type='+log_type + "&content=" + content, function(data){
            goods_visual_desc(title,815,data.content,function(){
                            append_mobile_text("#goodsMobile");
            });
    });
})
/* 模块上移 */
$(document).on("click",".move-up",function(){
	var _this = $(this);
	var _div = _this.parents(".section");
	var prev_div = _div.prev();
	
	var clone = _div.clone();
	if(!_this.hasClass("disabled")){
		_div.remove();
		prev_div.before(clone);
		disabled();
		hiddenInput();
	}
});

/* 模块下移 */
$(document).on("click",".move-down",function(){
	var _this = $(this);
	var _div = _this.parents(".section");
	var next_div = _div.next();
	
	var clone = _div.clone();
	if(!_this.hasClass("disabled")){
		_div.remove();
		next_div.after(clone);
		disabled();
		hiddenInput();
	}
});

/* 删除模块 */
$(document).on("click",".move-remove",function(){
	var _this = $(this);
	_this.parents(".section").remove();
	disabled();
	hiddenInput();
});

/* 判断模块是顶部模块或底部模块 */
function disabled(){
	var demo = $("[ectype='mobile_pannel']");
	demo.find(".section .move-up").removeClass("disabled");
	demo.find(".section:first .move-up").addClass("disabled");
	
	demo.find(".section .move-down").removeClass("disabled");
	demo.find(".section:last .move-down").addClass("disabled");
}

/* 把手机端描述编辑内容 保存到隐藏域 */
function hiddenInput(){
	var obj = $(".section_warp");
	var clone = obj.clone();
	//clone.find(".tools").remove();
	$("input[name='desc_mobile']").val(clone.html());
}

/* 后台商品详情-手机端详情内容操作 end*/
	
/****************************后台商品详情-商品描述-电脑端手机端end****************************/


/***********************************图片库相关方法start***********************************/

/* 弹出层图片库中选中的图片保存后执行方法（分为多选和单选）*/
function insert_img(pic_id,inid,img_src){
	/**
	** pic_id 图片库中的选中图片的图片id
	** inid 触发图片库标识，用于判断
	** img_src 图片库中的图片多选时用到，多个图片拼接后的字符串
	**/
	
	if(pic_id > 0){
		if(inid == 'gallery_album_dsc'){
			//商品详情中的电脑端百度编辑器 图片库选择图片
			var content = $("input[name='goods_desc']").val();
			$.jqueryAjax('get_ajax_content.php', 'act=getFCKeditor&content='+encodeURIComponent(content)+"&img_src="+img_src, function(data){
				$("#FCKeditor").html(data.goods_desc);
			});
		}else{
			$.jqueryAjax('get_ajax_content.php', 'is_ajax=1&act=insert_goodsImg' + '&pic_id=' + pic_id + '&goods_id=' + goods_id + "&inid="+inid, function(data){
				if(data.error > 0){
					alert(data.message);
				}else{
					if(inid == 'addAlbumimg'){
						//商品详情页相册图片库选择图片
						if(data.img_id > 0){
                                                    var html = "";
                                                    if(data.img_id == data.min_img_id){
                                                            html = "主图";
                                                            $("#ul_pics").find(".zt").each(function(){
                                                                    $(this).html("");
                                                            });
                                                    }
                                                    $("#gallery_"+data.min_img_id).find(".zt").html("主图");
                                                    var img_html = "<li id='gallery_" + data.img_id + "'><div class='img' onclick='img_default("+data.img_id+")'><img src='../" + data.data['goods_thumb']+ "'/></div><div class='info'><span class='zt red'>"+html +"</span><div class='sort'><span>排序：</span><input type='text' name='sort' value='"+ data.img_desc + "' class='stext' /></div><a href='javascript:void(0);' class='delete_img' data-imgid='"+data.img_id+"'><i class='icon icon-trash'></i></a></div><div class='info'><input name='external_url' type='text' class='text w130' ectype='external_url' value='' title='' data-imgid='" + data.img_id + "' placeholder='图片外部链接地址' onfocus='if (this.value == '图片外部链接地址'){this.value='http://';this.style.color='#000';}></div></li>";
                                                    $("#ul_pics").append(img_html);
                                                }else{
                                                    alert('图片'+ data.data['goods_thumb'] + '"已存在！');
                                                }
					}else{
						//商品详情页商品主图图片库选择图片
						$("#goods_figure").html("<div class='img'><img src='../" + data.data['goods_thumb'] + "'/><div class='edit_images'>更换图片</div></div>");
						$("input[name=original_img]").val(data.data['original_img']);
						$("input[name=goods_img]").val(data.data['goods_img']);
						$("input[name=goods_thumb]").val(data.data['goods_thumb']);
					}
				}
			});
		}
	}else{
		alert("系统出错，请重新选择图片");
	}
}

/* 图片库弹出窗 */
function goods_visual_desc(title,width,content,onOk){
	pb({
		id:"goodsMobile",
		title:title,
		width:width,
		content:content,
		ok_title:"确定",
		cl_title:"取消",
		drag:true,
		foot:true,
		cl_cBtn:true,
		onOk:onOk
	});
}

/*后台 图片库弹窗 选择使用图片（分为单复选）*/
$(document).on("click","*[ectype='pic_replace'] li",function(){
	var length = $(this).siblings(".current").length;
	var type = $(this).parents("*[ectype='pic_replace']").data("type");
	if(type == "check"){
		if(length<20){
			if($(this).hasClass("current")){
				$(this).removeClass("current");
			}else{
				$(this).addClass("current");
			}
		}else{
			alert("图片不能超过20张");
		}
	}else{
		if($(this).hasClass("current")){
			$(this).removeClass("current");
		}else{
			$(this).addClass("current").siblings().removeClass("current");
		}
	}
});

/*后台 弹窗 动态添加图片库相册 */
$(document).on("click","[ectype='add_album']",function(){
	Ajax.call('index.php?&con=visual_editing&is_ajax=1&fun=add_albun_pic', '', add_albumResponse , 'POST', 'JSON');
});


/*添加图片库相册回调方法 弹出窗口*/
function add_albumResponse(data){
	var content = data.content;
	pb({
		id: "add_albun_piccomtent",
		title: "图片编辑器",
		width: 950,
		content: content,
		ok_title: "确定",
		drag: true,
		foot: true,
		cl_cBtn: false,
		onOk: function () {
			var parents = $("#add_albun_pic");
			var required = parents.find("*[ectype='required']");

			if(validation(required) == true){
				var actionUrl = "index.php?&con=visual_editing&fun=add_albun_pic";
				$("#add_albun_pic").ajaxSubmit({
					type: "POST",
					dataType: "json",
					url: actionUrl,
					data: { "action": "TemporaryImage" },
					success: function (data) {
						if (data.error == "0") {
							alert(data.content);
						}else{
							$("[ectype='album_list_check']").html(data.content)
							$("input[name='album_id']").val(data.pic_id);
							
							changedpic(data.pic_id,"",1,0);
							
							album_select(1);
						}
						return true;
					},
					async: true
				});
				return true;
			}else{
				return false;
			}
		}
	});
}

/* 相册选择仿select下拉 默认值赋值 */
function album_select(type,mark){
	/*
	**
	** type判断是否是可视化图片库弹出 
	** type = 0 表示是可视化图片库弹出
	** type = 1 表示不是可视化图片库弹出
	**
	*/
	
	var obj = $("*[ectype='albumFilter']").find(".imitate_select"),
		str = $.cookie('albumFilterDefalt'),
		arr = new Array(),
		inid = "";
	
	if(str){
		arr = str.split(",");
	}
	
	if(type == 1){
		$("[ectype='pic_list']").html('<i class="icon-spinner icon-spin"></i>');
	}else{
		$("[ectype='pic_list']").html("<i class='icon-spin'><img src='images/visual/load.gif' width='30' height='30'></i>");
	}
	
	if(mark){
		inid = mark;
	}

	setTimeout(function(){
		for(i=0;i<arr.length;i++){
			obj.find("input[type='hidden']").eq(i).val(arr[i]);
		}
		if(arr[1] == '' || arr[1] == 'undefined'){
                    arr[1] = 2;
                }
                
		changedpic(arr[0],"",type,arr[1],inid);

		obj.each(function(index, element) {
			var obj = $(this);
			
			obj.find("input[type='hidden']").eq(index).val(arr[index]);
			
			obj.find("li").each(function(){
				var val = $(this).find("a").data("value");
				var text = $(this).find("a").html();
				if(val == arr[index]){
					obj.find(".cite").html(text);
				}
			});
		});
	},300);
}

/* 切换图片库 */
function changedpic(val,obj,is_vis,sort,inid){
	/*
	**
	** val 表示选中图片库的值
	** obj 表示选中图片库的对象
	** is_vis 判断是否图库类型，0表示是可视化图片库，1表示普通图片库弹窗多选，2表示普通图片库弹窗单选
	** sort 弹出图片库选中筛选排序值
	** inid 商品详情页图片库3个图片库标识；商品图片:gallery_album、电脑端商品描述:gallery_album_desc、商品相册:addAlbumimg
	**
	*/
	
	var album_id 	= 0,
		sort_name 	= 0,
		where 		= "",
		str 		= "";
	
	if(val > 0){
		album_id = val;
	}else{
		album_id = $("input[name='album_id']").val();
	}
	
	if(sort){
		sort_name = sort;
	}else{
		sort_name = $("input[name='sort_name']").val();
	}
	
	if(inid){
		where = "&inid=" + inid;
	}
    
	Ajax.call('index.php?&con=visual_editing&fun=get_albun_pic&is_ajax=1', "sort_name="+sort_name+"&album_id="+album_id+"&is_vis=" + is_vis + where, function(data){
		if(obj){
		    if(obj.parents("*[ectype='album-warp']").html()){
			obj = obj.parents("*[ectype='album-warp']");
			obj.find("[ectype='pic_list']").html(data.content);
			}else{$("[ectype='pic_list']").html(data.content);}
		}else{
			$("[ectype='pic_list']").html(data.content);
		}
		
		$("[ectype='pic_list']").perfectScrollbar("destroy");
		$("[ectype='pic_list']").perfectScrollbar();
		
		if(is_vis != 1){
			//可视化弹出图片库选择
			if(obj){
				var id = $(obj).parents(".pb").attr("id");
				pbct("#" + id);
			}else{
				pbct();
			}
		}
	} , 'POST', 'JSON');
	
	var str = album_id + ',' + sort_name;
	albumFilterDefalt(str);
}

/*设置 弹出图片库 图库筛选选择值存入cookie 方便下次默认选中上一次选中的值*/
function albumFilterDefalt(str){
	$.cookie('albumFilterDefalt', str , { expires: 1 ,path:'/'});
}
/* 新闻切换库 */
function changednew(val,obj,is_vis,sort,inid){
	/*
	**
	** val 表示选中图片库的值
	** obj 表示选中图片库的对象
	** is_vis 判断是否图库类型，0表示是可视化图片库，1表示普通图片库弹窗多选，2表示普通图片库弹窗单选
	** sort 弹出图片库选中筛选排序值
	** inid 商品详情页图片库3个图片库标识；商品图片:gallery_album、电脑端商品描述:gallery_album_desc、商品相册:addAlbumimg
	**
	*/
	
	var album_id 	= 0,
		sort_name 	= 0,
		where 		= "",
		str 		= "";
	
	if(val > 0){
		album_id = val;
	}else{
		album_id = $("input[name='cmsnew_id']").val();
	}
	

	
	if(inid){
		where = "&inid=" + inid;
	}
    
	Ajax.call('index.php?&con=visual_editing&fun=get_news&is_ajax=1', "album_id="+album_id+"&is_vis=" + is_vis + where, function(data){
		if(obj){
		    if(obj.parents("*[ectype='album-warp']").html()){
			obj = obj.parents("*[ectype='album-warp']");
			obj.find("[ectype='cmsnew_list']").html(data.content);
			}else{$("[ectype='cmsnew_list']").html(data.content);}
		}else{
			$("[ectype='cmsnew_list']").html(data.content);
		}
		
		$("[ectype='cmsnew_list']").perfectScrollbar("destroy");
		$("[ectype='cmsnew_list']").perfectScrollbar();
		
		if(is_vis != 1){
			//可视化弹出图片库选择
			if(obj){
				var id = $(obj).parents(".pb").attr("id");
				pbct("#" + id);
			}else{
				pbct();
			}
		}
	} , 'POST', 'JSON');
	
	var str = album_id;
	newsFilterDefalt(str);
}
/* 新闻切换库 */
function z_changednew(val,obj,is_vis,sort,inid){
	/*
	**
	** val 表示选中图片库的值
	** obj 表示选中图片库的对象
	** is_vis 判断是否图库类型，0表示是可视化图片库，1表示普通图片库弹窗多选，2表示普通图片库弹窗单选
	** sort 弹出图片库选中筛选排序值
	** inid 商品详情页图片库3个图片库标识；商品图片:gallery_album、电脑端商品描述:gallery_album_desc、商品相册:addAlbumimg
	**
	*/
	
	var album_id 	= 0,
		sort_name 	= 0,
		where 		= "",
		str 		= "";
	
	if(val > 0){
		album_id = val;
	}else{
		album_id = $("input[name='z_cmsnew_id']").val();
	}
	

	
	if(inid){
		where = "&inid=" + inid;
	}
    
	Ajax.call('index.php?&con=visual_editing&fun=get_news&is_ajax=2', "album_id="+album_id+"&is_vis=" + is_vis + where, function(data){
		if(obj){
		    if(obj.parents("*[ectype='z_album-warp']").html()){
			obj = obj.parents("*[ectype='z_album-warp']");
			obj.find("[ectype='z_cmsnew_list']").html(data.content);
			}else{$("[ectype='z_cmsnew_list']").html(data.content);}
		}else{
			$("[ectype='z_cmsnew_list']").html(data.content);
		}
		
		$("[ectype='z_cmsnew_list']").perfectScrollbar("destroy");
		$("[ectype='z_cmsnew_list']").perfectScrollbar();
		
		if(is_vis != 1){
			//可视化弹出图片库选择
			if(obj){
				var id = $(obj).parents(".pb").attr("id");
				pbct("#" + id);
			}else{
				pbct();
			}
		}
	} , 'POST', 'JSON');
	
	var str = album_id;
	newsFilterDefalt(str);
}
/* 新闻切换库 */
function r_changednew(val,obj,is_vis,sort,inid){
	/*
	**
	** val 表示选中图片库的值
	** obj 表示选中图片库的对象
	** is_vis 判断是否图库类型，0表示是可视化图片库，1表示普通图片库弹窗多选，2表示普通图片库弹窗单选
	** sort 弹出图片库选中筛选排序值
	** inid 商品详情页图片库3个图片库标识；商品图片:gallery_album、电脑端商品描述:gallery_album_desc、商品相册:addAlbumimg
	**
	*/
	
	var album_id 	= 0,
		sort_name 	= 0,
		where 		= "",
		str 		= "";
	
	if(val > 0){
		album_id = val;
	}else{
		album_id = $("input[name='r_cmsnew_id']").val();
	}
	

	
	if(inid){
		where = "&inid=" + inid;
	}
    
	Ajax.call('index.php?&con=visual_editing&fun=get_news&is_ajax=3', "album_id="+album_id+"&is_vis=" + is_vis + where, function(data){
		if(obj){
		    if(obj.parents("*[ectype='r_album-warp']").html()){
			obj = obj.parents("*[ectype='r_album-warp']");
			obj.find("[ectype='r_cmsnew_list']").html(data.content);
			}else{$("[ectype='r_cmsnew_list']").html(data.content);}
		}else{
			$("[ectype='r_cmsnew_list']").html(data.content);
		}
		
		$("[ectype='r_cmsnew_list']").perfectScrollbar("destroy");
		$("[ectype='r_cmsnew_list']").perfectScrollbar();
		
		if(is_vis != 1){
			//可视化弹出图片库选择
			if(obj){
				var id = $(obj).parents(".pb").attr("id");
				pbct("#" + id);
			}else{
				pbct();
			}
		}
	} , 'POST', 'JSON');
	
	var str = album_id;
	newsFilterDefalt(str);
}
/*设置 弹出新闻筛选选择值存入cookie 方便下次默认选中上一次选中的值*/
function newsFilterDefalt(str){
	$.cookie('newsFilterDefalt', str , { expires: 1 ,path:'/'});
}
/* 弹窗内图片库分页 */
function gallery_album_list_pb(obj,page,type) {
	var _this = $(obj).parents('.gallery_album');
	var where = '';
	var inid = _this.data("inid");
	var act = _this.data("act");
	var actionUrl = _this.data("url");
	var datawhere = _this.data("where");
	var url = (actionUrl) ? actionUrl : 'get_ajax_content.php';
	var is_goods = _this.data("goods");
	var is_vis = _this.data("vis");

	if(is_vis != 1){
		is_vis = 0;
	}

	where += "&is_vis=" + is_vis;
	page = parseInt(page);
	if(page){
		
		if(type == 'next'){
			//下一页
			page = page+1;
		}else if(type == 'prev'){
			//上一页
			page = page-1;
		}
		where += "&page="+page;
	}
	if(datawhere){
		where += "&" + datawhere;
	}
	if(act == 'brand_query'){
		var brand_ids = $("input[name='brand_ids']").val();
		where += "&brand_ids=" + brand_ids;
	}
	if(is_goods == 1){
		var goods_ids = $(obj).parents(".modal-body").find("input[name='goods_ids']").val();
		where += "&goods_ids=" + goods_ids;
	}
	
	$.jqueryAjax(url, 'act='+act + where, function(data){
		$("[ectype='"+inid+"']").html(data.content);
		$("[ectype='"+inid+"']").perfectScrollbar("destroy");
		$("[ectype='"+inid+"']").perfectScrollbar();
	});
};

/*************************图片库end*******************************/

/* 判断弹框高度，如果有多个弹框同时出现需要传obj定位，一个弹出则不需要 */
function pbct(obj){
	var height = 0;
	
	if(obj){
		var obj = $(obj);
		pbct = obj.find(".pb-ct");
	}else{
		var pbct = $(".pb-ct");
	}
	
	height = pbct.height();
	
	if(height>499){
		pbct.css({"overflow":"hidden"})
		
		$(".pb-ct").perfectScrollbar("destroy");
		$(".pb-ct").perfectScrollbar();
	}
}

/*************************弹出框显示设置验证 start**********************/
/* 弹窗验证 */
function validation(required){
	var val = "";
	var msg = "";
	var flog = true;
	required.each(function(){
		val = $(this).val();
		msg = $(this).data("msg");
		if(val == ""){
			alert(msg);
			flog = false;
			return false;
		}else{
			flog = true;
		}
	});
	return flog;
}
/*************************弹出框显示设置验证 end**********************/
