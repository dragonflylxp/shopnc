<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>地区管理管理</h3>
        <h5>管理人审核及相关操作</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
        <li>点击查看按钮可以查看地区管理人提交申请的信息</li>
      <li>点击审核按钮可以对地区管理人提交的申请信息进行审核</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?con=manager&fun=get_manager_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '管理人ID', name : 'member_id', width : 60, sortable : true, align: 'center'},
            {display: '公司名称', name : 'complete_company_name', width : 150, sortable : false, align: 'center'},
            {display: '公司电话', name : 'company_phone', width : 100, sortable : false, align: 'center'},
            {display: '申请状态', name : 'apply_state', width: 60, sortable : true, align : 'center'},
            {display: '法人姓名', name : 'legal_person_name', width: 80, sortable : false, align : 'center'},
            {display: '法人身份证', name : 'id_number', width: 150, sortable : false, align : 'center'},
            {display: '联系人姓名', name : 'contacts_name', width : 80, sortable : true, align: 'center'},
            {display: '联系人电话', name : 'contacts_phone', width : 100, sortable : true, align: 'center'},
            {display: '电子邮箱', name : 'contacts_email', width : 100, sortable : true, align: 'center'},
            {display: '所在地区', name : 'company_address', width : 150, sortable : false, align : 'left'},
            {display: '详细地址', name : 'company_address_detail', width : 200, sortable : false, align : 'left'}
        ],
        searchitems : [
            {display: '管理人ID', name : 'member_id', isdefault: true},
            {display: '公司名称', name : 'complete_company_name'},
            {display: '公司电话', name : 'company_phone'},
            {display: '法人姓名', name : 'legal_person_name'}

            ],
        sortname: "member_id",
        sortorder: "desc",
        title: '地区管理人申请列表'
    });
});

function test(name, bDiv) {
    if (name == 'excel') {
        confirm('Delete ' + $('.trSelected', bDiv).length + ' items?')
    } else if (name == 'Add') {
        alert('Add New Item');
    }
}
</script> 
