<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">



</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"><a href="<?php echo urlMobile();?>"><i class="home"></i></a></div>

    <div class="header-title">

      <h1>会员注册</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="<?php echo urlMobile('login');?>" class="text">登录</a> </div>

  </div>

</header>

<div class="nctouch-main-layout fixed-Width">

  <div class="nctouch-single-nav mb5 register-tab">

    <ul>

      <li class="selected"><a href="javascript: void(0);"><i class="reg"></i>普通注册</a></li>

      <li><a href="<?php echo urlMobile('register','register_mobile');?>"><i class="regm"></i>手机注册</a></li>

    </ul>

  </div>

  <div class="nctouch-inp-con">

    <form action="" method ="">

      <ul class="form-box">

        <li class="form-item">

          <h4>用&nbsp;户&nbsp;名</h4>

          <div class="input-box">

            <input type="text" placeholder="请输入6-20个字符" class="inp" name="username" id="username" oninput="writeClear($(this));"/>

            <span class="input-del"></span></div>

        </li>

        <li class="form-item">

          <h4>设置密码</h4>

          <div class="input-box">

            <input type="password" placeholder="请输入6-20位密码" class="inp" name="pwd" id="userpwd" oninput="writeClear($(this));"/>

            <span class="input-del"></span></div>

        </li>

        <li class="form-item">

          <h4>确认密码</h4>

          <div class="input-box">

            <input type="password" placeholder="请再次输入密码" class="inp" name="password_confirm" id="password_confirm" oninput="writeClear($(this));"/>

            <span class="input-del"></span></div>

        </li>

        <li class="form-item">

          <h4>邮&#12288;&#12288;箱</h4>

          <div class="input-box">

            <input type="email" placeholder="请输入常用邮箱地址" class="inp" name="email" id="email" oninput="writeClear($(this));"/>

            <span class="input-del"></span></div>

        </li>

      </ul>

      <div class="remember-form">

        <input id="checkbox" type="checkbox" checked="" class="checkbox">

        <label for="checkbox">同意</label>

        <a class="reg-cms" id="reg-cms" >用户注册协议</a> </div>

      <div class="error-tips"></div>

      <div class="form-btn"><a href="javascript:void(0);" class="btn" id="registerbtn">注册</a></div>

    </form>

    <input type="hidden" name="referurl">

  </div>

</div>



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/register.js"></script>

<script type="text/javascript">

  $(function(){

    var apurl = "<?php echo urlMobile('register','agreement');?>";

      $("#reg-cms").click(function(){

          var op = layer.open({type:2});

          $.ajax({

            type: "post",

            url: apurl,

            dataType: "json",

            success: function(a) {

                  layer.close(op);

                   var pagei = layer.open({

                      type: 1,

                      title:a.datas.doc_title,

                      content:'<div style="overflow:auto;height:300px;">'+a.datas.doc_content+'</div>' ,

                      shadeClose: false,

                      style: 'width:' + ($(window).width() * 0.8) + 'px; max-height:' + ($(window).height() * 0.8) + 'px;border-radius:5px; border:none;text-align:center;padding:15px;',

                      yes: function(olayer) {

                          var cla = 'getElementsByClassName';

                          olayer[cla]('close')[0].onclick = function() {

                              layer.closeAll();

                        }

                

                }

              })

            }

        })

  })

})

</script>

