<?php

defined('Inshopec') or exit('Access Invalid!'); ?>



<footer id="footer" >

    <div class="nctouch-footer-wrap posr">

        <div class="nav-text">

            

            <?php if(!empty($_SESSION['is_login'])){?>

            <a href="<?php echo urlMOBILE('member');?>">

                我的商城

            </a>

            <a class ="logoutbtn" href="javascript:void(0);">

                注销

            </a>

            <?php }else{?>

             <a  href="javascript:void(0);">

                登录

            </a>

             <a  href="javascript:void(0);">

                注册

            </a>

            <?php } ?>

            <a href="<?php echo urlMobile('seller_login');?>">

                商家中心

            </a>

            <a href="javascript:void(0);" class="gotop">

                返回顶部

            </a>

        </div>

        <div class="nav-pic">

           <!--  <a href="http://localhost/shop/index.php?con=mb_app" class="app">

                <span>

                    <i>

                    </i>

                </span>

                <p>

                    客户端

                </p>

            </a> -->

            <a href="javascript:void(0);" class="touch">

                <span>

                    <i>

                    </i>

                </span>

                <p>

                    触屏版

                </p>

            </a>

            <a href="http://localhost/shop" class="pc">

                <span>

                    <i>

                    </i>

                </span>

                <p>

                    电脑版

                </p>

            </a>

        </div>

        <div class="copyright">

        	<p>运营单位: <?php echo $output['setting_config']['operating_unit'];?></p>

        	

           <p> <?php echo $output['setting_config']['shopnc_version'];?> <?php echo $output['setting_config']['icp_number']; ?><a href="javascript:void(0);"><?php echo $output['setting_config']['site_name'];?></a>版权所有</p>

        </div>

    </div>

</footer>

</body>



</html>

