<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="alert mt15 mb5"><strong>操作提示：</strong>
  <ul>
    <li>1、只显示店铺可发布的分类商品，按分类查询时要选择到最后一级分类。</li>
  </ul>
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="con" value="store_lib_goods" />
    <input type="hidden" name="fun" value="index" />
    <tr>
      <td>&nbsp;</td>
      <td style="text-align: right;">商品分类&nbsp;&nbsp;
        <select name="gc_id[]" class="w150">
          <?php if (!empty($output['goods_class'])) {?>
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            <?php foreach ($output['goods_class'] as $val) {?>
            <option <?php if($val['gc_id'] == $_GET['gc_id'][0]){?>selected<?php }?> value="<?php echo $val['gc_id']?>"><?php echo $val['gc_name'];?></option>
            <?php }?>
          <?php }?>
        </select></td>
      <td class="w70" style="text-align: right;">
        商品名称&nbsp;&nbsp;
      </td>
      <td class="w100"><input type="text" class="w100" id="goods_name" name="goods_name" value="<?php echo $_GET['goods_name']; ?>"/></td>
      <td class="tc w70"><label class="submit-border"><input id="submit" type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<table class="ncsc-default-table">
  <thead>
    <tr nc_type="table_header">
      <th class="w30"></th>
      <th class="w50"></th>
      <th>商品名称</th>
      <th class="w80">重量/体积</th>
      <th class="w250">商品分类</th>
      <th class="w120">发布时间</th>
      <th class="w80"><?php echo $lang['nc_handle'];?></th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['list'])) { ?>
    <?php foreach ($output['list'] as $val) { ?>
    <tr>
      <td class="trigger"></td>
      <td><div class="pic-thumb">
        <img src="<?php echo thumb($val, 60);?>"/></div></td>
      <td class="tl"><dl class="goods-name">
          <dt><?php echo $val['goods_name']; ?></dt>
          <dd>商品卖点<?php echo $lang['nc_colon'];?><?php echo str_cut($val['goods_jingle'],50); ?></dd>
        </dl></td>
      <td><span><?php echo $val['goods_trans_kg']; ?>/<?php echo $val['goods_trans_v']; ?></span></td>
      <td><span><?php echo $val['gc_name']; ?></span></td>
      <td><span><?php echo date('Y-m-d H:i:s',$val['goods_addtime']); ?></span></td>
      <td class="nscs-table-handle">
        <span><a href="javascript:void(0)" class="btn-mint" nc_type="dialog" dialog_title="认领商品：<?php echo str_cut($val['goods_name'],50); ?>" dialog_id="map_edit" dialog_width="480" uri="index.php?con=store_lib_goods&fun=edit_goods&gc_id=<?php echo $val['gc_id']; ?>&goods_id=<?php echo $val['goods_id']; ?>" href="" class="btn-bluejeans"><i class="icon-edit"></i>
        <p>认领</p>
        </a></span></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
    <?php  if (!empty($output['list'])) { ?>
  <tfoot>
    <tr>
      <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
    </tr>
  </tfoot>
  <?php } ?>
</table>
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/store_goods_list.js"></script> 
<script>
    var gc_1 = "<?php echo $_GET['gc_id'][0]; ?>";
    var gc_2 = "<?php echo $_GET['gc_id'][1]; ?>";
    var gc_3 = "<?php echo $_GET['gc_id'][2]; ?>";
    $(function(){
	    $(".search-form select").change(gcategoryChange);
	    if(gc_1) $(".search-form select").trigger("change");
	});
	$("#submit").click(function(){
        if($(".search-form select").last().attr('end')){
            return true;
		}
        if($("#goods_name").val()){
            return true;
		}
		return false;
	});
    
function gcategoryChange(){
    // 删除后面的select
    $(this).nextAll("select").remove();
    var deep = $(".search-form select").size()+1;
    // ajax请求下级分类
    if (this.value > 0){
        var _self = this;
        var url = SITEURL + '/index.php?con=store_lib_goods&fun=ajax_goods_class';
        $.getJSON(url, {'gc_id':this.value,'deep':deep}, function(data){
            if (data){
                if (data.length > 0){
                    $("<select name='gc_id[]' class='w150'></select>").change(gcategoryChange).insertAfter(_self);
                    var add_html = '<option value="">请选择...</option>';
                    for (i = 0; i < data.length; i++){
                        var s = '';
                        if(gc_2==data[i].gc_id) s = 'selected';
                        if(gc_3==data[i].gc_id) s = 'selected';
                        add_html += "<option value='" + data[i].gc_id + "' " + s + " >" + data[i].gc_name + "</option>";
                    }
                    $(_self).next("select").append(add_html);
                    if(gc_2) $(_self).next("select").trigger("change");
                }
            }else{
                $(_self).attr('end','1');
                gc_2 = "";
                gc_3 = "";
            }
        });
    }
}
</script>