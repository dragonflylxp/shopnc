<?php if ($this->_var['temp'] == 'addCategory'): ?>
<div class="dialog_addCategory">
	<dl>
    	<dt><?php echo $this->_var['lang']['category_name']; ?>：</dt>
        <dd><input type="text" class="text text_2" name="addedCategoryName" id="addedCategoryName" value="" autocomplete="off" /></dd>
    </dl>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'addBrand'): ?>
<div class="dialog_addBrand">
	<dl>
    	<dt><?php echo $this->_var['lang']['brand_name']; ?>：</dt>
        <dd><input type="text" class="text text_2" name="addBrandName" id="addBrandName" value="" autocomplete="off" /></dd>
    </dl>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'addWarehouse'): ?>
<div class="addWarehouse">
    <dl>
        <dt><?php echo $this->_var['lang']['warehouse_name']; ?>：</dt>
        <dd>
            <div class="imitate_select select_w140">
                <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
                <ul>
                	<?php $_from = $this->_var['warehouse_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'warehouse');$this->_foreach['nowarehouse'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nowarehouse']['total'] > 0):
    foreach ($_from AS $this->_var['warehouse']):
        $this->_foreach['nowarehouse']['iteration']++;
?>
                    <li><a href="javascript:;" data-value="<?php echo $this->_var['warehouse']['region_id']; ?>" class="ftx-01"><?php echo $this->_var['warehouse']['region_name']; ?></a></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
                <input name="warehouse_name" type="hidden" value="" id="warehouse_name">
            </div>
        </dd>
    </dl>    
    <dl>
        <dt><?php echo $this->_var['lang']['warehouse_number']; ?>：</dt>
        <dd><input name="warehouse_number" id="warehouse_number" value="0" type="text" size="10" class="text text_2" autocomplete="off" /></dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['warehouse_price']; ?>：</dt>
        <dd><input name="warehouse_price" id="warehouse_price" value="0" type="text" size="10" class="text text_2" autocomplete="off" /></dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['warehouse_promote_price']; ?>：</dt>
        <dd><input name="warehouse_promote_price" id="warehouse_promote_price" value="0" type="text" size="10" class="text text_2" autocomplete="off" /></dd>
    </dl>
    
    <dl>
        <dt><?php echo $this->_var['lang']['lab_give_integral']; ?></dt>
        <dd>
        	<input name="give_integral" id="warehouse_give_integral" value="0" type="text" size="10" class="text text_2" rev="give" autocomplete="off" />
            <?php if ($this->_var['user_id']): ?>
            &nbsp;<span class="color999" id="give_html">可设置<em id="give">0</em>消费积分</span>
            <?php endif; ?>
        </dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['lab_rank_integral']; ?></dt>
        <dd>
        	<input name="rank_integral" id="warehouse_rank_integral" value="0" type="text" size="10" class="text text_2" rev="rank" autocomplete="off" />
            <?php if ($this->_var['user_id']): ?>
            &nbsp;<span class="color999" id="rank_html">可设置<em id="rank">0</em>等级积分</span>
            <?php endif; ?>
        </dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['lab_integral']; ?></dt>
        <dd>
        	<input name="pay_integral" id="warehouse_pay_integral" value="0" type="text" size="10" class="text text_2" rev="pay" autocomplete="off" />
            <?php if ($this->_var['user_id']): ?>
            &nbsp;<span class="color999" id="pay_html">可设置积分购买<em id="pay">0</em>金额</span>
            <?php endif; ?>
        </dd>
    </dl>
</div>
<script type="text/javascript">
<?php if ($this->_var['user_id']): ?>
$(function(){
	$('#warehouse_price, #warehouse_promote_price').blur(function(){
		var warehouse_price = Number($("#warehouse_price").val());
		var warehouse_promote_price = Number($("#warehouse_promote_price").val());
		var shop_price;
		
		if(warehouse_price > warehouse_promote_price && warehouse_promote_price == 0){
			shop_price = warehouse_price;
		}else if(warehouse_price < warehouse_promote_price && warehouse_promote_price != 0){
			shop_price = warehouse_price;
		}else{
			shop_price = warehouse_promote_price;
		}
		
		var give_integral = Math.floor(shop_price * <?php echo $this->_var['grade_rank']['give_integral']; ?>);

		$("#give").html(give_integral);
		
		var rank_integral = Math.floor(shop_price * <?php echo $this->_var['grade_rank']['rank_integral']; ?>);
		$("#rank").html(rank_integral);
		
		var pay_integral = Math.floor(shop_price / 100 * <?php echo $this->_var['integral_scale']; ?> * <?php echo $this->_var['grade_rank']['pay_integral']; ?>);
		$("#pay").html(pay_integral);
		
		$("#warehouse_give_integral").val('');
		$("#warehouse_rank_integral").val('');
		$("#warehouse_pay_integral").val('');
	});
	
	$('#warehouse_give_integral, #warehouse_rank_integral, #warehouse_pay_integral').blur(function(){
		var give = $('#give').html();
		var rank = $('#rank').html();
		var pay = $('#pay').html();
		var val = $(this).val();
		var rev = $(this).attr('rev');
		var integral = $("#" + rev).html();
		if(val > integral){
			if(rev == 'give'){
				alert("可设置" + integral + "消费积分");
			}else if(rev == 'rank'){
				alert("可设置" + integral + "等级积分");
			}else{
				alert("可设置积分购买" + integral + "金额");
			}
			$(this).val(integral);
		}
	});
	
});
<?php endif; ?>
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'addBatchWarehouse'): ?>
<div class="warehouse_warpper" id="batchWarehouelist">
	<div class="add_warehouse_list">
		<div class="warehouse_item">
			<span class="item">
				<span class="tit">仓库名称</span>
				
                <div class="imitate_select select_w140">
                    <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
                    <ul>
                        <?php $_from = $this->_var['warehouse_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'warehouse');$this->_foreach['nowarehouse'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nowarehouse']['total'] > 0):
    foreach ($_from AS $this->_var['warehouse']):
        $this->_foreach['nowarehouse']['iteration']++;
?>
                        <li><a href="javascript:;" data-value="<?php echo $this->_var['warehouse']['region_id']; ?>" class="ftx-01"><?php echo $this->_var['warehouse']['region_name']; ?></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    <input name="warehouse_name" type="hidden" value="0" id="warehouse_name">
                </div>						
			</span>
			<span class="item"><span class="tit">仓库库存</span><input type="text" value="0" name="warehouse_number" class="text w65" autocomplete="off" /></span>
			<span class="item"><span class="tit">仓库价格</span><input type="text" value="0" name="warehouse_price" class="text w65" autocomplete="off" /></span>
			<span class="item last"><span class="tit">仓库促销价格</span><input type="text" value="0" name="warehouse_promote_price" class="text w65" autocomplete="off" /></span>
			<div class="hide">
				<span class="item"><span class="tit">赠送消费积分数</span><input type="text" value="0" name="give_integral" class="text w65" autocomplete="off" /></span>
				<span class="item"><span class="tit">赠送等级积分数</span><input type="text" value="0" name="rank_integral" class="text w65" autocomplete="off" /></span>
				<span class="item"><span class="tit">积分购买金额</span><input type="text" value="0" name="pay_integral" class="text w65" autocomplete="off" /></span>
			</div>
		</div>
		<a href="javascript:void(0);" class="addList"></a>
	</div>
</div>

<?php endif; ?>
<?php if ($this->_var['temp'] == 'addRegion'): ?>
<div class="addWarehouse">
    <dl>
        <dt><?php echo $this->_var['lang']['warehouse_region_name']; ?>：</dt>
        <dd>
            <select name="warehouse_area_name" onchange="get_warehouse_area_name(this.value, this.id, <?php echo $this->_var['goods_id']; ?>, <?php echo $this->_var['user_id']; ?>)" id="1" class="select" style=" margin:0 10px 0 0;">
                <option value="0" selected><?php echo $this->_var['lang']['select_please']; ?></option>
                <?php $_from = $this->_var['warehouse_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'warehouse');$this->_foreach['nowarehouse'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nowarehouse']['total'] > 0):
    foreach ($_from AS $this->_var['warehouse']):
        $this->_foreach['nowarehouse']['iteration']++;
?>
                <option value="<?php echo $this->_var['warehouse']['region_id']; ?>"><?php echo $this->_var['warehouse']['region_name']; ?></option>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </select>
            <font style="font-size:12px; float:left;" id="warehouse_area_list_1" name="warehouse_area_list"></font>
        </dd>
    </dl>    
    <dl>
        <dt><?php echo $this->_var['lang']['region_number']; ?>：</dt>
        <dd><input name="region_number" id="region_number" value="0" type="text" size="10" class="text text_2" autocomplete="off" /></dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['region_price']; ?>：</dt>
        <dd><input name="region_price" id="region_price" value="0" type="text" size="10" class="text text_2" autocomplete="off" /></dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['region_promote_price']; ?>：</dt>
        <dd><input name="region_promote_price" id="region_promote_price" value="0" type="text" size="10" class="text text_2" autocomplete="off" /></dd>
    </dl>
    
    <dl>
        <dt><?php echo $this->_var['lang']['lab_give_integral']; ?></dt>
        <dd>
        	<input name="give_integral" id="region_give_integral" value="0" type="text" size="10" class="text text_2" rev="give" autocomplete="off" />
            <?php if ($this->_var['user_id']): ?>
        	&nbsp;<span class="color999" id="give_html">可设置<em id="give">0</em>消费积分</span>
            <?php endif; ?>
        </dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['lab_rank_integral']; ?></dt>
        <dd>
        	<input name="rank_integral" id="region_rank_integral" value="0" type="text" size="10" class="text text_2" rev="rank" autocomplete="off" />
        	<?php if ($this->_var['user_id']): ?>
            &nbsp;<span class="color999" id="rank_html">可设置<em id="rank">0</em>等级积分</span>
            <?php endif; ?>
        </dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['lab_integral']; ?></dt>
        <dd>
        	<input name="pay_integral" id="region_pay_integral" value="0" type="text" size="10" class="text text_2" rev="pay" autocomplete="off" />
        	<?php if ($this->_var['user_id']): ?>
        	&nbsp;<span class="color999" id="pay_html">可设置积分购买<em id="pay">0</em>金额</span>
            <?php endif; ?>
        </dd>
    </dl>
</div>

