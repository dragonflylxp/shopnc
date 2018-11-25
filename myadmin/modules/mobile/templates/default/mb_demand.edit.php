<?php defined('Inshopec') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/seller_center.css" rel="stylesheet" type="text/css">
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<style>
    .ncsc-form-goods .btn {font: 12px/28px "microsoft yahei";  color: #434A54;  background-color: #F6F7FB;  width: 64px;  height: 28px;  padding: 0;  border: 0;  border-radius: 0;  cursor: pointer;}
    .ncsc-form-goods .btn:hover{ background-color: #36bc9b; color: #fff; }
    .search-result{margin-top:10px;}
    .ncsc-form-radio-list li{ display: block}
    .ncsc-form-goods textarea{ display: block}
</style>
<div class="page item-publish">
    <div class="fixed-bar">
        <div class="item-title">
            <a class="back" href="index.php?con=mb_demand&fun=index" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>点播管理 - 编辑</h3>
                <h5>管理数据的新增、编辑、删除</h5>
            </div>
        </div>
    </div>
    <form method="post" enctype="multipart/form-data" id="post_form">
        <input type="hidden" name="form_submit" value="ok" />
        <div class="ncap-form-default ncsc-form-goods">
            <dl>
                <dt><i class="required">*</i>推荐店铺：</dt>
                <dd class="datas">
                    <?php echo $output['store_info']['store_name'] ?>
                    <input type="hidden" name="store" value="<?php echo $output['demand_array']['store_id']; ?>" />
                    <a class="ncbtn">编辑</a>
                </dd>
                <dd class="search">
                    <select class="selected" name="qtype">
                        <option selected="selected" value="store_name">店铺名称</option>
                        <option value="member_name">店主账号</option>
                        <option value="seller_name">商家账号</option>
                    </select>
                    <input nctype="search_keyword" class="qsbox" type="text" placeholder="搜索相关数据..." name="search_keyword" size="30">
                    <input class="btn" nctype="btn_search_store" type="button" value="<?php echo $lang['nc_search'];?>">
                    <p class="hint">1、不输入名称直接搜索将显示所有开启状态的店铺。<br /> 2、留空表示不修改推荐店铺</p>
                    <div nctype="div_search_result" class="search-result"></div>
                    <input type="hidden" name="store" value="<?php echo $output['demand_array']['store_id']; ?>" />
                    <span></span>
                </dd>
            </dl>

            <dl>
                <dt><i class="required">*</i>视频分类：</dt>
                <dd>
                    <select name="video_category">
                        <?php foreach($output['video_cate_list'] as $v){ ?>
                            <option <?php if($output['demand_array']['cate_id'] == $v['cate_id']){ ?> selected="selected" <?php } ?> value="<?php echo $v['cate_id']; ?>"><?php echo $v['cate_name']; ?></option>
                        <?php } ?>
                    </select>
                    <span></span>
                    <p class="hint"></p>
                </dd>
            </dl>

            <dl>
                <dt><i class="required">*</i>推广位：</dt>
                <dd>
                    <ul class="ncsc-form-radio-list">
                        <li>
                            <input id="promote_0" nctype="promote" name="promote" class="radio" type="radio" <?php if(!empty($output['demand_array']['promote_video'])) {?> checked="checked" <?php } ?> value="0">
                            <label for="promote_0">6秒短视频/文字</label>
                            <div nctype="div_promote" <?php if(!empty($output['demand_array']['promote_video'])) {?> style="display:block;" <?php }else{ ?> style="display: none" <?php } ?>>
                                <div class="input-file-show">
                                    <span class="show">
                                        <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_MOBILE.DS.'demand'.DS.$output['demand_array']['promote_video']);?>">
                                            <i class="fa fa-file-video-o" onMouseOver="toolTip('<video width=150 height=80 src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_MOBILE.DS.'demand'.DS.$output['demand_array']['promote_video']);?>></video>')" onMouseOut="toolTip()"/></i>
                                        </a>
                                    </span>
                                    <span class="type-file-box">
                                        <input name="promote_video" type="file" class="type-file-file" id="promote_video" size="30" hidefocus="true">
                                        <span></span>
                                    </span>
                                </div>
                                <div class="clear"></div>
                                <div style="margin:10px 0;">
                                    <textarea name="promote_text"><?php echo $output['demand_array']['promote_text'] ?></textarea>
                                    <span></span>
                                </div>
                                <p class="hint">上传推广位视频；支持MP4格式上传，建议使用
                                    <font color="red">大小不超过5M的视频</font></p>
                            </div>

                        </li>
                        <li>
                            <input id="promote_1" nctype="promote" name="promote" class="radio" type="radio" <?php if(!empty($output['demand_array']['promote_image'])) {?> checked="checked" <?php } ?> value="1">
                            <label for="promote_1">图片</label>
                            <div nctype="div_promote" <?php if(!empty($output['demand_array']['promote_image'])) {?> style="display:block;" <?php }else{ ?> style="display: none" <?php } ?>>
                                <div class="input-file-show">
                                    <span class="show">
                                        <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_MOBILE.DS.'demand'.DS.$output['demand_array']['promote_image']);?>">
                                            <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_MOBILE.DS.'demand'.DS.$output['demand_array']['promote_image']);?>>')" onMouseOut="toolTip()"/></i>
                                        </a>
                                    </span>
                                    <span class="type-file-box">
                                        <input name="promote_image" type="file" value="<?php echo $output['demand_array']['promote_image']; ?>" class="type-file-file" id="promote_image" size="30" hidefocus="true">
                                        <span></span>
                                    </span>
                                </div>
                                <p class="hint">上传推广位默认图，支持jpg、gif、png格式上传或从图片空间中选择，建议使用
                                    <font color="red">尺寸750*440像素以上</font></p>
                            </div>

                        </li>
                    </ul>
                </dd>
            </dl>

            <dl id="li_video" <?php if(!empty($output['demand_array']['demand_video'])) {?> style="display:block;" <?php }else{ ?> style="display: none" <?php } ?>>
                <dt><i class="required">*</i>点播视频：</dt>
                <dd>
                    <div class="input-file-show">
                        <span class="show">
                            <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_MOBILE.DS.'demand'.DS.$output['demand_array']['demand_video']);?>">
                                <i class="fa fa-file-video-o" onMouseOver="toolTip('<video width=150 height=80 src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_MOBILE.DS.'demand'.DS.$output['demand_array']['demand_video']);?>></video>')" onMouseOut="toolTip()"/></i>
                            </a>
                        </span>
                        <span class="type-file-box">
                            <input name="demand_video" type="file" class="type-file-file" id="demand_video" size="30" hidefocus="true">
                            <span class="err2"></span>
                        </span>
                    </div>
                    <p class="hint">上传点播视频；支持MP4格式上传，建议使用
                                    <font color="red">大小不超过20M的视频</font></p>
                    <span class="err2"></span>
                    <p class="hint"></p>
                </dd>
            </dl>




            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
        </div>
    </form>
