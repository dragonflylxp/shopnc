<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?con=manager&fun=store_joinin" title="返回地区管理人列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>查看地区管理人注册信息</h3>
        <h5> 地区管理人查看及审核</h5>
      </div>
    </div>
  </div>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">公司及联系人信息</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">公司名称：</th>
        <td colspan="20"><?php echo $output['manager_detail']['complete_company_name'];?></td>
      </tr>
      <tr>
        <th>公司所在地：</th>
        <td><?php echo $output['manager_detail']['company_address'];?></td>
        <th>公司详细地址：</th>
        <td colspan="20"><?php echo $output['manager_detail']['company_address_detail'];?></td>
      </tr>
      <tr>
        <th>公司电话：</th>
        <td><?php echo $output['manager_detail']['company_phone'];?></td>
        <th>员工总数：</th>
        <td><?php echo $output['manager_detail']['company_employee_count'];?>&nbsp;人</td>
        <th>注册资金：</th>
        <td><?php echo $output['manager_detail']['company_registered_capital'];?>&nbsp;万元 </td>
      </tr>
      <tr>
        <th>联系人姓名：</th>
        <td><?php echo $output['manager_detail']['contacts_name'];?></td>
        <th>联系人电话：</th>
        <td><?php echo $output['manager_detail']['contacts_phone'];?></td>
        <th>电子邮箱：</th>
        <td><?php echo $output['manager_detail']['contacts_email'];?></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
    <tr>
      <th colspan="20">公司法人信息</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <th class="w150">公司法人姓名：</th>
      <td colspan="20"><?php echo $output['manager_detail']['legal_person_name'];?></td>
    </tr>
    <tr>
      <th>公司法人身份证号：</th>
      <td><?php echo _decrypt($output['manager_detail']['id_number']);?></td>
    </tr>
    <tr>
      <th>公司法人身份证电子版：</th>
      <td>
        <?php foreach ($output['manager_detail']['identity_card_electronic'] as $pic_url){ ?>
        <a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($pic_url);?>"> <?php if(!empty($pic_url)){ ?><img src="<?php echo getStoreJoininImageUrl($pic_url);?>" alt="" /> </a>
          <?php } ?><?php } ?>
      </td>
    </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">营业执照信息（副本）</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">营业执照号：</th>
        <td><?php echo _decrypt($output['manager_detail']['business_licence_number']);?></td>
      </tr>
      <tr>
        <th>营业执照所在地：</th>
        <td><?php echo $output['manager_detail']['business_licence_address'];?></td>
      </tr>
      <tr>
        <th>营业执照有效期：</th>
        <td><?php echo $output['manager_detail']['business_licence_start'];?> - <?php echo $output[' manager_detail']['business_licence_end'];?></td>
      </tr>
      <tr>
        <th>法定经营范围：</th>
        <td colspan="20"><?php echo $output['manager_detail']['business_sphere'];?></td>
      </tr>
      <tr>
        <th>营业执照<br />
          电子版：</th>
        <td colspan="20"><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['manager_detail']['organization_code_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['manager_detail']['business_licence_number_elc']);?>" alt="" /> </a></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">组织机构代码证</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>组织机构代码：</th>
        <td colspan="20"><?php echo _decrypt($output['manager_detail']['organization_code']);?></td>
      </tr>
      <tr>
        <th>组织机构代码证<br/>
          电子版：</th>
        <td colspan="20"><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['manager_detail']['organization_code_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['manager_detail']['organization_code_electronic']);?>" alt="" /> </a></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">一般纳税人证明：</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>一般纳税人证明：</th>
        <td colspan="20"><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['manager_detail']['general_taxpayer']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['manager_detail']['general_taxpayer']);?>" alt="" /> </a></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">开户银行信息：</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">银行开户名：</th>
        <td><?php echo $output['manager_detail']['bank_account_name'];?></td>
      </tr>
      <tr>
        <th>公司银行账号：</th>
        <td><?php echo _decrypt($output['manager_detail']['bank_account_number']);?></td>
      </tr>
      <tr>
        <th>开户银行支行名称：</th>
        <td><?php echo $output['manager_detail']['bank_name'];?></td>
      </tr>
      <tr>
        <th>支行联行号：</th>
        <td><?php echo _decrypt($output['manager_detail']['bank_code']);?></td>
      </tr>
      <tr>
        <th>开户银行所在地：</th>
        <td colspan="20"><?php echo $output['manager_detail']['bank_address'];?></td>
      </tr>
      <tr>
        <th>开户银行许可证<br/>
          电子版：</th>
        <td colspan="20"><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['manager_detail']['bank_licence_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['manager_detail']['bank_licence_electronic']);?>" alt="" /> </a></td>
      </tr>
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
        <th class="w150">银行开户名：</th>
        <td><?php echo $output['manager_detail']['settlement_bank_account_name'];?></td>
      </tr>
      <tr>
        <th>公司银行账号：</th>
        <td><?php echo _decrypt($output['manager_detail']['settlement_bank_account_number']);?></td>
      </tr>
      <tr>
        <th>开户银行支行名称：</th>
        <td><?php echo $output['manager_detail']['settlement_bank_name'];?></td>
      </tr>
      <tr>
        <th>支行联行号：</th>
        <td><?php echo _decrypt($output['manager_detail']['settlement_bank_code']);?></td>
      </tr>
      <tr>
        <th>开户银行所在地：</th>
        <td><?php echo $output['manager_detail']['settlement_bank_address'];?></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">税务登记证</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">税务登记证号：</th>
        <td><?php echo _decrypt($output['manager_detail']['tax_registration_certificate']);?></td>
      </tr>
      <tr>
        <th>纳税人识别号：</th>
        <td><?php echo _decrypt($output['manager_detail']['taxpayer_id']);?></td>
      </tr>
      <tr>
        <th>税务登记证号<br />
          电子版：</th>
        <td><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['manager_detail']['tax_registration_certif_elc']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['manager_detail']['tax_registration_certif_elc']);?>" alt="" /> </a></td>
      </tr>

    </tbody>
  </table>
  <form id="form_store_verify" action="index.php?con=manager&fun=store_joinin_verify" method="post">
    <?php if(in_array(intval($output['manager_detail']['apply_state']), array(20))) { ?>
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <tr>
        <th>审核意见：</th>
        <td colspan="2"><textarea id="apply_message" name="apply_message"></textarea></td>
      </tr>
      </table>
    <?php } ?>
    <input id="verify_type" name="verify_type" type="hidden" />
    <input name="manager_id" type="hidden" value="<?php echo $output['manager_detail']['manager_id'];?>" />
  <?php if(in_array(intval($output['manager_detail']['apply_state']), array(20))) { ?>
    <div id="validation_message" style="color:red;display:none;"></div>
    <div class="bottom"><a id="btn_pass" class="ncap-btn-big ncap-btn-green mr10" href="JavaScript:void(0);">通过</a><a id="btn_fail" class="ncap-btn-big ncap-btn-red" href="JavaScript:void(0);">拒绝</a> </div>
  <?php } ?>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('a[nctype="nyroModal"]').nyroModal();
        //审核失败
        $('#btn_fail').on('click', function() {
          if($('#apply_message').val() == '') {
            $('#validation_message').text('请输入审核意见');
            $('#validation_message').show();
            return false;
          } else {
              $('#validation_message').hide();
          }
            if(confirm('确认拒绝申请？')) {
                $('#verify_type').val(40);
                $('#form_store_verify').submit();
            }
        });
      //审核通过
        $('#btn_pass').on('click', function() {
            var valid = true;
            $('[nctype="commis_rate"]').each(function(commis_rate) {
                rate = $(this).val();
                if(rate == '') {
                    valid = false;
                    return false;
                }

                var rate = Number($(this).val());
                if(isNaN(rate) || rate < 0 || rate >= 100) {
                    valid = false;
                    return false;
                }
            });
            if(valid) {
                $('#validation_message').hide();
                if(confirm('确认通过申请？')) {
                    $('#verify_type').val(30);
                    $('#form_store_verify').submit();
                }
            } else {
                $('#validation_message').show();
            }
        });
    });
</script>