<script type="text/javascript">
<?php if ($this->_var['user_id']): ?>
$(function(){
	$('#region_price, #region_promote_price').blur(function(){
		var region_price = Number($('#region_price').val());
		var region_promote_price = Number($('#region_promote_price').val());
		var shop_price;
		
		if(region_price > region_promote_price && region_promote_price == 0){
			shop_price = region_price;
		}else if(region_price < region_promote_price && region_promote_price != 0){
			shop_price = region_price;
		}else{
			shop_price = region_promote_price;
		}
		
		var give_integral = Math.floor(shop_price * <?php echo $this->_var['grade_rank']['give_integral']; ?>);

		$("#give").html(give_integral);
		
		var rank_integral = Math.floor(shop_price * <?php echo $this->_var['grade_rank']['rank_integral']; ?>);
		$("#rank").html(rank_integral);
		
		var pay_integral = Math.floor(shop_price / 100 * <?php echo $this->_var['integral_scale']; ?> * <?php echo $this->_var['grade_rank']['pay_integral']; ?>);
		$("#pay").html(pay_integral);
		
		$("#warehouse_give_integral").val('');
		$("#warehouse_rank_integral").val('');
		$("#warehouse_pay_integral").val('');
	});
	
	$('#region_give_integral, #region_rank_integral, #region_pay_integral').blur(function(){
		var give = $('#give').html();
		var rank = $('#rank').html();
		var pay = $('#pay').html();
		var val = $(this).val();
		var rev = $(this).attr('rev');
		var integral = $("#" + rev).html();
		if(val > integral){
			if(rev == 'give'){
				alert("可设置" + integral + "消费积分");
			}else if(rev == 'rank'){
				alert("可设置" + integral + "等级积分");
			}else{
				alert("可设置积分购买" + integral + "金额");
			}
			$(this).val(integral);
		}
	});
	
});
<?php endif; ?>
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'addBatchRegion'): ?>
<div class="warehouse_warpper" id="batchRegionlist">
	<div class="add_warehouse_list">
		<div class="warehouse_item" id="area_1">
			<span class="item">
				<span class="tit">地区名称</span>
                <div class="imitate_select select_w140 warehouse_area_name" data-key="1" data-goodsid="<?php echo $this->_var['goods_id']; ?>" data-userid="<?php echo $this->_var['user_id']; ?>" id="warehouse_area_name_1">
                    <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
                    <ul>
                        <?php $_from = $this->_var['warehouse_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'warehouse');$this->_foreach['nowarehouse'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nowarehouse']['total'] > 0):
    foreach ($_from AS $this->_var['warehouse']):
        $this->_foreach['nowarehouse']['iteration']++;
?>
                        <li><a href="javascript:;" data-value="<?php echo $this->_var['warehouse']['region_id']; ?>" class="ftx-01"><?php echo $this->_var['warehouse']['region_name']; ?></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    <input name="warehouse_area_name" type="hidden" value="" id="warehouse_area_name_val_1">
                </div>
				<font style="font-size:12px;" id="warehouse_area_list_1" name="warehouse_area_list"></font>									
			</span>
			<span class="item"><span class="tit">地区库存</span><input type="text" value="0" name="region_number" class="text w65" autocomplete="off" /></span>
			<span class="item"><span class="tit">地区价格</span><input type="text" value="0" name="region_price" class="text w65" autocomplete="off" /></span>
			<span class="item"><span class="tit">地区促销价格</span><input type="text" value="0" name="region_promote_price" class="text w65" autocomplete="off" /></span>
			<div class="hide">
				<span class="item"><span class="tit">赠送消费积分数</span><input type="text" value="0" name="give_integral" class="text w65" autocomplete="off" /></span>
				<span class="item"><span class="tit">赠送等级积分数</span><input type="text" value="0" name="rank_integral" class="text w65" autocomplete="off" /></span>
				<span class="item last"><span class="tit">积分购买金额</span><input type="text" value="0" name="pay_integral" class="text w65" autocomplete="off" /></span>
			</div>
		</div>
		<a href="javascript:void(0);" class="addList"></a>
	</div>
</div>
<script type="text/javascript">
/*$(function(){
	$(document).on("click",".warehouse_area_name",function(){
		var key = $(this).data("key");
		var goods_id =$(this).data("goodsid");
		var user_id =$(this).data("userid");
		$.divselect("#warehouse_area_name_" + key,"#warehouse_area_name_val_" + key,function(obj){
			var value = $(obj).data("value");
			get_warehouse_area_name(value, key ,goods_id, user_id);
		});
	});
})*/
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'addImg'): ?>
<form  action="goods.php?act=addImg" id="fileForm" method="post"  enctype="multipart/form-data"  runat="server" >
<div class="addImg" id="addImg">
	<dl>
        <dt><?php echo $this->_var['lang']['img_count']; ?>：</dt>
        <dd><input type="text" class="text_3 mr10"  name="img_desc[]" size="20" autocomplete="off" /></dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['img_url']; ?>：</dt>
        <dd><input type="file" name="img_url[]" id="img_url"  class="file mr10 mt5" autocomplete="off" /></dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['img_file']; ?>：</dt>
        <dd><input type="text" size="40" value="<?php echo $this->_var['lang']['img_file']; ?>" style="color:#aaa;" autocomplete="off" onfocus="if (this.value == '<?php echo $this->_var['lang']['img_file']; ?>'){this.value='http://';this.style.color='#000';}" name="img_file[]"/></dd>
    </dl>
    <input type="hidden"   value="<?php echo $this->_var['goods_id']; ?>" name="goods_id_img"/>
</div>
</form>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'addBatchImg'): ?>
<form  action="goods.php?act=addBatchImg" id="addBatchImg_from" method="post"  enctype="multipart/form-data"  runat="server" >
	<div class="img_item"  >
		<span class="red"><?php echo $this->_var['lang']['remind']; ?></span>
	</div>
	<div class="img_item">
    <a href="javascript:;" onclick="addImg(this)" class="up"></a>
    <?php echo $this->_var['lang']['img_count']; ?>：<input type="text" class="text_2 mr10" name="img_desc[]" size="20" autocomplete="off" />
    <?php echo $this->_var['lang']['img_url']; ?>：<input type="file" name="img_url[]" id="Batch_img_url" class="mr10" autocomplete="off" />
    <input type="text" size="40" value="<?php echo $this->_var['lang']['img_file']; ?>" style="color:#aaa;" autocomplete="off" onfocus="if (this.value == '<?php echo $this->_var['lang']['img_file']; ?>'){this.value='http://';this.style.color='#000';}" name="img_file[]"/>
    <input type="hidden"   value="<?php echo $this->_var['goods_id']; ?>" name="goods_id_img"/>
    </div>

</form>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'privilege'): ?>
<div class="dialog_privilege" id="dialog_privilege">
	<dl>
    	<dt><?php echo $this->_var['lang']['label_region']; ?>：</dt>
        <dd>
        	<select name="country" id="selCountries" onChange="region.changed(this, 1, 'selProvinces')" class="select">
              <?php $_from = $this->_var['countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'country');$this->_foreach['fe_country'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_country']['total'] > 0):
    foreach ($_from AS $this->_var['country']):
        $this->_foreach['fe_country']['iteration']++;
?>
                <option value="<?php echo $this->_var['country']['region_id']; ?>" <?php if (($this->_foreach['fe_country']['iteration'] <= 1)): ?>selected<?php endif; ?>><?php echo htmlspecialchars($this->_var['country']['region_name']); ?></option>
              <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </select>
            <select name="province" id="selProvinces" onChange="region.changed(this, 2, 'selCities')" class="select mr10">
              <option value=""><?php echo $this->_var['lang']['select_please']; ?></option>
            </select>
            <select name="city" id="selCities" onChange="region.changed(this, 3, 'selDistricts')" class="select mr10">
              <option value=""><?php echo $this->_var['lang']['select_please']; ?></option>
            </select>
            <select name="district" id="selDistricts" class="select mr10">
              <option value=""><?php echo $this->_var['lang']['select_please']; ?></option>
            </select>
        </dd>
    </dl>
</div>
<script type="text/javascript">
	var selCountry = document.getElementById("selCountries");
	if (selCountry.selectedIndex >= 0)
	{
		region.loadProvinces(selCountry.options[selCountry.selectedIndex].value);
	}
</script>
<?php endif; ?> 

<?php if ($this->_var['temp'] == 'load_url'): ?>
<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>
<body>
	<div class="loadSpin">
		<i class="icon-spinner icon-spin"></i>
    </div>
