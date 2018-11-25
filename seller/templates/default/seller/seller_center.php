<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<style type="text/css">

  .member-center dd ul#order_ul li{

    width: 20%;

  }

  .member-top{

    background-image:url(<?php echo MOBILE_TEMPLATES_URL;?>/images/sjbg.png) ;

  }

  #order_ul li em{

    display: none;

  }

  </style>

</head>

<body>

<header id="header" class="transparent">

  <div class="header-wrap">

    <!-- <div class="header-l"> <a href="<?php echo urlMobile('member_account');?>"> <i class="set"></i> </a> </div> -->

    <div class="header-title">

      <h1>商家中心</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div> 

  <?php include template('layout/seller_toptip');?>



</header>

<div class="scroller-body">

  <div class="scroller-box">

    <div class="member-top">

      <div class="member-info">



      <div class="user-avatar"> <img src="<?php echo $output['store_info']['store_avatar'];?>"><sup><?php echo $output['store_info']['grade_name'];?></sup> </div>



      <div class="user-name"> <span><?php echo $output['store_info']['seller_name'];?></span> </div>

    </div>

    <div class="member-collect">

      <span><a href="<?php echo urlMobile('seller_goods','index',array('data-state'=>'online'));?>"><em class="sale_goods"></em><p>出售中</p></a> </span>

      <span><a href="<?php echo urlMobile('seller_goods','index',array('data-state'=>'offline'));?>"><em class="ck_goods"></em><p>仓库中</p></a> </span>

      <span><a href="<?php echo urlMobile('seller_goods','index',array('data-state'=>'lockup'));?>"><em class="wg_goods"></em><p>违规下架</p></a> </span>

    </div>

    </div>

    <div class="member-center">

      <dl class="mt5">

        <dt><a href="<?php echo urlMobile('seller_order');?>">

          <h3><i class="mc-01"></i>销售订单</h3>

          <h5>查看全部订单<i class="arrow-r"></i></h5>

          </a></dt>

        <dd>

          <ul id="order_ul">

        

          <li>

            <a href="<?php echo urlMobile('seller_order','index',array('data-state'=>'state_new'));?>"><em class="state_new"></em><i class="cc-17"></i><p>待付款</p></a>

          </li>

          <li>

            <a href="<?php echo urlMobile('seller_order','index',array('data-state'=>'state_pay'));?>"><em class="state_pay"></em><i class="cc-14"></i><p>待发货</p></a>

          </li>

            <li>

            <a href="<?php echo urlMobile('seller_order','index',array('data-state'=>'state_send'));?>"><em class="state_send"></em><i class="cc-22"></i><p>已发货</p></a>

          </li>

          <li>

            <a href="<?php echo urlMobile('seller_order','index',array('data-state'=>'state_success'));?>"><em class="state_success"></em><i class="cc-15"></i><p>已完成</p></a>

          </li>

          <li>

            <a href="<?php echo urlMobile('seller_order','index',array('data-state'=>'state_cancel'));?>"><em class="state_cancel"></em><i class="cc-16"></i><p>已取消</p></a>

          </li>

          </ul>

        </dd>

      </dl>

      <dl class="mt5">

        <dt><a href="<?php echo urlMobile('seller_stat');?>">

          <h3><i class="mc-02"></i>销售统计</h3>

          <h5>查看全部统计<i class="arrow-r"></i></h5>

          </a></dt>

        <dd>

           <ul id="order_ul">

          <li>

            <a href="<?php echo urlMobile('seller_stat','index');?>"><i class="cc-17"></i><p>店铺概况</p></a>

            </li>

          <li>

            <a href="<?php echo urlMobile('seller_stat','goodslist');?>"><i class="cc-18"></i><p>店铺统计</p></a>

          </li>

          <li>

            <a href="<?php echo urlMobile('seller_stat','storeflow');?>"><i class="cc-19"></i><p>店铺流量</p></a>

          </li>

          <li>

            <a href="<?php echo urlMobile('seller_stat','goodsflow');?>"><i class="cc-20"></i><p>商品流量</p></a>

          </li>

          <li>

            <a href="<?php echo urlMobile('seller_stat','hotgoods');?>"><i class="cc-21"></i><p>热销排行</p></a>

          </li>

        </dd>

      </dl>



      <dl class="mt5">

        <dt>

          <h3><i class="mc-08"></i>退款/退货</h3>

         

          </dt>

        <dd>

           <ul id="order_ul">

          <li>

            <a href="<?php echo urlMobile('seller_order_refund');?>"><em class="order_refund"></em><i class="cc-23"></i><p>售前退款</p></a>

            </li>

          <li>

            <a href="<?php echo urlMobile('seller_order_refund','',array('lock'=>1));?>"><em class="order_refund1"></em><i class="cc-24"></i><p>售后退款</p></a>

          </li>

          <li>

            <a href="<?php echo urlMobile('seller_order_return');?>"><i class="cc-25"><em class="order_return"></em></i><p>售前退货</p></a>

          </li>

          <li>

            <a href="<?php echo urlMobile('seller_order_return','',array('lock'=>1));?>"><em class="order_return1"></em><i class="cc-26"></i><p>售后退货</p></a>

          </li>

         

        </dd>

      </dl>

      <dl class="mt5">

        <dt><a href="<?php echo urlMobile('seller_message');?>">

          <h3><i class="mc-07"></i>消息</h3>

          <h5><i class="arrow-r"></i></h5>

          </a></dt>

      </dl>

    

    </div>

  </div>

</div>



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js?201511"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/seller_center.js"></script> 

<?php require_once template('layout/seller_footer');?>

