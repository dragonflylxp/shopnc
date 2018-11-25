<?php defined('Inshopec') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>结算管理</h3>
        <h5>实物商品分销结算账单表</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>查看分销员分销佣金结算情况</li>
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
            <dl>
              <dt>订单编号</dt>
              <dd>
                <input type="text" value="" name="order_sn" id="order_sn" class="s-input-txt">
              </dd>
            </dl>
            <dl>
              <dt>商品名称</dt>
              <dd>
              <label><input type="text" value="" name=goods_name id="goods_name" class="s-input-txt"></label>
              <label><input type="checkbox" value="1" name="jq_query">精确</label>
              </dd>
            </dl>
            <dl>
              <dt>结算状态</dt>
              <dd>
                    <select class="s-select" name="log_state">
                    <option value="">-请选择-</option>
                    <option value="0">未结算</option>
                    <option value="1">已经结算</option>
                    </select>
              </dd>
            </dl>            
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
    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?con=distri_bill&fun=get_bill_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?con=distri_bill&fun=get_bill_xml'}).flexReload();
        $("#formSearch")[0].reset();
    });
    $("#flexigrid").flexigrid({
        url: 'index.php?con=distri_bill&fun=get_bill_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '结算编号', name : 'log_id', width : 60, sortable : true, align: 'center'}, 
            {display: '订单编号', name : 'order_sn', width : 120, sortable : true, align: 'center'}, 
			      {display: '商品名称', name : 'goods_name', width : 100, sortable : true, align: 'left'},
            {display: '添加时间', name : 'add_time', width : 60, sortable : true, align: 'center'},
			      {display: '支付金额', name : 'pay_goods_amount', width: 50, sortable : true, align : 'center'},                                           
            {display: '退款金额', name : 'refund_amount', width : 60, sortable : true, align: 'center'},
			      {display: '分销佣金比例', name : 'dis_commis_rate', width: 70, sortable : true, align : 'center'},
			      {display: '分销佣金', name : 'dis_pay_amount', width: 70, sortable : true, align : 'center'}, 
            {display: '结算时间', name : 'dis_pay_time', width : 80, sortable : true, align: 'center'},
            {display: '结算状态', name : 'log_state', width : 70, sortable : true, align: 'center'},
            {display: '商家ID', name : 'store_id', width : 90, sortable : true, align: 'center'},
            {display: '分销员ID', name : 'dis_member_id', width : 90, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出csv文件,如果不选中行，将导出列表所有数据', onpress : fg_operate}
        ],
        searchitems : [
           {display: '订单编号', name : 'order_sn'},
		       {display: '商品名称', name : 'goods_name'}
        ],
        sortname: "log_id",
        sortorder: "desc",
        title: '分销佣金结算列表'
    });
});
function fg_operate(name, grid) {
    if (name == 'csv') {
    	var itemlist = new Array();
        if($('.trSelected',grid).length>0){
            $('.trSelected',grid).each(function(){
            	itemlist.push($(this).attr('data-id'));
            });
        }
        fg_csv(itemlist);
    }
}
function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&fun=export_bill&ob_id='+id;
}
</script> 
