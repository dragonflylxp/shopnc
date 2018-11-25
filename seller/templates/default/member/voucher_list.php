<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

    <span class="header-tab"> <a href="javascript:void(0);" class="cur">我的代金券</a> <a href="<?php echo urlMobile('member_voucher','voucher_pwex');?>">领取代金券</a> </span>

   <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>



  <?php include template('layout/toptip');?>



</header>

<div class="nctouch-main-layout">

  <div class="nctouch-voucher-list">

    <ul class="nctouch-tickets" id="voucher-list">

    </ul>

  </div>

</div>

<div class="fix-block-r"> <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a> </div>

<footer id="footer" class="bottom"></footer>

<script type="text/html" id="voucher-list-tmpl">

<% if (voucher_list && voucher_list.length > 0) { %>

<% for (var k in voucher_list) { var v = voucher_list[k]; %>

	<li class="ticket-item <% if (v.voucher_state == 1) { %>normal<% }else{ %>invalid<%}%>">

		<% if (v.is_own_shop) { %>

		<a href="<?php echo urlMobile('goods','list');?>">

		<% }else{ %>

		<a href="<?php echo urlMobile('store','index');?>&store_id=<%= v.store_id %>">

		<% } %>

		<div class="border-left"></div>

		<div class="block-center">

			<div class="store-info">

				<div class="store-avatar"><img src="<%= v.voucher_t_customimg %>" /></div>

				<dl>

					<dt class="store-name"><%= v.store_name %></dt>

					<dd>有效期至：<%= v.voucher_end_date %></dd>

				</dl>

			</div>

			<div class="ticket-info">

				<div class="bg-ico"></div>

				<% if (v.voucher_state==2) { %>

				<div class="watermark ysy"></div>

				<% } %>

				<% if (v.voucher_state==3 || v.voucher_state==4) { %>

				<div class="watermark ysx"></div>

				<% } %>

				<dl>

				<dt>￥<%= v.voucher_price %></dt>

				<dd><% if (v.voucher_limit) { %>满<%= v.voucher_limit %>使用<% } %></dd>

				</dl>

			</div>

		</div>

		<div class="border-right"></div>

		</a>

	</li>

<% } %>

<li class="loading"><div class="spinner"><i></i></div>数据读取中</li>

<% } else { %>

	<div class="nctouch-norecord voucher">

		<div class="norecord-ico"><i></i></div>

		<dl>

			<dt>您还没有相关的代金券</dt>

			<dd>店铺代金券可享受商品折扣</dd>

		</dl>

	</div>

<% } %>

</script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/ncscroll-load.js"></script> 

<script>

	function showSpacing(){

		$('.spacing-div').remove();

		$('.invalid').first().before('<div class="spacing-div"><span>已失效的券</span></div>');

	}

	$(function(){

		var key = getCookie('key');

		if (!key) {

            window.location.href =  WapUrl + "/tmpl/member/login.html";

			return;

		}

		//渲染list

		var load_class = new ncScrollLoad();

		load_class.loadInit({

			'url':ApiUrl + '/index.php?con=member_voucher&fun=voucher_list',

			'getparam':{'key':key},

			'tmplid':'voucher-list-tmpl',

			'containerobj':$("#voucher-list"),

			'iIntervalId':true,

			'callback':showSpacing,

			'data':{WapSiteUrl:WapSiteUrl}

		});

	});

</script>

