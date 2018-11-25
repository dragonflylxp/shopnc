<?php defined('Inshopec') or exit('Access Invalid!');?>

<ul id="fullScreenSlides" class="full-screen-slides">
    <?php if (is_array($output['code_screen_list']['code_info']) && !empty($output['code_screen_list']['code_info'])) { ?>
        <?php foreach ($output['code_screen_list']['code_info'] as $key => $val) { ?>
            <?php if (is_array($val) && $val['ap_id'] > 0) { ?>
                <li ap_id="<?php echo $val['ap_id'];?>" color="<?php echo $val['color'];?>" style="background: <?php echo $val['color'];?> url('<?php echo UPLOAD_SITE_URL.'/'.$val['pic_img'];?>') no-repeat center top">
                    <a href="<?php echo $val['pic_url'];?>" target="_blank" title="<?php echo $val['pic_name'];?>">&nbsp;</a></li>
            <?php }else { ?>
                <li style="background: <?php echo $val['color'];?> url('<?php echo UPLOAD_SITE_URL.'/'.$val['pic_img'];?>') no-repeat center top">
                    <a href="<?php echo $val['pic_url'];?>" target="_blank" title="<?php echo $val['pic_name'];?>">&nbsp;</a></li>
            <?php } ?>
        <?php } ?>
    <?php } ?>

</ul>