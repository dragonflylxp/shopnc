<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_categroy.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_products_detail.css">

<style type="text/css">

.goods-detail-price{

	padding: 0.1rem 0;

}

.goods-detail-price dt.mk_price{

padding: 0.05rem 0.3rem 0.05rem 0.3rem;

color: #fff;

text-align: left;

border-radius: 3px;

background: #333 ;

position: relative;

font-size: 0.5rem;

margin-left: 0.5rem;

margin-top: 0.2rem;

top: -0.1rem;

}

 .zhuaxiang{

	font: 600 12px/16px Georgia,Arial;

    color: #FFF;

    background-color: #F32613;

    padding: 2px 4px;

    border-radius: 2px;

}

.goods-detail-price dt{

	text-indent: 0.5rem;

}

.goods-detail-price dt.mk_price{

	text-indent: 0;

}

.goods-detail-price dd{

	text-decoration:none;

}

.countdown i {

    font-family: Georgia,Arial;

    font-size: 16px;

    color: #F32613;

}

.goods-detail-foot .buy-handle, .goods-option-foot .buy-handle{

	width: 100%;

}

</style>

</head>

<body>

<header id="header" class="transparent">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <ul class="header-nav">

      <li class="cur"><a href="javascript:void(0);">商品</a></li>

      <li><a href="javascript:void(0);" id="goodsBody">详情</a></li>

      <li><a href="javascript:void(0);" id="goodsdh">记录</a></li>



    </ul>

   <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

   <?php include template('layout/toptip');?>



</header>

<?php require_once template('layout/fiexd');?>



<!-- <div class="pre-loading">

	  <div class="pre-block">

	    <div class="spinner"><i></i></div>

	    商品数据读取中... 

	  </div>

	</div> -->





<div id="product_detail_html" style="position: relative; z-index: 1;"><div class="goods-detail-top">

		<div style="visibility: visible;" class="goods-detail-pic" id="mySwipe">

			<ul style="width: 800px;">

				

				<li data-index="0" style="width: 400px; left: 0px; transition-duration: 0ms; transform: translateX(0px);"><img src="<?php echo $output['prodinfo']['pgoods_image_max']; ?>"></li>

				

			

				

			</ul>

		</div>

		<!-- <div class="goods-detail-turn">

			<ul>

				<li class="cur"></li>

				

				<li class=""></li>

				

			</ul>

		</div> -->



</div>



<div class="goods-detail-cnt">

	<div class="goods-detail-name">

		<dl> 

			<dt><?php echo $output['prodinfo']['pgoods_name'];?></dt>

			<dd><?php echo $output['prodinfo']['pgoods_description'];?></dd>

		</dl>

	</div>

	<div class="goods-detail-price">

		

			<dl>

				<dt><em><?php echo $output['prodinfo']['pgoods_points']; ?><?php echo $lang['points_unit']; ?></em></dt>

				<dt class="mk_price"><?php echo $lang['currency'].ncPriceFormat($output['prodinfo']['pgoods_price']); ?></dt>

				 <?php if ($output['prodinfo']['pgoods_limitmgrade']){ ?>

	            <span class="zhuaxiang"><?php echo $output['prodinfo']['pgoods_limitgradename'].'专享'; ?></span>

	            <?php } ?>

			</dl>

		

		<span class="sold">剩余：<?php echo $output['prodinfo']['pgoods_storage']; ?>件</span>



	</div>

	 <?php if ($output['prodinfo']['pgoods_islimittime'] == 1){ ?>

	<div class="goods-detail-price">

	 

        <dl>

          <dt>兑换时间:</dt>

          <dd>

            <?php if ($output['prodinfo']['pgoods_starttime'] && $output['prodinfo']['pgoods_endtime']){

              		echo @date('Y-m-d H:i:s',$output['prodinfo']['pgoods_starttime']).'&nbsp;至&nbsp;'.@date('Y-m-d H:i:s',$output['prodinfo']['pgoods_endtime']);

              	}?>

          </dd>

        </dl>

    

      

	</div>

	<div class="goods-detail-price">

    <?php if ($output['prodinfo']['ex_state'] == 'going' && $output['prodinfo']['pgoods_islimittime']==1){?>

        <dl>

          <dt>&nbsp;</dt>

          <dd class="countdown">剩余:&nbsp;&nbsp;<i id="dhpd"><?php echo $output['prodinfo']['timediff']['diff_day']; ?></i> 天 <i id="dhph"><?php echo $output['prodinfo']['timediff']['diff_hour']; ?></i> 时 <i id="dhpm"><?php echo $output['prodinfo']['timediff']['diff_mins']; ?></i> 分 <i id="dhps"><?php echo $output['prodinfo']['timediff']['diff_secs']; ?></i> 秒 </dd>

        </dl>

        <?php }?>

    </div>

      <?php } ?>

	<div class="goods-detail-recom">

		<h4>热门礼品</h4>



		<?php if (is_array($output['recommend_pointsprod']) && count($output['recommend_pointsprod'])>0){?>

		<ul>

		 <?php foreach ($output['recommend_pointsprod'] as $k=>$v){?>

			<li>

				<a target="_blank" href="<?php echo urlMobile('points', 'detail', array('pgoods_id' => $v['pgoods_id']));?>" title="<?php echo $v['pgoods_name']; ?>"> 

					<div class="pic"><img src="<?php echo $v['pgoods_image'] ?>" alt="<?php echo $v['pgoods_name']; ?>" /> </div>

					<dl>

						<dt><?php echo $v['pgoods_name']; ?></dt>

						<dd><em><?php echo $v['pgoods_points']; ?><?php echo $lang['points_unit']; ?><?php if (intval($v['pgoods_limitmgrade']) > 0){ ?> <span class="zhuaxiang"><?php echo $v['pgoods_limitgradename']; ?>专享</span><?php } ?></em></dd>

					</dl>

				</a>

			</li>

		<?php } ?>

		</ul>

		<?php }else{?>

          <div class="norecord">暂时没有推荐...</div>

       <?php }?>

	</div>

	<div class="goods-detail-bottom"><a href="javascript:void(0);" id="goodsBody1">点击查看商品详情</a></div>

	<div class="goods-detail-foot">

	

		<div class="buy-handle " style="width:100%">

			<a href="javascript:void(0);" class="animation-up buy-now buy-now1" >立即兑换</a>

		</div>

	</div>

