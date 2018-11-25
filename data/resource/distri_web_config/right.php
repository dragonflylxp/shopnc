<?php defined('Inshopec') or exit('Access Invalid!');?>
<?php if (is_array($output['code_adv_list']['code_info']) && !empty($output['code_adv_list']['code_info'])) { ?>
    <?php foreach ($output['code_adv_list']['code_info'] as $key => $val) { ?>
        <a href="<?php echo $val['pic_url'];?>" target="_blank" title="<?php echo $val['pic_name'];?>">
            <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_img'];?>" alt="" class="nc-login-enter-ad"/>
        </a>
    <?php } ?>
<?php } ?>
