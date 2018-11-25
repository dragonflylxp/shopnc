<?php defined('Inshopec') or exit('Access Invalid!');?>
<script type="text/javascript">
	<?php if(!empty($output['group']) && is_array($output['group'])) { ?>
	parent.eight_focus_group("<?php echo $output['group']['group_name'];?>","<?php echo $output['group']['group_image'];?>");
	<?php } ?>
	<?php if(!empty($output['pic']) && is_array($output['pic'])){ ?>
	parent.eight_focus_pic("<?php echo $output['pic']['pic_id'];?>","<?php echo $output['pic']['pic_img'];?>");
	<?php } ?>
</script>