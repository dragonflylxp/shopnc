<?php defined('Inshopec') or exit('Access Invalid!');?>
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/layer/layer.js"></script>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
<?php if ($notOwnShop = !checkPlatformStore()) { ?>
  <a href="javascript:void(0)" class="ncbtn ncbtn-mint" nc_type="dialog" dialog_title="申请新的经营类目" dialog_id="my_goods_brand_apply" dialog_width="480" uri="index.php?con=store_info&fun=bind_class_add">申请新的经营类目</a>
<?php } ?>
  </div>

<?php if (checkPlatformStoreBindingAllGoodsClass()) { ?>
<table class="ncsc-default-table">
  <tbody>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><span>店铺已绑定全部商品类目</span></div></td>
    </tr>
  </tbody>
</table>

<?php } else { ?>

<table class="ncsc-default-table">
  <thead>
    <tr>
      <th class="w20"></th>
      <th colspan="3">经营类目</th>
      <th>分佣比例</th>
<?php if ($notOwnShop) { ?>
      <th>状态</th>
      <th>审核信息</th>
      <th>操作</th>
<?php } ?>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['bind_list'])) { ?>
    <?php foreach($output['bind_list'] as $val) { ?>
    <tr class="bd-line">
      <td></td>
      <td class="w180 tl"><?php echo $val['class_1_name']; ?></td>
      <td class="w180 tl"><?php echo $val['class_2_name'] ? '>' : null; ?>&emsp;<?php echo $val['class_2_name']; ?></td>
      <td class="w180 tl"><?php echo $val['class_3_name'] ? '>' : null; ?>&emsp;<?php echo $val['class_3_name']; ?></td>
      <td class="w60"><?php echo $val['commis_rate'];?> %</td>
<?php if ($notOwnShop) { ?>
      <td class="w100"><?php
          if($val['state'] == '1'){
              echo  '已审核' ;
          }elseif($val['state'] == '3'){
              echo  '审核失败' ;
          }else{
              echo  '审核中' ;
          }
          ?></td>

      <td class="w100">
          <a href="javascript:void(0)" id="checkinfo"><?php
          if(!empty($val['checkinfo'])){
//              echo $val['checkinfo'];
              echo mb_strimwidth($val['checkinfo'], 0,9, '...');
          }else{
             echo '无';
          }  ?>
              <input type="hidden" class="hidden_text" name="hidden_text" value="<?php echo $val['checkinfo'];?>">
          </a>

      </td>

      <td class="nscs-table-handle">
      <?php if (in_array($val['state'],array(0,3))) {?>
      <span><a href="javascript:void(0)" class="btn-grapefruit" onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', 'index.php?con=store_info&fun=bind_class_del&bid=<?php echo $val['bid']; ?>');"><i class="icon-trash"></i><p><?php echo $lang['nc_del'];?></p></a></span>
     <?php } ?>
      </td>
<?php } ?>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
</table>

<?php } ?>
<script type="text/javascript">
    $(document).ready(function(){

        $('#checkinfo').live('click',function(){
            var checkinfo = $(this).find('.hidden_text').val();
//            layer.alert(checkinfo);
            layer.open({
                type: 1,
                area: ['360px', '300px'],
                skin: 'layui-layer-rim', //加上边框
                content: checkinfo,
            });
        })

    });

</script>
