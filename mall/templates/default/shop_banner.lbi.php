<?php if ($this->_var['temp'] == 'shop_banner'): ?>
<div class="tishi">
	<div class="tishi_info">
    	<?php if (! $this->_var['uploadImage']): ?>
        <p class="first">注意：1、弹出框鼠标移到头部可以拖动，以防笔记本小屏幕内容展示不全；</p>
        <?php else: ?>
        <p class="first">注意：1、请按照模板展示模式图片上面的尺寸来设置广告；</p>
        <p>2、弹出框鼠标移到头部可以拖动，以防笔记本小屏幕内容展示不全；</p>
        <i class="icon"></i>
        <?php endif; ?>
    </div>
</div>
<div class="tab">
	<ul class="clearfix">
    	<li class="current">内容设置</li>
        <?php if ($this->_var['uploadImage'] == 0): ?><li>显示设置</li><?php endif; ?>
    </ul>
</div>
<div class="modal-body">
    <div class="body_info" id="banner_info">
    	<div class="ps_table">
        <table id="addpictable" class="table">
            <thead>
                <tr>
                    <th>图片</th>
                        <?php if ($this->_var['uploadImage'] != 1): ?>
                        <th>图片链接</th>
                        <th class="center">排序</th>
                        <?php if ($this->_var['mode'] == 'lunbo'): ?>
                        <th class="center">背景颜色</th>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($this->_var['titleup'] == 1): ?>
                        <th class="center">主标题</th>
                        <th class="center">副标题</th>
                        <?php endif; ?>
                        <th class="center">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php $_from = $this->_var['banner_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['item']):
?>
                    <tr>
                         <td>
                                <div class="img">
                                    <span><img src="<?php echo $this->_var['item']['pic_src']; ?>"></span>
                                    <input type="hidden" name="pic_src[]" value="<?php echo $this->_var['item']['pic_src']; ?>"/>
                                </div>
                            </td>
                            <?php if ($this->_var['uploadImage'] != 1): ?>
                            <td>
                                <input type="text" name="link[]" value="<?php echo $this->_var['item']['link']; ?>" class="form-control">
                            </td>
                            <td class="center">
                                <input type="text" value="<?php echo $this->_var['item']['sort']; ?>" name="sort[]" class="form-control small">
                            </td>
                            <?php if ($this->_var['mode'] == 'lunbo'): ?>
                            <td class="center">
                                <input type="text" value="<?php echo $this->_var['item']['bg_color']; ?>" name="bg_color[]" class="form-control small">
                            </td>
                            <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($this->_var['titleup'] == 1): ?>
                            <td class="center">
                                <input type="text" value="<?php echo $this->_var['item']['title']; ?>" name="title[]" class="form-control small">
                            </td>
                            <td class="center">
                                <input type="text" value="<?php echo $this->_var['item']['subtitle']; ?>" name="subtitle[]" class="form-control small">
                            </td>
                            <?php endif; ?>
                            <td class="center">
                                <a href="javascript:;" class="pic_del del">删除</a>
                            </td>
                    </tr>
                <?php endforeach; else: ?>
                	<tr class="notic">
                    	<td colspan="5">点击下列图片空间图片可添加图片或点击上传图片按钮上传新图片</td>
                    </tr>    
                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </tbody>
        </table>
        </div>
        <div class="images_space">
            <div class="goods_gallery mt20">
                <form  action="" id="gallery_pic" method="post"  enctype="multipart/form-data"  runat="server" >
                    <div class="nav clearfix">
                        <div class="f_l">
                            <div class="imitate_select select_w220" id="album_id">
                                <div class="cite">请选择相册...</div>
                                <ul style="display: none;" class="ps-container" ectype="album_list_check">
                                    <li><a href="javascript:;" data-value="0" class="ftx-01">请选择...</a></li>
                                    <?php $_from = $this->_var['album_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['list']):
?>
                                    <li><a href="javascript:;" data-value="<?php echo $this->_var['list']['album_id']; ?>" class="ftx-01"><?php echo $this->_var['list']['album_mame']; ?></a></li>
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </ul>
                                <input name="album_id" type="hidden" value="<?php echo $this->_var['album_id']; ?>" id="album_id_val">
                            </div>
                            <div class="imitate_select select_w220" id="sort_name">
                                <div class="cite">请选择相册...</div>
                                <ul style="display: none;" class="ps-container">
                                    <li><a href="javascript:;" data-value="2" class="ftx-01">按上传时间从晚到早</a></li>
                                    <li><a href="javascript:;" data-value="1" class="ftx-01">按上传时间从早到晚</a></li>
                                    <li><a href="javascript:;" data-value="3" class="ftx-01">按图片从小到大</a></li>
                                    <li><a href="javascript:;" data-value="4" class="ftx-01">按图片从大到小</a></li>
                                    <li><a href="javascript:;" data-value="5" class="ftx-01">按图片名升序</a></li>
                                    <li><a href="javascript:;" data-value="6" class="ftx-01">按图片名降序</a></li>
                                </ul>
                                <input name="sort_name" type="hidden" value="2" id="sort_name_val">
                            </div>
                        </div>
                        <div class="f_r"><i class="glyphicon glyphicon-cloud-upload"></i>上传图片<input name="file" type="file"></div>
                        <div class="f_r mr5 add_album" ectype='add_album'><i class="glyphicon"></i>添加相册</div>
                    </div>
                </form>
                <div class="table_list" ectype='pic_list'>
                    <div class="gallery_album" data-act="get_albun_pic" data-inid="pic_list" data-url='get_ajax_content.php' data-where="sort_name=<?php echo $this->_var['filter']['sort_name']; ?>&album_id=<?php echo $this->_var['filter']['album_id']; ?>">
                        <ul class="ga-images-ul">
                            <?php $_from = $this->_var['pic_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'pic_list');if (count($_from)):
    foreach ($_from AS $this->_var['pic_list']):
?>
                            <li><a href="javascript:;" onclick="addpic('<?php echo $this->_var['pic_list']['pic_file']; ?>',this)"><img src="<?php echo $this->_var['pic_list']['pic_file']; ?>"><span class="pixel"><?php echo $this->_var['pic_list']['pic_spec']; ?></span></a></li>
                            <?php endforeach; else: ?>
                            <li class="notic">暂无图片</li>
                            <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </ul>
                        <div class="clear"></div>
                        <?php echo $this->fetch('library/lib_page.lbi'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="body_info" style="display:none;">
    	<div class="control_list">
        	<div class="control_item">
                <div class="control_text">图片高度：</div>
                <div class="control_value">
                	<?php if ($this->_var['mode'] == 'lunbo'): ?>
                	<input type="text" name="picHeight" value="<?php if ($this->_var['spec_attr']['picHeight']): ?><?php echo $this->_var['spec_attr']['picHeight']; ?><?php else: ?>600<?php endif; ?>" class="shop_text" autocomplete="off" /><span>px</span><span>请设置在300-600px这个之间</span>
                    <?php elseif ($this->_var['mode'] == 'advImg1'): ?>
                    <input type="text" name="picHeight" value="<?php if ($this->_var['spec_attr']['picHeight']): ?><?php echo $this->_var['spec_attr']['picHeight']; ?><?php else: ?>400<?php endif; ?>" class="shop_text" autocomplete="off" /><span>px</span><span>根据实际需求自行填写高度，默认为400</span>
                    <?php elseif ($this->_var['mode'] == 'advImg2'): ?>
                    <input type="text" name="picHeight" value="<?php if ($this->_var['spec_attr']['picHeight']): ?><?php echo $this->_var['spec_attr']['picHeight']; ?><?php else: ?>650<?php endif; ?>" class="shop_text" autocomplete="off" /><span>px</span><span>根据实际需求自行填写高度，默认为650</span>
                    <?php elseif ($this->_var['mode'] == 'advImg3'): ?>
                    <input type="text" name="picHeight" value="<?php if ($this->_var['spec_attr']['picHeight']): ?><?php echo $this->_var['spec_attr']['picHeight']; ?><?php else: ?>380<?php endif; ?>" class="shop_text" autocomplete="off" /><span>px</span><span>根据实际需求自行填写高度，默认为380</span>
                    <?php elseif ($this->_var['mode'] == 'advImg4'): ?>
                    <input type="text" name="picHeight" value="<?php if ($this->_var['spec_attr']['picHeight']): ?><?php echo $this->_var['spec_attr']['picHeight']; ?><?php else: ?>250<?php endif; ?>" class="shop_text" autocomplete="off" /><span>px</span><span>根据实际需求自行填写高度，默认为250</span>
                	<?php endif; ?>
                </div>
            </div>
            <?php if ($this->_var['mode'] == 'lunbo' || $this->_var['mode'] == 'advImg1'): ?>
            <div class="control_item">
                <div class="control_text">切换效果：</div>
                <div class="control_value">
                	<div class="checkbox_items">
                        <div class="checkbox_item">
                            <input type="radio" name="slide_type" value="fold" class="ui-radio" id="shade" <?php if ($this->_var['spec_attr']['slide_type'] != 'roll'): ?>checked<?php endif; ?> >
                            <label class="ui-radio-label" for="shade">渐变</label>
                        </div>
                        <div class="checkbox_item">
                            <input type="radio" name="slide_type" value="roll" class="ui-radio" id="roll" <?php if ($this->_var['spec_attr']['slide_type'] == 'roll'): ?>checked<?php endif; ?>>
                            <label class="ui-radio-label" for="roll">滚动</label>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($this->_var['mode'] == 'advImg4'): ?>
            <div class="control_item">
                <div class="control_text">展示方式：</div>
                <div class="control_value">
                	<div class="itemsLayout line-item3" data-line="row3">
                    	<div class="itemsLayoutShot <?php if ($this->_var['spec_attr']['itemsLayout'] == 'row3'): ?>dtselected<?php endif; ?>"><a href="javascript:void(0);"><span></span></a></div>
                        <div class="dd">展示3个广告位<br>（建议尺寸394*394）</div>
                    </div>
                    <div class="itemsLayout line-item4" data-line="row4">
                    	<div class="itemsLayoutShot <?php if ($this->_var['spec_attr']['itemsLayout'] == 'row4'): ?>dtselected<?php endif; ?>"><a href="javascript:void(0);"><span></span></a></div>
                        <div class="dd">展示4个广告位<br>（建议尺寸292*350）</div>
                    </div>
                    <div class="itemsLayout line-item5" data-line="row5">
                    	<div class="itemsLayoutShot <?php if ($this->_var['spec_attr']['itemsLayout'] == 'row5'): ?>dtselected<?php endif; ?>"><a href="javascript:void(0);"><span></span></a></div>
                        <div class="dd">展示5个广告位<br>（建议尺寸232*337）</div>
                    </div>
                    <div class="itemsLayout line-item6" data-line="row6">
                    	<div class="itemsLayoutShot <?php if ($this->_var['spec_attr']['itemsLayout'] == 'row6' || $this->_var['spec_attr']['itemsLayout'] == ''): ?>dtselected<?php endif; ?>"><a href="javascript:void(0);"><span></span></a></div>
                        <div class="dd">展示6个广告位<br>（建议尺寸190*250）</div>
                    </div>
                </div>
            </div>
            <input name="itemsLayout" type="hidden" value="<?php if ($this->_var['spec_attr']['itemsLayout']): ?><?php echo $this->_var['spec_attr']['itemsLayout']; ?><?php else: ?>row6<?php endif; ?>"/>
            <?php endif; ?>
            <?php if ($this->_var['mode'] == 'advImg3'): ?>
            <div class="control_item">
                <div class="control_text">展示方式：</div>
                <div class="control_value">
                	<div class="itemsLayout line-item-left-right" data-line="left-right">
                    	<div class="itemsLayoutShot <?php if ($this->_var['spec_attr']['itemsLayout'] == 'left-right' || $this->_var['spec_attr']['itemsLayout'] == ''): ?>dtselected<?php endif; ?>"><a href="javascript:void(0);"><span></span></a></div>
                        <div class="dd">左小图右大图</div>
                    </div>
                    <div class="itemsLayout line-item-right-left" data-line="right-left">
                    	<div class="itemsLayoutShot <?php if ($this->_var['spec_attr']['itemsLayout'] == 'right-left'): ?>dtselected<?php endif; ?>"><a href="javascript:void(0);"><span></span></a></div>
                        <div class="dd">左大图右小图</div>
                    </div>
                </div>
            </div>
            <input name="itemsLayout" type="hidden" value="<?php if ($this->_var['spec_attr']['itemsLayout']): ?><?php echo $this->_var['spec_attr']['itemsLayout']; ?><?php else: ?>left-right<?php endif; ?>"/>
            <?php endif; ?>
            <div class="control_item">
                <div class="control_text">是否新窗口打开：</div>
                <div class="control_value">
                	<div class="checkbox_items">
                        <div class="checkbox_item">
                            <input type="radio" name="target" value="_blank" class="ui-radio" id="blank" <?php if ($this->_var['spec_attr']['target'] != '_self'): ?>checked<?php endif; ?> >
                            <label class="ui-radio-label" for="blank">是</label>
                        </div>
                        <div class="checkbox_item">
                            <input type="radio" name="target" value="_self" class="ui-radio" id="self" <?php if ($this->_var['spec_attr']['target'] == '_self'): ?>checked<?php endif; ?>>
                            <label class="ui-radio-label" for="self">否</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>      
<script type="text/javascript">
    imitate_select();
    $.divselect("#album_id","#album_id_val",function(obj){
        var val = obj.attr("data-value");
        changedpic(val,obj);
    });
    $.divselect("#sort_name","#sort_name_val",function(obj){
        changedpic(0,obj);
    });
	$("input[name='file']").change(function(){
		var album_id = $("input[name='album_id']").val();
		if(album_id > 0){
			var actionUrl = "index.php?con=visual_editing&fun=upload_pic";
			$("#gallery_pic").ajaxSubmit({
				type: "POST",
				dataType: "json",
				url: actionUrl,
				data: {"action": "TemporaryImage"},
				success: function (data) {
						if (data.error == "1") {
							alert(data.massege);
						}else{
							changedpic(0);
						}
				},
				async: true
			});
		}else{
			alert("请选择相册");
		}
	});
	//function changedpic(val,obj){
	//	var album_id = 0;
	//	if(val > 0){
	//		album_id = val;
	//	}else{
	//		album_id = $("input[name='album_id']").val();
	//	}
		//var sort_name = $("input[name='sort_name']").val();
		
		//Ajax.call('get_ajax_content.php?is_ajax=1&act=get_albun_pic', "sort_name="+sort_name+"&album_id="+album_id, function(data){
			//$("[ectype='pic_list']").html(data.content);
			//$("[ectype='pic_list']").perfectScrollbar("destroy");
			//$("[ectype='pic_list']").perfectScrollbar();
			
			//if(obj){
			//	var id = $(obj).parents(".pb").attr("id");
			//	pbct("#" + id);
			//}else{
			//	pbct();
			//}
		//} , 'POST', 'JSON');
	
	//} 
	function addpic(src,obj){
		var i = 0;
		var mode = "<?php echo $this->_var['mode']; ?>";
		var length = "<?php echo $this->_var['pic_number']; ?>";
                var uploadImage = "<?php echo $this->_var['uploadImage']; ?>";
		var titleup = "<?php echo $this->_var['titleup']; ?>";
		var id = $(obj).parents(".pb").attr("id");
		$("#addpictable").find('tr').each(function(){
			i++
		});
		
		if($("#addpictable").find('tr.notic').length>0){
			i-=1;
		}

		if( length< i  && length != 0){
			alert("此模块最多可添加"+length+"个图片");
		}else{
                        if(mode != "lunbo"){
				if(uploadImage == 1){
					var html = '<tr><td><div class="img"><span><img src="'+src+'"></span><input type="hidden" name="pic_src[]" value="'+src+'"/></div></td><td class="center"><a href="javascript:;" class="pic_del del">删除</a></td></tr>';
				}else{
					var title = '';
					if(titleup == 1){
						title = '<td class="center"><input type="text" value="" name="title[]" class="form-control small"></td><td class="center"> <input type="text" value="" name="subtitle[]" class="form-control small"></td>';
					}
					var html = '<tr><td><div class="img"><span><img src="'+src+'"></span><input type="hidden" name="pic_src[]" value="'+src+'"/></div></td><td><input type="text" name="link[]" class="form-control"></td><td class="center"><input type="text" value="1" name="sort[]" class="form-control small"></td>' + title + '<td class="center"><a href="javascript:;" class="pic_del del">删除</a></td></tr>';
				}
			}else{
				var html = '<tr><td><div class="img"><span><img src="'+src+'"></span><input type="hidden" name="pic_src[]" value="'+src+'"/></div></td><td><input type="text" name="link[]" class="form-control"></td><td class="center"><input type="text" value="1" name="sort[]" class="form-control small"></td><td class="center"><input type="text" value="" name="bg_color[]" class="form-control small"></td><td class="center"><a href="javascript:;" class="pic_del del">删除</a></td></tr>';
			}
			
			if($("#addpictable").find(".notic").length>0){
				$("#addpictable").find(".notic").remove();
			}
			$("#addpictable").find("tbody").prepend(html);
		}
		
		pbct("#"+id);
	}
    
	//添加相册
    //$(document).on("click",".add_album",function(){
    //    Ajax.call('index.php?&con=visual_editing&is_ajax=1&fun=add_albun_pic', '', add_albumResponse , 'POST', 'JSON');
    //});
 
    //function add_albumResponse(data){
    //    var content = data.content;
	//	pb({
	//		id: "add_albun_piccomtent",
	//		title: "图片编辑器",
	//		width: 950,
	//		content: content,
	//		ok_title: "确定",
	//		drag: true,
	//		foot: true,
	//		cl_cBtn: false,
	//		onOk: function () {
	//			var parents = $("#add_albun_pic");
	//			var required = parents.find("*[ectype='required']");
//
	//			if(validation(required) == true){
	//				var actionUrl = "get_ajax_content.php?act=add_albun_pic";
	//				$("#add_albun_pic").ajaxSubmit({
	//					type: "POST",
	//					dataType: "json",
	//					url: actionUrl,
	//					data: {"action": "TemporaryImage"},
	//					success: function (data) {
	//						if (data.error == "0") {
	//							alert(data.content);
	//						}else{
	//							$("[ectype='album_list_check']").html(data.content)
	//							$("input[name='album_id']").val(data.pic_id);
     //                                                           imitate_select();
	//							changedpic(data.pic_id);
	//						}
	//						return true;
	//					},
	//					async: true
	//				});
	//				return true;
	//			}else{
	//				return false;
	//			}
	//		}
	//	});
    //}
    //select下拉默认值赋值
            function imitate_select(){
                $('.imitate_select').each(function()
                {
                        var sel_this = $(this)
                        var val = sel_this.children('input[type=hidden]').val();
                        sel_this.find('a').each(function(){
                                if($(this).attr('data-value') == val){
                                        sel_this.children('.cite').html($(this).html());
                                }
                        })
                });
            }
</script>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'goods_info'): ?>
<div class="tishi">
	<div class="tishi_info">
        <p class="first">注意：1、弹出框鼠标移到头部可以拖动，以防笔记本小屏幕内容展示不全；</p>
    </div>
</div>
<?php if ($this->_var['goods_type'] != 1): ?>
<div class="tab">
	<ul class="clearfix">
    	<li class="current">内容设置</li>
        <li>显示设置</li>
    </ul>
</div>
<?php endif; ?>

<div class="modal-body">
	<div class="body_info floor_info">
        <div class="search">
            <div class="select_box mr10">
<!--                <?php if ($this->_var['select_category_html']): ?><?php echo $this->_var['select_category_html']; ?><?php endif; ?>
                <input type="hidden" name="cat_id" id="cat_id" value="<?php echo empty($this->_var['cat_id']) ? '0' : $this->_var['cat_id']; ?>"/>-->
                <!--<div class="categorySelect">
                    <div class="selection">
                        <input type="text" name="category_name" id="category_name" class="text w250 mr0 valid" value="<?php if ($this->_var['parent_category']): ?><?php echo $this->_var['parent_category']; ?><?php else: ?>顶级分类<?php endif; ?>" autocomplete="off" readonly data-filter="cat_name" />
                        <input type="hidden" name="cat_id" id="category_id" ectype="goods_cat_dialog" value="<?php echo empty($this->_var['cat_id']) ? '0' : $this->_var['cat_id']; ?>" data-filter="cat_id" />
                    </div>
                    <div class="select-container" style="display:none;">
                        <?php echo $this->fetch('library/filter_category.lbi'); ?>
                    </div>
                </div>-->
            </div>
            <div class="search_con"><input width="20" name="keyword_brand" type="text" id="keyword_brand" placeholder="搜索店铺商品" class="text text_6"></div>
            <a href="javascript:void(0);" class="btn fl" ectype="changedgoods">确定</a>
            <div class="checkobx-item">
                <input type="checkbox" name="is_selected" id="is_selected" class="ui-checkbox" onclick="checkd_selected(this)"/>
                <label class="ui-label" for="is_selected">已选择商品</label>
            </div>
        </div>
        <div class="table_list" ectype='goods_list'>
            <div class="gallery_album" data-act="changedgoods" data-goods='1' data-inid="goods_list" data-url='get_ajax_content.php' data-where="cat_id=<?php echo $this->_var['filter']['cat_id']; ?>&search_type=<?php echo $this->_var['filter']['search_type']; ?>&sort_order=<?php echo $this->_var['filter']['sort_order']; ?>&keyword=<?php echo $this->_var['filter']['keyword']; ?>&type=1">
                <ul class="ga-goods-ul" id="goods_list">
                    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['gl'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['gl']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['gl']['iteration']++;
?>
                    <li class="on">
                        <div class="img"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"  width="50" height="50"></div>
                       <div class="text">
                            <p class="name"><?php echo $this->_var['goods']['goods_name']; ?></p>
                            <p class="price">
                                <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                                <?php echo $this->_var['goods']['promote_price']; ?>
                                <?php else: ?>
                                <?php echo $this->_var['goods']['shop_price']; ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="choose"><a href="javascript:void(0);" class="on" onclick="selected_goods(this,'<?php echo $this->_var['goods']['goods_id']; ?>')"><i class="iconfont icon-gou"></i>已选择</a></div>
                    </li>
                    <?php endforeach; else: ?>
                    <li class="notic">请先搜索商品</li>
                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="body_info floor_info" style="display:none;">
        <div class="control_list">
        	<div class="control_item">
                <div class="control_text">是否显示标题：</div>
                <div class="control_value">
                	<div class="checkbox_items">
                        <div class="checkbox_item">
                        	<input type="radio" name="is_title" value="1" class="ui-radio" id="is_title_1" <?php if ($this->_var['arr']['is_title'] != 0): ?> checked <?php endif; ?>>
                        	<label class="ui-radio-label" for="is_title_1">是</label>
                        </div>
                        <div class="checkbox_item">
                        	<input type="radio" name="is_title" value="0" class="ui-radio" id="is_title_0" <?php if ($this->_var['arr']['is_title'] == 0): ?> checked <?php endif; ?>>
                            <label class="ui-radio-label" for="is_title_0">否</label>
                        </div>    
                    </div>
                </div>
            </div>
            <div class="tit_head"<?php if ($this->_var['arr']['is_title'] == 0): ?> style="display:none;"<?php endif; ?>>
                <div class="control_item">
                    <div class="control_text">楼层名称：</div>
                    <div class="control_value"><input name="cat_name" type="text" class="text text2" size="25" value="<?php echo $this->_var['arr']['cat_name']; ?>"></div>
                </div>
                <div class="control_item">
                    <div class="control_text">楼层描述：</div>
                    <div class="control_value"><input name="cat_desc" type="text" class="text" size="25" value="<?php echo $this->_var['arr']['cat_desc']; ?>"></div>
                </div>
                <div class="control_item">
                    <div class="control_text">文字显示：</div>
                    <div class="control_value">
                        <select class="select" name="align">
                            <option value="left" <?php if ($this->_var['arr']['align'] == 'left'): ?>checked <?php endif; ?>>居左</option>
                            <option value="center" <?php if ($this->_var['arr']['align'] == 'center'): ?>checked <?php endif; ?>>居中</option>
                            <option value="regiht" <?php if ($this->_var['arr']['align'] == 'regiht'): ?>checked <?php endif; ?>>居右</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="control_item">
                <div class="control_text">展示方式：</div>
                <div class="control_value">
                	<div class="itemsLayout line-item3" data-line="row3">
                    	<div class="itemsLayoutShot <?php if ($this->_var['arr']['itemsLayout'] == 'row3'): ?>dtselected<?php endif; ?>"><a href="javascript:void(0);"><span></span></a></div>
                        <div class="dd">一行展示3个商品</div>
                    </div>
                    <div class="itemsLayout line-item4" data-line="row4">
                    	<div class="itemsLayoutShot <?php if ($this->_var['arr']['itemsLayout'] == 'row4'): ?>dtselected<?php endif; ?>"><a href="javascript:void(0);"><span></span></a></div>
                        <div class="dd">一行展示4个商品</div>
                    </div>
                    <div class="itemsLayout line-item5" data-line="row5">
                    	<div class="itemsLayoutShot <?php if ($this->_var['arr']['itemsLayout'] == 'row5'): ?>dtselected<?php endif; ?>"><a href="javascript:void(0);"><span></span></a></div>
                        <div class="dd">一行展示5个商品</div>
                    </div>
                </div>
            </div>
            <input name="itemsLayout" type="hidden" value="<?php echo $this->_var['arr']['itemsLayout']; ?>"/>
			<input type="hidden" name="search_type" value="<?php echo $this->_var['search_type']; ?>"/>
        </div>    
    </div>
</div>
<input type="hidden" name="goods_ids" value="<?php echo $this->_var['arr']['goods_ids']; ?>"/>
<script language="JavaScript">

    ajaxchangedgoods(1);
    function checkd_selected(){
        var is_selected =$("input[name='is_selected']").is(':checked');
        var type = 1;
        if(is_selected == true){
            $(".icon-gou").parents('li').show();
			$(".icon-dsc-plus").parents('li').hide();
                        type = 0;
        }else{
            $(".icon-gou,.icon-dsc-plus").parents('li').show();
        }
        ajaxchangedgoods(type);
    }
    $(document).on("click","button[ectype='changedgoods']",function(){
        ajaxchangedgoods(1);
    })

	
	function ajaxchangedgoods(type){
        var cat_id = $("[ectype='goods_cat_dialog']").val();
        var keyword = $("input[name='keyword_brand']").val();
        var goods_ids = $("input[name='goods_ids']").val();
		var search_type = $("input[name='search_type']").val();
		$("[ectype='goods_list']").html("<i class='icon-spin'><img src='themes//images/visual/load.gif' width='30' height='30'></i>");
		
		function ajax(){
			Ajax.call('index.php?con=visual_editing&fun=changedgoods&is_ajax=1', "cat_id="+cat_id+"&keyword="+keyword+"&goods_ids="+goods_ids+"&type="+type + "&resetRrl=1&search_type=" + search_type, function(data){
				$("[ectype='goods_list']").html(data.content);
				
				$("*[ectype='goods_list']").perfectScrollbar("destroy");
				$("*[ectype='goods_list']").perfectScrollbar();
			} , 'POST', 'JSON');
		}
		
		setTimeout(function(){ajax()},300);
    }

	//选中商品
	function selected_goods(obj,goods_id){
		var obj = $(obj);
		var arr = '';
		var goods_ids = $("input[name='goods_ids']").val();
	   
		if(obj.hasClass("on")){
			obj.removeClass("on");
			obj.html('<i class="iconfont icon-dsc-plus"></i>选择');
			arr = goods_ids.split(',');
			for(var i =0;i<arr.length;i++){
				if(arr[i] == goods_id){
					 arr.splice(i,1);
				}
			}
		}else{
			$(obj).addClass('on');
			$(obj).html('<i class="iconfont icon-gou"></i>已选择');
			if(goods_ids){
					arr = goods_ids + ','+goods_id;
			}else{
					arr = goods_id;
			}
		}
		$("input[name='goods_ids']").val(arr);
	}
	
	$("input[name='is_title']").on("click",function(){
		var val = $(this).val();
		if(val == 1){
			$(this).parents(".control_list").find(".tit_head").show();
		}else{
			$(this).parents(".control_list").find(".tit_head").hide();
		}
	});
</script>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'header'): ?>
<div class="tishi">
	<div class="tishi_info">
	<p class="first">注意：1、弹出框鼠标移到头部可以拖动，以防笔记本小屏幕内容展示不全;</p>
    <p>2、自定义编辑器里面的编辑模板，样式可以写在编辑器里面;</p>
    </div>
</div>
<div class="tab">
	<ul class="clearfix">
    	<li class="current">内容设置</li>
    </ul>
</div>
<div class="modal-body">
	<div class="control_list">
        <div class="control_item">
            <div class="control_text">设置类型：</div>
            <div class="control_value">
            	<label><input type="radio" name="header_type" value="defalt_type" class="checkbox" <?php if ($this->_var['content']['header_type'] == 'defalt_type'): ?>checked<?php endif; ?>><span>默认类型</span></label>
                <label><input type="radio" name="header_type" value="custom_type" class="checkbox" <?php if ($this->_var['content']['header_type'] == 'custom_type'): ?>checked<?php endif; ?>><span>自定义类型</span></label>
            </div>
        </div>
        <div class="defalt_type" <?php if ($this->_var['content']['header_type'] != 'defalt_type'): ?>style="display:none;"<?php endif; ?>>
            <div class="control_item">
                <div class="control_text">头部图片：</div>
                <form  action="" id="fileForm" method="post"  enctype="multipart/form-data"  runat="server" >
                    <div class="control_value relative">
                        <a href="javascript:void(0);" class="uploader-button">
                        	<span class="btn-text">选择文件</span>
                        	<div class="file-input-wrapper"><input type="file" name="headerFile" value="上传图片" id="headerFile"  class="file-header-img"></div>
                        </a>
                        <div class="preview-box"><input name="fileimg" type="hidden" value="<?php if ($this->_var['content']['headerbg_img']): ?><?php echo $this->_var['content']['headerbg_img']; ?><?php else: ?>../data/gallery_album/ksh_bg.jpg<?php endif; ?>"/><img id="headerbg_img" src="<?php if ($this->_var['content']['headerbg_img']): ?><?php echo $this->_var['content']['headerbg_img']; ?><?php else: ?>../data/gallery_album/ksh_bg.jpg<?php endif; ?>" height="86"></div>
                    </div>
                </form>
            </div>
        </div>
        <div class="custom_type" <?php if ($this->_var['content']['header_type'] == 'defalt_type'): ?>style="display:none;"<?php endif; ?>>
        	<div class="control_item">
                <div class="control_text">自定义内容：</div>
                <div class="control_value over">
                    <?php echo $this->_var['FCKeditor']; ?>
                </div>
            </div>
        </div>
        <div class="control_item">
            <div class="control_text">高度：</div>
            <div class="control_value"><input type="text" name="picHeight" value="<?php if ($this->_var['content']['picHeight']): ?><?php echo $this->_var['content']['picHeight']; ?><?php else: ?>128<?php endif; ?>" class="shop_text" autocomplete="off" /><span>px</span><span>请设置在120-150px这个之间</span></div>
        </div>
	</div>        
</div>
<script type="text/javascript">
    var suffix = "<?php echo $this->_var['content']['suffix']; ?>";
	$.upload_file("input[name='headerFile']","visual_editing.php?act=header_bg&name=headerFile&suffix="+suffix+"&topic_type="+topic_type,"#headerbg_img");
	
	$(document).on("click","input[name='header_type']",function(){
		if($(this).val() == "defalt_type"){
			$(".defalt_type").show().siblings(".custom_type").hide();
			pbct();
		}else{
			$(".custom_type").show().siblings(".defalt_type").hide();
			pbct();
		}
	});
</script>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'navigator'): ?>
<div class="tishi">
	<div class="tishi_info">
	<p class="first">注意：1、弹出框鼠标移到头部可以拖动，以防笔记本小屏幕内容展示不全;&nbsp;&nbsp;&nbsp;&nbsp;2、切换是否显示，点击对应的小图标即可完成切换</p>
    </div>
</div>
<div class="tab">
	<ul class="clearfix">
    	<li class="current">内容设置</li>
        <?php if ($this->_var['topic_type'] != 'topic_type'): ?><li>显示设置</li><?php endif; ?>
    </ul>
</div>
<div class="modal-body">
	<div class="body_info">
    	<div class="ov_list">
        <form action="" id="navInsert" method="post"  enctype="multipart/form-data"  runat="server" >
    	<table class="table" data-table="navtable">
        	<thead>
                <tr>
                    <th width="30%">分类名称</th>
                    <th width="25%">链接地址</th>
                    <th width="15%" class="center">排序</th>
                    <?php if ($this->_var['topic_type'] != 'topic_type'): ?>
                    <th width="20%" class="center">是否显示</th>
                    <?php endif; ?>
                    <th width="15%" class="center">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php $_from = $this->_var['navigator']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'navigator');if (count($_from)):
    foreach ($_from AS $this->_var['navigator']):
?>
            	<tr>
                	<td><input type="text" <?php if ($this->_var['topic_type'] != 'topic_type'): ?>onchange = "edit_nav(this.value ,'<?php echo $this->_var['navigator']['id']; ?>','edit_navname')"<?php endif; ?> name="navname[]" value="<?php echo $this->_var['navigator']['name']; ?>"></td>
                    <td><input type="text" <?php if ($this->_var['topic_type'] != 'topic_type'): ?>onchange = "edit_nav(this.value ,'<?php echo $this->_var['navigator']['id']; ?>','edit_navurl')"<?php endif; ?> name="navurl[]" value="<?php echo $this->_var['navigator']['url']; ?>"></td>
                    <td class="center"><input type="text" <?php if ($this->_var['topic_type'] != 'topic_type'): ?>onchange = "edit_nav(this.value ,'<?php echo $this->_var['navigator']['id']; ?>','edit_navvieworder')"<?php endif; ?> class="small" name="navvieworder[]" value="<?php echo $this->_var['navigator']['vieworder']; ?>"></td>
                    <?php if ($this->_var['topic_type'] != 'topic_type'): ?>
                    <td class="center" id="nav_<?php echo $this->_var['navigator']['id']; ?>"><img onclick = "edit_nav('<?php echo $this->_var['navigator']['ifshow']; ?>' ,'<?php echo $this->_var['navigator']['id']; ?>','edit_ifshow','1')" src="<?php if ($this->_var['navigator']['ifshow'] == 1): ?>images/yes.gif<?php else: ?>images/no.gif<?php endif; ?>"/></td>
                    <?php endif; ?>
                    <td class="center"><a href="javascript:void(0);" <?php if ($this->_var['topic_type'] != 'topic_type'): ?>onclick="remove_nav('<?php echo $this->_var['navigator']['id']; ?>')"<?php else: ?>onclick="remove_topicnav(this)"<?php endif; ?> class="pic_del del">删除</a></td>
                </tr>
                <?php endforeach; else: ?>
                <tr class="notic">
                    <td colspan="4">当前没有自定义商品分类，点击下面添加新分类添加</td>
                </tr>
            	<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </tbody>
        </table>
            </form>
        </div>
        <div class="addCatagory" ectype="addCatagory">
        	<select class="select" id="catagory_type" <?php if ($this->_var['topic_type'] == 'topic_type'): ?>style="display:none;" <?php endif; ?>>
                <option value="0">请选择</option>
                <option value="1">自定义分类</option>
                <option value="2">店铺分类</option>
            </select>
            <input type="text" name="custom_catagory" class="text" <?php if ($this->_var['topic_type'] != 'topic_type'): ?>style="display:none;" <?php endif; ?>/>
            <select class="select" id="sys_catagory" style="display:none;">
                <?php $_from = $this->_var['sysmain']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['val']):
