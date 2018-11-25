<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_store.css">



</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1);"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>店铺介绍</h1>

    </div>

    <div class="header-r"> <a href="javascript:void(0);" id="header-nav"><i class="more"></i><sup></sup></a> </div>

  </div>

   <?php include template('layout/toptip');?>

</header>

<div class="nctouch-main-layout fixed-Width">

  <div class="nctouch-main-layout" id="store_intro">

  	<div class="pre-loading">

		  <div class="pre-block">

		    <div class="spinner"><i></i></div>

		    数据读取中... </div>

		</div>

   </div>

</div>

<div class="fix-block-r">

	<a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>

</div>

</body>

<script type="text/html" id="store_intro_tpl">

	<div class="nctouch-store-info">

		<div class="store-avatar"><img src="<%= store_info.store_avatar %>" /></div>

		<dl class="store-base">

			<dt><%= store_info.store_name %></dt>

			<dd class="class"><% if(!store_info.is_own_shop){%>类型：<%= store_info.sc_name %><% } %></dd>

			<dd class="type">

				<% if(store_info.is_own_shop){%>平台自营<% }else{%>普通店铺<% } %>

			</dd>

		</dl>

		<div class="store-collect">

			<a href="javascript:void(0);" id="store_collected">已收藏</a>

			<a href="javascript:void(0);" id="store_notcollect">收藏</a>

			<p><input type="hidden" id="store_favornum_hide" value="<%= store_info.store_collect %>"/>

			<em id="store_favornum"><%= store_info.store_collect %></em>粉丝</p>

		</div>

	</div>

	<% if(!store_info.is_own_shop){%>

	<div class="nctouch-store-block">

		<ul class="credit">

			<li><!-- span 样式名称可以是high、equal、low -->

				<h4>描述相符</h4>

				<span class="<%=store_info.store_credit.store_desccredit.percent_class %>">

					<strong><%= store_info.store_credit.store_desccredit.credit %></strong>

					<% if(store_info.store_credit.store_desccredit.percent_class == 'equal'){%>

					与同行业持平

					<% }else{ %>

					<%= store_info.store_credit.store_desccredit.percent_text %>同行业

					<% } %>

					<em><%= store_info.store_credit.store_desccredit.percent %></em>

				</span>

			</li>

			<li>

				<h4>服务态度</h4>

				<span class="<%=store_info.store_credit.store_servicecredit.percent_class %>">

					<strong><%= store_info.store_credit.store_servicecredit.credit %></strong>

					<% if(store_info.store_credit.store_servicecredit.percent_class == 'equal'){%>

					与同行业持平

					<% }else{ %>

					<%= store_info.store_credit.store_servicecredit.percent_text %>同行业

					<% } %>

					<em><%= store_info.store_credit.store_servicecredit.percent %></em>

				</span>

			</li>

			<li>

				<h4>物流服务</h4>

				<span class="<%=store_info.store_credit.store_deliverycredit.percent_class %>">

					<strong><%= store_info.store_credit.store_deliverycredit.credit %></strong>

					<% if(store_info.store_credit.store_deliverycredit.percent_class == 'equal'){%>

					与同行业持平

					<% }else{ %>

					<%= store_info.store_credit.store_deliverycredit.percent_text %>同行业

					<% } %>

					<em><%= store_info.store_credit.store_deliverycredit.percent %></em>

				</span>

			</li>

		</ul>

	</div>

	<% } %>

	<div class="nctouch-store-block">

		<ul>

			<% if(store_info.store_company_name){%>

			<li>

				<h4>公司名称</h4>

				<span><%= store_info.store_company_name %></span>

			</li>

			<% } %>

			<% if(store_info.area_info){%>

			<li>

				<h4>所在地</h4>

				<span><%= store_info.area_info %></span>

			</li>

			<% } %>

			<% if(store_info.store_time_text){%>

			<li>

				<h4>开店时间</h4>

				<span><%= store_info.store_time_text %></span>

			</li>

			<% } %>

			<% if(store_info.store_zy){%>

			<li>

				<h4>主营商品</h4>

				<span><%= store_info.store_zy %></span>

			</li>

			<% } %>

		</ul>

	</div>

	<div class="nctouch-store-block">

		<ul>

			<% if(store_info.store_phone){%>

			<li>

				<h4>联系电话</h4>

				<span>

					<%= store_info.store_phone %>

				</span>

				<a href="tel:<%= store_info.store_phone %>" class="call"></a>

			</li>

			<% } %>

			<% if(store_info.store_workingtime){%>

			<li>

				<h4>工作时间</h4>

				<span><%= store_info.store_workingtime %></span>

			</li>

			<% } %>

			<% if(store_info.store_qq || store_info.store_ww){%>

			<li>

				<h4>联系方式</h4>

				<span>

					<% if(store_info.store_qq){%>

					<a href="http://wpa.qq.com/msgrd?v=3&uin=<%= store_info.store_qq %>&site=qq&menu=yes" target="_blank" class="qq">

						<i></i>QQ联系

					</a>

					<% }　%>

				</span>

			</li>

			<% } %>

		</ul>

	</div>

</script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/store_intro.js"></script>

