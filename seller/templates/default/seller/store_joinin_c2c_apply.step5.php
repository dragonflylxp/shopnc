<header id="header" class="fixed">
  <div class="header-wrap">
    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>
   <div class="header-title">
      <h1>审核状态</h1>
    </div>
   <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
   </div>
       <?php include template('layout/seller_toptip');?>


</header>
<div class="nctouch-main-layout fixed-Width">
<div class="alert">
    <h4>审核状态:</h4>
    <?php echo $output['joinin_message'];?>
</div>


<div class="bottom">
    <?php if($output['btn_next']) { ?>
    <a class="btn-l mt5 mb5" id="next_company_info" href="<?php echo $output['btn_next'];?>">下一步</a>
    <?php } ?>
</div>


</div>


<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 
<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 