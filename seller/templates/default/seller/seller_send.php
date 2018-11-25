<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

<style type="text/css">

/* 本例子css -------------------------------------- */

	.tabBox{ margin:0auto;   }

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

      <h1>订单详情</h1>

    </div>

	<div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

   </div>

   <?php include template('layout/seller_toptip');?>





</header>

<div class="nctouch-main-layout mb20">



  <div class="nctouch-order-list" id="order-info-container">

  <div class="nctouch-order-list" id="order-info-container">

  <div class="nctouch-oredr-detail-block">

		<h3><b style="color:orange">第一步</b> 确认收货信息及交易详情</h3>

	</div>

	

	<div class="nctouch-order-item">

		<div class="nctouch-order-item-head">

			

			<a class="store"><i class="icon"></i><?php echo $output['order_info']['order_sn']; ?></a>

			<span class="state">

				<span class="ot-cancel"><?php echo date("y-m-d H:i:s",$output['order_info']['add_time']); ?></span>

			</span>



		</div>

		<div class="nctouch-order-item-con">

			  <?php foreach($output['order_info']['extend_order_goods'] as $k => $goods_info) { ?>

			<div class="goods-block detail">

				<a href="<?php echo urlMobile('goods','detail',array('goods_id'=>$goods_info['goods_id']));?>">

				<div class="goods-pic">

					<img src="<?php echo cthumb($goods_info['goods_image'],'60',$output['order_info']['store_id']); ?>">

				</div>

				<dl class="goods-info">

					<dt class="goods-name"><?php echo $goods_info['goods_name']; ?></dt>

					<dd class="goods-type"><?php echo $goods_info['goods_spec']; ?></dd>

				</dl>

				<div class="goods-subtotal">

					<span class="goods-price">￥<em><?php echo ncPriceFormat($goods_info['goods_price']); ?></em></span>

					<span class="goods-num">x<?php echo $goods_info['goods_num'];?></span>

				</div>

				

			</a>

			</div>

			<?php }?>

			<div class="goods-subtotle">

				

				<dl>

					<dt>运费</dt>

					<dd>￥<em>

					<?php if (!empty($output['order_info']['shipping_fee']) && $output['order_info']['shipping_fee'] != '0.00'){?>

	                <?php echo $output['order_info']['shipping_fee'];?>

	                <?php }else{?>

	                (免运费)

	                <?php }?>

					</em></dd>

				</dl>

				<dl class="t">

					<dt>实付款（含运费）</dt>

					<dd>￥<em> <?php echo $output['order_info']['order_amount'];?></em></dd>

				</dl>

			</div>

		</div>

		

	</div>

	<div class="nctouch-oredr-detail-block ">

		<div class="nctouch-oredr-detail-add">

			<i class="icon-add"></i>

			<dl>

        		<dt>收货人：<span><?php echo $output['order_info']['extend_order_common']['reciver_name'];?></span><span><?php echo $output['order_info']['extend_order_common']['reciver_info']['phone'];?></span></dt>

				<dd>收货地址：<?php echo $output['order_info']['extend_order_common']['reciver_info']['address'];?> <?php echo $output['order_info']['extend_order_common']['reciver_info']['dlyp'] ? '[自提服务站]' : '';?></dd>

			</dl>

			<div class="icon_edit" id="shr_edit" order_id="<?php echo $output['order_info']['order_id']; ?>"><i></i>编辑</div>

		</div>

	</div>





	<div class="nctouch-oredr-detail-block ">

		<div class="message">

			<input placeholder="发货备忘(仅卖家自己可见)：" id="deliver_explain" name="deliver_explain" type="text" value="<?php echo $output['order_info']['extend_order_common']['deliver_explain'];?>">

		</div>

	</div>

	

	<!--确认发货信息  -->

	 <div class="nctouch-oredr-detail-block mt5">

		<h3><b style="color:orange">第二步</b> 确认发货信息</h3>

	</div>

	<div class="nctouch-oredr-detail-block ">

		<div class="nctouch-oredr-detail-add">

			<i class="icon-add"></i>

			

			   <?php if (empty($output['daddress_info'])) {?>

			     	<dl>还未设置发货地址，请进入发货设置 > 地址库中添加(pc端设置)</dl>

			      <?php } else { ?>

					<dl>

		        		<dt>发货人：<span><?php echo $output['daddress_info']['seller_name'];?></span><span><?php echo $output['daddress_info']['telphone'];?></span></dt>

						<dd>发货地址：<?php echo $output['daddress_info']['area_info'];?>&nbsp;<?php echo $output['daddress_info']['address'];?></dd>

					</dl>

			      <?php } ?>

			<div class="icon_edit" id="select_daddress"><i></i>编辑</div>

		</div>

	</div>



	<!-- 选择物流服务 -->

	 <div class="nctouch-oredr-detail-block mt5">

		<h3><b style="color:orange">第三步</b> 选择物流服务</h3>

	</div>

	<div class="nctouch-oredr-detail-block ">

	<!-- Tab切换（高度自适应示范） -->

			<div id="tabBox1" class="tabBox">

				<div class="hd">

					

					<ul>

						<li><a href="javascript:void(0)">物流运输</a></li>

						<li><a href="javascript:void(0)">无需物流运输</a></li>

					</ul>

				</div>

				<div class="bd" id="tabBox1-bd"><!-- 添加id，js用到 -->

					<div class="con"><!-- 高度自适应需添加外层 -->

					<table style="" class="ncsc-default-table order" id="texpress1">

				      <tbody>

				        <tr>

				          <td class="bdl w150">公司名称</td>

				          <td class="w250">物流单号</td>

				          <td class="bdr w90 tc">操作</td>

				        </tr>

				           <?php if (is_array($output['my_express_list']) && !empty($output['my_express_list'])){?>

					        <?php foreach ($output['my_express_list'] as $k=>$v){?>

					        <?php if (!isset($output['express_list'][$v])) continue;?>

					        <tr>

					          <td class="bdl"><?php echo $output['express_list'][$v]['e_name'];?></td>

					          <td class="bdl"><input name="shipping_code" type="text" class="text w200 tip-r" title="<?php echo $lang['store_deliver_shipping_code_tips'];?>" maxlength="20" nc_type='eb' nc_value="<?php echo $v;?>" /></td>

					          <td class="bdl bdr tc"><a nc_type='eb' nc_value="<?php echo $v;?>" href="javascript:void(0);" class="ncbtn btn key">确认</a></td>

					        </tr>

					        <?php }?>

					        <?php }?>

				        </tbody>

				    </table>

					</div>

					<div class="con"><!-- 高度自适应需添加外层 -->

						<table class="ncsc-default-table order" id="texpress2" style="">

					      <tbody>

					        <tr>

					          <td colspan="2"></td>

					        </tr>

					        <tr>

					          <td class="bdl tr">如果订单中的商品无需物流运送，您可以直接点击确认</td>

					          <td class="bdr tl w400"> <a nc_type="eb" nc_value="e1000" href="javascript:void(0);" class="ncbtn btn key">确认</a></td>

					        </tr>

					        <tr>

					          <td colspan="2"></td>

					        </tr>

					      </tbody>

					    </table>

					</div>

					



				</div>

			</div>

		</div>

	

	</div>

  </div>