</body>
</html>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'pic_album'): ?>
    <div class="pic_album">
        <div class="items border-bottom0">
            <div class="item album_Percent hide">
                <div class="label">上传进度：</div>
                <div class="label_value">
                    <div class="text_div mr0 w120 pl0"><span class="Percent_pic" ></span></div><div class="Percent"></div>
                </div>
            </div>
            <div class="item">
                <div class="label">选择相册：</div>
                <div class="label_value">
                    <div id="parent_cat" class="imitate_select select_w320">
                      <div class="cite"><?php if ($this->_var['album_mame']): ?><?php echo $this->_var['album_mame']; ?><?php else: ?><?php echo $this->_var['lang']['please_select']; ?><?php endif; ?></div>
                      <ul>
                        <?php $_from = $this->_var['album_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                        <li><a href="javascript:;" data-value="<?php echo $this->_var['item']['album_id']; ?>"  class="ftx-01"><?php echo $this->_var['item']['album_mame']; ?></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                      </ul>
                      <input name="album_id" type="hidden" id="album_number" value="<?php echo $this->_var['album_id']; ?>" >
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="label"><?php echo $this->_var['lang']['img_url']; ?>：</div>
                <div class="label_value">
                    <div class="type-file-box">
                        <input type="button" name="button" id="button" class="type-file-button type-file-button-radius" value="上传..." />
                        <span class="red lh30 ml10">按住ctrl可同时批量选择多张图片上传</span>
                    </div>
                    <div class="form_prompt"></div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <script type="text/javascript">
    var uploader_gallery = new plupload.Uploader({//创建实例的构造方法
		runtimes: 'html5,flash,silverlight,html4', //上传插件初始化选用那种方式的优先级顺序
		browse_button: 'button', // 上传按钮
		url: "gallery_album.php?is_ajax=1&act=upload_pic", //远程上传地址
		filters: {
			max_file_size: '2mb', //最大上传文件大小（格式100b, 10kb, 10mb, 1gb）
			mime_types: [//允许文件上传类型
				{title: "files", extensions: "jpg,png,gif"}
			]
		},
		multi_selection: true, //true:ctrl多文件上传, false 单文件上传
		init: {
			FilesAdded: function(up, files) { //文件上传前
				window.Percent = 0; //初始化进度
				var i = 0;
				plupload.each(files, function(file) { //遍历文件
					i ++;
				});
				
				window.Percentage = 1/i;
				$(".album_Percent").show();
				album_submitBtn();
			},
			FileUploaded: function(up, file, info) { //文件上传成功的时候触发
				window.Percent = window.Percent + Percentage*100;
				$(".Percent_pic").css({"width": window.Percent + "%"});
				$(".Percent").html(Math.round(window.Percent) + "%");
			},
			UploadComplete:function(up,file){//所有文件上传成功时触发
				window.location.href="gallery_album.php?act=view&id=<?php echo $this->_var['album_id']; ?>"; 
			},
			Error: function(up, err) { //上传出错的时候触发
				alert(err.message);
			}
		}
	});
	uploader_gallery.init();
	function album_submitBtn(){
		var album_id = $("#album_number").val();
		var data = {
			album_id: album_id
		};
		uploader_gallery.setOption("multipart_params", data);
		uploader_gallery.start();
	};
        
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'album_move'): ?>
    <div class="switch_info">
        <div class="items">
            <div class="item">
                <div class="label">选择相册：</div>
                <div class="label_value">
                    <div id="parent_cat" class="imitate_select select_w145">
                      <div class="cite"><?php if ($this->_var['album_mame']): ?><?php echo $this->_var['album_mame']; ?><?php else: ?><?php echo $this->_var['lang']['please_select']; ?><?php endif; ?></div>
                      <ul>
                            <?php $_from = $this->_var['album_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                            	<?php if ($this->_var['item']['album_id'] != $this->_var['album_id']): ?><li><a href="javascript:;" data-value="<?php echo $this->_var['item']['album_id']; ?>"  class="ftx-01"><?php echo $this->_var['item']['album_mame']; ?></a></li><?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                      </ul>
                      <input name="album_id" type="hidden" value="0"  id="remove_album_id">
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
$(function(){
    //select下拉默认值赋值
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
})
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'goods_list'): ?>
    <div class="gallery_album" data-act="<?php if ($this->_var['action']): ?><?php echo $this->_var['action']; ?><?php else: ?>changedgoods<?php endif; ?>" data-goods='1' data-inid="goods_list" data-url='<?php if ($this->_var['action']): ?><?php echo $this->_var['url']; ?><?php else: ?>get_ajax_content.php<?php endif; ?>' data-where="cat_id=<?php echo $this->_var['filter']['cat_id']; ?>&search_type=<?php echo $this->_var['filter']['search_type']; ?>&sort_order=<?php echo $this->_var['filter']['sort_order']; ?>&keyword=<?php echo $this->_var['filter']['keyword']; ?>&type=1&PromotionType=<?php echo $this->_var['PromotionType']; ?>">
        <ul class="ga-goods-ul" id="goods_list">
            <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['gl'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['gl']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['gl']['iteration']++;
?>
            <li class="<?php if ($this->_var['goods']['is_selected'] == 1): ?>on<?php endif; ?>">
                <div class="img"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></div>
                <div class="name"><?php echo $this->_var['goods']['goods_name']; ?></div>
                <div class="price">
                    <?php if ($this->_var['PromotionType'] == 'exchange'): ?>
                        <?php echo $this->_var['goods']['exchange_integral']; ?>
                    <?php else: ?>
                        <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                            <?php echo $this->_var['goods']['promote_price']; ?>
                        <?php else: ?>
                            <?php echo $this->_var['goods']['shop_price']; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="choose">
                    <a href="javascript:void(0);" <?php if ($this->_var['goods']['is_selected'] == 1): ?>class="on"<?php endif; ?> onclick="selected_goods(this,'<?php echo $this->_var['goods']['goods_id']; ?>')"><i class="iconfont <?php if ($this->_var['goods']['is_selected'] == 1): ?>icon-gou<?php else: ?>icon-dsc-plus<?php endif; ?>"></i><?php if ($this->_var['goods']['is_selected'] == 1): ?>已选择<?php else: ?>选择<?php endif; ?></a>
                    <?php if ($this->_var['PromotionType']): ?>
                    <div class="checkbox_item"> 
                        <input name="recommend" type="radio" class="ui-radio" value="<?php echo $this->_var['goods']['goods_id']; ?>" id="recommend<?php echo $this->_var['goods']['goods_id']; ?>"<?php if ($this->_var['goods']['goods_id'] == $this->_var['recommend']): ?> checked="checked"<?php endif; ?>>
                        <label for="recommend<?php echo $this->_var['goods']['goods_id']; ?>" class="ui-radio-label-shou"><i class="iconfont icon-thumb"></i>主推</label>
                    </div>
                    <?php endif; ?>
                </div>
            </li>
            <?php endforeach; else: ?>
            <li class="notic">该分类下没有商品</li>
            <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
        <div class="clear"></div>
        <?php echo $this->fetch('library/lib_page.lbi'); ?>
    </div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'replace'): ?>
<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
    <li>
        <div class="img"><a href="<?php echo $this->_var['goods']['url']; ?>"><img src="<?php echo $this->_var['goods']['original_img']; ?>"></a></div>
        <div class="info">
            <div class="name"><a href="<?php echo $this->_var['goods']['url']; ?>"><?php echo $this->_var['goods']['goods_name']; ?></a></div>
            <div class="price">
                    <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                        <?php echo $this->_var['goods']['promote_price']; ?>
                    <?php else: ?>
                        <?php echo $this->_var['goods']['shop_price']; ?>
                    <?php endif; ?>
            </div>
            <div class="btn_hover"><a href="<?php echo $this->_var['goods']['url']; ?>">立即购买</a></div>
        </div>
    </li>
<?php endforeach; else: ?>
	<?php if ($this->_var['attr']['itemsLayout'] == "row3"): ?>
    <li>
        <div class="img"><a href="" title=""><img src="themes//images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href="">立即购买</a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="themes//images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href="">立即购买</a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="themes//images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href="">立即购买</a></div>
        </div>
    </li>
    <?php elseif ($this->_var['attr']['itemsLayout'] == "row4"): ?>
    <li>
        <div class="img"><a href="" title=""><img src="themes//images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href="">立即购买</a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="themes//images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href="">立即购买</a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="themes//images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href="">立即购买</a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="themes//images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href="">立即购买</a></div>
        </div>
    </li>
	<?php else: ?>
    <li>
        <div class="img"><a href="" title=""><img src="themes//images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href="">立即购买</a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="themes//images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href="">立即购买</a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="themes//images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href="">立即购买</a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="themes//images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href="">立即购买</a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="themes//images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href="">立即购买</a></div>
        </div>
    </li>
    <?php endif; ?>
<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'img_list'): ?>
<?php $_from = $this->_var['img_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'img');if (count($_from)):
    foreach ($_from AS $this->_var['img']):
?>
	<?php if ($this->_var['mode'] == 'lunbo'): ?>
    <li style="background-image:url(<?php echo $this->_var['img']['pic_src']; ?>); background-position: center center;  background-repeat: no-repeat; background-color:<?php echo $this->_var['img']['bg_color']; ?>;"><a href="<?php echo $this->_var['img']['link']; ?>" target="<?php echo $this->_var['attr']['target']; ?>"  style="height:<?php echo $this->_var['attr']['picHeight']; ?>px;"></a></li>
    <?php else: ?>
    <?php if ($this->_var['is_li'] == 1): ?><li style="height:<?php echo $this->_var['attr']['picHeight']; ?>px;"><?php endif; ?><?php if ($this->_var['img']['link']): ?><a href="<?php echo $this->_var['img']['link']; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><?php if ($this->_var['mode'] == 'advImg4'): ?><span class="btm"></span><?php endif; ?><?php endif; ?><img src="<?php echo $this->_var['img']['pic_src']; ?>" width="<?php if ($this->_var['mode'] == 'advImg2'): ?>1200<?php else: ?><?php echo $this->_var['width']; ?><?php endif; ?>" height="<?php echo $this->_var['height']; ?>"><?php if ($this->_var['img']['link']): ?></a><?php endif; ?><?php if ($this->_var['is_li'] == 1): ?></li><?php endif; ?>
    <?php endif; ?>
<?php endforeach; else: ?>
	<?php if ($this->_var['mode'] == 'advImg1'): ?>
		<li><img src="themes//images/default/ad_01_pic.jpg"></li>
    <?php elseif ($this->_var['mode'] == 'advImg2'): ?>
    	<li><img src="themes//images/default/ad_02_a_pic.jpg" width="595" height="595"></li>
        <li><img src="themes//images/default/ad_02_a_pic.jpg" width="595" height="595"></li>
    <?php elseif ($this->_var['mode'] == 'advImg3'): ?>
    	<?php if ($this->_var['attr']['itemsLayout'] == "left-right"): ?>
    	<li><a href="#"><img src="themes//images/default/ad_02_c_pic.jpg"></a></li>
        <li><a href="#"><img src="themes//images/default/ad_02_d_pic.jpg"></a></li>
        <?php else: ?>
        <li><a href="#"><img src="themes//images/default/ad_02_d_pic.jpg"></a></li>
        <li><a href="#"><img src="themes//images/default/ad_02_c_pic.jpg"></a></li>
        <?php endif; ?>
    <?php elseif ($this->_var['mode'] == 'advImg4'): ?>
    	<?php if ($this->_var['attr']['itemsLayout'] == "row3"): ?>
    	<li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic_03.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic_03.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic_03.jpg"></a></li>
        <?php elseif ($this->_var['attr']['itemsLayout'] == "row4"): ?>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic_04.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic_04.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic_04.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic_04.jpg"></a></li>
        <?php elseif ($this->_var['attr']['itemsLayout'] == "row5"): ?>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic_02.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic_02.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic_02.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic_02.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic_02.jpg"></a></li>
        <?php else: ?>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="themes//images/default/ad_03_pic.jpg"></a></li>
        <?php endif; ?>
    <?php elseif ($this->_var['mode'] == 'lunbo'): ?>
    	<li><a href="#"><img src="themes//images/default/shop_banner_pic.jpg"></a></li>
    <?php endif; ?>
<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'navigator'): ?>
    <?php $_from = $this->_var['navigator']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'navigator');if (count($_from)):
    foreach ($_from AS $this->_var['navigator']):
?>
    <li><a href="<?php echo $this->_var['navigator']['url']; ?>" style="text-align:<?php echo $this->_var['attr']['align']; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><?php echo $this->_var['navigator']['name']; ?></a></li>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>


