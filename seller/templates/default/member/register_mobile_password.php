<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>提交验证码</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="<?php echo urlMobile('login');?>" class="text">登录</a> </div>

  </div>

</header>

<div class="nctouch-main-layout fixed-Width">

  <div class="register-mobile-tip">

    <p>请设置登录密码</p>

  </div>

  <div class="nctouch-inp-con">

    <form action="" method ="">

      <ul class="form-box">

        <li class="form-item">

          <h4>密&#12288;&#12288;码</h4>

          <div class="input-box">

            <input type="text" placeholder="请输入6-20位密码" class="inp" name="password" id="password" oninput="writeClear($(this));"/>

            <span class="input-del"></span> </div>

        </li>

      </ul>

      <div class="remember-form">

        <input id="checkbox" type="checkbox" checked="">

        <label for="checkbox">显示密码</label>

      </div>

      <div class="error-tips"></div>

      <div class="form-btn"><a href="javascript: void(0);" class="btn" id="completebtn">完成</a></div>

    </form>

  </div>

  <input type="hidden" name="referurl">

</div>



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/register_mobile_password.js"></script>

