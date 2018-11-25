<?php defined('Inshopec') or exit('Access Invalid!');?>
<div id="flexigrid"></div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
    	url: 'index.php?con=bill&fun=get_bill_info_xml&query_type=<?php echo $_GET['query_type'];?>&ob_id=<?php echo $_GET['ob_id'];?>',
        colModel : [
            {display: '商品名称', name : 'goods_name', width : 250, sortable : false, align: 'center'},
            {display: '订单编号', name : 'order_sn', width : 150, sortable : false, align: 'center'}, 
			{display: '支付金额', name : 'pay_goods_amount', width : 70, sortable : false, align: 'left'},
            {display: '退款金额', name : 'refund_amount', width : 70, sortable : false, align: 'left'},
			{display: '佣金比例', name : 'dis_commis_rate', width : 70, sortable : false, align: 'left'},
            {display: '已结佣金', name : 'dis_pay_amount', width : 70, sortable : true, align: 'center'},
			{display: '结算时间', name : 'rpt_amount', width : 100, sortable : false, align: 'left'}
            ],
        title: '账单-分销佣金列表'
    });
});
</script>
