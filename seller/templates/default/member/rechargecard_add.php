<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

    <div class="header-tab"> <a href="<?php echo urlMobile('member_fund');?>">充值卡余额</a> <a href="javascript:void(0);" class="cur">充值卡充值</a> </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

  <?php include template('layout/toptip');?>





</header>

<div class="nctouch-main-layout">

  <div class="nctouch-asset-info">

    <div class="container rcard"> <i class="icon"></i>

      <dl class="rule">

        <dd>请输入已知平台充值卡号码</dd>

        <dd>充值后可以在购物结算时选取使用充值卡余额进行支付</dd>

      </dl>

    </div>

  </div>

  <div class="nctouch-inp-con">

    <form action="" method ="">

      <ul class="form-box">

        <li class="form-item">

          <h4>充值卡号</h4>

          <div class="input-box">

            <input type="text" id="rc_sn" name="rc_sn" class="inp" maxlength="100" placeholder="请输入平台充值卡号" oninput="writeClear($(this));" onFocus="writeClear($(this));"/>

            <span class="input-del"></span> </div>

        </li>

        <li class="form-item">

          <h4>验&nbsp;证&nbsp;码</h4>

          <div class="input-box">

            <input type="text" id="captcha" name="captcha" maxlength="4" size="10" class="inp" autocomplete="off" placeholder="输入4位验证码" oninput="writeClear($(this));"/>

            <span class="input-del code"></span> <a href="javascript:void(0)" id="refreshcode" class="code-img"><img border="0" id="codeimage" name="codeimage"></a>

            <input type="hidden" id="codekey" name="codekey" value="">

          </div>

        </li

      </ul>

      <div class="error-tips"></div>

      <div class="form-btn"><a href="javascript:void(0);" class="btn" id="saveform">确认提交</a></div>

    </form>

  </div>

</div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/rechargecard_add.js"></script> 

