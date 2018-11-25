<?php defined('Inshopec') or exit('Access Invalid!');?>
<!--店铺基本信息 S-->

<div class="ncs-info">
  <div class="title">
    <h4><?php echo $output['store_info']['store_name']; ?></h4>
  </div>
  <div class="content">
    <?php if (!$output['store_info']['is_own_shop']) { ?>
    <div class="ncs-detail-rate">
      <ul>
        <?php  foreach ($output['store_info']['store_credit'] as $value) {?>
        <li>
          <h5><?php echo $value['text'];?></h5>
          <div class="<?php echo $value['percent_class'];?>" title="<?php echo $value['percent_text'];?><?php echo $value['percent'];?>"><?php echo $value['credit'];?><i></i></div>
        </li>
        <?php } ?>
      </ul>
    </div>
    <div class="btns">
    	<a href="<?php echo urlShop('show_store', 'index', array('store_id' => $output['store_info']['store_id']), $output['store_info']['store_domain']);?>" class="goto" >进店逛逛</a>

    	
    	<a href="javascript:collect_store('<?php echo $output['store_info']['store_id'];?>','count','store_collect')" >收藏店铺<span>(<em nctype="store_collect"><?php echo $output['store_info']['store_collect']?></em>)</span></a></div>
    <?php } ?>
    <?php if (!$output['store_info']['is_own_shop']) { ?>
    <dl class="no-border">
      <dt>公司名称：</dt>
      <dd><?php echo $output['store_info']['store_company_name'];?></dd>
    </dl>
    <?php if(!empty($output['store_info']['store_phone'])){?>
    <dl>
      <dt>电&#12288;&#12288;话：</dt>
      <dd><?php echo $output['store_info']['store_phone'];?></dd>
    </dl>
    <?php } ?>
    <dl>
      <dt><?php echo $lang['nc_srore_location'];?></dt>
      <dd><?php echo $output['store_info']['area_info'];?></dd>
    </dl>
    <?php } ?>
    <?php if(!empty($output['store_info']['store_qq']) || !empty($output['store_info']['store_ww'])){?>
    <dl class="messenger">
      <dt><?php echo $lang['nc_contact_way'];?>：</dt>
      <dd><span member_id="<?php echo $output['store_info']['member_id'];?>"></span>
        <?php if(!empty($output['store_info']['store_qq'])){?>
        <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['store_info']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $output['store_info']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $output['store_info']['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
        <?php }?>
        <?php if(!empty($output['store_info']['store_ww'])){?>
        <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="<?php echo $lang['nc_message_me'];?>" style=" vertical-align: middle;"/></a>
        <?php }?>
      </dd>
    </dl>
    <?php } ?>
  </div>
</div>
    			<?php if(!empty($output['store_info']['store_zhenren'])){?>
<div id='ios_mini'></div>
<script type="text/javascript" src=" <?php echo $output['store_info']['store_zhenren'];?>"></script>
    <?php } ?>
<script>
$(function(){
	var store_id = "<?php echo $output['store_info']['store_id']; ?>";
	var goods_id = "<?php echo $_GET['goods_id']; ?>";
	var act = "<?php echo trim($_GET['con']); ?>";
	var op  = "<?php echo trim($_GET['fun']) != ''?trim($_GET['fun']):'index'; ?>";
	$.getJSON("index.php?con=show_store&fun=ajax_flowstat_record",{store_id:store_id,goods_id:goods_id,act_param:act,op_param:op});
});
</script> 
