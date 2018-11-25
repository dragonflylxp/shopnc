<?php defined('Inshopec') or exit('Access Invalid!');?>
<!doctype html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="author" content="shopec">
<meta name="copyright" content="shopec Inc. All Rights Reserved">
<meta name="renderer" content="webkit">
<meta name="renderer" content="ie-stand">
<?php echo html_entity_decode($output['setting_config']['qq_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['sina_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_qqzone_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_sinaweibo_appcode'],ENT_QUOTES); ?>
<style type="text/css">
body { _behavior: url(<?php echo SHOP_TEMPLATES_URL;
?>/css/csshover.htc);
}
</style>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_header.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var LOGIN_SITE_URL = '<?php echo LOGIN_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var SITEURL = '<?php echo SHOP_SITE_URL;?>';var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
</script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript">
var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';
$(function(){
	//首页左侧分类菜单
	$(".category ul.menu").find("li").each(
		function() {
			$(this).hover(
				function() {
				    var cat_id = $(this).attr("cat_id");
					var menu = $(this).find("div[cat_menu_id='"+cat_id+"']");
					menu.show();
					$(this).addClass("hover");					
					var menu_height = menu.height();
					if (menu_height < 60) menu.height(80);
					menu_height = menu.height();
					var li_top = $(this).position().top;
					$(menu).css("top",-li_top + 38);
				},
				function() {
					$(this).removeClass("hover");
				    var cat_id = $(this).attr("cat_id");
					$(this).find("div[cat_menu_id='"+cat_id+"']").hide();
				}
			);
		}
	);
	$(".head-user-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});
	$('.head-user-menu .my-mall').mouseover(function(){// 最近浏览的商品
		load_history_information();
		$(this).unbind('mouseover');
	});
	$('.head-user-menu .my-cart').mouseover(function(){// 运行加载购物车
		load_cart_information();
		$(this).unbind('mouseover');
	});
	$('#button').click(function(){
	    if ($('#keyword').val() == '') {
		    if ($('#keyword').attr('data-value') == '') {
			    return false
			} else {
				window.location.href="<?php echo SHOP_SITE_URL?>/index.php?con=search&fun=index&keyword="+$('#keyword').attr('data-value');
			    return false;
			}
	    }
	});
	$(".head-search-bar").hover(null,
	function() {
		$('#search-tip').hide();
	});
	// input ajax tips
	$('#keyword').focus(function(){$('#search-tip').show()}).autocomplete({
		//minLength:0,
        source: function (request, response) {
            $.getJSON('<?php echo SHOP_SITE_URL;?>/index.php?con=search&fun=auto_complete', request, function (data, status, xhr) {
                $('#top_search_box > ul').unwrap();
                response(data);
                if (status == 'success') {
                    $('#search-tip').hide();
                    $(".head-search-bar").unbind('mouseover');
                    $('body > ul:last').wrap("<div id='top_search_box'></div>").css({'zIndex':'1000','width':'362px'});
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
<?php require_once template('layout/layout_top');?>
<!-- PublicHeadLayout Begin -->
<div class="header-wrap">
  <header class="public-head-layout wrapper">
    <h1 class="site-logo"><a href="<?php echo SHOP_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a></h1>
    <?php if (C('mobile_isuse') && C('mobile_app')){?>
    <div class="head-app"><span class="pic"></span>
      <div class="download-app">
        <div class="qrcode"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('mobile_app');?>" ></div>
        <div class="hint">
          <h4>扫描二维码</h4>
          下载手机客户端</div>
        <div class="addurl">
          <?php if (C('mobile_apk')){?>
          <a href="<?php echo C('mobile_apk');?>" target="_blank"><i class="icon-android"></i>Android</a>
          <?php } ?>
          <?php if (C('mobile_ios')){?>
          <a href="<?php echo C('mobile_ios');?>" target="_blank"><i class="icon-apple"></i>iPhone</a>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php } ?>
    <div class="head-search-layout">
      <div class="head-search-bar" id="head-search-bar">
        <form action="<?php echo SHOP_SITE_URL;?>" method="get" class="search-form" id="top_search_form">
          <input name="con" id="search_act" value="search" type="hidden">
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
          <input type="submit" id="button" value="<?php echo $lang['nc_common_search'];?>" class="input-submit">
        </form>
        <div class="search-tip" id="search-tip">
          <div class="search-history">
            <div class="title">历史纪录<a href="javascript:void(0);" id="search-his-del">清除</a></div>
            <ul id="search-his-list">
              <?php if (is_array($output['his_search_list']) && !empty($output['his_search_list'])) { ?>
              <?php foreach($output['his_search_list'] as $v) { ?>
              <li><a href="<?php echo urlShop('search', 'index', array('keyword' => $v));?>"><?php echo $v ?></a></li>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
          <div class="search-hot">
            <div class="title">热门搜索...</div>
            <ul>
              <?php if (is_array($output['rec_search_list']) && !empty($output['rec_search_list'])) { ?>
              <?php foreach($output['rec_search_list'] as $v) { ?>
              <li><a href="<?php echo urlShop('search', 'index', array('keyword' => $v['value']));?>"><?php echo $v['value']?></a></li>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="keyword">
        <ul>
          <?php if(is_array($output['hot_search']) && !empty($output['hot_search'])) { foreach($output['hot_search'] as $val) { ?>
          <li><a href="<?php echo urlShop('search', 'index', array('keyword' => $val));?>"><?php echo $val; ?></a></li>
          <?php } }?>
        </ul>
      </div>
    </div>
    <div class="head-user-menu">
      <dl class="my-mall">
        <dt><span class="ico"></span>我的商城<i class="arrow"></i></dt>
        <dd>
          <div class="sub-title">
            <h4><?php echo $_SESSION['member_name'];?>
              <?php if ($output['member_info']['level_name']){ ?>
              <div class="nc-grade-mini" style="cursor:pointer;" onclick="javascript:go('<?php echo urlShop('pointgrade','index');?>');"><?php echo $output['member_info']['level_name'];?></div>
              <?php } ?>
            </h4>
            <a href="<?php echo urlShop('member', 'home');?>" class="arrow">我的用户中心<i></i></a></div>
          <div class="user-centent-menu">
            <ul>
              <li><a href="<?php echo MEMBER_SITE_URL;?>/index.php?con=member_message&fun=message">站内消息(<span><?php echo $output['message_num']>0 ? $output['message_num']:'0';?></span>)</a></li>
              <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?con=member_order" class="arrow">我的订单<i></i></a></li>
              <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?con=member_consult&fun=my_consult">咨询回复(<span id="member_consult">0</span>)</a></li>
              <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?con=member_favorite_goods&fun=fglist" class="arrow">我的收藏<i></i></a></li>
              <?php if (C('voucher_allow') == 1){?>
              <li><a href="<?php echo MEMBER_SITE_URL;?>/index.php?con=member_voucher">代金券(<span id="member_voucher">0</span>)</a></li>
              <?php } ?>
              <?php if (C('points_isuse') == 1){ ?>
              <li><a href="<?php echo MEMBER_SITE_URL;?>/index.php?con=member_points" class="arrow">我的积分<i></i></a></li>
              <?php } ?>
            </ul>
          </div>
          <div class="browse-history">
            <div class="part-title">
              <h4>最近浏览的商品</h4>
              <span style="float:right;"><a href="<?php echo SHOP_SITE_URL;?>/index.php?con=member_goodsbrowse&fun=list">全部浏览历史</a></span> </div>
            <ul>
              <li class="no-goods"><img class="loading" src="<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif" /></li>
            </ul>
          </div>
        </dd>
      </dl>
      <dl class="my-cart">
        <?php if ($output['cart_goods_num'] > 0) { ?>
        <div class="addcart-goods-num"><?php echo $output['cart_goods_num'];?></div>
        <?php } ?>
        <dt><span class="ico"></span>购物车结算<i class="arrow"></i></dt>
        <dd>
          <div class="sub-title">
            <h4>最新加入的商品</h4>
          </div>
          <div class="incart-goods-box">
            <div class="incart-goods"> <img class="loading" src="<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif" /> </div>
          </div>
          <div class="checkout"> <span class="total-price">共<i><?php echo $output['cart_goods_num'];?></i><?php echo $lang['nc_kindof_goods'];?></span><a href="<?php echo SHOP_SITE_URL;?>/index.php?con=cart" class="btn-cart">结算购物车中的商品</a> </div>
        </dd>
      </dl>
    </div>
  </header>
</div>
<!-- PublicHeadLayout End --> 

<!-- publicNavLayout Begin -->
<nav class="public-nav-layout <?php if($output['channel']) {echo 'channel-'.$output['channel']['channel_style'].' channel-'.$output['channel']['channel_id'];} ?>">
  <div class="wrapper">
    <div class="all-category">
      <?php require template('layout/home_goods_class');?>
    </div>
    <ul class="site-menu">
      <li><a href="<?php echo SHOP_SITE_URL;?>" <?php if($output['index_sign'] == 'index' && $output['index_sign'] != '0') {echo 'class="current"';} ?>><?php echo $lang['nc_index'];?></a></li>
      <?php if (C('groupbuy_allow')){ ?>
      <li><a href="<?php echo urlShop('show_groupbuy', 'index');?>" <?php if($output['index_sign'] == 'groupbuy' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['nc_groupbuy'];?></a></li>
      <?php } ?>
      <li><a href="<?php echo urlShop('brand', 'index');?>" <?php if($output['index_sign'] == 'brand' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['nc_brand'];?></a></li>
      <?php if (C('points_isuse') && C('pointshop_isuse')){ ?>
      <li><a href="<?php echo urlShop('pointshop', 'index');?>" <?php if($output['index_sign'] == 'pointshop' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['nc_pointprod'];?></a></li>
      <?php } ?>
      <?php if (C('cms_isuse')){ ?>
      <li><a href="<?php echo urlShop('special', 'special_list');?>" <?php if($output['index_sign'] == 'special' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> 专题</a></li>
      <?php } ?>
      <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
      <?php foreach($output['nav_list'] as $nav){?>
      <?php if($nav['nav_location'] == '1'){?>
      <li><a
        <?php
        if($nav['nav_new_open']) {
            echo ' target="_blank"';
        }
        switch($nav['nav_type']) {
            case '0':
                echo ' href="' . $nav['nav_url'] . '"';
                break;
            case '1':
                echo ' href="' . urlShop('search', 'index',array('cate_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['cate_id']) && $_GET['cate_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
            case '2':
                echo ' href="' . urlMember('article', 'article',array('ac_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['ac_id']) && $_GET['ac_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
            case '3':
                echo ' href="' . urlShop('activity', 'index', array('activity_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['activity_id']) && $_GET['activity_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
        }
        ?>><?php echo $nav['nav_title'];?></a></li>
      <?php }?>
      <?php }?>
      <?php }?>
    </ul>
  </div>
</nav>



<div style="width:1200px;margin:20px auto;">
	<p style="font-size:18px;font-weight:bold;text-align:center;">银行汇款方式支付</p>
	<ul class='offbank_list'>
		<li id='non-line'>公司名：<span><?php echo $output['payment_info']['payment_config']['company_name'];?></span></li>
		<li>卡&nbsp;&nbsp;&nbsp;号：<span><?php echo $output['payment_info']['payment_config']['id_code'];?></span></li>
		<li>开户行：<span><?php echo $output['payment_info']['payment_config']['bank_name'];?></span></li>
		<li>分&nbsp;&nbsp;&nbsp;行：<span><?php echo $output['payment_info']['payment_config']['bank_branch'];?></span></li>
        <li>订单号：<span><?php echo $output['order_info']['order_sn'];?></span></li>
	</ul>
      <p style="text-align:center;color:red;font-size: 16px;margin-top: 10px;">备注：<span>汇款时，如不是收货人本人付款，请付款人在付款备注栏注明商品交易订单号与付款人姓名</span></p>
</div>
<div class="ncs-btn" style="width:1200px;margin:20px auto;text-align:center;" >
    <div class="ncs-cart-popup">
        <dd class="btns"><a href="javascript:void(0);" class="ncbtn-mini ncbtn-mint" onclick="location.href='<?php echo $output['url'];?>&fun=show_order&order_id=<?php echo $output['order_id'];?>'">查看订单</a> <a href="javascript:void(0);" class="ncbtn-mini" value="" onclick="location.href='index.php'">继续购物</a></dd>
         </dl>
     </div>
</div>

 <style type="text/css">
        .offbank_list { width:800px;margin:0px auto;border:1px solid #2cbca3;margin-top:20px;}
        .offbank_list li {border-top:1px solid #2cbca3;height:36px;line-height:36px;font-size:16px;padding-left:8px;}
        #non-line{border-top:0px solid #2cbca3;}
   </style>

<?php defined('Inshopec') or exit('Access Invalid!');?>
<?php echo getChat($layout);?>

<div id="cti">
  <div class="wrapper">
    <ul>
      <?php if ($output['contract_list']) {?>
      <?php foreach($output['contract_list'] as $k=>$v){?>
        <?php if($v['cti_descurl']){ ?>
            <li><span class="line"></span><a href="<?php echo $v['cti_descurl'];?>" target="_blank"><span class="icon"> <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span></a></li>
        <?php }else{ ?>
            <li><span class="line"></span> <span class="icon"> <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span> </li>
        <?php }?>
      <?php }?>
      <?php }?>
    </ul>
  </div>
</div>
<div id="faq">
  <div class="wrapper">
    <?php if(is_array($output['article_list']) && !empty($output['article_list'])){ ?>
    <ul>
      <?php foreach ($output['article_list'] as $k=> $article_class){ ?>
      <?php if(!empty($article_class)){ ?>
      <li>
        <dl class="s<?php echo ''.$k+1;?>">
          <dt>
            <?php if(is_array($article_class['class'])) echo $article_class['class']['ac_name'];?>
          </dt>
          <?php if(is_array($article_class['list']) && !empty($article_class['list'])){ ?>
          <?php foreach ($article_class['list'] as $article){ ?>
          <dd><i></i><a href="<?php if($article['article_url'] != '')echo $article['article_url'];else echo urlMember('article', 'show',array('article_id'=> $article['article_id']));?>" title="<?php echo $article['article_title']; ?>"> <?php echo $article['article_title'];?> </a></dd>
          <?php }?>
          <?php }?>
        </dl>
      </li>
      <?php }?>
      <?php }?>
    </ul>
    <?php }?>
  </div>
</div>
<div id="footer" class="wrapper">
  <p><a href="<?php echo SHOP_SITE_URL;?>"><?php echo $lang['nc_index'];?></a>
    <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
    <?php foreach($output['nav_list'] as $nav){?>
    <?php if($nav['nav_location'] == '2'){?>
    | <a  <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){
    	case '0':echo $nav['nav_url'];break;
    	case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
    	case '2':echo urlMember('article', 'article',array('ac_id'=>$nav['item_id']));break;
    	case '3':echo urlShop('activity', 'index',array('activity_id'=>$nav['item_id']));break;
    }?>"><?php echo $nav['nav_title'];?></a>
    <?php }?>
    <?php }?>
    <?php }?>
  </p><?php echo $output['setting_config']['icp_number']; ?><br />
  <?php echo html_entity_decode($output['setting_config']['statistics_code'],ENT_QUOTES); ?> </div>
<?php if (C('debug') == 1){?>
<div id="think_page_trace" class="trace">
  <fieldset id="querybox">
    <legend><?php echo $lang['nc_debug_trace_title'];?></legend>
    <div> <?php print_r(\shopec\Tpl::showTrace());?> </div>
  </fieldset>
</div>
<?php }?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<!-- 对比 --> 
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/compare.js"></script> 
<script type="text/javascript">
$(function(){
	// Membership card
	$('[nctype="mcard"]').membershipCard({type:'shop'});
});
</script>