<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_products_list.css">

</head>

<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-tab"><a href="javascript:void(0);" class="cur">商品收藏</a><a href="<?php echo urlMobile('member_favorites_store');?>">店铺收藏</a></div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

   <?php include template('layout/toptip');?>

</header>

<div class="nctouch-main-layout">

  <div class="grid">

	  <ul class="goods-secrch-list fav-list" id="favorites_list"></ul>

  </div>

</div>

<div class="fix-block-r">

	<a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>

</div>

<script type="text/html" id="sfavorites_list">

     <%if(favorites_list.length>0){%>

	 	<% for (var k in favorites_list) { var v = favorites_list[k]; %>

		<li class="goods-item" id="favitem_<%=v.fav_id %>">

			<a href="<%=ApiUrl%>/index.php?con=goods&fun=detail&goods_id=<%=v.fav_id %>">

				<span class="goods-pic"><img src="<%=v.goods_image_url %>"/></span>

				<dl class="goods-info"><dt class="goods-name"><h4><%=v.goods_name %></h4></dt>

				</dl>

				<dd class="goods-sale">

					<span class="goods-price">￥<em><%=v.goods_price %></em></span>

				</dd>

			</a>

			<a href="javascript:void(0);" nc_type="fav_del" data_id="<%=v.fav_id %>" class="fav-del"></a>

		</li>

		<%}%>

		<li class="loading"><div class="spinner"><i></i></div>数据读取中</li>

	 <%}else{%>

	 <div class="nctouch-norecord favorite-goods">

		 <div class="norecord-ico"><i></i></div>

		 <dl>

			 <dt>您还没有关注任何商品</dt>

			 <dd>可以去看看哪些商品值得收藏</dd>

		 </dl>

		 <a href="<%=ApiUrl%>" class="btn">随便逛逛</a>

	 </div>

	 <%}%>

</script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/ncscroll-load.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/favorites.js"></script>

