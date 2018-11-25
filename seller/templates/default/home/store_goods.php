<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_store.css">

</head>

<body>

<header id="header" class="nctouch-store-header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <a class="header-inp" id="goods_search" href="<?php echo urlMobile('store','store_search',array('store_id'=>$output['store_id']));?>"><i class="icon"></i><span class="search-input">搜索店铺内商品</span></a>

    <div class="header-r"> <a id="store_categroy" href="<?php echo urlMobile('goods_class');?>" class="store-categroy"><i></i>

      <p>分类</p>

    </a> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a></div>

  </div>

   <?php include template('layout/toptip');?>



</header>

<?php require_once template('layout/fiexd');?>



<div class="nctouch-main-layout mt40" id="goodslist_con">

</div>



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script>





<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/ncscroll-load.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script>

<script>

$(function() {

 



  //加载列表页



  $("#goodslist_con").load('<?php echo urlMobile("store","store_goods_list");?>');



});

</script>

