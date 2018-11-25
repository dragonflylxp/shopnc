<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/member_infos.js"></script> 

</head>

<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>生日设置</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

    <?php include template('layout/toptip');?>

  </div>

</header>

<div class="nctouch-main-layout">

  <div class="nctouch-inp-con">



      <div class="edtbox">

        

      <div class="edtbirth">

            <select id="myyear" style="width:32%"><option value="0">选择年份</option></select>

            <select id="mymonth" style="width:32%"><option value="0">选择月份</option></select>

            <select id="myday"  style="width:32%"><option value="0">选择日期</option></select>

            <?php if(!empty($output['update_birthday'][0])){?>

            <script>initBirthdaySelect(<?php echo $output['update_birthday'][0]?>,<?php echo $output['update_birthday'][1]?>,<?php echo $output['update_birthday'][2]?>)</script>

            <?php }else{?>

              <script>initBirthdaySelect(0,0,0)</script>

            <?php } ?>

        </div>

     </div>

      <div class="form-btn ok" ><a href="javascript:void(0);" class="btn" id="edtBirthBtn">修改</a></div>

    <div class="register-mobile-tip"> 小提示：请设置生日信息,会有惊喜哟。</div>

  </div>

</div>











