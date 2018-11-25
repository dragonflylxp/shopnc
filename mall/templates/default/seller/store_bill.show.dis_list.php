<?php defined('Inshopec') or exit('Access Invalid!');?>

<table class="ncsc-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th>商品名称</th>
        <th>订单编号</th>
        <th>支付金额</th>
        <th>退款金额</th>
        <th>佣金比例</th>
        <th>已结佣金</th>
        <th>结算时间</th>
      </tr>
    </thead>
    <tbody>
      <?php if (is_array($output['list']) && !empty($output['list'])) { ?>
      <?php foreach($output['list'] as $info) { ?>
      <tr class="bd-line">
        <td></td>
        <td><?php echo $info['goods_name'];?></td>
        <td><?php echo $info['order_sn'];?></td>
        <td><?php echo ncPriceFormat($info['pay_goods_amount']);?></td>
        <td><?php echo ncPriceFormat($info['refund_amount']);?></td>
        <td><?php echo $info['dis_commis_rate'];?>%</td>
        <td><?php echo ncPriceFormat($info['dis_pay_amount']);?></td>
        <td><?php echo date("Y-m-d",$info['dis_pay_time']);?></td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php if (is_array($output['list']) && !empty($output['list'])) { ?>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>