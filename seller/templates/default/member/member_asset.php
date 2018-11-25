<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>



<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>我的财产</h1>

    </div>

  <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

   <?php include template('layout/toptip');?>



</header>

<div class="nctouch-main-layout">

    <dl class="mt5">

        

          <ul id="kj_ul" class="mt5 mb5">

            <li><a href="<?php echo urlMobile('member_signin');?>"><i class="kj-01"></i><p>签到</p></a></li>

            <li><a href="<?php echo urlMobile('member_fund','recharge_add');?>"><i class="kj-02"></i><p>充值</p></a></li>

            <li><a href="<?php echo urlMobile('member_voucher');?>"><i class="kj-03"></i><p>代金券</p></a></li>

          </ul>

        </dd>

      </dl>

  <ul class="nctouch-default-list">

    <li><a href="<?php echo urlMobile('member_fund','predepositlog_list');?>">

      <h4><i class="cc-06"></i>账户余额</h4>

      <h6>预存款账户余额、充值及提现明细</h6>

      <span class="tip" id="predepoit"></span> <span class="arrow-r"></span></a>

    </li>

    <li><a href="<?php echo urlMobile('member_signin');?>">

      <h4><i class="cc-13"></i>签到</h4>

      <h6>每天登录,签到送积分</h6>

       <span class="arrow-r"></span></a>

    </li>

    <li><a href="<?php echo urlMobile('member_fund','recharge_add');?>">

      <h4><i class="cc-12"></i>充值</h4>

      <h6>账户余额充值操作</h6>

       <span class="arrow-r"></span></a>

    </li>



    <li><a href="<?php echo urlMobile('member_fund');?>">

      <h4><i class="cc-07"></i>充值卡余额</h4>

      <h6>充值卡账户余额以及卡密充值操作</h6>

      <span class="tip" id="rcb"></span> <span class="arrow-r"></span></a>

    </li>

    <li><a href="<?php echo urlMobile('member_voucher');?>">

      <h4><i class="cc-08"></i>店铺代金券</h4>

      <h6>店铺代金券使用情况以及卡密兑换代金券操作</h6>

      <span class="tip" id="voucher"></span><span class="arrow-r"></span></a>

    </li>

    <li><a href="<?php echo urlMobile('member_redpacket');?>">

      <h4><i class="cc-09"></i>平台红包</h4>

      <h6>平台红包使用情况以及卡密领取红包操作</h6>

      <span class="tip" id="redpacket"></span><span class="arrow-r"></span></a>

    </li>

    <li><a href="<?php echo urlMobile('member_points');?>">

      <h4><i class="cc-10"></i>会员积分</h4>

      <h6>会员积分获取及消费日志</h6>

      <span class="tip" id="point"></span><span class="arrow-r"></span></a>

    </li>

  </ul>

</div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/member_asset.js"></script>

