<?php defined('Inshopec') or exit('Access Invalid!');?>
<style>
.ncm-index-container .user-account .icon { background: url(<?php echo DISTRIBUTE_TEMPLATES_URL;?>/images/member_pics-01.png) no-repeat; width: 64px; height: 64px; margin: 10px auto;}
.ncm-index-container .user-account .value em{ color:#f31616}
.link{ display:block; width:1029px; height:auto; margin:20px 0}
.link img{ width:100%; height:auto; display:block}
</style>
<div id="member_center_box" class="ncm-index-container">
  <div class="user-account">
    <dl class="account01">
      <a href="<?php echo urlDistribute('cash', 'cash_list');?>" title="可提现金额">
      <dt>可提现金额</dt>
      <dd class="icon"></dd>
      <dd class="value"><em>
        <?php
          echo ncPriceFormatForList($output['member_info']['available_distri_trad']);
        ?>
      </em></dd>
      </a>
    </dl>
    <dl class="account03">
      <a href="<?php echo urlDistribute('cash', 'cash_list');?>" title="冻结佣金金额">
      <dt>冻结佣金金额</dt>
      <dd class="icon"></dd>
      <dd class="value"><em>
        <?php
          echo ncPriceFormatForList($output['member_info']['freeze_distri_trad']);
        ?>
      </em></dd>
      </a>
    </dl>
    <dl class="account04">
      <a href="<?php echo urlDistribute('distri_goods');?>" title="分销商品">
      <dt>分销商品</dt>
      <dd class="icon"></dd>
      <dd class="value"><a href="">查看详情</a></dd>
      </a>
    </dl>
    <dl class="account05">
      <a href="<?php echo urlDistribute('distri_order');?>" title="分销订单">
      <dt>分销订单</dt>
      <dd class="icon"></dd>
      <dd class="value"><a href="">查看详情</a></dd>
      </a>
    </dl>
    <dl class="account02">
      <a href="<?php echo urlDistribute('distri_bill');?>" title="结算管理">
      <dt>结算管理</dt>
      <dd class="icon"></dd>
      <dd class="value"><a href="">查看详情</a></dd>
      </a>
    </dl>
  </div>
  <a href="<?php echo urlDistribute('index','index');?>" target="_blank" class="link"><img src="<?php echo DISTRIBUTE_TEMPLATES_URL;?>/images/banner.jpg" alt=""/></a>
  
</div>
