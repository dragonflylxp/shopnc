<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

	<div class="header-wrap">

		<div class="header-l">

			<a href="javascript:history.go(-1)">

				<i class="back"></i>

			</a>

		</div>

		<div class="header-title">

			<h1>修改登录密码</h1>

		</div>

      <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

    </div>

      <?php include template('layout/toptip');?>





</header>

<div class="nctouch-main-layout">

<div class="register-mobile-tip"> 您当前的手机号码是<em id="mobile"></em></div>

  <div class="nctouch-inp-con">

    <form action="" method ="">

    <ul class="form-box">

      <li class="form-item">

        <h4>验&nbsp;证&nbsp;码</h4>

        <div class="input-box">

          <input type="text" id="captcha" name="captcha" maxlength="4" size="10" class="inp" autocomplete="off" placeholder="输入图形验证码" oninput="writeClear($(this));"/>

          <span class="input-del code"></span> <a href="javascript:void(0)" id="refreshcode" class="code-img"><img border="0" id="codeimage" name="codeimage"></a>

          <input type="hidden" id="codekey" name="codekey" value="">

        </div>

      </li>

      <li class="form-item">

        <h4>动&nbsp;态&nbsp;码</h4>

        <div class="input-box">

          <input type="text" id="auth_code" name="auth_code" class="inp" value="" maxlength="11" placeholder="输入短信动态验证码" oninput="writeClear($(this));" onfocus="writeClear($(this));" pattern="[0-9]*" />

          <span class="input-del code"></span>

          <span class="code-countdown" style=" display: none;">

             <p><em>59</em>秒后重新获取</p>

            </span>

          <span class="code-again" style=""><a id="send" href="javascript: void(0);">获取短信验证</a></span>

          </div>

      </li>

    </ul>

    <div class="error-tips"></div>

    <div class="form-btn"><a href="javascript:void(0);" class="btn" id="nextform">下一步</a></div>

    </form>

  </div>

</div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/member_password_step1.js"></script> 

