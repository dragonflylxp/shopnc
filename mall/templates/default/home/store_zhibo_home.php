<?php defined('Inshopec') or exit('Access Invalid!');?>
<script>
var PURL = '<?php echo $output['purl'];?>';
$(document).ready(function(){
    $('#area_info').nc_region();
});
</script>

<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/store_zhibo.css" rel="stylesheet" type="text/css">
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jcarousel/skins/tango/skin.css" rel="stylesheet" type="text/css">

<style type="text/css">
.sticky #main-nav { width: 1198px;}
/*.jcarousel-skin-tango .jcarousel-prev-horizontal, .jcarousel-skin-tango .jcarousel-next-horizontal { margin-top: -60px;}*/
.jcarousel-skin-tango .jcarousel-clip-horizontal { width: 1000px !important; height: 225px !important;}
.jcarousel-skin-tango .jcarousel-item { height: 225px !important;}
.jcarousel-skin-tango .jcarousel-container-horizontal { width: 1000px !important;}
</style>

<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL.'/js/Jquery.Query.js';?>" charset="utf-8"></script>
<script type="text/javascript">
//<!CDATA[
/* 替换参数 */
function ss_replaceParam(key, value)
{
    location.assign($.query.set('key', key).set('order', value));
}

/* 替换参数 */
function ss_dropParam(key1, key2)
{
	location.assign($.query.REMOVE(key1).REMOVE(key2));
}

/* 替换参数 */
function ss_dropParam2(key1)
{
	location.assign($.query.REMOVE(key1));
}

/* 替换参数 */
function ss_replaceParam2(key, value)
{
    location.assign($.query.set(key, value, value));
}

$(function (){
    var order = '<?php echo $_GET['order'];?>';
    var arrow = '';
    var class_val = 'sort_desc';

    switch (order){
        case 'store_credit desc' : order = 'store_credit asc';  class_val = 'sort_desc'; break;
        case 'store_credit asc'  : order = 'store_credit desc'; class_val = 'sort_asc' ; break;
        default : order = 'store_credit asc';
    }
    $('#credit_grade').addClass(class_val);
    $('#credit_grade').click(function(){query('order', order);return false;});
}
);

function query(name, value){
    $("input[name='"+name+"']").val(value);
    $('#searchStore').submit();
}

//]]>
</script>

<div class="content">
<div class="nc-store-class">
  <?php if(!empty($output['class_list']) && is_array($output['class_list'])){?>
  <dl>
    <dt>【店铺类目】</dt>
    <dd>
      <a href="<?php echo urlShop('zhibo_list','index');?>">全部</a>
      <?php foreach($output['class_list'] as $k=>$v){?>
	      <?php if ($_GET['cate_id'] == $v['sc_parent_id']){?>
	      <a href="<?php echo urlShop('zhibo_list','index',array('cate_id'=>$k));?>"><?php echo $v['sc_name'];?></a>
	      <?php }elseif (!isset($v['child']) && $output['class_list'][$_GET['cate_id']]['sc_parent_id'] == $v['sc_parent_id']){?>
	      <a href="<?php echo urlShop('zhibo_list','index',array('cate_id'=>$k));?>"><?php echo $v['sc_name'];?></a>
	      <?php }?>
      <?php }?>
    </dd>
  </dl>
  <?php }?>
</div>
<ul class="nc-store-list">
<?php if(!empty($output['store_list']) && is_array($output['store_list'])){?>
<?php foreach($output['store_list'] as $skey => $store){?>
    <li class="item">
      <dl class="shop-info">
        <dt class="shop-pic"><a href="<?php echo urlShop('show_zhibo','', array('store_id'=>$store['store_id']),$store['store_domain']);?>" title="" target="_blank"><img src="<?php echo $store['img'];?>"  alt="<?php echo $store['store_name'];?>" title="<?php echo $store['store_name'];?>" /></a></dt>
        <dd class="shop-name"><a href="<?php echo urlShop('show_zhibo','', array('store_id'=>$store['store_id']),$store['store_domain']);?>" target="_blank"><?php echo $store['store_name'];?></a></dd>
        <dd class="shopkeeper"><?php echo $lang['store_class_index_owner'].$lang['nc_colon'];?><?php echo $store['member_name'];?></dd>
        <dd class="main-runs" title="<?php echo $store['store_zy']?>"><?php echo $lang['store_class_index_store_zy'].$lang['nc_colon'];?><?php echo $store['store_zy']?></dd>
      </dl>
    </li>
<?php }?>

<?php }else{?>
<div id="no_results"><?php echo $lang['store_class_index_no_record'];?></div>
<?php }?>
</ul>
<div class="pagination"> <?php echo $output['show_page'];?> </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jcarousel/jquery.jcarousel.min.js"></script> 

<script type="text/javascript">
$(function(){
	//图片轮换
    $('[nc_type="jcarousel"]').jcarousel({visible: 4});
    $('[attr="morep"]').click(function(){
    	var id = $(this).attr('nc_type');
    	if($(this).attr('class')=='more-off'){
    		$(this).addClass('more-on').removeClass('more-off').html('<?php echo $lang['store_class_index_goods_hiden'];?><i></i>');
    		$('div[nc_type="goods_'+id+'"]').show();
    	}else{
    		$(this).addClass('more-off').removeClass('more-on').html('<?php echo $lang['store_class_index_goods_show'];?><i></i>');
    		$('div[nc_type="goods_'+id+'"]').hide();
    	}
    });
   
});
</script>
