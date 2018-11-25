<div class="nch-breadcrumb-layout">
  <?php if(!empty($output['nav_link_list']) && is_array($output['nav_link_list'])){?>
  <div class="nch-breadcrumb wrapper"><i class="icon-home"></i>
    <?php foreach($output['nav_link_list'] as $nav_link){?>
    <?php if(!empty($nav_link['link'])){?>
    <span><a href="<?php echo $nav_link['link'];?>"><?php echo $nav_link['title'];?></a></span><span class="arrow">></span>
    <?php }else{?>
    <span><?php echo $nav_link['title'];?></span>
    <?php }?>
    <?php }?>
    <?php if(!empty($output['indexer_count'])){?>
    <!-- 显示数量qunashop -->
        <div class="counts">
        <span class="crumbs-items">找到相关结果约 <span class="H"><?php echo $output['indexer_count'];?></span> 件宝贝</span>
  </div>
   <?php }?>
  </div><!-- 显示数量qunashop -->
  <?php }?>
</div>