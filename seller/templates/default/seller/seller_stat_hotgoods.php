<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/mobiscroll.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/mobiscroll_date.css">

<style type="text/css">

  .w20h li {

    width: 33.3%;

}



/* 本例子css -------------------------------------- */

.tabBox{ margin:0 auto;   }

.tabBox .hd{ height:40px; line-height:40px; font-size:0.7rem; overflow:hidden;border-bottom: solid 0.05rem #EEE; padding:0 10px;  }

.tabBox .hd h3 span{color:#ccc; font-family:Georgia; margin-left:10px;  }

.tabBox .hd ul{ float:left;  }

.tabBox .hd ul li{ float:left;  padding:0 15px; vertical-align:top;  }

.tabBox .hd ul li.on a{ color:#e44d4d; display:block; height:38px; line-height:38px;   border-bottom:2px solid #e44d4d;  }

.tabBox .bd ul{ padding:10px;  }

.tabBox .bd ul li{ border-bottom:1px dotted #ddd;  }

.tabBox .bd li a{ -webkit-tap-highlight-color:rgba(0,0,0,0); }  /* 去掉链接触摸高亮 */

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

      <li><a href="<?php echo urlMobile('seller_stat','price');?>" >价格销量</a></li>

      <li class="selected"><a href="javascript:void(0);" >热卖商品</a></li>

    </ul>

  </div>

  <div class="alert " style="clear:both;">

  <ul class="mt5">

      <li>1、符合以下任何一种条件的订单即为有效订单：1）采用在线支付方式支付并且已付款；2）采用货到付款方式支付并且交易已完成</li>

      <li>2、图表展示了符合搜索条件的有效订单中的下单总金额和下单商品总数排名前7位的商品</li>

  </ul>

</div>



<form method="get" action="index.php" target="_self">

  <table class="search-form">

    <input type="hidden" name="con" value="seller_stat" />

    <input type="hidden" name="fun" value="hotgoods" />

    <tr>

      <td class="tr">

        <div class="fr">

          <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_common_search'];?>" /></label>

        </div>

        <div class="fr">

          <div class="fl" style="margin-right:3px;">

            <select name="search_type" id="search_type" class="querySelect">

              <option value="day" <?php echo $output['search_arr']['search_type']=='day'?'selected':''; ?>>按照天统计</option>

              <option value="week" <?php echo $output['search_arr']['search_type']=='week'?'selected':''; ?>>按照周统计</option>

              <option value="month" <?php echo $output['search_arr']['search_type']=='month'?'selected':''; ?>>按照月统计</option>

            </select>

            </div>

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

<div class="nctouch-oredr-detail-block ">

  <!-- Tab切换（高度自适应示范） -->

      <div id="tabBox1" class="tabBox">

        <div class="hd">

          

          <ul>

            <li><a href="javascript:void(0)">下单金额</a></li>

            <li><a href="javascript:void(0)">下单商品数</a></li>

          </ul>

        </div>

        <div class="bd" id="tabBox1-bd"><!-- 添加id，js用到 -->

          <div class="con"><!-- 高度自适应需添加外层 -->

     

              <div id="container_ordergamount"></div>

                 

              <div>

                <table class="ncsc-default-table">

                      <thead>

                        <tr class="sortbar-array">

                          <th style="width:10%">序号</th>

                          <th>商品名称</th>

                          <th>金额</th>

                        </tr>

                      </thead>

                      <tbody>

                        <?php if (!empty($output['statlist']['orderamount']) && is_array($output['statlist']['orderamount'])) { ?>

                        <?php foreach($output['statlist']['orderamount'] as $k=>$v) { ?>

                        <tr class="bd-line">

                          <td><?php echo $k+1; ?></td>

                          <td class="tl"><span class="  h20"><a href="<?php echo urlMobile('goods', 'index', array('goods_id' => $v['goods_id']));?>" target="_blank"><?php echo str_cut($v['goods_name'],36,'...');?></a></span></td>

                          <td><?php echo $v['orderamount'];?></td>

                        </tr>

                        <?php }?>

                        <?php } else { ?>

                        <tr>

                          <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span>暂无数据...</span></div></td>

                        </tr>

                        <?php } ?>

                      </tbody>

                    </table>

              </div>

                    

                

            

          </div>

          <div class="con"><!-- 高度自适应需添加外层 -->

                <div id="container_goodsnum"></div>

                  <div>

                  <table class="ncsc-default-table">

                        <thead>

                          <tr class="sortbar-array">

                            <th style="width:10%">序号</th>

                            <th>商品名称</th>

                            <th>商品数</th>

                          </tr>

                        </thead>

                        <tbody>

                          <?php if (!empty($output['statlist']['goodsnum']) && is_array($output['statlist']['goodsnum'])) { ?>

                          <?php foreach($output['statlist']['goodsnum'] as $k=>$v) { ?>

                          <tr class="bd-line">

                            <td><?php echo $k+1; ?></td>

                            <td class="tl"><span class="h20"><a href="<?php echo urlMobile('goods', 'index', array('goods_id' => $v['goods_id']));?>" target="_blank"><?php echo str_cut($v['goods_name'],36,'...');?></a></span></td>

                            <td><?php echo $v['goodsnum'];?></td>

                          </tr>

                          <?php }?>

                          <?php } else { ?>

                          <tr>

                            <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span>暂无数据...</span></div></td>

                          </tr>

                          <?php } ?>

                        </tbody>

                      </table>

                </div>

             

  

          </div>

          



        </div>

      </div>

  

    </div>

</div>







<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/jquery.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/TouchSlide.1.1.js"></script> 

<script charset="utf-8" type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/common_select.js" ></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/mobiscroll_date.js"  charset="gb2312"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/mobiscroll.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/highcharts.js"></script>

<script type="text/javascript">







function show_searchtime(){

  s_type = $("#search_type").val();

  $("[id^='searchtype_']").hide();

  $("#searchtype_"+s_type).show();

}



$(function(){

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

  

  TouchSlide( { slideCell:"#tabBox1"});

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



  $('#container_goodsnum').highcharts(<?php echo $output['stat_json']['goodsnum'];?>);

  $('#container_ordergamount').highcharts(<?php echo $output['stat_json']['orderamount'];?>);

});

</script>