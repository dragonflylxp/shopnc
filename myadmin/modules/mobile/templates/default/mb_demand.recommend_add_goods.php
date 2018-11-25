<div class="div-goods-select">
    <table class="search-form">
        <tbody>
        <tr>
            <td>&nbsp;</td>
            <input type="hidden" name="store_id" value="<?php echo $_GET['store_id']; ?>">
            <th></th>
            <td class="w160">
                <select class="selected" name="qtype">
                    <option value="0">--请选择--&nbsp;&nbsp;</option>
                    <option <?php if($_GET['qtype'] == 'goods_commonid'){ ?> selected <?php } ?> value="goods_commonid">SPU&nbsp;&nbsp;</option>
                    <option <?php if($_GET['qtype'] == 'goods_name'){ ?> selected <?php } ?> value="goods_name">商品名称&nbsp;&nbsp;</option>
                    <option <?php if($_GET['qtype'] == 'gc_id'){ ?> selected <?php } ?> value="gc_id">分类ID&nbsp;&nbsp;</option>
                    <option <?php if($_GET['qtype'] == 'store_id'){ ?> selected <?php } ?> value="store_id">店铺ID&nbsp;&nbsp;</option>
                    <option <?php if($_GET['qtype'] == 'store_name'){ ?> selected <?php } ?> value="store_name">店铺名称&nbsp;&nbsp;</option>
                    <option <?php if($_GET['qtype'] == 'brand_id'){ ?> selected <?php } ?> value="brand_id">品牌ID&nbsp;&nbsp;</option>
                    <option <?php if($_GET['qtype'] == 'brand_name'){ ?> selected <?php } ?> value="brand_name">品牌名称&nbsp;&nbsp;</option>
                </select>
            </td>
            <td class="w160"><input type="text" name="b_search_keyword" class="text" value="<?php echo $_GET['keyword'];?>" /></td>
            <td class="tc w70"><a href="index.php?con=mb_demand&fun=recommend_add_goods" nctype="search_a" class="ncs-btn"><i class="icon-search"></i><?php echo $lang['nc_search'];?></a></td>
            <td class="w10"></td>
        </tr>
        </tbody>
    </table>
    <div class="search-result" style="width:739px;">
        <?php if(!empty($output['goods_common_list']) && is_array($output['goods_common_list'])){ ?>
            <ul class="goods-list" nctype="recommend_goods_add_tbody" style=" width:760px;">
                <?php foreach ($output['goods_common_list'] as $val){?>
                    <li nctype="<?php echo $val['goods_commonid'];?>">
                        <div class="goods-thumb"><img src="<?php echo cthumb($val['goods_image'], 240, $_SESSION['store_id']);?>" nctype="<?php echo $val['goods_image'];?>" /></div>
                        <dl class="goods-info">
                            <dt><a href="<?php echo urlShop('goods', 'index', array('goods_commonid' => $val['goods_commonid']))?>" target="_blank" title="<?php echo $lang['recommend_goods_name'].'/'.$lang['recommend_goods_code'];?><?php echo $val['goods_name'];?><?php  if($val['goods_serial'] != ''){ echo $val['goods_serial'];}?>"><?php echo $val['goods_name'];?></a></dt>
                            <dd>价格¥<?php echo ncPriceFormat($val['goods_price']);?></dd>
                            <dd>库存<?php echo $val['goods_storage'].'件';?></dd>
                        </dl>
                        <div data-param="{gid:<?php echo $val['goods_commonid'];?>,image:'<?php echo $val['goods_image'];?>',src:'<?php echo cthumb($val['goods_image'], 60, $_SESSION['store_id']);?>',gname:'<?php echo $val['goods_name'];?>',gprice:'<?php echo $val['goods_price'];?>',gstorang:'<?php echo $val['goods_storage'];?>'}"><a href="JavaScript:void(0);" class="ncbtn-mini ncbtn-mint" onclick="recommend_goods_add($(this))"><i class="icon-plus"></i>添加到推荐商品</a></div>
                    </li>
                <?php }?>
            </ul>
        <?php }else{?>
            <div class="norecord">
                <div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div>
            </div>
        <?php }?>
        <?php if(!empty($output['goods_common_list']) && is_array($output['goods_common_list'])){?>
            <div class="pagination"><?php echo $output['show_page']; ?></div>
        <?php }?>
    </div>
