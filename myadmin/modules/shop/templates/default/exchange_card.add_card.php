<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo urlAdminShop('exchange_card', 'index'); ?>" title="返回平台兑换卷列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>平台兑换卷 - 新增</h3>
        <h5>商城兑换卷设置生成</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>

      <li>输入兑换卷总数，由系统自动生成指定总数；</li>
      <li>兑换券金额以输入金额为准</li>
    </ul>
  </div>
  <form method="post" enctype="multipart/form-data" name="form_add" id="form_add">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>相关内容</label>
        </dt>
        <dd class="opt tabswitch-target"> 总数：
          <input type="text" class="txt" name="total" style="width:40px;" />
          <span class="err"></span>
          <p class="notic">请输入总数，总数为1~9999之间的整数</p>
        </dd>
          <dl class="row">
        <dt class="tit">
          <label><em>*</em>面额(元)</label>
        </dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="denomination" />
          <span class="err"></span>
          <p class="notic">请输入面额，面额不可超过1000</p>
        </dd>
      </dl>
        <dl class="time">
            <dt class="tit">
                <label>选择时间</label>
            </dt>
            <dd class="opt">
                <input id="start_time" type="text" name="start_time" value="<?php echo date("Y-m-d",time()); ?>" />
               -<input id="end_time" type="text" name="end_time"/>
                <span class="err"></span>
                <p class="notic">请选择兑换券的使用时间范围,结束时间未选择表示兑换卷永久可用</p>
            </dd>
        </dl>

      <dl class="row">
        <dt class="tit">
          <label>批次标识</label>
        </dt>
        <dd class="opt">
            <select class="input-txt" type="text" name="batchflag"  id="batchflag">
                <option value="">缺省</option>
                <option value="400元兑换卷" data-id="dhq_400">400元兑换卷</option>

            </select>
            <input type="hidden" name="func_name" id="func_name">
          <p class="notic">用于标识和区分不同批次添加的兑换券，便于检索</p>
        </dd>
      </dl>
      <div class="bot"><a href="javascript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){

$('.tabswitch').click(function() {
    var i = parseInt(this.value);
    $('.tabswitch-target').hide().eq(i).show();
});

$("#submitBtn").click(function(){
    $("#form_add").submit();
});

jQuery.validator.addMethod("r0total", function(value, element) {
    var v = parseInt(value);
    return $(":radio[name='type']:checked").val() != '0' || (value == v && v >= 1 && v <= 9999);
}, "<i class='fa fa-exclamation-circle'></i>总数必须是1~9999之间的整数");

jQuery.validator.addMethod("r0prefix", function(value, element) {
    return $(":radio[name='type']:checked").val() != '0' || this.optional(element) || /^[0-9a-zA-Z]{0,16}$/.test(value);
}, "<i class='fa fa-exclamation-circle'></i>前缀必须是16字之内字母数字的组合");

jQuery.validator.addMethod("r1textfile", function(value, element) {
    return $(":radio[name='type']:checked").val() != '1' || value;
}, "<i class='fa fa-exclamation-circle'></i>请选择纯文本格式充值卡卡号文件");

jQuery.validator.addMethod("r2manual", function(value, element) {
    return $(":radio[name='type']:checked").val() != '2' || value;
}, "<i class='fa fa-exclamation-circle'></i>请输入充值卡卡号");
    //抵用卡使用时间
    $('#start_time,#end_time').datepicker({dateFormat:'yy-mm-dd',Date: '<?php echo date('Y-m-d',TIMESTAMP);?>'});
    //选中兑换卷给隐藏域赋值
    $('#batchflag').change(function(){
        var sval =$("#batchflag").find ("option:selected").attr("data-id");

        $('input[name="func_name"]').val(sval);


    });


$("#form_add").validate({
     errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
    rules : {
        denomination : {
            required : true,
            min: 0.01,
            max: 1000
        },
        batchflag : {
            maxlength: 20
        },
        total : {
            r0total : true
        },
        prefix : {
            r0prefix : true
        },
        textfile : {
            r1textfile : true
        },
        manual : {
            r2manual : true
        },
        start_time : {
            required : true
        },
        total : {
            required : true
        }
    },
    messages : {
        denomination : {
            required : '<i class="fa fa-exclamation-circle"></i>请填写面额',
            min : '<i class="fa fa-exclamation-circle"></i>面额不能小于0.01',
            max: '<i class="fa fa-exclamation-circle"></i>面额不能大于1000'
        },
        batchflag : {
            maxlength: '<i class="fa fa-exclamation-circle"></i>请输入20字之内的批次标识'
        },
        start_time : {
            required :'<i class="fa fa-exclamation-circle"></i>请选择兑换卷开始使用日期'
        },
        total : {
            required :'<i class="fa fa-exclamation-circle"></i>请选择兑换卷发放数量'
        }
    }
});
});
</script> 
