<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>视频空间</h3>
        <h5>商品视频及商家店铺视频管理</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>媒体库删除后，媒体库内全部视频都会删除，不能恢复，请谨慎操作</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?con=goods_video_album&fun=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: 'ID', name : 'video_class_id', width : 40, sortable : true, align: 'left'},
            {display: '媒体库名称', name : 'video_class_name', width : 120, sortable : true, align: 'left'},
            {display: '店铺ID', name : 'store_id', width : 40, sortable : true, align: 'center'},
            {display: '店铺名称', name : 'store_name', width : 150, sortable : false, align: 'left'},
            {display: '视频数量', name : 'pic_count', width : 150, sortable : false, align: 'center'},
            {display: '媒体库描述', name : 'video_class_des', width : 300, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-video-o"></i>全部视频', name : 'add', bclass : 'add', title : '全部视频', onpress : fg_operation }
        ],
        searchitems : [
            {display: 'ID', name : 'video_class_id'},
            {display: '媒体库名称', name : 'video_class_name'},
            {display: '店铺ID', name : 'store_id'}
            ],
        sortname: "video_class_id",
        sortorder: "asc",
        title: '媒体库列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?con=goods_video_album&fun=video_list';
    }
}
function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?con=goods_video_album&fun=video_class_del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>