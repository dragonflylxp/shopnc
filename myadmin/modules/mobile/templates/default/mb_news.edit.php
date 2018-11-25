<?php defined('Inshopec') or exit('Access Invalid!');?>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script>
<script src="<?php echo ADMIN_RESOURCE_URL;?>/js/mb_news.js"></script>
<link href="<?php echo SHOP_SITE_URL; ?>/resource/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/seller_center.css" rel="stylesheet" type="text/css">
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/mb_news.css" rel="stylesheet" type="text/css">
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<div class="page item-publish">
    <div class="fixed-bar">
        <div class="item-title">
            <a class="back" href="index.php?con=mb_news&fun=index" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>资讯管理 - 编辑</h3>
                <h5>管理数据的新增、编辑、删除</h5>
            </div>
        </div>
    </div>
    <form method="post" enctype="multipart/form-data" id="post_form">
        <input type="hidden" name="form_submit" value="ok" />
        <div class="ncap-form-default ncsc-form-goods">
            <dl>
                <dt><i class="required">*</i>资讯名称：</dt>
                <dd>
                    <input name="news_name" id="news_name" type="text" class="text w400" value="<?php echo $output['news_array']['news_name']; ?>" />
                    <span></span>
                    <p class="hint"></p>
                </dd>
            </dl>

            <dl>
                <dt><i class="required">*</i>视频分类：</dt>
                <dd>
                    <select name="video_category">
                        <?php foreach($output['video_cate_list'] as $v){ ?>
                            <option <?php if($output['news_array']['cate_id'] == $v['cate_id'] ) { ?> selected="selected" <?php } ?> value="<?php echo $v['cate_id']; ?>"><?php echo $v['cate_name']; ?></option>
                        <?php } ?>
                    </select>
                    <span></span>
                    <p class="hint"></p>
                </dd>
            </dl>


            <dl>
                <dt><i class="required">*</i>资讯图：</dt>
                <dd>
                    <div class="input-file-show">
                    <span class="show">
                            <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_MOBILE.DS.'news'.DS.$output['news_array']['news_image']);?>">
                                <i class="fa fa-picture-o" onMouseOver="toolTip('<img width=150 height=80 src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_MOBILE.DS.'news'.DS.$output['news_array']['news_image']);?>>')" onMouseOut="toolTip()"/></i>
                            </a>
                        </span>
                        <span class="type-file-box">
                            <input name="news_image" type="file" class="type-file-file" id="news_image" size="30" hidefocus="true">
                            <span></span>
                        </span>
                    </div>
                    
                            
                    <p class="hint">上传资讯默认图，支持jpg、gif、png格式上传，建议使用
                    <font color="red">尺寸750x750像素以上的正方形图片</font></p>
                </dd>
            </dl>


            <dl>
                <dt>描述：</dt>
                <dd id="ncProductDetails">
                    <div id="panel-2" class="ui-tabs-panel ui-tabs-hide">
                        <div class="ncsc-mobile-editor">
                            <div class="pannel">
                                <div class="size-tip"><span nctype="img_count_tip">图片总数不得超过<em>20</em>张</span><i>|</i><span nctype="txt_count_tip">文字不得超过<em>5000</em>字</span></div>
                                <div class="control-panel" nctype="mobile_pannel">
                                    <?php if (!empty($output['news_array']['mb_body'])) {?>
                                        <?php foreach ($output['news_array']['mb_body'] as $val) {?>
                                            <?php if ($val['type'] == 'text') {?>
                                                <div class="module m-text">
                                                    <div class="tools"><a nctype="mp_up" href="javascript:void(0);">上移</a><a nctype="mp_down" href="javascript:void(0);">下移</a><a nctype="mp_edit" href="javascript:void(0);">编辑</a><a nctype="mp_del" href="javascript:void(0);">删除</a></div>
                                                    <div class="content">
                                                        <div class="text-div"><?php echo $val['value'];?></div>
                                                    </div>
                                                    <div class="cover"></div>
                                                </div>
                                            <?php }?>
                                            <?php if ($val['type'] == 'image') {?>
                                                <div class="module m-image">
                                                    <div class="tools"><a nctype="mp_up" href="javascript:void(0);">上移</a><a nctype="mp_down" href="javascript:void(0);">下移</a><a nctype="mp_rpl" href="javascript:void(0);">替换</a><a nctype="mp_del" href="javascript:void(0);">删除</a></div>
                                                    <div class="content">
                                                        <div class="image-div"><img src="<?php echo $val['value'];?>"></div>
                                                    </div>
                                                    <div class="cover"></div>
                                                </div>
                                            <?php }?>
                                            <?php if ($val['type'] == 'video') {?>
                                                <div class="module m-video">
                                                    <div class="tools"><a nctype="mp_up" href="javascript:void(0);">上移</a><a nctype="mp_down" href="javascript:void(0);">下移</a><a nctype="mp_del" href="javascript:void(0);">删除</a></div>
                                                    <div class="content">
                                                        <div class="image-div"><video width="300" height="200" src="<?php echo $val['value'];?>"></video></div>
                                                    </div>
                                                    <div class="cover"></div>
                                                </div>
                                            <?php }?>
                                        <?php }?>
                                    <?php }?>
                                </div>
                                <div class="add-btn">
                                    <ul class="btn-wrap">
                                        <li><a href="javascript:void(0);" nctype="mb_add_img"><i class="icon-picture"></i>
                                                <p>图片</p>
                                            </a></li>
                                        <li><a href="javascript:void(0);" nctype="mb_add_txt"><i class="icon-font"></i>
                                                <p>文字</p>
                                            </a></li>
                                        <li><a href="javascript:void(0);" nctype="mb_add_video"><i class="icon-facetime-video"></i>
                                                <p>视频</p>
                                            </a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="explain">
                                <dl>
                <dt>1、基本要求：</dt>
                <dd>（1）资讯详情总体大小：图片+文字，图片不超过20张，文字不超过5000字；</dd>
                <dd>建议：所有图片都是本资讯相关的图片。</dd>
            </dl>
            <dl>
                <dt>2、图片大小要求：</dt>
                <dd>（1）建议使用宽度480 ~ 620像素、高度小于等于960像素的图片；</dd>
                <dd>（2）格式为：JPG\JEPG\GIF\PNG；</dd>
                <dd>举例：可以上传一张宽度为480，高度为960像素，格式为JPG的图片。</dd>
            </dl>
            <dl>
                <dt>3、视频大小要求：</dt><br />
                <dd>（1）建议使用大小不超过20M的视频；</dd><br />
                <dd>（2）格式为：MP4；</dd>
            </dl>
            <dl>
                <dt>4、文字要求：</dt>
                <dd>（1）每次插入文字不能超过500个字，标点、特殊字符按照一个字计算；</dd>
                <dd>（2）请手动输入文字，不要复制粘贴网页上的文字，防止出现乱码；</dd>
                <dd>（3）以下特殊字符“<”、“>”、“"”、“'”、“\”会被替换为空。</dd>
                <dd>建议：不要添加太多的文字，这样看起来更清晰。</dd>
            </dl>
        </div>
