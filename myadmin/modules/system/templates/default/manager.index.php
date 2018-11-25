<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>管理人列表</h3>
        <h5>管理人列表展示</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current">管理人列表</a></li>
          <li><a href="index.php?con=manager_member&fun=index">管理人绑定列表</a></li>
      </ul>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>平台管理员可对管理人进行增加、编辑和删除</li>
      <li>增加管理人将展示在管理人列表</li>
       <li>提示：绑定管理人之前需新增管理人；如果管理已绑定地区，请先解绑后才能删除</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?con=manager&fun=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
//            {display: '管理人ID', name : 'manager_id', width : 60, sortable : true, align: 'center'},
            {display: '会员ID', name : 'member_id', width : 60, sortable : true, align: 'center'},
            {display: '管理人账户', name : 'member_name', width : 80, sortable : false, align: 'left'},
            {display: '管理人公司名', name : 'company_name', width : 180, sortable : false, align: 'left'},
            {display: '添加时间', name : 'add_time', width: 120, sortable : true, align : 'center'},
            {display: '是否绑定', name : 'is_bind', width: 60, sortable : true, align : 'center'},
            {display: '审核状态', name : 'apply_state', width: 60, sortable : true, align : 'center'},
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增管理人', name : 'add_manager', bclass : 'add_manager', title : '新增管理人', onpress : fg_operation },
            {display: '<i class="fa fa-plus"></i>绑定管理人', name : 'add', bclass : 'add', title : '绑定管理人', onpress : fg_operation },
        ],
        searchitems : [
            {display: '管理人ID', name : 'manager_id', isdefault: true},
            {display: '管理人公司名', name : 'company_name', isdefault: true},
            ],
        sortname: "manager_id",
        sortorder: "desc",
        title: '管理人列表'
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
function fg_del(id,mid) {
    if(!confirm('删除后将不能恢复，确认删除这项吗？')){
        return false;
    }
	window.location.href = "index.php?con=manager&fun=delete&manager_id="+id+"&member_id="+mid;
}
</script>
