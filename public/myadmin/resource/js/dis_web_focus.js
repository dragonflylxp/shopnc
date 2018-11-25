  var screen_max = 5;//大图数
  var adv_max = 1;//右侧广告图
  var focus_max = 5;//小图组数
  var two_focus_max = 6;//小图组数
  var pic_max = 3;//组内小图数
  var two_pic_max = 2; //2张广告图
  var eight_pic_max = 8; //8张广告图
  var screen_obj = {};
  var adv_obj = {};
  var ap_obj = {};
  var upload_obj = {};
  var adv_upload_obj = {};
  var focus_obj = {};
  var two_focus_obj = {};
  var eight_focus_obj = {};
  var focus_ap_obj = {};
  var two_focus_ap_obj = {};
  var eight_focus_ap_obj = {};
  var focus_upload_obj = {};
  var two_focus_upload_obj = {};
  var eight_focus_upload_obj = {};
$(function(){
    $('#screen_color').colorpicker({showOn:'both'});//初始化切换大图背景颜色控件
    $('#screen_color').parent().css("width",'');
    $('#screen_color').parent().addClass("color");
    $('#ap_color').colorpicker({showOn:'both'});//初始化广告位背景颜色控件
    $('#ap_color').parent().css("width",'');
    $('#ap_color').parent().addClass("color");
	$(".type-file-file").change(function() {//初始化图片上传控件
		$(this).prevAll(".type-file-text").val($(this).val());
	});
	$("#homepageFocusTab .tab-base li a").click(function() {//切换
	    var pic_form = $(this).attr("form");
	    $('form').hide();
	    $("#homepageFocusTab .tab-base li a").removeClass("current");
	    $('#'+pic_form).show();
	    $(this).addClass("current");
	});
	screen_obj = $("#upload_screen_form");//初始化焦点大图区数据
    ap_obj = $("#ap_screen");
    upload_obj = $("#upload_screen");
	screen_obj.find("ul").sortable({ items: 'li' });
	$("#ap_id_screen").append(screen_adv_append);

	adv_obj = $("#upload_adv_form");//初始化右侧广告图区域数据
	adv_upload_obj = $("#upload_adv");
	adv_obj.find("ul").sortable({ items: 'li' });

	focus_obj = $("#upload_focus_form");//初始化三张联动区数据
	focus_obj.find(".focus-trigeminy").sortable({ items: 'div[focus_id]' });
	focus_obj.find("ul").sortable({ items: 'li' });
	two_focus_obj = $("#upload_two_focus_form");//初始化2张联动区数据
	two_focus_obj.find(".two-focus-trigeminy").sortable({ items: 'div[focus_id]' });
	two_focus_obj.find("ul").sortable({ items: 'li' });
	eight_focus_obj = $("#upload_eight_focus_form");//初始化8张联动区数据
	eight_focus_obj.find(".eight-focus-trigeminy").sortable({ items: 'div[focus_id]' });
	eight_focus_obj.find("ul").sortable({ items: 'li' });
	$("#ap_id_focus").append(focus_adv_append);
    focus_ap_obj = $("#ap_focus");
	two_focus_ap_obj = $("#ap_two_focus");
	eight_focus_ap_obj = $("#ap_eight_focus");
    focus_upload_obj = $("#upload_focus");
	two_focus_upload_obj = $("#upload_two_focus");
	eight_focus_upload_obj = $("#upload_eight_focus");
	focus_edit_obj = $("#edit_focus");
	two_focus_edit_obj = $("#edit_two_focus");
	eight_focus_edit_obj = $("#edit_eight_focus");
});

