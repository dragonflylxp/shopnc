<?php defined('Inshopec') or exit('Access Invalid!');?> 

<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />

<meta http-equiv="cache-control" content="no-cache" />

<style>

    .success { width: 100%; text-align: center; padding: 200px 0 10px 0; color: green; }

    .fail { width: 100%; text-align: center; padding: 200px 0 10px 0; color: red; }

    .return { width: 100%; text-align: center; }

</style>



<div class="<?php echo $output['result'];?>" >

<?php echo $output['message'];?>

</div>

<div class="return" >

    <a href=""><img src="<?php echo MOBILE_TEMPLATES_URL;?>/images/pay_ok.png"></a>

</div>

 -->



 <?php defined( 'Inshopec') or exit( 'Access Invalid!');?>



<style type="text/css">

body{

  background: #fff;

}

.left{

  float: left;

}

.right{

  float: right;

}

.ncc-receipt-info {

  background: #fff;

  margin-top: 5rem;

}

.ncc-finish-a{

  height: 5rem;

  width: 11rem;

  margin: 0 auto;



}

.ncc-finish-a em {

    font: bold 18px/32px Verdana, Geneva, sans-serif;

    color: #FF3300;

    margin: 0 4px;

}

.all-points {



    color: #52A452;

    margin: 0 4px;

    width: 100%;

    height: 2rem;

    line-height: 2rem;

    font-size: 1rem;

    display: inline-block;

    text-align: center;

}

.ncc-finish-a i{

  width: 5rem;

  height: 5rem;

  background: url(<?php echo MOBILE_TEMPLATES_URL;?>/images/payok.png) no-repeat center center;

  background-size: 50% 50%;

  float: left;

  display: inline-block;

}

.ncc-finish-a i.error{

	 background: url(<?php echo MOBILE_TEMPLATES_URL;?>/images/payerror.png) no-repeat center center;

	  background-size: 50% 50%;

}

.ncc-finish-a span{

  width:6rem;

  line-height: 5rem;

  text-decoration: center;

  float: left;

  display: inline-block;

}

.ncc-finish-b{

  font-size: 0.65rem;

  height: 2rem;

  line-height: 2rem;

  text-align: center;

  padding-bottom:1rem;



}

.ncc-finish-b a{

	color: #06f;

}

.ncbtn-mini{

  color: #fff;

  background: #1dccaa;

  border-radius: 3px;

  width: 100%;

  height: 35px;

  margin: 10px;

  font-size: 0.7rem;

  text-align: center;

  line-height: 35px;

  display: inline-block;

}

.ncbtn-mint{

  display: inline-block;

  width: 40%;

  background: rgb(228, 77, 77) none repeat scroll 0% 0%;

  color: rgb(255, 255, 255);

}

.ncbtn-aqua{

  width: 40%;

}

</style>



</head>



<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

    <div class="header-title">

      <h1>支付结果</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

    <?php include template('layout/toptip');?>

</header>

<script>window.demo.checkPaymentAndroid("<?php echo $output['result'];?>");</script>

<div class="nctouch-main-layout fixed-Width">

<div class="nctouch-home-block  mt20">

  <div class="ncc-receipt-info ">

      <div class="ncc-finish-a"><i <?php if($output['result'] !='success'){?>class="error"<?php }?>></i><span><?php echo $output['message'];?></span>  </div>

      <div class="ncc-finish-b">可通过用户中心<a href="<?php echo urlMobile('member_order');?>">我的订单</a>查看订单状态。 </div>

      <div class="ncc-finish-c mb30"> 

      <a class="ncbtn-mini ncbtn-mint mr15 left" href="<?php echo MOBILE_SITE_URL?>"><i class="icon-shopping-cart"></i>继续购物</a> 

      <a class="ncbtn-mini ncbtn-aqua right" href="<?php echo urlMobile('member_order');?>"><i class="icon-file-text-alt"></i>查看详单</a>

      </div>

    </div>

</div>

 

  </div>



</body>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 