<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<style type="text/css">

  #kj_ul li{width: 100%}

</style>

</head>

<body>



<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>新浪微博解绑</h1>

    </div>

  <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

   <?php include template('layout/toptip');?>



</header>

<div class="nctouch-main-layout">

 <div class="alert " style="clear:both;">

  <div class="alert">

      <h4>提示信息：</h4>

      <ul>

        <li>您已将本站账号<em>“<?php echo $_SESSION['member_name'];?>”</em>与微博账号<em>“<?php echo $output['member_sinainfo']['name'];?>”</em>绑定</li>

        <li>如果您忘记本站账号<em>“<?php echo $_SESSION['member_name']; ?>”</em>的密码，请重新设置本站登录密码，再确认解除</li>

      </ul>

    </div>

  </div>

    <dl class="mt5">

        

          <ul id="kj_ul" class="mt5 mb5">

            <li><a href="javascript:void(0)"><i class="kj-05"></i><p>解除已绑定账号？</p></a> <span class="jcbtn" type="sina">确认解除</span></li>

           

            

          </ul>

        </dd>

      </dl>

     <div class="nctouch-inp-con">

          <form>

            <ul class="form-box">

                    

                  <li class="form-item">

                    <h4>新&nbsp;密&nbsp;码</h4>

                    <div class="input-box write">

                      <input id="new_password" name="new_password" class="inp" autocomplete="off"  placeholder="输入新密码" oninput="writeClear($(this));" onfocus="writeClear($(this));"  type="password">

                     </div>

                  </li>

                  <li class="form-item">

                    <h4>确认密码</h4>

                    <div class="input-box">

                      <input id="confirm_password" name="confirm_password"   class="inp" autocomplete="off" placeholder="输入确认密码" oninput="writeClear($(this));" type="password">

                    </div>

                  </li>

                </ul>

                <div class="form-btn"><a href="javascript:void(0);" class="btn" id="nextform" type="sina">修改密码并解除</a></div>

           </form>     

          </div>

    </div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/unbind.js"></script> 



