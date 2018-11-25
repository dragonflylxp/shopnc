<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="alert mt15 mb5"><strong>操作提示：</strong>
  <ul>
    <li>1、统计数据由系统每个小时更新一次，会有廷迟。</li>
  </ul>
</div>
<table class="ncsc-default-table">
  <thead>
    <tr nc_type="table_header">
      <th class="w30"></th>
      <th class="w50"></th>
      <th>商品名称</th>
      <th class="w90">SPU</th>
      <th class="w60">领取次数</th>
      <th class="w60">分销单数</th>
      <th class="w90">订单总额</th>
      <th class="w90">支付总额</th>
      <th class="w80">已结佣金</th>
      <th class="w80">未结佣金</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['goods_list'])) { ?>
    <?php foreach ($output['goods_list'] as $val) { ?>
    <tr>
      <td class="trigger"></td>
      <td><div class="pic-thumb">
        <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $output['storage_array'][$val['goods_commonid']]['goods_id']));?>" target="_blank">
            <img src="<?php echo thumb($val, 60);?>"/></a></div></td>
      <td class="tl"><dl class="goods-name">
          <dt><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $output['storage_array'][$val['goods_commonid']]['goods_id']));?>" target="_blank">
            <?php echo $val['goods_name']; ?></a></dt>
          <dd>价格<?php echo $lang['nc_colon'];?><?php echo $lang['currency'].ncPriceFormat($val['goods_price']); ?></dd>
        </dl></td>
      <td><span><?php echo $val['goods_commonid']; ?></span></td>
      <td><span><?php echo $output['sta_list'][$val['goods_commonid']]['dis_num']; ?></span></td>
      <td><span><?php echo $output['sta_list'][$val['goods_commonid']]['order_num']; ?></span></td>
      <td><span><?php echo ncPriceFormat($output['sta_list'][$val['goods_commonid']]['order_goods_amount']); ?></span></td>
      <td><span><?php echo ncPriceFormat($output['sta_list'][$val['goods_commonid']]['pay_goods_amount']); ?></span></td>
      <td><span><?php echo ncPriceFormat($output['sta_list'][$val['goods_commonid']]['dis_commis_amount']); ?></span></td>
      <td><span><?php echo ncPriceFormat($output['sta_list'][$val['goods_commonid']]['order_commis_amount']); ?></span></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
    <?php  if (!empty($output['goods_list'])) { ?>
  <tfoot>
    <tr>
      <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
    </tr>
  </tfoot>
  <?php } ?>
</table>
<script>
function change_commis(common_id){
    var obj = $("#"+common_id);
	var num = parseInt($.trim(obj.val()));
	if (num>0 && num <= 30) {
	    var ajaxurl = 'index.php?con=store_dis_goods&fun=edit_goods&num='+num+'&id='+common_id;
		$.ajax({
			type: "GET",
			url: ajaxurl,
			async: false,
		    success: function(state){
		  	if(state == '1') {
		  		obj.val(num);
		  		showDialog('保存成功','succ','','','','','','','',2);
			}
		  }
		});
	}
}
</script>