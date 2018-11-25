<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>设置</h1>

    </div>

  <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

  <?php include template('layout/toptip');?>



</header>

<div class="nctouch-main-layout">



  <ul class="nctouch-default-list">

   <li>

    <a href="<?php echo urlMobile('member_account','update_img');?>">

      <h4>会员头像</h4>

      <h6>建议您设置会员头像</h6>

      <span class="vip-touxiang"><img src="<?php echo $output['avator'];?>"></span>

    </a>

  </li>

  <li>

    <a href="<?php echo urlMobile('member_account','member_truename');?>">

      <h4>真实姓名</h4>

      <h6>建议您设置真实姓名,方便物流配送</h6>

      <span class="tip" id="truename_value">

         <?php if(!empty($output['member_info']['member_truename'])){?>

            

        <?php echo $output['member_info']['member_truename'];}else{?>

            未设置

        <?php  }?>

      </span><span class="arrow-r"></span>

    </a>

  </li>

  <li>

    <a href="<?php echo urlMobile('member_account','member_sex');?>">

      <h4>性别</h4>

      <h6>建议您设置性别</h6>

      <span class="tip" id="sex_value">

        <?php if(empty($output['member_info']['member_sex'])){?>

              <b>未设置</b>

        <?php }elseif($output['member_info']['member_sex']==1){?>

              男

        <?php }elseif($output['member_info']['member_sex']==2){?>

              女

        <?php }elseif($output['member_info']['member_sex']==3){?>

              保密

        <?php  }?>

      </span><span class="arrow-r"></span>

    </a>

  </li>

  <li>

    <a href="<?php echo urlMobile('member_account','member_birthday');?>">

      <h4>生日</h4>

      <h6>建议您设置生日</h6>

      <span class="tip" id="birthday_value">

         <?php if(!empty($output['member_info']['member_birthday'])){?>

            

        <?php echo $output['member_info']['member_birthday'];}else{?>

            <b>未设置</b>

        <?php  }?>

      </span><span class="arrow-r"></span>

    </a>

  </li>

  <li>

    <a href="<?php echo urlMobile('member_account','member_password_step1');?>">

      <h4>登录密码</h4>

      <h6>建议您定期更改密码以保护账户安全</h6>

       <span class="tip" id="password_value">

        <?php if(!empty($output['member_info']['member_passwd'])){?>

            已设置

        <?php }else{?>

            <b>未设置</b>

        <?php  }?>

       </span><span class="arrow-r"></span>

    </a>

  </li>

  <li>

    <a href="<?php echo urlMobile('member_account','member_mobile_bind');?>" id="mobile_link">

      <h4>手机验证</h4>

      <h6>若您的手机已丢失或停用，请立即修改更换</h6>

      <span class="tip" id="mobile_value">

        <?php if($output['member_info']['member_mobile_bind']==1){?>

              已验证

        <?php }else{?>

            <b>未验证</b>

        <?php  }?>

      </span> <span class="arrow-r"></span>

    </a>

  </li>

  <li>

    <a href="<?php echo urlMobile('member_account','member_email_bind');?>" id="mobile_link">

      <h4>邮箱验证</h4>

      <h6>你修改密码,找回密码时需用到邮箱</h6>

      <span class="tip" id="mail_value">

        <?php if($output['member_info']['member_email_bind']==1){?>

              已验证

        <?php }else{?>

            <b>未验证</b>

        <?php  }?>

      </span> <span class="arrow-r"></span>

    </a>

  </li>

  <li>

    <a href="<?php echo urlMobile('member_bind');?>" id="mobile_link">

      <h4>绑定第三方登录</h4>

      <h6>无需记住本站的账号和密码,即可轻松登录</h6>

      <span class="arrow-r"></span>

    </a>

  </li>

  <li>

    <a href="<?php echo urlMobile('member_account','member_paypwd_step1');?>">

      <h4>支付密码</h4>

      <h6>建议您设置复杂的支付密码保护账户金额安全</h6>

      <span class="tip" id="paypwd_tips">

        <?php if(!empty($output['member_info']['member_paypwd'])){?>

            已设置

        <?php }else{?>

            <b>未设置</b>

        <?php  }?>

      </span> <span class="arrow-r"></span> 

    </a>

  </li>

  </ul>

  <ul class="nctouch-default-list mt5">

    <li><a href="<?php echo urlMobile('member_feedback','index');?>">

      <h4>用户反馈</h4>

      <h6>您在使用中遇到的问题与建议可向我们反馈</h6>

      <span class="arrow-r"></span></a></li>

  </ul>

  <ul class="nctouch-default-list mt5">

    <li><a class="logoutbtn" href="javascript:void(0);">

      <h4>安全退出</h4>

      </a></li>

  </ul>

</div>



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