<?php if ($this->_var['temp'] == 'backupTemplates'): ?>
    <ul>
        <?php $_from = $this->_var['available_templates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'template');$this->_foreach['template'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['template']['total'] > 0):
    foreach ($_from AS $this->_var['template']):
        $this->_foreach['template']['iteration']++;
?>
        <li <?php if ($this->_var['default_tem'] == $this->_var['template']['code']): ?>class="curr"<?php endif; ?> data-name="$template.code}">
            <div class="tit"><?php echo $this->_var['template']['name']; ?></div>
            <div class="span"><?php echo $this->_var['template']['desc']; ?></div>
            <div class="img">
                <?php if ($this->_var['template']['screenshot']): ?><img width="263" height="338" src="<?php echo $this->_var['template']['screenshot']; ?>" data-src-wide="<?php echo $this->_var['template']['template']; ?>" border="0" id="<?php echo $this->_var['template']['code']; ?>" class="pic"> <?php endif; ?>                                       <div class="bg"></div>
            </div>
            <div class="info">
                <p>
                    <a href="<?php echo $this->_var['template']['template']; ?>" class="btnSeeImg">查看大图</a>
                </p>
                <p class="mt5">
                    <a href="javascript:template_information('<?php echo $this->_var['template']['code']; ?>','<?php echo $this->_var['ru_id']; ?>');">编辑模板信息</a>
                    <a href="javascript:removeTemplate('<?php echo $this->_var['template']['code']; ?>','<?php echo $this->_var['ru_id']; ?>')" class="ml10">删除模板</a>
                </p>
            </div>
            <div class="box" onclick="javascript:setupTemplate('<?php echo $this->_var['template']['code']; ?>','0')">
                <i class="icon icon-gou"></i>
                <span>立即使用</span>
            </div>
            <i class="ing"></i>
        </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
<?php endif; ?>
<!--<?php if ($this->_var['temp'] == 'album_move'): ?>
<div class="addImg">
	<dl>
        <dt>选择相册：</dt>
        <dd>
            <select name="album_id" class="select">
                <option value=""><?php echo $this->_var['lang']['select_please']; ?></option>
                <?php $_from = $this->_var['album_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                <option value="<?php echo $this->_var['item']['album_id']; ?>" <?php if ($this->_var['item']['album_id'] == $this->_var['album_id']): ?>selected<?php endif; ?>><?php echo $this->_var['item']['album_mame']; ?></option>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </select>
        </dd>
    </dl>
</div>
<?php endif; ?>-->

<?php if ($this->_var['temp'] == 'add_albun_pic'): ?>
<form  id="add_albun_pic" method="post"  enctype="multipart/form-data"  runat="server" >
<div class="addImg">
    <dl>
        <dt><span class="require-field">*</span>相册名称：</dt>
        <dd>
            <input type="text" name='album_mame'  class="text" autocomplete="off" />
        </dd>
    </dl>
    <!--<dl>
        <dt>封面：</dt>
        <dd>
            <input type='file' name='album_cover' size='35' class="file" />
        </dd>
    </dl>
    <dl>-->
        <dt>描述 ：</dt>
        <dd>
            <textarea class="textarea" name="album_desc" id="role_describe" style="border:;height:20px;"></textarea>
        </dd>
    </dl>
    <dl>
        <dt>排序 ：</dt>
        <dd>
            <input type="text" name="sort_order" value="50" size="35" class="text" />
        </dd>
    </dl>
    <!--<dl>
        <dt>&nbsp;</dt>
        <dd>
            <input type="button" value="确定" class="button fl" onclick="albun_submit()"/>
        </dd>
    </dl>-->
</div>
</form>
<script type="text/javascript">
    
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'getmap_html'): ?>
<div>
    <div id="mapcontainer" class="mapcontainer"></div>
    <div id="myPageTop" class="ml10 fl">
        <dl class="button_info">
            <dt>按关键字搜索：</dt>
            <dd>
                <input type="text" class="text text_2" placeholder="请输入关键字进行搜索" id="tipinput"><input type="button" value=" 搜索 " class="sc-btn btn30 sc-blueBg-btn ml10 auto-item" id="mapsubmit" >
            </dd>
        </dl>
        <br />
        <dl class="button_info">
            <dt>经纬度：</dt>
            <dd>
                <input type="text" class="text text_2" readonly id="lnglat" name="lnglat">
            </dd>
        </dl>
    </div>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'ajaxPiclist'): ?>
	<?php if ($this->_var['is_vis'] == 1 || $this->_var['is_vis'] == 2): ?>
	<div class="gallery_album" data-act="get_albun_pic" data-vis="<?php echo $this->_var['is_vis']; ?>" data-inid="pic_list" data-url='get_ajax_content.php' data-where="sort_name=<?php echo $this->_var['filter']['sort_name']; ?>&album_id=<?php echo $this->_var['filter']['album_id']; ?>&is_vis=<?php echo $this->_var['is_vis']; ?>&inid=<?php echo $this->_var['inid']; ?>">
		<ul class="ga-images-ul" ectype="pic_replace" data-type="<?php if ($this->_var['is_vis'] == 1): ?>check<?php elseif ($this->_var['is_vis'] == 2): ?>radio<?php endif; ?>">
			<?php $_from = $this->_var['pic_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'pic_album');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['pic_album']):
?>
			<li data-url="<?php echo $this->_var['pic_album']['pic_file']; ?>" data-picid='<?php echo $this->_var['pic_album']['pic_id']; ?>'><div class="img-container"><img src="<?php echo $this->_var['pic_album']['pic_file']; ?>"></div><i class="checked"></i></li>
			<?php endforeach; else: ?>
			<li class="notic">暂无图片</li>
			<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
        <div class="clear"></div>
		<?php echo $this->fetch('library/lib_page.lbi'); ?>
	</div>
	<?php else: ?>
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
	<?php endif; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'ajaxnews'): ?>
	<div class="gallery_album" data-act="get_news" data-inid="news_list" data-url='visual_editing.php' data-where="album_id=<?php echo $this->_var['filter1']['album_id']; ?>">
		<ul class="ga-images-ul">
			<?php $_from = $this->_var['pic_list1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'pic_list');if (count($_from)):
    foreach ($_from AS $this->_var['pic_list']):
?>
			<li><a href="javascript:;" data-url='<?php echo $this->_var['pic_list']['url_cms']; ?>' onclick="addpic('<?php echo $this->_var['pic_list']['pic_file']; ?>',this,'<?php echo $this->_var['pic_list']['url_cms']; ?>','<?php echo $this->_var['pic_list']['pic_title']; ?>','<?php echo $this->_var['pic_list']['article_id']; ?>','<?php echo $this->_var['pic_list']['class_id']; ?>','<?php echo $this->_var['pic_list']['label']; ?>')"><img src="<?php echo $this->_var['pic_list']['pic_file']; ?>"><span class="pixel"><?php echo $this->_var['pic_list']['pic_title']; ?></span></a></li>
			<?php endforeach; else: ?>
			<li class="notic">暂无新闻</li>
			<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
		<div class="clear"></div>
		<?php echo $this->fetch('library/lib_page.lbi'); ?>
	</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'r_ajaxnews'): ?>
	<div class="gallery_album" data-act="get_news" data-inid="news_list" data-url='visual_editing.php' data-where="album_id=<?php echo $this->_var['r_filter1']['album_id']; ?>">
		<ul class="ga-images-ul">
			<?php $_from = $this->_var['r_pic_list1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'r_pic_list');if (count($_from)):
    foreach ($_from AS $this->_var['r_pic_list']):
?>
			<li><a href="javascript:;" data-url='<?php echo $this->_var['r_pic_list']['url_cms']; ?>' onclick="r_addpic('<?php echo $this->_var['r_pic_list']['pic_file']; ?>',this,'<?php echo $this->_var['r_pic_list']['url_cms']; ?>','<?php echo $this->_var['r_pic_list']['pic_title']; ?>','<?php echo $this->_var['r_pic_list']['article_id']; ?>','<?php echo $this->_var['r_pic_list']['class_id']; ?>','<?php echo $this->_var['r_pic_list']['label']; ?>')"><img src="<?php echo $this->_var['r_pic_list']['pic_file']; ?>"><span class="pixel"><?php echo $this->_var['r_pic_list']['pic_title']; ?></span></a></li>
			<?php endforeach; else: ?>
			<li class="notic">暂无新闻</li>
			<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
		<div class="clear"></div>
		<?php echo $this->fetch('library/lib_page.lbi'); ?>
	</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'z_ajaxnews'): ?>
	<div class="gallery_album" data-act="get_news" data-inid="news_list" data-url='visual_editing.php' data-where="album_id=<?php echo $this->_var['z_filter1']['album_id']; ?>">
		<ul class="ga-images-ul">
			<?php $_from = $this->_var['z_pic_list1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'z_pic_list');if (count($_from)):
    foreach ($_from AS $this->_var['z_pic_list']):
?>
			<li><a href="javascript:;" data-url='<?php echo $this->_var['z_pic_list']['url_cms']; ?>' onclick="z_addpic('<?php echo $this->_var['z_pic_list']['pic_file']; ?>',this,'<?php echo $this->_var['z_pic_list']['url_cms']; ?>','<?php echo $this->_var['z_pic_list']['pic_title']; ?>','<?php echo $this->_var['z_pic_list']['article_id']; ?>','<?php echo $this->_var['z_pic_list']['class_id']; ?>','<?php echo $this->_var['z_pic_list']['label']; ?>')"><img src="<?php echo $this->_var['z_pic_list']['pic_file']; ?>"><span class="pixel"><?php echo $this->_var['z_pic_list']['pic_title']; ?></span></a></li>
			<?php endforeach; else: ?>
			<li class="notic">暂无新闻</li>
			<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
		<div class="clear"></div>
		<?php echo $this->fetch('library/lib_page.lbi'); ?>
	</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'ylinks'): ?>
