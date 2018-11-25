<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?con=mb_push&fun=index" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>推送通知 - <?php echo $lang['nc_new'];?></h3>
        <h5>手机客户端接收网站通知等设置</h5>
      </div>
    </div>
  </div>
  <form id="post_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="msg_tag"><em>*</em>会员级别</label>
        </dt>
        <dd class="opt">
          <select name="msg_tag" id="msg_tag">
            <option value="default">全部</option>
            <option value="v0">V0</option>
            <option value="v1">V1</option>
            <option value="v2">V2</option>
            <option value="v3">V3</option>
          </select>
          <span class="err"></span>
          <p class="notic">默认给全部手机端推送通知，当选择级别后，只有这个级别的会员能收到通知。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="log_type"><em>*</em>推送类型</label>
        </dt>
        <dd class="opt">
          <select name="log_type" id="log_type">
            <option value="1">关键字</option>
            <option value="2">专题编号</option>
            <option value="3">商品编号</option>
          </select>
          <span class="err"></span>
          <p class="notic">"关键字"跳转至商品列表页面，"专题编号"跳转至专题页面，"商品编号"跳转至商品详细页面。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="log_type_v"><em>*</em>类型值</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="log_type_v" id="log_type_v" class="input-txt">
          <span class="err"></span>
          <p class="notic">请根据"推送类型"，填写正确的信息。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>推送内容</label>
        </dt>
        <dd class="opt">
          <textarea name="log_msg" rows="6" class="tarea" id="log_msg" ></textarea>
          <span class="err"></span>
          <p class="notic">建议最多输入100个字符。</p>
        </dd>
      </dl>
      <div class="bot"> <a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
        if($("#post_form").valid()){
            $("#post_form").submit();
    	}
	});
	$("#post_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
			log_type_v : {
				required : true
            },
			log_msg : {
                required   : true
            }
        },
        messages : {
            log_type_v : {
                required : "<i class='fa fa-exclamation-circle'></i>类型值不能为空"
            },
            log_msg : {
                required : "<i class='fa fa-exclamation-circle'></i>推送内容不能为空"
            }
        }
	});
});
</script> 
