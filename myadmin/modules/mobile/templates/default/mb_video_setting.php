<?php defined('Inshopec') or exit('Access Invalid!');?>
<style>
  .input-file-show{ padding-left: 0}
  .input-file-show .type-file-box .err{ position: absolute; width: 120px}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>视频设置</h3>
        <h5>视频模块的开启和关闭</h5>
      </div>
    </div>
  </div>
  <form method="post" name="settingForm" enctype="multipart/form-data" id="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">视频设置</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="video_isuse_1" class="cb-enable <?php if($output['list_setting']['video_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="video_isuse_0" class="cb-disable <?php if($output['list_setting']['video_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="video_isuse_1" name="video_isuse" <?php if($output['list_setting']['video_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="video_isuse_0" name="video_isuse" <?php if($output['list_setting']['video_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic">视频启用后，会员将可以通过移动端查看点播和直播模块</p>
        </dd>
      </dl>
      <dl class="row" id="name">
        <dt class="tit">
          <label for="video_modules_name"><em>*</em>视频模块名称</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['list_setting']['video_modules_name'];?>" name="video_modules_name" id="video_modules_name" maxlength="20" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row" id="logo">
        <dt class="tit">
          <label for="video_modules_logo"><em>*</em>视频模块LOGO</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show">
            <span class="show">
            <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_COMMON.DS.$output['list_setting']['video_modules_logo']);?>">
              <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_COMMON.DS.$output['list_setting']['video_modules_logo']);?>>')" onMouseOut="toolTip()"/></i>
            </a>
            </span>
            <span class="type-file-box">
            <input class="type-file-file" id="video_logo" name="video_logo" type="file" size="30"  title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            <span class="err"></span>
            </span></div>
          <p class="notic">视频模块logo，前台页面显示，最佳显示尺寸为34*32像素</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>
<script>
$(function(){
  <?php if(C('video_isuse') == 0) { ?>
    $('#name').css('display','none');
    $('#logo').css('display','none');
  <?php } ?>
  $('#video_isuse_1').click(function(){
    $('#name').css('display','');
    $('#logo').css('display','');
  });
  $('#video_isuse_0').click(function(){
    $('#name').css('display','none');
    $('#logo').css('display','none');
  });
  //图片上传
  var textButton="<input type='text' name='textfield' id='textfield' class='type-file-text'/><input type='button' name='button' id='button2' value='选择上传...' class='type-file-button' />"
  $(textButton).insertBefore("#video_logo");
  $("#video_logo").change(function(){
    $("#textfield").val($("#video_logo").val());
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
// 点击查看图片
  $('.nyroModal').nyroModal();
  $("#submitBtn").click(function(){
      if($("#settingForm").valid()){
        $("#settingForm").submit();
      }
	});
  $('#settingForm').validate({
    errorPlacement: function(error, element){
      $(element).nextAll('span').append(error);
    },
    rules : {
      video_modules_name : {
        required	: function () {if ($('#video_isuse_1').prop("checked")) {return true;} else {return false;}}
      },
      <?php if(!file_exists(BASE_UPLOAD_PATH.'/'.(ATTACH_COMMON.'/'.$output['list_setting']['video_modules_logo']))){ ?>
      textfield : {
        required	: function () {if ($('#video_isuse_1').prop("checked")) {return true;} else {return false;}}
      }
      <?php } ?>
    },
    messages : {
      video_modules_name : {
        required	: '请填写视频模块名称'
      },
      <?php if(!file_exists(BASE_UPLOAD_PATH.'/'.(ATTACH_COMMON.'/'.$output['list_setting']['video_modules_logo']))){ ?>
      textfield : {
        required	: '请上传视频模块logo'
      }
      <?php } ?>
    }
  });
});
</script>
