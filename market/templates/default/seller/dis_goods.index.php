<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="index.php?con=store_dis_goods&fun=goods_list" class="ncbtn ncbtn-mint" title="添加商品"><i class="icon-plus-sign"></i>添加商品</a>
</div>
<div class="alert mt15 mb5"><strong>操作提示：</strong>
  <ul>
    <li>1、佣金比例最小为1%，最大为30%。</li>
  </ul>
</div>
<table class="ncsc-default-table">
  <thead>
    <tr nc_type="table_header">
      <th class="w50"></th>
      <th>商品名称</th>
      <th class="w60">SPU</th>
      <th class="w60">库存</th>
      <th class="w60">商品状态</th>
      <th class="w200">商品分类</th>
      <th class="w100">添加时间</th>
      <th class="w50">佣金比例</th>
      <th class="w80">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['goods_list'])) { ?>
    <?php foreach ($output['goods_list'] as $val) { ?>
    <tr>
      <td><div class="pic-thumb">
        <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $output['storage_array'][$val['goods_commonid']]['goods_id']));?>" target="_blank">
            <img src="<?php echo thumb($val, 60);?>"/></a></div></td>
      <td class="tl" style="width:280px"><dl class="goods-name">
          <dt><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $output['storage_array'][$val['goods_commonid']]['goods_id']));?>" target="_blank">
            <?php echo $val['goods_name']; ?></a></dt>
          <dd>价格<?php echo $lang['nc_colon'];?><?php echo $lang['currency'].ncPriceFormat($val['goods_price']); ?></dd>
        </dl></td>
      <td><span><?php echo $val['goods_commonid']; ?></span></td>
      <td><span><?php echo $output['storage_array'][$val['goods_commonid']]['sum']; ?></span></td>
      <td><span><?php echo $val['goods_state'] == 1 ? '出售中':'仓库中';?></span></td>
      <td><span><?php echo $val['gc_name']; ?></span></td>
      <td><span><?php echo date('Y-m-d H:i:s',$val['dis_add_time']);; ?></span></td>
      <td><input class="text w20" type="text" id="<?php echo $val['goods_commonid']; ?>" name="dis_commis_rate" maxlength="2" value="<?php echo $val['dis_commis_rate']; ?>" onchange="change_commis(<?php echo $val['goods_commonid']; ?>);" /><span style="line-height:32px;">&nbsp;%</span></td>
      <td class="nscs-table-handle">
        <span><a href="javascript:void(0)" onclick="ajax_get_confirm('您确定要删除吗?', '<?php echo DISTRIBUTE_SITE_URL;?>/index.php?con=store_dis_goods&fun=del_goods&id=<?php echo $val['goods_commonid']; ?>');" class="btn-grapefruit"><i class="icon-trash"></i>
        <p>取消分销</p>
        </a></span></td>
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