<?php defined('Inshopec') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<div class="breadcrumb"><span class="icon-home"></span><span>首页</span> <span class="arrow">></span> <span>管理员申请</span> </div>
<div class="main">
  <div class="sidebar">
    <div class="title">
      <h3>管理员申请</h3>
    </div>
    <div class="content">
      <dl>
        <dt class="<?php echo $output['sub_step'] == 'step0' ? 'current' : '';?><?php echo $output['sub_step'] == 'step1' ? 'current' : '';?>"> <i class="hide"></i>提交申请</dt>
      </dl>
      <dl>
        <dt class="<?php echo $output['step'] == '4' ? 'current' : '';?>"> <i class="hide"></i>等待审核</dt>
      </dl>
      <dl>
        <dt> <i class="hide"></i>审核通过</dt>
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
      <ul>
        <li class="step1 <?php echo $output['step'] >= 0 ? 'current' : '';?>"><span>公司资质信息</span></li>
        <li class="<?php echo $output['step'] >= 1 ? 'current' : '';?>"><span>财务资质信息</span></li>
        <li class="<?php echo $output['step'] >= 4 ? 'current' : '';?>"><span>等待审核</span></li>
        <li class="step6"><span>审核通过</span></li>
      </ul>
    </div>
    <div class="joinin-concrete">
      <?php require('manager_register.'.$output['sub_step'].'.php'); ?>
    </div>
  </div>
</div>
