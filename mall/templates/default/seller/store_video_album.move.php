<div class="eject_con">
  <div id="warning"></div>
  <?php if(!empty($output['class_list'])){?>
  <form id="category_form" method="post" target="_parent" onsubmit="ajaxpost('category_form','','','onerror')" action="index.php?con=store_video&fun=album_video_move">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="id" value="<?php echo $output['id']?>" />
    <dl>
      <dt><?php echo '选择视频'.$lang['nc_colon'];?></dt>
      <dd>
        <select name="cid" class="w100">
          <?php foreach ($output['class_list'] as $v){?>
          <option value="<?php echo $v['video_class_id']?>" class="w100" ><?php echo $v['video_class_name']?></option>
          <?php }?>
        </select>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border">
        <input type="submit" class="submit" value="开始转移" />
      </label>
    </div>
  </form>
  <?php }else{?>
  <h2>只有一个视频，赶快去创建视频吧！</h2>
  <?php }?>
</div>
