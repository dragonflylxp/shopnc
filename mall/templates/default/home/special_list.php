<?php defined('Inshopec') or exit('Access Invalid!');?>
<!--<style type="text/css">
.head-app { display: none; }
.public-nav-layout { width: 1000px; }
.public-nav-layout .site-menu { max-width: 788px;}
.wrapper { width: 1000px !important; }
.no-content { font: normal 16px/20px Arial, "microsoft yahei"; color: #999999; text-align: center; padding: 150px 0; }
.public-nav-layout .category .sub-class { width: 746px;}
.public-nav-layout .category .sub-class-right  { display: none;}-->

</style>
<style type="text/css">
/*专题活动列表*/
.special-list { }
.special-list li { clear: both; width: 1200px; margin: 10px 0 0 0; overflow: hidden; }
.special-list .special-title { font-size: 16px; font-weight: 600; line-height: 24px; padding: 0 0 6px 12px; }
.special-list .special-cover { width: 1180px; height: 245px; padding: 9px; border: solid 1px #E7E7E7 }
.special-list .special-cover img { width: 1180px; height: 245px; }

.nc-appbar-tabs a.compare { display: none !important; }
</style>
<div class="warp-all">
  <div class="mainbox">
    <?php if(!empty($output['special_list']) && is_array($output['special_list'])) {?>
    <ul class="special-list">
      <?php foreach($output['special_list'] as $value) {?>
      <li>
        <h3 class="special-title"> <a href="<?php echo $value['special_link'];?>" target="_blank"> <?php echo $value['special_title'];?> </a> </h3>
        <div class="special-cover"> <a href="<?php echo $value['special_link'];?>" target="_blank"> <img src="<?php echo getCMSSpecialImageUrl($value['special_image']);?>" alt="" /></a> </div>
      </li>
      <?php } ?>
    </ul>
    <div class="pagination"> <?php echo $output['show_page'];?> </div>
    <?php } else { ?>
    <div class="no-content">暂无专题内容</div>
    <?php } ?>
  </div>
</div>
