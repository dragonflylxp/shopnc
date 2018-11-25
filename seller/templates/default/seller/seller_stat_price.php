<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/mobiscroll.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/mobiscroll_date.css">





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

      <li><a href="<?php echo urlMobile('seller_stat','goodslist');?>">商品详情</a></li>

      <li class="selected"><a href="javascript:void(0);" >价格销量</a></li>

      <li><a href="<?php echo urlMobile('seller_stat','hotgoods');?>" >热卖商品</a></li>

    </ul>

  </div>

  <div class="alert mt10" style="clear:both;">

  <ul class="mt5">

        <li>1、符合以下任何一种条件的订单即为有效订单：1）采用在线支付方式支付并且已付款；2）采用货到付款方式支付并且交易已完成</li>

        <li>2、“设置价格区间”请在pc端商家中心进行设置，下方统计图将根据您设置的价格区间进行统计</li>

        <li>3、统计图展示符合搜索条件的有效订单中的商品单价，在所设置的价格区间的分布情况</li>

      </ul>

</div>

<form method="get" action="index.php" target="_self">

  <table class="search-form">

    <input type="hidden" name="con" value="seller_stat" />

    <input type="hidden" name="fun" value="price" />

    <tr>

      <td class="tr">

        

      

        

        <div class="fl">商品分类&nbsp;<span id="searchgc_td"></span><input type="hidden" id="choose_gcid" name="choose_gcid" value="0"/></div>

      </td>

    </tr>

    <tr class="tr">

        <td class="fl">

           <div class="fl" style="margin-right:3px;">

            <select name="search_type" id="search_type" class="querySelect">

              <option value="day" <?php echo $output['search_arr']['search_type']=='day'?'selected':''; ?>>按照天统计</option>

              <option value="week" <?php echo $output['search_arr']['search_type']=='week'?'selected':''; ?>>按照周统计</option>

              <option value="month" <?php echo $output['search_arr']['search_type']=='month'?'selected':''; ?>>按照月统计</option>

            </select>

          </div>

        </td>

    </tr>

    <tr>

      <td class="tr">

       <div class="fr">

          <label class="submit-border"><input type="submit" class="submit" value="搜索" /></label>

        </div>

          <div class="fl">

         

            <div id="searchtype_day" style="display:none;" class="fl">

              <input type="text" class="text w70" name="search_time" id="search_time" value="<?php echo @date('Y-m-d',$output['search_arr']['day']['search_time']);?>" /><label class="add-on"><i class="icon-calendar"></i></label>

                </div>

                <div id="searchtype_week" style="display:none;" class="fl">

                    <select name="searchweek_year" class="querySelect">

                      <?php foreach ($output['year_arr'] as $k=>$v){?>

                      <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_year'] == $k?'selected':'';?>><?php echo $v; ?></option>

                      <?php } ?>

                    </select>

                    <select name="searchweek_month" class="querySelect">

                      <?php foreach ($output['month_arr'] as $k=>$v){?>

                      <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_month'] == $k?'selected':'';?>><?php echo $v; ?></option>

                      <?php } ?>

                    </select>

                    <select name="searchweek_week" class="querySelect">

                      <?php foreach ($output['week_arr'] as $k=>$v){?>

                      <option value="<?php echo $v['key'];?>" <?php echo $output['search_arr']['week']['current_week'] == $v['key']?'selected':'';?>><?php echo $v['val']; ?></option>

                      <?php } ?>

                    </select>

              </div>

              <div id="searchtype_month" style="display:none;" class="fl">

                    <select name="searchmonth_year" class="querySelect">

                      <?php foreach ($output['year_arr'] as $k=>$v){?>

                      <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_year'] == $k?'selected':'';?>><?php echo $v; ?></option>

                      <?php } ?>

                    </select>

                    <select name="searchmonth_month" class="querySelect">

                      <?php foreach ($output['month_arr'] as $k=>$v){?>

                      <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_month'] == $k?'selected':'';?>><?php echo $v; ?></option>

                      <?php } ?>

                    </select>

              </div>

        </div>



      </td>

    </tr>

  </table>

</form>



        <?php if ($output['statjson']){ ?>

        <div id="container_pricerange"></div>

        <?php } else { ?>

        <div class="tc h50 mt10" style="text-align:center">查看分布情况前，请先在pc端商家中心设置价格区间。</div>

        <?php }?>



</div>







<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/jquery.js"></script>

<script charset="utf-8" type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/common_select.js" ></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/mobiscroll_date.js"  charset="gb2312"></script>



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/mobiscroll.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/highcharts.js"></script>

<script type="text/javascript">

//展示搜索时间框

function show_searchtime(){

  s_type = $("#search_type").val();

  $("[id^='searchtype_']").hide();

  $("#searchtype_"+s_type).show();

}



$(function(){



  

  //统计数据类型

  var s_type = $("#search_type").val();

  // $('#search_time').datepicker({dateFormat: 'yy-mm-dd'});



  show_searchtime();

  $("#search_type").change(function(){

    show_searchtime();

  });

  

  //更新周数组

  $("[name='searchweek_month']").change(function(){

    var year = $("[name='searchweek_year']").val();

    var month = $("[name='searchweek_month']").val();

    $("[name='searchweek_week']").html('');

    $.getJSON('/wap/index.php?con=seller_stat&fun=getweekofmonth',{y:year,m:month},function(data){

          if(data != null){

            for(var i = 0; i < data.length; i++) {

              $("[name='searchweek_week']").append('<option value="'+data[i].key+'">'+data[i].val+'</option>');

          }

          }

      });

  });

  

  $('#container_pricerange').highcharts(<?php echo $output['statjson'];?>);

  

  //商品分类

  init_gcselect(<?php echo $output['gc_choose_json'];?>,<?php echo $output['gc_json']?>);

});

</script>

<script type="text/javascript">

$(function () {

  var currYear = (new Date()).getFullYear();  

  var opt={};

  opt.date = {preset : 'date'};

  opt.datetime = {preset : 'datetime'};

  opt.time = {preset : 'time'};

  opt.default = {

    theme: 'android-ics light', //皮肤样式

    display: 'modal', //显示方式 

    mode: 'scroller', //日期选择模式

    dateFormat: 'yyyy-mm-dd',

    lang: 'zh',

    showNow: true,

    nowText: "今天",

    startYear: currYear - 50, //开始年份

    endYear: currYear + 10 //结束年份

  };



  $("#search_time").mobiscroll($.extend(opt['date'], opt['default']));



});

</script>