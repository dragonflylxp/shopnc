<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<style type="text/css">

#kj_ul li{

    width: 50%;

  }

</style>

</head>

<body>



<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>消息管理</h1>

    </div>

  <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

   <?php include template('layout/toptip');?>



</header>

<div class="nctouch-main-layout">

    <dl class="mt5">

        

          <ul id="kj_ul" class="mt5 mb5">

            <li><a href="<?php echo urlMobile('message');?>"><i class="kj-07"></i><p>站内消息</p></a></li>

            <li><a href="<?php echo urlMobile('member_chat','chat_list');?>"><i class="kj-08"></i><p>在线咨询</p></a></li>

           

          </ul>

        </dd>

      </dl>



</div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/member_asset.js"></script>

