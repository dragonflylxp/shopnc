<?php defined('Inshopec') or exit('Access Invalid!');?>
<link href="<?php echo RESOURCE_SITE_URL; ?>/js/jcarousel/skins/tango/skin.css" rel="stylesheet" type="text/css">
<style type="text/css">
  img { border: 0px; }
  .ad-video-wrapper {width: 680px; height: 680px; float: left; border: solid 3px #E7E7E7; position: relative; z-index: 1; overflow: hidden;}
  .item{ list-style: none}
  #focus { width: 100%; height: 680px; clear: both; overflow: hidden;}
  #focus ul { width: 100%; height: 680px; float: left; position: absolute; clear: both; padding: 0px; margin: 0px; }
  #focus ul li { float: left; width: 680px; height: 680px; overflow: hidden; position: relative; padding: 0px; margin: 0px; }
  #focus .preNext { width: 50px; height: 680px; position: absolute; top: 0px; cursor: pointer; }
  #focus .pre { left: 0; background: url(templates/default/images/member/ad_prev.png) no-repeat left center; }
  #focus .next { right: 0; background: url(templates/default/images/member/ad_next.png) no-repeat right center; }
  #focus ul li .ad-video{width:100%; height: 100%; display: inline-flex}
  #focus ul li .ad-video video{ max-width:680px;max-height:680px;display: block; margin: 0 auto}
  .jcarousel-item dl a{ display: inline-flex; width: 100%; height: 100%}
  .jcarousel-item dl a video{ max-width: 96px; max-height: 96px; display: block; margin: 0 auto}
  .jcarousel-item{ padding: 10px; width: 96px !important; height: 96px !important;border: #f0f0f0 solid 1px}
  .jcarousel-skin-tango .jcarousel-next-horizontal{right:120px}
  .jcarousel-skin-tango .jcarousel-clip-horizontal { width: 890px !important; height: 120px !important;}
  .jcarousel-skin-tango .jcarousel-item { height: 96px !important;}
  .jcarousel-skin-tango .jcarousel-container-horizontal { width: 1000px !important;}

</style>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div id="pictureFolder" class="ncsc-picture-folder">
  <dl class="ncsc-album-intro">
    <dt class="albume-name"><a href="index.php?con=store_video&fun=album_video_list&id=<?php echo $output['class_info']['video_class_id']?>"><?php echo $output['class_info']['video_class_name']?></a></dt>
    <dd class="album-covers">
      <i class="icon-facetime-video"></i>
      </a></dd>
    <dd class="album-info"><?php echo $output['class_info']['video_class_des']?></dd>
  </dl>
  <div id="gallery" class="ad-gallery">

    <li class="item">
      <div class="nc-shop-goodslist" nc_type="goods_1">
        <div class=" jcarousel-skin-tango">
          <div class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;">
            <div class="jcarousel-clip jcarousel-clip-horizontal" style="position: relative;">
              <ul class="jcarousel-list jcarousel-list-horizontal" nc_type="jcarousel" style="overflow: hidden; position: relative; top: 10px; margin: 0px; padding: 0px; left: 30px; width:900px;">
                <?php foreach($output['video_list'] as $key => $v) {   ?>
                  <?php $k = $key + 1 ; ?>
                  <li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-<?php echo $k; ?> jcarousel-item-<?php echo $k; ?>-horizontal" jcarouselindex="<?php echo $k; ?>">
                    <dl>
                      <a href="" value="<?php echo $v['video_id'] ?>">
                        <video title="<?php echo $v['video_name']?>" src="<?php echo goodsVideoPath($v['video_cover'],$_SESSION['store_id']);?>" class="image0"/>
                        </video>
                      </a>
                    </dl>
                  </li>
                <?php }  ?>
              </ul>
            </div>
            <div class="jcarousel-prev jcarousel-prev-horizontal jcarousel-prev-disabled jcarousel-prev-disabled-horizontal" disabled="disabled" style="display: block;"></div>
            <div class="jcarousel-next jcarousel-next-horizontal" style="display: block;"></div></div></div>
      </div>
    </li>

    <div class="ad-image-date">
      <dt>视频名称</dt>
      <dd id="video_name"><?php echo $output['video_info']['video_name'];?></dd>
      <dt>视频属性</dt>
      <dd>
        <p id="upload_time"><b><?php echo '上传时间'.$lang['nc_colon'];?></b><?php echo date('Y-m-d',$output['video_info']['upload_time']);?></p>
        <p><b><?php echo '视频名称'.$lang['nc_colon'];?></b><?php echo str_cut($output['class_info']['video_class_name'],20);?></p>
        <p id="default_size"><b><?php echo '原图大小'.$lang['nc_colon'];?></b><?php echo $output['video_info']['video_size'];?>MB</p>
      </dd>
    </div>
    <div class="ad-video-wrapper">
      <div id="focus">
        <ul>
      <?php foreach ((array)$output['video_list'] as $v) {?>
        <li>
          <a class="ad-video" value="<?php echo $v['video_id'] ?>">
            <video controls title="<?php echo $v['video_name']?>" src="<?php echo goodsVideoPath($v['video_cover'], $_SESSION['store_id']);?>" class="image0" />
          </a>
        </li>
      <?php }?>
          </ul>
      </div>
    </div>

    <div class="clear"></div>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jcarousel/jquery.jcarousel.min.js"></script>
<script>
  $(function() {
    $('[nc_type="jcarousel"]').jcarousel({visible: 7});
    var sWidth = $("#focus").width();
    var len = $("#focus ul li").length;
    var index = 0;
    var btn = "<div class='btnBg'></div><div class='btn'>";
    for (var i = 0; i < len; i++) {
      btn += "<span></span>";
    }
    btn += "</div><div class='preNext pre'></div><div class='preNext next'></div>";
    $("#focus").append(btn);
    $("#focus .btnBg").css("opacity", 0);
    $("#focus .pre").click(function () {
      index -= 1;
      if (index == -1) { index = len - 1; }
      showVideoMessage(index);
    });
    $("#focus .next").click(function () {
      index += 1;
      if (index == len) { index = 0; }
      showVideoMessage(index);

    });
    $("#focus ul").css("width", sWidth * (len));

    function showVideoMessage(index) {
      var nowLeft = -index * sWidth;
      $("#focus ul").stop(true, false).animate({ "left": nowLeft });
      $("#focus .btn span").stop(true, false).animate({ "opacity": "0.4" }).eq(index).stop(true, false).animate({ "opacity": "1" });
      ajax_change_videomessage($(".ad-video > video").attr('src'));
    }
});

function ajax_change_videomessage(url){
	$.getJSON("<?php echo SHOP_SITE_URL; ?>/index.php?con=store_video&fun=ajax_change_videomessage", {'url':url}, function(data){
		$("#video_name").html(data.video_name);
		$("#upload_time").html('<b><?php echo '上传时间'.$lang['nc_colon'];?></b>'+data.upload_time);
		$("#default_size").html('<b><?php echo '原图大小'.$lang['nc_colon'];?></b>'+data.default_size+'MB');
	});
}
</script>