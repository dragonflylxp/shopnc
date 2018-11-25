<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<style type="text/css">

  #kj_ul li{width: 50%}

</style>

</head>

<body>



<header id="header"  class="fixed">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>第三登录设置</h1>

    </div>

  <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

   <?php include template('layout/toptip');?>



</header>

<div class="nctouch-main-layout">

 <div class="alert " style="clear:both;">

  <ul class="mt5">

      <li>1、无需记住本站的账号和密码,即可轻松登录</li>

     

  </ul>

  </div>

    <dl class="mt5">

        

          <ul id="kj_ul" class="mt5 mb5">

            <?php if(!$output['member_info']['member_qqopenid'] && !$output['member_info']['member_qqinfo']){?>

              <li><a href="<?php echo MOBILE_SITE_URL;?>/api.php?con=toqq"><i class="kj-04"></i><p>QQ登录</p></a></li>

            <?php }else{ ?>

              <li><a href="<?php echo urlMobile('member_bind','unbindqq');?>"><i class="kj-04"></i><p>QQ登录</p></a></li>

            <?php } ?>

            <?php if(!$output['member_info']['member_sinaopenid'] && !$output['member_info']['member_sinainfo']){?>

              <li><a href="<?php echo MOBILE_SITE_URL;?>/api.php?con=tosina"><i class="kj-05"></i><p>微博登录</p></a></li>            

            <?php }else{ ?>

              <li><a href="<?php echo urlMobile('member_bind','unbindsina');?>"><i class="kj-05"></i><p>微博登录</p></a></li>

            <?php } ?>

            

            <!-- <li><a href="<?php echo MOBILE_SITE_URL;?>/api.php?con=toqq"><i class="kj-06"></i><p>微信登录</p></a></li> -->

          </ul>

        </dd>

      </dl>



</div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

