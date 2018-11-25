<?php defined('Inshopec') or exit('Access Invalid!');?>

	
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<style type="text/css">

  .w20h li {

    width: 33.3%;

}

</style>

</head>

<body>



<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>店铺统计</h1>

    </div>

  <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

   <?php include template('layout/seller_toptip');?>



</header>

<div class="nctouch-main-layout">

  <div id="fixed_nav" class="nctouch-single-nav">

    <ul id="filtrate_ul" class="w20h">

      <li class="selected"><a href="javascript:void(0);" >商品详情</a></li>

      <li><a href="<?php echo urlMobile('seller_stat','price');?>" >价格销量</a></li>

      <li><a href="<?php echo urlMobile('seller_stat','hotgoods');?>" >热卖商品</a></li>

    </ul>

  </div>

  <div class="alert " style="clear:both;">

  <ul class="mt5">

    <li>1、符合以下任何一种条件的订单即为有效订单：1）采用在线支付方式支付并且已付款；2）采用货到付款方式支付并且交易已完成</li>

    <li>2、以下列表为从昨天开始最近7天有效订单中的所有商品数据</li>

        <li>3、近7天下单商品数：从昨天开始最近7天有效订单的某商品总销量</li>

        <li>4、近7天下单金额：从昨天开始最近7天有效订单的某商品总销售额</li>

        <li>5、点击每条记录，查看最近7天下单金额、下单商品数、下单量走势</li>

      </ul>

</div>

<form method="get" action="index.php" target="_self" id="formSearch" >

  <table class="search-form mb5">

    <input type="hidden" name="con" value="seller_stat" />

    <input type="hidden" name="fun" value="goodslist" />

    <input type="hidden" id="orderby" name="orderby" value="<?php echo $output['orderby'];?>"/>

    <tr class="tr">

      <td class="fr">

         <div class="fr">商品分类&nbsp;<span id="searchgc_td"></span><input type="hidden" id="choose_gcid" name="choose_gcid" value="0"/></div>

      </td>

    </tr>

    <tr>

      <td class="tr">

        <div class="fr">&nbsp;&nbsp;商品名称

          <input type="text" class="text w150" name="search_gname" value="<?php echo $_GET['search_gname']; ?>" />

          <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_common_search'];?>" /></label>

        </div>

       

      </td>

    </tr>

  </table>

</form>

    <?php if (!empty($output['goodslist']) && is_array($output['goodslist'])) { ?>

    <?php foreach($output['goodslist'] as $v) { ?>

      <dl class="goods_info_list cur" nc_type='showdata' data-param='{"gid":"<?php echo $v['goods_id'];?>"}'>

        <dt><img src="<?php echo thumb($v, 60);?>"/></dt>

        <dd><?php echo $v['goods_name'];?></dd>

        <dd>￥<?php echo ncPriceFormat($v['goods_price']);?>*<?php echo $v['ordergoodsnum'];?></dd>

        <dd class="red">￥<?php echo $v['ordergamount'];?></dd>

      </dl>

    <?php }?>

    <?php } else {?>

      <div class="warning-option">暂时没有数据...</div>

    <?php }?>



<?php if (!empty($output['goodslist']) && is_array($output['goodslist'])) { ?>

  <div class="pagination"><?php echo $output['show_page']; ?></div>

 

<?php } ?>



<table class="ncsc-default-table">

  <tbody>

      <tr>

        <div id="goodsinfo_div" class="close_float" ></div>

      </tr>

  </tbody>

</table>

</div>







<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/jquery.js"></script>

<script charset="utf-8" type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/common_select.js" ></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/highcharts.js"></script>

<script type="text/javascript">

$(function(){

   $("#header").on("click", "#header-nav",

    function() {

        if ($(".nctouch-nav-layout").hasClass("show")) {

            $(".nctouch-nav-layout").removeClass("show")

        } else {

            $(".nctouch-nav-layout").addClass("show")

        }

    });

    //商品分类

    init_gcselect(<?php echo $output['gc_choose_json'];?>,<?php echo $output['gc_json']?>);

    //加载商品详情

    <?php if (!empty($output['goodslist']) && is_array($output['goodslist'])) { ?>

    getStatdata(<?php echo $output['goodslist'][0]['goods_id'];?>);

    <?php }?>

    $("[nc_type='showdata']").click(function(){

        var data_str = $(this).attr('data-param');

        eval('data_str = '+data_str);

        $(this).addClass('goods_info_list_cur').siblings().removeClass('goods_info_list_cur')

        getStatdata(data_str.gid);

    });

    //排序

    $("[nc_type='orderitem']").click(function(){

      var data_str = $(this).attr('data-param');

      eval( "data_str = "+data_str);

        if($(this).hasClass('desc')){

          $("#orderby").val(data_str.orderby + ' asc');

        } else {

          $("#orderby").val(data_str.orderby + ' desc');

        }

        $('#formSearch').submit();

    });

});

function getStatdata(gid){

  $('#goodsinfo_div').load('/wap/index.php?con=seller_stat&fun=goodsinfo&gid='+gid);

}

</script>