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
        <h3><?php echo $lang['nc_store_manage'];?> - 会员“<?php echo $output['joinin_detail']['member_name'];?>”商户入驻业务</h3>
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
      <li>商家提交的入驻信息，用于商户入驻业务</li>
      <li>此页面数据不提供修改功能，请前往<font color="red">编辑店铺信息</font>页面修改</li>
    </ul>
  </div>
  <div class="homepage-focus">
    <div class="title">
        <ul class="tab-base nc-row">
        <li><a class="current" href=javascript:void(0);">入驻信息</a></li>
        </ul>
    </div>
    <form id="joinin_form" enctype="application/x-www-form-urlencoded " method="post" action="index.php?con=store&fun=store_merchant_register">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="store_id" value="<?php echo $output['store_array']['store_id'];?>" />
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">公司及联系人信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w150">公司名称：</th>
            <td colspan="20"><input readonly="readonly" type="text" class="input-txt" name="company_name" value="<?php echo $output['joinin_detail']['company_name'];?>"></td>
          </tr>
          <tr>
            <th>公司所在地：</th>
            <td colspan="20"><input readonly="readonly" type="text" class="txt w300" name="company_address" value="<?php echo $output['joinin_detail']['company_address'];?>"></td>
            </td>
          </tr>
          <tr>
            <th>公司详细地址：</th>
            <td colspan="20"><input readonly="readonly" type="text" class="txt w300" name="company_address_detail" value="<?php echo $output['joinin_detail']['company_address_detail'];?>"></td>
          </tr>
          <tr>
            <th>公司电话：</th>
            <td><input readonly="readonly" type="text" class="input-txt" name="company_phone" value="<?php echo $output['joinin_detail']['company_phone'];?>"></td>
            <th>员工总数：</th>
            <td><input readonly="readonly" type="text" class="txt w70" name="company_employee_count" value="<?php echo $output['joinin_detail']['company_employee_count'];?>">
              &nbsp;人</td>
            <th>注册资金：</th>
            <td><input readonly="readonly" type="text" class="txt w70" name="company_registered_capital" value="<?php echo $output['joinin_detail']['company_registered_capital'];?>">
              &nbsp;万元 </td>
          </tr>
          <tr>
            <th>联系人姓名：</th>
            <td><input readonly="readonly" type="text" class="input-txt" name="contacts_name" value="<?php echo $output['joinin_detail']['contacts_name'];?>"></td>
            <th>联系人电话：</th>
            <td><input readonly="readonly" type="text" class="input-txt" name="contacts_phone" value="<?php echo $output['joinin_detail']['contacts_phone'];?>"></td>
            <th>电子邮箱：</th>
            <td><input readonly="readonly" type="text" class="input-txt" name="contacts_email" value="<?php echo $output['joinin_detail']['contacts_email'];?>"></td>
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
            <th>法人姓名：</th>
            <td colspan="20"><input readonly="readonly" type="text" class="txt w300" name="legal_person_name" value="<?php echo $output['joinin_detail']['legal_person_name'];?>"></td>
          </tr>
          <tr>
            <th>法人身份证：</th>
            <td colspan="20"><input readonly="readonly" type="text" class="txt w300" name="legal_person_id" value="<?php echo $output['joinin_detail']['id_number'];?>"></td>
          </tr>
          <tr>
            <th>组织机构代码：</th>
            <td colspan="20"><input readonly="readonly" type="text" class="txt w300" name="organization_code" value="<?php echo $output['joinin_detail']['organization_code'];?>"></td>
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
            <th class="w150">银行：</th>
            <td>
               <input readonly="readonly" type="text" class="txt w300" name="bank_no_name" value="<?php $banks = Model('merchant_bank')->getList(array('bank_code'=>$output['joinin_detail']['bank_no'])); echo $banks[0]['bank_name'];?>">
               <input type="hidden" class="txt w300" name="bank_no" value="<?php echo $output['joinin_detail']['bank_no'];?>">
            </td>
          </tr>
          <tr>
            <th>银行开户名：</th>
            <td><input readonly="readonly" type="text" class="txt w300" name="bank_account_name" value="<?php echo $output['joinin_detail']['bank_account_name'];?>"></td>
          </tr>
          <tr>
            <th>公司银行账号：</th>
            <td><input readonly="readonly" type="text" class="txt w300" name="bank_account_number" value="<?php echo $output['joinin_detail']['bank_account_number'];?>"></td>
          </tr>
          <tr>
            <th>开户银行支行名称：</th>
            <td><input readonly="readonly" type="text" class="txt w300" name="bank_name" value="<?php echo $output['joinin_detail']['bank_name'];?>"></td>
          </tr>
          <tr>
            <th>支行联行号：</th>
            <td><input readonly="readonly" type="text" class="txt w300" name="bank_code" value="<?php echo $output['joinin_detail']['bank_code'];?>"></td>
          </tr>
          <tr>
            <th>开户银行所在地：</th>
            <td colspan="20"><input readonly="readonly" type="text" name="bank_address" id="bank_address" value="<?php echo $output['joinin_detail']['bank_address'];?>"></td>
          </tr>
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
            <th>商户id：</th>
            <td colspan="20"><input readonly="readonly" type="text" class="txt w300" name="merchant_id" value="<?php echo $output["store_array"]["store_merchantno"];?>"></td>
          </tr>
          <tr>
            <th>商户类型：</th>
            <td colspan="20"><input readonly="readonly" type="text" class="txt w300" name="merchant_type_name" value="公司商户"></td>
                <input type="hidden" class="txt w300" name="merchant_type" value="00">
          </tr>
          <tr>
            <th>商户城市：</th>
            <td colspan="20"><input readonly="readonly" type="text" class="txt w300" name="merchant_city" value="<?php $areas = Model('merchant_area')->getList(array("area_code"=>$output['joinin_detail']['area_no'])); echo $areas[0]["area_name"];?>">
                <input type="hidden" class="txt w300" name="area_no" value="<?php echo $output['joinin_detail']['area_no'];?>">
           </td>
          </tr>
          <tr>
            <th>经营类目：</th>
            <td colspan="20">
                <input readonly="readonly" type="text" class="txt w300" name="gc_category" value="<?php $categories = Model('merchant_category')->getList(array("category_code"=>$output['joinin_detail']['gc_no'])); echo $categories[0]['category_name'];?>">
                <input type="hidden" class="txt w300" name="gc_no" value="<?php echo $output['joinin_detail']['gc_no'];?>">
           </td>
          </tr>
        </tbody>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">辅助信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>是否自动提现：</th>
            <td colspan="20">
              <dd class="opt">
                <div class="onoff">
                  <label for="autocus1" class="cb-enable selected">是</label>
                  <label for="autocus0" class="cb-disable">否</label>
                  <input id="autocus1" checked="checked" name="autocus" value="1" type="radio">
                  <input id="autocus0" name="autocus" value="0" type="radio">
                </div>
              </dd>
            </td>
          </tr>
          <tr>
             <th>入驻备注：</th>
             <td colspan="20">
               <dd class="opt">
                 <textarea name="merchant_register_remark" rows="6" class="tarea" id="merchant_register_remark"></textarea>
                 <span class="err"></span>
               </dd>
             </td>
          </tr>
        </tbody>
      </table>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn">提交</a></div>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 

<script type="text/javascript">
var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';
$(function(){
    //按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        if($("#joinin_form").valid()){
            $("#joinin_form").submit();
        }
    });

    $('#joinin_form').validate({
        rules : {
             merchant_id: {
                 maxlength: 0 
              }
        },
        messages : {
            merchant_id: {
                maxlength: '<i class="fa fa-exclamation-circle"></i>商户已入驻，请勿重复入驻'
            }
        }
    });
});
</script>
