<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="wrap">
  <form id="voucher_list_form" method="get">
    <table class="ncm-search-table">
      <input type="hidden" id='con' name='con' value='distri_bill' />
      <input type="hidden" id='fun' name='fun' value='index' />
      <tr>
        <td class="w100">分销结算列表</td>
        <td class="tr">
          <span>结算状态：</span>
          <select name="bill_state">
            <option value="-1">-请选择-</option>
            <option value="0" <?php echo (isset($_GET['bill_state']) && $_GET['bill_state'] == 0)?'selected':''?>>未结算</option>
            <option value="1" <?php echo ($_GET['bill_state'] == 10)?'selected':''?>>已结算</option>            
          </select>
          <input type="text" name="goods_name" class="txt w100" placeholder="商品名称">
        </td>
        <td class="w70 tc"><label class="submit-border">
            <input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" />
          </label></td>
      </tr>
    </table>
  </form>
  <table class="ncm-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w70"></th>
        <th class="w150" style="text-align:left">订单编号</th>
        <th class="tl">商品名称</th>
        <th class="w80">支付金额</th>
        <th class="w80">退款金额</th>
        <th class="w70">佣金比例</th>
        <th class="w70">分销佣金</th>        
        <th class="w70">已结时间</th>
        <th class="w70">结算状态</th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['bill_list'])>0) { ?>
      <?php foreach($output['bill_list'] as $val) { ?>
      <tr class="bd-line">
        <td></td>
        <td><div class="ncm-goods-thumb"><a href="javascript:void(0);"><img src="<?php echo cthumb($val['goods_image'], 60,$val['store_id']);?>" onMouseOver="toolTip('<img src=<?php echo cthumb($val['goods_image'], 360,$val['store_id']);?>>')" onMouseOut="toolTip()" /></a></div>
        </td>
        <td class="goods-name" style="text-align:left"><?php echo $val['order_sn'];?></td>
        <td class="tl">
          <dl class="goods-name">
            <a href="<?php echo urlShop('goods','index',array('goods_id'=>$val['goods_id']));?>" title="<?php echo $val['goods_name'];?>"><?php echo $val['goods_name'];?></a>
          </dl>
        </td>
        <td class="goods-price">
          <?php echo ncPriceFormatForList($val['pay_goods_amount']);?>
        </td>
        <td class="goods-price">
          <?php echo ncPriceFormatForList($val['refund_amount']);?>
        </td>
        <td class="goods-time"><?php echo $val['dis_commis_rate'];?>%</td>
        <td class="goods-price"><?php echo ncPriceFormatForList($val['dis_pay_amount']);?></td>

        <td class="goods-time"><?php echo $val['dis_pay_time']?date("Y-m-d",$val['dis_pay_time']):'';?></td>
        <td class="goods-time"><?php echo str_replace(array(0,1), array('未结算','已结算'), $val['log_state']);?></td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <?php  if (count($output['bill_list'])>0) { ?>
    <tfoot>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page'];?></div></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>
</div>
