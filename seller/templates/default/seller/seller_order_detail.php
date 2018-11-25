<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<style type="text/css">

	footer{

		display: none;

	}

</style>

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

   <?php include template('layout/seller_toptip');?>





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

		<div class="order-state"><%=order_state_tip%></div>

		<%if (order_tips != ''){%><div class="info"><%=order_tips%></div><%}%>

	</div>

	<%if(if_deliver){%>

	<div class="nctouch-oredr-detail-delivery">

		<a href="<%=ApiUrl%>/index.php?con=member_order&fun=deliver&order_id=<%=order_id%>">

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

	<%}%>

	<div class="nctouch-oredr-detail-block mt5">

		<div class="nctouch-oredr-detail-add">

			<i class="icon-add"></i>

			<dl>

        		<dt>收货人：<span><%=reciver_name%></span><span><%=reciver_phone%></span></dt>

				<dd>收货地址：<%=reciver_addr%></dd>

			</dl>

		</div>

	</div>

	<%if (order_message != ''){%>

	<div class="nctouch-oredr-detail-block">

		<h3><i class="msg"></i>买家留言</h3>

		<div class="info"><%=order_message%></div>

	</div>

	<%}%>

	<%if (invoice != ''){%>

	<div class="nctouch-oredr-detail-block">

		<h3><i class="invoice"></i>发票信息</h3>

		<div class="info"><%=invoice%></div>

	</div>

	<%}%>

	<%if (payment_name != ''){%>

	<div class="nctouch-oredr-detail-block">

		<h3><i class="pay"></i>付款方式</h3>

		<div class="info"><%=payment_name%></div>

	</div>

	<%}%>

	<div class="nctouch-order-item mt5">

		<div class="nctouch-order-item-head">

			<%if (ownshop){%>

			<a class="store"><i class="icon"></i><%=store_name%></a>

			<%}else{%>

				<a href="<%=ApiUrl%>/index.php?con=store&store_id=<%=store_id%>" class="store"><i class="icon"></i><%=store_name%><i class="arrow-r"></i></a>

			<%}%>

		</div>

		<div class="nctouch-order-item-con">

			<%for(i=0; i<goods_list.length; i++){%>

			<div class="goods-block detail">

				<a href="<%=ApiUrl%>/index.php?con=goods&fun=detail&goods_id=<%=goods_list[i].goods_id%>">

				<div class="goods-pic">

					<img src="<%=goods_list[i].image_url%>">

				</div>

				<dl class="goods-info">

					<dt class="goods-name"><%=goods_list[i].goods_name%></dt>

					<dd class="goods-type"><%=goods_list[i].goods_spec%></dd>

				</dl>

				<div class="goods-subtotal">

					<span class="goods-price">￥<em><%=goods_list[i].goods_price%></em></span>

					<span class="goods-num">x<%=goods_list[i].goods_num%></span>

				</div>

			

			</a>

			</div>

			<%}%>

			<% if (zengpin_list.length > 0){%>

				<div class="goods-gift">

				<%for(i=0; i<zengpin_list.length; i++){%>

					<span><em>赠品</em><%=zengpin_list[i].goods_name%> x <%=zengpin_list[i].goods_num%></span>

				<%}%>

				</div>

			<%}%>

			

			<div class="goods-subtotle">

				<%if (promotion.length > 0){%>

				<dl>

					<dt>优惠</dt>

					<dd><%for (var ii in promotion){%><span><%=promotion[ii][1]%></span><%}%></dd>

				</dl>

				<%}%>

				<dl>

					<dt>运费</dt>

					<dd>￥<em><%=shipping_fee%></em></dd>

				</dl>

				<dl class="t">

					<dt>实付款（含运费）</dt>

					<dd>￥<em><%=real_pay_amount%></em></dd>

				</dl>

			</div>

		</div>

		<div class="nctouch-order-item-bottom">

			<span></span>

			<span><a tel="<%=buyer_phone%>"><i class="tel"></i>联系用户</a></span>

		</div>

	</div>

	<div class="nctouch-oredr-detail-block mt5">

		<ul class="order-log">

			<li>订单编号：<%=order_sn%></li>

			<li>创建时间：<%=add_time%></li>

			<% if(payment_time){%>

			<li>付款时间：<%=payment_time%></li>

			<%}%>

			<% if(shipping_time){%>

			<li>发货时间：<%=shipping_time%></li>

			<%}%>

			<% if(finnshed_time){%>

			<li>完成时间：<%=finnshed_time%></li>

			<%}%>

		</ul>

	</div>

	<div class="nctouch-oredr-detail-bottom">

	<% if (if_lock) {%>

	<p>退款/退货中...</p>

	<% } %>

	<% if (if_buyer_cancel) {%>

	<a href="javascript:void(0)" order_id="<%=order_id%>" order_sn="<%=order_sn%>" class="btn cancel-order">取消订单</a>

	<% } %>

	<%if(if_spay_price){%>

	<a href="javascript:void(0)" order_id="<%=order_id%>" class="btn spay_price-order" goods_price="<%=order_amount%>" mai_name="<%=buyer_name%>" order_sn="<%=order_sn%>">修改价格</a>

	<%}%>

	<% if (if_deliver) { %>

	<a href="javascript:void(0)" order_id="<%=order_id%>" class="btn viewdelivery-order">查看物流</a>

	<%}%>

	<% if (if_store_send){ %>

	<a href="javascript:void(0)" order_id="<%=order_id%>" class="btn key sure-order">设置发货</a>

	<% } %>

	</div>

</script>

<!-- 修改价格 -->

<div class="alert_box_hide">

<div class="alert_box">

	 <input type="hidden" class="text" id="order_id" name="order_id" value=""/>

	 <dl>

      <dt>买&nbsp;家：</dt>

      <dd class="mai_name"></dd>

    </dl>

    <dl>

      <dt>订单号：</dt>

      <dd><span class="num"></span></dd>

    </dl>

    <dl>

      <dt>订单金额：</dt>

      <dd>

        <input type="text" class="text" id="goods_amount" name="goods_amount" />

      </dd>

    </dl>

</div>

</div>



<div class="alert_box_two hide">

	<div class="alert_box">

	 <input type="hidden" class="text" id="order_id" name="order_id" value=""/>

	<dl class="order_sn">

      <dt>订单编号：</dt>

      <dd><span class="num"></span></dd>

    </dl>

    <dl>

      <dt>取消缘由：</dt>

      <dd>

        <ul class="checked">

          <li>

            <input checked="" name="state_info" id="d1" value="无法备齐货物" type="radio" onclick="select_input(this)">

            <label for="d1">无法备齐货物</label>

          </li>

          <li>

            <input name="state_info" id="d2" value="不是有效的订单" type="radio" onclick="select_input(this)">

            <label for="d2">不是有效的订单</label>

          </li>

          <li>

            <input name="state_info" id="d3" value="买家主动要求" type="radio" onclick="select_input(this)">

            <label for="d3">买家主动要求</label>

          </li>

          <li>

            <input name="state_info" flag="other_reason" id="d4" value="" type="radio" onclick="select_input(this)">

            <label for="d4">其他原因</label>

          </li>

          <li id="other_reason" style="height: 48px; display: none;">

            <textarea name="state_info1" rows="2" id="other_reason_input" style="width:150px;border:1px solid #686868"></textarea>

          </li>

        </ul>

      </dd>

    </dl>

    

</div>

</div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/seller_order_detail.js"></script>

