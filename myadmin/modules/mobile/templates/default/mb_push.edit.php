<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>推送通知</h3>
        <h5>手机客户端接收网站通知等设置</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>iOS应用分两种推送模式，开发版应用和生产版应用需要在"<a href="http://push.baidu.com/" target="_blank"><strong>云推送</strong></a>"上传对应的开发版证书和生产版证书。</li>
    </ul>
  </div>
  <form method="post" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
    <div class="title">
      <h3>iOS 平台</h3>
    </div>
      <dl class="row">
        <dt class="tit">
          <label>应用状态</label>
        </dt>
        <dd class="opt">
            <label><input type="radio" name="baidu_push_ios" value="1" <?php echo $output['list_setting']['baidu_push_ios']==1?'checked=checked':''; ?>>开发版</label>
            <label><input type="radio" name="baidu_push_ios" value="2" <?php echo $output['list_setting']['baidu_push_ios']==2?'checked=checked':''; ?>>生产版</label>
          <p class="notic">请注意：开发版应用和生产版应用，两种类型的应用之间消息推送不能互通</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="baidu_push_ios_key">API KEY</label>
        </dt>
        <dd class="opt">
          <input id="baidu_push_ios_key" name="baidu_push_ios_key" value="<?php echo $output['list_setting']['baidu_push_ios_key'];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="baidu_push_ios_secret">SECRET KEY</label>
        </dt>
        <dd class="opt">
          <input id="baidu_push_ios_secret" name="baidu_push_ios_secret" value="<?php echo $output['list_setting']['baidu_push_ios_secret'];?>" class="input-txt" type="text">
          <p class="notic">&nbsp;</p>
        </dd>
      </dl>
    <div class="title">
      <h3>Android平台</h3>
    </div>
      <dl class="row">
        <dt class="tit">
          <label for="baidu_push_android_key">API KEY</label>
        </dt>
        <dd class="opt">
          <input id="baidu_push_android_key" name="baidu_push_android_key" value="<?php echo $output['list_setting']['baidu_push_android_key'];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="baidu_push_android_secret">SECRET KEY</label>
        </dt>
        <dd class="opt">
          <input id="baidu_push_android_secret" name="baidu_push_android_secret" value="<?php echo $output['list_setting']['baidu_push_android_secret'];?>" class="input-txt" type="text">
          <p class="notic">&nbsp;</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
