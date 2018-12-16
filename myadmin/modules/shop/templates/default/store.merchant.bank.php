<?php defined('Inshopec') or exit('Access Invalid!');?>
<style type="text/css">
.d_inline {
	display: inline;
}
</style>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?con=store&fun=store" title="返回<?php echo $lang['manage'];?>列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_store_manage'];?> - 会员“<?php echo $output['joinin_detail']['member_name'];?>”登记银行卡信息</h3>
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
      <li>商家提交的入驻信息，用于商户银行卡信息登记业务</li>
    </ul>
  </div>
  <div class="homepage-focus">
    <div class="title">
        <ul class="tab-base nc-row">
        <li><a class="current" href=javascript:void(0);">银行卡信息</a></li>
        </ul>
    </div>
    <form id="joinin_form" enctype="application/x-www-form-urlencoded " method="post" action="index.php?con=store&fun=store_merchant_bank">
      <input type="hidden" id="form_submit" name="form_submit" value="ok" />
      <input type="hidden" id="store_id" name="store_id" value="<?php echo $output['store_array']['store_id'];?>" />
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">已添加的银行卡</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>账户属性</th>
            <th>银行代码</th>
            <th>银行卡类型</th>
            <th>银行卡号</th>
            <th>持卡人姓名</th>
            <th>证件号码</th>
          </tr>
          <?php $bankCodeMap = array(); foreach($output['bank_list'] as $k=>$v){$bankCodeMap[$v['bank_code']]=$v['bank_name'];}?>
          <?php $bankaccPropMap = array('0'=>'私人','1'=>'公司');?>
          <?php $bankaccountTypeMap = array('1'=>'借记卡','2'=>'贷记卡','3'=>'存折');?>
          <?php if(!empty($output['bankaccounList']) && is_array($output['bankaccounList'])){ ?>
          <?php foreach($output['bankaccounList'] as $k => $v){ ?>
          <tr>
            <td><?php echo $bankaccPropMap[$v['bankaccProp']];?></td>
            <td><?php echo $bankCodeMap[$v['bankCode']]?></td>
            <td><?php echo $bankaccountTypeMap[$v['bankaccountType']];?></td>
            <td><?php echo $v['bankaccountNo'];?></td>
            <td><?php echo $v['name'];?></td>
            <td><?php echo $v['certNo'];?></td>
          </tr>
          <?php } ?>
          <?php } ?>
        </tbody>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">商户信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>操作类型：</th>
            <td colspan="20">
              <select name="handleType" id="handleType">
              <option value="">请选择</option>
              <option value="0" selected>新增</option>
              <option value="1">删除</option>
              <option value="2">修改</option>
            </select>
            </td>
          </tr>
          <tr>
            <th>商户id：</th>
            <td colspan="20"><input readonly="readonly" type="text" class="txt w300" id="merchant_id" name="merchant_id" value="<?php echo $output["store_array"]["store_merchantno"];?>"></td>
          </tr>
        </tbody>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">银行卡信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>账户属性：</th>
            <td colspan="20">
              <select name="bankaccProp" id="bankaccProp">
              <option value="">请选择</option>
              <option value="0" >私人</option>
              <option value="1" >公司</option>
              </select>
            </td>
          </tr>
          <tr>
            <th>银行代码：</th>
            <td colspan="20">
              <select name="bankCode" id="bankCode">
              <option value="">请选择</option>
              <?php if(!empty($output['bank_list']) && is_array($output['bank_list'])){ ?>
              <?php foreach($output['bank_list'] as $k => $v){ ?>
              <option value=<?php echo $v['bank_code'];?>><?php echo $v['bank_name'];?></option>
              <?php } ?>
              <?php } ?>
            </select>
            </td>
          </tr>
          <tr>
            <th>银行卡类型：</th>
            <td colspan="20">
              <select name="bankaccountType" id="bankaccountType">
              <option value="">请选择</option>
              <option value="1" >借记卡</option>
              <option value="2" >贷记卡</option>
              <option value="3" >存折</option>
              </select>
            </td>
          </tr>
          <tr>
            <th>银行卡号：</th>
            <td colspan="20"><input type="text" class="txt w300" id="bankaccountNo" name="bankaccountNo" value=""></td>
          </tr>
          <tr>
            <th>联行号：</th>
            <td colspan="20"><input type="text" class="txt w300" id="bankbranchNo" name="bankbranchNo" value=""></td>
          </tr>
          <tr>
            <th>持卡人姓名：</th>
            <td colspan="20"><input type="text" class="txt w300" id="name" name="name" value=""></td>
          </tr>
          <tr>
            <th>持卡人手机号：</th>
            <td colspan="20"><input type="text" class="txt w300" id="mobileNo" name="mobileNo" value=""></td>
          </tr>
          <tr>
            <th>办卡证件类型：</th>
            <td colspan="20">
              <select name="certCode" id="certCode">
              <option value="">请选择</option>
              <option value="1" selected>身份证</option>
              <option value="2" >护照</option>
              <option value="3" >军官证</option>
              <option value="4" >回乡证</option>
              <option value="5" >台胞证</option>
              <option value="6" >港澳通行证</option>
              <option value="7" >国际海员证</option>
              <option value="8" >外国人永久居住证</option>
              <option value="9" >其他</option>
              </select>
            </td>
          </tr>
          <tr>
            <th>证件号码：</th>
            <td colspan="20"><input type="text" class="txt w300" id="certNo" name="certNo" value=""></td>
          </tr>
        </tbody>
      </table>
      <table  border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">账户设置</th>
          </tr>
        </thead>
        <tbody>
           <tr>
             <th>设置为默认账户：</th>
             <td>
               <select name="defaultAcc" id="defaultAcc">
               <option value="">请选择</option>
               <option value="0" selected>否</option>
               <option value="1">是</option>
               </select>
             </td>
           </tr>
        </tbody>
      </table>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn">提交</a></div>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script> 

