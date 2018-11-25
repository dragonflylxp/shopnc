<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>订单退款</h1>

    </div>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

  </div>

    <?php include template('layout/toptip');?>



</header>

<div class="nctouch-main-layout mb20">

  <div class="nctouch-order-list" id="order-info-container"></div>

	<div class="special-tips">

	<p>特别提示：退款凭证选择直接拍照或从手机相册上传图片时，请注意图片尺寸控制在1M以内，超出请压缩裁剪后再选择上传！</p>

	</div>

  <form>

    <div class="nctouch-inp-con">

      <ul class="form-box">

      <li class="form-item">

          <h4>退款原因</h4>

          <div class="input-box">

            <input type="text" class="inp" value="取消订单，全部退款" readonly>

          </div>

        </li>

        <li class="form-item">

          <h4>退款金额</h4>

          <div class="input-box">

            <input id="allow_refund_amount" type="text" class="inp" value="" readonly>

          </div>

        </li>

        <li class="form-item">

          <h4>退款说明</h4>

          <div class="input-box">

            <input type="text" class="inp" name="buyer_message" placeholder="申请原因！">

          </div>

        </li>

        <li class="form-item upload-item">

          <h4>退款凭证</h4>

          <div class="input-box">

            <div class="nctouch-upload"> <a href="javascript:void(0);"> <span>

              <input type="file" hidefocus="true" size="1" class="input-file" name="refund_pic" id="">

              </span>

              <p><i class="icon-upload"></i></p>

              </a>

              <input type="hidden" name="refund_pic[0]" value="" />

            </div>

            <div class="nctouch-upload"> <a href="javascript:void(0);"> <span>

              <input type="file" hidefocus="true" size="1" class="input-file" name="refund_pic" id="">

              </span>

              <p><i class="icon-upload"></i></p>

              </a>

              <input type="hidden" name="refund_pic[1]" value="" />

            </div>

            <div class="nctouch-upload"> <a href="javascript:void(0);"> <span>

              <input type="file" hidefocus="true" size="1" class="input-file" name="refund_pic" id="">

              </span>

              <p><i class="icon-upload"></i></p>

              </a>

              <input type="hidden" name="refund_pic[2]" value="" />

            </div>

          </div>

        </li>

      </ul>

      <div class="form-btn"><a href="javascript:;" class="btn-l">提交</a></div>

    </div>

  </form>

</div>

<script type="text/html" id="order-info-tmpl">

	<div class="nctouch-order-item mt5">

		<div class="nctouch-order-item-head">

			<a href="<%=WapSiteUrl%>/index.php?con=store&store_id=<%=order.store_id%>" class="store"><i class="icon"></i><%=order.store_name%><i class="arrow-r"></i></a>

		</div>

		<div class="nctouch-order-item-con">

			<%for(i=0; i<goods_list.length; i++){%>

			<div class="goods-block detail">

				<a href="<%=WapSiteUrl%>/index.php?con=goods&fun=detail&goods_id=<%=goods_list[i].goods_id%>">

				<div class="goods-pic">

					<img src="<%=goods_list[i].goods_img_360%>">

				</div>

				<dl class="goods-info">

					<dt class="goods-name"><%=goods_list[i].goods_name%></dt>

					<dd class="goods-type"><%=goods_list[i].goods_spec%></dd>

				</dl>

				<div class="goods-subtotal">

					<span class="goods-price">￥<em><%=goods_list[i].goods_price%></em></span>

					<span class="goods-num">x<%=goods_list[i].goods_num%></span>

				</div>

			</a>

			</div>

			<%}%>

			<% if (gift_list.length > 0){%>

				<div class="goods-gift">

				<%for(i=0; i<gift_list.length; i++){%>

					<span><em>赠品</em><%=gift_list[i].goods_name%> x <%=gift_list[i].goods_num%></span>

				<%}%>

				</div>

			<%}%>

		</div>

	</div>

</script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/refund_all.js"></script>

