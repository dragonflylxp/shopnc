<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_products_detail.css">



</head>

<body>

<header id="header" class="posf">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <ul class="header-nav">

      <li><a href="javascript:void(0);" id="goodsDetail">商品</a></li>

      <li class="cur"><a href="javascript:void(0);" id="goodsBody">详情</a></li>

      <li><a href="javascript:void(0);" id="goodsdh">记录</a></li>

    </ul>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

  <?php include template('layout/toptip');?>



</header>

<?php require_once template('layout/fiexd');?>



<div class="nctouch-main-layout" id="fixed-tab-pannel">

  <div class="fixed-tab-pannel">

    <?php echo $output['pgoods_body'];?>

  </div>

</div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript">

    $(function(){

     var o= <?php echo $output['pgoods_id'];?>;

         $("#goodsDetail").click(function() {

             window.location.href = ApiUrl + "/index.php?con=points&fun=detail&pgoods_id=" + o

        });

        $("#goodsBody").click(function() {

           window.location.href = ApiUrl + "/index.php?con=points&fun=goods_body&pgoods_id=" + o

        });

         $("body").on("click", "#goodsdh",

        function() {

            window.location.href = ApiUrl + "/index.php?con=points&fun=records&pgoods_id=" + o

        });



    })

</script>

