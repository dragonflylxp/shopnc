<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_cart.css">



</head>

<body>

<header id="header" class="fixed">

	<div class="header-wrap">

		<div class="header-l">

			<a href="javascript:history.go(-1)">

				<i class="back"></i>

			</a>

		</div>

		<div class="header-title">

			<h1>购物车</h1>

		</div>

		<div class="header-r">

			<a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a>

		</div>

	</div>

	 <?php include template('layout/toptip');?>

</header>

<div class="nctouch-main-layout">

  <div id="cart-list-wp"></div>

</div>

<div class="pre-loading hide">

  <div class="pre-block">

    <div class="spinner"><i></i></div>购物车数据读取中...

  </div>

</div>

<script id="cart-list" type="text/html">

	<% if(cart_list.length >0){%>

		<% for (var i = 0;i<cart_list.length;i++){%>

			<div class="nctouch-cart-container">

				<dl class="nctouch-cart-store">

					<dt><span class="store-check">

							<input class="store_checkbox" type="checkbox" checked>

						</span>

						<i class="icon-store"></i>

						<%=cart_list[i].store_name%>

						<% if (cart_list[i].voucher) { %>

						<span class="handle">

							<a href="javascript:void(0);" class="voucher animation-up"><i></i>领券</a>

						</span>

						<% } %>

					</dt>

					<% if (cart_list[i].free_freight) { %>

					<dd class="store-activity">

						<em>免运费</em>

						<span><%=cart_list[i].free_freight%></span>

					</dd>

					<% } %>

					<% if (cart_list[i].mansong) { %>

					<dd class="store-activity">

      					<em>满即送</em>

							<% for (var j=0; j<cart_list[i].mansong.length; j++) { var mansong = cart_list[i].mansong[j]%>

								<span><%=mansong.desc%><%if(!isEmpty(mansong.url)){%><img src="<%=mansong.url%>" /><%}%></span>

							<% } %>

						<i class="arrow-down"></i>

					</dd>

					<% } %>

				</dl>

				<ul class="nctouch-cart-item">

				<% if (cart_list[i].goods) { %>

					<% for (var j=0; j<cart_list[i].goods.length; j++) {var goods = cart_list[i].goods[j];%>

					<li cart_id="<%=goods.cart_id%>" class="cart-litemw-cnt" >

						<div class="goods-check">

							<input type="checkbox" checked name="cart_id" value="<%=goods.cart_id%>" />

						</div>

						<div class="goods-pic">

							<a href="<%=ApiUrl%>/index.php?con=goods&fun=detail&goods_id=<%=goods.goods_id%>">

								<img src="<%=goods.goods_image_url%>"/>

							</a>

						</div>

						<dl class="goods-info">

							<dt class="goods-name"> <a href="<%=ApiUrl%>/index.php?con=goods&fun=detail&goods_id=<%=goods.goods_id%>"> <%=goods.goods_name%> </a></dt>

							<dd class="goods-type">

								<% if (goods.goods_type && goods.goods_type.length > 0) { %>

									<% for (var k=0; k<goods.goods_type.length; k++) { var gt = goods.goods_type[k]%>

										<b><%=gt.type%></b>:<%=gt.name%>

									<% } %>

								<% } %>

							</dd>

						</dl>

						<div class="goods-del" cart_id="<%=goods.cart_id%>"><a href="javascript:void(0);"></a></div>

						<div class="goods-subtotal"> <span class="goods-price">￥<em><%=goods.goods_price%></em></span> 

							<span class="goods-sale">

							<% if (!isEmpty(goods.groupbuy_info)) 

								{%><em>特卖</em><% } 

							else if (!isEmpty(goods.xianshi_info)) 

								{ %><em>限时折扣</em><% } 

							else if (!isEmpty(goods.sole_info)) 

								{ %><em><i></i>手机专享￥<%=goods.sole_info.sole_price%></em><% } %>

							</span>

							<div class="value-box">

								<span class="minus">

									<a href="javascript:void(0);">&nbsp;</a>

								</span>

        						<span>

									<input type="text" pattern="[0-9]*" readonly class="buy-num buynum" value="<%=goods.goods_num%>"/>

								</span>

								<span class="add">

									<a href="javascript:void(0);">&nbsp;</a>

								</span>

							</div>

						</div>

						<% if (goods.gift_list && goods.gift_list.length > 0) { %>

							<div class="goods-gift">

							<% for (var k=0; k<goods.gift_list.length; k++) { var gift = goods.gift_list[k]%>

								<span><em>赠品</em><%=gift.gift_goodsname%>x<%=gift.gift_amount%></span>

							<% } %>

							</div>

						<% } %>

					</li>

					<% } %>

				<% } %>

				</ul>

						<% if (cart_list[i].voucher) { %>

						<div class="nctouch-bottom-mask">

							<div class="nctouch-bottom-mask-bg"></div>

							<div class="nctouch-bottom-mask-block">

								<div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>

								<div class="nctouch-bottom-mask-top store-voucher">

									<i class="icon-store"></i>

									<%=cart_list[i].store_name%>&nbsp;&nbsp;领取店铺代金券

									<a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a>

								</div>

								<div class="nctouch-bottom-mask-rolling">

									<div class="nctouch-bottom-mask-con">

										<ul class="nctouch-voucher-list">

										<% for (var j=0; j<cart_list[i].voucher.length; j++) { 

										var voucher = cart_list[i].voucher[j];%>

										<li>

											<dl>

												<dt class="money">面额<em><%=voucher.voucher_t_price%></em>元</dt>

												<dd class="need">需消费<%=voucher.voucher_t_limit%>使用</dd>

												<dd class="time">至<%=$getLocalTime(voucher.voucher_t_end_date)%>前使用</dd>

											</dl>

											<a href="javascript:void(0);" class="btn" data-tid=<%=voucher.voucher_t_id%>>领取</a>

										</li>

										<% } %>

										</ul>

									</div>

								</div>

							</div>

						</div>

						<% } %>

					</div> 

                <%}%>

				<% if (check_out === true) {%>

                    <div class="nctouch-cart-bottom">

						<div class="all-check"><input class="all_checkbox" type="checkbox" checked></div>

						<div class="total">

                        	<dl class="total-money"><dt>合计总金额：</dt><dd>￥<em><%=sum%></em></dd></dl>

                    	</div>

                    	<div class="check-out ok">

                        	<a href="javascript:void(0)">确认信息</a>

						</div>

                    </div>

				<% } else { %>

					<div class="nctouch-cart-bottom no-login">

						<div class="cart-nologin-tip">结算购物车中的商品，需先登录商城</div>

						<div class="cart-nologin-btn"><a href="<?php echo urlMobile('login');?>" class="btn">登录</a>

							<a href="<?php echo urlMobile('register');?>" class="btn">注册</a>

						</div>

					</div>

				<% } %>

            <%}else{%>

            <div class="nctouch-norecord cart">

				<div class="norecord-ico"><i></i></div>

				<dl>

					<dt>您的购物车还是空的</dt>

					<dd>去挑一些中意的商品吧</dd>

				</dl>

				<a href="<%=WapSiteUrl%>" class="btn">随便逛逛</a>

			</div>

            <%}%>

            </script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/cart-list.js"></script>

