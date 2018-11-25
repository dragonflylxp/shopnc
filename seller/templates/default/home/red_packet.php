<?php defined( 'Inshopec') or exit( 'Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/main.css">
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/child.css">
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/red_packet.css">
<style type="text/css">
.header-wrap h2{line-height:35px;}
</style>

<header id="header">
  <div class="header-wrap">
    <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>
    <div class="header-title">
      <h1>领取红包</h1>
    </div>
    <div class="header-r"> <a href="javascript:void(0);" id="header-nav"><i class="more bgc-t"></i><sup></sup></a> </div>
  </div>
    <?php include template('layout/toptip');?>
</header>
	
	<section class="content area_01 page1">
		<article class="flex_img"><img src="<?php echo MOBILE_TEMPLATES_URL;?>/images/red_packet.png"></article>
		<section class="mian_cont">
			<div class="col_btn" id="chaihongbao">
				<a href="javascript:;" class="bomb_btn" id="rush_get">拆红包</a>
			</div>
			<div class="col_btn col_btn2" id="fenxiang" style="display:none;">
			    <a href="javascript:;" class="bomb_btn bomb_btn2">已参加</a>
			</div>
		</section>
	</section>


<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script>
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/list/red_packet.js"></script>

