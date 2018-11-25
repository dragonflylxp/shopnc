<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <span class="header-tab"><a href="javascript:void(0);" class="cur">退款列表</a><a href="<?php echo urlMobile('member_return');?>">退货列表</a></span>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

  	<?php include template('layout/toptip');?>





</header>



<div class="nctouch-main-layout">

  <div class="nctouch-order-list">

    <ul id="refund-list">

    </ul>

  </div>

</div>

<div class="fix-block-r">

	<a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>

</div>

<script type="text/html" id="refund-list-tmpl">

<% if (refund_list.length > 0){%>

	<% for(var i = 0;i<refund_list.length;i++){

	%>

		<li class=" <%if(i>0){%>mt10<%}%>">

			<div class="nctouch-order-item">

				<div class="nctouch-order-item-head">

					<a href="<%=WapSiteUrl%>/index.php?con=member_refund&fun=member_refund_info&refund_id=<%=refund_list[i].refund_id%>" class="store"><i class="icon"></i><%=refund_list[i].store_name%></a><span class="state"><%=refund_list[i].seller_state%></span>

				</div>

				<div class="nctouch-order-item-con">

				<% for(var j = 0;j<refund_list[i].goods_list.length;j++){

					var goods_list = refund_list[i].goods_list[j];

				%>

					<div class="goods-block">

					<a href="<%=WapSiteUrl%>/index.php?con=member_refund&fun=member_refund_info&refund_id=<%=refund_list[i].refund_id%>">

						<div class="goods-pic">

							<img src="<%=goods_list.goods_img_360%>"/>

						</div>

						<dl class="goods-info" style="margin-right: auto;">

							<dt class="goods-name"><%=goods_list.goods_name%></dt>

							<dd class="goods-type"><%=goods_list.goods_spec%></dd>

						</dl>

					</a>

					</div>

				<%}%>

				</div>

				<div class="nctouch-order-item-footer">

					<div class="store-totle">

					<time class="refund-time"><%=refund_list[i].add_time%></time>

					<span class="refund-sum">退款金额：<em>￥<%=refund_list[i].refund_amount%></em></span>

					</div>

					<div class="handle">

						<a href="<%=WapSiteUrl%>/index.php?con=member_refund&fun=member_refund_info&refund_id=<%=refund_list[i].refund_id%>" class="btn evaluation-again-order">退款详情</a>

					</div>

				</div>

			</div>

		</li>

	<%}%>

	<% if (hasmore) {%>

	<li class="loading"><div class="spinner"><i></i></div>订单数据读取中...</li>

	<% } %>

<%}else {%>

	<div class="nctouch-norecord refund">

		<div class="norecord-ico"><i></i></div>

		<dl>

			<dt>您还没有退款信息</dt>

			<dd>已购订单详情可申请退款</dd>

		</dl>

	</div>

<%}%>

</script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/ncscroll-load.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/member_refund.js"></script>

