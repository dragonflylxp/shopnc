<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_cart.css">

<style type="text/css">

.nctouch-order-item-head{

	font-size: 0.65rem;

}

.w20h li{

	width: 20%;

}

</style>

</head>

<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

    <span class="header-tab"><a href="<?php echo urlMobile('member_points');?>" >积分明细</a><a href="javascript:void(0);" class="cur" >兑换记录</a></span>

   <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

   </div>

       <?php include template('layout/toptip');?>





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

      <li><a href="javascript:void(0);" data-state="20">待发货</a></li>

      <li><a href="javascript:void(0);" data-state="30">待收货</a></li>

      <li><a href="javascript:void(0);" data-state="40">已完成</a></li>

      <!-- <li><a href="javascript:void(0);" data-state="state_notakes">待自提</a></li> -->

      <li><a href="javascript:void(0);" data-state="2">已取消</a></li>

    </ul>

  </div>

  <div class="nctouch-order-list">

  	<div id="loding"></div>

    <ul id="order-list">

    </ul>

  </div>



</div>

<div class="fix-block-r">

	<a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>

</div>

<script type="text/html" id="order-list-tmpl">

<% var order_group_list = datas.order_group_list; %>

<% if (order_group_list.length > 0){%>

	<% for(var i = 0;i< order_group_list.length;i++){

		var orderlist = order_group_list[i].prodlist;

	%>

		<li class="gray-order-skin">

			

				<div class="nctouch-order-item">

					<div class="nctouch-order-item-head">

						<%=order_group_list[i].point_ordersn%>

					

						<span class="state">

							

							<span class="<%=stateClass%>"><%=order_group_list[i].point_orderstatetext%></span>

						</span>

					</div>

					<div class="nctouch-order-item-con">

						<% var count = 0;for(var j = 0;j< orderlist.length;j++){count += parseInt(orderlist[j].point_goodsnum);%>



						<div class="goods-block">

						<a href="<%=ApiUrl%>/index.php?con=member_pointorder&fun=order_info&order_id=<%=orderlist[j].point_orderid%>">

							<div class="goods-pic">

								<img src="<%=orderlist[j].point_goodsimage_small%>"/>

							</div>

							<dl class="goods-info">

								<dt class="goods-name"><%=orderlist[j].point_goodsname%></dt>

								

							</dl>

							<div class="goods-subtotal">

								<span class="goods-price"><em><%=orderlist[j].point_goodspoints%></em>积分</span>

								<span class="goods-num">x<%=orderlist[j].point_goodsnum%></span>

							</div>

						</a>

						</div>

						<%}%>

					

					</div>

					<div class="nctouch-order-item-footer">

						<div class="store-totle">

							<span>共<em><%=count%></em>件商品，合计</span><span class="sum"><em><%=order_group_list[i].point_allpoint%></em>积分</span>

						</div>    

						<div class="handle">

							<%if(order_group_list[i].if_delete){%>

							<a href="javascript:void(0)" order_id="<%=order_group_list[i].point_orderid%>" class="del delete-order"><i></i>移除</a>

							<%}%>

					

							<%if(order_group_list[i].point_orderallowcancel){%>

							<a href="javascript:void(0)" order_id="<%=order_group_list[i].point_orderid%>" class="btn cancel-order">取消兑换</a>

							<%}%>

							<%if(order_group_list[i].point_orderallowreceiving){%>

							<a href="javascript:void(0)" order_id="<%=order_group_list[i].point_orderid%>" class="btn viewdelivery-order">查看物流</a>

							<%}%>

							<%if(order_group_list[i].point_orderallowreceiving){%>

							<a href="javascript:void(0)" order_id="<%=order_group_list[i].point_orderid%>" class="btn key sure-order">确认收货</a>

							<%}%>

						

						</div>

					</div>

				</div>

		

		

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

			<dd>可以去看看哪些想要买的</dd>

		</dl>

		<a href="<%=ApiUrl%>" class="btn">随便逛逛</a>

	</div>

<%}%>

</script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.waypoints.js"></script> 



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/points_order_list.js"></script>

