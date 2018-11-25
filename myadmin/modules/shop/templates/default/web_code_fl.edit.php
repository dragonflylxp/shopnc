<?php defined('Inshopec') or exit('Access Invalid!');?>
<script type="text/javascript">
var SHOP_SITE_URL = "<?php echo SHOP_SITE_URL; ?>";
var UPLOAD_SITE_URL = "<?php echo UPLOAD_SITE_URL; ?>";
</script>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?con=web_config&fun=web_config" title="返回板块区列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['web_config_index'];?> - 设计“<?php echo $output['web_array']['web_name']?>”板块</h3>
        <h5><?php echo $lang['nc_web_index_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>所有相关设置完成，使用底部的“更新板块内容”前台展示页面才会变化。</li>
      <li>左侧的“推荐分类”没有个数限制，但是如果太多会不显示(已选择的子分类可以拖动进行排序，单击选中，双击删除)。</li>
      <li>中部的“商品推荐模块”由于页面宽度只能加9个，商品数为8个；底部的品牌最多为10个(已选择的可以拖动进行排序，单击选中，双击删除)。</li>
    </ul>
  </div>
  <div class="ncap-form-all">
    <div class="title">
      <h3><?php echo $lang['web_config_style_name'];?>:<?php echo $output['style_array'][$output['web_array']['style_name']];?></h3>
      <a href="index.php?con=web_config&fun=web_edit&web_id=<?php echo $output['web_array']['web_id'];?>">设置该板块色彩</a> </div>
    <dl class="row">
      <dt class="tit">
        <label><?php echo $lang['web_config_edit_html'].$lang['nc_colon'];?></label>
      </dt>
      <dd class="opt">
        <div class="home-templates-board-layout style-<?php echo $output['web_array']['style_name'];?>">
          <div class="left">
            <dl id="left_tit">
              <dt>
                <h4><?php echo $lang['web_config_picture_tit'];?></h4>
                <a href="JavaScript:show_dialog('upload_tit');"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a></dt>
              <dd class="tit-txt" <?php if($output['code_tit']['code_info']['type'] != 'txt'){ ?>style="display:none;"<?php } ?>>
                <div id="picture_floor" class="txt-type"> <span><?php echo $output['code_tit']['code_info']['floor'];?></span>
                  <h2><?php echo $output['code_tit']['code_info']['title'];?></h2>
                </div>
              </dd>
              <dd class="tit-pic" <?php if($output['code_tit']['code_info']['type'] == 'txt'){ ?>style="display:none;"<?php } ?>>
                <div id="picture_tit" class="picture"> <img src="<?php echo UPLOAD_SITE_URL.'/'.$output['code_tit']['code_info']['pic'];?>"/> </div>
              </dd>
            </dl>
            <dl>
              <dt>
                <h4><?php echo $lang['web_config_edit_category'];?></h4>
                <a href="JavaScript:show_dialog('category_list');"><i class="icon-th"></i><?php echo $lang['nc_edit'];?></a></dt>
              <dd class="category-list g02">
                <?php if (is_array($output['code_category_list']['code_info']['goods_class']) && !empty($output['code_category_list']['code_info']['goods_class'])) { ?>
                <ul>
                  <?php foreach ($output['code_category_list']['code_info']['goods_class'] as $k => $v) { ?>
                  <li title="<?php echo $v['gc_name'];?>"><a href="javascript:void(0);"><?php echo $v['gc_name'];?></a></li>
                  <?php } ?>
                </ul>
                <?php }else { ?>
                <ul>
                  <li><a href="javascript:void(0);"><?php echo $lang['web_config_gc_name'];?></a></li>
                  <li><a href="javascript:void(0);"><?php echo $lang['web_config_gc_name'];?></a></li>
                  <li><a href="javascript:void(0);"><?php echo $lang['web_config_gc_name'];?></a></li>
                  <li><a href="javascript:void(0);"><?php echo $lang['web_config_gc_name'];?></a></li>
                  <li><a href="javascript:void(0);"><?php echo $lang['web_config_gc_name'];?></a></li>
                  <li><a href="javascript:void(0);"><?php echo $lang['web_config_gc_name'];?></a></li>
                  <li><a href="javascript:void(0);"><?php echo $lang['web_config_gc_name'];?></a></li>
                  <li><a href="javascript:void(0);"><?php echo $lang['web_config_gc_name'];?></a></li>
                </ul>
                <?php } ?>
              </dd>
            </dl>
            <dl>
              <dt>
                <h4>切换广告图片</h4>
                <a href="JavaScript:show_dialog('upload_adv');"><?php echo $lang['nc_edit'];?></a></dt>
              <dd class="adv-pic">
                <div id="picture_adv" class="picture g03">
                  <?php if(is_array($output['code_adv']['code_info']) && !empty($output['code_adv']['code_info'])) {
					        	$adv = current($output['code_adv']['code_info']);
					        	?>
                  <?php if(is_array($adv) && !empty($adv)) { ?>
                  <img src="<?php echo UPLOAD_SITE_URL.'/'.$adv['pic_img'];?>"/>
                  <?php } ?>
                  <?php } ?>
                </div>
              </dd>
            </dl>
          </div>
          <div class="middle middle01">
            <div>
              <?php if (is_array($output['code_recommend_list']['code_info']) && !empty($output['code_recommend_list']['code_info'])) { ?>
              <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) { ?>
              <dl recommend_id="<?php echo $key;?>">
                <dt>
                  <h4><?php echo $val['recommend']['name'];?></h4>
                  <a href="JavaScript:del_recommend(<?php echo $key;?>);"><i class="fa fa-trash"></i><?php echo $lang['nc_del'];?></a> <a href="JavaScript:show_recommend_dialog(<?php echo $key;?>);"><i class="fa fa-shopping-cart"></i><?php echo '商品块';?></a> 
                  <a href="JavaScript:show_recommend_pic2_dialog(<?php echo $key;?>,2);"><i class="icon-lightbulb"></i>广告块2</a>
                  <a href="JavaScript:show_recommend_pic2_dialog(<?php echo $key;?>,1);"><i class="icon-lightbulb"></i>广告块1</a> </dt>
                <dd>
                  <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
                  <ul class="goods-list goods-list01">
                    <?php foreach($val['goods_list'] as $k => $v) { ?>
                    <li><span><a href="javascript:void(0);"> <img title="<?php echo $v['goods_name'];?>" src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>"/></a></span> </li>
                    <?php } ?>
                  </ul>
                  <?php } elseif (!empty($val['pic_list2']) && is_array($val['pic_list2'])) { ?>
                  <div class="middle-banner middle-banner01"> 
                    <a href="javascript:void(0);" class="left-a left-a01"><img pic_url="<?php echo $val['pic_list2']['11']['pic_url'];?>" title="<?php echo $val['pic_list2']['11']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['11']['pic_img'];?>"/></a> 
                    <a href="javascript:void(0);" class="left-b left-b01"><img pic_url="<?php echo $val['pic_list2']['12']['pic_url'];?>" title="<?php echo $val['pic_list2']['12']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['12']['pic_img'];?>"/></a> 
                    <a href="javascript:void(0);" class="bottom-a bottom-a01"><img pic_url="<?php echo $val['pic_list2']['31']['pic_url'];?>" title="<?php echo $val['pic_list2']['31']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['31']['pic_img'];?>"/></a> 
                    <a href="javascript:void(0);" class="bottom-b bottom-b01"><img pic_url="<?php echo $val['pic_list2']['32']['pic_url'];?>" title="<?php echo $val['pic_list2']['32']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['32']['pic_img'];?>"/></a> 
                    <a href="javascript:void(0);" class="middle-a middle-a01"><img pic_url="<?php echo $val['pic_list2']['50']['pic_url'];?>" title="<?php echo $val['pic_list2']['50']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['50']['pic_img'];?>"/></a> 
                    <?php if (!empty($val['pic_list2']['33']) || !empty($val['pic_list2']['34'])) { ?>
                    <a href="javascript:void(0);" class="bottom-c bottom-c01"><img pic_url="<?php echo $val['pic_list2']['33']['pic_url'];?>" title="<?php echo $val['pic_list2']['33']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['33']['pic_img'];?>"/></a> 
                    <a href="javascript:void(0);" class="bottom-d bottom-d01"><img pic_url="<?php echo $val['pic_list2']['34']['pic_url'];?>" title="<?php echo $val['pic_list2']['34']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['34']['pic_img'];?>"/></a> 
                    <?php }else { ?>
                    <a href="javascript:void(0);" class="middle-b middle-b01"><img pic_url="<?php echo $val['pic_list2']['60']['pic_url'];?>" title="<?php echo $val['pic_list2']['60']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['60']['pic_img'];?>"/></a> 
                    <?php } ?>
                    <a href="javascript:void(0);" class="right-a right-a01"><img pic_url="<?php echo $val['pic_list2']['21']['pic_url'];?>" title="<?php echo $val['pic_list2']['21']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['21']['pic_img'];?>"/></a> 
                    <a href="javascript:void(0);" class="right-b right-b01"><img pic_url="<?php echo $val['pic_list2']['24']['pic_url'];?>" title="<?php echo $val['pic_list2']['24']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['24']['pic_img'];?>"/></a> 
                  </div>
                  <?php }else { ?>
                  <ul class="goods-list goods-list01">
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                  </ul>
                  <?php } ?>
                </dd>
              </dl>
              <?php } ?>
              <?php } ?>
              <div class="add-tab" id="btn_add_list"><a href="JavaScript:add_recommend();"><i class="icon-plus-sign-alt"></i><?php echo $lang['web_config_add_recommend'];?></a>(最多9个)</div>
            </div>
          </div>
          <div class="right right01">
            <dl>
              <dt>
                <h4><?php echo $lang['web_config_brand_title'];?></h4>
                <a href="JavaScript:show_dialog('brand_list');"><i class="icon-ticket"></i><?php echo $lang['nc_edit'];?></a></dt>
              <dd>
                <ul class="brands brands01">
                  <?php if (is_array($output['code_brand_list']['code_info']) && !empty($output['code_brand_list']['code_info'])) { ?>
                  <?php foreach ($output['code_brand_list']['code_info'] as $key => $val) { ?>
                  <li><span><img title="<?php echo $val['brand_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['brand_pic'];?>"/> </span></li>
                  <?php } ?>
                  <?php }else { ?>
                  <li> <span><i class="fa fa-picture-o"></i></span> </li>
                  <li> <span><i class="fa fa-picture-o"></i></span> </li>
                  <li> <span><i class="fa fa-picture-o"></i></span> </li>
                  <li> <span><i class="fa fa-picture-o"></i></span> </li>
                  <li> <span><i class="fa fa-picture-o"></i></span> </li>
                  <li> <span><i class="fa fa-picture-o"></i></span> </li>
                  <li> <span><i class="fa fa-picture-o"></i></span> </li>
                  <li> <span><i class="fa fa-picture-o"></i></span> </li>
                  <li> <span><i class="fa fa-picture-o"></i></span> </li>
                  <li> <span><i class="fa fa-picture-o"></i></span> </li>
                  <?php } ?>
                </ul>
              </dd>
            </dl>
          </div>
        </div>
      </dd>
    </dl>
  </div>
  <div class="bot"><a href="index.php?con=web_config&fun=web_html&web_id=<?php echo $_GET['web_id'];?>" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['web_config_web_html'];?></a> </div>
