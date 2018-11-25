<?php defined('Inshopec') or exit('Access Invalid!');?>
<style>
    .d_index_first{width: 1228px !important;}
    .d_index_first .d_index_box-01{ width:588px;float: left; margin-left: 13px; margin-right: 12px}
</style>
<div class="d_index d_index_first">
    <?php if (is_array($output['code_two_focus_list']['code_info']) && !empty($output['code_two_focus_list']['code_info'])) { ?>
        <?php foreach ($output['code_two_focus_list']['code_info'] as $key => $val) { ?>
        <div class="d_index_box-01">
            <h1 style="border-bottom:#a44bd0 solid 3px"><?php if($val['group_list']['group_image']){ ?><img src="<?php echo UPLOAD_SITE_URL.'/'.$val['group_list']['group_image'];?>" width="20" height="20" alt="" class="fl"/><?php } ?>
                <span class="fl"><?php echo $val['group_list']['group_name'] ?></span></h1>
            <ul class="d_index_shop">
                <?php foreach($val['pic_list'] as $k => $v) { ?>
                    <li><a href="<?php echo $v['pic_url'];?>" target="_blank" title="<?php echo $v['pic_name'];?>">
                            <img src="<?php echo UPLOAD_SITE_URL.'/'.$v['pic_img'];?>" alt="<?php echo $v['pic_name'];?>"></a></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
    <?php } ?>
    <div class="clear"></div>
</div>
<div class="clear"></div>
<?php if (is_array($output['code_eight_focus_list']['code_info']) && !empty($output['code_eight_focus_list']['code_info'])) { ?>
    <?php foreach ($output['code_eight_focus_list']['code_info'] as $key => $val) { ?>
    <div class="d_index" style="margin-top: 15px">
        <div class="d_index_box-02">
            <h1 style="border-bottom:#df1e36 solid 3px">
            <?php if($val['group_list']['group_image']){ ?><img src="<?php echo UPLOAD_SITE_URL.'/'.$val['group_list']['group_image'];?>" width="20" height="20" alt="" class="fl"/><?php } ?>
                <span class="fl"><?php echo $val['group_list']['group_name']; ?></span></h1>
            <ul class="d_index_shop" style="height:242px;">
                <?php foreach($val['pic_list'] as $k => $v) { ?>
                    <li><a href="<?php echo $v['pic_url'];?>" target="_blank" title="<?php echo $v['pic_name'];?>">
                            <img src="<?php echo UPLOAD_SITE_URL.'/'.$v['pic_img'];?>" alt="<?php echo $v['pic_name'];?>" class="fl"></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>
<?php } ?>
<div class="clear"></div>
<?php if (is_array($output['code_focus_list']['code_info']) && !empty($output['code_focus_list']['code_info'])) { ?>
    <?php foreach ($output['code_focus_list']['code_info'] as $key => $val) { ?>
    <div class="d_index">
        <div class="d_index_box-02 d_index_box-03">
            <h1 style="border-bottom:#f06e0b solid 3px">
            <?php if($val['group_list']['group_image']){ ?><img src="<?php echo UPLOAD_SITE_URL.'/'.$val['group_list']['group_image'];?>" width="20" height="20" alt="" class="fl"/><?php } ?>
                <span class="fl"><?php echo $val['group_list']['group_name']; ?></span></h1>
            <ul class="d_index_shop" style="height:160px;">
                <?php foreach($val['pic_list'] as $k => $v) { ?>
                    <li><a href="<?php echo $v['pic_url'];?>" target="_blank" title="<?php echo $v['pic_name'];?>">
                            <img src="<?php echo UPLOAD_SITE_URL.'/'.$v['pic_img'];?>" alt="<?php echo $v['pic_name'];?>" class="fl"></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>
<?php } ?>
<div class="clear" style="height:50px"></div>


