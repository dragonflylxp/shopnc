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
      <li>查看分销员分销订单列表及结算情况</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?con=distri_member&fun=get_member_xml&member_id=<?php echo intval($output['member_id']);?>',
        colModel : [
            {display: '订单编号', name : 'order_sn', width : 180, sortable : true, align: 'center'},
            {display: '下单时间', name : 'add_time', width : 180, sortable : true, align: 'center'},
            {display: '订单金额', name : 'order_amount', width : 150, sortable : true, align: 'center'},            
            {display: '订单状态', name : 'order_state', width : 150, sortable : true, align: 'center'},
            {display: '佣金', name : 'dis_pay_amount', width : 80, sortable : true, align: 'center'}, 
            {display: '结算时间', name : 'dis_pay_time', width : 100, sortable : true, align: 'center'},
            {display: '结算状态', name : 'log_state', width : 100, sortable : true, align: 'center'},

            ],
        sortname: "add_time",
        sortorder: "desc",
        title: '分销商订单列表'
    });  
});



</script> 

