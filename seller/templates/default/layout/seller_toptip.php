 <?php defined('Inshopec') or exit('Access Invalid!');?>

 <div class="nctouch-nav-layout">

    <div class="nctouch-nav-menu"> <span class="arrow"></span>

      <ul>

        <li><a href="<?php echo WAP_SITE_URL;?>"><i class="home"></i>首页</a></li>

       

       

        <?php if(!empty($_SESSION['sellerkey'])){?>

           <li><a href="<?php echo urlMobile('seller_center');?>"><i class="member"></i>商家中心</a></li>

           <li><a href="<?php echo urlMobile('seller_message');?>"><i class="message"></i>消息<sup></sup></a></li>

           <li><a href="javascript:void(0);" class="logoutseller"><i class="layout" ></i>注销</a></li>

        <?php }else{?>

           <li><a href="<?php echo urlMobile('join');?>"><i class="register"></i>入驻</a></li>

           <li><a href="<?php echo urlMobile('seller_login');?>"><i class="login"></i>登录</a></li>

        <?php } ?>

        

      </ul>

    </div>

  </div>

