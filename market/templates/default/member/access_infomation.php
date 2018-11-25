<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu'); ?>
    <?php if($output['member_info']['distri_state'] == 2){?>
    <a class="ncbtn ncbtn-bittersweet" href="index.php?con=access_infomation&fun=distri_quit"><i class="icon-shield"></i>退出分销</a>
    <?php }?>
  </div>
  <div class="alert alert-success">
    <h4>操作提示：</h4>
    <ul>
      <li>1. 分销员必须绑定手机号和邮箱。</li>
      <li>2. 设置提现收款账号时请请确保所设置账号在正常使用中，若因收款账号问题造成的任何损失平台概不负责。</li>
    </ul>
  </div>
  <div class="ncm-default-form">
    <form id="form_company_info" action="index.php?con=access_infomation&fun=save_member" method="post">
      <input type="hidden" name="form_submit" value="ok" />
      <dl>
        <dt><i class="required"></i>用户名：</dt>
        <dd>
          <?php echo $output['member_info']['member_name']?>
        </dd>
      </dl>
      <dl>
        <dt><i class="required"></i>邮箱：</dt>
        <dd>
          <?php echo $output['member_info']['member_email']?>
            <?php if($output['member_info']['member_email_bind'] == 0){?>
              <a href="<?php echo urlMember('member_security','auth',array('type'=>'modify_email'));?>" target="_blank">去验证</a>
            <?php }?>  
            <input type="hidden" id="check_mail" value="<?php echo $output['member_info']['member_email_bind'];?>">
        </dd>
      </dl>
      <dl>
        <dt><i class="required"></i>手机：</dt>
        <dd>
          <?php echo $output['member_info']['member_mobile']?>
            <?php if($output['member_info']['member_mobile_bind'] == 0){?>
              <a href="<?php echo urlMember('member_security','auth',array('type'=>'modify_mobile'));?>" target="_blank">去验证</a>
            <?php }?>  
            <input type="hidden" id="check_mobile" value="<?php echo $output['member_info']['member_mobile_bind']?>">
        </dd>
      </dl>

      <?php if($_GET['type'] == 'edit'){?>
      <dl>
        <dt><i class="required">*</i>结算账户类型：</dt>
        <dd>
          <input type="radio" name="bill_type_code" value="bank" <?php if($output['member_info']['bill_type_code'] == 'bank'){?> checked="checked"<?php }?>><img src="<?php echo DISTRIBUTE_TEMPLATES_URL;?>/images/d_yinlian.jpg" width="auto" height="24" alt=""/>&nbsp;银行账号
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="bill_type_code" value="alipay" <?php if($output['member_info']['bill_type_code'] == 'alipay'){?> checked="checked"<?php }?>><img src="<?php echo DISTRIBUTE_TEMPLATES_URL;?>/images/d_zhifubao.jpg" width="auto" height="24" alt=""/>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>收款人：</dt>
        <dd>
          <input name="bill_user_name" type="text" class="w200" value="" />
          <label for="bill_user_name" generated="true" class="error"></label>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>收款账号：</dt>
        <dd>
          <input name="bill_type_number" type="text" class="w200" value="" />
          <label for="bill_type_number" generated="true" class="error"></label>
        </dd>
      </dl>
      
      <dl id="bank_name" <?php if($output['member_info']['bill_type_code'] != 'bank'){?> style="display:none" <?php }?>>
        <dt><i class="required">*</i>开户行：</dt>
        <dd>
          <input name="bill_bank_name" type="text" class="w300" value="" />
          <label for="bill_bank_name" generated="true" class="error"></label>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>验证码：</dt>
        <dd>
          <input  type="text" id="captcha" name="captcha" class="text w80" size="10"  />
          <span onclick="javascript:document.getElementById('codeimage').src='index.php?con=seccode&fun=makecode&type=50,120&nchash=<?php echo getNchash();?>&t=' + Math.random();">
            <img src="index.php?con=seccode&fun=makecode&type=50,120&nchash=<?php echo getNchash();?>" name="codeimage" id="codeimage"/> <a class="makecode" href="javascript:void(0)">更换验证码</a>
          </span>
        </dd>
      </dl>
      <dl class="bottom">
        <dt>&nbsp;</dt>
        <dd><label class="submit-border">
          <a id="btn_apply_company_next" href="javascript:;" class="submit">提交</a></label>
        </dd>
      </dl>
      <?php }else{?>
        <dl>
        <dt>结算账户类型：</dt>
        <dd>
          <?php if($output['member_info']['bill_type_code'] == 'bank'){?>
            <img src="<?php echo DISTRIBUTE_TEMPLATES_URL;?>/images/d_yinlian.jpg" width="auto" height="24" alt="银行账号"/>&nbsp;银行账号
          <?php }else{?>
            <img src="<?php echo DISTRIBUTE_TEMPLATES_URL;?>/images/d_zhifubao.jpg" width="auto" height="24" alt="支付宝"/>支付宝
          <?php }?>  
        </dd>
      </dl>
      <dl>
        <dt>收款人：</dt>
        <dd>
          <?php echo $output['member_info']['bill_user_name']?>
        </dd>
      </dl>
      <dl>
        <dt>收款账号：</dt>
        <dd>
          <?php echo $output['member_info']['bill_type_number']?>
        </dd>
      </dl>
      <?php if($output['member_info']['bill_type_code'] == 'bank'){?>
      <dl id="bank_name">
        <dt>开户行：</dt>
        <dd>
          <?php echo $output['member_info']['bill_bank_name']?>          
        </dd>
      </dl>
      <?php }?>
      <dl class="bottom">
        <dt>&nbsp;</dt>
        <dd><label class="submit-border">
          <a id="btn_apply_company_edit" href="index.php?con=access_infomation&fun=member&type=edit" class="submit">编辑</a></label>
        </dd>
      </dl>
      <?php }?>
  </form>
  </div>
</div>
<script>
  $(function(){
    var b_type = '<?php echo $output['member_info']['bill_type_code'];?>';
    $('input[type="radio"]').on('change',function(){
      b_type = $(this).val();
      if(b_type == 'bank'){
        $('#bank_name').show();
      }else{
        $('#bank_name').hide();
      }
    });

    $('#form_company_info').validate({
        errorPlacement: function(error, element){
            element.nextAll('span').first().after(error);
        },
        onkeyup: false,
        rules : {
            bill_user_name: {
                required: true,
                maxlength: 50 
            },
            bill_type_number: {
                required: true,
                maxlength: 20 
            },
            bill_bank_name: {
                required: function(){ return b_type == 'bank'},
                maxlength: 50
            },
            captcha : {
                required : true,
                remote   : {
                    url : 'index.php?con=seccode&fun=check&nchash=<?php echo getNchash();?>',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha').val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                          document.getElementById('codeimage').src='index.php?con=seccode&fun=makecode&type=50,120&nchash=<?php echo getNchash();?>&t=' + Math.random();
                        }
                    }
                }
            }
        },
        messages : {
            bill_user_name: {
                required: '请输入收款人姓名',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            bill_type_number: {
                required: '请输入收款账号',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            bill_bank_name: {
                required: '请输入开户行名称',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            captcha: {
                required: '请输入验证码',
                remote: '请输入正确的验证码'
            }
        }
    });
    $('#btn_apply_company_next').on('click', function() {
      if($('#check_mail').val() == 0 || $('#check_mobile').val() == 0) {
        alert('请先去会员中心绑定安全手机和安全邮箱');
      }else{
        if($('#form_company_info').valid()) {
          $('#form_company_info').submit();
        }
      }        
    });
  });
</script>  