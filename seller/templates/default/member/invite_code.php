<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_member.css">
<style>
	img.index_code {
    margin: 30% 10% 10% 10%;
    width: 80%;
}
</style>
</head>

<body>

<header id="header" class="nctouch-product-header fixed">

		<div class="header-wrap">

			<div class="header-l">
				<a href="javascript:history.go(-1)">
					<i class="back"></i>
				</a>
			</div>

			<div class="header-title">

				<h1>推广二维码</h1>

			</div>

			<div class="header-r">
				<a id="header-nav" href="javascript:void(0);">
					<i class="more"></i><sup></sup>
				</a>
			</div>

		</div>

  <?php include template('layout/toptip');?>



</header>


<footer id="footer" class="bottom"></footer>
  <ul id="invite_code_list" class="nctouch-invite-list" ></ul>



	</body>
    
<script type="text/html" id="invite_code">
  	<% if (datas.urld) { %>
  	<div class="member_invite_code">
  		
  		<img class="index_code" src=<%= datas.urld; %>  alt="上海鲜花港 - 郁金香" />
  	</div>
  	<% } else { %>
    <div class="nctouch-norecord pdre">
        <div class="norecord-ico"><i></i></div>
        <dl>
            <dt>生成二维码失败，请从新刷新此页面！</dt>
        </dl>
    </div>
    <%}%>
</script>  	  

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.waypoints.js"></script>

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/ncscroll-load.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/invite_code.js"></script>
</html>