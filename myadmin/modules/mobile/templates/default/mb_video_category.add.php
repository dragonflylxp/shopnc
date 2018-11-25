<?php defined('Inshopec') or exit('Access Invalid!');?>
<style>
    .input-file-show{ padding-left: 0}
    .input-file-show .type-file-box .err{ position: absolute; width: 120px}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?con=mb_video_category&fun=video_category" title="返回视频分类列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>视频分类管理 - 新增</h3>
        <h5>管理数据的新增、编辑、删除</h5>
      </div>
    </div>
  </div>
  <form id="video_category_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="cate_name"><em>*</em>分类名称</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="cate_name" id="cate_name" maxlength="20" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
        <dl class="row">
            <dt class="tit">
                <label for="cate_description"><em>*</em>分类描述</label>
            </dt>
            <dd class="opt">
                <input type="text" value="" name="cate_description" id="cate_description" maxlength="20" class="input-txt">
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
                    <span class="type-file-box">
                        <input name="cate_image" type="file" class="type-file-file" id="cate_image" size="30" hidefocus="true">
                        <span class="err"></span>
                    </span>
                </div>
                <p class="notic">展示图片，建议大小226x226像素的图片。</p>
            </dd>
        </dl>
      <dl class="row">
        <dt class="tit">
          <label>排序</label>
        </dt>
        <dd class="opt">
          <input type="text" value="0" name="cate_sort" id="cate_sort" class="input-txt">
          <span class="err"></span>
          <p class="notic">数字范围为0~255，数字越小越靠前</p>
        </dd>
      </dl>
        <dl class="row">
            <dt class="tit">是否推荐</dt>
            <dd class="opt">
                <div class="onoff">
                    <label for="recommend_1" class="cb-enable" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
                    <label for="recommend_0" class="cb-disable selected" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
                    <input id="recommend_1" name="recommend" value="1" type="radio">
                    <input id="recommend_0" checked name="recommend" value="0" type="radio">
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
    var textButton="<input type='text' name='textfield' id='textfield' class='type-file-text'/><input type='button' name='button' id='button2' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#cate_image");
    $("#cate_image").change(function(){
        $("#textfield").val($("#cate_image").val());
    });
    // 上传图片类型
    $('input[class="type-file-file"]').change(function(){
        var filepath=$(this).val();
        var extStart=filepath.lastIndexOf(".");
        var ext=filepath.substring(extStart,filepath.length).toUpperCase();
        if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
            alert("图片限于png,gif,jpeg,jpg格式");
            $(this).attr('value','');
            return false;
        }
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
			var error_td = element.nextAll('span.err');
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
                    cate_id : ''
                  }
                }
            },
            cate_sort : {
                number   : true
            },
            cate_description : {
                required   : true,
                maxlength  : 10
            },
            textfield  : {
                required : true
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
                required : '<i class="fa fa-exclamation-circle"></i>分类描述不能为空',
                maxlength: '<i class="fa fa-exclamation-circle"></i>分类描述字数在10字以内'
            },
            textfield  : {
                required : '<i class="fa fa-exclamation-circle"></i>分类图片不能为空'
            }
        }
    });


});
</script> 
