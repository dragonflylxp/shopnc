<?php defined('Inshopec') or exit('Access Invalid!');?>
<?php defined('Inshopec') or exit('Access Invalid!');?>

<link href="<?php echo SHOP_TEMPLATES_URL?>/css/shop_custom.css" rel="stylesheet" type="text/css">
	<link href="<?php echo SHOP_TEMPLATES_URL?>/css/shop_store_mb.css" rel="stylesheet" type="text/css">
<div class="ncsc-path"><i class="icon-desktop"></i>商家管理中心<i class="icon-angle-right"></i>店铺<i class="icon-angle-right"></i>手机店铺装修<i class="icon-angle-right"></i>页面设计</div>
<div class="page"> 

  <form id="form_item" action="<?php echo urlShop('mb_store_decoration', 'special_item_save');?>" method="post">
    <input type="hidden" name="special_id" value="<?php echo $output['item_info']['special_id'];?>">
    <input type="hidden" name="item_id" value="<?php echo $output['item_info']['item_id'];?>">
    <?php $item_data = $output['item_info']['item_data'];?>
    <?php $item_edit_flag = true;?>
    <div id="item_edit_content" class="mb-item-edit-content">
      <?php require('mb_special_item.module_' . $output['item_info']['item_type'] . '.php');?>
    </div>
    <div class="bot"><a id="btn_save" class="ncap-btn-big ncap-btn-green" href="javascript:;">保存编辑</a> </div>
  </form>
</div>
<div id="dialog_item_edit_image" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i>请按提示尺寸制作上传图片，以达到手机客户端及Wap手机商城最佳显示效果。</div>
  <div class="upload-thumb"> <img style="display: block;margin: 0 auto;" id="dialog_item_image" src="" alt=""></div>
  <input id="dialog_item_image_name" type="hidden">
  <input id="dialog_type" type="hidden">
  <form id="form_image" action="">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">选择要上传的图片：</dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type='text' name='textfield' id='textfield' class='type-file-text' />
            <input type='button' name='button' id='button' value='选择上传...' class='type-file-button' />
            <input id="btn_upload_image" type="file" name="special_image" class="type-file-file" size="30" hidefocus="true" >
            </span> </div>
          <p id="dialog_image_desc" class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">操作类型：</dt>
        <dd class="opt">
          <select id="dialog_item_image_type" name="" class="vatop">
            <option value="">-请选择-</option>
            <option value="keyword">关键字</option>
            <!--<option value="special">专题编号</option>-->
            <option value="goods">商品编号</option>
            <option value="url">链接</option>
          </select>
          <input id="dialog_item_image_data" type="text" class="txt w200 marginright marginbot vatop">
          <p id="dialog_item_image_desc" class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a id="btn_save_item" class="ncap-btn-big ncap-btn-green" href="javascript:;">保存</a></div>
    </div>
  </form>
</div>
<div id="dialog_item_edit_gg" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i>请填写标题、内容</div>
  <div class="upload-thumb"> <img style="display: block;margin: 0 auto;" id="dialog_item_image" src="" alt=""></div>
  <input id="dialog_item_image_name" type="hidden">
  <input id="dialog_type_gg" type="hidden">
  <form id="form_image" action="">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">*标题：</dt>
        <dd class="opt">
          <div class="input-file-showg"><span class="type-file-box">
            <input type='text' name='textfieldg' id='textfieldg' class='type-file-textg' />

            </span> </div>
          <p id="dialog_image_desc" class="notic"></p>
        </dd>
        <dt class="tit">*内容：</dt>
        <dd class="opt">
          <textarea name="statistics_code" rows="6" class="tarea" id="statistics_code"></textarea>
        </dd>
      </dl>
      <div class="bot"><a id="btn_save_itemjj" class="ncap-btn-big ncap-btn-green" href="javascript:;">保存</a></div>
    </div>
  </form>
