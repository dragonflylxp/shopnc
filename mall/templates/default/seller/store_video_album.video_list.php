<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu'); ?>
</div>
<div id="pictureFolder" class="ncsc-picture-folder">
  
  <table class="search-form">
    <tbody>
      <tr>
        <th></th>
        <td></td>
        <?php if(is_array($output['video_list']) && !empty($output['video_list'])){?>
        <th>排序方式</th>
        <td class="w100">
          <form name="select_sort" id="select_sort">
            <input type="hidden" name="con" value="store_video" />
            <input type="hidden" name="fun" value="album_video_list" />
            <input type="hidden" name="id" value="<?php echo $output['class_info']['video_class_id']?>" />
            <select name="sort" id="video_sort">
              <option value="0"  <?php if($_GET['sort'] == '0'){?>selected <?php }?> >按上传时间从晚到早</option>
              <option value="1"  <?php if($_GET['sort'] == '1'){?>selected <?php }?> >按上传时间从早到晚</option>
              <option value="2"  <?php if($_GET['sort'] == '2'){?>selected <?php }?> >按视频从大到小</option>
              <option value="3"  <?php if($_GET['sort'] == '3'){?>selected <?php }?> >按视频从小到大</option>
              <option value="4"  <?php if($_GET['sort'] == '4'){?>selected <?php }?> >按视频名降序</option>
              <option value="5"  <?php if($_GET['sort'] == '5'){?>selected <?php }?> >按视频名升序</option>
            </select>
          </form>
          </td><?php }?>
      </tr>
    </tbody>
  </table>
  <?php if(is_array($output['video_list']) && !empty($output['video_list'])){?>
  <form name="checkboxform" id="checkboxform" method="POST" action="">
    <div class="ncsc-picture-list">
      <ul>
        <?php $ii=0;?>
        <?php foreach($output['video_list'] as $v){?>
        <?php
        	$curpage = intval($_GET['curpage']) ? intval($_GET['curpage']) : 1;
        	$ii++;
        ?>
        <li>
          <dl>
            <dt>
                <div class="picture">
                  <video width="160" height="160" id="video_<?php echo $v['video_id'];?>" src="<?php echo goodsVideoPath($v['video_cover'],$_SESSION['store_id']);?>"></video></div>
              
              <input id="<?php echo $v['video_id'];?>" class="editInput1" readonly value="<?php echo $v['video_name']?>" ></dt>
            <dd class="date">
              <p><?php echo '上传时间'.$lang['nc_colon'].date("Y-m-d",$v['upload_time'])?></p>
            </dd>
            <dd class="buttons">
              <a href="javascript:void(0)" onclick="ajax_get_confirm('您确定进行该操作吗?注意：使用中的视频也将被删除','index.php?con=store_video&fun=album_video_del&id=<?php echo $v['video_id'];?>');"><i class="icon-trash"></i>删除视频</a> </dd>
          </dl>
        </li>
        <?php }?>
      </ul>
    </div>
  </form>
  <div class="pagination"><?php echo $output['show_page']; ?></div>
  <?php }else{?>
  <div class="warning-option"><i class="icon-warning-sign">&nbsp;</i><span><?php echo $lang['no_record'];?></span></div>
  <?php }?>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script>
$(function() {
    //鼠标触及区域li改变class
    $(".ncsc-picture-list ul li").hover(function() {
        $(this).addClass("hover");
    }, function() {
        $(this).removeClass("hover");
    });
    //凸显鼠标触及区域、其余区域半透明显示
    $(".ncsc-picture-list > ul > li").jfade({
        start_opacity:"1",
        high_opacity:"1",
        low_opacity:".5",
        timing:"200"
    });

    
});



$(function(){
	$("#video_sort").change(function(){
		$('#select_sort').submit();
	});
	$("#video_move").click(function(){
		if($('#batchClass').css('display') == 'none'){
			$('#batchClass').show();
		}else {
			$('#batchClass').hide();
		}
	});
});
</script>
