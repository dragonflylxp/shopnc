 <?php defined('Inshopec') or exit('Access Invalid!');?>

 <div class="nctouch-nav-layout">

    <div class="nctouch-nav-menu"> <span class="arrow"></span>

      <ul>

        <li><a href="./../wap"><i class="home"></i>首页</a></li>

        <li><a href="./../wap/tmpl/search.html"><i class="search"></i>搜索</a></li>

        <li><a href="./../wap/tmpl/product_first_categroy.html"><i class="categroy"></i>分类</a></li>

        <li><a href="./../wap/tmpl/cart_list.html"><i class="cart"></i>购物车<sup></sup></a></li>

        <?php if(!empty($_SESSION['is_login'])){?>

           <li><a href="./../wap/tmpl/member/member.html"><i class="member"></i>我的商城</a></li>

           
        <?php }else{?>

           <li><a href="./../wap/tmpl/member/register.html"><i class="register"></i>注册</a></li>

           <li><a href="./../wap/tmpl/member/login.html"><i class="login"></i>登录</a></li>

        <?php } ?>

        

      </ul>

    </div>

  </div>