</div>

<!-- 修改收货人信息 -->

<div class="alert_box_hide">

<div class="alert_box">

	

	 <dl>

      <dt>收货人：</dt>

       <dd>

         <input type="text" class="text" id="reciver_name" name="reciver_name" value="<?php echo $output['order_info']['extend_order_common']['reciver_name'];?>"/>

      </dd>

    </dl>



    <dl>

      <dt>地区:</dt>

      <dd>

        <input type="text" class="text" id="area" name="area" value="<?php echo $output['order_info']['extend_order_common']['reciver_info']['area'];?>"/>

      </dd>

    </dl>

    <dl>

      <dt>街道地址:</dt>

      <dd>

        <input type="text" class="text" id="street" name="street" value="<?php echo $output['order_info']['extend_order_common']['reciver_info']['street'];?>"/>

      </dd>

    </dl>

    <dl>

      <dt>手机:</dt>

      <dd>

        <input type="text" class="text" id="mob_phone" name="mob_phone" value="<?php echo $output['order_info']['extend_order_common']['reciver_info']['mob_phone'];?>"/>

      </dd>

    </dl>

    <dl>

      <dt>座机:</dt>

      <dd>

        <input type="text" class="text" id="tel_phone" name="tel_phone" value="<?php echo $output['order_info']['extend_order_common']['reciver_info']['tel_phone'];?>"/>

      </dd>

    </dl>

