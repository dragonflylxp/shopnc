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
                <li><a href="index.php?con=web_config&fun=focus_edit"><?php echo '联动焦点组图';?></a></li>
                <li><a href="JavaScript:void(0);" class="current"><?php echo '右侧广告图';?></a></li>
                <li><a href="index.php?con=web_config&fun=web_config"><?php echo '焦点大图';?></a></li>
            </ul>
        </div>
    </div>
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
        <ul>
            <li><?php echo '所有相关设置完成，使用底部的“更新板块内容”前台展示页面才会变化。';?></li>
        </ul>
    </div>
    <div class="homepage-focus" id="homepageFocusTab">
        <div class="title">
            <h3>首页右侧广告图设置</h3>
            <ul class="tab-base nc-row">
                <li><a href="JavaScript:void(0);" class="current" form="upload_adv_form"><?php echo '右侧广告图';?></a></li>
            </ul>
        </div>
        <form id="upload_adv_form" class="tab-content" name="upload_adv_form" enctype="multipart/form-data" method="post" action="index.php?con=web_config&fun=adv_pic" target="upload_pic">
            <input type="hidden" name="form_submit" value="ok" />
            <input type="hidden" name="web_id" value="<?php echo $output['code_adv_list']['web_id'];?>">
            <input type="hidden" name="code_id" value="<?php echo $output['code_adv_list']['code_id'];?>">
            <input type="hidden" name="key" value="">
            <div class="ncap-form-default">
                <dl class="row">
                    <dt class="tit">右侧广告图预览</dt>
                    <dd class="opt">
                        <div class="full-screen-slides">
                            <ul>
                                <?php if (is_array($output['code_adv_list']['code_info']) && !empty($output['code_adv_list']['code_info'])) { ?>
                                    <?php foreach ($output['code_adv_list']['code_info'] as $key => $val) { ?>
                                        <li ap="0" adv_id="<?php echo $val['pic_id'];?>" title="可上下拖拽更改显示顺序"><div class="title"><h4>图片调用</h4><a class="ncap-btn-mini del" href="JavaScript:del_adv(<?php echo $val['pic_id'];?>);"><i class="fa fa-trash"></i>删除</a></div>
                                            <div class="focus-thumb" onclick="select_adv(<?php echo $val['pic_id'];?>);" style="background-color:<?php echo $val['color'];?>;" title="点击编辑选中区域内容"> <img title="<?php echo $val['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_img'];?>"/></div>
                                            <input name="adv_list[<?php echo $val['pic_id'];?>][pic_id]" value="<?php echo $val['pic_id'];?>" type="hidden">
                                            <input name="adv_list[<?php echo $val['pic_id'];?>][pic_name]" value="<?php echo $val['pic_name'];?>" type="hidden">
                                            <input name="adv_list[<?php echo $val['pic_id'];?>][pic_url]" value="<?php echo $val['pic_url'];?>" type="hidden">
                                            <input name="adv_list[<?php echo $val['pic_id'];?>][color]" value="<?php echo $val['color'];?>" type="hidden">
                                            <input name="adv_list[<?php echo $val['pic_id'];?>][pic_img]" value="<?php echo $val['pic_img'];?>" type="hidden">
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                        <p class="notic"><?php echo '小提示：单击图片选中修改，拖动可以排序，添加最多1个，保存后生效。';?></p>
                        <div class="mt20"><a class="ncap-btn" href="JavaScript:add_adv('pic');"><?php echo '图片调用';?></a>
                        </div>
                    </dd>
                </dl>
            </div>
            <div id="upload_adv" class="ncap-form-default" style="display:none; overflow: visible;">
                <div class="title">
                    <h3>新增/选中区域内容设置详情</h3>
                </div>
                <dl class="row">
                    <dt class="tit"><?php echo '文字标题';?></dt>
                    <dd class="opt">
                        <input type="hidden" name="adv_id" value="">
                        <input class="txt" type="text" name="adv_pic[pic_name]" value="">
                        <span class="err"></span>
                        <p class="notic">图片标题文字将作为图片Alt形式显示。</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label><?php echo $lang['web_config_upload_url'];?></label>
                    </dt>
                    <dd class="opt">
                        <input name="adv_pic[pic_url]" value="" class="input-txt" type="text">
                        <span class="err"></span>
                        <p class="vatop tips">输入图片要跳转的URL地址，正确格式应以"http://"开头，点击后将以"_blank"形式另打开页面。</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit"><?php echo $lang['web_config_upload_adv_pic'];?></dt>
                    <dd class="opt">
                        <div class="input-file-show"><span class="type-file-box">
              <input type='text' name='textfield' id='textfield1' class='type-file-text' />
              <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
              <input name="pic" id="pic" type="file" class="type-file-file" size="30">
              </span></div>
                        <p class="notic">为确保显示效果正确，请选择W:350px H:120px的清晰图片作为全屏焦点图。</p>
                    </dd>
                </dl>
            </div>
            <div class="ncap-form-default">
                <div class="bot"><a href="JavaScript:void(0);" onclick="$('#upload_adv_form').submit();" class="ncap-btn-big ncap-btn-green"><?php echo $lang['web_config_save'];?></a> <a href="index.php?con=web_config&fun=html_update&web_id=<?php echo $output['code_adv_list']['web_id'];?>" class="ncap-btn-big ncap-btn-green"><?php echo $lang['web_config_web_html'];?></a> <span class="web-save-succ" style="display:none;"><?php echo $lang['nc_common_save_succ'];?></span> </div>
            </div>
        </form>



    </div>
</div>
<iframe style="display:none;" src="" name="upload_pic"></iframe>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/colorpicker/evol.colorpicker.css" rel="stylesheet" type="text/css">
<script src="<?php echo RESOURCE_SITE_URL;?>/js/colorpicker/evol.colorpicker.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script src="<?php echo ADMIN_RESOURCE_URL?>/js/dis_web_focus.js"></script>
