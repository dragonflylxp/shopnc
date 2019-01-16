<?php defined('Inshopec') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo MOBILE_TEMPLATES_URL;?>/css/nctouch_common.css">





<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

   <div class="header-title">

      <h1>个人入驻申请</h1>

    </div>

   <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

   </div>

       <?php include template('layout/seller_toptip');?>





</header>

<div class="nctouch-main-layout fixed-Width">

<div class="alert">

    <h4>注意事项：</h4>

    以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1M之内。

</div>

<div class="nctouch-home-block">

  <div class="tit-bar"><i style="background:#EC5464;"></i>店铺及联系人信息</div>

  <div class="input_box">

    <dl class="border_bottom">

        <dt><i>*</i>店铺名称</dt>

        <dd><input id="company_name" name="company_name" value="" type="text"></dd>

    </dl>

     <dl class="border_bottom">

        <dt><i>*</i>所在地</dt>

        <dd>

          <input id="company_address" name="company_address" type="text" value=""/>

        </dd>

    </dl>

    <dl class="border_bottom">

        <dt><i>*</i>详细地址</dt>

        <dd>

          <input id="company_address_detail" name="company_address_detail" value="" type="text">

        </dd>

    </dl>

    <dl class="border_bottom">

        <dt><i>*</i>联系人姓名</dt>

        <dd>

         <input id="contacts_name" name="contacts_name" value="" type="text">

        </dd>

    </dl>

    <dl class="border_bottom">

        <dt><i>*</i>联系人电话</dt>

        <dd>

          <input id="contacts_phone" name="contacts_phone" value="" type="text">

        </dd>

    </dl>

    <dl class="border_bottom">

        <dt><i>*</i>电子邮箱</dt>

        <dd>

          <input id="contacts_email" name="contacts_email" value="" type="text">

        </dd>

    </dl>

  </div>

</div>



<div class="nctouch-home-block mt5">

  <div class="tit-bar"><i style="background:#EC5464;"></i>身份证信息</div>

   <div class="input_box">

    <dl class="border_bottom">

        <dt><i>*</i>姓名</dt>

        <dd><input id="legal_person_name" name="legal_person_name" value="" type="text"></dd>

    </dl>

   <dl class="border_bottom">

        <dt><i>*</i>身份证号</dt>

        <dd><input id="legal_person_id" name="legal_person_id" value="" type="text"></dd>

    </dl>

  

  <dl class="border_bottom">

  <div class="evaluation-upload-block">

        <div class="tit"><i></i><p>添&nbsp;加</p></div>

        <div class="nctouch-upload">

          <a href="javascript:void(0);">

            <span><input hidefocus="true" size="1" class="input-file" name="file" id="" type="file"></span>

            <p><i class="icon-upload"></i></p>

                  </a>

          <input name="business_licence_number_electronic" value="" type="hidden">

        </div>

      

  </div>

  </dl>

  </div>

  <div class="evaluation-upload-block">

    <div class="center_img" style="margin:0 auto;width:317px;">

      <img src="./templates/default/images/example.jpg">

    </div>

  </div>

  

</div>



<div class="nctouch-home-block mt5">

  <div class="tit-bar"><i style="background:#EC5464;"></i>结算账号信息</div>

  <div class="input_box">

    <dl class="border_bottom">

        <dt><i>*</i>银行</dt>
        <dd><select name="bank_no" id="bank_no">
              <option value="">请选择</option>
              <?php if(!empty($output['bank_list']) && is_array($output['bank_list'])){ ?>
              <?php foreach($output['bank_list'] as $k => $v){ ?>
              <option value="<?php echo $v['bank_code'];?>"><?php echo $v['bank_name'];?></option>
              <?php } ?>
              <?php } ?>
            </select>
        </dd>

    </dl>

    <dl class="border_bottom">

        <dt><i>*</i>银行开户名</dt>

        <dd><input id="bank_account_name" name="bank_account_name" value="" type="text"></dd>

    </dl>

     <dl class="border_bottom">

        <dt><i>*</i>银行账号</dt>

        <dd>

         <input id="bank_account_number" name="bank_account_number" value="" type="text">

        </dd>

    </dl>

    <dl class="border_bottom">

        <dt><i>*</i>银行卡类型</dt>
        <dd>
              <select name="bank_account_type" id="bank_account_type">
              <option value="">请选择</option>
              <option value="1" >借记卡</option>
              <option value="2" >贷记卡</option>
              <option value="3" >存折</option>
              </select>
        </dd>

    </dl>

  </div>

