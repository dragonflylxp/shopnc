/* $Id : common.js 4824 2007-01-31 08:23:56Z paulgao $ */

/* 检查新订单的时间间隔 */
var NEW_ORDER_INTERVAL = 180000;

/* *
 * 开始检查新订单；
 */
function startCheckOrder()
{
  checkOrder();
  window.setInterval("checkOrder()", NEW_ORDER_INTERVAL);
}

/*
 * 检查订单
 */
function checkOrder()
{
	Ajax.call('index.php?is_ajax=1&act=check_order','', checkOrderResponse, 'GET', 'JSON');
}

/* *
 * 处理检查订单的反馈信息
 */
function checkOrderResponse(result)
{
  //出错屏蔽
  if (result.error != 0 || (result.new_orders == 0 && result.new_paid == 0))
  {
    return;
  }
  try
  {
    document.getElementById('spanNewOrder').innerHTML = result.new_orders;
    document.getElementById('spanNewPaid').innerHTML = result.new_paid;
    Message.show();
  }
  catch (e) { }
}

/* *
 * 开始检查账单；
 */
function startCheckBill()
{
  checkBill();
  window.setInterval("checkBill()", NEW_ORDER_INTERVAL);
}

/*
 * 检查订单
 */
function checkBill()
{
	Ajax.call('index.php?is_ajax=1&act=check_bill','', checkBillResponse, 'GET', 'JSON');
}

/* *
 * 处理检查账单的反馈信息
 */
function checkBillResponse(result)
{
  //出错屏蔽
  if (result.error != 0)
  {
    return;
  }
  try
  {
  }
  catch (e) { }
}

/* *
 * 检查出账单；
 */
function outCheckBill()
{
  outBill();
  window.setInterval("outBill()", NEW_ORDER_INTERVAL);
}

/*
 * 检查出账单
 */
function outBill()
{
	Ajax.call('index.php?is_ajax=1&act=out_check_bill','', outCheckBillResponse, 'GET', 'JSON');
}

/* *
 * 处理检查账单的反馈信息
 */
function outCheckBillResponse(result)
{
  //出错屏蔽
  if (result.error != 0)
  {
    return;
  }
  try
  {
  }
  catch (e) { }
}

/* *
 * 检测配送地区缓存文件是否存在；
 */
function sellerShippingArea(){
	Ajax.call('index.php?is_ajax=1&act=seller_shipping_area','', shippingAreaResponse, 'GET', 'JSON');
}

function shippingAreaResponse(result){
}

/**
 * 确认后跳转到指定的URL
 */
function confirm_redirect(msg, url)
{
  if (confirm(msg))
  {
    location.href=url;
  }
}

/* *
 * 设置页面宽度
 */
function set_size(w)
{
  var y_width = document.body.clientWidth
  var s_width = screen.width
  var agent   = navigator.userAgent.toLowerCase();

  if (y_width < w)
  {
    if (agent.indexOf("msie") != - 1)
    {
      document.body.style.width = w + "px";
    }
    else
    {
      document.getElementById("bd").style.width = (w - 10) + 'px';
    }
  }
}

/* *
 * 显示隐藏图片
 * @param   id  div的id
 * @param   show | hide
 */
function showImg(id, act)
{
  if (act == 'show')
  {
    document.getElementById(id).style.visibility = 'visible';
  }
  else
  {
    document.getElementById(id).style.visibility = 'hidden';
  }
}

/*
 * 气泡式提示信息
 */
var Message = Object();

Message.bottom  = 0;
Message.count   = 0;
Message.elem    = "popMsg";
Message.mvTimer = null;

Message.show = function()
{
  try
  {
    Message.controlSound('msgBeep');
    document.getElementById(Message.elem).style.visibility = "visible"
    document.getElementById(Message.elem).style.display = "block"

    Message.bottom  = 0 - parseInt(document.getElementById(Message.elem).offsetHeight);
    Message.mvTimer = window.setInterval("Message.move()", 10);

    document.getElementById(Message.elem).style.bottom = Message.bottom + "px";
  }
  catch (e)
  {
    alert(e);
  }
}

