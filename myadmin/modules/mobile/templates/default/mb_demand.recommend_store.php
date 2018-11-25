<?php defined('Inshopec') or exit('Access Invalid!');?>
<?php if(!empty($output['store_list']) && is_array($output['store_list'])){?>
<style>
.s-list{ width: 100%}
.s-list li{ margin-bottom: 20px}
.s-list li input{ float: left; display:block; width: 13px; height: 13px; margin: 10px 10px 0 0}
.s-list li img{ float: left; width: 32px; height: 32px; margin-right: 20px}
.s-list li span{ float: left; width: 60%;}
</style>
    <ul class="s-list">
        <?php foreach($output['store_list'] as $key=>$val){?>
            <li>
                <input type="radio" name="ncstore" value="<?php echo $val['store_id']; ?>">
                <img src="<?php echo getStoreLogo($val['store_avatar']);?>"/>
                <span>店铺名称：<?php echo $val['store_name'];?></span>
                <div class="clear"></div>
            </li>
        <?php } ?>
    </ul>
    <div class="pagination"><?php echo $output['show_page'];?></div>
<?php } else { ?>
    <div><?php echo $lang['no_record'];?></div>
<?php } ?>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js"></script>
<script type="text/javascript">
    $(function(){
        $('input[name="ncstore"]').change(function(){
            var ids = $(this).val();
            $('input[name="store"]').val(ids);
        });
        $('.demo').on('click', function(){
            $('div[nctype="div_search_result"]').html('').load($(this).attr('href'));
            return false;
        });
    });
</script>