//焦点区切换大图上传
function add_screen(add_type) {//增加图片
	for (var i = 1; i <= screen_max; i++) {//防止数组下标重复
		if (screen_obj.find("li[screen_id='"+i+"']").size()==0) {//编号不存在时添加
    	    var text_input = '';
    	    var text_type = '图片调用';
    	    var ap = 0;
    	    text_input += '<input name="screen_list['+i+'][pic_id]" value="'+i+'" type="hidden">';
    	    text_input += '<input name="screen_list['+i+'][pic_name]" value="" type="hidden">';
    	    if(add_type == 'adv') {
    	        ap = 1;
    	        text_type = '广告调用';
    	        text_input += '<input name="screen_list['+i+'][ap_id]" value="" type="hidden">';
    	    } else {
    	        text_input += '<input name="screen_list['+i+'][pic_url]" value="" type="hidden">';
    	    }
    	    text_input += '<input name="screen_list['+i+'][color]" value="" type="hidden">';
    	    text_input += '<input name="screen_list['+i+'][pic_img]" value="" type="hidden">';
			var add_html = '';
			add_html = '<li ap="'+ap+'" screen_id="'+i+'" title="可上下拖拽更改显示顺序"><div class="title"><h4>'+text_type+
			'</h4><a class="ncap-btn-mini del" href="JavaScript:del_screen('+i+
			');"><i class="fa fa-trash"></i>删除</a></div>' +
				'<div class="focus-thumb" onclick="select_screen('+i+');" title="点击编辑选中区域内容"><img src="'+ADMIN_TEMPLATES_URL
			+'/images/picture.gif" /></div>'+text_input+'</li>';
			screen_obj.find("ul").append(add_html);
			select_screen(i);
			break;
		}
    }
}
function screen_pic(pic_id,pic_img) {//更新图片
	if (pic_img!='') {
	    var color = screen_obj.find("input[name='screen_pic[color]']").val();
	    var pic_name = screen_obj.find("input[name='screen_pic[pic_name]']").val();
	    var pic_url = screen_obj.find("input[name='screen_pic[pic_url]']").val();
	    var obj = screen_obj.find("li[screen_id='"+pic_id+"']");
	    obj.find("img").attr("src",UPLOAD_SITE_URL+'/'+pic_img);
	    obj.find("img").attr("title",pic_name);
        obj.find("input[name='screen_list["+pic_id+"][pic_name]']").val(pic_name);
        obj.find("input[name='screen_list["+pic_id+"][pic_url]']").val(pic_url);
        obj.find("input[name='screen_list["+pic_id+"][color]']").val(color);
        obj.find("input[name='screen_list["+pic_id+"][pic_img]']").val(pic_img);
	    obj.find(".focus-thumb").css("background-color",color);
	}
	screen_obj.find('.web-save-succ').show();
	setTimeout("screen_obj.find('.web-save-succ').hide()",2000);
}
function screen_ap(pic_id,color) {//更新广告位
    var obj = screen_obj.find("li[screen_id='"+pic_id+"']");
    obj.find(".focus-thumb").css("background-color",color);
	screen_obj.find('.web-save-succ').show();
	setTimeout("screen_obj.find('.web-save-succ').hide()",2000);
}
function select_screen(pic_id) {//选中图片
    var obj = screen_obj.find("li[screen_id='"+pic_id+"']");
    var ap = obj.attr("ap");
    screen_obj.find("li").removeClass("selected");
    screen_obj.find("input[name='key']").val(pic_id);
    obj.addClass("selected");
    if(ap == '1') {
        upload_obj.hide();
        screen_obj.find("input[name='ap_pic_id']").val(pic_id);
        var a_id = obj.find("input[name='screen_list["+pic_id+"][ap_id]']").val();
        if(a_id == '') {//未选择广告位时用默认的
            $("#ap_id_screen").trigger("onchange");
        } else {
            var color = obj.find("input[name='screen_list["+pic_id+"][color]']").val();
            $("#ap_id_screen").val(a_id);
            $("#ap_color").val(color);
            ap_obj.find('.evo-pointer').css("background-color",color);
        }
        ap_obj.show();
    } else {
        ap_obj.hide();
        var pic_name = obj.find("input[name='screen_list["+pic_id+"][pic_name]']").val();
        var pic_url = obj.find("input[name='screen_list["+pic_id+"][pic_url]']").val();
        var color = obj.find("input[name='screen_list["+pic_id+"][color]']").val();
        $("input[name='screen_id']").val(pic_id);
        $("input[name='screen_pic[pic_name]']").val(pic_name);
        $("input[name='screen_pic[pic_url]']").val(pic_url);
        $("input[name='screen_pic[color]']").val(color);
        upload_obj.find(".type-file-file").val('');
        upload_obj.find(".type-file-text").val('');
        upload_obj.show();
        upload_obj.find('.evo-pointer').css("background-color",color);
    }
}
function select_ap_screen() {//选择广告位
    ap_id = $("#ap_id_screen").val();
    if (ap_id > 0 && typeof screen_adv_list[ap_id] !== "undefined") {
        var adv = screen_adv_list[ap_id];
	    var color = $("#ap_color").val();
	    var pic_name = adv['ap_name'];
	    var pic_img = adv['ap_img'];
	    var obj = screen_obj.find("li.selected");
	    var pic_id = obj.attr("screen_id");
	    obj.find("img").attr("src",UPLOAD_SITE_URL+'/'+ATTACH_ADV+'/'+pic_img);
	    obj.find("img").attr("title",pic_name);
        obj.find("input[name='screen_list["+pic_id+"][pic_name]']").val(pic_name);
        obj.find("input[name='screen_list["+pic_id+"][ap_id]']").val(ap_id);
        obj.find("input[name='screen_list["+pic_id+"][color]']").val(color);
        obj.find("input[name='screen_list["+pic_id+"][pic_img]']").val(ATTACH_ADV+'/'+pic_img);
	    obj.find(".focus-thumb").css("background-color",color);
    }
}
function del_screen(pic_id) {//删除图片
    if (screen_obj.find("li").size()<2) {
         return;//保留一个
    }
	screen_obj.find("li[screen_id='"+pic_id+"']").remove();
	var slide_id = screen_obj.find("input[name='key']").val();
	if (pic_id==slide_id) {
    	screen_obj.find("input[name='key']").val('');
    	ap_obj.hide();
    	upload_obj.hide();
	}
}

  //右侧广告图上传
  function add_adv(add_type) {//增加图片
	  for (var i = 1; i <= adv_max; i++) {//防止数组下标重复
		  if (adv_obj.find("li[adv_id='"+i+"']").size()==0) {//编号不存在时添加
			  var text_input = '';
			  var text_type = '图片调用';
			  var ap = 0;
			  text_input += '<input name="adv_list['+i+'][pic_id]" value="'+i+'" type="hidden">';
			  text_input += '<input name="adv_list['+i+'][pic_name]" value="" type="hidden">';
			  text_input += '<input name="adv_list['+i+'][pic_url]" value="" type="hidden">';
			  text_input += '<input name="adv_list['+i+'][color]" value="" type="hidden">';
			  text_input += '<input name="adv_list['+i+'][pic_img]" value="" type="hidden">';
			  var add_html = '';
			  add_html = '<li ap="'+ap+'" adv_id="'+i+'" title="可上下拖拽更改显示顺序"><div class="title"><h4>'+text_type+
				  '</h4><a class="ncap-btn-mini del" href="JavaScript:del_adv('+i+
				  ');"><i class="fa fa-trash"></i>删除</a></div>' +
				  '<div class="focus-thumb" onclick="select_adv('+i+');" title="点击编辑选中区域内容"><img src="'+ADMIN_TEMPLATES_URL
				  +'/images/picture.gif" /></div>'+text_input+'</li>';
			  adv_obj.find("ul").append(add_html);
			  select_adv(i);
			  break;
		  }
	  }
  }
  function adv_pic(pic_id,pic_img) {//更新图片
	  if (pic_img!='') {
		  var color = adv_obj.find("input[name='adv_pic[color]']").val();
		  var pic_name = adv_obj.find("input[name='adv_pic[pic_name]']").val();
		  var pic_url = adv_obj.find("input[name='adv_pic[pic_url]']").val();
		  var obj = adv_obj.find("li[adv_id='"+pic_id+"']");
		  obj.find("img").attr("src",UPLOAD_SITE_URL+'/'+pic_img);
		  obj.find("img").attr("title",pic_name);
		  obj.find("input[name='adv_list["+pic_id+"][pic_name]']").val(pic_name);
		  obj.find("input[name='adv_list["+pic_id+"][pic_url]']").val(pic_url);
		  obj.find("input[name='adv_list["+pic_id+"][color]']").val(color);
		  obj.find("input[name='adv_list["+pic_id+"][pic_img]']").val(pic_img);
		  obj.find(".focus-thumb").css("background-color",color);
	  }
	  adv_obj.find('.web-save-succ').show();
	  setTimeout("adv_obj.find('.web-save-succ').hide()",2000);
  }
  function select_adv(pic_id) {//选中图片
	  var obj = adv_obj.find("li[adv_id='"+pic_id+"']");
	  adv_obj.find("li").removeClass("selected");
	  adv_obj.find("input[name='key']").val(pic_id);
	  obj.addClass("selected");

	  var pic_name = obj.find("input[name='adv_list["+pic_id+"][pic_name]']").val();
	  var pic_url = obj.find("input[name='adv_list["+pic_id+"][pic_url]']").val();
	  var color = obj.find("input[name='adv_list["+pic_id+"][color]']").val();
	  $("input[name='adv_id']").val(pic_id);
	  $("input[name='adv_pic[pic_name]']").val(pic_name);
	  $("input[name='adv_pic[pic_url]']").val(pic_url);
	  $("input[name='adv_pic[color]']").val(color);
	  adv_upload_obj.find(".type-file-file").val('');
	  adv_upload_obj.find(".type-file-text").val('');
	  adv_upload_obj.show();
	  adv_upload_obj.find('.evo-pointer').css("background-color",color);
  }
  function del_adv(pic_id) {//删除图片
	  adv_obj.find("li[adv_id='"+pic_id+"']").remove();
	  var slide_id = adv_obj.find("input[name='key']").val();
	  if (pic_id==slide_id) {
		  adv_obj.find("input[name='key']").val('');
		  adv_upload_obj.hide();
	  }
  }

