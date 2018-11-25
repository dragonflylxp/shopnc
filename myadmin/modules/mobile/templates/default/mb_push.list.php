<?php defined('Inshopec') or exit('Access Invalid!');?>
<div class="page"> 
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>推送通知</h3>
        <h5>手机客户端接收网站通知等设置</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>借助百度"云推送"服务，向已经安装Android及iOS两个平台的客户端推送通知。</li>
      <li>使用前要有百度账号，并注册成为百度开发者，<a class="ncap-btn" target="_blank" href="http://push.baidu.com/">查看</a>。</li>
    </ul>
  </div>
  
  <div id="flexigrid"></div>
</div>
</div>
<script type="text/javascript">
function update_flex(){
    $("#flexigrid").flexigrid({
        url: 'index.php?con=mb_push&fun=get_list_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle-s'},
            {display: '会员级别', name : 'msg_tag', width : 50, sortable : false, align: 'center'},
            {display: '推送类型', name : 'log_type', width : 100, sortable : false, align: 'center'},
            {display: '类型值', name : 'log_type_v', width : 100, sortable : false, align: 'center'},
            {display: '推送内容', name : 'log_msg', width : 250, sortable : false, align: 'left'},
            {display: '添加时间', name : 'add_time',  width : 120, sortable : false, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增通知', name : 'add', bclass : 'add', title : '新增通知', onpress : fg_operation_add }
        ],
        usepager: true,
        rp: 15,
        title: '通知列表'
    });
}
function fg_operation_add(name, bDiv){
    var _url = 'index.php?con=mb_push&fun=add';
    window.location.href = _url;
}
$(function(){
    update_flex();
});
</script> 
