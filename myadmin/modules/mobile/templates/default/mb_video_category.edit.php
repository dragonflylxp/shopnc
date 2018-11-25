<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="index.php?con=mb_video_category&fun=video_category" title="返回视频分类列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>视频分类管理 - 编辑</h3>
                <h5>管理数据的新增、编辑、删除</h5>
            </div>
        </div>
    </div>
    <form id="video_category_form" enctype="multipart/form-data" method="post">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="cate_id" value="<?php echo $output['class_array']['cate_id']; ?>">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="cate_name"><em>*</em>分类名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['class_array']['cate_name']; ?>" name="cate_name" id="cate_name" maxlength="20" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="cate_description"><em>*</em>分类描述</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['class_array']['cate_description']; ?>" name="cate_description" id="cate_description" maxlength="20" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="cate_image"><em>*</em>分类图片</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
                        <span class="show">
                            <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.DS.ATTACH_MOBILE.'/video_category/'.$output['class_array']['cate_image'];?>">
                                <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.DS.ATTACH_MOBILE.'/video_category/'.$output['class_array']['cate_image'];?>>')" onMouseOut="toolTip()"></i></a>
                        </span>
                        <span class="type-file-box">
                            <input name="cate_image" type="file" class="type-file-file" id="cate_image" size="30" hidefocus="true">
                        </span>
                    </div>
                    <span class="err"></span>
                    <p class="notic">展示图片，建议大小90x90像素PNG图片。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>排序</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['class_array']['cate_sort']; ?>" name="cate_sort" id="cate_sort" class="input-txt">
                    <span class="err"></span>
                    <p class="notic">数字范围为0~255，数字越小越靠前</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">是否推荐</dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="recommend_1" class="cb-enable <?php if($output['class_array']['is_recommend']==1){?>selected<?php }?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
                        <label for="recommend_0" class="cb-disable <?php if($output['class_array']['is_recommend']==0){?>selected<?php }?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
                        <input id="recommend_1" <?php if($output['class_array']['is_recommend']==1){?>checked="checked"<?php }?> name="recommend" value="1" type="radio">
                        <input id="recommend_0" <?php if($output['class_array']['is_recommend']==0){?>checked="checked"<?php }?> name="recommend" value="0" type="radio">
                    </div>
                    <p class="notic">是否推荐前台显示，最多3个分类</p>
                </dd>
            </dl>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
        </div>
    </form>
</div>
<script>
    $(function(){
        //图片上传
        var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text'/><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
        $(textButton).insertBefore("#cate_image");
        $("#cate_image").change(function(){
            $("#textfield1").val($("#cate_image").val());
        });
        //按钮先执行验证再提交表单
        $("#submitBtn").click(function(){
            if($("#video_category_form").valid()){
                $("#video_category_form").submit();
            }
        });
        //表单验证
        $('#video_category_form').validate({
            errorPlacement: function(error, element){
                var error_td = element.parent('dd').children('span.err');
                error_td.append(error);
            },
            rules : {
                cate_name : {
                    required : true,
                    remote   : {
                        url :'index.php?con=mb_video_category&fun=ajax&branch=check_cate_name',
                        type:'get',
                        data:{
                            cate_name : function(){
                                return $('#cate_name').val();
                            },
                            cate_id : '<?php echo $output['class_array']['cate_id']; ?>'
                        }
                    }
                },
                cate_sort : {
                    number   : true
                },
                cate_description : {
                    required   : true
                }
            },
            messages : {
                cate_name : {
                    required : '<i class="fa fa-exclamation-circle"></i>分类名称不能为空',
                    remote   : '<i class="fa fa-exclamation-circle"></i>该分类名称已存在'
                },
                cate_sort  : {
                    number   : '<i class="fa fa-exclamation-circle"></i>请填写正确的分类排序'
                },
                cate_description : {
                    required : '<i class="fa fa-exclamation-circle"></i>分类描述不能为空'
                }
            }
        });


    });
</script>
