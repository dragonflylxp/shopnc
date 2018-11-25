<?php defined('Inshopec') or exit('Access Invalid!');?>
<script type="text/javascript">
    <?php if (is_array($output['pic']) && !empty($output['pic'])) { ?>
	parent.adv_pic("<?php echo $output['pic']['pic_id'];?>","<?php echo $output['pic']['pic_img'];?>");
	<?php } ?>

</script>