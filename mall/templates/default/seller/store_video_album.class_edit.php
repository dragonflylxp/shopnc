
<div class="eject_con">
  <div id="warning"></div>
  <form id="category_form" method="post" target="_parent" action="index.php?con=store_video&fun=album_edit_save">
    <input type="hidden" name="id" value="<?php echo $output['class_info']['video_class_id'];?>" />
    <dl>
      <dt><i class="required">*</i><?php echo '媒体库名称'.$lang['nc_colon'];?></dt>
      <dd>
        <input class="w300 text" type="text" name="name" id="name" value="<?php echo $output['class_info']['video_class_name'];?>" />
      </dd>
    </dl>
    <dl>
      <dt><?php echo '描述'.$lang['nc_colon'];?></dt>
      <dd>
        <textarea rows="3" class="textarea w300" name="description" id="description"><?php echo $output['class_info']['video_class_des'];?></textarea>
      </dd>
    </dl>
    <dl>
      <dt><?php echo '排序'.$lang['nc_colon'];?></dt>
      <dd>
        <input type="text" class="text w50" name="sort" id="sort" value="<?php echo $output['class_info']['video_class_sort'];?>" />
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border">
        <input type="submit" class="submit" value="提交" />
      </label>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#category_form').validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
        submitHandler:function(form){
            ajaxpost('category_form', '', '', 'onerror') 
        },
        rules : {
        	name : {
                required : true,
                maxlength: 20
            },
            description : {
            	maxlength	: 100
            },
            sort : {
            	digits   : true
            }
        },
        messages : {
        	name : {
                required : '视频名称不能为空',
                maxlength	: '视频名称不能超过20个字符'
            },
            description : {
            	maxlength	: '描述不能超过100个字符'
            },
            sort  : {
            	digits   : '排序只能填写数字'
            }
        }
    });
});
</script>