//焦点区切换小图上传
function add_focus(add_type) {//增加
	for (var i = 1; i <= focus_max; i++) {//防止数组下标重复
		if (focus_obj.find("div[focus_id='"+i+"']").size()==0) {//编号不存在时添加
			var add_html = '';
			var img_type = '<img width="20" height="20" title="" src="'+ADMIN_TEMPLATES_URL+'/images/picture.gif"/>';
			var text_type = '';
			text_type += '<div class="name">';
			text_type += '图片调用';
			text_type += '</div>';
			if(add_type == 'adv') {
			    text_type = '广告调用';
			}
			add_html = '<div focus_id="'+i+'" class="focus-trigeminy-group" title="可上下拖拽更改显示顺序">'+'<div class="title">'+img_type+'<h4>'+text_type+'</h4>'+
					'<input name="focus_list['+i+'][group_list][group_name]" value="" type="hidden">' +
					'<input name="focus_list['+i+'][group_list][group_image]" value="" type="hidden">' +
			'<a class="ncap-btn-mini del" href="JavaScript:edit_focus('+i+');"><i class="fa fa-pencil-square-o"></i>编辑</a>' +
				'<a class="ncap-btn-mini del" href="JavaScript:del_focus('+i+');"><i class="fa fa-trash"></i>删除</a></div><ul></ul></div>';
			focus_obj.find("#btn_add_list").before(add_html);
			for (var pic_id = 1; pic_id <= pic_max; pic_id++) {
			    var text_append = '';
			    text_append += '<li list="'+add_type+'" pic_id="'+pic_id+'" onclick="select_focus('+i+',this);" title="可左右拖拽更改图片排列顺序">';
				text_append += '<div class="focus-thumb">';
			    text_append += '<img title="" src="'+ADMIN_TEMPLATES_URL+'/images/picture.gif"/>';
				text_append += '</div>';
        	    text_append += '<input name="focus_list['+i+'][pic_list]['+pic_id+'][pic_id]" value="'+pic_id+'" type="hidden">';
        	    text_append += '<input name="focus_list['+i+'][pic_list]['+pic_id+'][pic_name]" value="" type="hidden">';
        	    if(add_type == 'adv') {
        	        text_append += '<input name="focus_list['+i+'][pic_list]['+pic_id+'][ap_id]" value="" type="hidden">';
        	    } else {
        	        text_append += '<input name="focus_list['+i+'][pic_list]['+pic_id+'][pic_url]" value="" type="hidden">';
        	    }
        	    text_append += '<input name="focus_list['+i+'][pic_list]['+pic_id+'][pic_img]" value="" type="hidden">';
			    text_append += '</li>';
			    focus_obj.find("div[focus_id='"+i+"'] ul").append(text_append);
			    if(add_type == 'adv') {
			        focus_obj.find("div[focus_id='"+i+"'] li[pic_id='"+pic_id+"']").trigger("click");
			    }
			}
			focus_obj.find("div ul").sortable({ items: 'li' });
			focus_obj.find("div[focus_id='"+i+"'] li[pic_id='1']").trigger("click");//默认选中第一个图片
			break;
		}
	}
}
function select_focus(focus_id,pic) {//选中图片
    var obj = $(pic);
    var pic_id = obj.attr("pic_id");
    var list = obj.attr("list");
    focus_obj.find("li").removeClass("selected");
    focus_obj.find("input[name='key']").val(focus_id);
    obj.addClass("selected");
    if(list == 'adv') {
        focus_upload_obj.hide();
		focus_edit_obj.hide();
        var a_id = obj.find("input[name*='[ap_id]']").val();
        if(a_id == '') {//未选择广告位时用默认的
            $("#ap_id_focus").trigger("onchange");
        } else {
            $("#ap_id_focus").val(a_id);
        }
        focus_ap_obj.show();
    } else {
        focus_ap_obj.hide();
		focus_edit_obj.hide();
        var pic_name = obj.find("input[name*='[pic_name]']").val();
        var pic_url = obj.find("input[name*='[pic_url]']").val();

        focus_obj.find("input[name='slide_id']").val(focus_id);
        focus_obj.find("input[name='pic_id']").val(pic_id);
        focus_obj.find("input[name='focus_pic[pic_name]']").val(pic_name);
        focus_obj.find("input[name='focus_pic[pic_url]']").val(pic_url);
        focus_obj.find(".type-file-file").val('');
        focus_obj.find(".type-file-text").val('');
        focus_upload_obj.show();
    }
}
function focus_pic(pic_id,pic_img) {//更新图片
	if (pic_img!='') {
	    var focus_id = focus_obj.find("input[name='slide_id']").val();
	    var pic_name = focus_obj.find("input[name='focus_pic[pic_name]']").val();
	    var pic_url = focus_obj.find("input[name='focus_pic[pic_url]']").val();
	    var obj = focus_obj.find("div[focus_id='"+focus_id+"'] li[pic_id='"+pic_id+"']");
	    obj.find("img").attr("src",UPLOAD_SITE_URL+'/'+pic_img);
	    obj.find("img").attr("title",pic_name);
        obj.find("input[name*='[pic_name]']").val(pic_name);
        obj.find("input[name*='[pic_url]']").val(pic_url);
        obj.find("input[name*='[pic_img]']").val(pic_img);
    }
	focus_obj.find('.web-save-succ').show();
	setTimeout("focus_obj.find('.web-save-succ').hide()",2000);
}
function select_ap_focus() {//选择广告位
    ap_id = $("#ap_id_focus").val();
    if (ap_id > 0 && typeof focus_adv_list[ap_id] !== "undefined") {
        var adv = focus_adv_list[ap_id];
	    var pic_name = adv['ap_name'];
	    var pic_img = adv['ap_img'];
	    var obj = focus_obj.find("li.selected");
	    var pic_id = obj.attr("pic_id");
	    obj.find("img").attr("src",UPLOAD_SITE_URL+'/'+ATTACH_ADV+'/'+pic_img);
	    obj.find("img").attr("title",pic_name);
        obj.find("input[name*='[pic_name]']").val(pic_name);
        obj.find("input[name*='[ap_id]']").val(ap_id);
        obj.find("input[name*='[pic_img]']").val(ATTACH_ADV+'/'+pic_img);
    }
}

  function focus_group(group_name,group_img) {//更新组名

	  if (group_img!='') {
		  var focus_id = focus_obj.find("input[name='slide_id']").val();
		  var obj = focus_obj.find("div[focus_id='"+focus_id+"']");

		  obj.find(".title img").attr("src",UPLOAD_SITE_URL+'/'+group_img);
		  obj.find(".title img").attr("title",group_name);
		  obj.find(".name").html(group_name);
		  obj.find("input[name*='[group_name]']").val(group_name);
		  obj.find("input[name*='[group_image]']").val(group_img);
	  }
	  focus_obj.find('.web-save-succ').show();
	  setTimeout("focus_obj.find('.web-save-succ').hide()",2000);
  }

