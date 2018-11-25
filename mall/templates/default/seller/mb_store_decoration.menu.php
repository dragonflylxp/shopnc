<?php defined('Inshopec') or exit('Access Invalid!');?>
<style>
.mb-sliders li { width: 225px; height: 168px; display: inline-block; padding: 3px; margin: 3px; border: 1px solid #ccc; }
.mb-sliders img { max-width: 100%; max-height: 100%; display: block; margin: 3px auto; }
.img-wrapper { width: 220px; height: 80px; overflow: hidden; }
</style>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="alert alert-block mt10">
  <ul>
    <li>1、可以在此处对手机店铺底部菜单设置，修改后的设置需要点击“保存修改”按钮进行保存</li>

    <li>2、选择开启为显示此处设置，关闭为默认显示http://</li>
    <li>3、菜单地址必须以 http:// 开头</li>
    <li>4、为空不显示</li>
  </ul>
</div>
<div class="ncsc-form-default">
  <form method="post" action="index.php?con=mb_store_decoration&fun=decoration_menu" enctype="multipart/form-data" id="my_store_form">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt>启用手机店铺底部菜单装修<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <label for="store_decoration_switch_on" class="mr30">
          <input id="store_decoration_switch_on" type="radio" class="radio vm mr5" name="mb_store_menu" value="1" <?php echo $output['mb_store_menu'] > 0?'checked':'';?>>
          是</label>
        <label for="store_decoration_switch_off">
          <input id="store_decoration_switch_off" type="radio" class="radio vm mr5" name="mb_store_menu" value="0" <?php echo $output['mb_store_menu'] == 0?'checked':'';?>>
          否</label>
        <p class="hint">选择是否使用手机店铺底部菜单装修模板；<br/>
          如选择“是”，店铺首页底部菜单区域将根此处设置的内容进行显示；<br/>
          如选择“否”根据 <a href="index.php?con=store_setting&fun=theme">“默认手机店铺底部菜单主题”</a> 所选中的系统预设值风格进行显示。</p>
      </dd>
    </dl>
 <?php for ($k = 0; $k <= 4; $k++) {  ?>    
<dl><input type="hidden" name="mb_sliders_sort[]" value="<?php echo $k; ?>" />
      <dt>菜单<?php echo $k?>：菜单名称</dt>
      <dd>
        <input class="w200 text" name="name_<?php echo $k?>" type="text" id="name" value=<?php echo $output['decoration_banner'][$k]['name']; ?>>
      </dd>
      <dt>菜单<?php echo $k?>：菜单地址</dt>
      <dd>
        <input class="w200 text" name="links_<?php echo $k?>" type="text" id="links" value=<?php echo $output['decoration_banner'][$k]['links']?>>
      </dd>      
    </dl>
<?php } ?>
    <div class="bottom">
      <label class="submit-border">
        <input type="submit" class="submit" value="保存修改" />
      </label>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.flexslider-min.js"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js" charset="utf-8"></script> 
<script>
$(function() {
    $('.flexslider').flexslider();

    $(".sortable").sortable();

    var DEFAULT_GOODS_IMAGE = '<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON; ?>/default_goods_image.gif';
    var LOADING_IMAGE = '<?php echo SHOP_TEMPLATES_URL; ?>/images/loading.gif';

    $('input.input-file').change(function() {
        var id = this.id;

        $('div[nctype="'+id+'"]').find('i,img').remove().end()
            .prepend('<img nctype="'+id+'" src="'+LOADING_IMAGE+'">');

        $.ajaxFileUpload({
            url: 'index.php?con=store_setting&fun=store_mb_sliders',
            secureuri: false,
            fileElementId: id,
            dataType: 'json',
            data: {id: id},
            success: function(data, status) {
                if (data.error) {
                    alert(data.error);
                    $('img[nctype="'+id+'"]').attr('src', DEFAULT_GOODS_IMAGE);
                    return;
                }
                $('img[nctype="'+id+'"]').attr('src', data.uploadedUrl);
            },
            error: function(data, status, e) {
                alert(e);
            }
        });
    });

    $("a[data-slider-drop]").click(function() {
        var id = $(this).attr('data-slider-drop');
        var $this = $(this);

        $.getJSON('index.php?con=store_setting&fun=store_mb_sliders_drop', {id: id}, function(d) {
            if (!d.success) {
                alert(d.error);
                return;
            }
            $this.parents('div.picture').find('img,i').remove().end()
                .prepend('<i class="icon-picture"></i>');
        });
    });


});
</script> 
