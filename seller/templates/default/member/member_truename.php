<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>修改真实姓名</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

   

    </div>

 <?php include template('layout/toptip');?>



</header>

<div class="nctouch-main-layout">

  <div class="nctouch-inp-con">





      <ul class="form-box mt5">

        <li class="form-item">

       

          <div class="input-box" style="margin-left:0">

            <input type="text" id="truename"  name="truename" class="inp"  placeholder="输入您的真实姓名" oninput="writeClear($(this));" onfocus="writeClear($(this));" value="<?php echo $output['truename'];?>" />

            <span class="input-del"></span> 

          </div>

        </li>

      </ul>

    

      <div class="form-btn <?php if(!empty($output['truename'])){ echo 'ok';}?>" ><a href="javascript:void(0);" class="btn" id="nextform">修改</a></div>



    <div class="register-mobile-tip"> 小提示：通过设置真实姓名以后方便商家进行物流配送。</div>

  </div>

</div>

<footer id="footer" class="bottom"></footer>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/member_infos.js"></script> 

</body>

</html>

