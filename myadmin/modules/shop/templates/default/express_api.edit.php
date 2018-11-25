<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>快递接口</h3>
        <h5>快递接口的选择和设置</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>在两种快递接口中选择使用一个，需在"<a href="http://www.kuaidi100.com/" target="_blank"><strong>快递100</strong></a>"、"<a href="http://www.kdniao.com/" target="_blank"><strong>快递鸟</strong></a>"上申请开通后才能使用。</li>
    </ul>
  </div>
  <form method="post" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>接口网站</label>
        </dt>
        <dd class="opt">
            <label><input type="radio" name="express_api" value="1" <?php echo $output['list_setting']['express_api']==1?'checked=checked':''; ?>>快递100</label>
            <label><input type="radio" name="express_api" value="2" <?php echo $output['list_setting']['express_api']==2?'checked=checked':''; ?>>快递鸟</label>
          <p class="notic">快递100接口为收费版本，快递鸟可免费申请</p>
        </dd>
      </dl>
    <div class="title">
      <h3>快递100接口设置</h3>
    </div>
      <dl class="row">
        <dt class="tit">
          <label for="express_kuaidi100_id">公司编号</label>
        </dt>
        <dd class="opt">
          <input id="baidu_push_ios_key" name="express_kuaidi100_id" value="<?php echo $output['list_setting']['express_kuaidi100_id'];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="express_kuaidi100_key">授权密钥</label>
        </dt>
        <dd class="opt">
          <input id="baidu_push_ios_secret" name="express_kuaidi100_key" value="<?php echo $output['list_setting']['express_kuaidi100_key'];?>" class="input-txt" type="text">
          <p class="notic">&nbsp;</p>
        </dd>
      </dl>
    <div class="title">
      <h3>快递鸟接口设置</h3>
    </div>
      <dl class="row">
        <dt class="tit">
          <label for="express_kdniao_id">商户ID</label>
        </dt>
        <dd class="opt">
          <input id="baidu_push_android_key" name="express_kdniao_id" value="<?php echo $output['list_setting']['express_kdniao_id'];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="baidu_push_android_secret">商户私钥</label>
        </dt>
        <dd class="opt">
          <input id="express_kdniao_key" name="express_kdniao_key" value="<?php echo $output['list_setting']['express_kdniao_key'];?>" class="input-txt" type="text">
          <p class="notic">&nbsp;</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
