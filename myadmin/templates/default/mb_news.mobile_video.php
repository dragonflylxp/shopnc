<div class="ncsc-upload-btn"> <a href="javascript:void(0);"><span>
  <input type="file" hidefocus="true" size="1" class="input-file" name="add_video_album" id="mobile_add_video_album" multiple>
  </span>
  <p><i class="icon-upload-alt" data_type="0" nctype="mobile_add_video_album_i"></i>视频上传</p>
  </a> </div>
<a href="javascript:void(0);" nctype="meai_video_cancel" class="ncbtn mt5"><i class=" icon-circle-arrow-up"></i>关闭专辑</a>
<div class="goods-gallery add-step2">
    <?php if(!empty($output['video_list'])){?>
        <ul class="list">
            <?php foreach ($output['video_list'] as $v){?>
                <li onclick="insert_mobile_video('<?php echo goodsVideoPath($v['video_cover'],0);?>');"><a href="JavaScript:void(0);"><video width="100" height="100" src="<?php echo goodsVideoPath($v['video_cover']);?>" title='<?php echo $v['video_name']?>'/></video></a></li>
            <?php }?>
        </ul>
    <?php }else{?>
        <div class="warning-option"><i class="icon-warning-sign"></i><span>专辑中暂无视频</span></div>
    <?php }?>
    <div class="pagination"><?php echo $output['show_page']; ?></div>
</div>
<script type="text/javascript">
    $(function(){
        $('[nctype="mea_video"]').find('a[class="demo"]').click(function(){
            $('[nctype="mea_video"]').load($(this).attr('href'));
            return false;
        });
        $('#mobile_add_video_album').fileupload({
            dataType: 'json',
            url: ADMIN_SITE_URL+'/index.php?con=mb_news&fun=mobile_video_upload',
            formData: {name:'add_album'},
            add: function (e,data) {
                $('i[nctype="mobile_add_video_album_i"]').removeClass('icon-upload-alt').addClass('icon-spinner icon-spin icon-large').attr('data_type', parseInt($('i[nctype="mobile_add_video_album_i"]').attr('data_type'))+1);
                data.submit();
            },
            done: function (e,data) {
                var _counter = parseInt($('i[nctype="mobile_add_video_album_i"]').attr('data_type'));
                _counter -= 1;
                if (_counter == 0) {
                    $('i[nctype="mobile_add_video_album_i"]').removeClass('icon-spinner icon-spin icon-large').addClass('icon-upload-alt');
                    $('div[nctype="mea_video"]').show().load(ADMIN_SITE_URL+'/index.php?con=mb_news&fun=video_list&item=mobile');
                }
                $('i[nctype="mobile_add_video_album_i"]').attr('data_type', _counter);
            }
        });
    });
</script>