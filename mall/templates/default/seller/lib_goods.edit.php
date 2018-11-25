<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <form id="post_form" method="post" action="index.php?con=store_lib_goods&fun=edit_goods&gc_id=<?php echo $output['goods']['gc_id']; ?>&goods_id=<?php echo $output['goods']['goods_id']; ?>">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt><i class="required">*</i>商品价格<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input class="text w60" type="text" name="g_price" value="" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
        <p class="hint">价格必须是0.01~9999999之间的数字，且不能高于市场价。</p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>市场价格<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input class="text w60" type="text" name="g_marketprice" value=""  /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
        <p class="hint">价格必须是0.01~9999999之间的数字，此价格仅为市场参考售价，请根据该实际情况认真填写</p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>商品库存<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input class="text w60" type="text" name="g_storage" value=""  />
        <p class="hint">商铺库存数量必须为0~999999999之间的整数</p>
      </dd>
    </dl>
    <dl>
      <dt>固定运费<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input class="w50 text" type="text" value="" name="g_freight"><em class="add-on"><i class="icon-renminbi"></i></em>
      </dd>
    </dl>
    <dl>
      <dt>售卖区域<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <select name="transport_id">
            <option><?php echo $lang['nc_please_choose'];?></option>
          <?php if (!empty($output['trans_list'])) {?>
            <?php foreach ($output['trans_list'] as $val) {?>
            <option value="<?php echo $val['id'];?>"><?php echo $val['title'];?>(<?php echo $output['goods_trans_array'][$val['goods_trans_type']];?>)</option>
            <?php }?>
          <?php }?>
        </select>
        <p class="hint">当选择售卖区域时，“固定运费”将不会保存生效</p>
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_ok'];?>" /></label>
    </div>
  </form>
</div>
<script type="text/javascript">
    $.validator.addMethod('checkPrice', function(value,element){
    	_g_price = parseFloat($('input[name="g_price"]').val());
        _g_marketprice = parseFloat($('input[name="g_marketprice"]').val());
        if (_g_marketprice <= 0) {
            return true;
        }
        if (_g_price > _g_marketprice) {
            return false;
        }else {
            return true;
        }
    }, '');
$(function(){
    $('#post_form').validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
            $('#warning').show();
        },
    	submitHandler:function(form){
    	    ajaxpost('post_form', '', '', 'onerror');
    	},
        rules : {
            g_price : {
                required    : true,
                number      : true,
                min         : 0.01,
                max         : 9999999,
                checkPrice  : true
            },
            g_marketprice : {
                required    : true,
                number      : true,
                min         : 0.01,
                max         : 9999999,
                checkPrice  : true
            },
            g_storage  : {
                required    : true,
                digits      : true,
                min         : 0,
                max         : 999999999
            },
            g_freight : {
                number      : true
            }
        },
        messages : {
            g_price : {
                required    : '<i class="icon-exclamation-sign"></i>商品价格不能为空',
                number      : '<i class="icon-exclamation-sign"></i>商品价格只能是数字',
                min         : '<i class="icon-exclamation-sign"></i>商品价格必须是0.01~9999999之间的数字',
                max         : '<i class="icon-exclamation-sign"></i>商品价格必须是0.01~9999999之间的数字',
                checkPrice  : '<i class="icon-exclamation-sign"></i>商品价格不能高于市场价格'
            },
            g_marketprice : {
                required    : '<i class="icon-exclamation-sign"></i>请填写市场价',
                number      : '<i class="icon-exclamation-sign"></i>请填写正确的价格',
                min         : '<i class="icon-exclamation-sign"></i>请填写0.01~9999999之间的数字',
                max         : '<i class="icon-exclamation-sign"></i>请填写0.01~9999999之间的数字',
                checkPrice  : '<i class="icon-exclamation-sign"></i>市场价格不能低于商品价格'
            },
            g_storage : {
                required    : '<i class="icon-exclamation-sign"></i>商品库存不能为空',
                digits      : '<i class="icon-exclamation-sign"></i>库存只能填写数字',
                min         : '<i class="icon-exclamation-sign"></i>商铺库存数量必须为0~999999999之间的整数',
                max         : '<i class="icon-exclamation-sign"></i>商铺库存数量必须为0~999999999之间的整数'
            },
            g_freight : {
                number      : '<i class="icon-exclamation-sign"></i>请填写正确的运费'
            }
        }
    });
});
</script>