function edit_focus(focus_id){//编辑切换组
	focus_obj.find("input[name='slide_id']").val(focus_id);
	focus_obj.find("input[name='key']").val(focus_id);
	var obj = focus_obj.find("div[focus_id='"+focus_id+"']");
	var group_name = obj.find("input[name*='[group_list][group_name]']").val();

	focus_obj.find("input[name='slide_id']").val(focus_id);
    focus_obj.find("input[name='pic_group[group_name]']").val(group_name);
    focus_obj.find(".type-file-file").val('');
    focus_obj.find(".type-file-text").val('');

	focus_upload_obj.hide();
	focus_ap_obj.hide();
	focus_edit_obj.show();

}
function del_focus(focus_id) {//删除切换组
    if (focus_obj.find("div[focus_id]").size()<2) {
         return;//保留一个
    }
	focus_obj.find("div[focus_id='"+focus_id+"']").remove();
	var slide_id = focus_obj.find("input[name='key']").val();
	if (focus_id==slide_id) {
    	focus_obj.find("input[name='key']").val('');
		focus_edit_obj.hide();
    	focus_upload_obj.hide();
    	focus_ap_obj.hide();
	}
}

  //2联广告图
  function add_two_focus(add_type) {//增加
	  for (var i = 1; i <= two_focus_max; i++) {//防止数组下标重复
		  if (two_focus_obj.find("div[focus_id='"+i+"']").size()==0) {//编号不存在时添加
			  var add_html = '';
			  var img_type = '<img width="20" height="20" title="" src="'+ADMIN_TEMPLATES_URL+'/images/picture.gif"/>';
			  var text_type = '';
			  text_type += '<div class="name">';
			  text_type += '图片调用';
			  text_type += '</div>';
			  if(add_type == 'adv') {
				  text_type = '广告调用';
			  }
			  add_html = '<div focus_id="'+i+'" class="focus-trigeminy-group" title="可上下拖拽更改显示顺序">'+'<div class="title">'+img_type+'<h4>'+text_type+'</h4>'+
				  '<input name="two_focus_list['+i+'][group_list][group_name]" value="" type="hidden">' +
				  '<input name="two_focus_list['+i+'][group_list][group_image]" value="" type="hidden">' +
				  '<a class="ncap-btn-mini del" href="JavaScript:edit_two_focus('+i+');"><i class="fa fa-pencil-square-o"></i>编辑</a>' +
				  '<a class="ncap-btn-mini del" href="JavaScript:del_two_focus('+i+');"><i class="fa fa-trash"></i>删除</a></div><ul></ul></div>';
			  two_focus_obj.find("#btn_add_list_two").before(add_html);
			  for (var pic_id = 1; pic_id <= two_pic_max; pic_id++) {
				  var text_append = '';
				  text_append += '<li list="'+add_type+'" pic_id="'+pic_id+'" onclick="select_two_focus('+i+',this);" title="可左右拖拽更改图片排列顺序">';
				  text_append += '<div class="focus-thumb">';
				  text_append += '<img title="" src="'+ADMIN_TEMPLATES_URL+'/images/picture.gif"/>';
				  text_append += '</div>';
				  text_append += '<input name="two_focus_list['+i+'][pic_list]['+pic_id+'][pic_id]" value="'+pic_id+'" type="hidden">';
				  text_append += '<input name="two_focus_list['+i+'][pic_list]['+pic_id+'][pic_name]" value="" type="hidden">';
				  if(add_type == 'adv') {
					  text_append += '<input name="two_focus_list['+i+'][pic_list]['+pic_id+'][ap_id]" value="" type="hidden">';
				  } else {
					  text_append += '<input name="two_focus_list['+i+'][pic_list]['+pic_id+'][pic_url]" value="" type="hidden">';
				  }
				  text_append += '<input name="two_focus_list['+i+'][pic_list]['+pic_id+'][pic_img]" value="" type="hidden">';
				  text_append += '</li>';
				  two_focus_obj.find("div[focus_id='"+i+"'] ul").append(text_append);
				  if(add_type == 'adv') {
					  two_focus_obj.find("div[focus_id='"+i+"'] li[pic_id='"+pic_id+"']").trigger("click");
				  }
			  }
			  two_focus_obj.find("div ul").sortable({ items: 'li' });
			  two_focus_obj.find("div[focus_id='"+i+"'] li[pic_id='1']").trigger("click");//默认选中第一个图片
			  break;
		  }
	  }
  }

  function two_focus_pic(pic_id,pic_img) {//更新图片
	  if (pic_img!='') {
		  var focus_id = two_focus_obj.find("input[name='slide_id']").val();
		  var pic_name = two_focus_obj.find("input[name='focus_pic[pic_name]']").val();
		  var pic_url = two_focus_obj.find("input[name='focus_pic[pic_url]']").val();
		  var obj = two_focus_obj.find("div[focus_id='"+focus_id+"'] li[pic_id='"+pic_id+"']");
		  obj.find("img").attr("src",UPLOAD_SITE_URL+'/'+pic_img);
		  obj.find("img").attr("title",pic_name);
		  obj.find("input[name*='[pic_name]']").val(pic_name);
		  obj.find("input[name*='[pic_url]']").val(pic_url);
		  obj.find("input[name*='[pic_img]']").val(pic_img);
	  }
	  two_focus_obj.find('.web-two-save-succ').show();
	  setTimeout("two_focus_obj.find('.web-two-save-succ').hide()",2000);
  }

  function select_two_focus(focus_id,pic) {//选中图片
	  var obj = $(pic);
	  var pic_id = obj.attr("pic_id");
	  var list = obj.attr("list");
	  two_focus_obj.find("li").removeClass("selected");
	  two_focus_obj.find("input[name='key']").val(focus_id);
	  obj.addClass("selected");
	  if(list == 'adv') {
		  two_focus_upload_obj.hide();
		  two_focus_edit_obj.hide();
		  var a_id = obj.find("input[name*='[ap_id]']").val();
		  if(a_id == '') {//未选择广告位时用默认的
			  $("#ap_id_focus").trigger("onchange");
		  } else {
			  $("#ap_id_focus").val(a_id);
		  }
		  two_focus_ap_obj.show();
	  } else {
		  two_focus_ap_obj.hide();
		  two_focus_edit_obj.hide();
		  var pic_name = obj.find("input[name*='[pic_name]']").val();
		  var pic_url = obj.find("input[name*='[pic_url]']").val();

		  two_focus_obj.find("input[name='slide_id']").val(focus_id);
		  two_focus_obj.find("input[name='pic_id']").val(pic_id);
		  two_focus_obj.find("input[name='focus_pic[pic_name]']").val(pic_name);
		  two_focus_obj.find("input[name='focus_pic[pic_url]']").val(pic_url);
		  two_focus_obj.find(".type-file-file").val('');
		  two_focus_obj.find(".type-file-text").val('');
		  two_focus_upload_obj.show();
	  }
  }
  function select_ap_two_focus() {//选择广告位
	  ap_id = $("#ap_id_focus").val();
	  if (ap_id > 0 && typeof focus_adv_list[ap_id] !== "undefined") {
		  var adv = focus_adv_list[ap_id];
		  var pic_name = adv['ap_name'];
		  var pic_img = adv['ap_img'];
		  var obj = two_focus_obj.find("li.selected");
		  var pic_id = obj.attr("pic_id");
		  obj.find("img").attr("src",UPLOAD_SITE_URL+'/'+ATTACH_ADV+'/'+pic_img);
		  obj.find("img").attr("title",pic_name);
		  obj.find("input[name*='[pic_name]']").val(pic_name);
		  obj.find("input[name*='[ap_id]']").val(ap_id);
		  obj.find("input[name*='[pic_img]']").val(ATTACH_ADV+'/'+pic_img);
	  }
  }
  function del_two_focus(focus_id) {//删除切换组
	  if (two_focus_obj.find("div[focus_id]").size()<2) {
		  return;//保留一个
	  }
	  two_focus_obj.find("div[focus_id='"+focus_id+"']").remove();
	  var slide_id = two_focus_obj.find("input[name='key']").val();
	  if (focus_id==slide_id) {
		  two_focus_obj.find("input[name='key']").val('');
		  two_focus_edit_obj.hide();
		  two_focus_upload_obj.hide();
		  two_focus_ap_obj.hide();
	  }
  }

  function two_focus_group(group_name,group_img) {//更新组名

	  if (group_img!='') {
		  var focus_id = two_focus_obj.find("input[name='slide_id']").val();
		  var obj = two_focus_obj.find("div[focus_id='"+focus_id+"']");

		  obj.find(".title img").attr("src",UPLOAD_SITE_URL+'/'+group_img);
		  obj.find(".title img").attr("title",group_name);
		  obj.find(".name").html(group_name);
		  obj.find("input[name*='[group_name]']").val(group_name);
		  obj.find("input[name*='[group_image]']").val(group_img);
	  }
	  two_focus_obj.find('.web-save-succ').show();
	  setTimeout("two_focus_obj.find('.web-save-succ').hide()",2000);
  }

  function edit_two_focus(focus_id){//编辑切换组
		two_focus_obj.find("input[name='slide_id']").val(focus_id);
		two_focus_obj.find("input[name='key']").val(focus_id);
		var obj = two_focus_obj.find("div[focus_id='"+focus_id+"']");
		var group_name = obj.find("input[name*='[group_list][group_name]']").val();
	    console.log(group_name);

		two_focus_obj.find("input[name='slide_id']").val(focus_id);
	    two_focus_obj.find("input[name='pic_group[group_name]']").val(group_name);
	    two_focus_obj.find(".type-file-file").val('');
	    two_focus_obj.find(".type-file-text").val('');
		two_focus_upload_obj.hide();
		two_focus_ap_obj.hide();
		two_focus_edit_obj.show();

  }

  //八联广告图
  function add_eight_focus(add_type) {//增加
	  for (var i = 1; i <= focus_max; i++) {//防止数组下标重复
		  if (eight_focus_obj.find("div[focus_id='"+i+"']").size()==0) {//编号不存在时添加
			  var add_html = '';
			  var img_type = '<img width="20" height="20" title="" src="'+ADMIN_TEMPLATES_URL+'/images/picture.gif"/>';
			  var text_type = '';
			  text_type += '<div class="name">';
			  text_type += '图片调用';
			  text_type += '</div>';
			  if(add_type == 'adv') {
				  text_type = '广告调用';
			  }
			  add_html = '<div style="width: 800px;" focus_id="'+i+'" class="focus-trigeminy-group" title="可上下拖拽更改显示顺序">'+'<div class="title">'+img_type+'<h4>'+text_type+'</h4>'+
				  '<input name="eight_focus_list['+i+'][group_list][group_name]" value="" type="hidden">' +
				  '<input name="eight_focus_list['+i+'][group_list][group_image]" value="" type="hidden">' +
				  '<a class="ncap-btn-mini del" href="JavaScript:edit_eight_focus('+i+');"><i class="fa fa-pencil-square-o"></i>编辑</a>' +
				  '<a class="ncap-btn-mini del" href="JavaScript:del_eight_focus('+i+');"><i class="fa fa-trash"></i>删除</a></div><ul></ul></div>';
			  eight_focus_obj.find("#btn_add_list_eight").before(add_html);
			  for (var pic_id = 1; pic_id <= eight_pic_max; pic_id++) {
				  var text_append = '';
				  text_append += '<li list="'+add_type+'" pic_id="'+pic_id+'" onclick="select_eight_focus('+i+',this);" title="可左右拖拽更改图片排列顺序">';
				  text_append += '<div class="focus-thumb">';
				  text_append += '<img title="" src="'+ADMIN_TEMPLATES_URL+'/images/picture.gif"/>';
				  text_append += '</div>';
				  text_append += '<input name="eight_focus_list['+i+'][pic_list]['+pic_id+'][pic_id]" value="'+pic_id+'" type="hidden">';
				  text_append += '<input name="eight_focus_list['+i+'][pic_list]['+pic_id+'][pic_name]" value="" type="hidden">';
				  if(add_type == 'adv') {
					  text_append += '<input name="eight_focus_list['+i+'][pic_list]['+pic_id+'][ap_id]" value="" type="hidden">';
				  } else {
					  text_append += '<input name="eight_focus_list['+i+'][pic_list]['+pic_id+'][pic_url]" value="" type="hidden">';
				  }
				  text_append += '<input name="eight_focus_list['+i+'][pic_list]['+pic_id+'][pic_img]" value="" type="hidden">';
				  text_append += '</li>';
				  eight_focus_obj.find("div[focus_id='"+i+"'] ul").append(text_append);
				  if(add_type == 'adv') {
					  eight_focus_obj.find("div[focus_id='"+i+"'] li[pic_id='"+pic_id+"']").trigger("click");
				  }
			  }
			  eight_focus_obj.find("div ul").sortable({ items: 'li' });
			  eight_focus_obj.find("div[focus_id='"+i+"'] li[pic_id='1']").trigger("click");//默认选中第一个图片
			  break;
		  }
	  }
  }
  function eight_focus_pic(pic_id,pic_img) {//更新图片
	  if (pic_img!='') {
		  var focus_id = eight_focus_obj.find("input[name='slide_id']").val();
		  var pic_name = eight_focus_obj.find("input[name='focus_pic[pic_name]']").val();
		  var pic_url = eight_focus_obj.find("input[name='focus_pic[pic_url]']").val();
		  var obj = eight_focus_obj.find("div[focus_id='"+focus_id+"'] li[pic_id='"+pic_id+"']");
		  obj.find("img").attr("src",UPLOAD_SITE_URL+'/'+pic_img);
		  obj.find("img").attr("title",pic_name);
		  obj.find("input[name*='[pic_name]']").val(pic_name);
		  obj.find("input[name*='[pic_url]']").val(pic_url);
		  obj.find("input[name*='[pic_img]']").val(pic_img);
	  }
	  eight_focus_obj.find('.web-eight-save-succ').show();
	  setTimeout("eight_focus_obj.find('.web-eight-save-succ').hide()",2000);
  }
  function select_eight_focus(focus_id,pic) {//选中图片
	  var obj = $(pic);
	  var pic_id = obj.attr("pic_id");
	  var list = obj.attr("list");
	  eight_focus_obj.find("li").removeClass("selected");
	  eight_focus_obj.find("input[name='key']").val(focus_id);
	  obj.addClass("selected");
	  if(list == 'adv') {
		  eight_focus_upload_obj.hide();
		  var a_id = obj.find("input[name*='[ap_id]']").val();
		  if(a_id == '') {//未选择广告位时用默认的
			  $("#ap_id_focus").trigger("onchange");
		  } else {
			  $("#ap_id_focus").val(a_id);
		  }
		  eight_focus_ap_obj.show();
		  eight_focus_edit_obj.hide();
	  } else {
		  eight_focus_ap_obj.hide();
		  eight_focus_edit_obj.hide();
		  var pic_name = obj.find("input[name*='[pic_name]']").val();
		  var pic_url = obj.find("input[name*='[pic_url]']").val();

		  eight_focus_obj.find("input[name='slide_id']").val(focus_id);
		  eight_focus_obj.find("input[name='pic_id']").val(pic_id);
		  eight_focus_obj.find("input[name='focus_pic[pic_name]']").val(pic_name);
		  eight_focus_obj.find("input[name='focus_pic[pic_url]']").val(pic_url);
		  eight_focus_obj.find(".type-file-file").val('');
		  eight_focus_obj.find(".type-file-text").val('');
		  eight_focus_upload_obj.show();
	  }
  }

  function select_ap_eight_focus() {//选择广告位
	  ap_id = $("#ap_id_focus").val();
	  if (ap_id > 0 && typeof focus_adv_list[ap_id] !== "undefined") {
		  var adv = focus_adv_list[ap_id];
		  var pic_name = adv['ap_name'];
		  var pic_img = adv['ap_img'];
		  var obj = eight_focus_obj.find("li.selected");
		  var pic_id = obj.attr("pic_id");
		  obj.find("img").attr("src",UPLOAD_SITE_URL+'/'+ATTACH_ADV+'/'+pic_img);
		  obj.find("img").attr("title",pic_name);
		  obj.find("input[name*='[pic_name]']").val(pic_name);
		  obj.find("input[name*='[ap_id]']").val(ap_id);
		  obj.find("input[name*='[pic_img]']").val(ATTACH_ADV+'/'+pic_img);
	  }
  }
  function del_eight_focus(focus_id) {//删除切换组
	  if (eight_focus_obj.find("div[focus_id]").size()<2) {
		  return;//保留一个
	  }
	  eight_focus_obj.find("div[focus_id='"+focus_id+"']").remove();
	  var slide_id = eight_focus_obj.find("input[name='key']").val();
	  if (focus_id==slide_id) {
		  eight_focus_obj.find("input[name='key']").val('');
		  eight_focus_edit_obj.hide();
		  eight_focus_upload_obj.hide();
		  eight_focus_ap_obj.hide();
	  }
  }
  function eight_focus_group(group_name,group_img) {//更新组名

	  if (group_img!='') {
		  var focus_id = eight_focus_obj.find("input[name='slide_id']").val();
		  var obj = eight_focus_obj.find("div[focus_id='"+focus_id+"']");

		  obj.find(".title img").attr("src",UPLOAD_SITE_URL+'/'+group_img);
		  obj.find(".title img").attr("title",group_name);
		  obj.find(".name").html(group_name);
		  obj.find("input[name*='[group_name]']").val(group_name);
		  obj.find("input[name*='[group_image]']").val(group_img);
	  }
	  eight_focus_obj.find('.web-save-succ').show();
	  setTimeout("eight_focus_obj.find('.web-save-succ').hide()",2000);
  }

  function edit_eight_focus(focus_id){//编辑切换组
	  eight_focus_obj.find("input[name='slide_id']").val(focus_id);
	  eight_focus_obj.find("input[name='key']").val(focus_id);
	  var obj = eight_focus_obj.find("div[focus_id='"+focus_id+"']");
	  var group_name = obj.find("input[name*='[group_list][group_name]']").val();

	  eight_focus_obj.find("input[name='slide_id']").val(focus_id);
	  eight_focus_obj.find("input[name='pic_group[group_name]']").val(group_name);
	  eight_focus_obj.find(".type-file-file").val('');
	  eight_focus_obj.find(".type-file-text").val('');
	  eight_focus_upload_obj.hide();
	  eight_focus_ap_obj.hide();
	  eight_focus_edit_obj.show();

  }