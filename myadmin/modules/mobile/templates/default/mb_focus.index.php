<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
    <!-- 页面导航 -->
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>广告图设置</h3>
                <h5>广告图设置</h5>
            </div>
        </div>
    </div>
    <div id="item_edit_content" class="mb-item-edit-content">
        <?php require('mb_focus.adv_list.php');?>
    </div>
</div>
<div id="dialog_item_edit_image" style="display:none;">
    <div class="s-tips"><i class="fa fa-lightbulb-o"></i>请按提示尺寸制作上传图片，以达到手机客户端最佳显示效果。</div>
    <form id="focus_form" enctype="multipart/form-data" method="post">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="focus_id" id="focus_id" value="" />
        <input id="dialog_type" type="hidden" name="data_type">
        <div class="upload-thumb"> <img style="display: block;margin: 0 auto;" nctype="dialog_item_image" id="dialog_item_image" src="" alt=""></div>
        <input id="dialog_item_image_name" type="hidden" name="focus">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="focus_image"><em>*</em>图片</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
                        <span class="type-file-box">
                            <input name="focus_image" type="file" class="type-file-file" value="" id="focus_image" size="30" hidefocus="true">
                        </span>
                    </div>
                    <span class="err"></span>
                    <p class="notic">推荐图片尺寸750*350。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>排序</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="0" name="focus_sort" id="dialog_item_focus_sort" class="input-txt">
                    <span class="err"></span>
                    <p class="notic">数字范围为0~255，数字越小越靠前</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>操作类型</label>
                </dt>
                <dd class="opt">
                    <select id="dialog_item_image_type" name="image_type" class="vatop">
                        <option value="">-请选择-</option>
                        <option value="keyword">关键字</option>
                        <option value="goods">商品编号</option>
                        <option value="url">链接</option>
                    </select>
                    <input id="dialog_item_image_data" name="image_data" type="text" class="txt w200 marginright marginbot vatop">
                    <p id="dialog_item_image_desc" class="notic"></p>
                </dd>
            </dl>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/template.min.js" charset="utf-8"></script>
<script type="text/javascript">

    $(document).ready(function(){
        var $current_content = null;
        var $current_image = null;
        var $current_image_name = null;
        var $current_image_type = null;
        var $current_image_data = null;
        var $current_focus_sort = null;
        var $dialog_item_image = $('#dialog_item_image');
        var $dialog_item_image_name = $('#dialog_item_image_name');
        var $dialog_item_image_type = $('#dialog_item_image_type');
        var $dialog_item_image_data = $('#dialog_item_image_data');
        var $dialog_item_focus_sort = $('#dialog_item_focus_sort');

        //图片上传
        var textButton="<input type='text' name='textfield2' id='textfield2' class='type-file-text'/><input type='button' name='button' id='button2' value='选择上传...' class='type-file-button' />"
        $(textButton).insertBefore("#focus_image");
        $("#focus_image").change(function(){
            $("#textfield2").val($("#focus_image").val());
        });

        //按钮提交表单
        $("#submitBtn").click(function(){
            $("#focus_form").submit();
        });

        //编辑图片
        $('[nctype="btn_edit_item_image"]').on('click', function() {
            //初始化当前图片对象
            $item_image = $(this).parents('[nctype="item_image"]');
            $current_image = $item_image.find('[nctype="image"]');
            $current_image_name = $item_image.find('[nctype="image_name"]');
            $current_image_type = $item_image.find('[nctype="image_type"]');
            $current_image_data = $item_image.find('[nctype="image_data"]');
            $current_focus_sort = $item_image.find('[nctype="focus_sort"]');

            $dialog_item_image.show();
            $('#dialog_item_image').attr('src', $current_image.attr('src'));
            $('#dialog_item_image_name').val($current_image_name.val());
            $('#dialog_item_image_type').val($current_image_type.val());
            $('#dialog_item_image_data').val($current_image_data.val());
            $('#dialog_item_focus_sort').val($current_focus_sort.val());

            $('#dialog_type').val('edit');
            var focus_id = $(this).attr('data-id');
            $('#focus_id').val(focus_id);
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
            $dialog_item_image_type.val('');
            $dialog_item_image_data.val('');
            $dialog_item_focus_sort.val(0);
            $current_content = $(this).parent().find('[nctype="item_content"]');
            $('#dialog_type').val('add');
            change_image_type_desc($('#dialog_item_image_type').val());
            $('#dialog_item_edit_image').nc_show_dialog({
                width: 600,
                title: '添加'
            });
        });

        //删除图片
        $('#item_edit_content').on('click', '[nctype="btn_del_item_image"]', function() {
            var focus_id = $(this).attr('data-id');
            if(confirm('删除后将不能恢复，确认删除这项吗？')){
                $.getJSON('index.php?con=mb_focus_setting&fun=focus_del', {id:focus_id}, function(data){
                    if (data.state) {
                        location.reload();
                    } else {
                        showError(data.msg)
                    }
                });
            }
        });


        $('#dialog_item_image_type').on('change', function() {
            change_image_type_desc($(this).val());
        });

        function change_image_type_desc(type) {
            var desc_array = {};
            var desc = '操作类型一共三种，对应点击以后的操作。';
            if(type != '') {
                desc_array['keyword'] = '关键字类型会根据搜索关键字跳转到商品搜索页面，输入框填写搜索关键字。';
                desc_array['goods'] = '商品编号会跳转到指定的商品详细页面，输入框填写商品编号。';
                desc_array['url'] = '链接会跳转到指定链接，输入框填写完整的URL。';
                desc = desc_array[type];
            }
            $('#dialog_item_image_desc').text(desc);
        }
    });
</script>
