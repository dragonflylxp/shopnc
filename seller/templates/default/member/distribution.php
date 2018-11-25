<?php defined('Inshopec') or exit('Access Invalid!');?>
	
<!--20160906-->

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_cart.css">
<style>

.w20h li{

	width:20%;

}

</style>



<body>

<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

   <div class="header-title">

      <h1>分销管理</h1>

    </div>

   <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

   </div>

       <?php include template('layout/toptip');?>





</header>
<div class="nctouch-main-layout">
    <div class="nctouch-asset-info">
        <div class="container pre">
            <i class="icon"></i>
            <dl>
                <dt>推广佣金</dt>
                <dd>￥<em class="commision-amount"><%=predepoit;%></em></dd>
            </dl>
        </div>
    </div>
    <ul id="data-list" class="nctouch-log-list"></ul>
</div>
<div class="fix-block-r">
    <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
</div>
<footer id="footer" class="bottom"></footer>
<script type="text/html" id="data-list-tmpl">
    <% var list = datas.commision_list; %>
    <% if (list.length > 0) { %>
    <% for (var i = 0; i < list.length; i++) { var v = list[i]; %>
    <li>
        <div class="detail"><%=v.lg_desc;%></div>
        <% if (v.lg_av_amount > 0) { %>
        <div class="money add">+<%=v.lg_av_amount;%></div>
        <% } else { %>
        <div class="money reduce"><%=v.lg_av_amount;%></div>
        <% } %>
        <time class="date"><%=v.lg_add_time_text;%></time>
    </li>
    <%}%>
    <% if (hasmore) {%>
    <li class="loading"><div class="spinner"><i></i></div>数据读取中...</li>
    <% } %>
    <% } else { %>
    <div class="nctouch-norecord pdre">
        <div class="norecord-ico"><i></i></div>
        <dl>
            <dt>您尚无相关信息</dt>
        </dl>
    </div>
    <%}%>
</script>
<script type="text/javascript">

  var ukey = "<?php echo $_SESSION['key'];?>";

</script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.waypoints.js"></script>
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/ncscroll-load.js"></script>


<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/distribution.js"></script>



