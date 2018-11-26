<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
  <form method="post"  action="index.php?con=store_setting&fun=store_zhibo" enctype="multipart/form-data" id="my_store_form">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt>说明：</dt>
      <dd>
        <p>更多设置需要您联系我们在线客服处理！QQ：1232123</p>
        </dd>
    </dl>
    <dl>
      <dt>直播背景：</dt>
      <dd>
        <ul>
        <?php for($i=1;$i<=13;$i++){?>
        	<li <?php if($output['zb_config']['bgid']==$i){echo "class='select'";}?>><img class="zb_bg" src="<?php echo  UPLOAD_SITE_URL.'/shop/store/zhibo_bg/'.$i.'.jpg'; ?>"/><input type="radio" name="zb_bg" value="<?php echo $i;?>" <?php if($output['zb_config']['bgid']==$i){echo "checked='checked'";}?> ></li>
        <?php }?>
        </ul>
      </dd>
    </dl>
     <dl>
      <dt>直播公告：</dt>
      <dd>
       <input type="text" value="<?php echo $output['zb_config']['gonggao'];?>" name="zb_gonggao" class="text w400">
      </dd>
    </dl>
    <dl>
      <dt>主播ID：</dt>
      <dd>
       <input type="text" value="<?php echo $output['zb_config']['uid'];?>" name="zb_uid" class="text w400">
      </dd>
    </dl> 
       
    <dl>
      <dt>宣传图片：</dt>
      <dd>
        <div class="ncsc-upload-thumb store-logo">
          <p><img src="<?php echo $output['zb_config']['img'];?>" nc_type="store_zb_img" /> </p>
          
          </p>
        </div>
        <div class="ncsc-upload-btn"> <a href="javascript:void(0);"><span>
          <input type="file" hidefocus="true" size="1" class="input-file" name="zb_img" id="storeLablePic" nc_type="change_zb_img"/>
          </span>
          <p><i class="icon-upload-alt"></i>图片上传</p>
          </a> </div>
        <p class="hint">本图片将显示在页面直播首页及直播列表页</p>
      </dd>
    </dl>
    <?php for($p=1;$p<=5;$p++){$m=$p-1;?>
    <dl>
      <dt>宣传视频<?php echo $p;?></dt>
      <dd>
        <input type="text" value="<?php echo $output['recode'][$m];?>" name="stream<?php echo $p;?>" class="text w400">
        <p class="hint">请将录制的视频压缩打包发邮件提交给我们审核！</p>
      </dd>
    </dl>
	<?php } ?>
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['store_goods_class_submit'];?>" /></label>
      </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
$(function(){
	$('.zb_bg').click(function(){
		$(this).parent().parent().children().removeClass('select');
		$(this).parent().addClass('select');		
		$(this).next().attr("checked","checked");
		});
});
</script>