<script type="text/javascript">
var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';
$(function(){
    var merchant_id = $("#merchant_id").val();
    //根据cookie回复上次填写的数据
    var joinin_form_cookie = getCookie('joinin_form_'+merchant_id);
    if(joinin_form_cookie != null && joinin_form_cookie != ''){
        joinin_form_cookie = decodeURIComponent(joinin_form_cookie);
        var joinin_form_array = joinin_form_cookie.split('&'); 
        for(i=0; i<joinin_form_array.length; i++){
            param = joinin_form_array[i].split('=');
            if(param[1] != null && param[1] != '' && param[0] != 'merchant_id'){
                m_setval(param[0], param[1]);
            }
        }
    }
    
    //按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        if($("#joinin_form").valid()){
            //保存上次填写的数据1小时
            addCookie('joinin_form_'+merchant_id, $("#joinin_form").serialize(), 1);
            $("#joinin_form").submit();
        }
    });

    $('#joinin_form').validate({
        rules : {
             merchant_id: {
                 required: true 
              },
             bankaccProp: {
                 required: true 
              },
             bankCode: {
                 required: true 
              },
             bankaccountType: {
                 required: true 
              },
             bankaccountNo: {
                 required: true 
              },
             bankbranchNo: {
                 required: true 
              },
             name: {
                 required: true 
              },
             mobileNo: {
                 required: true 
              },
             certNo: {
                 required: true 
              },
             defaultAcc: {
                 required: true 
              }
        },
        messages : {
            merchant_id: {
                required: '<i class="fa fa-exclamation-circle"></i>商户未入驻，请先入驻'
            },
            bankaccProp: {
                required: '<i class="fa fa-exclamation-circle"></i>请选择账户属性'
            },
            bankCode: {
                required: '<i class="fa fa-exclamation-circle"></i>请选择银行'
            },
            bankaccountType: {
                required: '<i class="fa fa-exclamation-circle"></i>请选择银行卡类型'
            },
            bankaccountNo: {
                required: '<i class="fa fa-exclamation-circle"></i>请填写银行卡号'
            },
            bankbranchNo: {
                required: '<i class="fa fa-exclamation-circle"></i>请填写联行号'
            },
            name: {
                required: '<i class="fa fa-exclamation-circle"></i>请填写持卡人姓名'
            },
            mobileNo: {
                required: '<i class="fa fa-exclamation-circle"></i>请填写持卡人手机号'
            },
            certNo: {
                required: '<i class="fa fa-exclamation-circle"></i>请填写证件号码'
            },
            defaultAcc: {
                required: '<i class="fa fa-exclamation-circle"></i>请选择是否设置默认账户'
            },
        }
    });
});
</script>