<div class="ylinks" data-type="range" style="<?php if ($this->_var['header_dis_links'] == 1): ?>background-color: <?php echo $this->_var['tm-picker-trigger-links']; ?>;<?php endif; ?>">
    <div class="f_link">
        <div class="f_link01">
            <div class="f_link02"><?php echo $this->_var['linkslift']; ?>：   
            	<?php $_from = $this->_var['linkslist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'z_linkslist');if (count($_from)):
    foreach ($_from AS $this->_var['z_linkslist']):
?>
            	<a href="<?php echo $this->_var['z_linkslist']['title']; ?>" target="_blank"><?php echo $this->_var['z_linkslist']['links_title']; ?></a>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>                                                
        </div>
    </div>	
</div>	
<?php endif; ?>

<?php if ($this->_var['temp'] == 'cnews'): ?>
<div class="main_wrap mt40 clearfix newslist"  data-type="range">
	
	<div class="fl tFocus clearfix" id="tFocus">
		<div class="tFocus_btn">
			<a href="javascript:void(0);" id="prev" class="prev"></a>
			<a href="javascript:void(0);" id="next" class="next"></a>
		</div>
		<ul class="tFocus-pic zoom" id="tFocus-pic">
			<?php if ($this->_var['attrlist']): ?>
			<?php $_from = $this->_var['attrlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('cms_key', 'cms_list');if (count($_from)):
    foreach ($_from AS $this->_var['cms_key'] => $this->_var['cms_list']):
?>
			<?php if ($this->_var['cms_key'] == 1): ?>
			<li style="opacity: 1; z-index: 5;">
			<div class="s_tit_wrap">
				<a class="s_pic_tit" href="<?php echo $this->_var['cms_list']['link']; ?>" target="_blank" title="<?php echo $this->_var['cms_list']['title']; ?>"><?php echo $this->_var['cms_list']['title']; ?></a>
			</div>
			<span class="slide_num"><em></em><b><?php echo $this->_var['cms_key']; ?></b><i><?php echo $this->_var['attrcount']; ?></i></span>
			<p class="s_info">
				<a class="tp_block tb_4" href="<?php echo $this->_var['cms_list']['link']; ?>" target="_blank"><?php echo $this->_var['cms_list']['cms_label']; ?></a><?php echo $this->_var['cms_list']['article_abstract']; ?>
			</p>
			<a class="s_pic" target="_blank" title="<?php echo $this->_var['cms_list']['title']; ?>" href="<?php echo $this->_var['cms_list']['link']; ?>">
			<img src="<?php echo $this->_var['cms_list']['pic_src']; ?>" width="580" height="267" alt="<?php echo $this->_var['cms_list']['title']; ?>"></a>
			</li>
            <?php else: ?>
			<li style="opacity: 0; z-index: 0;">
			<div class="s_tit_wrap">
				<a class="s_pic_tit" href="<?php echo $this->_var['cms_list']['link']; ?>" target="_blank" title="<?php echo $this->_var['cms_list']['title']; ?>"><?php echo $this->_var['cms_list']['title']; ?></a>
			</div>
			<span class="slide_num"><em></em><b><?php echo $this->_var['cms_key']; ?></b><i><?php echo $this->_var['attrcount']; ?></i></span>
			<p class="s_info">
				<a class="tp_block tb_4" href="<?php echo $this->_var['cms_list']['link']; ?>" target="_blank"><?php echo $this->_var['cms_list']['cms_label']; ?></a><?php echo $this->_var['cms_list']['article_abstract']; ?>
			</p>
			<a class="s_pic" target="_blank" title="<?php echo $this->_var['cms_list']['title']; ?>" href="<?php echo $this->_var['cms_list']['link']; ?>">
			<img src="<?php echo $this->_var['cms_list']['pic_src']; ?>" width="580" height="267" alt="<?php echo $this->_var['cms_list']['title']; ?>"></a>
			</li>          
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php endif; ?>
            
		</ul>
		<div class="tFocusBtn nodisplay" id="tFocusBtn">
			<a class="tFocus-leftbtn nodisplay" id="tFocus-leftbtn" href="javascript:void(0);">‹</a>
			<div class="tFocus-btn clearfix" id="tFocus-btn">
				<ul>
					<?php $_from = $this->_var['attrlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cms_listc');if (count($_from)):
    foreach ($_from AS $this->_var['cms_listc']):
?>
					<li class=""></li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					
				</ul>
			</div>
			<a class="tFocus-rightbtn fr nodisplay" id="tFocus-rightbtn" href="javascript:void(0);">›</a>
		</div>
	</div>
	
	<div class="fl col_mod_0 ml30 w280">
		<div class="col_tit_wrap">
			<h3 class="col_tit">
      <?php echo $this->_var['attrlift']; ?>
			</h3>
		</div>

		<div class="col_con mt10">
		<?php if ($this->_var['z_attrlist']): ?>
		<?php $_from = $this->_var['z_attrlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('z_cms_key', 'z_cms_list');if (count($_from)):
    foreach ($_from AS $this->_var['z_cms_key'] => $this->_var['z_cms_list']):
?>
		    <?php if ($this->_var['z_cms_key'] == 1): ?>
			<div class="news_mod">			 	
				<div class="news_tit">
					<a class="tp_block tb_1" href="<?php echo $this->_var['z_cms_list']['link']; ?>" target="_blank"><?php echo $this->_var['z_cms_list']['cms_label']; ?></a>
					<a href="<?php echo $this->_var['z_cms_list']['link']; ?>" target="_blank" title="<?php echo $this->_var['z_cms_list']['title']; ?>"><?php echo $this->_var['z_cms_list']['title']; ?></a>
				</div>
				<p class="news_dis">
					<?php echo $this->_var['z_cms_list']['article_abstract']; ?>
				</p>
			</div>
			<?php endif; ?> 
			<?php if ($this->_var['z_cms_key'] == 2): ?>
			<div class="news_mod mt20">
				<div class="news_tit">
					<a class="tp_block tb_1" href="<?php echo $this->_var['z_cms_list']['link']; ?>" target="_blank"><?php echo $this->_var['z_cms_list']['cms_label']; ?></a>
					<a href="<?php echo $this->_var['z_cms_list']['link']; ?>" target="_blank" title="<?php echo $this->_var['z_cms_list']['title']; ?>"><?php echo $this->_var['z_cms_list']['title']; ?></a>
				</div>
				<p class="news_dis">
					<?php echo $this->_var['z_cms_list']['article_abstract']; ?>
				</p>
			</div>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>	
				
			
			<ul class="t_n_list mt15">
				<?php $_from = $this->_var['z_attrlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('z_cms_skey', 'z_cms_slist');if (count($_from)):
    foreach ($_from AS $this->_var['z_cms_skey'] => $this->_var['z_cms_slist']):
?>
				<?php if ($this->_var['z_cms_skey'] > 2): ?>
				<li><a href="<?php echo $this->_var['z_cms_slist']['link']; ?>" target="_blank" title="<?php echo $this->_var['z_cms_slist']['title']; ?>"><?php echo $this->_var['z_cms_slist']['title']; ?></a></li>
				<?php endif; ?>
			    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
        <?php endif; ?>
		</div>
	</div>
	
	<div class="fr col_mod w280">
		<div class="col_tit_wrap clearfix">
			<h3 class="fl col_tit"><a href="/news/" target="_blank"><?php echo $this->_var['attrfloor_title']; ?></a></h3>
		</div>
		<div class="col_con">
		<?php if ($this->_var['r_attrlist']): ?>
		<?php $_from = $this->_var['r_attrlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('r_cms_key', 'r_cms_list');if (count($_from)):
    foreach ($_from AS $this->_var['r_cms_key'] => $this->_var['r_cms_list']):
?>		
		    <?php if ($this->_var['r_cms_key'] == 1): ?>
			<div class="n_p_mod_0 clearfix">
				<a class="n_pic fl" href="<?php echo $this->_var['r_cms_list']['link']; ?>" target="_blank" title="<?php echo $this->_var['r_cms_list']['title']; ?>">
				<img src="<?php echo $this->_var['r_cms_list']['pic_src']; ?>" width="125" height="94" alt="<?php echo $this->_var['r_cms_list']['title']; ?>"></a>
				<a class="n_tit" href="<?php echo $this->_var['r_cms_list']['link']; ?>" target="_blank"><?php echo $this->_var['r_cms_list']['title']; ?></a>
			</div>
			<?php endif; ?>	
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>	
			<ul class="news_list_dot mt25 clearfix">
				<?php $_from = $this->_var['r_attrlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('r_cms_skey', 'r_cms_slist');if (count($_from)):
    foreach ($_from AS $this->_var['r_cms_skey'] => $this->_var['r_cms_slist']):
?>
				<?php if ($this->_var['r_cms_skey'] > 1): ?>
				<li>
				<a href="<?php echo $this->_var['r_cms_slist']['link']; ?>" title="<?php echo $this->_var['r_cms_slist']['title']; ?>" target="_blank"><?php echo $this->_var['r_cms_slist']['title']; ?></a>
				</li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
		<?php endif; ?>	
		</div>
	</div>
	
</div>                  

<?php endif; ?>
<?php if ($this->_var['temp'] == 'navigator_home'): ?>
    <li><a href="index.php" class="curr">首页</a></li>
    <?php $_from = $this->_var['navigator']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'navigator');if (count($_from)):
    foreach ($_from AS $this->_var['navigator']):
?>
    <li><a href="<?php echo $this->_var['navigator']['url']; ?>" target="_blank"><?php echo $this->_var['navigator']['name']; ?></a></li>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'brand_query'): ?>
<ul class="brand_list">
    <?php $_from = $this->_var['recommend_brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'brand');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['brand']):
?>
    <li ectype='cliclkBrand' <?php if ($this->_var['brand']['selected'] == 1): ?> class="selected"<?php endif; ?> data-brand='<?php echo $this->_var['brand']['brand_id']; ?>' data-type="homeBrand"><a href="JavaScript:void(0);"><img src="<?php echo $this->_var['brand']['brand_logo']; ?>" title="<?php echo $this->_var['brand']['brand_name']; ?>"></a><b></b></li>
    <?php endforeach; else: ?>
    <li class="notic">您选择的此分类下暂无品牌</li>
    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
<div class="clear"></div>
<?php echo $this->fetch('library/lib_page.lbi'); ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'homeFloor'): ?>

<div class="floor-line-con floorOne <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" id="floor_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
    <div class="floor-hd" ectype="floorTit">
    	<i class="box_hd_arrow"></i>
    	<i class="box_hd_dec"></i>
        <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="hd-tit"><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?>主分类名称<?php endif; ?></div><?php endif; ?>
        <div class="hd-tags">
            <ul>
                <li class="first current">
                    <span>新品推荐</span>
                    <i class="arrowImg"></i>
                </li>
                <?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" class="first" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="6" data-id="<?php echo $this->_var['cat']['cat_id']; ?>">
                    <span><?php echo $this->_var['cat']['cat_name']; ?></span>
                    <i class="arrowImg"></i>
                </li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="floor-bd bd-mode-0<?php echo $this->_var['spec_attr']['floorMode']; ?>">
        <div class="bd-left">
            <?php if ($this->_var['spec_attr']['floorMode'] == 1 || $this->_var['spec_attr']['floorMode'] == 2): ?>
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_002.jpg<?php endif; ?>"></a></li>
                        <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>"></a></li>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd"><ul></ul></div>
            </div>
            <?php endif; ?>
            
            <div class="floor-left-adv">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['spec_attr']['floorMode'] == 3): ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>"></a>
                <?php else: ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_004.jpg<?php endif; ?>"></a>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            
            <?php if ($this->_var['spec_attr']['floorMode'] == 4): ?>
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd"><ul></ul></div>
            </div>
            <?php endif; ?>
        </div>
        <div class="bd-right">
            <div class="floor-tabs-content clearfix">
                <div class="f-r-main f-r-m-adv">
                    <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
                    <div class="f-r-m-item
                    <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                    	<?php if ($this->_foreach['name']['iteration'] == 5): ?> f-r-m-i-double<?php endif; ?>
                    <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
                    	<?php if ($this->_foreach['name']['iteration'] == 1): ?> f-r-m-i-double<?php endif; ?>
                    <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
                    	<?php if ($this->_foreach['name']['iteration'] == 2): ?> f-r-m-i-double<?php endif; ?>
                    <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
                    	<?php if ($this->_foreach['name']['iteration'] == 4): ?> f-r-m-i-double<?php endif; ?>
                    <?php endif; ?>">
                        <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                            <div class="title">
                                <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php endif; ?></h3>
                                <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php endif; ?></span>
                            </div>
                            <img src="
                            	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                                	<?php if ($this->_foreach['name']['iteration'] == 5): ?>
                            			<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_004.jpg<?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
                                	<?php if ($this->_foreach['name']['iteration'] == 1): ?>
                            			<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_004.jpg<?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
                                	<?php if ($this->_foreach['name']['iteration'] == 2): ?>
                            			<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_004.jpg<?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
                                	<?php if ($this->_foreach['name']['iteration'] == 4): ?>
                            			<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_004.jpg<?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            ">
                        </a>
                    </div>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                    <ul class="p-list"></ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
    </div>
    <?php if ($this->_var['brand_list']): ?>
    <div class="floor-fd">
        <div class="floor-fd-brand clearfix">
            <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
            <div class="item">
                <a href="<?php echo $this->_var['list']['url']; ?>" target="_blank">
                    <div class="link-l"></div>
                    <div class="img"><img src="<?php echo $this->_var['list']['brand_logo']; ?>" title="<?php echo $this->_var['list']['brand_name']; ?>"></div>
                    <div class="link"></div>
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <?php else: ?>
        <?php if ($this->_var['spec_attr']['cat_id'] == 0): ?>
        <div class="floor-fd">
            <div class="floor-fd-brand clearfix" ectype="defaultBrand">
                <div class="item">
                    <a href="#" target="_blank">
                        <div class="link-l"></div>
                        <div class="img"><img src="data/gallery_album/visualDefault/homeIndex_010.jpg" title="esprit"></div>
                        <div class="link"></div>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'homeFloorModule'): ?>

<div class="floor-line-con floorTwo <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" <?php if ($this->_var['spec_attr']['hierarchy'] != 2): ?>id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>"<?php endif; ?> ectype="floorItem">
    <div class="floor-hd" ectype="floorTit">
    	<i class="box_hd_arrow"></i>
    	<i class="box_hd_dec"></i>
        <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="hd-tit"><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?>主分类名称<?php endif; ?></div><?php endif; ?>
        <div class="hd-tags">
            <ul>
                <li class="first current">
                    <span>新品推荐</span>
                    <i class="arrowImg"></i>
                </li>
                <?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" class="first" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="4" data-id="<?php echo $this->_var['cat']['cat_id']; ?>">
                    <span><?php echo $this->_var['cat']['cat_name']; ?></span>
                    <i class="arrowImg"></i>
                </li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="floor-bd">
        <div class="bd-left">
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_013.jpg<?php endif; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd"><ul></ul></div>
            </div>
        </div>
        <div class="bd-right">
            <div class="floor-tabs-content clearfix">
                <div class="f-r-main f-r-m-adv">
                    <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
                    <?php if ($this->_foreach['name']['iteration'] < $this->_var['advNumber']): ?>
                        <div class="f-r-m-item<?php if ($this->_var['spec_attr']['floorMode'] == 2): ?><?php if ($this->_foreach['name']['iteration'] == 3): ?> f-r-m-i-double<?php endif; ?><?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?><?php if ($this->_foreach['name']['iteration'] == 1): ?> f-r-m-i-double<?php endif; ?><?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?> f-r-m-i-double<?php endif; ?>">
                            <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                <div class="title">
                                    <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php endif; ?></h3>
                                    <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php endif; ?></span>
                                </div>
                                <img src="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                                	<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_012.jpg<?php endif; ?>
                                <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
                                    <?php if ($this->_foreach['name']['iteration'] == 3): ?>
                                    	<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_014.jpg<?php endif; ?>
                                    <?php else: ?>
                                    	<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_012.jpg<?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
                                	<?php if ($this->_foreach['name']['iteration'] == 1): ?>
                                    	<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_014.jpg<?php endif; ?>
                                    <?php else: ?>
                                    	<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_012.jpg<?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
                                	<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_014.jpg<?php endif; ?>    
                                <?php endif; ?>">
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                    <ul class="p-list"></ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
    </div>
    <?php if ($this->_var['brand_list']): ?>
    <div class="floor-fd">
        <div class="floor-fd-brand clearfix">
            <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
            <div class="item">
                <a href="<?php echo $this->_var['list']['url']; ?>" target="_blank">
                    <div class="link-l"></div>
                    <div class="img"><img src="<?php echo $this->_var['list']['brand_logo']; ?>" title="<?php echo $this->_var['list']['brand_name']; ?>"></div>
                    <div class="link"></div>
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <?php else: ?>
        <?php if ($this->_var['spec_attr']['cat_id'] == 0): ?>
        <div class="floor-fd">
            <div class="floor-fd-brand clearfix" ectype="defaultBrand">
                <div class="item">
                    <a href="#" target="_blank">
                        <div class="link-l"></div>
                        <div class="img"><img src="data/gallery_album/visualDefault/homeIndex_010.jpg" title="esprit"></div>
                        <div class="link"></div>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'homeFloorThree'): ?>

<div class="floor-line-con floorThree <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
	<div class="floor-hd" ectype="floorTit">
		<?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="hd-tit"><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?>主分类名称<?php endif; ?></div><?php endif; ?>
        <div class="hd-tags">
			<ul>
				<li class="first current">新品推荐</li>
				<?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>10<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>6<?php else: ?>8<?php endif; ?>" data-id="<?php echo $this->_var['cat']['cat_id']; ?>" data-floorcat="1"><?php echo $this->_var['cat']['cat_name']; ?></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
			</ul>
		</div>
	</div>
    
    <div class="floor-bd FT-bd-more-0<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>1<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>2<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>3<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>4<?php endif; ?>">
    	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
        <div class="floor-tabs-content clearfix">
            <div class="f-r-main f-r-m-adv">
                <ul>
                     <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                     <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual232x590.jpg<?php endif; ?>"></a></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </div>
            <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
            <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>"></div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
        <?php else: ?>
    	<div class="bd-left">
        	<?php if ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                    	<?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual232x590.jpg<?php endif; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd"><ul></ul></div>
            </div>
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
            	<div class="floor-left-adv">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual232x290.jpg<?php endif; ?>"></a>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="bd-right">
        	<div class="floor-tabs-content clearfix">
        	<div class="f-r-main f-r-m-adv">
            <?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <div class="floor-left-adv"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual474x290.jpg<?php endif; ?>"></a></div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php endif; ?>
            
            <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
            <div class="f-r-m-item">
                <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                    <div class="title">
                        <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php endif; ?></h3>
                        <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php endif; ?></span>
                    </div>
                    <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual232x290.jpg<?php endif; ?>">
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
            <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
            	<?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <div class="floor-left-adv"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual474x290.jpg<?php endif; ?>"></a></div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>    
    	<?php endif; ?>
    </div>
    <?php if ($this->_var['brand_list']): ?>
    <div class="floor-fd">
        <div class="floor-fd-brand clearfix">
            <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
            <div class="item">
                <a href="<?php echo $this->_var['list']['url']; ?>" target="_blank">
                    <div class="link-l"></div>
                    <div class="img"><img src="<?php echo $this->_var['list']['brand_logo']; ?>" title="<?php echo $this->_var['list']['brand_name']; ?>"></div>
                    <div class="link"></div>
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <?php else: ?>
        <?php if ($this->_var['spec_attr']['cat_id'] == 0): ?>
        <div class="floor-fd">
            <div class="floor-fd-brand clearfix" ectype="defaultBrand">
                <div class="item">
                    <a href="#" target="_blank">
                        <div class="link-l"></div>
                        <div class="img"><img src="data/gallery_album/visualDefault/homeIndex_010.jpg" title="esprit"></div>
                        <div class="link"></div>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'homeFloorFour'): ?>

