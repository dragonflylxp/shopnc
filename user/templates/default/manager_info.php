<?php defined('Inshopec') or exit('Access Invalid!');?>
<style type="text/css">
    html body{
        overflow: auto;
    }
    .page{
        padding: 0px;
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
</style>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>

<div class="page">
    <form id="joinin_form" enctype="multipart/form-data" method="post" action="">
        <input type="hidden" name="form_submit" value="ok" />
       <!--公司及联系人信息-->
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">公司及联系人信息</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">公司名称:</th>
                <td colspan="20">
                    <input type="text" class="input-txt" name="complete_company_name" value="<?php echo $output['joinin_detail']['complete_company_name'];?>">
                </td>
            </tr>
            <tr>
                <th>公司所在地:</th>
                <td colspan="20">
                    <input type="hidden" name="company_address" id="company_address" value="<?php echo $output['joinin_detail']['company_address'];?>">
                    <input type="hidden" value="" name="province_id" id="province_id">
                </td>
            </tr>
            <tr>
                <th>公司详细地址:</th>
                <td colspan="20">
                    <input type="text" class="txt w300" name="company_address_detail" value="<?php echo $output['joinin_detail']['company_address_detail'];?>">
                </td>
            </tr>
            <tr>
                <th>公司电话:</th>
                <td>
                    <input type="text" class="input-txt" name="company_phone" value="<?php echo $output['joinin_detail']['company_phone'];?>">
                </td>
                <th>员工总数:</th>
                <td>
                    <input type="text" class="txt w70" name="company_employee_count" value="<?php echo $output['joinin_detail']['company_employee_count'];?>">&nbsp;人
                </td>
                <th>注册资金:</th>
                <td>
                    <input type="text" class="txt w70" name="company_registered_capital" value="<?php echo $output['joinin_detail']['company_registered_capital'];?>">&nbsp;万元
                </td>
            </tr>
            <tr>
                <th>联系人姓名:</th>
                <td>
                    <input type="text" class="input-txt" name="contacts_name" value="<?php echo $output['joinin_detail']['contacts_name'];?>">
                </td>
                <th>联系人电话:</th>
                <td>
                    <input type="text" class="input-txt" name="contacts_phone" value="<?php echo $output['joinin_detail']['contacts_phone'];?>">
                </td>
                <th>电子邮箱:</th>
                <td>
                    <input type="text" class="input-txt" name="contacts_email" value="<?php echo $output['joinin_detail']['contacts_email'];?>">
                </td>
            </tr>
            </tbody>
        </table>
        <!--公司法人信息-->
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">公司法人信息</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">公司法人姓名:</th>
                <td>
                    <input type="text" class="txt w300" name="legal_person_name" value="<?php echo $output['joinin_detail']['legal_person_name'];?>">
                </td>
            </tr>
            <tr>
                <th>公司法人身份证号:</th>
                <td>
                    <input type="text" class="txt w300" name="id_number" value="<?php echo _decrypt($output['joinin_detail']['id_number']);?>">
                </td>
            </tr>

            <tr>
                <th>公司法人身份证电子版:</th>
                <td>
                    <input name="identity_card_electronic" type="file" class="w60" multiple/>
                    <div class="img_box">
                        <?php
                        $img_str = $output['joinin_detail']['identity_card_electronic'];
                        if(!empty($img_str)){
                            $htm = '';
                            $img_arr = explode("|",$img_str);
                            foreach($img_arr as $val){
                                if(!empty($val)){
                                    $htm .= '<span class="img_span">';
                                    $htm .='<img height="60" class="per_img" src="'.$output['pic_url'].$val.'" />';
                                    $htm .='<img data-field="identity_card_electronic" class="delate_small" src="'.MEMBER_TEMPLATES_URL.'/images/delate_small.jpg" alt=""/>';
                                    $htm .='</span>';
                                }
                            }
                            echo $htm;
                        }
                        ?>
                    </div>
                    <span class="block">请上传身份证正面和反面的电子版。</span>
                    <span class="block">请确保图片清晰，文字可辨。</span>
                    <input name="identity_card_electronic1" type="hidden" value="<?php echo $output['joinin_detail']['identity_card_electronic']; ?>"/><span></span>
                </td>
            </tr>

            </tbody>
        </table>
        <!--营业执照信息(副本)-->
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">营业执照信息(副本)</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">营业执照号:</th>
                <td>
                    <input type="text" class="input-txt" name="business_licence_number" value="<?php echo _decrypt($output['joinin_detail']['business_licence_number']);?>">
                </td>
            </tr>
            <tr>
                <th>营业执照所在地:</th>
                <td>
                    <input type="hidden" name="business_licence_address" id="business_licence_address" value="<?php echo $output['joinin_detail']['business_licence_address'];?>">
                </td>
            </tr>
            <tr>
                <th>营业执照有效期:</th>
                <td>
                    <input id="business_licence_start" name="business_licence_start" type="text" class="w90"  value="<?php echo $output['joinin_detail']['business_licence_start']; ?>" />
                    <input id="business_licence_end" name="business_licence_end" type="text" value="<?php echo $output['joinin_detail']['business_licence_end']; ?>" class="w90" />
                </td>
            </tr>
            <tr>
                <th>法定经营范围:</th>
                <td>
                    <textarea name="business_sphere" rows="15">
                        <?php echo $output['joinin_detail']['business_sphere']; ?>
                    </textarea>
                </td>
            </tr>
            <tr>
                <th>营业执照电子版:</th>
                <td>
                    <input name="business_licence_number_elc" type="file" class="w60" />
                    <?php
                    if(!empty($output['joinin_detail']['business_licence_number_elc'])){
                        $htm = '<img height="60" src="';
                        $htm .= $output['pic_url'].$output['joinin_detail']['business_licence_number_elc'];
                        $htm .='"/>';
                        echo $htm;
                    }
                    ?>
                    <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span>
                    <input name="business_licence_number_elc1" value="<?php echo $output['joinin_detail']['business_licence_number_elc']; ?>" type="hidden"/>
                </td>
            </tr>
            </tbody>
        </table>
        <!--组织机构代码证-->
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">组织机构代码证</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th style="width: 150px">组织机构代码:</th>
                <td colspan="20">
                    <input type="text" class="txt w300" name="organization_code" value="<?php echo _decrypt($output['joinin_detail']['organization_code']);?>">
                </td>
            </tr>
            <tr>
                <th style="width: 150px">组织机构代码证电子版:</th>
                <td>
                    <input name="organization_code_electronic" type="file" class="w60"/>
                    <?php
                    if(!empty($output['joinin_detail']['organization_code_electronic'])){
                        $htm = '<img height="60" src="';
                        $htm .= $output['pic_url'].$output['joinin_detail']['organization_code_electronic'];
                        $htm .='"/>';
                        echo $htm;
                    }
                    ?>
                    <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span>
                    <input name="organization_code_electronic1" type="hidden" value="<?php echo $output['joinin_detail']['organization_code_electronic']; ?>" />
                </td>
            </tr>
            </tbody>
        </table>
        <!--一般纳税人证明-->
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">一般纳税人证明</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th style="width: 150px">一般纳税人证明:</th>
                <td>
                    <input name="general_taxpayer" type="file" class="w60" />
                    <?php
                    if(!empty($output['joinin_detail']['general_taxpayer'])){
                        $htm = '<img height="60" src="';
                        $htm .= $output['pic_url'].$output['joinin_detail']['general_taxpayer'];
                        $htm .='"/>';
                        echo $htm;
                    }
                    ?>
                    <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章.</span>
                    <input name="general_taxpayer1" type="hidden" value="<?php echo $output['joinin_detail']['general_taxpayer']; ?>"/>
                </td>
            </tr>
            </tbody>
        </table>
        <!--开户银行信息-->
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">开户银行信息</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">银行开户名:</th>
                <td>
                    <input type="text" class="txt w300" name="bank_account_name" value="<?php echo $output['joinin_detail']['bank_account_name'];?>">
                </td>
            </tr>
            <tr>
                <th>公司银行账号:</th>
                <td>
                    <input type="text" class="txt w300" name="bank_account_number" value="<?php echo _decrypt($output['joinin_detail']['bank_account_number']);?>">
                </td>
            </tr>
            <tr>
                <th>开户银行支行名称:</th>
                <td>
                    <input type="text" class="txt w300" name="bank_name" value="<?php echo $output['joinin_detail']['bank_name'];?>">
                </td>
            </tr>
            <tr>
                <th>支行联行号:</th>
                <td>
                    <input type="text" class="txt w300" name="bank_code" value="<?php echo _decrypt($output['joinin_detail']['bank_code']);?>">
                </td>
            </tr>
            <tr>
                <th>开户银行所在地:</th>
                <td colspan="20">
                    <input id="bank_address" name="bank_address" type="hidden" value="<?php echo $output['joinin_detail']['bank_address']; ?>" />
                </td>
            </tr>
            <tr>
                <th>开户银行许可证电子版:</th>
                <td>
                    <input name="bank_licence_electronic" type="file"  class="w60"/>
                    <?php
                    if(!empty($output['joinin_detail']['bank_licence_electronic'])){
                        $htm = '<img height="60" src="';
                        $htm .= $output['pic_url'].$output['joinin_detail']['bank_licence_electronic'];
                        $htm .='"/>';
                        echo $htm;
                    }
                    ?>
                    <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章.</span>
                    <input name="bank_licence_electronic1" value="<?php echo $output['joinin_detail']['bank_licence_electronic']; ?>" type="hidden"/>
                </td>
            </tr>
            </tbody>
        </table>
        <!--结算账号信息-->
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">结算账号信息</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">银行开户名:</th>
                <td>
                    <input type="text" class="txt w300" name="settlement_bank_account_name" value="<?php echo $output['joinin_detail']['settlement_bank_account_name'];?>">
                </td>
            </tr>
            <tr>
                <th>公司银行账号:</th>
                <td>
                    <input type="text" class="txt w300" name="settlement_bank_account_number" value="<?php echo _decrypt($output['joinin_detail']['settlement_bank_account_number']);?>">
                </td>
            </tr>
            <tr>
                <th>开户银行支行名称:</th>
                <td>
                    <input type="text" class="txt w300" name="settlement_bank_name" value="<?php echo $output['joinin_detail']['settlement_bank_name'];?>">
                </td>
            </tr>
            <tr>
                <th>支行联行号:</th>
                <td>
                    <input type="text" class="txt w300" name="settlement_bank_code" value="<?php echo _decrypt($output['joinin_detail']['settlement_bank_code']);?>">
                </td>
            </tr>
            <tr>
                <th>开户银行所在地:</th>
                <td>
                    <input type="hidden" name="settlement_bank_address" id="settlement_bank_address" value="<?php echo $output['joinin_detail']['settlement_bank_address'];?>">
                </td>
            </tr>
            </tbody>
        </table>
        <!--税务登记证-->
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">税务登记证</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">税务登记证号：</th>
                <td>
                    <input type="text" class="txt w300" name="tax_registration_certificate" value="<?php echo _decrypt($output['joinin_detail']['tax_registration_certificate']);?>">
                </td>
            </tr>
            <tr>
                <th>纳税人识别号:</th>
                <td>
                    <input type="text" class="txt w300" name="taxpayer_id" value="<?php echo _decrypt($output['joinin_detail']['taxpayer_id']);?>">
                </td>
            </tr>
            <tr>
                <th>税务登记证号电子版:</th>
                <td>
                    <input name="tax_registration_certif_elc" type="file"  class="w60"/>
                    <?php
                    if(!empty($output['joinin_detail']['tax_registration_certif_elc'])){
                        $htm = '<img height="60" src="';
                        $htm .= $output['pic_url'].$output['joinin_detail']['tax_registration_certif_elc'];
                        $htm .='"/>';
                        echo $htm;
                    }
                    ?>
                    <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span>
                    <input name="tax_registration_certif_elc1" value="<?php echo $output['joinin_detail']['tax_registration_certif_elc']; ?>"   type="hidden"/>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
            <div class="bottom" style="padding-left: 20%;padding-bottom: 50px">
                <a id="btn_pass" class="ncap-btn-big ncap-btn-green mr10">修改资料</a>
                <span style="color: red">(点击修改资料后,需要管理员再次审核,会影响提现申请)</span>
            </div>
    <script type="text/javascript">
        $(document).ready(function(){
            /*显示上传营业执照电子版``````*/
            <?php foreach (array('business_licence_number_elc','organization_code_electronic','general_taxpayer','tax_registration_certif_elc','bank_licence_electronic') as $input_id) { ?>
            $('input[name="<?php echo $input_id;?>"]').fileupload({
                dataType: 'json',
                url: '<?php echo urlMember('manager_index', 'ajax_upload_image');?>',
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
            <!--公司法人身份证上传-->
            <?php foreach (array('identity_card_electronic') as $input_id) { ?>
            $('input[name="<?php echo $input_id;?>"]').fileupload({
                dataType: 'json',
                url: "<?php echo urlMember('manager_index', 'ajax_upload_image');?>",
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
                        $(this).next('div').append('<span class="img_span">'+'<img height="60" class="per_img"src="'+data.result.pic_url+'"><img class="delate_small" src="<?php echo MEMBER_TEMPLATES_URL;?>/images/delate_small.jpg" alt=""/>'+'<span/>');
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
            /*所在地*/
            $('#company_address').nc_region();
            $('#business_licence_address').nc_region();
            $("#bank_address").nc_region();
            $("#settlement_bank_address").nc_region();
            /*时间插件*/
            $('#business_licence_start').datepicker();
            $('#business_licence_end').datepicker();


            //多文件上传删除图片功能js      未做完
            $('.delate_small').live('click',function(){
                var obj=$(this);
                var img_field = $(this).attr('data-field');
                var img_url=$($(obj.closest('span').find('img'))[0]).prop('src') ;
                var inputHidden = $(obj.closest('td')).find("input[type='hidden']");
                var str = inputHidden.val();
                $.ajax({
                    type:"GET",
                    url:'<?php echo urlMember('manager_index', 'delateimg');?>',
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

            /*表单验证*/
            $('#joinin_form').validate({
                errorPlacement: function(error, element){
                    element.nextAll('span').first().after(error);
                },
                rules : {
                    complete_company_name: {
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
                    },
                    bank_account_name: {
                        required: true,
                        maxlength: 50
                    },
                    bank_account_number: {
                        required: true,
                        maxlength: 40
                    },
                    bank_name: {
                        required: true,
                        maxlength: 50
                    },
                    bank_code: {
                        required: true,
                        maxlength: 20
                    },
                    bank_address: {
                        required: true
                    },
                    bank_licence_electronic1: {
                        required: true,
                    },
                    settlement_bank_account_name: {
                        required: true,
                        maxlength: 50
                    },
                    settlement_bank_account_number: {
                        required: true,
                        maxlength: 20
                    },
                    settlement_bank_name: {
                        required: true,
                        maxlength: 50
                    },
                    settlement_bank_code: {
                        required: true,
                        maxlength: 20
                    },
                    settlement_bank_address: {
                        required: true,
                    },
                    tax_registration_certificate: {
                        required: true,
                        maxlength: 20
                    },
                    taxpayer_id: {
                        required: true,
                        maxlength: 20
                    },
                    tax_registration_certif_elc1: {
                        required: true
                    }
                },
                messages : {
                    complete_company_name: {
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
                    },
                    bank_account_name: {
                        required: '请填写银行开户名',
                        maxlength: jQuery.validator.format("最多{0}个字")
                    },
                    bank_account_number: {
                        required: '请填写公司银行账号',
                        maxlength: jQuery.validator.format("最多{0}个字")
                    },
                    bank_name: {
                        required: '请填写开户银行支行名称',
                        maxlength: jQuery.validator.format("最多{0}个字")
                    },
                    bank_code: {
                        required: '请填写支行联行号',
                        maxlength: jQuery.validator.format("最多{0}个字")
                    },
                    bank_address: {
                        required: '请选择开户银行所在地'
                    },
                    bank_licence_electronic1: {
                        required: '请选择上传开户银行许可证电子版文件'
                    },
                    settlement_bank_account_name: {
                        required: '请填写银行开户名',
                        maxlength: jQuery.validator.format("最多{0}个字")
                    },
                    settlement_bank_account_number: {
                        required: '请填写公司银行账号',
                        maxlength: jQuery.validator.format("最多{0}个字")
                    },
                    settlement_bank_name: {
                        required: '请填写开户银行支行名称',
                        maxlength: jQuery.validator.format("最多{0}个字")
                    },
                    settlement_bank_code: {
                        required: '请填写支行联行号',
                        maxlength: jQuery.validator.format("最多{0}个字")
                    },
                    settlement_bank_address: {
                        required: '请选择开户银行所在地'
                    },
                    tax_registration_certificate: {
                        required: '请填写税务登记证号',
                        maxlength: jQuery.validator.format("最多{0}个字")
                    },
                    taxpayer_id: {
                        required: '请填写纳税人识别号',
                        maxlength: jQuery.validator.format("最多{0}个字")
                    },
                    tax_registration_certif_elc1: {
                        required: '请选择上传税务登记证号电子版文件'
                    }
                }
            });
            /*点击提交*/
            $('#btn_pass').on('click', function() {
                /*表单验证*/
                if($('#joinin_form').valid()){
                    layer.confirm('是否需要修改资料', {
                        btn: ['确定','取消'] //按钮
                    }, function(){
                        var url = '<?php echo urlMember('manager_index','edit_manager_details');?>';
                        var param = $("#joinin_form").serialize();
                        $.post(url,param,function(data){
                            if(data.state){
                                layer.alert(data.info, {
                                    skin: '' //样式类名
                                    ,closeBtn: 0
                                }, function(){
                                    var url = '<?php echo urlMember('manager_index','manager_details');?>'
                                    window.location.href = url;
                                });
                            }else{
                                layer.alert(data.info);
                            }
                        },'json');
                    }, function(){
                        layer.close();
                    });
                }
            });
        });
    </script>