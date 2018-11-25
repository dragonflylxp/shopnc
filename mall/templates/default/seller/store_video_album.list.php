<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu'); ?>
</div>
<div id="pictureIndex" class="ncsc-picture-folder">
  </table>
  <?php if(!empty($output['video_class_info'])){?>
  <div class="ncsc-album">
    <ul>
      <?php foreach ($output['video_class_info'] as $v){?>
      <li class="hidden">
        <dl>
          <dt>
            <div class="covers"><a href="index.php?con=store_video&fun=album_video_list&id=<?php echo $v['video_class_id']?>">
              <i class=" icon-facetime-video"></i>
              </a></div>
            <h3 class="title"><a href="index.php?con=store_video&fun=album_video_list&id=<?php echo $v['video_class_id']?>"><?php echo $v['video_class_name'];?></a></h3>
          </dt>
          <dd class="date">共<?php echo $v['count']?>个</dd>
        </dl>
      </li>
      <?php }?>
    </ul>
  </div>
  <?php }else{?>
  <div class="warning-option"><i class="icon-warning-sign">&nbsp;</i><span><?php echo $lang['no_record'];?></span></div>
  <?php }?>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script type="text/javascript">

$(function() {
    //鼠标触及区域li改变class
    $(".ncsc-album ul li").hover(function() {
        $(this).addClass("hover");
    }, function() {
        $(this).removeClass("hover");
    });
    //凸显鼠标触及区域、其余区域半透明显示
    $(".ncsc-album > ul > li").jfade({
        start_opacity:"1",
        high_opacity:"1",
        low_opacity:".5",
        timing:"200"
    });

    

});

$(function(){
	$("#img_sort").change(function(){
		$('#select_sort').submit();
	});
});
</script>
