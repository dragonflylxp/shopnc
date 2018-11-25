<?php defined('Inshopec') or exit('Access Invalid!');?>

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

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>确认收货人资料</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

</div>

  <?php include template('layout/toptip');?>



</header>



<div class="nctouch-main-layout mb20">

  <div class="nctouch-cart-block"> 

    <!--正在使用的默认地址Begin-->

    <div class="nctouch-cart-add-default"><a href="javascript:void(0);" id="list-address-valve"><i class="icon-add"></i>

      <dl id="address_options" address_options="<?php echo $output['address_list']['address_id']; ?>">

        <dt>收货人：<span id="true_name"><?php echo $output['address_list']['true_name'];?></span><span id="mob_phone">  <?php if($output['address_list']['mob_phone']) echo $output['address_list']['mob_phone']; else echo $output['address_list']['tel_phone']; ?></span></dt>

        <dd><span id="address"><?php echo $output['address_list']['area_info']; ?>&nbsp;&nbsp;<?php echo $output['address_list']['address']; ?></span></dd>

      </dl>

      <i class="icon-arrow"></i></a></div>

    <!--正在使用的默认地址End--> 

  </div>

  <!--选择收货地址Begin-->

  <div id="list-address-wrapper" class="nctouch-full-mask hide">

    <div class="nctouch-full-mask-bg"></div>

    <div class="nctouch-full-mask-block">

      <div class="header">

        <div class="header-wrap">

          <div class="header-l"> <a href="javascript:void(0);"> <i class="back"></i> </a> </div>

          <div class="header-title">

            <h1>收货地址管理</h1>

          </div>

        </div>

      </div>

      <div class="nctouch-main-layout" id="list-address-scroll">

        <ul class="nctouch-cart-add-list" id="list-address-add-list-ul">

        </ul>

        <div id="addresslist" class="mt10"> <a href="javascript:void(0);" class="btn-l" id="new-address-valve">新增收货地址</a> </div>

      </div>

    </div>

  </div>

  <!--选择收货地址End--> 

  <!--新增收货地址Begin-->

  <div id="new-address-wrapper" class="nctouch-full-mask hide">

    <div class="nctouch-full-mask-bg"></div>

    <div class="nctouch-full-mask-block">

      <div class="header">

        <div class="header-wrap">

          <div class="header-l"> <a href="javascript:void(0);"> <i class="back"></i> </a> </div>

          <div class="header-title">

            <h1>新增收货地址</h1>

          </div>

        </div>

      </div>

      <div class="nctouch-main-layout" id="new-address-scroll">

        <div class="nctouch-inp-con">

          <form id="add_address_form">

            <ul class="form-box">

              <li class="form-item">

                <h4>收货人姓名</h4>

                <div class="input-box">

                  <input type="text" class="inp" name="true_name" id="vtrue_name" autocomplete="off" oninput="writeClear($(this));"/>

                  <span class="input-del"></span> </div>

              </li>

              <li class="form-item">

                <h4>联系手机</h4>

                <div class="input-box">

                  <input type="tel" class="inp" name="mob_phone" id="vmob_phone" autocomplete="off" oninput="writeClear($(this));"/>

                  <span class="input-del"></span> </div>

              </li>

              <li class="form-item">

                <h4>地区选择</h4>

                <div class="input-box">

                  <input name="area_info" type="text" class="inp" id="varea_info" autocomplete="off" onchange="btn_check($('form'));" readonly/>

                </div>

              </li>

              <li class="form-item">

                <h4>详细地址</h4>

                <div class="input-box">

                  <input type="text" class="inp" name="vaddress" id="vaddress" autocomplete="off" oninput="writeClear($(this));"/>

                  <span class="input-del"></span> </div>

              </li>

            </ul>

            <div class="error-tips"></div>

            <div class="form-btn"><a href="javascript:void(0);" class="btn">保存地址</a></div>

          </form>

        </div>

      </div>

    </div>

  </div>

  <!--新增收货地址End--> 



  



  

  

 

  

  <!--商品列表Begin-->

  <div id="goodslist_before" class="mt5">

    <div id="deposit"> 

      <div id="goodslist_before" class="mt5">

    <div id="deposit">

  <div class="nctouch-cart-container">

    <dl class="nctouch-cart-store">

      <dt>已选礼品

      </dt>

      

    </dl>

    <ul class="nctouch-cart-item">

     <?php

          if(is_array($output['pointprod_arr']['pointprod_list']) and count($output['pointprod_arr']['pointprod_list'])>0) {

        foreach($output['pointprod_arr']['pointprod_list'] as $val) {

      ?>

      <li class="buy-item">

        <div class="goods-pic">

          <a href="<?php echo urlMobile('points', 'detail', array('pgoods_id' => $val['pgoods_id']));?>">

            <img src="<?php echo $val['pgoods_image_small']; ?>" alt="<?php echo $val['pgoods_name']; ?>">

          </a>

        </div>

        <dl class="goods-info">

          <dt class="goods-name"><a href="<?php echo urlMobile('points', 'detail', array('pgoods_id' => $val['pgoods_id']));?>"><?php echo $val['pgoods_name']; ?></a></dt>

          <dd class="goods-type"></dd>

        </dl>

        <div class="goods-subtotal">

          <span class="goods-price"><em><?php echo $val['onepoints']; ?></em>积分</span>

        </div>

        <div class="goods-num">

          <em><?php echo $val['quantity']; ?></em>件

        </div>

        

       

      </li>

    <?php } }?>

    </ul>

  <div class="nctouch-cart-subtotal">

    

    

    <div class="message">

      <input placeholder="备注留言：" id="point_ordermessage" type="text">

    </div>

 

  </div>

  </div>



  </div>

  </div>

    </div>

  </div>

  <!--商品列表End--> 

  



  

  <!--底部总金额固定层Begin-->

  <div class="nctouch-cart-bottom">

    <div class="total"><span id="online-total-wrapper"></span>

      <dl class="total-money">

        <dt>所需总积分:</dt>

        <dd><em id="totalPrice"><?php echo $output['pointprod_arr']['pgoods_pointall'];?></em>积分</dd>

      </dl>

    </div>

    <div class="check-out"><a href="javascript:void(0);" id="ToBuyStep2">兑换完成</a></div>

  </div>

  <!--底部总金额固定层End-->

 

</div>



<script type="text/html" id="list-address-add-list-script">

<% for (var i=0; i<address_list.length; i++) { %>

<li <% if (address_id == address_list[i].address_id) { %>class="selected"<% } %> data-param="{address_id:'<%=address_list[i].address_id%>',true_name:'<%=address_list[i].true_name%>',mob_phone:'<%=address_list[i].mob_phone%>',area_info:'<%=address_list[i].area_info%>',address:'<%=address_list[i].address%>',area_id:'<%=address_list[i].area_id%>',city_id:'<%=address_list[i].city_id%>'}"> <i></i>

  <dl>

    <dt>收货人：<span id=""><%=address_list[i].true_name%></span><span id=""><%=address_list[i].mob_phone%></span><% if (address_list[i].is_default == 1) { %><sub>默认</sub><% } %></dt>

    <dd><span id=""><%=address_list[i].area_info %>&nbsp;<%=address_list[i].address %></span></dd>

  </dl>

</li>

<% } %>

</script> 

<form action="<?php  echo urlMobile('pointcart','step2');?>" id="substep2" method="post">

  <input type="hidden"  name="address_options"  value="<?php echo $output['address_list']['address_id']; ?>" />

  <input type="hidden"  name="pcart_message" value=""></input>

</form>



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/points_buy_step1.js"></script>