</div>
<script id="item_image_template" type="text/html">
    <div nctype="item_image" class="item">
        <img nctype="image" src="<%=image%>" alt="">
        <input nctype="image_name" name="item_data[item][<%=image_name%>][image]" type="hidden" value="<%=image_name%>">
        <input nctype="image_type" name="item_data[item][<%=image_name%>][type]" type="hidden" value="<%=image_type%>">
        <input nctype="image_data" name="item_data[item][<%=image_name%>][data]" type="hidden" value="<%=image_data%>">
        <a nctype="btn_del_item_image" href="javascript:;">删除</a>
    </div>
</script>
<script id="item_image_templatejj" type="text/html">
    <div nctype="item_image" class="item">
        <input nctype="image_name" name="item_data[item][<%=image_name%>][image]" type="hidden" value="<%=image_name%>">
        <input nctype="image_data" name="item_data[item][<%=image_name%>][data]" type="hidden" value="<%=image_data%>">
        <a nctype="btn_del_item_imagejj" href="javascript:;">删除</a>
    </div>
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/template.min.js" charset="utf-8"></script> 


<script type="text/javascript">
	  var LOADING_IMAGE = "<?php echo SHOP_TEMPLATES_URL.DS.'images/loading.gif';?>";
    var url_upload_image = '<?php echo urlShop('mb_store_decoration', 'special_image_upload');?>';

    $(document).ready(function(){
        var $current_content = null;
        var $current_image = null;
        var $current_image_name = null;
        var $current_image_type = null;
        var $current_image_data = null;
        var old_image = '';
        var $dialog_item_image = $('#dialog_item_image');
        var $dialog_item_image_name = $('#dialog_item_image_name');
        var special_id = <?php echo $output['item_info']['special_id'];?>;

        //保存
        $('#btn_save').on('click', function() {
            $('#form_item').submit();
        });

        //编辑图片
        $('[nctype="btn_edit_item_image"]').on('click', function() {
            //初始化当前图片对象
            $item_image = $(this).parents('[nctype="item_image"]');
            $current_image = $item_image.find('[nctype="image"]');
            $current_image_name = $item_image.find('[nctype="image_name"]');
            $current_image_type = $item_image.find('[nctype="image_type"]');
            $current_image_data = $item_image.find('[nctype="image_data"]');

            $('#dialog_item_image').attr('src', $current_image.attr('src'));
            $('#dialog_item_image_name').val($current_image_name.val());
            $('#dialog_item_image_type').val($current_image_type.val());
            $('#dialog_item_image_data').val($current_image_data.val());
            $('#dialog_image_desc').text('推荐图片尺寸' + $(this).attr('data-desc'));
            $('#dialog_type').val('edit');
            change_image_type_desc($('#dialog_item_image_type').val());
            $('#dialog_item_edit_image').nc_show_dialog({
                width: 600,
                title: '编辑'
            });
        });

        //添加图片
        $('[nctype="btn_add_item_image"]').on('click', function() {
            $dialog_item_image.hide();
            $dialog_item_image_name.val('');
            $current_content = $(this).parent().find('[nctype="item_content"]');
            $('#dialog_image_desc').text('推荐图片尺寸' + $(this).attr('data-desc'));
            $('#dialog_type').val('add');
            change_image_type_desc($('#dialog_item_image_type').val());
            $('#dialog_item_edit_image').nc_show_dialog({
                width: 600,
                title: '添加'
            });
        });
        //添加公告
        $('[nctype="btn_add_item_gg"]').on('click', function() {
            //$dialog_item_image.hide();
            //$dialog_item_image_name.val('');
            $current_content = $(this).parent().find('[nctype="item_content"]');
            //$('#dialog_image_desc').text('推荐图片尺寸' + $(this).attr('data-desc'));
            $('#dialog_type_gg').val('add');
            //change_image_type_desc($('#dialog_item_image_type').val());
            $('#dialog_item_edit_gg').nc_show_dialog({
                width: 600,
                title: '添加'
            });
        });
        //添加简介
        $('[nctype="btn_add_item_jj"]').on('click', function() {
            //$dialog_item_image.hide();
            //$dialog_item_image_name.val('');
            $current_content = $(this).parent().find('[nctype="item_content"]');
            //$('#dialog_image_desc').text('推荐图片尺寸' + $(this).attr('data-desc'));
            $('#dialog_type_gg').val('add');
            //change_image_type_desc($('#dialog_item_image_type').val());
            $('#dialog_item_edit_gg').nc_show_dialog({
                width: 600,
                title: '添加'
            });
        }); 
        $('#btn_save_itemjj').on('click', function() {

            var itemjj = {};
            itemjj.image = $('#dialog_item_image').attr('src');
            itemjj.image_name = $('#dialog_item_image_name').val();
            itemjj.image_type = $('#dialog_item_image_type').val();
            itemjj.image_data = $('#dialog_item_image_data').val();
            $current_content.append(template.render('item_image_templatejj', itemjj));
        });  
        //删除图片
        $('#item_edit_content').on('click', '[nctype="btn_del_item_image"]', function() {
            $(this).parents('[nctype="item_image"]').remove();
        });
        //删除简介
        $('#item_edit_content').on('click', '[nctype="btn_del_item_imagejj"]', function() {
            $(this).parents('[nctype="item_image"]').remove();
        });
        //图片上传
        $("#btn_upload_image").fileupload({
            dataType: 'json',
            url: url_upload_image,
            formData: {special_id: special_id},
            add: function(e, data) {
                old_image = $dialog_item_image.attr('src');
                $dialog_item_image.attr('src', LOADING_IMAGE);
                data.submit();
            },
            done: function (e, data) {
                var result = data.result;
                if(typeof result.error === 'undefined') {
                    $dialog_item_image.attr('src', result.image_url);
                    $dialog_item_image.show();
                    $dialog_item_image_name.val(result.image_name);
                } else {
                    $dialog_item_image.attr('src') = old_image;
                    showError(result.error);
                }
            }
        });

        $('#btn_save_item').on('click', function() {
            var type = $('#dialog_type').val();
            if(type == 'edit') {
                edit_item_image_save();
            } else {
                if($dialog_item_image_name.val() == '') {
                    showError('请上传图片');
                    return false;
                }
                add_item_image_save();
            }
            $('#dialog_item_edit_image').hide();
        });

        function edit_item_image_save() {
            $current_image.attr('src', $('#dialog_item_image').attr('src'));
            $current_image_name.val($('#dialog_item_image_name').val());
            $current_image_type.val($('#dialog_item_image_type').val());
            $current_image_data.val($('#dialog_item_image_data').val());
        }

        function add_item_image_save() {
            var $html_item_image = $('#html_item_image');
            var item = {};
            item.image = $('#dialog_item_image').attr('src');
            item.image_name = $('#dialog_item_image_name').val();
            item.image_type = $('#dialog_item_image_type').val();
            item.image_data = $('#dialog_item_image_data').val();
            $current_content.append(template.render('item_image_template', item));
        }


        $('#dialog_item_image_type').on('change', function() {
            change_image_type_desc($(this).val());
        });

        function change_image_type_desc(type) {
            var desc_array = {};
            var desc = '操作类型一共四种，对应点击以后的操作。';
            if(type != '') {
                desc_array['keyword'] = '关键字类型会根据搜索关键字跳转到商品搜索页面，输入框填写搜索关键字。';
                desc_array['special'] = '专题编号会跳转到指定的专题，输入框填写专题编号。';
                desc_array['goods'] = '商品编号会跳转到指定的商品详细页面，输入框填写商品编号。';
                desc_array['url'] = '链接会跳转到指定链接，输入框填写完整的URL。';
                desc = desc_array[type];
            }
            $('#dialog_item_image_desc').text(desc);
        }
    });
    </script> 
