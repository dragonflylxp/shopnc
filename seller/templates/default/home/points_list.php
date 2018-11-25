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

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

    <div class="header-title">

      <h1>代金券列表</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

    <?php include template('layout/toptip');?>

</header>

<?php require_once template('layout/fiexd');?>

<div class="goods-search-list-nav">

    <ul id="nav_ul">

      <li><a href="javascript:void(0);" class="current" id="sort_default"  onclick="init_get_list('1', '1')">兑换量</a></li>

      <li><a href="javascript:void(0);" class="" onclick="init_get_list('2', '1')">积分值</a></li>

      <li><a href="javascript:void(0);" id="search_adv">筛选<i></i></a></li>

    </ul>

    

  </div>







<div class="nctouch-main-layout pointsclub mt40 mb20">



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

    <dt>选择分类</dt>

    <dd><span class="inp-balck add"><select id="sc_id">

          <option value="">不限</option>

            <% for (j = 0; j < store_class.length; j++) { %>

            <option value="<%=store_class[j]['sc_id']%>"><%=store_class[j]['sc_name']%></option>

            <% } %>

            </select>

         

      </span>

    </dd>

  </dl>

  <dl>

    <dt>所需积分</dt>

    <dd>

      <span class="inp-balck"><input type="text" id="points_min" nctype="price" pattern="[0-9]*" class="inp" placeholder="最低"/></span>

      <span class="line"></span>

      <span class="inp-balck"><input nctype="price" type="text" id="points_max" pattern="[0-9]*" class="inp" placeholder="最高"/></span>

    </dd>

  </dl>

  <dl>

    <dt>优惠券面额</dt>

    <dd id="click_level">

     

      <% for (i = 0; i < pricelist.length; i++) { %>

           <a href="javascript:void(0);" nctype="mprice"  class="mprice" price="<%=pricelist[i]['voucher_price']%>"><%=pricelist[i]['voucher_price']%></a>

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

  <% var voucherlist = datas.voucherlist; %>

  <% if(voucherlist.length >0){%>

  <%for(i=0;i< voucherlist.length;i++){%>

 <div class="coupon-excellent m <% if(voucherlist[i].voucher_t_sy == 0){ %>  sold-out  <% }else{ %> coupon-jing <% } %>" onclick="ajax_cashing(<%=voucherlist[i].voucher_t_id;%>,$(this));">

                    <div class="mt">

                        <h4><%=voucherlist[i].voucher_t_title;%></h4>

                        <div class="ce-progress ce-progress-baidu">

                            <div class="ce-p-bg" style="width:<%=voucherlist[i].voucher_jd;%>%"></div>

                            <p>已领取<span><%=voucherlist[i].voucher_jd;%></span>%</p>

                            

                        </div>

                    </div>

                    <div class="mc">

                        <div class="ce-con">

                            <div class="ce-con-l">

                                <span> <%=voucherlist[i].voucher_t_storename;%> </span>

                                <p><i>¥</i><%=voucherlist[i].voucher_t_price;%></p>

            

                            </div>

                            <div style="margin-top: -31px;" class="ce-con-r">

                                <p class="ce-con-txt01" displaylength="36">需<em><%=voucherlist[i].voucher_t_points;%></em>积分</p>

                                <span class="ce-con-line"></span>

                                <p class="ce-con-txt02">

                                  <% if(voucherlist[i].voucher_t_limit > 0){%>购物满<%=voucherlist[i].voucher_t_limit;%>元可用<% } else { %>无限额代金券<% } %>

                                </p>

                            </div>

                        </div> 

                        <div class="ce-hr"></div>

                        <div class="ce-time">有效期至 <%=voucherlist[i].voucher_t_end_date;%></div>

            <span class="sold-out-signet"></span>

            

          </div>

</div>

<%}%>

      <% if (hasmore) {%>

      <li class="loading"><div class="spinner"><i></i></div>代金券数据读取中...</li>

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

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/points_list.js"></script>



