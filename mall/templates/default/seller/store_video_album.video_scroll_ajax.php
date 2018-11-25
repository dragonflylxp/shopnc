<?php defined('Inshopec') or exit('Access Invalid!');?>
            <?php foreach ((array)$output['video_list'] as $v) {?>
            <li> <a value="<?php echo $v['video_id'] ?>"> <span class="thumb size90"><i></i> <video title="<?php echo $v['video_name']?>" src="<?php echo goodsVideoPath($v['video_cover'], $_SESSION['store_id']);?>" class="image0" />
              <input type="hidden" value="" />
              </span> </a> </li>
            <?php }?>
<script type="text/javascript">
  $(function() {
    var galleries = $('.ad-gallery').adGallery({loader_image:'<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif', start_at_index:0, slideshow:{enable: false,start_label: '自动播放', stop_label: '暂停'}});
	$(".image0").click(function(){
		ajax_change_videomessage(this.src);
	});
  });
</script>