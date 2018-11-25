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
            <a class="back" href="index.php?con=mb_demand&fun=index" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>点播管理</h3>
                <h5>管理数据的新增、编辑、删除</h5>
            </div>
        </div>
    </div>
    <form method="post" id="post_form">
        <input type="hidden" name="form_submit" value="ok" />
        <div class="ncap-form-default ncsc-form-goods">

        <dl>
            <dt>商品推荐：</dt>
            <dd>
                <p>
                    <input id="recommend_goods" type="hidden" value="" name="recommend_goods">
                    <span></span>
                </p>
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
                        <input type="hidden" value="<?php echo $val['goods_name'];?>" name="goods[<?php echo $val['goods_commonid'];?>][gname]" nctype="goods_name">
                        <input type="hidden" value="<?php echo $val['goods_price'];?>" name="goods[<?php echo $val['goods_commonid'];?>][gprice]" nctype="goods_price">
                        <input type="hidden" value="<?php echo $val['goods_image'];?>" name="goods[<?php echo $val['goods_commonid'];?>][image]" nctype="goods_image">
                        <td class="w50"><div class="shelf-state"><div class="pic-thumb"><img src="<?php echo cthumb($output['recommend_goods_common_list'][$val['goods_commonid']]['goods_image'], 60, $_SESSION['store_id']);?>" ncname="<?php echo $output['recommend_goods_common_list'][$val['goods_commonid']]['goods_image'];?>" nctype="recommend_data_img">
                                </div></div>
                        </td>
                        <td class="tl">
                            <dl class="goods-name">
                                <dt style="width: 300px;"><?php echo $output['recommend_goods_common_list'][$val['goods_commonid']]['goods_name'];?></dt>
                            </dl>
                        </td>

                        <td class="nscs-table-handle w90">
                            <span>
                                <a onclick="recommend_operate_delete($('#recommend_tr_<?php echo $val['goods_commonid']?>'), <?php echo $val['goods_commonid']?>)" href="JavaScript:void(0);" class="btn-bittersweet"><i class="icon-ban-circle"></i>
                                    <p>移除</p>
                                </a>
                            </span>
                        </td>
                    </tr>
                    <?php }?>
                    <?php }?>
                    <?php }?>
                    </tbody>
                </table>
            <a id="recommend_add_goods" href="index.php?con=mb_demand&fun=recommend_add_goods&store_id=<?php echo $output['store_id'];?>" class="ncbtn ncbtn-aqua">添加商品</a>
            <div class="div-goods-select-box">
                <div id="recommend_add_goods_ajaxContent"></div>
                <a id="recommend_add_goods_delete" class="close" href="javascript:void(0);" style="display: none; right: -10px;">X</a>
            </div>
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

        $("#submitBtn").click(function(){
            $("#post_form").submit();
        });

        check_recommend_data_length();

        /* ajax添加推荐商品  */
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

    /* 删除商品 */
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

