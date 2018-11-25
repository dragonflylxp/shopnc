<?php defined('Inshopec') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<style type="text/css">
.d_inline {
	display: inline;
}
.img{
  padding: 0;
  margin: 0;
}
.img_span{
  position: relative;
}
.per_img{
  margin: 4px 2px;
}
.delate_small{
  position: absolute;
  top: -20px;
  right: 2px;
  border-radius: 8px;
}
.alert-red{
  background:#cceeff;
  border:#dd9999;
  color:#528CC6;
}
</style>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?con=manager&fun=manager" title="返回<?php echo $lang['manage'];?>列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>编辑地区管理人信息</h3>
        <h5>地区管理人账户信息及注册信息编辑</h5>
      </div>
    </div>
  </div>
  <div class="homepage-focus" nctype="editStoreContent">
  <div class="title">
  <h3>编辑管理人信息</h3>
    <ul class="tab-base nc-row">
      <li><a class="current" href="javascript:void(0);">账户信息</a></li>
      <li><a href="javascript:void(0);">注册信息</a></li>
    </ul>
    </div>
    <form id="store_form" method="post" action="index.php?con=manager&fun=edit_save_manager">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="form_mark" value="1" />
      <input type="hidden" name="member_id" value="<?php echo $output['manager_array']['member_id'];?>" />
      <div class="ncap-form-default">

        <dl class="row">
          <dt class="tit">
            <label for="complete_company_name">公司名称</label>
          </dt>
          <dd class="opt">
            <h3><?php echo $output['manager_array']['complete_company_name'];?></h3>
            <span class="err"></span>
            <p class="notic"> </p>
          </dd>
        </dl>
      <dl class="row">
        <dt class="tit">
          <label for="store_company_name">管理人账号</label>
        </dt>
        <dd class="opt">
          <p><?php echo $output['manager_array']['member_name'];?></p>
          <input type="hidden" value="<?php echo $output['manager_array']['member_name'];?>" id="member_name" name="member_name" class="input-txt" />

          <span class="err"></span>
        </dd>
        <dl class="row">
          <dt class="tit">
            <label for="store_company_name">重置密码</label>
          </dt>
          <dd class="opt">
            <input type="text" value="" id="member_password" name="member_password" class="input-txt" />
            <span class="err"></span>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="store_company_name">确认密码</label>
          </dt>
          <dd class="opt">
            <input type="text" value="<?php echo $output['manager_array']['new_password'];?>" id="new_password" name="new_password" class="input-txt" />
            <span class="err"></span>
          </dd>
        </dl>
        <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
      </div>
    </form>
    <form id="joinin_form" enctype="multipart/form-data" method="post" action="index.php?con=manager&fun=edit_save_manager" style="display:none;">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="form_mark" value="2" />
      <input type="hidden" name="member_id" value="<?php echo $output['manager_array']['member_id'];?>" />
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">公司及联系人信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w150">公司名称：</th>
            <td colspan="20"><input type="text" class="input-txt" name="complete_company_name" value="<?php echo $output['manager_array']['complete_company_name'];?>"></td>
          </tr>
          <tr>
            <th>公司所在地：</th>
            <td colspan="20"><input type="hidden" name="company_address" id="company_address" value="<?php echo $output['manager_array']['company_address'];?>">
                <input type="hidden" value="" name="province_id" id="province_id">
                </td>
          </tr>
          <tr>
            <th>公司详细地址：</th>
            <td colspan="20"><input type="text" class="txt w300" name="company_address_detail" value="<?php echo $output['manager_array']['company_address_detail'];?>"></td>
          </tr>
          <tr>
            <th>公司电话：</th>
            <td><input type="text" class="input-txt" name="company_phone" value="<?php echo $output['manager_array']['company_phone'];?>"></td>
            <th>员工总数：</th>
            <td><input type="text" class="txt w70" name="company_employee_count" value="<?php echo $output['manager_array']['company_employee_count'];?>">
              &nbsp;人</td>
            <th>注册资金：</th>
            <td><input type="text" class="txt w70" name="company_registered_capital" value="<?php echo $output['manager_array']['company_registered_capital'];?>">
              &nbsp;万元 </td>
          </tr>
          <tr>
            <th>联系人姓名：</th>
            <td><input type="text" class="input-txt" name="contacts_name" value="<?php echo $output['manager_array']['contacts_name'];?>"></td>
            <th>联系人电话：</th>
            <td><input type="text" class="input-txt" name="contacts_phone" value="<?php echo $output['manager_array']['contacts_phone'];?>"></td>
            <th>电子邮箱：</th>
            <td><input type="text" class="input-txt" name="contacts_email" value="<?php echo $output['manager_array']['contacts_email'];?>"></td>
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
          <td><input type="text" class="txt w300" name="legal_person_name" value="<?php echo $output['manager_array']['legal_person_name'];?>"></td>
        </tr>
        <tr>
          <th>公司法人身份证号：</th>
          <td><input type="text" class="txt w300" name="id_number" value="<?php echo _decrypt($output['manager_array']['id_number']);?>"></td>
        </tr>

        <tr>
          <th>公司法人身份证<br />电子版：</th>
          <td id="card">
            <input name="identity_card_electronic1" type="hidden" data='input_hidden' value=""/><span></span>
            <?php
            $cardStr = implode('|',$output['manager_array']['identity_card_electronic'])
            ; ?>
            <input name="identity_card_electronic2" type="hidden"  value="<?php echo $cardStr; ?>"/>

            <input name="identity_card_electronic" type="file" class="w200" multiple/>
            <div class="img_box">
            </div>
            <?php foreach ($output['manager_array']['identity_card_electronic'] as $pic_url){ ;?>
            <a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($pic_url);?>"> <?php if(!empty($pic_url)){ ?><img src="<?php echo getStoreJoininImageUrl($pic_url);?>" alt="" /> </a>
              <?php } ?>
            <?php } ?>

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
            <td><input type="text" class="input-txt" name="business_licence_number" value="<?php echo _decrypt($output['manager_array']['business_licence_number']);?>"></td>
          </tr>
          <tr>
            <th>营业执照所在地：</th>
            <td><input type="hidden" name="business_licence_address" id="business_licence_address" value="<?php echo $output['manager_array']['business_licence_address'];?>"></td>
          </tr>
          <tr>
            <th>营业执照有效期：</th>
            <td><input type="text" class="input-txt" name="business_licence_start" id="business_licence_start" value="<?php echo $output['manager_array']['business_licence_start'];?>">
              -
              <input type="text" class="input-txt" name="business_licence_end" id="business_licence_end" value="<?php echo $output['manager_array']['business_licence_end'];?>"></td>
          </tr>
          <tr>
            <th>法定经营范围：</th>
            <td colspan="20"><input type="text" class="txt w300" name="business_sphere" value="<?php echo $output['manager_array']['business_sphere'];?>"></td>
          </tr>
          <tr>
            <th>营业执照<br />
              电子版：</th>
            <td colspan="20"><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['manager_array']['business_licence_number_elc']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['manager_array']['business_licence_number_elc']);?>" alt="" /> </a>
              <input class="w200" type="file" name="business_licence_number_elc"></td>
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
            <td colspan="20"><input type="text" class="txt w300" name="organization_code" value="<?php echo _decrypt($output['manager_array']['organization_code']);?>"></td>
          </tr>
          <tr>
            <th>组织机构代码证<br/>
              电子版：</th>
            <td colspan="20"><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['manager_array']['organization_code_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['manager_array']['organization_code_electronic']);?>" alt="" /> </a>
              <input type="file" name="organization_code_electronic"></td>
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
            <td colspan="20"><a nctype="nyroModal" href="<?php echo getStoreJoininImageUrl($output['manager_array']['general_taxpayer']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['manager_array']['general_taxpayer']);?>" alt="" /> </a>
              <input type="file" name="general_taxpayer"></td>
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
            <td><input type="text" class="txt w300" name="bank_account_name" value="<?php echo $output['manager_array']['bank_account_name'];?>"></td>
          </tr>
          <tr>
            <th>公司银行账号：</th>
            <td><input type="text" class="txt w300" name="bank_account_number" value="<?php echo _decrypt($output['manager_array']['bank_account_number']);?>"></td>
          </tr>
          <tr>
            <th>开户银行支行名称：</th>
            <td><input type="text" class="txt w300" name="bank_name" value="<?php echo $output['manager_array']['bank_name'];?>"></td>
          </tr>
          <tr>
            <th>支行联行号：</th>
            <td><input type="text" class="txt w300" name="bank_code" value="<?php echo _decrypt($output['manager_array']['bank_code']);?>"></td>
          </tr>
          <tr>
            <th>开户银行所在地：</th>
            <td colspan="20"><input type="hidden" name="bank_address" id="bank_address" value="<?php echo $output['manager_array']['bank_address'];?>"></td>
          </tr>
          <tr>
            <th>开户银行许可证<br/>
              电子版：</th>
            <td colspan="20"><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['manager_array']['bank_licence_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['manager_array']['bank_licence_electronic']);?>" alt="" /> </a>
              <input type="file" name="bank_licence_electronic"></td>
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
            <td><input type="text" class="txt w300" name="settlement_bank_account_name" value="<?php echo $output['manager_array']['settlement_bank_account_name'];?>"></td>
          </tr>
          <tr>
            <th>公司银行账号：</th>
            <td><input type="text" class="txt w300" name="settlement_bank_account_number" value="<?php echo _decrypt($output['manager_array']['settlement_bank_account_number']);?>"></td>
          </tr>
          <tr>
            <th>开户银行支行名称：</th>
            <td><input type="text" class="txt w300" name="settlement_bank_name" value="<?php echo $output['manager_array']['settlement_bank_name'];?>"></td>
          </tr>
          <tr>
            <th>支行联行号：</th>
            <td><input type="text" class="txt w300" name="settlement_bank_code" value="<?php echo _decrypt($output['manager_array']['settlement_bank_code']);?>"></td>
          </tr>
          <tr>
            <th>开户银行所在地：</th>
            <td><input type="hidden" name="settlement_bank_address" id="settlement_bank_address" value="<?php echo $output['manager_array']['settlement_bank_address'];?>"></td>
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
            <td><input type="text" class="txt w300" name="tax_registration_certificate" value="<?php echo _decrypt($output['manager_array']['tax_registration_certificate']);?>"></td>
          </tr>
          <tr>
            <th>纳税人识别号：</th>
            <td><input type="text" class="txt w300" name="taxpayer_id" value="<?php echo _decrypt($output['manager_array']['taxpayer_id']);?>"></td>
          </tr>
          <tr>
            <th>税务登记证号<br />
              电子版：</th>
            <td><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['manager_array']['tax_registration_certif_elc']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['manager_array']['tax_registration_certif_elc']);?>" alt="" /> </a>
              <input type="file" name="tax_registration_certif_elc"></td>
          </tr>
        </tbody>
      </table>
      <div><a id="btn_fail" class="ncap-btn-big ncap-btn-green" href="JavaScript:void(0);"><?php echo $lang['nc_submit'];?></a></div>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';
$(function(){
    $("#company_address").nc_region();
    $("#business_licence_address").nc_region();
    $("#bank_address").nc_region();
    $("#settlement_bank_address").nc_region();
    $('#end_time').datepicker();
    $('#business_licence_start').datepicker();
    $('#business_licence_end').datepicker();
    $('a[nctype="nyroModal"]').nyroModal();
    $('input[name=store_state][value=<?php echo $output['store_array']['store_state'];?>]').trigger('click');

    //按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        if($("#store_form").valid()){
            $("#store_form").submit();
        }
    });

    $("#btn_fail").click(function(){
        $('#province_id').val($("#company_address").fetch('area_id_1'));
        $("#joinin_form").submit();

      //删除文件夹图片开始
      var inputVal = $('input[name="identity_card_electronic1"]').val();
      $.ajax({
        cache: true,
        type: "POST",
        url:'<?php echo urlAdminShop('upload_pic', 'delete_file');?>',
        data:{img_name: '<?php echo implode('|',$output['manager_array']['identity_card_electronic']); ?>',inputVal:inputVal},
        async: false,
        error: function(request) {

        },
        success: function(data) {

        }
      });
      //删除文件夹图片结束
    });

    $('#store_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
          new_password: {
            equalTo : '#member_password'
          }
        },
        messages : {
          new_password: {
            equalTo : '两次密码输入不一致'

          }
        }
    });

    $('div[nctype="editStoreContent"] > .title').find('li').click(function(){
        $(this).children().addClass('current').end().siblings().children().removeClass('current');
        var _index = $(this).index();
        var _form = $('div[nctype="editStoreContent"]').find('form');
        _form.hide();
        _form.eq(_index).show();
    });


  //图片上传
  //公司法人身份证上传
  <?php foreach (array('identity_card_electronic') as $input_id) { ?>
  $('input[name="<?php echo $input_id;?>"]').fileupload({
    dataType: 'json',
    url: '<?php echo urlAdminShop('manager', 'ajax_upload_image');?>',
    formData: '',
    sequentialUploads: true,  // 连续上传配置
    add: function (e,data) {
      data.submit();
    },
    done: function (e,data) {
      if (!data.result){
        alert('上传失败，请尝试上传小图或更换图片格式');return;
      }
      if(data.result.state) {
//        console.log(data.result.pic_name);
//        console.log( $('input[name="<?php //echo $input_id;?>//"]'));
        //删除原来的图片
        $('#card a').remove();

        $(this).next('div').append('<span class="img_span">'+'<img height="60" class="per_img"src="'+data.result.pic_url+'"><img class="delate_small" src="<?php echo SHOP_TEMPLATES_URL;?>/images/shop/delate_small.jpg" alt=""/>'+'<span/>');
        var str = data.result.pic_name;
        var oldstr = $('input[name="<?php echo $input_id;?>1"]').val();
        if(oldstr != ""){
          str = oldstr+'|'+str;
        }
        $('input[name="<?php echo $input_id;?>1"]').val(str);
      } else {
        alert(data.result.message);
      }
    },
    fail: function(){
      alert('上传失败，请尝试上传小图或更换图片格式');
    }
  });
  <?php } ?>
  //多文件上传删除图片功能js

  $('.delate_small').live('click',function(){
    var obj=$(this);
    var img_field = $(this).attr('data-field');
    var img_url=$($(obj.closest('span').find('img'))[0]).prop('src') ;
    var inputHidden = $(obj.closest('td')).find("input[data='input_hidden']");
    var str = inputHidden.val();
    $.ajax({
      type:"GET",
      url:'<?php echo urlAdminShop('upload_pic', 'delateimg');?>',
      async:true,
      data:{img_url: img_url,img_field:img_field},
      dataType:"json",
      success: function(data){
        if(data.status){
          var img_name = data.img_name;
          //删除页面上的显示图片
          $(obj.closest('span')).remove();
          //删除隐藏域里面的图片名字
          //获取隐藏域的src
          //获取当前div里面的input[hidden]标签
          var arr = str.split('|');
          Array.prototype.indexOf = function(val) {
            for (var i = 0; i < this.length; i++) {
              if (this[i] == val) return i;
            }
            return -1;
          };
          Array.prototype.remove = function(val) {
            var index = this.indexOf(val);
            if (index > -1) {
              this.splice(index, 1);
            }
          };

          arr.remove(img_name);
          var new_str=arr.join('|');
          inputHidden.val(new_str);

        }
      }
    })
  });


});
</script>