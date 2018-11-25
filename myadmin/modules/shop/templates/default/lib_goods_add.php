<?php defined('Inshopec') or exit('Access Invalid!');?>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script>
<script src="<?php echo ADMIN_RESOURCE_URL;?>/js/store_goods_add.step2.js"></script>
<link href="<?php echo SHOP_SITE_URL; ?>/resource/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<style type="text/css">
.ncap-form-default { padding: 0;}
.item-publish { width: 999px;}
.hint { font-size: 12px; line-height: 16px; color: #BBB; margin-top: 10px; }
.add-on { line-height: 28px; background-color: #F6F7Fb; vertical-align: top; display: inline-block; text-align: center; width: 28px; height: 28px; border: solid #E6E9EE; border-width: 1px 1px 1px 0;}
.add-on { *display: inline/*IE6,7*/; zoom:1;}
.add-on i { font-size: 14px; color: #434A54; *margin-top: 8px/*IE7*/; margin-right: 0!important;}
.ncsc-upload-btn { vertical-align: top; display: inline-block; *display: inline/*IE7*/; width: 80px; height: 30px; margin: 5px 5px 0 0; *zoom:1;}
.ncsc-upload-btn a { display: block; position: relative; z-index: 1;}
.ncsc-upload-btn span { width: 80px; height: 30px; position: absolute; left: 0; top: 0; z-index: 2; cursor: pointer;}
.ncsc-upload-btn .input-file { width: 80px; height: 30px; padding: 0; margin: 0; border: none 0; opacity:0; filter: alpha(opacity=0); cursor: pointer; }
.ncsc-upload-btn p { font-size: 12px; line-height: 20px; background-color: #F5F5F5; color: #999; text-align: center; color: #666; width: 78px; height: 20px; padding: 4px 0; border: solid 1px; border-color: #DCDCDC #DCDCDC #B3B3B3 #DCDCDC; position: absolute; left: 0; top: 0; z-index: 1;}
.ncsc-upload-btn p i { vertical-align: middle; margin-right: 4px;}
.ncsc-upload-btn a:hover p { background-color: #E6E6E6; color: #333; border-color: #CFCFCF #CFCFCF #B3B3B3 #CFCFCF;}a.ncbtn-mini,
a.ncbtn { font: normal 12px/20px "microsoft yahei", arial; color: #FFF; background-color: #CCD0D9; text-align: center; vertical-align: middle; display: inline-block; *display: inline; height: 20px; padding: 5px 10px; border-radius: 3px; cursor: pointer; *zoom: 1;}
a.ncbtn-mini { line-height: 16px; height: 16px; padding: 3px 7px; border-radius: 2px;}
a.ncbtn { height: 20px; padding: 5px 10px; border-radius: 3px; }
a:hover.ncbtn-mini,
a:hover.ncbtn { text-decoration: none; color: #FFF; background-color: #AAB2BD;}
a.ncbtn-mini i, 
a.ncbtn i { font-size: 14px !important; vertical-align: middle; margin: 0 4px 0 0 !important;}
.ncsc-brand-select { width: 230px; position: relative; z-index: 1;}
.ncsc-brand-select .selection { cursor: pointer;}
.ncsc-brand-select-container { background: #FFF; display: none; width: 220px; border: solid 1px #CCC; position: absolute; z-index: 1; top: 29px; left: 0;}
.ncsc-brand-select-container .brand-index { width: 210px; padding-bottom: 10px; margin: 6px auto; border-bottom: dotted 1px #CCC;}
.ncsc-brand-select-container .letter {  }
.ncsc-brand-select-container .letter ul { overflow: hidden;}
.ncsc-brand-select-container .letter ul li { float: left; }
.ncsc-brand-select-container .letter ul li a { line-height: 16px; color: #666; text-align: center; display: block; min-width: 16px; padding: 2px; margin: 0;}
.ncsc-brand-select-container .letter ul li a:hover { text-decoration: none; color: #FFF; background: #27A9E3; }
.ncsc-brand-select-container .search { line-height: normal; clear: both; margin-top: 6px;}
.ncsc-brand-select-container .search .text { width: 160px; height: 20px; padding: 0 2px;}
.ncsc-brand-select-container .search .ncsc-btn-mini { vertical-align: top; margin-left: 4px;}
.ncsc-brand-select-container .brand-list { width: 220px; max-height: 220px; position: relative; z-index: 1; overflow: hidden;}
.ncsc-brand-select-container .brand-list ul {}
.ncsc-brand-select-container .brand-list ul li { line-height: 20px; padding: 5px 0; border-bottom: solid 1px #F5F5F5;}
.ncsc-brand-select-container .brand-list ul li:hover { color: #333; background: #F7F7F7; cursor: pointer;}
.ncsc-brand-select-container .brand-list ul li em { display: inline-block; *display: inline; text-align: center; width: 20px; margin-right: 6px; border-right: solid 1px #DDD; *zoom: 1;}
.ncsc-brand-select-container .no-result { color: #999; text-align: center; padding: 20px 10px; }
.ncsc-brand-select-container .no-result strong { color: #27A9E3;}
.ncsc-form-goods { border: solid #E6E6E6; border-width: 1px 1px 0 1px; width: 957px;}
.ncsc-form-goods h3 { font-size: 14px; font-weight: 600; line-height: 22px; color: #000; clear: both; background-color: #F5F5F5; padding: 5px 0 5px 12px; border-bottom: solid 1px #E7E7E7;}
.ncsc-form-goods dl { font-size: 0; *word-spacing:-1px/*IE6、7*/; line-height: 20px; clear: both; padding: 0; margin: 0; border-bottom: solid 1px #E6E6E6; overflow: hidden;}

.ncsc-form-goods dl:hover .hint { color: #666;}
.ncsc-form-goods dl.bottom { border-bottom-width: 0px;}
.ncsc-form-goods dl dt { font-size: 12px; line-height: 30px; color: #333; vertical-align: top; letter-spacing: normal; word-spacing: normal; text-align: right; display: inline-block; width: 13%; padding: 8px 1% 8px 0; margin: 0;}
.ncsc-form-goods dl dt { *display: inline/*IE6,7*/;}
.ncsc-form-goods dl dt i.required { font: 12px/16px Tahoma; color: #F30; vertical-align: middle; margin-right: 4px;}
.ncsc-form-goods dl dd { font-size: 12px; line-height: 30px; vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; width: 84%; padding: 8px 0 8px 1%; border-left: solid 1px #E6E6E6;}
.ncsc-form-goods dl dd { *display: inline/*IE6,7*/;}
.ncsc-form-goods dl dd span.property { display: inline-block; white-space: nowrap; margin: 0 10px 10px 0;}

/* 电脑端手机端商品详情切换Tab */
#ncProductDetails .ui-tabs { padding: 0;}
#ncProductDetails .ui-widget-content { background: #FFF none; border: none;}
#ncProductDetails .ui-widget-header { background: #f0f0ee none !important; border: 1px solid #CCC !important;}
#ncProductDetails .ui-tabs .ui-tabs-nav li a i { font-size: 14px; vertical-align: middle; margin-right: 4px;}
#ncProductDetails .ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited { color: #999;}
#ncProductDetails .ui-state-default, .ui-widget-content .ui-state-default { border-color: #CCC;}
#ncProductDetails .ui-state-hover a, .ui-state-hover a:hover { color: #555; }
#ncProductDetails .ui-state-active a, .ui-state-active a:link, .ui-state-active a:visited { color: #333;}
#ncProductDetails .ui-tabs .ui-tabs-panel { padding: 8px 0;}

/* 手机端商品介绍 */
.ncsc-mobile-editor { overflow: hidden;}
.ncsc-mobile-editor .pannel { width: 320px; height: 490px; float: left; border: solid 1px #DDD; position: relative;}
.ncsc-mobile-editor .pannel .size-tip { line-height: 30px; background-color: #F7F7F7; height: 30px; padding: 0 15px; border-bottom: solid 1px #DDD;}
.ncsc-mobile-editor .pannel .size-tip em { color: #F60;}
.ncsc-mobile-editor .pannel .size-tip i { color: #CCC; margin: 0 10px;}
.ncsc-mobile-editor .pannel .control-panel { -moz-user-select: none; max-height: 380px; overflow-x: hidden; overflow-y: auto;}
.ncsc-mobile-editor .pannel .control-panel .module { background: #fff; width: 290px; margin: 10px 15px 0; position: relative; }
.ncsc-mobile-editor .pannel .control-panel .module .image-div img { max-width: 290px;}
.ncsc-mobile-editor .pannel .control-panel .module .text-div { line-height: 150%; word-wrap: break-word;}
.ncsc-mobile-editor .pannel .control-panel .tools { display: none; position: absolute; z-index: 20; top: 10px; right: 10px;}
.ncsc-mobile-editor .pannel .control-panel .tools a { line-height: 25px; color: #000; background: #fff; float: left; padding: 0 10px; margin-right: 1px; }
.ncsc-mobile-editor .pannel .control-panel .cover { background-color: #000; display: none; width: 100%; height: 100%; left: 0; opacity: 0.5; position: absolute; top: 0;}
.ncsc-mobile-editor .pannel .control-panel .current { min-height: 40px;}
.ncsc-mobile-editor .pannel .control-panel .current .tools,
.ncsc-mobile-editor .pannel .control-panel .current .cover { display: block;}

.ncsc-mobile-editor .pannel .add-btn{ background: none repeat scroll 0 0 #ececec; height: 60px; margin: 10px 15px; overflow: hidden;}
.ncsc-mobile-editor .pannel .add-btn ul { padding: 5px;}
.ncsc-mobile-editor .pannel .add-btn li { text-align: center; width: 50%; height: 50px; float: left;}
.ncsc-mobile-editor .pannel .add-btn li a { display: block; height: 50px; color: #999;}
.ncsc-mobile-editor .pannel .add-btn li i { font-size: 24px; line-height: 30px; height: 30px}
.ncsc-mobile-editor .pannel .add-btn li p { font-size: 14px; line-height: 20px; height: 20px;}
.ncsc-mobile-editor .explain { float: left; width: 400px; margin-left: 32px;}
.ncsc-mobile-editor .explain dl,
.ncsc-mobile-editor .explain dt,
.ncsc-mobile-editor .explain dd { color: #777; line-height: 24px; width: auto; height: auto; margin: 0; padding: 0; border: 0;}
.ncsc-mobile-editor .explain dl { margin-bottom: 15px;}
.ncsc-mobile-editor .explain dt { color: #555; font-weight: 600;}
.ncsc-mobile-edit-area {}
.ncsc-mea-text { width: 320px; border: solid 1px #FFF; position: relative; z-index: 1;}
.ncsc-mea-text .text-tip { color: #333; line-height: 20px; height: 20px; padding: 5px 0;}
.ncsc-mea-text .text-tip em { color: #F60; margin: 0 2px;}
.ncsc-mea-text .textarea { width: 310px; height: 80px; resize: none;}
.ncsc-mea-text .button { text-align: center; padding: 10px 0;}
.ncsc-mea-text .text-close { font: 11px/16px Verdana; color: #FFF; background: #AAA; text-align: center; width: 16px; height: 16px; position: absolute; z-index: 1; top: 8px; right: 4px; }
.ncsc-mea-text .text-close:hover { text-decoration: none; background-color: #F30;}
.ncsc-mobile-editor .ncsc-mea-text { width: 300px; margin: 0 0 0 -15px; border: solid #27a9e3; border-width: 1px 0;}
.ncsc-mobile-editor .ncsc-mea-text .text-tip { background: #F5F5F5; padding: 5px;}
.ncsc-mobile-editor .ncsc-mea-text .textarea { width: 290px; border: none;}
.ncsc-mobile-editor .ncsc-mea-text .textarea:focus { box-shadow: none;}
.ncsc-mobile-editor .ncsc-mea-text .button { background: #F5F5F5;}
.ncsc-mobile-edit-area .ncsc-mea-img {}
.ncsc-mobile-edit-area .goods-gallery { border: solid 1px #EEE; margin-top: 5px;}

/*视频上传 按钮样式*/
.ncsc-upload-video-btn { vertical-align: top; display: inline-block; *display: inline/*IE7*/; width: 80px; height: 30px; margin: 5px 5px 0 0; *zoom:1;}
.ncsc-upload-video-btn a { display: block; position: relative; z-index: 1;}
.ncsc-upload-video-btn span { width: 80px; height: 30px; position: absolute; left: 0; top: 0; z-index: 2; cursor: pointer;}
.ncsc-upload-video-btn .input_video_file { width: 80px; height: 30px; padding: 0; margin: 0; border: none 0; opacity:0; filter: alpha(opacity=0); cursor: pointer; }
.ncsc-upload-video-btn p { font-size: 12px; line-height: 20px; background-color: #F5F5F5; color: #999; text-align: center; color: #666; width: 78px; height: 20px; padding: 4px 0; border: solid 1px; border-color: #DCDCDC #DCDCDC #B3B3B3 #DCDCDC; position: absolute; left: 0; top: 0; z-index: 1;}
.ncsc-upload-video-btn p i { vertical-align: middle; margin-right: 4px;}
.ncsc-upload-video-btn a:hover p { background-color: #E6E6E6; color: #333; border-color: #CFCFCF #CFCFCF #B3B3B3 #CFCFCF;}

/* 发布商品-上传主图 */
.ncsc-goods-default-pic { overflow: hidden;}
.ncsc-goods-default-pic .goodspic-upload { float: left;}
.ncsc-goods-default-pic .goodspic-upload .upload-thumb { position: relative;background-color: #FFF; text-align: center; vertical-align: middle; display: table-cell; *display: block; width: 160px; height: 160px; overflow: hidden;}
.ncsc-goods-default-pic .goodspic-upload .upload-thumb img { max-width: 160px; max-height: 160px; margin-top:expression(160-this.height/2); *margin-top:expression(80-this.height/2)/*IE6,7*/;}
.ncsc-goods-default-pic .goodspic-upload .handle { height: 30px; margin: 10px 0;}
.ncsc-goods-default-pic .faq { width: 300px; float: right;}

.ncsc-form-goods-pic { min-height: 480px; overflow: hidden;}
.ncsc-form-goods-pic .container { width: 708px; float: left;}
.ncsc-form-goods-pic .sidebar { width: 228px; float: right;}
.sticky #uploadHelp { width: 178px; position: fixed; z-index: 10; top: 75px;}
.ncsc-form-goods-pic .ncsc-goodspic-list { margin-bottom: 20px; border: solid 1px #E6E6E6; overflow: hidden;}
.ncsc-goodspic-list:hover { border-color: #AAA;}
.ncsc-goodspic-list .title { background-color: #F5F5F5; height: 20px; padding: 5px 0 5px 12px; border-bottom: solid 1px #E6E6E6;}
.ncsc-goodspic-list:hover .title { background-color: #CCC; border-color: #AAA;}
.ncsc-goodspic-list .title h3 { font-size: 14px; font-weight: 600; line-height: 20px; color: #555; }
.ncsc-goodspic-list:hover .title h3 { color: #000;}
/* 发布与编辑商品-AJAX图片上传及控制删除 */

.ncsc-goodspic-list .goods-pic-list { font-size: 0; *word-spacing:-1px/*IE6、7*/; margin-left: -1px;}
.ncsc-goodspic-list .goods-pic-list li { font-size: 12px; vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; *display: inline/*IE6,7*/; width: 140px; height: 180px; border-left: solid 1px #E6E6E6; position: relative; z-index: 1; zoom: 1;}
.ncsc-goodspic-list:hover .goods-pic-list li { border-color: #CCC;}
.ncsc-goodspic-list .goods-pic-list li .upload-thumb { line-height: 0; background-color: #FFF; text-align: center; vertical-align: middle; display: table-cell; *display: block; width: 120px; height: 120px; border: solid 1px #F5F5F5; position: absolute; z-index: 1; top: 10px; left: 10px; overflow: hidden;}
.ncsc-goodspic-list .goods-pic-list li .upload-thumb img { max-width: 120px; max-height: 120px; margin-top:expression(120-this.height/2); *margin-top:expression(60-this.height/2)/*IE6,7*/;}
.ncsc-goodspic-list .goods-pic-list li .show-default { display: block; width: 120px; height: 30px; padding: 90px 0 0; border: solid 1px #F5F5F5; position: absolute; z-index: 2; top: 10px; left: 10px; cursor: pointer;}

.ncsc-goodspic-list ul li .show-default:hover { border-color: #27A9E3;}
.ncsc-goodspic-list ul li .show-default.selected,
.ncsc-goodspic-list ul li .show-default.selected:hover { border-color: #28B779;}
.ncsc-goodspic-list ul li .show-default p { color: #28B779; line-height: 20px; filter:progid:DXImageTransform.Microsoft.gradient(enabled='true',startColorstr='#E5FFFFFF', endColorstr='#E5FFFFFF');background:rgba(255,255,255,0.9); display: none; height: 20px; padding: 5px;}
.ncsc-goodspic-list ul li .show-default:hover p { color: #27A9E3; display: block;}
.ncsc-goodspic-list ul li .show-default.selected p { color: #28B779; display: block;}
.ncsc-goodspic-list ul li .show-default p i { font-size: 14px; margin-right: 4px;}
.ncsc-goodspic-list ul li a.del { font-family:Tahoma, Geneva, sans-serif; font-size: 9px; font-weight: lighter; background-color: #FFF; line-height: 14px; text-align: center; display: none; width: 14px; height: 14px; border-style: solid; border-width: 1px; border-radius: 8px; position: absolute; z-index: 3; top: -8px; right: -8px;}
.ncsc-goodspic-list ul li .show-default:hover a.del { color: #27A9E3; display: block;}
.ncsc-goodspic-list ul li .show-default.selected:hover a.del { color: #28B779;}
.ncsc-goodspic-list ul li .show-default:hover a.del:hover { text-decoration: none;}

.ncsc-goodspic-upload .show-sort { line-height: 20px; color: #999; width: 55px; height: 20px; padding: 4px 0 4px 4px; border-style: solid; border-color: #E6E6E6; border-width: 1px 0 1px 1px; position: absolute; z-index: 2; left: 10px; top: 140px;}
.ncsc-goodspic-upload .show-sort .text { font-size: 12px; font-weight: bold; line-height: 20px; vertical-align: middle; width: 10px; height: 20px; padding: 0; border: none 0;}
.ncsc-goodspic-upload .show-sort .text:focus { color: #28B779; text-decoration: underline; box-shadow: none;}
.ncsc-goodspic-upload .ncsc-upload-btn { width: 60px; height: 30px; margin: 0; position: absolute; z-index: 1px; left: 70px; top: 140px;}
.ncsc-goodspic-upload .ncsc-upload-btn span { width: 60px; height: 30px;}
.ncsc-goodspic-upload .ncsc-upload-btn .input-file { width: 60px; height: 30px;}
.ncsc-goodspic-upload .ncsc-upload-btn p { width: 58px; height: 20px;}
.ncsc-select-album { background-color: #FFF; border-top: solid 1px #E6E6E6; padding: 10px;}
.ncsc-goodspic-list:hover .ncsc-select-album { border-color: #CCC;}
/* 从图片空间选择图片 */
.goods-gallery { display: block; overflow: hidden;}
.goods-gallery .nav { background-color: #F5F5F5; height: 32px; padding: 4px;}
.goods-gallery .nav .l { font-size: 12px; line-height: 30px; color: #999; float: left;}
.goods-gallery .nav .r { float: right;}
.goods-gallery .list { font-size: 0; *word-spacing:-1px/*IE6、7*/; text-align: left;}
.goods-gallery .list li { vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; width: 92px; height: 92px; padding: 12px; border: solid #E6E6E6; border-width: 1px 0 0 1px;}

.goods-gallery .list li { *display: inline/*IE6,7*/;}
.goods-gallery .list li a { line-height: 0; background-color: #FFF; text-align: center; vertical-align: middle; display: table-cell; *display: block; width: 90px; height: 90px; border: solid 1px #FFF; overflow: hidden;}
.goods-gallery .list li a img { max-width: 90px; max-height: 90px; margin-top:expression(90-this.height/2); *margin-top:expression(45-this.height/2)/*IE6,7*/;}
.goods-gallery.add-step2 { width: 790px;}
.goods-gallery.add-step2 .list { width: 791px; margin:-1px;}
.goods-gallery.add-step2 .list li { padding: 10px;}

#demo, #des_demo { line-height: 0; text-align: center; width: 100%}
#demo .ajaxload,
#des_demo .ajaxload { width: 16px; height: 16px; margin: 80px auto;}


.goodspic-upload .selected img{ border: solid 1px #27A9E3;}
.upload-thumb a.del { font-family:Tahoma, Geneva, sans-serif; font-size: 9px; font-weight: lighter; background-color: #FFF; line-height: 14px; text-align: center; display: none; width: 14px; height: 14px; border-style: solid; border-width: 1px; /*border-radius: 8px;*/ position: absolute; z-index: 3;right:0px;}
.ncsc-goods-default-pic .selected a.del{ color: #27A9E3;display: block; }
</style>

<div class="page item-publish">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商品库管理</h3>
        <h5>管理数据的新增、编辑、删除</h5>
      </div>
    </div>
  </div>
  <form method="post" id="post_form">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="commonid" value="<?php echo $output['goods']['goods_id'];?>" />
    <input type="hidden" name="type_id" value="<?php echo $output['goods_class']['type_id'];?>" />
    <div class="ncap-form-default ncsc-form-goods">
      <h3 id="demo1">商品基本信息</h3>
      <dl>
        <dt>商品分类<?php echo $lang['nc_colon'];?></dt>
        <dd id="gcategory"> <?php echo $output['goods_class']['gc_tag_name'];?> <a class="ncbtn" href="index.php?con=lib_goods&fun=goods_class&goods_id=<?php echo $output['goods']['goods_id'];?>"><?php echo $lang['nc_edit'];?></a>
          <input type="hidden" id="cate_id" name="cate_id" value="<?php echo $output['goods_class']['gc_id'];?>" class="text" />
          <input type="hidden" name="cate_name" value="<?php echo $output['goods_class']['gc_tag_name'];?>" class="text"/>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>商品名称：</dt>
        <dd>
          <input name="g_name" id="g_name" type="text" class="text w400" value="<?php echo $output['goods']['goods_name']; ?>" />
          <span></span>
          <p class="hint"><?php echo $lang['store_goods_index_goods_name_help'];?></p>
        </dd>
      </dl>
      <dl>
        <dt>商品卖点<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <textarea name="g_jingle" class="textarea h60 w400"><?php echo $output['goods']['goods_jingle']; ?></textarea>
          <span></span>
          <p class="hint">商品卖点最长不能超过140个汉字</p>
        </dd>
      </dl>
      <dl>
        <dt nc_type="no_spec">商品条形码：</dt>
        <dd nc_type="no_spec">
          <p>
            <input name="g_barcode" value="<?php echo $output['goods']['goods_barcode'];?>" type="text" class="text" />
          </p>
          <p class="hint">请填写商品条形码下方数字。</p>
        </dd>
      </dl>
      <dl>
        <dt>商品视频：</dt>
        <dd>
          <div class="ncsc-goods-default-video">
            <div class="goodsvideo-uplaod">
              <lable nctype="goods_video" id="goods_video">
                <?php if(!empty($output['goods']['goods_video'])) {?>
                <video width="240" height="240" src="<?php echo goodsVideoPath($output['goods']['goods_video'],0); ?>">
                  <img width="240" height="240" src="<?php echo ADMIN_SITE_URL.'/templates/'.TPL_NAME.'/images/member/default_video.gif';?>">
                </video>
                <?php } ?>
              </lable>
              <input type="hidden" name="video_path" id="video_path" nctype="goods_video" value="<?php echo $output['goods']['goods_video'] ?>" />
              <span></span>
              
              <div class="handle">
                <div class="ncsc-upload-video-btn">
                  <a href="javascript:void(0);"><span>
                  <input class="input_video_file" id="btn_video_upload" type="file" name="video_upload" size="1" hidefocus="true" accept="audio/mp4,video/mp4,application/ogg,audio/ogg">
                  </span>
                  <p><i class="icon-upload-alt"></i>视频上传</p>
                  </a> 
                </div>
                <p class="hint">上传商品视频；支持MP4格式上传，建议使用
                <font color="red">大小不超过10M的视频</font>，上传后的视频将会自动保存在视频空间的默认分类中。</p>
              </div>
            </div>
          </div>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>商品图片：</dt>
        <dd>
          <div class="ncsc-goods-default-pic">
            <div class="goodspic-upload">
              <div class="upload-thumb selected"><img nctype="goods_image" src="<?php echo thumb($output['goods'], 240);?>"/> </div>
              <div class="upload-thumb"><a href="javascript:void(0)" nctype="goods_image2" class="del" title="移除">X</a> <img nctype="goods_image2" src="<?php echo cthumb($output['img'][2], 240);?>"/> </div>
              <div class="upload-thumb"><a href="javascript:void(0)" nctype="goods_image3" class="del" title="移除">X</a> <img nctype="goods_image3" src="<?php echo cthumb($output['img'][3], 240);?>"/> </div>
              <div class="upload-thumb"><a href="javascript:void(0)" nctype="goods_image4" class="del" title="移除">X</a> <img nctype="goods_image4" src="<?php echo cthumb($output['img'][4], 240);?>"/> </div>
              <div class="upload-thumb"><a href="javascript:void(0)" nctype="goods_image5" class="del" title="移除">X</a> <img nctype="goods_image5" src="<?php echo cthumb($output['img'][5], 240);?>"/> </div>
              <input type="hidden" name="image_path" id="image_path" nctype="goods_image" value="<?php echo $output['goods']['goods_image'];?>" />
              <span></span>
              <input type="hidden" name="img[2]" nctype="goods_image2" value="<?php echo $output['img'][2];?>" />
              <input type="hidden" name="img[3]" nctype="goods_image3" value="<?php echo $output['img'][3];?>" />
              <input type="hidden" name="img[4]" nctype="goods_image4" value="<?php echo $output['img'][4];?>" />
              <input type="hidden" name="img[5]" nctype="goods_image5" value="<?php echo $output['img'][5];?>" />
              <p class="hint">上传商品默认图，支持jpg、gif、png格式上传或从图片空间中选择，建议使用
                <font color="red">尺寸800x800像素以上、大小不超过<?php echo intval(C('image_max_filesize'))/1024;?>M的正方形图片</font>，
                单击选中图片，可进行上传、替换和删除。</p>
              <div class="handle">
                <div class="ncsc-upload-btn"> <a href="javascript:void(0);"><span>
                  <input type="file" hidefocus="true" size="1" class="input-file" name="goods_image" id="goods_image">
                  </span>
                  <p><i class="icon-upload-alt"></i>图片上传</p>
                  </a> </div>
                <a class="ncbtn mt5" nctype="show_image" href="<?php echo ADMIN_SITE_URL;?>/index.php?con=lib_goods&fun=pic_list&item=goods"><i class="icon-picture"></i>从图片空间选择</a> <a href="javascript:void(0);" nctype="del_goods_demo" class="ncbtn mt5" style="display: none;"><i class="icon-circle-arrow-up"></i>关闭相册</a></div>
            </div>
          </div>
          <div id="demo"></div>
        </dd>
      </dl>
      <h3 id="demo2">商品详情描述</h3>
      <dl style="overflow: visible;">
        <dt>商品品牌：</dt>
        <dd>
          <div class="ncsc-brand-select">
            <div class="selection">
              <input name="b_name" id="b_name" value="<?php echo $output['goods']['brand_name'];?>" type="text" class="text w180" readonly /><input type="hidden" name="b_id" id="b_id" value="<?php echo $output['goods']['brand_id'];?>" /><em class="add-on"><i class="icon-collapse"></i></em></div>
            <div class="ncsc-brand-select-container">
              <div class="brand-index" data-tid="<?php echo $output['goods_class']['type_id'];?>" data-url="<?php echo ADMIN_SITE_URL;?>/index.php?con=lib_goods&fun=ajax_get_brand">
                <div class="letter" nctype="letter">
                  <ul>
                    <li><a href="javascript:void(0);" data-letter="all">全部品牌</a></li>
                    <li><a href="javascript:void(0);" data-letter="A">A</a></li>
                    <li><a href="javascript:void(0);" data-letter="B">B</a></li>
                    <li><a href="javascript:void(0);" data-letter="C">C</a></li>
                    <li><a href="javascript:void(0);" data-letter="D">D</a></li>
                    <li><a href="javascript:void(0);" data-letter="E">E</a></li>
                    <li><a href="javascript:void(0);" data-letter="F">F</a></li>
                    <li><a href="javascript:void(0);" data-letter="G">G</a></li>
                    <li><a href="javascript:void(0);" data-letter="H">H</a></li>
                    <li><a href="javascript:void(0);" data-letter="I">I</a></li>
                    <li><a href="javascript:void(0);" data-letter="J">J</a></li>
                    <li><a href="javascript:void(0);" data-letter="K">K</a></li>
                    <li><a href="javascript:void(0);" data-letter="L">L</a></li>
                    <li><a href="javascript:void(0);" data-letter="M">M</a></li>
                    <li><a href="javascript:void(0);" data-letter="N">N</a></li>
                    <li><a href="javascript:void(0);" data-letter="O">O</a></li>
                    <li><a href="javascript:void(0);" data-letter="P">P</a></li>
                    <li><a href="javascript:void(0);" data-letter="Q">Q</a></li>
                    <li><a href="javascript:void(0);" data-letter="R">R</a></li>
                    <li><a href="javascript:void(0);" data-letter="S">S</a></li>
                    <li><a href="javascript:void(0);" data-letter="T">T</a></li>
                    <li><a href="javascript:void(0);" data-letter="U">U</a></li>
                    <li><a href="javascript:void(0);" data-letter="V">V</a></li>
                    <li><a href="javascript:void(0);" data-letter="W">W</a></li>
                    <li><a href="javascript:void(0);" data-letter="X">X</a></li>
                    <li><a href="javascript:void(0);" data-letter="Y">Y</a></li>
                    <li><a href="javascript:void(0);" data-letter="Z">Z</a></li>
                    <li><a href="javascript:void(0);" data-letter="0-9">其他</a></li>
                  </ul>
                </div>
                <div class="search" nctype="search">
                  <input name="search_brand_keyword" id="search_brand_keyword" type="text" class="text" placeholder="品牌名称关键字查找"/><a href="javascript:void(0);" class="ncbtn-mini" style="vertical-align: top;">Go</a></div>
              </div>
              <div class="brand-list" nctype="brandList">
                <ul nctype="brand_list">
                  <?php if(is_array($output['brand_list']) && !empty($output['brand_list'])){?>
                  <?php foreach($output['brand_list'] as $val) { ?>
                  <li data-id='<?php echo $val['brand_id'];?>'data-name='<?php echo $val['brand_name'];?>'><em><?php echo $val['brand_initial'];?></em><?php echo $val['brand_name'];?></li>
                  <?php } ?>
                  <?php }?>
                </ul>
              </div>
              <div class="no-result" nctype="noBrandList" style="display: none;">没有符合"<strong>搜索关键字</strong>"条件的品牌</div>
              <div class="tc"><a href="javascript:void(0);" class="ncbtn-mini" onclick="$(this).parents('.ncsc-brand-select-container:first').hide();">关闭品牌列表</a></div>
            </div>
            
          </div>
        </dd>
      </dl>
      <?php if(is_array($output['attr_list']) && !empty($output['attr_list'])){?>
      <dl>
        <dt>商品属性：</dt>
        <dd>
          <?php foreach ($output['attr_list'] as $k=>$val){?>
          <span class="property">
          <label class="mr5"><?php echo $val['attr_name']?></label>
          <input type="hidden" name="attr[<?php echo $k;?>][name]" value="<?php echo $val['attr_name']?>" />
          <?php if(is_array($val) && !empty($val)){?>
          <select name="" attr="attr[<?php echo $k;?>][__NC__]" nc_type="attr_select">
            <option value='不限' nc_type='0'>不限</option>
            <?php foreach ($val['value'] as $v){?>
            <option value="<?php echo $v['attr_value_name']?>" <?php if(isset($output['attr_checked']) && in_array($v['attr_value_id'], $output['attr_checked'])){?>selected="selected"<?php }?> nc_type="<?php echo $v['attr_value_id'];?>"><?php echo $v['attr_value_name'];?></option>
            <?php }?>
          </select>
          <?php }?>
          </span>
          <?php }?>
        </dd>
      </dl>
      <?php }?>
      <?php if (!empty($output['custom_list'])) {?>
      <dl>
        <dt>自定义属性：</dt>
        <dd>
          <?php foreach ($output['custom_list'] as $val) {?>
          <span class="property">
            <label class="mr5"><?php echo $val['custom_name'];?></label>
            <input type="hidden" name="custom[<?php echo $val['custom_id'];?>][name]" value="<?php echo $val['custom_name'];?>" />
            <input class="text w60" type="text" name="custom[<?php echo $val['custom_id'];?>][value]" value="<?php if ($output['goods']['goods_custom'][$val['custom_id']]['value'] != '') {echo $output['goods']['goods_custom'][$val['custom_id']]['value'];}?>" />
          </span>
          <?php }?>
        </dd>
      </dl>
      <?php }?>
      <dl>
        <dt>商品描述：</dt>
        <dd id="ncProductDetails">
          <div class="tabs">
            <ul class="ui-tabs-nav">
              <li class="ui-tabs-selected"><a href="#panel-1"><i class="icon-desktop"></i> 电脑端</a></li>
              <li class="selected"><a href="#panel-2"><i class="icon-mobile-phone"></i>手机端</a></li>
            </ul>
            <div id="panel-1" class="ui-tabs-panel">
              <?php showEditor('g_body',$output['goods']['goods_body'],'100%','480px');?>
              <div class="hr8">
                <div class="ncsc-upload-btn"> <a href="javascript:void(0);"><span>
                  <input type="file" hidefocus="true" size="1" class="input-file" name="add_album" id="add_album" multiple>
                  </span>
                  <p><i class="icon-upload-alt" data_type="0" nctype="add_album_i"></i>图片上传</p>
                  </a> </div>
                <a class="ncbtn mt5" nctype="show_desc" href="<?php echo ADMIN_SITE_URL;?>/index.php?con=lib_goods&fun=pic_list&item=des"><i class="icon-picture"></i>插入相册图片</a> <a href="javascript:void(0);" nctype="del_desc" class="ncbtn mt5" style="display: none;"><i class=" icon-circle-arrow-up"></i>关闭相册</a> </div>
              <p id="des_demo"></p>
            </div>
            <div id="panel-2" class="ui-tabs-panel ui-tabs-hide">
              <div class="ncsc-mobile-editor">
                <div class="pannel">
                  <div class="size-tip"><span nctype="img_count_tip">图片总数不得超过<em>20</em>张</span><i>|</i><span nctype="txt_count_tip">文字不得超过<em>5000</em>字</span></div>
                  <div class="control-panel" nctype="mobile_pannel">
                    <?php if (!empty($output['goods']['mb_body'])) {?>
                    <?php foreach ($output['goods']['mb_body'] as $val) {?>
                    <?php if ($val['type'] == 'text') {?>
                    <div class="module m-text">
                      <div class="tools"><a nctype="mp_up" href="javascript:void(0);">上移</a><a nctype="mp_down" href="javascript:void(0);">下移</a><a nctype="mp_edit" href="javascript:void(0);">编辑</a><a nctype="mp_del" href="javascript:void(0);">删除</a></div>
                      <div class="content">
                        <div class="text-div"><?php echo $val['value'];?></div>
                      </div>
                      <div class="cover"></div>
                    </div>
                    <?php }?>
                    <?php if ($val['type'] == 'image') {?>
                    <div class="module m-image">
                      <div class="tools"><a nctype="mp_up" href="javascript:void(0);">上移</a><a nctype="mp_down" href="javascript:void(0);">下移</a><a nctype="mp_rpl" href="javascript:void(0);">替换</a><a nctype="mp_del" href="javascript:void(0);">删除</a></div>
                      <div class="content">
                        <div class="image-div"><img src="<?php echo $val['value'];?>"></div>
                      </div>
                      <div class="cover"></div>
                    </div>
                    <?php }?>
                    <?php }?>
                    <?php }?>
                  </div>
                  <div class="add-btn">
                    <ul class="btn-wrap">
                      <li><a href="javascript:void(0);" nctype="mb_add_img"><i class="icon-picture"></i>
                        <p>图片</p>
                        </a></li>
                      <li><a href="javascript:void(0);" nctype="mb_add_txt"><i class="icon-font"></i>
                        <p>文字</p>
                        </a></li>
                    </ul>
                  </div>
                </div>
                <div class="explain">
                  <dl>
                    <dt>1、基本要求：</dt>
                    <dd>（1）手机详情总体大小：图片+文字，图片不超过20张，文字不超过5000字；</dd>
                    <dd>建议：所有图片都是本宝贝相关的图片。</dd>
                  </dl><dl>
                    <dt>2、图片大小要求：</dt>
                    <dd>（1）建议使用宽度480 ~ 620像素、高度小于等于960像素的图片；</dd>
                    <dd>（2）格式为：JPG\JEPG\GIF\PNG；</dd>
                    <dd>举例：可以上传一张宽度为480，高度为960像素，格式为JPG的图片。</dd>
                  </dl><dl>
                    <dt>3、文字要求：</dt>
                    <dd>（1）每次插入文字不能超过500个字，标点、特殊字符按照一个字计算；</dd>
                    <dd>（2）请手动输入文字，不要复制粘贴网页上的文字，防止出现乱码；</dd>
                    <dd>（3）以下特殊字符“<”、“>”、“"”、“'”、“\”会被替换为空。</dd>
                    <dd>建议：不要添加太多的文字，这样看起来更清晰。</dd>
                  </dl>
                </div>
              </div>
              <div class="ncsc-mobile-edit-area" nctype="mobile_editor_area">
                <div nctype="mea_img" class="ncsc-mea-img" style="display: none;"></div>
                <div class="ncsc-mea-text" nctype="mea_txt" style="display: none;">
                  <p id="meat_content_count" class="text-tip"></p>
                  <textarea class="textarea valid" nctype="meat_content"></textarea>
                  <div class="button"><a class="ncbtn ncbtn-bluejeansjeansjeans" nctype="meat_submit" href="javascript:void(0);">确认</a><a class="ncbtn ml10" nctype="meat_cancel" href="javascript:void(0);">取消</a></div>
                  <a class="text-close" nctype="meat_cancel" href="javascript:void(0);">X</a>
                </div>
              </div>
              <input name="m_body" autocomplete="off" type="hidden" value='<?php echo $output['goods']['mobile_body'];?>'>
            </div>
          </div>
        </dd>
      </dl>
      <h3>其他信息</h3>
      <dl>
        <dt>商品重量<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input name="goods_trans_kg" value="<?php echo $output['goods']['goods_trans_kg'];?>" type="text" class="text w60" />
          <span></span>
          <p class="hint"></p>
        </dd>
      </dl>
      <dl>
        <dt>商品体积<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input name="goods_trans_v" value="<?php echo $output['goods']['goods_trans_v'];?>" type="text" class="text w60" />
          <span></span>
          <p class="hint"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
var ADMIN_SITE_URL = "<?php echo ADMIN_SITE_URL; ?>";
var ADMIN_TEMPLATES_URL = "<?php echo ADMIN_TEMPLATES_URL; ?>";
var DEFAULT_GOODS_IMAGE = "<?php echo thumb(array(), 240);?>";

$(function(){
	$("#submitBtn").click(function(){
        if($("#post_form").valid()){
            $("#post_form").submit();
		}
	});
	
    $.validator.addMethod('checkPrice', function(value,element){
    	_g_price = parseFloat($('input[name="g_price"]').val());
        _g_marketprice = parseFloat($('input[name="g_marketprice"]').val());
        if (_g_marketprice <= 0) {
            return true;
        }
        if (_g_price > _g_marketprice) {
            return false;
        }else {
            return true;
        }
    }, '');
    $('#post_form').validate({
        errorPlacement: function(error, element){
            $(element).nextAll('span').append(error);
        },
        rules : {
            g_name : {
                required    : true,
                minlength   : 3,
                maxlength   : 50,
				remote   : {
                    url :ADMIN_SITE_URL+'/index.php?con=lib_goods&fun=check_name',
                    type:'get',
                    data:{
                        g_name : function(){
                            return $('#g_name').val();
                        },
                        goods_id : '<?php echo $output['goods']['goods_id'];?>'
                    }
                }
            },
            g_jingle : {
                maxlength   : 140
            },
            image_path : {
                required    : true
            },
            goods_trans_kg : {
                number   : true
            },
            goods_trans_v : {
                number   : true
            }
        },
        messages : {
            g_name  : {
                required    : '<i class="fa fa-exclamation-circle"></i>商品名称不能为空',
                minlength   : '<i class="fa fa-exclamation-circle"></i>商品标题名称长度至少3个字符，最长50个汉字',
                maxlength   : '<i class="fa fa-exclamation-circle"></i>商品标题名称长度至少3个字符，最长50个汉字',
				remote   : '<i class="fa fa-exclamation-circle"></i>商品已经存在，商品库中商品不能同名'
            },
            g_jingle : {
                maxlength   : '<i class="fa fa-exclamation-circle"></i>商品卖点不能超过140个字符'
            },
            image_path : {
                required    : '<i class="fa fa-exclamation-circle"></i>请设置商品主图'
            },
            goods_trans_kg : {
                number   : '<i class="fa fa-exclamation-circle"></i>请填写正确的重量'
            },
            goods_trans_v : {
                number   : '<i class="fa fa-exclamation-circle"></i>请填写正确的体积'
            }
        }
    });
});

</script> 