<div class="floor-line-con floorFour <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
    <div class="floor-hd" ectype="floorTit">
        <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="hd-tit"><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?>主分类名称<?php endif; ?></div><?php endif; ?>
        <div class="hd-tags">
            <ul>
                <li class="first current" data-catGoods="<?php echo $this->_var['spec_attr']['top_goods']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>8<?php elseif ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>10<?php else: ?>12<?php endif; ?>" data-id="<?php echo $this->_var['spec_attr']['cat_id']; ?>" data-floorcat="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>2<?php else: ?>0<?php endif; ?>">新品推荐</li>
                <?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>8<?php elseif ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>10<?php else: ?>12<?php endif; ?>" data-id="<?php echo $this->_var['cat']['cat_id']; ?>" data-floorcat="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>2<?php else: ?>0<?php endif; ?>"><?php echo $this->_var['cat']['cat_name']; ?></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="floor-bd FF-bd-more-0<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>1<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>2<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>3<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>4<?php endif; ?>">
        <?php if ($this->_var['spec_attr']['floorMode'] != 4): ?>
        <div class="bd-left">
        	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
            <div class="floor-left-adv">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adc'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adc']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adc']['iteration']++;
?>
                <?php if ($this->_foreach['adc']['iteration'] == 1): ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x520.jpg<?php endif; ?>"></a>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <ul class="p-list" ectype="pList">
                <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                <?php if (($this->_foreach['goods']['iteration'] - 1) < 4): ?>
                <li class="li opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                        <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                        <div class="p-price">
                            <div class="shop-price">
                                <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                <?php echo $this->_var['list']['promote_price']; ?>
                                <?php else: ?>
                                <?php echo $this->_var['list']['shop_price']; ?>
                                <?php endif; ?>
                            </div>
                        </div>    
                    </div>
                </li>
                <?php endif; ?>
                <?php endforeach; else: ?>
                <li class="li right-child opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">请选择您所需的商品</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <li class="li right-child opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">请选择您所需的商品</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <li class="li left-child opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">请选择您所需的商品</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <li class="li right-child opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">请选择您所需的商品</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                    	<?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x520.jpg<?php endif; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd"><ul></ul></div>
            </div>
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
            <div class="floor-left-adv">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adc'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adc']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adc']['iteration']++;