</div>

</div>

<div id="voucher_html" class="nctouch-bottom-mask"></div>

<div id="product_detail_spec_html" class="nctouch-bottom-mask "><div class="nctouch-bottom-mask-bg"></div>

<div class="nctouch-bottom-mask-block">

	<div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>

	<div class="nctouch-bottom-mask-top goods-options-info">

		<div class="goods-pic">

		<img src="<?php echo $output['prodinfo']['pgoods_image_max']; ?>">

	</div>

	<dl>

		<dt><?php echo $output['prodinfo']['pgoods_name'];?></dt>

		<dd class="goods-price">

		<em><?php echo $output['prodinfo']['pgoods_points']; ?><?php echo $lang['points_unit']; ?></em>

		<span style="color: #333;text-decoration:line-through "><?php echo  $lang['currency'];?><?php echo ncPriceFormat($output['prodinfo']['pgoods_price']); ?></span>

		<span class="goods-storage">库存：<span class="kcnum"><?php echo $output['prodinfo']['pgoods_storage']; ?></span>件</span>

		</dd>

	</dl>

	<a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a>

</div>

<div class="nctouch-bottom-mask-rolling" id="product_roll">

	<div style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);" class="goods-options-stock">

		

		

		

		

		

	</div>

</div>

<div class="goods-option-value">购买数量

	<div class="value-box">

		<span class="minus">

			<a href="javascript:void(0);">&nbsp;</a>

		</span>

		<span>

			<input pattern="[0-9]*" class="buy-num" id="buynum" value="1" type="text">

		</span>

		<span class="add">

			<a href="javascript:void(0);">&nbsp;</a>

		</span>

	</div>

</div>

<div class="goods-option-foot">



	<div class="buy-handle ">

		

	

		

		<a href="javascript:void(0);" class="buy-now2 buy-now" id="buy-now" pgoods_islimit="<?php echo $output['prodinfo']['pgoods_islimit']; ?>" pgoods_limitnum="<?php echo $output['prodinfo']['pgoods_islimit']; ?>" pgoods_storage="<?php echo $output['prodinfo']['pgoods_storage']; ?>" pgoods_id="<?php echo $output['prodinfo']['pgoods_id']; ?>">立即购买</a>

	</div>

</div></div></div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/swipe.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/simple-plugin.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/points_detail.js"></script>



<?php if ($output['prodinfo']['ex_state'] == 'going' && $output['prodinfo']['pgoods_islimittime']==1 ){?>

<script type="text/javascript">

function pendingzero(str)

{

   var result=str+"";

   if(str<10)

   {

       result="0"+str;

   }

   return result;

}

function GetRTime2() //积分礼品兑换倒计时

{

   var rtimer=null;

   var startTime = new Date();

   var EndTime = 1492099200000;

   var NowTime = new Date();

   var nMS =EndTime - NowTime.getTime();

   if(nMS>0)

   {

       var nD=Math.floor(nMS/(1000*60*60*24));

       var nH=Math.floor(nMS/(1000*60*60)) % 24;

       var nM=Math.floor(nMS/(1000*60)) % 60;

       var nS=Math.floor(nMS/1000) % 60;

       document.getElementById("dhpd").innerHTML=pendingzero(nD);

       document.getElementById("dhph").innerHTML=pendingzero(nH);

       document.getElementById("dhpm").innerHTML=pendingzero(nM);

       document.getElementById("dhps").innerHTML=pendingzero(nS);

       if(nS==0&&nH==0&&nM==0)

       {

          // document.getElementById("returntime").style.display='none';

           clearTimeout(rtimer2);

           history.go(-1);

           return;

       }

       rtimer2=setTimeout("GetRTime2()",1000);

   }

}



GetRTime2();

</script>

<?php }?>