Message.move = function()
{
  try
  {
    if (Message.bottom == 0)
    {
      window.clearInterval(Message.mvTimer)
      Message.mvTimer = window.setInterval("Message.close()", 5000)
    }

    Message.bottom ++ ;
    document.getElementById(Message.elem).style.bottom = Message.bottom + "px";
  }
  catch (e)
  {
    alert(e);
  }
}

Message.close = function()
{
  document.getElementById(Message.elem).style.visibility = 'hidden';
  document.getElementById(Message.elem).style.display = 'none';
  if (Message.mvTimer) window.clearInterval(Message.mvTimer)
}

Message.controlSound = function(_sndObj)
{
  sndObj = document.getElementById(_sndObj);

  try
  {
    sndObj.Play();
  }
  catch (e) { }
}

var listZone = new Object();

/* *
 * 显示正在载入
 */
listZone.showLoader = function()
{
  listZone.toggleLoader(true);
}

listZone.hideLoader = function()
{
  listZone.toggleLoader(false);
}

listZone.toggleLoader = function(disp)
{
  document.getElementsByTagName('body').item(0).style.cursor = (disp) ? "wait" : 'auto';

  try
  {
    var doc = top.frames['header-frame'].document;
    var loader = doc.getElementById("load-div");

    if (typeof loader == 'object') loader.style.display = disp ? "block" : "none";
  }
  catch (ex) { }
}

function $import(path,type,title){
  var s,i;
  if(type == "js"){
    var ss = document.getElementsByTagName("script");
    for(i =0;i < ss.length; i++)
    {
      if(ss[i].src && ss[i].src.indexOf(path) != -1)return ss[i];
    }
    s      = document.createElement("script");
    s.type = "text/javascript";
    s.src  =path;
  }
  else if(type == "css")
  {
    var ls = document.getElementsByTagName("link");
    for(i = 0; i < ls.length; i++)
    {
      if(ls[i].href && ls[i].href.indexOf(path)!=-1)return ls[i];
    }
    s          = document.createElement("link");
    s.rel      = "alternate stylesheet";
    s.type     = "text/css";
    s.href     = path;
    s.title    = title;
    s.disabled = false;
  }
  else return;
  var head = document.getElementsByTagName("head")[0];
  head.appendChild(s);
  return s;
}

/**
 * 返回随机数字符串
 *
 * @param : prefix  前缀字符
 *
 * @return : string
 */
function rand_str(prefix)
{
  var dd = new Date();
  var tt = dd.getTime();
  tt = prefix + tt;

  var rand = Math.random();
  rand = Math.floor(rand * 100);

  return (tt + rand);
}

// 分类分级 by qin

function catList(val, level)
{
    var cat_id = val;
    document.getElementById('cat_id').value = cat_id;
    Ajax.call('goods.php?is_ajax=1&act=sel_cat', 'cat_id='+cat_id+'&cat_level='+level, catListResponse, 'GET', 'JSON');
}

function catListResponse(result)
{
    if (result.error == '1' && result.message != '')
    {
      alert(result.message);
      return;
    }
    var response = result.content;
    var cat_level = result.cat_level; // 分类级别， 1为顶级分类
    for(var i=cat_level;i<10;i++)
    {
      $("#cat_list"+Number(i+1)).remove();
    }
    if(response)
    {
        $("#cat_list"+cat_level).after(response);
    }
	
	if(document.getElementById('cat_level')){
		if(result.parent_id == 0){
			cat_level = 0;
		}
		document.getElementById('cat_level').value = cat_level;
	}
  return;
}

/*
 * 获取选择分类下拉列表by wu
 * cat_id:选择的分类id 
 * cat_level:选择的分类id等级
 * select_jsId:需要赋值的input id,为0,则将值赋给同级
 */
