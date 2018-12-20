<?php defined('Inshopec') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<style type="text/css">
.d_inline {
	display: inline;
}
</style>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?con=store&fun=store" title="返回<?php echo $lang['manage'];?>列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_store_manage'];?> - 会员“<?php echo $output['joinin_detail']['member_name'];?>”上传证件照片</h3>
        <h5><?php echo $lang['nc_store_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>商家上传证件照片</li>
    </ul>
  </div>
  <div class="homepage-focus">
    <div class="title">
        <ul class="tab-base nc-row">
        <li><a class="current" href=javascript:void(0);">上传证件照片</a></li>
        </ul>
    </div>
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="store_id" value="<?php echo $output['store_array']['store_id'];?>" />
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="20">商户信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th>商户id：</th>
          <td colspan="20"><input readonly="readonly" type="text" class="txt w300" name="merchant_id" value="<?php echo $output["store_array"]["store_merchantno"];?>"></td>
        </tr>
        <form id="form_bank_card_front" enctype="application/x-www-form-urlencoded " method="post" action="index.php?con=store&fun=store_merchant_image">
        <tr>
          <th>银行卡正面：</th>
          <td>
              <input name="pic_type" value="01" type="hidden"/>
              <input name="member_id" value="<?php echo $output["store_array"]["member_id"];?>" type="hidden"/>
              <input name="merchant_id" value="<?php echo $output["store_array"]["store_merchantno"];?>" type="hidden"/>
              <input name="img_bank_card_front" type="file"  multiple class="w60"/>

              <div class="img_box">
                  <?php
                  $img_str = $output['joinin_detail']['img_bank_card_front'];
                  if(!empty($img_str)){
                      $htm = '';
                      $img_arr = explode("|",$img_str);
                      foreach($img_arr as $val){
                          if(!empty($val)){
                              $htm .= '<span class="img_span">';
                              $htm .='<img height="60" class="per_img" src="'.UPLOAD_SITE_URL.'/mall/store_joinin/'.$val.'" />';
                              $htm .='<img data-field="img_bank_card_front" class="delate_small" src="'.SHOP_TEMPLATES_URL.'/images/shop/delate_small.jpg" alt=""/>';
                              $htm .='</span>';
                          }
                      }
                      echo $htm;
                  }
                  ?>
              </div>
              <span class="block">请确保图片清晰(支持格式：jpg/jpeg/gif/png', 单张大小不超过<?php echo intval(C('image_max_filesize'))/1024;?>M)</span>
              <input name="img_bank_card_front1" value="<?php echo $output['joinin_detail']['img_bank_card_front']; ?>" type="hidden"/><span></span>
          </td>
          <td>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn_bank_card_front">上传</a></div>
          </td>
        </tr>
        </form>
        <form id="form_id_card_front" enctype="application/x-www-form-urlencoded " method="post" action="index.php?con=store&fun=store_merchant_image">
        <tr>
          <th>身份证正面：</th>
          <td>
              <input name="pic_type" value="02" type="hidden"/>
              <input name="member_id" value="<?php echo $output["store_array"]["member_id"];?>" type="hidden"/>
              <input name="merchant_id" value="<?php echo $output["store_array"]["store_merchantno"];?>" type="hidden"/>
              <input name="img_id_card_front" type="file"  multiple class="w60"/>

              <div class="img_box">
                  <?php
                  $img_str = $output['joinin_detail']['img_id_card_front'];
                  if(!empty($img_str)){
                      $htm = '';
                      $img_arr = explode("|",$img_str);
                      foreach($img_arr as $val){
                          if(!empty($val)){
                              $htm .= '<span class="img_span">';
                              $htm .='<img height="60" class="per_img" src="'.$output['pic_url'].$val.'" />';
                              $htm .='<img data-field="img_id_card_front" class="delate_small" src="'.SHOP_TEMPLATES_URL.'/images/shop/delate_small.jpg" alt=""/>';
                              $htm .='</span>';
                          }
                      }
                      echo $htm;
                  }
                  ?>
              </div>
              <span class="block">请确保图片清晰(支持格式：jpg/jpeg/gif/png', 单张大小不超过<?php echo intval(C('image_max_filesize'))/1024;?>M)</span>
              <input name="img_id_card_front1" value="<?php echo $output['joinin_detail']['img_id_card_front']; ?>" type="hidden"/><span></span>
          </td>
          <td>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn_id_card_front">上传</a></div>
          </td>
        </tr>
        </form>
        <form id="form_id_card_back" enctype="application/x-www-form-urlencoded " method="post" action="index.php?con=store&fun=store_merchant_image">
        <tr>
          <th>身份证反面：</th>
          <td>
              <input name="pic_type" value="03" type="hidden"/>
              <input name="member_id" value="<?php echo $output["store_array"]["member_id"];?>" type="hidden"/>
              <input name="merchant_id" value="<?php echo $output["store_array"]["store_merchantno"];?>" type="hidden"/>
              <input name="img_id_card_back" type="file"  multiple class="w60"/>

              <div class="img_box">
                  <?php
                  $img_str = $output['joinin_detail']['img_id_card_back'];
                  if(!empty($img_str)){
                      $htm = '';
                      $img_arr = explode("|",$img_str);
                      foreach($img_arr as $val){
                          if(!empty($val)){
                              $htm .= '<span class="img_span">';
                              $htm .='<img height="60" class="per_img" src="'.$output['pic_url'].$val.'" />';
                              $htm .='<img data-field="img_id_card_back" class="delate_small" src="'.SHOP_TEMPLATES_URL.'/images/shop/delate_small.jpg" alt=""/>';
                              $htm .='</span>';
                          }
                      }
                      echo $htm;
                  }
                  ?>
              </div>
              <span class="block">请确保图片清晰(支持格式：jpg/jpeg/gif/png', 单张大小不超过<?php echo intval(C('image_max_filesize'))/1024;?>M)</span>
              <input name="img_id_card_back1" value="<?php echo $output['joinin_detail']['img_id_card_back']; ?>" type="hidden"/><span></span>
          </td>
          <td>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn_id_card_back">上传</a></div>
          </td>
        </tr>
        </form>
        <form id="form_idcard_in_hand" enctype="application/x-www-form-urlencoded " method="post" action="index.php?con=store&fun=store_merchant_image">
        <tr>
          <th>手持身份证：</th>
          <td>
              <input name="pic_type" value="04" type="hidden"/>
              <input name="member_id" value="<?php echo $output["store_array"]["member_id"];?>" type="hidden"/>
              <input name="merchant_id" value="<?php echo $output["store_array"]["store_merchantno"];?>" type="hidden"/>
              <input name="img_idcard_in_hand" type="file"  multiple class="w60"/>

              <div class="img_box">
                  <?php
                  $img_str = $output['joinin_detail']['img_idcard_in_hand'];
                  if(!empty($img_str)){
                      $htm = '';
                      $img_arr = explode("|",$img_str);
                      foreach($img_arr as $val){
                          if(!empty($val)){
                              $htm .= '<span class="img_span">';
                              $htm .='<img height="60" class="per_img" src="'.$output['pic_url'].$val.'" />';
                              $htm .='<img data-field="img_idcard_in_hand" class="delate_small" src="'.SHOP_TEMPLATES_URL.'/images/shop/delate_small.jpg" alt=""/>';
                              $htm .='</span>';
                          }
                      }
                      echo $htm;
                  }
                  ?>
              </div>
              <span class="block">请确保图片清晰(支持格式：jpg/jpeg/gif/png', 单张大小不超过<?php echo intval(C('image_max_filesize'))/1024;?>M)</span>
              <input name="img_idcard_in_hand1" value="<?php echo $output['joinin_detail']['img_idcard_in_hand']; ?>" type="hidden"/><span></span>
          </td>
          <td>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn_idcard_in_hand">上传</a></div>
          </td>
        </tr>
        </form>
        <form id="form_business_licence" enctype="application/x-www-form-urlencoded " method="post" action="index.php?con=store&fun=store_merchant_image">
        <tr>
          <th>商业登记证/执业执照：</th>
          <td>
              <input name="pic_type" value="05" type="hidden"/>
              <input name="member_id" value="<?php echo $output["store_array"]["member_id"];?>" type="hidden"/>
              <input name="merchant_id" value="<?php echo $output["store_array"]["store_merchantno"];?>" type="hidden"/>
              <input name="img_business_licence" type="file"  multiple class="w60"/>

              <div class="img_box">
                  <?php
                  $img_str = $output['joinin_detail']['img_business_licence'];
                  if(!empty($img_str)){
                      $htm = '';
                      $img_arr = explode("|",$img_str);
                      foreach($img_arr as $val){
                          if(!empty($val)){
                              $htm .= '<span class="img_span">';
                              $htm .='<img height="60" class="per_img" src="'.$output['pic_url'].$val.'" />';
                              $htm .='<img data-field="img_business_licence" class="delate_small" src="'.SHOP_TEMPLATES_URL.'/images/shop/delate_small.jpg" alt=""/>';
                              $htm .='</span>';
                          }
                      }
                      echo $htm;
                  }
                  ?>
              </div>
              <span class="block">请确保图片清晰(支持格式：jpg/jpeg/gif/png', 单张大小不超过<?php echo intval(C('image_max_filesize'))/1024;?>M)</span>
              <input name="img_business_licence1" value="<?php echo $output['joinin_detail']['img_business_licence']; ?>" type="hidden"/><span></span>
          </td>
          <td>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn_business_licence">上传</a></div>
          </td>
        </tr>
        </form>
        <form id="form_company_register" enctype="application/x-www-form-urlencoded " method="post" action="index.php?con=store&fun=store_merchant_image">
        <tr>
          <th>开户许可证：</th>
          <td>
              <input name="pic_type" value="06" type="hidden"/>
              <input name="member_id" value="<?php echo $output["store_array"]["member_id"];?>" type="hidden"/>
              <input name="merchant_id" value="<?php echo $output["store_array"]["store_merchantno"];?>" type="hidden"/>
              <input name="img_company_register" type="file"  multiple class="w60"/>

              <div class="img_box">
                  <?php
                  $img_str = $output['joinin_detail']['img_company_register'];
                  if(!empty($img_str)){
                      $htm = '';
                      $img_arr = explode("|",$img_str);
                      foreach($img_arr as $val){
                          if(!empty($val)){
                              $htm .= '<span class="img_span">';
                              $htm .='<img height="60" class="per_img" src="'.$output['pic_url'].$val.'" />';
                              $htm .='<img data-field="img_company_register" class="delate_small" src="'.SHOP_TEMPLATES_URL.'/images/shop/delate_small.jpg" alt=""/>';
                              $htm .='</span>';
                          }
                      }
                      echo $htm;
                  }
                  ?>
              </div>
              <span class="block">请确保图片清晰(支持格式：jpg/jpeg/gif/png', 单张大小不超过<?php echo intval(C('image_max_filesize'))/1024;?>M)</span>
              <input name="img_company_register1" value="<?php echo $output['joinin_detail']['img_company_register']; ?>" type="hidden"/><span></span>
          </td>
          <td>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn_company_register">上传</a></div>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn">提交</a></div>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 

<script type="text/javascript">
var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';

$(function(){
    //证件图片上传
    <?php foreach (array('img_id_card_front','img_id_card_back','img_bank_card_front','img_idcard_in_hand','img_business_card','img_company_register') as $input_id) { ?>
    $('input[name="<?php echo $input_id;?>"]').fileupload({
        dataType: 'json',
        url: '<?php echo urlShop('store_joinin', 'ajax_upload_image');?>',
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
                $(this).next('div').append('<span class="img_span">'+'<img height="60" class="per_img"src="'+data.result.pic_url+'"><img data-field="" class="delate_small" src="<?php echo SHOP_TEMPLATES_URL;?>/images/shop/delate_small.jpg" alt=""/>'+'<span/>');
                if($(this).next('div').find('span').length > 2){
                    $(this).next('div').find('span')[0].remove();
                }
                $('input[name="<?php echo $input_id;?>1"]').val(data.result.pic_name);
            } else {
                alert(data.result.message);
            }
        },
        fail: function(){
            alert('上传失败，请重试!');
        }
    });
    <?php } ?>

    $("#submitBtn_bank_card_front").click(function(){
        $("#form_bank_card_front").submit();
    });
    $("#submitBtn_id_card_front").click(function(){
        $("#form_id_card_front").submit();
    });
    $("#submitBtn_id_card_back").click(function(){
        $("#form_id_card_back").submit();
    });
    $("#submitBtn_idcard_in_hand").click(function(){
        $("#form_idcard_in_hand").submit();
    });
    $("#submitBtn_business_licence").click(function(){
        $("#form_business_licence").submit();
    });
    $("#submitBtn_company_register").click(function(){
        $("#form_business_licence").submit();
    });
});
</script>
