<?php defined('Inshopec') or exit('Access Invalid!');?>
<style>
	.no-reaults{text-align: center;margin:20px;}
	.video-reaults{margin:10px;}
</style>
<?php if(file_exists(BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/0' .'/' . 'goods_video' . '/' . $output['info']['goods_video'])) {?>
<div class="video-reaults">
<video controls width="600" height="300" src="<?php echo goodsVideoPath($output['info']['goods_video'],0)?>"></video></div>
<?php }else{ ?>
<div class="no-reaults">该商品视频不存在!</div>
<?php } ?>