<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">



</head>

<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>订单详情</h1>

    </div>

	<div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

   </div>

   <?php include template('layout/toptip');?>





</header>

<div class="nctouch-main-layout mb20">



  <div class="nctouch-order-list" id="order-info-container">

  	<div id="loding"></div>

    <ul>

    </ul>

  </div>

</div>

<script type="text/html" id="order-info-tmpl">

	<div class="nctouch-oredr-detail-block">

		<h3><i class="orders"></i>交易状态</h3>

		<div class="order-state"><%=point_orderstatetext%></div>

		<%if (order_tips != ''){%><div class="info"><%=order_tips%></div><%}%>

	</div>

	<div class="nctouch-oredr-detail-delivery">

		<a href="<%=ApiUrl%>/index.php?con=member_pointorder&fun=deliver&order_id=<%=point_orderid%>">

			<span class="time-line">

				<i></i>

			</span>

			<div class="info">

				<p id="delivery_content"></p>

				<time id="delivery_time"></time>

			</div>

			<span class="arrow-r"></span>

		</a>

	</div>



	<div class="nctouch-oredr-detail-block mt5">

		<div class="nctouch-oredr-detail-add">

			<i class="icon-add"></i>

			<dl>

        		<dt>收货人：<span><%=reciver_name%></span><span><%=reciver_phone%></span></dt>

				<dd>收货地址：<%=reciver_addr%></dd>

			</dl>

		</div>

	</div>

	<%if (point_ordermessage != ''){%>

	<div class="nctouch-oredr-detail-block">

		<h3><i class="msg"></i>买家留言</h3>

		<div class="info"><%=point_ordermessage%></div>

	</div>

	<%}%>

	

	<div class="nctouch-order-item mt5">

		<div class="nctouch-order-item-head">

	

			<a class="store"><i class="icon"></i><%=point_ordersn%></a>

			

		</div>

		<div class="nctouch-order-item-con">

			<%for(i=0; i<prod_list.length; i++){%>

			<div class="goods-block detail">

				<a href="<%=ApiUrl%>/index.php?con=points&fun=detail&pgoods_id=<%=prod_list[i].point_goodsid%>">

				<div class="goods-pic">

					<img src="<%=prod_list[i].point_goodsimage_small%>">

				</div>

				<dl class="goods-info">

					<dt class="goods-name"><%=prod_list[i].point_goodsname%></dt>

				</dl>

				<div class="goods-subtotal">

					<span class="goods-price"><em><%=prod_list[i].point_goodspoints%>积分</em></span>

					<span class="goods-num">x<%=prod_list[i].point_goodsnum%></span>

				</div>

			

			</a>

			</div>

			<%}%>

			

			

			<div class="goods-subtotle">

				

				<dl class="t">

					<dt>兑换单所需:</dt>

					<dd><em><%=point_allpoint%></em>积分</dd>

				</dl>

			</div>

		</div>

		<!-- <div class="nctouch-order-item-bottom">

			<span><a href="http://wpa.qq.com/msgrd?v=3&uin=<%=store_qq%>&site=qq&menu=yes"><i class="im"></i>联系客服</a></span>

			<span><a tel="<%=store_phone%>"><i class="tel"></i>拨打电话</a></span>

		</div> -->

	</div>

	<div class="nctouch-oredr-detail-block mt5">

		<ul class="order-log">

			<li>订单编号：<%=point_ordersn%></li>

			<li>创建时间：<%=point_addtime%></li>

			<% if(point_shippingtime){%>

			<li>发货时间：<%=point_shippingtime%></li>

			<%}%>

			

			<% if(point_finnshedtime){%>

			<li>完成时间：<%=point_finnshedtime%></li>

			<%}%>

		</ul>

	</div>

	<div class="nctouch-oredr-detail-bottom">

	

	<% if (point_orderallowcancel) {%>

	<a href="javascript:void(0)" order_id="<%=point_orderid%>" class="btn cancel-order">取消兑换</a>

	<% } %>



	<% if (point_orderallowreceiving) { %>

	<a href="javascript:void(0)" order_id="<%=point_orderid%>" class="btn viewdelivery-order">查看物流</a>

	<%}%>

	<% if (point_orderallowreceiving){ %>

	<a href="javascript:void(0)" order_id="<%=point_orderid%>" class="btn key sure-order">确认收货</a>

	<% } %>



	</div>

</script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/points_order_detail.js"></script>