</div>
<div class="ncsc-mobile-edit-area" nctype="mobile_editor_area">
    <div nctype="mea_img" class="ncsc-mea-img" style="display: none;"></div>
    <div class="ncsc-mea-text" nctype="mea_txt" style="display: none;">
        <p id="meat_content_count" class="text-tip"></p>
        <textarea class="textarea valid" nctype="meat_content"></textarea>
        <div class="button"><a class="ncbtn ncbtn-bluejeansjeansjeans" nctype="meat_submit" href="javascript:void(0);">确认</a><a class="ncbtn ml10" nctype="meat_cancel" href="javascript:void(0);">取消</a></div>
        <a class="text-close" nctype="meat_cancel" href="javascript:void(0);">X</a>
    </div>
    <div nctype="mea_video" class="ncsc-mea-video" style="display: none;"></div>
</div>
<input name="m_body" autocomplete="off" type="hidden" value='<?php echo $output['news_array']['mobile_body']; ?>'>

</div>
</dd>
</dl>

<dl>
    <dt>商品推荐：</dt>
    <dd>
        <p>
            <input id="recommend_goods" type="hidden" value="" name="recommend_goods">
            <span></span></p>
        <table class="ncsc-default-table mb15">
            <thead>
            <tr>
                <th class="w70"></th>
                <th class="tl">商品名称</th>
                <th class="w90">操作</th>
            </tr>
            </thead>
            <tbody nctype="recommend_data"  class="bd-line tip">
            <tr style="display:none;">
                <td colspan="20" class="norecord"><div class="no-promotion"><i class="zh"></i><span>推荐商品还未选择。</span></div></td>
            </tr>
            <?php if(!empty($output['recommend_goods_common_list'])){?>
            <?php foreach($output['recommend_goods_common_list'] as $goods_commonid => $val){ ?>
            <?php if (isset($output['recommend_goods_common_list'][$goods_commonid])) {?>
            <tr id="recommend_tr_<?php echo $val['goods_commonid']?>" class="off-shelf">
                <input type="hidden" value="<?php echo $val['goods_commonid'];?>" name="goods[<?php echo $val['goods_commonid'];?>][gid]" nctype="goods_commonid">
                
                <td class="w50"><div class="shelf-state"><div class="pic-thumb"><img src="<?php echo cthumb($output['recommend_goods_common_list'][$val['goods_commonid']]['goods_image'], 60, $_SESSION['store_id']);?>" ncname="<?php echo $output['recommend_goods_common_list'][$val['goods_commonid']]['goods_image'];?>" nctype="recommend_data_img">
                        </div></div>
                </td>
                <td class="tl"><dl class="goods-name">
    <dt style="width: 300px;"><?php echo $output['recommend_goods_common_list'][$val['goods_commonid']]['goods_name'];?></dt>
</dl></td>

    <td class="nscs-table-handle w90"><span><a onclick="recommend_operate_delete($('#recommend_tr_<?php echo $val['goods_commonid']?>'), <?php echo $val['goods_commonid']?>)" href="JavaScript:void(0);" class="btn-bittersweet"><i class="icon-ban-circle"></i>
                <p>移除</p>
            </a></span></td>
    </tr>
<?php }?>
<?php }?>
<?php }?>
</tbody>
</table>
<a id="recommend_add_goods" href="index.php?con=mb_news&fun=recommend_add_goods" class="ncbtn ncbtn-aqua">添加商品</a>
<div class="div-goods-select-box">
    <div id="recommend_add_goods_ajaxContent"></div>
    <a id="recommend_add_goods_delete" class="close" href="javascript:void(0);" style="display: none; right: -10px;">X</a></div>