</div>





<a class="btn-l mt5 mb5" id="next_company_info">下一步，提交店铺经营信息</a>

</div>

<div class="fix-block-r">

  <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>

</div>









<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/zepto.min.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/template.js"></script> 

<script type="text/javascript" src="<?php echo MOBILE_TEMPLATES_URL;?>/js/common.js"></script> 

<script type="text/javascript">

  $(function(){

    $('input[name="file"]').ajaxUploadImage({

      url: ApiUrl + "/index.php?con=sns_album&fun=file_upload_rz",

      

      start: function(e) {

        e.parent().after('<div class="upload-loading"><i></i></div>');

        e.parent().siblings(".pic-thumb").remove()

      },

      success: function(e, a) {

        if (a.datas.error) {

          e.parent().siblings(".upload-loading").remove();

          layer.open({

            content:'图片尺寸过大！',

            time:1.5

          });

          return false

        }

        e.parent().after('<div class="pic-thumb"><img src="' + a.datas.file_url + '"/></div>');

        e.parent().siblings(".upload-loading").remove();

        e.parents("a").next().val(a.datas.file_name)

      }

    });



    $("#company_address").on("click",

    function() {

        $.areaSelected({

            success: function(a) {

                $("#company_address").val(a.area_info).attr({

                    "data-areaid": a.area_id,

                    "data-areaid2": a.area_id_2 == 0 ? a.area_id_1: a.area_id_2

                })

            }

        })

    })

    $("#next_company_info").click(function(){

       var data={

       'company_name':$("#company_name").val(),

       'company_address':$("#company_address").val(),

       'company_address_detail':$("#company_address_detail").val(),

       'contacts_name':$("#contacts_name").val(),

       'contacts_phone': $("#contacts_phone").val(),

       'contacts_email':$("#contacts_email").val(),

       'legal_person_name':$("#legal_person_name").val(),

       'legal_person_id':$("#legal_person_id").val(),

       'business_licence_number_electronic':$("input[name='business_licence_number_electronic']").val(),

       'bank_no':$("#bank_no").val(),

       'bank_account_name':$("#bank_account_name").val(),

       'bank_account_number':$("#bank_account_number").val(),

       'bank_account_type':$("#bank_account_type").val()
     
      };

       if(data['company_name']==''){

          layer.open({content:'店铺名称不得为空!'});

       }

        if(data['company_address']==''){

          layer.open({content:'地址不得为空!'});

       }

        if(data['company_address_detail']==''){

          layer.open({content:'地址详情不得为空!'});

       }

        if(data['contacts_name']==''){

          layer.open({content:'联系人不得为空!'});

       }

       if(data['business_sphere']==''){

          layer.open({content:'姓名不得为空!'});

       }

       if(data['bank_no']==''){

          layer.open({content:'银行不得为空!'});

       }
       if(data['bank_account_name']==''){

          layer.open({content:'银行开户名不得为空!'});

       }

       if(data['bank_account_number']==''){

          layer.open({content:'银行账号不得为空!'});

       }
       if(data['bank_account_type']==''){

          layer.open({content:'银行类型不得为空!'});

       }

       

        if(data['business_licence_number_electronic']==''){

          layer.open({content:'请上传手持身份证照片!'});

       }

        if(data['legal_person_name']==''){

          layer.open({content:'身份证姓名不得为空!'});

       }


        if(data['legal_person_id']==''){

          layer.open({content:'身份证号码不得为空!'});

       }

      

       var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 

        if(data['contacts_phone']=='' && !reg.test(data['contacts_phone'])){

          layer.open({content:'电话号码格式不正确!'});

       }

       var regemail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        if(data['contacts_email']=='' && !regemail.test(data['contacts_email'])){

          layer.open({content:'邮箱格式不正确!'});

       }



    

       $.ajax({

        type: "post",

        url: ApiUrl + "/index.php?con=store_joinin_c2c&fun=savesetep",

        data: data,

        dataType: "json",

        async: false,

        success: function(e) {

            if (e.code == 200) {

               setTimeout(function () {

                    window.location.href = e.datas.url;   

                }, 1000); 

            } else {

         

                   layer.open({

                    content:e.datas.error,

                    time:1.5

                 })

              





               

            }

        }

    });

      

    })



  })

</script>





