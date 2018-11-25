<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">



</head>

<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="<?php echo urlMobile();?>"><i class="home"></i></a></div>

    <div class="header-title">

      <h1>登录</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="<?php echo urlMobile('register');?>" class="text">注册</a> </div>

  </div>

</header>

<div class="nctouch-main-layout fixed-Width">

  <div class="nctouch-inp-con">

    <form action="" method ="">

      <ul class="form-box">

        <li class="form-item">

          <h4>账&#12288;户</h4>

          <div class="input-box">

            <input type="text" placeholder="请输入用户名/已验证手机" class="inp" name="username" id="username" oninput="writeClear($(this));"/>

            <span class="input-del"></span> </div>

        </li>

        <li class="form-item">

          <h4>密&#12288;码</h4>

          <div class="input-box">

            <input type="password" placeholder="请输入登录密码" class="inp" name="pwd" id="userpwd" oninput="writeClear($(this));"/>

            <span class="input-del"></span> </div>

        </li>

      </ul>

      <div class="remember-form">

        <input id="checkbox" type="checkbox" checked="" class="checkbox">

        <label for="checkbox">七天自动登录</label>

        <a class="forgot-password" href="<?php echo urlMobile('login', 'find_password');?>">忘记密码？</a> </div>

      <div class="error-tips"></div>

      <div class="form-btn"><a href="javascript:void(0);" class="btn" id="loginbtn">登录</a></div>

    </form>

  </div>

  <div class="joint-login">

    <h2><span>合作账号登录</span></h2>

    <ul>

      <li><a class="weibo" href="javascript: void(0);"></a></li>

      <li><a class="qq" href="javascript: void(0);"></a></li>

      <li><a class="weixin" href="javascript: void(0);"></a></li>

    </ul>

  </div>

</div>



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/login.js"></script>