function get_select_category(cat_id,cat_level,select_jsId,type,table)
{
	//需要赋值的input
	var obj=$("#"+select_jsId);
	
	//当前页面url
	//var page_url=window.location.href.replace(/\?(.)+/g,'');
	var page_url='get_ajax_content.php';
	
	//给input赋值
	switch(type)
	{
		case 0: obj.val(cat_id+'_'+(cat_level-1)); break;
		case 1: obj.val(cat_id); break;
		case 2: obj.val(cat_level); break;
		default: obj.val(cat_id+'_'+(cat_level-1)); break;
	}	
	
	//移除该级的其他子分类列表
	obj.siblings("select[cat-type=select]").each(function(){
		if($(this).attr('cat-level')>cat_level)
		{
			$(this).remove();
		}
		else
		{
			if(cat_id==0 && cat_level==1)
			{
				switch(type)
				{
					case 0: obj.val('0_0'); break;
					case 1: obj.val('0'); break;
					case 2: obj.val('0'); break;
					default: obj.val('0_0'); break;
				}
			}
			if(cat_id==0 && cat_level>1)
			{
				switch(type)
				{
					case 0: obj.val($(this).prev().val()+'_'+($(this).prev().attr('cat-level')-1)); break;
					case 1: obj.val($(this).prev().val()); break;
					case 2: obj.val($(this).prev().attr('cat-level')-1); break;
					default: obj.val($(this).prev().val()+'_'+($(this).prev().attr('cat-level')-1)); break;
				}
			}
		}
	});

	if(cat_id>0)
	{	
		//加载选择分类的子分类列表
		$.ajax({
			type:'get',
			url:page_url,
			data:'act=get_select_category&cat_id='+cat_id+'&cat_level='+cat_level+'&select_jsId='+select_jsId+'&type='+type+'&table='+table,
			dataType:'json',
			success:function(data){
				if(data.error==1)
				{			
					obj.siblings("select[cat-type=select]").last().after(data.content);
				}
			}		
		});
	}
}

//筛选显示分类 by wu
function filter_category(cat_id,cat_level,select_jsId)
{
	var obj=$("#"+select_jsId);
	obj.children('option').each(function(){
		var val=$(this).val();		
		var valArr=val.split('_');
		if(valArr[1]>cat_level)
		{
			$(this).hide();
		}
	});
}

//jqueryAjax异步加载
$.jqueryAjax = function(url, data, ajaxFunc, type, dataType)
{
	var baseData = "is_ajax=1&";
	var baseFunc = function(){}
	
	if(!url)
	{
		url = "index.php";
	}
	
	if(!data)
	{
		data = "";
	}
	
	if(!type)
	{
		type = "get";
	}
	
	if(!dataType)
	{
		dataType = "json";
	}
	
	if(!ajaxFunc)
	{
		ajaxFunc = baseFunc;
	}
	
	data = baseData + data;
	
	$.ajax({
		type:type,
		url:url,
		data:data,
		dataType:dataType,
		success:ajaxFunc.success? ajaxFunc.success:ajaxFunc,
		error:ajaxFunc.error? ajaxFunc.error:baseFunc,
		beforeSend:ajaxFunc.beforeSend? ajaxFunc.beforeSend:baseFunc,
		complete:ajaxFunc.complete? ajaxFunc.complete:baseFunc,
		//dataFilter:ajaxFunc.dataFilter? ajaxFunc.dataFilter:baseFunc
	});	
}

//添加/删除扩展分类
function deal_extension_category(obj, goods_id, cat_id, type)
{
    var other_catids = $("#other_catids").val();
	var obj = $(obj);
	$.jqueryAjax("goods.php", "act=deal_extension_category&goods_id="+goods_id+"&cat_id="+cat_id+"&type="+type + "&other_catids="+other_catids, function(data){
		$("#other_catids").val(data.content);
	});
}
//设置商品分类 by wu
function get_select_category_pro(obj, cat_id, cat_level, goods_id)
{
	var obj = $(obj);
	var thisSection = obj.parents(".sort_info");
	
	var ex_goods = '';
	if(goods_id){
		ex_goods = '&goods_id=' + goods_id;
	}
	
	$.jqueryAjax('goods.php', 'act=get_select_category_pro&cat_id=' + cat_id + '&cat_level=' + cat_level + ex_goods, function(data){
		if(cat_id == 0){
			var parent_id1 = thisSection.find("ul[data-cat_level="+(cat_level-1)+"] li.current").data("cat_id"); //上一级
			var parent_id2 = thisSection.find("ul[data-cat_level="+(cat_level-2)+"] li.current").data("cat_id"); //上上一级
			parent_id = parent_id1? parent_id1:parent_id2? parent_id2:0; //如果都没有，则cat_id=0
			thisSection.find("input[ectype=cat_id]").val(parent_id); //设置分类id
			thisSection.find("ul[data-cat_level="+(cat_level+1)+"] li:gt(0)").remove(); //除第一行，其他移除
			thisSection.find("ul[data-cat_level="+(cat_level+1)+"] li:first").removeClass("current"); //去除第一行的选中效果
			
		}else{
			thisSection.find("input[ectype=cat_id]").val(cat_id); //设置分类id
			thisSection.find("ul[data-cat_level="+(cat_level+1)+"]").html(data.content); //异步加载内容
			$(".category_list").perfectScrollbar('destroy');
			$(".category_list").perfectScrollbar();
		}
		thisSection.find("ul[data-cat_level="+(cat_level+2)+"] li:gt(0)").remove(); //除第一行，其他移除
		thisSection.find("ul[data-cat_level="+(cat_level+2)+"] li:first").removeClass("current"); //去除第一行的选中效果
		
		//拓展分类不能修改分类导航
		if(obj.parents("#extension_category").length == 0){
			set_cat_nav();
		}
	});
}

