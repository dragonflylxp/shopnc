<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_products_list.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/points.css">

<style type="text/css">

.goods-search-list-nav ul{

    width: 100%;

 }   

</style>

</head>

<body>

<div class="pre-loading">

    <div class="pre-block">

      <div class="spinner"><i></i></div>

     数据读取中... 

    </div>

</div>

<header id="header" class="nctouch-product-header fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

    <div class="header-title">

      <h1>兑换礼品列表</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

    <?php include template('layout/toptip');?>

</header>

<?php require_once template('layout/fiexd');?>

<div class="goods-search-list-nav">

    <ul id="nav_ul">

      <li><a href="javascript:void(0);" class="current" onclick="init_get_list('1', '1')">积分值</a></li>

      <li><a href="javascript:void(0);" class="" onclick="init_get_list('2', '1')">上架时间</a></li>

      <li><a href="javascript:void(0);" id="search_adv">筛选<i></i></a></li>

    </ul>

    

  </div>



<div class="nctouch-main-layout mt40 mb20">

  <div class="panes vlist clear mt5">

     

           

                <ul id="duihuanList">

                    

                    

                </ul>

     

  </div>

</div>



<!--筛选部分-->

<div class="nctouch-full-mask hide">

  <div class="nctouch-full-mask-bg"></div>

  <div class="nctouch-full-mask-block">

    <div class="header">

      <div class="header-wrap">

        <div class="header-l"> <a href="javascript:void(0);"><i class="back"></i></a></div>

        <div class="header-title">

          <h1>礼品筛选</h1>

        </div>

        <div class="header-r"><a href="javascript:void(0);" id="reset" class="text">重置</a> </div>

      </div>

    </div>

    <div class="nctouch-main-layout-a secreen-layout" id="list-items-scroll" style="top: 2rem;"><div></div></div>

  </div>

</div>



<script type="text/html" id="search_items">

<div>

  <dl>

    <dt>关键字搜索</dt>

    <dd>

      <span class="inp-balck" style="padding: 0.25rem 2.5rem;"><input type="text" id="keyword"   class="inp" placeholder="关键字" /></span>

     </dd>

  </dl>

  <dl>

    <dt>所需积分</dt>

    <dd>

      <span class="inp-balck"><input type="text" id="points_min" nctype="price" pattern="[0-9]*" class="inp" placeholder="最低价"/></span>

      <span class="line"></span>

      <span class="inp-balck"><input nctype="price" type="text" id="points_max" pattern="[0-9]*" class="inp" placeholder="最高价"/></span>

    </dd>

  </dl>

  <dl>

    <dt>会员等级</dt>

    <dd id="click_level">

     

      <% for (i = 0; i < level_list.length; i++) { %>

           <a href="javascript:void(0);" nctype="level"  class="level" level="<%=level_list[i]['level']%>"><%=level_list[i]['level_name']%></a>

       <% } %>

 

         

      </span>

    </dd>

  </dl>

  <dl>

    <dt>只看我能兑换的</dt>

    <dd>

      <a href="javascript:void(0);" nctype="items" id="isable" class="">我能兑换的</a>

    

    </dd>

  </dl>

  

  

  <div class="bottom">

  <a href="javascript:void(0);" class="btn-l" id="search_submit">筛选商品</a>

  </div>

</div>

</script> 

<script type="text/html" id="home_body">

  <% var pointprod_list = datas.pointprod_list; %>

  <% if(pointprod_list.length >0){%>

  <%for(i=0;i<pointprod_list.length;i++){%>

  <li>

      <a href="<%=pointprod_list[i].url;%>">

          <img class="list_img" src="<%=pointprod_list[i].pgoods_image_small;%>">

          <div class="list_info">

              <div class="tit c666"><%=pointprod_list[i].pgoods_name;%></div>

              <div class="desc c999"><%=pointprod_list[i].pgoods_description;%></div>

              <div class="price">

                <span class="c999"><%=pointprod_list[i].pgoods_points;%>积分</span> 

                <del class="c999">￥<%=pointprod_list[i].pgoods_price;%></del> 

                <% if(pointprod_list[i].pgoods_storage==0){ %>

                  <i class="red2">已兑完</i>

                <% }else{ %>

                  <i class="red2">剩<%=pointprod_list[i].pgoods_storage;%></i>

                 <% }%>

              </div>

             

          </div>

      </a>

  </li>

<%}%>

      <% if (hasmore) {%>

      <li class="loading"><div class="spinner"><i></i></div>商品数据读取中...</li>

      <% }else{ %>

        <li class="loading">没有了...</li>



        

      <% } %>

  <%

     }else {

  %>

    <div class="nctouch-norecord search">

      <div class="norecord-ico"><i></i></div>

        <dl style="background:none;box-shadow:none;height:auto">

          <dt >没有找到任何相关信息</dt>

          <dd style="background-color:none;box-shadow:none">选择或搜索其它礼品名称...</dd>

        </dl>

      <a href="javascript:history.go(-1)" class="btn">重新选择</a>

    </div>



    

  <%

     }

  %>

</script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/points_gifts.js"></script>

