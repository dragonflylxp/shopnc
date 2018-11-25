<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">

</head>

<body>

<header id="header">

  <div class="header-wrap">

    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>

    <span class="header-title">

    <h1>退货详情</h1>

    </span>

    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

    </div>

     <?php include template('layout/toptip');?>





</header>

<div class="nctouch-main-layout" id="return-info-div"> </div>

<script type="text/html" id="return-info-script">

<h3 class="nctouch-default-list-tit">我的退货申请</h3>

<ul class="nctouch-default-list">

  <li>

    <h4>退货编号</h4>

    <span class="num"><%=return_info.refund_sn%></span> </li>

  <li>

    <h4>退货原因</h4>

    <span class="num"><%=return_info.reason_info%></span></li>

  <li>

    <h4>退货金额</h4>

    <span class="num"><%=return_info.refund_amount%></span></li>

  <li>

    <h4>退货说明</h4>

    <span class="num"><%=return_info.buyer_message%></span></li>

  <li>

    <h4>凭证上传</h4>

    <span class="pics">

    <% for (var k in pic_list) { %>

    <img src="<%=pic_list[k]%>" />

    <% } %>

    </span></li>

</ul>

<h3 class="nctouch-default-list-tit">商家退货处理</h3>

<ul class="nctouch-default-list">

  <li>

    <h4>审核状态</h4>

    <span class="num"><%=return_info.seller_state%></span></li>

  <li>

    <h4>商家备注</h4>

    <span class="num"><%=return_info.seller_message%></span></li>

</ul>

<h3 class="nctouch-default-list-tit">商城退货审核</h3>

<ul class="nctouch-default-list">  

  <li>

    <h4>平台确认</h4>

    <span class="num"><%=return_info.admin_state%></span></li>

  <li>

    <h4>平台备注</h4>

    <span class="num"><%=return_info.admin_message%></span></li>

</ul>

<%if(!isEmpty(detail_array)) {%>

<h3 class="nctouch-default-list-tit">退货详细</h3>

<ul class="nctouch-default-list">

  <li>

    <h4>支付方式</h4>

    <span class="num"><%=detail_array.refund_code%></span></li>

  <li>

    <h4>在线退货金额</h4>

    <span class="num"><%=detail_array.pay_amount%></span></li>

  <li>

    <h4>预存款返还金额</h4>

    <span class="num"><%=detail_array.pd_amount%></span></li>

  <li>

    <h4>充值卡返还金额</h4>

    <span class="num"><%=detail_array.rcb_amount%></span></li>

</ul>

<%}%>

</script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/member_return_info.js"></script>

