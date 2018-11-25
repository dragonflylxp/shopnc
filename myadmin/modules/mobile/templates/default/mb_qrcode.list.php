<?php defined('Inshopec') or exit('Access Invalid!');


?>
<style>

#dialog_edit_qrcode .pre_view{
 width: 300px;
 margin: 4px auto;
 background: #fff;
 position: relative;
}

#dialog_edit_qrcode .pre_view img{border:none;width: 100%;}
#dialog_edit_qrcode .pre_view .member_name{
 display: block;
 line-height: 1em;
 font-size: 16px;
 position: absolute;
 left: 0;
 top:0;
}
#dialog_edit_qrcode .pre_view .qrcode_area{
 display: block;
 background: rgba(10,200,10,0.5);
 position: absolute;
 left: 0;
 top:0;
}

#dialog_edit_qrcode .pre_view .photo_area{
 display: block;
 background: rgba(97, 96, 200, 0.5);
 position: absolute;
 left: 0;
 top:0;
}



#dialog_edit_qrcode .pre_view .member_name{
 display: block;
 line-height: 1em;
 font-size: 16px;
 position: absolute;
 left: 0;
 top:0;
 cursor: all-scroll;
 user-select: none;
 -webkit-user-select: none;
 -moz-user-select: none;
}



#form_image .hot-point-list{
 max-height: 200px;
 overflow-y: scroll;
}
	
</style>
<div class="page" xmlns:top="http://www.w3.org/1999/xhtml">
    <!-- 页面导航 -->
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>模板设置</h3>
                <h5>手机客户端首页/分享模板设置</h5>
            </div>
            <ul class="tab-base nc-row">
                <?php foreach ($output['menu'] as $menu) {
                    if ($menu['menu_key'] == $output['menu_key']) { ?>
                        <li><a href="JavaScript:void(0);" class="current"><?php echo $menu['menu_name']; ?></a></li>
                    <?php } else { ?>
                        <li><a href="<?php echo $menu['menu_url']; ?>"><?php echo $menu['menu_name']; ?></a></li>
                    <?php }
                } ?>
            </ul>
        </div>
    </div>
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li>点击添加模板按钮可以添加新的模板，模板描述可以点击后直接修改</li>
            <li>点击保存按钮对专题内容进行保存</li>
            <li>点击删除按钮可以删除整个模板</li>
        </ul>
    </div>

    <!-- 列表 -->
    <form id="list_form" method="post">
        <table class="flex-table">
            <thead>
            <tr>
                <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
                <th width="150" align="center" class="handle"><?php echo $lang['nc_handle']; ?></th>
                <th width="60" align="center">模板编号</th>
