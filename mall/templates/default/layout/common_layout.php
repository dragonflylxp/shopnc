<?php defined('Inshopec') or exit('Access Invalid!'); ?>
<!doctype html>
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo $output['html_title']; ?></title>
    <meta name="keywords" content="<?php echo $output['seo_keywords']; ?>"/>
    <meta name="description" content="<?php echo $output['seo_description']; ?>"/>
    <meta name="author" content="shopec">
    <meta name="copyright" content="shopec Inc. All Rights Reserved">
    <meta name="renderer" content="webkit">
    <meta name="renderer" content="ie-stand">
    <?php echo html_entity_decode($output['setting_config']['qq_appcode'], ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['sina_appcode'], ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_qqzone_appcode'], ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_sinaweibo_appcode'], ENT_QUOTES); ?>
    <style type="text/css">
        body {
            _behavior: url(<?php echo SHOP_TEMPLATES_URL;
?>/css/csshover.htc);
        }
    .city-info {float: left;margin: 38px 10px 0 0px;}
.public-head-layout .city-info a {
    text-decoration: none;
    padding: 0 5px;
}.public-head-layout .city-info__name {
    font-size: 15px;
    font-weight: 700;
    color: #000;
}.public-head-layout .city-info a {
    text-decoration: none;
    padding: 0 5px;
}.public-head-layout .city-info__toggle {
    border: 1px solid #eee;
    padding: 0 5px;
    line-height: 20px;
    font-size: 12px;
    color: #999;
    background: #fff;
}    
    </style>
    <link href="<?php echo SHOP_TEMPLATES_URL; ?>/css/base.css" rel="stylesheet" type="text/css">
    <link href="<?php echo SHOP_TEMPLATES_URL; ?>/css/home_header.css" rel="stylesheet" type="text/css">
    <link href="<?php echo SHOP_RESOURCE_SITE_URL; ?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="https://g.alicdn.com/kg/??search-suggest/6.2.11/new_suggest-min.css" rel="stylesheet" type="text/css">
    <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
    <![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
    <![endif]-->
    <script>
        var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';
        var _CHARSET = '<?php echo strtolower(CHARSET);?>';
        var LOGIN_SITE_URL = '<?php echo LOGIN_SITE_URL;?>';
        var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';
        var SITEURL = '<?php echo SHOP_SITE_URL;?>';
        var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';
        var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';
        var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';
        var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
    </script>
    <script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL; ?>/js/common.js" charset="utf-8"></script>
<script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.validation.min.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL; ?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
    <script type="text/javascript">
        var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';
        $(function () {
            //首页左侧分类菜单
            $(".category ul.menu").find("li").each(
                function () {
                    $(this).hover(
                        function () {
                            var cat_id = $(this).attr("cat_id");
                            var menu = $(this).find("div[cat_menu_id='" + cat_id + "']");
                            menu.show();
                            $(this).addClass("hover");
                            var menu_height = menu.height();
                            if (menu_height < 60) menu.height(80);
                            menu_height = menu.height();
                            var li_top = $(this).position().top;
                            $(menu).css("top", -li_top + 38);
                        },
                        function () {
                            $(this).removeClass("hover");
                            var cat_id = $(this).attr("cat_id");
                            $(this).find("div[cat_menu_id='" + cat_id + "']").hide();
                        }
                    );
                }
            );
            $(".head-user-menu dl").hover(function () {
                    $(this).addClass("hover");
                },
                function () {
                    $(this).removeClass("hover");
                });
            $('.head-user-menu .my-mall').mouseover(function () {// 最近浏览的商品
                load_history_information();
                $(this).unbind('mouseover');
            });
            $('.head-user-menu .my-cart').mouseover(function () {// 运行加载购物车
                load_cart_information();
                $(this).unbind('mouseover');
            });
  //@kw

  $('#button').click(function(){

      if ($('#keyword').val() == '') {

                //如果是搜索店铺, 可以空条件提交 - Dino

                if($('#search_type').val() == 'store'){

                    return true;

                }

                //END - Dino

        if ($('#keyword').attr('data-value') == '') {

          return false

      } else {

                            window.location.href="<?php echo SHOP_SITE_URL?>/index.php?con=search&fun=index&keyword="+$('#keyword').attr('data-value')+"&search_type="+$('#search_type').val();//添加搜索类型条件 - Dino

          return false;

      }

      }

  });

  $(".head-search-bar").hover(null,

  function() {

    $('#search-tip').hide();

  });

  // input ajax tips

  $('#keywor').focus(function(){$('#search-tip').show()}).autocomplete({

    //minLength:0,
        messages: {
            noResults: '',
            results: function() {}
        },	
        source: function (request, response) {

            $.getJSON('<?php echo SHOP_SITE_URL;?>/index.php?con=search&fun=auto_complete', request, function (data, status, xhr) {

                $('#ui-id-1').unwrap();
                $('#ui-id-2').unwrap();
                response(data);

                if (status == 'success') {

                    $('#search-tip').hide();

                    $(".head-search-bar").unbind('mouseover');

                    $('#ui-id-1').wrap("<div id='top_search_box'></div>").css({'zIndex':'1000','width':'362px'});

                }

            });

       },

    select: function(ev,ui) {

      $('#keyword').val(ui.item.label);

      $('#top_search_form').submit();

    }

  });

  $('#search-his-del').on('click',function(){$.cookie('<?php echo C('cookie_pre')?>his_sh',null,{path:'/'});$('#search-his-list').empty();});

});

</script>
</head>
<body>
<!-- PublicTopLayout Begin -->
<?php require_once template('layout/layout_top'); ?>
<!-- PublicHeadLayout Begin -->
<div class="header-wrap">
    <header class="public-head-layout wrapper">
        <h1 class="site-logo" style="width: 200px;height: 81px;float: left;margin: 19px 0px auto 0;"><a href="<?php echo SHOP_SITE_URL; ?>"><img
                    src="<?php echo UPLOAD_SITE_URL . DS . ATTACH_COMMON . DS . $output['setting_config']['site_logo']; ?>" style="margin-top: 5px;"
                    class="pngFix"></a></h1>
    <!--by0114-->
    <div class="city-info" style="margin-left: -12px;">
      <h2><a class="city-info__name" href="<?php echo SHOP_SITE_URL;?>"><?php echo mb_substr($output['city'],0,4,'utf-8');?></a></h2>
      <a class="city-info__toggle"  style="float: left;margin-top: 2px;" href="<?php echo SHOP_SITE_URL;?>/index.php?con=city&fun=city">切换城市</a>
    </div>                    
        <?php if (C('mobile_isuse') && C('mobile_app')) { ?>
            <div class="head-app"><span class="pic"></span>

                <div class="download-app">
                    <div class="qrcode"><img class="android"
                                             src="<?php echo UPLOAD_SITE_URL . DS . ATTACH_COMMON . DS . 'mb_android_app.png'; ?>">
                    </div>
                    <div class="qrcode" style="display: none"><img class="apple"
                                                                   src="<?php echo UPLOAD_SITE_URL . DS . ATTACH_COMMON . DS . 'mb_apple_app.png'; ?>"
                                                                   src=""></div>
                    <div class="hint">
                        <h4>扫描二维码</h4>
                        下载手机客户端
                    </div>
                    <div class="addurl">
                        <?php if (C('mobile_apk')) { ?>
                            <a href="<?php echo C('mobile_apk'); ?>" class="qr-btn" ncdata="android" target="_blank"
                               style="color: red;border: 1px solid red"><i class="icon-android"></i>Android</a>
                        <?php } ?>
                        <?php if (C('mobile_ios')) { ?>
                            <a href="<?php echo C('mobile_ios'); ?>" class="qr-btn" ncdata="apple" target="_blank"><i
                                    class="icon-apple"></i>iPhone</a>
                        <?php } ?>
                    </div>

                </div>
            </div>
        <?php } ?>
        <div class="head-search-layout">
  <!-- 商品和店铺切换 - Dino -->

        <style>

            .head-search-layout {

                margin: 7px 0 0;

            }

            .head-search-layout ul.tab {

                width: 232px;

                height: 24px;

                display: block;

                z-index: 99;
                margin-left: -18px;

                overflow: hidden;
                margin-top: 4px;

            }

            .head-search-layout ul.tab li {

     /*           font-weight: bold;*/

                line-height:14px;

                color: #555;

                white-space: nowrap;


         /*       float: left;*/

                height: 14px;

                float: left;


    /*            margin-right: 0px;*/

                cursor: pointer;


                    border-left: solid 1px #CCC;
                       display: inline-block;
                           position: relative;
                     padding: 0 12px 0 15px;
                             margin-left: -1px;
              margin-bottom: 15px;
                  z-index: 1;
                  /* white-space: nowrap;*/
                   cursor: pointer;



            }
            .head-search-layout ul.tab li:hover{  color: #0094DE;}

            .head-search-layout ul.tab li.current {

                line-height:14px;

                    font-weight: 600;

                color: #0094DE;
/*
              background:rgba(255, 255, 255, 0.6);

             */
                height:14px;

                float: left;

            padding: 0 12px 0 15px;


/*
                 border-left: solid 2px #CCC;*/
          
/*
                    display: block;*/
/*    border-color: transparent transparent #D93600;*/

            }

                .head-search-layout ul.tab li.current i{
                        display: block;

                }
                   .head-search-layout ul.tab li i{
                      font-size: 0px;
    line-height: 0;
    display: none;
    width: 0px;
    height: 0px;
    margin-left: -3px;
    border-width: 6px;
    border-color: transparent transparent #D3E7FC transparent;
    border-style: dashed dashed solid dashed;
    position: absolute;
    top: 14px;
    left: 50%;

                }



        </style>

<script>

            $(function(){

                //search

                var act = "store";

                if (act == "store_list"){

                    $(".head-search-layout").children('ul').children('li:eq(1)').addClass("current");

                    $(".head-search-layout").children('ul').children('li:eq(0)').removeClass("current");

                }

                $(".head-search-layout").children('ul').children('li').click(function(){

                    $(this).parent().children('li').removeClass("current");

                    $(this).addClass("current");
                    if($(this).attr("act") == 'stores'){
                    	$('#search_type').attr("value",'store');
                    }else{
                    	$('#search_type').attr("value",$(this).attr("act"));
                    }
                    
                    
                    var thisse = $(this).attr("act");
                    if(thisse == 'goods'){
                    	$('#search_act').attr("value",'search');
                    }else{
                    	$('#search_act').attr("value",thisse);
                    }
                   
                    
//                    $('#keyword').attr("placeholder",$(this).attr("title"));

                });

                $("#keyword").blur();

            });

        </script>

      <ul class="tab">
       <li title="请输入您要搜索的商品关键字" act="goods"<?php echo in_array($_GET['search_type'], array('goods', '')) ? ' class="current"' : '';?>>商品</li>
        <li title="请输入您要搜索的店铺关键字" act="store"<?php echo in_array($_GET['search_type'], array('store')) ? ' class="current"' : '';?>>店铺</li>

      </ul>

        <!-- END - Dino -->        	
            <div class="head-search-bar" id="head-search-bar">
        <form action="<?php echo SHOP_SITE_URL;?>" method="get" class="search-form" id="top_search_form">

          <input name="con" id="search_act" value="search" type="hidden">

          <!-- Add Input - Dino -->

          <input name="search_type" id="search_type" value="<?php echo $_GET['search_type'] ? $_GET['search_type'] : 'goods';?>" type="hidden">

          <?php

      if ($_GET['keyword']) {

        $keyword = stripslashes($_GET['keyword']);

      } elseif ($output['rec_search_list']) {

                $_stmp = $output['rec_search_list'][array_rand($output['rec_search_list'])];

        $keyword_name = $_stmp['name'];

        $keyword_value = $_stmp['value'];

      } else {

                $keyword = '';

            }

    ?>

          <input name="keyword" id="keyword" type="text" class="input-text" value="<?php echo $keyword;?>" maxlength="60" x-webkit-speech lang="zh-CN" onwebkitspeechchange="foo()" placeholder="<?php echo $keyword_name ? $keyword_name : '请输入您要搜索的商品关键字';?>" data-value="<?php echo rawurlencode($keyword_value);?>" x-webkit-grammar="builtin:search" autocomplete="off" />

          <input type="submit" id="button" value="搜 &nbsp索" class="input-submit">

        </form>
<!--
	作者：511613932@qq.com
	时间：2017-02-23
	描述：
-->        
<script type="text/javascript">

/** 
* 判断是否null 
* @param data 
*/ 
function isNull(data){ 
return (data == "" || data == undefined || data == null) ? false : data; 
}






$(document).ready(function(){	
/**
 *初始化搜索参数 
 */
 var one_act = $('#search_act').attr("value");

if(isNull(one_act)){
if(one_act == 'goods' || one_act == 'index'){
	$('#search_act').attr("value",'search');
	}else{
		$('#search_act').attr("value",one_act);
	}	

} 		
 	$("#ks-component").hover(null,
	function() {
		$('#ks-component').addClass('search-popupmenu-hidden');  
	});

	var ceidf = false;
	var cthis = false;
function zdxs(){
	var j_cloudList = "#ks-component0";var f_cloudList = "#J_CloudList0";
	if(isNull($(f_cloudList).html())){

		$(j_cloudList).addClass("search-menu-item-hover");
		$(j_cloudList).addClass("search-menuitem-hover");		
			$('.search-combobox-menu-footer').css('display','block');
			$(f_cloudList).css('display','block');		
	}
}			
function houa(){
	zdxs();	
	$(".ks-component-child1").hover(function() {
		var daraid = $(this).attr('id');
		var pom = "#"+daraid+' .item-wrapper';
		var data_index = $(pom).attr("data-index");
		var ceid = "#J_CloudList"+data_index;
		var ceul = "#F_CloudList"+data_index;
		var datau = "#dataurl"+data_index;
		$(this).click(function(){
			window.location.href= $(datau).data('url');
		})
		ceidf = ceid;
		cthis =$(this);
		var l_top = $(this).position().top;
		var dqceid = ceid+' .inner-wrap'
		var fu_div = $('.search-popupmenu-content').children('div').length -1;
    var fu_li = $(ceul).children('li').length;
    
    //计算推荐分类高度
    var li_last ='0';
    if(fu_li > 0){
    	li_last = fu_li / 2 * 25 +32;   	         
    }
    var jis = 220 - li_last; 
		if(l_top > 30){
		  if(l_top > 150){
		  	if(fu_li > 8 && fu_li < 10){jis + 25}
		  	if(fu_li > 10){jis + 45}
		  	$(dqceid).css("padding-top",jis);
		  }else{
		  	$(dqceid).css("padding-top",l_top - 30);
		  } 
		  
		}
		
				
		if($(ceid).length>0){
			$('.search-combobox-menu-footer').css('display','block');
			$(ceid).css('display','block');
		}
		
		$(this).addClass("search-menu-item-hover");
		$(this).addClass("search-menuitem-hover");
		
		$(ceid).hover(function() {
			if($(ceid).length>0){
			  $('.search-combobox-menu-footer').css('display','block');
			  $(ceid).css('display','block');
			  cthis.addClass("search-menu-item-hover");
		    cthis.addClass("search-menuitem-hover");
       
		  }
		},function() {
		if($(ceidf).length>0){
			$('.search-combobox-menu-footer').css('display','none');
			$(ceidf).css('display','none');
		}    
		cthis.removeClass("search-menu-item-hover");
		cthis.removeClass("search-menuitem-hover");
				
	  });	
	},
	function(ceid) {
		if($(ceidf).length>0){
			$('.search-combobox-menu-footer').css('display','none');
			$(ceidf).css('display','none');
		}    
		$(this).removeClass("search-menu-item-hover");
		$(this).removeClass("search-menuitem-hover");

	});
}	
//生成	
  $("#keyword").keyup(function(){
  	var urrll = $("#search_type").val();
  	var urrllsearch = $("#search_type").val();
  	var actt = urrll;
  	if(urrll == 'goods'){
  		actt = 'search';
  	}

	  if($("#keyword").val()){
	    $.ajax({
        type: "GET",
        dataType: "json",
        url: SHOP_SITE_URL + "/index.php?con="+ actt +"&fun="+ urrll + "suggestions",
        data: "q=1&term="+$("#keyword").val(),
        success: function(data){
        	var eem = '<b>';  
        	var _div = false;
        	var _li = $('.search-menu-content');
        	var _ul = $('.cloud-box');
        	$('.ks-component-child1').remove();$('.J_cloud-handle').remove();$('.search-combobox-menu-footer').css('display','none');
        	if(data !==null){ 
        	  if(data.retur.length > 0){  

              $.each(data.retur, function(i, o){
              	if(o.em !== undefined){
              		eem = o.em+ '<b>';
              		
              	}
              	
            	  var tecs = '<div id="dataurl'+ i +'" data-url= "' + SHOP_SITE_URL + '/index.php?con='+ actt +'&search_type='+ urrllsearch +'&keyword='+ o.setword +'" ><div data-index="' + i + '" class="item-wrapper"><span class="item-text">' + eem + o.keyword + '</b></span><span class="item-count"></span></div></div>';
            	  if(data.magic !== null){
            	    if(data.magic.length > 0){
            		    $.each(data.magic, function(ii, oo){
            			    if(oo.index == i){
            			  	  tecs = '<div class="search-wrap-cloud"><div id="dataurl'+ i +'" data-url= "' + SHOP_SITE_URL + '/index.php?con='+ actt +'&search_type='+ urrllsearch +'&keyword='+ o.setword +'" ><div data-index="' + i + '" class="item-wrapper"><span class="item-text">' + eem + o.keyword + '</b></span><span class="item-count"></span></div></div></div>';
            			      return true;
            			    }
            		    });	
            	    }
            	  }  
          	    $('<div id="ks-component' + i + '" class="search-menuitem ks-component-child1" role="menuitem" style="-webkit-user-select: none;" aria-pressed="false">'+ tecs +'</div>').appendTo(_li);
            
              });            	
            }
            
          var datr = data.magic;
          var parentul=$('<ul></ul>');
          if(data.magic !== null){
            if(datr.length > 0){
          	  $.each(datr, function(q, w){         		
          		  if(w.datea.length > 0){
          			  parentul=$('<ul></ul>');
          			  $.each(w.datea, function(a, d){          				
          				  var aac = 'hot';
          				  (d.type == 'hot') ? aac="hot" : aac=""
          				  $('<li id="'+ w.index +'a'+ a +'" ><a target="_self" href=  "' + SHOP_SITE_URL + '/index.php?con='+ actt +'&search_type='+ urrllsearch +'&cate_id='+ d.id +'&keyword='+ w.type +'" class="cloud-link '+ aac +'"><span>'+ d.title +'</span></a></li>').appendTo(parentul);
          			  });	
          		  }          		
          		  $('<div id="J_CloudList'+ w.index +'" data-align="true" class="J_cloud-handle cloud-list type-taggroup" style="display: none;"><div class="inner-wrap"><h3>'+ w.type +'</h3><ul><li class="tag-group"><ul id="F_CloudList'+ w.index +'">'+ parentul.html() +'</ul></li></ul></div></div>').appendTo(_ul);
          	    parentul = '';
          	  });	
            }
          }
          $('#ks-component').removeClass('search-popupmenu-hidden');  
          }else{$('#ks-component').addClass('search-popupmenu-hidden');}   
         
        houa();   
        } 
      });
	  }
  });
  
  
});
</script>
<!--
	作者：511613932@qq.com
	时间：2017-02-23
	描述：
-->       
<div id="ks-component" class="search-popupmenu search-menu search-cloud-menu search-popupmenu-hidden search-menu-hidden" role="menu" style="-webkit-user-select: none; left: 3px; top: 42px; width: 481px; height: auto;" aria-pressed="false" aria-activedescendant="">
	<div class="search-popupmenu-content search-menu-content"></div>
	<div class="search-combobox-menu-footer cloud-footer" style="display: none;">
		<div class="cloud-box" style="height: 100%;"></div>
	</div>
</div>                       
            </div>
            <div class="keyword">
                <ul>
                    <?php if (is_array($output['hot_search']) && !empty($output['hot_search'])) {
                        foreach ($output['hot_search'] as $val) { ?>
                            <li>
                                <a href="<?php echo urlShop('search', 'index', array('keyword' => $val)); ?>"><?php echo $val; ?></a>
                            </li>
                        <?php }
                    } ?>
                </ul>
            </div>
        </div>
        <div class="head-user-menu">
            <dl class="my-mall">
                <dt><span class="ico"></span>我的商城<i class="arrow"></i></dt>
                <dd>
                    <div class="sub-title">
                        <h4><?php echo $_SESSION['member_name']; ?>
                            <?php if ($output['member_info']['level_name']) { ?>
                                <div class="nc-grade-mini" style="cursor:pointer;"
                                     onclick="javascript:go('<?php echo urlShop('pointgrade', 'index'); ?>');"><?php echo $output['member_info']['level_name']; ?></div>
                            <?php } ?>
                        </h4>
                        <a href="<?php echo urlShop('member', 'home'); ?>" class="arrow">我的用户中心<i></i></a></div>
                    <div class="user-centent-menu">
                        <ul>
                            <li>
                                <a href="<?php echo MEMBER_SITE_URL; ?>/index.php?con=member_message&fun=message">站内消息(<span><?php echo $output['message_num'] > 0 ? $output['message_num'] : '0'; ?></span>)</a>
                            </li>
                            <li><a href="<?php echo SHOP_SITE_URL; ?>/index.php?con=member_order"
                                   class="arrow">我的订单<i></i></a></li>
                            <li>
                                <a href="<?php echo SHOP_SITE_URL; ?>/index.php?con=member_consult&fun=my_consult">咨询回复(<span
                                        id="member_consult">0</span>)</a></li>
                            <li><a href="<?php echo SHOP_SITE_URL; ?>/index.php?con=member_favorite_goods&fun=fglist"
                                   class="arrow">我的收藏<i></i></a></li>
                            <?php if (C('voucher_allow') == 1) { ?>
                                <li><a href="<?php echo MEMBER_SITE_URL; ?>/index.php?con=member_voucher">代金券(<span
                                            id="member_voucher">0</span>)</a></li>
                            <?php } ?>
                            <?php if (C('points_isuse') == 1) { ?>
                                <li><a href="<?php echo MEMBER_SITE_URL; ?>/index.php?con=member_points" class="arrow">我的积分<i></i></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="browse-history">
                        <div class="part-title">
                            <h4>最近浏览的商品</h4>
                            <span style="float:right;"><a
                                    href="<?php echo SHOP_SITE_URL; ?>/index.php?con=member_goodsbrowse&fun=list">全部浏览历史</a></span>
                        </div>
                        <ul>
                            <li class="no-goods"><img class="loading"
                                                      src="<?php echo SHOP_TEMPLATES_URL; ?>/images/loading.gif"/></li>
                        </ul>
                    </div>
                </dd>
            </dl>
            <dl class="my-cart">
                <?php if ($output['cart_goods_num'] > 0) { ?>
                    <div class="addcart-goods-num"><?php echo $output['cart_goods_num']; ?></div>
                <?php } ?>
                <dt><span class="ico"></span>购物车结算<i class="arrow"></i></dt>
                <dd>
                    <div class="sub-title">
                        <h4>最新加入的商品</h4>
                    </div>
                    <div class="incart-goods-box">
                        <div class="incart-goods"><img class="loading"
                                                       src="<?php echo SHOP_TEMPLATES_URL; ?>/images/loading.gif"/>
                        </div>
                    </div>
                    <div class="checkout"><span
                            class="total-price">共<i><?php echo $output['cart_goods_num']; ?></i><?php echo $lang['nc_kindof_goods']; ?></span><a
                            href="<?php echo SHOP_SITE_URL; ?>/index.php?con=cart" class="btn-cart">结算购物车中的商品</a></div>
                </dd>
            </dl>
        </div>
    </header>
</div>
<!-- PublicHeadLayout End -->

<!-- publicNavLayout Begin -->
<nav class="public-nav-layout <?php if ($output['channel']) {
    echo 'channel-' . $output['channel']['channel_style'] . ' channel-' . $output['channel']['channel_id'];
} ?>">
    <div class="wrapper">
        <div class="all-category">
            <?php require template('layout/home_goods_class'); ?>
        </div>
        <ul class="site-menu">
            <li>
                <a href="<?php echo SHOP_SITE_URL; ?>" <?php if ($output['index_sign'] == 'index' && $output['index_sign'] != '0') {
                    echo 'class="current"';
                } ?>><?php echo $lang['nc_index']; ?>
                </a>
            </li>

        <!--           判断显示在首页后面  start  author liming-->
            <?php if (!empty($output['nav_list']) && is_array($output['nav_list'])) { ?>
                <?php foreach ($output['nav_list'] as $nav) { ?>
                    <?php if ($nav['nav_location'] == '1') { ?>
                        <?php if($nav['is_nav_add'] =='1'){;?>

                            <li><a
                                    <?php
                                    if ($nav['nav_new_open']) {
                                        echo ' target="_blank"';
                                    }
                                    switch ($nav['nav_type']) {
                                        case '0':
                                            echo ' href="' . $nav['nav_url'] . '"';
                                            break;
                                        case '1':
                                            echo ' href="' . urlShop('search', 'index', array('cate_id' => $nav['item_id'])) . '"';
                                            if (isset($_GET['cate_id']) && $_GET['cate_id'] == $nav['item_id']) {
                                                echo ' class="current"';
                                            }
                                            break;
                                        case '2':
                                            echo ' href="' . urlMember('article', 'article', array('ac_id' => $nav['item_id'])) . '"';
                                            if (isset($_GET['ac_id']) && $_GET['ac_id'] == $nav['item_id']) {
                                                echo ' class="current"';
                                            }
                                            break;
                                        case '3':
                                            echo ' href="' . urlShop('activity', 'index', array('activity_id' => $nav['item_id'])) . '"';
                                            if (isset($_GET['activity_id']) && $_GET['activity_id'] == $nav['item_id']) {
                                                echo ' class="current"';
                                            }
                                            break;
                                    }
                                    ?>><?php echo $nav['nav_title']; ?></a></li>

                        <?php };?>
                    <?php } ?>
                <?php } ?>
            <?php } ?>

            <!--           判断显示在首页后面  end author liming-->

            <?php if (C('groupbuy_allow')) { ?>
                <li>
                    <a href="<?php echo urlShop('show_groupbuy', 'index'); ?>" <?php if ($output['index_sign'] == 'groupbuy' && $output['index_sign'] != '0') {
                        echo 'class="current"';
                    } ?>> <?php echo $lang['nc_groupbuy']; ?></a>
                </li>
            <?php } ?>

            <li>
                <a href="<?php echo urlShop('brand', 'index'); ?>" <?php if ($output['index_sign'] == 'brand' && $output['index_sign'] != '0') {
                    echo 'class="current"';
                } ?>> <?php echo $lang['nc_brand']; ?></a>
            </li>
            <?php if (C('points_isuse') && C('pointshop_isuse')) { ?>
                <li>
                    <a href="<?php echo urlShop('pointshop', 'index'); ?>" <?php if ($output['index_sign'] == 'pointshop' && $output['index_sign'] != '0') {
                        echo 'class="current"';
                    } ?>> <?php echo $lang['nc_pointprod']; ?>
                    </a>
                </li>
            <?php } ?>

            <?php if (C('cms_isuse')) { ?>
                <li>
                    <a href="<?php echo urlShop('special', 'special_list'); ?>" <?php if ($output['index_sign'] == 'special' && $output['index_sign'] != '0') {
                        echo 'class="current"';
                    } ?>> 专题
                    </a>
                </li>
            <?php } ?>

            <?php if (!empty($output['nav_list']) && is_array($output['nav_list'])) { ?>
                <?php foreach ($output['nav_list'] as $nav) { ?>
                    <?php if ($nav['nav_location'] == '1') { ?>
                        <?php if($nav['is_nav_add'] =='0'){;?>

                            <li><a
                                    <?php
                                    if ($nav['nav_new_open']) {
                                        echo ' target="_blank"';
                                    }
                                    switch ($nav['nav_type']) {
                                        case '0':
                                            echo ' href="' . $nav['nav_url'] . '"';
                                            break;
                                        case '1':
                                            echo ' href="' . urlShop('search', 'index', array('cate_id' => $nav['item_id'])) . '"';
                                            if (isset($_GET['cate_id']) && $_GET['cate_id'] == $nav['item_id']) {
                                                echo ' class="current"';
                                            }
                                            break;
                                        case '2':
                                            echo ' href="' . urlMember('article', 'article', array('ac_id' => $nav['item_id'])) . '"';
                                            if (isset($_GET['ac_id']) && $_GET['ac_id'] == $nav['item_id']) {
                                                echo ' class="current"';
                                            }
                                            break;
                                        case '3':
                                            echo ' href="' . urlShop('activity', 'index', array('activity_id' => $nav['item_id'])) . '"';
                                            if (isset($_GET['activity_id']) && $_GET['activity_id'] == $nav['item_id']) {
                                                echo ' class="current"';
                                            }
                                            break;
                                    }
                                    ?>><?php echo $nav['nav_title']; ?></a></li>

                            <?php };?>
                    <?php } ?>
                <?php } ?>
            <?php } ?>

        </ul>
    </div>
</nav>
<script type="text/javascript">

    /*************android 和苹果下载的二维码切换 author liming start  2016.12.06************/
        //android 和苹果下载的二维码切换
    $(function () {
        $('.qr-btn').live('mouseover', function () {
            var ncdata = $(this).attr('ncdata');
            $(this).css('color', 'red').css('border', '1px solid red');
            $($('.' + ncdata).closest('div')[0]).css('display', 'block');
            if (ncdata == 'android') {
                $($('.apple').closest('div')[0]).css('display', 'none');
                $($('.qr-btn')[1]).css('color', '').css('border', '');
                $($('.android').parent('div')[0]).css('display', 'block');
            } else if (ncdata == 'apple') {
                $($('.android').closest('div')[0]).css('display', 'none');
                $($('.qr-btn')[0]).css('color', '').css('border', '');
                $($('.apple').parent('div')[0]).css('display', 'block')
            }
        })
    })

    /*************android 和苹果下载的二维码切换 author liming end 2016.12.06************/
</script>
