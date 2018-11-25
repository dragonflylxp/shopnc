<?php defined('Inshopec') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<!-- 公司信息 -->
<style>
.go-to{display: block;padding: 2px 6px; margin-left:20px;background:#27A9E3;width: 64px;color: #fff;border-radius: 4px;text-align: center;}
.go-to:hover{ color:#fff}
.count-name{ width:204px; margin-top:0 !important;background-color: #FFF;vertical-align: top;display: inline-block;height: 18px;padding: 3px 1px;border: solid 1px #CCC;outline: 0 none;cursor: not-allowed; line-height:18px; text-indent:4px}
</style>
<div id="apply_company_info" class="apply-company-info">
 
  <form id="form_company_info" action="index.php?con=distri_join&fun=step2" method="post">
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <tbody>
        <tr>
          <th>用户名：</th>
          <td>
            <span class="count-name"><?php echo $output['member_info']['member_name']?></span><span></span>
          </td>
        </tr>
        <tr>
          <th style="line-height:28px">邮箱：</th>
          <td>
            <span class="count-name fl"><?php echo $output['member_info']['member_email']?></span>
            <?php if($output['member_info']['member_email_bind'] == 0){?>
              <a href="<?php echo urlMember('member_security','auth',array('type'=>'modify_email'));?>" target="_blank" class="go-to fl" title="邮箱验证"><i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;去验证</a>
            <?php }?>  
            <input type="hidden" id="check_mail" value="<?php echo $output['member_info']['member_email_bind'];?>">
            <span></span></td>
        </tr>
        <tr>
          <th style="line-height:28px">手机：</th>
          <td>
            <span class="count-name fl"><?php echo $output['member_info']['member_mobile']?></span>
            <?php if($output['member_info']['member_mobile_bind'] == 0){?>
              <a href="<?php echo urlMember('member_security','auth',array('type'=>'modify_mobile'));?>" target="_blank" class="go-to fl" title="手机验证"><i class="fa fa-tablet" aria-hidden="true"></i>&nbsp;去验证</a>
            <?php }?>  
            <input type="hidden" id="check_mobile" value="<?php echo $output['member_info']['member_mobile_bind']?>">
            <span></span></td>
        </tr>
        <tr>
          <th>结算账户类型：</th>
          <td>
            <input type="radio" name="bill_type_code" value="bank" checked="checked"><img src="<?php echo DISTRIBUTE_TEMPLATES_URL;?>/images/d_yinlian.jpg" width="auto" height="24" alt=""/>&nbsp;银行账号
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="bill_type_code" value="alipay"><img src="<?php echo DISTRIBUTE_TEMPLATES_URL;?>/images/d_zhifubao.jpg" width="auto" height="24" alt=""/>
          </td>
        </tr>
        <tr>
          <th><i>*</i>收款人：</th>
          <td><input name="bill_user_name" type="text" class="w200"/>
             <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>收款账号：</th>
          <td><input name="bill_type_number" type="text" class="w200">
            <span></span></td>
        </tr>
        <tr id="bank_name">
          <th><i>*</i>开户行：</th>
          <td><input name="bill_bank_name" type="text" class="w300" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>验证码：</th>
          <td><input  type="text" id="captcha" name="captcha" class="text w80" size="10"  />
            <span>
              <img src="index.php?con=seccode&fun=makecode&type=50,120&nchash=<?php echo getNchash();?>" name="codeimage" id="codeimage"/> <a class="makecode" href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage').src='index.php?con=seccode&fun=makecode&type=50,120&nchash=<?php echo getNchash();?>&t=' + Math.random();">更换验证码</a>
            </span></td>
        </tr>
      </tbody>
    </table>
  </form>
  <div class="bottom"><a id="btn_apply_company_next" href="javascript:;" class="btn">提交申请</a></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  var b_type = 'bank';
  $('input[type="radio"]').on('change',function(){
    b_type = $(this).val();
    if(b_type == 'bank'){
      $('#bank_name').show();
    }else{
      $('#bank_name').hide();
    }
  });

    $('#btn_apply_agreement_next').on('click', function() {
        if($('#check_mail').val() == 0 || $('#check_mobile').val() == 0) {
            alert('请阅读并同意协议');
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
