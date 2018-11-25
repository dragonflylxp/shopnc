<?php defined('Inshopec') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
    <?php if(empty($output['pintuan_info'])) { ?>
    <form id="add_form" action="index.php?con=store_promotion_pintuan&fun=pintuan_save" method="post">
    <?php } else { ?>
    <form id="add_form" action="index.php?con=store_promotion_pintuan&fun=pintuan_edit_save" method="post">
        <input type="hidden" name="pintuan_id" value="<?php echo $output['pintuan_info']['pintuan_id'];?>">
    <?php } ?>
    <dl>
      <dt><i class="required">*</i>活动名称<?php echo $lang['nc_colon'];?></dt>
      <dd>
          <input id="pintuan_name" name="pintuan_name" type="text"  maxlength="25" class="text w400" value="<?php echo empty($output['pintuan_info'])?'':$output['pintuan_info']['pintuan_name'];?>"/>
          <span></span>
        <p class="hint"><?php echo $lang['pintuan_name_explain'];?></p>
      </dd>
    </dl>
    <?php if(empty($output['pintuan_info'])) { ?>
    <dl>
      <dt><i class="required">*</i>开始时间<?php echo $lang['nc_colon'];?></dt>
      <dd>
          <input id="start_time" name="start_time" type="text" class="text w130" /><em class="add-on"><i class="icon-calendar"></i></em><span></span>
        <p class="hint">
<?php if (!$output['isOwnShop'] && $output['current_pintuan_quota']['start_time'] > 1) { ?>
        开始时间不能为空且不能早于<?php echo date('Y-m-d H:i',$output['current_pintuan_quota']['start_time']);?>
<?php } ?>
        </p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>结束时间<?php echo $lang['nc_colon'];?></dt>
      <dd>
          <input id="end_time" name="end_time" type="text" class="text w130"/><em class="add-on"><i class="icon-calendar"></i></em><span></span>
        <p class="hint">
<?php if (!$output['isOwnShop']) { ?>
        结束时间不能为空且不能晚于<?php echo date('Y-m-d H:i',$output['current_pintuan_quota']['end_time']);?>
<?php } ?>
        </p>
      </dd>
    </dl>
    <?php } ?>
    <dl>
      <dt><i class="required">*</i>参团人数<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input id="min_num" name="min_num" type="text" class="text w130" value="<?php echo empty($output['pintuan_info'])?'3':$output['pintuan_info']['min_num'];?>"/><span></span>
        <p class="hint">建议设置3到5人，因人数越多成团机率越小</p>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border"><input id="submit_button" type="submit" class="submit" value="<?php echo $lang['nc_submit'];?>"></label>
    </div>
  </form>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.css"  />
<script>
$(document).ready(function(){
    <?php if(empty($output['pintuan_info'])) { ?>
    $('#start_time').datetimepicker({
        controlType: 'select'
    });

    $('#end_time').datetimepicker({
        controlType: 'select'
    });
    <?php } ?>

    jQuery.validator.methods.greaterThanDate = function(value, element, param) {
        var date1 = new Date(Date.parse(param.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 < date2;
    };
    jQuery.validator.methods.lessThanDate = function(value, element, param) {
        var date1 = new Date(Date.parse(param.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 > date2;
    };
    jQuery.validator.methods.greaterThanStartDate = function(value, element) {
        var start_date = $("#start_time").val();
        var date1 = new Date(Date.parse(start_date.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 < date2;
    };

    //页面输入内容验证
    $("#add_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span');
            error_td.append(error);
        },
        onfocusout: false,
    	submitHandler:function(form){
    		ajaxpost('add_form', '', '', 'onerror');
    	},
        rules : {
            pintuan_name : {
                required : true
            },
            start_time : {
                required : true,
                greaterThanDate : '<?php echo date('Y-m-d H:i',TIMESTAMP);?>'
            },
            end_time : {
                required : true,
<?php if (!$output['isOwnShop']) { ?>
                lessThanDate : '<?php echo date('Y-m-d H:i',$output['current_pintuan_quota']['end_time']);?>',
<?php } ?>
                greaterThanStartDate : true
            },
            min_num: {
                required: true,
                digits: true,
                min: 2
            }
        },
        messages : {
            pintuan_name : {
                required : '<i class="icon-exclamation-sign"></i>活动名称不能为空'
            },
            start_time : {
            required : '<i class="icon-exclamation-sign"></i>开始时间不能为空',
                greaterThanDate : '<i class="icon-exclamation-sign"></i>开始时间不能为空且不能早于<?php echo date('Y-m-d H:i',TIMESTAMP);?>'
            },
            end_time : {
            required : '<i class="icon-exclamation-sign"></i>结束时间不能为空',
<?php if (!$output['isOwnShop']) { ?>
                lessThanDate : '<i class="icon-exclamation-sign"></i>结束时间不能为空且不能晚于<?php echo date('Y-m-d H:i',$output['current_pintuan_quota']['end_time']);?>',
<?php } ?>
                greaterThanStartDate : '<i class="icon-exclamation-sign"></i>结束时间必须大于开始时间'
            },
            min_num: {
                required : '<i class="icon-exclamation-sign"></i>参团人数不能为空',
                digits: '<i class="icon-exclamation-sign"></i>参团人数必须为数字',
                min: '<i class="icon-exclamation-sign"></i>参团人数不能小于2'
            }
        }
    });
});
</script>
