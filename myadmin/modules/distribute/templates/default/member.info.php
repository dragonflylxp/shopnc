<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?con=distri_member" title="返回<?php echo $lang['pending'];?>列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo '分销认证';?> - 认证详情</h3>
        <h5>分销会员及认证管理</h5>
      </div>
    </div>
  </div>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">会员详情</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">会员名称：</th>
        <td colspan="20"><?php echo $output['member_info']['member_name'];?></td>
      </tr>
      <tr>
        <th>真实姓名：</th>
        <td><?php echo $output['member_info']['member_truename'];?></td>
        <th>性别：</th>
        <td><?php echo $output['sex_array'][$output['member_info']['member_sex']];?></td>
        <th>注册时间：</th>
        <td><?php echo date('Y-m-d H:i:s',$output['member_info']['member_time']);?></td>
      </tr>
      <tr>        
        <th>绑定手机：</th>
        <td><?php echo ($output['member_info']['member_mobile_bind'] == 1)?$output['member_info']['member_mobile']:'--';?></td>
        <th>绑定邮箱：</th>
        <td><?php echo ($output['member_info']['member_email_bind'] == 1)?$output['member_info']['member_email']:'--';?></td>
        <th>最后登录时间：</th>
        <td><?php echo date('Y-m-d H:i:s',$output['member_info']['member_login_time']);?></td>
      </tr>
      <?php if(intval($output['member_info']['distri_state']) == 2){?>
      <tr>
        <th>当前佣金：</th>
        <td><?php echo $output['member_info']['trad_amount'];?></td>
        <th>冻结金额：</th>
        <td><?php echo $output['member_info']['freeze_distri_trad'];?></td>
        <th>可提现金额：</th>
        <td><?php echo $output['member_info']['available_distri_trad'];?></td>
      </tr>
      <?php }?>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">结算账号信息：</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">账户类型：</th>
        <td><?php echo getDistriBillName($output['member_info']['bill_type_code']);?></td>
      </tr>
      <tr>
        <th>收款人姓名:</th>
        <td><?php echo $output['member_info']['bill_user_name'];?></td>
      </tr>
      <tr>
        <th>收款账号:</th>
        <td><?php echo $output['member_info']['bill_type_number'];?></td>
      </tr>
      <?php if($output['member_info']['bill_type_code'] == 'bank'){?>
      <tr>
        <th>开户银行名称：</th>
        <td><?php echo $output['member_info']['bill_bank_name'];?></td>
      </tr>
      <?php }?>
    </tbody>
  </table>
  
  <form id="form_store_verify" action="index.php?con=distri_member&fun=auth" method="post">
    <input id="verify_type" name="verify_type" type="hidden" />
    <input name="member_id" type="hidden" value="<?php echo $output['member_info']['member_id'];?>" />
    <?php if(in_array(intval($output['member_info']['distri_state']), array(1, 3))) { ?>
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <tbody>
        <tr>
          <th>审核意见：</th>
          <td colspan="2"><textarea id="joinin_message" name="joinin_message"></textarea></td>
        </tr>
      </tbody>
    </table>
    <div id="validation_message" style="color:red;display:none;"></div>
    <div class="bottom">
      <a id="btn_pass" class="ncap-btn-big ncap-btn-green mr10" href="JavaScript:void(0);">通过</a>
      <a id="btn_fail" class="ncap-btn-big ncap-btn-red" href="JavaScript:void(0);">拒绝</a> 
    </div>
    <?php } ?>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('a[nctype="nyroModal"]').nyroModal();

        $('#btn_fail').on('click', function() {
            if($('#joinin_message').val() == '') {
                $('#validation_message').text('请输入审核意见');
                $('#validation_message').show();
                return false;
            } else {
                $('#validation_message').hide();
            }
            if(confirm('确认拒绝申请？')) {
                $('#verify_type').val('fail');
                $('#form_store_verify').submit();
            }
        });
        $('#btn_pass').on('click', function() {
            $('#validation_message').hide();
            if(confirm('确认通过申请？')) {
              $('#verify_type').val('pass');
              $('#form_store_verify').submit();
            } 
        });
    });
</script>