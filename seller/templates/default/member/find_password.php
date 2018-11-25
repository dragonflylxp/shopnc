<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"><a href="<?php echo urlMobile();?>"><i class="home"></i></a></div>

    <div class="header-title">

      <h1>找回密码</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="<?php echo urlMobile('login');?>" class="text">登录</a> </div>

  </div>

</header>

<div class="nctouch-main-layout fixed-Width">

  <div class="nctouch-inp-con">

    <form action="" method ="">

      <ul class="form-box">

        <li class="form-item">

          <h4>手&nbsp;机&nbsp;号</h4>

          <div class="input-box">

            <input type="tel" placeholder="请输入手机号" class="inp" name="usermobile" id="usermobile" oninput="writeClear($(this));" maxlength="11" autocomplete="off" />

            <span class="input-del"></span> </div>

        </li>

        <li class="form-item">

          <h4>验&nbsp;证&nbsp;码</h4>

          <div class="input-box">

            <input type="text" id="captcha" name="captcha" maxlength="4" size="10" class="inp" autocomplete="off" placeholder="输入4位验证码" oninput="writeClear($(this));" />

            <span class="input-del code"></span><a href="javascript:void(0)" id="refreshcode" class="code-img"><img border="0" id="codeimage" name="codeimage"></a>

            <input type="hidden" id="codekey" name="codekey" value="">

          </div>

        </li>

      </ul>

      <div class="error-tips"></div>

      <div class="form-btn"><a href="javascript:void(0);" class="btn" id="find_password_btn">获取验证码</a></div>

      <div class="form-notes">请填写已经绑定过的手机号码。</div>

    </form>

  </div>

  <input type="hidden" name="referurl">

</div>

<footer id="footer" class="bottom"></footer>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/find_password.js"></script>