?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x260.jpg<?php endif; ?>"></a>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="bd-right">
            <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
            <div class="floor-left-adv">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adc'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adc']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adc']['iteration']++;
?>
                <?php if ($this->_foreach['adc']['iteration'] == 2): ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x520.jpg<?php endif; ?>"></a>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <ul class="p-list" ectype="pList">
                <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                <?php if (($this->_foreach['goods']['iteration'] - 1) > $this->_var['goods_num']): ?>
                <li class="li opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                        <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                        <div class="p-price">
                            <div class="shop-price">
                                <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                <?php echo $this->_var['list']['promote_price']; ?>
                                <?php else: ?>
                                <?php echo $this->_var['list']['shop_price']; ?>
                                <?php endif; ?>
                            </div>
                        </div>    
                    </div>
                </li>
                <?php endif; ?>
                <?php endforeach; else: ?>
                <li class="li left-child opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <li class="li opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <li class="li left-child opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <li class="li opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <?php else: ?>
            <div class="floor-tabs-content clearfix">
            	<div class="f-r-main f-r-m-adv" ectype="floor_cat_<?php echo $this->_var['spec_attr']['cat_id']; ?>">
                    <ul class="p-list">
                        <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <?php if (($this->_foreach['goods']['iteration'] - 1) > $this->_var['goods_num']): ?>
                        <li class="<?php if ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?><?php if ($this->_foreach['goods']['iteration'] % 5 == 1): ?>left-child <?php endif; ?><?php else: ?><?php if ($this->_foreach['goods']['iteration'] % 6 == 1): ?>left-child <?php endif; ?><?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                    <div class="shop-price">
                                        <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['list']['promote_price']; ?>
                                        <?php else: ?>
                                        <?php echo $this->_var['list']['shop_price']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>    
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; else: ?>
                        <li class="left-child opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="<?php if ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>left-child <?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="<?php if ($this->_var['spec_attr']['floorMode'] == 4): ?>left-child <?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <?php if ($this->_var['spec_attr']['floorMode'] == 4): ?>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                    <ul class="p-list"></ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($this->_var['brand_list']): ?>
    <div class="floor-fd">
        <div class="floor-fd-brand clearfix">
            <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
            <div class="item">
                <a href="<?php echo $this->_var['list']['url']; ?>" target="_blank">
                    <div class="link-l"></div>
                    <div class="img"><img src="<?php echo $this->_var['list']['brand_logo']; ?>" title="<?php echo $this->_var['list']['brand_name']; ?>"></div>
                    <div class="link"></div>
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <?php else: ?>
        <?php if ($this->_var['spec_attr']['cat_id'] == 0): ?>
        <div class="floor-fd">
            <div class="floor-fd-brand clearfix" ectype="defaultBrand">
                <div class="item">
                    <a href="#" target="_blank">
                        <div class="link-l"></div>
                        <div class="img"><img src="data/gallery_album/visualDefault/homeIndex_010.jpg" title="esprit"></div>
                        <div class="link"></div>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'homeFloorFive'): ?>

<div class="floor-line-con floorFive <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
    <div class="floor-hd" ectype="floorTit">
        <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="hd-tit"><i class="iconfont icon-ele"></i><em class="iconfont icon-spot"></em><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?>主分类名称<?php endif; ?></div><?php endif; ?>
        <div class="hd-tags">
            <ul>
                <li class="first current">新品推荐</li>
                <?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="10" data-id="<?php echo $this->_var['cat']['cat_id']; ?>"><?php echo $this->_var['cat']['cat_name']; ?></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="floor-bd FFI-bd-more-0<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>1<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>2<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>3<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>4<?php elseif ($this->_var['spec_attr']['floorMode'] == 5): ?>5<?php endif; ?>">
        <div class="floor-tabs-content clearfix">
            <div class="f-r-main f-r-m-adv">
                <div class="bd-left">
                    <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                        <div class="floor-left-slide">
                            <div class="bd">
                                <ul>
                                    <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                    <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual477x450.jpg<?php endif; ?>"></a></li>
                                   <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </ul>
                            </div>
                            <div class="hd">
                                <ul></ul>
                            </div>
                        </div>
                        <div class="floor-left-adv">
                            <ul>
                                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x450.jpg<?php endif; ?>"></a></li>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <?php if ($this->_var['spec_attr']['floorMode'] == 3 || $this->_var['spec_attr']['floorMode'] == 4 || $this->_var['spec_attr']['floorMode'] == 5): ?>
                        <div class="floor-left-adv">
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] == 1): ?>
                            <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x450.jpg<?php endif; ?>"></a>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </div>
                        <?php endif; ?>
                    
                        <?php if ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>
                        <div class="floor-left-slide">
                            <div class="bd">
                                <ul>
                                    <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                    <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual477x450.jpg<?php endif; ?>"></a></li>
                                   <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </ul>
                            </div>
                            <div class="hd">
                                <ul></ul>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($this->_var['spec_attr']['floorMode'] == 4 || $this->_var['spec_attr']['floorMode'] == 5): ?>
                        <ul>
                            <li class="f-bd-item">
                                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                                <?php if ($this->_foreach['adv']['iteration'] == 2): ?>
                                <div class="floor-adv"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x165.jpg<?php endif; ?>"></a></div>
                                <?php endif; ?>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                
                                <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                                <?php if ($this->_foreach['adv']['iteration'] == 1): ?>
                                <div class="fr-adv mt5">
                                    <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                        <div class="title">
                                            <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php else: ?>主标题<?php endif; ?></h3>
                                            <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php else: ?>次标题<?php endif; ?></span>
                                        </div>
                                        <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x280.jpg<?php endif; ?>">
                                    </a>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </li>
                            <?php if ($this->_var['spec_attr']['floorMode'] == 5): ?>
                            <li class="f-bd-item">
                                <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                                <?php if ($this->_foreach['adv']['iteration'] == 2): ?>
                                <div class="fr-adv">
                                    <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                        <div class="title">
                                            <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php else: ?>主标题<?php endif; ?></h3>
                                            <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php else: ?>次标题<?php endif; ?></span>
                                        </div>
                                        <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x280.jpg<?php endif; ?>">
                                    </a>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                
                                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                                <?php if ($this->_foreach['adv']['iteration'] == 3): ?>
                                <div class="floor-adv mt5"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x165.jpg<?php endif; ?>"></a></div>
                                <?php endif; ?>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </li>
                            <?php endif; ?>
                        </ul>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            
                <?php if ($this->_var['spec_attr']['floorMode'] != 1): ?>
                <div class="bd-right">
                    <?php if ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>
                    <ul>
                        <?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>
                        <li class="f-bd-item">
                            <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] == 1): ?>
                            <div class="fr-adv">
                                <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                    <div class="title">
                                        <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php else: ?>主标题<?php endif; ?></h3>
                                        <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php else: ?>次标题<?php endif; ?></span>
                                    </div>
                                    <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x280.jpg<?php endif; ?>">
                                </a>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] == 1): ?>
                            <div class="floor-adv mt5"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x165.jpg<?php endif; ?>"></a></div>                    
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </li>
                        <?php endif; ?>
                        <li class="f-bd-item">
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] == 2): ?>
                            <div class="floor-adv"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x165.jpg<?php endif; ?>"></a></div>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            
                            <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] == 2): ?>
                            <div class="fr-adv mt5">
                                <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                    <div class="title">
                                        <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php else: ?>主标题<?php endif; ?></h3>
                                        <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php else: ?>次标题<?php endif; ?></span>
                                    </div>
                                    <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x280.jpg<?php endif; ?>">
                                </a>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </li>
                        <li class="f-bd-item">
                            <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] == 1): ?>
                            <div class="fr-adv">
                                <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                    <div class="title">
                                        <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php else: ?>主标题<?php endif; ?></h3>
                                        <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php else: ?>次标题<?php endif; ?></span>
                                    </div>
                                    <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x280.jpg<?php endif; ?>">
                                </a>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] == 3): ?>
                            <div class="floor-adv mt5"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x165.jpg<?php endif; ?>"></a></div>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </li>
                    </ul>
                    <?php endif; ?>
                    
                    <?php if ($this->_var['spec_attr']['floorMode'] == 4 || $this->_var['spec_attr']['floorMode'] == 5): ?>
                    <div class="floor-left-slide">
                        <div class="bd">
                            <ul>
                                <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual477x450.jpg<?php endif; ?>"></a></li>
                               <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                        </div>
                        <div class="hd">
                            <ul></ul>
                        </div>
                    </div>
                    <?php if ($this->_var['spec_attr']['floorMode'] == 4): ?>
                    <div class="floor-left-adv">
                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                        <?php if ($this->_foreach['adv']['iteration'] == 3): ?>
                        <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x450.jpg<?php endif; ?>"></a>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
            <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                <ul class="p-list"></ul>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <div class="floor-fd">
        <div class="floor-fd-slide">
            <div class="bd">
                <ul class="current" data-catGoods="<?php echo $this->_var['spec_attr']['top_goods']; ?>" ectype="identi_floorgoods" data-identi="1" data-flooreveval="0" data-visualhome="1" data-floornum="10" data-id="<?php echo $this->_var['spec_attr']['cat_id']; ?>" data-floorcat="2">
                    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                    <li>
                        <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                        <div class="p-info">
                            <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                            <div class="p-price">
                            	<div class="shop-price">
                                    <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                    <?php echo $this->_var['list']['promote_price']; ?>
                                    <?php else: ?>
                                    <?php echo $this->_var['list']['shop_price']; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; else: ?>
                    <li>
                        <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-info">
                            <div class="p-name"><a href="#" target="_blank">唐人基 灌汤鱼丸180g*4袋 福州鱼丸 贡丸冷冻肉丸海鲜</a></div>
                            <div class="p-price"><em>¥</em>370.50</div>
                        </div>
                    </li>
                    <li>
                        <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-info">
                            <div class="p-name"><a href="#" target="_blank">唐人基 灌汤鱼丸180g*4袋 福州鱼丸 贡丸冷冻肉丸海鲜</a></div>
                            <div class="p-price"><em>¥</em>370.50</div>
                        </div>
                    </li>
                    <li>
                        <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-info">
                            <div class="p-name"><a href="#" target="_blank">唐人基 灌汤鱼丸180g*4袋 福州鱼丸 贡丸冷冻肉丸海鲜</a></div>
                            <div class="p-price"><em>¥</em>370.50</div>
                        </div>
                    </li>
                    <li>
                        <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-info">
                            <div class="p-name"><a href="#" target="_blank">唐人基 灌汤鱼丸180g*4袋 福州鱼丸 贡丸冷冻肉丸海鲜</a></div>
                            <div class="p-price"><em>¥</em>370.50</div>
                        </div>
                    </li>
                    <li>
                        <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-info">
                            <div class="p-name"><a href="#" target="_blank">唐人基 灌汤鱼丸180g*4袋 福州鱼丸 贡丸冷冻肉丸海鲜</a></div>
                            <div class="p-price"><em>¥</em>370.50</div>
                        </div>
                    </li>
                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </div>
            <a href="javascript:void(0);" class="ff-prev"></a>
            <a href="javascript:void(0);" class="ff-next"></a>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'homeFloorSix'): ?>
    
