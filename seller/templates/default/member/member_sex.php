<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>性别设置</h1>

    </div>

     <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

    </div>

    <?php include template('layout/toptip');?>





</header>

<div class="nctouch-main-layout">

  <div class="nctouch-inp-con">



      <em class="border_bottom"><input name="sex" value="1" type="radio" <?php if($output['member_sex']==1){ echo 'checked="checked"';}?> />男</em>

      <em class="border_bottom"><input name="sex" value="2" type="radio" <?php if($output['member_sex']==2){ echo 'checked="checked"';}?> />女</em>

      <em class="border_bottom"><input name="sex" value="3" type="radio" <?php if($output['member_sex']==3){ echo 'checked="checked"';}?>/>保密</em>

      

      <div class="form-btn ok" ><a href="javascript:void(0);" class="btn" id="nextform1">修改</a></div>



    <div class="register-mobile-tip"> 小提示：请设置真实性别。</div>

  </div>

</div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/member_infos.js"></script> 







