<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>

<div nctype="div_goods_select" class="div-goods-select">
    <form method="get" action="index.php">
    <input type="hidden" name="con" value="store_dis_goods" />
    <input type="hidden" name="fun" value="goods_list" />
    <table class="search-form">
      <tr><th class="w150"><strong>商品名称</strong></th><td class="w160"><input nctype="search_goods_name" type="text w150" class="text" name="goods_name" value=""/></td>
        <td class="w70 tc"><label class="submit-border"><input nctype="btn_search_goods" type="submit" value="搜索" class="submit"/></label></td><td class="w10"></td><td>
      </td>
      </tr>
    </table>
    </form>
    <div nctype="div_goods_search_result" class="search-result">
    <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
    
    <ul class="goods-list">
      <?php foreach($output['goods_list'] as $key=>$val){?>
      <li select_id="<?php echo $val['goods_commonid'];?>">
        <div class="goods-thumb"><img src="<?php echo thumb($val, 240);?>"/></div>
        <dl class="goods-info">
          <dt><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $output['storage_array'][$val['goods_commonid']]['goods_id']));?>" target="_blank"><?php echo $val['goods_name'];?></a> </dt>
          <dd>销售价格：<?php echo $lang['currency'].ncPriceFormat($val['goods_price']);?>
        </dl>
        <a onclick="select_goods(<?php echo $val['goods_commonid'];?>);" href="javascript:void(0);" class="ncbtn-mini">选择商品</a> </li>
      <?php } ?>
    </ul>
    <div class="pagination"><?php echo $output['show_page'];?></div>
    <?php } else { ?>
    <div><?php echo $lang['no_record'];?></div>
    <?php } ?>
    </div>
</div>

<script>
function select_goods(common_id){//商品选择
    var obj = $("li[select_id='"+common_id+"']");
    obj.find(".ncbtn-mini").unbind("click").html('已选择');
    $.get('index.php?con=store_dis_goods&fun=add_goods&id='+common_id);
}
</script>