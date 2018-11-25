<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
  <form id="form_setting" method="post" action="<?php echo urlShop('mb_store_decoration', 'decoration_setting_save');?>">
    <dl>
      <dt>启用手机店铺装修<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <label for="store_decoration_switch_on" class="mr30">
          <input id="store_decoration_switch_on" type="radio" class="radio vm mr5" name="mb_store_decoration_switch" value="1" <?php echo $output['mb_store_decoration_switch'] > 0?'checked':'';?>>
          是</label>
        <label for="store_decoration_switch_off">
          <input id="store_decoration_switch_off" type="radio" class="radio vm mr5" name="mb_store_decoration_switch" value="0" <?php echo $output['mb_store_decoration_switch'] == 0?'checked':'';?>>
          否</label>
        <p class="hint">选择是否使用手机店铺装修模板；<br/>
          如选择“是”，店铺首页背景、头部、导航以及上方区域都将根据店铺装修模板所设置的内容进行显示；<br/>
          如选择“否”根据 <a href="index.php?con=store_setting&fun=theme">“默认手机店铺主题”</a> 所选中的系统预设值风格进行显示。</p>
      </dd>
    </dl>

    <?php if ($output['is_own_shop']) { ?>
    <dl>
      <dt>商品页左侧显示<?php echo $lang['nc_colon']; ?></dt>
      <dd>
        <label>
          <input type="radio" name="left_bar_type" value="1"<?php if ($output['left_bar_type'] == 1) { ?>checked="checked"<?php } ?> /> 默认
        </label>
        <label>
          <input type="radio" name="left_bar_type" value="2"<?php if ($output['left_bar_type'] == 2) { ?>checked="checked"<?php } ?> />
          商城相关分类品牌商品推荐
        </label>
      </dd>
    </dl>
<?php } ?>
    <dl>
      <dt>店铺装修<?php echo $lang['nc_colon'];?></dt>
      <dd> <a href="<?php echo urlShop('mb_store_decoration', 'decoration_edit', array('decoration_id' => $output['decoration_id']));?>" class="ncbtn ncbtn-aqua mr5" target="_blank"><i class="icon-puzzle-piece"></i>装修页面</a>
        <p class="hint">点击“装修页面”按钮，在新窗口对店铺首页进行装修设计；<br/>
   </p>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border">
        <input id="btn_submit" type="button" class="submit" value="提交" />
      </label>
    </div>
  </form>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#btn_submit').on('click', function() {
            ajaxpost('form_setting', '', '', 'onerror');
        });

        $('#btn_build').on('click', function() {
            $.getJSON($(this).attr('href'), function(data) {
                if(typeof data.error == 'undefined') {
                    showSucc(data.message);
                } else {
                    showError(data.error);
                }
            });
            return false;
        });
    });
</script> 
