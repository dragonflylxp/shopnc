<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_store.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1);"> <i class="back"></i> </a> </div>

    <div class="header-inp"> <i class="icon"></i>

      <input type="text" class="search-input" id="search_keyword" placeholder="请输入搜索关键词" maxlength="50" autocomplete="on" autofocus>

    </div>

    <div class="header-r"><a id="search_btn" href="javascript:void(0);" class="search-btn">搜索</a></div>

  </div>

</header>

<div class="nctouch-main-layout fixed-Width">

  <div class="nctouch-main-layout">

    <div class="categroy-cnt">

      <div class="categroy-all"><a id="goods_search_all" href="javascript:void(0);">全部商品<i class="arrow-r"></i></a></div>

      <ul class="categroy-list" id="store_category">

      </ul>

    </div>

  </div>

</div>

<div class="fix-block-r">

  <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>

</div>

</body>

<script type="text/html" id="store_category_tpl">

	<% for (var i in store_goods_class) { var gc = store_goods_class[i]; %>

		<% if (gc.level == 1) { %>

		<li class="category-frist">

			<a class="level<%= gc.level %>" href="<%=ApiUrl%>index.php?con=store&fun=store_goods&store_id=<%= store_info.store_id %>&stc_id=<%= gc.id %>"><%= gc.name %><span>查看全部</sapn></a>

		</li>

		<% } else { %>

		<li class="category-seciond" >

			<a href="<%=ApiUrl%>index.php?con=store&fun=store_goods&store_id=<%= store_info.store_id %>&stc_id=<%= gc.id %>"><%= gc.name %></a>

		</li>

		<% } %>

	<% } %>

</script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/touch.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/store_search.js"></script>



