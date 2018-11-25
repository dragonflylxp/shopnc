<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商品管理</h3>
        <h5>商城所有分销商品索引及管理</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['goods_index_help1'];?></li>
      <li><?php echo $lang['goods_index_help2'];?></li>
      <li>设置项中可以查看商品详细、查看商品SKU。查看商品详细，跳转到商品详细页。查看商品SKU，显示商品的SKU、图片、价格、库存信息。</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?con=distri_goods&fun=get_goods_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 180, sortable : false, align: 'center', className: 'handle'},
            {display: 'SPU', name : 'goods_commonid', width : 60, sortable : true, align: 'center'},
            {display: '商品名称', name : 'goods_name', width : 150, sortable : false, align: 'left'},
            {display: '商品价格(元)', name : 'goods_price', width : 100, sortable : true, align: 'center'},
            {display: '商品状态', name : 'goods_state', width : 60, sortable : true, align: 'center'},
            {display: '审核状态', name : 'goods_verify', width : 60, sortable : false, align: 'center'},
            {display: '商品图片', name : 'goods_image', width : 60, sortable : true, align: 'center'},
            {display: '广告词', name : 'goods_jingle', width : 150, sortable : true, align: 'left'},
            {display: '分类ID', name : 'gc_id', width : 60, sortable : true, align: 'center'},
            {display: '分类名称', name : 'gc_name', width : 180, sortable : true, align: 'center'},
            {display: '店铺ID', name : 'store_id', width : 60, sortable : true, align: 'center'},
            {display: '店铺名称', name : 'store_name', width : 80, sortable : true, align: 'left'},
            {display: '店铺类型', name : 'is_own_shop', width : 80, sortable : true, align: 'center'},
            {display: '品牌名称', name : 'brand_name', width : 80, sortable : true, align: 'left'},
            {display: '分销发布时间', name : 'dis_add_time', width : 100, sortable : true, align: 'center'},
            {display: '分销佣金比例', name : 'dis_commis_rate', width : 80, sortable : true, align: 'center'},
            {display: '销量', name : 'sale_count', width : 80, sortable : true, align: 'center'},
            {display: '点击量', name : 'click_count', width : 80, sortable : true, align: 'center'}            
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operation }
            ],
        searchitems : [
            {display: 'SPU', name : 'goods_commonid'},
            {display: '商品名称', name : 'goods_name'},
            {display: '分类ID', name : 'gc_id'},
            {display: '店铺ID', name : 'store_id'},
            {display: '店铺名称', name : 'store_name'},
            {display: '品牌名称', name : 'brand_name'}
            ],
        sortname: "goods_commonid",
        sortorder: "desc",
        title: '商品列表'
    });


    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?con=distri_goods&fun=get_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?con=distri_goods&fun=get_xml'}).flexReload();
        $("#formSearch")[0].reset();
    });
});

function fg_operation(name, bDiv) {
    if (name == 'csv') {
        if ($('.trSelected', bDiv).length == 0) {
            if (!confirm('您确定要下载全部数据吗？')) {
                return false;
            }
        }
        var itemids = new Array();
        $('.trSelected', bDiv).each(function(i){
            itemids[i] = $(this).attr('data-id');
        });
        fg_csv(itemids);
    }
}

function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&fun=export_csv&type=<?php echo $output['type'];?>&id=' + id;
}


//商品取消分销
function fg_del(id) {
    if(confirm('取消后将不能恢复且所有分销员对该商品的分销都将失效，确认取消这项吗？')){
        $.getJSON('index.php?con=distri_goods&fun=del_distri', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg);
            }
        });
    }
}
</script> 
