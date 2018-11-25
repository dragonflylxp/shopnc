<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">



</head>

<body>

<header id="header">

	<div class="header-wrap">

		<div class="header-l">

			<a href="javascript:history.go(-1)">

				<i class="back"></i>

			</a>

		</div>

		<div class="header-inp">

			<i class="icon"></i>

			<input type="text" class="search-input" value="" oninput="writeClear($(this));" id="keyword" placeholder="请输入搜索关键词" maxlength="50" autocomplete="on" autofocus>

			<span class="input-del"></span>

		</div>

		<div class="header-r">

			<a id="header-nav" href="javascript:void(0);" class="search-btn">搜索</a>

		</div>

	</div>

</header>



<!-- 全文搜索提示 begin -->

<div class="nctouch-main-layout mb-20" id="search_tip_list_container" style="display:none"></div>

<script type="text/html" id="search_tip_list_script">

<ul class="nctouch-default-list">

<%for(i = 0; i < list.length; i++){%>

	<li><a href="<%=$buildUrl('keyword',list[i])%>"><%=list[i]%></a></li>

<%}%>

</ul>

</script>

<!-- 全文搜索提示 end -->



<div id="store-wrapper">

  <div class="nctouch-search-layout">

    <dl class="hot-keyword">

      <dt>热门搜索</dt>

      <dd id="hot_list_container"></dd>

    </dl>

    <dl>

      <dt>历史纪录</dt>

      <dd id="search_his_list_container"></dd>

    </dl>

  </div>

</div>

<script type="text/html" id="hot_list">

<ul>

<%for(i = 0; i < list.length; i++){%>

	<li><a href="<%=$buildUrl('keyword',list[i])%>"><%=list[i]%></a></li>

<%}%>

</ul>

</script>

<script type="text/html" id="search_his_list">

<ul>

<%for(i = 0; i < his_list.length; i++){%>

	<li><a href="<%=$buildUrl('keyword',his_list[i])%>"><%=his_list[i]%></a></li>

<%}%>

</ul><a href="javascript:void(0);" class="clear-history" onclick="$(this).prev().remove();delCookie('hisSearch');">清空历史</a>

</script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/search.js"></script> 



