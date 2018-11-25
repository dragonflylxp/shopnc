<?php defined('Inshopec') or exit('Access Invalid!');?>
<style type="text/css">
    .flexigrid .bDiv tr:nth-last-child(2) span.btn ul { bottom: 0; top: auto}
</style>


<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>直播管理</h3>
                <h5>手机端所有直播及管理</h5>
            </div>
            <?php echo $output['top_link'];?>
        </div>
    </div>
    
    <div id="flexigrid"></div>
    
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jwpalyer/jwplayer.html5.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jwpalyer/jwplayer.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){
        $("#flexigrid").flexigrid({
            url: 'index.php?con=mb_movie&fun=get_movie_xml',
            colModel : [
                {display: '操作', name : 'operation', width:5, sortable : false, align: 'center', className: 'handle-s'},
                {display: '会员ID', name : 'member_id', width : 50, sortable : true, align: 'center'},
                {display: '会员名称', name : 'member_name', width : 60, sortable : false, align: 'center'},
                {display: '分类', name : 'cate_name', width : 60, sortable : false, align: 'center'},
                {display: '直播标题', name : 'movie_title', width : 200, sortable : true, align: 'center'},
                {display: '直播封面图', name : 'movie_cover_img', width : 120, sortable : true, align: 'center'},
                {display: '直播时间', name : 'add_time', width : 150, sortable : true, align: 'center'},
                {display: '直播状态', name : 'state_desc', width : 60, sortable : true, align: 'center'},
                ],
            buttons : false,
            searchitems : [
                {display: '会员ID', name : 'member_id', isdefault: true},
                {display: '会员名称', name : 'member_name'},
            ],
            sortname: "video_id",
            sortorder: "desc",
            title: '播主列表'
        });
    });


    //关闭直播
    function fg_state(ids) {
        if(confirm('关闭后将不能恢复，确认关闭这项吗？')){
            $.getJSON('index.php?con=mb_movie&fun=movie_off', {id:ids}, function(data){
                if (data.state) {
                    location.reload();
                } else {
                    showError(data.msg)
                }
            });
        }
    }

    //预览直播
    function fg_see_movie(ids) {
          _uri = "index.php?con=mb_movie&fun=see_movie&id=" + ids;
          CUR_DIALOG = ajax_form('see_movie', '预览直播', _uri, 1000);
    }

    
</script>