<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="ncm-default-form">
    <dl>
      <dt><?php echo '申请单号'.$lang['nc_colon']; ?></dt>
      <dd><?php echo $output['info']['tradc_sn']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo '提现金额'.$lang['nc_colon']; ?></dt>
      <dd><?php echo $output['info']['tradc_amount']; ?> <?php echo $lang['currency_zh']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo '收款银行'.$lang['nc_colon']; ?></dt>
      <dd><?php echo $output['info']['tradc_bank_name']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo '收款账号'.$lang['nc_colon'];?></dt>
      <dd><?php echo $output['info']['tradc_bank_no']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo '收款人'.$lang['nc_colon'];?></dt>
      <dd><?php echo $output['info']['tradc_bank_user']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo '申请时间'.$lang['nc_colon'];?></dt>
      <dd><?php echo @date('Y-m-d',$output['info']['tradc_add_time']); ?></dd>
    </dl>
    <dl>
      <dt><?php echo '付款状态'.$lang['nc_colon'];?></dt>
      <dd><?php echo str_replace(array('0','1'),array('未支付','已支付'),$output['info']['tradc_payment_state']);?></dd>
    </dl>
   <?php if (intval($output['info']['tradc_payment_time'])) {?>
    <dl>
      <dt><?php echo '付款时间'.$lang['nc_colon'];?></dt>
      <dd><?php echo @date('Y-m-d H:i:s',$output['info']['tradc_payment_time']); ?></dd>
    </dl>
   <?php } ?> 
  </div>
</div>
