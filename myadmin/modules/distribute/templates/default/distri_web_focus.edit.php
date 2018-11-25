<?php defined('Inshopec') or exit('Access Invalid!');?>
<script type="text/javascript">
var SHOP_SITE_URL = "<?php echo SHOP_SITE_URL; ?>";
var UPLOAD_SITE_URL = "<?php echo UPLOAD_SITE_URL; ?>";
var ATTACH_ADV = "<?php echo ATTACH_ADV; ?>";
var screen_adv_list = new Array();//焦点大图广告数据
var screen_adv_append = '';
var focus_adv_list = new Array();//三张联动区广告数据
var focus_adv_append = '';
var adv_info = new Array();
var ap_id = 0;
<?php if(!empty($output['screen_adv_list']) && is_array($output['screen_adv_list'])){ ?>
<?php foreach ($output['screen_adv_list'] as $key => $val) { ?>
adv_info = new Array();
ap_id = "<?php echo $val['ap_id'];?>";
adv_info['ap_id'] = ap_id;
adv_info['ap_name'] = "<?php echo $val['ap_name'];?>";
adv_info['ap_img'] = "<?php echo $val['default_content'];?>";
screen_adv_list[ap_id] = adv_info;
screen_adv_append += '<option value="'+ap_id+'">'+adv_info['ap_name']+'</option>';
<?php } ?>
<?php } ?>
<?php if(!empty($output['focus_adv_list']) && is_array($output['focus_adv_list'])){ ?>
<?php foreach ($output['focus_adv_list'] as $key => $val) { ?>
adv_info = new Array();
ap_id = "<?php echo $val['ap_id'];?>";
adv_info['ap_id'] = ap_id;
adv_info['ap_name'] = "<?php echo $val['ap_name'];?>";
adv_info['ap_img'] = "<?php echo $val['default_content'];?>";
focus_adv_list[ap_id] = adv_info;
focus_adv_append += '<option value="'+ap_id+'">'+adv_info['ap_name']+'</option>';
<?php } ?>
<?php } ?>
</script>
<style type="text/css">
.color {
	position: relative!important;
	z-index: 1!important;
	padding: 0!important;
}
.color .evo-pop {
	bottom: 26px!important;
}
.evo-colorind-ie {
	position: relative;
*top:0/*IE6,7*/ !important;
}
</style>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_web_index'];?></h3>
        <h5><?php echo $lang['nc_web_index_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current"><?php echo '联动焦点组图';?></a></li>
        <li><a href="index.php?con=web_config&fun=adv_list"><?php echo '右侧广告图';?></a></li>
        <li><a href="index.php?con=web_config&fun=web_config"><?php echo '焦点大图';?></a></li>
      </ul>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo '三张联动区一组三个图片，两张联动区一组两个图片，八张联动区一组八个图片。';?></li>
      <li><?php echo '所有相关设置完成，使用底部的“更新板块内容”前台展示页面才会变化。';?></li>
    </ul>
  </div>
  <div class="homepage-focus" id="homepageFocusTab">
    <div class="title">
      <h3>首页焦点区域设置</h3>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current" form="upload_focus_form"><?php echo '三张联动焦点组图';?></a></li>
        <li><a href="JavaScript:void(0);" form="upload_two_focus_form"><?php echo '两张联动焦点组图';?></a></li>
        <li><a href="JavaScript:void(0);" form="upload_eight_focus_form"><?php echo '八张联动焦点组图';?></a></li>
      </ul>
    </div>
    <form id="upload_focus_form" class="tab-content" name="upload_screen_form" enctype="multipart/form-data" method="post" action="index.php?con=web_config&fun=focus_pic" target="upload_pic">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="web_id" value="<?php echo $output['code_focus_list']['web_id'];?>">
      <input type="hidden" name="code_id" value="<?php echo $output['code_focus_list']['code_id'];?>">
      <input type="hidden" name="key" value="">
      <div class="ncap-form-default">
        <dl class="row">
          <dt class="tit">三张联动焦点组图预览</dt>
          <dd class="opt focus-trigeminy">
              <?php if (is_array($output['code_focus_list']['code_info']) && !empty($output['code_focus_list']['code_info'])) { ?>
              <?php foreach ($output['code_focus_list']['code_info'] as $key => $val) { ?>
              <div focus_id="<?php echo $key;?>" class="focus-trigeminy-group" title="<?php echo '可上下拖拽更改图片组显示顺序';?>">
                <div class="title">
                  <?php if(empty($val['group_list']['group_image'])) { ?>
                    <img width="20" height="20" class="group_pic" title="" src="<?php echo ADMIN_TEMPLATES_URL ?>/images/picture.gif"/>
                  <?php }else{ ?>
                    <img width="20" height="20" class="group_pic" title="<?php echo $val['group_list']['group_image'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['group_list']['group_image'];?>"/>
                  <?php } ?>
                  <h4>
                    <?php if(!empty($val['group_list']['group_name'])) {?>
                      <div class="name"><?php echo $val['group_list']['group_name']; ?></div>
                    <?php }else{ ?>
                        <div class="name">图片调用</div>
                    <?php } ?>
                  </h4>
                  <input name="focus_list[<?php echo $key;?>][group_list][group_name]" value="<?php echo $val['group_list']['group_name'];?>" type="hidden">
                  <input name="focus_list[<?php echo $key;?>][group_list][group_image]" value="<?php echo $val['group_list']['group_image'];?>" type="hidden">
                  <a class="ncap-btn-mini del" href="JavaScript:edit_focus(<?php echo $key;?>);"><i class="fa fa-pencil-square-o"></i>编辑</a>
                  <a class="ncap-btn-mini del" href="JavaScript:del_focus(<?php echo $key;?>);"><i class="fa fa-trash"></i>删除</a>
                </div>
                <ul>
                  <?php foreach($val['pic_list'] as $k => $v) { ?>
                  <li list="pic" pic_id="<?php echo $k;?>" onclick="select_focus(<?php echo $key;?>,this);" title="<?php echo '可左右拖拽更改图片排列顺序';?>">
                    <div class="focus-thumb"><img title="<?php echo $v['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$v['pic_img'];?>"/></div>
                    <input name="focus_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_id]" value="<?php echo $v['pic_id'];?>" type="hidden">
                    <input name="focus_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_name]" value="<?php echo $v['pic_name'];?>" type="hidden">
                    <input name="focus_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_url]" value="<?php echo $v['pic_url'];?>" type="hidden">
                    <input name="focus_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_img]" value="<?php echo $v['pic_img'];?>" type="hidden">
                  </li>
                  <?php } ?>
                </ul>
              </div>
              <?php } ?>
              <?php } ?>
            <p class="notic" id="btn_add_list">小提示：可添加每组3张，最多5组联动广告图，单击图片为单张编辑，拖动排序，保存生效。</p>
            <div class="mt20"> <a class="ncap-btn" href="JavaScript:add_focus('pic');"><?php echo '图片组';?></a>
            </div>
          </dd>
        </dl>
      </div>
      <div id="edit_focus" class="ncap-form-default" style="display:none;">
        <div class="title">
          <h3>选中区域编辑</h3>
        </div>
        <dl class="row">
          <dt class="tit"> <?php echo '文字标题';?> </dt>
          <dd class="opt">
            <input type="hidden" name="slide_id" value="">
            <input class="input-txt" type="text" name="pic_group[group_name]" value="">
            <span class="err"></span>
            <p class="notic">  </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit"> <?php echo $lang['web_config_upload_adv_pic'];?> </dt>
          <dd class="opt">
            <div class="input-file-show"> <span class="type-file-box">
              <input type='text' name='group_image' id='textfield1' class='type-file-text' />
              <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
              <input name="group_image" id="group_image" type="file" class="type-file-file" size="30">
              </span> </div>
            <span class="err"></span>
            <p class="notic"> 为确保显示效果正确，请选择W:20px H:20px的清晰图片。</p>
          </dd>
        </dl>
      </div>
      <div id="upload_focus" class="ncap-form-default" style="display:none;">
        <div class="title">
          <h3>新增/选中区域内容设置详情</h3>
        </div>
        <dl class="row">
          <dt class="tit"> <?php echo '文字标题';?> </dt>
          <dd class="opt">
            <input type="hidden" name="slide_id" value="">
            <input type="hidden" name="pic_id" value="">
            <input class="input-txt" type="text" name="focus_pic[pic_name]" value="">
            <span class="err"></span>
            <p class="notic"> 图片标题文字将作为图片Alt形式显示。 </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label> <?php echo $lang['web_config_upload_url'];?> </label>
          </dt>
          <dd class="opt">
            <input name="focus_pic[pic_url]" value="" class="input-txt" type="text">
            <span class="err"></span>
            <p class="notic"> 输入图片要跳转的URL地址，正确格式应以"http://"开头，点击后将以"_blank"形式另打开页面。 </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit"> <?php echo $lang['web_config_upload_adv_pic'];?> </dt>
          <dd class="opt">
            <div class="input-file-show"> <span class="type-file-box">
              <input type='text' name='textfield' id='textfield1' class='type-file-text' />
              <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
              <input name="pic" id="pic" type="file" class="type-file-file" size="30">
              </span> </div>
            <span class="err"></span>
            <p class="notic"> 为确保显示效果正确，请选择W:398px H:160px的清晰图片作为联动广告图组单图。 </p>
          </dd>
        </dl>
      </div>
      <div class="ncap-form-default">
        <div class="bot"> <a href="JavaScript:void(0);" onclick="$('#upload_focus_form').submit();" class="ncap-btn-big ncap-btn-green"><?php echo $lang['web_config_save'];?></a> <a href="index.php?con=web_config&fun=html_update&web_id=<?php echo $output['code_focus_list']['web_id'];?>" class="ncap-btn-big ncap-btn-green"><?php echo $lang['web_config_web_html'];?></a> <span class="web-save-succ" style="display:none;"><?php echo $lang['nc_common_save_succ'];?></span> </div>
      </div>
    </form>
    <form id="upload_two_focus_form" class="tab-content" name="upload_screen_form" enctype="multipart/form-data" method="post" action="index.php?con=web_config&fun=two_focus_pic" target="upload_pic" style="display:none;">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="web_id" value="<?php echo $output['code_two_focus_list']['web_id'];?>">
      <input type="hidden" name="code_id" value="<?php echo $output['code_two_focus_list']['code_id'];?>">
      <input type="hidden" name="key" value="">
      <div class="ncap-form-default">
        <dl class="row">
          <dt class="tit">两张联动焦点组图预览</dt>
          <dd class="opt two-focus-trigeminy">
            <?php if (is_array($output['code_two_focus_list']['code_info']) && !empty($output['code_two_focus_list']['code_info'])) { ?>
              <?php foreach ($output['code_two_focus_list']['code_info'] as $key => $val) { ?>
                <div focus_id="<?php echo $key;?>" class="focus-trigeminy-group" title="<?php echo '可上下拖拽更改图片组显示顺序';?>">
                    <div class="title">
                      <?php if(empty($val['group_list']['group_image'])) { ?>
                        <img height="20" width="20" class="group_pic" title="" src="<?php echo ADMIN_TEMPLATES_URL ?>/images/picture.gif"/>
                      <?php }else{ ?>
                        <img width="20" height="20" class="group_pic" title="<?php echo $val['group_list']['group_image'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['group_list']['group_image'];?>"/>
                      <?php } ?>
                    <h4>
                    <?php if(!empty($val['group_list']['group_name'])) {?>
                      <div class="name"><?php echo $val['group_list']['group_name']; ?></div>
                    <?php }else{ ?>
                        <div class="name">图片调用</div>
                    <?php } ?>
                    </h4>
                    <input name="two_focus_list[<?php echo $key;?>][group_list][group_name]" value="<?php echo $val['group_list']['group_name'];?>" type="hidden">
                    <input name="two_focus_list[<?php echo $key;?>][group_list][group_image]" value="<?php echo $val['group_list']['group_image'];?>" type="hidden">
                    <a class="ncap-btn-mini del" href="JavaScript:edit_two_focus(<?php echo $key;?>);"><i class="fa fa-pencil-square-o"></i>编辑</a>
                    <a class="ncap-btn-mini del" href="JavaScript:del_two_focus(<?php echo $key;?>);"><i class="fa fa-trash"></i>删除</a></div>
                    <ul>
                      <?php foreach($val['pic_list'] as $k => $v) { ?>
                        <li list="pic" pic_id="<?php echo $k;?>" onclick="select_two_focus(<?php echo $key;?>,this);" title="<?php echo '可左右拖拽更改图片排列顺序';?>">
                          <div class="focus-thumb"><img title="<?php echo $v['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$v['pic_img'];?>"/></div>
                          <input name="two_focus_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_id]" value="<?php echo $v['pic_id'];?>" type="hidden">
                          <input name="two_focus_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_name]" value="<?php echo $v['pic_name'];?>" type="hidden">
                          <input name="two_focus_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_url]" value="<?php echo $v['pic_url'];?>" type="hidden">
                          <input name="two_focus_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_img]" value="<?php echo $v['pic_img'];?>" type="hidden">
                        </li>
                      <?php } ?>
                    </ul>
                </div>
                  <?php } ?>
              <?php } ?>
            <p class="notic" id="btn_add_list_two">小提示：可添加每组2张，最多6组联动广告图，单击图片为单张编辑，拖动排序，保存生效。</p>
            <div class="mt20"> <a class="ncap-btn" href="JavaScript:add_two_focus('pic');"><?php echo '图片组';?></a>
            </div>
          </dd>
        </dl>
      </div>
      <div id="edit_two_focus" class="ncap-form-default" style="display:none;">
        <div class="title">
          <h3>选中区域编辑</h3>
        </div>
        <dl class="row">
          <dt class="tit"> <?php echo '文字标题';?> </dt>
          <dd class="opt">
            <input type="hidden" name="slide_id" value="">
            <input class="input-txt" type="text" name="pic_group[group_name]" value="">
            <span class="err"></span>
            <p class="notic">  </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit"> <?php echo $lang['web_config_upload_adv_pic'];?> </dt>
          <dd class="opt">
            <div class="input-file-show"> <span class="type-file-box">
              <input type='text' name='group_image' id='textfield1' class='type-file-text' />
              <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
              <input name="group_image" id="group_image" type="file" class="type-file-file" size="30">
              </span> </div>
            <span class="err"></span>
            <p class="notic"> 为确保显示效果正确，请选择W:20px H:20px的清晰图片。 </p>
          </dd>
        </dl>
      </div>
      
      <div id="upload_two_focus" class="ncap-form-default" style="display:none;">
        <div class="title">
          <h3>新增/选中区域内容设置详情</h3>
        </div>
        <dl class="row">
          <dt class="tit"> <?php echo '文字标题';?> </dt>
          <dd class="opt">
            <input type="hidden" name="slide_id" value="">
            <input type="hidden" name="pic_id" value="">
            <input class="input-txt" type="text" name="focus_pic[pic_name]" value="">
            <span class="err"></span>
            <p class="notic"> 图片标题文字将作为图片Alt形式显示。 </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label> <?php echo $lang['web_config_upload_url'];?> </label>
          </dt>
          <dd class="opt">
            <input name="focus_pic[pic_url]" value="" class="input-txt" type="text">
            <span class="err"></span>
            <p class="notic"> 输入图片要跳转的URL地址，正确格式应以"http://"开头，点击后将以"_blank"形式另打开页面。 </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit"> <?php echo $lang['web_config_upload_adv_pic'];?> </dt>
          <dd class="opt">
            <div class="input-file-show"> <span class="type-file-box">
              <input type='text' name='textfield' id='textfield1' class='type-file-text' />
              <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
              <input name="pic" id="pic" type="file" class="type-file-file" size="30">
              </span> </div>
            <span class="err"></span>
            <p class="notic"> 为确保显示效果正确，请选择W:294px H:400px的清晰图片作为联动广告图组单图。 </p>
          </dd>
        </dl>
      </div>
      <div class="ncap-form-default">
        <div class="bot"> <a href="JavaScript:void(0);" onclick="$('#upload_two_focus_form').submit();" class="ncap-btn-big ncap-btn-green"><?php echo $lang['web_config_save'];?></a> <a href="index.php?con=web_config&fun=html_update&web_id=<?php echo $output['code_two_focus_list']['web_id'];?>" class="ncap-btn-big ncap-btn-green"><?php echo $lang['web_config_web_html'];?></a> <span class="web-two-save-succ" style="display:none;"><?php echo $lang['nc_common_save_succ'];?></span> </div>
      </div>
    </form>
    <form id="upload_eight_focus_form" class="tab-content" name="upload_screen_form" enctype="multipart/form-data" method="post" action="index.php?con=web_config&fun=eight_focus_pic" target="upload_pic" style="display:none;">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="web_id" value="<?php echo $output['code_eight_focus_list']['web_id'];?>">
      <input type="hidden" name="code_id" value="<?php echo $output['code_eight_focus_list']['code_id'];?>">
      <input type="hidden" name="key" value="">
      <div class="ncap-form-default">
        <dl class="row">
          <dt class="tit">八张联动焦点组图预览</dt>
          <dd class="opt eight-focus-trigeminy">
            <?php if (is_array($output['code_eight_focus_list']['code_info']) && !empty($output['code_eight_focus_list']['code_info'])) { ?>
              <?php foreach ($output['code_eight_focus_list']['code_info'] as $key => $val) { ?>
                <div style="width:800px;" focus_id="<?php echo $key;?>" class="focus-trigeminy-group" title="<?php echo '可上下拖拽更改图片组显示顺序';?>">
                    <div class="title">
                      <?php if(empty($val['group_list']['group_image'])) { ?>
                        <img width="20" height="20" class="group_pic" title="" src="<?php echo ADMIN_TEMPLATES_URL ?>/images/picture.gif"/>
                      <?php }else{ ?>
                        <img width="20" height="20" class="group_pic" title="<?php echo $val['group_list']['group_image'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['group_list']['group_image'];?>"/>
                      <?php } ?>
                      <h4>
                      <?php if(!empty($val['group_list']['group_name'])) {?>
                        <div class="name"><?php echo $val['group_list']['group_name']; ?></div>
                      <?php }else{ ?>
                          <div class="name">图片调用</div>
                      <?php } ?>
                      </h4>
                      <input name="eight_focus_list[<?php echo $key;?>][group_list][group_name]" value="<?php echo $val['group_list']['group_name'];?>" type="hidden">
                      <input name="eight_focus_list[<?php echo $key;?>][group_list][group_image]" value="<?php echo $val['group_list']['group_image'];?>" type="hidden">
                      <a class="ncap-btn-mini del" href="JavaScript:edit_eight_focus(<?php echo $key;?>);"><i class="fa fa-pencil-square-o"></i>编辑</a>
                      <a class="ncap-btn-mini del" href="JavaScript:del_eight_focus(<?php echo $key;?>);"><i class="fa fa-trash"></i>删除</a></div>
                    <ul>
                      <?php foreach($val['pic_list'] as $k => $v) { ?>
                        <li list="pic" pic_id="<?php echo $k;?>" onclick="select_eight_focus(<?php echo $key;?>,this);" title="<?php echo '可左右拖拽更改图片排列顺序';?>">
                          <div class="focus-thumb"><img title="<?php echo $v['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$v['pic_img'];?>"/></div>
                          <input name="eight_focus_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_id]" value="<?php echo $v['pic_id'];?>" type="hidden">
                          <input name="eight_focus_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_name]" value="<?php echo $v['pic_name'];?>" type="hidden">
                          <input name="eight_focus_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_url]" value="<?php echo $v['pic_url'];?>" type="hidden">
                          <input name="eight_focus_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_img]" value="<?php echo $v['pic_img'];?>" type="hidden">
                        </li>
                      <?php } ?>
                    </ul>
                </div>
                  <?php } ?>
              <?php } ?>
            <p class="notic" id="btn_add_list_eight">小提示：可添加每组8张，最多5组联动广告图，单击图片为单张编辑，拖动排序，保存生效。</p>
            <div class="mt20"> <a class="ncap-btn" href="JavaScript:add_eight_focus('pic');"><?php echo '图片组';?></a>

            </div>
          </dd>
        </dl>
      </div>
      
      <div id="edit_eight_focus" class="ncap-form-default" style="display:none;">
        <div class="title">
          <h3>选中区域编辑</h3>
        </div>
        <dl class="row">
          <dt class="tit"> <?php echo '文字标题';?> </dt>
          <dd class="opt">
            <input type="hidden" name="slide_id" value="">
            <input class="input-txt" type="text" name="pic_group[group_name]" value="">
            <span class="err"></span>
            <p class="notic">  </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit"> <?php echo $lang['web_config_upload_adv_pic'];?> </dt>
          <dd class="opt">
            <div class="input-file-show"> <span class="type-file-box">
              <input type='text' name='group_image' id='textfield1' class='type-file-text' />
              <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
              <input name="group_image" id="group_image" type="file" class="type-file-file" size="30">
              </span> </div>
            <span class="err"></span>
            <p class="notic"> 为确保显示效果正确，请选择W:20px H:20px的清晰图片。 </p>
          </dd>
        </dl>
      </div>
      <div id="upload_eight_focus" class="ncap-form-default" style="display:none;">
        <div class="title">
          <h3>新增/选中区域内容设置详情</h3>
        </div>
        <dl class="row">
          <dt class="tit"> <?php echo '文字标题';?> </dt>
          <dd class="opt">
            <input type="hidden" name="slide_id" value="">
            <input type="hidden" name="pic_id" value="">
            <input class="input-txt" type="text" name="focus_pic[pic_name]" value="">
            <span class="err"></span>
            <p class="notic"> 图片标题文字将作为图片Alt形式显示。 </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label> <?php echo $lang['web_config_upload_url'];?> </label>
          </dt>
          <dd class="opt">
            <input name="focus_pic[pic_url]" value="" class="input-txt" type="text">
            <span class="err"></span>
            <p class="notic"> 输入图片要跳转的URL地址，正确格式应以"http://"开头，点击后将以"_blank"形式另打开页面。 </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit"> <?php echo $lang['web_config_upload_adv_pic'];?> </dt>
          <dd class="opt">
            <div class="input-file-show"> <span class="type-file-box">
              <input type='text' name='textfield' id='textfield1' class='type-file-text' />
              <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
              <input name="pic" id="pic" type="file" class="type-file-file" size="30">
              </span> </div>
            <span class="err"></span>
            <p class="notic"> 为确保显示效果正确，请选择W:298px H:120px的清晰图片作为联动广告图组单图。 </p>
          </dd>
        </dl>
      </div>
      <div class="ncap-form-default">
        <div class="bot"> <a href="JavaScript:void(0);" onclick="$('#upload_eight_focus_form').submit();" class="ncap-btn-big ncap-btn-green"><?php echo $lang['web_config_save'];?></a> <a href="index.php?con=web_config&fun=html_update&web_id=<?php echo $output['code_eight_focus_list']['web_id'];?>" class="ncap-btn-big ncap-btn-green"><?php echo $lang['web_config_web_html'];?></a> <span class="web-eight-save-succ" style="display:none;"><?php echo $lang['nc_common_save_succ'];?></span> </div>
      </div>
    </form>
  </div>
</div>
<iframe style="display:none;" src="" name="upload_pic"></iframe>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/colorpicker/evol.colorpicker.css" rel="stylesheet" type="text/css">
<script src="<?php echo RESOURCE_SITE_URL;?>/js/colorpicker/evol.colorpicker.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script src="<?php echo ADMIN_RESOURCE_URL?>/js/dis_web_focus.js"></script>
