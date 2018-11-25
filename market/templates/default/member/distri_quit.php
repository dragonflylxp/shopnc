<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu'); ?>
  </div>
  <div class="alert alert-success">
    <h4>操作提示：</h4>
    <ul>
      <li>1. 退出分销后，您分享的所有分销商品链接都将失效。</li>
      <li>2. 已经通过您的分销链接生成订单所产生的分销佣金继续有效。</li>
      <li>3. 退出分销后您所有分销佣金会在M天后为您结算并提现到您认证分销时所设置的提现账户中。</li>
    </ul>
  </div>
  <div class="ncm-default-form">
    <form id="form_company_info" action="index.php?con=access_infomation&fun=save_quit" method="post">
      <input type="hidden" name="form_submit" value="ok" />
      <dl>
        <dt><i class="required"></i>可提现金额：</dt>
        <dd>
          <?php echo ncPriceFormatForList($output['member_info']['available_distri_trad']); ?>
        </dd>
      </dl>
      <dl>
        <dt><i class="required"></i>冻结佣金：</dt>
        <dd>
            <?php echo ncPriceFormatForList($output['member_info']['freeze_distri_trad']); ?>
        </dd>
      </dl>
      <dl>
        <dt><i class="required"></i>总佣金：</dt>
        <dd>
          <?php echo ncPriceFormatForList($output['member_info']['available_distri_trad'] + $output['member_info']['freeze_distri_trad']);?>
        </dd>
      </dl>
      <dl class="bottom">
        <dt>&nbsp;</dt>
        <dd><label class="submit-border">
          <a id="btn_apply_company_quit" href="javascript:void(0);" class="submit">确认退出</a></label>
        </dd>
      </dl>      
  </form>
  </div>
</div>
<script>
  $(function(){
    $('#btn_apply_company_quit').on('click', function() {
      if(confirm('确认要退出分销吗？')) {
        $('form').submit();
      }      
    });
  });
</script>