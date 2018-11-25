<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>分销商管理</h3>
        <h5>分销会员及认证管理</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>管理平台中的分销员，在认证申请中可对普通用户提出的分销申请进行审核</li>
      <li>在管理中可清退分销员或查看每个分销员的分销订单列表</li>
      <li>分销员被清退后失去分销员身份成为普通会员</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?con=distri_member&fun=get_xml&mem_state=<?php echo intval($output['mem_stat']);?>',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center'},
            {display: '会员ID', name : 'member_id', width : 40, sortable : true, align: 'center'},
            {display: '会员名称', name : 'member_name', width : 150, sortable : true, align: 'left'},
            <?php if(intval($output['mem_stat']) == 1){?>
             {display: '申请状态', name : 'distri_stat', width : 60, sortable : true, align: 'left'},
             {display: '会员手机', name : 'member_mobile', width : 80, sortable : true, align: 'center'},
             {display: '会员邮箱', name : 'member_email', width : 150, sortable : true, align: 'left'},
             {display: '申请时间', name : 'distri_time', width : 80, sortable : true, align: 'center'},
             {display: '通过时间', name : 'distri_handle_time', width : 80, sortable : true, align: 'center'}
            <?php }else{?>
            {display: '会员邮箱', name : 'member_email', width : 150, sortable : true, align: 'left'},
            {display: '会员手机', name : 'member_mobile', width : 80, sortable : true, align: 'center'}, 
            {display: '分销单数', name : 'order_count', width : 100, sortable : true, align: 'center'},
            {display: '已结佣金(元)', name : 'had_pay_amount', width : 100, sortable : true, align: 'center'},
            {display: '未结佣金(元)', name : 'unpay_amount', width : 100, sortable : true, align: 'center'},
            {display: '分销总额(元)', name : 'distri_amount', width : 100, sortable : true, align: 'center'},
            <?php }?>
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operation }		
            ],
        searchitems : [
            {display: '会员ID', name : 'member_id'},
            {display: '会员名称', name : 'member_name'}
            ],
        sortname: "member_id",
        sortorder: "desc",
        title: '认证分销商列表'
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
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&fun=export_csv&id=' + id;
}

//商品取消分销
function fg_del(id) {
    if(confirm('清退后将不能恢复且该分销员及其的所分销的商品都将失效，确认清退该分销员吗？')){
        $.getJSON('index.php?con=distri_member&fun=member_cancle', {member_id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg);
            }
        });
    }
}

</script> 

