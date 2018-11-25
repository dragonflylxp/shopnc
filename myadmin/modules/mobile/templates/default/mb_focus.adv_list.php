<?php defined('Inshopec') or exit('Access Invalid!');?>
<style>
</style>

<div class="explanation" id="explanation">
  <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
    <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
    <span id="explanationZoom" title="收起提示"></span>
  </div>
  <ul>
    <li>点击添加新的广告条按钮可以添加新的广告条，最多可添加5条广告条</li>
    <li>鼠标移动到已有的广告条上点击出现的删除按钮可以删除对应的广告条</li>
    <li>鼠标移动到已有的广告条上点击出现的编辑按钮可以编辑对应的广告条</li>
  </ul>
</div>
<div class="index_block adv_list">
  <h3>广告条版块</h3>
  <div nctype="item_content" class="content">
    <h5>内容：</h5>
    <?php if(!empty($output['focus_list']) && is_array($output['focus_list'])) {?>
    <?php foreach($output['focus_list'] as $key => $item_value) {?>
    <div nctype="item_image" class="item"> <img nctype="image" src="<?php echo $item_value['focus_image_url'];?>" alt="">
      <input nctype="focus_sort" name="item_data[item][<?php echo $item_value['focus_image'];?>][image]" type="hidden" value="<?php echo $item_value['focus_sort'];?>">
      <input nctype="image_name" name="item_data[item][<?php echo $item_value['focus_image'];?>][image]" type="hidden" value="<?php echo $item_value['focus_image'];?>">
      <input nctype="image_type" name="item_data[item][<?php echo $item_value['focus_image'];?>][type]" type="hidden" value="<?php echo $item_value['focus_type'];?>">
      <input nctype="image_data" name="item_data[item][<?php echo $item_value['focus_image'];?>][data]" type="hidden" value="<?php echo $item_value['focus_url'];?>">
      <a data-id="<?php echo $item_value['focus_id'] ?>" nctype="btn_edit_item_image" href="javascript:;" style="right:65px;"><i class="fa fa-pencil-square-o"></i>编辑</a>
      <a data-id="<?php echo $item_value['focus_id'] ?>" nctype="btn_del_item_image" href="javascript:;"><i class="fa fa-trash-o"></i>删除</a>
    </div>
    <?php } ?>
    <?php } ?>
  </div>
  <?php if($output['count_focus'] < 5) { ?>
    <a nctype="btn_add_item_image" class="ncap-btn" data-desc="640*340" href="javascript:;"><i class="fa fa-plus"></i>添加新的广告条</a>
  <?php } ?>

</div>
