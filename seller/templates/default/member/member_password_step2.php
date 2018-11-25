<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

	<div class="header-wrap">

		<div class="header-l">

			<a href="<?php echo urlMobile('member_account');?>">

				<i class="back"></i>

			</a>

		</div>

		<div class="header-title">

			<h1>更改登录密码</h1>

		</div>

	   <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

    </div>

      <?php include template('layout/toptip');?>



</header>

<div class="nctouch-main-layout">

<div class="register-mobile-tip"> 登录密码由 6-20个大小写英文字母、符号或数字组成</div>

  <div class="nctouch-inp-con">

    <form action="" method ="">

    <ul class="form-box">

      <li class="form-item">

        <h4>设置密码</h4>

        <div class="input-box">

          <input type="password" id="password" name="password" maxlength="20" size="10" class="inp" autocomplete="off" placeholder="输入登录密码" oninput="writeClear($(this));"/>

        </div>

      </li>

      <li class="form-item">

        <h4>密码确认</h4>

        <div class="input-box">

          <input type="password" id="password1" name="password" class="inp" maxlength="20" placeholder="再次输入登录密码" oninput="writeClear($(this));" onfocus="writeClear($(this));" />

          </div>

      </li>

    </ul>

    <div class="error-tips"></div>

    <div class="form-btn"><a href="javascript:void(0);" class="btn" id="nextform">提交</a></div>

    </form>

  </div>

</div>





<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/member_password_step2.js"></script> 

