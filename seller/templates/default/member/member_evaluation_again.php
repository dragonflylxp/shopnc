<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <div class="header-title">

      <h1>再次评价</h1>

    </div>

  </div>

</header>

<div class="nctouch-main-layout" id="member-evaluation-div"> </div>

<script type="text/html" id="member-evaluation-script">

	<div class="special-tips">

	<p>特别提示：评价晒图选择直接拍照或从手机相册上传图片时，请注意图片尺寸控制在1M以内，超出请压缩裁剪后再选择上传！</p>

	</div>

<%if(evaluate_goods.length > 0){%>

	<form>

	<ul class="nctouch-evaluation-goods">

		<%for(var i=0; i<evaluate_goods.length; i++){%>

		<li>

			<div class="evaluation-info">

				<div class="goods-pic">

					<img src="<%=evaluate_goods[i].geval_goodsimage%>"/>

				</div>

				<dl class="goods-info">

					<dt class="goods-name"><%=evaluate_goods[i].geval_goodsname%></dt>

					<dd class="goods-rate"><b>初次评价:</b><%=evaluate_goods[i].geval_content%>

						

					</dd>

				</dl>

			</div>

			<div class="evaluation-inp-block">

			<input type="text" class="textarea" name="goods[<%=evaluate_goods[i].geval_id%>][comment]" placeholder="请输入追加评价的内容，不要超过150个字符。">

				<label>

					<input type="checkbox" class="checkbox" name="goods[<%=evaluate_goods[i].geval_id%>][anony]" value="1" /><p>匿&nbsp;名</p>

				</label>

			</div>

			<div class="evaluation-upload-block">

				<div class="tit"><i></i><p>追&nbsp;加</p></div>

				<div class="nctouch-upload">

					<a href="javascript:void(0);">

						<span><input type="file" hidefocus="true" size="1" class="input-file" name="file" id=""></span>

						<p><i class="icon-upload"></i></p>

              		</a>

					<input type="hidden" name="goods[<%=evaluate_goods[i].geval_id%>][evaluate_image][0]" value="" />

				</div>

				<div class="nctouch-upload">

					<a href="javascript:void(0);">

						<span><input type="file" hidefocus="true" size="1" class="input-file" name="file" id=""></span>

						<p><i class="icon-upload"></i></p>

              		</a>

					<input type="hidden" name="goods[<%=evaluate_goods[i].geval_id%>][evaluate_image][1]" value="" />

				</div>

				<div class="nctouch-upload">

					<a href="javascript:void(0);">

						<span><input type="file" hidefocus="true" size="1" class="input-file" name="file" id=""></span>

						<p><i class="icon-upload"></i></p>

              		</a>

					<input type="hidden" name="goods[<%=evaluate_goods[i].geval_id%>][evaluate_image][2]" value="" />

				</div>

				<div class="nctouch-upload">

					<a href="javascript:void(0);">

						<span><input type="file" hidefocus="true" size="1" class="input-file" name="file" id=""></span>

						<p><i class="icon-upload"></i></p>

              		</a>

					<input type="hidden" name="goods[<%=evaluate_goods[i].geval_id%>][evaluate_image][3]" value="" />

				</div>

				<div class="nctouch-upload">

					<a href="javascript:void(0);">

						<span><input type="file" hidefocus="true" size="1" class="input-file" name="file" id=""></span>

						<p><i class="icon-upload"></i></p>

              		</a>

					<input type="hidden" name="goods[<%=evaluate_goods[i].geval_id%>][evaluate_image][4]" value="" />

				</div>

			</div>

		</li>

		<%}%>

	</ul>

	<%if(store_info.is_own_shop == 0){%>

	<div class="nctouch-evaluation-store">

		<dl>

			<dt>描述相符</dt>

			<dd>

				<span class="star-level">

					<i class="star-level-solid"></i>

					<i class="star-level-solid"></i>

					<i class="star-level-solid"></i>

					<i class="star-level-solid"></i>

					<i class="star-level-solid"></i>

				</span>

				<input type="hidden" name="store_desccredit" value="5" />

			</dd>

		</dl>

		<dl>

			<dt>服务态度</dt>

			<dd>

				<span class="star-level">

					<i class="star-level-solid"></i>

					<i class="star-level-solid"></i>

					<i class="star-level-solid"></i>

					<i class="star-level-solid"></i>

					<i class="star-level-solid"></i>

				</span>

				<input type="hidden" name="store_servicecredit" value="5" />

			</dd>

		</dl>

		<dl>

			<dt>发货速度</dt>

			<dd>

				<span class="star-level">

					<i class="star-level-solid"></i>

					<i class="star-level-solid"></i>

					<i class="star-level-solid"></i>

					<i class="star-level-solid"></i>

					<i class="star-level-solid"></i>

				</span>

				<input type="hidden" name="store_deliverycredit" value="5" />

			<dd>

		</dl>

	</div>

	<%}%>

	<a class="btn-l mt5 mb5">提交评价</a>

	<form>

<%}%>

</script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/member_evaluation_again.js"></script> 