<div class="floor-line-con floorSix <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
    <div class="floor-hd" ectype="floorTit">
        <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="hd-tit"><i class="icon"></i><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?>主分类名称<?php endif; ?></div><?php endif; ?>
        <div class="hd-tags">
            <ul>
                <li class="first current"data-catGoods="<?php echo $this->_var['spec_attr']['top_goods']; ?>" <?php if ($this->_var['spec_attr']['floorMode'] > 2): ?> ectype="floor_cat_content" <?php endif; ?>data-flooreveval="0" data-visualhome="1" data-floornum="<?php if ($this->_var['spec_attr']['floorMode'] == 3): ?>6<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>8<?php else: ?>0<?php endif; ?>" data-id="<?php echo $this->_var['spec_attr']['cat_id']; ?>" data-floorcat="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>2<?php else: ?>0<?php endif; ?>">新品推荐</li>
                <?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="10" data-id="<?php echo $this->_var['cat']['cat_id']; ?>"><?php echo $this->_var['cat']['cat_name']; ?></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="floor-bd FS-bd-more-0<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>1<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>2<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>3<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>4<?php endif; ?>">
        <div class="bd-left">
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x480.jpg<?php endif; ?>"></a></li>
                       <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd">
                    <ul></ul>
                </div>
            </div>
            <?php if ($this->_var['brand_list']): ?>
            <div class="floor-brand">
                <div class="fb-bd">
                    <ul>
                        <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank" title="<?php echo $this->_var['list']['brand_name']; ?>"><img src="<?php echo $this->_var['list']['brand_logo']; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <a href="javascript:void(0);" class="fs_prev"><i class="iconfont icon-left"></i></a>
                <a href="javascript:void(0);" class="fs_next"><i class="iconfont icon-right"></i></a>
            </div>
            <?php endif; ?>
        </div>
        <div class="bd-right">
        	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
            <div class="floor-tabs-content clearfix">
            	<div class="f-r-main f-r-m-adv">
                    <div class="floor-left-adv">
                        <ul>
                            <li class="f-bd-item child-double">
                                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x240.jpg<?php endif; ?>"></a>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </li>
                            
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] > 2): ?>
                            <li class="f-bd-item"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x480.jpg<?php endif; ?>"></a></li>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </ul>
                    </div>
                </div>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                    <ul class="p-list"></ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
            <div class="floor-left-adv">
            	<ul>
                    <li class="f-bd-item child-double">
                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x240.jpg<?php endif; ?>"></a>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </li>
                </ul>
            </div>   
            <?php endif; ?>
            
            <?php if ($this->_var['spec_attr']['floorMode'] != 1): ?>
            <div class="floor-tabs-content">
                <div class="f-r-main f-r-curr" ectype="floor_cat_<?php echo $this->_var['spec_attr']['cat_id']; ?>">
                    <ul class="p-list">
                        <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <li class="child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                            <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['list']['promote_price']; ?>
                                        <?php else: ?>
                                        <?php echo $this->_var['list']['shop_price']; ?>
                                        <?php endif; ?>
                                </div>
                            </div>
                            <a href="<?php echo $this->_var['list']['url']; ?>" target="_blank" class="fr-btn">立即购买</a>
                        </li>
                        <?php endforeach; else: ?>
                        <li class="child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn">立即购买</a>
                        </li>
                        <li class="child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn">立即购买</a>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn">立即购买</a>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn">立即购买</a>
                        </li>
                        <?php if ($this->_var['spec_attr']['floorMode'] == 3 || $this->_var['spec_attr']['floorMode'] == 4): ?>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn">立即购买</a>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn">立即购买</a>
                        </li>
                        <?php if ($this->_var['spec_attr']['floorMode'] == 4): ?>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn">立即购买</a>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn">立即购买</a>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                    <ul class="p-list"></ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php endif; ?>
            <?php if ($this->_var['spec_attr']['floorMode'] == 3): ?>
            <div class="floor-left-adv">
                <ul>
                    <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                    <li class="f-bd-item"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x480.jpg<?php endif; ?>"></a></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'homeFloorSeven'): ?>

<div class="floor-line-con floorSeven <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
    <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="ftit"><h3><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?>主分类名称<?php endif; ?></h3></div><?php endif; ?>
    <div class="floor-bd FSE-bd-more-0<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>1<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>2<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>3<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>4<?php elseif ($this->_var['spec_attr']['floorMode'] == 5): ?>5<?php endif; ?>">
        <div class="bd-left">
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x440.jpg<?php endif; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd">
                    <ul></ul>
                </div>
            </div>
            <div class="floor-nav">
                <ul>
                    <li class="current" data-catGoods="<?php echo $this->_var['spec_attr']['top_goods']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="10" data-id="<?php echo $this->_var['spec_attr']['cat_id']; ?>" data-floorcat="2">新品推荐<i></i></li>
                    <?php if ($this->_var['spec_attr']['cateValue']): ?>
                    <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                    <?php if ($this->_var['cat']['cat_name']): ?>
                    <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="10" data-id="<?php echo $this->_var['cat']['cat_id']; ?>" data-floorcat="2"><?php echo $this->_var['cat']['cat_name']; ?><i></i></li>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="bd-right">
        	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
            <div class="floor-left-adv">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x440.jpg<?php endif; ?>"></a>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php endif; ?>
            <div class="floor-tabs-content">
            	<?php if ($this->_var['spec_attr']['floorMode'] == 1 || $this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 5): ?>
                <div class="f-r-main f-r-curr">
                    <ul class="p-list<?php if ($this->_var['spec_attr']['floorMode'] == 5): ?> p-list-six<?php endif; ?>" ectype="pList">
                    	<?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>
                        <li class="child-double opacity_img">
                        	<div class="floor-left-adv">
                            	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x220.jpg<?php endif; ?>"></a>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </div>
                        </li>
                        <?php endif; ?>
                        
                        <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <li class="li <?php if ($this->_var['spec_attr']['floorMode'] == 1 || $this->_var['spec_attr']['floorMode'] == 5): ?><?php if ($this->_foreach['goods']['iteration'] < 4): ?>child-curr <?php endif; ?><?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                    <div class="shop-price">
                                        <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['list']['promote_price']; ?>
                                        <?php else: ?>
                                        <?php echo $this->_var['list']['shop_price']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; else: ?>
                        <li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    <?php if ($this->_var['spec_attr']['floorMode'] == 5): ?>
                    <div class="floor-left-adv">
                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x440.jpg<?php endif; ?>"></a>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
                <div class="f-r-main f-r-curr">
                	<ul class="p-list p-list-two" ectype="pList">
                    	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <?php if ($this->_foreach['goods']['iteration'] < 3): ?>
                        <li class="li <?php if ($this->_foreach['goods']['iteration'] == 1): ?>child-curr <?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                    <div class="shop-price">
                                        <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['list']['promote_price']; ?>
                                        <?php else: ?>
                                        <?php echo $this->_var['list']['shop_price']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; else: ?>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    <div class="floor-left-adv">
                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x440.jpg<?php endif; ?>"></a>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </div>
                    <ul class="p-list p-list-four" ectype="pList">
                    	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <?php if ($this->_foreach['goods']['iteration'] > 2 && $this->_foreach['goods']['iteration'] < 7): ?>
                        <li class="li <?php if ($this->_foreach['goods']['iteration'] < 3): ?>child-curr <?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                    <div class="shop-price">
                                        <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['list']['promote_price']; ?>
                                        <?php else: ?>
                                        <?php echo $this->_var['list']['shop_price']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; else: ?>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
                <div class="f-r-main f-r-curr">
                    <ul class="p-list" ectype="pList">
                        <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <?php if ($this->_foreach['goods']['iteration'] < 6): ?>
                        <li class="li<?php if ($this->_foreach['goods']['iteration'] < 6): ?> child-curr <?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                    <div class="shop-price">
                                        <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['list']['promote_price']; ?>
                                        <?php else: ?>
                                        <?php echo $this->_var['list']['shop_price']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; else: ?>
                        <li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        <li class="child-double opacity_img">
                        	<div class="floor-left-adv">
                            	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x220.jpg<?php endif; ?>"></a>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </div>
                        </li>
                        <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <?php if ($this->_foreach['goods']['iteration'] == 6): ?>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                    <div class="shop-price">
                                        <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['list']['promote_price']; ?>
                                        <?php else: ?>
                                        <?php echo $this->_var['list']['shop_price']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; else: ?>
                        <li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ($this->_var['brand_list']): ?>
    <div class="floor-fd">
        <div class="floor-fd-brand clearfix">
            <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
            <div class="item">
                <a href="<?php echo $this->_var['list']['url']; ?>" target="_blank">
                    <div class="link-l"></div>
                    <div class="img"><img src="<?php echo $this->_var['list']['brand_logo']; ?>" title="<?php echo $this->_var['list']['brand_name']; ?>"></div>
                    <div class="link"></div>
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <?php else: ?>
    <?php if ($this->_var['spec_attr']['cat_id'] == 0): ?>
    <div class="floor-fd">
        <div class="floor-fd-brand clearfix" ectype="defaultBrand">
            <div class="item">
                <a href="#" target="_blank">
                    <div class="link-l"></div>
                    <div class="img"><img src="data/gallery_album/visualDefault/homeIndex_010.jpg" title="esprit"></div>
                    <div class="link"></div>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>
<?php endif; ?>