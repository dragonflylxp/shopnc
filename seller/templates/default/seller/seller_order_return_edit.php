<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<style type="text/css">

#saleRefundReturn{

	text-align: center;

}

.input-box label{

	line-height: 1rem !important;

	height: 1rem !important;

}

.feedback textarea{

	border:1px solid #eee;

}

</style>

</head>

<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>退货记录</h1>

    </div>

	<div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

   </div>

   <?php include template('layout/seller_toptip');?>





</header>

<div class="nctouch-main-layout mb20">



  <div class="nctouch-order-list" id="order-info-container">

  <h3 class="nctouch-default-list-tit">退货退款服务</h3>

  	<div class="nctouch-order-item " id="saleRefundReturn">

  	 <div class="ncsc-flow-step">

        <dl class="step-first current">

          <dt>买家申请</dt>

          <dd class="bg"></dd>

        </dl>

        <dl class="<?php echo $output['return']['seller_time'] > 0 ? 'current':'';?>">

          <dt>商家处理</dt>

          <dd class="bg"> </dd>

        </dl>

        <dl class="<?php echo ($output['return']['ship_time'] > 0 || $output['return']['return_type']==1) ? 'current':'';?>">

          <dt>买家退货</dt>

          <dd class="bg"> </dd>

        </dl>

        <dl class="<?php echo $output['return']['admin_time'] > 0 ? 'current':'';?>">

          <dt>平台审核</dt>

          <dd class="bg"> </dd>

        </dl>

      </div>

  	

  	</div>

  	   	<div class="nctouch-order-item mt5">

		<div class="nctouch-order-item-head">

			

			<a class="store"><i class="icon"></i><?php echo $output['order']['order_sn'];?></a>

			

		</div>

		<div class="nctouch-order-item-con">

		<?php if (is_array($output['goods_list']) && !empty($output['goods_list'])) { ?>

        <?php foreach ($output['goods_list'] as $key => $val) { ?>	

			<div class="goods-block detail">

				<a href="<?php echo urlMobile('goods','detail',array('goods_id'=> $val['goods_id'])); ?>">

				<div class="goods-pic">

					<img src="<?php echo thumb($val,240);?>">

				</div>

				<dl class="goods-info">

					<dt class="goods-name"><?php echo $val['goods_name']; ?></dt>

					<dd class="goods-type"></dd>

				</dl>

				<div class="goods-subtotal">

					<span class="goods-price">￥<em><?php echo ncPriceFormat($val['goods_price']); ?></em></span>

					<span class="goods-num">x<?php echo $val['goods_num']; ?></span>

					<span class="goods-type" ><?php echo orderGoodsType($val['goods_type']);?></span>

				</div>

				

			</a>

			</div>

		<?php } ?>

        <?php } ?>	

			<div class="goods-subtotle">

				<dl>

					<dt>收货人</dt>

					<dd><?php echo $output['order_common']['reciver_name'];?></dd>

				</dl>	

				<dl>

					<dt>运费</dt>

					<dd><?php echo $output['order']['shipping_fee']>0 ? ncPriceFormat($output['order']['shipping_fee']):'免运费';?></dd>

				</dl>

				<dl class="t">

					<dt>订单总额</dt>

					<dd>￥<em><?php echo ncPriceFormat($output['order']['order_amount']); ?></em>   

					<?php if ($output['order']['refund_amount'] > 0) { ?>

			        (<?php echo '退款:￥'.$output['order']['refund_amount'];?>)

			        <?php } ?>

			        </dd>

				</dl>

			</div>

		</div>

	

		<div class="nctouch-oredr-detail-block border_top">

		<div class="nctouch-oredr-detail-add">

			<i class="icon-add"></i>

			<dl>

        		<dt>收货人：<span><?php echo $output['order_common']['reciver_name'];?></span><span><?php echo $output['order_common']['reciver_info']['phone'];?></span></dt>

				<dd>收货地址：<?php echo $output['order_common']['reciver_info']['address'];?></dd>

			</dl>

		</div>

		</div>

		<div class="nctouch-oredr-detail-block mt5">

		<ul class="order-log">

			<li>

				

			<?php if($output['order']['payment_code'] != 'offline' && !in_array($output['order']['order_state'],array(ORDER_STATE_CANCEL,ORDER_STATE_NEW))) { ?>

            		<?php echo '付款单号：';?><span><?php echo $output['order']['pay_sn']; ?></span>

            <?php } ?>

			</li>

			<li>

				<?php echo '付款方式：';?><span><?php echo $output['order']['payment_name']; ?></span>

			</li>

			 <?php if ($output['order']['payment_time'] > 0) { ?>

            <li><?php echo  '下单时间：';?><span><?php echo date("Y-m-d H:i:s",$output['order']['payment_time']); ?></span></li>

            <?php } ?>

			 <?php if ($output['order_common']['shipping_time'] > 0) { ?>

            <li><?php echo  '付款时间：';?><span><?php echo date("Y-m-d H:i:s",$output['order_common']['shipping_time']); ?></span></li>

            <?php } ?>

			<?php if ($output['order']['finnshed_time'] > 0) { ?>

            <li><?php echo '完成时间：';?><span><?php echo date("Y-m-d H:i:s",$output['order']['finnshed_time']); ?></span></li>

            <?php } ?>

		</ul>

	</div>

		

	</div>

