<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>地区管理人管理</h3>
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
      <li>点击设置按钮可以设置地区管理人提交申请的信息</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
    <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
    <div class="ncap-search-bar">
      <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
      <div class="title">
        <h3>高级搜索</h3>
      </div>
      <form method="get" name="formSearch" id="formSearch">
        <div id="searchCon" class="content">
          <div class="layout-box">
              </dl>
              <dl>
                  <dt>管理人ID</dt>
                  <dd>
                      <input type="text" value="" name="member_id" id="member_id" class="s-input-txt">
                  </dd>
              </dl>
            <dl>
              <dt>公司名称</dt>
              <dd>
                <input type="text" value="" name="complete_company_name" id="complete_company_name" class="s-input-txt">
              </dd>
            </dl>
              <dl>
                  <dt>公司电话</dt>
                  <dd>
                      <input type="text" value="" name="company_phone" id="company_phone" class="s-input-txt">
                  </dd>
              </dl>
            <dl>
              <dt>法人姓名</dt>
              <dd>
                <input type="text" value="" name="legal_person_name" id="legal_person_name" class="s-input-txt">
              </dd>

          </div>
        </div>
        <div class="bottom">
          <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a>
          <a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a>
        </div>
      </form>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?con=manager&fun=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '管理人ID', name : 'member_id', width : 60, sortable : true, align: 'center'},
            {display: '公司名称', name : 'complete_company_name', width : 150, sortable : false, align: 'center'},
            {display: '公司电话', name : 'company_phone', width : 100, sortable : false, align: 'center'},
            {display: '法人姓名', name : 'legal_person_name', width: 80, sortable : false, align : 'center'},
            {display: '法人身份证', name : 'id_number', width: 150, sortable : false, align : 'center'},
            {display: '联系人姓名', name : 'contacts_name', width : 80, sortable : true, align: 'center'},
            {display: '联系人电话', name : 'contacts_phone', width : 100, sortable : true, align: 'center'},
            {display: '电子邮箱', name : 'contacts_email', width : 100, sortable : true, align: 'center'},
            {display: '所在地区', name : 'company_address', width : 150, sortable : false, align : 'left'},
            {display: '详细地址', name : 'company_address_detail', width : 200, sortable : false, align : 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operation }						
        ],
        searchitems : [
            {display: '管理人ID', name : 'member_id', isdefault: true},
            {display: '公司名称', name : 'complete_company_name'},
            {display: '法人姓名', name : 'legal_person_name'}
            ],
        sortname: "member_id",
        sortorder: "desc",
        title: '地区管理人列表'
    });

    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?con=manager&fun=get_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?con=manager&fun=get_xml'}).flexReload();
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
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&fun=export_csv&id=' + id;
}
</script>