<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_cart.css">

<style type="text/css">

.w20h li{

	width: 17%;

}

</style>

</head>

<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

   <div class="header-title">

      <h1>实物交易订单</h1>

    </div>

   <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

   </div>

       <?php include template('layout/seller_toptip');?>





</header>

<div class="nctouch-main-layout">

  <!-- <div class="nctouch-order-search">

    <form>

      <span><input type="text" autocomplete="on" maxlength="50" placeholder="订单号进行搜索" name="order_key" id="order_key" oninput="writeClear($(this));" >

      <span class="input-del"></span></span>

      <input type="button" id="search_btn" value="&nbsp;">

    </form>

  </div> -->

  <div id="fixed_nav" class="nctouch-single-nav">

    <ul id="filtrate_ul" class="w20h">

      <li class="selected"><a href="javascript:void(0);" data-state="">全部</a></li>

      <li><a href="javascript:void(0);" data-state="state_new">待付款</a></li>

      <li><a href="javascript:void(0);" data-state="state_pay">待发货</a></li>

      <li><a href="javascript:void(0);" data-state="state_send">已发货</a></li>

      <li><a href="javascript:void(0);" data-state="state_success">已完成</a></li>

      <li><a href="javascript:void(0);" data-state="state_cancel">已取消</a></li>

    </ul>

  </div>

  <div class="nctouch-order-list">

  	<div id="loding"></div>

    <ul id="order-list" >

    </ul>

  </div>



</div>

<div class="fix-block-r">

	<a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>

</div>

<script type="text/html" id="order-list-tmpl">

<% var order_group_list = datas.order_group_list; %>

<% if (order_group_list.length > 0){%>

	<% for(var i = 0;i<order_group_list.length;i++){

		var orderlist = order_group_list[i].order_list;

	%>

		<li class="<%if(order_group_list[i].pay_amount){%>green-order-skin<%}else{%>gray-order-skin<%}%> <%if(i>0){%>mt10<%}%>">

			<% for(var j = 0;j<orderlist.length;j++){

				var order_goods = orderlist[j].extend_order_goods;

			%>

				<div class="nctouch-order-item">

					<div class="nctouch-order-item-head">

					

							<a class="store" href="<%=ApiUrl%>/index.php?con=store&store_id=<%=orderlist[j].store_id%>"><i class="icon"></i><%=orderlist[j].order_sn%></a>

					

						<span class="state">

							<%

								var stateClass ="ot-finish";

								var orderstate = orderlist[j].order_state;

								if(orderstate == 20 || orderstate == 30 || orderstate == 40){

									stateClass = stateClass;

								}else if(orderstate == 0) {

									stateClass = "ot-cancel";

								}else {

									stateClass = "ot-nofinish";

								}

							%>

							<span class="<%=stateClass%>"><%=orderlist[j].state_desc%></span>

						</span>

					</div>

					<div class="nctouch-order-item-con">

						<%

							var count = 0;

							 for (k in order_goods){

								count += parseInt(order_goods[k].goods_num);

						%>

						<div class="goods-block">

						<a href="<%=ApiUrl%>/index.php?con=seller_order&fun=order_detail&order_id=<%=orderlist[j].order_id%>">

							<div class="goods-pic">

								<img src="<%=order_goods[k].goods_image_url%>"/>

							</div>

							<dl class="goods-info">

								<dt class="goods-name"><%=order_goods[k].goods_name%></dt>

								<dd class="goods-type"><%=order_goods[k].goods_spec%></dd>

							</dl>

							<div class="goods-subtotal">

								<span class="goods-price">￥<em><%=order_goods[k].goods_price%></em></span>

								<span class="goods-num">x<%=order_goods[k].goods_num%></span>

							</div>

						</a>

						</div>

						<%}%>

						<%if (orderlist[j].zengpin_list.length > 0){%>

						<div class="goods-gift">

							<span><em>赠品</em>

								<%

									var num = 1;

									for (k in orderlist[j].zengpin_list){%>

										<%=num;%>、<%=orderlist[j].zengpin_list[k].goods_name;%><br>

								<% ++num;}%>

							</span>

						</div>

						<%}%>

					</div>

					<div class="nctouch-order-item-footer">

						<div class="store-totle">

							<span>共<em><%=count%></em>件商品，合计</span><span class="sum">￥<em><%=orderlist[j].order_amount%></em></span><span class="freight">(含运费￥<%=orderlist[j].shipping_fee%>)</span>

						</div>    

						<div class="handle">

						

							<%if(orderlist[j].if_lock){%>

							<p>退款/退货中...</p>

							<%}%>

							<%if(orderlist[j].if_cancel){%>

							<a href="javascript:void(0)" order_id="<%=orderlist[j].order_id%>" class="btn cancel-order" order_sn="<%=orderlist[j].order_sn%>">取消订单</a>

							<%}%>

							<%if(orderlist[j].if_spay_price){%>

							<a href="javascript:void(0)" order_id="<%=orderlist[j].order_id%>" class="btn spay_price-order" goods_price="<%=orderlist[j].order_amount%>" mai_name="<%=orderlist[j].buyer_name%>" order_sn="<%=orderlist[j].order_sn%>">修改价格</a>

							<%}%>

							<%if(orderlist[j].if_store_send){%>

							<a href="javascript:void(0)" order_id="<%=orderlist[j].order_id%>" class="btn key store_send-order">发货</a>

							<%}%>

							<%if(orderlist[j].if_deliver){%>

							<a href="javascript:void(0)" order_id="<%=orderlist[j].order_id%>" class="btn viewdelivery-order">查看物流</a>

							<%}%>

						

					
							

						</div>

					</div>

				</div>

			<%}%>

			

		</li>

	<%}%>

	<% if (hasmore) {%>

	<li class="loading"><div class="spinner"><i></i></div>订单数据读取中...</li>

	<% } %>

<%}else {%>

	<div class="nctouch-norecord order">

		<div class="norecord-ico"><i></i></div>

		<dl>

			<dt>您还没有相关的订单</dt>

			<dd>可以去看看其他统计</dd>

		</dl>

		

	</div>

<%}%>

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

	<dl>

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
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.waypoints.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/seller_order_list.js"></script>