?>
                    <option value='<?php echo $this->_var['key']; ?>|<?php echo $this->_var['val']['cat_id']; ?>|<?php echo $this->_var['val']['cat_name']; ?>|<?php echo $this->_var['val']['url']; ?>' id="" url="<?php echo $this->_var['val']['url']; ?>"><?php echo $this->_var['val']['cat_name']; ?></option>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </select>
            <a href="javascript:void(0);" class="<?php if ($this->_var['topic_type'] == 'topic_type'): ?>btn<?php else: ?>btn btn_disabled<?php endif; ?>" ectype="<?php if ($this->_var['topic_type'] == 'topic_type'): ?>topic_btn<?php else: ?>store_btn<?php endif; ?>">添加新分类</a>
        </div>
    </div>
    <div class="body_info" style="display:none;">
    	<div class="control_list">
            <div class="control_item">
                <div class="control_text">导航栏背景色：</div>
                <div class="control_value">
                    <input type="text" name="navColor" class="navColor" value="<?php if ($this->_var['attr']['navColor']): ?><?php echo $this->_var['attr']['navColor']; ?><?php else: ?>#000<?php endif; ?>">
                </div>
            </div>
            <div class="control_item">
                <div class="control_text">是否新窗口打开：</div>
                <div class="control_value">
                	<label><input type="radio" name="target" value="_blank" class="checkbox" <?php if ($this->_var['attr']['target'] == '_blank'): ?> checked<?php endif; ?>><span>是</span></label>
                    <label><input type="radio" name="target" value="_self" class="checkbox" <?php if ($this->_var['attr']['target'] == '_self'): ?> checked<?php endif; ?>><span>否</span></label>
                </div>
            </div>
            <div class="control_item">
                <div class="control_text">文字显示：</div>
                <div class="control_value">
                	<select class="select" name="align">
                        <option value="left" <?php if ($this->_var['attr']['align'] == 'left'): ?>selected="selected" <?php endif; ?>>居左</option>
                        <option value="center" <?php if ($this->_var['attr']['align'] == 'center'): ?>selected="selected" <?php endif; ?>>居中</option>
                        <option value="regiht" <?php if ($this->_var['attr']['align'] == 'regiht'): ?>selected="selected" <?php endif; ?>>居右</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	var navColor = $(".navColor").val();
	$(".navColor").spectrum({
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		showInput: true,
		color: navColor,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		palette: [
			["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
			"rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
			["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
			"rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"], 
			["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)", 
			"rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)", 
			"rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)", 
			"rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)", 
			"rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)", 
			"rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
			"rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
			"rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
			"rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)", 
			"rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
		]
	});
	$(".sp-choose").click(function(){
		var val = $(this).parents(".sp-picker-container").find(".sp-input").val();
		$(".navColor").val(val);
	});
        function remove_topicnav(obj){
            $(obj).parents('tr').remove();
        }
        function edit_nav(val,id,act,type){
            if(id > 0){
                if(type == 0){
                    Ajax.call('index.php?con=visual_editing&fun='+act+'&is_ajax=1&act='+act, "val=" +encodeURIComponent(val) + "&id=" + id , edit_navnameResponse, 'POST', 'JSON');
                }else{
                    Ajax.call('index.php?con=visual_editing&fun='+act+'&is_ajax=1&act='+act, "val=" +encodeURIComponent(val) + "&id=" + id , edit_ifshowResponse, 'POST', 'JSON');
                }
                
            }else{
                alert("导航不存在");
            }
        }

        function edit_navnameResponse(data){
            if(data.error == 0){
                alert(data.content);
            }
        }
        function edit_ifshowResponse(data){
            var obj = $("#nav_"+data.id);
            if(data.error == 0){
                alert(data.content);
            }else{
                if(data.content == 0){
                    obj.html(data.content)
                }else{
                    obj.html(data.content)
                }
            }
        }
        function remove_nav(id){
            if(id > 0){
                Ajax.call('get_ajax_content.php?is_ajax=1&act=remove_nav', "id=" + id , remove_navResponse, 'POST', 'JSON');
            }else{
                alert("导航不存在");
            }
        }

        function remove_navResponse(data){
            if(data.error == 0){
                alert(data.content);
            }
        }
       
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'custom'): ?>
<div class="tishi">
	<div class="tishi_info">
        <p class="first">注意：1、弹出框鼠标移到头部可以拖动，以防笔记本小屏幕内容展示不全；</p>
    </div>
</div>
<div class="modal-body"><?php echo $this->_var['FCKeditor']; ?></div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'template_information'): ?>
<form  action="visual_editing.php?act=edit_information" id="information" method="post"  enctype="multipart/form-data"  runat="server" >
<div class="modal-body">
    <div class="body_info">
    	<div class="control_list">
            <div class="control_item">
                <div class="control_text">模板名称：</div>
                <div class="control_value">
                	<input type="text" class="text" name="name"  value="<?php echo htmlspecialchars($this->_var['template']['name']); ?>"  autocomplete="off" />
                </div>
            </div>
            <div class="control_item">
                <div class="control_text">版本：</div>
                <div class="control_value">
                	<input type="text" class="text" name="version"  value="<?php echo htmlspecialchars($this->_var['template']['version']); ?>"  autocomplete="off" />
                </div>
            </div>
            <div class="control_item">
                <div class="control_text">作者：</div>
                <div class="control_value">
                	<input type="text" class="text" name="author"  value="<?php echo htmlspecialchars($this->_var['template']['author']); ?>"  autocomplete="off" />
                </div>
            </div>
            <div class="control_item">
                <div class="control_text">作者链接：</div>
                <div class="control_value">
                	<input type="text" class="text" name="author_url"  value="<?php echo htmlspecialchars($this->_var['template']['author_uri']); ?>"  autocomplete="off" />
                </div>
            </div>
            <div class="control_item">
                <div class="control_text">模板封面：</div>
                <div class="control_value">
                        <input type="file" class="type-file-file" id="ten_file" name="ten_file" data-state="imgfile" size="30" hidefocus="true" value="" />
                        <?php if ($this->_var['template']['screenshot']): ?>
                        <img src=<?php echo $this->_var['template']['screenshot']; ?> width="25" height="25"/>
                        <?php endif; ?>
                        <span class="hint" style="color:#1b9ad5;">请上传265*388的图片，防止图片变型</span>
                </div>
            </div>
            <div class="control_item">
                <div class="control_text">模板大图：</div>
                <div class="control_value">
                        <input type="file" class="type-file-file" id="big_file" name="big_file" data-state="imgfile" size="30" hidefocus="true" value="" />
                        <?php if ($this->_var['template']['template']): ?>
                        <img src=<?php echo $this->_var['template']['template']; ?> width="25" height="25"/>
                        <?php endif; ?>
                </div>
            </div>
            <div class="control_item">
                <div class="control_text">描述：</div>
                <div class="control_value">
                	<textarea class="textarea" name="description"><?php echo htmlspecialchars($this->_var['template']['desc']); ?></textarea>
                </div>
            </div>
            <input type="hidden" name="tem" value="<?php echo $this->_var['code']; ?>" />
            <input type="hidden" name="id" value="<?php echo $this->_var['ru_id']; ?>" />
        </div>
    </div>