</div>
<script>
    $(function(){
        /* ajax添加商品  */
        $('.demo').unbind().ajaxContent({
            event:'click', //mouseover
            loaderType:"img",
            loadingMsg:ADMIN_TEMPLATES_URL+"/images/loading.gif",
            target:'#recommend_add_goods_ajaxContent'
        });

        $('a[nctype="search_a"]').click(function(){
            $(this).attr('href', $(this).attr('href')+ '&qtype='+$('select[name="qtype"]').val() + '&store_id='+$('input[name="store_id"]').val()+ '&' +$.param({'keyword':$('input[name="b_search_keyword"]').val()}));
            $('a[nctype="search_a"]').ajaxContent({
                event:'dblclick', //mouseover
                loaderType:'img',
                loadingMsg:'<?php echo ADMIN_TEMPLATES_URL;?>/images/loading.gif',
                target:'#recommend_add_goods_ajaxContent'
            });
            $(this).dblclick();
            return false;
        });


        // 验证商品是否已经被选择。
        O = $('input[nctype="goods_commonid"]');
        A = new Array();
        if(typeof(O) != 'undefined'){
            O.each(function(){
                A[$(this).val()] = $(this).val();
            });
        }
        T = $('ul[nctype="recommend_goods_add_tbody"] li');
        if(typeof(T) != 'undefined'){
            T.each(function(){
                if(typeof(A[$(this).attr('nctype')]) != 'undefined'){
                    $(this).children(':last').html('<a href="JavaScript:void(0);" onclick="recommend_operate_delete($(\'#recommend_tr_'+$(this).attr('nctype')+'\'), '+$(this).attr('nctype')+')" class="ncbtn-mini ncbtn-bittersweet"><i class="icon-ban-circle"></i>从推荐商品中移除</a>');
                }
            });
        }
    });

    /* 添加商品 */
    function recommend_goods_add(o){
        // 验证商品是否已经添加。
        var _bundlingtr = $('tbody[nctype="recommend_data"] tr:not(:first)');
        if(typeof(_bundlingtr) != 'undefined'){
            if(_bundlingtr.length == 10){
                alert('<?php printf('您已经添加了%d个，不能在继续添加商品。', 10);?>');
                return false;
            }
        }

        eval('var _data = ' + o.parent().attr('data-param'));
        if (_data.gstrong == 0) {
            alert('<?php echo '库存不足，不能推荐商品';?>');
            return false;
        }
        // 隐藏第一个tr
        $('tbody[nctype="recommend_data"]').children(':first').hide();
        // 插入数据
        $('<tr id="recommend_tr_' + _data.gid + '"></tr>')
            .append('<input type="hidden" nctype="goods_commonid" name="goods[g_' + _data.gid + '][gid]" value="' + _data.gid + '">')
            .append('<input type="hidden" nctype="goods_name" name="goods[g_' + _data.gid + '][gname]" value="' + _data.gname + '">')
            .append('<input type="hidden" nctype="goods_price" name="goods[g_' + _data.gid + '][gprice]" value="' + _data.gprice + '">')
            .append('<input type="hidden" nctype="goods_image" name="goods[g_' + _data.gid + '][image]" value="' + _data.image + '">')
            .append('<td class="w50 "><div class="shelf-state"><div class="pic-thumb"><img nctype="recommend_data_img" ncname="' + _data.image + '" src="' + _data.src + '" onload="javascript:DrawImage(this,60,60)"></span></div></div></td>')
            .append('<td class="tl"><dl class="goods-name"><dt style="width: 300px;">' + _data.gname + '</dt></dl></td>')
            .append('<td class="nscs-table-handle w90"><span><a href="javascript:void(0);" onclick="recommend_operate_delete($(\'#recommend_tr_' + _data.gid + '\'), ' + _data.gid + ')" class="btn-bittersweet"><i class="icon-ban-circle"></i><p>移除</p></a></span></td>')
            .fadeIn().appendTo('tbody[nctype="recommend_data"]');

        $('li[nctype="' + _data.gid + '"]').children(':last').html('<a href="JavaScript:void(0);" class="ncbtn-mini ncbtn-bittersweet" onclick="recommend_operate_delete($(\'#recommend_tr_' + _data.gid + '\'), ' + _data.gid + ')"><i class="icon-ban-circle"></i>从推荐商品中移除</a>');
    }

</script>