<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_products_list.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">

<style type="text/css">

.nctouch-inp-con ul li h4{width:3.8rem}

.rzs_info{ width:100%; margin-top:10px; overflow:hidden; background:#FFF; padding-top:10px; padding-bottom:10px;float:left;}

.rzs_info dl{ width:95%; margin:auto; overflow:hidden;}

.rzs_info dl span{ width:20%; float:left; overflow:hidden}

.rzs_info dl span img{ display:block; width:90%; float:left; height:auto;}

.rzs_info dl dt{ width:64%; float:left;}

.rzs_info dl dt strong{ width:100%;font-size:20px; line-height:200%;color:#333;font-weight:400;}

.rzs_info dl dt strong a{color:#333;}

.rzs_info dl dt p{ width:100%; height:1rem;font-size:0.5rem; line-height:1rem; color:#999;}

.rzs_info dl dt p img{margin-top:6px;}

.rzs_info dl dd{ width:auto;}

.rzs_info dl dd i{ display:block; height:25px; font-size:12px; line-height:25px; color:#FFF; background:#F60; text-align:center; margin-top:0px;padding:0 12px;float:right;border-radius:4px;}



.rzs_info ul{width:95%; margin:auto; overflow:hidden;}

.rzs_info ul li{ width:33.3%; float:left; height:50px; text-align:center;clear: none;}

.rzs_info ul li span{float:left; font-size:13px; line-height:40px; color:#666;}

.rzs_info ul li strong{float:left; font-size:13px; line-height:40px; color:#DD2726; font-weight:normal; text-align:left}

.rzs_info ul li em{ float:left; width:13px; height:13px;font-size:10px; line-height:15px; color:#fff;border-radius: 3px; background:#DD2726; text-align:center; margin-left:1px; margin-top:12px; }

.rzs_img{ width:100%; overflow:hidden}

.rzs_img img{ width:100% !important; height:auto !important}

.s_dianpu{ width:100%; margin-top:1rem;display: inline-block; overflow:hidden;}

.s_dianpu span{ display:block; float:left; width:50%; height:32px;}

.s_dianpu span a{ display:block; width:70%;height:30px; border:1px solid #0094DE;border-radius:3px; font-size:14px; line-height:30px; text-align:center; text-indent:20px;position:relative; color:#333;}

.bg1{ display:block; width:25px; height:25px; position:absolute; background:url(<?php echo MOBILE_TEMPLATES_URL;?>/images/rzs.png) no-repeat;background-size: auto 50px;  background-position:0 -5px; margin-top:5px; left:15%;}

.bg2{ display:block; width:25px; height:25px; position:absolute; background:url(<?php echo MOBILE_TEMPLATES_URL;?>/images/rzs.png) no-repeat;background-size: auto 50px;  background-position:0 -29px; margin-top:6px;left:15%;}

.index_taocan{overflow-x:auto;}

.index_taocan dl{ width:25%;float:left; overflow:hidden;}

.index_taocan dl dt{ width:100%; overflow:hidden; position:relative;}

.index_taocan dl dt em{ display:block; width:90%; height:20px; position:absolute; margin-left:5%; bottom:0px;background-color:rgba(0,0,0,0.5); font-size:12px; line-height:20px; text-align:center; color:#FFF}

.index_taocan dl dt img{ display:block; width:90%; margin:auto}

.index_taocan dl dd{ width:90%; height:20px;margin-top:8px; margin-left:5%;line-height:20px; font-size:12px; color:#666; }

.leixing {

    color: #FFF;

    background: #08f none repeat scroll 0% 0%;

    font-size: 0px;

    padding: 0px 0px;

    border-radius: 5px;



    float: left;

  line-height:12px;

  margin:10px 8px 0 0;

}

.goods-raty b{

  font-weight: normal;

  color: #666;

  font-size: 0.65rem;

}

.goods-raty i {

    display: inline-block;

    height: 0.5rem;

    background-image: url(<?php echo MOBILE_TEMPLATES_URL;?>/images/star_r.png);

    background-repeat: repeat-x;

    background-position: 0 0;

    background-size: contain;

}

.goods-raty i.star1 { width: 0.5rem;}

.goods-raty i.star2 { width: 1rem;}

.goods-raty i.star3 { width: 1.5rem;}

.goods-raty i.star4 { width: 2rem;}

.goods-raty i.star5 { width: 2.5rem;}

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

    <div class="header-tab"><a href="<?php echo urlMobile('shop');?>" class="cur">所有店铺</a><a href="<?php echo urlMobile('shop','shopclass');?>">店铺分类</a></div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

    <?php include template('layout/toptip');?>

</header>



<div class="nctouch-main-layout fixed-Width">

 <div class="nctouch-inp-con">

      <ul class="form-box">

<li class="form-item">

          <h4>店铺所在地：<i></i></h4>

          <div class="input-box">

            <input name="area_info" type="text" class="inp" id="area_info" autocomplete="off" onChange="btn_check($('form'));" placeholder="请选择店铺地区" readonly />  <span class="input-del"></span>

          </div>

        </li>

</ul>

</div>



<div class="nctouch-order-search">

      <span><input type="text" oninput="writeClear($(this));" id="keyword" name="keyword" placeholder="输入店铺名称" maxlength="50" autocomplete="on">

      <span class="input-del"></span></span>

      <input type="button" value="&nbsp;" id="serach_store">

  </div>

      <div class="favorites-store-list" id="categroy-cnt"></div>

</div>



</body>

<script type="text/html" id="category-one">

    <%if(store_list.length > 0){%>

        <% for (var k in store_list) { 

          var v = store_list[k]; 

          var store_desccredit = formatnum(v.store_credit.store_desccredit.credit);

          var store_servicecredit = formatnum(v.store_credit.store_servicecredit.credit);

          var store_deliverycredit = formatnum(v.store_credit.store_deliverycredit.credit);

          

          

        %>

          <section class="rzs_info border_top border_bottom">

          <dl>

            <a href="<%=ApiUrl%>/index.php?con=store&store_id=<%=v.store_id%>">

            <span><img src="<%=v.store_avatar %>"></span>

            </a>

              <dt>

              <a href="<%=ApiUrl%>/index.php?con=store&store_id=<%=v.store_id%>"><strong></strong></a>

              <strong><a href="<%=ApiUrl%>/index.php?con=store&store_id=<%=v.store_id%>"><font class="leixing"><%=v.grade_id %></font><%=v.store_name %></a></strong>

              <p>好评率： <%=v.store_credit_percent %> </p>

              <p>共 <%=v.goods_count %> 件宝贝</p>

              <p>最近成交 <%=v.num_sales_jq %> 件宝贝</p>

             </dt>

			 

            <dd><a href="javascript:void(0)" onclick="favoriteStore('<%=v.store_id %>');"><i>收藏</i></a></dd>

          </dl>

          <ul>

          <li><span>宝贝描述:</span><div class="goods-raty"><i class="star<%=store_desccredit %>"></i><b> <%=v.store_credit.store_desccredit.credit %>分 </b></div></li>

          <li><span>卖家服务:</span><div class="goods-raty"><i class="star<%=store_servicecredit %>"></i><b> <%=v.store_credit.store_servicecredit.credit %>分</b></div></li>

          <li><span>物流服务:</span><div class="goods-raty"><i class="star<%=store_deliverycredit %>"></i><b> <%=v.store_credit.store_deliverycredit.credit %>分</b></div></li>

          </ul>

          <div class="index_taocan">





          <%if(v.search_list_goods.length > 0 ){%>

            <% for (var j in v.search_list_goods) { var vt = v.search_list_goods[j]; %>

                <a href="<%=vt.goods_url %>"> 

                  <dl>

                      <dt><img src="<%=vt.goods_image %>" class="B_eee"><em>¥<%=vt.goods_price %></em></dt>

                      <dd><%=vt.goods_name %></dd>

                  </dl>

                  </a>

           <%}%>

          <%}%>

            </div>

          <div class="s_dianpu">

          <span><a href="http://wpa.qq.com/msgrd?v=3&amp;uin=<%=v.store_qq%>&amp;site=qq&amp;menu=yes" style="margin-left:4%"><em class="bg1"></em>联系客服</a></span>

          <span><a href="<%=ApiUrl%>/index.php?con=store&store_id=<%=v.store_id%>" class="fr" style="margin-right:4%"><em class="bg2"></em>进入店铺</a></span>

          </div>

          </section>

        <%}%>

    <%}else{%>

    <div class="nctouch-norecord search">

      <div class="norecord-ico"><i></i></div>

        <dl>

          <dt>没有找到任何相关信息</dt>

          <dd>选择或搜索其它商家名称...</dd>

        </dl>

      <a href="javascript:history.go(-1)" class="btn">重新选择</a>

    </div>



    

  <%}%>

</script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/swipe.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/shop.js"></script>



