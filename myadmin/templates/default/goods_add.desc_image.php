<div class="goods-gallery add-step2">
  <?php if(!empty($output['pic_list'])){?>
  <ul class="list">
    <?php foreach ($output['pic_list'] as $v){?>
    <li onclick="insert_editor('<?php echo cthumb($v['apic_cover'], 1280, 0);?>');"><a href="JavaScript:void(0);"><img src="<?php echo cthumb($v['apic_cover'], 240, 0);?>" title='<?php echo $v['apic_name']?>'/></span></a></li>
    <?php }?>
  </ul>
  <?php }else{?>
  <div class="warning-option"><i class="icon-warning-sign"></i><span>相册中暂无图片</span></div>
  <?php }?>
  <div class="pagination"><?php echo $output['show_page']; ?></div>
</div>
<script>
$(document).ready(function(){
	$('.demo').ajaxContent({
		event:'click', //mouseover
		loaderType:'img',
		loadingMsg:'<?php echo ADMIN_TEMPLATES_URL;?>/images/loading.gif',
		target:'#des_demo'
	});
});
</script>