//设置分类导航 by wu
function set_cat_nav()
{
	var cat_nav = "";
	$("ul[ectype='category']").each(function(){
		var category = $(this).find("li.current a").text();
		if(category){
			if($(this).data("cat_level") == 1){
				cat_nav += category;
			}else{
				cat_nav += " > " + category;
			}		
		}		
	})
	$("#choiceClass strong").html(cat_nav);
	$(".edit_category").siblings("span").html(cat_nav);
}

//设置属性表格
function set_attribute_table(goodsId)
{
	var attr_id_arr = [];
	var attr_value_arr = [];
	var attrId = $("#tbody-goodsAttr").find("input[type=checkbox][data-type=attr_id]:checked");
	//var attrValue = $("#tbody-goodsAttr").find("input[type=checkbox][data-type=attr_value]:checked");
	var attrValue = attrId.siblings("input[type=checkbox][data-type=attr_value]");
	attrId.each(function(){
		attr_id_arr.push($(this).val());
	});
	attrValue.each(function(){
		
		/**
		*过滤ajax传值加号问题
		*/

		var dataVal = $(this).val();
		dataVal = dataVal.replace(/\+/g, "%2B");
    	dataVal = dataVal.replace(/\&/g, "%26");
	
		attr_value_arr.push(dataVal);
	});

	//商品模式
	var extension = "";
	var goods_model = $("input[name=model_price]").val(); 
	var warehouse_id = $("#attribute_model").find("input[type=radio][data-type=warehouse_id]:checked").val();
	var region_id = $("#attribute_model").find("input[type=radio][data-type=region_id]:checked").val();
	extension += "&goods_model="+goods_model;
	if(goods_model == 1){
		extension += "&region_id="+warehouse_id;
	}else if(goods_model == 2){
		extension += "&region_id="+region_id;
	}
	
	var goods_type = $("input[name='goods_type']").val();
	if(goods_type > 0){
		extension += "&goods_type="+goods_type;
	}
	
	$.jqueryAjax('goods.php', 'act=set_attribute_table&goods_id='+goodsId+'&attr_id='+attr_id_arr+'&attr_value='+attr_value_arr+extension, function(data){
		$("#attribute_table").html(data.content);
		
		/*处理属性图片 start*/
		$("#goods_attr_gallery").html(data.goods_attr_gallery);
		/*处理属性图片 end*/
	});

//getAttrList(goodsId);
}

function ajax_title(){
	var content = "<div class='list-div'> " + 
					"<table cellpadding='0' cellspacing='0' border='0'>" +
						"<tbody>" +
							"<tr>" +
								"<td align='center'>&nbsp;</td>" +
							"</tr>" +
							"<tr>" +
								"<td align='center'><div class='ml10' id='title_name'></div></td>" +
							"</tr>" +
							"<tr>" +
								"<td align='center'>&nbsp;</td>" +
							"</tr>" +
						"</tbody>" +
					"</table>   " +     
				"</div>";
	pb({
		id:"categroy_dialog",
		title:"温馨提示",
		width:588,
		content:content,
		ok_title:"确定",
		drag:false,
		foot:false,
		cl_cBtn:false,
	});
}