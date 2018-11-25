<?php defined('Inshopec') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_SITE_URL; ?>/resource/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<style type="text/css">
.wrapper_search { width: 999px;}
.wp_data_loading { font-size:12px; background-color: #FFF; display: none; width:120px; height:16px; padding: 20px; border:1px solid #92AED1; margin-left: -81px; margin-top: -28px; position: absolute; z-index:30; left: 50%; top: 50%; filter: alpha(opacity=75); -moz-opacity: 0.75; opacity: .75;}
.data_loading { line-height: 16px; padding-left: 30px;}
.wp_sort { background-color: #FAFAFA; height:318px; padding:15px; margin: 10px auto; border: solid 1px #E6E6E6; position:relative; z-index: 1;}
.sort_title { font-size: 12px; line-height: 30px; color: #777; text-align: center; height: 30px; margin: 0 auto; }
.sort_title .text { font: 12px/20px Arial; color: #777; background-color: #FFF; vertical-align: top; text-align: left; display: inline-block; width: 700px; height: 20px; padding: 4px; border: solid 1px #CCC; outline: 0 none; letter-spacing: normal; word-spacing: normal; *display: inline/*IE6,7*/; cursor:pointer; zoom:1;}
.sort_title .text:hover{ color: #333; border-color: #75B9F0; box-shadow: 0 0 0 2px rgba(82, 168, 236, 0.15); outline: 0 none;}


.sort_title i { color: #CCC; position: absolute; z-index: 1; top: 20px; left: 870px;}
.sort_title:hover i{ color: #333;}
.select_list{ background-color: #FFF; display: none; width: 708px; height: 200px; border: solid 1px #75B9F0; position: absolute; z-index: 2; top: 44px; left: 194px; overflow-y: scroll; overflow-x: hidden;}
.select_list ul { margin:0; padding:0;}
.select_list ul li { display: block; clear: both; border-bottom: dashed 1px #E6E6E6; position: relative; z-index: 1;}
.select_list ul li span{ line-height: 30px; display: block; height: 30px; padding: 0 10px; margin: 0; cursor: pointer;}
.select_list ul li:hover { color:#06C; background-color: #f4fafe;}
.select_list ul li a { width: 16px; height: 16px; color: #27A9E3; position: absolute; z-index: 2; top: 5px; right: 10px;}
.select_list ul li a:hover { text-decoration: none; color: #DA542E;}
.wp_sort_block{ font-size: 0; *word-spacing:-1px/*IE6、7*/; margin: 15px 0 0; overflow: hidden;}
.sort_list { background: #FFF; vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; margin-right: 15px; border: solid 1px #E6E6E6;}
.sort_list { *display: inline/*IE6,7*/;}
.sort_list_last { margin-right: 0;}
.wp_category_list{ width: 284px; height: 264px; padding: 8px 4px 8px 8px; margin:0;}
.wp_category_list.blank { background-color: #F0F0F0;}
.wp_category_list.blank .category_list{ display:none;}
.category_list { height: 264px; overflow: hidden; position: relative; z-index: 1;}
.category_list ul { margin: 0 15px 0 0 ;}
.category_list ul li { clear: both;}
.category_list ul li a { font-size: 12px; line-height: 20px; color: #666; display: block; height: 20px; padding: 4px 8px; margin: 1px; overflow:hidden; }
.category_list ul li a i { font-size: 12px; display: none;}
.category_list ul li a.classDivClick { color: #3A87AD; background-color: #D9EDF7; display: block; margin: 0; border: solid 1px #BCE8F1;}
.category_list ul li a.classDivClick i { font-size: 14px; display: block; margin-left: 6px; float: right;}
.category_list ul li a:hover {text-decoration: none;}
.category_list ul .hight_light { color:#f50;}
.hover_tips_cont { font-size: 0; *word-spacing:-1px/*IE6、7*/; text-align:left; overflow: hidden;}
.hover_tips_cont dt, .hover_tips_cont dd { font-size: 12px; vertical-align: top; letter-spacing: normal; word-spacing: normal; white-space: nowrap; display: inline-block; *display: inline/*IE6,7*/; zoom:1;}
.hover_tips_cont dt { font-weight: 600;}
.hover_tips_cont dd i { margin: 0 5px;}
.bottom .submit-border { margin: 10px auto;}
.bottom .submit { font: 14px/36px "microsoft yahei"; text-align: center; min-width: 100px; *min-width: auto; height: 36px;}
.bottom a.submit { width: 100px; margin: 0 auto;}
.bottom .submit[disabled="disabled"] { color: #999; text-shadow: none; background-color: #F5F5F5; border: solid 1px; border-color: #DCDCDC #DCDCDC #B3B3B3 #DCDCDC; cursor: default;}
.bottom .ncbtn { font-size: 14px; vertical-align: top; padding: 8px 19px; margin: 10px auto;}
</style>
<div class="page wrapper_search">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商品库管理</h3>
        <h5>管理数据的新增、编辑、删除</h5>
      </div>
    </div>
  </div>
  <div class="wp_sort">
    <div id="dataLoading" class="wp_data_loading">
      <div class="data_loading">加载中...</div>
    </div>
    <div class="sort_selector">
      <div class="sort_title">常用的商品分类：
        <div class="text" id="commSelect">
            <div>请选择</div>
            <div class="select_list" id="commListArea">
              <ul>
                <?php if(is_array($output['staple_array']) && !empty($output['staple_array'])) {?>
                <?php foreach ($output['staple_array'] as $val) {?>
                <li  data-param="{stapleid:<?php echo $val['staple_id']?>}"><span nctype="staple_name"><?php echo $val['staple_name']?></span><a href="JavaScript:void(0);" nctype="del-comm-cate" title="<?php echo $lang['nc_delete'];?>">X</a></li>
                <?php }?>
                <?php }?>
                <li id="select_list_no" <?php if (!empty($output['staple_array'])) {?>style="display: none;"<?php }?>><span class="title">还没有添加过常用的分类</span></li>
              </ul>
            </div>
        </div>
        <i class="icon-angle-down"></i>
      </div>
    </div>
    <div id="class_div" class="wp_sort_block">
      <div class="sort_list">
        <div class="wp_category_list">
          <div id="class_div_1" class="category_list">
            <ul>
              <?php if(isset($output['goods_class']) && !empty($output['goods_class']) ) {?>
              <?php foreach ($output['goods_class'] as $val) {?>
              <li class="" nctype="selClass" data-param="{gcid:<?php echo $val['gc_id'];?>,deep:1,tid:<?php echo $val['type_id'];?>}"> <a class="" href="javascript:void(0)"><i class="icon-double-angle-right"></i><?php echo $val['gc_name'];?></a></li>
              <?php }?>
              <?php }?>
            </ul>
          </div>
        </div>
      </div>
      <div class="sort_list">
        <div class="wp_category_list blank">
          <div id="class_div_2" class="category_list">
            <ul>
            </ul>
          </div>
        </div>
      </div>
      <div class="sort_list sort_list_last">
        <div class="wp_category_list blank">
          <div id="class_div_3" class="category_list">
            <ul>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="alert">
    <dl class="hover_tips_cont">
      <dt id="commodityspan"><span style="color:#F00;">请选择商品类别</span></dt>
      <dt id="commoditydt" style="display: none;" class="current_sort">当前选择的商品类别是：</dt>
      <dd id="commoditydd"></dd>
    </dl>
  </div>
  <div class="wp_confirm">
    <form method="get">
      <input type="hidden" name="con" value="lib_goods" />
      <?php if ($output['goods_id']) {?>
      <input type="hidden" name="fun" value="goods_edit" />
      <input type="hidden" name="goods_id" value="<?php echo $output['goods_id'];?>" />
      <?php } else {?>
      <input type="hidden" name="fun" value="goods_add" />
      <?php }?>
      <input type="hidden" name="class_id" id="class_id" value="" />
      <input type="hidden" name="t_id" id="t_id" value="" />
      <div class="bottom tc">
      <label class="submit-border"><input disabled="disabled" nctype="buttonNextStep" value="下一步，填写商品信息" type="submit" class="submit"style=" width: 200px;" /></label>
      </div>
    </form>
  </div>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script> 
<script>
var ADMIN_SITE_URL = "<?php echo ADMIN_SITE_URL; ?>";
// 选择商品分类
function selClass($this){

    $('.wp_category_list').css('background', '');

    $("#commodityspan").hide();
    $("#commoditydt").show();
    $("#commoditydd").show();
    $this.siblings('li').children('a').attr('class', '');
    $this.children('a').attr('class', 'classDivClick');
    var data_str = '';
    eval('data_str = ' + $this.attr('data-param'));
    $('#class_id').val(data_str.gcid);
    $('#t_id').val(data_str.tid);
    $('#dataLoading').show();
    var deep = parseInt(data_str.deep) + 1;
    
    $.getJSON(ADMIN_SITE_URL+'/index.php?con=lib_goods&fun=ajax_goods_class', {gc_id : data_str.gcid, deep: deep}, function(data) {
        if (data != null) {
            $('input[nctype="buttonNextStep"]').attr('disabled', true);
            $('#class_div_' + deep).children('ul').html('').end()
                .parents('.wp_category_list:first').removeClass('blank')
                .parents('.sort_list:first').nextAll('div').children('div').addClass('blank').children('ul').html('');
            $.each(data, function(i, n){
                $('#class_div_' + deep).children('ul').append('<li data-param="{gcid:'
                        + n.gc_id +',deep:'+ deep +',tid:'+ n.type_id +'}"><a class="" href="javascript:void(0)"><i class="icon-double-angle-right"></i>'
                        + n.gc_name + '</a></li>')
                        .find('li:last').click(function(){
                            selClass($(this));
                        });
            });
        } else {
            $('#class_div_' + data_str.deep).parents('.sort_list:first').nextAll('div').children('div').addClass('blank').children('ul').html('');
            disabledButton();
        }
        // 显示选中的分类
        showCheckClass();
        $('#dataLoading').hide();
    });
}
function disabledButton() {
    if ($('#class_id').val() != '') {
        $('input[nctype="buttonNextStep"]').attr('disabled', false).css('cursor', 'pointer');
    } else {
        $('input[nctype="buttonNextStep"]').attr('disabled', true).css('cursor', 'auto');
    }
}

$(function(){
    //自定义滚定条
    $('#class_div_1').perfectScrollbar();
    $('#class_div_2').perfectScrollbar();
    $('#class_div_3').perfectScrollbar();

    // ajax选择分类
    $('li[nctype="selClass"]').click(function(){
        selClass($(this));
    });

    // 返回分类选择
    $('a[nc_type="return_choose_sort"]').unbind().click(function(){
        $('#class_id').val('');
        $('#t_id').val('');
        $("#commodityspan").show();
        $("#commoditydt").hide();
        $('#commoditydd').html('');
        $('.wp_search_result').hide();
        $('.wp_sort').show();
    });
    
    // 常用分类选择 展开与隐藏
    $('#commSelect').hover(
        function(){
            $('#commListArea').show();
        },function(){
            $('#commListArea').hide();
        }
    );
    
    // 常用分类选择
    $('#commListArea').find('span[nctype="staple_name"]').die().live('click',function() {
        $('#dataLoading').show();
        $('.wp_category_list').addClass('blank');
        $this = $(this);
        eval('var data_str = ' + $this.parents('li').attr('data-param'));
        $.getJSON(ADMIN_SITE_URL+'/index.php?con=lib_goods&fun=ajax_show_comm&stapleid=' + data_str.stapleid, function(data) {
            if (data.done) {
                $('.category_list').children('ul').empty();
                if (data.one.length > 0) {
                    $('#class_div_1').children('ul').append(data.one).parents('.wp_category_list').removeClass('blank');
                }
                if (data.two.length > 0) {
                    $('#class_div_2').children('ul').append(data.two).parents('.wp_category_list').removeClass('blank');
                }
                if (data.three.length > 0) {
                    $('#class_div_3').children('ul').append(data.three).parents('.wp_category_list').removeClass('blank');
                }
                // 绑定ajax选择分类事件
                $('#class_div').find('li[nctype="selClass"]').click(function(){
                    selClass($(this));
                });
                $('#class_id').val(data.gc_id);
                $('#t_id').val(data.type_id);
                $("#commodityspan").hide();
                $("#commoditydt").show();
                // 显示选中的分类
                showCheckClass();
                $('#commSelect').children('div:first').html($this.text());
                disabledButton();
                $('#commListArea').hide();
            } else {
                $('.wp_category_list').css('background', '#E7E7E7 none');
                $('#commListArea').find('li').css({'background' : '', 'color' : ''});
                $this.parent().css({'background' : '#3399FD', 'color' : '#FFF'});
            }
        });
        $('#dataLoading').hide();
    });
    
    // ajax删除常用分类
    $('#commListArea').find('a[nctype="del-comm-cate"]').die().live('click',function() {
        $this = $(this);
        eval('var data_str = ' + $this.parents('li').attr('data-param'));
        $.getJSON(ADMIN_SITE_URL+'/index.php?con=lib_goods&fun=ajax_stapledel&staple_id='+ data_str.stapleid, function(data) {
            if (data.done) {
                $this.parents('li:first').remove();
                if ($('#commListArea').find('li').length == 1) {
                    $('#select_list_no').show();
                }
            } else {
                alert(data.msg);
            }
        });
    });
});
// 显示选中的分类
function showCheckClass(){
    var str = "";
    $.each($('a[class=classDivClick]'), function(i) {
        str += $(this).text() + '<i class="icon-double-angle-right"></i>';
    });
    str = str.substring(0, str.length - 39);
    $('#commoditydd').html(str);
}
</script>

