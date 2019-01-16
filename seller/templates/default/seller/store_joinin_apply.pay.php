<header id="header" class="fixed">

  <div class="header-wrap">

    <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>

   <div class="header-title">

      <h1>上传付款凭证</h1>

    </div>

   <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>

   </div>

       <?php include template('layout/seller_toptip');?>





</header>

<div class="nctouch-main-layout fixed-Width">



<div class="nctouch-home-block">

  <div class="tit-bar"><i style="background:#EC5464;"></i>公司及联系人信息</div>

  <div class="input_box">

    <dl class="border_bottom">

        <dt>公司名称</dt>

        <dd><?php echo $output['joinin_detail']['company_name'];?></dd>

    </dl>

     <dl class="border_bottom">

        <dt>公司所在地</dt>

        <dd>

           <?php echo $output['joinin_detail']['company_address'];?>

        </dd>

    </dl>

   <dl class="border_bottom">

        <dt>公司详细地址</dt>

        <dd>

          <?php echo $output['joinin_detail']['company_address_detail'];?>

        </dd>

    </dl>

    <dl class="border_bottom">

        <dt>联系人</dt>

        <dd>

           <?php echo $output['joinin_detail']['contacts_name'];?>

        </dd>

    </dl>

    <dl class="border_bottom">

        <dt>联系人电话</dt>

        <dd>

           <?php echo $output['joinin_detail']['contacts_phone'];?>

        </dd>

    </dl>

   <dl class="border_bottom">

        <dt>电子邮箱</dt>

        <dd>

           <?php echo $output['joinin_detail']['contacts_email'];?>

        </dd>

    </dl>

  </div>

</div>



<div class="nctouch-home-block mt5">

  <div class="tit-bar"><i style="background:#EC5464;"></i>公司法人信息</div>

  <div class="input_box">

    <dl class="border_bottom">

        <dt><i>*</i>法人姓名</dt>

        <dd>
           <?php echo $output['joinin_detail']['legal_person_name'];?>

        </dd>

    </dl>

    <dl class="border_bottom">

        <dt><i>*</i>法人身份证</dt>

        <dd>
           <?php echo $output['joinin_detail']['id_nmuber'];?>

        </dd>

    </dl>

  </div>

</div>



<div class="nctouch-home-block mt5">

  <div class="tit-bar"><i style="background:#EC5464;"></i>营业执照信息（副本）</div>

  <div class="input_box">

    <dl class="border_bottom">

        <dt><i>*</i>营业执照号</dt>

        <dd>
           <?php echo $output['joinin_detail']['business_licence_number'];?>

        </dd>

    </dl>

  </div>

  <div class="evaluation-upload-block">

     

        <div class="nctouch-upload">

          <a href="javascript:void(0);">

            <span>

              <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_elc']);?>" width="48px" height="48px">

            </span>

     

          </a>

     

        </div>

      

  </div>

</div>

<div class="nctouch-home-block mt5">

  <div class="tit-bar"><i style="background:#EC5464;"></i>组织机构代码证</div>

  <div class="input_box">

    <dl class="border_bottom">

        <dt><i>*</i>组织机构代码</dt>

        <dd>
           <?php echo $output['joinin_detail']['organization_code'];?>

        </dd>

    </dl>

  </div>

  <div class="evaluation-upload-block">

          <div class="nctouch-upload">

          <a href="javascript:void(0);">

            <span>

              <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['organization_code_electronic']);?>" width="48px" height="48px">

            </span>

     

          </a>

     

        </div>

      

  </div>

</div>



<div class="nctouch-home-block mt5">

  <div class="tit-bar"><i style="background:#EC5464;"></i>结算账号信息</div>

  <div class="input_box">

    <dl class="border_bottom">

        <dt>银行</dt>

        <dd><?php $banks = Model('merchant_bank')->getList(array("bank_code"=>$output['joinin_detail']['bank_no']));echo $banks[0]['bank_name'];?></dd>

    </dl>

    <dl class="border_bottom">

        <dt>银行开户名</dt>

        <dd><?php echo $output['joinin_detail']['settlement_bank_account_name'];?></dd>

    </dl>

     <dl class="border_bottom">

        <dt>公司银行账号</dt>

        <dd>

           <?php echo $output['joinin_detail']['settlement_bank_account_number'];?>

        </dd>

    </dl>

    <dl class="border_bottom">

        <dt>银行卡类型</dt>

        <dd><?php if($output['joinin_detail']['bank_account_type'] == '1'){echo "借记卡";} else if($output['joinin_detail']['bank_account_type'] == '2'){echo "贷记卡";} else if($output['joinin_detail']['bank_account_type'] == '3'){echo "存折";} else{echo "";}?></dd>

    </dl>



  </div>

</div>