</div>

<!-- 标题图片 -->
<div id="upload_tit_dialog" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo $lang['web_config_prompt_tit'];?></div>
  <form id="upload_tit_form" name="upload_tit_form" enctype="multipart/form-data" method="post" action="index.php?con=web_config&fun=upload_pic" target="upload_pic">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="web_id" value="<?php echo $output['code_tit']['web_id'];?>">
    <input type="hidden" name="code_id" value="<?php echo $output['code_tit']['code_id'];?>">
    <input type="hidden" name="tit[pic]" value="<?php echo $output['code_tit']['code_info']['pic'];?>">
    <input type="hidden" name="tit[url]" value="">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit"><?php echo $lang['web_config_upload_type'].$lang['nc_colon'];?></dt>
        <dd class="opt">
          <label title="<?php echo $lang['web_config_upload_pic'];?>">
            <input type="radio" name="tit[type]" value="pic" onclick="upload_type('tit');" <?php if($output['code_tit']['code_info']['type'] != 'txt'){ ?>checked="checked"<?php } ?>>
            <span><?php echo $lang['web_config_upload_pic'];?></span></label>
          <label title="<?php echo '文字类型';?>">
            <input type="radio" name="tit[type]" value="txt" onclick="upload_type('tit');" <?php if($output['code_tit']['code_info']['type'] == 'txt'){ ?>checked="checked"<?php } ?>>
            <span><?php echo '文字类型';?></span></label>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl id="upload_tit_type_pic" class="row" <?php if($output['code_tit']['code_info']['type'] == 'txt'){ ?>style="display:none;"<?php } ?>>
        <dt class="tit"><?php echo $lang['web_config_upload_tit'].$lang['nc_colon'];?></dt>
        <dd class="opt">
          <div class="input-file-show"> <span class="type-file-box">
            <input type='text' name='textfield' id='textfield1' class='type-file-text' />
            <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
            <input name="pic" id="pic" type="file" class="type-file-file" size="30">
            </span></div>
          <p class="notic"><?php echo $lang['web_config_upload_tit_tips'];?></p>
        </dd>
      </dl>
      <div id="upload_tit_type_txt" <?php if($output['code_tit']['code_info']['type'] != 'txt'){ ?>style="display:none;"<?php } ?>>
        <dl class="row">
          <dt class="tit"><?php echo '楼层编号';?></dt>
          <dd class="opt">
            <input class="input-txt" type="text" name="tit[floor]" id="tit_floor" value="<?php echo $output['code_tit']['code_info']['floor'];?>">
            <p class="notic"><?php echo '如1F、2F、3F。';?></p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit"><?php echo '版块标题';?></dt>
          <dd class="opt">
            <input class="input-txt" type="text" name="tit[title]" id="tit_title" value="<?php echo $output['code_tit']['code_info']['title'];?>">
            <p class="notic"><?php echo '如鞋包配饰、男女服装、运动户外。';?></p>
          </dd>
        </dl>
      </div>
      <div class="bot"><a href="JavaScript:void(0);" onclick="$('#upload_tit_form').submit();" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<!-- 推荐分类模块 -->
