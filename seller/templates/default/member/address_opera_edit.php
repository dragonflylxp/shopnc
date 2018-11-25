<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>编辑收货地址</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="save"></i></a> </div>

  </div>

</header>

<div class="nctouch-main-layout">

  <form>

    <div class="nctouch-inp-con">

      <ul class="form-box">

        <li class="form-item">

          <h4>收货人姓名</h4>

          <div class="input-box">

            <input type="text" class="inp" name="true_name" id="true_name" autocomplete="off" oninput="writeClear($(this));"/>

            <span class="input-del"></span> </div>

        </li>

        <li class="form-item">

          <h4>联系手机</h4>

          <div class="input-box">

            <input type="tel" class="inp" name="mob_phone" id="mob_phone" autocomplete="off" oninput="writeClear($(this));"/>

            <span class="input-del"></span> </div>

        </li>

        <li class="form-item">

          <h4>地区选择</h4>

          <div class="input-box">

            <input type="text" class="inp" name="area_info" id="area_info" autocomplete="off" onchange="btn_check($('form'));" readonly/>

          </div>

        </li>

        <li class="form-item">

          <h4>详细地址</h4>

          <div class="input-box">

            <input type="text" class="inp" name="address" id="address" autocomplete="off" oninput="writeClear($(this));">

            <span class="input-del"></span> </div>

        </li>

        <li>

          <h4>默认地址</h4>

          <div class="input-box">

            <label>

              <input type="checkbox" name="is_default" id="is_default" value="1" />

              <span class="power"><i></i></span> </label>

          </div>

        </li>

      </ul>

      <div class="error-tips"></div>

      <div class="form-btn ok"><a class="btn" href="javascript:;">保存地址</a></div>

    </div>

  </form>

</div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/address_opera_edit.js"></script>

