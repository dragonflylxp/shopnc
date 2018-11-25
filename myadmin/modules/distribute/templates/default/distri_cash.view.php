<?php defined('Inshopec') or exit('Access Invalid!');?>
  <div class="ncap-form-default">
    <dl class="row">
      <dt class="tit">
        <label>提现编号</label>
      </dt>
      <dd class="opt"><?php echo $output['info']['tradc_sn']; ?>
        <p class="notic"></p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">
        <label>会员名称</label>
      </dt>
      <dd class="opt"><?php echo $output['info']['tradc_member_name']; ?>
        <p class="notic"></p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">
        <label>提现金额</label>
      </dt>
      <dd class="opt"><?php echo $output['info']['tradc_amount']; ?>&nbsp;<?php echo $lang['currency_zh'];?>
        <p class="notic"></p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">
        <label>收款银行</label>
      </dt>
      <dd class="opt"><?php echo $output['info']['tradc_bank_name']; ?>
        <p class="notic"></p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">
        <label>收款账号</label>
      </dt>
      <dd class="opt"><?php echo $output['info']['tradc_bank_no']; ?>
        <p class="notic"></p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">
        <label>收款人姓名</label>
      </dt>
      <dd class="opt"><?php echo $output['info']['tradc_bank_user']; ?>
        <p class="notic"></p>
      </dd>
    </dl>
    <?php if (intval($output['info']['tradc_payment_time'])) {?>
    <dl class="row">
      <dt class="tit">
        <label>付款时间</label>
      </dt>
      <dd class="opt"><?php echo @date('Y-m-d',$output['info']['tradc_payment_time']); ?> ( 支付管理员: <?php echo $output['info']['tradc_payment_admin'];?> )
        <p class="notic"></p>
      </dd>
    </dl>
    <?php } ?>
    <?php if (!intval($output['info']['tradc_payment_state'])) {?>
    <div class="bot" id="submit-holder"> <a class="ncap-btn-big ncap-btn-green" href="javascript:if (confirm('付款后不能取消，确认要付款吗？')){window.location.href='index.php?con=distri_cash&fun=cash_pay&id=<?php echo $output['info']['tradc_id']; ?>';}else{}">付款</a></div>
  <?php } ?>
  </div>