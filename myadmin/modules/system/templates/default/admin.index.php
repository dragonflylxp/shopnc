<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_limit_manage'];?></h3>
        <h5><?php echo $lang['nc_limit_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?con=admin&fun=get_admin_xml',
        colModel : [
            {display: '<?php echo $lang['nc_handle'];?>', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '<?php echo $lang['admin_index_username'];?>', name : 'admin_name', width : 100, sortable : false, align: 'left'}, 
			{display: '<?php echo $lang['admin_index_last_login'];?>', name : 'admin_login_time', width : 120, sortable : false, align : 'left'},           
			{display: '<?php echo $lang['admin_index_login_times'];?>', name : 'admin_login_num', width : 60, sortable : false, align: 'center'},
			{display: '<?php echo $lang['gadmin_name'];?>', name : 'gname', width : 120, sortable : false, align: 'left'}
            ],
        buttons : [
               {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation }
        ],
        title: '管理员列表'
    });
});

function fg_operation(name, grid) {
    if (name == 'add') {
        window.location.href = 'index.php?con=admin&fun=admin_add';
    }
}
function fg_operation_del(admin_id){
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        var _url = 'index.php?con=admin&fun=admin_del&admin_id='+admin_id;
        $.getJSON(_url, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>