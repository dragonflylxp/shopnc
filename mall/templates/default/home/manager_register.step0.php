<?php defined('Inshopec') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<!-- 公司信息 -->
<!--多文件上传div样式 控制-->
<style>
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
<div id="apply_company_info" class="apply-company-info">
  <div class="alert">
    <h4>注意事项：</h4>
    以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1M之内。</div>
    <?php
        if(!empty($output['store_info']['apply_message'])){
            $htm ='<div class="alert alert-red">';
            $htm .='<h4>未通过原因：</h4>';
            $htm .=$output['store_info']['apply_message'];
            $htm .='</div>';
            echo $htm;
        }
    ?>

  <form id="form_company_info" action="index.php?con=manager_register&fun=step1" method="post">
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="2">公司及联系人信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i>公司名称：</th>
          <td><input name="company_name" type="text" class="w200" value="<?php echo $output['store_info']['complete_company_name']; ?>"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>公司所在地：</th>
          <td><input id="company_address" name="company_address" type="hidden" value="<?php echo $output['store_info']['company_address']; ?>"/>
          <input type="hidden" value="<?php echo $output['store_info']['company_province_id']; ?>" name="province_id" id="province_id">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>公司详细地址：</th>
          <td><input name="company_address_detail" value="<?php echo $output['store_info']['company_address_detail']; ?>" type="text" class="w200">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>公司电话：</th>
          <td><input name="company_phone" type="text" value="<?php echo $output['store_info']['company_phone']; ?>" class="w100">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>员工总数：</th>
          <td><input name="company_employee_count" type="text" value="<?php echo $output['store_info']['company_employee_count']; ?>" class="w50"/>
            &nbsp;人 <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>注册资金：</th>
          <td><input name="company_registered_capital" value="<?php echo $output['store_info']['company_registered_capital']; ?>" type="text" class="w50">
            &nbsp;万元<span></span></td>
        </tr>
        <tr>
          <th><i>*</i>联系人姓名：</th>
          <td><input name="contacts_name" type="text" value="<?php echo $output['store_info']['contacts_name']; ?>" class="w100" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>联系人电话：</th>
          <td><input name="contacts_phone" type="text" value="<?php echo $output['store_info']['contacts_phone']; ?>" class="w100" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>电子邮箱：</th>
          <td><input name="contacts_email" type="text" value="<?php echo $output['store_info']['contacts_email']; ?>" class="w200" />
            <span></span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
      <table border="0" cellpadding="0" cellspacing="0" class="all">
          <thead>
          <tr>
              <th colspan="20">公司法人信息</th>
          </tr>
          </thead>
          <tbody>
          <tr>
              <th><i>*</i>公司法人姓名：</th>
              <td><input name="legal_person_name" type="text" class="w200" value="<?php echo $output['store_info']['legal_person_name']; ?>"/>
                  <span></span></td>
          </tr>
          <tr>
              <th><i>*</i>公司法人身份证号：</th>
              <td><input name="id_number" type="text" class="w200" value="<?php echo _decrypt($output['store_info']['id_number']); ?>"/>
                  <span></span></td>
          </tr>
          <tr>
              <th><i>*</i>公司法人身份证电子版：</th>
              <td><input name="identity_card_electronic" type="file" class="w60" multiple/>
                  <div class="img_box">
                      <?php
                      $img_str = $output['store_info']['identity_card_electronic'];
                      if(!empty($img_str)){
                          $htm = '';
                          $img_arr = explode("|",$img_str);
                          foreach($img_arr as $val){
                              if(!empty($val)){
                                  $htm .= '<span class="img_span">';
                                  $htm .='<img height="60" class="per_img" src="'.$output['pic_url'].$val.'" />';
                                  $htm .='<img data-field="identity_card_electronic" class="delate_small" src="'.SHOP_TEMPLATES_URL.'/images/shop/delate_small.jpg" alt=""/>';
                                  $htm .='</span>';
                              }
                          }
                          echo $htm;
                      }
                      ?>
                  </div>
                  <span class="block">请上传身份证正面和反面的电子版。</span>
                  <span class="block">请确保图片清晰，文字可辨。</span>
                  <input name="identity_card_electronic1" type="hidden" value="<?php echo $output['store_info']['identity_card_electronic']; ?>"/><span></span>
              </td>
          </tr>
          </tbody>
          <tfoot>
          <tr>
              <td colspan="20">&nbsp;</td>
          </tr>
          </tfoot>
      </table>
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="20">营业执照信息（副本）</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i>营业执照号：</th>
          <td><input name="business_licence_number" type="text" value="<?php echo _decrypt($output['store_info']['business_licence_number']); ?>" class="w200" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>营业执照所在地：</th>
          <td><input id="business_licence_address" name="business_licence_address" type="hidden" value="<?php echo $output['store_info']['business_licence_address']; ?>" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>营业执照有效期：</th>
          <td><input id="business_licence_start" name="business_licence_start" type="text" class="w90"  value="<?php echo $output['store_info']['business_licence_start']; ?>" />
            <span></span>-
            <input id="business_licence_end" name="business_licence_end" type="text" value="<?php echo $output['store_info']['business_licence_end']; ?>" class="w90" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>法定经营范围：</th>
          <td><textarea name="business_sphere" rows="3"   class="w200"><?php echo $output['store_info']['business_sphere']; ?></textarea>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>营业执照电子版：</th>
          <td><input name="business_licence_number_elc" type="file" class="w60" />
              <?php
              if(!empty($output['store_info']['business_licence_number_elc'])){
                  $htm = '<img height="60" src="';
                  $htm .= $output['pic_url'].$output['store_info']['business_licence_number_elc'];
                  $htm .='"/>';
                  echo $htm;
              }
              ?>
            <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span>
            <input name="business_licence_number_elc1" value="<?php echo $output['store_info']['business_licence_number_elc']; ?>" type="hidden"/><span></span>
            </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="20">组织机构代码证</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i>组织机构代码：</th>
          <td><input name="organization_code" type="text" class="w200" value="<?php echo _decrypt($output['store_info']['organization_code']); ?>" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>组织机构代码证电子版：</th>
          <td><input name="organization_code_electronic" type="file" class="w60"/>
              <?php
              if(!empty($output['store_info']['organization_code_electronic'])){
                  $htm = '<img height="60" src="';
                  $htm .= $output['pic_url'].$output['store_info']['organization_code_electronic'];
                  $htm .='"/>';
                  echo $htm;
              }
              ?>
            <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span>
            <input name="organization_code_electronic1" type="hidden" value="<?php echo $output['store_info']['organization_code_electronic']; ?>" /><span></span>
            </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="20">一般纳税人证明<em>注：所属企业具有一般纳税人证明时，此项为必填。</em></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">一般纳税人证明：</th>
          <td><input name="general_taxpayer" type="file" class="w60" />
              <?php
              if(!empty($output['store_info']['general_taxpayer'])){
                  $htm = '<img height="60" src="';
                  $htm .= $output['pic_url'].$output['store_info']['general_taxpayer'];
                  $htm .='"/>';
                  echo $htm;
              }
              ?>
            <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span>
            <input name="general_taxpayer1" type="hidden" value="<?php echo $output['store_info']['general_taxpayer']; ?>"/><span></span>
            </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
  </form>
  <div class="bottom"><a id="btn_apply_company_next" href="javascript:;" class="btn">下一步，提交财务资质信息</a></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	<?php foreach (array('business_licence_number_elc','organization_code_electronic','general_taxpayer') as $input_id) { ?>
    $('input[name="<?php echo $input_id;?>"]').fileupload({
        dataType: 'json',
        url: '<?php echo urlShop('manager_register', 'ajax_upload_image');?>',
        formData: '',
        add: function (e,data) {
            data.submit();
        },
        done: function (e,data) {
            if (!data.result){
            	alert('上传失败，请尝试上传小图或更换图片格式');return;
            }
            if(data.result.state) {
            	$('input[name="<?php echo $input_id;?>"]').nextAll().remove('img');
            	$('input[name="<?php echo $input_id;?>"]').after('<img height="60" src="'+data.result.pic_url+'">');
            	$('input[name="<?php echo $input_id;?>1"]').val(data.result.pic_name);
            } else {
            	alert(data.result.message);
            }
        },
        fail: function(){
        	alert('上传失败，请尝试上传小图或更换图片格式');
        }
    });
    <?php } ?>
    //公司法人身份证上传


    <?php foreach (array('identity_card_electronic') as $input_id) { ?>
    $('input[name="<?php echo $input_id;?>"]').fileupload({
        dataType: 'json',
        url: "<?php echo urlShop('manager_register', 'ajax_upload_image');?>",
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
            alert('上传失败，请尝试上传小图或更换图片格式2222');
        }
    });
    <?php } ?>
    $('#company_address').nc_region();
    $('#business_licence_address').nc_region();
    
    $('#business_licence_start').datepicker();
    $('#business_licence_end').datepicker();

    $('#btn_apply_agreement_next').on('click', function() {
        if($('#input_apply_agreement').prop('checked')) {
            $('#apply_agreement').hide();
            $('#apply_company_info').show();
        } else {
            alert('请阅读并同意协议');
        }
    });
    //多文件上传删除图片功能js

    $('.delate_small').live('click',function(){
        var obj=$(this);
        var img_field = $(this).attr('data-field');
        var img_url=$($(obj.closest('span').find('img'))[0]).prop('src') ;
        var inputHidden = $(obj.closest('td')).find("input[type='hidden']");
        var str = inputHidden.val();
        $.ajax({
            type:"GET",
            url:'<?php echo urlShop('manager_register', 'delateimg');?>',
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


    $('#form_company_info').validate({
        errorPlacement: function(error, element){
            element.nextAll('span').first().after(error);
        },
        rules : {
            company_name: {
                required: true,
                maxlength: 50 
            },
            company_address: {
                required: true,
                maxlength: 50 
            },
            company_address_detail: {
                required: true,
                maxlength: 50 
            },
            company_phone: {
                required: true,
                maxlength: 20 
            },
            /*company_employee_count: {
                required: true,
                digits: true 
            },
            company_registered_capital: {
                required: true,
                digits: true 
            },*/
            contacts_name: {
                required: true,
                maxlength: 20 
            },
            contacts_phone: {
                required: true,
                maxlength: 20 
            },
            contacts_email: {
                required: true,
                email: true 
            },
            legal_person_name: {
                required: true
            },
            id_number: {
                required: true,
                maxlength: 18
            },
            identity_card_electronic1: {
                required: true
            },
            business_licence_number: {
                required: true,
                maxlength: 20
            },
            business_licence_address: {
                required: true,
                maxlength: 50
            },
            business_licence_start: {
                required: true
            },
            business_licence_end: {
                required: true
            },
            business_sphere: {
                required: true,
                maxlength: 500
            },
            business_licence_number_elc1: {
                required: true
            },
            organization_code: {
                required: true,
                maxlength: 20
            },
            organization_code_electronic1: {
                required: true
            }
        },
        messages : {
            company_name: {
                required: '请输入公司名称',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            company_address: {
                required: '请选择区域地址',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            company_address_detail: {
                required: '请输入公司详细地址',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            company_phone: {
                required: '请输入公司电话',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            /*company_employee_count: {
                required: '请输入员工总数',
                digits: '必须为数字'
            },
            company_registered_capital: {
                required: '请输入注册资金',
                digits: '必须为数字'
            },*/
            contacts_name: {
                required: '请输入联系人姓名',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            contacts_phone: {
                required: '请输入联系人电话',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            contacts_email: {
                required: '请输入常用邮箱地址',
                email: '请填写正确的邮箱地址'
            },
            legal_person_name: {
                required: '请输入公司法人的姓名'

            },
            id_number: {
                required: '请输入公司法人的身份证号',
                maxlength: jQuery.validator.format("最多{0}个字")

            },
            identity_card_electronic1: {
                required: '请上传公司法人身份证正面和反面的电子版'

            },
            business_licence_number: {
                required: '请输入营业执照号',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            business_licence_address: {
                required: '请选择营业执照所在地',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            business_licence_start: {
                required: '请选择生效日期'
            },
            business_licence_end: {
                required: '请选择结束日期'
            },
            business_sphere: {
                required: '请填写营业执照法定经营范围',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            business_licence_number_elc1: {
                required: '请选择上传营业执照电子版文件'
            },
            organization_code: {
                required: '请填写组织机构代码',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            organization_code_electronic1: {
                required: '请选择上传组织机构代码证电子版文件'
            }
        }
    });

    $('#btn_apply_company_next').on('click', function() {
        if($('#form_company_info').valid()) {
        	$('#province_id').val($("#company_address").fetch('area_id_1'));
            $('#form_company_info').submit();
        }
    });
});
</script>
