<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>地区管理人列表</h3>
        <h5>地区管理人列表展示</h5>
      </div>
      <ul class="tab-base nc-row">
          <li><a href="index.php?con=manager&fun=index">管理人列表</a></li>
          <li><a href="JavaScript:void(0);" class="current">管理人绑定列表</a></li>
      </ul>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>平台管理员可对地区管理人进行增加、编辑和删除</li>
      <li>绑定地区后的管理人将展示在管理人列表</li>
       <li>提示：请尽量在每月1号6点-24点之间绑定或修改管理人</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?con=manager_member&fun=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '绑定编号', name : 'mid', width : 100, sortable : true, align: 'center'},
            {display: '会员ID', name : 'uid', width : 60, sortable : true, align: 'center'},
            {display: '管理人账户', name : 'member_name', width : 80, sortable : false, align: 'left'},
            {display: '管理人公司名', name : 'company_name', width : 180, sortable : false, align: 'left'},
            {display: '管理人级别', name : 'grade', width : 120, sortable : true, align: 'center'},
            {display: '管理区域', name : 'area', width : 180, sortable : false, align: 'left'},
            {display: '提成比例', name : 'point', width: 60, sortable : true, align : 'center'},
            {display: '绑定时间', name : 'add_time', width: 120, sortable : true, align : 'center'},
            {display: '修改时间', name : 'update_time', width: 120, sortable : true, align : 'center'},
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>绑定管理人', name : 'add', bclass : 'add', title : '绑定管理人', onpress : fg_operation },
            {display: '<i class="fa fa-plus"></i>新增管理人', name : 'add_manager', bclass : 'add_manager', title : '新增管理人', onpress : fg_operation },
        ],
        searchitems : [
            {display: '管理人ID', name : 'uid', isdefault: true},
            {display: '管理人账户', name : 'manager_account'},
            {display: '管理人公司名', name : 'company_name'},
            ],
        sortname: "mid",
        sortorder: "desc",
        title: '地区管理人列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?con=manager_member&fun=manager_member_add';
    }
    if (name == 'add_manager') {
        window.location.href = 'index.php?con=manager&fun=add';
    }
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

//删除
function fg_del(id) {
    if(!confirm('删除后将不能恢复，确认删除这项吗？')){
        return false;
    }
	window.location.href = "index.php?con=manager_member&fun=manager_member_delete&mid="+id;
}
</script>
