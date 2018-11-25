<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="wrap">
  <form id="voucher_list_form" method="get">
    <table class="ncm-search-table">
      <input type="hidden" id='con' name='con' value='distri_order' />
      <input type="hidden" id='fun' name='fun' value='index' />
      <tr>
        <td class="w100">分销订单列表</td>
        <td class="tr">
          <span>订单状态：</span>
          <select name="order_state">
            <option value="-1">-请选择-</option>
            <option value="10" <?php echo ($_GET['order_state'] == 10)?'selected':''?>>待付款</option>
            <option value="11" <?php echo ($_GET['order_state'] == 11)?'selected':''?>>门店付款自提</option>
            <option value="20" <?php echo ($_GET['order_state'] == 20)?'selected':''?>>待发货</option>
            <option value="21" <?php echo ($_GET['order_state'] == 21)?'selected':''?>>待自提</option>
            <option value="30" <?php echo ($_GET['order_state'] == 30)?'selected':''?>>待收货</option>
            <option value="40" <?php echo ($_GET['order_state'] == 40)?'selected':''?>>交易完成</option>
            <option value="0" <?php echo (isset($_GET['order_state']) && $_GET['order_state'] == 0)?'selected':''?>>已取消</option>
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
        <th class="w120" style="text-align:left">订单编号</th>
        <th class="tl">商品名称</th>
        <th class="w80">商品价格</th>
        <th class="w80">商品数量</th>
        <th class="w80">商品结算金额</th>
        <th class="w100">店铺名称</th>
        <th class="w70">佣金比例</th>
        <th class="w70">佣金</th>
        <th class="w70">下单时间</th>
        <th class="w70">订单状态</th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['order_list'])>0) { ?>
      <?php foreach($output['order_list'] as $val) { ?>
      <tr class="bd-line">
        <td></td>
        <td><div class="ncm-goods-thumb"><a href="javascript:void(0);"><img src="<?php echo cthumb($val['goods_image'], 60,$val['store_id']);?>" onMouseOver="toolTip('<img src=<?php echo cthumb($val['goods_image'], 360,$val['store_id']);?>>')" onMouseOut="toolTip()" /></a></div></td>
        <td class="goods-name" style="text-align:left"><?php echo $val['order_sn'];?></td>
        <td class="tl">
          <dl class="goods-name">
            <a href="<?php echo urlShop('goods','index',array('goods_id'=>$val['goods_id']));?>" title="<?php echo $val['goods_name'];?>"><?php echo $val['goods_name'];?></a>
          </dl>
        </td>
        <td class="goods-price">
          <?php echo ncPriceFormatForList($val['goods_price']);?>
        </td>
        <td class="goods-price">
          <?php echo $val['goods_num'];?>
        </td>
        <td class="goods-price">
          <?php echo ncPriceFormatForList($val['goods_pay_price']);?>
        </td>
        <td class="goods-name">
          <a href="<?php echo urlDistribute('store','index',array('store_id'=>$val['store_id']));?>" title="<?php echo $val['store_name'];?>"><?php echo $val['store_name'];?></a>
        </td>
        <td class="goods-time"><?php echo $val['dis_commis_rate'];?>%</td>
        <td class="goods-price"><?php echo ncPriceFormatForList($val['goods_pay_price']*$val['dis_commis_rate']*0.01);?></td>
        <td class="goods-time"><?php echo date("Y-m-d",$val['add_time']);?></td>
        <td class="goods-time"><?php echo orderState($val);?></td>        
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <?php  if (count($output['order_list'])>0) { ?>
    <tfoot>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page'];?></div></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>
</div>