<div class="nctouch-home-block mt5">


  <div class="tit-bar"><i style="background:#EC5464;"></i>税务登记证</div>

  <div class="input_box">

    <dl class="border_bottom">

        <dt><i>*</i>税务登记证号</dt>

        <dd>
           <?php echo $output['joinin_detail']['tax_registration_certificate'];?>

        </dd>

    </dl>

  </div>

  <div class="evaluation-upload-block">

  <div class="nctouch-upload">

          <a href="javascript:void(0);">

            <span>

              <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certif_elc']);?>" width="48px" height="48px">

            </span>

     

          </a>

     </div>

   </div>

</div>

<div class="nctouch-home-block mt5">

  <div class="tit-bar"><i style="background:#EC5464;"></i>店铺经营信息</div>

  <div class="input_box">

    <dl class="border_bottom">

        <dt>商家账号</dt>

        <dd><?php echo $output['joinin_detail']['seller_name'];?></dd>

    </dl>

    <dl class="border_bottom">

        <dt>商家地址</dt>

        <dd><?php echo $output['joinin_detail']['area_address'];?></dd>

    </dl>

     <dl class="border_bottom">

        <dt>店铺名称</dt>

        <dd><?php echo $output['joinin_detail']['store_name'];?></dd>

    </dl>

     <dl class="border_bottom">

        <dt>店铺等级</dt>

        <dd><?php echo $output['joinin_detail']['sg_name'];?>（开店费用：<?php echo $output['joinin_detail']['sg_price'];?> 元/年）</dd>

    </dl>

     <dl class="border_bottom">

        <dt>开店时长</dt>

        <dd>

           <?php echo $output['joinin_detail']['joinin_year'];?> 年

        </dd>

    </dl>

   <dl class="border_bottom">

        <dt>店铺分类</dt>

        <dd>

          <?php echo $output['joinin_detail']['sc_name'];?>（开店保证金：<?php echo $output['joinin_detail']['sc_bail'];?> 元）

        </dd>

    </dl>

   <dl class="border_bottom">

        <dt>经营类目</dt>

        <dd>

         <?php $categories= Model('merchant_category')->getList(array("category_code"=>$output['joinin_detail']['gc_no']));echo $categories[0]['category_name'];?>
        </dd>

    </dl>



   <dl class="border_bottom">

        <dt>应付金额</dt>

        <dd>

          <?php echo $output['joinin_detail']['paying_amount'];?> 元

        </dd>

    </dl>

    <div class="input_box">

          <table id="table_category" class="type" border="0" cellpadding="0" cellspacing="0">

                <thead>

                  <tr>

                    <th class="w120 tc">一级类目</th>

                  

                    <th class="w50 tc">分佣比例</th>

                  </tr>

                </thead>

              <tbody>

              <tr class="store-class-item store-class-items">

                    <?php $store_class_names = unserialize($output['joinin_detail']['store_class_names']);?>

                  <?php $store_class_commis_rates = explode(',', $output['joinin_detail']['store_class_commis_rates']);?>

                  <?php if(!empty($store_class_names) && is_array($store_class_names)) {?>

                  <?php for($i=0, $length = count($store_class_names); $i < $length; $i++) {?>

                  <?php list($class1, $class2, $class3) = explode(',', $store_class_names[$i]);?>

                  <tr>

                    <td><?php echo $class1;?></td>

                 

                    <td><?php echo $store_class_commis_rates[$i];?>%</td>

                  </tr>

                  <?php } ?>

                  <?php } ?>

             

              </tr>

              </tbody>

              </table>

        

    </div>

   <dl class="border_bottom">

        <dt>审核意见</dt>

        <dd style="color:#e44d4d">

          <?php echo $output['joinin_detail']['joinin_message'];?>

        </dd>

    </dl>

  </div>

</div>



<div class="nctouch-home-block mt5">

  <div class="tit-bar"><i style="background:#EC5464;"></i>上传付款凭证</div>

  <div class="evaluation-upload-block">

        <div class="tit"><i></i><p>添&nbsp;加</p></div>

        <div class="nctouch-upload">

          <a href="javascript:void(0);">

            <span><input hidefocus="true" size="1" class="input-file" name="file" id="" type="file"></span>

            <p><i class="icon-upload"></i></p>

          </a>

          <input name="paying_money_certificate" value="" type="hidden">

        </div>

       

  </div>

  <div class="input_box">

  <dl class="border_bottom">

        <dt>备注信息</dt>

        <dd>

          <textarea name="paying_money_certif_exp" class="paying_money_certif_exp" ></textarea>

        </dd>

    </dl>

  </div>

</div>



<a class="btn-l mt5 mb5" id="next_company_info">提交</a>



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





    $("#next_company_info").click(function(){

       var data={

       'paying_money_certif_exp':$(".paying_money_certif_exp").val(),

       'paying_money_certificate':$("input[name='paying_money_certificate']").val(),

     

      };

   

   

        if(data['paying_money_certificate']==''){

          layer.open({content:'请上传付款凭证!'});

       }

       

    

       $.ajax({

        type: "post",

        url: ApiUrl + "/index.php?con=store_joinin&fun=pay_save",

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

