<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">



</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>物流信息</h1>

    </div>

    <div class="header-r"><a href="javascript:void(0)" onClick="location.reload();"><i class="refresh"></i></a></div>

  </div>

</header>

<div class="nctouch-main-layout" id="order-delivery"><div class="loading"><div class="spinner"><i></i></div>物流信息读取中...</div></div>

<div class="nctouch-delivery-tip">以上部分信息来自于第三方，仅供参考<br/>

  如需准确信息可联系卖家或物流公司</div>

<script type="text/html" id="order-delivery-tmpl">

<% if (err) { %>

	<div class="no-record m10"><%= err %></div>

<% } else { %>

<div class="nctouch-order-deivery-info">

	<i class="icon"></i>

	<dl>

		<dt>物流公司：<%= express_name %></dt>

        <dd>运单号码：<%= shipping_code %></dd>

	</dl>

</div>

<div class="nctouch-order-deivery-con">

	<ul>

	<% for (var i in deliver_info) { %>

		<li><span><i></i></span><%= deliver_info[i] %></li>

	<% } %>

	</ul>

</div>

<% } %>

</script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/order_delivery.js"></script>

