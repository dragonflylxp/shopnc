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
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
        </div>
        <ul>
            <li>直播申请审核，只有通过审核，才能进行直播。</li>
        </ul>
    </div>
    <div id="flexigrid"></div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){
        $("#flexigrid").flexigrid({
            url: 'index.php?con=mb_movie&fun=get_xml',
            colModel : [
                {display: '操作', name : 'operation', width : 10, sortable : false, align: 'center', className: 'handle-s'},
                {display: '会员ID', name : 'member_id', width : 50, sortable : true, align: 'center'},
                {display: '会员名称', name : 'member_name', width : 60, sortable : false, align: 'center'},
                {display: '真实姓名', name : 'true_name', width : 60, sortable : false, align: 'center'},
                {display: '身份证号码', name : 'card_number', width : 150, sortable : true, align: 'center'},
                {display: '身份证正面照', name : 'before_image_url', width : 120, sortable : true, align: 'center'},
                {display: '身份证反面照', name : 'behind_image_url', width : 120, sortable : true, align: 'center'},
                {display: '审核状态', name : 'verify_desc', width : 60, sortable : true, align: 'center'},
                ],
            buttons : false,
            searchitems : [
                {display: '会员ID', name : 'member_id', isdefault: true},
                {display: '会员名称', name : 'member_name'},
            ],
            sortname: "movie_id",
            sortorder: "desc",
            title: '直播申请列表'
        });
    });


    function fg_del(ids) {
        if (typeof ids == 'number') {
            var ids = new Array(ids.toString());
        };
        id = ids.join(',');
        if(confirm('删除后将不能恢复，确认删除这项吗？')){
            $.getJSON('index.php?con=mb_movie&fun=movie_del', {id:id}, function(data){
                if (data.state) {
                    location.reload();
                } else {
                    showError(data.msg)
                }
            });
        }
    }

    //直播审核
    function fg_verify(ids) {
        _uri = "index.php?con=mb_movie&fun=movie_verify&id=" + ids;
        CUR_DIALOG = ajax_form('movie_verify', '会员直播审核', _uri, 640);
    }
</script>