<?php defined('Inshopec') or exit('Access Invalid!');?>
<style type="text/css">
    .flexigrid .bDiv tr:nth-last-child(2) span.btn ul { bottom: 0; top: auto}
</style>


<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>资讯管理</h3
                <h5>管理数据的新增、编辑、删除</h5>
            </div>
        </div>
    </div>
    
    <div id="flexigrid"></div>
    
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){
        $("#flexigrid").flexigrid({
            url: 'index.php?con=mb_news&fun=get_xml',
            colModel : [
                {display: '操作', name : 'operation', width : 100, sortable : false, align: 'center', className: 'handle'},
                {display: '分类', name : 'cate_name', width : 80, sortable : false, align: 'center'},
                {display: '资讯名称', name : 'news_name', width : 220, sortable : false, align: 'center'},
                {display: '资讯图片', name : 'news_image', width : 120, sortable : true, align: 'center'},
                ],
            buttons : [
                {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '添加一条新数据到列表', onpress : fg_operation }                   
            ],
            searchitems : [
                {display: '分类ID', name : 'cate_id', isdefault: true},
                {display: '资讯名称', name : 'news_name'},
            ],
            sortname: "video_id",
            sortorder: "desc",
            title: '资讯列表'
        });
    });

    function fg_operation(name, bDiv) {
        if (name == 'add') {
            window.location.href = 'index.php?con=mb_news&fun=news_add';
        } else if (name == 'del') {
            if ($('.trSelected', bDiv).length == 0) {
                showError('请选择要操作的数据项！');
            }
            var itemids = new Array();
            $('.trSelected', bDiv).each(function(i){
                itemids[i] = $(this).attr('data-id');
            });
            fg_del(itemids);
        }
    }
    function fg_del(ids) {
        if (typeof ids == 'number') {
            var ids = new Array(ids.toString());
        };
        id = ids.join(',');
        if(confirm('删除后将不能恢复，确认删除这项吗？')){
            $.getJSON('index.php?con=mb_news&fun=news_del', {id:id}, function(data){
                if (data.state) {
                    location.reload();
                } else {
                    showError(data.msg)
                }
            });
        }
    }
</script>