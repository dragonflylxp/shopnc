<?php if($output['class_count'] <20){?>

<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <form id="category_form" method="post" target="_parent" action="index.php?con=store_video&fun=album_add_save">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt><i class="required">*</i><?php echo '媒体库名称'.$lang['nc_colon'];?></dt>
      <dd>
        <input class="w300 text" type="text" name="name" id="name" value="" />
      </dd>
    </dl>
    <dl>
      <dt><?php echo '描述'.$lang['nc_colon'];?></dt>
      <dd>
        <textarea class="w300 textarea" rows="3" name="description" id="description"></textarea>
      </dd>
    </dl>
    <dl>
      <dt><?php echo '排序'.$lang['nc_colon'];?></dt>
      <dd>
        <input class="w50 text" type="text" name="sort" id="sort" value="" />
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border">
        <input type="submit" class="submit" value="提交" />
      </label>
    </div>
  </form>
</div>
<?php }else{?>
<div class="warning-option"><i class="icon-warning-sign">&nbsp;</i><span>最多只能添加20个视频</span></div>
<?php }?>
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
                maxlength	: 20,
                remote   : {
                    url :'index.php?con=store_video&fun=ajax_check_class_name&column=ok',
                    type:'get',
                    data:{
                        ac_name : function(){
                            return $('#name').val();
                        }
                    }
                }
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
                required : '<i class="icon-exclamation-sign"></i>视频名称不能为空',
                maxlength	: '<i class="icon-exclamation-sign"></i>视频名称不能超过20个字符',
                remote		: '<i class="icon-exclamation-sign"></i>视频名称已存在'
            },
            description : {
            	maxlength	: '<i class="icon-exclamation-sign"></i>描述不能超过100个字符'
            },
            sort  : {
            	digits   : '<i class="icon-exclamation-sign"></i>排序只能填写数字'
            }
        }
    });
});
</script> 
