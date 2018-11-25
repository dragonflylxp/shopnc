<?php defined('Inshopec') or exit('Access Invalid!');?>
<style type="text/css">

.ke-container{width: 400px !important;}	
.mb-item-edit-content {
background: #EFFAFE url(<?php echo ADMIN_TEMPLATES_URL;
?>/images/cms_edit_bg_line.png) repeat-y scroll 0 0;
}
</style>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/kindeditor/kindeditor-min.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/kindeditor/lang/zh_CN.js" charset="utf-8"></script>
<?php if($item_edit_flag) { ?>
<!-- 操作说明 -->
<div class="explanation" id="explanation">
  <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
    <h4 title="<?php echo '操作说明';?>"><?php echo '操作说明';?></h4>
    <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
  <ul>
    <li>从右侧编辑框输入内容，点击添加按钮完成添加</li>
    <li>鼠标移动到已有简介上，会出现删除按钮可以对简介进行删除</li>
    <li>操作完成后点击保存编辑按钮进行保存,保存完毕即可看效果</li>
  </ul>
</div>
<?php } ?>
<div class="index_block goods-list">
  <?php if($item_edit_flag) { ?>
  <h3>简介版块</h3>
  <?php } ?>
  <div class="title">
    <?php if($item_edit_flag) { ?>
    <h5>标题：</h5>
    <input id="home1_title" type="text" class="txt w200" name="item_data[title]" value="<?php echo $item_data['title'];?>">
    <?php } else { ?>
    <span><?php echo $item_data['title'];?></span>
    <?php } ?>
  </div>
  <div nctype="item_content" class="content">
    <?php if($item_edit_flag) { ?>
    <h5>内容：</h5>
    <?php } ?>
    <?php if(!empty($item_data['article_content']) ) {?>

    <div nctype="item_image" class="item">

      <div class="goods-name" nctype="goods_name">
      	<div class="default">
      	<?php echo $item_data['article_content'];?>
      		</div>
      	</div>

      <?php if($item_edit_flag) { ?>

      <a nctype="btn_del_item_image" href="javascript:;"><i class="fa fa-trash-o
"></i>删除</a>
      <?php } ?>
    </div>

    <?php } ?>
  </div>
</div>
<?php if($item_edit_flag) { ?>
<div class="search-goods">
  <h3>编辑简介内容</h3>

      	 <dl class="row">
        <dt class="tit">

        </dt>
        <dd class="opt">
          <?php showEditor('article_content', $value='', $width='200px', $height='300px', $style='visibility:hidden;',$upload_state="false", $media_open=false, $type='jj');?>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>

      <a id="mb_special_goods_list" name="getHtml" class="ncap-btn" nctype="btn_add_jj" href="javascript:;" style="vertical-align: top; margin-top: 10px;">添加</a>
</div>
<?php } ?>
<script id="item_goods_template" type="text/html">
    <div nctype="item_image" class="item"> 
        <div class="goods-name" nctype="goods_name">成功添加！</div>
        <div class="goods-data" style="display:none;"nctype="goods_data"><%=goods_data%></div>
        <a nctype="btn_del_item_image" href="javascript:;">删除</a>
    </div>
</script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script> 
<script type="text/javascript">
    $(document).ready(function(){
        $('#btn_mb_special_goods_search').on('click', function() {
            var url = '<?php echo urlShop('mb_store_decoration', 'goods_list');?>';
            var keyword = $('#txt_goods_name').val();
            if(keyword) {
                $('#mb_special_goods_list').load(url + '&' + $.param({keyword: keyword}));
            }
        });
			var editor;
			KindEditor.ready(function(K) {
				editor = K.create('textarea[name="article_content"]', {
					allowFileManager : true
				});
				K('#mb_special_goods_list').click(function(e) {
					var item = {};
					item.goods_data = editor.html();
					item.goods_name = editor.text();
					var html = template.render('item_goods_template', item);
					if($('.item').length && $('.item').length>0){
						alert("请删除后在添加");
					}else{
						$('[nctype="item_content"]').append(html);
					}
					
		
				});			
			});
//      $('#mb_special_goods_list').on('click', '[nctype="btn_add_jj"]', function() {
//      	
//
//		
//      	
//      	
//      	
//      	
//          var item = {};
//          item.goods_id = $(this).attr('data-goods-id');
//          item.goods_name = $(this).attr('data-goods-name');
//          item.goods_price = $(this).attr('data-goods-price');
//          item.goods_image = $(this).attr('data-goods-image');
//          var html = template.render('item_goods_template', item);
//          $('[nctype="item_content"]').append(html);
//      });
    });
</script> 