<span></span>
<p class="hint"></p>
</dd>
</dl>

<div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
</div>
</form>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js"></script>
<script type="text/javascript">
    var ADMIN_SITE_URL = "<?php echo ADMIN_SITE_URL; ?>";
    var ADMIN_TEMPLATES_URL = "<?php echo ADMIN_TEMPLATES_URL; ?>";
    var DEFAULT_GOODS_IMAGE = "<?php echo thumb(array(), 240);?>";

    $(function(){

        //图片上传
        var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text'/><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
        $(textButton).insertBefore("#news_image");
        $("#news_image").change(function(){
            $("#textfield1").val($("#news_image").val());
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
                news_name : {
                    required    : true,
                    minlength   : 3,
                    maxlength   : 50,
                    remote   : {
                        url :ADMIN_SITE_URL+'/index.php?con=mb_news&fun=check_name',
                        type:'get',
                        data:{
                            news_name : function(){
                                return $('#news_name').val();
                            },
                            video_id : '<?php echo $output['video_id'] ?>'
                        }
                    }
                },
                image_path : {
                    required    : true
                },
                video_category : {
                    required    : true
                },
            },
            messages : {
                news_name  : {
                    required    : '<i class="fa fa-exclamation-circle"></i>资讯名称不能为空',
                    minlength   : '<i class="fa fa-exclamation-circle"></i>资讯名称长度至少3个字符，最长50个汉字',
                    maxlength   : '<i class="fa fa-exclamation-circle"></i>资讯名称长度至少3个字符，最长50个汉字',
                    remote   : '<i class="fa fa-exclamation-circle"></i>资讯已经存在，资讯库中资讯不能同名'
                },
                image_path : {
                    required    : '<i class="fa fa-exclamation-circle"></i>请设置资讯图片'
                },
                video_category : {
                    required    : '<i class="fa fa-exclamation-circle"></i>请设置视频分类'
                },
            }
        });

        check_recommend_data_length();




        /* ajax添加资讯  */
        $('#recommend_add_goods').ajaxContent({
            event:'click', //mouseover
            loaderType:"img",
            loadingMsg:ADMIN_TEMPLATES_URL+"/images/loading.gif",
            target:'#recommend_add_goods_ajaxContent'
        }).click(function(){
            $(this).hide();
            $('#recommend_add_goods_delete').show();
        });

        $('#recommend_add_goods_delete').click(function(){
            $(this).hide();
            $('#recommend_add_goods_ajaxContent').html('');
            $('#recommend_add_goods').show();
        });
        // 退拽效果
        $('tbody[nctype="recommend_data"]').sortable({ items: 'tr' });
        $('#goods_images').sortable({ items: 'li' });





    });

    /* 删除 */
    function recommend_operate_delete(o, id){
        o.remove();
        check_recommend_data_length();
        $('li[nctype="'+id+'"]').children(':last').html('<a href="JavaScript:void(0);" onclick="recommend_goods_add($(this))" class="ncbtn-mini ncbtn-mint"><i class="icon-plus"></i>添加到推荐商品</a>');
    }

    function check_recommend_data_length(){
        if ($('tbody[nctype="recommend_data"] tr').length == 1) {
            $('tbody[nctype="recommend_data"]').children(':first').show();
        }
    }



</script>

