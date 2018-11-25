<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>

<table class="ncsc-default-table order">
  <thead>
    <tr>
      <th class="w10"></th>
      <th colspan="2">商品</th>
      <th class="w100">单价（元）</th>
      <th class="w50">数量</th>
      <th class="w90">分销人</th>
      <th class="w100">订单金额</th>
      <th class="w60">佣金</th>
      <th class="w100">交易状态</th>
    </tr>
  </thead>
  <?php if (is_array($output['order_list']) && !empty($output['order_list'])) { ?>
  <?php foreach($output['order_list'] as $order_id => $order) { ?>
  <tbody>
    <tr>
      <td colspan="20" class="sep-row"></td>
    </tr>
    <tr>
      <th colspan="20"><span class="ml10">订单编号<?php echo $lang['nc_colon'];?><em><?php echo $order['order_sn']; ?></em></span>
        <span>下单时间<?php echo $lang['nc_colon'];?><em class="goods-time"><?php echo $order['add_time_text']; ?></em></span>
        <span>结算状态<?php echo $lang['nc_colon'];?><em class="goods-time"><?php echo $order['dis_pay_state_text']; ?></em></span>
        <span>结算时间<?php echo $lang['nc_colon'];?><em class="goods-time"><?php echo $order['dis_pay_time']; ?></em></span>
         </th>
    </tr>
    <?php $i = 0;?>
    <?php foreach($order['extend_order_goods'] as $k => $goods) { ?>
    <?php $i++;?>
    <tr>
      <td class="bdl"></td>
      <td class="w70"><div class="ncsc-goods-thumb"><a href="<?php echo $goods['goods_url'];?>" target="_blank"><img src="<?php echo $goods['image_60_url'];?>" onMouseOver="toolTip('<img src=<?php echo $goods['image_240_url'];?>>')" onMouseOut="toolTip()"/></a></div></td>
      <td class="tl"><dl class="goods-name">
          <dt><a target="_blank" href="<?php echo $goods['goods_url'];?>"><?php echo $goods['goods_name']; ?></a><a target="_blank" class="blue ml5" href="<?php echo urlShop('snapshot', 'index', array('rec_id' => $goods['rec_id']));?>">[交易快照]</a></dt>
          <?php if ($goods['goods_spec']){ ?>
          <dd><?php echo $goods['goods_spec'];?></dd>
          <?php } ?>
        </dl></td>
      <td><p><?php echo ncPriceFormat($goods['goods_price']); ?></p>
      </td>
      <td><?php echo $goods['goods_num']; ?></td>
      <td><?php echo $goods['dis_member_name']; ?></td>
      <!-- S 合并TD -->
      <?php if (($order['goods_count'] > 1 && $k ==0) || ($order['goods_count']) == 1){ ?>
      <td class="bdl" rowspan="<?php echo $order['goods_count'];?>" style="width:110px"><p class="ncsc-order-amount"><?php echo $order['dis_order_amount']; ?></p>
        <p class="goods-pay" title="<?php echo $lang['store_order_pay_method'].$lang['nc_colon'];?><?php echo $order['payment_name']; ?>"><?php echo $order['payment_name']; ?></p></td>
      <td class="bdl" rowspan="<?php echo $order['goods_count'];?>"><p class="ncsc-order-amount" style="width:90px"><?php echo $order['dis_commis_amount']; ?></p>
        </td>
      <td class="bdl bdr" rowspan="<?php echo $order['goods_count'];?>"><p><?php echo $order['state_desc']; ?>
        </p>
        </td>
      <?php } ?>
      <!-- E 合并TD --> 
    </tr>
    
    <?php } ?>
    <?php } } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (is_array($output['order_list']) and !empty($output['order_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>