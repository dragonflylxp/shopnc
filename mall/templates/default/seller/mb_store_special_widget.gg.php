<?php defined('Inshopec') or exit('Access Invalid!');?>
<?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){ ?>

<ul class="search-goods-list">
  <?php foreach($output['goods_list'] as $key => $value){ ?>
  <li>
    <div class="goods-name"><?php echo $value['article_title'];?></div>
    <div class="goods-price">摘要:<?php echo $value['article_abstract'];?></div>
    <div class="goods-pic"><img title="<?php echo $value['article_title'];?>" src="<?php echo getCMSArticleImageUrl($value['article_attachment_path'], $value['article_image']);?>" /></div>
    <a nctype="btn_add_goods" data-goods-id="<?php echo $value['article_id'];?>" data-goods-name="<?php echo $value['article_title'];?>" data-goods-price="<?php echo $value['article_abstract'];?>" data-goods-image="<?php echo getCMSArticleImageUrl($value['article_attachment_path'], $value['article_image']);?>" " href="javascript:;">添加</a> </li>
  <?php } ?>
</ul>
<div id="goods_pagination" class="pagination"> <?php echo $output['show_page'];?> </div>
<?php }else { ?>
<p class="no-record"><?php echo $lang['nc_no_record'];?></p>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#goods_pagination').find('.demo').ajaxContent({
            event:'click', 
            loaderType:"img",
            loadingMsg:"<?php echo ADMIN_TEMPLATES_URL;?>/images/transparent.gif",
            target:'#mb_special_goods_list'
        });
    });
</script> 