<div id="category_list_dialog" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo $lang['web_config_category_tips'];?></div>
  <form id="category_list_form">
    <input type="hidden" name="web_id" value="<?php echo $output['code_category_list']['web_id'];?>">
    <input type="hidden" name="code_id" value="<?php echo $output['code_category_list']['code_id'];?>">
    <div class="ncap-form-all">
      <dl class="row">
        <dt class="tit">已选商品分类</dt>
        <dd class="opt">
          <div class="category-list category-list-edit">
            <ul>
              <?php if (is_array($output['code_category_list']['code_info']['goods_class']) && !empty($output['code_category_list']['code_info']['goods_class'])) { ?>
              <?php foreach($output['code_category_list']['code_info']['goods_class'] as $k => $v) { ?>
              <li gc_id="<?php echo $v['gc_id'];?>" gc_name="<?php echo $v['gc_name'];?>" title="<?php echo $v['gc_name'];?>" ondblclick="del_goods_class(<?php echo $v['gc_id'];?>);"><i onclick="del_goods_class(<?php echo $v['gc_id'];?>);"></i><?php echo $v['gc_name'];?>
                <input name="category_list[goods_class][<?php echo $v['gc_id'];?>][gc_id]" value="<?php echo $v['gc_id'];?>" type="hidden">
                <input name="category_list[goods_class][<?php echo $v['gc_id'];?>][gc_name]" value="<?php echo $v['gc_name'];?>" type="hidden">
                 </li>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['web_config_category_title'];?></dt>
        <dd class="opt"><div class="search-bar">商品分类：
          <select name="gc_parent_id" id="gc_parent_id" onchange="get_goods_class();">
            <option value="0"><?php echo $lang['nc_please_choose'];?></option>
            <?php if(!empty($output['parent_goods_class']) && is_array($output['parent_goods_class'])) { ?>
            <?php foreach($output['parent_goods_class'] as $k => $v) { ?>
            <option value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
            <?php } ?>
            <?php } ?>
          </select></div>
          <p class="notic"><?php echo $lang['web_config_category_note'];?></p>
        </dd>
      </dl>
    </div>
    <div class="bot"><a href="JavaScript:void(0);" onclick="update_category();" class="ncap-btn-big ncap-btn-green"><?php echo $lang['web_config_save'];?></a></div>
  </form>
</div>
<!-- 活动图片 -->
<div id="upload_act_dialog" class="upload_act_dialog" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo $lang['web_config_prompt_act'];?></div>
  <form id="upload_act_form" name="upload_act_form" enctype="multipart/form-data" method="post" action="index.php?con=web_config&fun=upload_pic" target="upload_pic">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="web_id" value="<?php echo $output['code_act']['web_id'];?>">
    <input type="hidden" name="code_id" value="<?php echo $output['code_act']['code_id'];?>">
    <input type="hidden" name="act[pic]" value="<?php echo $output['code_act']['code_info']['pic'];?>">
    <input type="hidden" name="act[type]" value="pic">
    <div class="ncap-form-default" id="upload_act_type_pic" <?php if($output['code_act']['code_info']['type'] == 'adv') { ?>style="display:none;"<?php } ?>>
      <dl class="row">
        <dt class="tit"><?php echo '活动名称';?></dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="act[title]" value="<?php echo $output['code_act']['code_info']['title'];?>">
          <p class="notic"><?php echo '';?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['web_config_upload_url'].$lang['nc_colon'];?></label>
        </dt>
        <dd class="opt">
          <input name="act[url]" value="<?php echo !empty($output['code_act']['code_info']['url']) ? $output['code_act']['code_info']['url']:SHOP_SITE_URL;?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['web_config_upload_act_url'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['web_config_upload_act'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type='text' name='textfield' id='textfield1' class='type-file-text' />
            <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
            <input name="pic" id="pic" type="file" class="type-file-file" size="30">
            </span></div>
          <p class="notic"><?php echo $lang['web_config_upload_act_tips'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" onclick="$('#upload_act_form').submit();" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<!-- 商品推荐模块 -->
<div id="recommend_list_dialog" style="display:none;">
  <div class="s-tips"><i></i><?php echo $lang['web_config_recommend_goods_tips'];?></div>
  <form id="recommend_list_form">
    <input type="hidden" name="web_id" value="<?php echo $output['code_recommend_list']['web_id'];?>">
    <input type="hidden" name="code_id" value="<?php echo $output['code_recommend_list']['code_id'];?>">
    <div id="recommend_input_list" style="display:none;"><!-- 推荐拖动排序 --></div>
    <?php if (is_array($output['code_recommend_list']['code_info']) && !empty($output['code_recommend_list']['code_info'])) { ?>
    <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) { ?>
    <div class="ncap-form-default" select_recommend_id="<?php echo $key;?>">
      <dl class="row">
        <dt class="tit"> <?php echo $lang['web_config_recommend_title'];?></dt>
        <dd class="opt">
          <input name="recommend_list[<?php echo $key;?>][recommend][name]" value="<?php echo $val['recommend']['name'];?>" type="text" class="input-txt">
          <p class="notic"><?php echo $lang['web_config_recommend_tips'];?></p>
        </dd>
      </dl>
    </div>
    <div class="ncap-form-all" select_recommend_id="<?php echo $key;?>">
      <dl class="row">
        <dt class="tit"><?php echo $lang['web_config_recommend_goods'];?></dt>
        <dd class="opt">
          <ul class="dialog-goodslist-s1 goods-list">
            <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
            <?php foreach($val['goods_list'] as $k => $v) { ?>
            <li id="select_recommend_<?php echo $key;?>_goods_<?php echo $k;?>">
              <div ondblclick="del_recommend_goods(<?php echo $v['goods_id'];?>);" class="goods-pic"> <span class="ac-ico" onclick="del_recommend_goods(<?php echo $v['goods_id'];?>);"></span> <span class="thumb size-72x72"><i></i><img select_goods_id="<?php echo $v['goods_id'];?>" title="<?php echo $v['goods_name'];?>" goods_name="<?php echo $v['goods_name'];?>" src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>" onload="javascript:DrawImage(this,72,72);" /></span></div>
              <div class="goods-name"><a href="<?php echo SHOP_SITE_URL."/index.php?con=goods&goods_id=".$v['goods_id'];?>" target="_blank"><?php echo $v['goods_name'];?></a></div>
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_id]" value="<?php echo $v['goods_id'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][market_price]" value="<?php echo $v['market_price'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_name]" value="<?php echo $v['goods_name'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_price]" value="<?php echo $v['goods_price'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_pic]" value="<?php echo $v['goods_pic'];?>" type="hidden">
            </li>
            <?php } ?>
            <?php } elseif (!empty($val['pic_list2']) && is_array($val['pic_list2'])) { ?>
            <?php foreach($val['pic_list2'] as $k => $v) { ?>
            <li id="select_recommend_<?php echo $key;?>_pic_<?php echo $k;?>" style="display:none;">
              <input name="recommend_list[<?php echo $key;?>][pic_list2][<?php echo $v['pic_id'];?>][pic_id]" value="<?php echo $v['pic_id'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][pic_list2][<?php echo $v['pic_id'];?>][pic_name]" value="<?php echo $v['pic_name'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][pic_list2][<?php echo $v['pic_id'];?>][pic_url]" value="<?php echo $v['pic_url'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][pic_list2][<?php echo $v['pic_id'];?>][pic_img]" value="<?php echo $v['pic_img'];?>" type="hidden">
            </li>
            <?php } ?>
            <?php } ?>
          </ul>
        </dd>
      </dl>
    </div>
    <?php } ?>
    <?php } ?>
    <div id="add_recommend_list" style="display:none;"></div>
    <div class="ncap-form-all">
      <dl class="row">
        <dt class="tit"><?php echo $lang['web_config_recommend_add_goods'];?></dt>
        <dd class="opt">
          <div class="search-bar">
            <label id="recommend_gcategory">商品分类
              <input type="hidden" id="cate_id" name="cate_id" value="0" class="mls_id" />
              <input type="hidden" id="cate_name" name="cate_name" value="" class="mls_names" />
              <select>
                <option value="0"><?php echo $lang['nc_please_choose'];?></option>
                <?php if(!empty($output['goods_class']) && is_array($output['goods_class'])) { ?>
                <?php foreach($output['goods_class'] as $k => $v) { ?>
                <option value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </label>
            <input type="text" value="" name="recommend_goods_name" id="recommend_goods_name" placeholder="输入商品名称或SKU编号" class="txt w150">
            <a href="JavaScript:void(0);" onclick="get_recommend_goods();" class="ncap-btn"><?php echo $lang['nc_query'];?></a></div>
          <div id="show_recommend_goods_list" class="show-recommend-goods-list"></div>
        </dd>
      </dl>
    </div>
    <div class="bot"><a href="JavaScript:void(0);" onclick="update_recommend();" class="ncap-btn-big ncap-btn-green"><span><?php echo $lang['web_config_save'];?></span></a></div>
  </form>
</div>
<!-- 中部推荐图片 -->
<div id="recommend_pic2_dialog" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo '单击广告图选中对应的位置，在底部上传和修改图片信息。';?></div>
  <form id="recommend_pic2_form" name="recommend_pic2_form" enctype="multipart/form-data" method="post" action="index.php?con=web_config&fun=recommend_pic2" target="upload_pic">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="web_id" value="<?php echo $output['code_recommend_list']['web_id'];?>">
    <input type="hidden" name="code_id" value="<?php echo $output['code_recommend_list']['code_id'];?>">
    <input type="hidden" name="key_id" value="">
    <input type="hidden" name="pic_id" value="">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit"><?php echo '推荐模块标题名称';?></dt>
        <dd class="opt">
          <input name="recommend_list[recommend][name]" value="" type="text" class="input-txt">
          <p class="notic"><?php echo ' 修改该区域中部推荐模块选项卡名称，控制名称字符在4-8字左右，超出范围自动隐藏';?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">广告图区域选择</dt>
        <dd class="opt" id="add_recommend_pic">
          <?php if (is_array($output['code_recommend_list']['code_info']) && !empty($output['code_recommend_list']['code_info'])) { ?>
          <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) { ?>
          <?php if (!empty($val['pic_list2']) && is_array($val['pic_list2'])) { ?>
          <div select_recommend_pic_id="<?php echo $key;?>" class="middle-banner middle-banner01"> 
            <a recommend_pic_id="11" href="javascript:void(0);" class="left-a left-a01"><img pic_url="<?php echo $val['pic_list2']['11']['pic_url'];?>" title="<?php echo $val['pic_list2']['11']['pic_name'];?>" pic_name="<?php echo $val['pic_list2']['11']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['11']['pic_img'];?>"/></a> 
            <a recommend_pic_id="12" href="javascript:void(0);" class="left-b left-b01"><img pic_url="<?php echo $val['pic_list2']['12']['pic_url'];?>" title="<?php echo $val['pic_list2']['12']['pic_name'];?>" pic_name="<?php echo $val['pic_list2']['12']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['12']['pic_img'];?>"/></a> 
            <a recommend_pic_id="31" href="javascript:void(0);" class="bottom-a bottom-a01"><img pic_url="<?php echo $val['pic_list2']['31']['pic_url'];?>" title="<?php echo $val['pic_list2']['31']['pic_name'];?>" pic_name="<?php echo $val['pic_list2']['31']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['31']['pic_img'];?>"/></a> 
            <a recommend_pic_id="32" href="javascript:void(0);" class="bottom-b bottom-b01"><img pic_url="<?php echo $val['pic_list2']['32']['pic_url'];?>" title="<?php echo $val['pic_list2']['32']['pic_name'];?>" pic_name="<?php echo $val['pic_list2']['32']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['32']['pic_img'];?>"/></a> 
            <a recommend_pic_id="50" href="javascript:void(0);" class="middle-a middle-a01"><img pic_url="<?php echo $val['pic_list2']['50']['pic_url'];?>" title="<?php echo $val['pic_list2']['50']['pic_name'];?>" pic_name="<?php echo $val['pic_list2']['50']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['50']['pic_img'];?>"/></a> 
            <a recommend_pic_id="33" href="javascript:void(0);" class="bottom-c bottom-c01"><img pic_url="<?php echo $val['pic_list2']['33']['pic_url'];?>" title="<?php echo $val['pic_list2']['33']['pic_name'];?>" pic_name="<?php echo $val['pic_list2']['33']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['33']['pic_img'];?>"/></a> 
            <a recommend_pic_id="34" href="javascript:void(0);" class="bottom-d bottom-d01"><img pic_url="<?php echo $val['pic_list2']['34']['pic_url'];?>" title="<?php echo $val['pic_list2']['34']['pic_name'];?>" pic_name="<?php echo $val['pic_list2']['34']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['34']['pic_img'];?>"/></a> 
            <a recommend_pic_id="60" href="javascript:void(0);" class="middle-b middle-b01"><img pic_url="<?php echo $val['pic_list2']['60']['pic_url'];?>" title="<?php echo $val['pic_list2']['60']['pic_name'];?>" pic_name="<?php echo $val['pic_list2']['60']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['60']['pic_img'];?>"/></a> 
            <a recommend_pic_id="21" href="javascript:void(0);" class="right-a right-a01"><img pic_url="<?php echo $val['pic_list2']['21']['pic_url'];?>" title="<?php echo $val['pic_list2']['21']['pic_name'];?>" pic_name="<?php echo $val['pic_list2']['21']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['21']['pic_img'];?>"/></a> 
            <a recommend_pic_id="24" href="javascript:void(0);" class="right-b right-b01"><img pic_url="<?php echo $val['pic_list2']['24']['pic_url'];?>" title="<?php echo $val['pic_list2']['24']['pic_name'];?>" pic_name="<?php echo $val['pic_list2']['24']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list2']['24']['pic_img'];?>"/></a> 
          </div>
          <?php } ?>
          <?php } ?>
          <?php } ?>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo '文字标题';?></dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="pic_list2[pic_name]" value="">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo '跳转链接';?></dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="pic_list2[pic_url]" value="<?php echo SHOP_SITE_URL;?>">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo '图片上传';?></dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type='text' name='textfield' id='textfield1' value='' class='type-file-text' />
            <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
            <input name="pic" id="pic" type="file" class="type-file-file" value='' size="30">
            </span></div>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" onclick="$('#recommend_pic2_form').submit();" class="ncap-btn-big ncap-btn-green"><span><?php echo $lang['web_config_save'];?></span></a></div>
    </div>
  </form>
</div>
<!-- 品牌模块 -->
<div id="brand_list_dialog" class="brand_list_dialog" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i>小提示：单击候选品牌选中，双击已选择的可以删除，最多10个，保存后生效。</div>
  <form id="brand_list_form">
    <input type="hidden" name="web_id" value="<?php echo $output['code_brand_list']['web_id'];?>">
    <input type="hidden" name="code_id" value="<?php echo $output['code_brand_list']['code_id'];?>">
    <div class="ncap-form-all">
      <dl class="row">
        <dt class="tit"><?php echo '已选择品牌';?></dt>
        <dd class="opt">
          <ul class="brands dialog-brandslist-s1">
            <?php if (is_array($output['code_brand_list']['code_info']) && !empty($output['code_brand_list']['code_info'])) { ?>
            <?php foreach ($output['code_brand_list']['code_info'] as $key => $val) { ?>
            <li>
              <div class="brands-pic"><span class="ac-ico" onclick="del_brand(<?php echo $val['brand_id'];?>);"></span><span class="thumb size-88x29"><i></i><img ondblclick="del_brand(<?php echo $val['brand_id'];?>);" select_brand_id="<?php echo $val['brand_id'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['brand_pic'];?>" onload="javascript:DrawImage(this,88,30);" /></span></div>
              <div class="brands-name"><?php echo $val['brand_name'];?></div>
              <input name="brand_list[<?php echo $val['brand_id'];?>][brand_id]" value="<?php echo $val['brand_id'];?>" type="hidden">
              <input name="brand_list[<?php echo $val['brand_id'];?>][brand_name]" value="<?php echo $val['brand_name'];?>" type="hidden">
              <input name="brand_list[<?php echo $val['brand_id'];?>][brand_pic]" value="<?php echo $val['brand_pic'];?>" type="hidden">
            </li>
            <?php } ?>
            <?php } ?>
          </ul>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['web_config_brand_list'];?></dt>
        <dd class="opt">
          <div class="search-bar">
            <input type="text" value="" name="recommend_brand_name" id="recommend_brand_name" placeholder="请输入品牌名称" class="txt w100">
            <select name="recommend_brand_initial" id="recommend_brand_initial">
              <option value="">首字母</option>
              <?php foreach(range('A','Z') as $k => $v){ ?>
              <option value="<?php echo $v;?>"><?php echo $v;?></option>
              <?php } ?>
            </select>
            <a href="JavaScript:void(0);" onclick="get_recommend_brand();" class="ncap-btn"><?php echo $lang['nc_query'];?></a> </div>
          <div id="show_brand_list"></div>
        </dd>
      </dl>
      <div class="bot"> <a href="JavaScript:void(0);" onclick="update_brand();" class="ncap-btn-big ncap-btn-green"><?php echo $lang['web_config_save'];?></a></div>
    </div>
  </form>
</div>
<!-- 切换广告图片 -->
<div id="upload_adv_dialog" class="upload_adv_dialog" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo '小提示：单击图片选中修改，拖动可以排序，最少保留1个，最多可加5个，保存后生效。';?></div>
  <form id="upload_adv_form" name="upload_adv_form" enctype="multipart/form-data" method="post" action="index.php?con=web_config&fun=slide_adv" target="upload_pic">
    <input type="hidden" name="web_id" value="<?php echo $output['code_adv']['web_id'];?>">
    <input type="hidden" name="code_id" value="<?php echo $output['code_adv']['code_id'];?>">
    <div class="ncap-form-all">
      <dl class="row">
        <dt class="tit"><?php echo '已上传图片';?></dt>
        <dd class="opt">
          <ul class="adv dialog-adv-s1">
            <?php if (is_array($output['code_adv']['code_info']) && !empty($output['code_adv']['code_info'])) { ?>
            <?php foreach ($output['code_adv']['code_info'] as $key => $val) { ?>
            <?php if (is_array($val) && !empty($val)) { ?>
            <li slide_adv_id="<?php echo $val['pic_id'];?>">
              <div class="adv-pic"><span class="ac-ico" onclick="del_slide_adv(<?php echo $val['pic_id'];?>);"></span><img onclick="select_slide_adv(<?php echo $val['pic_id'];?>);" title="<?php echo $val['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_img'];?>"/></div>
              <input name="adv[<?php echo $val['pic_id'];?>][pic_id]" value="<?php echo $val['pic_id'];?>" type="hidden">
              <input name="adv[<?php echo $val['pic_id'];?>][pic_name]" value="<?php echo $val['pic_name'];?>" type="hidden">
              <input name="adv[<?php echo $val['pic_id'];?>][pic_url]" value="<?php echo $val['pic_url'];?>" type="hidden">
              <input name="adv[<?php echo $val['pic_id'];?>][pic_img]" value="<?php echo $val['pic_img'];?>" type="hidden">
            </li>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </ul>
          <a class="ncap-btn" href="JavaScript:add_slide_adv();"><i class="fa fa-plus"></i><?php echo '新增图片';?>&nbsp;(最多5个)</a>
          <span id="adv_slide_succ" class="web-save-succ" style="display:none;"><?php echo $lang['nc_common_save_succ'];?></span></dd>
      </dl>
    </div>
    <div id="upload_slide_adv" class="ncap-form-default" style="display:none;">
      <dl class="row">
        <dt class="tit"><?php echo '文字标题';?></dt>
        <dd class="opt">
          <input type="hidden" name="slide_id" value="">
          <input class="input-txt" type="text" name="slide_pic[pic_name]" value="">
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['web_config_upload_url'];?></label>
        </dt>
        <dd class="opt">
          <input name="slide_pic[pic_url]" value="" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['web_config_adv_url_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['web_config_upload_adv_pic'].$lang['nc_colon'];?></dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type='text' name='textfield' id='textfield1' class='type-file-text' />
            <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
            <input name="pic" id="pic" type="file" class="type-file-file" size="30">
            </span></div>
          <p class="notic">建议上传宽200*高310像素GIF\JPG\PNG格式图片，超出规定范围的图片部分将被自动隐藏。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" onclick="$('#upload_adv_form').submit();" class="ncap-btn-big ncap-btn-green"><?php echo $lang['web_config_save'];?></a></div>
    </div>
  </form>
</div>
<!-- 商品推荐切换广告图片 -->
<div id="recommend_adv_dialog" class="recommend_adv_dialog" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i>小提示：单击图片选中修改，拖动可以排序，最少保留1个，最多可加5个，保存后生效。</div>
  <form id="recommend_adv_form" name="recommend_adv_form" enctype="multipart/form-data" method="post" action="index.php?con=web_config&fun=recommend_slide" target="upload_pic">
    <input type="hidden" name="web_id" value="<?php echo $output['code_recommend_list']['web_id'];?>">
    <input type="hidden" name="code_id" value="<?php echo $output['code_recommend_list']['code_id'];?>">
    <input type="hidden" name="key_id" value="">
    <input type="hidden" name="pic_id" value="">
    <div class="ncap-form-all">
      <dl class="row">
        <dt class="tit">已上传图片</dt>
        <dd class="opt">
          <ul class="adv dialog-adv-s1">
          </ul>
          <a class="ncap-btn" href="JavaScript:add_recommend_slide();"><i class="fa fa-plus"></i>新增图片&nbsp;(最多5个)</a>
          <span id="recommend_slide_succ" class="web-save-succ" style="display:none;"><?php echo $lang['nc_common_save_succ'];?></span></dd>
      </dl>
    </div>
    <div id="recommend_slide_adv" class="ncap-form-default" style="display:none;">
      <dl class="row">
        <dt class="tit">文字标题</dt>
        <dd class="opt">
          <input type="hidden" name="slide_id" value="">
          <input class="input-txt" type="text" name="slide_pic[pic_name]" value="">
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['web_config_upload_url'];?></label>
        </dt>
        <dd class="opt">
          <input name="slide_pic[pic_url]" value="" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['web_config_adv_url_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['web_config_upload_adv_pic'].$lang['nc_colon'];?></dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type='text' name='textfield' id='textfield1' class='type-file-text' />
            <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
            <input name="pic" id="pic" type="file" class="type-file-file" size="30">
            </span></div>
          <p class="notic">建议上传宽400*高230像素GIF\JPG\PNG格式图片，超出规定范围的图片部分将被自动隐藏。
            </p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" onclick="$('#recommend_adv_form').submit();" class="ncap-btn-big ncap-btn-green"><?php echo $lang['web_config_save'];?></a>
        </div>
    </div>
  </form>
</div>
<iframe style="display:none;" src="" name="upload_pic"></iframe>
<script src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script src="<?php echo ADMIN_RESOURCE_URL?>/js/web_index_fl.js"></script>
