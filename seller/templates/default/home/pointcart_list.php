

<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_cart.css">

<style type="text/css">

	footer{

		display: none;

	}

</style>

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

			<h1>已选择兑换礼品   </h1>

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

	<% if(cartgoods_list.length >0){%>

			<div class="nctouch-cart-container">

				

				<ul class="nctouch-cart-item">

				

					<% for (var i = 0;i< cartgoods_list.length;i++){var goods = cartgoods_list[i];%>

				

					<li cart_id="<%=goods.pcart_id%>" class="cart-litemw-cnt" >

						<div class="goods-check">

							<input type="checkbox" checked name="pcart_id" value="<%=goods.pcart_id%>" />

						</div>

						<div class="goods-pic">

							<a href="<%=ApiUrl%>/index.php?con=goods&fun=detail&goods_id=<%=goods.goods_id%>">

								<img src="<%=goods.pgoods_image_small%>"/>

							</a>

						</div>

						<dl class="goods-info">

							<dt class="goods-name"> <a href="<%=ApiUrl%>/index.php?con=points&fun=detail&pgoods_id=<%=goods.pgoods_id%>"> <%=goods.pgoods_name%> </a></dt>

							

						</dl>

						<div class="goods-del" cart_id="<%=goods.pcart_id%>"><a href="javascript:void(0);"></a></div>

						<div class="goods-subtotal"> <span class="goods-price"><em><%=goods.pgoods_points%></em>积分</span> 

							

							<div class="value-box">

								<span class="minus">

									<a href="javascript:void(0);">&nbsp;</a>

								</span>

        						<span>

									<input type="text" pattern="[0-9]*" readonly class="buy-num buynum" value="<%=goods.pgoods_choosenum%>"/>

								</span>

								<span class="add">

									<a href="javascript:void(0);">&nbsp;</a>

								</span>

							</div>

						</div>

				

					</li>

					<% } %>

		

				</ul>

				</div> 

         		<% if (check_out === true) {%>

                    <div class="nctouch-cart-bottom">

						<div class="all-check"><input class="all_checkbox" type="checkbox" checked></div>

						<div class="total">

                        	<dl class="total-money"><dt>合计总金额：</dt><dd><em><%=cartgoods_pointall%></em>积分</dd></dl>

                    	</div>

                    	<div class="check-out ok">

                        	<a href="javascript:void(0)">兑换</a>

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

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/point_cart_list.js"></script>