</div>

</div>

<!-- 选择发货地址 -->

<div class="fh_area hide">

<div class="alert_box">

  <?php if(is_array($output['address_list']) && !empty($output['address_list'])){?>

  <?php $i=0;foreach($output['address_list'] as $key => $value) {$i++;?>



		 <a class="acheck_two " href="javascript:;" onclick="setAddress(<?php echo $output['order_info']['order_id']; ?>,<?php echo $value['address_id'];?>,this)">

		    <h4><?php echo $value['seller_name'];?> <?php echo $value['telphone'];?></h4>

		    <p><?php echo $value['area_info'];?> <?php echo $value['address'];?></p>

	

		</a>

  <?php } ?>

  <?php }else{?>

  			<p>还没有添加发货地址,请在pc端添加</p>

  <?php }?>





<input type="hidden" name="daddress_id" id="daddress_id" value=""/>

<input type="hidden" name="dorder_id" id="dorder_id" value=""/>



</div>

</div>



	



<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/TouchSlide.1.1.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/seller_send.js"></script> 

<script>

	$(function(){

		<?php if ($output['order_info']['shipping_code'] != ''){?>

	    	$('input[nc_value="<?php echo $output['order_info']['extend_order_common']['shipping_express_id'];?>"]').val('<?php echo $output['order_info']['shipping_code'];?>');

	    	$('td[nc_value="<?php echo $output['order_info']['extend_order_common']['shipping_express_id'];?>"]').html('<?php echo $output['order_info']['extend_order_common']['deliver_explain'];?>');

	    <?php } ?>

	    var deliver_explain=$('input[name="deliver_explain"]').val();

  	

    	var reciver_name="<?php echo $output['order_info']['extend_order_common']['reciver_name'];?>";

	   	var reciver_area="<?php echo $output['order_info']['extend_order_common']['reciver_info']['area'];?>";

	    var reciver_street="<?php echo $output['order_info']['extend_order_common']['reciver_info']['street'];?>";

	   	var reciver_mob_phone ="<?php echo $output['order_info']['extend_order_common']['reciver_info']['mob_phone'];?>";

	    var reciver_tel_phone="<?php echo $output['order_info']['extend_order_common']['reciver_info']['tel_phone'];?>";

	    var reciver_dlyp="<?php echo $output['order_info']['extend_order_common']['reciver_info']['dlyp'];?>";

   		var daddress_id="<?php echo $output['daddress_info']['address_id'];?>";

   		var order_id = "<?php echo $output['order_info']['order_id']; ?>";

	    $('a[nc_type="eb"]').on('click',function(){

		if ($('input[nc_value="'+$(this).attr('nc_value')+'"]').val() == ''){

			layer.open({content:'请填写物流单号',time:1.5});

			return false;

		}

		$('input[nc_type="eb"]').attr('disabled',true);

		$('input[nc_value="'+$(this).attr('nc_value')+'"]').attr('disabled',false);

  		var shipping_code = $('input[nc_value="'+$(this).attr('nc_value')+'"]').val();

  		var shipping_express_id=$(this).attr('nc_value');

		layer.open({type:2,content:"提交中..."});

              $.ajax({

                type: "post",

                url: ApiUrl + "/index.php?con=seller_order&fun=edit_send",

                data: {

                    shipping_code: shipping_code,

                    deliver_explain:deliver_explain,

                    shipping_express_id:shipping_express_id,

                    reciver_name:reciver_name,

                    reciver_area:reciver_area,

                    reciver_street:reciver_street,

                    reciver_mob_phone:reciver_mob_phone,

                    reciver_tel_phone:reciver_tel_phone,

                    reciver_dlyp:reciver_dlyp,

                    daddress_id:daddress_id,

                    order_id: order_id

                },

                dataType: "json",

                async: false,

                success: function(e) {

                    if (e.code == 200) {

                        layer.open({

                            content:'发货成功!'

                        });

                         setTimeout(function () {

                            location.href=ApiUrl + "/index.php?con=seller_order&fun=order_detail&order_id="+order_id;

                            layer.close(index);

                        }, 1000);  

                       

                    } else {

                    

                        layer.open({

                            content:e.datas.error,

                            time:1.5

                         })

                        layer.close(index);

                    }

                }

            });

	});

	})

</script>