<!--                <th width="200" align="center">类型</th>-->
                <th width="350">标题</th>
                <th width="270">专题图片</th>
                <th width="40px"></th>
                <th></th>
            </tr>
            </thead>
            <tbody id="treet1">
            <?php if (!empty($output['list']) && is_array($output['list'])) { ?>
                <?php foreach ($output['list'] as $keyword => $value) { ?>
                    <tr>
                        <td class="sign"><i class="ico-check"></i></td>
                        <td class="handle">
                            <!--<a href="javascript:;" nctype="btn_del" data-special-id="<?php /*echo $value['special_id'];*/ ?>" class="btn red"><i class="fa fa-trash-o"></i>删除</a>-->
                            <a href="javascript:;" data-position='<?php echo $value['position'] ?>'
                               data-param='<?php unset($value['position']);
                               echo json_encode($value); ?>' class="btn green style_edit"><i
                                    class="fa fa-save"></i>编辑</a>
                        </td>
                        <td><?php echo $value['id']; ?></td>
                        <!--<td class="type"><select>
                                <option value="0" <?php echo $value['type'] ? '' : 'selected' ?> >用户</option>
                    <option value="1" <?php echo $value['type']?'selected':'' ?> >商家</option>
                            </select></td>-->
                        <td class="title"><?php echo $value['title']; ?></td>
                        <td class="img" style="position: relative;">

                        </td>
                        <td><img class="img_preview" width="40px;" src="<?php echo $value['img_url'] ?>"></td>
                        <td></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td class="no-data" colspan="100"><i
                            class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php echo($output['page']) ?>
        </div>
    </form>
</div>
<form id="del_form" action="<?php echo urlAdminMobile('mb_special', 'qrcode_del'); ?>" method="post">
    <input type="hidden" id="del_special_id" name="special_id">
</form>
<div id="dialog_add_mb_special" style="display:none;">
    <form id="add_form" method="post" action="<?php echo urlAdminMobile('mb_special', 'qrcode_save'); ?>">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="title"><em>*</em>标题</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="title" class="input-txt">
                    <span class="err"></span>
                    <p class="notic">分享标题</p>
                </dd>
            </dl>
            <!--<dl class="row">
                <dt class="tit">
                    <label for="title"><em>*</em>类型</label>
                </dt>
                <dd class="opt">
                    <select name="type">
                        <option value="0">用户</option>
                        <option value="1">商家</option>
                    </select>
                    <span class="err"></span>
                    <p class="notic">商家模板或者用户</p>
                </dd>
            </dl>-->
            <div class="bot"><a id="submit" href="javascript:void(0)"
                                class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit']; ?></a></div>
        </div>
    </form>
</div>


<div id="dialog_edit_qrcode" style="display:none;">
    <div class="s-tips"><i class="fa fa-lightbulb-o"></i>请按提示尺寸制作上传图片，已达到手机客户端及Wap手机商城最佳显示效果。</div>
    <div class="upload-thumb " style="background: #F0F0F0;">

        <div class="pre_view">

            <img src=""/>

            <div class="member_name">用户名</div>

            <div class="qrcode_area"></div>
            <div class="photo_area"></div>
        </div>

    </div>


    <form id="form_image" action="">
        <div class="ncap-form-default">
            <input type="hidden" name="id">

            <dl class="row">
                <dt class="tit">保存二维码位置</dt>
                <dd class="opt">
                    <button type="button" class="ncap-btn-big ncap-btn-green" id="save_qrcode">保存</button>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">头像位置保存</dt>
                <dd class="opt">
                    <button type="button" class="ncap-btn-big ncap-btn-green" id="save_photo">保存</button>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">标题：</dt>
                <dd class="opt">
                    <input type="text" name="title">
                </dd>
            </dl>

            <!--<dl class="row">
                <dt class="tit">类型：</dt>
                <dd class="opt">
                    <select name="type">
                        <option value="0">用户</option>
                        <option value="1">商家</option>
                    </select>
                    <span class="err"></span>
                    <p class="notic">商家模板或者用户</p>
                </dd>
            </dl>-->

            <dl class="row">
                <dt class="tit">商品图片的相框：</dt>
                <dd class="opt">
                    <div class="input-file-show"><span class="type-file-box">
            <input type='text' name='textfield' id="image_name" class='type-file-text'/>
            <input type='button' name='button' value='选择上传...' class='type-file-button'/>
            <input class="btn_upload_image type-file-file" type="file" name="share_image" data-index="2" size="30"
                   hidefocus="true">
            </span></div>
                    <p id="dialog_image_desc" class="notic"></p>
                </dd>
            </dl>

            <div class="bot"><a id="btn_save_item" class="ncap-btn-big ncap-btn-green" href="javascript:;">保存</a></div>
        </div>
    </form>
</div>


<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/fileupload/jquery.iframe-transport.js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/fileupload/jquery.ui.widget.js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/fileupload/jquery.fileupload.js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL; ?>/js/jquery.edit.js"></script>

<link media="all" rel="stylesheet"
      href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.imgareaselect/imgareaselect-animated.css" type="text/css">
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.imgareaselect/jquery.imgareaselect.min.js"
        charset="utf-8"></script>


<script type="text/javascript">
    $(function () {
        $('.flex-table').flexigrid({
            height: 'auto',// 高度自动
            usepager: false,// 不翻页
            striped: false,// 不使用斑马线
            resizable: false,// 不调节大小
            title: '移动客户端专题模板列表',// 表格标题
            reload: false,// 不使用刷新
            columnControl: false,// 不使用列控制
            buttons: [
                {
                    display: '<i class="fa fa-plus"></i>新增模板',
                    name: 'add',
                    bclass: 'add',
                    title: '新增模板',
                    onpress: function () {
                        $('#dialog_add_mb_special').nc_show_dialog({title: '新增模板'});
                    }
                }
            ]
        });
        //添加专题
        $('#btn_add_mb_special').on('click', function () {
            $('#dialog_add_mb_special').nc_show_dialog({title: '新增模板'});
        });

        //提交
        $("#submit").click(function () {


            $.ajax({
                url: '<?php echo urlAdminMobile('mb_special', 'qrcode_save');?>',
                type: 'post',
                dataType: 'json',
                data: $('#add_form').serialize(),
                success: function (rs) {
                    alert(rs.datas);
                    if (rs.datas == '保存成功') {
                        window.location.href=document.referrer;
                    }
                }

            })

        });

        $('#add_form').validate({
            errorPlacement: function (error, element) {
                var error_td = element.parent('dd').children('span.err');
                error_td.append(error);
            },
            rules: {
                title: {
                    required: true,
                    maxlength: 30
                }
            },
            messages: {
                title: {
                    required: "<i class='fa fa-exclamation-circle'></i>模板描述不能为空",
                    maxlength: "<i class='fa fa-exclamation-circle'></i>模板描述最多30个字"
                }
            }
        });

        //删除专题
        $('[nctype="btn_del"]').on('click', function () {
            if (confirm('确认删除?')) {
                $('#del_special_id').val($(this).attr('data-share-id'));
                $('#del_form').submit();
            }
        });


        var url_upload_image = '<?php echo urlAdminMobile('mb_special', 'share_image_upload');?>';

        //图片上传
        $(".btn_upload_image").fileupload({
            dataType: 'json',
            url: url_upload_image,
            formData: {special_id: 0},
            add: function (e, data) {
                data.submit();
            },
            done: function (e, data) {
                var result = data.result;
                if (typeof result.error === 'undefined') {
                    var index = $(e.target).data('index');
                    $('.pre_view img').append().attr('src', result.image_url);
                    $(e.target).siblings('[name="textfield"]').val(result.image_name);
                } else {
                    showError(result.error);
                }
            }
        });


        var _position;

        var _tmp_position;


        $('#save_qrcode').on('click', function () {
            if (_tmp_position && _tmp_position.width > 0) {
                var h = $('#dialog_edit_qrcode .pre_view img').height();
                _position.width = (_tmp_position.width / 300).toFixed(3);
                _position.x1 = (_tmp_position.x1 / 300).toFixed(3);
                _position.y1 = (_tmp_position.y1 / h).toFixed(3);

                $('.pre_view .qrcode_area').css({
                    width: _position.width * 300 + 'px',
                    height: _position.width * 300 + 'px',
                    left: _position.x1 * 300 + 'px',
                    top: _position.y1 * h + 'px'
                });

            }
        })
        $('#save_photo').on('click', function () {
            if (_tmp_position && _tmp_position.width > 0) {
                var h = $('#dialog_edit_qrcode .pre_view img').height();
                _position.width2 = (_tmp_position.width / 300).toFixed(3);
                _position.x2 = (_tmp_position.x1 / 300).toFixed(3);
                _position.y2 = (_tmp_position.y1 / h).toFixed(3);

                $('.pre_view .photo_area').css({
                    width: _position.width2 * 300 + 'px',
                    height: _position.width2 * 300 + 'px',
                    left: _position.x2 * 300 + 'px',
                    top: _position.y2 * h + 'px'
                });

            }
        })

        $('.style_edit').on('click', function () {

            var param = $(this).data('param');
            var position = $(this).data('position');

            $('#dialog_edit_qrcode [name="title"]').val(param.title);
            $('#dialog_edit_qrcode #image_name').val(param.image);
            $('#dialog_edit_qrcode [name="id"]').val(param.id);
            $('#dialog_edit_qrcode .pre_view img').attr('src', param.img_url);
            $('#dialog_edit_qrcode [name="type"] option[value="' + param.type + '"]').prop('selected', true);

            if (position == "") {
                _position = {width: 0.1, x1: 0, y1: 0, x2: 0, y2: 0, 3: 0, y3: 0};
            } else {
                _position = position;
            }

            $('#dialog_edit_qrcode .pre_view img')[0].onload = function () {

                var h = $(this).height();

                $('.pre_view .member_name').css({left: _position.x3 * 300 + 'px', top: _position.y3 * h + 'px'});

            $('.pre_view .qrcode_area').css({width:_position.width*300+'px',height:_position.width*300+'px',left:_position.x1*300+'px',top:_position.y1*h+'px'});
            $('.pre_view .photo_area').css({width:_position.width2*300+'px',height:_position.width2*300+'px',left:_position.x2*300+'px',top:_position.y2*h+'px'});

            st_x=300;
            st_y=h;

                s_img = $('#dialog_edit_qrcode .pre_view img').imgAreaSelect({
                    aspectRatio: '1:1',
                    instance: true,
                    handles: true,
                    onSelectEnd: function (a, b, c) {

                        _tmp_position = b;
                    }
                });
            }


            $('#dialog_edit_qrcode').nc_show_dialog({title: '样式'});


        });


        var st_x;
        var st_y;
        var s_img;
        $('.pre_view .member_name').mousedown(function (e) {

            var self = this,
                st_x = e.pageX,
                st_y = e.pageY,
                n_x = Number($(self).css('left').replace('px', '')),
                n_y = Number($(self).css('top').replace('px', '')),
                w = $(this).width(),
                h = $(this).height();
            $('.pre_view .member_name').mousemove(function (e) {

            var top=n_y+(e.pageY-st_y);
            var left=n_x+(e.pageX-st_x);
            if(top<0||left<0||top>st_y-h||left>st_x-w){
                return
            }
            $(self).css({top:top+'px',left:left+'px'});

            });


        })

        $(document).mouseup(function () {
            $('.pre_view .member_name').unbind('mousemove');
        });


        $('#btn_save_item').click(function () {
            var p = $('#form_image');

            _position.x3 = (Number($('.pre_view .member_name').css('left').replace('px', '')) / 300).toFixed(3);
            _position.y3 = (Number($('.pre_view .member_name').css('top').replace('px', '')) / $('.pre_view img').height()).toFixed(3);

            var data = {
                'id': p.find('[name="id"]').val(),
                'title': p.find('[name="title"]').val(),
                'image': p.find('#image_name').val(),
                'type': p.find('[name="type"]').val(),
                'position': JSON.stringify(_position)
            };

            $.ajax({
                url: '<?php echo urlAdminMobile('mb_special', 'qrcode_update');?>',
                type: 'post',
                dataType: 'json',
                data: data,
                success: function (rs) {
                    alert(rs.datas);
                }
            })
        })


    });


    function fg_operation(name, bDiv) {
        if (name == 'add') {
            window.location.href = 'javascript:;';

        }
    }
</script> 