<h3 class="nctouch-default-list-tit">买家退货退款申请</h3>

  	<ul class="nctouch-default-list">

	  <li>

	    <h4>退货退款编号</h4>

	    <span class="num"><?php echo $output['return']['refund_sn']; ?></span> 

	  </li>

	  <li>

	    <h4>申请人（买家）</h4>

	    <span class="num"><?php echo $output['return']['buyer_name']; ?></span>

	  </li>

	  <li>

	    <h4>退货原因</h4>

	    <span class="num"><?php echo $output['return']['reason_info']; ?></span>

	  </li>

	  <li>

	    <h4>退款金额</h4>

	    <span class="num">￥<?php echo $output['return']['refund_amount']; ?></span>

	  </li>

	   <li>

	    <h4>退货数量</h4>

	    <span class="num"><?php echo $output['return']['return_type']==2 ? $output['return']['goods_num']:'无'; ?></span>

	  </li>

	  <li>

	    <h4>退款说明</h4>

	    <span class="num"><?php echo $output['return']['buyer_message']; ?></span>

	  </li>

	  <li>

	    <h4>凭证上传</h4>

	    <span class="pics">

	    

	  <?php if (is_array($output['pic_list']) && !empty($output['pic_list'])) { ?>

          <ul class="ncsc-evidence-pic">

            <?php foreach ($output['pic_list'] as $key => $val) { ?>

            <?php if(!empty($val)){ ?>

             <img class="show_image" src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/'.$val;?>">

            <?php } ?>

            <?php } ?>

          </ul>

          <?php } ?>

	    

	    </span></li>

	</ul>



<h3 class="nctouch-default-list-tit">商家处理意见</h3>

 <form id="post_form" method="post" action="<?php echo urlMobile('seller_order_return','edit',array('return_id'=>$output['return']['refund_id']));?>">

<input type="hidden" name="form_submit" value="ok" />

<div class="alert">

    <h4>注意事项：</h4>

    1.如果选择弃货，买家将不用退回原商品，提交后直接由管理员确认退款。</br>

    2.如是同意退货，请及时关注买家的发货情况，并进行收货（发货5天后可以选择未收到，超过7天不处理按弃货处理）。

</div>

  	<ul class="nctouch-default-list">

	  <li>

	    <h4>是否同意</h4>

	    <span class="num">

	    <div class=" input-box">

		  <label class="">

		    <input class="checkbox" id="seller_state" autocomplete="off" type="checkbox" name="seller_state">

		    <span class="power"><i></i></span> 

		  </label>

		  

		</div>

		</span> 

	  </li>

	  <li class="feedback">

	    <h4>是否弃货</h4>

	    <span class="num">

	    <div class=" input-box">

		  <label class="">

		    <input class="checkbox" id="return_type" autocomplete="off" type="checkbox" name="return_type">

		    <span class="power"><i></i></span> 

		  </label>

		</div>

		</span>

	  </li>

	  <li class="feedback">

	    <h4>备注信息</h4>

	    <textarea name="seller_message"><?php echo $output['refund']['seller_message']; ?></textarea>

	  </li>

	

	</ul>

  <a class="btn-l mt5 mb5" id="confirm_button" href="javascript:void(0)">确定</a>

</form>

</div>

</div>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script>

$(function(){

	  $("#confirm_button").click(function(){

        $("#post_form").submit();

    });

})	

</script>