<?php defined('Inshopec') or exit('Access Invalid!');?>
	
<!--20160906-->

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">
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
<style>
#distribution_list {width: 98%;padding: 0 1%;}
#distribution_list li {width: 100%;display: inline-flex;}
#distribution_list li.hd div {font-weight: 600;}
#distribution_list li div {width: 33.333%; text-align: left;font-size: 0.5rem;}
#distribution_list li.hd div {font-weight: 600;}
#distribution_list li div {width: 33.333%;text-align: left;font-size: 0.5rem;}
#distribution_list li.hd div {font-weight: 600;}
#distribution_list li div {width: 33.333%;text-align: left;font-size: 0.5rem;}
#distribution_list li div {width: 33.333%;text-align: left;font-size: 0.5rem;}
	
</style>
  <?php include template('layout/toptip');?>



</header>
<div class="nctouch-main-layout">
    <div class="nctouch-asset-info">
        <div class="container pre">
            <i class="icon"></i>
            <dl>
                <dt class="distribution_level"></dt>
            </dl>
        </div>
    </div>
    <div id="fixed_nav" class="nctouch-single-nav">
        <ul id="filtrate_ul" class="w33h">
            <li class="selected"><a href="javascript:void(0);" data-level="first">一级推广</a></li>
            <li><a href="javascript:void(0);" data-level="second">二级推广</a></li>
            <li><a href="javascript:void(0);" data-level="third">三级推广</a></li>
        </ul>
    </div>
    <ul id="distribution_list" class="nctouch-log-list"></ul>
</div>
<div class="fix-block-r">
    <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
</div>
<footer id="footer" class="bottom"></footer>
<script type="text/html" id="distribution-list-tmpl">
    <li class="hd">
        <div class="member_name">用户名</div>
        <div class="buy_count">下单次数</div>
        <div class="refund_amount">提成金额</div>
    </li>
    <% var list = datas.invite_list; %>
    <% if (list.length > 0) { %>
    <% for (var i = 0; i < list.length; i++) { var v = list[i]; %>
    <li>
        <div class="member_name"><%= v.member_name; %></div>
        <div class="buy_count"><%= v.buy_count; %></div>
        <div class="refund_amount"><%=v.refund_amount; %></div>
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


<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/distributionlist.js"></script>