</div>
<script type="text/javascript">

    $(function(){

        <?php if($output['demand_array']['store_id']){ ?>
            $('.search').hide();
            $('.datas').show();
        <?php }else{ ?>
            $('.search').show();
            $('.datas').hide();
        <?php } ?>

        $('.ncbtn').on('click' ,function(){
            $('.search').show();
            $('.datas').hide();
        });


        //视频上传
        var textButton="<input type='text' name='textfield1' id='textfield1' class='type-file-text'/><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
        $(textButton).insertBefore("#promote_video");
        $("#promote_video").change(function(){
            $("#textfield1").val($("#promote_video").val());
        });

        $('#promote_video').change(function(){
            var video=$(this).val();
            var extStart=video.lastIndexOf(".");
            var ext=video.substring(extStart,video.length).toUpperCase();
            $(this).parent().find(".type-file-text").val(video);
            if(ext!=".mp4"&&ext!=".MP4"){
                alert("视频限于mp4格式");
                $(this).attr('value','');
                return false;
            }
        });



        //视频上传
        var textButton="<input type='text' name='textfield3' id='textfield3' class='type-file-text'/><input type='button' name='button' id='button3' value='选择上传...' class='type-file-button' />"
        $(textButton).insertBefore("#demand_video");
        $("#demand_video").change(function(){
            $("#textfield3").val($("#demand_video").val());
        });

        $('#demand_video').change(function(){
            var video=$(this).val();
            var extStart=video.lastIndexOf(".");
            var ext=video.substring(extStart,video.length).toUpperCase();
            $(this).parent().find(".type-file-text").val(video);
            if(ext!=".mp4"&&ext!=".MP4"){
                alert("视频限于mp4格式");
                $(this).attr('value','');
                return false;
            }
        });

        //图片上传
        var textButton="<input type='text' name='textfield2' id='textfield2' class='type-file-text'/><input type='button' name='button' id='button2' value='选择上传...' class='type-file-button' />"
        $(textButton).insertBefore("#promote_image");
        $("#promote_image").change(function(){
            $("#textfield2").val($("#promote_image").val());
        });

        // 搜索店铺
        $('input[nctype="btn_search_store"]').click(function(){
            _url = '<?php echo urlAdminMobile('mb_demand', 'select_recommend_store');?>';
            $('div[nctype="div_search_result"]').html('').load(_url + '&qtype='+$('.selected').val() + '&search_keyword='+$('input[nctype="search_keyword"]').val());
        });

        // 推广位显示隐藏
        $('input[nctype="promote"]').click(function(){
            $('input[nctype="promote"]').nextAll('div[nctype="div_promote"]').hide();
            $(this).nextAll('div[nctype="div_promote"]').show();
        });

        $('#promote_0').click(function() {
            if($('#promote_0').prop("checked") == true){
                $('#li_video').show();
            }
        });
        
        $('#promote_1').click(function() {
            if($('#promote_1').prop("checked") == true){
                $('#li_video').hide();
            }
        });

        $("#submitBtn").click(function(){
            if($("#post_form").valid()){
                $("#post_form").submit();
            }
        });


        $('#post_form').validate({
            errorPlacement: function(error, element){
                $(element).nextAll('span').append(error);
            },
            rules : {
                promote_text : {
                    required    : function() {if ($("#promote_0").prop("checked")) {return true;} else {return false;}},
                    maxlength   : 50
                },
                store : {
                    required    : true
                }
            },
            messages : {
                promote_text  : {
                    required    : '<i class="fa fa-exclamation-circle"></i>推广位文字不能为空',
                    maxlength   : '<i class="fa fa-exclamation-circle"></i>推广位文字长度最长50个汉字'
                },
                store : {
                    required : '<i class="fa fa-exclamation-circle"></i>推荐店铺不能为空',
                }
            }
        });


    });

</script>

