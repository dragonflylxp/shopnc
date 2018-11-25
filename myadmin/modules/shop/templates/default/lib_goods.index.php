<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商品库管理</h3>
        <h5>管理数据的新增、编辑、删除</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>商品库中数据的删除不影响店铺已经认领的商品。</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?con=lib_goods&fun=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '商品名称', name : 'goods_name', width : 150, sortable : false, align: 'left'},
            {display: '商品图片', name : 'goods_image', width : 60, sortable : false, align: 'center'},
            {display: '商品视频', name : 'goods_video', width : 60, sortable : false, align: 'center'},
            {display: '广告词', name : 'goods_jingle', width : 150, sortable : false, align: 'left'},
            {display: '分类ID', name : 'gc_id', width : 60, sortable : false, align: 'center'},
            {display: '分类名称', name : 'gc_name', width : 180, sortable : false, align: 'center'},
            {display: '品牌ID', name : 'brand_id', width : 60, sortable : false, align: 'center'},
            {display: '品牌名称', name : 'brand_name', width : 80, sortable : false, align: 'left'},
            {display: '发布时间', name : 'goods_addtime', width : 120, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '添加一条新数据到列表', onpress : fg_operation }
            ],
        searchitems : [
            {display: '商品名称', name : 'goods_name'},
            {display: '广告词', name : 'goods_jingle'},
            {display: '分类ID', name : 'gc_id'},
            {display: '品牌名称', name : 'brand_name'}
            ],
        sortname: "goods_addtime",
        sortorder: "desc",
        title: '商品列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?con=lib_goods&fun=goods_class';
    }
}
function fg_operation_del(goods_id){
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        var _url = 'index.php?con=lib_goods&fun=del_goods&goods_id='+goods_id;
        $.getJSON(_url, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
//预览视频
function fg_see_video(ids) {
      _uri = "index.php?con=lib_goods&fun=see_video&id=" + ids;
      CUR_DIALOG = ajax_form('see_video', '预览视频', _uri, 640);
}
</script> 
