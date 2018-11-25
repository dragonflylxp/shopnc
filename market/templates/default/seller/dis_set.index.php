<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
  <form method="post" id="post_form" action="<?php echo DISTRIBUTE_SITE_URL;?>/index.php?con=store_dis_set&fun=index">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt>默认佣金比例<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input class="text w30" name="dis_commis_rate" maxlength="2" type="text"  id="dis_commis_rate" value="<?php echo $output['dis_commis_rate'];?>" />
        %
        <label for="dis_commis_rate" class="error"></label>
        <p class="hint">最小为1，最大为30，只能为整数。</p>
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_common_button_save'];?>" /></label>
      </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('#post_form').validate({
    	submitHandler:function(form){
    		ajaxpost('post_form', '', '', 'onerror')
    	},
		rules : {
			dis_commis_rate: {
                required    : true,
                number      : true,
                min         : 1,
                max         : 30,
			}
        },
        messages : {
        	dis_commis_rate: {
                required    : '请填写正确的佣金比例',
                number      : '请填写正确的佣金比例',
                min         : '佣金比例最小为1',
                max         : '佣金比例最大为30',
			}
        }
    });    
    
});
</script> 