</div>
</form>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'homeFloor'): ?>

<form action="" id="<?php echo $this->_var['mode']; ?>Insert" method="post" enctype="multipart/form-data" runat="server" >
    <div class="tab">
        <ul class="clearfix">
            <li class="current">楼层分类设置</li>
            <li>楼层广告设置</li>
            <?php if ($this->_var['mode'] != 'homeFloorFive'): ?>
            <li>楼层品牌设置</li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="modal-body hfloor">
        <div class="body_info">
            <div class="control_list">
                <div class="control_item">
                    <?php if ($this->_var['hierarchy'] != '2'): ?>
                    <div class="control_item">
                        <div class="control_text"><em class="red">*</em>模块名称：</div>
                        <div class="control_value"><input type="text" value="<?php echo $this->_var['lift']; ?>" class="text" name="lift" autocomplete="off" maxlength="2" placeholder="请填写电梯层标题" ectype="required" data-msg="请填写电梯层标题" /><div class="notic">模块名称是显示首页左侧浮动导航；只能2个中文字符，如：店铺</div></div>
                    </div>
                    <?php endif; ?>
                    <div class="control_item">
                        <div class="control_text">标题：</div>
                        <div class="control_value"><input type="text" value="<?php echo $this->_var['spec_attr']['floor_title']; ?>" class="text" name="floor_title" autocomplete="off" placeholder="楼层标题"  data-msg="楼层标题" /><div class="notic">楼层标题，不填则默认为选择的楼层分类</div></div>
                    </div>
                    <div class="control_text lh30">楼层分类：</div>
                    <div class="control_value">
                        <div class="imitate_select select_w220" id="cat_id">
                            <div class="cite">请选择</div>
                            <ul>
                                <li><a href="javascript:void(0);" data-value="">请选择...</a></li>
                                <?php $_from = $this->_var['cat_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                <li<?php if ($this->_var['list']['cat_id'] == $this->_var['spec_attr']['Selected']): ?> class="current"<?php endif; ?>><a href="javascript:void(0);" data-value="<?php echo $this->_var['list']['cat_id']; ?>"><?php echo $this->_var['list']['cat_name']; ?></a></li>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                            <input name="" value="<?php echo $this->_var['spec_attr']['Selected']; ?>" type="hidden" id='cat_id_val'/>
                        </div>
                        <div class="cat_floor">
                            <?php if ($this->_var['spec_attr']['catChild']): ?>
                            <div class="imitate_select select_w220" id="cat_id1">
                                <div class="cite">请选择</div>
                                <ul>
                                    <li><a href="javascript:void(0);" data-value=""><?php echo $this->_var['lang']['select_please']; ?></a></li>
                                    <?php $_from = $this->_var['spec_attr']['catChild']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                    <li <?php if ($this->_var['list']['cat_id'] == $this->_var['spec_attr']['cat_id']): ?> class="current"<?php endif; ?>><a href="javascript:void(0);" data-value="<?php echo $this->_var['list']['cat_id']; ?>"><?php echo $this->_var['list']['cat_name']; ?></a></li>
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </ul>
                                <input name="" value="<?php echo $this->_var['spec_attr']['cat_id']; ?>" type="hidden" id='cat_id_val1'/>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($this->_var['mode'] == 'homeFloorFour' || $this->_var['mode'] == 'homeFloorFive' || $this->_var['mode'] == 'homeFloorSeven' || $this->_var['mode'] == 'homeFloorSix'): ?>
                        <input name="top_goods" type="hidden" value='<?php echo $this->_var['spec_attr']['top_goods']; ?>'/>
                        <a href="javascript:void(0);" class="hdle" ectype="setupGoods" data-top='1' data-goodsnumber="<?php if ($this->_var['mode'] == 'homeFloorSeven'): ?>6<?php else: ?>12<?php endif; ?>">设置商品</a>
                        <?php endif; ?>
                    </div>
                    <input name="Floorcat_id" type="hidden" value='<?php echo $this->_var['spec_attr']['cat_id']; ?>'/>
                </div>
                <div class="control_item">
                    <div class="control_text lh30">楼层二级分类：</div>
                    <div class="control_value" data-catnumber="<?php if ($this->_var['mode'] == 'homeFloorModule'): ?>2<?php else: ?>6<?php endif; ?>" data-goodsnumber="<?php if ($this->_var['mode'] == 'homeFloorModule'): ?>4<?php else: ?>12<?php endif; ?>">
                        <?php if ($this->_var['spec_attr']['juniorCat']): ?>
                            <?php $_from = $this->_var['spec_attr']['catInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'value');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['value']):
        $this->_foreach['name']['iteration']++;
?>
                            <div class="item" ectype="item">
                                <div class="imitate_select select_w220" ectype="iselectErji">
                                    <div class="cite erji" ectype="tit">请选择</div>
                                    <ul>
                                        <li><a href="javascript:void(0);" data-value="">请选择..</a></li>
                                        <?php $_from = $this->_var['spec_attr']['juniorCat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                        <li<?php if ($this->_var['list']['cat_id'] == $this->_var['value']['cat_id']): ?> class="current"<?php endif; ?>><a href="javascript:void(0);" data-value="<?php echo $this->_var['list']['cat_id']; ?>"><?php echo $this->_var['list']['cat_name']; ?></a></li>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </ul>
                                    <input name="cateValue[]" type="hidden" value="<?php echo $this->_var['value']['cat_id']; ?>" ectype="cateValue" />
                                </div>
                                <?php if (($this->_foreach['name']['iteration'] <= 1)): ?>
                                <a href="javascript:void(0);" class="hdle" ectype="addCate">增加分类</a>
                                <?php endif; ?>
                                <a href="javascript:void(0);" class="hdle" ectype="setupGoods">
                                    设置商品
                                    <input type="hidden" name="cat_goods[]" value="<?php echo $this->_var['value']['cat_goods']; ?>">
                                </a>
                                <?php if (! ($this->_foreach['name']['iteration'] <= 1)): ?>
                                <a href="javascript:void(0);" class="hdle" ectype="removeCate">删除分类</a>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        <?php else: ?>
                        <div class="item" ectype="item">
                            <div class="imitate_select select_w220" ectype="iselectErji">
                                <div class="cite" ectype="tit">请选择</div>
                                <ul>
                                    <li><a href="javascript:void(0);" data-value="">请选择..</a></li>
                                </ul>
                                <input name="cateValue[]" type="hidden" value="" ectype="cateValue" />
                            </div>
                            <a href="javascript:void(0);" class="hdle" ectype="addCate">增加分类</a>
                            <a href="javascript:void(0);" class="hdle" ectype="setupGoods" style="display:none;">
                                设置商品
                                <input type="hidden" name="cat_goods[]" value="">
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="control_item">
                    <div class="control_text lh30">设置颜色：</div>
                    <div class="control_value">
                        <div class="color-item color-item1<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-1' || ! $this->_var['spec_attr']['typeColor']): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" value="floor-color-type-1">
                            <i></i>
                        </div>
                        <div class="color-item color-item2<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-2'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-2">
                            <i></i>
                        </div>
                        <div class="color-item color-item3<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-3'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-3">
                            <i></i>
                        </div>
                        <div class="color-item color-item4<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-4'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-4">
                            <i></i>
                        </div>
                        <div class="color-item color-item5<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-5'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-5">
                            <i></i>
                        </div>
                        <div class="color-item color-item6<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-6'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-6">
                            <i></i>
                        </div>
                        <div class="color-item color-item7<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-7'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-7">
                            <i></i>
                        </div>
                        <div class="color-item color-item8<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-8'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-8">
                            <i></i>
                        </div>
                        <div class="color-item color-item9<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-9'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-9">
                            <i></i>
                        </div>
                        <div class="color-item color-item10<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-10'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-10">
                            <i></i>
                        </div>
                        <div class="color-item color-item11<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-11'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-11">
                            <i></i>
                        </div>
                        <div class="color-item color-item12<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-12'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-12">
                            <i></i>
                        </div>
                        <div class="color-item color-item13<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-13'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-13">
                            <i></i>
                        </div>
                        <div class="color-item color-item14<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-14'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-14">
                            <i></i>
                        </div>
                        
                        <div class="color-item color-item15<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-15'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-15">
                            <i></i>
                        </div>
                        <div class="color-item color-item16<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-16'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-16">
                            <i></i>
                        </div>
                        <div class="color-item color-item17<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-17'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-17">
                            <i></i>
                        </div>
                        <div class="color-item color-item18<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-18'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-18">
                            <i></i>
                        </div>
                        
                        <div class="color-item color-item19<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-19'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-19">
                            <i></i>
                        </div>
                        <div class="color-item color-item20<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-20'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-20">
                            <i></i>
                        </div>
                        <div class="color-item color-item21<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-21'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-21">
                            <i></i>
                        </div>
                        <div class="color-item color-item22<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-22'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-22">
                            <i></i>
                        </div>
                        
                        <div class="color-item color-item23<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-23'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-23">
                            <i></i>
                        </div>
                        <div class="color-item color-item24<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-24'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-24">
                            <i></i>
                        </div>
                        <div class="color-item color-item25<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-25'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-25">
                            <i></i>
                        </div>
                        <div class="color-item color-item26<?php if ($this->_var['spec_attr']['typeColor'] == 'floor-color-type-26'): ?> selected<?php endif; ?>" ectype="colorItem">
                            <input type="hidden" name="" value="floor-color-type-26">
                            <i></i>
                        </div>
                        <input type="hidden" name="typeColor" value="<?php if ($this->_var['spec_attr']['typeColor']): ?><?php echo $this->_var['spec_attr']['typeColor']; ?><?php else: ?>floor-color-type-1<?php endif; ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="body_info" style="display:none;">
            <div class="control_list">
                <div class="control_item control_item_quan">
                    <div class="control_value">
                        <div class="floormodeItem<?php if ($this->_var['mode'] == 'homeFloorModule'): ?> floormodeModuleItem<?php endif; ?><?php if ($this->_var['spec_attr']['floorMode'] < 2): ?> selected<?php endif; ?>" ectype="floormodeItem">
                        	<div class="img"><img src="themes//images/visual/<?php echo $this->_var['mode']; ?>_01.jpg"></div>
                            <div class="checkbox_item">
                            	<input type="radio" name="floorMode" value="1" data-pattern="<?php echo $this->_var['floor_style']['0']; ?>" class="ui-radio" id="floorMode_1"<?php if ($this->_var['spec_attr']['floorMode'] < 2): ?> checked<?php endif; ?>/>
                            	<label class="ui-radio-label" for="floorMode_1">楼层广告模板一</label>
                            </div>
                            <div ectype="floorModehide" class="hide">
                                <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                                <div ectype="leftBanner">
                                    <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBanner[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    <?php $_from = $this->_var['spec_attr']['leftBannerLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBannerLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   
                                    <?php $_from = $this->_var['spec_attr']['leftBannerSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBannerSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <div ectype="leftAdv">
                                    <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdv[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                                    <?php $_from = $this->_var['spec_attr']['leftAdvLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdvLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['leftAdvSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdvSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <div ectype="rightAdv">
                                    <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdv[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                                    <?php $_from = $this->_var['spec_attr']['rightAdvLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['rightAdvSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>  

                                    <?php $_from = $this->_var['spec_attr']['rightAdvTitle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvTitle[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['rightAdvSubtitle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvSubtitle[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div ectype="leftBanner">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <div ectype="leftAdv">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <div ectype="rightAdv">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>   
                        </div>
                        <div class="floormodeItem<?php if ($this->_var['mode'] == 'homeFloorModule'): ?> floormodeModuleItem<?php endif; ?><?php if ($this->_var['spec_attr']['floorMode'] == 2): ?> selected<?php endif; ?>" ectype="floormodeItem">
                        	<div class="img"><img src="themes//images/visual/<?php echo $this->_var['mode']; ?>_02.jpg"></div>
                            <div class="checkbox_item">
                            	<input type="radio" name="floorMode" value="2" data-pattern="<?php echo $this->_var['floor_style']['1']; ?>" class="ui-radio" id="floorMode_2"<?php if ($this->_var['spec_attr']['floorMode'] == 2): ?> checked<?php endif; ?> />
                            	<label class="ui-radio-label" for="floorMode_2">楼层广告模板二</label>
                            </div>
                            <div ectype="floorModehide" class="hide">
                                <?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>
                                <div ectype="leftBanner">
                                    <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBanner[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    <?php $_from = $this->_var['spec_attr']['leftBannerLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBannerLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   
                                    <?php $_from = $this->_var['spec_attr']['leftBannerSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBannerSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <div ectype="leftAdv">
                                    <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdv[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                                    <?php $_from = $this->_var['spec_attr']['leftAdvLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdvLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['leftAdvSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdvSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <div ectype="rightAdv">
                                    <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdv[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                                    <?php $_from = $this->_var['spec_attr']['rightAdvLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['rightAdvSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>  

                                    <?php $_from = $this->_var['spec_attr']['rightAdvTitle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvTitle[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['rightAdvSubtitle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvSubtitle[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div ectype="leftBanner">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <div ectype="leftAdv">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <div ectype="rightAdv">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="floormodeItem<?php if ($this->_var['mode'] == 'homeFloorModule'): ?> floormodeModuleItem<?php endif; ?><?php if ($this->_var['spec_attr']['floorMode'] == 3): ?> selected<?php endif; ?>" ectype="floormodeItem">
                        	<div class="img"><img src="themes//images/visual/<?php echo $this->_var['mode']; ?>_03.jpg"></div>
                            <div class="checkbox_item">
                            	<input type="radio" name="floorMode" value="3" data-pattern="<?php echo $this->_var['floor_style']['2']; ?>" class="ui-radio" id="floorMode_3"<?php if ($this->_var['spec_attr']['floorMode'] == 3): ?> checked<?php endif; ?>/>
                            	<label class="ui-radio-label" for="floorMode_3">楼层广告模板三</label>
                            </div>
                            <div ectype="floorModehide" class="hide">
                                <?php if ($this->_var['spec_attr']['floorMode'] == 3): ?>
                                <div ectype="leftBanner">
                                    <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBanner[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    <?php $_from = $this->_var['spec_attr']['leftBannerLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBannerLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   
                                    <?php $_from = $this->_var['spec_attr']['leftBannerSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBannerSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <div ectype="leftAdv">
                                    <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdv[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                                    <?php $_from = $this->_var['spec_attr']['leftAdvLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdvLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['leftAdvSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdvSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <div ectype="rightAdv">
                                    <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdv[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                                    <?php $_from = $this->_var['spec_attr']['rightAdvLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['rightAdvSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>  

                                    <?php $_from = $this->_var['spec_attr']['rightAdvTitle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvTitle[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['rightAdvSubtitle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvSubtitle[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div ectype="leftBanner">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <div ectype="leftAdv">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <div ectype="rightAdv">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="floormodeItem<?php if ($this->_var['mode'] == 'homeFloorModule'): ?> floormodeModuleItem<?php endif; ?><?php if ($this->_var['spec_attr']['floorMode'] == 4): ?> selected<?php endif; ?> last" ectype="floormodeItem">
                        	<div class="img"><img src="themes//images/visual/<?php echo $this->_var['mode']; ?>_04.jpg"></div>
                            <div class="checkbox_item">
                            	<input type="radio" name="floorMode" value="4" class="ui-radio" data-pattern="<?php echo $this->_var['floor_style']['3']; ?>" id="floorMode_4"<?php if ($this->_var['spec_attr']['floorMode'] == 4): ?> checked<?php endif; ?>/>
                            	<label class="ui-radio-label" for="floorMode_4">楼层广告模板四</label>
                            </div>
                            <div ectype="floorModehide" class="hide">
                                <?php if ($this->_var['spec_attr']['floorMode'] == 4): ?>
                                <div ectype="leftBanner">
                                    <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBanner[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    <?php $_from = $this->_var['spec_attr']['leftBannerLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBannerLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   
                                    <?php $_from = $this->_var['spec_attr']['leftBannerSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBannerSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <div ectype="leftAdv">
                                    <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdv[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                                    <?php $_from = $this->_var['spec_attr']['leftAdvLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdvLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['leftAdvSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdvSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <div ectype="rightAdv">
                                    <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdv[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                                    <?php $_from = $this->_var['spec_attr']['rightAdvLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['rightAdvSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>  

                                    <?php $_from = $this->_var['spec_attr']['rightAdvTitle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvTitle[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['rightAdvSubtitle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvSubtitle[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div ectype="leftBanner">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <div ectype="leftAdv">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <div ectype="rightAdv">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
						<?php if ($this->_var['mode'] == "homeFloorFive" || $this->_var['mode'] == "homeFloorSeven"): ?>
                        <div class="floormodeItem<?php if ($this->_var['spec_attr']['floorMode'] == 5): ?> selected<?php endif; ?>" ectype="floormodeItem">
                            <div class="img"><img src="themes//images/visual/<?php echo $this->_var['mode']; ?>_05.jpg"></div>
                            <div class="checkbox_item">
                            	<input type="radio" name="floorMode" value="5" data-pattern="<?php echo $this->_var['floor_style']['4']; ?>" class="ui-radio" id="floorMode_5"<?php if ($this->_var['spec_attr']['floorMode'] == 5): ?> checked<?php endif; ?>/>
                            	<label class="ui-radio-label" for="floorMode_5">楼层广告模板五</label>
                            </div>
                            <div ectype="floorModehide" class="hide">
                                <?php if ($this->_var['spec_attr']['floorMode'] == 5): ?>
                                <div ectype="leftBanner">
                                    <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBanner[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    <?php $_from = $this->_var['spec_attr']['leftBannerLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBannerLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   
                                    <?php $_from = $this->_var['spec_attr']['leftBannerSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftBannerSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <div ectype="leftAdv">
                                    <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdv[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                                    <?php $_from = $this->_var['spec_attr']['leftAdvLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdvLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['leftAdvSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="leftAdvSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <div ectype="rightAdv">
                                    <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdv[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                                    <?php $_from = $this->_var['spec_attr']['rightAdvLink']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvLink[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['rightAdvSort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvSort[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>  

                                    <?php $_from = $this->_var['spec_attr']['rightAdvTitle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvTitle[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   

                                    <?php $_from = $this->_var['spec_attr']['rightAdvSubtitle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                    <input name="rightAdvSubtitle[]" type="hidden" value="<?php echo $this->_var['item']; ?>">
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                                    <div ectype="advimg">
                                        <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                        <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div ectype="leftBanner">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <div ectype="leftAdv">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <div ectype="rightAdv">
                                    <div ectype="advimg">
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
						<?php endif; ?>
                    </div>
                </div>
                <div class="control_item control_item_cent" ectype="floorMode">
                    <div class="control_text lh30"><span class="cor cor1"></span>轮播广告图：</div>
                    <div class="control_value lh30" ectype="imgControl">
                        <a href="javascript:void(0);" class="blue fl" ectype="uploadImage" data-uploadimagetype="home" data-title="轮播广告图"  data-number="<?php if ($this->_var['mode'] == 'homeFloorThree'): ?>5<?php else: ?>3<?php endif; ?>">选择上传图片</a>
                        <div class="imgup_icon" ectype="imgValue" data-name="leftBanner">
                            <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                            <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </div>
                    </div>
                </div>
               	
                <div class="control_item control_item_cent" ectype="floorMode">
                    <div class="control_text lh30"><span class="cor cor2"></span>普通广告图：</div>
                    <div class="control_value lh30" ectype="imgControl">
                        <a href="javascript:void(0);" class="blue fl" ectype="uploadImage" data-uploadimagetype="home" data-title="普通广告图" data-number="2">选择上传图片</a>
                        <div class="imgup_icon" ectype="imgValue" data-name="leftAdv">
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                            <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </div>
                    </div>
                </div>
                
                <div class="control_item control_item_cent" ectype="floorMode">
                    <div class="control_text lh30"><span class="cor cor3"></span>标题广告图：</div>
                    <div class="control_value lh30" ectype="imgControl">
                        <a href="javascript:void(0);" class="blue fl" ectype="uploadImage" data-uploadimagetype="home" data-title="标题广告图" <?php if ($this->_var['mode'] != 'homeFloorFour' && $this->_var['mode'] != 'homeFloorSix'): ?>data-titleup="1"<?php endif; ?> data-number="<?php if ($this->_var['mode'] == 'homeFloor'): ?>5<?php elseif ($this->_var['mode'] == 'homeFloorFour' || $this->_var['mode'] == 'homeFloorFive'): ?>3<?php elseif ($this->_var['mode'] == 'homeFloorSix'): ?>2<?php elseif ($this->_var['mode'] == 'homeFloorSeven'): ?>1<?php else: ?>4<?php endif; ?>">选择上传图片</a>
                        <div class="imgup_icon" ectype="imgValue" data-name="rightAdv">
                            <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                            <?php if ($this->_var['item']): ?><a href="<?php echo $this->_var['item']; ?>" class="nyroModal" target="_blank"><i class="iconfont icon-image" onmouseover="toolTip('<img src=<?php echo $this->_var['item']; ?>>')" onmouseout="toolTip()"></i></a><?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </div>
                    </div>
                </div>    
            </div>        
        </div>
        <div class="body_info" style="display:none;">
            <div class="control_item">
                <div class="checkobx-item">
                    <input type="checkbox" id="selected_brand" name="is_selected" class="ui-checkbox fl" onclick="selected_brands(this)">
                    <label class="ui-label" for="selected_brand">已选择品牌</label>
                </div>
            </div>
            <div class="brand_list" ectype='brand_list' data-bandnumber="<?php if ($this->_var['mode'] == 'homeFloorModule'): ?>5<?php endif; ?>">
            	<div class="notic">请先现在楼层一级分类或者该分类下暂无品牌</div>
            </div>
        </div>
    </div>
    <input type="hidden" name="mode" value="<?php echo $this->_var['mode']; ?>">
    <input name='brand_ids' type='hidden' value='<?php echo $this->_var['spec_attr']['brand_ids']; ?>'>
</form>
<script type="text/javascript">
    var mode = "<?php echo $this->_var['mode']; ?>";
    if(mode != 'homeFloorFive'){
        $(function(){
            var cat_id = $("input[name='Floorcat_id']").val();
            var brand_ids = $("input[name='brand_ids']").val();
            if(cat_id > 0){
                searchFloorBrand(cat_id,brand_ids);
            }
        })
    }
    
    function selected_brands(obj){
        var brand_ids = $("input[name='brand_ids']").val();
        var cat_id = $("input[name='Floorcat_id']").val();
        if(cat_id > 0){
            var is_selected =$("input[name='is_selected']").is(':checked');
            if(is_selected){
                is_selected = 1;
            }else{
                is_selected = 0;
            }
            searchFloorBrand(cat_id,brand_ids,is_selected);
        }
    }
    //select下拉默认值赋值
    $('.imitate_select').each(function(){
		var sel_this = $(this)
		var val = sel_this.children('input[type=hidden]').val();
		sel_this.find('a').each(function(){
			if($(this).attr('data-value') == val){
				sel_this.children('.cite').html($(this).html());
			}
		})
    });
	
    $.divselect("#cat_id","#cat_id_val",function(obj){
        var val = obj.attr("data-value");
        $("input[name='Floorcat_id']").val(val);
        getChildCat(val,1);
    });
	
    $.divselect("#cat_id1","#cat_id_val1",function(obj){
        var val = obj.attr("data-value");
        $("input[name='Floorcat_id']").val(val);
        getChildCat(val,2);
    });
	
    function getChildCat(cat_id,type){
    	if(type == 1){var type2 = 2}else if(type == 2){var type2 = 3}
        Ajax.call('index.php?con=visual_editing&fun=getChildCat', 'act=getChildCat&cat_id=' + cat_id +'&deep=' + type2, function(result){
            if(type == 1){
                $(".cat_floor").html(result.content);
            }
        
            $("*[ectype='addCate']").parents(".item").find("ul").html(result.contentChild);
            $("*[ectype='addCate']").parents(".item").find(".cite").html("请选择..");
            $("*[ectype='addCate']").parents(".item").find("input[type='hidden']").val("");
            $("*[ectype='addCate']").parents(".item").siblings().remove();
            if(mode != 'homeFloorFive'){
                searchFloorBrand(cat_id);
            }
            
            if(result.content == "" && type2 == 3){
           
                $(".erji").html(result.contentChild);
            }               
        }, 'POST', 'JSON');
    }
	
	function searchFloorBrand(cat_id,brand_ids,is_selected){
		Ajax.call('index.php?con=visual_editing&fun=filter_list', 'is_ajax=1&act=filter_list&search_type=get_content&FloorBrand=1&cat_id=' + cat_id + "&brand_ids=" + brand_ids + "&is_selected=" + is_selected, function(result){
		   $("*[ectype='brand_list']").html(result.FloorBrand);
		}, 'POST', 'JSON');
	}
		
	//楼层广告模板选择
	$.divselect("#fm-select","#floorMode",function(obj){
        var val = obj.attr("data-value");
		var nyroModal = obj.parents("*[ectype='iselect']").siblings(".nyroModal");
        
		for(var i = 1; i < 9; i++){
			if(val == i){
				nyroModal.attr("href","images/visual/homeFloor_0" + i + ".jpg");
				nyroModal.find("i").attr("onmouseover","toolTip('<img src=images/visual/homeFloor_0" + i + ".jpg>')");
			}
		}
    });
	
	//判断模板模式选中后图片选择
	$(document).on("click","*[ectype='floormodeItem']",function(){
		var val = $(this).find("input[type='radio']").val(),
			pattern = $(this).find("input[type='radio']").data("pattern"),
			arr = new Array(),
			imgNumberArr = <?php echo $this->_var['imgNumberArr']; ?>;
			
		$(this).addClass("selected").siblings().removeClass("selected");
		$(this).find("input[type='radio']").prop("checked",true);
		
		pattern = pattern.toString();
		
		if(pattern.indexOf(',') > 0){
			arr = pattern.split(',');
		}else{
			arr = pattern;
		}

		$("*[ectype='floorMode']").hide();
		
		for(var i = 0; i<arr.length;i++){
			$("*[ectype='floorMode']").eq((arr[i]-1)).show();
		}
		
		for(var i in imgNumberArr){
			if(i == val){
				$.each(imgNumberArr[i],function(index,value){
					$("[data-name='"+index+"']").siblings("*[ectype='uploadImage']").attr("data-number",value);
				});
			}
		}
		var _this = $(this);
		$("*[ectype='imgValue']").each(function(){
			var name = $(this).data("name");
			var imgHtml = _this.find("[ectype='" + name + "']").find("[ectype='advimg']").html();
			$(this).html(imgHtml);
		});
	});
	
	//默认编辑弹框时模板模式选中
	var radioVal = $("input[name='floorMode']:checked").val();
	$("#floorMode_"+ radioVal).click();
</script>
<?php endif; ?>
<script type="text/javascript">
	pbct();

	$(".table_list").perfectScrollbar("destroy");
	$(".table_list").perfectScrollbar();
	
	$(".ps_table").perfectScrollbar("destroy");
	$(".ps_table").perfectScrollbar();
	
	$(".select-container").hover(function(){
		$(".select-list").perfectScrollbar("destroy");
		$(".select-list").perfectScrollbar();
	});
	
	$(document).click(function(e){
		//仿select
		if(e.target.className !='cite' && !$(e.target).parents("div").is(".imitate_select")){
			$('.imitate_select ul').hide();
		}
		
		//分类
		if(e.target.id !='category_name' && !$(e.target).parents("div").is(".select-container")){
			$('.categorySelect .select-container').hide();
		}
	});
</script>
<?php if ($this->_var['temp'] == 'ylinks'): ?>

<form action="" id="<?php echo $this->_var['mode']; ?>Insert" method="post" enctype="multipart/form-data" runat="server" >
    <div class="tab">
        <ul class="clearfix">
            <li class="current">友情链接设置</li>
        </ul>
    </div>
    <div class="modal-body hfloor">
        <div class="body_info">
    	    <div class="control_list">
                <div class="control_item">
                    <div class="control_item">
                        <div class="control_text"><em class="red">*</em>模块名称：</div>
                        <div class="control_value"><input type="text" value="<?php echo $this->_var['lift']; ?>" class="text" name="lift" autocomplete="off" maxlength="4" placeholder="请填写标题" ectype="required" data-msg="请填写标题" /><div class="notic">模块名称是显示左侧友情链接；只能4个中文字符，如：合作商家</div></div>
                    </div>
                </div>   
            </div> 
            <div class="ps_table">
                <table id="addpictable" class="table">
                    <thead>
                        <tr>
                            <th>友情链接标题</th>
                          
                            <th>友情链接地址</th>
                  
                                

                         

                            <th class="center">操作</th>
                       </tr>
                    </thead>
                   <tbody>
                        <?php $_from = $this->_var['banner_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['item']):
?>
                            <tr>

                                    <td>
                                        <input type="text" name="links_title[]" value="<?php echo $this->_var['item']['links_title']; ?>" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="title[]" value="<?php echo $this->_var['item']['title']; ?>" class="form-control">
                                    </td>                                    



                                <td class="center">
                                    <a href="javascript:;" class="pic_del del">删除</a>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr class="notic">
                                <td colspan="5">点击添加按钮，添加友情链接</td>
                            </tr>    
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </tbody>
                </table>
            </div>        
            
            <div class="images_space">
                <div class="goods_gallery mt20">
                    <div class="nav clearfix">
                    <div class="f_r mr5 add_links" ectype='add_links' onclick= "addlinks(this)"><i class="glyphicon"></i>添加友情链接</div>
                    </div>           
             </div>           
            <div class="control_list" style="padding-top: 30px;">                               
                <div class="control_item">
                    <div class="control_text lh30">设置颜色：</div>
                        <div class="page-head-bglink">

                            <input type="hidden" class="tm-picker-trigger-links" name="tm-picker-trigger-links" value="<?php echo $this->_var['spec_attr']['tm-picker-trigger-links']; ?>"  />
                            <input type="checkbox" class="ui-checkbox" name="header_dis_links" value="<?php echo $this->_var['spec_attr']['header_dis_links']; ?>" id="header_dis_links"  <?php if ($this->_var['spec_attr']['header_dis_links'] == 1): ?>checked<?php endif; ?> />
                            <label for="header_dis_links" class="ui-label">显示</label>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="mode" value="<?php echo $this->_var['mode']; ?>">
</form>
<script type="text/javascript">
         //商品名称颜色设置
        $(".page-head-bglink .tm-picker-trigger-links").spectrum({
            showInitial: true,
            showPalette: true,
            showSelectionPalette: true,
            showInput: true,
            color: "#fff",
            showSelectionPalette: true,
            maxPaletteSize: 10,
            preferredFormat: "hex",
            palette: [
                ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
                "rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
                ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
                "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"], 
                ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)", 
                "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)", 
                "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)", 
                "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)", 
                "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)", 
                "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
                "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
                "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
                "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)", 
                "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
            ]
        });        
        //添加背景颜色
        $("input[name='header_dis_links']").click(function(){
			var style = $(this).parents(".li").data("style");
            var bgDiv = $(this).parents(".page-head-bg");
            var bgColor = bgDiv.find(".tm-picker-trigger-links").val();
            var  yesno = $(this).val();
            if(yesno == 0 ){
            	$(this).val('1');
            }else{
            	$(this).val('0');
            }
            if($(this).prop("checked") == true){
                $(".pc-page").find(".hd_bg").css({"background-color":bgColor});
            }else{
                $(".pc-page").find(".hd_bg").css({"background-color":"transparent"});
            }
			//generate(style);
        });   	
	function addlinks(obj){
		var i = 0;
		var mode = "<?php echo $this->_var['mode']; ?>";
		var length = "8";
        var uploadImage = "<?php echo $this->_var['uploadImage']; ?>";
		var titleup = "<?php echo $this->_var['titleup']; ?>";
		var id = $(obj).parents(".pb").attr("id");
		$("#addpictable").find('tr').each(function(){
			i++
		});
		
		if($("#addpictable").find('tr.notic').length>0){
			i-=1;
		}

		if( length< i  && length != 0){
			alert("此模块最多可添加"+length+"个友情链接");
		}else{
				var html = '<tr><td><input type="text" name="links_title[]" value="" class="form-control"></td><td><input type="text" name="title[]" value="" class="form-control"></td><td class="center"><a href="javascript:;" class="pic_del del">删除</a></td></tr>';			
			if($("#addpictable").find(".notic").length>0){
				$("#addpictable").find(".notic").remove();
			}
			$("#addpictable").find("tbody").prepend(html);
		}
		
		pbct("#"+id);
	}
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'cmsnew'): ?>
<form action="" id="<?php echo $this->_var['mode']; ?>Insert" method="post" enctype="multipart/form-data" runat="server" >
    <div class="tishi">
		<div class="tishi_info">
		<p class="first">注意：1、弹出框鼠标移到头部可以拖动，以防笔记本小屏幕内容展示不全;</p>
	    <p>2、关联新闻/活动来源于资讯，没有新闻/活动请到资讯发布文章;</p>
	    </div>
	</div>
	<div class="tab">
		<ul class="clearfix">
	    	<li class="current">内容设置</li>
	    	<li>左侧轮播设置</li>
	    	<li>中部设置</li>
	    	<li>右侧设置</li>
	    </ul>
	</div>
    <div class="modal-body hfloor">
        <div class="body_info">
            <div class="control_list">
                <div class="control_item">
                    
                    <div class="control_item">
                        <div class="control_text"><em class="red">*</em>中部名称：</div>
                        <div class="control_value"><input type="text" value="<?php echo $this->_var['spec_attr']['lift']; ?>" class="text" name="lift" autocomplete="off" maxlength="4" placeholder="请填中部名称标题" ectype="required" data-msg="请填写中部名称标题" /><div class="notic">中部名称是显示今日头条修改；最多4个中文字符，如：今日头条</div></div>
                    </div>
                   
                    <div class="control_item">
                        <div class="control_text"><em class="red">*</em>右侧标题：</div>
                        <div class="control_value"><input type="text" value="<?php echo $this->_var['spec_attr']['floor_title']; ?>" class="text" name="floor_title" autocomplete="off" placeholder="右侧标题"  data-msg="右侧标题" /><div class="notic">右侧标题，是修改新闻/活动标题</div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="body_info" style="display:none;">
    	    <div class="ps_table">
                <table id="addpictable" class="table">
                    <thead>
                        <tr>
                            <th>新闻封面</th>
                          
                                <th>新闻链接</th>
                                <th>新闻标题</th>
                                

                         

                            <th class="center">操作</th>
                       </tr>
                    </thead>
                   <tbody>
                        <?php $_from = $this->_var['banner_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['item']):
?>
                            <tr>
                                <td>
                                    <div class="img">
                                        <span><img src="<?php echo $this->_var['item']['pic_src']; ?>"></span>
                                        <input type="hidden" name="pic_src[]" value="<?php echo $this->_var['item']['pic_src']; ?>"/>
                                    </div>
                                </td>
                                <?php if ($this->_var['uploadImage'] != 1): ?>
                                    <td>
                                        <input type="text" name="link[]" value="<?php echo $this->_var['item']['link']; ?>" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="title[]" value="<?php echo $this->_var['item']['title']; ?>" class="form-control">
                                    </td>                                    
                                        <input type="hidden" name="article_id[]" value="<?php echo $this->_var['item']['article_id']; ?>">
                                        <input type="hidden" name="cms_label[]" value="<?php echo $this->_var['item']['cms_label']; ?>">
                                        <input type="hidden" name="class_id[]" value="<?php echo $this->_var['item']['class_id']; ?>">
                                <?php endif; ?>
                                <?php if ($this->_var['titleup'] == 1): ?>
                                    <td class="center">
                                        <input type="text" value="<?php echo $this->_var['item']['title']; ?>" name="title[]" class="form-control small">
                                    </td>
                                    <td class="center">
                                        <input type="text" value="<?php echo $this->_var['item']['subtitle']; ?>" name="subtitle[]" class="form-control small">
                                    </td>
                                <?php endif; ?>
                                <td class="center">
                                    <a href="javascript:;" class="pic_del del">删除</a>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr class="notic">
                                <td colspan="5">点击下列新闻，可选中最多五个新闻</td>
                            </tr>    
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </tbody>
                </table>
            </div>
            <div class="images_space">
                <div class="goods_gallery mt20">
                    <form  action="" id="gallery_pic" method="post"  enctype="multipart/form-data"  runat="server" >
                        <div class="nav clearfix">
                            <div class="f_l">
                                <div class="imitate_select select_w220" id="cmsnew_id">
                                    <div class="cite">请选择新闻分类...</div>
                                        <ul style="display: none;" class="ps-container" ectype="album_list_check">
                                            <li><a href="javascript:;" data-value="0" class="ftx-01">请选择...</a></li>
                                            <?php $_from = $this->_var['album_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['list']):
?>
                                                <li><a href="javascript:;" data-value="<?php echo $this->_var['list']['album_id']; ?>" class="ftx-01"><?php echo $this->_var['list']['album_mame']; ?></a></li>
                                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                        </ul>
                                        <input name="cmsnew_id" type="hidden" value="<?php echo $this->_var['album_id']; ?>" id="cmsnew_id_val">
                                </div>

                            </div>

                        </div>
                    </form>
                    <div class="table_list" ectype='cmsnew_list'>
                        <div class="gallery_album" data-act="get_cmsnew_pic" data-inid="cmsnew_list" data-url='visual_editing.php' data-where="sort_name=<?php echo $this->_var['filter']['sort_name']; ?>&album_id=<?php echo $this->_var['filter']['album_id']; ?>">
                            <ul class="ga-images-ul">
                                <?php $_from = $this->_var['pic_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'pic_list');if (count($_from)):
    foreach ($_from AS $this->_var['pic_list']):
?>
                                    <li><a href="javascript:;" onclick="addpic('<?php echo $this->_var['pic_list']['pic_file']; ?>',this,'<?php echo $this->_var['pic_list']['url_cms']; ?>','<?php echo $this->_var['pic_list']['pic_title']; ?>','<?php echo $this->_var['pic_list']['article_id']; ?>','<?php echo $this->_var['pic_list']['class_id']; ?>','<?php echo $this->_var['pic_list']['label']; ?>')"><img src="<?php echo $this->_var['pic_list']['pic_file']; ?>"><span class="pixel"><?php echo $this->_var['pic_list']['pic_title']; ?></span></a></li>
                                <?php endforeach; else: ?>
                                    <li class="notic">暂无新闻</li>
                                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                            <div class="clear"></div>
                            <?php echo $this->fetch('library/lib_page.lbi'); ?>
                        </div>
                    </div>
                </div>
            </div>
          	
        </div>	
        <div class="body_info" style="display:none;">
    	    <div class="ps_table">
                <table id="z_addpictable" class="table">
                    <thead>
                        <tr>
                            <th>新闻封面</th>
                          
                                <th>新闻链接</th>
                                <th>新闻标题</th>
                                

                         

                            <th class="center">操作</th>
                       </tr>
                    </thead>
                   <tbody>
                        <?php $_from = $this->_var['z_banner_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('z_k', 'z_item');if (count($_from)):
    foreach ($_from AS $this->_var['z_k'] => $this->_var['z_item']):
?>
                            <tr>
                                <td>
                                    <div class="img">
                                        <span><img src="<?php echo $this->_var['z_item']['pic_src']; ?>"></span>
                                        <input type="hidden" name="z_pic_src[]" value="<?php echo $this->_var['z_item']['pic_src']; ?>"/>
                                    </div>
                                </td>
                                <?php if ($this->_var['z_uploadImage'] != 1): ?>
                                    <td>
                                        <input type="text" name="z_link[]" value="<?php echo $this->_var['z_item']['link']; ?>" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="z_title[]" value="<?php echo $this->_var['z_item']['title']; ?>" class="form-control">
                                    </td>                                    
                                        <input type="hidden" name="z_article_id[]" value="<?php echo $this->_var['z_item']['article_id']; ?>">
                                        <input type="hidden" name="z_cms_label[]" value="<?php echo $this->_var['z_item']['cms_label']; ?>">
                                        <input type="hidden" name="z_class_id[]" value="<?php echo $this->_var['z_item']['class_id']; ?>">
                                <?php endif; ?>
                                <?php if ($this->_var['z_titleup'] == 1): ?>
                                    <td class="center">
                                        <input type="text" value="<?php echo $this->_var['z_item']['title']; ?>" name="z_title[]" class="form-control small">
                                    </td>
                                    <td class="center">
                                        <input type="text" value="<?php echo $this->_var['z_item']['subtitle']; ?>" name="z_subtitle[]" class="form-control small">
                                    </td>
                                <?php endif; ?>
                                <td class="center">
                                    <a href="javascript:;" class="pic_del del">删除</a>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr class="notic">
                                <td colspan="5">点击下列新闻，可选中最多五个新闻</td>
                            </tr>    
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </tbody>
                </table>
            </div>
            <div class="images_space">
                <div class="goods_gallery mt20">
                    <form  action="" id="gallery_pic" method="post"  enctype="multipart/form-data"  runat="server" >
                        <div class="nav clearfix">
                            <div class="f_l">
                                <div class="imitate_select select_w220" id="z_cmsnew_id">
                                    <div class="cite">请选择新闻分类...</div>
                                        <ul style="display: none;" class="ps-container" ectype="album_list_check">
                                            <li><a href="javascript:;" data-value="0" class="ftx-01">请选择...</a></li>
                                            <?php $_from = $this->_var['z_album_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('z_k', 'z_list');if (count($_from)):
    foreach ($_from AS $this->_var['z_k'] => $this->_var['z_list']):
?>
                                                <li><a href="javascript:;" data-value="<?php echo $this->_var['z_list']['album_id']; ?>" class="ftx-01"><?php echo $this->_var['z_list']['album_mame']; ?></a></li>
                                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                        </ul>
                                        <input name="z_cmsnew_id" type="hidden" value="<?php echo $this->_var['z_album_id']; ?>" id="z_cmsnew_id_val">
                                </div>

                            </div>

                        </div>
                    </form>
                    <div class="table_list" ectype='z_cmsnew_list'>
                        <div class="gallery_album" data-act="get_cmsnew_pic" data-inid="z_cmsnew_list" data-url='visual_editing.php' data-where="sort_name=<?php echo $this->_var['filter']['sort_name']; ?>&album_id=<?php echo $this->_var['filter']['album_id']; ?>">
                            <ul class="ga-images-ul">
                                <?php $_from = $this->_var['z_pic_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'z_pic_list');if (count($_from)):
    foreach ($_from AS $this->_var['z_pic_list']):
?>
                                    <li><a href="javascript:;" onclick="z_addpic('<?php echo $this->_var['z_pic_list']['pic_file']; ?>',this,'<?php echo $this->_var['z_pic_list']['url_cms']; ?>','<?php echo $this->_var['z_pic_list']['pic_title']; ?>','<?php echo $this->_var['z_pic_list']['article_id']; ?>','<?php echo $this->_var['z_pic_list']['class_id']; ?>','{z_$pic_list.label}')"><img src="<?php echo $this->_var['z_pic_list']['pic_file']; ?>"><span class="pixel"><?php echo $this->_var['z_pic_list']['pic_title']; ?></span></a></li>
                                <?php endforeach; else: ?>
                                    <li class="notic">暂无新闻</li>
                                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                            <div class="clear"></div>
                            <?php echo $this->fetch('library/lib_page.lbi'); ?>
                        </div>
                    </div>
                </div>
            </div>
          	
        </div>	    
        <div class="body_info" style="display:none;">
    	    <div class="ps_table">
                <table id="r_addpictable" class="table">
                    <thead>
                        <tr>
                            <th>新闻封面</th>
                          
                                <th>新闻链接</th>
                                <th>新闻标题</th>
                                

                         

                            <th class="center">操作</th>
                       </tr>
                    </thead>
                   <tbody>
                        <?php $_from = $this->_var['r_banner_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('r_k', 'r_item');if (count($_from)):
    foreach ($_from AS $this->_var['r_k'] => $this->_var['r_item']):
?>
                            <tr>
                                <td>
                                    <div class="img">
                                        <span><img src="<?php echo $this->_var['r_item']['pic_src']; ?>"></span>
                                        <input type="hidden" name="r_pic_src[]" value="<?php echo $this->_var['r_item']['pic_src']; ?>"/>
                                    </div>
                                </td>
                                <?php if ($this->_var['r_uploadImage'] != 1): ?>
                                    <td>
                                        <input type="text" name="r_link[]" value="<?php echo $this->_var['r_item']['link']; ?>" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="r_title[]" value="<?php echo $this->_var['r_item']['title']; ?>" class="form-control">
                                    </td>                                    
                                        <input type="hidden" name="r_article_id[]" value="<?php echo $this->_var['r_item']['article_id']; ?>">
                                        <input type="hidden" name="r_cms_label[]" value="<?php echo $this->_var['r_item']['cms_label']; ?>">
                                        <input type="hidden" name="r_class_id[]" value="<?php echo $this->_var['r_item']['class_id']; ?>">
                                <?php endif; ?>
                                <?php if ($this->_var['r_titleup'] == 1): ?>
                                    <td class="center">
                                        <input type="text" value="<?php echo $this->_var['r_item']['title']; ?>" name="r_title[]" class="form-control small">
                                    </td>
                                    <td class="center">
                                        <input type="text" value="<?php echo $this->_var['r_item']['subtitle']; ?>" name="r_subtitle[]" class="form-control small">
                                    </td>
                                <?php endif; ?>
                                <td class="center">
                                    <a href="javascript:;" class="pic_del del">删除</a>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr class="notic">
                                <td colspan="5">点击下列新闻，可选中最多五个新闻</td>
                            </tr>    
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </tbody>
                </table>
            </div>
            <div class="images_space">
                <div class="goods_gallery mt20">
                    <form  action="" id="gallery_pic" method="post"  enctype="multipart/form-data"  runat="server" >
                        <div class="nav clearfix">
                            <div class="f_l">
                                <div class="imitate_select select_w220" id="r_cmsnew_id">
                                    <div class="cite">请选择新闻分类...</div>
                                        <ul style="display: none;" class="ps-container" ectype="album_list_check">
                                            <li><a href="javascript:;" data-value="0" class="ftx-01">请选择...</a></li>
                                            <?php $_from = $this->_var['r_album_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('r_k', 'r_list');if (count($_from)):
    foreach ($_from AS $this->_var['r_k'] => $this->_var['r_list']):
?>
                                                <li><a href="javascript:;" data-value="<?php echo $this->_var['r_list']['album_id']; ?>" class="ftx-01"><?php echo $this->_var['r_list']['album_mame']; ?></a></li>
                                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                        </ul>
                                        <input name="r_cmsnew_id" type="hidden" value="<?php echo $this->_var['r_album_id']; ?>" id="r_cmsnew_id_val">
                                </div>

                            </div>

                        </div>
                    </form>
                    <div class="table_list" ectype='r_cmsnew_list'>
                        <div class="gallery_album" data-act="get_cmsnew_pic" data-inid="r_cmsnew_list" data-url='visual_editing.php' data-where="sort_name=<?php echo $this->_var['filter']['sort_name']; ?>&album_id=<?php echo $this->_var['filter']['album_id']; ?>">
                            <ul class="ga-images-ul">
                                <?php $_from = $this->_var['r_pic_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'r_pic_list');if (count($_from)):
    foreach ($_from AS $this->_var['r_pic_list']):
?>
                                    <li><a href="javascript:;" onclick="r_addpic('<?php echo $this->_var['r_pic_list']['pic_file']; ?>',this,'<?php echo $this->_var['r_pic_list']['url_cms']; ?>','<?php echo $this->_var['r_pic_list']['pic_title']; ?>','<?php echo $this->_var['r_pic_list']['article_id']; ?>','<?php echo $this->_var['r_pic_list']['class_id']; ?>','{r_$pic_list.label}')"><img src="<?php echo $this->_var['r_pic_list']['pic_file']; ?>"><span class="pixel"><?php echo $this->_var['r_pic_list']['pic_title']; ?></span></a></li>
                                <?php endforeach; else: ?>
                                    <li class="notic">暂无新闻</li>
                                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                            <div class="clear"></div>
                            <?php echo $this->fetch('library/lib_page.lbi'); ?>
                        </div>
                    </div>
                </div>
            </div>
          	
        </div>	 	        
        
    </div>
<script type="text/javascript">
    imitate_select();
    
    $.divselect("#cmsnew_id","#cmsnew_id_val",function(obj){
        var val = obj.attr("data-value");
        changednew(val,obj);
    });
    
    $.divselect("#z_cmsnew_id","#z_cmsnew_id_val",function(obj){
        var val = obj.attr("data-value");
        z_changednew(val,obj);
    });    
    $.divselect("#r_cmsnew_id","#r_cmsnew_id_val",function(obj){
        var val = obj.attr("data-value");
        r_changednew(val,obj);
    });       
    //select下拉默认值赋值
    function imitate_select(){
        $('.imitate_select').each(function(){
            var sel_this = $(this)
            var val = sel_this.children('input[type=hidden]').val();
            sel_this.find('a').each(function(){
                if($(this).attr('data-value') == val){
                    sel_this.children('.cite').html($(this).html());
                }
            })
        });
    } 

    
	function addpic(src,obj,url_cms,pic_title,article_id,class_id,label){
		var i = 0;
		var mode = "<?php echo $this->_var['mode']; ?>";
		var length = "5";
        var uploadImage = "<?php echo $this->_var['uploadImage']; ?>";
		var titleup = "<?php echo $this->_var['titleup']; ?>";
		var id = $(obj).parents(".pb").attr("id");
		$("#addpictable").find('tr').each(function(){
			i++
		});
		
		if($("#addpictable").find('tr.notic').length>0){
			i-=1;
		}

		if( length< i  && length != 0){
			alert("此模块最多可添加"+length+"个新闻");
		}else{
            if(mode != "lunbo"){
				if(uploadImage == 1){
					var html = '<tr><td><div class="img"><span><img src="'+src+'"></span><input type="hidden" name="pic_src[]" value="'+src+'"/></div></td><td class="center"><a href="javascript:;" class="pic_del del">删除</a></td></tr>';
				}else{
					var title = '';
					if(titleup == 1){
						title = '<td class="center"><input type="text" value="" name="title[]" class="form-control small"></td><td class="center"> <input type="text" value="" name="subtitle[]" class="form-control small"></td>';
					}
					var html = '<tr><td><div class="img"><span><img src="'+src+'"></span><input type="hidden" name="pic_src[]" value="'+src+'"/></div></td><td><input type="text" name="link[]" value="'+url_cms+'" class="form-control"></td><td><input type="text" name="title[]" value="'+pic_title+'" class="form-control"></td><input type="hidden" name="article_id[]" value="'+article_id+'" ><input type="hidden" name="cms_label[]" value="'+label+'" ><input type="hidden" name="class_id[]" value="'+class_id+'" >' + title + '<td class="center"><a href="javascript:;" class="pic_del del">删除</a></td></tr>';
				}
			}else{
				var html = '<tr><td><div class="img"><span><img src="'+src+'"></span><input type="hidden" name="pic_src[]" value="'+src+'"/></div></td><td><input type="text" name="link[]" class="form-control"></td><td><input type="text" name="title[]" class="form-control"></td><td class="center"><input type="text" value="1" name="sort[]" class="form-control small"></td><td class="center"><input type="text" value="" name="bg_color[]" class="form-control small"></td><td class="center"><a href="javascript:;" class="pic_del del">删除</a></td></tr>';
			}
			
			if($("#addpictable").find(".notic").length>0){
				$("#addpictable").find(".notic").remove();
			}
			$("#addpictable").find("tbody").prepend(html);
		}
		
		pbct("#"+id);
	}
	function z_addpic(src,obj,url_cms,pic_title,article_id,class_id,label){
		var i = 0;
		var mode = "<?php echo $this->_var['mode']; ?>";
		var length = "5";
        var uploadImage = "<?php echo $this->_var['uploadImage']; ?>";
		var titleup = "<?php echo $this->_var['titleup']; ?>";
		var id = $(obj).parents(".pb").attr("id");
		$("#z_addpictable").find('tr').each(function(){
			i++
		});
		
		if($("#z_addpictable").find('tr.notic').length>0){
			i-=1;
		}

		if( length< i  && length != 0){
			alert("此模块最多可添加"+length+"个新闻");
		}else{
            if(mode != "lunbo"){
				if(uploadImage == 1){
					var html = '<tr><td><div class="img"><span><img src="'+src+'"></span><input type="hidden" name="pic_src[]" value="'+src+'"/></div></td><td class="center"><a href="javascript:;" class="pic_del del">删除</a></td></tr>';
				}else{
					var title = '';
					if(titleup == 1){
						title = '<td class="center"><input type="text" value="" name="title[]" class="form-control small"></td><td class="center"> <input type="text" value="" name="subtitle[]" class="form-control small"></td>';
					}
					var html = '<tr><td><div class="img"><span><img src="'+src+'"></span><input type="hidden" name="z_pic_src[]" value="'+src+'"/></div></td><td><input type="text" name="z_link[]" value="'+url_cms+'" class="form-control"></td><td><input type="text" name="z_title[]" value="'+pic_title+'" class="form-control"></td><input type="hidden" name="z_article_id[]" value="'+article_id+'" ><input type="hidden" name="z_cms_label[]" value="'+label+'" ><input type="hidden" name="z_class_id[]" value="'+class_id+'" >' + title + '<td class="center"><a href="javascript:;" class="pic_del del">删除</a></td></tr>';
				}
			}else{
				var html = '<tr><td><div class="img"><span><img src="'+src+'"></span><input type="hidden" name="pic_src[]" value="'+src+'"/></div></td><td><input type="text" name="link[]" class="form-control"></td><td><input type="text" name="title[]" class="form-control"></td><td class="center"><input type="text" value="1" name="sort[]" class="form-control small"></td><td class="center"><input type="text" value="" name="bg_color[]" class="form-control small"></td><td class="center"><a href="javascript:;" class="pic_del del">删除</a></td></tr>';
			}
			
			if($("#z_addpictable").find(".notic").length>0){
				$("#z_addpictable").find(".notic").remove();
			}
			$("#z_addpictable").find("tbody").prepend(html);
		}
		
		pbct("#"+id);
	}
	function r_addpic(src,obj,url_cms,pic_title,article_id,class_id,label){
		var i = 0;
		var mode = "<?php echo $this->_var['mode']; ?>";
		var length = "8";
        var uploadImage = "<?php echo $this->_var['uploadImage']; ?>";
		var titleup = "<?php echo $this->_var['titleup']; ?>";
		var id = $(obj).parents(".pb").attr("id");
		$("#r_addpictable").find('tr').each(function(){
			i++
		});
		
		if($("#r_addpictable").find('tr.notic').length>0){
			i-=1;
		}

		if( length< i  && length != 0){
			alert("此模块最多可添加"+length+"个新闻");
		}else{
            if(mode != "lunbo"){
				if(uploadImage == 1){
					var html = '<tr><td><div class="img"><span><img src="'+src+'"></span><input type="hidden" name="pic_src[]" value="'+src+'"/></div></td><td class="center"><a href="javascript:;" class="pic_del del">删除</a></td></tr>';
				}else{
					var title = '';
					if(titleup == 1){
						title = '<td class="center"><input type="text" value="" name="title[]" class="form-control small"></td><td class="center"> <input type="text" value="" name="subtitle[]" class="form-control small"></td>';
					}
					var html = '<tr><td><div class="img"><span><img src="'+src+'"></span><input type="hidden" name="r_pic_src[]" value="'+src+'"/></div></td><td><input type="text" name="r_link[]" value="'+url_cms+'" class="form-control"></td><td><input type="text" name="r_title[]" value="'+pic_title+'" class="form-control"></td><input type="hidden" name="r_article_id[]" value="'+article_id+'" ><input type="hidden" name="r_cms_label[]" value="'+label+'" ><input type="hidden" name="r_class_id[]" value="'+class_id+'" >' + title + '<td class="center"><a href="javascript:;" class="pic_del del">删除</a></td></tr>';
				}
			}else{
				var html = '<tr><td><div class="img"><span><img src="'+src+'"></span><input type="hidden" name="pic_src[]" value="'+src+'"/></div></td><td><input type="text" name="link[]" class="form-control"></td><td><input type="text" name="title[]" class="form-control"></td><td class="center"><input type="text" value="1" name="sort[]" class="form-control small"></td><td class="center"><input type="text" value="" name="bg_color[]" class="form-control small"></td><td class="center"><a href="javascript:;" class="pic_del del">删除</a></td></tr>';
			}
			
			if($("#r_addpictable").find(".notic").length>0){
				$("#r_addpictable").find(".notic").remove();
			}
			$("#r_addpictable").find("tbody").prepend(html);
		}
		
		pbct("#"+id);
	}  		
</script>
    
    
    
<?php endif; ?>