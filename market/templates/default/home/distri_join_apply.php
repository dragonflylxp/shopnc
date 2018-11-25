<?php defined('Inshopec') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="breadcrumb"><span class="icon-home"></span><span><a href="<?php echo SHOP_SITE_URL;?>">首页</a></span> <span class="arrow">></span> <span>分销商认证申请</span> </div>
<div class="main">
  <div class="sidebar">
    <div class="title">
      <h3>分销商认证申请</h3>
    </div>
    <div class="content">
      <dl>
        <dt class="<?php echo $output['sub_step'] == 'step0' ? 'current' : '';?>"> <i class="hidle"></i>签订认证协议</dt>
      </dl>
      <dl show_id="0">
        <dt class="<?php echo $output['step'] == '1' ? 'current' : '';?>"> <i class="hidle"></i>提交申请</dt>
      </dl>
      <?php if(C('distribute_check') == 1 || $output['member_info']['distri_state'] == 1){?>
      <dl>
        <dt class="<?php echo $output['step'] == '2' ? 'current' : '';?>"> <i class="hidle"></i>平台审核</dt>
      </dl>
      <?php }?>
      <dl>
        <dt> <i class="hidle"></i>认证完成</dt>
      </dl>
    </div>
    <div class="title">
      <h3>平台联系方式</h3>
    </div>
    <div class="content">
      <ul>
        <?php
			if(is_array($output['phone_array']) && !empty($output['phone_array'])) {
				foreach($output['phone_array'] as $key => $val) {
			?>
        <li><?php echo '电话'.($key+1).$lang['nc_colon'];?><?php echo $val;?></li>
        <?php
				}
			}
			 ?>
        <li><?php echo '邮箱'.$lang['nc_colon'];?><?php echo C('site_email');?></li>
      </ul>
    </div>
  </div>
  <div class="right-layout">
    <div class="joinin-step">
      <?php if(C('distribute_check') == 1 || $output['member_info']['distri_state'] == 1){?>
        <ul style="width: 616px;">
        <li class="step1 <?php echo $output['step'] >= 0 ? 'current' : '';?>"><span>签订入驻协议</span></li>
        <li class="<?php echo $output['step'] >= 1 ? 'current' : '';?>"><span>提交申请</span></li>
        <li class="<?php echo $output['step'] >= 2 ? 'current' : '';?>"><span>平台审核</span></li>
        <li class="step6"><span>认证完成</span></li>
      <?php } else {?>
        <ul style="width: 516px;">
        <li class="step1_0 <?php echo $output['step'] >= 0 ? 'current' : '';?>"><span>签订入驻协议</span></li>
        <li class="<?php echo $output['step'] >= 1 ? 'current' : '';?>" style="width: 240px;"><span style="margin-left: -60px;">提交申请</span></li>
        <li class="step6_0"><span>认证完成</span></li>
      <?php }?>  
      </ul>
    </div>
    <div class="joinin-concrete">
      <?php require('distri_joinin_apply_'.$output['sub_step'].'.php'); ?>
    </div>
  </div>
</div>
